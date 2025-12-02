<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Units - Study Material</title>
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  
  <!-- Bootstrap 5.3.6 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  <style>
    .right { padding: 0 !important; }
    .container-fluid { padding: 20px !important; }
    .table-responsive { overflow-x: visible !important; overflow-y: visible !important; }
    #unitsTable { width: 100% !important; margin-bottom: 0 !important; }
    .table thead th { background-color: #ffffff; font-weight: 600; color: #d2691e; border: none; border-bottom: 2px solid #f0f0f0; padding: 12px; font-size: 14px; }
    .table tbody td { padding: 12px; vertical-align: middle; border: 1px solid #f0f0f0; }
    .table { border-collapse: separate; border-spacing: 0; }
    .table thead th:first-child { border-top-left-radius: 8px; }
    .table thead th:last-child { border-top-right-radius: 8px; }
    .dataTables_wrapper { overflow-x: visible !important; }
    .dataTables_scroll { overflow: visible !important; }
    .dataTables_scrollBody { overflow: visible !important; max-height: none !important; }
    .btn-primary { background-color: #d2691e; border-color: #d2691e; }
    .btn-primary:hover { background-color: #b8571a; border-color: #b8571a; }
    .page-title { font-size: 24px; font-weight: 600; color: #333; margin-bottom: 20px; }
    .btn-group .btn { background: none; border: none; font-size: 20px; color: #666; padding: 2px 8px; }
    .view-more-btn { background-color: #d2691e; border-color: #d2691e; color: white; }
    .view-more-btn:hover { background-color: #b8571a; border-color: #b8571a; color: white; }
    .card { border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 8px; margin-bottom: 0; }
    .card-body { padding: 20px; }
    .dataTables_wrapper .row { margin: 0; }
    .dataTables_info, .dataTables_paginate { padding-top: 15px; }
    #search-input { border: 1px solid #d2691e !important; border-radius: 4px; }
    #search-input:focus { outline: none; border-color: #d2691e !important; box-shadow: 0 0 0 0.2rem rgba(210, 105, 30, 0.25); }
    #entries-per-page { border: 1px solid #ced4da; border-radius: 4px; }
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
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{route('profile.index') }}"><i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log In</a></li>
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
      <div class="container-fluid">
        <h2 class="page-title">Units</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="dataTables_length">
            <label>Show 
              <select id="entries-per-page" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
              entries
            </label>
          </div>
          <button class="btn btn-primary" id="addUnitBtn">
            <i class="fas fa-plus"></i> Add Units
          </button>
        </div>
        <div class="d-flex justify-content-end mb-3">
          <div class="dataTables_filter">
            <label>Search: 
              <input type="search" id="search-input" class="form-control form-control-sm" placeholder="">
            </label>
          </div>
        </div>
        <div class="table-responsive">
          <table id="unitsTable" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Serial No.</th>
                <th>Course Name</th>
                <th>Subject</th>
                <th>Units</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="unitModalLabel">Add Units</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="unitForm">
          <div class="modal-body">
            <input type="hidden" id="unit_id" name="unit_id">
            <input type="hidden" id="session" name="session" value="{{ session('selected_session', '2025-2026') }}">
            <div class="mb-3">
              <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
              <select class="form-select" id="course_name" name="course_name" required>
                <option value="">Select Course</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="subject" class="form-label">Subject Name <span class="text-danger">*</span></label>
              <select class="form-select" id="subject" name="subject" required>
                <option value="">Select subject</option>
              </select>
              <small class="text-danger" id="subject-error" style="display:none;">Subject Name Is Required</small>
            </div>
            <div class="mb-3">
              <label class="form-label">Units <span class="text-danger">*</span></label>
              <div id="units-container">
                <div class="row mb-2 unit-row">
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="unit_number[]" placeholder="Unit Number" required>
                    <small class="text-danger unit-number-error" style="display:none;">Unit Number Is Required</small>
                  </div>
                  <div class="col-md-8">
                    <textarea class="form-control" name="unit_name[]" rows="1" placeholder="Unit Name" required></textarea>
                    <small class="text-danger unit-name-error" style="display:none;">Unit Name Is Required</small>
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-primary add-unit-btn">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="viewMoreModal" tabindex="-1" aria-labelledby="viewMoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewMoreModalLabel">View More</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-bold">Course Name</label>
            <p id="view_course_name" class="text-muted"></p>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Subject Name</label>
            <p id="view_subject" class="text-muted"></p>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Units</label>
            <div id="view_units_container"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="{{asset('js/emp.js')}}"></script>

  <script>
  $(document).ready(function() {
      let table;
      let currentSession = '{{ session("selected_session", "2025-2026") }}';
      
      const coursesData = {
          'Anthesis 11th NEET': ['Physics', 'Chemistry', 'Biology'],
          'Momentum 12th NEET': ['Physics', 'Chemistry', 'Biology'],
          'Dynamic Target NEET': ['Physics', 'Chemistry', 'Biology'],
          'Impulse 11th IIT': ['Physics', 'Chemistry', 'Mathematics'],
          'Intensity 12th IIT': ['Physics', 'Chemistry', 'Mathematics'],
          'Thurst Target IIT': ['Physics', 'Chemistry', 'Mathematics'],
          'Seedling 10th': ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'SST'],
          'Plumule 9th': ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'SST'],
          'Radicle 8th': ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'SST'],
          'Nucleus 7th': ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'SST', 'Mental Ability'],
          'Atom 6th': ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'SST', 'Mental Ability']
      };
      
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      function loadCourses() {
          let options = '<option value="">Select Course</option>';
          Object.keys(coursesData).forEach(function(course) {
              options += `<option value="${course}">${course}</option>`;
          });
          $('#course_name').html(options);
      }

      loadCourses();

      $('#course_name').on('change', function() {
          let courseName = $(this).val();
          $('#subject').html('<option value="">Select subject</option>');
          $('#subject-error').hide();
          
          if (courseName && coursesData[courseName]) {
              let options = '<option value="">Select subject</option>';
              coursesData[courseName].forEach(function(subject) {
                  options += `<option value="${subject}">${subject}</option>`;
              });
              $('#subject').html(options);
          }
      });

      $(document).on('click', '.add-unit-btn', function() {
          let newRow = `
              <div class="row mb-2 unit-row">
                  <div class="col-md-3">
                      <input type="text" class="form-control" name="unit_number[]" placeholder="Unit Number" required>
                      <small class="text-danger unit-number-error" style="display:none;">Unit Number Is Required</small>
                  </div>
                  <div class="col-md-8">
                      <textarea class="form-control" name="unit_name[]" rows="1" placeholder="Unit Name" required></textarea>
                      <small class="text-danger unit-name-error" style="display:none;">Unit Name Is Required</small>
                  </div>
                  <div class="col-md-1">
                      <button type="button" class="btn btn-sm btn-danger remove-unit-btn">
                          <i class="fas fa-minus"></i>
                      </button>
                  </div>
              </div>
          `;
          $('#units-container').append(newRow);
      });

      $(document).on('click', '.remove-unit-btn', function() {
          $(this).closest('.unit-row').remove();
      });

      function initDataTable() {
          table = $('#unitsTable').DataTable({
              processing: true,
              serverSide: true,
              ajax: {
                  url: '{{ route("units.getData") }}',
                  type: 'GET',
                  data: function(d) {
                      d.session = currentSession;
                  }
              },
              columns: [
                  { data: 'serial_no', name: 'serial_no', orderable: false, searchable: false, width: '10%' },
                  { data: 'course_name', name: 'course_name', width: '25%' },
                  { data: 'subject', name: 'subject', width: '20%' },
                  { 
                      data: 'action', 
                      name: 'units',
                      orderable: false,
                      searchable: false,
                      width: '20%',
                      render: function(data, type, row) {
                          return `<button class="btn btn-sm btn-primary view-more-btn" data-id="${data}">View More</button>`;
                      }
                  },
                  {
                      data: 'action',
                      name: 'action',
                      orderable: false,
                      searchable: false,
                      width: '10%',
                      render: function(data, type, row) {
                          return `
                              <div class="btn-group">
                                  <button type="button" class="btn btn-sm" data-bs-toggle="dropdown" aria-expanded="false">⋮</button>
                                  <ul class="dropdown-menu">
                                      <li><a class="dropdown-item edit-btn" href="#" data-id="${data}"><i class="fas fa-edit"></i> Edit</a></li>
                                      <li><a class="dropdown-item delete-btn text-danger" href="#" data-id="${data}"><i class="fas fa-trash"></i> Delete</a></li>
                                  </ul>
                              </div>
                          `;
                      }
                  }
              ],
              pageLength: 10,
              lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
              dom: 'rtip',
              scrollX: false,
              scrollY: false,
              scrollCollapse: false,
              language: {
                  processing: 'Loading...',
                  emptyTable: 'No units found',
                  paginate: {
                      previous: 'Previous',
                      next: 'Next'
                  }
              }
          });
      }

      initDataTable();

      $('#entries-per-page').on('change', function() {
          table.page.len($(this).val()).draw();
      });

      $('#search-input').on('keyup', function() {
          table.search($(this).val()).draw();
      });

      $('#addUnitBtn').on('click', function() {
          $('#unitForm')[0].reset();
          $('#unit_id').val('');
          $('#unitModal').removeData('editing-id');
          $('#unitModalLabel').text('Add Units');
          
          $('#units-container').html(`
              <div class="row mb-2 unit-row">
                  <div class="col-md-3">
                      <input type="text" class="form-control" name="unit_number[]" placeholder="Unit Number" required>
                      <small class="text-danger unit-number-error" style="display:none;">Unit Number Is Required</small>
                  </div>
                  <div class="col-md-8">
                      <textarea class="form-control" name="unit_name[]" rows="1" placeholder="Unit Name" required></textarea>
                      <small class="text-danger unit-name-error" style="display:none;">Unit Name Is Required</small>
                  </div>
                  <div class="col-md-1">
                      <button type="button" class="btn btn-sm btn-primary add-unit-btn">
                          <i class="fas fa-plus"></i>
                      </button>
                  </div>
              </div>
          `);
          
          loadCourses();
          $('#unitModal').modal('show');
      });

      $('#unitForm').on('submit', function(e) {
          e.preventDefault();
          
          if (!$('#subject').val()) {
              $('#subject-error').show();
              return false;
          }
          
          let units = [];
          let hasError = false;
          
          $('.unit-row').each(function() {
              let unitNumber = $(this).find('input[name="unit_number[]"]').val();
              let unitName = $(this).find('textarea[name="unit_name[]"]').val();
              
              if (!unitNumber || !unitName) {
                  hasError = true;
              } else {
                  units.push({
                      unit_number: unitNumber,
                      unit_name: unitName
                  });
              }
          });
          
          if (hasError) {
              return false;
          }
          
          // CRITICAL FIX: Get ID from TWO sources!
          let unitId = $('#unit_id').val() || $('#unitModal').data('editing-id');
          let isEdit = unitId && unitId.trim() !== '';
          
          console.log('==========================================');
          console.log('FORM SUBMISSION');
          console.log('Unit ID from hidden field:', $('#unit_id').val());
          console.log('Unit ID from modal data:', $('#unitModal').data('editing-id'));
          console.log('Final Unit ID:', unitId);
          console.log('Is Edit Mode?:', isEdit);
          console.log('==========================================');
          
          let formData = {
              course_name: $('#course_name').val(),
              subject: $('#subject').val(),
              units: units,
              session: $('#session').val()
          };
          
          let url;
          if (isEdit) {
              url = '/study_material/units/edit/' + unitId.trim();
              // formData._method = 'PUT';
              console.log('→ MODE: UPDATE');
              console.log('→ URL:', url);
          } else {
              url = '/study_material/units/store';
              console.log('→ MODE: CREATE');
              console.log('→ URL:', url);
          }
          
          console.log('→ Form Data:', formData);

          $.ajax({
              url: url,
              type: 'POST',
              data: formData,
              success: function(response) {
                  console.log('✓ SUCCESS:', response);
                  if (response.success) {
                      $('#unitModal').modal('hide');
                      $('#unitForm')[0].reset();
                      $('#unit_id').val('');
                      $('#unitModal').removeData('editing-id');
                      table.ajax.reload(null, false);
                      alert(response.message);
                  }
              },
              error: function(xhr) {
                  console.error('✗ ERROR:', xhr);
                  alert('Error: ' + (xhr.responseJSON?.message || xhr.statusText || 'Operation failed'));
              }
          });
      });

      $(document).on('click', '.view-more-btn', function() {
          let unitId = $(this).data('id');
          
          $.ajax({
              url: '/study_material/units/' + unitId,
              type: 'GET',
              success: function(response) {
                  if (response.success) {
                      $('#view_course_name').text(response.data.course_name);
                      $('#view_subject').text(response.data.subject);
                      
                      let unitsHtml = '';
                      if (Array.isArray(response.data.units)) {
                          response.data.units.forEach(function(unit) {
                              unitsHtml += `
                                  <div class="row mb-3">
                                      <div class="col-md-2">
                                          <label class="form-label">Unit Number</label>
                                          <input type="text" class="form-control" value="${unit.unit_number}" readonly>
                                      </div>
                                      <div class="col-md-10">
                                          <label class="form-label">Unit Name</label>
                                          <textarea class="form-control" rows="2" readonly>${unit.unit_name}</textarea>
                                      </div>
                                  </div>
                              `;
                          });
                      }
                      $('#view_units_container').html(unitsHtml);
                      $('#viewMoreModal').modal('show');
                  }
              },
              error: function(xhr) {
                  alert('Error loading unit details');
              }
          });
      });

      // ULTIMATE FIX: Edit button with DOUBLE backup!
      $(document).on('click', '.edit-btn', function(e) {
          e.preventDefault();
          let clickedUnitId = $(this).data('id');
          
          console.log('🔍 Edit clicked, ID from button:', clickedUnitId);
          
          $.ajax({
              url: '/study_material/units/' + clickedUnitId,
              type: 'GET',
              success: function(response) {
                  console.log('🔍 Response:', response);
                  
                  if (response.success) {
                      // CRITICAL: Try multiple ID sources!
                      let unitId = response.data._id || response.data.id || clickedUnitId;
                      
                      console.log('🔍 Got unit ID:', unitId);
                      console.log('🔍 Setting hidden field...');
                      
                      // Set in hidden field
                      $('#unit_id').val(unitId);
                      
                      // BACKUP: Store in modal data attribute
                      $('#unitModal').data('editing-id', unitId);
                      
                      console.log('🔍 Hidden field now contains:', $('#unit_id').val());
                      console.log('🔍 Modal data now contains:', $('#unitModal').data('editing-id'));
                      
                      $('#session').val(response.data.session);
                      $('#unitModalLabel').text('Edit Units');
                      
                      loadCourses();
                      $('#course_name').val(response.data.course_name);
                      
                      if (coursesData[response.data.course_name]) {
                          let options = '<option value="">Select subject</option>';
                          coursesData[response.data.course_name].forEach(function(subject) {
                              let selected = subject === response.data.subject ? 'selected' : '';
                              options += `<option value="${subject}" ${selected}>${subject}</option>`;
                          });
                          $('#subject').html(options);
                      }
                      
                      $('#units-container').html('');
                      if (Array.isArray(response.data.units) && response.data.units.length > 0) {
                          response.data.units.forEach(function(unit, index) {
                              let btnClass = index === 0 ? 'add-unit-btn btn-primary' : 'remove-unit-btn btn-danger';
                              let btnIcon = index === 0 ? 'fa-plus' : 'fa-minus';
                              
                              let row = `
                                  <div class="row mb-2 unit-row">
                                      <div class="col-md-3">
                                          <input type="text" class="form-control" name="unit_number[]" value="${unit.unit_number}" required>
                                      </div>
                                      <div class="col-md-8">
                                          <textarea class="form-control" name="unit_name[]" rows="1" required>${unit.unit_name}</textarea>
                                      </div>
                                      <div class="col-md-1">
                                          <button type="button" class="btn btn-sm ${btnClass}">
                                              <i class="fas ${btnIcon}"></i>
                                          </button>
                                      </div>
                                  </div>
                              `;
                              $('#units-container').append(row);
                          });
                      }
                      
                      $('#unitModal').modal('show');
                      
                      setTimeout(function() {
                          console.log('✅ After modal shown - Hidden field:', $('#unit_id').val());
                          console.log('✅ After modal shown - Modal data:', $('#unitModal').data('editing-id'));
                      }, 100);
                  }
              },
              error: function(xhr) {
                  console.error('❌ Error:', xhr);
                  alert('Error loading unit details');
              }
          });
      });

      $(document).on('click', '.delete-btn', function(e) {
          e.preventDefault();
          let unitId = $(this).data('id');
          
          if (confirm('Are you sure you want to delete this unit?')) {
              $.ajax({
                  url: '/study_material/units/' + unitId,
                  type: 'DELETE',
                  success: function(response) {
                      if (response.success) {
                          table.ajax.reload();
                          alert(response.message);
                      }
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON?.message || 'Failed to delete unit'));
                  }
              });
          }
      });
  });
  </script>
</body>
</html>