<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Student Attendance Report</title>
  
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

.page-header {
  margin-bottom: 20px;
}

.page-title {
  color: #333;
  font-size: 20px;
  font-weight: 600;
  margin: 0;
}

.tab-container {
  display: flex;
  gap: 0;
  margin-bottom: 25px;
  border: 1px solid #ddd;
  border-radius: 4px;
  overflow: hidden;
  width: fit-content;
}

.tab-btn {
  padding: 8px 24px;
  border: none;
  background: white;
  color: #333;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s;
  text-decoration: none;
  border-right: 1px solid #ddd;
}

.tab-btn:last-child {
  border-right: none;
}

.tab-btn.active {
  background: #ed5b00;
  color: white;
}

.tab-btn:hover:not(.active) {
  background: #f8f9fa;
}

.filter-card {
  background: white;
  border-radius: 6px;
  padding: 25px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  margin-bottom: 25px;
}

.filter-row {
  display: flex;
  gap: 15px;
  align-items: flex-end;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  flex: 1;
  min-width: 180px;
}

.filter-label {
  color: #333;
  font-size: 13px;
  font-weight: 500;
}

.filter-select,
.filter-date {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  background: white;
  transition: all 0.3s;
  height: 38px;
}

.filter-select:focus,
.filter-date:focus {
  outline: none;
  border-color: #ed5b00;
}

.filter-actions {
  display: flex;
  gap: 10px;
  align-items: flex-end;
}

/* Calendar Styles */
#calendarView {
  background: white;
  border-radius: 6px;
  padding: 5px;
}

.fc-header-toolbar {
  padding: 8px 10px !important;
  margin-bottom: 5px !important;
  background: #f8f9fa;
  border-radius: 4px;
}

.fc-toolbar-title {
  font-size: 13px !important;
  font-weight: 600 !important;
  color: #333 !important;
}

.fc .fc-button {
  background-color: #ed5b00 !important;
  border: none !important;
  color: white !important;
  font-size: 12px !important;
  padding: 4px 10px !important;
  border-radius: 4px !important;
  box-shadow: none !important;
}

.fc .fc-button:hover {
  background-color: #d54f00 !important;
}

.fc .fc-button:focus {
  box-shadow: none !important;
}

.fc-col-header-cell {
  background: #f8f9fa;
  border: 1px solid #e0e0e0 !important;
  padding: 5px 2px !important;
}

.fc-col-header-cell-cushion {
  font-size: 11px !important;
  font-weight: 600 !important;
  color: #666 !important;
  text-transform: uppercase;
}

.fc-daygrid-day {
  border: 1px solid #e0e0e0 !important;
  font-size: 12px !important;
  height: 35px !important;
}

.fc-daygrid-day-number {
  padding: 4px !important;
  font-size: 11px !important;
  color: #333 !important;
}

.fc-day-today {
  background-color: rgba(0, 123, 255, 0.1) !important;
}

.fc-day-today .fc-daygrid-day-number {
  background: #007bff;
  color: white !important;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
}

.fc-daygrid-day.fc-day-past {
  background-color: #fafafa;
}

.fc-scroller {
  overflow-y: hidden !important;
}
.btn-search,
.btn-reset {
  padding: 8px 20px;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s;
  height: 38px;
  white-space: nowrap;
}

.btn-search {
  background: #ed5b00;
  color: white;
}

.btn-search:hover {
  background: #d54f00;
}

.btn-reset {
  background: #6c757d;
  color: white;
}

.btn-reset:hover {
  background: #5a6268;
}

