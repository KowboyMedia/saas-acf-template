(() => {
  const STYLE_ID = 'kowboy-rounded-override';
  const CSS = `
    .contact-form-section input,
    .contact-form-section select,
    .contact-form-section textarea,
    .contact-form-section button,
    .contact-form-section .contact-form-checkbox,
    .rounded-none,
    .rounded-none input,
    .rounded-none select,
    .rounded-none textarea,
    .rounded-none button,
    .rounded-none .btn,
    input.rounded-none,
    select.rounded-none,
    textarea.rounded-none,
    button.rounded-none,
    .btn.rounded-none {
      border-radius: 12px !important;
    }
  `;

  const tryInject = () => {
    const el = document.querySelector('kowboy-lead-form');
    if (!el || !el.shadowRoot) return false;
    if (el.shadowRoot.getElementById(STYLE_ID)) return true;
    const style = document.createElement('style');
    style.id = STYLE_ID;
    style.textContent = CSS;
    el.shadowRoot.appendChild(style);
    return true;
  };

  const boot = () => {
    if (tryInject()) return;
    let attempts = 0;
    const timer = setInterval(() => {
      attempts += 1;
      if (tryInject() || attempts > 40) {
        clearInterval(timer);
      }
    }, 250);
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
