/* ==============================================
   FAQ APP - Practical: Fetch API + JSON
   Fetches data/faqs.json and renders a searchable,
   filterable, paginated FAQ accordion.
   (No sort needed for FAQs - kept to search/filter/pagination.)
   ============================================== */

(function () {
  const list = document.getElementById('faq-json-list');
  if (!list) return; // only run on faq.html

  const searchInput   = document.getElementById('faq-search');
  const categorySelect = document.getElementById('faq-category');
  const paginationEl   = document.getElementById('faq-pagination');
  const resultCountEl  = document.getElementById('faq-result-count');

  const ITEMS_PER_PAGE = 5;

  let allFaqs = [];
  let currentPage = 1;

  function loadFaqs() {
    showLoading(list, 'Loading FAQs...');
    if (paginationEl) paginationEl.innerHTML = '';

    fetchJSON('data/faqs.json')
      .then(data => {
        allFaqs = data;
        populateCategoryOptions(allFaqs);
        currentPage = 1;
        renderPage();
      })
      .catch(err => {
        showError(list, 'Could not load FAQs right now.', loadFaqs);
        console.error(err);
      });
  }

  function populateCategoryOptions(faqs) {
    if (!categorySelect) return;
    const categories = [...new Set(faqs.map(f => f.category))].sort();
    categorySelect.innerHTML = '<option value="">All Topics</option>' +
      categories.map(c => `<option value="${c}">${c}</option>`).join('');
  }

  function getFiltered() {
    const term = (searchInput?.value || '').trim().toLowerCase();
    const category = categorySelect?.value || '';

    return allFaqs.filter(f => {
      const matchesTerm = !term ||
        f.question.toLowerCase().includes(term) ||
        f.answer.toLowerCase().includes(term);
      const matchesCategory = !category || f.category === category;
      return matchesTerm && matchesCategory;
    });
  }

  function renderPage() {
    const filtered = getFiltered();
    const totalPages = Math.max(1, Math.ceil(filtered.length / ITEMS_PER_PAGE));
    currentPage = Math.min(currentPage, totalPages);

    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const pageItems = filtered.slice(start, start + ITEMS_PER_PAGE);

    if (resultCountEl) {
      resultCountEl.textContent = `${filtered.length} question${filtered.length === 1 ? '' : 's'} found`;
    }

    if (pageItems.length === 0) {
      list.innerHTML = '<p class="state-message">No FAQs match your search/filter.</p>';
    } else {
      list.innerHTML = pageItems.map(faqToItemHTML).join('');
      attachAccordionHandlers();
    }

    renderPagination(paginationEl, filtered.length, ITEMS_PER_PAGE, currentPage, (page) => {
      currentPage = page;
      renderPage();
      list.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
  }

  function faqToItemHTML(f) {
    return `
      <div class="faq-item" data-faq-id="${f.id}">
        <button class="faq-question" type="button" aria-expanded="false">
          <span>${f.question} <em class="faq-tag">(${f.category})</em></span>
          <span class="faq-icon" aria-hidden="true">+</span>
        </button>
        <div class="faq-answer" aria-hidden="true">
          <p>${f.answer}</p>
        </div>
      </div>`;
  }

  function attachAccordionHandlers() {
    list.querySelectorAll('.faq-item').forEach(item => {
      const question = item.querySelector('.faq-question');
      const answer   = item.querySelector('.faq-answer');
      question.addEventListener('click', () => {
        const isOpen = item.classList.toggle('faq-open');
        question.setAttribute('aria-expanded', isOpen);
        answer.setAttribute('aria-hidden', !isOpen);

        list.querySelectorAll('.faq-item').forEach(other => {
          if (other !== item) {
            other.classList.remove('faq-open');
            other.querySelector('.faq-question')?.setAttribute('aria-expanded', 'false');
            other.querySelector('.faq-answer')?.setAttribute('aria-hidden', 'true');
          }
        });
      });
    });
  }

  if (searchInput)   searchInput.addEventListener('input', debounce(() => { currentPage = 1; renderPage(); }, 300));
  if (categorySelect) categorySelect.addEventListener('change', () => { currentPage = 1; renderPage(); });

  loadFaqs();
})();