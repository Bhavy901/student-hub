/*=============================== */

/* ---- 1. Dynamic footer year ---- */
document.querySelectorAll('[data-year]').forEach(el => {
  el.textContent = new Date().getFullYear();
});

/* ---- 2. Hamburger / Nav-toggle menu ---- */
const navToggle = document.querySelector('.nav-toggle');
const primaryNav = document.getElementById('primary-nav');

if (navToggle && primaryNav) {
  navToggle.addEventListener('click', () => {
    const isOpen = primaryNav.classList.toggle('nav-open');
    navToggle.setAttribute('aria-expanded', isOpen);
    navToggle.textContent = isOpen ? '✕ Close' : '☰ Menu';
  });
}

/* ---- 3. Light / Dark theme switcher ---- */
const themeToggleBtn = document.getElementById('theme-toggle');

function applyTheme(theme) {
  document.body.setAttribute('data-theme', theme);
  localStorage.setItem('sh-theme', theme);
  if (themeToggleBtn) {
    themeToggleBtn.textContent = theme === 'dark' ? '☀ Light Mode' : '🌙 Dark Mode';
    themeToggleBtn.setAttribute('aria-label', theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
  }
}

// Load saved theme or respect OS preference
const savedTheme = localStorage.getItem('sh-theme') ||
  (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
applyTheme(savedTheme);

if (themeToggleBtn) {
  themeToggleBtn.addEventListener('click', () => {
    const current = document.body.getAttribute('data-theme');
    applyTheme(current === 'dark' ? 'light' : 'dark');
  });
}

/* ---- 4. Notification banner ---- */
const banner = document.getElementById('notification-banner');
const bannerClose = document.getElementById('banner-close');

if (banner && bannerClose) {
  // Show banner after a short delay if not dismissed this session
  if (!sessionStorage.getItem('sh-banner-dismissed')) {
    setTimeout(() => banner.classList.add('banner-visible'), 600);
  }

  bannerClose.addEventListener('click', () => {
    banner.classList.remove('banner-visible');
    sessionStorage.setItem('sh-banner-dismissed', '1');
  });
}

/* ---- 5. Modal popup ---- */
/* FIXED for dynamic content: uses event delegation on document instead of
   querySelectorAll at load time, so buttons added later by fetch-rendered
   cards (events, FAQs) also open the modal correctly. */
const modalOverlay   = document.getElementById('modal-overlay');
const modalClose     = document.getElementById('modal-close');
const modalTitle     = document.getElementById('modal-title');
const modalBody      = document.getElementById('modal-body');

function openModal(title, body) {
  if (!modalOverlay) return;
  if (modalTitle) modalTitle.textContent = title;
  if (modalBody)  modalBody.innerHTML    = body;
  modalOverlay.classList.add('modal-active');
  modalOverlay.setAttribute('aria-hidden', 'false');
  document.body.classList.add('modal-open');
  if (modalClose) modalClose.focus();
}

function closeModal() {
  if (!modalOverlay) return;
  modalOverlay.classList.remove('modal-active');
  modalOverlay.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('modal-open');
}

document.addEventListener('click', e => {
  const trigger = e.target.closest('[data-modal-title]');
  if (trigger) {
    openModal(trigger.dataset.modalTitle || 'Information', trigger.dataset.modalBody || '');
  }
});

if (modalClose)   modalClose.addEventListener('click', closeModal);
if (modalOverlay) {
  modalOverlay.addEventListener('click', e => {
    if (e.target === modalOverlay) closeModal();
  });
}
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeModal();
});

/* ---- 6. Image / Content slider ---- */
const slider      = document.getElementById('content-slider');
const slides      = slider ? slider.querySelectorAll('.slide') : [];
const prevBtn     = document.getElementById('slider-prev');
const nextBtn     = document.getElementById('slider-next');
const dotsWrapper = document.getElementById('slider-dots');

let currentSlide = 0;
let autoSlide;

function showSlide(index) {
  slides.forEach((s, i) => {
    s.classList.toggle('slide-active', i === index);
  });
  if (dotsWrapper) {
    dotsWrapper.querySelectorAll('.slider-dot').forEach((d, i) => {
      d.classList.toggle('dot-active', i === index);
    });
  }
  currentSlide = index;
}

function nextSlide() {
  showSlide((currentSlide + 1) % slides.length);
}

function prevSlide() {
  showSlide((currentSlide - 1 + slides.length) % slides.length);
}

if (slider && slides.length) {
  // Build dots
  if (dotsWrapper) {
    slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = 'slider-dot';
      dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
      dot.addEventListener('click', () => { clearInterval(autoSlide); showSlide(i); });
      dotsWrapper.appendChild(dot);
    });
  }

  showSlide(0);
  autoSlide = setInterval(nextSlide, 4000);

  if (prevBtn) prevBtn.addEventListener('click', () => { clearInterval(autoSlide); prevSlide(); });
  if (nextBtn) nextBtn.addEventListener('click', () => { clearInterval(autoSlide); nextSlide(); });

  // Touch / swipe support
  let touchStartX = 0;
  slider.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].clientX; }, { passive: true });
  slider.addEventListener('touchend', e => {
    const diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 40) { clearInterval(autoSlide); diff > 0 ? nextSlide() : prevSlide(); }
  }, { passive: true });
}

/* ---- 7. Collapsible FAQ accordion ---- */
const faqItems = document.querySelectorAll('.faq-item');

faqItems.forEach(item => {
  const question = item.querySelector('.faq-question');
  const answer   = item.querySelector('.faq-answer');

  if (!question || !answer) return;

  // Wrap existing content in answer div if plain article
  question.setAttribute('aria-expanded', 'false');
  answer.setAttribute('aria-hidden', 'true');

  question.addEventListener('click', () => {
    const isOpen = item.classList.toggle('faq-open');
    question.setAttribute('aria-expanded', isOpen);
    answer.setAttribute('aria-hidden', !isOpen);

    // Close siblings (accordion behaviour)
    faqItems.forEach(other => {
      if (other !== item) {
        other.classList.remove('faq-open');
        const otherQ = other.querySelector('.faq-question');
        const otherA = other.querySelector('.faq-answer');
        if (otherQ) otherQ.setAttribute('aria-expanded', 'false');
        if (otherA) otherA.setAttribute('aria-hidden', 'true');
      }
    });
  });
});