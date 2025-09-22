<!DOCTYPE html>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

</head>

<body>
  
  <div class="header">
    <div class="logo">
      <img src="{{asset('images/logo.png.jpg')}}" class="img">
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
                <li><a class="item" href="{{ route('emp') }}"> <i class="fa-solid fa-user"
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
                <li><a class="item" href="/master/courses/course.html"><i class="fa-solid fa-book-open"
                      id="side-icon"></i> Courses</a></li>
                <li><a class="item" href="/master/batches/batches.html"><i
                      class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i>
                    Batches</a></li>
                <li><a class="item" href="/master/scholarship/scholar.html"><i class="fa-solid fa-graduation-cap"
                      id="side-icon"></i> Scholarship</a>
                </li>
                <li><a class="item" href="/master/feesm/fees.html"><i class="fa-solid fa-credit-card"
                      id="side-icon"></i> Fees Master</a></li>
                <li><a class="item" href="/master/other fees/other.html"><i class="fa-solid fa-wallet"
                      id="side-icon"></i> Other Fees Master</a>
                </li>
                <li><a class="item" href="/master/branch/branch.html"><i class="fa-solid fa-diagram-project"
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
                <li><a class="item" href="/session mana/calendar/cal.html"><i class="fa-solid fa-calendar-days"
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
                <li><a class="item" href="/student management/stu onboard/onstu.html"><i class="fa-solid fa-user-check"
                      id="side-icon"></i>Student Onboard</a>
                </li>
                <li><a class="item" href="/student management/pending/pending.html"><i class="fa-solid fa-user-check"
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
                <li><a class="item" href="/reports/inq/inq.html"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry
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
        <h2>Welcome to Dashboard</h2>
        <p>This is the dashboard page.</p>
    </div>
 
</body>
<script src="{{asset('User_js/emp.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<!-- 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

  // Ajax for dynamic user addition without page reload
  $('#addUserForm').on('submit', function (e) {
    e.preventDefault();
    $('.text-danger').text('');

    $.ajax({
      url: "{{ route('users.add') }}",
      method: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        if (response.status === 'success') {
          $('#addUserModal').modal('hide');
          $('#addUserForm')[0].reset();

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
        if (xhr.status === 422) {
          const errors = xhr.responseJSON.errors;
          for (let field in errors) {
            $(`#error-${field}`).text(errors[field][0]);
          }
        }
      }
    });
  });
</script> -->

</html>