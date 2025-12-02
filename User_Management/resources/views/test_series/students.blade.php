<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $testSeries->test_name }} - Students</title>
  <link rel="stylesheet" href="{{ asset('css/emp.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <!-- Sidebar-->
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
                <li><a class="item" href="{{ route('attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Employee</a></li>
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
<a class="item" href="{{ route('test_series.index') }}">            <i class="fa-solid fa-book" id="side-icon"></i>Test Master
          </a>
        </li>
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
      <div class="top" style="margin-top: 10px;">
        <div class="top-text" style="margin-left: 10px;">
          <h4>{{ $testSeries->test_name }}</h4>
          @php
            $courseName = $testSeries->course_name ?? ($testSeries->course->course_name ?? ($testSeries->course->name ?? 'Unknown'));
          @endphp

        </div>
        <div class="d-flex gap-2 flex-wrap" style="margin-right: 20px;">
          <!-- Lock Result Button -->
          <button class="btn btn-warning {{ $testSeries->result_locked ? 'disabled' : '' }}" 
                  data-bs-toggle="modal" 
                  data-bs-target="#lockResultModal"
                  {{ $testSeries->result_locked ? 'disabled' : '' }}
                  style="background-color: #ff6607ff; color: white; border-color: #ff6607ff;">
            {{ $testSeries->result_locked ? 'Result Locked' : 'Lock Result' }}
          </button>
          
            <!-- Upload Syllabus Button -->
        <button class="btn btn-primary" 
                data-bs-toggle="modal" 
                data-bs-target="#uploadSyllabusModal" 
                style="background-color: #ff6600ff; border-color: #ff6600ff;">
            {{ $testSeries->syllabus ? 'Edit' : 'Upload' }} Syllabus
        </button>
        
        @if($testSeries->syllabus)
            <!-- Download Syllabus -->
            <a href="{{ route('test_series.download_syllabus', $testSeries->_id) }}" 
               class="btn btn-info">
                <i class="fas fa-download"></i> Download Syllabus
            </a>
            
            <!-- Delete Syllabus -->
            <form action="{{ route('test_series.delete_syllabus', $testSeries->_id) }}" 
                  method="POST" 
                  style="display: inline;"
                  onsubmit="return confirm('Are you sure you want to delete the syllabus?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Syllabus
                </button>
            </form>
        @endif
          
          <!-- Upload Result Button -->
          <button class="btn btn-success {{ $testSeries->result_locked ? 'disabled' : '' }}" 
                  data-bs-toggle="modal" 
                  data-bs-target="#uploadResultModal"
                  {{ $testSeries->result_locked ? 'disabled' : '' }}
                  style="background-color: #ff6607ff; border-color: #ff6607ff;">
           Upload Result
          </button>

          
          <!-- Add Test Button -->
          <button class="btn" 
                  style="background-color: #ff6600ff; color: white; border-color: #ff6600ff;" 
                  data-bs-toggle="modal" 
                  data-bs-target="#addTestModal">
           Add Test
          </button>

          <!-- Result Uploaded Badge -->
          @if($testSeries->result_uploaded)
            <span class="badge bg-success align-self-center px-3 py-2">
              <i class="fas fa-check-circle me-1"></i>Result Uploaded
            </span>
          @endif
        </div>
      </div>

      <div class="whole">
        <!-- Controls -->
        <div class="dd">
          <div class="line">
            <h6>Show Enteries:</h6>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            {{ request('per_page', 10) }}
          </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item">10</a></li>
                <li><a class="dropdown-item">25</a></li>
                <li><a class="dropdown-item">50</a></li>
                <li><a class="dropdown-item">100</a></li>
              </ul>
            </div>
          </div>
          <div class="search">
            <h4 class="search-text">Search:</h4>
            <input type="search" placeholder="" class="search-holder" id="searchInput">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>

        <!-- Students Table -->
        <table class="table table-hover" id="studentsTable">
          <thead>
            <tr>
              <th scope="col" id="one">Serial No.</th>
              <th scope="col" id="one">Student Name</th>
              <th scope="col" id="one">RollNumber</th>
              <th scope="col" id="one">Test Type</th>
              <th scope="col" id="one">Subject Type</th>
              <th scope="col" id="one">Father Name</th>
              <th scope="col" id="one">Batch code</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @forelse($students as $index => $student)
              <tr data-row="true">
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->student_name ?? $student->name }}</td>
                <td>{{ $student->roll_no }}</td>
                <td>{{ $testSeries->test_type }}</td>
                <td>{{ $testSeries->subject_type }}</td>
                <td>{{ $student->father_name ?? $student->father }}</td>
                <td>{{ $student->batch->batch_id ?? $student->batch_name ?? 'N/A' }}</td>
              </tr>
            @empty
              <tr id="noResultsRow">
                <td colspan="7" class="text-center">
                  <div class="alert alert-info m-3">
                    <i class="fas fa-info-circle me-2"></i>
                    No students found enrolled in course: <strong>{{ $testSeries->course_name }}</strong>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div id="paginationInfo">
            Showing <span id="showingFrom">1</span> to <span id="showingTo">{{ min(10, $students->count()) }}</span> of <span id="totalEntries">{{ $students->count() }}</span> entries
          </div>
          <nav>
            <ul class="pagination" id="pagination"></ul>
          </nav>
        </div>
 </div>
    </div>
  </div>

  <!-- Lock Result Modal -->
  <div class="modal fade" id="lockResultModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #ff6607ff; color: white;">
          <h5 class="modal-title">Lock Result</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-3">
            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
          </div>
          <h5 class="text-center mb-3">Are you sure you want to lock the result?</h5>
          <p class="text-muted text-center">
            Once locked, you will <strong>NOT</strong> be able to upload or modify results anymore.
          </p>
          
          @if(!$testSeries->result_uploaded)
            <div class="alert alert-danger">
              <i class="fas fa-info-circle me-2"></i>Please upload result before locking!
            </div>
          @else
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i>
              Last uploaded: {{ $testSeries->result_uploaded_at ? $testSeries->result_uploaded_at->format('d M Y, h:i A') : 'N/A' }}
              <br>Uploaded by: {{ $testSeries->result_uploaded_by ?? 'N/A' }}
            </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Cancel
          </button>
          <form method="POST" action="{{ route('test_series.lock_result', $testSeries->_id) }}" style="display: inline;">
            @csrf
            <button type="submit" 
                    class="btn btn-warning" 
                    {{ !$testSeries->result_uploaded ? 'disabled' : '' }}
                    style="background-color: #ff6607ff; border-color: #ff6607ff; color: white;">
              Yes, Lock Result
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

