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
 * with the currently active categories array using classes and 'checked'.
 * @param {string[]} activeCategories - An array of category slugs.
 */
export function syncTogglesWithFilters(activeCategories) {
  // Target the input (checkbox)
  const toggles = document.querySelectorAll('.category-toggle');

  toggles.forEach((toggle) => {
    const shouldBeChecked = activeCategories.includes(toggle.value);

    // Set the functional checked state
    toggle.checked = shouldBeChecked;

    // Find the nearest label wrapper
    const wrapper = toggle.closest('.toggle-switch');

    // FIX: Set the visual state using a class. This class is styled by CSS.
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
 * Expects a JSON response from the server with 'productsHtml' and 'resultCountHtml'.
 */
export function fetchProducts(categories = []) {
  const orderby = document.querySelector('.woocommerce-ordering .orderby')?.value || 'menu_order';
  const productContainer = document.querySelector('.woocommerce ul.products');
  // Target the results count container element
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
    .then((response) => response.json()) // Changed: Expect JSON response
    .then((data) => {
      if (productContainer && data.productsHtml) {
        productContainer.innerHTML = data.productsHtml;
        fadeInProducts();
      }
      // Added: Update the results count element
      if (resultCountContainer && data.resultCountHtml) {
        resultCountContainer.innerHTML = data.resultCountHtml;
      }

      // FINAL FIX: Using a 50ms delay. This is long enough to outrun virtually any
      // theme/library DOM manipulation cycle that might be causing the reset.
      setTimeout(() => {
        // 1. Re-initialize the custom select menu (Choices.js)
        initCustomSelect();
        // 2. FORCE-SYNC the visual and functional state of the toggles last
        syncTogglesWithFilters(categories);
      }, 50);
    })
    .catch((error) => console.error('Product fetch error:', error));
}

/**
 * Initializes filter and sort event listeners.
 */
export function initFilters() {
  document.addEventListener('DOMContentLoaded', () => {
    fadeInProducts();
    // *** ADDED: Initialize custom select on first page load ***
    initCustomSelect();

    // Find initial state of categories
    const initialCheckedCategories = Array.from(document.querySelectorAll('.category-toggle'))
      .filter((t) => t.checked)
      .map((t) => t.value);

    // Sync toggles on load, in case the server sent pre-checked ones
    syncTogglesWithFilters(initialCheckedCategories);

    const toggles = document.querySelectorAll('.category-toggle');
    const sortSelect = document.querySelector('.woocommerce-ordering .orderby');

    // Category toggles
    toggles.forEach((toggle) => {
      toggle.addEventListener('change', () => {
        const checkedCategories = Array.from(toggles)
          .filter((t) => t.checked)
          .map((t) => t.value);

        // When user changes toggle, update the class visually immediately
        toggle.closest('.toggle-switch').classList.toggle('is-active', toggle.checked);

        fetchProducts(checkedCategories);
      });
    });

    // Sort dropdown
    if (sortSelect) {
      sortSelect.addEventListener('change', () => {
        const checkedCategories = Array.from(toggles)
          .filter((t) => t.checked)
          .map((t) => t.value);

        fetchProducts(checkedCategories);
      });
    }
  });
}

// filters-sort btn for desktop/mobile (This section remains unchanged)
document.addEventListener('DOMContentLoaded', function () {
  const mobileToggle = document.getElementById('mobile-filter-toggle');
  const filtersContainer = document.getElementById('filters-container');
  const filterIcon = document.getElementById('filter-icon');
  const filterText = document.getElementById('filter-text');

  // Check if all required elements exist
  if (!mobileToggle || !filtersContainer || !filterIcon || !filterText) {
    console.error('Required elements not found');
    return;
  }

  // Store original SVG path for filter icon (same for both states)
  const filterPath =
    'M440-120v-240h80v80h320v80H520v80h-80Zm-320-80v-80h240v80H120Zm160-160v-80H120v-80h160v-80h80v240h-80Zm160-80v-80h400v80H440Zm160-160v-240h80v80h160v80H680v80h-80Zm-480-80v-80h400v80H120Z';

  mobileToggle.addEventListener('click', function (e) {
    e.preventDefault();

    // Toggle visibility of filters
    filtersContainer.classList.toggle('mobile-filters-visible');

    // Update text based on visibility state (icon stays the same)
    if (filtersContainer.classList.contains('mobile-filters-visible')) {
      // Keep the same filter icon, update text to "Hide Filters"
      filterText.textContent = 'Hide Filters';
      filterIcon.setAttribute('aria-label', 'Hide Filters');
    } else {
      // Keep the same filter icon, update text to "Show Filters"
      filterText.textContent = 'Show Filters';
      filterIcon.setAttribute('aria-label', 'Show Filters');
    }
  });

  // Optional: Close filters when clicking outside
  document.addEventListener('click', function (e) {
    const isClickInsideFilters = filtersContainer.contains(e.target);
    const isClickOnToggle = mobileToggle.contains(e.target);

    if (
      !isClickInsideFilters &&
      !isClickOnToggle &&
      filtersContainer.classList.contains('mobile-filters-visible') &&
      window.innerWidth <= 800
    ) {
      filtersContainer.classList.remove('mobile-filters-visible');
      filterText.textContent = 'Show Filters';
      filterIcon.setAttribute('aria-label', 'Show Filters');
    }
  });

  // Handle window resize to reset state properly
  window.addEventListener('resize', function () {
    setTimeout(function () {
      if (window.innerWidth > 800) {
        // On desktop, ensure filters are visible and icon shows filter
        filtersContainer.classList.remove('mobile-filters-visible');
        filterText.textContent = 'Show Filters';
        filterIcon.setAttribute('aria-label', 'Show Filters');
      } else {
        // On mobile, if filters are hidden, ensure it shows the filter icon and "Show Filters" text
        if (!filtersContainer.classList.contains('mobile-filters-visible')) {
          filterText.textContent = 'Show Filters';
          filterIcon.setAttribute('aria-label', 'Show Filters');
        }
      }
    }, 150);
  });
});
