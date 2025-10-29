{{--

SESSION MANAGEMENT BLADE FILE - CODE SUMMARY


LINE 1-19: Document setup - HTML5 doctype, head section with meta tags, title, 
           external CSS (Font Awesome, custom emp.css, Bootstrap)

LINE 20-49: Header section - Logo, toggle button for sidebar, session selector,
            notification bell, user dropdown menu with profile and login options

LINE 50-51: Main container div starts

LINE 52-233: Left Sidebar Navigation
  - LINE 52-58: Sidebar container and admin info display
  - LINE 60-233: Bootstrap accordion menu with 9 collapsible sections:
    * LINE 61-75: User Management (Employee, Batches Assignment)
    * LINE 76-99: Master (Courses, Batches, Scholarship, Fees, Branch)
    * LINE 100-114: Session Management (Session, Calendar, Student Migrate)
    * LINE 115-131: Student Management (Inquiry, Onboard, Pending Fees, Students)
    * LINE 132-142: Fees Management (Fees Collection)
    * LINE 143-155: Attendance Management (Student, Employee)
    * LINE 156-168: Study Material (Units, Dispatch Material)
    * LINE 169-179: Test Series Management (Test Master)
    * LINE 180-200: Reports (Walk In, Attendance, Test Series, Inquiry, Onboard)

LINE 234-252: Right Content Area Header
  - LINE 239-246: Action buttons 

LINE 253-282: Table Controls
  - LINE 254-268: Show entries dropdown (10, 25, 50, 100 options)
  - LINE 269-274: Search input field with icon

LINE 275-295: Table Structure
  - LINE 287-289: Empty tbody tag
  - LINE 290-294: Comment indicating modal fillables location

LINE 296-338: Dynamic session Table Rows (Blade foreach loop)
  - Displays session data from database
  - Status badge with color coding

LINE 340-342: Comment for options modals section

LINE 344-375: View Modal (foreach loop for each session)
  - Read-only display of session details

LINE 377-445: Edit Modal (foreach loop for each session)
  - LINE 379-382: PHP variables setup for current department and roles
  - LINE 384-443: Edit form with PUT method

LINE 481-498: Footer Section
  - LINE 482-484: Pagination info text
  - LINE 485-493: Pagination controls (Previous, page numbers, Next)

LINE 499-500: Closing divs for main container

LINE 622-624: Closing divs and body tag

LINE 625-628: External JavaScript includes (Bootstrap bundle, emp.js, jQuery)

LINE 629-665: AJAX Script for Dynamic Session Addition
  - Prevents page reload on form submit
  - Handles form validation errors
  - Appends new session to table without refresh
--}}


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Courses Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/courses.css') }}">
</head>

<body>
  <!-- Flash Messages -->
  @if(session('success'))
    <div class="flash-container">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="flash-container">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  @endif

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
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Main Container -->
  <div class="main-container">
    <!-- Sidebar -->
    <div class="left" id="sidebar">
      <div class="text" id="text">
        <h6>ADMIN</h6>
        <p>synthesisbikaner@gmail.com</p>
      </div>

      <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"
              id="accordion-button">
              <i class="fa-solid fa-user-group" id="side-icon"></i>User Management </button>
          </h2>
          <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li>><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user"
                      id="side-icon"></i> Employee</a></li>
                <li>><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group"
                      id="side-icon"></i> Batches
                    Assignment</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo"
              id="accordion-button">
              <i class="fa-solid fa-user-group" id="side-icon"></i> Master </button>
          </h2>
          <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('courses.index') }}"><i class="fa-solid fa-book-open"
                      id="side-icon"></i> Courses</a></li>
                <li><a class="item" href="{{ route('batches.index') }}"><i
                      class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i>
                    Batches</a></li>
                <li><a class="item" href="{{ route('master.scholarship.index') }}"><i class="fa-solid fa-graduation-cap"
                      id="side-icon"></i> Scholarship</a>
                </li>
                <li><a class="item" href="{{ route('fees.index') }}">
