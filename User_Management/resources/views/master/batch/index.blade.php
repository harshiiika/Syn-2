{{--

BATCH ASSIGNMENT BLADE FILE - CODE SUMMARY


LINE 1-19: Document setup - HTML5 doctype, head section with meta tags, title,
external CSS (Font Awesome, custom emp.css, Bootstrap 5.3.6)

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
- LINE 239-246: Action buttons (Add Batch, Upload)

LINE 253-282: Table Controls
- LINE 254-268: Show entries dropdown (10, 25, 50, 100 options)
- LINE 269-274: Search input field with icon

LINE 275-295: Batch Table Structure
- LINE 276-286: Table headers
- LINE 287-289: Empty tbody tag
- LINE 290-294: Comment indicating modal fillables location

LINE 296-338: Dynamic Batch Table Rows (Blade foreach loop)
- Displays batch data from database
- Status badge with color coding
- Action dropdown with 4 options: View, Edit, Password Update, Activate/Deactivate

LINE 340-342: Comment for options modals section

LINE 344-375: View Modal (foreach loop for each batch)

LINE 377-445: Edit Modal (foreach loop for each batch)
- LINE 379-382: PHP variables setup for current department and roles
- LINE 384-443: Edit form with PUT method
- Editable fields: Name, Email, Mobile, Alternate Mobile, Branch, Department
- Current Role displayed as read-only


LINE 481-498: Footer Section
- LINE 482-484: Pagination info text
- LINE 485-493: Pagination controls (Previous, page numbers, Next)

LINE 499-500: Closing divs for main container

LINE 504-600: Add Batch Modal
- LINE 504-509: Modal dialog setup
- LINE 510-586: Form with POST method to add new batch

LINE 622-624: Closing divs and body tag

LINE 625-628: External JavaScript includes (Bootstrap bundle, emp.js)

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
  <title>Batches</title>
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
      <div class="top">
        <div class="top-text">
          <h4>BATCH ASSIGNMENT</h4>
        </div>
        <div class="buttons">
          <!-- Button to open Add Batch modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalOne"
            id="add">
            Create Batch
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
      <th scope="col" id="one">Batch Code</th>
      <th scope="col" id="one">Class</th>
      <th scope="col" id="one">Course Type</th>
      <th scope="col" id="one">Branch</th>
      <th scope="col" id="one">Delivery Mode</th>
      <th scope="col" id="one">Medium</th>
      <th scope="col" id="one">Shift</th>
      <th scope="col" id="one">Status</th>
      <th scope="col" id="one">Action</th>
    </tr>
          </thead>
          <tbody>
 <!-- Modal fillables where roles are assigned according to dept automatically -->

          @foreach($batches as $index => $batch)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $batch->batch_id ?? '—' }}</td>
              <td>{{ $batch->class ?? '—' }}</td>
              <td>{{ $batch->course ?? '—' }}</td>
              <td>{{ $batch->course_type ?? '—' }}</td>
              <td>{{ $batch->medium ?? '—' }}</td>
              <td>{{ $batch->mode ?? '—' }}</td>
              <td>{{ $batch->shift ?? '—' }}</td>
              <td>
                <span class="badge {{ $batch->status === 'Inactive' ? 'bg-danger' : 'bg-success' }}">
                  {{ $batch->status ?? 'Active' }}
                </span>
              </td>
              <td>
                <div class="dropdown">
                  
                    <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenuButton"
                      data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-ellipsis-v"></i>
    </button>
                  <ul class="dropdown-menu">
                    <li>
                      <button class="dropdown-item" data-bs-toggle="modal"
                        data-bs-target="#viewBatchModal{{ $batch->_id }}">
                        View Details
                      </button>
                    </li>
                    <li>
                      <button class="dropdown-item" data-bs-toggle="modal"
                        data-bs-target="#editBatchModal{{ $batch->_id }}">
                        Edit Details
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          @endforeach
  </tbody>
         

        </table>

        <!-- Here options modals are present. -->

        <!-- View Modal -->

@foreach($batches as $batch)
  <div class="modal fade" id="viewBatchModal{{ $batch->_id }}" tabindex="-1" 
       aria-labelledby="viewBatchModalLabel{{ $batch->_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewBatchModalLabel{{ $batch->_id }}">Batch Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-bold">Batch Code</label>
            <input type="text" class="form-control" value="{{ $batch->batch_id ?? '—' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Course</label>
            <input type="text" class="form-control" value="{{ $batch->course ?? '—' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Course Type</label>
            <input type="text" class="form-control" value="{{ $batch->course_type ?? '—' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Branch Name</label>
            <input type="text" class="form-control" value="{{ $batch->branch_name ?? '—' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Start Date</label>
            <input type="text" class="form-control" value="{{ $batch->start_date ?? '—' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Delivery Mode</label>
            <input type="text" class="form-control" value="{{ $batch->mode ?? '—' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Medium</label>
            <input type="text" class="form-control" value="{{ $batch->medium ?? '—' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Shift</label>
            <input type="text" class="form-control" value="{{ $batch->shift ?? '—' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Installment Date 2</label>
            <input type="text" class="form-control" value="{{ $batch->installment_date_2 ?? 'Not Set' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Installment Date 3</label>
            <input type="text" class="form-control" value="{{ $batch->installment_date_3 ?? 'Not Set' }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Status</label>
            <input type="text" class="form-control" value="{{ $batch->status ?? 'Active' }}" readonly>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endforeach

       <!-- Edit Batch Modal -->