.report-container {
  display: none;
  animation: fadeIn 0.3s;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.student-info-card {
  background: white;
  border-radius: 6px;
  padding: 20px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  margin-bottom: 20px;
}

.info-header {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  padding-bottom: 12px;
  border-bottom: 1px solid #e9ecef;
}

.info-title {
  color: #333;
  font-size: 15px;
  font-weight: 600;
  margin: 0;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 15px;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.info-label {
  color: #666;
  font-size: 12px;
  font-weight: 500;
}

.info-value {
  color: #333;
  font-size: 14px;
  font-weight: 600;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 15px;
  margin-bottom: 20px;
}

.stat-box {
  background: white;
  border-radius: 6px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  border-top: 3px solid #ed5b00;
}

.stat-label {
  color: #666;
  font-size: 11px;
  font-weight: 500;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-value {
  color: #ed5b00;
  font-size: 28px;
  font-weight: 700;
}

.stat-box.present {
  border-top-color: #28a745;
}

.stat-box.present .stat-value {
  color: #28a745;
}

.stat-box.absent {
  border-top-color: #dc3545;
}

.stat-box.absent .stat-value {
  color: #dc3545;
}

.stat-box.percentage {
  border-top-color: #007bff;
}

.stat-box.percentage .stat-value {
  color: #007bff;
}

.attendance-table-card {
  background: white;
  border-radius: 6px;
  padding: 20px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.table-title {
  color: #333;
  font-size: 15px;
  font-weight: 600;
  margin: 0;
}

.attendance-table {
  width: 100%;
  border-collapse: collapse;
}

.attendance-table thead th {
  background: #f8f9fa;
  color: #333;
  font-weight: 600;
  font-size: 13px;
  padding: 12px;
  text-align: left;
  border-bottom: 2px solid #dee2e6;
}

.attendance-table tbody td {
  padding: 12px;
  font-size: 13px;
  color: #333;
  border-bottom: 1px solid #f0f0f0;
}

.attendance-table tbody tr:hover {
  background: #f8f9fa;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  display: inline-block;
}

.status-present {
  background: #d4edda;
  color: #155724;
}

.status-absent {
  background: #f8d7da;
  color: #721c24;
}

.status-not-marked {
  background: #fff3cd;
  color: #856404;
}

.day-badge {
  color: #666;
  font-size: 12px;
  font-weight: 500;
}

.loading-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  z-index: 9999;
  align-items: center;
  justify-content: center;
}

.loading-content {
  background: white;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #ed5b00;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 15px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.no-data {
  text-align: center;
  padding: 40px 20px;
  color: #999;
}

.no-data i {
  font-size: 48px;
  margin-bottom: 15px;
  color: #ddd;
}

.flatpickr-calendar {
  z-index: 9999;
  max-width: 300px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.flatpickr-prev-month svg,
.flatpickr-next-month svg {
  width: 14px;
  height: 14px;
  fill: #333;
}
#calendarView {
  background: white;
  border-radius: 6px;
  padding: 15px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
/* Compact day labels */
.fc-col-header-cell-cushion {
  font-size: 12px;
  font-weight: 600;
  color: #555;
  padding: 6px 0;
}

/* Date cell styling */
.fc-daygrid-day {
  border: 1px solid #eee;
  font-size: 11px;
  padding: 4px;
}

/* Highlight selected date */
.fc-daygrid-day.fc-day-today {
  background-color: #007bff !important;
  color: white !important;
  font-weight: bold;
  border-radius: 4px;
}

/* Navigation arrows */
.fc .fc-button {
  background-color: transparent;
  border: none;
  color: #333;
  font-size: 14px;
}

.fc .fc-button:hover {
  color: #ed5b00;
}

/* Title styling */
.fc-toolbar-title {
  font-size: 14px;
  font-weight: 600;
  color: #333;
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
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="toggle-btn">
        <li>
            <a class="dropdown-item" href="{{ route('profile.index') }}">
                <i class="fa-solid fa-user me-2"></i>Profile
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Log Out
                </button>
            </form>
        </li>
    </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <!-- Left Sidebar -->
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
          <li><a class="item" href="{{ route('smstudents.index') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
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
          <li><a class="item" href="{{ route(name: 'test_series.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
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
          <li><a class="item" href="{{ route('reports.walkin.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Walk In</a></li>
          <li><a class="item" href="{{ route('reports.attendance.student.index') }}"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Attendance</a></li>
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
  <div class="page-header">
    <h4 class="page-title">Attendance</h4>
  </div>

  <div class="tab-container">
    <button type="button" class="tab-btn active">Student</button>
    <button type="button" class="tab-btn">Staff</button>
  </div>

  <!-- Filter Card -->
  <div class="filter-card">
    <div class="filter-row">
      <div class="filter-group">
        <label class="filter-label">Course</label>
        <select class="filter-select" id="courseFilter">
          <option value="">Select Course</option>
          @foreach($courses as $course)
            <option value="{{ $course->_id }}">{{ $course->name }}</option>
          @endforeach
        </select>
      </div>
      
      <div class="filter-group">
        <label class="filter-label">Batch</label>
        <select class="filter-select" id="batchFilter" disabled>
          <option value="">Select Batch</option>
        </select>
      </div>
      
      <div class="filter-group">
        <label class="filter-label">Roll No.</label>
        <select class="filter-select" id="rollNoFilter" disabled>
          <option value="">Select Roll No.</option>
        </select>
      </div>
      
      <div class="filter-actions">
        <button class="btn-search" id="searchBtn">Search</button>
        <button class="btn-reset" id="resetBtn">Reset</button>
      </div>
    </div>
  </div>

  <div class="report-container" id="reportContainer">
    <!-- Student Info Card -->
    <div class="student-info-card">
      <div class="info-header">
        <h6 class="info-title">Student Information</h6>
      </div>
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Roll No.</span>
          <span class="info-value" id="studentRollNo">-</span>
        </div>
        <div class="info-item">
          <span class="info-label">Name</span>
          <span class="info-value" id="studentName">-</span>
        </div>
        <div class="info-item">
          <span class="info-label">Batch</span>
          <span class="info-value" id="studentBatch">-</span>
        </div>
        <div class="info-item">
          <span class="info-label">Course</span>
          <span class="info-value" id="studentCourse">-</span>
        </div>
      </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
      <div class="stat-box">
        <div class="stat-label">Total Days</div>
        <div class="stat-value" id="statTotalDays">0</div>
      </div>
      <div class="stat-box present">
        <div class="stat-label">Present</div>
        <div class="stat-value" id="statPresent">0</div>
      </div>
      <div class="stat-box absent">
        <div class="stat-label">Absent</div>
        <div class="stat-value" id="statAbsent">0</div>
      </div>
      <div class="stat-box">
        <div class="stat-label">Not Marked</div>
        <div class="stat-value" id="statNotMarked">0</div>
      </div>
      <div class="stat-box percentage">
        <div class="stat-label">Percentage</div>
        <div class="stat-value" id="statPercentage">0%</div>
      </div>
    </div>

    <!-- Charts + Calendar Row -->
    <div class="row g-4 mb-4" id="chartRow">
      <div class="col-lg-3">
        <div class="card shadow-sm p-3" style="height: 380px;">
          <h6 class="text-center mb-2" style="font-size: 14px;">Calendar</h6>
          <div id="calendarView" style="font-size: 11px;"></div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card shadow-sm p-3" style="height: 380px;">
          <h6 class="text-center mb-2" style="font-size: 14px;">Attendance Status</h6>
          <canvas id="attendancePieChart" style="height: 320px;"></canvas>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card shadow-sm p-3" style="height: 380px;">
          <h6 class="text-center mb-2" style="font-size: 14px;">Avg. Attendance Rate</h6>
          <canvas id="monthlyAttendanceChart" style="height: 320px;"></canvas>
        </div>
      </div>
    </div>

    <!-- Attendance Table -->
    <div class="attendance-table-card">
      <div class="table-header">
        <h6 class="table-title">Attendance Details</h6>
      </div>
      
      <div class="table-wrapper">
        <table class="attendance-table">
          <thead>
            <tr>
              <th>Serial No.</th>
              <th>Month</th>
              <th>Total Days</th>
              <th>Total Attendance</th>
              <th>Total Absent</th>
            </tr>
          </thead>
          <tbody id="attendanceTableBody">
            <tr>
              <td colspan="5" class="no-data">
                <i class="fas fa-inbox"></i>
                <p>No data available</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-content">
    <div class="spinner"></div>
    <p>Loading...</p>
  </div>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="{{asset('js/emp.js')}}"></script>

<script>
// Global variables
var globalCalendar;
var fullAttendanceData = [];
var currentMonthData = {};

// Initialize Calendar
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendarView');
  globalCalendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev',
      center: 'title',
      right: 'next'
    },
    height: 340,
    fixedWeekCount: false,
    selectable: false,
    dayMaxEvents: false,
    events: [],
    datesSet: function(info) {
      // When month changes, update the charts with that month's data
      if (fullAttendanceData.length > 0) {
        updateChartsForMonth(info.start);
      }
    },
    dayCellDidMount: function(info) {
      const today = new Date().toISOString().split('T')[0];
      if (info.date.toISOString().split('T')[0] === today) {
        info.el.classList.add('fc-day-today');
      }
    }
  });
  globalCalendar.render();
});