<i class="fa-solid fa-credit-card"
                      id="side-icon"></i> Fees Master</a></li>
                <li><a class="item" href="{{ route('master.other_fees.index') }}"><i class="fa-solid fa-wallet"
                      id="side-icon"></i> Other Fees Master</a>
                </li>
                <li><a class="item" href="{{ route('branches.index') }}"><i class="fa-solid fa-diagram-project"
                      id="side-icon"></i> Branch
                    Management</a></li>
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
                <li><a class="item" href="{{ route('sessions.index') }}"><i class="fa-solid fa-calendar-day"
                      id="side-icon"></i> Session</a></li>
                <li><a class="item {{ request()->routeIs('calendar.index') ? 'active' : '' }}" 
                  href="{{ route('calendar.index') }}"><i class="fa-solid fa-calendar-days"
                      id="side-icon"></i> Calendar</a></li>
                <li><a class="item" href="/session mana/student/student.html"><i class="fa-solid fa-user-check"
                      id="side-icon"></i> Student Migrate</a>
                </li>
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
                <li>><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info"
                      id="side-icon"></i> Inquiry
                    Management</a></li>
                <li><a class="item" href="{{ route('student.student.pending') }}">
  <i class="fa-solid fa-user-check"
                      id="side-icon"></i>Student Onboard</a>
                </li>
                <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check"
                      id="side-icon"></i>Pending Fees
                    Students</a></li>
                <li><a class="item" href="/student management/students/stu.html"><i class="fa-solid fa-user-check"
                      id="side-icon"></i>Students</a></li>
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
                <li><a class="item" href="/fees management/collect/collect.html"><i class="fa-solid fa-credit-card"
                      id="side-icon"></i> Fees Collection</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix"
              id="accordion-button">
              <i class="fa-solid fa-user-check" id="side-icon"></i> Attendance Managment
            </button>
          </h2>
          <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="/attendance management/students/student.html"> <i class="fa-solid fa-user"
                      id="side-icon"> </i>Student</a></li>
                <li><a class="item" href="/attendance management/employee/employee.html"> <i class="fa-solid fa-user"
                      id="side-icon"> </i>Employee</a></li>
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
                <li><a class="item" href="/study material/units/units.html"> <i class="fa-solid fa-user" id="side-icon">
                    </i>Units</a></li>
                <li><a class="item" href="/study material/dispatch/dispatch.html"> <i class="fa-solid fa-user"
                      id="side-icon"> </i>Dispatch Material</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight"
              id="accordion-button">
              <i class="fa-solid fa-chart-column" id="side-icon"></i> Test Series Managment
            </button>
          </h2>
          <div id="flush-collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="/testseries/test.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Test
                    Master</i></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine"
              id="accordion-button">
              <i class="fa-solid fa-square-poll-horizontal" id="side-icon"></i> Reports</i>
            </button>
          </h2>
          <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="/reports/walk in/walk.html"> <i class="fa-solid fa-user" id="side-icon">
                    </i>Walk In</a></li>
                <li><a class="item" href="/reports/att/att.html"><i class="fa-solid fa-calendar-days"
                      id="side-icon"></i> Attendance</a>
                </li>
                <li><a class="item" href="/reports/test/test.html"><i class="fa-solid fa-file" id="side-icon"></i>Test
                    Series</a></li>
                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry
                    History</a></li>
                <li><a class="item" href="/reports/onboard/onboard.html"><i class="fa-solid fa-file"
                      id="side-icon"></i>Onboard History</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

<div class="right" id="right">
  <div class="top d-flex justify-content-between align-items-center flex-wrap">
  <div class="top-text">
    <h4>Courses</h4>
  </div>

  <div class="d-flex gap-2 align-items-center">
    <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center" id="liveToastBtn" data-bs-toggle="modal"
    style="min-width: 140px; height: 38px;" 
      data-bs-target="#createCourseModal">
      Create Course
    </button>

  <button type="button" class="btn btn-success d-flex align-items-center justify-content-center" 
          style="min-width: 140px; height: 38px;" 
          data-bs-toggle="modal" data-bs-target="#uploadCourseModal">
    <i class="fa-solid fa-upload me-1"></i> Upload
  </button>
</div>

  <div class="toast-container end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-body" id="toast">
        <i class="fa-regular fa-circle-xmark" style="color: #ff0000;"></i>Cannot create course. Error occurred
      </div>
    </div>
  </div>
</div>

  <div class="whole">
    <div class="dd">
      <div class="line">
        <h6>Show Entries:</h6>
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            10
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item">10</a></li>
            <li><a class="dropdown-item">25</a></li>
            <li><a class="dropdown-item">50</a></li>
            <li><a class="dropdown-item">100</a></li>
          </ul>
        </div>
      </div>
      <div class="search mb-3">
    <form method="GET" action="{{ route('courses.index') }}">
        <div class="input-group">
            <input 
                type="search" 
                name="search" 
                id="searchInput" 
                class="form-control" 
                placeholder="Search courses..." 
                value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-primary" style="background-color: #ff6600; color: white;">
    <i class="fa-solid fa-magnifying-glass"></i>
</button>
        </div>
    </form>
