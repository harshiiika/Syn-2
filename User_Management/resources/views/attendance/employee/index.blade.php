<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Employee Attendance</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
<style>
.right {
  background-color: #f5f5f5;
  padding: 25px;
  height: calc(100vh - 100px);
  overflow-y: auto;
}

.attendance-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  width: 100%;
}

.page-title {
  color: #ed5b00;
  font-size: 24px;
  font-weight: 600;
  margin: 0;
}

.filters-container {
  display: flex;
  gap: 12px;
}

.filter-select,
.filter-date {
  padding: 8px 16px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  background: white;
  min-width: 180px;
}

.tab-container {
  display: flex;
  gap: 10px;
  margin-bottom: 25px;
    width: 90%;

}

.tab-btn {
  padding: 10px 30px;
  border: 2px solid #ed5b00;
  border-radius: 6px;
  background: white;
  color: #ed5b00;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.tab-btn.active {
  background: #ed5b00;
  color: white;
}

.stats-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 25px;
    width: 90%;

}

.stat-card {
  background: white;
  padding: 25px;
  border-radius: 10px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: transform 0.3s;
}

.stat-card h6 {
  color: #ed5b00;
  font-size: 14px;
  font-weight: 600;
  margin: 0 0 15px 0;
  text-transform: uppercase;
}

.stat-card h2 {
  color: #333;
  font-size: 42px;
  font-weight: 700;
  margin: 0;
}

.content-card {
  background: white;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    width: 90%;

}

.list-header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 0;
  margin-bottom: 25px;
  border-bottom: 2px solid #f0f0f0;
}

.list-title {
  color: #333;
  font-size: 16px;
  font-weight: 600;
  margin: 0;
}

.list-title span {
  color: #ed5b00;
  font-weight: 700;
}

.action-btns {
  display: flex;
  gap: 10px;
}

.btn-action {
  padding: 10px 24px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-mark-present {
  background: #ed5b00;
  color: white;
}

.btn-mark-present:disabled {
  background: #e0e0e0;
  color: #999;
  cursor: not-allowed;
}

.btn-mark-present:not(:disabled):hover {
  background: #d54f00;
}

.btn-mark-absent {
  background: #dc3545;
  color: white;
}

.btn-mark-absent:disabled {
  background: #e0e0e0;
  color: #999;
  cursor: not-allowed;
}

.btn-mark-absent:not(:disabled):hover {
  background: #c82333;
}

.btn-upload {
  background: #28a745;
  color: white;
}

.btn-upload:hover {
  background: #218838;
}

.table-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.entries-control {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  color: #666;
}

.entries-btn {
  padding: 6px 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
}

.search-control {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  color: #666;
}

.search-wrapper {
  position: relative;
}

.search-input {
  padding: 8px 35px 8px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  width: 250px;
}

.search-icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #ed5b00;
}

.attendance-table {
  width: 100%;
  border-collapse: collapse;
}
/* Add this to your <style> section */
.table-success {
    background-color: #d4edda !important;
    transition: background-color 0.3s ease;
}

.table-warning {
    background-color: #fff3cd !important;
    transition: background-color 0.3s ease;
}
.attendance-table thead th {
  background: white;
  color: #ed5b00;
  font-weight: 600;
  font-size: 14px;
  padding: 15px 12px;
  text-align: left;
  border-bottom: 2px solid #ed5b00;
}

.attendance-table tbody td {
  padding: 15px 12px;
  font-size: 14px;
  color: #333;
  border-bottom: 1px solid #f0f0f0;
  vertical-align: middle;
}

.attendance-table tbody tr:hover {
  background: #f8f9fa;
}

.status-badge {
  padding: 6px 16px;
  border-radius: 4px;
  font-size: 13px;
  font-weight: 500;
  display: inline-block;
  text-align: center;
  min-width: 100px;
}

.status-not-marked {
  background: #f8f9fa;
  color: #6c757d;
  border: 1px solid #dee2e6;
}

