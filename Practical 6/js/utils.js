/* ==============================================
   SHARED UTILITIES - used by events-app.js,
   students-app.js and faq-app.js
   Kept in one module so each page script only
   needs fetch/search/sort/pagination logic that
   is specific to it (modularity).
   ============================================== */

/**
 * Fetch and parse a JSON file.
 * Throws on network failure or non-OK response so
 * callers can show a proper error state.
 */
async function fetchJSON(url) {
  const response = await fetch(url);
  if (!response.ok) {
    throw new Error(`Failed to load ${url} (status ${response.status})`);
  }
  return response.json();
}

/**
 * Debounce: delays calling fn until the user has
 * stopped typing in a search box for `delay` ms.
 */
function debounce(fn, delay = 300) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}

/**
 * Render Prev / page-number / Next controls into `container`.
 * Calls onPageChange(newPage) when the user clicks a control.
 */
function renderPagination(container, totalItems, itemsPerPage, currentPage, onPageChange) {
  if (!container) return;
  const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
  container.innerHTML = '';

  if (totalPages <= 1) return;

  const makeBtn = (label, page, disabled = false, active = false) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'page-btn' + (active ? ' page-btn-active' : '');
    btn.textContent = label;
    btn.disabled = disabled;
    btn.addEventListener('click', () => onPageChange(page));
    return btn;
  };

  container.appendChild(makeBtn('← Prev', currentPage - 1, currentPage === 1));

  for (let p = 1; p <= totalPages; p++) {
    container.appendChild(makeBtn(String(p), p, false, p === currentPage));
  }

  container.appendChild(makeBtn('Next →', currentPage + 1, currentPage === totalPages));
}

/** Shows a loading spinner message inside `container`. */
function showLoading(container, message = 'Loading data...') {
  container.innerHTML = `<div class="state-message state-loading">⏳ ${message}</div>`;
}

/** Shows an error message with a Retry button inside `container`. */
function showError(container, message, onRetry) {
  container.innerHTML = `
    <div class="state-message state-error">
      ⚠ ${message}
      <button type="button" class="retry-btn">Retry</button>
    </div>`;
  const retryBtn = container.querySelector('.retry-btn');
  if (retryBtn) retryBtn.addEventListener('click', onRetry);
}