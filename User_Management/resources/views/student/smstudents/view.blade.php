<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Student View Detail - {{ $student->student_name ?? $student->name }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/smstudents.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .page-header {
      background-color: rgba(0, 0, 0, 0);
      /* padding: 20px 30px; */
      margin: 20px;
      /* border-radius: 8px; */
      display: flex;
      justify-content: space-between;
      align-items: center;
      /* box-shadow: 0 2px 4px rgba(0,0,0,0.1); */
      width: 80%;
    }
    .page-title {
      color: #e05301;
      font-size: 24px;
      font-weight: 600;
      margin: 0;
    }
    .back-link {
      color: #e05301;
      text-decoration: none;
      font-size: 16px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .back-link:hover {
      color: #c04501;
    }
    .tab-container {
      background-color: #ffffff;
      margin: 0 20px 20px 20px;
      border-radius: 8px;
      padding: 20px;
      width: 80%;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .tab-navigation {
      border-bottom: 2px solid #dee2e6;
      margin-bottom: 30px;
      display: flex;
      gap: 10px;
    }
    .tab-btn {
      background: none;
      border: none;
      padding: 12px 24px;
      font-size: 15px;
      color: #666;
      cursor: pointer;
      border-bottom: 3px solid transparent;
      transition: all 0.3s;
    }
    .tab-btn.active {
      color: #ffffff;
      background-color: #e05301;
      border-radius: 5px 5px 0 0;
      font-weight: 600;
    }
    .tab-btn:hover:not(.active) {
      color: #e05301;
    }
    .tab-content-section {
      display: none;
    }
    .tab-content-section.active {
      display: block;
    }
    
    /* Student Detail Tab Styles */
    .student-avatar {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      background-color: #f0f0f0;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      border: 3px solid #e05301;
    }
    .student-avatar i {
      font-size: 80px;
      color: #666;
    }
    .roll-number {
      text-align: center;
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 30px;
    }
    .detail-section {
      margin-bottom: 30px;
    }
    .detail-section h5 {
      color: #e05301;
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #e05301;
    }
    .detail-row {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-bottom: 15px;
    }
    .detail-item {
      display: flex;
      flex-direction: column;
    }
    .detail-label {
      font-size: 13px;
      color: #666;
      font-weight: 500;
      margin-bottom: 5px;
    }
    .detail-value {
      font-size: 15px;
      color: #333;
      font-weight: 400;
    }
    .info-box {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .info-box-question {
      font-size: 14px;
      color: #333;
      margin-bottom: 5px;
    }
    .info-box-answer {
      font-size: 15px;
      font-weight: 500;
      color: #e05301;
    }
    .document-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 15px;
      background-color: #f8f9fa;
      border-radius: 5px;
      margin-bottom: 10px;
    }
    .document-name {
      font-size: 14px;
      color: #333;
    }
    .document-status {
      font-size: 13px;
      color: #dc3545;
      font-weight: 500;
    }
    
    /* Attendance Tab Styles */
    .attendance-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }
    .attendance-card {
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 20px;
    }
    .calendar-widget {
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 15px;
    }
    .attendance-status-circle {
      width: 200px;
      height: 200px;
      margin: 0 auto;
    }
    .chart-container {
      position: relative;
      height: 250px;
      margin: 20px 0;
    }
    
    /* Fees Management Tab Styles */
    .fees-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }
    .fees-tab-btn {
      padding: 10px 20px;
      border: 1px solid #e05301;
      background-color: #ffffff;
      color: #e05301;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
    }
    .fees-tab-btn.active {
      background-color: #e05301;
      color: #ffffff;
    }
    .scholarship-box {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    
    /* Test Series Tab Styles */
    .test-type-btns {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }
    .test-type-btn {
      padding: 10px 20px;
      border: 1px solid #e05301;
      background-color: #ffffff;
      color: #e05301;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
    }
    .test-type-btn.active {
      background-color: #e05301;
      color: #ffffff;
    }
    .test-stats-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }
    .stats-card {
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 20px;
    }

    /* Test Series Tab Styles */
.test-type-section {
  margin-bottom: 30px;
}

.test-type-buttons {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
  border-bottom: 2px solid #dee2e6;
  padding-bottom: 10px;
}

.test-type-btn {
  padding: 10px 20px;
  border: 1px solid #e05301;
  background-color: #ffffff;
  color: #e05301;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.3s;
}

.test-type-btn.active {
  background-color: #e05301;
  color: #ffffff;
}

.test-type-btn:hover:not(.active) {
  background-color: #fff5f0;
}

.test-type-content {
  display: none;
}

.test-type-content.active {
  display: block;
}

.pattern-buttons {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.pattern-btn {
  padding: 8px 16px;
  border: 1px solid #e05301;
  background-color: #ffffff;
  color: #e05301;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.3s;
}

.pattern-btn.active {
  background-color: #e05301;
  color: #ffffff;
}

.download-report-btn {
  float: right;
  padding: 10px 20px;
  background-color: #e05301;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 500;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.download-report-btn:hover {
  background-color: #c04501;
  color: white;
}

/* Report Card Styles */
.report-card {
  background: white;
  border: 2px solid #e05301;
  border-radius: 8px;
  padding: 30px;
  margin-top: 20px;
}

.report-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 20px;
}

.institute-logo {
  width: 80px;
  height: 80px;
}

.institute-name {
  text-align: center;
}

.institute-name img {
  max-width: 400px;
  height: auto;
}

.student-info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 30px;
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 5px;
}

.info-item {
  display: flex;
  gap: 10px;
}

.info-label {
  font-weight: 600;
  color: #333;
}

.info-value {
  color: #666;
}

.test-table {
  width: 100%;
  margin-top: 20px;
  border-collapse: collapse;
}

.test-table th {
  background-color: #808080;
  color: white;
  padding: 12px;
  text-align: center;
  font-weight: 600;
  border: 1px solid #666;
}

.test-table td {
  padding: 10px;
  text-align: center;
  border: 1px solid #ddd;
}

.test-table tbody tr:nth-child(even) {
  background-color: #f8f9fa;
}

.statistics-row {
  background-color: #e8e8e8;
  font-weight: 600;
}

.statistics-row td {
  padding: 15px 10px;
  border: 1px solid #999;
}

.signature-section {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-top: 50px;
  padding-top: 30px;
  border-top: 1px solid #ddd;
}

.signature-box {
  text-align: center;
}

.signature-line {
  border-top: 1px solid #333;
  margin-top: 40px;
  padding-top: 5px;
  font-size: 14px;
  color: #666;
}

/* Stats Cards for Type 1 */
.test-stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 30px;
}

.stats-card h6 {
  color: #e05301;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 15px;
}

.attendance-donut {
  max-width: 200px;
  margin: 0 auto;
}

.type-content {
  display: none;
}

.type-content:first-child {
  display: block;
}

@media print {
  .header, .left, .page-header, .tab-navigation, 
  .test-type-buttons, .pattern-buttons, .download-report-btn,
  .back-link {
    display: none !important;
  }
  
  .right {
    margin: 0 !important;
    padding: 0 !important;
  }
  
  .tab-container {
    width: 100% !important;
    margin: 0 !important;
    box-shadow: none !important;
  }

  .documents-grid {
  display: grid;
  gap: 15px;
}

.document-item {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 15px;
  background: #fff;
  transition: all 0.3s ease;
}

.document-item:hover {
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  border-color: #e05301;
}

.document-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 12px;
  font-weight: 600;
  color: #333;
}

.document-header i {
  color: #e05301;
  font-size: 18px;
}

.document-name {
  flex: 1;
  font-size: 14px;
}

.document-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  align-items: center;
}

.document-status {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 13px;
  padding: 4px 10px;
  border-radius: 4px;
  font-weight: 500;
}