.status-present {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.status-absent {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.checkbox-input {
  width: 18px;
  height: 18px;
  cursor: pointer;
  accent-color: #ed5b00;
}

.loading-cell {
  text-align: center;
  padding: 60px 20px !important;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #ed5b00;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.spinner-border {
  display: inline-block;
  width: 1rem;
  height: 1rem;
  vertical-align: text-bottom;
  border: 0.15em solid currentColor;
  border-right-color: transparent;
  border-radius: 50%;
  animation: spin 0.75s linear infinite;
}

.spinner-border-sm {
  width: 0.875rem;
  height: 0.875rem;
  border-width: 0.12em;
}

.table-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 20px;
  border-top: 1px solid #f0f0f0;
}

.pagination {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
  gap: 5px;
}

.pagination li a {
  padding: 8px 14px;
  border: 1px solid #ddd;
  border-radius: 5px;
  color: #333;
  text-decoration: none;
  transition: all 0.3s;
}

.pagination li a:hover {
  background: #ed5b00;
  color: white;
  border-color: #ed5b00;
}

.pagination li.active a {
  background: #ed5b00;
  color: white;
  border-color: #ed5b00;
}
</style>
</head>

<body>
  <div class="header">
    <div class="logo">
      <img src="{{asset('images/logo.png.jpg')}}" class="img" alt="Logo">
      <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="pfp">
      <div class="session">
        <h5>Session:</h5>
        <select>
          <option>{{ session('current_session', '2025-2026') }}</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fa-solid fa-user"></i> Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <div class="left" id="sidebar">
 <div class="accordion accordion-flush" id="accordionFlushExample">
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
                <li>><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i>
                    Employee</a></li>
                <li>><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group"
                      id="side-icon"></i> Batches Assignment</a></li>
              </ul>
            </div>
          </div>
        </div>

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
                <li><a class="item" href="#"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Collection</a></li>
              </ul>
            </div>
          </div>
        </div>

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
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Student</a></li>
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
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Units</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Dispatch Material</a></li>
              </ul>
            </div>
          </div>
        </div>

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
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
              </ul>
            </div>
          </div>
        </div>

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

    <div class="right" id="right">
      <div class="attendance-header">
        <h4 class="page-title">Employee Attendance</h4>
        
        <div class="filters-container">
          <select class="filter-select" id="branchFilter">
            <option value="">Select Branch</option>
            @foreach($branches as $branch)
              <option value="{{ $branch->name }}">{{ $branch->name }}</option>
            @endforeach
          </select>
          
          <select class="filter-select" id="roleFilter">
            <option value="">Select Role</option>
            @foreach($roles as $role)
              <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
          </select>
          
          <input type="date" class="filter-date" id="dateFilter" value="{{ date('Y-m-d') }}">
        </div>
      </div>

      <div class="tab-container">
        <button type="button" class="tab-btn active" id="dailyTab">Daily</button>
        <button type="button" class="tab-btn" id="monthlyTab">Monthly</button>
      </div>

      <div class="stats-container">
        <div class="stat-card">
          <h6>Total Employees</h6>
          <h2 id="totalEmployees">0</h2>
        </div>
        <div class="stat-card">
          <h6>Present</h6>
          <h2 id="presentCount">0</h2>
        </div>
        <div class="stat-card">
          <h6>Absent</h6>
          <h2 id="absentCount">0</h2>
        </div>
      </div>

      <div class="content-card">
        <div class="list-header-section">
          <h6 class="list-title">List of Staff: <span id="selectedDate">{{ date('M d, Y') }}</span></h6>
          <div class="action-btns">
            <button class="btn-action btn-mark-present" id="markPresentBtn" disabled>Mark Present</button>
            <button class="btn-action btn-mark-absent" id="markAbsentBtn" disabled>Mark Absent</button>
            <button class="btn-action btn-upload" id="uploadBtn">Upload</button>
          </div>
        </div>

        <div class="table-controls">
          <div class="entries-control">
            <span>Show</span>
            <div class="dropdown">
              <button class="entries-btn" type="button" data-bs-toggle="dropdown">
                <span id="entriesCount">10</span>
                <i class="fas fa-chevron-down"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" data-value="10">10</a></li>
                <li><a class="dropdown-item" data-value="25">25</a></li>
                <li><a class="dropdown-item" data-value="50">50</a></li>
                <li><a class="dropdown-item" data-value="100">100</a></li>
              </ul>
            </div>
            <span>entries</span>
          </div>
          
          <div class="search-control">
            <label>Search:</label>
            <div class="search-wrapper">
              <input type="search" class="search-input" id="searchInput" placeholder="Search employees...">
              <i class="fas fa-search search-icon"></i>
            </div>
          </div>
        </div>

        <div class="table-wrapper">
          <table class="attendance-table">
            <thead>
              <tr>
                <th style="width: 50px;">
                  <input type="checkbox" id="selectAll" class="checkbox-input">
                </th>
                <th>Employee Name</th>
                <th>Roles</th>
                <th>Email</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="attendanceTableBody">
              <tr>
                <td colspan="5" class="loading-cell">
                  <div class="loading-state">
                    <div class="spinner"></div>
                    <p>Loading attendance data...</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          <div class="pagination-info">
            <p id="paginationInfo">Showing 0 to 0 of 0 entries</p>
          </div>
          <nav class="pagination-nav">
            <ul class="pagination" id="pagination"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('js/sidebar.js')}}"></script>