<!-- Upload Syllabus Modal -->
<div class="modal fade" id="uploadSyllabusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="syllabusUploadForm" action="{{ route('test_series.upload_syllabus', $testSeries->_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="background-color: #ff6607ff; color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-upload"></i> Upload
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-file-upload" style="font-size: 4rem; color: #ff6607ff;"></i>
                    </div>
                    <h5 class="mb-4">Upload Syallbus File</h5>
                    
                    <!-- Hidden file input -->
                    <input type="file" 
                           id="syllabusFileInput" 
                           name="syllabus_file" 
                           accept=".pdf,.doc,.docx,.txt" 
                           style="display: none;"
                           required>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" 
                                class="btn btn-secondary" 
                                style="min-width: 100px;"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>No
                        </button>
                        <button type="button" 
                                class="btn btn-success" 
                                id="selectFileBtn"
                                style="background-color: #ff6607ff; border-color: #ff6607ff; min-width: 100px;">
                            <i class="fas fa-check me-2"></i>Yes
                        </button>
                    </div>
                    
                    <div id="selectedFileName" class="mt-3 text-success" style="display: none;">
                        <i class="fas fa-check-circle me-2"></i>
                        <small>Selected: <strong><span id="fileName"></span></strong></small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



  <!-- Upload Result Modal -->
  <div class="modal fade" id="uploadResultModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <form method="POST" 
            action="{{ route('test_series.upload_result', $testSeries->_id) }}" 
            enctype="multipart/form-data" 
            class="modal-content">
        @csrf
        <div class="modal-header" style="background-color: #ff6607ff; color: white;">
          <h5 class="modal-title">
            <i class="fas fa-upload me-2"></i>Upload Result
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info mb-3">
            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Test Series Information</h6>
            <div class="row">
              <div class="col-md-6">
                <strong>Test Name:</strong> {{ $testSeries->test_name }}
              </div>
              <div class="col-md-6">
                <strong>Subjects:</strong> {{ implode(', ', $testSeries->subjects ?? []) }}
              </div>
            </div>
          </div>

          <div class="mb-3">
            <a href="{{ route('test_series.generate_template', $testSeries->_id) }}" 
               class="btn btn-outline-primary btn-sm">
              <i class="fas fa-download me-2"></i>Download Result Template
            </a>
            <small class="text-muted d-block mt-2">
              Download the template with student details pre-filled. Just add marks!
            </small>
          </div>


          @if($testSeries->result_uploaded)
            <div class="alert alert-success">
              <i class="fas fa-check-circle me-2"></i>
              <strong>Result already uploaded!</strong><br>
              Uploading again will replace the previous result.<br>
              <small>Last uploaded: {{ $testSeries->result_uploaded_at->format('d M Y, h:i A') }}</small>
            </div>
          @endif
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Cancel
          </button>
          <button type="submit" class="btn btn-success" style="background-color: #ff6607ff; border-color: #ff6607ff;">
            <i class="fas fa-upload me-2"></i>Upload Result
          </button>
        </div>
      </form>
    </div>
  </div>

<!-- Add Test Modal -->
<div class="modal fade" id="addTestModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <form method="POST" action="{{ route('test_series.store_multiple_tests', $testSeries->_id) }}" class="modal-content">
      @csrf
      <div class="modal-header" style="background-color: #ff6607ff; color: white;">
        <h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i>Add Test dates</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
        <div class="alert alert-info mb-3">
          <i class="fas fa-info-circle me-2"></i>
          Select test dates for the batches you want to schedule tests for. Leave dates empty for batches you don't want to schedule.
        </div>

        <table class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th style="width: 40%;">Batch Code</th>
              <th style="width: 60%;">Select Test Date</th>
            </tr>
          </thead>
          <tbody id="batchTestDatesBody">
            <tr>
              <td colspan="2" class="text-center">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading batches...</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Cancel
        </button>
        <button type="button" class="btn btn-primary" id="nextBtn" style="background-color: #ff6607ff; border: none;" disabled>
          <i class="fas fa-arrow-right me-2"></i>Next
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Test Details Modal - Step 2 -->
<div class="modal fade" id="testDetailsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('test_series.store_multiple_tests', $testSeries->_id) }}" class="modal-content" id="testDetailsForm">
      @csrf
      <input type="hidden" name="test_dates_data" id="testDatesDataInput">
      
      <div class="modal-header" style="background-color: #ff6607ff; color: white;">
        <h5 class="modal-title"><i class="fas fa-clipboard-list me-2"></i>Test Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">Test Number <span class="text-danger">*</span></label>
          <input type="number" name="test_number" class="form-control" min="1" required>
          <small class="text-muted">This will be the starting test number. Subsequent tests will be numbered automatically.</small>
        </div>
        
        <div class="mb-3">
          <label class="form-label fw-semibold">Scheduled Time</label>
          <input type="time" name="scheduled_time" class="form-control" value="14:00">
          <small class="text-muted">Default: 2:00 PM (applies to all tests)</small>
        </div>
        
        <div class="mb-3">
          <label class="form-label fw-semibold">Duration (minutes)</label>
          <input type="number" name="duration_minutes" class="form-control" min="30" value="180">
          <small class="text-muted">Default: 180 minutes (3 hours, applies to all tests)</small>
        </div>

        <div class="alert alert-success">
          <i class="fas fa-check-circle me-2"></i>
          <strong>Ready to create <span id="testCount">0</span> tests</strong>
          <div id="batchesList" class="mt-2"></div>
        </div>
      </div>
      
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" id="backBtn">
          <i class="fas fa-arrow-left me-2"></i>Back
        </button>
        <button type="submit" class="btn btn-success" style="background-color: #ff6607ff; border: none;">
          <i class="fas fa-save me-2"></i>Create Tests
        </button>
      </div>
    </form>
  </div>
</div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset(path: 'js/emp.js') }}"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
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

      setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
          alert.classList.remove('show');
          setTimeout(() => alert.remove(), 150);
        });
      }, 5000);

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
        option.addEventListener('click', function(e) {
          e.preventDefault();
          entriesPerPage = parseInt(this.dataset.value);
          document.getElementById('number').textContent = entriesPerPage;
          currentPage = 1;
          updateTable();
        });
      });

      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          const searchTerm = this.value.toLowerCase().trim();
          filteredRows = searchTerm === '' ? [...allRows] : allRows.filter(row => row.textContent.toLowerCase().includes(searchTerm));
          currentPage = 1;
          updateTable();
        });
      }
