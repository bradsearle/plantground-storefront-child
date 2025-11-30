// custom-select.js

import Choices from 'choices.js';

export function initCustomSelect() {
  const orderby = document.querySelector('.woocommerce-ordering .orderby');
  if (orderby && orderby.choices instanceof Choices === false) {
    // A reliable check
    new Choices(orderby, {
      searchEnabled: false,
      itemSelectText: '',
      shouldSort: false,
    });
  }
}
