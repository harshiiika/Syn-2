<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Student Details - {{ $student->student_name ?? $student->name }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/smstudents.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .page-header {
      background-color: rgba(0, 0, 0, 0);
      margin: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
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
      text-decoration: none;
      display: inline-block;
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

    /* Student Detail Table Styles */
    .student-detail-section {
      margin-bottom: 30px;
    }
    .profile-section {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }
    .profile-image-box {
      text-align: center;
    }
    .profile-image-box img {
      width: 120px;
      height: 150px;
      border: 1px solid #ddd;
      object-fit: cover;
    }
    .profile-image-box p {
      margin-top: 10px;
      font-size: 14px;
    }
    .info-table {
      flex: 1;
    }
    .info-table table {
      width: 100%;
      border-collapse: collapse;
    }
    .info-table table td {
      padding: 8px 12px;
      border: 1px solid #e0e0e0;
      font-size: 14px;
    }
    .info-table table td:first-child {
      width: 20%;
      font-weight: 500;
      background-color: #f8f9fa;
    }
    .info-table table td:nth-child(2) {
      width: 30%;
    }
    .info-table table td:nth-child(3) {
      width: 20%;
      font-weight: 500;
      background-color: #f8f9fa;
    }
    .info-table table td:nth-child(4) {
      width: 30%;
    }
    .question-row {
      background-color: #fff;
      padding: 12px;
      border: 1px solid #e0e0e0;
      margin-bottom: 10px;
      display: flex;
      justify-content: space-between;
    }
    .question-row span:first-child {
      font-weight: 400;
    }
    .section-header {
      background-color: #f8f9fa;
      padding: 10px;
      font-weight: 600;
      margin-top: 20px;
      margin-bottom: 10px;
      border-left: 3px solid #e05301;
    }
    .document-table {
      width: 100%;
      margin-top: 10px;
    }
    .document-table table {
      width: 100%;
      border-collapse: collapse;
    }
    .document-table table td {
      padding: 10px 12px;
      border: 1px solid #e0e0e0;
      font-size: 14px;
    }
    .document-table table td:first-child {
      width: 70%;
    }
    .document-table table td:last-child {
      width: 30%;
      text-align: right;
      color: #dc3545;
    }

    /* Attendance Tab Styles */
    .attendance-container {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
    }
    .calendar-section {
      flex: 0 0 auto;
      background-color: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 15px;
    }
    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 1px solid #e0e0e0;
    }
    .calendar-header h5 {
      margin: 0;
      color: #e05301;
      font-size: 14px;
      font-weight: 600;
    }
    .calendar-nav {
      display: flex;
      gap: 10px;
    }
    .calendar-nav button {
      background: none;
      border: 1px solid #ddd;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 3px;
    }
    .calendar-table {
      width: 100%;
      border-collapse: collapse;
    }
    .calendar-table th {
      padding: 8px;
      text-align: center;
      font-size: 12px;
      color: #666;
      font-weight: 500;
    }
    .calendar-table td {
      padding: 8px;
      text-align: center;
      font-size: 13px;
      cursor: pointer;
    }
    .calendar-table td:hover {
      background-color: #f8f9fa;
    }
    .calendar-table td.today {
      background-color: #007bff;
      color: white;
      border-radius: 50%;
    }
    .calendar-table td.selected {
      background-color: #e05301;
      color: white;
      border-radius: 50%;
    }
    .stats-section {
      flex: 1;
      display: flex;
      gap: 20px;
    }
    .attendance-status-card {
      flex: 1;
      background-color: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
    }
    .attendance-status-card h6 {
      color: #333;
      font-size: 14px;
      margin-bottom: 20px;
    }
    .donut-chart {
      width: 200px;
      height: 200px;
      margin: 0 auto 15px;
      position: relative;
    }
    .attendance-percentage {
      font-size: 24px;
      font-weight: 600;
      color: #e05301;
    }
    .attendance-count {
      font-size: 14px;
      color: #666;
      margin-top: 5px;
    }
    .avg-attendance-card {
      flex: 1;
      background-color: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 20px;
    }
    .avg-attendance-card h6 {
      color: #333;
      font-size: 14px;
      margin-bottom: 20px;
    }
    .month-select {
      float: right;
      padding: 5px 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 13px;
    }
    .line-chart {
      width: 100%;
      height: 200px;
    }
    .attendance-table-section {
      background-color: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 20px;
    }
    .table-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    .entries-control {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
    }
    .entries-control select {
      padding: 5px 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .search-control {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
    }
    .search-control input {
      padding: 5px 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      width: 200px;
    }
    .attendance-data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    .attendance-data-table thead {
      background-color: #fff;
    }
    .attendance-data-table th {
      padding: 12px;
      text-align: center;
      font-size: 14px;
      font-weight: 600;
      color: #e05301;
      border-bottom: 2px solid #e0e0e0;
    }
    .attendance-data-table td {
      padding: 12px;
      text-align: center;
      font-size: 14px;
      border-bottom: 1px solid #e0e0e0;
    }
    .attendance-data-table tbody tr:hover {
      background-color: #f8f9fa;
    }
    .pagination-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
      color: #666;
    }
    .pagination-controls {
      display: flex;
      gap: 5px;
    }
    .pagination-controls button {
      padding: 5px 12px;
      border: 1px solid #ddd;
      background-color: #fff;
      cursor: pointer;
      border-radius: 3px;
      font-size: 13px;
    }
    .pagination-controls button.active {
      background-color: #e05301;
      color: white;
      border-color: #e05301;
    }
    .pagination-controls button:hover:not(.active) {
      background-color: #f8f9fa;
    }
    .pagination-controls button:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    /* Fees Management Styles */
    .fees-section {
      margin-bottom: 20px;
    }
    .fees-type-buttons {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }
    .fees-type-btn {
      padding: 10px 20px;
      border: 1px solid #e05301;
      background-color: #ffffff;
      color: #e05301;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s;
    }
    .fees-type-btn.active {
      background-color: #e05301;
      color: #ffffff;
    }
    .fees-type-btn:hover:not(.active) {
      background-color: #fff5f0;
    }
    .fees-content {
      display: none;
    }
    .fees-content.active {
      display: block;
    }
    .fees-data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    .fees-data-table thead {
      background-color: #fff;
    }
    .fees-data-table th {
      padding: 12px;
      text-align: center;
      font-size: 14px;
      font-weight: 600;
      color: #e05301;
      border-bottom: 2px solid #e0e0e0;
    }
    .fees-data-table td {
      padding: 12px;
      text-align: center;
      font-size: 14px;
      border-bottom: 1px solid #e0e0e0;
    }
    .fees-data-table tbody tr:hover {
      background-color: #f8f9fa;
    }
    .status-badge {
      padding: 4px 12px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
    }
    .status-due {
      background-color: #f8d7da;
      color: #721c24;
    }
    .status-paid {
      background-color: #d4edda;
      color: #155724;
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
          <button class="tab-btn active" data-tab="student-detail">
            Student Detail
          </button>
          
          <button class="tab-btn" data-tab="student-attendance">
            Student attendance
          </button>
          
          <button class="tab-btn" data-tab="fees-management">
            Fees management
          </button>
          
    <a href="{{ route('smstudents.testseries', $student->_id) }}" class="tab-btn">
    Test Series
</a>
        </div>

        <!-- Student Detail Content -->
        <div class="tab-content" id="student-detail-tab">
          <!-- Profile and Basic Info Section -->
          <div class="profile-section">
            <div class="profile-image-box">
              <img src="{{ asset('images/default-avatar.png') }}" alt="Student Photo">
              <p><strong>Roll Number</strong><br>{{ $student->roll_no ?? '2513/14133' }}</p>
            </div>
            
            <div class="info-table">
              <table>
                <tr>
                  <td>Student Name</td>
                  <td>{{ $student->student_name ?? $student->name ?? 'SUTVI GAUR' }}</td>
                  <td>Father Name</td>
                  <td>{{ $student->father_name ?? 'HANUMAN SHARMA' }}</td>
                </tr>
                <tr>
                  <td>Mother Name</td>
                  <td>{{ $student->mother_name ?? 'SANTOSH SHARMA' }}</td>
                  <td>DOB</td>
                  <td>{{ $student->dob ?? '2004-06-30' }}</td>
                </tr>
                <tr>
                  <td>Father Contact No</td>
                  <td>{{ $student->father_contact ?? '9251031431' }}</td>
                  <td>Father whatsApp No</td>
                  <td>{{ $student->father_whatsapp ?? '9251031431' }}</td>
                </tr>
                <tr>
                  <td>Mother Contact No</td>
                  <td>{{ $student->mother_contact ?? '7611842680' }}</td>
                  <td>Student contact No</td>
                  <td>{{ $student->student_contact ?? '7611842680' }}</td>
                </tr>
                <tr>
                  <td>Category</td>
                  <td>{{ $student->category ?? 'GENERAL' }}</td>
                  <td>Gender</td>
                  <td>{{ $student->gender ?? 'Female' }}</td>
                </tr>
                <tr>
                  <td>Father Occupation</td>
                  <td>{{ $student->father_occupation ?? 'Business' }}</td>
                  <td>Mother Occupation</td>
                  <td>{{ $student->mother_occupation ?? 'House Wife' }}</td>
                </tr>
                <tr>
                  <td>State</td>
                  <td>{{ $student->state ?? 'RJ Rajasthan' }}</td>
                  <td>City</td>
                  <td>{{ $student->city ?? 'Bikaner' }}</td>
                </tr>
                <tr>
                  <td>Pincode</td>
                  <td>{{ $student->pincode ?? '334001' }}</td>
                  <td>Address</td>
                  <td>{{ $student->address ?? 'STREET NO 1 SHIV BARI CIRCLE AMBEDKAR COLONY BIKANER' }}</td>
                </tr>
              </table>
            </div>
          </div>

          <!-- Additional Questions -->
          <div class="question-row">
            <span>Do you belong to another city</span>
            <span>{{ $student->belongs_to_another_city ?? 'No' }}</span>
          </div>

          <div class="question-row">
            <span>Do You Belong to Economic Weaker Section</span>
            <span>{{ $student->economic_weaker_section ?? 'No' }}</span>
          </div>

          <div class="question-row">
            <span>Do You Belong to Any Army/Police/Martyr Background?</span>
            <span>{{ $student->army_police_background ?? 'No' }}</span>
          </div>

          <div class="question-row">
            <span>Are You a Specially Abled ?</span>
            <span>{{ $student->specially_abled ?? 'No' }}</span>
          </div>

          <!-- Course Detail Section -->
          <div class="section-header">Course Detail</div>
          <table class="info-table" style="width: 100%;">
            <tr>
              <td style="width: 20%; font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Course Type</td>
              <td style="width: 30%; padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->course_type ?? 'Pre-Medical' }}</td>
              <td style="width: 20%; font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Course Name</td>
              <td style="width: 30%; padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->course_name ?? 'Dynamic Target NEET' }}</td>
            </tr>
            <tr>
              <td style="font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Delivery Mode</td>
              <td style="padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->delivery_mode ?? 'Offline' }}</td>
              <td style="font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Medium</td>
              <td style="padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->medium ?? 'English' }}</td>
            </tr>
            <tr>
              <td style="font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Board</td>
              <td style="padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->board ?? 'RBSE' }}</td>
              <td style="font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Course Content</td>
              <td style="padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->course_content ?? 'Class room course (with test series & study material)' }}</td>
            </tr>
          </table>

          <!-- Academic Detail Section -->
          <div class="section-header">Academic Detail</div>
          <table class="info-table" style="width: 100%;">
            <tr>
              <td style="width: 20%; font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Previous Class</td>
              <td style="width: 30%; padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->previous_class ?? '12th (XII)' }}</td>
              <td style="width: 20%; font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Medium</td>
              <td style="width: 30%; padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->academic_medium ?? 'English' }}</td>
            </tr>
            <tr>
              <td style="font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Name of School</td>
              <td style="padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->school_name ?? 'LADY ALGIN SR.SEC.SCHOOL' }}</td>
              <td style="font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Board</td>
              <td style="padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->academic_board ?? 'RBSE' }}</td>
            </tr>
            <tr>
              <td style="font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Passing Year</td>
              <td style="padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->passing_year ?? '2025' }}</td>
              <td style="font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Percentage</td>
              <td style="padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->percentage ?? '' }}</td>
            </tr>
          </table>

          <!-- Scholarship Eligibility Section -->
          <div class="section-header">Scholarship Eligibility</div>
          <div class="question-row">
            <span>Have You Appeared For the Synthesis Scholarship test?</span>
            <span>{{ $student->scholarship_test ?? 'No' }}</span>
          </div>

          <div class="question-row">
            <span>Percentage of Marks in last Board Exam</span>
            <span>{{ $student->board_exam_percentage ?? '' }}</span>
          </div>

          <div class="question-row">
            <span>Have You Appeared For any of the competition exam?</span>
            <span>{{ $student->competition_exam ?? 'No' }}</span>
          </div>

          <!-- Batch Allocation Section -->
          <div class="section-header">Batch Allocation</div>
          <table class="info-table" style="width: 100%;">
            <tr>
              <td style="width: 20%; font-weight: 500; background-color: #f8f9fa; padding: 8px 12px; border: 1px solid #e0e0e0;">Batch Name</td>
              <td style="width: 80%; padding: 8px 12px; border: 1px solid #e0e0e0;">{{ $student->batch_name ?? ($student->batch->name ?? 'D4') }}</td>
            </tr>
          </table>

          <!-- View Documents Section -->
          <div class="section-header">View Documents</div>
          <div class="document-table">
            <table>
              <tr>
                <td>Passport Size Photo</td>
                <td>{{ $student->passport_photo ?? 'Not Uploaded' }}</td>
              </tr>
              <tr>
                <td>Marksheet of Last qualifying Exam</td>
                <td>{{ $student->last_marksheet ?? 'Not Uploaded' }}</td>
              </tr>
              <tr>
                <td>If you are Ex Synthesis, upload Identity card issued by Synthesis</td>
                <td>{{ $student->synthesis_id ?? 'Not Uploaded' }}</td>
              </tr>
              <tr>
                <td>Upload Proof of Scholarship to avail Concession</td>
                <td>{{ $student->scholarship_proof ?? 'Not Uploaded' }}</td>
              </tr>
              <tr>
                <td>Secondary Board Marksheet</td>
                <td>{{ $student->secondary_marksheet ?? 'Not Uploaded' }}</td>
              </tr>
              <tr>
                <td>Senior Secondary Board Marksheet</td>
                <td>{{ $student->senior_secondary_marksheet ?? 'Not Uploaded' }}</td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Student Attendance Content -->
        <div class="tab-content" id="student-attendance-tab" style="display: none;">
          <!-- Calendar and Stats Section -->
          <div class="attendance-container">
            <!-- Calendar Section -->
            <div class="calendar-section">
              <div class="calendar-header">
                <h5>NOV 2025</h5>
                <div class="calendar-nav">
                  <button>&lt;</button>
                  <button>&gt;</button>
                </div>
              </div>
              <table class="calendar-table">
                <thead>
                  <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>1</td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                  </tr>
                  <tr>
                    <td>9</td>
                    <td>10</td>
                    <td>11</td>
                    <td>12</td>
                    <td>13</td>
                    <td>14</td>
                    <td>15</td>
                  </tr>
                  <tr>
                    <td class="today">16</td>
                    <td>17</td>
                    <td>18</td>
                    <td>19</td>
                    <td>20</td>
                    <td>21</td>
                    <td>22</td>
                  </tr>
                  <tr>
                    <td>23</td>
                    <td>24</td>
                    <td>25</td>
                    <td>26</td>
                    <td>27</td>
                    <td>28</td>
                    <td>29</td>
                  </tr>
                  <tr>
                    <td>30</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
              <!-- Attendance Status Card -->
              <div class="attendance-status-card">
                <h6>Attendance Status</h6>
                <div class="donut-chart">
                  <canvas id="attendanceDonutChart"></canvas>
                </div>
                <div class="attendance-percentage">0.00%</div>
                <div class="attendance-count">Total Presence : 0/159</div>
              </div>

              <!-- Average Attendance Rate Card -->
              <div class="avg-attendance-card">
                <h6>
                  Avg. Attendance Rate
                  <select class="month-select">
                    <option>Jun-Nov</option>
                    <option>Jan-May</option>
                  </select>
                </h6>
                <div class="line-chart">
                  <canvas id="attendanceLineChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Attendance Data Table -->
          <div class="attendance-table-section">
            <div class="table-controls">
              <div class="entries-control">
                <span>Show</span>
                <select>
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <span>entries</span>
              </div>
              <div class="search-control">
                <span>Search:</span>
                <input type="text" placeholder="">
              </div>
            </div>

            <table class="attendance-data-table">
              <thead>
                <tr>
                  <th>Serial No.</th>
                  <th>Month</th>
                  <th>Total Days</th>
                  <th>Total Attendance</th>
                  <th>Total Absent</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $attendanceData = [
                    ['month' => 'June', 'total_days' => 30, 'attendance' => 0, 'absent' => 0],
                    ['month' => 'July', 'total_days' => 31, 'attendance' => 0, 'absent' => 0],
                    ['month' => 'August', 'total_days' => 31, 'attendance' => 0, 'absent' => 0],
                    ['month' => 'September', 'total_days' => 30, 'attendance' => 0, 'absent' => 0],
                    ['month' => 'October', 'total_days' => 31, 'attendance' => 0, 'absent' => 0],
                    ['month' => 'November', 'total_days' => 30, 'attendance' => 0, 'absent' => 0],
                    ['month' => 'December', 'total_days' => 31, 'attendance' => 0, 'absent' => 0],
                    ['month' => 'January', 'total_days' => 31, 'attendance' => 0, 'absent' => 0],
                    ['month' => 'February', 'total_days' => 28, 'attendance' => 0, 'absent' => 0],
                    ['month' => 'March', 'total_days' => 31, 'attendance' => 0, 'absent' => 0],
                  ];
                @endphp

                @foreach($attendanceData as $index => $data)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $data['month'] }}</td>
                  <td>{{ $data['total_days'] }}</td>
                  <td>{{ $data['attendance'] }}</td>
                  <td>{{ $data['absent'] }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>

            <div class="pagination-info">
              <span>Showing 1 to 10 of 11 entries</span>
              <div class="pagination-controls">
                <button disabled>Previous</button>
                <button class="active">1</button>
                <button>2</button>
                <button>Next</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Fees Management Content -->
        <div class="tab-content" id="fees-management-tab" style="display: none;">
          <!-- Scholarship Eligibility -->
          <div class="question-row">
            <span>Is Eligible For Scholarship</span>
            <span>{{ $student->scholarship_eligible ?? 'No' }}</span>
          </div>

          <!-- Fees Type Buttons -->
          <div class="fees-section">
            <div class="fees-type-buttons">
              <button class="fees-type-btn active" data-fees-type="fees">Fees</button>
              <button class="fees-type-btn" data-fees-type="other-fees">OtherFees</button>
              <button class="fees-type-btn" data-fees-type="transaction">Transaction</button>
            </div>

            <!-- Fees Content -->
            <div class="fees-content active" id="fees-content">
              <div class="table-controls">
                <div class="entries-control">
                  <span>Show</span>
                  <select>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                  </select>
                  <span>entries</span>
                </div>
                <div class="search-control">
                  <span>Search:</span>
                  <input type="text" placeholder="">
                </div>
              </div>

              <table class="fees-data-table">
                <thead>
                  <tr>
                    <th>Fee Type</th>
                    <th>Actual Amount</th>
                    <th>Paid Amount</th>
                    <th>Due Date</th>
                    <th>Paid Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $feesData = [
                      ['fee_type' => '1', 'actual_amount' => 47200, 'paid_amount' => 1, 'due_date' => '', 'paid_date' => '', 'status' => 'Due'],
                      ['fee_type' => '2', 'actual_amount' => 35400, 'paid_amount' => 0, 'due_date' => '2025-08-11', 'paid_date' => '', 'status' => 'Due'],
                      ['fee_type' => '3', 'actual_amount' => 35400, 'paid_amount' => 0, 'due_date' => '2025-10-11', 'paid_date' => '', 'status' => 'Due'],
                    ];
                  @endphp

                  @foreach($feesData as $fee)
                  <tr>
                    <td>{{ $fee['fee_type'] }}</td>
                    <td>{{ $fee['actual_amount'] }}</td>
                    <td>{{ $fee['paid_amount'] }}</td>
                    <td>{{ $fee['due_date'] }}</td>
                    <td>{{ $fee['paid_date'] }}</td>
                    <td><span class="status-badge status-due">{{ $fee['status'] }}</span></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>

              <div class="pagination-info">
                <span>Showing 1 to 3 of 3 entries</span>
                <div class="pagination-controls">
                  <button disabled>Previous</button>
                  <button class="active">1</button>
                  <button disabled>Next</button>
                </div>
              </div>
            </div>

            <!-- Other Fees Content -->
            <div class="fees-content" id="other-fees-content">
              <div class="table-controls">
                <div class="entries-control">
                  <span>Show</span>
                  <select>
                    <option value="10">10</option>
                    <option value="25">25</option>
                  </select>
                  <span>entries</span>
                </div>
                <div class="search-control">
                  <span>Search:</span>
                  <input type="text" placeholder="">
                </div>
              </div>

              <table class="fees-data-table">
                <thead>
                  <tr>
                    <th>Fee Type</th>
                    <th>Actual Amount</th>
                    <th>Paid Amount</th>
                    <th>Due Date</th>
                    <th>Paid Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                      No data available in table
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="pagination-info">
                <span>Showing 0 to 0 of 0 entries</span>
                <div class="pagination-controls">
                  <button disabled>Previous</button>
                  <button class="active">1</button>
                  <button disabled>Next</button>
                </div>
              </div>
            </div>

            <!-- Transaction Content -->
            <div class="fees-content" id="transaction-content">
              <div class="table-controls">
                <div class="entries-control">
                  <span>Show</span>
                  <select>
                    <option value="10">10</option>
                    <option value="25">25</option>
                  </select>
                  <span>entries</span>
                </div>
                <div class="search-control">
                  <span>Search:</span>
                  <input type="text" placeholder="">
                </div>
              </div>

              <table class="fees-data-table">
                <thead>
                  <tr>
                    <th>Transaction Date</th>
                    <th>Amount</th>
                    <th>Payment Mode</th>
                    <th>Reference No</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                      No data available in table
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="pagination-info">
                <span>Showing 0 to 0 of 0 entries</span>
                <div class="pagination-controls">
                  <button disabled>Previous</button>
                  <button class="active">1</button>
                  <button disabled>Next</button>
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
    // Tab Switching (Student Detail, Attendance, Fees Management)
    document.querySelectorAll('.tab-btn[data-tab]').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
          content.style.display = 'none';
        });
        
        // Show selected tab content
        const tabId = this.getAttribute('data-tab') + '-tab';
        const targetTab = document.getElementById(tabId);
        if (targetTab) {
          targetTab.style.display = 'block';
        }
      });
    });

    // Fees Type Switching (Fees/OtherFees/Transaction)
    document.querySelectorAll('.fees-type-btn').forEach(button => {
      button.addEventListener('click', function() {
        // Remove active from all buttons
        document.querySelectorAll('.fees-type-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        // Hide all fees contents
        document.querySelectorAll('.fees-content').forEach(content => {
          content.classList.remove('active');
          content.style.display = 'none';
        });
        
        // Show selected fees content
        const feesType = this.getAttribute('data-fees-type');
        const targetContent = document.getElementById(feesType + '-content');
        if (targetContent) {
          targetContent.classList.add('active');
          targetContent.style.display = 'block';
        }
      });
    });

    // Initialize Charts for Attendance Tab
    window.addEventListener('load', function() {
      // Attendance Donut Chart
      const donutCtx = document.getElementById('attendanceDonutChart');
      if (donutCtx) {
        new Chart(donutCtx, {
          type: 'doughnut',
          data: {
            labels: ['Present', 'Absent'],
            datasets: [{
              data: [0, 100],
              backgroundColor: ['#17a2b8', '#e0e0e0'],
              borderWidth: 0
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: {
                display: false
              }
            },
            cutout: '70%'
          }
        });
      }

      // Attendance Line Chart
      const lineCtx = document.getElementById('attendanceLineChart');
      if (lineCtx) {
        new Chart(lineCtx, {
          type: 'line',
          data: {
            labels: ['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov'],
            datasets: [{
              label: 'Attendance %',
              data: [0, 0, 0, 0, 0, 0],
              borderColor: '#e05301',
              backgroundColor: 'rgba(224, 83, 1, 0.1)',
              tension: 0.4,
              fill: true
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                max: 35,
                ticks: {
                  stepSize: 5
                }
              }
            }
          }
        });
      }
    });
  </script>
</body>
</html>