.document-status.text-success {
  background: #d4edda;
  color: #155724;
}

.document-status.text-danger {
  background: #f8d7da;
  color: #721c24;
}

.document-status.text-warning {
  background: #fff3cd;
  color: #856404;
}

.btn-sm {
  padding: 5px 12px;
  font-size: 13px;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-primary {
  background: #e05301;
  color: white;
}

.btn-primary:hover {
  background: #c04701;
}

.btn-success {
  background: #28a745;
  color: white;
}

.btn-success:hover {
  background: #218838;
}
}

  </style>
</head>

<body>
  <div class="flash-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  <div class="header">
    <div class="logo">
      <img src="{{ asset('images/logo.png.jpg') }}" class="img">
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
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <div class="left" id="sidebar">
      <div class="text" id="text">
        <h6>Admin</h6>
        <p>synthesisbikaner@gmail.com</p>
      </div>

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
           <li><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i> Employee</a></li>     
           <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group" id="side-icon"></i> Batches Assignment</a></li>
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
                <li>><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Inquiry Management</a></li>
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
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Student</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Employee</a></li>
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
      <!-- Page Header -->
      <div class="page-header">
        <h1 class="page-title">Student View Detail</h1>
        <a href="{{ route('smstudents.index') }}" class="back-link">
          <i class="fas fa-arrow-left"></i> Back
        </a>
      </div>

      <!-- Tab Container -->
      <div class="tab-container">
        <!-- Tab Navigation -->
        <div class="tab-navigation">
          <button class="tab-btn active" data-tab="student-detail">Student Detail</button>
          <button class="tab-btn" data-tab="student-attendance">Student attendance</button>
          <button class="tab-btn" data-tab="fees-management">Fees management</button>
          <button class="tab-btn" data-tab="test-series">Test Series</button>
        </div>

     <!-- COMPLETE FIXED TAB 1 - Replace your entire Tab 1 section -->
<!-- File: resources/views/student/smstudents/view.blade.php -->

