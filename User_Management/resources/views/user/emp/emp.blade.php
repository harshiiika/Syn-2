{{--

EMPLOYEE MANAGEMENT BLADE FILE - CODE SUMMARY


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
- LINE 236-238: Page title "EMPLOYEE"
- LINE 239-246: Action buttons (Add Employee, Upload)

LINE 253-282: Table Controls
- LINE 254-268: Show entries dropdown (10, 25, 50, 100 options)
- LINE 269-274: Search input field with icon

LINE 275-295: Employee Table Structure
- LINE 276-286: Table headers (Serial No, Name, Email, Mobile, Department, Role, Status, Action)
- LINE 287-289: Empty tbody tag
- LINE 290-294: Comment indicating modal fillables location

LINE 296-338: Dynamic Employee Table Rows (Blade foreach loop)
- Displays user data from database
- Status badge with color coding
- Action dropdown with 4 options: View, Edit, Password Update, Activate/Deactivate

LINE 344-375: View Modal (foreach loop for each user)
- Read-only display of employee details
- Shows: Name, Email, Mobile, Alternate Mobile, Branch, Department

LINE 377-445: Edit Modal (foreach loop for each user)
- LINE 379-382: PHP variables setup for current department and roles
- LINE 384-443: Edit form with PUT method
- Editable fields: Name, Email, Mobile, Alternate Mobile, Branch, Department
- Current Role displayed as read-only

LINE 447-480: Password Update Modal (foreach loop for each user)
- Form with PUT method for password update
- Fields: Current Password, New Password, Confirm New Password

LINE 481-498: Footer Section
- LINE 482-484: Pagination info text
- LINE 485-493: Pagination controls (Previous, page numbers, Next)

LINE 499-500: Closing divs for main container

LINE 501-503: Comment for Add Employee modal

LINE 504-600: Add Employee Modal
- LINE 504-509: Modal dialog setup
- LINE 510-586: Form with POST method to add new employee
- Fields: Name, Mobile, Alternate Mobile, Email, Branch, Department,
Password, Confirm Password, File upload
- LINE 587-591: Modal footer with Cancel and Submit buttons

LINE 622-624: Closing divs and body tag

LINE 625-628: External JavaScript includes (Bootstrap bundle)

LINE 629-665: AJAX Script for Dynamic User Addition
- Prevents page reload on form submit
- Handles form validation errors
- Appends new user to table without refresh
--}}

<!DOCTYPE html>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee</title>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <!-- Bootstrap 5.3.6 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

</head>