function updateCalendar(attendanceData) {
    if (!globalCalendar) return;
    
    // Store full data globally
    fullAttendanceData = attendanceData;
    
    // Remove all existing events
    globalCalendar.removeAllEvents();
    
    // Add attendance events
    attendanceData.forEach(function(record) {
        var color = '#ffc107'; // Not marked - yellow
        if (record.status === 'present') {
            color = '#28a745'; // Present - green
        } else if (record.status === 'absent') {
            color = '#dc3545'; // Absent - red
        }
        
        globalCalendar.addEvent({
            title: '',
            start: record.date,
            display: 'background',
            backgroundColor: color,
            borderColor: color
        });
    });
    
    // Update charts for current visible month
    var currentDate = globalCalendar.getDate();
    updateChartsForMonth(currentDate);
}

function updateChartsForMonth(date) {
    if (!fullAttendanceData || fullAttendanceData.length === 0) return;
    
    var targetMonth = date.getMonth();
    var targetYear = date.getFullYear();
    
    // Filter attendance data for the current month
    var monthData = fullAttendanceData.filter(function(record) {
        var recordDate = new Date(record.date);
        return recordDate.getMonth() === targetMonth && recordDate.getFullYear() === targetYear;
    });
    
    // Calculate statistics for the month
    var monthStats = {
        total_days: monthData.length,
        present: 0,
        absent: 0,
        not_marked: 0
    };
    
    monthData.forEach(function(record) {
        if (record.status === 'present') {
            monthStats.present++;
        } else if (record.status === 'absent') {
            monthStats.absent++;
        } else {
            monthStats.not_marked++;
        }
    });
    
    // Calculate percentage
    var percentage = monthStats.total_days > 0 
        ? ((monthStats.present / monthStats.total_days) * 100).toFixed(2) 
        : 0;
    
    // Update statistics display
    $('#statTotalDays').text(monthStats.total_days);
    $('#statPresent').text(monthStats.present);
    $('#statAbsent').text(monthStats.absent);
    $('#statNotMarked').text(monthStats.not_marked);
    $('#statPercentage').text(percentage + '%');
    
    // Update the pie chart with month-specific data
    if (window.attendancePieChart instanceof Chart) {
        window.attendancePieChart.data.datasets[0].data = [
            monthStats.present,
            monthStats.absent,
            monthStats.not_marked
        ];
        window.attendancePieChart.update();
    }
}

