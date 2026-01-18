// assets/js/qty.js
export function initQtyButtons() {
  document.addEventListener('click', function (e) {
    const target = e.target;
    if (target.classList.contains('plus') || target.classList.contains('minus')) {
      const wrapper = target.closest('.quantity');
      const input = wrapper.querySelector('input.qty');
      if (!input) return;

      let val = parseFloat(input.value) || 0;
      const step = parseFloat(input.step) || 1;
      const min = parseFloat(input.min) || 1;
      const max = parseFloat(input.max);

      if (target.classList.contains('plus')) {
        input.value = max && val >= max ? max : val + step;
      } else {
        input.value = val <= min ? min : val - step;
      }

      // Important: Tell WooCommerce the value changed
      input.dispatchEvent(new Event('change', { bubbles: true }));
    }
  });
}
