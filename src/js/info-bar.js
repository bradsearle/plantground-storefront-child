document.addEventListener('DOMContentLoaded', () => {
  const infoBar = document.querySelector('.nav__info-bar');
  const msg1 = document.getElementById('message-1');
  const msg2 = document.getElementById('message-2');
  const shipDateSpan = document.querySelector('.ship-date');

  // Month names array for display
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

  // Calculate next Tuesday after today (or next week if today is Tuesday)
  const now = new Date();
  const dayOfWeek = now.getDay(); // Sunday=0, Monday=1, ..., Saturday=6
  const daysUntilTuesday = (9 - dayOfWeek) % 7 || 7;
  const nextTuesday = new Date(now);
  nextTuesday.setDate(now.getDate() + daysUntilTuesday);

  // Set ship date content with month and day wrapped in ship-date span
  shipDateSpan.textContent = `${monthNames[nextTuesday.getMonth()]} ${nextTuesday.getDate()}`;

  // Initially hide both messages (opacity 0)
  msg1.classList.add('hidden');
  msg1.classList.remove('visible');
  msg2.classList.add('hidden');
  msg2.classList.remove('visible');

  const fadeDuration = 700; // must match CSS opacity transition duration

  // Fade in first message with 2 seconds delay and fade transition
  setTimeout(() => {
    msg1.classList.remove('hidden');
    setTimeout(() => {
      msg1.classList.add('visible');
    }, 50);
  }, 2000);

  let showingFirst = true;

  function fadeMessages() {
    const current = showingFirst ? msg1 : msg2;
    const next = showingFirst ? msg2 : msg1;

    // Fade out current message
    current.classList.remove('visible');

    // After fadeDuration, hide current and fade in next
    setTimeout(() => {
      current.classList.add('hidden');
      next.classList.remove('hidden');

      // Slight delay before fade-in for smooth transition
      setTimeout(() => {
        next.classList.add('visible');
      }, 100);

      showingFirst = !showingFirst;
    }, fadeDuration);
  }

  // Start cycling messages after initial fade-in completes
  setTimeout(() => {
    setInterval(fadeMessages, 6000);
  }, 2020 + fadeDuration);
});

export default function infoBar() {
  console.log('info-bar initialized');
  // your info-bar JS here...
}
