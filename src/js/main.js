// main.js

import '../sass/main.scss'; // includes Tailwind via @import
// DO NOT import tailwind.scss separately here if it's already in main.scss

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

document.addEventListener('DOMContentLoaded', function () {
  const nav = document.querySelector('.nav__flex');
  const infoBar = document.querySelector('.nav__info-bar');

  let lastScrollTop = window.scrollY;
  const scrollDownHideThreshold = 230;
  const scrollUpShowThreshold = 230;

  window.addEventListener('scroll', function () {
    const currentScroll = window.scrollY;
    const scrollDelta = currentScroll - lastScrollTop;

    // === SCROLL DOWN ===
    if (scrollDelta > 0 && currentScroll > scrollDownHideThreshold) {
      nav.classList.remove('nav--visible-top', 'nav--visible-scrollup');
      nav.classList.add('nav--hidden');

      infoBar.classList.add('nav--hidden');
      infoBar.classList.remove('nav--visible');
    }

    // === SCROLL UP ANYWHERE (not at top) ===
    else if (scrollDelta < 0 && currentScroll > 0) {
      nav.classList.remove('nav--hidden', 'nav--visible-top');
      nav.classList.add('nav--visible-scrollup');

      infoBar.classList.add('nav--hidden');
      infoBar.classList.remove('nav--visible');
    }

    // === SCROLL TO TOP ==
    else if (currentScroll === 0) {
      nav.classList.remove('nav--hidden', 'nav--visible-scrollup');
      nav.classList.add('nav--visible-top');

      infoBar.classList.remove('nav--hidden');
      infoBar.classList.add('nav--visible'); // <-- Added this lin
    }

    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
  });
});
