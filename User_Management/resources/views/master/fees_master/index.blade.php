<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Fees Master</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/FeesMaster.css') }}">
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
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</a></li>
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
                <li><a class="item" href="{{ route('master.other_fees.index') }}"><i class="fa-solid fa-wallet"
                      id="side-icon"></i> Other Fees Master</a>
                </li>
                <li><a class="item" href="{{ route('branches.index') }}"><i class="fa-solid fa-diagram-project"
                      id="side-icon"></i> Branch Management</a></li>
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
                      id="side-icon"></i>Pending Fees Students</a></li>
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
          <h4>FEES MASTER</h4>
        </div>
        <div class="buttons">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add">
            Create Fees
          </button>
        </div>
      </div>
      
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="width: 95%; margin: 10px auto;">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="width: 95%; margin: 10px auto;">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" style="width: 95%; margin: 10px auto;">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      
      <div class="whole">
        <div class="dd">
          <div class="line">
            <h6>Show Entries:</h6>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">10</button>
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

        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col">Serial No.</th>
              <th scope="col">Course Name</th>
              <th scope="col">Course Type</th>
              <th scope="col">Class Name</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data loaded via JavaScript -->
          </tbody>
        </table>

        <div class="footer">
          <div class="left-footer">
            <p id="entriesInfo">Showing 0 to 0 of 0 Entries</p>
          </div>
          <div class="right-footer">
            <nav aria-label="Page navigation example" id="bottom">
              <ul class="pagination" id="pagination">
                <!-- Pagination generated by JavaScript -->
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content" id="content">
        <form action="{{ route('fees.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Fees</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Course</label>
              <div class="input-group">
                <select class="form-control" id="scroll" name="course" required>
                  <option value="">Select Course</option>
                  <option value="Impulse">Impulse</option>
                  <option value="Momentum">Momentum</option>
                  <option value="Intensity">Intensity</option>
                  <option value="Thrust">Thrust</option>
                  <option value="Seedling 10th">Seedling 10th</option>
                  <option value="Anthesis">Anthesis</option>
                  <option value="Dynamic">Dynamic</option>
                  <option value="Radical 8th">Radical 8th</option>
                  <option value="Plumule 9th">Plumule 9th</option>
                  <option value="Pre Radical 7th">Pre Radical 7th</option>
                </select>
              </div>
            </div>
            <p style="color: orangered;">Fees Configuration</p>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST %</label>
              <div class="input-group">
                <input type="number" class="form-control" name="gst_percentage" step="0.01" placeholder="Enter GST %" required>
              </div>
            </div>
            <p style="color: orangered;">Fees After GST</p>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Class Room Course</label>
              <div class="input-group">
                <input type="number" class="form-control" name="classroom_course" step="0.01" placeholder="Enter amount" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="basic-url" class="form-label">Live online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" name="live_online_course" step="0.01" placeholder="Enter amount" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="basic-url" class="form-label">Recorded online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" name="recorded_online_course" step="0.01" placeholder="Enter amount" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="basic-url" class="form-label">Study Material only</label>
              <div class="input-group">
                <input type="number" class="form-control" name="study_material_only" step="0.01" placeholder="Enter amount" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="basic-url" class="form-label">Test series only</label>
              <div class="input-group">
                <input type="number" class="form-control" name="test_series_only" step="0.01" placeholder="Enter amount" required>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="submit">Cancel</button>
            <button type="submit" class="btn btn-primary" id="add">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="exampleModalTwo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content" id="content">
        <form id="editForm" method="POST">
          @csrf
          @method('PATCH')
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Fees</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Course</label>
              <div class="input-group">
                <select class="form-control" id="edit_course" name="course" required>
                  <option value="">Select Course</option>
                  <option value="Impulse">Impulse</option>
                  <option value="Momentum">Momentum</option>
                  <option value="Intensity">Intensity</option>
                  <option value="Thrust">Thrust</option>
                  <option value="Seedling 10th">Seedling 10th</option>
                  <option value="Anthesis">Anthesis</option>
                  <option value="Dynamic">Dynamic</option>
                  <option value="Radical 8th">Radical 8th</option>
                  <option value="Plumule 9th">Plumule 9th</option>
                  <option value="Pre Radical 7th">Pre Radical 7th</option>
                </select>
              </div>
            </div>
            <p style="color: orangered;">Fees Configuration</p>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST %</label>
              <div class="input-group">
                <input type="number" class="form-control" id="edit_gst_percentage" name="gst_percentage" step="0.01" required>
              </div>
            </div>
            <p style="color: orangered;">Fees After GST</p>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Class Room Course</label>
              <div class="input-group">
                <input type="number" class="form-control" id="edit_classroom_course" name="classroom_course" step="0.01" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="basic-url" class="form-label">Live online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" id="edit_live_online_course" name="live_online_course" step="0.01" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="basic-url" class="form-label">Recorded online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" id="edit_recorded_online_course" name="recorded_online_course" step="0.01" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="basic-url" class="form-label">Study Material only</label>
              <div class="input-group">
                <input type="number" class="form-control" id="edit_study_material_only" name="study_material_only" step="0.01" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="basic-url" class="form-label">Test series only</label>
              <div class="input-group">
                <input type="number" class="form-control" id="edit_test_series_only" name="test_series_only" step="0.01" required>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Modal -->
  <div class="modal fade" id="exampleModalThree" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content" id="content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">View Fees</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Course</label>
            <div class="input-group">
              <input type="text" class="form-control" id="view_course" readonly>
            </div>
          </div>
          <p style="color: orangered;">Fees Configuration</p>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST %</label>
            <div class="input-group">
              <input type="number" class="form-control" id="view_gst_percentage" readonly>
            </div>
          </div>
          <p style="color: orangered;">Fees Details</p>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Class Room Course</label>
            <input type="number" class="form-control" id="view_classroom_course" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Classroom GST</label>
            <input type="text" class="form-control" id="view_classroom_gst" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Classroom Total</label>
            <input type="text" class="form-control" id="view_classroom_total" readonly>
          </div>

          <div class="mb-3">
            <label for="basic-url" class="form-label">Live online class course</label>
            <input type="number" class="form-control" id="view_live_online_course" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Live Online GST</label>
            <input type="text" class="form-control" id="view_live_online_gst" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Live Online Total</label>
            <input type="text" class="form-control" id="view_live_online_total" readonly>
          </div>

          <div class="mb-3">
            <label for="basic-url" class="form-label">Recorded online class course</label>
            <input type="number" class="form-control" id="view_recorded_online_course" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Recorded Online GST</label>
            <input type="text" class="form-control" id="view_recorded_online_gst" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Recorded Online Total</label>
            <input type="text" class="form-control" id="view_recorded_online_total" readonly>
          </div>

          <div class="mb-3">
            <label for="basic-url" class="form-label">Study Material only</label>
            <input type="number" class="form-control" id="view_study_material_only" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Study Material GST</label>
            <input type="text" class="form-control" id="view_study_material_gst" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Study Material Total</label>
            <input type="text" class="form-control" id="view_study_material_total" readonly>
          </div>

          <div class="mb-3">
            <label for="basic-url" class="form-label">Test series only</label>
            <input type="number" class="form-control" id="view_test_series_only" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Test Series GST</label>
            <input type="text" class="form-control" id="view_test_series_gst" readonly>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Test Series Total</label>
            <input type="text" class="form-control" id="view_test_series_total" readonly>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<script src="{{asset('js/emp.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