<body>
  <!-- Header Section: Contains logo, sidebar toggle, session selector, notifications, and user menu -->

  <div class="header">
    <div class="logo">
      <img src="{{asset('images/logo.png.jpg')}}" class="img">

      <!-- Sidebar toggle button -->
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
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('profile.index') }}"> <i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log In</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="main-container">
    <!-- Left Sidebar: Navigation menu with collapsible accordion sections -->
    <div class="left" id="sidebar">

      <div class="text" id="text">
        <h6>ADMIN</h6>
        <p>synthesisbikaner@gmail.com</p>
      </div>

      <!-- Left side bar accordian -->
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
                <li>><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i>
                    Employee</a></li>
                <li>><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group"
                      id="side-icon"></i> Batches Assignment</a></li>
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
      <div class="top">
        <div class="top-text">
          <h4>EMPLOYEE</h4>
        </div>
        <div class="buttons">
          <!-- Button to open Add Employee modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalOne"
            id="add">
            Add Employee
          </button>

              <button type="button" 
            class="btn btn-success d-flex align-items-center justify-content-center" 
            style="min-width: 140px; height: 38px;" 
            data-bs-toggle="modal" 
            data-bs-target="#uploadBranchModal"
            id="up">
      <i class="fa-solid fa-upload me-1"></i> Upload
    </button>

        </div>
      </div>
      <div class="whole">
        <!-- Table controls: entries dropdown and search -->
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
                  <form method="GET" action="{{ route('user.emp.emp') }}" id="searchForm">
        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
           <input type="search" 
               name="search" 
               placeholder="Search" 
               class="search-holder" 
               value="{{ request('search') }}"
               id="searchInput">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>
        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col" id="one">Serial No.</th>
              <th scope="col" id="one">Name</th>
              <th scope="col" id="one">Email</th>
              <th scope="col" id="one">Mobile No.</th>
              <th scope="col" id="one">Department</th>
              <th scope="col" id="one">Role</th>
              <th scope="col" id="one">Status</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Modal fillables where roles are assigned according to dept automatically -->
            <!-- Dynamic table rows populated from database using Blade foreach loop -->
            <tr>
            </tr>
          </tbody>
          <!-- Modal fillables where roles are assigned according to dept automatically -->

         @foreach($users as $index => $user)
  <tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->mobileNumber ?? '—' }}</td>
    
    <!--Use the accessor properly -->
    <td>
      @php
        $deptNames = $user->departmentNames ?? collect();
      @endphp
      {{ $deptNames->isNotEmpty() ? $deptNames->implode(', ') : '—' }}
    </td>
    
    <td>
      @php
        $roleNames = $user->roleNames ?? collect();
      @endphp
      {{ $roleNames->isNotEmpty() ? $roleNames->implode(', ') : '—' }}
    </td>

    <td>
      <span class="badge {{ $user->status === 'Deactivated' ? 'bg-danger' : 'bg-success' }}">
        {{ $user->status ?? 'Active' }}
      </span>
    </td>

              <td>
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary" type="button" id="dropdownMenu{{ $loop->index }}"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu{{ $loop->index }}">
                    <li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal"
                        data-bs-target="#viewModal{{ $user->_id }}">
                        View Details
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal"
                        data-bs-target="#editModal{{ $user->_id }}">
                        Edit Details
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal"
                        data-bs-target="#passwordModal{{ $user->_id }}">
                        Password Update
                      </a>
                    </li>
                    <li>
                      <form method="POST" action="{{ route('users.toggleStatus', $user->_id) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="dropdown-item">
                          <!-- <i class="fas fa-toggle-{{ $user->status === 'Active' ? 'off' : 'on' }} me-2"></i> -->
                          {{ $user->status === 'Active' ? 'Deactivate' : 'Reactivate' }}
                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          @endforeach

        </table>

        <!-- Here options modals are present. -->
        <!-- View Modal -->
        @foreach($users as $user)
          <div class="modal fade" id="viewModal{{ $user->_id }}" tabindex="-1"
            aria-labelledby="viewModalLabel{{ $user->_id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="viewModalLabel{{ $user->_id }}">Employee Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" value="{{ $user->email }}" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" class="form-control" value="{{ $user->mobileNumber ?? '—' }}" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Alternate Mobile</label>
                    <input type="text" class="form-control" value="{{ $user->alternateNumber ?? '—' }}" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Branch</label>
                    <input type="text" class="form-control" value="{{ $user->branch ?? '—' }}" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input type="text" class="form-control"
                      value="{{ $user->departmentNames ? $user->departmentNames->join(', ') : '—' }}" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach

        <!-- Edit Modal -->
        @foreach($users as $user)
          <div class="modal fade" id="editModal{{ $user->_id }}" tabindex="-1"
            aria-labelledby="editModalLabel{{ $user->_id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <form method="POST" action="{{ route('users.update', $user->_id) }}">
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $user->_id }}">Edit Employee Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Name</label>
                      <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Mobile</label>
                      <input type="text" class="form-control" name="mobileNumber" value="{{ $user->mobileNumber ?? '' }}"
                        required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Alternate Mobile</label>
                      <input type="text" class="form-control" name="alternateNumber"
                        value="{{ $user->alternateNumber ?? '' }}">
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Branch</label>
                      <select class="form-select" name="branch" required>
                        <option value="Bikaner" {{ $user->branch == 'Bikaner' ? 'selected' : '' }}>Bikaner</option>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Department</label>
                      <select class="form-select" name="department" required>
                        @php
                          $currentDepartment = $user->departmentNames->first() ?? '';
                        @endphp
                        <option value="Front Office" {{ $currentDepartment == 'Front Office' ? 'selected' : '' }}>Front
                          Office</option>
                        <option value="Back Office" {{ $currentDepartment == 'Back Office' ? 'selected' : '' }}>Back Office
                        </option>
                        <option value="Office" {{ $currentDepartment == 'Office' ? 'selected' : '' }}>Office</option>
                        <option value="Test Management" {{ $currentDepartment == 'Test Management' ? 'selected' : '' }}>Test
                          Management</option>
                        <option value="Admin" {{ $currentDepartment == 'Admin' ? 'selected' : '' }}>Admin</option>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Current Role</label>
                      <input type="text" class="form-control" value="{{ $user->roleNames->join(', ') ?? '—' }}" readonly>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @endforeach

