<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Students Management</title>
  <link rel="stylesheet" href="{{ asset('css/emp.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Activity Timeline Styles */
    .activity-timeline {
      max-height: 400px;
      overflow-y: auto;
      padding-right: 10px;
    }
    .activity-item {
      border-left: 3px solid #fd550dff;
      padding-left: 20px;
      padding-bottom: 20px;
      position: relative;
      margin-bottom: 10px;
    }
    .activity-item:last-child {
      border-left-color: transparent;
      padding-bottom: 0;
    }
    .activity-item::before {
      content: '';
      position: absolute;
      left: -7px;
      top: 5px;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #fd550dff;
      border: 2px solid white;
    }
    .activity-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 8px;
    }
    .activity-title {
      font-weight: 600;
      color: #212529;
      margin: 0;
    }
    .activity-time {
      font-size: 0.875rem;
      color: #6c757d;
      white-space: nowrap;
    }
    .activity-description {
      color: #6c757d;
      font-size: 0.9rem;
      margin: 0;
    }
    .activity-user {
      color: #fd550dff;
      font-weight: 500;
    }

    #history{
      background-color: #fd550dff;
    }

    
  </style>
</head>

<body>
  <!-- Flash Messages -->
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
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
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
                <li><a class="item" href="#"><i cl  ass="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
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

    <!-- Main Content -->
    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
          <h4>STUDENTS MANAGEMENT</h4>
        </div>
        <a href="{{ route('smstudents.export') }}" class="btn btn-success">
          Export
        </a>
      </div>

      <div class="whole">
        <!-- Controls -->
        <div class="dd">
          <div class="line">
            <h6>Show Entries:</h6>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">10</button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item entries-option" href="#" data-value="10">10</a></li>
                <li><a class="dropdown-item entries-option" href="#" data-value="25">25</a></li>
                <li><a class="dropdown-item entries-option" href="#" data-value="50">50</a></li>
                <li><a class="dropdown-item entries-option" href="#" data-value="100">100</a></li>
              </ul>
            </div>
          </div>
          <div class="search">
            <h4 class="search-text">Search</h4>
            <input type="search" placeholder="" class="search-holder" id="searchInput">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>

        <!-- Table -->
        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col">Roll No.</th>
              <th scope="col">Student Name</th>
              <th scope="col">Batch Name</th>
              <th scope="col">Course Name</th>
              <th scope="col">Course Content</th>
              <th scope="col">Delivery Mode</th>
              <th scope="col">Shift</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @forelse($students as $student)
              @php
                $studentId = $student->_id ?? $student->id ?? null;
                if (is_object($studentId)) {
                  $studentId = (string) $studentId;
                }
              @endphp
              <tr data-row="true">
                <td>{{ $student->roll_no ?? 'N/A' }}</td>
                <td>{{ $student->student_name ?? $student->name ?? 'N/A' }}</td>
                <td>{{ $student->batch_name ?? ($student->batch->name ?? 'N/A') }}</td>
                <td>{{ $student->course_name ?? ($student->course->name ?? 'N/A') }}</td>
                <td>{{ $student->course_content ?? 'N/A' }}</td>
                <td>{{ $student->delivery ?? $student->delivery_mode ?? 'N/A' }}</td>
               {{-- Display shift with fallback --}}
                <td>{{ $student->shift->name ?? $student->shift ?? 'N/A' }}</td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                      id="actionDropdown{{ $studentId }}" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $studentId }}">
                      <li>
                        <a class="dropdown-item" href="{{ route('smstudents.show', $studentId) }}">
                         View Details
                        </a>
                      </li>
                      @if(($student->status ?? 'active') === 'active')
                        <li>
                          <a class="dropdown-item" href="{{ route('smstudents.edit', $studentId) }}">
                          Edit Details
                          </a>
                        </li>
                        <li>
                          <button class="dropdown-item" type="button" data-bs-toggle="modal"
                            data-bs-target="#passwordModal{{ $studentId }}">
                            Password Update
                          </button>
                        </li>
                        <li>
