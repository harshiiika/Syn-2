<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dispatch Study Material - Synthesis</title>
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  
  <!-- Bootstrap 5.3.6 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

  <style>
    /* Remove all unnecessary padding and margins */
    .right {
      padding: 0 !important;
      background-color: #f0f0f0;
    }
    
    .container-fluid {
      padding: 25px 30px !important;
      background-color: #f0f0f0;
    }

    /* Page title */
    .page-title {
      font-size: 24px;
      font-weight: 600;
      color: #d2691e;
      margin-bottom: 25px;
    }

    /* Filter Section */
    .filter-section {
      background-color: white;
      padding: 20px 25px;
      border-radius: 4px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      margin-bottom: 0;
    }

    .filter-row {
      display: grid;
      grid-template-columns: 1fr 1fr auto;
      gap: 20px;
      align-items: end;
    }

    .filter-item {
      display: flex;
      flex-direction: column;
    }

    .filter-label {
      color: #333;
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 8px;
    }

    .filter-select {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
      color: #333;
      background-color: white;
      cursor: pointer;
      transition: border-color 0.2s;
    }

    .filter-select:focus {
      outline: none;
      border-color: #d2691e;
      box-shadow: 0 0 0 0.2rem rgba(210, 105, 30, 0.15);
    }

    .filter-select:disabled {
      background-color: #f5f5f5;
      cursor: not-allowed;
    }

    .btn-search {
      padding: 8px 30px;
      background-color: #d2691e;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.2s;
      height: 38px;
    }

    .btn-search:hover:not(:disabled) {
      background-color: #b8571a;
    }

    .btn-search:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }

    /* Accordion Section */
    .accordion-section {
      margin-bottom: 0;
      margin-top: 15px;
    }

    .accordion-wrapper {
      background-color: white;
      border-radius: 0;
      box-shadow: none;
      border: 1px solid #e0e0e0;
      overflow: hidden;
    }

    .accordion-header {
      padding: 12px 20px;
      background-color: #f8f9fa;
      border-bottom: 1px solid #e0e0e0;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: background-color 0.2s;
    }

    .accordion-header:hover {
      background-color: #f0f0f0;
    }

    .accordion-title {
      color: #333;
      font-size: 14px;
      font-weight: 500;
      margin: 0;
    }

    .accordion-icon {
      color: #d2691e;
      font-size: 12px;
      transition: transform 0.3s;
    }

    .accordion-header.active .accordion-icon {
      transform: rotate(180deg);
    }

    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
      background-color: white;
    }

    .accordion-content.active {
      max-height: 2000px;
    }

    .accordion-inner {
      padding: 20px;
    }

    /* History Table */
    .history-table {
      width: 100%;
      border-collapse: collapse;
    }

    .history-table thead {
      background-color: #fafafa;
    }

    .history-table thead th {
      padding: 12px 15px;
      text-align: left;
      color: #d2691e;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      border-bottom: 2px solid #e0e0e0;
    }

    .history-table tbody td {
      padding: 12px 15px;
      border-bottom: 1px solid #f0f0f0;
      color: #333;
      font-size: 13px;
    }

    .history-table tbody tr:hover {
      background-color: #fafafa;
    }

    .history-empty {
      text-align: center;
      padding: 30px;
      color: #999;
      font-size: 14px;
    }

    /* Dispatch Button Section - POSITIONED ABOVE TABLE */
    .dispatch-section {
      display: none;
      justify-content: flex-end;
      padding: 15px 0;
      margin: 0;
      margin-top: 15px;
    }

    .dispatch-section.active {
      display: flex;
    }

    .btn-dispatch {
      padding: 8px 30px;
      background-color: #d2691e;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .btn-dispatch:hover {
      background-color: #b8571a;
    }

    /* Table Section - Starts AFTER dispatch button */
    .table-section {
      background-color: white;
      border-radius: 0;
      box-shadow: none;
      border: 1px solid #e0e0e0;
      overflow: hidden;
      min-height: 400px;
      margin-top: 0;
    }

    .table-container {
      overflow-x: visible;
      padding: 0;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin: 0;
    }

    .data-table thead {
      background-color: #f8f9fa;
    }

    .data-table thead th {
      padding: 12px 15px;
      text-align: left;
      color: #d2691e;
      font-size: 13px;
      font-weight: 600;
      text-transform: capitalize;
      letter-spacing: 0;
      border-bottom: 1px solid #e0e0e0;
      border: none;
    }

    .data-table tbody td {
      padding: 12px 15px;
      border-bottom: 1px solid #f0f0f0;
      color: #333;
      font-size: 14px;
      border-left: none;
      border-right: none;
    }

    .data-table tbody tr:last-child td {
      border-bottom: none;
    }

    .data-table tbody tr:hover {
      background-color: #fafafa;
    }

    .checkbox-column {
      width: 50px;
      text-align: center;
    }

    .data-checkbox {
      width: 16px;
      height: 16px;
      cursor: pointer;
      accent-color: #d2691e;
    }

    .action-btn {
      padding: 5px 15px;
      background-color: white;
      color: #d2691e;
      border: 1px solid #d2691e;
      border-radius: 3px;
      font-size: 13px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .action-btn:hover {
      background-color: #d2691e;
      color: white;
    }

    .delete-btn {
      padding: 5px 12px;
      background-color: white;
      color: #dc3545;
      border: 1px solid #dc3545;
      border-radius: 3px;
      font-size: 13px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .delete-btn:hover {
      background-color: #dc3545;
      color: white;
    }

    /* Badge */
    .badge-status {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .badge-success {
      background-color: #d4edda;
      color: #155724;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 80px 20px;
      color: #999;
    }

    .empty-state i {
      font-size: 64px;
      color: #ddd;
      margin-bottom: 15px;
    }

    .empty-state p {
      font-size: 14px;
      margin: 0;
      color: #d2691e;
    }

    /* Loading State */
    .loading-state {
      text-align: center;
      padding: 80px 20px;
    }

    .loading-spinner {
      width: 40px;
      height: 40px;
      border: 4px solid #f0f0f0;
      border-top-color: #d2691e;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 15px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .loading-text {
      color: #666;
      font-size: 14px;
    }

    /* Alert Messages */
    .alert-container {
      margin-bottom: 20px;
    }

    .alert-message {
      padding: 12px 20px;
      border-radius: 4px;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .alert-warning {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }

    .alert-close {
      background: none;
      border: none;
      color: inherit;
      font-size: 20px;
      cursor: pointer;
      padding: 0;
      width: 20px;
      height: 20px;
      line-height: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .filter-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <!-- Header Section -->
  <div class="header">
    <div class="logo">
      <img src="{{asset('images/logo.png.jpg')}}" class="img">
      <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="pfp">
      <div class="session">
        <h5>Session:</h5>
        <select>
          <option>2025-2026</option>
          <option>2024-2025</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{route('profile.index') }}"><i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</a></li>
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

      <!-- Sidebar Accordion -->
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
                <li><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i>Employee</a></li>
                <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group" id="side-icon"></i>Batches Assignment</a></li>
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
              <i class="fa-solid fa-user-group" id="side-icon"></i>Master
            </button>
          </h2>
          <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('courses.index') }}"><i class="fa-solid fa-book-open" id="side-icon"></i>Courses</a></li>
                <li><a class="item" href="{{ route('batches.index') }}"><i class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i>Batches</a></li>
                <li><a class="item" href="{{ route('master.scholarship.index') }}"><i class="fa-solid fa-graduation-cap" id="side-icon"></i>Scholarship</a></li>
                <li><a class="item" href="{{ route('fees.index') }}"><i class="fa-solid fa-credit-card" id="side-icon"></i>Fees Master</a></li>
                <li><a class="item" href="{{ route('master.other_fees.index') }}"><i class="fa-solid fa-wallet" id="side-icon"></i>Other Fees Master</a></li>
                <li><a class="item" href="{{ route('branches.index') }}"><i class="fa-solid fa-diagram-project" id="side-icon"></i>Branch Management</a></li>
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
                <li><a class="item" href="{{ route('sessions.index') }}"><i class="fa-solid fa-calendar-day" id="side-icon"></i>Session</a></li>
                <li><a class="item" href="{{ route('calendar.index') }}"><i class="fa-solid fa-calendar-days" id="side-icon"></i>Calendar</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Migrate</a></li>
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
                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i>Inquiry Management</a></li>
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
              <i class="fa-solid fa-credit-card" id="side-icon"></i>Fees Management
            </button>
          </h2>
          <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('fees.management.index') }}"><i class="fa-solid fa-money-bill-wave" id="side-icon"></i>Fees Collection</a></li>
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
              <i class="fa-solid fa-user-check" id="side-icon"></i>Attendance Management
            </button>
          </h2>
          <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i>Employee</a></li>
                <li><a class="item" href="{{ route('attendance.student.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i>Student</a></li>
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
              <i class="fa-solid fa-book-open" id="side-icon"></i>Study Material Co...
            </button>
          </h2>
          <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('units.index') }}"><i class="fa-solid fa-book" id="side-icon"></i>Units</a></li>
                <li><a class="item active" href="{{ route('study_material.dispatch.index') }}"><i class="fa-solid fa-truck" id="side-icon"></i>Dispatch Material</a></li>
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
              <i class="fa-solid fa-chart-column" id="side-icon"></i>Test Series Manag...
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

        <!-- Reports -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine"
              id="accordion-button">
              <i class="fa-solid fa-square-poll-horizontal" id="side-icon"></i>Reports
            </button>
          </h2>
          <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('reports.walkin.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Walk In</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-calendar-days" id="side-icon"></i>Attendance</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Test Series</a></li>
                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry History</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Onboard History</a></li>
              </ul>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Right Content Area -->
    <div class="right" id="right">
      <div class="container-fluid">
        <!-- Page Title -->
        <h2 class="page-title">Dispatch Study Material</h2>

        <!-- Alert Container -->
        <div class="alert-container" id="alertContainer"></div>

        <!-- Filter Section -->
        <div class="filter-section">
          <div class="filter-row">
            <div class="filter-item">
              <label class="filter-label">Select Course</label>
              <select class="filter-select" id="courseSelect">
                <option value="">Select Course</option>
                @foreach($courses as $course)
                  <option value="{{ $course['_id'] }}">{{ $course['name'] }}</option>
                @endforeach
              </select>
            </div>

            <div class="filter-item">
              <label class="filter-label">Select batch</label>
              <select class="filter-select" id="batchSelect" disabled>
                <option value="">Select batch</option>
              </select>
            </div>

            <div class="filter-item">
              <button class="btn-search" id="searchBtn" disabled>Search</button>
            </div>
          </div>
        </div>

        <!-- Accordion Section -->
        <div class="accordion-section">
          <div class="accordion-wrapper">
            <div class="accordion-header" id="accordionHeader">
              <h3 class="accordion-title">Recently Dispatched Records</h3>
              <i class="fas fa-chevron-down accordion-icon"></i>
            </div>
            <div class="accordion-content" id="accordionContent">
              <div class="accordion-inner">
                <div id="historyLoading" class="history-empty" style="display: none;">
                  <div class="loading-spinner" style="width: 30px; height: 30px; margin: 0 auto 10px;"></div>
                  <p>Loading dispatch history...</p>
                </div>
                
                <div id="historyEmpty" class="history-empty">
                  <p>No dispatch records found</p>
                </div>

                <div id="historyTableContainer" style="display: none;">
                  <div class="table-container">
                    <table class="history-table">
                      <thead>
                        <tr>
                          <th>Roll No.</th>
                          <th>Student Name</th>
                          <th>Father Name</th>
                          <th>Course</th>
                          <th>Batch</th>
                          <th>Dispatched At</th>
                          <th>Dispatched By</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="historyTableBody">
                        <!-- Dynamic content -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Dispatch Button Section - OUTSIDE TABLE -->
        <div class="dispatch-section" id="dispatchSection">
          <button class="btn-dispatch" id="dispatchBtn">Dispatch</button>
        </div>

        <!-- Table Section -->
        <div class="table-section">
          <!-- Loading State -->
          <div class="loading-state" id="loadingState" style="display: none;">
            <div class="loading-spinner"></div>
            <p class="loading-text">Loading students...</p>
          </div>

          <!-- Empty State -->
          <div class="empty-state" id="emptyState">
            <i class="fas fa-search"></i>
            <p>Please select a course and batch to view students</p>
          </div>

          <!-- Table Container -->
          <div class="table-container" id="tableContainer" style="display: none;">
            <table class="data-table">
              <thead>
                <tr>
                  <th class="checkbox-column">
                    <input type="checkbox" class="data-checkbox" id="selectAllCheckbox">
                  </th>
                  <th>Roll No.</th>
                  <th>Student Name</th>
                  <th>Father Name</th>
                  <th>Batch Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="studentsTableBody">
                <!-- Dynamic content -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

  <!-- Custom JS -->
  <script src="{{asset('js/emp.js')}}"></script>

  <script>
    $(document).ready(function() {
      // CSRF token setup
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      let studentsData = [];

      // Accordion toggle
      $('#accordionHeader').on('click', function() {
        $(this).toggleClass('active');
        $('#accordionContent').toggleClass('active');
        
        if ($('#accordionContent').hasClass('active') && $('#historyTableBody').children().length === 0) {
          loadDispatchHistory();
        }
      });

      // Load dispatch history
      function loadDispatchHistory() {
        $('#historyLoading').show();
        $('#historyEmpty').hide();
        $('#historyTableContainer').hide();

        $.ajax({
          url: '{{ route("study_material.dispatch.get-history") }}',
          method: 'GET',
          success: function(response) {
            if (response.success && response.dispatches.data && response.dispatches.data.length > 0) {
              displayDispatchHistory(response.dispatches.data);
            } else {
              $('#historyLoading').hide();
              $('#historyEmpty').show();
            }
          },
          error: function(xhr) {
            console.error(xhr);
            $('#historyLoading').hide();
            $('#historyEmpty').html('<p>Error loading dispatch history</p>').show();
          }
        });
      }

      // Display dispatch history
      function displayDispatchHistory(dispatches) {
        const tbody = $('#historyTableBody');
        tbody.empty();

        dispatches.forEach(function(dispatch) {
          const dispatchedAt = new Date(dispatch.dispatched_at).toLocaleString();
          const row = `
            <tr>
              <td>${dispatch.roll_no || 'N/A'}</td>
              <td>${dispatch.student_name || 'N/A'}</td>
              <td>${dispatch.father_name || 'N/A'}</td>
              <td>${dispatch.course_name || 'N/A'}</td>
              <td>${dispatch.batch_name || 'N/A'}</td>
              <td>${dispatchedAt}</td>
              <td>${dispatch.dispatched_by || 'N/A'}</td>
              <td><span class="badge-status badge-success">Dispatched</span></td>
              <td>
                <button class="delete-btn delete-dispatch-btn" data-id="${dispatch._id}">
                  Delete
                </button>
              </td>
            </tr>
          `;
          tbody.append(row);
        });

        $('#historyLoading').hide();
        $('#historyTableContainer').show();
      }

      // Delete dispatch record
      $(document).on('click', '.delete-dispatch-btn', function() {
        const dispatchId = $(this).data('id');
        
        if (!confirm('Are you sure you want to delete this dispatch record?')) {
          return;
        }

        $.ajax({
          url: `/study_material/dispatch/${dispatchId}`,
          method: 'DELETE',
          success: function(response) {
            if (response.success) {
              showAlert(response.message, 'success');
              loadDispatchHistory();
            } else {
              showAlert('Error deleting record', 'error');
            }
          },
          error: function(xhr) {
            showAlert('Error deleting record', 'error');
            console.error(xhr);
          }
        });
      });

      // Course select change
      $('#courseSelect').on('change', function() {
        const courseId = $(this).val();
        const batchSelect = $('#batchSelect');
        const searchBtn = $('#searchBtn');

        batchSelect.html('<option value="">Select batch</option>');
        batchSelect.prop('disabled', true);
        searchBtn.prop('disabled', true);

        resetView();

        if (courseId) {
          $.ajax({
            url: '{{ route("study_material.dispatch.get-batches") }}',
            method: 'POST',
            data: { course_id: courseId },
            success: function(response) {
              if (response.success && response.batches.length > 0) {
                response.batches.forEach(function(batch) {
                  batchSelect.append(
                    `<option value="${batch._id}">${batch.name}</option>`
                  );
                });
                batchSelect.prop('disabled', false);
              } else {
                showAlert('No batches found for this course', 'warning');
              }
            },
            error: function(xhr) {
              showAlert('Error loading batches', 'error');
              console.error(xhr);
            }
          });
        }
      });

      // Batch select change
      $('#batchSelect').on('change', function() {
        const batchId = $(this).val();
        $('#searchBtn').prop('disabled', !batchId);
      });

      // Search button click
      $('#searchBtn').on('click', function() {
        const courseId = $('#courseSelect').val();
        const batchId = $('#batchSelect').val();

        if (!courseId) {
          showAlert('Please select a course', 'warning');
          return;
        }

        showLoading();

        $.ajax({
          url: '{{ route("study_material.dispatch.get-students") }}',
          method: 'POST',
          data: {
            course_id: courseId,
            batch_id: batchId
          },
          success: function(response) {
            if (response.success) {
              studentsData = response.students;
              displayStudents(response.students);
            } else {
              showAlert('Error loading students', 'error');
              showEmptyState('No students found');
            }
          },
          error: function(xhr) {
            showAlert('Error loading students', 'error');
            console.error(xhr);
            showEmptyState('Error loading data');
          }
        });
      });

      // Display students
      function displayStudents(students) {
        const tbody = $('#studentsTableBody');
        tbody.empty();

        if (students.length === 0) {
          showEmptyState('No students found for the selected criteria');
          return;
        }

        students.forEach(function(student) {
          const row = `
            <tr data-student-id="${student._id}">
              <td class="checkbox-column">
                <input type="checkbox" class="data-checkbox student-checkbox" 
                       value="${student._id}">
              </td>
              <td>${student.roll_no || 'N/A'}</td>
              <td>${student.name || 'N/A'}</td>
              <td>${student.father_name || 'N/A'}</td>
              <td>${student.batch_name || 'N/A'}</td>
              <td>
                <button class="action-btn" data-student-id="${student._id}">
                  View
                </button>
              </td>
            </tr>
          `;
          tbody.append(row);
        });

        $('#loadingState').hide();
        $('#emptyState').hide();
        $('#tableContainer').show();
        $('#dispatchSection').addClass('active');
      }

      // Select all checkbox
      $('#selectAllCheckbox').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.student-checkbox').prop('checked', isChecked);
      });

      // Individual checkbox change
      $(document).on('change', '.student-checkbox', function() {
        const totalCheckboxes = $('.student-checkbox').length;
        const checkedCheckboxes = $('.student-checkbox:checked').length;
        $('#selectAllCheckbox').prop('checked', totalCheckboxes === checkedCheckboxes);
      });

      // Dispatch button click
      $('#dispatchBtn').on('click', function() {
        const selectedStudents = [];
        $('.student-checkbox:checked').each(function() {
          selectedStudents.push($(this).val());
        });

        if (selectedStudents.length === 0) {
          showAlert('Please select at least one student', 'warning');
          return;
        }

        if (!confirm(`Are you sure you want to dispatch study material to ${selectedStudents.length} student(s)?`)) {
          return;
        }

        const dispatchBtn = $(this);
        const originalText = dispatchBtn.text();
        dispatchBtn.prop('disabled', true).text('Dispatching...');

        $.ajax({
          url: '{{ route("study_material.dispatch.dispatch-material") }}',
          method: 'POST',
          data: {
            student_ids: selectedStudents
          },
          success: function(response) {
            if (response.success) {
              showAlert(response.message, 'success');
              $('.student-checkbox, #selectAllCheckbox').prop('checked', false);
              
              if ($('#accordionContent').hasClass('active')) {
                loadDispatchHistory();
              }
            } else {
              showAlert(response.message || 'Error dispatching material', 'error');
            }
          },
          error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || 'Error dispatching material';
            showAlert(errorMessage, 'error');
            console.error(xhr);
          },
          complete: function() {
            dispatchBtn.prop('disabled', false).text(originalText);
          }
        });
      });

      // Helper functions
      function showLoading() {
        $('#emptyState').hide();
        $('#tableContainer').hide();
        $('#dispatchSection').removeClass('active');
        $('#loadingState').show();
      }

      function showEmptyState(message) {
        $('#loadingState').hide();
        $('#tableContainer').hide();
        $('#dispatchSection').removeClass('active');
        $('#emptyState').html(`
          <i class="fas fa-search"></i>
          <p>${message}</p>
        `).show();
      }

      function resetView() {
        $('#loadingState').hide();
        $('#tableContainer').hide();
        $('#dispatchSection').removeClass('active');
        $('#emptyState').html(`
          <i class="fas fa-search"></i>
          <p>Please select a course and batch to view students</p>
        `).show();
      }

      function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                         type === 'error' ? 'alert-error' : 'alert-warning';
        
        const alertHtml = `
          <div class="alert-message ${alertClass}">
            <span>${message}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
          </div>
        `;
        
        $('#alertContainer').html(alertHtml);
        
        setTimeout(function() {
          $('.alert-message').fadeOut(function() {
            $(this).remove();
          });
        }, 5000);
      }
    });
  </script>
</body>
</html>