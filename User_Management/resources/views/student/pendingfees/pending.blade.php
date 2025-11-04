{{--

PENDING INQUIRIES BLADE FILE - CODE SUMMARY


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
  - LINE 236-238: Page title "Pending Inquiries"
  - LINE 239-246: Action buttons

LINE 253-282: Table Controls
  - LINE 254-268: Show entries dropdown (10, 25, 50, 100 options)
  - LINE 269-274: Search input field with icon

LINE 275-295: Onboarding Table Structure
  - LINE 276-286: Table headers 
  - LINE 287-289: Empty tbody tag
  - LINE 290-294: Comment indicating modal fillables location

LINE 296-338: Dynamic Employee Table Rows (Blade foreach loop)
  - Displays user data from database
  - Status badge with color coding
  - Action dropdown with 4 options: View, Edit, Transfer, History

LINE 340-342: Comment for options modals section

LINE 344-375: View Modal (foreach loop for each user)
  - Read-only display of employee details
  - Shows: Name, Email, Mobile, Alternate Mobile, Branch, Department

LINE 377-445: Edit Modal (foreach loop for each user)
  - LINE 379-382: PHP variables setup for current department and roles
  - LINE 384-443: Edit form with PUT method

  LINE 481-498: Footer Section
  - LINE 482-484: Pagination info text
  - LINE 485-493: Pagination controls (Previous, page numbers, Next)

LINE 499-500: Closing divs for main container


LINE 622-624: Closing divs and body tag

LINE 625-628: External JavaScript includes (Bootstrap bundle, emp.js, jQuery)

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
  <title>Pending Fees Students</title>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('css/onboard.css')}}">
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
              <i class="fa-solid fa-user-group" id="side-icon"></i>User Management
            </button>
          </h2>
          <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li>><a class="item" href="{{ route('emp') }}"><i class="fa-solid fa-user" id="side-icon"></i> Employee</a></li>
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
        </div>
            <div class="btns">
               <a href="{{ route('student.student.pending') }}"><button type="button" class="onboardbtn">Pending Fees Students</button></a>
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
              <th scope="col" id="one">Student Name</th>
              <th scope="col" id="one">Father Name</th>
              <th scope="col" id="one">Father Contact No.</th>
              <th scope="col" id="one">Course Name</th>
              <th scope="col" id="one">Delivery Mode</th>
              <th scope="col" id="one">Course Content</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            </tr>
          </tbody>
<!-- Modal fillables where roles are assigned according to dept automatically -->

@foreach($pendingFees as $index => $pending)
<tr>
   <!-- Serial number (index + 1) -->
  <td>{{ $index + 1 }}</td>
  <td>{{ $pending->name }}</td>
  <td>{{ $pending->father }}</td>
  <td>{{ $pending->mobileNumber ?? '—' }}</td>
<td>{{ $pending->courseName ?? '—' }}</td>
<td>{{ $pending->deliveryMode ?? '—' }}</td>
<td>{{ $pending->courseContent ?? '—' }}</td>
  <td>
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenuButton"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots-vertical" style="color: #000000;"></i>
      </button>
      <ul class="dropdown-menu" aria-labelledby="actionMenuButton">
      <li>
  <a class="dropdown-item" href="{{ route('student.pendingfees.edit', $pending->_id) }}">Edit</a>
      </li>
      <li>
       <a class="dropdown-item" href="{{ route('student.pendingfees.view', $pending->_id) }}">
    View Details
</a>
<li>
    <a class="dropdown-item" href="{{ route('student.pendingfees.pay', $pending->_id) }}">
      Pay Fees
    </a>
</li>
      <li>
        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#historyModal{{ $pending->_id }}">
        History
        </button>
      </li>
    </ul>
    </div>
  </td>
</tr>
@endforeach
        </table>
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
</body>
<!-- External JavaScript Libraries -->
<!-- Bootstrap Bundle JS (includes Popper) -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
           $(`#error-${field}`).text(errors[field][0]);
          }
        }
      }
    });
  });
</script>
</html>