<script>
// CSRF Token Setup
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Edit Fee Function - FIXED
function editFee(id) {
  fetch(`/fees-master/${id}`, {
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    console.log('Edit Data:', data); // Debug log
    
    document.getElementById('edit_course').value = data.course || '';
    document.getElementById('edit_gst_percentage').value = data.gst_percentage || '';
    document.getElementById('edit_classroom_course').value = data.classroom_course || '';
    document.getElementById('edit_live_online_course').value = data.live_online_course || '';
    document.getElementById('edit_recorded_online_course').value = data.recorded_online_course || '';
    document.getElementById('edit_study_material_only').value = data.study_material_only || '';
    document.getElementById('edit_test_series_only').value = data.test_series_only || '';
    
    document.getElementById('editForm').action = `/fees-master/${id}`;
    
    var editModal = new bootstrap.Modal(document.getElementById('exampleModalTwo'));
    editModal.show();
  })
  .catch(error => {
    console.error('Edit Error:', error);
    alert('Failed to load fee details for editing');
  });
}

// View Fee Function - FIXED
function viewFee(id) {
  fetch(`/fees-master/${id}`, {
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    console.log('View Data:', data); // Debug log
    
    // Basic fields
    document.getElementById('view_course').value = data.course || 'N/A';
    document.getElementById('view_gst_percentage').value = data.gst_percentage || 'N/A';
    
    // Classroom fields
    document.getElementById('view_classroom_course').value = data.classroom_course || 'N/A';
    document.getElementById('view_classroom_gst').value = data.classroom_gst || 'N/A';
    document.getElementById('view_classroom_total').value = data.classroom_total || 'N/A';
    
    // Live Online fields
    document.getElementById('view_live_online_course').value = data.live_online_course || 'N/A';
    document.getElementById('view_live_online_gst').value = data.live_online_gst || 'N/A';
    document.getElementById('view_live_online_total').value = data.live_online_total || 'N/A';
    
    // Recorded Online fields
    document.getElementById('view_recorded_online_course').value = data.recorded_online_course || 'N/A';
    document.getElementById('view_recorded_online_gst').value = data.recorded_online_gst || 'N/A';
    document.getElementById('view_recorded_online_total').value = data.recorded_online_total || 'N/A';
    
    // Study Material fields
    document.getElementById('view_study_material_only').value = data.study_material_only || 'N/A';
    document.getElementById('view_study_material_gst').value = data.study_material_gst || 'N/A';
    document.getElementById('view_study_material_total').value = data.study_material_total || 'N/A';
    
    // Test Series fields
    document.getElementById('view_test_series_only').value = data.test_series_only || 'N/A';
    document.getElementById('view_test_series_gst').value = data.test_series_gst || 'N/A';
    document.getElementById('view_test_series_total').value = data.test_series_total || 'N/A';
    
    var viewModal = new bootstrap.Modal(document.getElementById('exampleModalThree'));
    viewModal.show();
  })
  .catch(error => {
    console.error('View Error:', error);
    alert('Failed to load fee details for viewing');
  });
}

