// Add this to your main.js
document.addEventListener('DOMContentLoaded', function () {
  const menuBtn = document.querySelector('.menu__btn');
  const menuPanel = document.getElementById('mobile-menu-panel');
  const overlay = document.createElement('div');
  overlay.classList.add('mobile-menu-overlay');
  document.body.appendChild(overlay);

  menuBtn?.addEventListener('click', function (e) {
    e.preventDefault();
    menuPanel.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = menuPanel.classList.contains('active') ? 'hidden' : '';
  });

  overlay.addEventListener('click', function () {
    menuPanel.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const menuBtn = document.querySelector('.menu__btn');
  const menuPanel = document.getElementById('mobile-menu-panel');

  menuBtn?.addEventListener('click', function (e) {
    e.preventDefault();
    menuPanel.classList.toggle('active');

    // Toggle hamburger to X (add class to animate)
    menuBtn.classList.toggle('opened');
  });
});