<div class="tab-content-section active" id="student-detail">
  
  <!-- Student Avatar and Roll Number -->
  <div class="student-avatar">
    <i class="fas fa-user-circle"></i>
  </div>
  <div class="roll-number">
    Roll Number<br>
    <strong>{{ $student->roll_no ?? 'Not Assigned' }}</strong>
  </div>

  <!-- ========== PERSONAL INFORMATION ========== -->
  <div class="detail-section">
    <h5><i class="fas fa-user"></i> Personal Information</h5>
    
    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Student Name</span>
        <span class="detail-value">
          {{ $student->student_name ?? $student->name ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Father Name</span>
        <span class="detail-value">
          {{ $student->father_name ?? $student->father ?? 'N/A' }}
        </span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Mother Name</span>
        <span class="detail-value">
          {{ $student->mother_name ?? $student->mother ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">DOB</span>
        <span class="detail-value">
          @php
            $dob = $student->dob ?? null;
            if ($dob && $dob !== 'N/A') {
              try {
                if (is_string($dob)) {
                  echo \Carbon\Carbon::parse($dob)->format('d-m-Y');
                } elseif ($dob instanceof \Carbon\Carbon) {
                  echo $dob->format('d-m-Y');
                } elseif ($dob instanceof \MongoDB\BSON\UTCDateTime) {
                  echo $dob->toDateTime()->format('d-m-Y');
                } else {
                  echo 'N/A';
                }
              } catch (\Exception $e) {
                echo 'N/A';
              }
            } else {
              echo 'N/A';
            }
          @endphp
        </span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Father Contact No</span>
        <span class="detail-value">
          {{ $student->father_contact ?? $student->mobileNumber ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Father Whatsapp No</span>
        <span class="detail-value">
          {{ $student->father_whatsapp ?? $student->fatherWhatsapp ?? 'N/A' }}
        </span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Mother Contact No</span>
        <span class="detail-value">
          {{ $student->mother_contact ?? $student->motherContact ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Student Contact No</span>
        <span class="detail-value">
          {{ $student->phone ?? $student->studentContact ?? 'N/A' }}
        </span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Category</span>
        <span class="detail-value">
          {{ $student->category ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Gender</span>
        <span class="detail-value">
          {{ $student->gender ?? 'N/A' }}
        </span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Father Occupation</span>
        <span class="detail-value">
          {{ $student->father_occupation ?? $student->fatherOccupation ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Mother Occupation</span>
        <span class="detail-value">
          {{ $student->mother_occupation ?? $student->motherOccupation ?? 'N/A' }}
        </span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">State</span>
        <span class="detail-value">{{ $student->state ?? 'N/A' }}</span>
      </div>
      <div class="detail-item">
        <span class="detail-label">City</span>
        <span class="detail-value">{{ $student->city ?? 'N/A' }}</span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">PinCode</span>
        <span class="detail-value">
          {{ $student->pincode ?? $student->pinCode ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Address</span>
        <span class="detail-value">{{ $student->address ?? 'N/A' }}</span>
      </div>
    </div>
  </div>

  <!-- ========== ADDITIONAL INFORMATION ========== -->
  <div class="detail-section">
    <h5><i class="fas fa-info-circle"></i> Additional Information</h5>
    
    <div class="info-box">
      <div class="info-box-question">
        <i class="fas fa-map-marker-alt"></i> Do you belong to another city?
      </div>
      <div class="info-box-answer">
        {{ $student->belongs_other_city ?? $student->belongToOtherCity ?? 'No' }}
      </div>
    </div>

    <div class="info-box">
      <div class="info-box-question">
        <i class="fas fa-hand-holding-usd"></i> Do You Belong to Economic Weaker Section?
      </div>
      <div class="info-box-answer">
        {{ $student->economic_weaker_section ?? $student->economicWeakerSection ?? 'No' }}
      </div>
    </div>

    <div class="info-box">
      <div class="info-box-question">
        <i class="fas fa-shield-alt"></i> Do You Belong to Any Army/Police/Martyr Background?
      </div>
      <div class="info-box-answer">
        {{ $student->army_police_background ?? $student->armyPoliceBackground ?? 'No' }}
      </div>
    </div>

    <div class="info-box">
      <div class="info-box-question">
        <i class="fas fa-wheelchair"></i> Are You a Specially Abled?
      </div>
      <div class="info-box-answer">
        {{ $student->specially_abled ?? $student->speciallyAbled ?? 'No' }}
      </div>
    </div>
  </div>

  <!-- ========== COURSE DETAIL ========== -->
  <div class="detail-section">
    <h5><i class="fas fa-book"></i> Course Detail</h5>
    
    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Course Type</span>
        <span class="detail-value">
          {{ $student->course_type ?? $student->courseType ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Course Name</span>
        <span class="detail-value">
          {{ $student->course_name ?? $student->courseName ?? ($student->course->name ?? 'N/A') }}
        </span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Delivery Mode</span>
        <span class="detail-value">
          {{ $student->delivery_mode ?? $student->deliveryMode ?? $student->delivery ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Medium</span>
        <span class="detail-value">{{ $student->medium ?? 'N/A' }}</span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Board</span>
        <span class="detail-value">{{ $student->board ?? 'N/A' }}</span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Course Content</span>
        <span class="detail-value">
          {{ $student->course_content ?? $student->courseContent ?? 'N/A' }}
        </span>
      </div>
    </div>
  </div>

  <!-- ========== ACADEMIC DETAIL ========== -->
  <div class="detail-section">
    <h5><i class="fas fa-graduation-cap"></i> Academic Detail</h5>
    
    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Previous Class</span>
        <span class="detail-value">
          {{ $student->previous_class ?? $student->previousClass ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Medium</span>
        <span class="detail-value">
          {{ $student->academic_medium ?? $student->previousMedium ?? 'N/A' }}
        </span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Name of School</span>
        <span class="detail-value">
          {{ $student->school_name ?? $student->schoolName ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Board</span>
        <span class="detail-value">
          {{ $student->academic_board ?? $student->previousBoard ?? 'N/A' }}
        </span>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Passing Year</span>
        <span class="detail-value">
          {{ $student->passing_year ?? $student->passingYear ?? 'N/A' }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Percentage</span>
        <span class="detail-value">
          @php
            $percentage = $student->percentage ?? null;
            if ($percentage && $percentage !== 'N/A') {
              try {
                echo number_format(floatval($percentage), 2) . '%';
              } catch (\Exception $e) {
                echo 'N/A';
              }
            } else {
              echo 'N/A';
            }
          @endphp
        </span>
      </div>
    </div>
  </div>

  <!-- ========== SCHOLARSHIP ELIGIBILITY ========== -->
  <div class="detail-section">
    <h5><i class="fas fa-award"></i> Scholarship Eligibility</h5>
    
    <div class="info-box">
      <div class="info-box-question">
        <i class="fas fa-certificate"></i> Have You Appeared For the Synthesis Scholarship test?
      </div>
      <div class="info-box-answer">
        {{ $student->scholarship_test ?? $student->scholarshipTest ?? 'No' }}
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Percentage Of Marks in Last Board Exam</span>
        <span class="detail-value">
          @php
            $boardPercentage = null;
            
            // Try ALL possible field names
            if (isset($student->board_percentage)) {
              $boardPercentage = $student->board_percentage;
            } elseif (isset($student->last_board_percentage)) {
              $boardPercentage = $student->last_board_percentage;
            } elseif (isset($student->lastBoardPercentage)) {
              $boardPercentage = $student->lastBoardPercentage;
            }
            
            if ($boardPercentage && $boardPercentage !== 'N/A') {
              try {
                echo number_format(floatval($boardPercentage), 2) . '%';
              } catch (\Exception $e) {
                echo 'N/A';
              }
            } else {
              echo 'N/A';
            }
          @endphp
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Is Repeater?</span>
        <span class="detail-value">
          {{ $student->is_repeater ?? $student->isRepeater ?? 'No' }}
        </span>
      </div>
    </div>

    <div class="info-box">
      <div class="info-box-question">
        <i class="fas fa-medal"></i> Have You Appeared For any of the competition exam?
      </div>
      <div class="info-box-answer">
        {{ $student->competition_exam ?? $student->competitionExam ?? 'No' }}
      </div>
    </div>
  </div>

  <!-- ========== BATCH ALLOCATION ========== -->
  <div class="detail-section">
    <h5><i class="fas fa-users"></i> Batch Allocation</h5>
    
    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-label">Batch Name</span>
        <span class="detail-value">
          {{ $student->batch_name ?? $student->batchName ?? ($student->batch->name ?? ($student->batch->batch_id ?? 'N/A')) }}
        </span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Shift</span>
        <span class="detail-value">
          {{ $student->shift->name ?? $student->shift ?? 'Not Assigned' }}
        </span>
      </div>
    </div>
  </div>

  <!-- ========== VIEW DOCUMENTS ========== -->
  <div class="detail-section">
    <h5><i class="fas fa-folder-open"></i> View Documents</h5>
    
    @php
      $documents = [
        ['field' => 'passport_photo', 'label' => 'Passport Size Photo', 'icon' => 'fa-id-card'],
        ['field' => 'marksheet', 'label' => 'Marksheet of Last Qualifying Exam', 'icon' => 'fa-file-alt'],
        ['field' => 'caste_certificate', 'label' => 'Caste Certificate', 'icon' => 'fa-certificate'],
        ['field' => 'scholarship_proof', 'label' => 'Scholarship Proof Document', 'icon' => 'fa-award'],
        ['field' => 'secondary_marksheet', 'label' => 'Secondary Board Marksheet', 'icon' => 'fa-graduation-cap'],
        ['field' => 'senior_secondary_marksheet', 'label' => 'Senior Secondary Board Marksheet', 'icon' => 'fa-graduation-cap'],
      ];
    @endphp
    
    <div class="documents-container">
      @foreach($documents as $doc)
        @php
          // Get document value from student
          $docValue = $student->{$doc['field']} ?? null;
          $hasDocument = !empty($docValue) && $docValue !== 'N/A' && $docValue !== null;
        @endphp
        
        <div class="document-card {{ $hasDocument ? 'has-document' : 'no-document' }}">
          <div class="document-icon">
            <i class="fas {{ $doc['icon'] }}"></i>
          </div>
          <div class="document-info">
            <div class="document-title">{{ $doc['label'] }}</div>
            
            @if($hasDocument)
              <div class="document-actions">
                @if(str_starts_with($docValue, 'data:'))
                  {{-- Base64 Document --}}
                  <button type="button" 
                          class="btn-doc btn-view" 
                          onclick="viewDocument('{{ $docValue }}', '{{ $doc['label'] }}')"
                          title="View Document">
                    <i class="fas fa-eye"></i> View
                  </button>
                  <a href="{{ $docValue }}" 
                     download="{{ $doc['label'] }}.{{ str_contains($docValue, 'pdf') ? 'pdf' : 'jpg' }}"
                     class="btn-doc btn-download"
                     title="Download Document">
                    <i class="fas fa-download"></i> Download
                  </a>
                @else
                  {{-- URL Path --}}
                  <a href="{{ asset($docValue) }}" 
                     target="_blank"
                     class="btn-doc btn-view"
                     title="View Document">
                    <i class="fas fa-eye"></i> View
                  </a>
                  <a href="{{ asset($docValue) }}" 
                     download
                     class="btn-doc btn-download"
                     title="Download Document">
                    <i class="fas fa-download"></i> Download
                  </a>
                @endif
                <span class="document-status uploaded">
                  <i class="fas fa-check-circle"></i> Uploaded
                </span>
              </div>
            @else
              <div class="document-status not-uploaded">
                <i class="fas fa-times-circle"></i> Not Uploaded
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>

</div>

<!-- ========== STYLES ========== -->
<style>
.detail-section {
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
}

.detail-section h5 {
  color: #e05301;
  font-weight: 600;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #e05301;
  display: flex;
  align-items: center;
  gap: 10px;
}

.detail-section h5 i {
  font-size: 20px;
}

.detail-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 15px;
}

.detail-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.detail-label {
  font-size: 13px;
  color: #666;
  font-weight: 500;
}

.detail-value {
  font-size: 15px;
  color: #333;
  font-weight: 600;
  padding: 8px 12px;
  background: #f8f9fa;
  border-radius: 4px;
  border-left: 3px solid #e05301;
}

.info-box {
  background: #f8f9fa;
  border-left: 4px solid #e05301;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 4px;
}

.info-box-question {
  font-size: 14px;
  color: #555;
  font-weight: 500;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.info-box-question i {
  color: #e05301;
}

.info-box-answer {
  font-size: 16px;
  color: #333;
  font-weight: 600;
  padding-left: 24px;
}

/* Documents Styling */
.documents-container {
  display: grid;
  gap: 15px;
}

.document-card {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.document-card.has-document {
  border-color: #28a745;
  background: #f0fff4;
}

.document-card.no-document {
  border-color: #dc3545;
  background: #fff5f5;
}

.document-card:hover {
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transform: translateY(-2px);
}

.document-icon {
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #e05301;
  color: white;
  border-radius: 50%;
  font-size: 24px;
  flex-shrink: 0;
}

.document-info {
  flex: 1;
}

.document-title {
  font-size: 14px;
  font-weight: 600;
  color: #333;
  margin-bottom: 8px;
}

.document-actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
}

.btn-doc {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 6px 12px;
  font-size: 13px;
  font-weight: 500;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s ease;
}

.btn-view {
  background: #e05301;
  color: white;
}

.btn-view:hover {
  background: #c04701;
  color: white;
}

.btn-download {
  background: #28a745;
  color: white;
}

.btn-download:hover {
  background: #218838;
  color: white;
}

.document-status {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.document-status.uploaded {
  background: #d4edda;
  color: #155724;
}

.document-status.not-uploaded {
  background: #f8d7da;
  color: #721c24;
}

@media (max-width: 768px) {
  .detail-row {
    grid-template-columns: 1fr;
  }
  
  .document-card {
    flex-direction: column;
    text-align: center;
  }
}
</style>


        <!-- TAB 2: Student Attendance -->
        <div class="tab-content-section" id="student-attendance">
          <div class="attendance-grid">
            <!-- Calendar Widget -->
            <div class="attendance-card">
              <h6 style="color: #e05301; margin-bottom: 15px;">OCT 2025</h6>
              <div class="calendar-widget">
                <table class="table table-sm text-center">
                  <thead>
                    <tr>
                      <th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td></td><td></td><td></td><td>1</td><td>2</td><td>3</td><td>4</td>
                    </tr>
                    <tr>
                      <td>5</td><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td><td>11</td>
                    </tr>
                    <tr>
                      <td>12</td><td>13</td><td>14</td><td>15</td><td>16</td><td>17</td><td>18</td>
                    </tr>
                    <tr>
                      <td>19</td><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td><td>25</td>
                    </tr>
                    <tr>
                      <td>26</td><td style="background-color: #e05301; color: white; border-radius: 50%;">27</td><td>28</td><td>29</td><td>30</td><td>31</td><td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Attendance Status Circle -->
            <div class="attendance-card">
              <h6 style="color: #e05301; margin-bottom: 15px;">Attendance Status</h6>
              <div class="attendance-status-circle">
                <canvas id="attendanceChart"></canvas>
              </div>
              <p class="text-center mt-3" style="color: #e05301;">Total Presence - 0/167</p>
            </div>

            <!-- Avg Attendance Rate Chart -->
            <div class="attendance-card">
              <h6 style="color: #e05301; margin-bottom: 15px;">Avg. Attendance Rate</h6>
              <select class="form-select form-select-sm mb-3" style="width: 120px;">
                <option>May-Oct</option>
              </select>
              <div class="chart-container">
                <canvas id="attendanceRateChart"></canvas>
              </div>
            </div>
          </div>

          <!-- Attendance Table -->
          <div class="detail-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <label>Show</label>
                <select class="form-select form-select-sm d-inline-block mx-2" style="width: 80px;">
                  <option>10</option>
                  <option>25</option>
                  <option>50</option>
                </select>
                <span>entries</span>
              </div>
              <div>
                <label>Search:</label>
                <input type="search" class="form-control form-control-sm d-inline-block ms-2" style="width: 200px;">
              </div>
            </div>

            <table class="table table-bordered">
              <thead>
                <tr style="background-color: #f8f9fa;">
                  <th>Serial No.</th>
                  <th>Month</th>
                  <th>Total Days</th>
                  <th>Total Attendance</th>
                  <th>Total Absent</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>1</td><td>May</td><td>18</td><td>0</td><td>0</td></tr>
                <tr><td>2</td><td>June</td><td>30</td><td>0</td><td>0</td></tr>
                <tr><td>3</td><td>July</td><td>31</td><td>0</td><td>0</td></tr>
                <tr><td>4</td><td>August</td><td>31</td><td>0</td><td>0</td></tr>
                <tr><td>5</td><td>September</td><td>30</td><td>0</td><td>0</td></tr>
                <tr><td>6</td><td>October</td><td>31</td><td>0</td><td>0</td></tr>
                <tr><td>7</td><td>November</td><td>30</td><td>0</td><td>0</td></tr>
                <tr><td>8</td><td>December</td><td>31</td><td>0</td><td>0</td></tr>
                <tr><td>9</td><td>January</td><td>31</td><td>0</td><td>0</td></tr>
                <tr><td>10</td><td>February</td><td>28</td><td>0</td><td>0</td></tr>
              </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center">
              <span>Showing 1 to 10 of 12 entries</span>
              <nav>
                <ul class="pagination pagination-sm mb-0">
                  <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
              </nav>
            </div>
          </div>
        </div>

<!-- TAB 3: Fees Management -->
<div class="tab-content-section" id="fees-management">
  <!-- Scholarship Info -->
  @php
    // Use scholarshipData if available, otherwise fall back to scholarshipEligible
    $eligible = isset($scholarshipData) ? $scholarshipData['eligible'] === 'Yes' : ($scholarshipEligible['eligible'] ?? false);
    $hasDiscretionary = isset($scholarshipData) ? $scholarshipData['has_discretionary'] : false;
  @endphp

  @if($eligible || $hasDiscretionary)
  <div class="scholarship-box">
    <h5 style="color: #28a745; margin-bottom: 15px;">
      <i class="fas fa-check-circle"></i> Applied Discounts
    </h5>
    <div class="row">
      @if($eligible)
      <!-- Scholarship Discount -->
      <div class="col-md-12 mb-3">
        <div class="alert alert-success">
          <h6 class="mb-2">
            <i class="fas fa-graduation-cap"></i> Scholarship Applied
          </h6>
          <div class="row">
            <div class="col-md-6">
              <strong>Scholarship Name:</strong><br>
              {{ $scholarshipData['scholarship_name'] ?? $scholarshipEligible['reason'] ?? 'N/A' }}
            </div>
            <div class="col-md-6 text-end">
              <strong>Discount:</strong><br>
              <span class="text-success fw-bold">
                {{ $scholarshipData['discount_percentage'] ?? $scholarshipEligible['discountPercent'] ?? 0 }}%
              </span>
            </div>
          </div>
        </div>
      </div>
      @endif

      @if($hasDiscretionary)
      <!-- Discretionary Discount -->
      <div class="col-md-12 mb-3">
        <div class="alert alert-info">
          <h6 class="mb-2">
            <i class="fas fa-hand-holding-usd"></i> Additional Discretionary Discount
          </h6>
          <div class="row">
            <div class="col-md-6">
              <strong>Type:</strong><br>
              {{ $scholarshipData['discretionary_type'] === 'percentage' ? 'Percentage' : 'Fixed Amount' }}
            </div>
            <div class="col-md-6 text-end">
              <strong>Value:</strong><br>
              <span class="text-info fw-bold">
                @if($scholarshipData['discretionary_type'] === 'percentage')
                  {{ $scholarshipData['discretionary_value'] }}%
                @else
                  ₹{{ number_format($scholarshipData['discretionary_value'], 2) }}
                @endif
              </span>
            </div>
          </div>
          @if(!empty($scholarshipData['discretionary_reason']))
          <div class="mt-2 pt-2 border-top">
            <strong>Reason:</strong> {{ $scholarshipData['discretionary_reason'] }}
          </div>
          @endif
        </div>
      </div>
      @endif

      <!-- Fee Breakdown -->
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title mb-3">📋 Fee Breakdown</h6>
            <table class="table table-sm">
              <tr>
                <td>Original Fee:</td>
                <td class="text-end">
                  ₹{{ number_format($scholarshipData['total_before_discount'] ?? $feeSummary['grand']['total'], 2) }}
                </td>
              </tr>
              @if(($scholarshipData['discount_percentage'] ?? 0) > 0)
              <tr class="text-success">
                <td>Scholarship Discount ({{ $scholarshipData['discount_percentage'] }}%):</td>
                <td class="text-end">
                  - ₹{{ number_format(($scholarshipData['total_before_discount'] ?? $feeSummary['grand']['total']) * ($scholarshipData['discount_percentage'] ?? 0) / 100, 2) }}
                </td>
              </tr>
              @endif
              @if($hasDiscretionary)
              <tr class="text-info">
                <td>Discretionary Discount:</td>
                <td class="text-end">
                  @php
                    $afterScholarship = ($scholarshipData['total_before_discount'] ?? $feeSummary['grand']['total']) * (1 - ($scholarshipData['discount_percentage'] ?? 0)/100);
                  @endphp
                  @if($scholarshipData['discretionary_type'] === 'percentage')
                    - ₹{{ number_format($afterScholarship * ($scholarshipData['discretionary_value'] ?? 0) / 100, 2) }}
                  @else
                    - ₹{{ number_format($scholarshipData['discretionary_value'] ?? 0, 2) }}
                  @endif
                </td>
              </tr>
              @endif
              <tr class="border-top fw-bold">
                <td>Final Fee (Before GST):</td>
                <td class="text-end">₹{{ number_format($feeSummary['grand']['total'], 2) }}</td>
              </tr>
              <tr>
                <td>GST (18%):</td>
                <td class="text-end">₹{{ number_format($feeSummary['grand']['total'] * 0.18, 2) }}</td>
              </tr>
              <tr class="border-top fw-bold text-danger">
                <td>Total Payable:</td>
                <td class="text-end fs-5">₹{{ number_format($feeSummary['grand']['total'] * 1.18, 2) }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @else
  <!-- No Scholarship -->
  <div class="scholarship-box">
    <div class="row">
      <div class="col-md-12">
        <strong>Is Eligible For Scholarship:</strong> 
        <span style="color: #dc3545; font-weight: 600;">No</span>
      </div>
    </div>
  </div>
  @endif

  <!-- Fee Summary Cards -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card border-primary">
        <div class="card-body text-center">
          <h6 class="text-muted mb-2">Total Fees</h6>
          <h3 class="text-primary mb-0">₹{{ number_format($feeSummary['grand']['total'], 2) }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-success">
        <div class="card-body text-center">
          <h6 class="text-muted mb-2">Paid Amount</h6>
          <h3 class="text-success mb-0">₹{{ number_format($feeSummary['grand']['paid'], 2) }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-danger">
        <div class="card-body text-center">
          <h6 class="text-muted mb-2">Pending Amount</h6>
          <h3 class="text-danger mb-0">₹{{ number_format($feeSummary['grand']['pending'], 2) }}</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Fees Tabs -->
  <div class="fees-tabs">
    <button class="fees-tab-btn active" data-fees-tab="fees">
      Fees ({{ $student->fees->count() }})
    </button>
    <button class="fees-tab-btn" data-fees-tab="other-fees">
      Other Fees ({{ $student->other_fees->count() }})
    </button>
    <button class="fees-tab-btn" data-fees-tab="transaction">
      Transactions ({{ $student->transactions->count() }})
    </button>
  </div>

  <!-- Regular Fees Table -->
  <div class="fees-content active" id="fees-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <label>Show</label>
        <select class="form-select form-select-sm d-inline-block mx-2" style="width: 80px;">
          <option>10</option>
          <option>25</option>
          <option>50</option>
        </select>
        <span>entries</span>
      </div>
      <div>
        <label>Search:</label>
        <input type="search" class="form-control form-control-sm d-inline-block ms-2" style="width: 200px;">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead style="background-color: #f8f9fa;">
          <tr>
            <th>Installment</th>
            <th>Fee Type</th>
            <th>Actual Amount</th>
            <th>Discount</th>
            <th>Paid Amount</th>
            <th>Remaining</th>
            <th>Due Date</th>
            <th>Paid Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($student->fees as $index => $fee)
          <tr>
            <td>{{ $fee['installment_number'] ?? ($index + 1) }}</td>
            <td>{{ ucfirst($fee['fee_type'] ?? 'Regular Fee') }}</td>
            <td class="text-end">₹{{ number_format($fee['actual_amount'] ?? 0, 2) }}</td>
            <td class="text-end">₹{{ number_format($fee['discount_amount'] ?? 0, 2) }}</td>
            <td class="text-end">₹{{ number_format($fee['paid_amount'] ?? 0, 2) }}</td>
            <td class="text-end">₹{{ number_format($fee['remaining_amount'] ?? 0, 2) }}</td>
            <td>
              @if(isset($fee['due_date']))
                {{ is_string($fee['due_date']) ? \Carbon\Carbon::parse($fee['due_date'])->format('d-m-Y') : $fee['due_date']->format('d-m-Y') }}
              @else
                N/A
              @endif
            </td>
            <td>
              @if(isset($fee['paid_date']) && $fee['paid_date'])
                {{ is_string($fee['paid_date']) ? \Carbon\Carbon::parse($fee['paid_date'])->format('d-m-Y') : $fee['paid_date']->format('d-m-Y') }}
              @else
                -
              @endif
            </td>
            <td>
              <span class="badge bg-{{ $fee['status_badge'] ?? 'secondary' }}">
                {{ ucfirst($fee['status'] ?? 'pending') }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center text-muted py-4">
              <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
              No fees data available
            </td>
          </tr>
          @endforelse
        </tbody>
        @if($student->fees->count() > 0)
        <tfoot style="background-color: #f8f9fa; font-weight: 600;">
          <tr>
            <td colspan="2" class="text-end">Total</td>
            <td class="text-end">₹{{ number_format($feeSummary['fees']['total'], 2) }}</td>
            <td class="text-end">₹{{ number_format($feeSummary['fees']['discount'] ?? 0, 2) }}</td>
            <td class="text-end">₹{{ number_format($feeSummary['fees']['paid'], 2) }}</td>
            <td class="text-end">₹{{ number_format($feeSummary['fees']['pending'], 2) }}</td>
            <td colspan="3"></td>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>

  <!-- Other Fees Table -->
  <div class="fees-content" id="other-fees-content" style="display: none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <label>Show</label>
        <select class="form-select form-select-sm d-inline-block mx-2" style="width: 80px;">
          <option>10</option>
          <option>25</option>
          <option>50</option>
        </select>
        <span>entries</span>
      </div>
      <div>
        <label>Search:</label>
        <input type="search" class="form-control form-control-sm d-inline-block ms-2" style="width: 200px;">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead style="background-color: #f8f9fa;">
          <tr>
            <th>Sr. No.</th>
            <th>Fee Name</th>
            <th>Description</th>
            <th>Actual Amount</th>
            <th>Paid Amount</th>
            <th>Remaining</th>
            <th>Due Date</th>
            <th>Paid Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($student->other_fees as $index => $otherFee)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $otherFee['fee_name'] ?? 'N/A' }}</td>
            <td>{{ $otherFee['description'] ?? '-' }}</td>
            <td class="text-end">₹{{ number_format($otherFee['actual_amount'] ?? 0, 2) }}</td>
            <td class="text-end">₹{{ number_format($otherFee['paid_amount'] ?? 0, 2) }}</td>
            <td class="text-end">₹{{ number_format($otherFee['remaining_amount'] ?? 0, 2) }}</td>
            <td>
              @if(isset($otherFee['due_date']))
                {{ is_string($otherFee['due_date']) ? \Carbon\Carbon::parse($otherFee['due_date'])->format('d-m-Y') : $otherFee['due_date']->format('d-m-Y') }}
              @else
                N/A
              @endif
            </td>
            <td>
              @if(isset($otherFee['paid_date']) && $otherFee['paid_date'])
                {{ is_string($otherFee['paid_date']) ? \Carbon\Carbon::parse($otherFee['paid_date'])->format('d-m-Y') : $otherFee['paid_date']->format('d-m-Y') }}
              @else
                -
              @endif
            </td>
            <td>
              <span class="badge bg-{{ $otherFee['status_badge'] ?? 'secondary' }}">
                {{ ucfirst($otherFee['status'] ?? 'pending') }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center text-muted py-4">
              <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
              No other fees data available
            </td>
          </tr>
          @endforelse
        </tbody>
        @if($student->other_fees->count() > 0)
        <tfoot style="background-color: #f8f9fa; font-weight: 600;">
          <tr>
            <td colspan="3" class="text-end">Total</td>
            <td class="text-end">₹{{ number_format($feeSummary['other_fees']['total'], 2) }}</td>
            <td class="text-end">₹{{ number_format($feeSummary['other_fees']['paid'], 2) }}</td>
            <td class="text-end">₹{{ number_format($feeSummary['other_fees']['pending'], 2) }}</td>
            <td colspan="3"></td>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>

  <!-- Transactions Table -->
  <div class="fees-content" id="transaction-content" style="display: none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <label>Show</label>
        <select class="form-select form-select-sm d-inline-block mx-2" style="width: 80px;">
          <option>10</option>
          <option>25</option>
          <option>50</option>
        </select>
        <span>entries</span>
      </div>
      <div>
        <label>Search:</label>
        <input type="search" class="form-control form-control-sm d-inline-block ms-2" style="width: 200px;">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead style="background-color: #f8f9fa;">
          <tr>
            <th>Sr. No.</th>
            <th>Transaction ID</th>
            <th>Fee Type</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Payment Date</th>
            <th>Received By</th>
            <th>Remarks</th>
          </tr>
        </thead>
        <tbody>
          @forelse($student->transactions as $index => $transaction)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td><span class="badge bg-info">{{ $transaction['transaction_id'] ?? 'N/A' }}</span></td>
            <td>{{ ucfirst($transaction['fee_type'] ?? 'N/A') }}</td>
            <td class="text-end fw-bold">₹{{ number_format($transaction['amount'] ?? 0, 2) }}</td>
            <td>
              <span class="badge bg-secondary">
                {{ ucfirst($transaction['payment_method'] ?? 'N/A') }}
              </span>
            </td>
            <td>
              @if(isset($transaction['payment_date']))
                {{ is_string($transaction['payment_date']) ? \Carbon\Carbon::parse($transaction['payment_date'])->format('d-m-Y h:i A') : $transaction['payment_date']->format('d-m-Y h:i A') }}
              @else
                N/A
              @endif
            </td>
            <td>{{ $transaction['received_by'] ?? 'N/A' }}</td>
            <td>{{ $transaction['remarks'] ?? '-' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">
              <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
              No transaction history available
            </td>
          </tr>
          @endforelse
        </tbody>
        @if($student->transactions->count() > 0)
        <tfoot style="background-color: #f8f9fa; font-weight: 600;">
          <tr>
            <td colspan="3" class="text-end">Total Transactions</td>
            <td class="text-end">₹{{ number_format($student->transactions->sum('amount'), 2) }}</td>
            <td colspan="4"></td>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>
</div>

<!-- TAB 4: Test Series -->
<div class="tab-content-section" id="test-series">
  <div class="test-series-content">
    <!-- Test Type Buttons (General/SPR) -->
    <div class="test-type-section">
      <div class="test-type-buttons">
        <button class="test-type-btn active" data-test-type="general">
          General
        </button>
        <button class="test-type-btn" data-test-type="spr">
          SPR
        </button>
      </div>

      <!-- GENERAL TEST SECTION -->
      <div class="test-type-content active" id="general-content">
        <!-- Type 1/Type 2 Buttons -->
        <div class="pattern-buttons">
          <button class="pattern-btn active" data-type="type1">Type 1</button>
          <button class="pattern-btn" data-type="type2">Type 2</button>
        </div>

        <!-- Type 1 Content: Stats + Table -->
        <div id="type1-content" class="type-content" style="display: block;">
          <!-- Stats Grid -->
          <div class="test-stats-grid">
            <!-- Overall Rank Chart -->
            <div class="stats-card">
              <h6>OverAll Rank</h6>
              <div class="chart-container">
                <canvas id="overallRankChart"></canvas>
              </div>
            </div>

            <!-- Overall Percentage Chart -->
            <div class="stats-card">
              <h6>OverAll Percentage</h6>
              <div class="chart-container">
                <canvas id="overallPercentageChart"></canvas>
              </div>
            </div>

            <!-- Attendance Status -->
            <div class="stats-card">
              <h6>Attendance Status</h6>
              <div class="attendance-donut">
                <canvas id="testAttendanceChart"></canvas>
              </div>
              <p class="text-center mt-3" style="color: #e05301; font-weight: bold;">
                0%
              </p>
            </div>
          </div>

          <!-- Data Table -->
          <div class="table-responsive mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <label>Show</label>
                <select class="form-select form-select-sm d-inline-block mx-2" style="width: 80px;">
                  <option>5</option>
                  <option selected>10</option>
                  <option>25</option>
                </select>
                <span>entries</span>
              </div>
              <div>
                <label>Search:</label>
                <input type="search" class="form-control form-control-sm d-inline-block ms-2" style="width: 200px;">
              </div>
            </div>

            <table class="table table-bordered table-hover">
              <thead style="background-color: #f8f9fa;">
                <tr>
                  <th>Sr. No.</th>
                  <th>Test Name</th>
                  <th>Test Date</th>
                  <th>Total Marks</th>
                  <th>Obtained Marks</th>
                  <th>Topper Marks</th>
                  <th>Avg. Class Marks</th>
                  <th>Batch Rank</th>
                  <th>Over all Rank</th>
                  <th>Percentage</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="10" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                    No data available in table
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-3">
              <span>Showing 0 to 0 of 0 entries</span>
              <nav>
                <ul class="pagination pagination-sm mb-0">
                  <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                </ul>
              </nav>
            </div>
          </div>
        </div>

        <!-- Type 2 Content: Just Table -->
        <div id="type2-content" class="type-content" style="display: none;">
          <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <label>Show</label>
                <select class="form-select form-select-sm d-inline-block mx-2" style="width: 80px;">
                  <option>5</option>
                  <option selected>10</option>
                  <option>25</option>
                </select>
                <span>entries</span>
              </div>
              <div>
                <label>Search:</label>
                <input type="search" class="form-control form-control-sm d-inline-block ms-2" style="width: 200px;">
              </div>
            </div>

            <table class="table table-bordered table-hover">
              <thead style="background-color: #f8f9fa;">
                <tr>
                  <th>Sr. No.</th>
                  <th>Test Name</th>
                  <th>Test Date</th>
                  <th>Total Marks</th>
                  <th>Obtained Marks</th>
                  <th>Topper Marks</th>
                  <th>Avg. Class Marks</th>
                  <th>Batch Rank</th>
                  <th>Over all Rank</th>
                  <th>Percentage</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="10" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                    No data available in table
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-3">
              <span>Showing 0 to 0 of 0 entries</span>
              <nav>
                <ul class="pagination pagination-sm mb-0">
                  <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <!-- SPR TEST SECTION -->
      <div class="test-type-content" id="spr-content">
        <!-- Pattern Buttons -->
        <div class="pattern-buttons">
          <button class="pattern-btn active" data-pattern="board">Board Pattern</button>
          <button class="pattern-btn" data-pattern="neet">Neet Pattern</button>
          <button class="pattern-btn" data-pattern="iit">IIT Pattern</button>
        </div>

        <button type="button" class="download-report-btn" onclick="window.print();">
          <i class="fas fa-download"></i> Download Report
        </button>
        <div style="clear: both;"></div>

        <!-- Board Pattern Report Card -->
        <div class="report-card" id="board-pattern-content">
          <div class="report-header">
            <div class="logo-section">
              <img src="{{ asset('images/logo.png.jpg') }}" alt="Logo" class="institute-logo">
            </div>
            <div class="institute-name">
              <img src="{{ asset('images/synthesis-logo.png') }}" alt="Synthesis" style="max-width: 500px;">
            </div>
            <div class="logo-section">
              <img src="{{ asset('images/logo.png.jpg') }}" alt="Logo" class="institute-logo">
            </div>
          </div>

          <div class="student-info-grid">
            <div class="info-item">
              <span class="info-label">CLASS:</span>
              <span class="info-value">Target (XII +)</span>
            </div>
            <div class="info-item">
              <span class="info-label">SESSION:</span>
              <span class="info-value">2025-2026</span>
            </div>
            <div class="info-item">
              <span class="info-label">Student Name:</span>
              <span class="info-value">{{ $student->student_name ?? $student->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Father's Name:</span>
              <span class="info-value">{{ $student->father_name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Roll No:</span>
              <span class="info-value">{{ $student->roll_no ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Contact No:</span>
              <span class="info-value">{{ $student->father_contact ?? $student->phone ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Batch:</span>
              <span class="info-value">{{ $student->batch_name ?? ($student->batch->name ?? 'N/A') }}</span>
            </div>
          </div>

          <h5 style="text-align: center; background-color: #808080; color: white; padding: 10px; margin: 20px 0;">
            ::Board Pattern::
          </h5>

          <table class="test-table">
            <thead>
              <tr>
                <th>Test Date</th>
                <th>Test Name</th>
                <th>Total Marks</th>
                <th>%</th>
                <th>Overall Rank</th>
                <th>Batch Rank</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center text-muted py-3">No test data available</td>
              </tr>
            </tbody>
          </table>

          <table class="test-table" style="margin-top: 20px;">
            <tbody>
              <tr class="statistics-row">
                <td>AVG. Batch Rank: N/A</td>
                <td>AVG. Overall Rank: N/A</td>
                <td>AVG. % (ALL TEST): N/A</td>
                <td>DATE OF JOINING: {{ isset($student->created_at) ? $student->created_at->format('d/m/Y') : 'N/A' }}</td>
              </tr>
            </tbody>
          </table>

          <div class="signature-section">
            <div class="signature-box">
              <div class="signature-line">Sign Batch Mentor</div>
            </div>
            <div class="signature-box">
              <div class="signature-line">Sign Director (incharge)</div>
            </div>
            <div class="signature-box">
              <div class="signature-line">Sign Parents</div>
            </div>
            <div class="signature-box">
              <div class="signature-line">Sign Student</div>
            </div>
          </div>
        </div>

        <!-- NEET Pattern Report Card (hidden by default) -->
        <div class="report-card" id="neet-pattern-content" style="display: none;">
          <div class="report-header">
            <div class="logo-section">
              <img src="{{ asset('images/logo.png.jpg') }}" alt="Logo" class="institute-logo">
            </div>
            <div class="institute-name">
              <img src="{{ asset('images/synthesis-logo.png') }}" alt="Synthesis" style="max-width: 500px;">
            </div>
            <div class="logo-section">
              <img src="{{ asset('images/logo.png.jpg') }}" alt="Logo" class="institute-logo">
            </div>
          </div>

          <div class="student-info-grid">
            <div class="info-item">
              <span class="info-label">CLASS:</span>
              <span class="info-value">Target (XII +)</span>
            </div>
            <div class="info-item">
              <span class="info-label">SESSION:</span>
              <span class="info-value">2025-2026</span>
            </div>
            <div class="info-item">
              <span class="info-label">Student Name:</span>
              <span class="info-value">{{ $student->student_name ?? $student->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Father's Name:</span>
              <span class="info-value">{{ $student->father_name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Roll No:</span>
              <span class="info-value">{{ $student->roll_no ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Contact No:</span>
              <span class="info-value">{{ $student->father_contact ?? $student->phone ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Batch:</span>
              <span class="info-value">{{ $student->batch_name ?? ($student->batch->name ?? 'N/A') }}</span>
            </div>
          </div>

          <h5 style="text-align: center; background-color: #808080; color: white; padding: 10px; margin: 20px 0;">
            ::NEET Pattern::
          </h5>

          <table class="test-table">
            <thead>
              <tr>
                <th>Test Date</th>
                <th>Test Name</th>
                <th>Total Marks</th>
                <th>%</th>
                <th>Overall Rank</th>
                <th>Batch Rank</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center text-muted py-3">No test data available</td>
              </tr>
            </tbody>
          </table>

          <table class="test-table" style="margin-top: 20px;">
            <tbody>
              <tr class="statistics-row">
                <td>AVG. Batch Rank: N/A</td>
                <td>AVG. Overall Rank: N/A</td>
                <td>AVG. % (ALL TEST): N/A</td>
                <td>DATE OF JOINING: {{ isset($student->created_at) ? $student->created_at->format('d/m/Y') : 'N/A' }}</td>
              </tr>
            </tbody>
          </table>

          <div class="signature-section">
            <div class="signature-box">
              <div class="signature-line">Sign Batch Mentor</div>
            </div>
            <div class="signature-box">
              <div class="signature-line">Sign Director (incharge)</div>
            </div>
            <div class="signature-box">
              <div class="signature-line">Sign Parents</div>
            </div>
            <div class="signature-box">
              <div class="signature-line">Sign Student</div>
            </div>
          </div>
        </div>

        <!-- IIT Pattern Report Card (hidden by default) -->
        <div class="report-card" id="iit-pattern-content" style="display: none;">
          <div class="report-header">
            <div class="logo-section">
              <img src="{{ asset('images/logo.png.jpg') }}" alt="Logo" class="institute-logo">
            </div>
            <div class="institute-name">
              <img src="{{ asset('images/synthesis-logo.png') }}" alt="Synthesis" style="max-width: 500px;">
            </div>
            <div class="logo-section">
              <img src="{{ asset('images/logo.png.jpg') }}" alt="Logo" class="institute-logo">
            </div>
          </div>

          <div class="student-info-grid">
            <div class="info-item">
              <span class="info-label">CLASS:</span>
              <span class="info-value">Target (XII +)</span>
            </div>
            <div class="info-item">
              <span class="info-label">SESSION:</span>
              <span class="info-value">2025-2026</span>
            </div>
            <div class="info-item">
              <span class="info-label">Student Name:</span>
              <span class="info-value">{{ $student->student_name ?? $student->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Father's Name:</span>
              <span class="info-value">{{ $student->father_name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Roll No:</span>
              <span class="info-value">{{ $student->roll_no ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Contact No:</span>
              <span class="info-value">{{ $student->father_contact ?? $student->phone ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Batch:</span>
              <span class="info-value">{{ $student->batch_name ?? ($student->batch->name ?? 'N/A') }}</span>
            </div>
          </div>

          <h5 style="text-align: center; background-color: #808080; color: white; padding: 10px; margin: 20px 0;">
            ::IIT Pattern::
          </h5>

          <table class="test-table">
            <thead>
              <tr>
                <th>Test Date</th>
                <th>Test Name</th>
                <th>Total Marks</th>
                <th>%</th>
                <th>Overall Rank</th>
                <th>Batch Rank</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center text-muted py-3">No test data available</td>
              </tr>
            </tbody>
          </table>

          <table class="test-table" style="margin-top: 20px;">
            <tbody>
              <tr class="statistics-row">
                <td>AVG. Batch Rank: N/A</td>
                <td>AVG. Overall Rank: N/A</td>
                <td>AVG. % (ALL TEST): N/A</td>
                <td>DATE OF JOINING: {{ isset($student->created_at) ? $student->created_at->format('d/m/Y') : 'N/A' }}</td>
              </tr>
            </tbody>
          </table>

          <div class="signature-section">
            <div class="signature-box">
              <div class="signature-line">Sign Batch Mentor</div>
            </div>
            <div class="signature-box">
              <div class="signature-line">Sign Director (incharge)</div>
            </div>
            <div class="signature-box">
              <div class="signature-line">Sign Parents</div>
            </div>
            <div class="signature-box">
              <div class="signature-line">Sign Student</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/smstudents.js') }}"></script>
  <script>
    // Tab switching functionality
    document.querySelectorAll('.tab-btn').forEach(button => {
      button.addEventListener('click', function() {
        // Remove active class from all tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // Hide all tab contents
        document.querySelectorAll('.tab-content-section').forEach(content => {
          content.classList.remove('active');
        });
        
        // Show selected tab content
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
      });
    });

    // Fees tab switching
    document.querySelectorAll('.fees-tab-btn').forEach(button => {
      button.addEventListener('click', function() {
        document.querySelectorAll('.fees-tab-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Test type button switching
    document.querySelectorAll('.test-type-btn').forEach(button => {
      button.addEventListener('click', function() {
        const parent = this.parentElement;
        parent.querySelectorAll('.test-type-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Initialize Charts
    window.addEventListener('load', function() {
      // Attendance Donut Chart
      const attendanceCtx = document.getElementById('attendanceChart');
      if (attendanceCtx) {
        new Chart(attendanceCtx, {
          type: 'doughnut',
          data: {
            labels: ['Present', 'Absent'],
            datasets: [{
              data: [0, 167],
              backgroundColor: ['#28a745', '#dc3545']
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: { display: false }
            }
          }
        });
      }

      // Attendance Rate Line Chart
      const rateCtx = document.getElementById('attendanceRateChart');
      if (rateCtx) {
        new Chart(rateCtx, {
          type: 'line',
          data: {
            labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            datasets: [{
              label: 'Percentage',
              data: [0, 0, 0, 0, 0, 0],
              borderColor: '#e05301',
              backgroundColor: 'rgba(224, 83, 1, 0.1)',
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true, max: 35 }
            }
          }
        });
      }

      // Test Attendance Donut
      const testAttCtx = document.getElementById('testAttendanceChart');
      if (testAttCtx) {
        new Chart(testAttCtx, {
          type: 'doughnut',
          data: {
            labels: ['Attended', 'Not Attended'],
            datasets: [{
              data: [0, 100],
              backgroundColor: ['#28a745', '#dc3545']
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: { display: false }
            }
          }
        });
      }

      // Overall Rank Chart
      const rankCtx = document.getElementById('overallRankChart');
      if (rankCtx) {
        new Chart(rankCtx, {
          type: 'bar',
          data: {
            labels: [],
            datasets: [{
              label: 'Rank',
              data: [],
              backgroundColor: '#e05301'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true }
            }
          }
        });
      }

      // Overall Percentage Chart
      const percentCtx = document.getElementById('overallPercentageChart');
      if (percentCtx) {
        new Chart(percentCtx, {
          type: 'bar',
          data: {
            labels: [],
            datasets: [{
              label: 'Percentage',
              data: [],
              backgroundColor: '#e05301'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true, max: 100 }
            }
          }
        });
      }
    });
  </script>
  <script>
  // Fees tab switching with content display
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.fees-tab-btn').forEach(button => {
      button.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('.fees-tab-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        // Hide all fee contents
        document.querySelectorAll('.fees-content').forEach(content => {
          content.style.display = 'none';
        });
        
        // Show selected content
        const tab = this.getAttribute('data-fees-tab');
        const contentId = tab + '-content';
        const content = document.getElementById(contentId);
        if (content) {
          content.style.display = 'block';
        }
      });
    });
  });
  // Test Series Tab JavaScript
document.addEventListener('DOMContentLoaded', function() {
  // Test Type Switching (General/SPR)
  const testTypeButtons = document.querySelector('.test-type-buttons');
  if (testTypeButtons) {
    testTypeButtons.addEventListener('click', function(e) {
      const button = e.target.closest('.test-type-btn');
      if (!button) return;
      
      document.querySelectorAll('.test-type-btn').forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
      
      document.querySelectorAll('.test-type-content').forEach(content => {
        content.style.display = 'none';
        content.classList.remove('active');
      });
      
      const testType = button.getAttribute('data-test-type');
      const targetContent = document.getElementById(testType + '-content');
      if (targetContent) {
        targetContent.style.display = 'block';
        targetContent.classList.add('active');
      }
    });
  }

  // Type 1/Type 2 switching
  document.querySelectorAll('.pattern-btn[data-type]').forEach(button => {
    button.addEventListener('click', function() {
      document.querySelectorAll('.pattern-btn[data-type]').forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      
      document.querySelectorAll('.type-content').forEach(content => content.style.display = 'none');
      
      const type = this.getAttribute('data-type');
      const targetContent = document.getElementById(type + '-content');
      if (targetContent) targetContent.style.display = 'block';
    });
  });

  // Pattern switching for SPR (Board/NEET/IIT)
  document.querySelectorAll('.pattern-btn[data-pattern]').forEach(button => {
    button.addEventListener('click', function() {
      document.querySelectorAll('.pattern-btn[data-pattern]').forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      
      document.querySelectorAll('.report-card').forEach(card => card.style.display = 'none');
      
      const pattern = this.getAttribute('data-pattern');
      const targetCard = document.getElementById(pattern + '-pattern-content');
      if (targetCard) targetCard.style.display = 'block';
    });
  });

  // Initialize Test Series Charts
  initializeTestSeriesCharts();
});

function initializeTestSeriesCharts() {
  // Overall Rank Chart
  const rankCtx = document.getElementById('overallRankChart');
  if (rankCtx) {
    new Chart(rankCtx, {
      type: 'bar',
      data: {
        labels: ['Overall Rank'],
        datasets: [{
          label: 'Average Rank',
          data: [0],
          backgroundColor: '#e05301'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: { beginAtZero: true, reverse: true }
        },
        plugins: { legend: { display: false } }
      }
    });
  }

  // Overall Percentage Chart
  const percentCtx = document.getElementById('overallPercentageChart');
  if (percentCtx) {
    new Chart(percentCtx, {
      type: 'bar',
      data: {
        labels: ['Overall %'],
        datasets: [{
          label: 'Average Percentage',
          data: [0],
          backgroundColor: '#e05301'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, max: 100 } },
        plugins: { legend: { display: false } }
      }
    });
  }

  // Test Attendance Donut
  const testAttCtx = document.getElementById('testAttendanceChart');
  if (testAttCtx) {
    new Chart(testAttCtx, {
      type: 'doughnut',
      data: {
        labels: ['Attended', 'Not Attended'],
        datasets: [{
          data: [0, 100],
          backgroundColor: ['#28a745', '#dc3545']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { 
          legend: { display: true, position: 'bottom' }
        }
      }
    });
  }
}

function viewDocument(base64Data, title) {
  // Create modal or new window to view document
  const newWindow = window.open('', '_blank');
  
  if (base64Data.includes('application/pdf')) {
    // PDF Document
    newWindow.document.write(`
      <html>
        <head>
          <title>${title}</title>
          <style>
            body { margin: 0; }
            iframe { width: 100vw; height: 100vh; border: none; }
          </style>
        </head>
        <body>
          <iframe src="${base64Data}"></iframe>
        </body>
      </html>
    `);
  } else {
    // Image Document
    newWindow.document.write(`
      <html>
        <head>
          <title>${title}</title>
          <style>
            body { 
              margin: 0; 
              display: flex; 
              justify-content: center; 
              align-items: center;
              background-color: #f0f0f0;
              min-height: 100vh;
            }
            img { 
              max-width: 90%; 
              max-height: 90vh; 
              box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
          </style>
        </head>
        <body>
          <img src="${base64Data}" alt="${title}">
        </body>
      </html>
    `);
  }
}

</script>
</body>
</html>