<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fees Master</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
 <link rel="stylesheet" href="{{ asset('css/FeesMaster.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>
  <div class="header">
    <div class="logo">
   <img src="{{ asset('images/logo-big.png') }}" class="img">
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
          <li><a class="dropdown-item"  href="/profile/profile.html"> <i class="fa-solid fa-user"></i>Profile</a></li>
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
                <li><a class="item" href="{{ route('master.courses.index') }}"><i class="fa-solid fa-book-open"
                      id="side-icon"></i> Courses</a></li>
                <li><a class="item" href="/master/batches/batches.html"><i
                      class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i>
                    Batches</a></li>
                <li><a class="item" href="/master/scholarship/scholar.html"><i class="fa-solid fa-graduation-cap"
                      id="side-icon"></i> Scholarship</a>
                </li>
                <li><a class="item" href="{{ route('fees.index') }}">
<i class="fa-solid fa-credit-card"
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
                <li>><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info"
                      id="side-icon"></i> Inquiry
                    Management</a></li>
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
      <div class="whole">
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
                <li><a class="dropdown-item"> 50</a></li>
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
              <th scope="col" id="one">Start Date</th>
              <th scope="col" id="one">Username</th>
              <th scope="col" id="one">Shift</th>
              <th scope="col" id="one">Status</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
      <tbody>
@forelse($fees as $index => $fee)
<tr>
  <td>{{ $fees->firstItem() + $index }}</td>
  <td>{{ $fee->course }}</td>
  <td>{{ $fee->created_at?->format('Y-m-d') }}</td>
  <td>{{ auth()->user()->name ?? '' }}</td>
  <td>Morning</td>
  <td class="{{ $fee->status === 'Active' ? 'text-success' : 'text-danger' }}">{{ $fee->status }}</td>
  <td>
    <div class="dropdown">
      <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown">
        <i class="fa-solid fa-ellipsis-vertical" style="color:#000"></i>
      </button>
      <ul class="dropdown-menu">
        <li>
          <a href="#" class="dropdown-item btn-view" data-id="{{ $fee->id }}">View</a>
        </li>
        <li>
          <a href="#" class="dropdown-item btn-edit"
             data-bs-toggle="modal" data-bs-target="#editModal"
             data-id="{{ $fee->id }}"
             data-course="{{ $fee->course }}"
             data-gst="{{ $fee->gst_percent }}"
             data-classroom="{{ $fee->classroom_fee }}"
             data-live="{{ $fee->live_fee }}"
             data-recorded="{{ $fee->recorded_fee }}"
             data-study="{{ $fee->study_fee }}"
             data-test="{{ $fee->test_fee }}"
             data-status="{{ $fee->status }}">Edit</a>
        </li>
        <li>
          <form action="{{ route('fees.toggle', $fee) }}" method="POST">
            @csrf @method('PATCH')
            <button class="dropdown-item" type="submit">
              {{ $fee->status === 'Active' ? 'Deactivate' : 'Activate' }}
            </button>
          </form>
        </li>
      </ul>
    </div>
  </td>
</tr>
@empty
<tr><td colspan="7" class="text-center">No Records</td></tr>
@endforelse
</tbody>

</table>
@if(session('status'))
  <div class="alert alert-success mt-2">{{ session('status') }}</div>
@endif
@if ($errors->any())
  <div class="alert alert-danger mt-2">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="mt-3">
  {{ $fees->withQueryString()->links() }}
</div>

