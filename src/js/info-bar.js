export default function infoBar() {
  const infoBarEl = document.querySelector('.nav__info-bar');
  if (!infoBarEl) return;

  const msg1 = document.getElementById('message-1');
  const msg2 = document.getElementById('message-2');
  const shipDateSpan = document.querySelector('.ship-date');

  const monthNames = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
  ];

  // Calculate Date
  const now = new Date();
  const dayOfWeek = now.getDay();
  let daysUntilTuesday = (2 - dayOfWeek + 7) % 7;

  // If Sunday deadline passed (it's now Monday or Tuesday)
  if (dayOfWeek === 1 || dayOfWeek === 2) {
    daysUntilTuesday += 7;
  }

  const nextTuesday = new Date(now);
  nextTuesday.setDate(now.getDate() + daysUntilTuesday);

  if (shipDateSpan) {
    shipDateSpan.textContent = `${monthNames[nextTuesday.getMonth()]} ${nextTuesday.getDate()}`;
  }

  // Animation Logic
  const fadeDuration = 700;
  msg1.classList.add('hidden');
  msg2.classList.add('hidden');

  setTimeout(() => {
    msg1.classList.remove('hidden');
    setTimeout(() => msg1.classList.add('visible'), 50);
  }, 2000);

  let showingFirst = true;
  function fadeMessages() {
    const current = showingFirst ? msg1 : msg2;
    const next = showingFirst ? msg2 : msg1;

    current.classList.remove('visible');
    setTimeout(() => {
      current.classList.add('hidden');
      next.classList.remove('hidden');
      setTimeout(() => next.classList.add('visible'), 100);
      showingFirst = !showingFirst;
    }, fadeDuration);
  }

  setInterval(fadeMessages, 6000);
}
