// src/js/main.js

import '../sass/main.scss'; // includes Tailwind via @import
import infoBar from './info-bar.js';
import { simplePreloader } from './preloader'; // import your preloader function

/* -----------------------------
   Product fade-in + AJAX filter
------------------------------*/
function fadeInProducts() {
  const products = document.querySelectorAll('.product__container.card');
  console.log('Fading in', products.length, 'products');
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

function initCategoryFilters() {
  const toggles = document.querySelectorAll('.category-toggle');
  if (!toggles.length) return;

  toggles.forEach((toggle) => {
    toggle.addEventListener('change', () => {
      const checkedCategories = Array.from(toggles)
        .filter((t) => t.checked)
        .map((t) => t.value);
      fetchProducts(checkedCategories);
    });
  });
}

/* -----------------------------
   Remove Storefront sticky bar
------------------------------*/
function removeStickyBar() {
  const stickyBar = document.querySelector('.storefront-sticky-add-to-cart');
  if (stickyBar) stickyBar.remove();
}

/* -----------------------------
   Boot
------------------------------*/
function init() {
  fadeInProducts();
  initCategoryFilters();
  removeStickyBar();
  infoBar();
  simplePreloader(); // run your imported preloader
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
