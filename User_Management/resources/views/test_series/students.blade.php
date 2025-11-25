<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $testSeries->test_name }} - Students</title>
  <link rel="stylesheet" href="{{ asset('css/emp.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Flash Messages -->
  <div class="flash-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  <!-- Header -->
  <div class="header">
    <div class="logo">
      <img src="{{ asset('images/logo.png.jpg') }}" class="img" alt="Logo">
      <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="pfp">
      <div class="session">
        <h5>Session:</h5>
        <select>
          <option>2024-2025</option>
          <option selected>2025-2026</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <!-- Sidebar (copy from your detail.blade.php) -->
     <div class="left" id="sidebar">
      <div class="text" id="text">
        <h6>Admin</h6>
        <p>synthesisbikaner@gmail.com</p>
      </div>

    <div class="accordion accordion-flush" id="accordionFlushExample">
  <!-- User Management -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i>User Management
      </button>
    </h2>
    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i> Employee</a></li>     
          <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group" id="side-icon"></i> Batches Assignment</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Master -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo"
        id="accordion-button">
        <i class="fa-solid fa-user-group" id="side-icon"></i> Master
      </button>
    </h2>
    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('courses.index') }}"><i class="fa-solid fa-book-open" id="side-icon"></i> Courses</a></li>
          <li><a class="item" href="{{ route('batches.index') }}"><i class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i> Batches</a></li>
          <li><a class="item" href="{{ route('master.scholarship.index') }}"><i class="fa-solid fa-graduation-cap" id="side-icon"></i> Scholarship</a></li>
          <li><a class="item" href="{{ route('fees.index') }}"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Master</a></li>
          <li><a class="item" href="{{ route('master.other_fees.index') }}"><i class="fa-solid fa-wallet" id="side-icon"></i> Other Fees Master</a></li>
          <li><a class="item" href="{{ route('branches.index') }}"><i class="fa-solid fa-diagram-project" id="side-icon"></i> Branch Management</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Session Management -->
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
          <li><a class="item" href="{{ route('sessions.index') }}"><i class="fa-solid fa-calendar-day" id="side-icon"></i> Session</a></li>
          <li><a class="item" href="{{ route('calendar.index') }}"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Calendar</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-user-check" id="side-icon"></i> Student Migrate</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Student Management -->
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
          <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Inquiry Management</a></li>
          <li><a class="item" href="{{ route('student.student.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a></li>
          <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Fees Students</a></li>
          <li><a class="item active" href="{{ route('smstudents.index') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Fees Management -->
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
          <li><a class="item" href="{{ route('fees.management.index') }}"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Collection</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Attendance Management -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix"
        id="accordion-button">
        <i class="fa-solid fa-user-check" id="side-icon"></i> Attendance Management
      </button>
    </h2>
    <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Employee</a></li>
          <li><a class="item" href="{{ route('attendance.student.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Student</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Study Material -->
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
          <li><a class="item" href="{{ route('units.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Units</a></li>
          <li><a class="item" href="{{ route('study_material.dispatch.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Dispatch Material</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Test Series Management -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight"
        id="accordion-button">
        <i class="fa-solid fa-chart-column" id="side-icon"></i> Test Series Management
      </button>
    </h2>
    <div id="flush-collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="{{ route('test_series.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Reports -->
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine"
        id="accordion-button">
        <i class="fa-solid fa-square-poll-horizontal" id="side-icon"></i> Reports
      </button>
    </h2>
    <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <ul class="menu" id="dropdown-body">
          <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Walk In</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Attendance</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Test Series</a></li>
          <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry History</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Onboard History</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
    </div>

    <!-- Main Content -->
    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
          <h4><i class="fas fa-users me-2"></i>{{ $testSeries->test_name }}</h4>
          @php
  $courseName = $testSeries->course_name ?? ($testSeries->course->course_name ?? ($testSeries->course->name ?? 'Unknown'));
@endphp
<a href="{{ route('test_series.show', urlencode($courseName)) }}" class="btn btn-sm btn-outline-secondary">
</a>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#lockResultModal" style="background-color: #ff6607ff; color: white">
            Lock Result
          </button>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadSyllabusModal" style="background-color: #ff6607ff; border-color: #ff6607ff;">
           Upload Syllabus
          </button>
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadResultModal" style="background-color: #ff6607ff; border-color: #ff6607ff;">
            Upload Result
          </button>
          <button class="btn" style="background-color: #fd550dff; color: white;" data-bs-toggle="modal" data-bs-target="#addTestModal">
          Add Test
          </button>
        </div>
      </div>

      <div class="whole">
        <!-- Controls -->
        <div class="dd">
          <div class="line">
            <h6>Show</h6>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown">10</button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item entries-option" href="#" data-value="10">10</a></li>
                <li><a class="dropdown-item entries-option" href="#" data-value="25">25</a></li>
                <li><a class="dropdown-item entries-option" href="#" data-value="50">50</a></li>
                <li><a class="dropdown-item entries-option" href="#" data-value="100">100</a></li>
              </ul>
            </div>
            <h6>entries</h6>
          </div>
          <div class="search">
            <h4 class="search-text">Search:</h4>
            <input type="search" placeholder="" class="search-holder" id="searchInput">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>

        <!-- Students Table -->
        <table class="table table-hover" id="studentsTable">
          <thead>
            <tr>
              <th>Serial No.</th>
              <th>Student Name</th>
              <th>RollNumber</th>
              <th>Test Type</th>
              <th>Subject Type</th>
              <th>Father Name</th>
              <th>Batch code</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @forelse($students as $index => $student)
              <tr data-row="true">
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->student_name ?? $student->name }}</td>
                <td>{{ $student->roll_no }}</td>
                <td>{{ $testSeries->test_type }}</td>
                <td>{{ $testSeries->subject_type }}</td>
                <td>{{ $student->father_name ?? $student->father }}</td>
                <td>{{ $student->batch->batch_id ?? $student->batch_name ?? 'N/A' }}</td>
              </tr>
            @empty
              <tr id="noResultsRow">
                <td colspan="7" class="text-center">
                  <div class="alert alert-info m-3">
                    <i class="fas fa-info-circle me-2"></i>
                    No students found enrolled in course: <strong>{{ $testSeries->course_name }}</strong>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div id="paginationInfo">
            Showing <span id="showingFrom">1</span> to <span id="showingTo">{{ min(10, $students->count()) }}</span> of <span id="totalEntries">{{ $students->count() }}</span> entries
          </div>
          <nav>
            <ul class="pagination" id="pagination"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Lock Result Modal -->
  <div class="modal fade" id="lockResultModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #ff6607ff; color: white;">
          <h5 class="modal-title"><i class="fas fa-lock me-2"></i>Lock Result</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to lock the results for this test?</p>
          <p class="text-muted">Once locked, results cannot be modified.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-warning">Lock Results</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Upload Syllabus Modal -->
  <div class="modal fade" id="uploadSyllabusModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="POST" enctype="multipart/form-data" class="modal-content">
        @csrf
        <div class="modal-header" style="background-color: #ff6607ff; color: white;">
          <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Upload Syllabus</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Select Syllabus File</label>
            <input type="file" name="syllabus" class="form-control" accept=".pdf,.doc,.docx" required>
            <small class="text-muted">Accepted formats: PDF, DOC, DOCX</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Upload Result Modal -->
  <div class="modal fade" id="uploadResultModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="POST" enctype="multipart/form-data" class="modal-content">
        @csrf
        <div class="modal-header" style="background-color: #ff6607ff; color: white;">
          <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Upload Result</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Select Result File</label>
            <input type="file" name="result" class="form-control" accept=".xlsx,.xls,.csv" required>
            <small class="text-muted">Accepted formats: Excel, CSV</small>
          </div>
          <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <small>Format: Roll Number, Student Name, Marks</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Upload</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Add Test Modal -->
  <div class="modal fade" id="addTestModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="POST" class="modal-content">
        @csrf
        <div class="modal-header" style="background-color: #ff6607ff; color: white;">
          <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add New Test</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Test Number</label>
            <input type="number" name="test_number" class="form-control" min="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Scheduled Date</label>
            <input type="date" name="scheduled_date" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Duration (minutes)</label>
            <input type="number" name="duration" class="form-control" min="30">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background-color: #ff6607ff; color: white;">Add Test</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Sidebar toggle
      const toggleBtn = document.getElementById('toggleBtn');
      const sidebar = document.getElementById('sidebar');
      const right = document.getElementById('right');
      const text = document.getElementById('text');

      if (toggleBtn && sidebar && right && text) {
        toggleBtn.addEventListener('click', () => {
          sidebar.classList.toggle('collapsed');
          right.classList.toggle('expanded');
          text.classList.toggle('hidden');
        });
      }

      // Auto-hide flash messages
      setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
          alert.classList.remove('show');
          setTimeout(() => alert.remove(), 150);
        });
      }, 5000);

      // Table pagination
      let currentPage = 1;
      let entriesPerPage = 10;
      let allRows = [];
      let filteredRows = [];

      const tableBody = document.getElementById('tableBody');
      if (tableBody) {
        allRows = Array.from(tableBody.querySelectorAll('tr[data-row="true"]'));
        filteredRows = [...allRows];
        updateTable();
      }

      document.querySelectorAll('.entries-option').forEach(option => {
        option.addEventListener('click', function(e) {
          e.preventDefault();
          entriesPerPage = parseInt(this.dataset.value);
          document.getElementById('number').textContent = entriesPerPage;
          currentPage = 1;
          updateTable();
        });
      });

      // Search functionality
      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          const searchTerm = this.value.toLowerCase().trim();
          filteredRows = searchTerm === ''
            ? [...allRows]
            : allRows.filter(row => row.textContent.toLowerCase().includes(searchTerm));
          currentPage = 1;
          updateTable();
        });
      }

      function updateTable() {
        const start = (currentPage - 1) * entriesPerPage;
        const end = start + entriesPerPage;
        const pageRows = filteredRows.slice(start, end);

        allRows.forEach(row => row.style.display = 'none');
        const noResultsRow = document.getElementById('noResultsRow');
        if (noResultsRow) noResultsRow.style.display = 'none';

        if (pageRows.length > 0) {
          pageRows.forEach(row => row.style.display = '');
        } else if (noResultsRow) {
          noResultsRow.style.display = '';
        }

        const totalEntries = filteredRows.length;
        document.getElementById('showingFrom').textContent = totalEntries > 0 ? start + 1 : 0;
        document.getElementById('showingTo').textContent = Math.min(end, totalEntries);
        document.getElementById('totalEntries').textContent = totalEntries;

        updatePagination();
      }

      function updatePagination() {
        const totalPages = Math.ceil(filteredRows.length / entriesPerPage);
        const pagination = document.getElementById('pagination');
        if (!pagination) return;

        pagination.innerHTML = '';
        if (totalPages === 0) return;

        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">Previous</a>`;
        pagination.appendChild(prevLi);

        for (let i = 1; i <= Math.min(totalPages, 5); i++) {
          const li = document.createElement('li');
          li.className = `page-item ${i === currentPage ? 'active' : ''}`;
          li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>`;
          pagination.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Next</a>`;
        pagination.appendChild(nextLi);
      }

      window.changePage = function(page) {
        const totalPages = Math.ceil(filteredRows.length / entriesPerPage);
        if (page >= 1 && page <= totalPages) {
          currentPage = page;
          updateTable();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      };
    });
  </script>
</body>
</html>