</div>
    </div>
    <table class="table table-hover" id="table">
      <thead>
        <tr>
          <th scope="col" id="one">Serial No.</th>
          <th scope="col" id="one">Course Name</th>
          <th scope="col" id="one">Course Type</th>
          <th scope="col" id="one">Class</th>
          <th scope="col" id="one">Course Code</th>
          <th scope="col" id="one">Status</th>
          <th scope="col" id="one">Action</th>
        </tr>
      </thead>
      <tbody id="coursesTable">
        @foreach($courses as $index => $course)
          @php
            $courseId = $course->_id ?? $course->id ?? null;
            if (is_object($courseId)) {
              $courseId = (string) $courseId;
            }
          @endphp
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $course->course_name }}</td>
            <td>{{ ucfirst($course->course_type) }}</td>
            <td>{{ $course->class_name }}</td>
            <td>{{ $course->course_code }}</td>
            <td>
              <span class="badge {{ $course->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                {{ ucfirst($course->status) }}
              </span>
            </td>
            <td>
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                        type="button" 
                        id="actionDropdown{{ $courseId }}" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $courseId }}">
                  <li>
                    <button class="dropdown-item" 
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#viewCourseModal{{ $courseId }}">
                            View Details
                    </button>
                  </li>

                  <li>
                    <button class="dropdown-item" 
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#editCourseModal{{ $courseId }}">
                            Edit Details
                    </button>
                  </li>

                  <li><hr class="dropdown-divider"></li>

                  <li>
                    <form method="POST" action="{{ route('courses.destroy', $courseId) }}" class="d-inline w-100">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                              class="dropdown-item text-danger" 
                              onclick="return confirm('Are you sure you want to delete this course?')">
                              Delete Course
                      </button>
                    </form>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <!-- showing entries -->
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div class="show">
        Showing {{ $courses->count() }} entries
      </div>
      <div>
        {{ $courses->links() }}
      </div>

      <!-- UPLOAD MODAL - NEW FEATURE -->
