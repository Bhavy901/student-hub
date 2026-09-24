/* ==============================================
   EVENTS APP - Practical: Fetch API + JSON
   Fetches data/events.json and renders a searchable,
   filterable, sortable, paginated event list.
   ============================================== */

(function () {
  const grid       = document.getElementById('events-json-grid');
  if (!grid) return; // only run on events.html

  const searchInput   = document.getElementById('events-search');
  const categorySelect = document.getElementById('events-category');
  const sortSelect     = document.getElementById('events-sort');
  const paginationEl   = document.getElementById('events-pagination');
  const resultCountEl  = document.getElementById('events-result-count');

  const ITEMS_PER_PAGE = 6;

  let allEvents = [];
  let currentPage = 1;

  function loadEvents() {
    showLoading(grid, 'Loading events...');
    if (paginationEl) paginationEl.innerHTML = '';

    fetchJSON('data/events.json')
      .then(data => {
        allEvents = data;
        populateCategoryOptions(allEvents);
        currentPage = 1;
        renderPage();
      })
      .catch(err => {
        showError(grid, 'Could not load events right now.', loadEvents);
        console.error(err);
      });
  }

  function populateCategoryOptions(events) {
    if (!categorySelect) return;
    const categories = [...new Set(events.map(ev => ev.category))].sort();
    categorySelect.innerHTML = '<option value="">All Categories</option>' +
      categories.map(c => `<option value="${c}">${c}</option>`).join('');
  }

  function getFilteredSortedEvents() {
    const term = (searchInput?.value || '').trim().toLowerCase();
    const category = categorySelect?.value || '';
    const sortBy = sortSelect?.value || 'date-asc';

    let result = allEvents.filter(ev => {
      const matchesTerm = !term ||
        ev.title.toLowerCase().includes(term) ||
        ev.description.toLowerCase().includes(term) ||
        ev.venue.toLowerCase().includes(term);
      const matchesCategory = !category || ev.category === category;
      return matchesTerm && matchesCategory;
    });

    result = result.slice().sort((a, b) => {
      if (sortBy === 'date-asc')  return new Date(a.date) - new Date(b.date);
      if (sortBy === 'date-desc') return new Date(b.date) - new Date(a.date);
      if (sortBy === 'title-asc') return a.title.localeCompare(b.title);
      return 0;
    });

    return result;
  }

  function renderPage() {
    const filtered = getFilteredSortedEvents();
    const totalPages = Math.max(1, Math.ceil(filtered.length / ITEMS_PER_PAGE));
    currentPage = Math.min(currentPage, totalPages);

    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const pageItems = filtered.slice(start, start + ITEMS_PER_PAGE);

    if (resultCountEl) {
      resultCountEl.textContent = `${filtered.length} event${filtered.length === 1 ? '' : 's'} found`;
    }

    if (pageItems.length === 0) {
      grid.innerHTML = '<p class="state-message">No events match your search/filter.</p>';
    } else {
      grid.innerHTML = pageItems.map(eventToCardHTML).join('');
    }

    renderPagination(paginationEl, filtered.length, ITEMS_PER_PAGE, currentPage, (page) => {
      currentPage = page;
      renderPage();
      grid.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
  }

  function eventToCardHTML(ev) {
    const dateLabel = new Date(ev.date).toLocaleDateString('en-IN', {
      day: 'numeric', month: 'short', year: 'numeric'
    });
    const seatsLabel = ev.seatsLeft > 0 ? `${ev.seatsLeft} seats left` : 'Registration closed';
    const modalBody =
      `<p><strong>Date:</strong> ${dateLabel} | <strong>Time:</strong> ${ev.time}</p>` +
      `<p style="margin-top:8px"><strong>Venue:</strong> ${ev.venue}</p>` +
      `<p style="margin-top:8px"><strong>Organizer:</strong> ${ev.organizer}</p>` +
      `<p style="margin-top:8px">${ev.description}</p>`;

    return `
      <article class="event-item json-event-card">
        <p class="event-date">${dateLabel}</p>
        <div>
          <h3>${ev.title}</h3>
          <p class="event-meta">${ev.category} • ${ev.venue}</p>
          <p>${ev.description}</p>
          <p class="event-seats">${seatsLabel}</p>
          <button class="btn-modal"
                  style="margin-top:8px;font-size:13px;padding:6px 12px;"
                  data-modal-title="${escapeAttr(ev.title)}"
                  data-modal-body="${escapeAttr(modalBody)}">
            View Details
          </button>
        </div>
      </article>`;
  }

  function escapeAttr(str) {
    return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;');
  }

  if (searchInput)   searchInput.addEventListener('input', debounce(() => { currentPage = 1; renderPage(); }, 300));
  if (categorySelect) categorySelect.addEventListener('change', () => { currentPage = 1; renderPage(); });
  if (sortSelect)     sortSelect.addEventListener('change', () => { currentPage = 1; renderPage(); });

  loadEvents();
})();