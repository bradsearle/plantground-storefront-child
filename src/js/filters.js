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
