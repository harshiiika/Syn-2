@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Fees</h4>
    <a href="{{ route('fees.index') }}" class="btn btn-secondary">Back</a>
  </div>

  <!-- Top bar -->
  <div class="top">
    <div class="header">
         <img src="{{ asset('images/logo.png.jpg') }}" class="logo" alt="Logo">
      <i class="fa-solid fa-bars" id="toggleBtn"></i>
    </div>

    <div class="session">
      <label>Session:</label>
      <select class="select">
        <option>2026</option>
        <option>2024-25</option>
      </select>

      <i class="fa-solid fa-bell" style="color: rgb(233, 96, 47); font-size: 22px"></i>

      <div class="dropdown">
        <button
          class="btn btn-secondary dropdown-toggle"
          id="toggle-btn"
          type="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="fa-solid fa-user" style="color: rgb(233, 96, 47); font-size: 22px"></i>
        </button>
        <ul class="dropdown-menu">
          <li>
            <a href="/pfp/pfp.html" class="dropdown-item">
              <i class="fa-solid fa-user" style="color: rgb(233, 96, 47); font-size: 15px"></i
              >Profile</a
            >
          </li>
          <li>
            <a href="/login page/login.html" class="dropdown-item">
              <i
                class="fa-solid fa-arrow-right-from-bracket"
                style="color: rgb(233, 96, 47); font-size: 15px"
              ></i
              >Log In</a
            >
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Main layout -->
  <div class="main-container">
     
    <div class="left" id="sidebar">
      <div class="admin" id="admin">
        <h2>Admin</h2>
        <h4>synthesisbikaner@gmail.com</h4>
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
                <li>><a class="item" href="{{ route('batches') }}"><i class="fa-solid fa-user-group"
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
                <li><a class="item" href="/master/scholarship/scholar.html"><i class="fa-solid fa-graduation-cap"
                      id="side-icon"></i> Scholarship</a>
                </li>
                <li><a class="item" href="{{ route('fees.index') }}">
<i class="fa-solid fa-credit-card"
                      id="side-icon"></i> Fees Master</a></li>
                <li><a class="item" href="/master/other fees/other.html"><i class="fa-solid fa-wallet"
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
                <li><a class="item" href="{{ route('master.student.pending') }}">
  <i class="fa-solid fa-user-check"
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
      </div> <!-- /accordion -->
    </div> <!-- /left -->

    <!-- RIGHT: edit form -->
   <div class="right" id="right">
  {{-- Top line: title on left, Back on right --}}
  <div class="page-header">
    <h1 class="page-title m-0">Edit Inquiry</h1>
    <a href="{{ route('inquiries.index') }}" class="btn-back-link">
      <i class="fa-solid fa-angle-left me-1"></i> Back
    </a>
  </div>

      <div class="card mt-3">
        <div class="card-header border-0">
          <h2 class="section-title m-0">Edit Inquiry</h2>
        </div>

        <form method="POST" action="{{ route('fees.update', $fee->id) }}">
          @csrf
          @method('PUT')

          <div class="modal-body fees-form">
            <!-- Course -->
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Course</label>
                <select name="course" class="form-select" required>
                  <option value="" disabled>Select Course</option>
                  @foreach(['Impulse','Momentum','Intensity','Thrust','Seedling 10th','Anthesis','Dynamic','Radical 8th','Plumule 9th','Pre Radical 7th'] as $course)
                    <option value="{{ $course }}" {{ old('course', $fee->course)===$course ? 'selected' : '' }}>
                      {{ $course }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Fees Configuration -->
            <div class="form-section">Fees Configuration</div>
            <div class="row g-3 align-items-end">
              <div class="col-12 col-md-4">
                <label class="form-label">GST %</label>
                <input type="number" step="0.01" min="0" max="100"
                       name="gst_percent" class="form-control"
                       value="{{ old('gst_percent', $fee->gst_percent) }}" required>
              </div>
              <div class="col-12 col-md-4 offset-md-4">
                <label class="form-label">Status</label>
                @php $sv = old('status', $fee->status); @endphp
                <select name="status" class="form-select" required>
                  <option value="Active"   {{ $sv==='Active' ? 'selected' : '' }}>Active</option>
                  <option value="Inactive" {{ $sv==='Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
              </div>
            </div>

            <!-- Fees (before GST) -->
            <div class="form-section mt-2">Fees (before GST)</div>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Class Room Course</label>
                <input type="number" step="0.01" min="0"
                       name="classroom_fee" class="form-control"
                       value="{{ old('classroom_fee', $fee->classroom_fee) }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Live online class course</label>
                <input type="number" step="0.01" min="0"
                       name="live_fee" class="form-control"
                       value="{{ old('live_fee', $fee->live_fee) }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Recorded online class course</label>
                <input type="number" step="0.01" min="0"
                       name="recorded_fee" class="form-control"
                       value="{{ old('recorded_fee', $fee->recorded_fee) }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Study Material only</label>
                <input type="number" step="0.01" min="0"
                       name="study_fee" class="form-control"
                       value="{{ old('study_fee', $fee->study_fee) }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Test series only</label>
                <input type="number" step="0.01" min="0"
                       name="test_fee" class="form-control"
                       value="{{ old('test_fee', $fee->test_fee) }}">
              </div>
            </div>
          </div>

          <div class="modal-footer bg-light">
            <a href="{{ route('fees.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