<!-- Password Update Modal-->
@foreach($users as $user)
  <div class="modal fade" id="passwordModal{{ $user->_id }}" tabindex="-1"
    aria-labelledby="passwordModalLabel{{ $user->_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <form method="POST" action="{{ route('users.password.update', $user->_id) }}"
          id="passwordForm{{ $user->_id }}">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title" id="passwordModalLabel{{ $user->_id }}">Update Password for {{ $user->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            
            <!-- Display validation errors -->
            <div id="errorContainer{{ $user->_id }}" style="display: none;" class="alert alert-danger">
              <ul id="errorList{{ $user->_id }}" class="mb-0"></ul>
            </div>

            <!-- Current Password -->
            <div class="mb-3">
              <label class="form-label">Current Password <span class="text-danger">*</span></label>
              <input type="password" 
                     name="current_password" 
                     id="current_password{{ $user->_id }}"
                     class="form-control"
                     placeholder="Enter current password" 
                     required>
              <span class="text-danger" id="error-current_password{{ $user->_id }}"></span>
            </div>

            <!-- New Password -->
            <div class="mb-3">
              <label class="form-label">New Password <span class="text-danger">*</span></label>
              <input type="password" 
                     name="new_password" 
                     id="new_password{{ $user->_id }}" 
                     class="form-control"
                     placeholder="Enter new password" 
                     minlength="8" 
                     required>
              <small class="form-text text-muted">Minimum 8 characters, must include uppercase, lowercase, and number</small>
              <span class="text-danger" id="error-new_password{{ $user->_id }}"></span>
            </div>

            <!-- Confirm New Password -->
            <div class="mb-3">
              <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
              <input type="password" 
                     name="confirm_new_password" 
                     id="confirm_password{{ $user->_id }}" 
                     class="form-control"
                     placeholder="Re-enter new password"
                     required>
              <span class="text-danger" id="password-match-error{{ $user->_id }}"></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="submitBtn{{ $user->_id }}">Update Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

      </div>
<div class="footer">
  <div class="left-footer">
    <p>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
      @if(request('search'))
        <span class="text-muted">(filtered from {{ \App\Models\User\User::count() }} total entries)</span>
      @endif
    </p>
  </div>
  <div class="right-footer">
    <nav aria-label="Page navigation example" id="bottom">
      <ul class="pagination" id="pagination">
        {{-- Previous Page Link --}}
        @if ($users->onFirstPage())
          <li class="page-item disabled">
            <span class="page-link" id="pg1">Previous</span>
          </li>
        @else
          <li class="page-item">
            <a class="page-link" 
               href="{{ $users->previousPageUrl() }}" 
               id="pg1">Previous</a>
          </li>
        @endif

        {{-- Pagination Elements --}}
        @php
          $start = max($users->currentPage() - 2, 1);
          $end = min($start + 4, $users->lastPage());
          $start = max($end - 4, 1);
        @endphp

        @if($start > 1)
          <li class="page-item" id="pg2">
            <a class="page-link" href="{{ $users->url(1) }}">1</a>
          </li>
          @if($start > 2)
            <li class="page-item disabled">
              <span class="page-link">...</span>
            </li>
          @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
          <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
            <a class="page-link" 
               href="{{ $users->url($i) }}"
               id="pg{{ $i }}">{{ $i }}</a>
          </li>
        @endfor

        @if($end < $users->lastPage())
          @if($end < $users->lastPage() - 1)
            <li class="page-item disabled">
              <span class="page-link">...</span>
            </li>
          @endif
          <li class="page-item">
            <a class="page-link" href="{{ $users->url($users->lastPage()) }}">{{ $users->lastPage() }}</a>
          </li>
        @endif

        {{-- Next Page Link --}}
        @if ($users->hasMorePages())
          <li class="page-item">
            <a class="page-link" 
               href="{{ $users->nextPageUrl() }}" 
               id="pg4">Next</a>
          </li>
        @else
          <li class="page-item disabled">
            <span class="page-link" id="pg4">Next</span>
          </li>
        @endif
      </ul>
    </nav>
  </div>
</div>
  </div>
  </div>
  <!-- Modal Form with fillables for add employee starts here -->

  <!-- Add Employee Modal -->