<div class="modal fade" id="uploadCourseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #28a745; color: white;">
        <h5 class="modal-title">Upload</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold">Step 1: Download Sample File</label>
          <p class="text-muted small">Get a pre-formatted Excel file with dummy data to understand the required format.</p>
          <a href="{{ route('courses.downloadSample') }}" class="btn btn-warning w-100" style= "background-color: rgb(224, 83, 1);">
            <i class="fa-solid fa-download"></i> Download Sample File
          </a>
        </div>

        <hr>

        <div class="mb-3">
          <label class="form-label fw-bold">Step 2: Upload Your File</label>
          <p class="text-muted small">Select the edited Excel file to import courses in bulk.</p>
          
          <form id="uploadForm" action="{{ route('courses.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
              <input type="file" id="importFile" name="import_file" class="form-control" 
                accept=".xlsx,.xls,.csv" required>
              <small class="form-text text-muted d-block mt-2">
                Supported formats: Excel (.xlsx, .xls) or CSV. Max size: 2MB
              </small>
            </div>

            <div id="filePreview" class="alert alert-light d-none" style="border: 1px solid #ddd;">
              <strong>File Selected:</strong>
              <div id="previewText"></div>
            </div>

            <button type="submit" class="btn btn-success w-100" id="uploadBtn">
              <i class="fa-solid fa-upload"></i> Import Courses
            </button>
          </form>
        </div>

        <hr>

        <div class="alert alert-secondary" role="alert">
          <strong>Format Guide:</strong>
          <ul class="mb-0 mt-2 small">
            <li><strong>Course Name:</strong> Full name of the course</li>
            <li><strong>Course Type:</strong> Pre - Foundation | Pre - Medical | Pre - Engineering</li>
            <li><strong>Class Name:</strong> e.g., 11th (XI), 12th (XII)</li>
            <li><strong>Course Code:</strong> Unique code for the course</li>
            <li><strong>Subjects:</strong> Separate multiple subjects with semicolons (;)</li>
            <li><strong>Status:</strong> active or inactive</li>
          </ul>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <!-- Create Course Modal -->
    <div class="modal fade" id="createCourseModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="{{ route('courses.store') }}" method="POST" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Create Course</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Course Name</label>
              <input type="text" name="course_name" class="form-control" required value="{{ old('course_name') }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Course Type</label>
              <select name="course_type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="Pre - Foundation" {{ old('course_type') == 'Pre - Foundation' ? 'selected' : '' }}>Pre - Foundation</option>
                <option value="Pre - Medical" {{ old('course_type') == 'Pre - Medical' ? 'selected' : '' }}>Pre - Medical</option>
                <option value="Pre - Engineering" {{ old('course_type') == 'Pre - Engineering' ? 'selected' : '' }}>Pre - Engineering</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Class Name</label>
              <input type="text" name="class_name" class="form-control" required value="{{ old('class_name') }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Course Code</label>
              <input type="text" name="course_code" class="form-control" required value="{{ old('course_code') }}">
            </div>

            <div class="mb-3">
     <label class="form-label">Subjects</label>
      <div class="subject-input-wrapper position-relative">
        <input type="text" 
              id="subjectInput" 
              class="form-control" 
              placeholder="Type subject name (auto-suggest enabled)"
              autocomplete="off">
        
        <!-- Autocomplete dropdown -->
        <div id="subjectSuggestions" 
            class="list-group position-absolute w-100" 
            style="z-index: 1050; max-height: 200px; overflow-y: auto; display: none;">
        </div>
        
        <div id="subjectTags" class="subject-tags mt-2"></div>
        <small class="form-text text-muted">
          Start typing to see suggestions. Press Enter or click to add.
        </small>
      </div>
    </div>

            <div class="mb-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-control" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create Course</button>
          </div>
        </form>
      </div>
    </div>

    <!-- View Modal -->
    @foreach($courses as $course)
      @php
        $courseId = $course->_id ?? $course->id ?? null;
        if (is_object($courseId)) {
          $courseId = (string) $courseId;
        }
        $subjects = is_array($course->subjects) ? $course->subjects : json_decode($course->subjects, true) ?? [];
      @endphp
      <div class="modal fade" id="viewCourseModal{{ $courseId }}" tabindex="-1"
        aria-labelledby="viewCourseLabel{{ $courseId }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="viewCourseLabel{{ $courseId }}">Course Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Course Name</label>
                <input type="text" class="form-control" value="{{ $course->course_name }}" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Course Type</label>
                <input type="text" class="form-control" value="{{ ucfirst($course->course_type) }}" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Class Name</label>
                <input type="text" class="form-control" value="{{ $course->class_name }}" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Course Code</label>
                <input type="text" class="form-control" value="{{ $course->course_code }}" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Subjects</label>
                <div class="subject-tags-readonly">
                  @if(count($subjects) > 0)
                    @foreach($subjects as $subject)
                      <span class="subject-tag-readonly">{{ $subject }}</span>
                    @endforeach
                  @else
                    <span class="text-muted">No subjects assigned</span>
                  @endif
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" class="form-control" value="{{ ucfirst($course->status) }}" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach

    <!-- Edit Modal -->
    @foreach($courses as $course)
      @php
        $courseId = $course->_id ?? $course->id ?? null;
        if (is_object($courseId)) {
          $courseId = (string) $courseId;
        }
        $subjects = is_array($course->subjects) ? $course->subjects : json_decode($course->subjects, true) ?? [];
      @endphp
      <div class="modal fade" id="editCourseModal{{ $courseId }}" tabindex="-1"
        aria-labelledby="editCourseLabel{{ $courseId }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <form method="POST" action="{{ route('courses.update', $courseId) }}">
              @csrf
              @method('PUT')
              <div class="modal-header">
                <h5 class="modal-title" id="editCourseLabel{{ $courseId }}">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Course Name</label>
                  <input type="text" class="form-control" name="course_name" value="{{ $course->course_name }}" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Course Type</label>
                  <select name="course_type" class="form-control" required>
                    <option value="Pre - Foundation" {{ $course->course_type == 'Pre - Foundation' ? 'selected' : '' }}>Pre - Foundation</option>
                    <option value="Pre - Medical" {{ $course->course_type == 'Pre - Medical' ? 'selected' : '' }}>Pre - Medical</option>
                    <option value="Pre - Engineering" {{ $course->course_type == 'Pre - Engineering' ? 'selected' : '' }}>Pre - Engineering</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Class Name</label>
                  <input type="text" class="form-control" name="class_name" value="{{ $course->class_name }}" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Course Code</label>
                  <input type="text" class="form-control" name="course_code" value="{{ $course->course_code }}" required>
                </div>
                <div class="mb-3">
  <label class="form-label">Subjects</label>
  <div class="subject-input-wrapper position-relative">
    <input type="text" 
           id="editSubjectInput{{ $courseId }}" 
           class="form-control" 
           placeholder="Type subject name (auto-suggest enabled)"
           autocomplete="off">
    
    <div id="editSubjectSuggestions{{ $courseId }}" 
         class="list-group position-absolute w-100" 
         style="z-index: 1050; max-height: 200px; overflow-y: auto; display: none;">
    </div>
    
    <div id="editSubjectTags{{ $courseId }}" 
         class="subject-tags mt-2" 
         data-subjects='@json($subjects)' 
         data-course-id="{{ $courseId }}">
    </div>
  </div>
</div>

                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select class="form-select" name="status">
                    <option value="active" {{ $course->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $course->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/courses.js') }}"></script>
</body>

</html>
