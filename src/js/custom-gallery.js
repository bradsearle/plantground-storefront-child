// custom-gallery.js

export function initCustomProductGallery() {
  const gallery = document.querySelector('.custom-product-gallery');
  if (!gallery) return;

  const mainImage = gallery.querySelector('.gallery-main-image');
  const modal = document.getElementById('custom-gallery-modal');
  const modalImg = modal?.querySelector('.modal-image');
  const closeBtn = modal?.querySelector('.modal-close');
  const thumbBtns = gallery.querySelectorAll('.thumbnail-btn');

  // Update main image when thumbnail is clicked
  thumbBtns.forEach((btn) => {
    btn.addEventListener('click', function () {
      const fullSrc = this.dataset.fullSrc;
      if (fullSrc) {
        mainImage.src = fullSrc;
        mainImage.alt = this.querySelector('img').alt;
      }

      // Update active state
      thumbBtns.forEach((b) => b.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // Open modal on main image click
  gallery.querySelector('.gallery-main').addEventListener('click', function () {
    if (modal && modalImg) {
      modalImg.src = mainImage.src;
      modalImg.alt = mainImage.alt;
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  });

  // Close modal
  function closeModal() {
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
  }

  if (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === modal) closeModal();
    });
  }

  // Keyboard support
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal?.classList.contains('active')) {
      closeModal();
    }
  });
}

// Auto-init if not using a framework (optional fallback)
if (typeof window !== 'undefined' && document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    initCustomProductGallery();
  });
} else if (typeof window !== 'undefined') {
  initCustomProductGallery();
}
