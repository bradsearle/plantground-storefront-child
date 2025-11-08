// filters.js
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

export function fetchProducts(categories = []) {
  const orderby = document.querySelector('.woocommerce-ordering .orderby')?.value || 'menu_order';

  const data = new FormData();
  data.append('action', 'plantground_filter_products');
  data.append('categories', JSON.stringify(categories));
  data.append('orderby', orderby);

  return fetch(plantground_ajax.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    body: data,
  })
    .then((response) => response.text())
    .then((html) => {
      const productContainer = document.querySelector('.woocommerce ul.products');
      if (productContainer) {
        productContainer.innerHTML = html;
        fadeInProducts();
      }
    })
    .catch((error) => console.error('Product fetch error:', error));
}

export function initFilters() {
  document.addEventListener('DOMContentLoaded', () => {
    fadeInProducts();

    const toggles = document.querySelectorAll('.category-toggle');
    const sortSelect = document.querySelector('.woocommerce-ordering .orderby');

    // Category toggles
    toggles.forEach((toggle) => {
      toggle.addEventListener('change', () => {
        const checkedCategories = Array.from(toggles)
          .filter((t) => t.checked)
          .map((t) => t.value);

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

// filters-sort btn for desktop/mobile
// filters-sort btn for desktop/mobile
document.addEventListener('DOMContentLoaded', function () {
  const mobileToggle = document.getElementById('mobile-filter-toggle');
  const filtersContainer = document.getElementById('filters-container');
  const filterIcon = document.getElementById('filter-icon');

  // Check if all required elements exist
  if (!mobileToggle || !filtersContainer || !filterIcon) {
    console.error('Required elements not found');
    return;
  }

  // Store original SVG path for filter icon
  const filterPath =
    'M440-120v-240h80v80h320v80H520v80h-80Zm-320-80v-80h240v80H120Zm160-160v-80H120v-80h160v-80h80v240h-80Zm160-80v-80h400v80H440Zm160-160v-240h80v80h160v80H680v80h-80Zm-480-80v-80h400v80H120Z';
  // SVG path for X icon
  const xPath = 'M6 18L18 6M6 6l12 12';

  mobileToggle.addEventListener('click', function (e) {
    e.preventDefault();

    // Toggle visibility of filters
    filtersContainer.classList.toggle('mobile-filters-visible');

    // Update icon based on visibility state
    if (filtersContainer.classList.contains('mobile-filters-visible')) {
      // Change to X icon
      filterIcon.innerHTML =
        '<path d="' +
        xPath +
        '" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      filterIcon.setAttribute('aria-label', 'Close Filters');
    } else {
      // Revert to filter icon
      filterIcon.innerHTML = '<path d="' + filterPath + '" fill="currentColor"/>';
      filterIcon.setAttribute('aria-label', 'Filter');
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
      filterIcon.innerHTML = '<path d="' + filterPath + '" fill="currentColor"/>';
      filterIcon.setAttribute('aria-label', 'Filter');
    }
  });

  // Handle window resize to reset state properly
  window.addEventListener('resize', function () {
    setTimeout(function () {
      if (window.innerWidth > 800) {
        // On desktop, ensure filters are visible and icon shows filter
        filtersContainer.classList.remove('mobile-filters-visible');
        filterIcon.innerHTML = '<path d="' + filterPath + '" fill="currentColor"/>';
        filterIcon.setAttribute('aria-label', 'Filter');
      } else {
        // On mobile, if filters are hidden, ensure it shows the filter icon
        if (!filtersContainer.classList.contains('mobile-filters-visible')) {
          filterIcon.innerHTML = '<path d="' + filterPath + '" fill="currentColor"/>';
          filterIcon.setAttribute('aria-label', 'Filter');
        }
      }
    }, 150);
  });
});
