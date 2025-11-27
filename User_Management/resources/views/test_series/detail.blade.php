<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Test Series</title>
  <link rel="stylesheet" href="{{ asset('css/emp.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .conditional-field {
      display: none;
    }
    .conditional-field.show {
      display: block;
    }
    
    .top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    
    .top-text h4 {
      margin: 0;
      color: #333;
      font-weight: 600;
    }
    
    .btn-success {
      background-color: #ff6b35 !important;
      border: none !important;
    }
    
    .btn-success:hover {
      background-color: #ff5520 !important;
    }
  </style>
</head>
<body>
  <!-- Flash Messages -->
  <div class="flash-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  <!-- Header -->
  <div class="header">
    <div class="logo">
      <img src="{{ asset('images/logo.png.jpg') }}" class="img" alt="Logo">
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
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <!-- Sidebar -->
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
              data-bs-target="#flush-collapseOne" aria-expanded="false" id="accordion-button">
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
              data-bs-target="#flush-collapseTwo" aria-expanded="false" id="accordion-button">
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
              data-bs-target="#flush-collapseThree" aria-expanded="false" id="accordion-button">
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
              data-bs-target="#flush-collapseFour" aria-expanded="false" id="accordion-button">
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
              data-bs-target="#flush-collapseFive" aria-expanded="false" id="accordion-button">
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

        <!-- Attendance Management -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseSix" aria-expanded="false" id="accordion-button">
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
              data-bs-target="#flush-collapseSeven" aria-expanded="false" id="accordion-button">
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

        <!-- Test Series Management -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseEight" aria-expanded="false" id="accordion-button">
              <i class="fa-solid fa-chart-column" id="side-icon"></i> Test Series Management
            </button>
          </h2>
          <div id="flush-collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li>
                  <a class="item active" href="{{ route('test_series.index') }}">
                    <i class="fa-solid fa-book" id="side-icon"></i>Test Master
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Reports -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseNine" aria-expanded="false" id="accordion-button">
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

    <!-- Main Content -->
    <div class="right" id="right">
      <!-- Top Section -->
      <div class="top">
        <div class="top-text">
          <h4>TEST SERIES - {{ $courseName }}</h4>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTestSeriesModal" style="background-color: #ff6607ff; border-color: #ff6607ff;">
      Create Test Series
        </button>
      </div>

      <div class="whole">
        <!-- Controls -->
        <div class="dd">
          <div class="line">
            <h6>Show</h6>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown">10</button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item entries-option" href="#" data-value="10">10</a></li>
                <li><a class="dropdown-item entries-option" href="#" data-value="25">25</a></li>
                <li><a class="dropdown-item entries-option" href="#" data-value="50">50</a></li>
                <li><a class="dropdown-item entries-option" href="#" data-value="100">100</a></li>
              </ul>
            </div>
            <h6>entries</h6>
          </div>
          <div class="search">
            <h4 class="search-text">Search:</h4>
            <input type="search" placeholder="" class="search-holder" id="searchInput">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>

        <!-- Table -->
        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col">Serial No.</th>
              <th scope="col">Test Name</th>
              <th scope="col">Test Type</th>
              <th scope="col">Subject Type</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody id="tableBody">
  @forelse($testSeries as $index => $series)
    @php
      $seriesId = is_object($series->_id) ? (string)$series->_id : $series->_id;
    @endphp
    <tr data-row="true">
      <td>{{ $index + 1 }}</td>
      <td>{{ $series->test_name ?? 'N/A' }}</td>
      <td>{{ $series->test_type ?? 'N/A' }}</td>
      <td>{{ $series->subject_type ?? 'N/A' }}</td>
      <td>
        <span class="badge {{ $series->status == 'Active' ? 'bg-success' : ($series->status == 'Completed' ? 'bg-primary' : 'bg-warning') }}">
          {{ $series->status ?? 'Pending' }}
        </span>
      </td>
      <td>
        <div class="dropdown">
          <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="fas fa-ellipsis-v"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="{{ route('test_series.view_students', $seriesId) }}">
                View Details
              </a>
            </li>
            <!-- Edit - Opens modal -->
            <li>
              <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $seriesId }}">
               Edit
              </button>
          </ul>
        </div>
      </td>
    </tr>
  @empty
    <tr id="noResultsRow">
      <td colspan="6" class="text-center">No test series found</td>
    </tr>
  @endforelse
