<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scholarships</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
 <link rel="stylesheet" href="{{ asset('css/scholarship.css') }}">

</head>

<body>
  <div class="header">
    <div class="logo">
        <img src="{{asset('images/logo.png.jpg')}}" class="img">
      <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="pfp">
      <div class="session">
        <h5>Session:</h5>
        <select>
          <option>2024-2025</option>
          <option>2026</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="/profile/profile.html"> <i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log In</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <div class="left" id="sidebar">
      <div class="text" id="text">
        <h6>ADMIN</h6>
        <p>synthesisbikaner@gmail.com</p>
      </div>
      <div class="accordion accordion-flush" id="accordionFlushExample">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i>User Management </button>
      </h2>
      <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">
          <ul class="menu" id="dropdown-body">
            <li><a class="item" href="/user management/emp/emp.html "> <i class="fa-solid fa-user"
                  id="side-icon"></i> Employee</a></li>
            <li><a class="item" href="/user management/batches a/batchesa.html"><i class="fa-solid fa-user-group"
                  id="side-icon"></i> Batches
                Assignment</a></li>
          </ul>
        </div>
      </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i> Master </button>
      </h2>
      <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">
          <ul class="menu" id="dropdown-body">
            <li><a class="item" href="/master/courses/course.html"><i class="fa-solid fa-book-open"
                  id="side-icon"></i> Courses</a></li>
            <li><a class="item" href="/master/batches/batches.html"><i
                  class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i>
                Batches</a></li>
            <li><a class="item" href="/master/scholarship/scholar.html"><i class="fa-solid fa-graduation-cap"
                  id="side-icon"></i> Scholarship</a>
            </li>
            <li><a class="item" href="/master/feesm/fees.html"><i class="fa-solid fa-credit-card"
                  id="side-icon"></i> Fees Master</a></li>
            <li><a class="item" href="/master/other fees/other.html"><i class="fa-solid fa-wallet"
                  id="side-icon"></i> Other Fees Master</a>
            </li>
            <li><a class="item" href="/master/branch/branch.html"><i class="fa-solid fa-diagram-project"
                  id="side-icon"></i> Branch
                Management</a></li>
          </ul>
        </div>
      </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i>Session Management
      </button>
    </h2>
    <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="/session mana/session/session.html"><i class="fa-solid fa-calendar-day"
                id="side-icon"></i> Session</a></li>
          <li><a class="item" href="/session mana/calendar/cal.html"><i class="fa-solid fa-calendar-days"
                id="side-icon"></i> Calendar</a></li>
          <li><a class="item" href="/session mana/student/student.html"><i class="fa-solid fa-user-check"
                id="side-icon"></i> Student Migrate</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i>Student Management

      </button>
    </h2>
    <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="/student management/inq/inq.html"><i class="fa-solid fa-circle-info" id="side-icon"></i> Inquiry
              Management</a></li>
          <li><a class="item" href="/student management/stu onboard/onstu.html"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a>
          </li>
          <li><a class="item" href="/student management/pending/pending.html"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Fees
              Students</a></li>
          <li><a class="item" href="/student management/students/stu.html"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive"
        id="accordion-button">
        <i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Management
      </button>
    </h2>
    <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="/fees management/collect/collect.html"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Collection</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix"
        id="accordion-button">
        <i class="fa-solid fa-user-check" id="side-icon"></i> Attendance Managment
      </button>
    </h2>
    <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="/attendance management/students/student.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Student</a></li>
          <li><a class="item" href="/attendance management/employee/employee.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Employee</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven"
        id="accordion-button">
        <i class="fa-solid fa-book-open" id="side-icon"></i> Study Material
      </button>
    </h2>
    <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="/study material/units/units.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Units</a></li>
          <li><a class="item" href="/study material/dispatch/dispatch.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Dispatch Material</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight"
        id="accordion-button">
        <i class="fa-solid fa-chart-column" id="side-icon"></i> Test Series Managment
      </button>
    </h2>
    <div id="flush-collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="/testseries/test.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Test Master</i></a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine"
        id="accordion-button">
        <i class="fa-solid fa-square-poll-horizontal" id="side-icon"></i> Reports</i>
      </button>
    </h2>
    <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="/reports/walk in/walk.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Walk In</a></li>
          <li><a class="item" href="/reports/att/att.html"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Attendance</a>
          </li>
          <li><a class="item" href="/reports/test/test.html"><i class="fa-solid fa-file" id="side-icon"></i>Test Series</a></li>
          <li><a class="item" href="/reports/inq/inq.html"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry History</a></li>
          <li><a class="item" href="/reports/onboard/onboard.html"><i class="fa-solid fa-file" id="side-icon"></i>Onboard History</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
    </div>

    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
          <h4>SCHOLARSHIP</h4>
        </div>
        <div class="buttons">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scholarshipModal"
            id="add">
            Create Scholarship
          </button>
        </div>
      </div>

      <div class="whole">
        <div class="dd">
          <div class="line">
            <h6>Show Enteries:</h6>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
                aria-expanded="false"> 10 </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item">5</a></li>
                <li><a class="dropdown-item">10</a></li>
                <li><a class="dropdown-item">15</a></li>
                <li><a class="dropdown-item">25</a></li>
              </ul>
            </div>
          </div>
          <div class="search">
            <h4 class="search-text">Search</h4>
            <input type="search" id="searchInput" placeholder="" class="search-holder" required>
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>

        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col" id="one">Serial No.</th>
              <th scope="col" id="one">Scholarship Name</th>
              <th scope="col" id="one">Short Name</th>
              <th scope="col" id="one">Type</th>
              <th scope="col" id="one">Category</th>
              <th scope="col" id="one">Applicable For</th>
              <th scope="col" id="one">Status</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <p class="data" style="display:none;">No data available in the table</p>
      </div>

      <div class="footer">
        <div class="left-footer">
          <p>Showing 0 to 0 of 0 Enteries</p>
        </div>
        <div class="right-footer">
          <nav aria-label="Page navigation example" id="bottom">
            <ul class="pagination" id="pagination"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <div class="modal fade" id="scholarshipModal" tabindex="-1" aria-labelledby="scholarshipModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="scholarshipModalLabel">Create Scholarship</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form id="scholarshipForm">
            <input type="hidden" id="scholarship_id" name="scholarship_id" value="">

            <div class="mb-3">
              <label class="form-label">Scholarship Type <span class="text-danger">*</span></label>
              <select id="scholarship_type" name="scholarship_type" class="form-control" required>
                <option value="">Select type</option>
                <option>Continuing Education Scholarship</option>
                <option>Board Examination Scholarship</option>
                <option>Special Scholarship</option>
                <option>Competition Exam Scholarship</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Scholarship Name <span class="text-danger">*</span></label>
              <input type="text" id="scholarship_name" name="scholarship_name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Short Name <span class="text-danger">*</span></label>
              <input type="text" id="short_name" name="short_name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea id="description" name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Category <span class="text-danger">*</span></label>
              <select id="category" name="category" class="form-control" required>
                <option value="">Select category</option>
                <option>OBC</option>
                <option>General</option>
                <option>SC</option>
                <option>ST</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Applicable For <span class="text-danger">*</span></label>
              <select id="applicable_for" name="applicable_for" class="form-control" required>
                <option value="">Select</option>
                <option>EWS</option>
                <option>Person with Disability</option>
                <option>Defence/Police</option>
                <option>All</option>
              </select>
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelBtn">Cancel</button>
          <button type="button" class="btn btn-primary" id="saveScholarshipBtn">Save</button>
        </div>
      </div>
    </div>
  </div>

  <!-- View Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="viewModalLabel">Scholarship Details</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="viewModalBody">
          <!-- Details will be populated dynamically -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>

  <script src="{{asset('js/emp.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
      const ENDPOINT_BASE = '/master/scholarship';
      const DATA_URL = `${ENDPOINT_BASE}/data`;
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      const tableBody = document.querySelector('#table tbody');
      const dataMsg = document.querySelector('.data');
      const saveBtn = document.getElementById('saveScholarshipBtn');
      const form = document.getElementById('scholarshipForm');
      const scholarshipModalEl = document.getElementById('scholarshipModal');
      const bsScholarshipModal = new bootstrap.Modal(scholarshipModalEl);
      const viewModalEl = document.getElementById('viewModal');
      const bsViewModal = new bootstrap.Modal(viewModalEl);
      const searchInput = document.getElementById('searchInput');
      const perPageBtn = document.getElementById('number');
      const perPageItems = document.querySelectorAll('.dropdown-menu .dropdown-item');

      const footerLeft = document.querySelector('.left-footer p');
      const paginationContainer = document.querySelector('#pagination');

      let state = {
        page: 1,
        per_page: parseInt(perPageBtn?.textContent.trim()) || 10,
        search: '',
      };

      // Per page dropdown
      perPageItems.forEach(it => {
        it.addEventListener('click', (e) => {
          const value = parseInt(e.currentTarget.textContent.trim());
          if (isNaN(value)) return;
          state.per_page = value;
          perPageBtn.textContent = value;
          state.page = 1;
          loadData();
        });
      });

      // Search with debounce
      searchInput?.addEventListener('input', debounce(() => {
        state.search = searchInput.value.trim();
        state.page = 1;
        loadData();
      }, 400));

      function debounce(fn, wait) {
        let t;
        return (...args) => {
          clearTimeout(t);
          t = setTimeout(() => fn.apply(this, args), wait);
        };
      }

      // Load data
      async function loadData() {
        try {
          const url = `${DATA_URL}?per_page=${state.per_page}&search=${encodeURIComponent(state.search)}&page=${state.page}`;
          const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
          if (!res.ok) throw new Error('Failed to load');
          const json = await res.json();
          if (!json.success) throw new Error(json.message || 'No data');

          const items = json.data || [];
          renderTable(items);
          renderFooter(json);
        } catch (err) {
          console.error(err);
          tableBody.innerHTML = '';
          dataMsg.style.display = 'block';
          dataMsg.textContent = 'Failed to load data';
        }
      }

      function renderTable(items) {
        tableBody.innerHTML = '';
        if (!items || items.length === 0) {
          dataMsg.style.display = 'block';
          dataMsg.textContent = 'No data available in the table';
          return;
        }
        dataMsg.style.display = 'none';

        items.forEach((it, idx) => {
          const id = it._id || it.id || '';
          const serialNo = (state.page - 1) * state.per_page + idx + 1;
          
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${serialNo}</td>
            <td>${escapeHtml(it.scholarship_name || '')}</td>
            <td>${escapeHtml(it.short_name || '')}</td>
            <td>${escapeHtml(it.scholarship_type || '')}</td>
            <td>${escapeHtml(it.category || '')}</td>
            <td>${escapeHtml(it.applicable_for || '')}</td>
            <td>
              ${it.status === 'active' 
                ? '<span class="status-badge status-active">Active</span>' 
                : '<span class="status-badge status-inactive">Inactive</span>'}
            </td>
            <td>
              <div class="dropdown">
                <button class="action-dots-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item view-btn" href="#" data-id="${id}">View</a></li>
                  <li><a class="dropdown-item edit-btn" href="#" data-id="${id}">Edit</a></li>
                  <li><a class="dropdown-item toggle-btn" href="#" data-id="${id}" data-status="${it.status}">
                    ${it.status === 'active' ? 'Deactivate' : 'Activate'}
                  </a></li>
                </ul>
              </div>
            </td>
          `;
          tableBody.appendChild(tr);
        });

        // Bind events
        tableBody.querySelectorAll('.view-btn').forEach(b => b.addEventListener('click', onView));
        tableBody.querySelectorAll('.edit-btn').forEach(b => b.addEventListener('click', onEdit));
        tableBody.querySelectorAll('.toggle-btn').forEach(b => b.addEventListener('click', onToggleStatus));
      }

      function renderFooter(json) {
        const from = json.data.length ? ((json.current_page - 1) * json.per_page + 1) : 0;
        const to = json.data.length ? ((json.current_page - 1) * json.per_page + json.data.length) : 0;
        footerLeft && (footerLeft.textContent = `Showing ${from} to ${to} of ${json.total} Enteries`);

        paginationContainer.innerHTML = '';

        const prevLi = document.createElement('li');
        prevLi.className = 'page-item' + (json.current_page <= 1 ? ' disabled' : '');
        prevLi.innerHTML = `<a class="page-link" href="#">Previous</a>`;
        paginationContainer.appendChild(prevLi);
        if (json.current_page > 1) {
          prevLi.addEventListener('click', (e) => {
            e.preventDefault();
            state.page = json.current_page - 1;
            loadData();
          });
        }

        const last = json.last_page || 1;
        const current = json.current_page || 1;
        const start = Math.max(1, current - 2);
        const end = Math.min(last, current + 2);

        for (let p = start; p <= end; p++) {
          const li = document.createElement('li');
          li.className = 'page-item';
          li.innerHTML = `<a class="page-link ${p === current ? 'active' : ''}" href="#" style="${p === current ? 'background-color: rgb(224, 83, 1); color: white;' : ''}">${p}</a>`;
          li.addEventListener('click', (e) => {
            e.preventDefault();
            state.page = p;
            loadData();
          });
          paginationContainer.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = 'page-item' + (json.current_page >= last ? ' disabled' : '');
        nextLi.innerHTML = `<a class="page-link" href="#">Next</a>`;
        paginationContainer.appendChild(nextLi);
        if (json.current_page < last) {
          nextLi.addEventListener('click', (e) => {
            e.preventDefault();
            state.page = json.current_page + 1;
            loadData();
          });
        }
      }

      function escapeHtml(s) {
        if (!s) return '';
        return String(s)
          .replaceAll('&', '&amp;')
          .replaceAll('<', '&lt;')
          .replaceAll('>', '&gt;')
          .replaceAll('"', '&quot;')
          .replaceAll("'", '&#039;');
      }

      // View handler
      async function onView(e) {
        e.preventDefault();
        const id = e.currentTarget.dataset.id;
        try {
          const res = await fetch(`${ENDPOINT_BASE}/${id}`, {
            headers: { 'Accept': 'application/json' }
          });
          const json = await res.json();
          if (!json.success) throw new Error(json.message || 'Could not load');
          
          const it = json.data;
          const viewBody = document.getElementById('viewModalBody');
          viewBody.innerHTML = `
            <div class="detail-row">
              <div class="detail-label">Scholarship Type:</div>
              <div class="detail-value">${escapeHtml(it.scholarship_type || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Scholarship Name:</div>
              <div class="detail-value">${escapeHtml(it.scholarship_name || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Short Name:</div>
              <div class="detail-value">${escapeHtml(it.short_name || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Description:</div>
              <div class="detail-value">${escapeHtml(it.description || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Category:</div>
              <div class="detail-value">${escapeHtml(it.category || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Applicable For:</div>
              <div class="detail-value">${escapeHtml(it.applicable_for || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Status:</div>
              <div class="detail-value">
                ${it.status === 'active'
                  ? '<span class="status-badge status-active">Active</span>'
                  : '<span class="status-badge status-inactive">Inactive</span>'}
              </div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Created At:</div>
              <div class="detail-value">${it.created_at ? new Date(it.created_at).toLocaleString() : 'N/A'}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Updated At:</div>
              <div class="detail-value">${it.updated_at ? new Date(it.updated_at).toLocaleString() : 'N/A'}</div>
            </div>
          `;
          
          bsViewModal.show();
        } catch (err) {
          console.error(err);
          alert('Failed to load scholarship details');
        }
      }

      // Edit handler
      async function onEdit(e) {
        e.preventDefault();
        const id = e.currentTarget.dataset.id;
        try {
          const res = await fetch(`${ENDPOINT_BASE}/${id}`, {
            headers: { 'Accept': 'application/json' }
          });
          const json = await res.json();
          if (!json.success) throw new Error(json.message || 'Could not load');
          
          const it = json.data;
          document.getElementById('scholarship_id').value = it._id || it.id || '';
          document.getElementById('scholarship_type').value = it.scholarship_type || '';
          document.getElementById('scholarship_name').value = it.scholarship_name || '';
          document.getElementById('short_name').value = it.short_name || '';
          document.getElementById('description').value = it.description || '';
          document.getElementById('category').value = it.category || '';
          document.getElementById('applicable_for').value = it.applicable_for || '';
          
          document.getElementById('scholarshipModalLabel').textContent = 'Edit Scholarship';
          bsScholarshipModal.show();
        } catch (err) {
          console.error(err);
          alert('Failed to load record for editing');
        }
      }

      // Toggle status handler - ✅ FIXED
      async function onToggleStatus(e) {
        e.preventDefault();
        const id = e.currentTarget.dataset.id;
        const currentStatus = e.currentTarget.dataset.status;
        const action = currentStatus === 'active' ? 'deactivate' : 'activate';
        
        if (!confirm(`Are you sure you want to ${action} this scholarship?`)) return;
        
        try {
          const res = await fetch(`${ENDPOINT_BASE}/${id}/toggle-status`, {  // ✅ Changed from /toggle to /toggle-status
            method: 'PATCH',  // ✅ Changed from POST to PATCH
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
          });
          const json = await res.json();
          if (!json.success) throw new Error(json.message || 'Toggle failed');
          
          alert(`Scholarship ${action}d successfully`);
          await loadData();
        } catch (err) {
          console.error(err);
          alert('Toggle status failed: ' + err.message);
        }
      }

      // Save (create or update)
      saveBtn.addEventListener('click', async () => {
        const id = document.getElementById('scholarship_id').value || null;
        
        const payload = {
          scholarship_type: document.getElementById('scholarship_type').value.trim(),
          scholarship_name: document.getElementById('scholarship_name').value.trim(),
          short_name: document.getElementById('short_name').value.trim(),
          description: document.getElementById('description').value.trim(),
          category: document.getElementById('category').value.trim(),
          applicable_for: document.getElementById('applicable_for').value.trim()
        };

        // Client side validation
        if (!payload.scholarship_type || !payload.scholarship_name || !payload.short_name || 
            !payload.category || !payload.applicable_for) {
          alert('Please fill all required fields');
          return;
        }

        try {
          const method = id ? 'PUT' : 'POST';
          const url = id ? `${ENDPOINT_BASE}/${id}` : `${ENDPOINT_BASE}/`;
          
          const res = await fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          });

          const json = await res.json();
          if (!json.success) {
            const msg = json.message || (json.errors ? JSON.stringify(json.errors) : 'Save failed');
            throw new Error(msg);
          }

          alert(id ? 'Scholarship updated successfully' : 'Scholarship created successfully');
          bsScholarshipModal.hide();
          form.reset();
          document.getElementById('scholarship_id').value = '';
          document.getElementById('scholarshipModalLabel').textContent = 'Create Scholarship';
          await loadData();

        } catch (err) {
          console.error(err);
          alert('Save failed: ' + err.message);
        }
      });

      // Create button - reset form
      document.querySelectorAll('#add').forEach(btn => {
        btn.addEventListener('click', () => {
          form.reset();
          document.getElementById('scholarship_id').value = '';
          document.getElementById('scholarshipModalLabel').textContent = 'Create Scholarship';
        });
      });

      // Cancel button
      document.getElementById('cancelBtn')?.addEventListener('click', () => {
        form.reset();
        document.getElementById('scholarship_id').value = '';
        document.getElementById('scholarshipModalLabel').textContent = 'Create Scholarship';
      });

      // Reset form when modal is hidden
      scholarshipModalEl.addEventListener('hidden.bs.modal', () => {
        form.reset();
        document.getElementById('scholarship_id').value = '';
        document.getElementById('scholarshipModalLabel').textContent = 'Create Scholarship';
      });

      // Initial load
      loadData();
    });
</script>
  </html>