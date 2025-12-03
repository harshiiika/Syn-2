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
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.page-title {
  color: #ed5b00;
  font-size: 28px;
  font-weight: 700;
  margin: 0;
}

.tab-container {
  display: flex;
  gap: 10px;
  margin-bottom: 25px;
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
}

.tab-btn.active {
  background: #ed5b00;
  color: white;
}

.filter-card {
  background: white;
  border-radius: 10px;
  padding: 30px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  margin-bottom: 25px;
}

.filter-title {
  color: #333;
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 20px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filter-label {
  color: #555;
  font-size: 14px;
  font-weight: 500;
}

.filter-label span {
  color: #dc3545;
}

.filter-select,
.filter-date {
  padding: 10px 16px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  background: white;
  transition: all 0.3s;
}

.filter-select:focus,
.filter-date:focus {
  outline: none;
  border-color: #ed5b00;
  box-shadow: 0 0 0 3px rgba(237, 91, 0, 0.1);
}

.filter-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.btn-search,
.btn-reset {
  padding: 12px 30px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
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
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  margin-bottom: 25px;
}

.info-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
}

.info-title {
  color: #333;
  font-size: 18px;
  font-weight: 600;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.info-label {
  color: #666;
  font-size: 13px;
  font-weight: 500;
}

.info-value {
  color: #333;
  font-size: 15px;
  font-weight: 600;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 20px;
  margin-bottom: 25px;
}

.stat-box {
  background: white;
  border-radius: 10px;
  padding: 25px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  position: relative;
  overflow: hidden;
}

.stat-box::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #ed5b00, #ff7a33);
}

.stat-label {
  color: #666;
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 10px;
  text-transform: uppercase;
}

.stat-value {
  color: #ed5b00;
  font-size: 32px;
  font-weight: 700;
}

