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
          <li><a class="dropdown-item" href="/profile/profile.html"> <i class="fa-solid fa-user"></i>Profile</a></li>
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
              <i class="fa-solid fa-user-group" id="side-icon"></i>User Management </button>
          </h2>
          <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('user.emp.emp') }}"> <i class="fa-solid fa-user"
                      id="side-icon"></i> Employee</a></li>
                <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group"
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
                    <i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Master</a></li>
                <li><a class="item" href="{{ route('master.other_fees.index') }}
"><i class="fa-solid fa-wallet"
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
                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info"
                      id="side-icon"></i> Inquiry Management </a></li>
                <li><a class="item" href="{{ route('student.student.pending') }}">
                    <i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a>
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

          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalTwo"
            id="up">
            Upload
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
          <div class="search">
            <h4 class="search-text">Search</h4>
            <input type="search" placeholder="" class="search-holder" required>
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
              <!-- Serial number (index + 1) -->
              <td>{{ $index + 1 }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->mobileNumber ?? '—' }}</td>
              <td>{{ $user->roleNames->implode(', ') }}</td>
              <td>{{ $user->departmentNames->implode(', ') }}</td>

              <td>
                <span class="badge {{ $user->status === 'Deactivated' ? 'bg-danger' : 'bg-success' }}">
                  {{ $user->status ?? 'Active' }}
                </span>
              </td>

             <td>
  <div class="dropdown">
    <button class="btn btn-sm btn-outline-secondary" type="button" 
            id="dropdownMenu{{ $loop->index }}" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
      <i class="fas fa-ellipsis-v"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" 
        aria-labelledby="dropdownMenu{{ $loop->index }}">
      <li>
        <a class="dropdown-item" href="#" 
           data-bs-toggle="modal"
           data-bs-target="#viewModal{{ $user->_id }}">
          <i class="fas fa-eye me-2"></i>View Details
        </a>
      </li>
      <li>
        <a class="dropdown-item" href="#" 
           data-bs-toggle="modal"
           data-bs-target="#editModal{{ $user->_id }}">
          <i class="fas fa-edit me-2"></i>Edit Details
        </a>
      </li>
      <li>
        <a class="dropdown-item" href="#" 
           data-bs-toggle="modal"
           data-bs-target="#passwordModal{{ $user->_id }}">
          <i class="fas fa-key me-2"></i>Password Update
        </a>
      </li>
      <li>
        <form method="POST" action="{{ route('users.toggleStatus', $user->_id) }}" style="display: inline;">
          @csrf
          <button type="submit" class="dropdown-item">
            <i class="fas fa-toggle-{{ $user->status === 'Active' ? 'off' : 'on' }} me-2"></i>
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
          <div class="modal fade" id="viewModal{{ $user->_id }}" tabindex="-1" data-bs-target="#viewModal{{ $user->_id }}"
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
        <!-- Password Update Modal -->

        @foreach($users as $user)

          <div class="modal fade" id="passwordModal{{ $user->_id }}" tabindex="-1"
            aria-labelledby="passwordModalLabel{{ $user->_id }}" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="POST" action="{{ route('users.password.update', $user->_id) }}">
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel{{ $user->_id }}">Update Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Current Password</label>
                      <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">New Password</label>
                      <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Confirm New Password</label>
                      <input type="password" name="confirm_new_password" class="form-control" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" id="submit" class="btn btn-primary">Update Password</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

        @endforeach
      </div>
      <div class="footer">
        <div class="left-footer">
          <p>Showing 1 to 10 of 10 Enteries</p>
        </div>
        <div class="right-footer">
          <nav aria-label="...">
            <ul class="pagination">
              <li class="page-item"><a href="#" class="page-link" id="pg1">Previous</a></li>
              <li class="page-item active">
                <a class="page-link" href="#" aria-current="page" id="pg2">1</a>
              </li>
              <li class="page-item"><a class="page-link" href="/user management/emp/emp2.html" id="pg3">2</a></li>
              <li class="page-item"><a class="page-link" href="#" id="pg1">Next</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- Modal Form with fillables for add employee starts here -->

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
          <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Enter Your Name" required>
            <span class="text-danger" id="error-name"></span>
          </div>

          <div class="mb-3">
            <label for="mobileNumber" class="form-label">Mobile No. <span class="text-danger">*</span></label>
            <input type="tel" name="mobileNumber" class="form-control" placeholder="Enter Your Mobile Number" required>
            <span class="text-danger" id="error-mobileNumber"></span>
          </div>

          <div class="mb-3">
            <label for="alternateNumber" class="form-label">Alternate Mobile No.</label>
            <input type="tel" name="alternateNumber" class="form-control" placeholder="Enter Your Alternate Mobile Number">
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required>
            <span class="text-danger" id="error-email"></span>
          </div>

          <div class="mb-3">
            <label for="branch" class="form-label">Select Branch <span class="text-danger">*</span></label>
            <select class="form-select" name="branch" required>
              <option selected disabled>Select Branch</option>
              <option value="Bikaner">Bikaner</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="departments" class="form-label">Select Department <span class="text-danger">*</span></label>
            <select class="form-select" name="departments[]" required>
              <option selected disabled>Select Department</option>
              <option value="Front Office">Front Office</option>
              <option value="Back Office">Back Office</option>
              <option value="Office">Office</option>
              <option value="Test Management">Test Management</option>
              <option value="Admin">Admin</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
            <span class="text-danger" id="error-password"></span>
          </div>

          <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
            <span class="text-danger" id="error-confirm_password"></span>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

    <!-- Upload Modal -->
    <div class="modal fade" id="exampleModalTwo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">

        <div class="modal-dialog">
          <div class="modal-content" id="modal-two">
            <div class="modal-header">
              <h2 class="modal-title fs-5" id="exampleModalLabel">Upload</h2>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="sample-body">
              <a href="/user management/emp/employees_synthesis.xlsx"><button class="sampleFile" id="xlsx">Download
                  Sample File</button></a>
              <form action="upload.php" method="post" enctype="multipart/form-data" id="form-control">
                <input type="file" class="form-control" id="inputGroupFile01">
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="submit">Close</button>
              <button type="button" class="btn btn-primary" id="add">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

<!-- Custom JS -->
<script src="{{asset('js/emp.js')}}"></script>

<!-- AJAX Script -->
<script>
$('#addEmployeeForm').on('submit', function (e) {
    e.preventDefault();
    $('.text-danger').text(''); // Clear errors

    $.ajax({
        url: "{{ route('users.add') }}",
        method: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            // Close modal
            $('#exampleModalOne').modal('hide');
            
            // Reset form
            $('#addEmployeeForm')[0].reset();
            
            // Reload page to show new employee
            window.location.href = "{{ route('user.emp.emp') }}";
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                for (let field in errors) {
                    $('#error-' + field).text(errors[field][0]);
                }
            } else {
                alert('An error occurred. Please try again.');
            }
        }
    });
});
</script>

</html>