</div>
      <div class="footer">
        <div class="left-footer">
          <p>Showing 1 to 1 of 1 Enteries</p>
        </div>
      </div>
    </div>
  </div>
  </div>

 {{-- REPLACE your entire #exampleModal with this --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"> <!-- width comes from CSS above -->
    <div class="modal-content" id="content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create Fees</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="{{ route('fees.store') }}">
        @csrf
        <div class="modal-body">
          <!-- Row 1: Course full width -->
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Course</label>
              <select name="course" class="form-select" required>
                <option value="" selected disabled>Select Course</option>
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

          <!-- Section: Fees Configuration -->
          <div class="form-section">Fees Configuration</div>
          <div class="row g-3 align-items-end">
            <!-- Put GST on left, Status on right so header area looks balanced -->
            <div class="col-12 col-md-4">
              <label class="form-label">GST %</label>
              <input type="number" step="0.01" min="0" max="100" name="gst_percent" class="form-control" value="18" required>
            </div>
            <div class="col-12 col-md-4 offset-md-4">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
          </div>

          <!-- Section: Fees (before GST) -->
          <div class="form-section mt-2">Fees (before GST)</div>
          <!-- 2-column grid on md+, 1-column on mobile -->
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Class Room Course</label>
              <input type="number" step="0.01" min="0" name="classroom_fee" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Live online class course</label>
              <input type="number" step="0.01" min="0" name="live_fee" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Recorded online class course</label>
              <input type="number" step="0.01" min="0" name="recorded_fee" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Study Material only</label>
              <input type="number" step="0.01" min="0" name="study_fee" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Test series only</label>
              <input type="number" step="0.01" min="0" name="test_fee" class="form-control">
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

<div class="modal fade fees-modal" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" id="content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Edit Fees</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="editForm" method="POST">
        @csrf
        @method('PATCH')

        <div class="modal-body fees-form">
          <!-- Course -->
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Course</label>
              <input type="text" name="course" id="e_course" class="form-control" required>
            </div>
          </div>

          <!-- Fees Configuration -->
          <div class="form-section">Fees Configuration</div>
          <div class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
              <label class="form-label">GST %</label>
              <input type="number" step="0.01" min="0" max="100" name="gst_percent" id="e_gst" class="form-control" required>
            </div>
            <div class="col-12 col-md-4 offset-md-4">
              <label class="form-label">Status</label>
              <select name="status" id="e_status" class="form-select" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
          </div>

          <!-- Fees (before GST) -->
          <div class="form-section mt-2">Fees (before GST)</div>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Class Room Course</label>
              <input type="number" step="0.01" min="0" name="classroom_fee" id="e_classroom" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Live online class course</label>
              <input type="number" step="0.01" min="0" name="live_fee" id="e_live" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Recorded online class course</label>
              <input type="number" step="0.01" min="0" name="recorded_fee" id="e_recorded" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Study Material only</label>
              <input type="number" step="0.01" min="0" name="study_fee" id="e_study" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Test series only</label>
              <input type="number" step="0.01" min="0" name="test_fee" id="e_test" class="form-control">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- 
  <div class="modal fade" id="exampleModalOne" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" id="content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Create Fees</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Course</label>
            <div class="input-group">

              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="scroll" type="button" data-bs-toggle="dropdown"
                  aria-expanded="false">

                </button>
                <ul class="dropdown-menu" required>
                  <li><a class="dropdown-item" href="#">Select Course</a></li>
                  <li><a class="dropdown-item" href="#">Impulse</a></li>
                  <li><a class="dropdown-item" href="#">Momentum</a></li>
                  <li><a class="dropdown-item" href="#">Intensity</a></li>
                  <li><a class="dropdown-item" href="#">Thrust</a></li>
                  <li><a class="dropdown-item" href="#">Seedling 10th</a></li>
                  <li><a class="dropdown-item" href="#">Anthesis</a></li>
                  <li><a class="dropdown-item" href="#">Dynamic</a></li>
                  <li><a class="dropdown-item" href="#">Radical 8th</a></li>
                  <li><a class="dropdown-item" href="#">Plumule 9th</a></li>
                  <li><a class="dropdown-item" href="#">Pre Radical 7th</a></li>
                </ul>
              </div>
            </div>
          </div>
          <p style="color: orangered;">Fees Configuration</p>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST %</label>
            <div class="input-group">
              <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
            </div>
          </div>
          <p style="color: orangered;">Fees After GST</p>
          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Class Room Course</label>
              <div class="input-group" id="placeholder">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group" id="placeholder">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group" id="placeholder">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Live online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Recorded online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Study Material only</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Test series only</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" ">
              </div>
            </div>
          </div>

          <div class="written">
            <div class="hori-text">
              <p>Installment Types</p>
              <p>First Installment</p>
              <p>Second Installment</p>
              <p>Third Installment</p>
            </div>
            <div class="ver-text">
              <p>Class room course</p>
              <p>Live online class course</p>
              <p>Recorded online class course</p>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="submit">Cancel</button>
          <button type="button" class="btn btn-primary" id="add"> Submit </button>
        </div>
      </div>
    </div>
  </div>
   <div class="modal fade" id="exampleModalTwo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" id="content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Create Fees</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Course</label>
            <div class="input-group">

              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="scroll" type="button" data-bs-toggle="dropdown"
                  aria-expanded="false">

                </button>Anthesis
                <ul class="dropdown-menu" required>
                  <li><a class="dropdown-item" href="#">Select Course</a></li>
                  <li><a class="dropdown-item" href="#">Impulse</a></li>
                  <li><a class="dropdown-item" href="#">Momentum</a></li>
                  <li><a class="dropdown-item" href="#">Intensity</a></li>
                  <li><a class="dropdown-item" href="#">Thrust</a></li>
                  <li><a class="dropdown-item" href="#">Seedling 10th</a></li>
                  <li><a class="dropdown-item" href="#">Anthesis</a></li>
                  <li><a class="dropdown-item" href="#">Dynamic</a></li>
                  <li><a class="dropdown-item" href="#">Radical 8th</a></li>
                  <li><a class="dropdown-item" href="#">Plumule 9th</a></li>
                  <li><a class="dropdown-item" href="#">Pre Radical 7th</a></li>
                </ul>
              </div>
            </div>
          </div>
          <p style="color: orangered;">Fees Configuration</p>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST %</label>
            <div class="input-group">
              <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 18%">
            </div>
          </div>
          <p style="color: orangered;">Fees After GST</p>
          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Class Room Course</label>
              <div class="input-group" id="placeholder">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" 45000">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group" id="placeholder">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="18% ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group" id="placeholder">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 53100">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Live online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" 40000">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="18% ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 47200">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Recorded online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder="30000 ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 18%">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="35400 ">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Study Material only</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder="15000 ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="18% ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 17700">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Test series only</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" 10000">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 18%">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="11800 ">
              </div>
            </div>
          </div>

          <div class="written">
            <div class="hori-text">
              <p>Installment Types</p>
              <p>First Installment</p>
              <p>Second Installment</p>
              <p>Third Installment</p>
            </div>
            <div class="ver-text">
              <p>Class room course</p>
              <p>Live online class course</p>
              <p>Recorded online class course</p>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="submit">Cancel</button>
          <button type="button" class="btn btn-primary" id="add"> Submit </button>
        </div>
      </div>
    </div>
  </div>
   <div class="modal fade" id="exampleModalThree" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" id="content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Create Fees</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Course</label>
            <div class="input-group">

              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="scroll" type="button" data-bs-toggle="dropdown"
                  aria-expanded="false">

                </button>Anthesis
                <ul class="dropdown-menu" required>
                  <li><a class="dropdown-item" href="#">Select Course</a></li>
                  <li><a class="dropdown-item" href="#">Impulse</a></li>
                  <li><a class="dropdown-item" href="#">Momentum</a></li>
                  <li><a class="dropdown-item" href="#">Intensity</a></li>
                  <li><a class="dropdown-item" href="#">Thrust</a></li>
                  <li><a class="dropdown-item" href="#">Seedling 10th</a></li>
                  <li><a class="dropdown-item" href="#">Anthesis</a></li>
                  <li><a class="dropdown-item" href="#">Dynamic</a></li>
                  <li><a class="dropdown-item" href="#">Radical 8th</a></li>
                  <li><a class="dropdown-item" href="#">Plumule 9th</a></li>
                  <li><a class="dropdown-item" href="#">Pre Radical 7th</a></li>
                </ul>
              </div>
            </div>
          </div>
          <p style="color: orangered;">Fees Configuration</p>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST %</label>
            <div class="input-group">
              <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 18%">
            </div>
          </div>
          <p style="color: orangered;">Fees After GST</p>
          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Class Room Course</label>
              <div class="input-group" id="placeholder">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" 45000">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group" id="placeholder">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="18% ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group" id="placeholder">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 53100">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Live online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" 40000">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="18% ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 47200">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Recorded online class course</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder="30000 ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 18%">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="35400 ">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Study Material only</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder="15000 ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="18% ">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 17700">
              </div>
            </div>
          </div>

          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Test series only</label>
              <div class="input-group">
                <input type="number" class="form-control" id="basic-url" aria-describedby="basic-addon3"
                  placeholder=" 10000">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder=" 18%">
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group">
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="11800 ">
              </div>
            </div>
          </div>

          <div class="written">
            <div class="hori-text">
              <p>Installment Types</p>
              <p>First Installment</p>
              <p>Second Installment</p>
              <p>Third Installment</p>
            </div>
            <div class="ver-text">
              <p>Class room course</p>
              <p>Live online class course</p>
              <p>Recorded online class course</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="submit">Cancel</button>
          <button type="button" class="btn btn-primary" id="add"> Submit </button>
        </div>
      </div>
    </div>
  </div> -->
</body>
  <script src="{{ asset('js/courses.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // === Sidebar toggle ===
  const toggleBtn = document.getElementById('toggleBtn');
  const sidebar  = document.getElementById('sidebar');
  const right    = document.getElementById('right');
  const text     = document.getElementById('text');
  let isCollapsed = false;

  if (sidebar) {
    sidebar.style.transition = 'width 0.5s ease-in-out';
    sidebar.style.overflow = 'hidden';
    sidebar.style.width = '300px';
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', function () {
      if (!sidebar || !right || !text) return;
      if (isCollapsed) {
        sidebar.style.width = '26%';
        right.style.width = '100%';
        text.style.visibility = 'visible';
      } else {
        sidebar.style.width = '41px';
        right.style.width = '100%';
        text.style.visibility = 'hidden';
      }
      isCollapsed = !isCollapsed;
    });
  }

  // === Allow navigation for menu links inside accordion ===
  // Stop the click from bubbling to the accordion headers (which would re-toggle the collapse)
  document.querySelectorAll('.accordion .accordion-collapse a').forEach(a => {
    a.addEventListener('click', (e) => {
      // Only stop propagation; do NOT prevent default so navigation still happens
      e.stopPropagation();
      // Optional: if some global script ever blocks links, force navigation explicitly:
      // if (a.href && a.getAttribute('href') !== '#') window.location.href = a.href;
    });
  });

  // === EDIT: prefill modal and set form action ===
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const form = document.getElementById('editForm');
      if (!form) return;

      form.action = `/fees/${id}`;
      document.getElementById('e_course').value    = btn.dataset.course || '';
      document.getElementById('e_gst').value       = btn.dataset.gst || '';
      document.getElementById('e_classroom').value = btn.dataset.classroom || '';
      document.getElementById('e_live').value      = btn.dataset.live || '';
      document.getElementById('e_recorded').value  = btn.dataset.recorded || '';
      document.getElementById('e_study').value     = btn.dataset.study || '';
      document.getElementById('e_test').value      = btn.dataset.test || '';
      document.getElementById('e_status').value    = btn.dataset.status || 'Active';
    });
  });

  // === VIEW: quick JSON alert ===
  document.querySelectorAll('.btn-view').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault(); // this is a button-like link; we fetch instead of navigating
      try {
        const res = await fetch(`/fees/${btn.dataset.id}`);
        const data = await res.json();
        alert(`Course: ${data.course}\nGST: ${data.gst_percent}\nStatus: ${data.status}`);
      } catch {
        alert('Unable to fetch record.');
      }
    });
  });
});
</script>

</html>