@foreach($batches as $batch)
  <div class="modal fade" id="editBatchModal{{ $batch->_id }}" tabindex="-1" 
       aria-labelledby="editBatchModalLabel{{ $batch->_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <form method="POST" action="{{ route('batches.update', $batch->_id) }}">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title" id="editBatchModalLabel{{ $batch->_id }}">Edit Batch Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            
            <!-- Batch Code -->
            <div class="mb-3">
              <label class="form-label">Batch Code</label>
              <input type="text" class="form-control" name="batch_id" value="{{ $batch->batch_id ?? '' }}" required>
            </div>

            <!-- Course -->
            <div class="mb-3">
              <label class="form-label">Course</label>
              <select class="form-select" name="course" required>
                <option value="Anthesis 11th NEET" {{ ($batch->course ?? '') == 'Anthesis 11th NEET' ? 'selected' : '' }}>Anthesis 11th NEET</option>
                <option value="Momentum 12th NEET" {{ ($batch->course ?? '') == 'Momentum 12th NEET' ? 'selected' : '' }}>Momentum 12th NEET</option>
                <option value="Dynamic Target NEET" {{ ($batch->course ?? '') == 'Dynamic Target NEET' ? 'selected' : '' }}>Dynamic Target NEET</option>
                <option value="Impulse 11th IIT" {{ ($batch->course ?? '') == 'Impulse 11th IIT' ? 'selected' : '' }}>Impulse 11th IIT</option>
                <option value="Intensity 12th IIT" {{ ($batch->course ?? '') == 'Intensity 12th IIT' ? 'selected' : '' }}>Intensity 12th IIT</option>
                <option value="Thurst Target IIT" {{ ($batch->course ?? '') == 'Thurst Target IIT' ? 'selected' : '' }}>Thurst Target IIT</option>
                <option value="Seedling 10th" {{ ($batch->course ?? '') == 'Seedling 10th' ? 'selected' : '' }}>Seedling 10th</option>
                <option value="Plumule 9th" {{ ($batch->course ?? '') == 'Plumule 9th' ? 'selected' : '' }}>Plumule 9th</option>
                <option value="Radicle 8th" {{ ($batch->course ?? '') == 'Radicle 8th' ? 'selected' : '' }}>Radicle 8th</option>
                <option value="Nucleus 7th" {{ ($batch->course ?? '') == 'Nucleus 7th' ? 'selected' : '' }}>Nucleus 7th</option>
                <option value="Atom 6th" {{ ($batch->course ?? '') == 'Atom 6th' ? 'selected' : '' }}>Atom 6th</option>
              </select>
            </div>

            <!-- Course Type -->
            <div class="mb-3">
              <label class="form-label">Course Type</label>
              <select class="form-select" name="course_type" required>
                <option value="Pre-Medical" {{ ($batch->course_type ?? '') == 'Pre-Medical' ? 'selected' : '' }}>Pre-Medical</option>
                <option value="Pre-Engineering" {{ ($batch->course_type ?? '') == 'Pre-Engineering' ? 'selected' : '' }}>Pre-Engineering</option>
                <option value="Pre-Foundation" {{ ($batch->course_type ?? '') == 'Pre-Foundation' ? 'selected' : '' }}>Pre-Foundation</option>
              </select>
            </div>

            <!-- Branch Name -->
            <div class="mb-3">
              <label class="form-label">Branch Name</label>
              <select class="form-select" name="branch_name" required>
                <option value="Bikaner" {{ ($batch->branch_name ?? '') == 'Bikaner' ? 'selected' : '' }}>Bikaner</option>
                <option value="Jaipur" {{ ($batch->branch_name ?? '') == 'Jaipur' ? 'selected' : '' }}>Jaipur</option>
                <option value="Jodhpur" {{ ($batch->branch_name ?? '') == 'Jodhpur' ? 'selected' : '' }}>Jodhpur</option>
                <option value="Kota" {{ ($batch->branch_name ?? '') == 'Kota' ? 'selected' : '' }}>Kota</option>
              </select>
            </div>

            <!-- Start Date -->
            <div class="mb-3">
              <label class="form-label">Start Date</label>
              <input type="date" class="form-control" name="start_date" value="{{ $batch->start_date ?? '' }}" required>
            </div>

            <!-- Delivery Mode -->
            <div class="mb-3">
              <label class="form-label">Delivery Mode</label>
              <select class="form-select" name="mode" required>
                <option value="Distance Learning" {{ ($batch->mode ?? '') == 'Distance Learning' ? 'selected' : '' }}>Distance Learning</option>
                <option value="Online" {{ ($batch->mode ?? '') == 'Online' ? 'selected' : '' }}>Online</option>
                <option value="Offline" {{ ($batch->mode ?? '') == 'Offline' ? 'selected' : '' }}>Offline</option>
              </select>
            </div>

            <!-- Medium -->
            <div class="mb-3">
              <label class="form-label">Medium</label>
              <select class="form-select" name="medium" required>
                <option value="English" {{ ($batch->medium ?? '') == 'English' ? 'selected' : '' }}>English</option>
                <option value="Hindi" {{ ($batch->medium ?? '') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
              </select>
            </div>

            <!-- Shift -->
            <div class="mb-3">
              <label class="form-label">Shift</label>
              <select class="form-select" name="shift" required>
                <option value="Evening" {{ ($batch->shift ?? '') == 'Evening' ? 'selected' : '' }}>Evening</option>
                <option value="Morning" {{ ($batch->shift ?? '') == 'Morning' ? 'selected' : '' }}>Morning</option>
              </select>
            </div>

            <!-- Installment Date 2 -->
            <div class="mb-3">
              <label class="form-label">Installment Date 2</label>
              <input type="date" class="form-control" name="installment_date_2" value="{{ $batch->installment_date_2 ?? '' }}">
            </div>

            <!-- Installment Date 3 -->
            <div class="mb-3">
              <label class="form-label">Installment Date 3</label>
              <input type="date" class="form-control" name="installment_date_3" value="{{ $batch->installment_date_3 ?? '' }}">
            </div>

            <!-- Status -->
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select class="form-select" name="status">
                <option value="Active" {{ ($batch->status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ ($batch->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
  </div>
@endforeach

<!-- Add Batch Modal -->
<div class="modal fade" id="exampleModalOne" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" id="content-one">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create Batch</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('batches.add') }}" id="createBatchForm">
          @csrf
          
          <!-- Course Dropdown - This will auto-fill Class Name & Course Type -->
          <div class="mb-3">
            <label for="course" class="form-label">Course <span class="text-danger">*</span></label>
            <select class="form-select" name="course" id="courseSelect" required>
              <option selected disabled>Select Course</option>
              <option value="Anthesis 11th NEET">Anthesis 11th NEET</option>
              <option value="Momentum 12th NEET">Momentum 12th NEET</option>
              <option value="Dynamic Target NEET">Dynamic Target NEET</option>
              <option value="Impulse 11th IIT">Impulse 11th IIT</option>
              <option value="Intensity 12th IIT">Intensity 12th IIT</option>
              <option value="Thrust Target IIT">Thrust Target IIT</option>
              <option value="Seedling 10th">Seedling 10th</option>
              <option value="Plumule 9th">Plumule 9th</option>
              <option value="Radicle 8th">Radicle 8th</option>
              <option value="Nucleus 7th">Nucleus 7th</option>
              <option value="Atom 6th">Atom 6th</option>
            </select>
          </div>

          <!-- Auto-filled fields (Read-only, shown for reference) -->
          <div class="mb-3">
            <label class="form-label">Class Name <span class="text-muted">(Auto-filled)</span></label>
            <input type="text" class="form-control bg-light" id="classNameDisplay" readonly placeholder="Will be auto-filled">
          </div>

          <div class="mb-3">
            <label class="form-label">Course Type <span class="text-muted">(Auto-filled)</span></label>
            <input type="text" class="form-control bg-light" id="courseTypeDisplay" readonly placeholder="Will be auto-filled">
          </div>

          <!-- Batch Code -->
          <div class="mb-3">
            <label for="batch_id" class="form-label">Batch Code <span class="text-danger">*</span></label>
            <input type="text" name="batch_id" class="form-control" placeholder="e.g., 20T1, 19L1" required>
          </div>

          <!-- Branch Name -->
          <div class="mb-3">
            <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
            <select class="form-select" name="branch_name" required>
              <option selected disabled>Select Branch</option>
              <option value="Bikaner">Bikaner</option>
            </select>
          </div>

          <!-- Start Date -->
          <div class="mb-3">
            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="start_date" class="form-control" required>
          </div>

          <!-- Delivery Mode -->
          <div class="mb-3">
            <label for="mode" class="form-label">Delivery Mode <span class="text-danger">*</span></label>
            <select class="form-select" name="mode" required>
              <option selected disabled>Select Delivery Mode</option>
              <option value="Offline">Offline</option>
              <option value="Online">Online</option>
            </select>
          </div>

          <!-- Medium -->
          <div class="mb-3">
            <label for="medium" class="form-label">Medium <span class="text-danger">*</span></label>
            <select class="form-select" name="medium" required>
              <option selected disabled>Select Medium</option>
              <option value="English">English</option>
              <option value="Hindi">Hindi</option>
            </select>
          </div>

          <!-- Shift -->
          <div class="mb-3">
            <label for="shift" class="form-label">Shift <span class="text-danger">*</span></label>
            <select class="form-select" name="shift" required>
              <option selected disabled>Select Shift</option>
              <option value="Morning">Morning</option>
              <option value="Evening">Evening</option>
            </select>
          </div>

          <!-- Installment Dates -->
          <div class="mb-3">
            <label class="form-label fw-bold">Installment Dates (Optional)</label>
          </div>

          <div class="mb-3">
            <label for="installment_date_2" class="form-label">Installment Date 2</label>
            <input type="date" name="installment_date_2" class="form-control">
          </div>

          <div class="mb-3">
            <label for="installment_date_3" class="form-label">Installment Date 3</label>
            <input type="date" name="installment_date_3" class="form-control">
          </div>

          <!-- Status -->
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status">
              <option value="Active" selected>Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>

          <div class="modal-footer" id="footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create Batch</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Auto-fill class name and course type based on selected course
document.getElementById('courseSelect').addEventListener('change', function() {
  const courseMapping = {
    'Anthesis 11th NEET': { class: '11th (XI)', type: 'Pre-Medical' },
    'Momentum 12th NEET': { class: '12th (XII)', type: 'Pre-Medical' },
    'Dynamic Target NEET': { class: 'Target (XII +)', type: 'Pre-Medical' },
    'Impulse 11th IIT': { class: '11th (XI)', type: 'Pre-Engineering' },
    'Intensity 12th IIT': { class: '12th (XII)', type: 'Pre-Engineering' },
    'Thrust Target IIT': { class: 'Target (XII +)', type: 'Pre-Engineering' },
    'Seedling 10th': { class: '10th (X)', type: 'Pre-Foundation' },
    'Plumule 9th': { class: '9th (IX)', type: 'Pre-Foundation' },
    'Radicle 8th': { class: '8th (VIII)', type: 'Pre-Foundation' },
    'Nucleus 7th': { class: '7th (VII)', type: 'Pre-Foundation' },
    'Atom 6th': { class: '6th (VI)', type: 'Pre-Foundation' }
  };

  const selectedCourse = this.value;
  const courseData = courseMapping[selectedCourse];

  if (courseData) {
    document.getElementById('classNameDisplay').value = courseData.class;
    document.getElementById('courseTypeDisplay').value = courseData.type;
  }
});
</script>

<!-- Upload Modal-->
<!-- <div class="modal fade" id="exampleModalTwo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" id="modal-two">
      <div class="modal-header">
        <h2 class="modal-title fs-5" id="exampleModalLabel">Upload Batches</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="sample-body">
        <a href="{{ route('batches.downloadSample') }}">
          <button type="button" class="sampleFile" id="xlsx">Download Sample File</button>
        </a>
          @csrf
          <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
          <button type="submit" class="btn btn-primary mt-3">Upload</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> -->

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
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>

</body>
<!-- External JavaScript Libraries -->
<!-- Bootstrap Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="{{asset('js/emp.js')}}"></script>


<!-- AJAX Script: Handles dynamic user addition without page reload -->
<script>
  // Event handler for add user form submission
  // Ajax for dynamic user addition without page reload
  $('#addUserForm').on('submit', function (e) {
    // Prevent default form submission behavior
    e.preventDefault();
    // Clear previous error messages
    $('.text-danger').text('');


    // AJAX POST request to add user
    $.ajax({
      url: "{{ route('users.add') }}",
      method: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        // On successful user addition
        if (response.status === 'success') {
          // Close the modal
          $('#addUserModal').modal('hide');
          // Reset form fields
          $('#addUserForm')[0].reset();

          // Dynamically append new user row to table without page reload
          // Append user to table
          $('#users-table tbody').append(`
                    <tr>
                        <td>${response.user.name}</td>
                        <td>${response.user.email}</td>
                        <td>${response.user.phone}</td>
                    </tr>
                `);
        }
      },
      error: function (xhr) {
        // Handle validation errors (HTTP 422)
        if (xhr.status === 422) {
          const errors = xhr.responseJSON.errors;
          // Display error messages for each field
          for (let field in errors) {
            $(#error - ${ field }).text(errors[field][0]);
          }
        }
      }
    });
  });
</script>

</html>