(() => {
  const STYLE_ID = 'kowboy-rounded-override';
  const HOST_TAGS = ['kowboy-lead-form', 'kowboy-single-property'];
  const CSS = `
    .contact-form-section input:not([type="checkbox"]),
    .contact-form-section select,
    .contact-form-section textarea,
    .contact-form-section button,
    .contact-form-section .btn,
    .rounded-none input:not([type="checkbox"]),
    .rounded-none select,
    .rounded-none textarea,
    .rounded-none button,
    select.rounded-none,
    textarea.rounded-none,
    button.rounded-none,
    .btn.rounded-none,
    .sold_label,
    .viewings-action,
    .viewings-action button,
    .viewings-action .btn,
    .gallery-view-more-btn,
    .property-gallery-item.masonry-item {
      border-radius: 12px !important;
    }

    .property-gallery-item.masonry-item {
      overflow: hidden !important;
    }

    .property-gallery-item.masonry-item a,
    .property-gallery-item.masonry-item img {
      border-radius: inherit !important;
    }

    .property-gallery-item.masonry-item a {
      display: block;
    }
  `;

  const injectInto = (el) => {
    if (!el || !el.shadowRoot) return false;
    if (el.shadowRoot.getElementById(STYLE_ID)) return true;
    const style = document.createElement('style');
    style.id = STYLE_ID;
    style.textContent = CSS;
    el.shadowRoot.appendChild(style);
    return true;
  };

  const tryInjectAll = () => {
    let injected = false;
    HOST_TAGS.forEach((tag) => {
      document.querySelectorAll(tag).forEach((el) => {
        injected = injectInto(el) || injected;
      });
    });
    return injected;
  };

  const boot = () => {
    tryInjectAll();

    const observer = new MutationObserver(() => {
      tryInjectAll();
    });

    if (document.body) {
      observer.observe(document.body, { childList: true, subtree: true });
    }

    let attempts = 0;
    const timer = setInterval(() => {
      attempts += 1;
      tryInjectAll();
      if (attempts > 40) {
        clearInterval(timer);
        observer.disconnect();
      }
    }, 250);
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