.stat-box.present::before {
  background: linear-gradient(90deg, #28a745, #20c997);
}

.stat-box.present .stat-value {
  color: #28a745;
}

.stat-box.absent::before {
  background: linear-gradient(90deg, #dc3545, #e83e8c);
}

.stat-box.absent .stat-value {
  color: #dc3545;
}

.stat-box.percentage::before {
  background: linear-gradient(90deg, #007bff, #6610f2);
}

.stat-box.percentage .stat-value {
  color: #007bff;
}

.attendance-table-card {
  background: white;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.table-title {
  color: #333;
  font-size: 18px;
  font-weight: 600;
}

.attendance-table {
  width: 100%;
  border-collapse: collapse;
}

.attendance-table thead th {
  background: #f8f9fa;
  color: #333;
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
}

.attendance-table tbody tr:hover {
  background: #f8f9fa;
}

.status-badge {
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 13px;
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
  font-size: 13px;
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
  padding: 60px 20px;
  color: #999;
}

.no-data i {
  font-size: 64px;
  margin-bottom: 20px;
  color: #ddd;
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
    <!-- Left Sidebar: Navigation menu with collapsible accordion sections -->
    <div class="left" id="sidebar">

      <div class="text" id="text">
        <h6>ADMIN</h6>
        <p>synthesisbikaner@gmail.com</p>
      </div>

      <!-- Left side bar accordian -->
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
                <li><a class="item" href="{{ route(name: 'attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Employee</a></li>
                <li><a class="item" href="{{ route(name: 'attendance.student.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Student</a></li>
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
                <li>
    <a href="{{ route('test_series.index') }}">
        <i class="icon-class"></i> 
        <span>Test Master</span>
    </a>
</li>              </ul>
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
        <h4 class="page-title"><i class="fas fa-chart-bar me-2"></i>Attendance Report</h4>
      </div>

      <div class="tab-container">
        <button type="button" class="tab-btn active">Student</button>
        <button type="button" class="tab-btn">Staff</button>
      </div>

      <!-- Filter Card -->
      <div class="filter-card">
        <h6 class="filter-title"><i class="fas fa-filter me-2"></i>Report Filters</h6>
        
        <div class="filter-grid">
          <div class="filter-group">
            <label class="filter-label">Course <span>*</span></label>
            <select class="filter-select" id="courseFilter">
              <option value="">Select Course</option>
              @foreach($courses as $course)
                <option value="{{ $course->_id }}">{{ $course->name }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="filter-group">
            <label class="filter-label">Batch <span>*</span></label>
            <select class="filter-select" id="batchFilter" disabled>
              <option value="">Select Batch</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label class="filter-label">Roll Number <span>*</span></label>
            <select class="filter-select" id="rollNoFilter" disabled>
              <option value="">Select Roll No.</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label class="filter-label">Start Date <span>*</span></label>
            <input type="date" class="filter-date" id="startDateFilter">
          </div>
          
          <div class="filter-group">
            <label class="filter-label">End Date <span>*</span></label>
            <input type="date" class="filter-date" id="endDateFilter">
          </div>
        </div>
        
        <div class="filter-actions">
          <button class="btn-reset" id="resetBtn"><i class="fas fa-redo me-2"></i>Reset</button>
          <button class="btn-search" id="searchBtn"><i class="fas fa-search me-2"></i>Generate Report</button>
        </div>
      </div>

      <!-- Report Container (Initially Hidden) -->
      <div class="report-container" id="reportContainer">
        <!-- Student Info Card -->
        <div class="student-info-card">
          <div class="info-header">
            <h6 class="info-title"><i class="fas fa-user-circle me-2"></i>Student Information</h6>
          </div>
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">Roll Number</span>
              <span class="info-value" id="studentRollNo">-</span>
            </div>
            <div class="info-item">
              <span class="info-label">Student Name</span>
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
            <div class="info-item">
              <span class="info-label">Email</span>
              <span class="info-value" id="studentEmail">-</span>
            </div>
            <div class="info-item">
              <span class="info-label">Shift</span>
              <span class="info-value" id="studentShift">-</span>
            </div>
            <div class="info-item">
              <span class="info-label">Branch</span>
              <span class="info-value" id="studentBranch">-</span>
            </div>
          </div>
        </div>

        <!-- Statistics -->
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
            <div class="stat-label">Attendance %</div>
            <div class="stat-value" id="statPercentage">0%</div>
          </div>
        </div>

        <!-- Attendance Table -->
        <div class="attendance-table-card">
          <div class="table-header">
            <h6 class="table-title"><i class="fas fa-table me-2"></i>Attendance Details</h6>
          </div>
          
          <div class="table-wrapper">
            <table class="attendance-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Day</th>
                  <th>Status</th>
                  <th>Marked At</th>
                  <th>Marked By</th>
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
  </div>

  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
      <div class="spinner"></div>
      <p>Generating report...</p>
    </div>
  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('js/emp.js')}}"></script>

<script>
$(document).ready(function() {
    console.log('🚀 Attendance Report System Initialized');
    console.log('Available routes:', {
        batches: '{{ route("reports.attendance.student.batches") }}',
        rolls: '{{ route("reports.attendance.student.rolls") }}',
        data: '{{ route("reports.attendance.student.data") }}'
    });
    
    // Set default date range (current month)
    var today = new Date();
    var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    $('#startDateFilter').val(formatDate(firstDay));
    $('#endDateFilter').val(formatDate(lastDay));
    
    console.log('📅 Default dates set:', {
        start: formatDate(firstDay),
        end: formatDate(lastDay)
    });
    
    // Course change - load batches
    $('#courseFilter').on('change', function() {
        var courseId = $(this).val();
        var courseName = $(this).find('option:selected').text();
        
        console.log('🎓 Course selected:', {
            id: courseId,
            name: courseName
        });
        
        // Reset dependent dropdowns
        $('#batchFilter').prop('disabled', true).html('<option value="">Loading batches...</option>');
        $('#rollNoFilter').prop('disabled', true).html('<option value="">Select Roll No.</option>');
        
        if (!courseId) {
            console.log('⚠️ No course selected, resetting');
            $('#batchFilter').prop('disabled', false).html('<option value="">Select Batch</option>');
            return;
        }
        
        console.log('📡 Fetching batches for course:', courseId);
        
        $.ajax({
            url: '{{ route("reports.attendance.student.batches") }}',
            method: 'GET',
            data: { course_id: courseId },
            beforeSend: function(xhr) {
                console.log('📤 Request sent:', {
                    url: '{{ route("reports.attendance.student.batches") }}',
                    method: 'GET',
                    data: { course_id: courseId }
                });
            },
            success: function(response) {
                console.log('✅ Batches response received:', response);
                
                if (response.success && response.batches) {
                    console.log('📋 Processing', response.batches.length, 'batches');
                    
                    if (response.batches.length === 0) {
                        console.warn('⚠️ No batches found for this course');
                        $('#batchFilter').html('<option value="">No batches available</option>').prop('disabled', true);
                        alert('No batches found for the selected course. Please check if batches are assigned to this course.');
                        return;
                    }
                    
                    var options = '<option value="">Select Batch</option>';
                    
                    response.batches.forEach(function(batch, index) {
                        console.log('  Batch ' + (index + 1) + ':', {
                            id: batch._id,
                            batch_id: batch.batch_id,
                            name: batch.name
                        });
                        
                        var batchLabel = batch.batch_id || batch.name || 'Unknown';
                        options += '<option value="' + batch._id + '">' + batchLabel + '</option>';
                    });
                    
                    $('#batchFilter').html(options).prop('disabled', false);
                    console.log('✅ Batch dropdown populated with', response.batches.length, 'options');
                } else {
                    console.error('❌ Invalid response format:', response);
                    $('#batchFilter').html('<option value="">No batches found</option>').prop('disabled', true);
                    alert('Invalid response from server. Check console for details.');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error loading batches:', {
                    status: status,
                    error: error,
                    response: xhr.responseJSON,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                
                $('#batchFilter').html('<option value="">Error loading batches</option>').prop('disabled', true);
                
                var errorMsg = 'Failed to load batches';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += ': ' + xhr.responseJSON.message;
                }
                alert(errorMsg + '\n\nCheck console for details (Press F12)');
            }
        });
    });
    
    // Batch change - load students with roll numbers
    $('#batchFilter').on('change', function() {
        var batchId = $(this).val();
        var batchName = $(this).find('option:selected').text();
        var courseId = $('#courseFilter').val();
        
        console.log('📚 Batch selected:', {
            id: batchId,
            name: batchName,
            course_id: courseId
        });
        
        $('#rollNoFilter').prop('disabled', true).html('<option value="">Loading students...</option>');
        
        if (!batchId) {
            console.log('⚠️ No batch selected, resetting');
            $('#rollNoFilter').html('<option value="">Select Roll No.</option>');
            return;
        }
        
        console.log('📡 Fetching students for batch:', batchId);
        
        $.ajax({
            url: '{{ route("reports.attendance.student.rolls") }}',
            method: 'GET',
            data: { 
                batch_id: batchId,
                course_id: courseId
            },
            beforeSend: function(xhr) {
                console.log('📤 Request sent:', {
                    url: '{{ route("reports.attendance.student.rolls") }}',
                    method: 'GET',
                    data: { batch_id: batchId, course_id: courseId }
                });
            },
            success: function(response) {
                console.log('✅ Students response received:', response);
                
                if (response.success && response.students) {
                    console.log('👥 Processing', response.students.length, 'students');
                    
                    if (response.students.length === 0) {
                        console.warn('⚠️ No students found in this batch');
                        $('#rollNoFilter').html('<option value="">No students found</option>').prop('disabled', true);
                        alert('No students found in this batch. Please check if students are enrolled.');
                        return;
                    }
                    
                    var options = '<option value="">Select Roll No.</option>';
                    
                    // Sort students by roll number
                    response.students.sort(function(a, b) {
                        var rollA = a.roll_no || '';
                        var rollB = b.roll_no || '';
                        return rollA.localeCompare(rollB);
                    });
                    
                    response.students.forEach(function(student, index) {
                        console.log('  Student ' + (index + 1) + ':', {
                            roll_no: student.roll_no,
                            name: student.name
                        });
                        
                        var rollNo = student.roll_no || 'N/A';
                        var studentName = student.name || 'Unknown';
                        
                        options += '<option value="' + rollNo + '">' + 
                                  rollNo + ' - ' + studentName + 
                                  '</option>';
                    });
                    
                    $('#rollNoFilter').html(options).prop('disabled', false);
                    console.log('✅ Roll number dropdown populated with', response.students.length, 'options');
                } else {
                    console.error('❌ Invalid response format:', response);
                    $('#rollNoFilter').html('<option value="">No students found</option>').prop('disabled', true);
                    alert('Invalid response from server. Check console for details.');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error loading students:', {
                    status: status,
                    error: error,
                    response: xhr.responseJSON,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                
                $('#rollNoFilter').html('<option value="">Error loading students</option>').prop('disabled', true);
                
                var errorMsg = 'Failed to load students';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += ': ' + xhr.responseJSON.message;
                }
                console.error('Error details:', errorMsg);
            }
        });
    });
    
    // Search button
    $('#searchBtn').on('click', generateReport);
    
    // Reset button
    $('#resetBtn').on('click', function() {
        console.log('🔄 Resetting form');
        $('#courseFilter').val('');
        $('#batchFilter').prop('disabled', true).html('<option value="">Select Batch</option>');
        $('#rollNoFilter').prop('disabled', true).html('<option value="">Select Roll No.</option>');
        $('#startDateFilter').val(formatDate(firstDay));
        $('#endDateFilter').val(formatDate(lastDay));
        $('#reportContainer').hide();
        console.log('✅ Form reset complete');
    });
});

function generateReport() {
    var course = $('#courseFilter').val();
    var batch = $('#batchFilter').val();
    var rollNo = $('#rollNoFilter').val();
    var startDate = $('#startDateFilter').val();
    var endDate = $('#endDateFilter').val();
    
    console.log('📊 Generate Report clicked:', {
        course: course,
        batch: batch,
        rollNo: rollNo,
        startDate: startDate,
        endDate: endDate
    });
    
    // Validation
    if (!course || !batch || !rollNo || !startDate || !endDate) {
        console.warn('⚠️ Validation failed - missing fields');
        alert('Please fill all required fields');
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        console.warn('⚠️ Validation failed - invalid date range');
        alert('Start date cannot be after end date');
        return;
    }
    
    console.log('📡 Fetching attendance report...');
    $('#loadingOverlay').css('display', 'flex');
    
    $.ajax({
        url: '{{ route("reports.attendance.student.data") }}',
        method: 'GET',
        data: {
            course: course,
            batch: batch,
            roll_no: rollNo,
            start_date: startDate,
            end_date: endDate
        },
        beforeSend: function(xhr) {
            console.log('📤 Report request sent');
        },
        success: function(response) {
            console.log('✅ Report response received:', response);
            
            if (response.success) {
                console.log('📈 Displaying report');
                displayReport(response);
            } else {
                console.error('❌ Report generation failed:', response.message);
                alert(response.message || 'Failed to generate report');
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error generating report:', {
                status: status,
                error: error,
                response: xhr.responseJSON,
                responseText: xhr.responseText
            });
            
            var message = 'Failed to generate report';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message + '\n\nCheck console for details (Press F12)');
        },
        complete: function() {
            $('#loadingOverlay').hide();
        }
    });
}

function displayReport(data) {
    console.log('📊 Displaying report data');
    
    // Student info
    $('#studentRollNo').text(data.student.roll_no);
    $('#studentName').text(data.student.name);
    $('#studentBatch').text(data.student.batch_name);
    $('#studentCourse').text(data.student.course_name);
    $('#studentEmail').text(data.student.email);
    $('#studentShift').text(data.student.shift);
    $('#studentBranch').text(data.student.branch);
    
    // Statistics
    $('#statTotalDays').text(data.statistics.total_days);
    $('#statPresent').text(data.statistics.present);
    $('#statAbsent').text(data.statistics.absent);
    $('#statNotMarked').text(data.statistics.not_marked);
    $('#statPercentage').text(data.statistics.attendance_percentage + '%');
    
    console.log('📈 Statistics:', data.statistics);
    
    // Attendance table
    var tbody = $('#attendanceTableBody');
    tbody.empty();
    
    if (data.attendance_data.length === 0) {
        tbody.html('<tr><td colspan="5" class="no-data"><i class="fas fa-inbox"></i><p>No attendance data found for the selected date range</p></td></tr>');
    } else {
        console.log('📋 Populating', data.attendance_data.length, 'attendance records');
        
        data.attendance_data.forEach(function(record) {
            var statusBadge = getStatusBadge(record.status);
            var markedAt = record.marked_at ? formatDateTime(record.marked_at) : '-';
            var markedBy = record.marked_by || '-';
            
            var row = '<tr>' +
                '<td><strong>' + formatDateDisplay(record.date) + '</strong></td>' +
                '<td><span class="day-badge">' + record.day + '</span></td>' +
                '<td>' + statusBadge + '</td>' +
                '<td>' + markedAt + '</td>' +
                '<td>' + markedBy + '</td>' +
                '</tr>';
            
            tbody.append(row);
        });
    }
    
    $('#reportContainer').show();
    console.log('✅ Report displayed successfully');
    
    // Scroll to report
    $('html, body').animate({
        scrollTop: $('#reportContainer').offset().top - 100
    }, 500);
}

function getStatusBadge(status) {
    switch(status) {
        case 'present':
            return '<span class="status-badge status-present"><i class="fas fa-check-circle me-1"></i>Present</span>';
        case 'absent':
            return '<span class="status-badge status-absent"><i class="fas fa-times-circle me-1"></i>Absent</span>';
        default:
            return '<span class="status-badge status-not-marked"><i class="fas fa-question-circle me-1"></i>Not Marked</span>';
    }
}

function formatDate(date) {
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
}

function formatDateDisplay(dateStr) {
    var date = new Date(dateStr);
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
}

function formatDateTime(dateTimeStr) {
    var date = new Date(dateTimeStr);
    return formatDateDisplay(date.toISOString().split('T')[0]) + ' ' + 
           date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}
</script>
</body>
</html>