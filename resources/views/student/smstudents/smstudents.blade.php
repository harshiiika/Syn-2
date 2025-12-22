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

    /* Enhanced Timeline Styles */
.timeline-item {
  transition: all 0.3s ease;
}

.timeline-item .card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.timeline-item .card.hover-shadow:hover {
  transform: translateX(5px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Payment Details Card Styling */
.bg-success-subtle {
  background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-warning-subtle {
  background-color: rgba(255, 193, 7, 0.1) !important;
}

/* Badge styling */
.badge {
  font-weight: 500;
  padding: 0.35em 0.65em;
}

/* Timeline dots animation */
.timeline-item .position-absolute {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
  }
  50% {
    box-shadow: 0 0 0 6px rgba(13, 110, 253, 0);
  }
}
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
          <li><a class="item" href="{{ route(name: 'test_series.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
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
                              <div class="alert alert-danger">  No batches found!</div>
                            @endif
                          <button class="dropdown-item open-shift-modal" data-student-id="{{ $studentId }}">Shift Update</button>
                        </li>

                       <li>
  <button class="dropdown-item" type="button" onclick="loadStudentHistory('{{ $studentId }}'); return false;">
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
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #e15914ff; color: white;">
        <h5 class="modal-title" id="historyModalLabel">
          <i class="fa-solid fa-clock-rotate-left me-2"></i>Activity
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0" id="historyModalBody" style="min-height: 400px; background-color: #ffffff;">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2 text-muted">Loading history...</p>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark me-1"></i>Close
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
    // ========================================
    // SIDEBAR TOGGLE
    // ========================================
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

    // ========================================
    // AUTO-HIDE FLASH MESSAGES
    // ========================================
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        });
    }, 5000);

    // ========================================
    // TABLE FUNCTIONALITY
    // ========================================
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
            }
        }

        const totalEntries = filteredRows.length;
        const showingFromEl = document.getElementById('showingFrom');
        const showingToEl = document.getElementById('showingTo');
        const totalEntriesEl = document.getElementById('totalEntries');
        
        if (showingFromEl) showingFromEl.textContent = totalEntries > 0 ? start + 1 : 0;
        if (showingToEl) showingToEl.textContent = Math.min(end, totalEntries);
        if (totalEntriesEl) totalEntriesEl.textContent = totalEntries;

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

    // ========================================
    // SHIFT MODAL
    // ========================================
    document.querySelectorAll('.open-shift-modal').forEach(button => {
        button.addEventListener('click', function () {
            const studentId = this.dataset.studentId;
            const form = document.getElementById('shiftForm');
            if (form) {
                form.action = `/smstudents/${studentId}/update-shift`;
            }
            const shiftModal = document.getElementById('shiftModal');
            if (shiftModal) {
                const bsModal = new bootstrap.Modal(shiftModal);
                bsModal.show();
            }
        });
    });

    // ========================================
    // BATCH MODAL
    // ========================================
    document.querySelectorAll('.open-batch-modal').forEach(button => {
        button.addEventListener('click', function () {
            const studentId = this.dataset.studentId;
            const modalId = `#batchModal${studentId}`;
            const modalEl = document.querySelector(modalId);
            if (modalEl) {
                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();
            }
        });
    });
});

// ========================================
// HISTORY MODAL - GLOBAL SCOPE
// ========================================
let historyModal;

document.addEventListener('DOMContentLoaded', function() {
    const historyModalEl = document.getElementById('historyModal');
    if (historyModalEl) {
        historyModal = new bootstrap.Modal(historyModalEl);
        console.log('  History Modal initialized');
    }
});