<button class="dropdown-item open-batch-modal" data-student-id="{{ $studentId }}">Batch Update</button>                        <li>
                          <!-- <button class="dropdown-item" type="button" data-bs-toggle="modal"
                            data-bs-target="#shiftModal{{ $studentId }}">
                            Shift Update
                          </button> -->
                          @if(empty($batches))
                              <div class="alert alert-danger">⚠️ No batches found!</div>
                            @endif
                          <button class="dropdown-item open-shift-modal" data-student-id="{{ $studentId }}">Shift Update</button>
                        </li>

                        <li>
                          <button class="dropdown-item" type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#historyModal{{ $studentId }}">
                           History
                          </button>
                        </li>
                      @else
                        <li><span class="dropdown-item-text text-muted"><i class="fas fa-info-circle me-2"></i> Student Inactive</span></li>
                      @endif
                    </ul>
                  </div>
                </td>
              </tr>
            @empty
              <tr id="noResultsRow">
                <td colspan="8" class="text-center">No students found</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <!-- Pagination Info -->
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div id="paginationInfo">
            Showing <span id="showingFrom">1</span> to <span id="showingTo">10</span> of <span id="totalEntries">{{ $students->count() }}</span> entries
          </div>
          <nav>
            <ul class="pagination" id="pagination">
              <!-- Pagination buttons will be generated by JavaScript -->
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals: Password Update, Batch Update, History (NO EDIT MODAL) -->
  @foreach($students as $student)
    @php
      $studentId = $student->_id ?? $student->id ?? null;
      if (is_object($studentId)) {
        $studentId = (string) $studentId;
      }
    @endphp

    <!-- Password Update Modal -->
    <div class="modal fade" id="passwordModal{{ $studentId }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" action="{{ route('smstudents.updatePassword', $studentId) }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Update Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control" required minlength="6">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Password</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Batch Update Modal -->
    @php
  $studentId = $student->_id ?? $student->id ?? null;
  if (is_object($studentId)) {
    $studentId = (string) $studentId;
  }
@endphp

<div class="modal fade" id="batchModal{{ $studentId }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="{{ route('smstudents.updateBatch', $studentId) }}" class="modal-content">
      @csrf
      
      <div class="modal-header" style="background: linear-gradient(135deg, #fd550dff 0%, #ff7d3d 100%);">
        <h5 class="modal-title text-white">
          <i class="fas fa-user-group me-2"></i>Update Batch
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        <!-- Current Batch Info -->
        <div class="alert alert-info mb-3">
          <strong>Current Batch:</strong> 
          {{ $student->batch->batch_id ?? $student->batch_name ?? 'N/A' }}
          <br>
          <small class="text-muted">Course: {{ $student->course->name ?? $student->course_name ?? 'N/A' }}</small>
        </div>
        
        <!-- New Batch Selection -->
        <div class="mb-3">
          <label class="form-label fw-semibold">
            Select New Batch <span class="text-danger">*</span>
          </label>
          <select name="batch_id" class="form-select" required>
            <option value="">-- Select Batch --</option>
            @foreach($batches as $batch)
              @php
                $batchId = $batch->_id ?? $batch->id;
                if (is_object($batchId)) {
                  $batchId = (string) $batchId;
                }
                $currentBatchId = $student->batch_id ?? null;
                if (is_object($currentBatchId)) {
                  $currentBatchId = (string) $currentBatchId;
                }
                $isSelected = ($currentBatchId == $batchId);
              @endphp
              <option value="{{ $batchId }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $batch->batch_id ?? 'Batch' }}
                @if($batch->course)
                  - {{ $batch->course }}
                @endif
                @if($batch->class)
                  ({{ $batch->class }})
                @endif
                @if($batch->shift)
                  - {{ $batch->shift }}
                @endif
                @if($batch->mode)
                  [{{ $batch->mode }}]
                @endif
              </option>
            @endforeach
          </select>
        </div>
      </div>
      
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-2"></i>Update Batch
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Global Shift Modal -->
<div class="modal fade" id="shiftModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="shiftForm" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Update Shift</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Select New Shift</label>
          <select name="shift_id" id="shiftSelect" class="form-select" required>
            <option value="">-- Select Shift --</option>
            @foreach($shifts as $shift)
              <option value="{{ $shift->_id }}">
                {{ $shift->name }}
                @if($shift->start_time && $shift->end_time)
                  ({{ $shift->start_time }} - {{ $shift->end_time }})
                @endif
              </option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Shift</button>
      </div>
    </form>
  </div>
