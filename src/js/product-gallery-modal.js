// /assets/js/product-gallery-modal.js

export function initProductGalleryModal() {
  const gallery = document.querySelector('.plantground-product-gallery');
  if (!gallery) return;

  const modal = document.getElementById('plantground-image-modal');
  const modalImg = document.getElementById('plantground-modal-image');
  const closeBtn = document.querySelector('.plantground-modal-close');
  const prevBtn = document.querySelector('.plantground-modal-prev');
  const nextBtn = document.querySelector('.plantground-modal-next');

  if (!modal || !modalImg) return;

  const galleryData = JSON.parse(gallery.dataset.gallery);
  let currentIndex = 0;

  function showModal() {
    modalImg.src = galleryData[currentIndex].url;
    modalImg.alt = galleryData[currentIndex].alt;
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');
  }

  function closeModal() {
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
  }

  // Open on image click
  gallery.addEventListener('click', (e) => {
    if (e.target.classList.contains('plantground-gallery-image')) {
      currentIndex = parseInt(e.target.dataset.index, 10);
      showModal();
    }
  });

  // Close handlers
  if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
  }

  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (modal.style.display !== 'flex') return;
    if (e.key === 'Escape') closeModal();
    if (e.key === 'ArrowLeft') showPrev();
    if (e.key === 'ArrowRight') showNext();
  });

  function showPrev() {
    currentIndex = (currentIndex - 1 + galleryData.length) % galleryData.length;
    showModal();
  }

  function showNext() {
    currentIndex = (currentIndex + 1) % galleryData.length;
    showModal();
  }

  if (prevBtn) prevBtn.addEventListener('click', showPrev);
  if (nextBtn) nextBtn.addEventListener('click', showNext);
}

// Auto-init if not using a bundler (optional fallback)
if (typeof window !== 'undefined' && document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initProductGalleryModal);
} else if (document.readyState !== 'loading') {
  initProductGalleryModal();
}
