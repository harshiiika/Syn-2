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
          <li><a class="dropdown-item" href="{{route('profile.index') }}""> <i class="fa-solid fa-user"></i>Profile</a></li>
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
            {{ request('per_page', 10) }}
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
              <th scope="col">Serial No.</th>
              <th scope="col">Course Name</th>
              <th scope="col">Course Type</th>
              <th scope="col">Class Name</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($fees as $index => $fee)
              <tr>
                <td>{{ $fees->firstItem() + $index }}</td>
                <td>{{ $fee->course }}</td>
                <td>{{ $fee->course_type }}</td>
                <td>{{ $fee->class_name }}</td>
                <td class="{{ $fee->status === 'Active' ? 'text-success' : 'text-danger' }}">{{ $fee->status }}</td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown">
                      <i class="fa-solid fa-ellipsis-vertical" style="color:#000"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <a href="#" class="dropdown-item btn-view" data-id="{{ $fee->id }}">
                          <i class="fa-solid fa-eye"></i> View
                        </a>
                      </li>
                      <li>
                        <a href="#" class="dropdown-item btn-edit" data-id="{{ $fee->id }}">
                          <i class="fa-solid fa-edit"></i> Edit
                        </a>
                      </li>
                      <li>
                        <form action="{{ route('fees.toggle', $fee->id) }}" method="POST" style="display:inline;">
                          @csrf
                          @method('PATCH')
                          <button class="dropdown-item" type="submit">
                            <i class="fa-solid fa-{{ $fee->status === 'Active' ? 'ban' : 'check' }}"></i>
                            {{ $fee->status === 'Active' ? 'Deactivate' : 'Activate' }}
                          </button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">No fees records found</td>
              </tr>
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

  <div class="modal fade fees-modal" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" style="color: #ff6600;">Create Fees</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="{{ route('fees.store') }}" id="createForm">
          @csrf
          <div class="modal-body fees-form">
            <!-- Course Selection -->
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Course<span class="text-danger">*</span></label>
                <select name="course" id="course_select" class="form-select" required>
                  <option value="" selected disabled>Select Course</option>
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
            </div>

            <!-- Auto-filled fields -->
            <div class="row g-3 mt-2">
              <div class="col-12 col-md-6">
                <label class="form-label">Course Type</label>
                <input type="text" id="course_type" class="form-control readonly-field" readonly>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Class Name</label>
                <input type="text" id="class_name" class="form-control readonly-field" readonly>
              </div>
            </div>

            <div class="form-section">Fee Configuration</div>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">GST %<span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" max="100" name="gst_percent" id="gst_percent"
                  class="form-control" value="18" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Status<span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                  <option value="Active" selected>Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>
            </div>

            <div class="form-section">Fees after GST</div>

            <!-- Class Room Course -->
            <div class="row g-3 align-items-end">
              <div class="col-12 col-md-4">
                <label class="form-label">Class Room Course</label>
                <input type="number" step="0.01" min="0" name="classroom_fee" id="classroom_fee"
                  class="form-control fee-input" placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="classroom_gst" class="form-control readonly-field" readonly placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="classroom_total" class="form-control readonly-field" readonly placeholder="0">
              </div>
            </div>

            <!-- Live Online Class Course -->
            <div class="row g-3 align-items-end mt-2">
              <div class="col-12 col-md-4">
                <label class="form-label">Live Online Class Course</label>
                <input type="number" step="0.01" min="0" name="live_fee" id="live_fee" class="form-control fee-input"
                  placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="live_gst" class="form-control readonly-field" readonly placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="live_total" class="form-control readonly-field" readonly placeholder="0">
              </div>
            </div>

            <!-- Recorded Online Class Course -->
            <div class="row g-3 align-items-end mt-2">
              <div class="col-12 col-md-4">
                <label class="form-label">Recorded Online Class Course</label>
                <input type="number" step="0.01" min="0" name="recorded_fee" id="recorded_fee"
                  class="form-control fee-input" placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="recorded_gst" class="form-control readonly-field" readonly placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="recorded_total" class="form-control readonly-field" readonly placeholder="0">
              </div>
            </div>

            <!-- Study Material Only -->
            <div class="row g-3 align-items-end mt-2">
              <div class="col-12 col-md-4">
                <label class="form-label">Study Material Only</label>
                <input type="number" step="0.01" min="0" name="study_fee" id="study_fee" class="form-control fee-input"
                  placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="study_gst" class="form-control readonly-field" readonly placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="study_total" class="form-control readonly-field" readonly placeholder="0">
              </div>
            </div>

            <!-- Test Series Only -->
            <div class="row g-3 align-items-end mt-2">
              <div class="col-12 col-md-4">
                <label class="form-label">Test Series Only</label>
                <input type="number" step="0.01" min="0" name="test_fee" id="test_fee" class="form-control fee-input"
                  placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="test_gst" class="form-control readonly-field" readonly placeholder="0">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="test_total" class="form-control readonly-field" readonly placeholder="0">
              </div>
            </div>

            <!-- Installment Types Table -->
            <div class="form-section">Installment Types</div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Installment Types</th>
                    <th>First Installment</th>
                    <th>Second Installment</th>
                    <th>Third Installment</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong>Class room course</strong></td>
                    <td><input type="text" id="classroom_inst_1" class="form-control readonly-field" readonly
                        placeholder="0"></td>
                    <td><input type="text" id="classroom_inst_2" class="form-control readonly-field" readonly
                        placeholder="0"></td>
                    <td><input type="text" id="classroom_inst_3" class="form-control readonly-field" readonly
                        placeholder="0"></td>
                  </tr>
                  <tr>
                    <td><strong>Live online class course</strong></td>
                    <td><input type="text" id="live_inst_1" class="form-control readonly-field" readonly
                        placeholder="0"></td>
                    <td><input type="text" id="live_inst_2" class="form-control readonly-field" readonly
                        placeholder="0"></td>
                    <td><input type="text" id="live_inst_3" class="form-control readonly-field" readonly
                        placeholder="0"></td>
                  </tr>
                  <tr>
                    <td><strong>Recorded online class course</strong></td>
                    <td><input type="text" id="recorded_inst_1" class="form-control readonly-field" readonly
                        placeholder="0"></td>
                    <td><input type="text" id="recorded_inst_2" class="form-control readonly-field" readonly
                        placeholder="0"></td>
                    <td><input type="text" id="recorded_inst_3" class="form-control readonly-field" readonly
                        placeholder="0"></td>
                  </tr>
                </tbody>
              </table>
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


  <!-- Edit Modal -->
  <div class="modal fade fees-modal" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" style="color: #ff6600;">Edit Fees</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" id="editForm">
          @csrf
          @method('PATCH')
          <div class="modal-body fees-form">
            <!-- Course Selection -->
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Course<span class="text-danger">*</span></label>
                <select name="course" id="edit_course_select" class="form-select" required>
                  <option value="">Select Course</option>
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
            </div>

            <!-- Auto-filled fields -->
            <div class="row g-3 mt-2">
              <div class="col-12 col-md-6">
                <label class="form-label">Course Type</label>
                <input type="text" id="edit_course_type" class="form-control readonly-field" readonly>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Class Name</label>
                <input type="text" id="edit_class_name" class="form-control readonly-field" readonly>
              </div>
            </div>

            <div class="form-section">Fee Configuration</div>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">GST %<span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" max="100" name="gst_percent" id="edit_gst_percent"
                  class="form-control" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Status<span class="text-danger">*</span></label>
                <select name="status" id="edit_status" class="form-select" required>
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>
            </div>

            <div class="form-section">Fees after GST</div>

            <!-- Class Room Course -->
            <div class="row g-3 align-items-end">
              <div class="col-12 col-md-4">
                <label class="form-label">Class Room Course</label>
                <input type="number" step="0.01" min="0" name="classroom_fee" id="edit_classroom_fee"
                  class="form-control edit-fee-input">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="edit_classroom_gst" class="form-control readonly-field" readonly>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="edit_classroom_total" class="form-control readonly-field" readonly>
              </div>
            </div>

            <!-- Live Online Class Course -->
            <div class="row g-3 align-items-end mt-2">
              <div class="col-12 col-md-4">
                <label class="form-label">Live Online Class Course</label>
                <input type="number" step="0.01" min="0" name="live_fee" id="edit_live_fee"
                  class="form-control edit-fee-input">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="edit_live_gst" class="form-control readonly-field" readonly>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="edit_live_total" class="form-control readonly-field" readonly>
              </div>
            </div>

            <!-- Recorded Online Class Course -->
            <div class="row g-3 align-items-end mt-2">
              <div class="col-12 col-md-4">
                <label class="form-label">Recorded Online Class Course</label>
                <input type="number" step="0.01" min="0" name="recorded_fee" id="edit_recorded_fee"
                  class="form-control edit-fee-input">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="edit_recorded_gst" class="form-control readonly-field" readonly>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="edit_recorded_total" class="form-control readonly-field" readonly>
              </div>
            </div>

            <!-- Study Material Only -->
            <div class="row g-3 align-items-end mt-2">
              <div class="col-12 col-md-4">
                <label class="form-label">Study Material Only</label>
                <input type="number" step="0.01" min="0" name="study_fee" id="edit_study_fee"
                  class="form-control edit-fee-input">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="edit_study_gst" class="form-control readonly-field" readonly>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="edit_study_total" class="form-control readonly-field" readonly>
              </div>
            </div>

            <!-- Test Series Only -->
            <div class="row g-3 align-items-end mt-2">
              <div class="col-12 col-md-4">
                <label class="form-label">Test Series Only</label>
                <input type="number" step="0.01" min="0" name="test_fee" id="edit_test_fee"
                  class="form-control edit-fee-input">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Gst</label>
                <input type="text" id="edit_test_gst" class="form-control readonly-field" readonly>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Total</label>
                <input type="text" id="edit_test_total" class="form-control readonly-field" readonly>
              </div>
            </div>

            <!-- Installment Types Table -->
            <div class="form-section">Installment Types</div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Installment Types</th>
                    <th>First Installment</th>
                    <th>Second Installment</th>
                    <th>Third Installment</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong>Class room course</strong></td>
                    <td><input type="text" id="edit_classroom_inst_1" class="form-control readonly-field" readonly></td>
                    <td><input type="text" id="edit_classroom_inst_2" class="form-control readonly-field" readonly></td>
                    <td><input type="text" id="edit_classroom_inst_3" class="form-control readonly-field" readonly></td>
                  </tr>
                  <tr>
                    <td><strong>Live online class course</strong></td>
                    <td><input type="text" id="edit_live_inst_1" class="form-control readonly-field" readonly></td>
                    <td><input type="text" id="edit_live_inst_2" class="form-control readonly-field" readonly></td>
                    <td><input type="text" id="edit_live_inst_3" class="form-control readonly-field" readonly></td>
                  </tr>
                  <tr>
                    <td><strong>Recorded online class course</strong></td>
                    <td><input type="text" id="edit_recorded_inst_1" class="form-control readonly-field" readonly></td>
                    <td><input type="text" id="edit_recorded_inst_2" class="form-control readonly-field" readonly></td>
                    <td><input type="text" id="edit_recorded_inst_3" class="form-control readonly-field" readonly></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>



  <!-- View Modal -->
  <div class="modal fade view-modal" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" style="color: #ff6600;">View Fees</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewModalBody">
          <!-- Content loaded via JavaScript -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
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
    // Sidebar toggle
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('sidebar');
    const right = document.getElementById('right');
    const text = document.getElementById('text');
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

    // Edit button handler
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const form = document.getElementById('editForm');
        if (!form) return;

        form.action = `/fees-master/${btn.dataset.id}`;
        document.getElementById('e_course').value = btn.dataset.course || '';
        document.getElementById('e_gst').value = btn.dataset.gst || '';
        document.getElementById('e_classroom').value = btn.dataset.classroom || '';
        document.getElementById('e_live').value = btn.dataset.live || '';
        document.getElementById('e_recorded').value = btn.dataset.recorded || '';
        document.getElementById('e_study').value = btn.dataset.study || '';
        document.getElementById('e_test').value = btn.dataset.test || '';
        document.getElementById('e_status').value = btn.dataset.status || 'Active';
      });
    });

    // View button handler
    document.querySelectorAll('.btn-view').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.preventDefault();
        const feeId = btn.dataset.id;

        try {
          const response = await fetch(`/fees-master/${feeId}`);
          const data = await response.json();

          if (response.ok) {
            displayViewModal(data);
          } else {
            alert('Unable to fetch fee details');
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Unable to fetch fee details');
        }
      });
    });

    function displayViewModal(data) {
      const modalBody = document.getElementById('viewModalBody');
      let html = `
          <div class="info-row">
            <div class="info-item">
              <div class="info-label">Course</div>
              <div class="info-value">${data.course}</div>
            </div>
          </div>
          
          <div class="info-row">
            <div class="info-item">
              <div class="info-label">Course Type</div>
              <div class="info-value">${data.course_type}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Class Name</div>
              <div class="info-value">${data.class_name}</div>
            </div>
          </div>

          <div class="form-section">Fee Configuration</div>
          <div class="info-row">
            <div class="info-item">
              <div class="info-label">GST %</div>
              <div class="info-value">${data.gst_percent}%</div>
            </div>
          </div>

          <div class="form-section">Fees after GST</div>
        `;

      // Classroom Course
      if (data.fees.classroom) {
        html += `
            <div class="fee-section">
              <h6>Class Room Course</h6>
              <div class="fee-grid">
                <div>
                  <div class="info-label">Base Fee</div>
                  <div class="info-value">₹${formatNumber(data.fees.classroom.base)}</div>
                </div>
                <div>
                  <div class="info-label">GST</div>
                  <div class="info-value">₹${formatNumber(data.fees.classroom.gst)}</div>
                </div>
                <div>
                  <div class="info-label">Total</div>
                  <div class="info-value">₹${formatNumber(data.fees.classroom.total)}</div>
                </div>
              </div>
            </div>
          `;
      }

      // Live Online Course
      if (data.fees.live) {
        html += `
            <div class="fee-section">
              <h6>Live Online Class Course</h6>
              <div class="fee-grid">
                <div>
                  <div class="info-label">Base Fee</div>
                  <div class="info-value">₹${formatNumber(data.fees.live.base)}</div>
                </div>
                <div>
                  <div class="info-label">GST</div>
                  <div class="info-value">₹${formatNumber(data.fees.live.gst)}</div>
                </div>
                <div>
                  <div class="info-label">Total</div>
                  <div class="info-value">₹${formatNumber(data.fees.live.total)}</div>
                </div>
              </div>
            </div>
          `;
      }

      // Recorded Online Course
      if (data.fees.recorded) {
        html += `
            <div class="fee-section">
              <h6>Recorded Online Class Course</h6>
              <div class="fee-grid">
                <div>
                  <div class="info-label">Base Fee</div>
                  <div class="info-value">₹${formatNumber(data.fees.recorded.base)}</div>
                </div>
                <div>
                  <div class="info-label">GST</div>
                  <div class="info-value">₹${formatNumber(data.fees.recorded.gst)}</div>
                </div>
                <div>
                  <div class="info-label">Total</div>
                  <div class="info-value">₹${formatNumber(data.fees.recorded.total)}</div>
                </div>
              </div>
            </div>
          `;
      }

      // Study Material Only
      if (data.fees.study) {
        html += `
            <div class="fee-section">
              <h6>Study Material Only</h6>
              <div class="fee-grid">
                <div>
                  <div class="info-label">Base Fee</div>
                  <div class="info-value">₹${formatNumber(data.fees.study.base)}</div>
                </div>
                <div>
                  <div class="info-label">GST</div>
                  <div class="info-value">₹${formatNumber(data.fees.study.gst)}</div>
                </div>
                <div>
                  <div class="info-label">Total</div>
                  <div class="info-value">₹${formatNumber(data.fees.study.total)}</div>
                </div>
              </div>
            </div>
          `;
      }

      // Test Series Only
      if (data.fees.test) {
        html += `
            <div class="fee-section">
              <h6>Test Series Only</h6>
              <div class="fee-grid">
                <div>
                  <div class="info-label">Base Fee</div>
                  <div class="info-value">₹${formatNumber(data.fees.test.base)}</div>
                </div>
                <div>
                  <div class="info-label">GST</div>
                  <div class="info-value">₹${formatNumber(data.fees.test.gst)}</div>
                </div>
                <div>
                  <div class="info-label">Total</div>
                  <div class="info-value">₹${formatNumber(data.fees.test.total)}</div>
                </div>
              </div>
            </div>
          `;
      }

      // Installment Types
      const hasInstallments = data.fees.classroom || data.fees.live || data.fees.recorded;
      if (hasInstallments) {
        html += `
            <div class="form-section">Installment Types</div>
            <table class="installment-table">
              <thead>
                <tr>
                  <th>Installment Types</th>
                  <th>First Installment</th>
                  <th>Second Installment</th>
                  <th>Third Installment</th>
                </tr>
              </thead>
              <tbody>
          `;

        if (data.fees.classroom) {
          html += `
              <tr>
                <td><strong>Class room course</strong></td>
                <td>₹${formatNumber(data.fees.classroom.installments.first)}</td>
                <td>₹${formatNumber(data.fees.classroom.installments.second)}</td>
                <td>₹${formatNumber(data.fees.classroom.installments.third)}</td>
              </tr>
            `;
        }

        if (data.fees.live) {
          html += `
              <tr>
                <td><strong>Live online class course</strong></td>
                <td>₹${formatNumber(data.fees.live.installments.first)}</td>
                <td>₹${formatNumber(data.fees.live.installments.second)}</td>
                <td>₹${formatNumber(data.fees.live.installments.third)}</td>
              </tr>
            `;
        }

        if (data.fees.recorded) {
          html += `
              <tr>
                <td><strong>Recorded online class course</strong></td>
                <td>₹${formatNumber(data.fees.recorded.installments.first)}</td>
                <td>₹${formatNumber(data.fees.recorded.installments.second)}</td>
                <td>₹${formatNumber(data.fees.recorded.installments.third)}</td>
              </tr>
            `;
        }

        html += `
              </tbody>
            </table>
          `;
      }

      modalBody.innerHTML = html;

      // Show the modal
      const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
      viewModal.show();
    }

    function formatNumber(num) {
      return parseFloat(num).toFixed(2);
    }
  });
</script>

</html>