</div>

<!-- History Modal with Dynamic Activity Timeline -->
<div class="modal fade" id="historyModal{{ $studentId }}" tabindex="-1" aria-labelledby="historyModalLabel{{ $studentId }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg rounded-3">
      
      <!-- Modal Header -->
      <div class="modal-header text-white" style="background-color: #e15914ff;">
        <h5 class="modal-title fw-semibold" id="historyModalLabel{{ $studentId }}">
          <i class="fas fa-history me-2"></i>Student History - {{ $student->student_name ?? $student->name ?? 'N/A' }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        
        <!-- Student Details Section -->
        <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #ff7d3d;">
          <i class="fas fa-user-circle me-2"></i>Student Details
        </h6>
        <div class="row g-3 mb-4">
          
          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Roll No</label>
            <div class="border rounded p-2 bg-light">{{ $student->roll_no ?? 'N/A' }}</div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Student Name</label>
            <div class="border rounded p-2 bg-light">{{ $student->student_name ?? $student->name ?? 'N/A' }}</div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Email</label>
            <div class="border rounded p-2 bg-light">{{ $student->email ?? 'N/A' }}</div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Phone</label>
            <div class="border rounded p-2 bg-light">{{ $student->phone ?? $student->mobileNumber ?? 'N/A' }}</div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Course Name</label>
            <div class="border rounded p-2 bg-light">{{ $student->course->name ?? $student->course_name ?? $student->courseName ?? 'N/A' }}</div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Batch</label>
            <div class="border rounded p-2 bg-light">{{ $student->batch->name ?? $student->batch_name ?? $student->batchName ?? 'N/A' }}</div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Course Content</label>
            <div class="border rounded p-2 bg-light">{{ $student->course_content ?? $student->courseContent ?? 'N/A' }}</div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Delivery Mode</label>
            <div class="border rounded p-2 bg-light">{{ $student->delivery ?? $student->delivery_mode ?? $student->deliveryMode ?? 'N/A' }}</div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Shift</label>
            <div class="border rounded p-2 bg-light">
              @if($student->shift_id && $student->shift)
                {{ $student->shift->name }}
              @elseif($student->shift)
                {{ $student->shift }}
              @else
                N/A
              @endif
            </div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Status</label>
            <div class="border rounded p-2 bg-light">
              <span class="badge {{ ($student->status ?? 'active') == 'active' ? 'bg-success' : 'bg-danger' }}">
                {{ ucfirst($student->status ?? 'active') }}
              </span>
            </div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Created At</label>
            <div class="border rounded p-2 bg-light">
              @if(isset($student->created_at))
                {{ $student->created_at->format('d M Y, h:i A') }}
              @else
                N/A
              @endif
            </div>
          </div>

          <div class="col-md-4">
            <label class="fw-semibold text-secondary small">Last Updated</label>
            <div class="border rounded p-2 bg-light">
              @if(isset($student->updated_at))
                {{ $student->updated_at->format('d M Y, h:i A') }}
              @else
                N/A
              @endif
            </div>
          </div>

        </div>

        <!-- Activity Timeline Section -->
        <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #ff7d3d;">
          <i class="fas fa-history me-2"></i>Activity Timeline
        </h6>
        
        @if(isset($student->activities) && is_array($student->activities) && count($student->activities) > 0)
          <div class="activity-timeline">
            @foreach($student->activities as $activity)
              <div class="activity-item">
                <div class="activity-header">
                  <div>
                    <p class="activity-title">{{ $activity['title'] ?? 'Activity' }}</p>
                    <p class="activity-description">
                      <span class="activity-user">{{ $activity['performed_by'] ?? 'Admin' }}</span> 
                      {{ $activity['description'] ?? 'performed an action' }}
                    </p>
                  </div>
                  <span class="activity-time">
                    @if(isset($activity['created_at']))
                      @php
                        try {
                          $activityDate = is_string($activity['created_at']) 
                            ? \Carbon\Carbon::parse($activity['created_at']) 
                            : $activity['created_at'];
                          echo $activityDate->format('d M Y h:i A');
                        } catch (\Exception $e) {
                          echo 'N/A';
                        }
                      @endphp
                    @else
                      N/A
                    @endif
                  </span>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <!-- No Activity Found -->
          <div class="alert alert-info text-center py-4">
            <i class="fas fa-info-circle fa-2x mb-3"></i>
            <h6 class="mb-2">No Activity History</h6>
            <p class="mb-0 text-muted small">No activities have been recorded for this student yet.</p>
          </div>
        @endif

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Close
        </button>
      </div>

    </div>
  </div>
</div>

  @endforeach

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/emp.js') }}"></script>
<script>
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

    // Table functionality
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
        if (noResultsRow) {
          noResultsRow.style.display = '';
        } else {
          const tempRow = document.createElement('tr');
          tempRow.innerHTML = '<td colspan="8" class="text-center">No matching students found</td>';
          tempRow.id = 'tempNoResults';
          tableBody.appendChild(tempRow);
        }
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
      prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
      prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">Previous</a>`;
      pagination.appendChild(prevLi);

      const maxVisiblePages = 5;
      let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
      let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
      if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
      }

      if (startPage > 1) {
        pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1); return false;">1</a></li>`;
        if (startPage > 2) {
          pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
      }

      for (let i = startPage; i <= endPage; i++) {
        pagination.innerHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a></li>`;
      }

      if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
          pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${totalPages}); return false;">${totalPages}</a></li>`;
      }

      const nextLi = document.createElement('li');
      nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
      nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Next</a>`;
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

    // Shift modal setup
    document.querySelectorAll('.open-shift-modal').forEach(button => {
      button.addEventListener('click', function () {
        const studentId = this.dataset.studentId;
        const form = document.getElementById('shiftForm');
        form.action = `/smstudents/${studentId}/update-shift`;
        $('#shiftModal').modal('show');
      });
    });

    // Batch modal setup
    document.querySelectorAll('.open-batch-modal').forEach(button => {
  button.addEventListener('click', function () {
    const studentId = this.dataset.studentId;
    const modalId = `#batchModal${studentId}`;
    $(modalId).modal('show');
  });
});


    // AJAX batch update
