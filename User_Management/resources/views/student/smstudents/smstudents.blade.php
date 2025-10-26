<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Students Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/smstudents.css') }}">
  <style>
    /* Additional styles for detailed view */
    .student-detail-view {
      background-color: #ffffff;
      border-radius: 8px;
      padding: 30px;
      margin: 20px 0;
    }
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
      color: #e05301;
      border-bottom-color: #e05301;
      font-weight: 600;
    }
    .tab-btn:hover {
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
                <li>><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i> Employee</a></li>
                <li>><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group" id="side-icon"></i> Batches Assignment</a></li>
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
          <div id="flush-collapseFour" class="accordion-collapse collapse show" data-bs-parent="#accordionFlushExample">
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
      <div class="top">
        <div class="top-text">
          <h4>STUDENTS MANAGEMENT</h4>
        </div>
        <a href="{{ route('smstudents.export') }}" class="btn btn-success">
          <i class="fas fa-file-export me-2"></i>Export
        </a>
      </div>

      <div class="whole">
        <div class="dd">
          <div class="line">
            <h6>Show Entries:</h6>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">10</button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item entries-option" data-value="10">10</a></li>
                <li><a class="dropdown-item entries-option" data-value="25">25</a></li>
                <li><a class="dropdown-item entries-option" data-value="50">50</a></li>
                <li><a class="dropdown-item entries-option" data-value="100">100</a></li>
              </ul>
            </div>
          </div>
          <div class="search">
            <h4 class="search-text">Search</h4>
            <input type="search" placeholder="" class="search-holder" id="searchInput" required>
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>

        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col" id="one">Roll No.</th>
              <th scope="col" id="one">Student Name</th>
              <th scope="col" id="one">Batch Name</th>
              <th scope="col" id="one">Course Name</th>
              <th scope="col" id="one">Course Content</th>
              <th scope="col" id="one">Delivery Mode</th>
              <th scope="col" id="one">Shift</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($students as $student)
              @php
                $studentId = $student->_id ?? $student->id ?? null;
                if (is_object($studentId)) {
                  $studentId = (string) $studentId;
                }
              @endphp
              <tr>
                <td>{{ $student->roll_no }}</td>
                <td>{{ $student->student_name ?? $student->name }}</td>
                <td>{{ $student->batch_name ?? $student->batch->name ?? 'N/A' }}</td>
                <td>{{ $student->course_name ?? $student->course->name ?? 'N/A' }}</td>
                <td>{{ $student->course_content }}</td>
                <td>{{ $student->delivery ?? $student->delivery_mode }}</td>
                <td>{{ $student->shift }}</td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                      id="actionDropdown{{ $studentId }}" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $studentId }}">
                      <li>
                        <button class="dropdown-item view-details-btn" type="button" data-student-id="{{ $studentId }}">
                          <i class="fas fa-eye me-2"></i>View Details
                        </button>
                      </li>
                      @if(($student->status ?? 'active') === 'active')
                        <li>
                          <button class="dropdown-item" type="button" data-bs-toggle="modal"
                            data-bs-target="#editStudentModal{{ $studentId }}">
                            <i class="fas fa-edit me-2"></i>Edit Details
                          </button>
                        </li>
                        <li>
                          <button class="dropdown-item" type="button" data-bs-toggle="modal"
                            data-bs-target="#passwordModal{{ $studentId }}">
                            <i class="fas fa-key me-2"></i>Password Update
                          </button>
                        </li>
                        <li>
                          <button class="dropdown-item" type="button" data-bs-toggle="modal"
                            data-bs-target="#batchModal{{ $studentId }}">
                            <i class="fas fa-users me-2"></i>Batch Update
                          </button>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                          <form method="POST" action="{{ route('smstudents.deactivate', $studentId) }}" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"
                              onclick="return confirm('Are you sure you want to deactivate this student?')">
                              <i class="fas fa-ban me-2"></i>Deactivate Student
                            </button>
                          </form>
                        </li>
                      @else
                        <li><span class="dropdown-item-text text-muted"><i class="fas fa-info-circle me-2"></i> Student Inactive</span></li>
                      @endif>
                    </ul>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center">No students found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- MODALS SECTION -->
  
  <!-- View Details Modal (Full Detail View) -->
  @foreach($students as $student)
    @php
      $studentId = $student->_id ?? $student->id ?? null;
      if (is_object($studentId)) {
        $studentId = (string) $studentId;
      }
    @endphp

    <!-- Detailed View Modal -->
    <div class="modal fade" id="viewDetailModal{{ $studentId }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" style="color: #e05301;">Student View Detail</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="student-detail-view">
              <!-- Tab Navigation -->
              <div class="tab-navigation">
                <button class="tab-btn active" data-tab="student-detail-{{ $studentId }}">Student Detail</button>
                <button class="tab-btn" data-tab="student-attendance-{{ $studentId }}">Student attendance</button>
                <button class="tab-btn" data-tab="fees-management-{{ $studentId }}">Fees management</button>
                <button class="tab-btn" data-tab="test-series-{{ $studentId }}">Test Series</button>
              </div>

              <!-- Tab Content: Student Detail -->
              <div class="tab-content-section" id="student-detail-{{ $studentId }}">
                <!-- Student Avatar and Roll Number -->
                <div class="student-avatar">
                  <i class="fas fa-user"></i>
                </div>
                <div class="roll-number">
                  Roll Number<br>
                  <strong>{{ $student->roll_no }}</strong>
                </div>

                <!-- Personal Information -->
                <div class="detail-section">
                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Student Name</span>
                      <span class="detail-value">{{ $student->student_name ?? $student->name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Father Name</span>
                      <span class="detail-value">{{ $student->father_name ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Mother Name</span>
                      <span class="detail-value">{{ $student->mother_name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">DOB</span>
                      <span class="detail-value">{{ $student->dob ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Father Contact No</span>
                      <span class="detail-value">{{ $student->father_contact ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Father whatsapp No</span>
                      <span class="detail-value">{{ $student->father_whatsapp ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Mother Contact No</span>
                      <span class="detail-value">{{ $student->mother_contact ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Student contact No</span>
                      <span class="detail-value">{{ $student->phone ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Category</span>
                      <span class="detail-value">{{ $student->category ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Gender</span>
                      <span class="detail-value">{{ $student->gender ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Father Occupation</span>
                      <span class="detail-value">{{ $student->father_occupation ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Mother Occupation</span>
                      <span class="detail-value">{{ $student->mother_occupation ?? 'N/A' }}</span>
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
                      <span class="detail-value">{{ $student->pincode ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Address</span>
                      <span class="detail-value">{{ $student->address ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>

                <!-- Additional Information -->
                <div class="detail-section">
                  <div class="info-box">
                    <div class="info-box-question">Do you belong to another city</div>
                    <div class="info-box-answer">{{ $student->belongs_other_city ?? 'No' }}</div>
                  </div>

                  <div class="info-box">
                    <div class="info-box-question">Do You Belong to Economic Weaker Section</div>
                    <div class="info-box-answer">{{ $student->economic_weaker_section ?? 'No' }}</div>
                  </div>

                  <div class="info-box">
                    <div class="info-box-question">Do You Belong to Any Army/Police/Martyr Background?</div>
                    <div class="info-box-answer">{{ $student->army_police_background ?? 'No' }}</div>
                  </div>

                  <div class="info-box">
                    <div class="info-box-question">Are You a Specially Abled ?</div>
                    <div class="info-box-answer">{{ $student->specially_abled ?? 'No' }}</div>
                  </div>
                </div>

                <!-- Course Detail -->
                <div class="detail-section">
                  <h5>Course Detail</h5>
                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Course Type</span>
                      <span class="detail-value">{{ $student->course_type ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Course Name</span>
                      <span class="detail-value">{{ $student->course_name ?? $student->course->name ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Delivery Mode</span>
                      <span class="detail-value">{{ $student->delivery ?? $student->delivery_mode ?? 'N/A' }}</span>
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
                      <span class="detail-value">{{ $student->course_content ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>

                <!-- Academic Detail -->
                <div class="detail-section">
                  <h5>Academic Detail</h5>
                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Previous Class</span>
                      <span class="detail-value">{{ $student->previous_class ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Medium</span>
                      <span class="detail-value">{{ $student->academic_medium ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Name of School</span>
                      <span class="detail-value">{{ $student->school_name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Board</span>
                      <span class="detail-value">{{ $student->academic_board ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Passing Year</span>
                      <span class="detail-value">{{ $student->passing_year ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Percentage</span>
                      <span class="detail-value">{{ $student->percentage ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>

                <!-- Scholarship Eligibility -->
                <div class="detail-section">
                  <h5>Scholarship Eligibility</h5>
                  <div class="info-box">
                    <div class="info-box-question">Have You Appeared For the Synthesis Scholarship test?</div>
                    <div class="info-box-answer">{{ $student->scholarship_test ?? 'No' }}</div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Percentage Of Marks in last Board Exam</span>
                      <span class="detail-value">{{ $student->board_percentage ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="info-box">
                    <div class="info-box-question">Have You Appeared For any of the competition exam?</div>
                    <div class="info-box-answer">{{ $student->competition_exam ?? 'No' }}</div>
                  </div>
                </div>

                <!-- Batch Allocation -->
                <div class="detail-section">
                  <h5>Batch Allocation</h5>
                  <div class="detail-row">
                    <div class="detail-item">
                      <span class="detail-label">Batch Name</span>
                      <span class="detail-value">{{ $student->batch_name ?? $student->batch->name ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>

                <!-- View Documents -->
                <div class="detail-section">
                  <h5>View Documents</h5>
                  <div class="document-item">
                    <span class="document-name">Passport Size Photo.</span>
                    <span class="document-status">Not Uploaded</span>
                  </div>
                  <div class="document-item">
                    <span class="document-name">Marksheet of Last qualifying Exam.</span>
                    <span class="document-status">Not Uploaded</span>
                  </div>
                  <div class="document-item">
                    <span class="document-name">If you are an Ex-Synthesisian, upload Identity card issued by Synthesis</span>
                    <span class="document-status">Not Uploaded</span>
                  </div>
                  <div class="document-item">
                    <span class="document-name">Upload Proof of Scholarship to avail Concession</span>
                    <span class="document-status">Not Uploaded</span>
                  </div>
                  <div class="document-item">
                    <span class="document-name">Secondary Board Marksheet</span>
                    <span class="document-status">Not Uploaded</span>
                  </div>
                  <div class="document-item">
                    <span class="document-name">Senior Secondary Board Marksheet</span>
                    <span class="document-status">Not Uploaded</span>
                  </div>
                </div>
              </div>

              <!-- Tab Content: Student Attendance -->
              <div class="tab-content-section" id="student-attendance-{{ $studentId }}" style="display: none;">
                <p style="text-align: center; padding: 50px; color: #666;">Attendance data will be displayed here</p>
              </div>

              <!-- Tab Content: Fees Management -->
              <div class="tab-content-section" id="fees-management-{{ $studentId }}" style="display: none;">
                <p style="text-align: center; padding: 50px; color: #666;">Fees management data will be displayed here</p>
              </div>

              <!-- Tab Content: Test Series -->
              <div class="tab-content-section" id="test-series-{{ $studentId }}" style="display: none;">
                <p style="text-align: center; padding: 50px; color: #666;">Test series data will be displayed here</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editStudentModal{{ $studentId }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('smstudents.update', $studentId) }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Edit Student</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Roll Number</label>
                <input type="text" name="roll_no" class="form-control" value="{{ $student->roll_no }}" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Student Name</label>
                <input type="text" name="name" class="form-control" value="{{ $student->student_name ?? $student->name }}" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $student->email }}" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $student->phone }}" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Batch</label>
                <select name="batch_id" class="form-select" required>
                  @foreach($batches as $batch)
                    <option value="{{ $batch->_id }}" {{ $student->batch_id == $batch->_id ? 'selected' : '' }}>
                      {{ $batch->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Course</label>
                <select name="course_id" class="form-select" required>
                  @foreach($courses as $course)
                    <option value="{{ $course->_id }}" {{ $student->course_id == $course->_id ? 'selected' : '' }}>
                      {{ $course->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Course Content</label>
                <select name="course_content" class="form-select" required>
                  <option value="Test Series Only" {{ $student->course_content == 'Test Series Only' ? 'selected' : '' }}>Test Series Only</option>
                  <option value="Class Room Course" {{ $student->course_content == 'Class Room Course' ? 'selected' : '' }}>Class Room Course</option>
                  <option value="Both" {{ $student->course_content == 'Both' ? 'selected' : '' }}>Both</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Delivery Mode</label>
                <select name="delivery_mode" class="form-select" required>
                  <option value="Offline" {{ ($student->delivery ?? $student->delivery_mode) == 'Offline' ? 'selected' : '' }}>Offline</option>
                  <option value="Online" {{ ($student->delivery ?? $student->delivery_mode) == 'Online' ? 'selected' : '' }}>Online</option>
                  <option value="Hybrid" {{ ($student->delivery ?? $student->delivery_mode) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Shift</label>
                <select name="shift" class="form-select" required>
                  <option value="Morning" {{ $student->shift == 'Morning' ? 'selected' : '' }}>Morning</option>
                  <option value="Evening" {{ $student->shift == 'Evening' ? 'selected' : '' }}>Evening</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                  <option value="active" {{ ($student->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ ($student->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>

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
    <div class="modal fade" id="batchModal{{ $studentId }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" action="{{ route('smstudents.updateBatch', $studentId) }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Update Batch</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Select New Batch</label>
              <select name="batch_id" class="form-select" required>
                @foreach($batches as $batch)
                  <option value="{{ $batch->_id }}" {{ $student->batch_id == $batch->_id ? 'selected' : '' }}>
                    {{ $batch->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Batch</button>
          </div>
        </form>
      </div>
    </div>
  @endforeach

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/smstudents.js') }}"></script>
  <script>
    // View Details Button Click Handler
    document.querySelectorAll('.view-details-btn').forEach(button => {
      button.addEventListener('click', function() {
        const studentId = this.getAttribute('data-student-id');
        const modal = new bootstrap.Modal(document.getElementById('viewDetailModal' + studentId));
        modal.show();
      });
    });

    // Tab switching functionality for detail view
    document.querySelectorAll('.tab-btn').forEach(button => {
      button.addEventListener('click', function() {
        const parentModal = this.closest('.modal-body');
        
        // Remove active class from all buttons in this modal
        parentModal.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // Hide all tab contents in this modal
        parentModal.querySelectorAll('.tab-content-section').forEach(content => {
          content.style.display = 'none';
        });
        
        // Show selected tab content
        const tabId = this.getAttribute('data-tab');
        const targetContent = document.getElementById(tabId);
        if (targetContent) {
          targetContent.style.display = 'block';
        }
      });
    });
  </script>
</body>
</html>