// filters.js

// Import the custom select initialization function
import { initCustomSelect } from './custom-select.js';

/**
 * Fades in newly loaded products.
 */
export function fadeInProducts() {
  const products = document.querySelectorAll('.product__container.card');

  products.forEach((product, index) => {
    product.style.opacity = 0;
    product.style.transition = 'opacity 0.5s ease-in-out';

    setTimeout(() => {
      product.style.opacity = 1;
    }, index * 150);
  });
}

/**
 * Synchronizes the visual state of the category toggles
 */
export function syncTogglesWithFilters(activeCategories) {
  const toggles = document.querySelectorAll('.category-toggle');

  toggles.forEach((toggle) => {
    const shouldBeChecked = activeCategories.includes(toggle.value);
    toggle.checked = shouldBeChecked;

    const wrapper = toggle.closest('.toggle-switch');
    if (wrapper) {
      if (shouldBeChecked) {
        wrapper.classList.add('is-active');
      } else {
        wrapper.classList.remove('is-active');
      }
    }
  });
}

/**
 * Fetches products via AJAX based on categories and sort order.
 */
export function fetchProducts(categories = []) {
  const orderby = document.querySelector('.woocommerce-ordering .orderby')?.value || 'menu_order';

  // FIX: Look for your custom grid container specifically
  const productContainer =
    document.querySelector('.product-grid__container') ||
    document.querySelector('.woocommerce ul.products');
  const resultCountContainer = document.querySelector('.woocommerce-result-count');

  const data = new FormData();
  data.append('action', 'plantground_filter_products');
  data.append('categories', JSON.stringify(categories));
  data.append('orderby', orderby);

  return fetch(plantground_ajax.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    body: data,
  })
    .then((response) => response.json())
    .then((data) => {
      if (productContainer && data.productsHtml) {
        productContainer.innerHTML = data.productsHtml;
        fadeInProducts();
      }

      if (resultCountContainer && data.resultCountHtml) {
        resultCountContainer.innerHTML = data.resultCountHtml;
      }

      setTimeout(() => {
        initCustomSelect();
        syncTogglesWithFilters(categories);
      }, 50);
    })
    .catch((error) => console.error('Product fetch error:', error));
}

/**
 * Initializes filter and sort event listeners.
 */
export function initFilters() {
  // Check initial state
  const toggles = document.querySelectorAll('.category-toggle');
  const sortSelect = document.querySelector('.woocommerce-ordering .orderby');

  if (toggles.length > 0) {
    const initialCheckedCategories = Array.from(toggles)
      .filter((t) => t.checked)
      .map((t) => t.value);
    syncTogglesWithFilters(initialCheckedCategories);
  }

  // Category toggles listeners
  toggles.forEach((toggle) => {
    toggle.addEventListener('change', () => {
      const checkedCategories = Array.from(toggles)
        .filter((t) => t.checked)
        .map((t) => t.value);

      // Visual update
      const wrapper = toggle.closest('.toggle-switch');
      if (wrapper) wrapper.classList.toggle('is-active', toggle.checked);

      fetchProducts(checkedCategories);
    });
  });

  // Sort dropdown listener
  if (sortSelect) {
    sortSelect.addEventListener('change', () => {
      const checkedCategories = Array.from(toggles)
        .filter((t) => t.checked)
        .map((t) => t.value);
      fetchProducts(checkedCategories);
    });
  }
}

// Sidebar/Mobile Toggle Logic
document.addEventListener('DOMContentLoaded', function () {
  const mobileToggle = document.getElementById('mobile-filter-toggle');
  const filtersContainer = document.getElementById('filters-container');
  const filterText = document.getElementById('filter-text');

  if (!mobileToggle || !filtersContainer) return;

  mobileToggle.addEventListener('click', function (e) {
    e.preventDefault();
    filtersContainer.classList.toggle('mobile-filters-visible');
    filterText.textContent = filtersContainer.classList.contains('mobile-filters-visible')
      ? 'Hide Filters'
      : 'Show Filters';
  });

  // Resize handler
  window.addEventListener('resize', function () {
    if (window.innerWidth > 800) {
      filtersContainer.classList.remove('mobile-filters-visible');
      if (filterText) filterText.textContent = 'Show Filters';
    }
  });
});
