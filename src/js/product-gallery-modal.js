import { gsap } from 'gsap';

export function initProductGalleryModal() {
  const gallery = document.querySelector('.plantground-product-gallery');
  const modal = document.getElementById('plantground-image-modal');

  if (!gallery || !modal) return;

  const modalImg = modal.querySelector('#plantground-modal-image');
  const closeBtn = modal.querySelector('.plantground-modal-close');
  const prevBtn = modal.querySelector('.plantground-modal-prev');
  const nextBtn = modal.querySelector('.plantground-modal-next');

  const images = JSON.parse(gallery.dataset.gallery || '[]');
  let currentIndex = 0;

  const openModal = (index) => {
    currentIndex = index;
    modalImg.src = images[currentIndex].url;
    document.body.classList.add('modal-open');
    modal.style.display = 'flex';

    // GSAP: Fade in modal and slide image up slightly
    gsap.fromTo(modal, { opacity: 0 }, { opacity: 1, duration: 0.3 });
    gsap.fromTo(modalImg, { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.4, delay: 0.1 });
  };

  const closeModal = () => {
    gsap.to(modal, {
      opacity: 0,
      duration: 0.3,
      onComplete: () => {
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
      },
    });
  };

  const navigate = (direction) => {
    if (direction === 'next') {
      currentIndex = (currentIndex + 1) % images.length;
    } else {
      currentIndex = (currentIndex - 1 + images.length) % images.length;
    }

    gsap.to(modalImg, {
      opacity: 0,
      x: direction === 'next' ? -10 : 10,
      duration: 0.2,
      onComplete: () => {
        modalImg.src = images[currentIndex].url;
        gsap.fromTo(
          modalImg,
          { x: direction === 'next' ? 10 : -10, opacity: 0 },
          { x: 0, opacity: 1, duration: 0.2 }
        );
      },
    });
  };

  gallery.addEventListener('click', (e) => {
    const img = e.target.closest('.plantground-gallery-image');
    if (img) openModal(parseInt(img.dataset.index));
  });

  closeBtn?.addEventListener('click', closeModal);
  prevBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    navigate('prev');
  });
  nextBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    navigate('next');
  });

  modal.addEventListener('click', (e) => {
    if (e.target === modal || e.target.classList.contains('plantground-modal-content')) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (modal.style.display === 'flex') {
      if (e.key === 'Escape') closeModal();
      if (e.key === 'ArrowLeft') navigate('prev');
      if (e.key === 'ArrowRight') navigate('next');
    }
  });
}
