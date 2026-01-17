// /assets/js/product-gallery-modal.js
import { gsap } from 'gsap';

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

  // Set initial hidden state
  gsap.set(modal, { autoAlpha: 0, display: 'none' });

  function showModal(index) {
    currentIndex = index;
    modalImg.src = galleryData[currentIndex].url;
    modalImg.alt = galleryData[currentIndex].alt;

    document.body.classList.add('modal-open');

    // Animate Modal In
    gsap.to(modal, {
      duration: 0.4,
      autoAlpha: 1,
      display: 'flex',
      ease: 'power2.out',
    });

    // Animate Image Pop
    gsap.fromTo(
      modalImg,
      { scale: 0.9, opacity: 0 },
      { scale: 1, opacity: 1, duration: 0.4, delay: 0.1, ease: 'back.out(1.7)' }
    );
  }

  function closeModal() {
    gsap.to(modal, {
      duration: 0.3,
      autoAlpha: 0,
      onComplete: () => {
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
      },
    });
  }

  function updateImage(newIndex) {
    gsap.to(modalImg, {
      duration: 0.2,
      opacity: 0,
      onComplete: () => {
        currentIndex = newIndex;
        modalImg.src = galleryData[currentIndex].url;
        modalImg.alt = galleryData[currentIndex].alt;
        gsap.to(modalImg, { opacity: 1, duration: 0.3 });
      },
    });
  }

  // Event Listeners
  gallery.addEventListener('click', (e) => {
    if (e.target.classList.contains('plantground-gallery-image')) {
      showModal(parseInt(e.target.dataset.index, 10));
    }
  });

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  if (prevBtn)
    prevBtn.addEventListener('click', () =>
      updateImage((currentIndex - 1 + galleryData.length) % galleryData.length)
    );
  if (nextBtn)
    nextBtn.addEventListener('click', () => updateImage((currentIndex + 1) % galleryData.length));

  document.addEventListener('keydown', (e) => {
    if (modal.style.display !== 'flex') return;
    if (e.key === 'Escape') closeModal();
    if (e.key === 'ArrowLeft') prevBtn.click();
    if (e.key === 'ArrowRight') nextBtn.click();
  });
}