$(document).ready(function() {
    console.log('  Attendance Report System Initialized');
    
    // Course change - load batches
    $('#courseFilter').on('change', function() {
        var courseId = $(this).val();
        
        $('#batchFilter').prop('disabled', true).html('<option value="">Loading...</option>');
        $('#rollNoFilter').prop('disabled', true).html('<option value="">Select Roll No.</option>');
        
        if (!courseId) {
            $('#batchFilter').prop('disabled', false).html('<option value="">Select Batch</option>');
            return;
        }
        
        // AJAX call to load batches
        $.ajax({
            url: '/reports/attendance/student/batches',
            method: 'GET',
            data: { course_id: courseId },
            success: function(response) {
                if (response.success) {
                    if (response.batches.length === 0) {
                        $('#batchFilter')
                            .html('<option value="">No batches available</option>')
                            .prop('disabled', true);
                        return;
                    }
                    
                    var options = '<option value="">Select Batch</option>';
                    response.batches.forEach(function(batch) {
                        options += '<option value="' + batch.batch_id + '">' + batch.name + '</option>';
                    });
                    
                    $('#batchFilter').html(options).prop('disabled', false);
                }
            },
            error: function() {
                $('#batchFilter')
                    .html('<option value="">Error loading batches</option>')
                    .prop('disabled', true);
            }
        });
    });

    // Batch change - load students
    $('#batchFilter').on('change', function() {
        var batchId = $(this).val();
        var courseId = $('#courseFilter').val();
        
        $('#rollNoFilter').prop('disabled', true).html('<option value="">Loading...</option>');
        
        if (!batchId) {
            $('#rollNoFilter').html('<option value="">Select Roll No.</option>');
            return;
        }
        
        $.ajax({
            url: '/reports/attendance/student/rolls',
            method: 'GET',
            data: { 
                batch_id: batchId,
                course_id: courseId
            },
            success: function(response) {
                if (response.success && response.students) {
                    if (response.students.length === 0) {
                        $('#rollNoFilter')
                            .html('<option value="">No students found</option>')
                            .prop('disabled', true);
                        return;
                    }
                    
                    var options = '<option value="">Select Roll No.</option>';
                    response.students.forEach(function(student) {
                        options += '<option value="' + student.roll_no + '">' + 
                                  student.roll_no + ' - ' + student.name + '</option>';
                    });
                    
                    $('#rollNoFilter').html(options).prop('disabled', false);
                }
            },
            error: function() {
                $('#rollNoFilter')
                    .html('<option value="">Error loading students</option>')
                    .prop('disabled', true);
            }
        });
    });
    
    // Search button
    $('#searchBtn').on('click', generateReport);
    
    // Reset button
    $('#resetBtn').on('click', function() {
        $('#courseFilter').val('');
        $('#batchFilter').prop('disabled', true).html('<option value="">Select Batch</option>');
        $('#rollNoFilter').prop('disabled', true).html('<option value="">Select Roll No.</option>');
        $('#reportContainer').hide();
    });
});