function handleSyllabusFileSelect(input) {
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        document.getElementById('fileName').textContent = fileName;
        document.getElementById('selectedFileName').style.display = 'block';
        
        // Auto-submit the form after file selection
        input.form.submit();
    }
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
        } else if (noResultsRow) {
          noResultsRow.style.display = '';
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
          li.innerHTML = <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>;
          pagination.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = page-item ${currentPage === totalPages ? 'disabled' : ''};
        nextLi.innerHTML = <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Next</a>;
        pagination.appendChild(nextLi);
      }

      window.changePage = function(page) {
        const totalPages = Math.ceil(filteredRows.length / entriesPerPage);
        if (page >= 1 && page <= totalPages) {
          currentPage = page;
          updateTable();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      };
    });

document.addEventListener('DOMContentLoaded', function() {
    const selectFileBtn = document.getElementById('selectFileBtn');
    const fileInput = document.getElementById('syllabusFileInput');
    const form = document.getElementById('syllabusUploadForm');
    
    if (selectFileBtn && fileInput) {
        // When "Yes" button is clicked, trigger file input
        selectFileBtn.addEventListener('click', function() {
            fileInput.click();
        });
        
        // When file is selected, show filename and auto-submit
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                document.getElementById('fileName').textContent = fileName;
                document.getElementById('selectedFileName').style.display = 'block';
                
                // Change button text to indicate upload in progress
                selectFileBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
                selectFileBtn.disabled = true;
                
                // Auto-submit the form
                setTimeout(function() {
                    form.submit();
                }, 500);
            }
        });
    }
});
// Load batches when Add Test modal opens
const addTestModal = document.getElementById('addTestModal');
const testDetailsModal = new bootstrap.Modal(document.getElementById('testDetailsModal'));

