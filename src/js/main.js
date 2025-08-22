import '../sass/main.scss'; // includes Tailwind via @import
// DO NOT import tailwind.scss separately here if it's already in main.scss
// import menuBar from './info-bar.js';

function fadeInProducts() {
  const products = document.querySelectorAll('.product__container.card');

  console.log('Fading in', products.length, 'products'); // Debug line

  products.forEach((product, index) => {
    product.style.opacity = 0;
    product.style.transition = 'opacity 0.5s ease-in-out';

    setTimeout(() => {
      product.style.opacity = 1;
    }, index * 150);
  });
}

function fetchProducts(categories) {
  const data = new FormData();
  data.append('action', 'plantground_filter_products');
  data.append('categories', JSON.stringify(categories));

  fetch(plantground_ajax.ajax_url, {
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
    .catch((error) => {
      console.error('Product fetch error:', error);
    });
}

document.addEventListener('DOMContentLoaded', () => {
  fadeInProducts();

  const toggles = document.querySelectorAll('.category-toggle');

  toggles.forEach((toggle) => {
    toggle.addEventListener('change', () => {
      const checkedCategories = Array.from(toggles)
        .filter((toggle) => toggle.checked)
        .map((toggle) => toggle.value);

      fetchProducts(checkedCategories);
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const stickyBar = document.querySelector('.storefront-sticky-add-to-cart');
  if (stickyBar) {
    stickyBar.remove();
  }
});

import infoBar from './info-bar.js';

infoBar();
