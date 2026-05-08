document.addEventListener('submit', (e) => {
  const form = e.target;
  if (!(form instanceof HTMLFormElement)) return;

  const message = form.getAttribute('data-confirm');
  if (!message) return;

  const ok = window.confirm(message);
  if (!ok) {
    e.preventDefault();
  }
});