let selectedTestDates = [];

if (addTestModal) {
  addTestModal.addEventListener('show.bs.modal', function() {
    const batchesBody = document.getElementById('batchTestDatesBody');
    const nextBtn = document.getElementById('nextBtn');
    
    // Show loading state
    batchesBody.innerHTML = `
      <tr>
        <td colspan="2" class="text-center">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2 text-muted">Loading batches...</p>
        </td>
      </tr>
    `;
    nextBtn.disabled = true;
    
    const courseId = '{{ $testSeries->course_id }}';
    const baseUrl = '{{ url("/test-series/course") }}';
    const fetchUrl = ${baseUrl}/${courseId}/batches;
    
    fetch(fetchUrl, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success && data.batches && data.batches.length > 0) {
        let html = '';
        data.batches.forEach((batch, index) => {
          html += `
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <i class="fas fa-users text-primary me-2"></i>
                  <strong>${batch}</strong>
                </div>
              </td>
              <td>
                <input type="date" 
                       class="form-control test-date-input" 
                       data-batch="${batch}"
                       min="${new Date().toISOString().split('T')[0]}"
                       placeholder="dd-mm-yyyy">
              </td>
            </tr>
          `;
        });
        batchesBody.innerHTML = html;
        
        // Add event listeners to date inputs
        document.querySelectorAll('.test-date-input').forEach(input => {
          input.addEventListener('change', validateDates);
        });
        
      } else {
        batchesBody.innerHTML = `
          <tr>
            <td colspan="2" class="text-center text-danger">
              <i class="fas fa-exclamation-triangle me-2"></i>
              No batches available for this course
            </td>
          </tr>
        `;
      }
    })
    .catch(error => {
      console.error('Error:', error);
      batchesBody.innerHTML = `
        <tr>
          <td colspan="2" class="text-center text-danger">
            <i class="fas fa-times-circle me-2"></i>
            Error loading batches. Please try again.
          </td>
        </tr>
      `;
    });
  });

  // Validate dates and enable Next button
  function validateDates() {
    selectedTestDates = [];
    const nextBtn = document.getElementById('nextBtn');
    
    document.querySelectorAll('.test-date-input').forEach(input => {
      if (input.value) {
        selectedTestDates.push({
          batch_code: input.dataset.batch,
          test_date: input.value
        });
      }
    });
    
    nextBtn.disabled = selectedTestDates.length === 0;
  }

  // Next button click - Show test details modal
  document.getElementById('nextBtn')?.addEventListener('click', function() {
    // Close first modal
    const firstModal = bootstrap.Modal.getInstance(addTestModal);
    firstModal.hide();
    
    // Update summary
    document.getElementById('testCount').textContent = selectedTestDates.length;
    
    let batchesList = '<ul class="mb-0">';
    selectedTestDates.forEach(item => {
      batchesList += <li><strong>${item.batch_code}</strong> - ${new Date(item.test_date).toLocaleDateString('en-GB')}</li>;
    });
    batchesList += '</ul>';
    document.getElementById('batchesList').innerHTML = batchesList;
    
    // Store data
    document.getElementById('testDatesDataInput').value = JSON.stringify(selectedTestDates);
    
    // Show second modal
    testDetailsModal.show();
  });

  // Back button click
  document.getElementById('backBtn')?.addEventListener('click', function() {
    testDetailsModal.hide();
    const firstModal = new bootstrap.Modal(addTestModal);
    firstModal.show();
  });
}
 </script>
</body>
</html>