//      document.getElementById('batchForm').addEventListener('submit', function (e) {
//   e.preventDefault();

//   const studentId = document.getElementById('batchStudentId').value;
//   const batchId = document.getElementById('batchSelect').value;
//   const token = document.querySelector('#batchForm input[name="_token"]').value;

//   fetch(`/smstudents/${studentId}/update-batch`, {
//     method: 'POST',
//     headers: {
//       'Content-Type': 'application/json',
//       'Accept': 'application/json',
//       'X-CSRF-TOKEN': token
//     },
//     body: JSON.stringify({ batch_id: batchId })
//   })
//   .then(async response => {
//     const contentType = response.headers.get('content-type');
//     if (!contentType || !contentType.includes('application/json')) {
//       throw new Error('Server returned non-JSON response');
//     }
//     const data = await response.json();
//     if (data.success) {
//       alert('✅ Batch updated successfully!');
//       $('#batchModal').modal('hide');
//     } else {
//       alert('❌ Failed to update batch: ' + data.message);
//     }
//   })
//   .catch(error => {
//     console.error('Error:', error);
//     alert('❌ Something went wrong: ' + error.message);
//   });
// });
//   

//shift modal
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.open-shift-modal').forEach(button => {
        button.addEventListener('click', function () {
          const studentId = this.dataset.studentId;
          const form = document.getElementById('shiftForm');
          form.action = `/smstudents/${studentId}/update-shift`;
          $('#shiftModal').modal('show');
        });
      });
    });

  const studentId = document.getElementById('batchStudentId').value;
  const batchId = document.getElementById('batchSelect').value;
  const token = document.querySelector('#batchForm input[name="_token"]').value;

  fetch(`/smstudents/${studentId}/update-batch`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': token
    },
    body: JSON.stringify({ batch_id: batchId })
  })
  .then(async response => {
    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      throw new Error('Server returned non-JSON response');
    }
    const data = await response.json();
    if (data.success) {
      alert('✅ Batch updated successfully!');
      $('#batchModal').modal('hide');
    } else {
      alert('❌ Failed to update batch: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('❌ Something went wrong: ' + error.message);
  });
});
</script>
</body>
</html>