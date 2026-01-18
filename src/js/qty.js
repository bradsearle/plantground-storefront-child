/**
 * Quantity Selector Component
 */
export const initQtyButtons = () => {
  const quantityGroups = document.querySelectorAll('.quantity');

  quantityGroups.forEach((group) => {
    // Avoid double buttons
    if (group.querySelector('.minus')) return;

    const input = group.querySelector('input.qty');
    if (!input) return;

    // Create buttons
    const minusBtn = document.createElement('button');
    const plusBtn = document.createElement('button');

    minusBtn.type = plusBtn.type = 'button';
    minusBtn.className = 'minus';
    plusBtn.className = 'plus';

    minusBtn.innerHTML = '−';
    plusBtn.innerHTML = '+';

    // Injection
    group.insertBefore(minusBtn, input);
    group.appendChild(plusBtn);

    // Click Logic
    minusBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const val = parseInt(input.value) || 1;
      if (val > 1) {
        input.value = val - 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });

    plusBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const val = parseInt(input.value) || 1;
      input.value = val + 1;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });
  });
};
