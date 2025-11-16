<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Test Series - {{ $student->student_name ?? $student->name }}</title>
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

    /* Test Series Specific Styles */
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
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }

    .stats-card {
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 20px;
    }

    .stats-card h6 {
      color: #e05301;
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 15px;
    }

    .chart-container {
      position: relative;
      height: 200px;
    }

    .attendance-donut {
      max-width: 200px;
      margin: 0 auto;
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
        <!-- Tab Navigation - CORRECT ROUTES FROM YOUR SYSTEM -->
        <div class="tab-navigation">
          <a href="{{ url('/student_management/student_viewdetail/' . $student->_id . '/studenttab_detail') }}" class="tab-btn">
            Student Detail
          </a>
          
          <a href="{{ url('/student_management/student_viewdetail/' . $student->_id . '/studenttab_attendance') }}" class="tab-btn">
            Student attendance
          </a>
          
          <a href="{{ url('/student_management/student_viewdetail/' . $student->_id . '/studenttab_fee') }}" class="tab-btn">
            Fees management
          </a>
          
          <a href="{{ url('/student_management/student_viewdetail/' . $student->_id . '/student_testseries') }}" class="tab-btn active">
            Test Series
          </a>
        </div>

        <!-- Test Series Content -->
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
              <div id="type1-content" class="type-content">
                <!-- Stats Grid -->
                <div class="stats-grid">
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
                      {{ number_format($attendancePercentageGeneral ?? 0, 0) }}%
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
                      @php
                        $generalAllTests = collect($testSeries['general']['board'] ?? [])
                          ->merge($testSeries['general']['neet'] ?? [])
                          ->merge($testSeries['general']['iit'] ?? [])
                          ->filter(function($test) {
                            return !empty($test);
                          });
                      @endphp
                      
                      @forelse($generalAllTests as $index => $test)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $test['test_name'] ?? 'N/A' }}</td>
                        <td>{{ $test['test_date'] ?? 'N/A' }}</td>
                        <td>{{ $test['total_marks'] ?? 0 }}</td>
                        <td>{{ $test['obtained_marks'] ?? 0 }}</td>
                        <td>{{ $test['topper_marks'] ?? 0 }}</td>
                        <td>{{ $test['avg_marks'] ?? 0 }}</td>
                        <td>{{ $test['batch_rank'] ?? 'N/A' }}</td>
                        <td>{{ $test['overall_rank'] ?? 'N/A' }}</td>
                        <td>{{ number_format($test['percentage'] ?? 0, 2) }}%</td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                          No data available in table
                        </td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>

                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <span>Showing {{ $generalAllTests->count() }} to {{ $generalAllTests->count() }} of {{ $generalAllTests->count() }} entries</span>
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
                      @forelse($generalAllTests as $index => $test)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $test['test_name'] ?? 'N/A' }}</td>
                        <td>{{ $test['test_date'] ?? 'N/A' }}</td>
                        <td>{{ $test['total_marks'] ?? 0 }}</td>
                        <td>{{ $test['obtained_marks'] ?? 0 }}</td>
                        <td>{{ $test['topper_marks'] ?? 0 }}</td>
                        <td>{{ $test['avg_marks'] ?? 0 }}</td>
                        <td>{{ $test['batch_rank'] ?? 'N/A' }}</td>
                        <td>{{ $test['overall_rank'] ?? 'N/A' }}</td>
                        <td>{{ number_format($test['percentage'] ?? 0, 2) }}%</td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                          No data available in table
                        </td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>

                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <span>Showing {{ $generalAllTests->count() }} to {{ $generalAllTests->count() }} of {{ $generalAllTests->count() }} entries</span>
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
                    @forelse($testSeries['spr']['board'] ?? [] as $test)
                    <tr>
                      <td>{{ $test['test_date'] ?? 'N/A' }}</td>
                      <td>{{ $test['test_name'] ?? 'N/A' }}</td>
                      <td>{{ $test['total_marks'] ?? 0 }}</td>
                      <td>{{ number_format($test['percentage'] ?? 0, 2) }}%</td>
                      <td>{{ $test['overall_rank'] ?? 'N/A' }}</td>
                      <td>{{ $test['batch_rank'] ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="6" class="text-center text-muted py-3">No test data available</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>

                <table class="test-table" style="margin-top: 20px;">
                  <tbody>
                    <tr class="statistics-row">
                      <td>AVG. Batch Rank: {{ ($overallRankSpr ?? 0) > 0 ? number_format($overallRankSpr, 1) : 'N/A' }}</td>
                      <td>AVG. Overall Rank: {{ ($overallRankSpr ?? 0) > 0 ? number_format($overallRankSpr, 1) : 'N/A' }}</td>
                      <td>AVG. % (ALL TEST): {{ ($overallPercentageSpr ?? 0) > 0 ? number_format($overallPercentageSpr, 2) . '%' : 'N/A' }}</td>
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
                    @forelse($testSeries['spr']['neet'] ?? [] as $test)
                    <tr>
                      <td>{{ $test['test_date'] ?? 'N/A' }}</td>
                      <td>{{ $test['test_name'] ?? 'N/A' }}</td>
                      <td>{{ $test['total_marks'] ?? 0 }}</td>
                      <td>{{ number_format($test['percentage'] ?? 0, 2) }}%</td>
                      <td>{{ $test['overall_rank'] ?? 'N/A' }}</td>
                      <td>{{ $test['batch_rank'] ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="6" class="text-center text-muted py-3">No test data available</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>

                <table class="test-table" style="margin-top: 20px;">
                  <tbody>
                    <tr class="statistics-row">
                      <td>AVG. Batch Rank: {{ ($overallRankSpr ?? 0) > 0 ? number_format($overallRankSpr, 1) : 'N/A' }}</td>
                      <td>AVG. Overall Rank: {{ ($overallRankSpr ?? 0) > 0 ? number_format($overallRankSpr, 1) : 'N/A' }}</td>
                      <td>AVG. % (ALL TEST): {{ ($overallPercentageSpr ?? 0) > 0 ? number_format($overallPercentageSpr, 2) . '%' : 'N/A' }}</td>
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
                    @forelse($testSeries['spr']['iit'] ?? [] as $test)
                    <tr>
                      <td>{{ $test['test_date'] ?? 'N/A' }}</td>
                      <td>{{ $test['test_name'] ?? 'N/A' }}</td>
                      <td>{{ $test['total_marks'] ?? 0 }}</td>
                      <td>{{ number_format($test['percentage'] ?? 0, 2) }}%</td>
                      <td>{{ $test['overall_rank'] ?? 'N/A' }}</td>
                      <td>{{ $test['batch_rank'] ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="6" class="text-center text-muted py-3">No test data available</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>

                <table class="test-table" style="margin-top: 20px;">
                  <tbody>
                    <tr class="statistics-row">
                      <td>AVG. Batch Rank: {{ ($overallRankSpr ?? 0) > 0 ? number_format($overallRankSpr, 1) : 'N/A' }}</td>
                      <td>AVG. Overall Rank: {{ ($overallRankSpr ?? 0) > 0 ? number_format($overallRankSpr, 1) : 'N/A' }}</td>
                      <td>AVG. % (ALL TEST): {{ ($overallPercentageSpr ?? 0) > 0 ? number_format($overallPercentageSpr, 2) . '%' : 'N/A' }}</td>
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
    // COMPLETE WORKING JAVASCRIPT
    document.addEventListener('DOMContentLoaded', function() {
      console.log('Test Series page loaded successfully');
      
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

      // Pattern switching for SPR
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

      // Initialize Charts
      initializeCharts();
    });

    function initializeCharts() {
      // Overall Rank Chart
      const rankCtx = document.getElementById('overallRankChart');
      if (rankCtx) {
        const rankValue = {{ $overallRankGeneral ?? 0 }};
        try {
          new Chart(rankCtx, {
            type: 'bar',
            data: {
              labels: ['Overall Rank'],
              datasets: [{
                label: 'Average Rank',
                data: [rankValue],
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
        } catch(error) {
          console.error('Error creating rank chart:', error);
        }
      }

      // Overall Percentage Chart
      const percentCtx = document.getElementById('overallPercentageChart');
      if (percentCtx) {
        const percentValue = {{ $overallPercentageGeneral ?? 0 }};
        try {
          new Chart(percentCtx, {
            type: 'bar',
            data: {
              labels: ['Overall %'],
              datasets: [{
                label: 'Average Percentage',
                data: [percentValue],
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
        } catch(error) {
          console.error('Error creating percentage chart:', error);
        }
      }

      // Test Attendance Donut
      const testAttCtx = document.getElementById('testAttendanceChart');
      if (testAttCtx) {
        const attendancePercent = {{ $attendancePercentageGeneral ?? 0 }};
        try {
          new Chart(testAttCtx, {
            type: 'doughnut',
            data: {
              labels: ['Attended', 'Not Attended'],
              datasets: [{
                data: [attendancePercent, 100 - attendancePercent],
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
        } catch(error) {
          console.error('Error creating attendance chart:', error);
        }
      }
    }
  </script>
</body>
</html>