//  GLOBAL FUNCTION - Load Student History
function loadStudentHistory(studentId) {
    console.log('  Loading history for student:', studentId);

    const historyModalBody = document.getElementById('historyModalBody');
    if (!historyModalBody) {
        console.error(' historyModalBody element not found');
        return;
    }

    // Show loading spinner
    historyModalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading complete student history...</p>
        </div>
    `;

    // Show modal
    if (historyModal) {
        historyModal.show();
    }

    // Fetch history from SMStudents controller
    fetch(`/smstudents/${studentId}/history`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('📡 Response status:', response.status);
            return response.text().then(text => {
                console.log('📡 Raw response:', text);
                try {
                    const json = JSON.parse(text);
                    if (!response.ok) {
                        throw new Error(json.message || `HTTP ${response.status}: Failed to load history`);
                    }
                    return json;
                } catch (e) {
                    console.error('Failed to parse JSON:', e);
                    throw new Error(`Server returned invalid JSON. Status: ${response.status}`);
                }
            });
        })
        .then(json => {
            console.log('  History response:', json);

            if (!json.success) {
                throw new Error(json.message || 'Failed to load history');
            }

            const history = json.data || [];
            const studentName = json.student_name || 'N/A';
            const rollNo = json.roll_no || 'N/A';
            const totalPaid = json.total_paid || 0;
            const remaining = json.remaining || 0;
            const totalFees = json.total_fees || 0;

            // Update modal title
            document.getElementById('historyModalLabel').innerHTML = `
                <i class="fa-solid fa-clock-rotate-left me-2"></i>Activity - ${escapeHtml(studentName)} (${escapeHtml(rollNo)})
            `;

            // If no history exists
            if (history.length === 0) {
                historyModalBody.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fa-solid fa-clock-rotate-left fa-4x mb-3" style="color: #ddd;"></i>
                        <h5 class="mb-2">No History Available</h5>
                        <p class="text-muted">Activity will appear here once changes are made to this student</p>
                    </div>
                `;
                return;
            }

            // Render history list
            let historyHtml = `
               
                
                <!-- History List -->
                <div class="list-group list-group-flush">
            `;

            history.forEach((item, index) => {
                const action = item.action || item.title || 'Activity';
                const description = item.description || 'Activity recorded';
                const user = item.user || item.performed_by || 'Admin';
                
                const date = new Date(item.timestamp || item.created_at || Date.now());
                const formattedDate = date.toLocaleString('en-IN', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });

                let paymentDetailsHtml = '';
                if (action.toLowerCase().includes('fee paid') && item.details) {
                    const details = item.details;
                    paymentDetailsHtml = `
                        <div class="ms-4 mt-2 small text-muted">
                            <strong>Amount:</strong> ₹${Number(details.amount || 0).toLocaleString('en-IN')} | 
                            <strong>Method:</strong> ${escapeHtml(details.payment_method || 'N/A').toUpperCase()}
                            ${details.installment_number ? ` | <strong>Installment:</strong> #${details.installment_number}` : ''}
                            ${details.transaction_id ? `<br><strong>Transaction ID:</strong> <code class="small">${escapeHtml(details.transaction_id)}</code>` : ''}
                            ${details.remarks ? `<br><strong>Remarks:</strong> ${escapeHtml(details.remarks)}` : ''}
                        </div>
                    `;
                }

                historyHtml += `
                    <div class="list-group-item border-0 border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1" style="color: #e15914ff; font-weight: 600;">
                                    ${escapeHtml(user)}
                                </h6>
                                <p class="mb-0 text-dark">
                                    <strong>${escapeHtml(action)}</strong>
                                </p>
                                <p class="mb-0 text-muted small">
                                    ${escapeHtml(description)}
                                </p>
                                ${paymentDetailsHtml}
                            </div>
                            <div class="text-end ms-3" style="min-width: 180px;">
                                <small class="text-muted">
                                    ${formattedDate}
                                </small>
                            </div>
                        </div>
                    </div>
                `;
            });

            historyHtml += '</div>';
            historyModalBody.innerHTML = historyHtml;

        })
        .catch(error => {
            console.error(' History error:', error);
            historyModalBody.innerHTML = `
                <div class="text-center text-danger py-5">
                    <i class="fa-solid fa-exclamation-triangle fa-4x mb-3"></i>
                    <h5 class="mb-2">Failed to Load History</h5>
                    <p class="text-muted">${escapeHtml(error.message)}</p>
                    <button class="btn btn-primary mt-3" onclick="loadStudentHistory('${studentId}')">
                        <i class="fas fa-redo me-2"></i>Retry
                    </button>
                    <small class="d-block mt-3 text-muted">Check browser console for details</small>
                </div>
            `;
        });
}

//  HELPER FUNCTION - Escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
</body>
</html>