// Sidebar Toggle
document.getElementById('toggleBtn')?.addEventListener('click', function() {
  const sidebar = document.getElementById('sidebar');
  sidebar.style.display = sidebar.style.display === 'none' ? 'block' : 'none';
});

// PAGINATION CODE
document.addEventListener('DOMContentLoaded', () => {
  const tableBody = document.querySelector('#table tbody');
  const entriesInfo = document.getElementById('entriesInfo');
  const paginationContainer = document.getElementById('pagination');
  const perPageBtn = document.getElementById('number');
  const searchInput = document.getElementById('searchInput');

  let state = {
    page: 1,
    per_page: 10,
    search: ''
  };

  // Per page dropdown handler
  const dropdownItems = document.querySelectorAll('.dd .dropdown-menu .dropdown-item');
  dropdownItems.forEach(item => {
    item.addEventListener('click', (e) => {
      const value = parseInt(e.currentTarget.textContent.trim());
      if (isNaN(value)) return;
      state.per_page = value;
      perPageBtn.textContent = value;
      state.page = 1;
      loadData();
    });
  });

  // Search handler
  searchInput.addEventListener('input', debounce(() => {
    state.search = searchInput.value.trim();
    state.page = 1;
    loadData();
  }, 400));

  function debounce(fn, wait) {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => fn.apply(this, args), wait);
    };
  }

  // Load data function
  async function loadData() {
    try {
      const url = `/fees-master/data?per_page=${state.per_page}&search=${encodeURIComponent(state.search)}&page=${state.page}`;
      const response = await fetch(url, {
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      });

      if (!response.ok) throw new Error('Failed to load');
      const json = await response.json();
      
      console.log('Loaded Data:', json); // Debug log
      
      renderTable(json.data);
      renderPagination(json);
      updateEntriesInfo(json);
    } catch (error) {
      console.error('Load Data Error:', error);
      tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Failed to load data</td></tr>';
    }
  }

  // Render table
 function renderTable(fees) {
  if (!fees || fees.length === 0) {
    tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No Records</td></tr>';
    return;
  }

  tableBody.innerHTML = fees.map((fee, index) => {
    const serialNo = (state.page - 1) * state.per_page + index + 1;
    const statusClass = (fee.status === 'Active' || fee.status === 'active') ? 'text-success' : 'text-danger';
    const statusText = (fee.status === 'Active' || fee.status === 'active') ? 'Active' : 'Inactive';
    
    // MongoDB uses _id, not id
    const feeId = fee._id || fee.id;
    
    return `
      <tr>
        <td>${serialNo}</td>
        <td>${escapeHtml(fee.course || 'N/A')}</td>
        <td>${escapeHtml(fee.course_type || 'N/A')}</td>
        <td>${escapeHtml(fee.class_name || 'N/A')}</td>
        <td class="${statusClass}">${statusText}</td>
        <td>
          <div class="dropdown">
            <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown">
              <i class="fa-solid fa-ellipsis-vertical" style="color:#000"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a href="javascript:void(0)" class="dropdown-item" onclick="viewFee('${feeId}')">View Fees</a></li>
              <li><a href="javascript:void(0)" class="dropdown-item" onclick="editFee('${feeId}')">Edit</a></li>
              <li>
                <form action="/fees-master/${feeId}/toggle" method="POST" style="margin:0" onsubmit="return confirm('Are you sure?')">
                  <input type="hidden" name="_token" value="${csrfToken}">
                  <input type="hidden" name="_method" value="PATCH">
                  <button class="dropdown-item" type="submit">
                    ${statusText === 'Active' ? 'Deactivate' : 'Activate'}
                  </button>
                </form>
              </li>
            </ul>
          </div>
        </td>
      </tr>
    `;
  }).join('');
}

  // Escape HTML to prevent XSS
  function escapeHtml(text) {
    if (!text) return 'N/A';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // Render pagination
  function renderPagination(json) {
    paginationContainer.innerHTML = '';

    // Previous button
    const prevLi = document.createElement('li');
    prevLi.className = 'page-item' + (json.current_page <= 1 ? ' disabled' : '');
    prevLi.innerHTML = '<a class="page-link" href="javascript:void(0)">Previous</a>';
    paginationContainer.appendChild(prevLi);
    
    if (json.current_page > 1) {
      prevLi.addEventListener('click', (e) => {
        e.preventDefault();
        state.page = json.current_page - 1;
        loadData();
      });
    }

    // Page numbers
    const current = json.current_page;
    const last = json.last_page;
    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    for (let p = start; p <= end; p++) {
      const li = document.createElement('li');
      li.className = 'page-item' + (p === current ? ' active' : '');
      li.innerHTML = `<a class="page-link" href="javascript:void(0)" style="${p === current ? 'background-color: rgb(224, 83, 1); color: white;' : ''}">${p}</a>`;
      
      li.addEventListener('click', (e) => {
        e.preventDefault();
        state.page = p;
        loadData();
      });
      
      paginationContainer.appendChild(li);
    }

    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = 'page-item' + (json.current_page >= last ? ' disabled' : '');
    nextLi.innerHTML = '<a class="page-link" href="javascript:void(0)">Next</a>';
    paginationContainer.appendChild(nextLi);
    
    if (json.current_page < last) {
      nextLi.addEventListener('click', (e) => {
        e.preventDefault();
        state.page = json.current_page + 1;
        loadData();
      });
    }
  }

  // Update entries info
  function updateEntriesInfo(json) {
    const from = json.data.length ? ((json.current_page - 1) * json.per_page + 1) : 0;
    const to = json.data.length ? ((json.current_page - 1) * json.per_page + json.data.length) : 0;
    entriesInfo.textContent = `Showing ${from} to ${to} of ${json.total} Entries`;
  }

  // Initial load
  loadData();
});
</script>
 
</body>
</html>