<div class="modal fade" id="exampleModalOne" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" id="content-one">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Employee</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('users.add') }}" id="addEmployeeForm">
          @csrf
          
          <!-- Show validation errors -->
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Enter Your Name" value="{{ old('name') }}" required>
          </div>

          <div class="mb-3">
            <label for="mobileNumber" class="form-label">Mobile No. <span class="text-danger">*</span></label>
            <input type="tel" name="mobileNumber" class="form-control" placeholder="Enter 10 digit mobile number"
              pattern="[0-9]{10}" maxlength="10" value="{{ old('mobileNumber') }}" required>
            <small class="form-text text-muted">Enter exactly 10 digits</small>
          </div>

          <div class="mb-3">
            <label for="alternateNumber" class="form-label">Alternate Mobile No.</label>
            <input type="tel" name="alternateNumber" class="form-control"
              placeholder="Enter 10 digit alternate number" pattern="[0-9]{10}" maxlength="10" value="{{ old('alternateNumber') }}">
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="Enter Your Email" value="{{ old('email') }}" required>
          </div>

          <div class="mb-3">
            <label for="branch" class="form-label">Select Branch <span class="text-danger">*</span></label>
            <select class="form-select" name="branch" required>
              <option value="">Select Branch</option>
              <option value="Bikaner" {{ old('branch') == 'Bikaner' ? 'selected' : '' }}>Bikaner</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="department" class="form-label">Select Department <span class="text-danger">*</span></label>
            <select class="form-select" name="department" required>
              <option value="">Select Department</option>
              <option value="Front Office" {{ old('department') == 'Front Office' ? 'selected' : '' }}>Front Office</option>
              <option value="Back Office" {{ old('department') == 'Back Office' ? 'selected' : '' }}>Back Office</option>
              <option value="Office" {{ old('department') == 'Office' ? 'selected' : '' }}>Office</option>
              <option value="Test Management" {{ old('department') == 'Test Management' ? 'selected' : '' }}>Test Management</option>
              <option value="Admin" {{ old('department') == 'Admin' ? 'selected' : '' }}>Admin</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password"
              minlength="6" required>
            <small class="form-text text-muted">Minimum 6 characters</small>
          </div>

          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
              placeholder="Confirm Password" required>
          </div>

          <div class="modal-footer" id="footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="add">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

  <!-- Upload Modal -->
<div class="modal fade" id="uploadBranchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #ed5b00ff; color: white;">
        <h5 class="modal-title">Upload Employees</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- Export Current Data Section -->
        <div class="mb-3">
          <label class="form-label fw-bold">Export Current Data</label>
          <p class="text-muted small">Download all current employee data as Excel file.</p>
          <a href="{{ route('users.export') }}?search={{ request('search') }}&per_page={{ request('per_page', 10) }}" 
             class="btn btn-info w-100" 
Route::prefix('master/batch')->name('batches.')->group(function () {
    Route::get('/', [BatchController::class, 'index'])->name('index');
    Route::post('/add', [BatchController::class, 'store'])->name('add');
    Route::put('/{id}/update', [BatchController::class, 'update'])->name('update');
    Route::post('/{id}/toggle-status', [BatchController::class, 'toggleStatus'])->name('toggleStatus');
    
    // NEW: Export/Import Routes
    Route::get('/export', [BatchController::class, 'exportToExcel'])->name('export');
    Route::get('/download-sample', [BatchController::class, 'downloadSample'])->name('downloadSample');
    Route::post('/import', [BatchController::class, 'import'])->name('import');
});            <i class="fa-solid fa-download"></i> Download Current Employees
          </a>
        </div>

        <hr>

        <!-- Step 2: Upload File -->
        <div class="mb-3">
          <label class="form-label fw-bold">Upload Your File</label>          
          <form id="uploadEmployeeForm" action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
              <input type="file" id="importEmployeeFile" name="import_file" class="form-control" 
                accept=".xlsx,.xls,.csv" required>
            </div>

            <div id="employeeFilePreview" class="alert alert-light d-none" style="border: 1px solid #ddd;">
              <strong>File Selected:</strong>
              <div id="employeePreviewText"></div>
            </div>

            <button type="submit" class="btn btn-success w-100" id="uploadEmployeeBtn">
              <i class="fa-solid fa-upload"></i> Import Employees
            </button>
          </form>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
<!-- Custom JS -->
<script src="{{asset('js/emp.js')}}"></script>

<!-- AJAX Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

<!-- Your custom JS (must come after jQuery + Bootstrap) -->
<script src="{{ asset('js/emp.js') }}"></script>

