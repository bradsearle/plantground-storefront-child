import "../sass/main.scss";

function fadeInProducts() {
  const products = document.querySelectorAll(".products li.product");
  products.forEach((product, index) => {
    product.style.opacity = 0;
    product.style.transition = "opacity 0.5s ease-in-out";
    setTimeout(() => {
      product.style.opacity = 1;
    }, index * 150);
  });
}

function fetchProducts(categories) {
  const data = new FormData();
  data.append("action", "plantground_filter_products");
  data.append("categories", JSON.stringify(categories));

  fetch(plantground_ajax.ajax_url, {
    method: "POST",
    credentials: "same-origin",
    body: data,
  })
    .then((response) => response.text())
    .then((html) => {
      const productContainer = document.querySelector(
        ".woocommerce ul.products"
      );

      if (productContainer) {
        productContainer.innerHTML = html;
        fadeInProducts();
      }
    })
    .catch(console.error);
}

document.addEventListener("DOMContentLoaded", () => {
  fadeInProducts();

  const toggles = document.querySelectorAll(".category-toggle");
  toggles.forEach((toggle) => {
    toggle.addEventListener("change", () => {
      const checkedCategories = Array.from(toggles)
        .filter((toggle) => toggle.checked)
        .map((toggle) => toggle.value);

      fetchProducts(checkedCategories);
    });
  });
});