<script>
var currentPage = 1;
var perPage = 10;
var currentFilters = {
    branch: '',
    role: '',
    date: '',
    view: 'daily',
    search: ''
};

$(document).ready(function() {
    console.log('🚀 Attendance page initialized');
    
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!csrfToken) {
        console.error('❌ CSRF token not found!');
    } else {
        console.log('✅ CSRF token found');
    }
    
    var today = new Date().toISOString().split('T')[0];
    $('#dateFilter').val(today);
    currentFilters.date = today;
    console.log('📅 Date set to:', today);
    
    loadAttendanceData();
    
    $('#branchFilter, #roleFilter, #dateFilter').on('change', function() {
        currentPage = 1;
        updateFilters();
        loadAttendanceData();
    });
    
    $('#dailyTab, #monthlyTab').on('click', function() {
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');
        currentFilters.view = $(this).attr('id') === 'monthlyTab' ? 'monthly' : 'daily';
        currentPage = 1;
        loadAttendanceData();
    });
    
    var searchTimer;
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimer);
        var searchValue = $(this).val();
        searchTimer = setTimeout(function() {
            currentPage = 1;
            currentFilters.search = searchValue;
            loadAttendanceData();
        }, 500);
    });
    
    $('.entries-btn').next('.dropdown-menu').find('.dropdown-item').on('click', function(e) {
        e.preventDefault();
        perPage = parseInt($(this).data('value'));
        $('#entriesCount').text(perPage);
        currentPage = 1;
        loadAttendanceData();
    });
    
    $('#markPresentBtn').on('click', function() {
        markAllAttendance('present');
    });
    
    $('#markAbsentBtn').on('click', function() {
        markAllAttendance('absent');
    });
    
    $('#selectAll').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('#attendanceTableBody input[type="checkbox"]').prop('checked', isChecked);
        updateBulkActionButtons();
    });
    
    $('#uploadBtn').on('click', function() {
        Swal.fire({
            icon: 'info',
            title: 'Upload Feature',
            text: 'Bulk upload feature coming soon!',
            confirmButtonColor: '#ed5b00'
        });
    });
});

function updateFilters() {
    currentFilters = {
        branch: $('#branchFilter').val() || '',
        role: $('#roleFilter').val() || '',
        date: $('#dateFilter').val() || '',
        view: $('.tab-btn.active').attr('id') === 'monthlyTab' ? 'monthly' : 'daily',
        search: $('#searchInput').val() || ''
    };
    
    if (currentFilters.date) {
        var date = new Date(currentFilters.date);
        var formatted = date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
        $('#selectedDate').text(formatted);
    }
}