</tbody>
        </table>

        <!-- Pagination Info -->
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div id="paginationInfo">
Showing <span id="showingFrom">1</span> to <span id="showingTo">{{ min(10, $testSeries->count()) }}</span> of <span id="totalEntries">{{ $testSeries->count() }}</span> entries          </div>
          <nav>
            <ul class="pagination" id="pagination">
              <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item active" style="background-color: #ff6b35;"><a class="page-link" href="#" style="background-color: #ff6b35; border-color: #ff6b35;">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Test Series Modal -->
<div class="modal fade" id="createTestSeriesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('test_series.store') }}" class="modal-content" id="createTestForm">
      @csrf
      <input type="hidden" name="course_id" value="{{ is_object($course->_id) ? (string)$course->_id : $course->_id }}">
      <input type="hidden" name="course_name" value="{{ $courseName }}">
      
      <div class="modal-header" style="background: linear-gradient(135deg, #fd550dff 0%, #ff7d3d 100%);">
        <h5 class="modal-title text-white">
          <i class="fas fa-plus-circle me-2"></i>Create Test Series
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row">
          <!-- Test Series Type -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Test Series Type <span class="text-danger">*</span></label>
            <select name="test_type" id="testSeriesType" class="form-select" required>
              <option value="">-- Select Type --</option>
              <option value="Type1">Type1</option>
              <option value="Type2">Type2</option>
            </select>
          </div>

          <!-- Subject Type -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Subject Type <span class="text-danger">*</span></label>
            <select name="subject_type" class="form-select" required>
              <option value="">-- Select Subject Type --</option>
              <option value="Single">Single</option>
              <option value="Double">Double</option>
            </select>
          </div>

          <!-- Select Subjects - DYNAMICALLY LOADED FROM COURSE -->
          <div class="col-12 mb-3 conditional-field" id="selectSubjectsField">
            <label class="form-label fw-semibold">Select Subjects <span class="text-danger">*</span></label>
            <div id="subjectsCheckboxContainer" class="d-flex flex-wrap gap-3">
              @if(isset($course->subjects) && is_array($course->subjects))
                @foreach($course->subjects as $subject)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="subjects[]" 
                           value="{{ $subject }}" id="subject_{{ str_replace(' ', '_', $subject) }}">
                    <label class="form-check-label" for="subject_{{ str_replace(' ', '_', $subject) }}">
                      {{ $subject }}
                    </label>
                  </div>
                @endforeach
              @else
                <div class="alert alert-warning">No subjects found for this course. Please add subjects in the Courses section first.</div>
              @endif
            </div>
          </div>

          <!-- No. of Test Counts -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">No. of Test Counts <span class="text-danger">*</span></label>
            <input type="number" name="test_count" class="form-control" min="1" required>
          </div>

          <!-- Test Series Name (Only for Type1) -->
          <div class="col-md-6 mb-3 conditional-field" id="testSeriesNameField">
            <label class="form-label fw-semibold">Test Series Name <span class="text-danger">*</span></label>
            <input type="text" name="test_series_name" id="testSeriesNameInput" class="form-control" placeholder="e.g., Neet Pattern">
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary" style="background-color: #fd550dff; border: none;">
          <i class="fas fa-save me-2"></i>Create Test Series
        </button>
      </div>
    </form>
  </div>
