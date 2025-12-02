<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Monthly Student Attendance</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  
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
.filter-month {
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
  text-decoration: none;
  display: inline-block;
}

.tab-btn.active {
  background: #ed5b00;
  color: white;
}

.tab-btn:hover {
  background: #ed5b00;
  color: white;
}

.content-card {
  background: white;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  width: 90%;
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

.monthly-table {
  width: 100%;
  border-collapse: collapse;
}

.monthly-table thead th {
  background: white;
  color: #ed5b00;
  font-weight: 600;
  font-size: 14px;
  padding: 15px 12px;
  text-align: left;
  border-bottom: 2px solid #ed5b00;
}

.monthly-table tbody td {
  padding: 15px 12px;
  font-size: 14px;
  color: #333;
  border-bottom: 1px solid #f0f0f0;
  vertical-align: middle;
}

.monthly-table tbody tr:hover {
  background: #f8f9fa;
}

.action-btn {
  padding: 8px 20px;
  border: none;
  border-radius: 6px;
  background: #ed5b00;
  color: white;
  cursor: pointer;
  font-size: 13px;
  font-weight: 500;
  transition: all 0.3s;
}

.action-btn:hover {
  background: #d54f00;
}

.action-btn i {
  margin-right: 5px;
}

/* Modal Styles */
.attendance-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  animation: fadeIn 0.3s;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.modal-content-custom {
  position: relative;
  background-color: white;
  margin: 3% auto;
  width: 90%;
  max-width: 1000px;
  border-radius: 12px;
  box-shadow: 0 5px 30px rgba(0,0,0,0.3);
  animation: slideDown 0.3s;
  max-height: 85vh;
  display: flex;
  flex-direction: column;
}

@keyframes slideDown {
  from {
    transform: translateY(-50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-header-custom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 25px;
  border-bottom: 2px solid #f0f0f0;
  background: linear-gradient(135deg, #ed5b00 0%, #ff7a33 100%);
  border-radius: 12px 12px 0 0;
}

.modal-title-custom {
  color: white;
  font-size: 20px;
  font-weight: 600;
  margin: 0;
}

.close-btn-custom {
  background: none;
  border: none;
  color: white;
  font-size: 28px;
  cursor: pointer;
  padding: 0;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.3s;
}

.close-btn-custom:hover {
  background: rgba(255,255,255,0.2);
  transform: rotate(90deg);
}

.modal-body-custom {
  padding: 0;
  overflow-y: auto;
  max-height: calc(85vh - 80px);
}

.details-table-wrapper {
  overflow-x: auto;
}

.details-table {
  width: 100%;
  border-collapse: collapse;
}

.details-table thead th {
  background: #f8f9fa;
  color: #333;
  font-weight: 600;
  font-size: 14px;
  padding: 15px 20px;
  text-align: left;
  border-bottom: 2px solid #ed5b00;
  position: sticky;
  top: 0;
  z-index: 10;
}

.details-table tbody td {
  padding: 15px 20px;
  font-size: 14px;
  color: #555;
  border-bottom: 1px solid #f0f0f0;
}

.details-table tbody tr:hover {
  background: #f8f9fa;
}

.details-table tbody tr:nth-child(even) {
  background: #fafafa;
}

.status-badge-present {
  background: #d4edda;
  color: #155724;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
  display: inline-block;
}

.status-badge-absent {
  background: #f8d7da;
  color: #721c24;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
  display: inline-block;
}

.status-badge-holiday {
  background: #fff3cd;
  color: #856404;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
  display: inline-block;
}

.day-badge {
  font-size: 12px;
  color: #666;
  font-weight: 500;
  text-transform: capitalize;
}

.weekend-day {
  color: #dc3545;
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
  margin: 0 auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
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
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</a></li>
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

      <!-- Left side bar accordian -->
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
          <li><a class="item" href="{{ route('dispatch.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Dispatch Material</a></li>

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

    <div class="right" id="right">
      <div class="attendance-header">
        <h4 class="page-title">Student Attendance - Monthly View</h4>
        
        <div class="filters-container">
          <select class="filter-select" id="branchFilter">
            <option value="">All Branches</option>
            @foreach($branches as $branch)
              <option value="{{ $branch->name }}">{{ $branch->name }}</option>
            @endforeach
          </select>
          
          <select class="filter-select" id="batchFilter">
            <option value="">All Batches</option>
            @foreach($batches as $batch)
              <option value="{{ $batch->_id }}">{{ $batch->batch_id ?? $batch->name }}</option>
            @endforeach
          </select>
          
          <select class="filter-select" id="courseFilter">
            <option value="">All Courses</option>
            @foreach($courses as $course)
              <option value="{{ $course->_id }}">{{ $course->name }}</option>
            @endforeach
          </select>
          
          <input type="month" class="filter-month" id="monthFilter" value="{{ date('Y-m') }}">
        </div>
      </div>

      <div class="tab-container">
        <a href="{{ route('attendance.student.index') }}" class="tab-btn">Daily</a>
        <button type="button" class="tab-btn active">Monthly</button>
      </div>

      <div class="content-card">
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
              <input type="search" class="search-input" id="searchInput" placeholder="Search students...">
              <i class="fas fa-search search-icon"></i>
            </div>
          </div>
        </div>

        <div class="table-wrapper">
          <table class="monthly-table">
            <thead>
              <tr>
                <th>Serial No.</th>
                <th>Student Name</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Total Working Days</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="attendanceTableBody">
              <tr>
                <td colspan="6" class="loading-cell">
                  <div class="loading-state">
                    <div class="spinner"></div>
                    <p>Loading monthly attendance data...</p>
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

  <!-- Attendance Details Modal -->
  <div id="attendanceModal" class="attendance-modal">
    <div class="modal-content-custom">
      <div class="modal-header-custom">
        <h5 class="modal-title-custom" id="modalStudentName">Attendance Information</h5>
        <button class="close-btn-custom" id="closeModalBtn">&times;</button>
      </div>
      <div class="modal-body-custom">
        <div class="details-table-wrapper">
          <table class="details-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Days</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="modalTableBody">
              <tr>
                <td colspan="3" class="loading-cell">
                  <div class="spinner"></div>
                  <p>Loading attendance details...</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('js/emp.js')}}"></script>

<script>
var currentPage = 1;
var perPage = 10;
var currentFilters = {
    branch: '',
    batch: '',
    course: '',
    month: '',
    search: ''
};

$(document).ready(function() {
    console.log('🚀 Monthly Student Attendance initialized');
    
    var today = new Date();
    var currentMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
    $('#monthFilter').val(currentMonth);
    currentFilters.month = currentMonth;
    
    loadMonthlyData();
    
    $('#branchFilter, #batchFilter, #courseFilter, #monthFilter').on('change', function() {
        currentPage = 1;
        updateFilters();
        loadMonthlyData();
    });
    
    var searchTimer;
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimer);
        var searchValue = $(this).val();
        searchTimer = setTimeout(function() {
            currentPage = 1;
            currentFilters.search = searchValue;
            loadMonthlyData();
        }, 500);
    });
    
    $('.entries-btn').next('.dropdown-menu').find('.dropdown-item').on('click', function(e) {
        e.preventDefault();
        perPage = parseInt($(this).data('value'));
        $('#entriesCount').text(perPage);
        currentPage = 1;
        loadMonthlyData();
    });

    $('#closeModalBtn').on('click', closeModal);
    
    $('#attendanceModal').on('click', function(e) {
        if (e.target.id === 'attendanceModal') {
            closeModal();
        }
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});

function updateFilters() {
    currentFilters = {
        branch: $('#branchFilter').val() || '',
        batch: $('#batchFilter').val() || '',
        course: $('#courseFilter').val() || '',
        month: $('#monthFilter').val() || '',
        search: $('#searchInput').val() || ''
    };
}

function loadMonthlyData() {
    console.log('📊 Loading monthly student data...');
    updateFilters();
    
    var tbody = $('#attendanceTableBody');
    tbody.html('<tr><td colspan="6" class="loading-cell"><div class="loading-state"><div class="spinner"></div><p>Loading...</p></div></td></tr>');
    
    $.ajax({
        url: '{{ route("attendance.student.monthly.data") }}',
        method: 'GET',
        data: {
            page: currentPage,
            per_page: perPage,
            branch: currentFilters.branch,
            batch: currentFilters.batch,
            course: currentFilters.course,
            month: currentFilters.month,
            search: currentFilters.search
        },
        success: function(response) {
            console.log('  Data loaded:', response);
            
            if (response.success) {
                updateTable(response.data);
                updatePagination(response);
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ AJAX Error:', error);
            tbody.html('<tr><td colspan="6" class="text-center text-danger"><p class="mt-2">Error loading data</p></td></tr>');
        }
    });
}

function updateTable(students) {
    console.log('📋 Updating table with', students.length, 'students');
    
    var tbody = $('#attendanceTableBody');
    tbody.empty();
    
    if (!students || students.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center text-muted"><p class="mt-2">No students found</p></td></tr>');
        return;
    }
    
    $.each(students, function(index, student) {
        var serialNo = ((currentPage - 1) * perPage) + index + 1;
        
        var row = '<tr>' +
            '<td>' + serialNo + '</td>' +
            '<td>' + student.name + '</td>' +
            '<td>' + student.present_count + '</td>' +
            '<td>' + student.absent_count + '</td>' +
            '<td>' + student.total_working_days + '</td>' +
            '<td><button class="action-btn view-details-btn" data-student-id="' + student._id + '" data-student-name="' + student.name + '">' +
            '<i class="fas fa-eye"></i>View</button></td>' +
            '</tr>';
        
        tbody.append(row);
    });
    
    $('.view-details-btn').on('click', function() {
        var studentId = $(this).data('student-id');
        var studentName = $(this).data('student-name');
        showStudentDetails(studentId, studentName);
    });
    
    console.log('  Table updated');
}

function showStudentDetails(studentId, studentName) {
    console.log('👁️ Viewing details for:', studentId);
    
    $('#modalStudentName').text(studentName + ' - Attendance Information');
    $('#attendanceModal').fadeIn(300);
    
    var modalBody = $('#modalTableBody');
    modalBody.html('<tr><td colspan="3" class="loading-cell"><div class="spinner"></div><p>Loading attendance details...</p></td></tr>');
    
    $.ajax({
        url: '{{ route("attendance.student.monthly.details") }}',
        method: 'GET',
        data: {
            student_id: studentId,
            month: currentFilters.month
        },
        success: function(response) {
            console.log('  Details loaded:', response);
            
            if (response.success && response.data.length > 0) {
                renderDetailsTable(response.data);
            } else {
                modalBody.html('<tr><td colspan="3" class="text-center text-muted"><p class="mt-2">No attendance records found for this month</p></td></tr>');
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error loading details:', error);
            modalBody.html('<tr><td colspan="3" class="text-center text-danger"><p class="mt-2">Error loading attendance details</p></td></tr>');
        }
    });
}

function renderDetailsTable(records) {
    var modalBody = $('#modalTableBody');
    modalBody.empty();
    
    $.each(records, function(index, record) {
        var statusBadge = '';
        var dayClass = '';
        
        if (record.status === 'Present') {
            statusBadge = '<span class="status-badge-present">P</span>';
        } else if (record.status === 'Absent') {
            statusBadge = '<span class="status-badge-absent">A</span>';
        } else {
            statusBadge = '<span class="status-badge-holiday">N</span>';
        }
        
        var dayName = record.day || getDayFromDate(record.date);
        if (dayName === 'Saturday' || dayName === 'Sunday') {
            dayClass = 'weekend-day';
        }
        
        var row = '<tr>' +
            '<td>' + record.date + '</td>' +
            '<td><span class="day-badge ' + dayClass + '">' + dayName + '</span></td>' +
            '<td>' + statusBadge + '</td>' +
            '</tr>';
        
        modalBody.append(row);
    });
}

function getDayFromDate(dateStr) {
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var date = new Date(dateStr);
    return days[date.getDay()];
}

function closeModal() {
    $('#attendanceModal').fadeOut(300);
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
    loadMonthlyData();
}
</script>
</body>
</html>