<!-- Enhanced JavaScript for Password Update and upload modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  @foreach($users as $user)
  (function() {
    const userId = '{{ $user->_id }}';
    const form = document.getElementById('passwordForm' + userId);
    const currentPassword = document.getElementById('current_password' + userId);
    const newPassword = document.getElementById('new_password' + userId);
    const confirmPassword = document.getElementById('confirm_password' + userId);
    const submitBtn = document.getElementById('submitBtn' + userId);
    const matchError = document.getElementById('password-match-error' + userId);
    const newPasswordError = document.getElementById('error-new_password' + userId);
    const errorContainer = document.getElementById('errorContainer' + userId);
    const errorList = document.getElementById('errorList' + userId);

    // Real-time password strength validation
    if (newPassword) {
      newPassword.addEventListener('input', function() {
        const password = this.value;
        let errors = [];

        if (password.length > 0 && password.length < 8) {
          errors.push('Password must be at least 8 characters');
        }
        if (password.length > 0 && !/[a-z]/.test(password)) {
          errors.push('Must contain a lowercase letter');
        }
        if (password.length > 0 && !/[A-Z]/.test(password)) {
          errors.push('Must contain an uppercase letter');
        }
        if (password.length > 0 && !/\d/.test(password)) {
          errors.push('Must contain a number');
        }

        newPasswordError.textContent = errors.join(', ');
        
        // Also check if passwords match when typing in new password
        if (confirmPassword.value && password !== confirmPassword.value) {
          matchError.textContent = 'Passwords do not match';
        } else {
          matchError.textContent = '';
        }
      });
    }

    // Real-time password match validation
    if (confirmPassword) {
      confirmPassword.addEventListener('input', function() {
        if (newPassword.value !== this.value) {
          matchError.textContent = 'Passwords do not match';
        } else {
          matchError.textContent = '';
        }
      });
    }

    // Form submission validation
    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        matchError.textContent = '';
        newPasswordError.textContent = '';
        errorContainer.style.display = 'none';
        errorList.innerHTML = '';
        
        let isValid = true;
        let errors = [];

        // Validate current password
        if (!currentPassword.value) {
          errors.push('Current password is required');
          isValid = false;
        }

        // Validate new password
        if (!newPassword.value) {
          errors.push('New password is required');
          isValid = false;
        } else {
          if (newPassword.value.length < 8) {
            errors.push('New password must be at least 8 characters');
            isValid = false;
          }
          if (!/[a-z]/.test(newPassword.value)) {
            errors.push('New password must contain a lowercase letter');
            isValid = false;
          }
          if (!/[A-Z]/.test(newPassword.value)) {
            errors.push('New password must contain an uppercase letter');
            isValid = false;
          }
          if (!/\d/.test(newPassword.value)) {
            errors.push('New password must contain a number');
            isValid = false;
          }
          if (currentPassword.value === newPassword.value) {
            errors.push('New password must be different from current password');
            isValid = false;
          }
        }

        // Validate password confirmation
        if (!confirmPassword.value) {
          errors.push('Password confirmation is required');
          isValid = false;
        } else if (newPassword.value !== confirmPassword.value) {
          matchError.textContent = 'Passwords do not match';
          errors.push('Passwords do not match');
          isValid = false;
        }

        if (!isValid) {
          // Show errors
          errorList.innerHTML = errors.map(err => '<li>' + err + '</li>').join('');
          errorContainer.style.display = 'block';
          return false;
        }

        // If validation passes, submit the form
        submitBtn.disabled = true;
        submitBtn.textContent = 'Updating...';
        form.submit();
      });
    }

    // Reset form when modal is closed
    const modal = document.getElementById('passwordModal' + userId);
    if (modal) {
      modal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        matchError.textContent = '';
        newPasswordError.textContent = '';
        errorContainer.style.display = 'none';
        errorList.innerHTML = '';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update Password';
      });
    }
  })();
  @endforeach
});

document.addEventListener('DOMContentLoaded', function() {
  const fileInput = document.getElementById('importEmployeeFile');
  const preview = document.getElementById('employeeFilePreview');
  const previewText = document.getElementById('employeePreviewText');
  
  if (fileInput) {
    fileInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      
      if (file) {
        preview.classList.remove('d-none');
        const fileSize = (file.size / 1024).toFixed(2);
        const fileIcon = file.name.endsWith('.csv') ? 'fa-file-csv' : 'fa-file-excel';
        
        previewText.innerHTML = `
          <div class="d-flex align-items-center">
            <i class="fa-solid ${fileIcon} text-success me-2 fs-4"></i>
            <div>
              <div><strong>${file.name}</strong></div>
              <small class="text-muted">${fileSize} KB</small>
            </div>
          </div>
        `;
      } else {
        preview.classList.add('d-none');
      }
    });
  }

  // Reset form when modal closes
  const uploadModal = document.getElementById('uploadBranchModal');
  if (uploadModal) {
    uploadModal.addEventListener('hidden.bs.modal', function() {
      const form = document.getElementById('uploadEmployeeForm');
      if (form) {
        form.reset();
        preview.classList.add('d-none');
      }
    });
  }
});
</script>
</html>