function generateReport() {
    var course = $('#courseFilter').val();
    var batch = $('#batchFilter').val();
    var rollNo = $('#rollNoFilter').val();
    
    if (!course || !batch || !rollNo) {
        alert('Please fill all required fields');
        return;
    }
    
    var today = new Date();
    var firstDay = new Date(today.getFullYear(), 0, 1);
    var lastDay = new Date(today.getFullYear(), 11, 31);
    var startDate = formatDate(firstDay);
    var endDate = formatDate(lastDay);
    
    $('#loadingOverlay').css('display', 'flex');
    
    $.ajax({
        url: '/reports/attendance/student/data',
        method: 'GET',
        data: {
            course: course,
            batch: batch,
            roll_no: rollNo,
            start_date: startDate,
            end_date: endDate
        },
        success: function(response) {
            if (response.success) {
                displayReport(response);
            } else {
                alert(response.message || 'Failed to generate report');
            }
        },
        error: function(xhr) {
            var message = 'Failed to generate report';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        },
        complete: function() {
            $('#loadingOverlay').hide();
        }
    });
}

function displayReport(data) {
    // Student info
    $('#studentRollNo').text(data.student.roll_no);
    $('#studentName').text(data.student.name);
    $('#studentBatch').text(data.student.batch_name);
    $('#studentCourse').text(data.student.course_name);
    
    // Render charts with attendance data
    renderCharts(data.statistics, data.attendance_data);

    // Statistics - will be updated by month navigation
    $('#statTotalDays').text(data.statistics.total_days);
    $('#statPresent').text(data.statistics.present);
    $('#statAbsent').text(data.statistics.absent);
    $('#statNotMarked').text(data.statistics.not_marked);
    $('#statPercentage').text(data.statistics.attendance_percentage + '%');
    
    // Attendance table - Month-wise grouping
    var tbody = $('#attendanceTableBody');
    tbody.empty();
    
    if (data.attendance_data.length === 0) {
        tbody.html('<tr><td colspan="5" class="no-data"><i class="fas fa-inbox"></i><p>No attendance data found</p></td></tr>');
    } else {
        var monthlyData = {};
        var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                          'July', 'August', 'September', 'October', 'November', 'December'];
        
        data.attendance_data.forEach(function(record) {
            var date = new Date(record.date);
            var monthKey = date.getFullYear() + '-' + String(date.getMonth()).padStart(2, '0');
            var monthName = monthNames[date.getMonth()];
            
            if (!monthlyData[monthKey]) {
                monthlyData[monthKey] = {
                    month: monthName,
                    totalDays: 0,
                    present: 0,
                    absent: 0
                };
            }
            
            monthlyData[monthKey].totalDays++;
            if (record.status === 'present') {
                monthlyData[monthKey].present++;
            } else if (record.status === 'absent') {
                monthlyData[monthKey].absent++;
            }
        });
        
        var serialNo = 1;
        Object.keys(monthlyData).sort().forEach(function(monthKey) {
            var monthData = monthlyData[monthKey];
            
            var row = '<tr>' +
                '<td>' + serialNo + '</td>' +
                '<td><strong>' + monthData.month + '</strong></td>' +
                '<td>' + monthData.totalDays + '</td>' +
                '<td>' + monthData.present + '</td>' +
                '<td>' + monthData.absent + '</td>' +
                '</tr>';
            
            tbody.append(row);
            serialNo++;
        });
    }
    
    $('#reportContainer').show();
    
    $('html, body').animate({
        scrollTop: $('#reportContainer').offset().top - 100
    }, 500);
}

