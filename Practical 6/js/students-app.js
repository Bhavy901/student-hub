/* ==============================================
   STUDENTS APP - Practical: Fetch API + JSON
   Fetches data/students.json and renders a searchable,
   filterable, sortable, paginated student directory.
   ============================================== */

(function () {
  const tableBody = document.getElementById('students-json-body');
  if (!tableBody) return; // only run on admin.html

  const searchInput   = document.getElementById('students-search');
  const branchSelect  = document.getElementById('students-branch');
  const sortSelect    = document.getElementById('students-sort');
  const paginationEl  = document.getElementById('students-pagination');
  const resultCountEl = document.getElementById('students-result-count');
  const stateEl       = document.getElementById('students-state');

  const ITEMS_PER_PAGE = 5;

  let allStudents = [];
  let currentPage = 1;

  function loadStudents() {
    showLoading(stateEl, 'Loading students...');
    tableBody.innerHTML = '';
    if (paginationEl) paginationEl.innerHTML = '';

    fetchJSON('data/students.json')
      .then(data => {
        allStudents = data;
        populateBranchOptions(allStudents);
        stateEl.innerHTML = '';
        currentPage = 1;
        renderPage();
      })
      .catch(err => {
        showError(stateEl, 'Could not load student list right now.', loadStudents);
        console.error(err);
      });
  }

  function populateBranchOptions(students) {
    if (!branchSelect) return;
    const branches = [...new Set(students.map(s => s.branch))].sort();
    branchSelect.innerHTML = '<option value="">All Branches</option>' +
      branches.map(b => `<option value="${b}">${b}</option>`).join('');
  }

  function getFilteredSortedStudents() {
    const term = (searchInput?.value || '').trim().toLowerCase();
    const branch = branchSelect?.value || '';
    const sortBy = sortSelect?.value || 'name-asc';

    let result = allStudents.filter(s => {
      const matchesTerm = !term ||
        s.name.toLowerCase().includes(term) ||
        s.studentId.toLowerCase().includes(term);
      const matchesBranch = !branch || s.branch === branch;
      return matchesTerm && matchesBranch;
    });

    result = result.slice().sort((a, b) => {
      if (sortBy === 'name-asc')  return a.name.localeCompare(b.name);
      if (sortBy === 'name-desc') return b.name.localeCompare(a.name);
      if (sortBy === 'id-asc')    return a.studentId.localeCompare(b.studentId);
      return 0;
    });

    return result;
  }

  function renderPage() {
    const filtered = getFilteredSortedStudents();
    const totalPages = Math.max(1, Math.ceil(filtered.length / ITEMS_PER_PAGE));
    currentPage = Math.min(currentPage, totalPages);

    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const pageItems = filtered.slice(start, start + ITEMS_PER_PAGE);

    if (resultCountEl) {
      resultCountEl.textContent = `${filtered.length} student${filtered.length === 1 ? '' : 's'} found`;
    }

    if (pageItems.length === 0) {
      tableBody.innerHTML = '<tr><td colspan="5">No students match your search/filter.</td></tr>';
    } else {
      tableBody.innerHTML = pageItems.map(studentToRowHTML).join('');
    }

    renderPagination(paginationEl, filtered.length, ITEMS_PER_PAGE, currentPage, (page) => {
      currentPage = page;
      renderPage();
    });
  }

  function statusClass(status) {
    if (status === 'Active') return 'status-active';
    if (status === 'Inactive') return 'status-inactive';
    return 'status-suspended';
  }

  function studentToRowHTML(s) {
    return `
      <tr>
        <td>${s.studentId}</td>
        <td>${s.name}</td>
        <td>${s.branch}</td>
        <td><span class="status-badge ${statusClass(s.status)}">${s.status}</span></td>
        <td>${s.email}</td>
      </tr>`;
  }

  if (searchInput)  searchInput.addEventListener('input', debounce(() => { currentPage = 1; renderPage(); }, 300));
  if (branchSelect) branchSelect.addEventListener('change', () => { currentPage = 1; renderPage(); });
  if (sortSelect)   sortSelect.addEventListener('change', () => { currentPage = 1; renderPage(); });

  loadStudents();
})();