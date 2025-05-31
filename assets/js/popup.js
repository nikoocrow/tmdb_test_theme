document.addEventListener('DOMContentLoaded', function () {
 console.log('JS popup funcionando desde Gulp');
  const buttons = document.querySelectorAll('.primary-btn, .secondary-btn');
  const popupOverlay = document.getElementById('custom-popup-overlay');

  if (!popupOverlay) return;

  const closeBtn = popupOverlay.querySelector('.close-popup');

  buttons.forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      popupOverlay.style.display = 'flex';
    });
  });

  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      popupOverlay.style.display = 'none';
    });
  }

  popupOverlay.addEventListener('click', (e) => {
    if (e.target === popupOverlay) {
      popupOverlay.style.display = 'none';
    }
  });
});