function formatDate(date) {
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
}

function renderCharts(statistics, attendanceData) {
    // Destroy existing charts
    if (window.attendancePieChart instanceof Chart) window.attendancePieChart.destroy();
    if (window.monthlyAttendanceChart instanceof Chart) window.monthlyAttendanceChart.destroy();

    const pieCtx = document.getElementById('attendancePieChart').getContext('2d');
    const barCtx = document.getElementById('monthlyAttendanceChart').getContext('2d');

    // Pie Chart - Attendance Distribution
    window.attendancePieChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent', 'Not Marked'],
            datasets: [{
                data: [statistics.present, statistics.absent, statistics.not_marked],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        font: {
                            size: 10
                        },
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toFixed(2) + '%';
                        }
                    }
                }
            }
        }
    });
    
    // Update calendar with attendance events
    updateCalendar(attendanceData);
}
globalCalendar = new FullCalendar.Calendar(calendarEl, {
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next',
    center: 'title',
    right: ''
  },
  height: 'auto',
  selectable: false,
  dayMaxEvents: false,
  events: [], // dynamically added
  dayCellDidMount: function(info) {
    const today = new Date().toISOString().split('T')[0];
    if (info.date.toISOString().split('T')[0] === today) {
      info.el.classList.add('fc-day-today');
    }
  }
});

</script>

</body>
</html>