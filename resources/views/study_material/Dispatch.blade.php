<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dispatch Material - Synthesis</title>
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  
  <!-- Bootstrap 5.3.6 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

  <style>
    .right { padding: 0 !important; }
    .container-fluid { padding: 20px !important; }
    .page-title { font-size: 24px; font-weight: 600; color: #d2691e; margin-bottom: 20px; }
    .btn-primary { background-color: #d2691e; border-color: #d2691e; }
    .btn-primary:hover { background-color: #b8571a; border-color: #b8571a; }
    
    .filter-section {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    
    .filter-section .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-section select {
        width: 280px;
        height: 40px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 14px;
        padding: 8px 12px;
    }
    
    .filter-section select:focus {
        border: 2px solid #d2691e;
        outline: none;
    }
    
    .filter-section .btn-search {
        height: 40px;
        padding: 0 25px;
        margin-top: 0;
    }
    
    .error-text {
        color: #d2691e;
        font-size: 12px;
        margin-top: 4px;
        display: none;
    }
    
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .dispatch-btn-row {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 15px;
    }
    
    .btn-dispatch {
        background-color: #d2691e;
        border-color: #d2691e;
        color: white;
        padding: 8px 20px;
    }
    
    .btn-dispatch:hover {
        background-color: #b8571a;
        border-color: #b8571a;
        color: white;
    }
    
    .table thead th {
        background-color: #ffffff;
        font-weight: 600;
        color: #d2691e;
        border: none;
        border-bottom: 2px solid #f0f0f0;
        padding: 12px;
        font-size: 14px;
    }
    
    .table tbody td {
        padding: 12px;
        vertical-align: middle;
        border: 1px solid #f0f0f0;
    }
    
    .badge-success {
        background-color: #28a745 !important;
        color: white;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 12px;
    }
    
    .badge-warning {
        background-color: #ffc107 !important;
        color: #000;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 12px;
    }
    
    .select-error {
        border: 2px solid #d2691e !important;
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
          <option>{{ session('selected_session', '2025-2026') }}</option>
          <option>2024-2025</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
    <div class="left" id="sidebar">
      <div class="text" id="text">
        <h6>ADMIN</h6>
        <p>synthesisbikaner@gmail.com</p>
      </div>
      <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne" id="accordion-button">
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
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo" id="accordion-button">
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
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree" id="accordion-button">
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
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour" id="accordion-button">
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
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive" id="accordion-button">
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
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix" id="accordion-button">
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
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven" id="accordion-button">
              <i class="fa-solid fa-book-open" id="side-icon"></i>Study Material
            </button>
          </h2>
          <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('units.index') }}"><i class="fa-solid fa-book" id="side-icon"></i>Units</a></li>
                <li><a class="item active" href="{{ route('dispatch.index') }}"><i class="fa-solid fa-truck" id="side-icon"></i>Dispatch Material</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight" id="accordion-button">
              <i class="fa-solid fa-chart-column" id="side-icon"></i>Test Series Management
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
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine" id="accordion-button">
              <i class="fa-solid fa-square-poll-horizontal" id="side-icon"></i>Reports
            </button>
          </h2>
          <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Walk In</a></li>
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

    <div class="right" id="right">
      <div class="container-fluid">
        <h2 class="page-title">Dispatch Study Material</h2>
        
        <!-- Filter Section -->
        <div class="filter-section">
          <div class="form-group">
            <select id="course_name" required>
              <option value="">Select Course</option>
            </select>
            <span class="error-text" id="course-error">Course is required</span>
          </div>
          
          <div class="form-group">
            <select id="batch_name" required>
              <option value="">Select Batch</option>
            </select>
            <span class="error-text" id="batch-error">Batch is required</span>
          </div>
          
          <button class="btn btn-primary btn-search" id="searchBtn">Search</button>
        </div>
        
        <!-- Table Card -->
        <div class="card">
          <div class="card-body">
            <div class="dispatch-btn-row">
              <button class="btn btn-dispatch" id="dispatchBtn">Dispatch</button>
            </div>
            
            <table class="table table-hover" id="studentsTable">
              <thead>
                <tr>
                  <th><input type="checkbox" class="form-check-input" id="selectAll"></th>
                  <th>Roll Number</th>
                  <th>Student Name</th>
                  <th>Father Name</th>
                  <th>Batch Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="6" style="text-align:center;padding:30px;color:#999;">
                    Select course and batch, then click Search to load students
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <script src="{{asset('js/emp.js')}}"></script>

  <script>
  $(document).ready(function() {
      //   HARDCODED COURSES - Same as Units
      const coursesData = {
          'Anthesis 11th NEET': [],
          'Momentum 12th NEET': [],
          'Dynamic Target NEET': [],
          'Impulse 11th IIT': [],
          'Intensity 12th IIT': [],
          'Thurst Target IIT': [],
          'Seedling 10th': [],
          'Plumule 9th': [],
          'Radicle 8th': [],
          'Nucleus 7th': [],
          'Atom 6th': []
      };
      
      // Current selected batch name
      let currentBatchName = '';
      
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

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

            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <i class="fa-solid fa-user-group"></i>User Management
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/user management/emp/emp.html">
                                        <i class="fa-solid fa-user"></i>Employee
                                    </a></li>
                                <li><a href="/user management/batches a/batchesa.html">
                                        <i class="fa-solid fa-user-group"></i>Batches Assignment
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
>>>>>>> 57074747fa185acdc36be8f29d3ed2f3ab99d8c1
=======
>>>>>>> 2a9dc41185145fb5634f3ab52db0376f6d0dad60

      // Load courses on page load
      function loadCourses() {
          let options = '<option value="">Select Course</option>';
          Object.keys(coursesData).forEach(function(course) {
              options += `<option value="${course}">${course}</option>`;
          });
          $('#course_name').html(options);
      }

      loadCourses();

      //   DYNAMIC BATCH LOADING FROM DATABASE - EXACTLY LIKE FEES MANAGEMENT
      $('#course_name').on('change', function() {
          let courseName = $(this).val();
          
          console.log(' Course selected:', courseName);
          
          // Clear error
          $('#course-error').hide();
          $(this).removeClass('select-error');
          
          // Reset batch dropdown
          $('#batch_name').html('<option value="">Select Batch</option>');
          $('#batch-error').hide();
          $('#batch_name').removeClass('select-error');
          currentBatchName = '';
          
          // Clear table
          $('#studentsTable tbody').html('<tr><td colspan="6" style="text-align:center;padding:30px;color:#999;">Select course and batch, then click Search to load students</td></tr>');
          
          if (!courseName) {
              return;
          }
          
          //   FETCH BATCHES FROM DATABASE - REAL TIME DATA
          console.log('📡 Fetching batches from database...');
          $('#batch_name').html('<option value="">Loading batches...</option>').prop('disabled', true);
          
          $.ajax({
              url: '/study_material/dispatch/get-batches',
              type: 'GET',
              data: { course_name: courseName },
              success: function(response) {
                  console.log('  Batches loaded:', response);
                  
                  let options = '<option value="">Select Batch</option>';
                  
                  if (response.success && response.batches && response.batches.length > 0) {
                      response.batches.forEach(function(batch) {
                          // Handle both _id and id fields
                          let batchId = batch._id || batch.id;
                          let batchName = batch.name || batch.batch_name;
                          options += `<option value="${batchName}">${batchName}</option>`;
                      });
                      $('#batch_name').html(options).prop('disabled', false);
                      console.log(' ', response.batches.length, 'batches loaded');
                  } else {
                      $('#batch_name').html('<option value="">No batches found</option>').prop('disabled', true);
                      console.log('  No batches found for course:', courseName);
                  }
              },
              error: function(xhr) {
                  console.error(' Error fetching batches:', xhr);
                  $('#batch_name').html('<option value="">Error loading batches</option>').prop('disabled', true);
                  alert('Error loading batches. Please try again.');
              }
          });
      });

      // Batch change
      $('#batch_name').on('change', function() {
          $('#batch-error').hide();
          $(this).removeClass('select-error');
          currentBatchName = $(this).val();
          console.log('  Batch selected:', currentBatchName);
      });

      //   Search button click - FETCH REAL DATA FROM DATABASE
      $('#searchBtn').on('click', function() {
          let courseName = $('#course_name').val();
          let batchName = $('#batch_name').val();
          
          console.log(' Search clicked:', { courseName, batchName });
          
          // Reset errors
          $('#course-error').hide();
          $('#batch-error').hide();
          $('#course_name').removeClass('select-error');
          $('#batch_name').removeClass('select-error');
          
          let hasError = false;
          
          if (!courseName) {
              $('#course-error').show();
              $('#course_name').addClass('select-error');
              hasError = true;
          }
          
          if (!batchName) {
              $('#batch-error').show();
              $('#batch_name').addClass('select-error');
              hasError = true;
          }
          
          if (hasError) {
              console.log(' Validation failed');
              return;
          }
          
          currentBatchName = batchName;
          loadStudents(courseName, batchName);
      });

      //   Load students function - REAL TIME DATA FROM DATABASE
      function loadStudents(courseName, batchName) {
          console.log('📡 Loading students...', { courseName, batchName });
          
          let tbody = $('#studentsTable tbody');
          tbody.html('<tr><td colspan="6" style="text-align:center;padding:30px;">Loading students...</td></tr>');
          
          $.ajax({
              url: '/study_material/dispatch/get-students',
              type: 'GET',
              data: {
                  course_name: courseName,
                  batch_name: batchName
              },
              success: function(response) {
                  console.log('=== STUDENTS RESPONSE ===');
                  console.log('Full response:', response);
                  
                  if (response.debug) {
                      console.log('Debug info:', response.debug);
                      console.log('Available fields in student:', response.debug.sample_fields);
                  }
                  
                  if (response.students && response.students.length > 0) {
                      console.log('  Found', response.students.length, 'students');
                      console.log('First student:', response.students[0]);
                      if (response.students[0]._raw_fields) {
                          console.log('Raw field names:', response.students[0]._raw_fields);
                      }
                  }
                  
                  tbody.html('');
                  
                  if (response.success && response.students && response.students.length > 0) {
                      response.students.forEach(function(student) {
                          // Get values with fallbacks
                          let studentId = student._id || student.id || '';
                          let rollNo = student.roll_no || '-';
                          let studentName = student.student_name || student.name || '-';
                          let fatherName = student.father_name || '-';
                          let batchNameDisplay = student.batch_name || batchName || '-';
                          let isDispatched = student.is_dispatched;
                          
                          let statusBadge = isDispatched 
                              ? '<span class="badge-success">Dispatched</span>'
                              : '<span class="badge-warning">Pending</span>';
                          
                          let row = `
                              <tr>
                                  <td><input type="checkbox" class="form-check-input student-checkbox" value="${studentId}" data-roll="${rollNo}"></td>
                                  <td>${rollNo}</td>
                                  <td>${studentName}</td>
                                  <td>${fatherName}</td>
                                  <td>${batchNameDisplay}</td>
                                  <td>${statusBadge}</td>
                              </tr>
                          `;
                          tbody.append(row);
                      });
                      console.log('  Table rendered with', response.students.length, 'students');
                  } else {
                      tbody.html('<tr><td colspan="6" style="text-align:center;padding:30px;">No students found for this course and batch</td></tr>');
                      console.log('  No students found');
                  }
              },
              error: function(xhr) {
                  console.error(' Error fetching students:', xhr);
                  tbody.html('<tr><td colspan="6" style="text-align:center;padding:30px;color:red;">Error loading students. Please try again.</td></tr>');
              }
          });
      }

      // Select all checkbox
      $('#selectAll').on('change', function() {
          $('.student-checkbox').prop('checked', $(this).prop('checked'));
      });

      // Dispatch button click
      $('#dispatchBtn').on('click', function() {
          let selectedIds = [];
          
          $('.student-checkbox:checked').each(function() {
              selectedIds.push($(this).val());
          });
          
          if (selectedIds.length === 0) {
              alert('Please select at least one student to dispatch material');
              return;
          }
          
          if (!confirm(`Are you sure you want to dispatch material to ${selectedIds.length} student(s)?`)) {
              return;
          }
          
          console.log('📤 Dispatching to students:', selectedIds);
          
          let btn = $(this);
          btn.prop('disabled', true).text('Dispatching...');
          
          $.ajax({
              url: '/study_material/dispatch/dispatch-material',
              type: 'POST',
              data: {
                  student_ids: selectedIds
              },
              success: function(response) {
                  console.log('  Dispatch response:', response);
                  
                  if (response.success) {
                      alert(response.message);
                      
                      // Reload students
                      let courseName = $('#course_name').val();
                      let batchName = $('#batch_name').val();
                      loadStudents(courseName, batchName);
                      
                      // Uncheck select all
                      $('#selectAll').prop('checked', false);
                  } else {
                      alert('Error: ' + (response.message || 'Failed to dispatch material'));
                  }
              },
              error: function(xhr) {
                  console.error(' Dispatch error:', xhr);
                  alert('Error dispatching material. Please try again.');
              },
              complete: function() {
                  btn.prop('disabled', false).text('Dispatch');
              }
          });
      });
  });
  </script>
</body>
</html>