function loadAttendanceData() {
    console.log('📊 Loading attendance data...');
    updateFilters();
    
    var tbody = $('#attendanceTableBody');
    tbody.html('<tr><td colspan="5" class="loading-cell"><div class="loading-state"><div class="spinner"></div><p>Loading...</p></div></td></tr>');
    
    $.ajax({
        url: '{{ route("attendance.employee.data") }}',
        method: 'GET',
        data: {
            page: currentPage,
            per_page: perPage,
            branch: currentFilters.branch,
            role: currentFilters.role,
            date: currentFilters.date,
            view: currentFilters.view,
            search: currentFilters.search
        },
        success: function(response) {
            console.log('✅ Data loaded:', response);
            
            if (response.success) {
                updateStatistics(response.statistics);
                updateTable(response.data);
                updatePagination(response);
                updateBulkActionButtons();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ AJAX Error:', error);
            tbody.html('<tr><td colspan="5" class="text-center text-danger"><p class="mt-2">Error loading data</p></td></tr>');
        }
    });
}

function updateStatistics(stats) {
    $('#totalEmployees').text(stats.total || 0);
    $('#presentCount').text(stats.present || 0);
    $('#absentCount').text(stats.absent || 0);
}

function updateTable(employees) {
    console.log('📋 Updating table with', employees.length, 'employees');
    
    var tbody = $('#attendanceTableBody');
    tbody.empty();
    
    if (!employees || employees.length === 0) {
        tbody.html('<tr><td colspan="5" class="text-center text-muted"><p class="mt-2">No employees found</p></td></tr>');
        return;
    }
    
    $.each(employees, function(index, employee) {
        var statusBadge = getStatusBadge(employee.status);
        
        console.log('👤', employee.name, '| Status:', employee.status);
        
        var row = '<tr data-employee-id="' + employee._id + '">' +
            '<td class="text-center"><input type="checkbox" class="checkbox-input employee-checkbox" value="' + employee._id + '"></td>' +
            '<td><strong>' + employee.name + '</strong><br><small class="text-muted">' + employee.email + '</small></td>' +
            '<td>' + employee.role + '</td>' +
            '<td>' + employee.email + '</td>' +
            '<td>' + statusBadge + '</td>' +
            '</tr>';
        
        tbody.append(row);
    });
    
    $('.employee-checkbox').on('change', updateBulkActionButtons);
    console.log('✅ Table updated');
}

function getStatusBadge(status) {
    switch(status) {
        case 'present':
            return '<span class="status-badge status-present">Present</span>';
        case 'absent':
            return '<span class="status-badge status-absent">Absent</span>';
        default:
            return '<span class="status-badge status-not-marked">Not-Marked</span>';
    }
}

function updateBulkActionButtons() {
    var checkedCount = $('.employee-checkbox:checked').length;
    console.log('📊 Checked count:', checkedCount);
    $('#markPresentBtn, #markAbsentBtn').prop('disabled', checkedCount === 0);
}

function updatePagination(response) {
    var from = response.data.length > 0 ? ((response.current_page - 1) * response.per_page + 1) : 0;
    var to = Math.min(response.current_page * response.per_page, response.total);
    $('#paginationInfo').text('Showing ' + from + ' to ' + to + ' of ' + response.total + ' entries');
    
    var paginationContainer = $('#pagination');
    paginationContainer.empty();
    
    if (response.last_page > 1) {
        var prevDisabled = response.current_page === 1 ? 'disabled' : '';
        paginationContainer.append('<li class="page-item ' + prevDisabled + '"><a class="page-link" href="#" onclick="changePage(' + (response.current_page - 1) + '); return false;">Previous</a></li>');
        
        for (var i = 1; i <= response.last_page; i++) {
            var active = i === response.current_page ? 'active' : '';
            paginationContainer.append('<li class="page-item ' + active + '"><a class="page-link" href="#" onclick="changePage(' + i + '); return false;">' + i + '</a></li>');
        }
        
        var nextDisabled = response.current_page === response.last_page ? 'disabled' : '';
        paginationContainer.append('<li class="page-item ' + nextDisabled + '"><a class="page-link" href="#" onclick="changePage(' + (response.current_page + 1) + '); return false;">Next</a></li>');
    }
}

function changePage(page) {
    currentPage = page;
    loadAttendanceData();
}

function markAllAttendance(status) {
    var checkedIds = $('.employee-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    console.log('🔍 Checked IDs:', checkedIds);
    console.log('📝 Will mark as:', status);
    
    if (checkedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Selection',
            text: 'Please select employees first',
            confirmButtonColor: '#ed5b00'
        });
        return;
    }
    
    Swal.fire({
        title: 'Are you sure?',
        text: 'Mark ' + checkedIds.length + ' employee(s) as ' + status + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: status === 'present' ? '#ed5b00' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, mark as ' + status + '!'
    }).then(function(result) {
        if (result.isConfirmed) {
            console.log('✅ Confirmed! Marking', checkedIds.length, 'employees');
            
            var $presentBtn = $('#markPresentBtn');
            var $absentBtn = $('#markAbsentBtn');
            
            $presentBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Marking...');
            $absentBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Marking...');
            
            var successCount = 0;
            var errorCount = 0;
            var totalCount = checkedIds.length;
            var processedIds = [];
            
            checkedIds.forEach(function(employeeId, index) {
                console.log('📝 Marking', (index + 1), '/', totalCount, ':', employeeId);
                
                $.ajax({
                    url: '{{ route("attendance.employee.mark") }}',
                    method: 'POST',
                    data: {
                        employee_id: employeeId,
                        status: status,
                        date: currentFilters.date,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('✅ API Response:', response);
                        
                        if (response.success) {
                            successCount++;
                            processedIds.push(employeeId);
                            
                            // MULTIPLE SELECTOR ATTEMPTS - ONE WILL WORK
                            var $row = $('tr[data-employee-id="' + employeeId + '"]');
                            
                            if (!$row.length) {
                                // Try alternate selector - by checkbox value
                                $row = $('.employee-checkbox[value="' + employeeId + '"]').closest('tr');
                            }
                            
                            console.log('🔍 Looking for employee:', employeeId);
                            console.log('📍 Row found:', $row.length);
                            
                            if ($row.length > 0) {
                                // Find the status cell (last td)
                                var $statusCell = $row.find('td:last-child');
                                console.log('📍 Status cell found:', $statusCell.length);
                                console.log('📝 Old HTML:', $statusCell.html());
                                
                                // Update status
                                var newBadge = getStatusBadge(status);
                                $statusCell.html(newBadge);
                                console.log('✅ New HTML:', $statusCell.html());
                                
                                // Uncheck checkbox
                                $row.find('.employee-checkbox').prop('checked', false);
                                
                                // Visual feedback
                                $row.addClass('bg-success bg-opacity-10');
                                setTimeout(function() {
                                    $row.removeClass('bg-success bg-opacity-10');
                                }, 1500);
                                
                                console.log('✅ UI updated successfully for:', employeeId);
                            } else {
                                console.warn('⚠️ Row not found for employee:', employeeId);
                            }
                        } else {
                            errorCount++;
                            console.error('❌ API returned false for:', employeeId);
                        }
                        
                        checkCompletion();
                    },
                    error: function(xhr, textStatus, error) {
                        errorCount++;
                        console.error('❌ AJAX error for:', employeeId);
                        console.error('Status:', textStatus);
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                        checkCompletion();
                    }
                });
            });
            
            function checkCompletion() {
                var completedCount = successCount + errorCount;
                console.log('📊 Progress:', completedCount, '/', totalCount);
                console.log('✅ Success:', successCount, '| ❌ Errors:', errorCount);
                
                if (completedCount === totalCount) {
                    console.log('🏁 All operations complete!');
                    
                    // Reset buttons
                    $presentBtn.prop('disabled', false).text('Mark Present');
                    $absentBtn.prop('disabled', false).text('Mark Absent');
                    
                    // Uncheck all
                    $('#selectAll').prop('checked', false);
                    $('.employee-checkbox').prop('checked', false);
                    updateBulkActionButtons();
                    
                    // Show result
                    if (errorCount === 0) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: successCount + ' employee(s) marked as ' + status,
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Partial Success',
                            html: '<strong>' + successCount + '</strong> succeeded<br>' + 
                                  '<strong>' + errorCount + '</strong> failed',
                            confirmButtonColor: '#ed5b00'
                        });
                    }
                    
                    // Reload table after showing message
                    setTimeout(function() {
                        loadAttendanceData();
                    }, 500);
                }
            }
        }
    });
}

// Make sure getStatusBadge is working correctly
function getStatusBadge(status) {
    console.log('🎨 Creating badge for status:', status);
    
    var badge = '';
    switch(status) {
        case 'present':
            badge = '<span class="status-badge status-present">Present</span>';
            break;
        case 'absent':
            badge = '<span class="status-badge status-absent">Absent</span>';
            break;
        default:
            badge = '<span class="status-badge status-not-marked">Not-Marked</span>';
    }
    
    console.log('🎨 Badge HTML:', badge);
    return badge;
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: '#dc3545'
    });
}
</script>
</body>
</html>