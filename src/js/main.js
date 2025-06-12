import "../sass/main.scss";

function fadeInProducts() {
  const products = document.querySelectorAll(".products li.product__container");
  products.forEach((product, index) => {
    product.style.opacity = 0;
    product.style.transition = "opacity 0.5s ease-in-out";
    setTimeout(() => {
      product.style.opacity = 1;
    }, index * 150);
  });
}

// On initial page load
document.addEventListener("DOMContentLoaded", fadeInProducts);

// After Woof filter redraws products
document.addEventListener("woof-ajax-form-redrawing", fadeInProducts);
