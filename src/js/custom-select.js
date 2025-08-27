import Choices from 'choices.js';

export function initCustomSelect() {
  const orderby = document.querySelector('.woocommerce-ordering .orderby');
  if (orderby) {
    new Choices(orderby, {
      searchEnabled: false, // no search box
      itemSelectText: '', // no “press to select” text
      shouldSort: false, // keep Woo option order
    });
  }
}