</div>

  <!-- View & Edit Modals -->
  @foreach($testSeries as $series)
  @php
    $seriesId = is_object($series->_id) ? (string)$series->_id : $series->_id;
    $seriesSubjects = is_array($series->subjects) ? $series->subjects : (isset($series->subjects) ? [$series->subjects] : []);
  @endphp

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal{{ $seriesId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form method="POST" action="{{ route('test_series.update', $seriesId) }}" class="modal-content">
        @csrf
        @method('PUT')
        <div class="modal-header" style="background: linear-gradient(135deg, #fd550dff 0%, #ff7d3d 100%);">
          <h5 class="modal-title text-white"><i class="fas fa-edit me-2"></i>Update Test Series</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row">
            <!-- Test Series Type (Read-only) -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Test Series Type</label>
              <input type="text" class="form-control" value="{{ $series->test_type }}" readonly>
            </div>

            <!-- Test Series Name (if Type1) -->
            @if($series->test_type === 'Type1')
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Test Series Name</label>
              <input type="text" name="test_series_name" class="form-control" 
                     value="{{ $series->test_series_name ?? '' }}">
            </div>
            @endif

            <!-- Select Subjects with Marks -->
            <div class="col-12 mb-3">
              <label class="form-label fw-semibold">Select Subjects</label>
              <div class="mb-2">
                @if(isset($course->subjects) && is_array($course->subjects))
                  @foreach($course->subjects as $subject)
                    <div class="form-check">
                      <input class="form-check-input edit-subject-checkbox" type="checkbox" 
                             name="subjects[]" value="{{ $subject }}" 
                             id="edit_{{ $seriesId }}{{ str_replace(' ', '', $subject) }}"
                             {{ in_array($subject, $seriesSubjects) ? 'checked' : '' }}
                             data-series-id="{{ $seriesId }}">
                      <label class="form-check-label" for="edit_{{ $seriesId }}{{ str_replace(' ', '', $subject) }}">
                        {{ $subject }}
                      </label>
                    </div>
                  @endforeach
                @endif
              </div>
            </div>

            <!-- Subject Marks -->
            <div class="col-12 mb-3" id="subjectMarksDiv{{ $seriesId }}">
              <h6 class="fw-semibold mb-3">Subject Marks</h6>
              @foreach($seriesSubjects as $subject)
                @php
                  $marks = isset($series->subject_marks) && is_array($series->subject_marks) 
                         ? ($series->subject_marks[$subject] ?? '') 
                         : '';
                @endphp
                <div class="mb-2 subject-mark-field" data-subject="{{ $subject }}">
                  <label class="form-label">{{ $subject }} marks:</label>
                  <input type="number" name="subject_marks[{{ $subject }}]" 
                         class="form-control" value="{{ $marks }}" min="0" placeholder="Enter marks">
                </div>
              @endforeach
            </div>

            <!-- Subject Type -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Subject Type</label>
              <select name="subject_type" class="form-select">
                <option value="Single" {{ $series->subject_type == 'Single' ? 'selected' : '' }}>Single</option>
                <option value="Double" {{ $series->subject_type == 'Double' ? 'selected' : '' }}>Double</option>
              </select>
            </div>

            <!-- Status -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select">
                <option value="Pending" {{ $series->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Active" {{ $series->status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Completed" {{ $series->status == 'Completed' ? 'selected' : '' }}>Completed</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" style="background-color: #fd550dff; border: none;">
            <i class="fas fa-save me-2"></i>Update
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Modal (Updated with Link to Students Page) -->
  <div class="modal fade" id="viewModal{{ $seriesId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(135deg, #fd550dff 0%, #ff7d3d 100%);">
          <h5 class="modal-title text-white"><i class="fas fa-eye me-2"></i>Test Series Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <strong>Test Name:</strong>
              <p>{{ $series->test_name }}</p>
            </div>
            <div class="col-md-6 mb-3">
              <strong>Test Type:</strong>
              <p>{{ $series->test_type }}</p>
            </div>
            <div class="col-md-6 mb-3">
              <strong>Subject Type:</strong>
              <p>{{ $series->subject_type }}</p>
            </div>
            <div class="col-md-6 mb-3">
              <strong>Status:</strong>
              <p><span class="badge {{ $series->status == 'Active' ? 'bg-success' : 'bg-warning' }}">{{ $series->status }}</span></p>
            </div>
            @if(!empty($seriesSubjects))
            <div class="col-md-6 mb-3">
              <strong>Subjects:</strong>
              <p>{{ implode(', ', $seriesSubjects) }}</p>
            </div>
            @endif
            @if(isset($series->test_count))
            <div class="col-md-6 mb-3">
              <strong>Test Count:</strong>
              <p>{{ $series->test_count }}</p>
            </div>
            @endif
            @if(isset($series->students_count))
            <div class="col-12 mb-3">
              <strong>Enrolled Students:</strong>
              <p>{{ $series->students_count }} students</p>
            </div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a href="{{ route('test_series.view_students', $seriesId) }}" class="btn btn-primary" style="background-color: #fd550dff; border: none;">
            <i class="fas fa-users me-2"></i>View Enrolled Students
          </a>
        </div>
      </div>
    </div>
  </div>
@endforeach


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset(path: 'js/emp.js') }}"></script>
  <script>
document.querySelector('#createTestSeriesModal form').addEventListener('submit', function(e) {
    const testType = document.getElementById('testSeriesType').value;
    const checkboxes = document.querySelectorAll('input[name="subjects[]"]:checked');
    
    if ((testType === 'Type1' || testType === 'Type2') && checkboxes.length === 0) {
        e.preventDefault();
        alert('Please select at least one subject');
        return false;
    }
});
    document.addEventListener('DOMContentLoaded', function () {
      // Sidebar toggle
      const toggleBtn = document.getElementById('toggleBtn');
      const sidebar = document.getElementById('sidebar');
      const right = document.getElementById('right');
      const text = document.getElementById('text');

      if (toggleBtn && sidebar && right && text) {
        toggleBtn.addEventListener('click', () => {
          sidebar.classList.toggle('collapsed');
          right.classList.toggle('expanded');
          text.classList.toggle('hidden');
        });
      }

      // Auto-hide flash messages
      setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
          alert.classList.remove('show');
          setTimeout(() => alert.remove(), 150);
        });
      }, 5000);

      // Conditional fields logic
      const testSeriesType = document.getElementById('testSeriesType');
      const selectSubjectsField = document.getElementById('selectSubjectsField');
      const testSeriesNameField = document.getElementById('testSeriesNameField');
      const testSeriesNameInput = document.getElementById('testSeriesNameInput');

      if (testSeriesType) {
        testSeriesType.addEventListener('change', function() {
          const selectedType = this.value;
          
          if (selectedType === 'Type1') {
            selectSubjectsField.classList.add('show');
            testSeriesNameInput.value = '';
          } else {
            selectSubjectsField.classList.remove('show');
            testSeriesNameField.classList.remove('show');
            testSeriesNameInput.required = false;
          }
        });
      }

      // Form validation
      document.getElementById('createTestForm').addEventListener('submit', function(e) {
        const testType = document.getElementById('testSeriesType').value;
        const checkboxes = document.querySelectorAll('input[name="subjects[]"]:checked');
        
        if ((testType === 'Type1' || testType === 'Type2') && checkboxes.length === 0) {
          e.preventDefault();
          alert('Please select at least one subject');
          return false;
        }
      });

      // Table pagination and search
      let currentPage = 1;
      let entriesPerPage = 10;
      let allRows = [];
      let filteredRows = [];

      const tableBody = document.getElementById('tableBody');
      if (tableBody) {
        allRows = Array.from(tableBody.querySelectorAll('tr[data-row="true"]'));
        filteredRows = [...allRows];
        updateTable();
      }

      document.querySelectorAll('.entries-option').forEach(option => {
        option.addEventListener('click', function (e) {
          e.preventDefault();
          entriesPerPage = parseInt(this.dataset.value);
          document.getElementById('number').textContent = entriesPerPage;
          currentPage = 1;
          updateTable();
        });
      });

      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        searchInput.addEventListener('input', function () {
          const searchTerm = this.value.toLowerCase().trim();
          filteredRows = searchTerm === ''
            ? [...allRows]
            : allRows.filter(row => row.textContent.toLowerCase().includes(searchTerm));
          currentPage = 1;
          updateTable();
        });
      }

      function updateTable() {
        const start = (currentPage - 1) * entriesPerPage;
        const end = start + entriesPerPage;
        const pageRows = filteredRows.slice(start, end);

        allRows.forEach(row => row.style.display = 'none');

        const noResultsRow = document.getElementById('noResultsRow');
        if (noResultsRow) noResultsRow.style.display = 'none';

        if (pageRows.length > 0) {
          pageRows.forEach(row => row.style.display = '');
        } else {
          if (noResultsRow) noResultsRow.style.display = '';
        }

        const totalEntries = filteredRows.length;
        document.getElementById('showingFrom').textContent = totalEntries > 0 ? start + 1 : 0;
        document.getElementById('showingTo').textContent = Math.min(end, totalEntries);
        document.getElementById('totalEntries').textContent = totalEntries;

        updatePagination();
      }

      function updatePagination() {
        const totalPages = Math.ceil(filteredRows.length / entriesPerPage);
        const pagination = document.getElementById('pagination');
        if (!pagination) return;

        pagination.innerHTML = '';
        if (totalPages === 0) return;

        const prevLi = document.createElement('li');
        prevLi.className = page-item ${currentPage === 1 ? 'disabled' : ''};
        prevLi.innerHTML = <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">Previous</a>;
        pagination.appendChild(prevLi);

        for (let i = 1; i <= Math.min(totalPages, 5); i++) {
          const li = document.createElement('li');
          li.className = page-item ${i === currentPage ? 'active' : ''};
          const activeStyle = i === currentPage ? 'style="background-color: #ff6b35; border-color: #ff6b35;"' : '';
          li.innerHTML = <a class="page-link" href="#" onclick="changePage(${i}); return false;" ${activeStyle}>${i}</a>;
          pagination.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = page-item ${currentPage === totalPages ? 'disabled' : ''};
        nextLi.innerHTML = <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Next</a>;
        pagination.appendChild(nextLi);
      }

      window.changePage = function (page) {
        const totalPages = Math.ceil(filteredRows.length / entriesPerPage);
        if (page >= 1 && page <= totalPages) {
          currentPage = page;
          updateTable();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      };
    });
    document.addEventListener('DOMContentLoaded', function() {
  console.log('Test Series Detail Page JS loaded');

  // ===== CREATE MODAL LOGIC =====
  const testSeriesType = document.getElementById('testSeriesType');
  const selectSubjectsField = document.getElementById('selectSubjectsField');
  const testSeriesNameField = document.getElementById('testSeriesNameField');
  const testSeriesNameInput = document.getElementById('testSeriesNameInput');
  const createTestForm = document.getElementById('createTestForm');

  // Show/hide fields based on test type
  if (testSeriesType) {
    testSeriesType.addEventListener('change', function() {
      const selectedType = this.value;
      
      if (selectedType === 'Type1') {
        selectSubjectsField.classList.add('show');
        testSeriesNameField.classList.add('show');
        testSeriesNameInput.required = true;
      } else if (selectedType === 'Type2') {
        selectSubjectsField.classList.add('show');
        testSeriesNameField.classList.remove('show');
        testSeriesNameInput.required = false;
        testSeriesNameInput.value = '';
      } else {
        selectSubjectsField.classList.remove('show');
        testSeriesNameField.classList.remove('show');
        testSeriesNameInput.required = false;
      }
    });
  }

  // Form validation before submit
  if (createTestForm) {
    createTestForm.addEventListener('submit', function(e) {
      const testType = document.getElementById('testSeriesType').value;
      const checkboxes = document.querySelectorAll('input[name="subjects[]"]:checked');
      
      if ((testType === 'Type1' || testType === 'Type2') && checkboxes.length === 0) {
        e.preventDefault();
        alert('Please select at least one subject');
        return false;
      }
    });
  }

  // ===== EDIT MODAL LOGIC =====
  // Handle subject checkboxes in edit modal to show/hide mark fields
  document.querySelectorAll('.edit-subject-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const seriesId = this.dataset.seriesId;
      const subject = this.value;
      const marksDiv = document.getElementById('subjectMarksDiv' + seriesId);
      
      if (!marksDiv) return;
      
      if (this.checked) {
        // Add marks field if checkbox is checked
        const existingField = marksDiv.querySelector([data-subject="${subject}"]);
        if (!existingField) {
          const markField = document.createElement('div');
          markField.className = 'mb-2 subject-mark-field';
          markField.setAttribute('data-subject', subject);
          markField.innerHTML = `
            <label class="form-label">${subject} marks:</label>
            <input type="number" name="subject_marks[${subject}]" 
                   class="form-control" min="0" placeholder="Enter marks">
          `;
          marksDiv.appendChild(markField);
        }
      } else {
        // Remove marks field if checkbox is unchecked
        const fieldToRemove = marksDiv.querySelector([data-subject="${subject}"]);
        if (fieldToRemove) {
          fieldToRemove.remove();
        }
      }
    });
  });
});
  </script>
</body>
</html>