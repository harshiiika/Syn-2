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
        <h6>Show Enteries:</h6>
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
        @forelse($fees as $index => $fee)
        <tr>
          <td>{{ ($fees->currentPage() - 1) * $fees->perPage() + $index + 1 }}</td>              
          <td>{{ $fee->course }}</td>
          <td>{{ $fee->course_type ?? 'N/A' }}</td>
          <td>{{ $fee->class_name ?? 'N/A' }}</td>
          <td class="{{ $fee->status === 'Active' ? 'text-success' : 'text-danger' }}">{{ $fee->status }}</td>
          <td>
            <div class="dropdown">
              <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown">
                <i class="fa-solid fa-ellipsis-vertical" style="color:#000"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a href="#" class="dropdown-item btn-view" data-id="{{ $fee->id }}">View Fees</a></li>
                <li>
                  <a href="#" class="dropdown-item btn-edit"
                     data-id="{{ $fee->id }}">Edit</a>
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
        <tr><td colspan="6" class="text-center">No Records</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="footer">
    <div class="left-footer">
      <p>Showing {{ $fees->firstItem() ?? 0 }} to {{ $fees->lastItem() ?? 0 }} of {{ $fees->total() }} Entries</p>
    </div>
    <div class="right-footer">
      <nav aria-label="Page navigation" id="bottom">
        <ul class="pagination">
          @if ($fees->onFirstPage())
            <li class="page-item disabled">
              <span class="page-link">Previous</span>
            </li>
          @else
            <li class="page-item">
              <a class="page-link" href="{{ $fees->previousPageUrl() }}">Previous</a>
            </li>
          @endif

          @php
            $start = max($fees->currentPage() - 2, 1);
            $end = min($fees->currentPage() + 2, $fees->lastPage());
          @endphp

          @for ($i = $start; $i <= $end; $i++)
            @if ($i == $fees->currentPage())
              <li class="page-item active">
                <span class="page-link">{{ $i }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link" href="{{ $fees->url($i) }}">{{ $i }}</a>
              </li>
            @endif
          @endfor

          @if ($fees->hasMorePages())
            <li class="page-item">
              <a class="page-link" href="{{ $fees->nextPageUrl() }}">Next</a>
            </li>
          @else
            <li class="page-item disabled">
              <span class="page-link">Next</span>
            </li>
          @endif
        </ul>
      </nav>
    </div>
  </div>
</div>
  </div>
  <!-- Create Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                <select class="form-control" id="scroll" name="course" required style="width: 100%;">
                  <option value="">Select Course</option>
                  <option value="Impulse 11th IIT">Impulse 11th IIT</option>
                  <option value="Momentum 12th NEET">Momentum 12th NEET</option>
                  <option value="Intensity 12th IIT">Intensity 12th IIT</option>
                  <option value="Thrust Target IIT">Thrust Target IIT</option>
                  <option value="Seedling 10th">Seedling 10th</option>
                  <option value="Anthesis 11th NEET">Anthesis 11th NEET</option>
                  <option value="Dynamic Target NEET">Dynamic Target NEET</option>
                  <option value="Radicle 8th">Radicle 8th</option>
                  <option value="Plumule 9th">Plumule 9th</option>
                  <option value="Nucleus 7th">Nucleus 7th</option>
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
            <div class="fees">
              <div class="mb-3">
                <label for="basic-url" class="form-label">Class Room Course</label>
                <div class="input-group" id="placeholder">
                  <input type="number" class="form-control" name="classroom_course" step="0.01" placeholder="Enter amount" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="basic-url" class="form-label">GST</label>
                <div class="input-group" id="placeholder">
                  <input type="text" class="form-control" placeholder="Auto calculated" readonly>
                </div>
              </div>
              <div class="mb-3">
                <label for="basic-url" class="form-label">Total</label>
                <div class="input-group" id="placeholder">
                  <input type="text" class="form-control" placeholder="Auto calculated" readonly>
                </div>
              </div>
            </div>
        <div class="fees">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Live online class course</label>
            <div class="input-group">
              <input type="number" class="form-control" name="live_online_course" step="0.01" placeholder="Enter amount" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST</label>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Auto calculated" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Total</label>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Auto calculated" readonly>
            </div>
          </div>
        </div>

        <div class="fees">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Recorded online class course</label>
            <div class="input-group">
              <input type="number" class="form-control" name="recorded_online_course" step="0.01" placeholder="Enter amount" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST</label>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Auto calculated" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Total</label>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Auto calculated" readonly>
            </div>
          </div>
        </div>

        <div class="fees">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Study Material only</label>
            <div class="input-group">
              <input type="number" class="form-control" name="study_material_only" step="0.01" placeholder="Enter amount" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST</label>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Auto calculated" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Total</label>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Auto calculated" readonly>
            </div>
          </div>
        </div>

        <div class="fees">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Test series only</label>
            <div class="input-group">
              <input type="number" class="form-control" name="test_series_only" step="0.01" placeholder="Enter amount" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST</label>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Auto calculated" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Total</label>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Auto calculated" readonly>
            </div>
          </div>
        </div>

      <div class="written">
  <table class="table table-bordered" style="margin-top: 20px;">
    <thead>
      <tr>
        <th>Installment Types</th>
        <th>First Installment (40%)</th>
        <th>Second Installment (30%)</th>
        <th>Third Installment (30%)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Class room course</td>
        <td><input type="text" class="form-control" id="classroom_inst1" readonly></td>
        <td><input type="text" class="form-control" id="classroom_inst2" readonly></td>
        <td><input type="text" class="form-control" id="classroom_inst3" readonly></td>
      </tr>
      <tr>
        <td>Live online class course</td>
        <td><input type="text" class="form-control" id="live_inst1" readonly></td>
        <td><input type="text" class="form-control" id="live_inst2" readonly></td>
        <td><input type="text" class="form-control" id="live_inst3" readonly></td>
      </tr>
      <tr>
        <td>Recorded online class course</td>
        <td><input type="text" class="form-control" id="recorded_inst1" readonly></td>
        <td><input type="text" class="form-control" id="recorded_inst2" readonly></td>
        <td><input type="text" class="form-control" id="recorded_inst3" readonly></td>
      </tr>
    </tbody>
  </table>
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
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="content">
        <form id="editForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Fees</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Course</label>
              <div class="input-group">
                <select class="form-control" id="edit_course" name="course" required style="width: 100%;">
                  <option value="">Select Course</option>
                  <option value="Impulse 11th IIT">Impulse 11th IIT</option>
                  <option value="Momentum 12th NEET">Momentum 12th NEET</option>
                  <option value="Intensity 12th IIT">Intensity 12th IIT</option>
                  <option value="Thrust Target IIT">Thrust Target IIT</option>
                  <option value="Seedling 10th">Seedling 10th</option>
                  <option value="Anthesis 11th NEET">Anthesis 11th NEET</option>
                  <option value="Dynamic Target NEET">Dynamic Target NEET</option>
                  <option value="Radicle 8th">Radicle 8th</option>
                  <option value="Plumule 9th">Plumule 9th</option>
                  <option value="Nucleus 7th">Nucleus 7th</option>
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
            <div class="fees">
              <div class="mb-3">
                <label for="basic-url" class="form-label">Class Room Course</label>
                <div class="input-group" id="placeholder">
                  <input type="number" class="form-control" id="edit_classroom_course" name="classroom_course" step="0.01" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="basic-url" class="form-label">GST</label>
                <div class="input-group" id="placeholder">
                  <input type="text" class="form-control" id="edit_classroom_gst" placeholder="Auto calculated" readonly>
                </div>
              </div>
              <div class="mb-3">
                <label for="basic-url" class="form-label">Total</label>
                <div class="input-group" id="placeholder">
                  <input type="text" class="form-control" id="edit_classroom_total" placeholder="Auto calculated" readonly>
                </div>
              </div>
            </div>
        <div class="fees">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Live online class course</label>
            <div class="input-group">
              <input type="number" class="form-control" id="edit_live_online_course" name="live_online_course" step="0.01" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_live_gst" placeholder="Auto calculated" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Total</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_live_total" placeholder="Auto calculated" readonly>
            </div>
          </div>
        </div>

        <div class="fees">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Recorded online class course</label>
            <div class="input-group">
              <input type="number" class="form-control" id="edit_recorded_online_course" name="recorded_online_course" step="0.01" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_recorded_gst" placeholder="Auto calculated" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Total</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_recorded_total" placeholder="Auto calculated" readonly>
            </div>
          </div>
        </div>

        <div class="fees">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Study Material only</label>
            <div class="input-group">
              <input type="number" class="form-control" id="edit_study_material_only" name="study_material_only" step="0.01" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_study_gst" placeholder="Auto calculated" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Total</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_study_total" placeholder="Auto calculated" readonly>
            </div>
          </div>
        </div>

        <div class="fees">
          <div class="mb-3">
            <label for="basic-url" class="form-label">Test series only</label>
            <div class="input-group">
              <input type="number" class="form-control" id="edit_test_series_only" name="test_series_only" step="0.01" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">GST</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_test_gst" placeholder="Auto calculated" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label for="basic-url" class="form-label">Total</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_test_total" placeholder="Auto calculated" readonly>
            </div>
          </div>
        </div>

        <div class="written">
          <table class="table table-bordered" style="margin-top: 20px;">
            <thead>
              <tr>
                <th>Installment Types</th>
                <th>First Installment (40%)</th>
                <th>Second Installment (30%)</th>
                <th>Third Installment (30%)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Class room course</td>
                <td><input type="text" class="form-control" id="edit_classroom_inst1" readonly></td>
                <td><input type="text" class="form-control" id="edit_classroom_inst2" readonly></td>
                <td><input type="text" class="form-control" id="edit_classroom_inst3" readonly></td>
              </tr>
              <tr>
                <td>Live online class course</td>
                <td><input type="text" class="form-control" id="edit_live_inst1" readonly></td>
                <td><input type="text" class="form-control" id="edit_live_inst2" readonly></td>
                <td><input type="text" class="form-control" id="edit_live_inst3" readonly></td>
              </tr>
              <tr>
                <td>Recorded online class course</td>
                <td><input type="text" class="form-control" id="edit_recorded_inst1" readonly></td>
                <td><input type="text" class="form-control" id="edit_recorded_inst2" readonly></td>
                <td><input type="text" class="form-control" id="edit_recorded_inst3" readonly></td>
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
  <!-- View Modal -->
  <div class="modal fade" id="exampleModalThree" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
          <p style="color: orangered;">Fees After GST</p>
          <div class="fees">
            <div class="mb-3">
              <label for="basic-url" class="form-label">Class Room Course</label>
              <div class="input-group" id="placeholder">
                <input type="number" class="form-control" id="view_classroom_course" readonly>
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">GST</label>
              <div class="input-group" id="placeholder">
                <input type="text" class="form-control" id="view_classroom_gst" readonly>
              </div>
            </div>
            <div class="mb-3">
              <label for="basic-url" class="form-label">Total</label>
              <div class="input-group" id="placeholder">
                <input type="text" class="form-control" id="view_classroom_total" readonly>
              </div>
            </div>
          </div>
      <div class="fees">
        <div class="mb-3">
          <label for="basic-url" class="form-label">Live online class course</label>
          <div class="input-group">
            <input type="number" class="form-control" id="view_live_online_course" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label for="basic-url" class="form-label">GST</label>
          <div class="input-group">
            <input type="text" class="form-control" id="view_live_online_gst" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label for="basic-url" class="form-label">Total</label>
          <div class="input-group">
            <input type="text" class="form-control" id="view_live_online_total" readonly>
          </div>
        </div>
      </div>

      <div class="fees">
        <div class="mb-3">
          <label for="basic-url" class="form-label">Recorded online class course</label>
          <div class="input-group">
            <input type="number" class="form-control" id="view_recorded_online_course" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label for="basic-url" class="form-label">GST</label>
          <div class="input-group">
            <input type="text" class="form-control" id="view_recorded_online_gst" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label for="basic-url" class="form-label">Total</label>
          <div class="input-group">
            <input type="text" class="form-control" id="view_recorded_online_total" readonly>
          </div>
        </div>
      </div>

      <div class="fees">
        <div class="mb-3">
          <label for="basic-url" class="form-label">Study Material only</label>
          <div class="input-group">
            <input type="number" class="form-control" id="view_study_material_only" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label for="basic-url" class="form-label">GST</label>
          <div class="input-group">
            <input type="text" class="form-control" id="view_study_material_gst" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label for="basic-url" class="form-label">Total</label>
          <div class="input-group">
            <input type="text" class="form-control" id="view_study_material_total" readonly>
          </div>
        </div>
      </div>

      <div class="fees">
        <div class="mb-3">
          <label for="basic-url" class="form-label">Test series only</label>
          <div class="input-group">
            <input type="number" class="form-control" id="view_test_series_only" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label for="basic-url" class="form-label">GST</label>
          <div class="input-group">
            <input type="text" class="form-control" id="view_test_series_gst" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label for="basic-url" class="form-label">Total</label>
          <div class="input-group">
            <input type="text" class="form-control" id="view_test_series_total" readonly>
          </div>
        </div>
      </div>

      <div class="written">
        <table class="table table-bordered" style="margin-top: 20px;">
          <thead>
            <tr>
              <th>Installment Types</th>
              <th>First Installment (40%)</th>
              <th>Second Installment (30%)</th>
              <th>Third Installment (30%)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Class room course</td>
              <td><input type="text" class="form-control" id="view_classroom_inst1" readonly></td>
              <td><input type="text" class="form-control" id="view_classroom_inst2" readonly></td>
              <td><input type="text" class="form-control" id="view_classroom_inst3" readonly></td>
            </tr>
            <tr>
              <td>Live online class course</td>
              <td><input type="text" class="form-control" id="view_live_inst1" readonly></td>
              <td><input type="text" class="form-control" id="view_live_inst2" readonly></td>
              <td><input type="text" class="form-control" id="view_live_inst3" readonly></td>
            </tr>
            <tr>
              <td>Recorded online class course</td>
              <td><input type="text" class="form-control" id="view_recorded_inst1" readonly></td>
              <td><input type="text" class="form-control" id="view_recorded_inst2" readonly></td>
              <td><input type="text" class="form-control" id="view_recorded_inst3" readonly></td>
            </tr>
          </tbody>
        </table>
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
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.addEventListener('DOMContentLoaded', function() {
  // CREATE MODAL CALCULATIONS
  const gstInput = document.querySelector('#exampleModal input[name="gst_percentage"]');
  const classroomInput = document.querySelector('#exampleModal input[name="classroom_course"]');
  const liveInput = document.querySelector('#exampleModal input[name="live_online_course"]');
  const recordedInput = document.querySelector('#exampleModal input[name="recorded_online_course"]');
  const studyInput = document.querySelector('#exampleModal input[name="study_material_only"]');
  const testInput = document.querySelector('#exampleModal input[name="test_series_only"]');

  const classroomGstField = document.querySelectorAll('#exampleModal .fees')[0].querySelectorAll('input[readonly]')[0];
  const classroomTotalField = document.querySelectorAll('#exampleModal .fees')[0].querySelectorAll('input[readonly]')[1];
  const liveGstField = document.querySelectorAll('#exampleModal .fees')[1].querySelectorAll('input[readonly]')[0];
  const liveTotalField = document.querySelectorAll('#exampleModal .fees')[1].querySelectorAll('input[readonly]')[1];
  const recordedGstField = document.querySelectorAll('#exampleModal .fees')[2].querySelectorAll('input[readonly]')[0];
  const recordedTotalField = document.querySelectorAll('#exampleModal .fees')[2].querySelectorAll('input[readonly]')[1];
  const studyGstField = document.querySelectorAll('#exampleModal .fees')[3].querySelectorAll('input[readonly]')[0];
  const studyTotalField = document.querySelectorAll('#exampleModal .fees')[3].querySelectorAll('input[readonly]')[1];
  const testGstField = document.querySelectorAll('#exampleModal .fees')[4].querySelectorAll('input[readonly]')[0];
  const testTotalField = document.querySelectorAll('#exampleModal .fees')[4].querySelectorAll('input[readonly]')[1];

  function calculateCreateFees() {
    const gstPercent = parseFloat(gstInput.value) || 0;
    
    // Classroom calculations
    const classroomFee = parseFloat(classroomInput.value) || 0;
    const classroomGst = (classroomFee * gstPercent / 100).toFixed(2);
    const classroomTotal = (classroomFee + parseFloat(classroomGst)).toFixed(2);
    classroomGstField.value = classroomGst;
    classroomTotalField.value = classroomTotal;
    
    // Live online calculations
    const liveFee = parseFloat(liveInput.value) || 0;
    const liveGst = (liveFee * gstPercent / 100).toFixed(2);
    const liveTotal = (liveFee + parseFloat(liveGst)).toFixed(2);
    liveGstField.value = liveGst;
    liveTotalField.value = liveTotal;
    
    // Recorded online calculations
    const recordedFee = parseFloat(recordedInput.value) || 0;
    const recordedGst = (recordedFee * gstPercent / 100).toFixed(2);
    const recordedTotal = (recordedFee + parseFloat(recordedGst)).toFixed(2);
    recordedGstField.value = recordedGst;
    recordedTotalField.value = recordedTotal;
    
    // Study material calculations
    const studyFee = parseFloat(studyInput.value) || 0;
    const studyGst = (studyFee * gstPercent / 100).toFixed(2);
    const studyTotal = (studyFee + parseFloat(studyGst)).toFixed(2);
    studyGstField.value = studyGst;
    studyTotalField.value = studyTotal;
    
    // Test series calculations
    const testFee = parseFloat(testInput.value) || 0;
    const testGst = (testFee * gstPercent / 100).toFixed(2);
    const testTotal = (testFee + parseFloat(testGst)).toFixed(2);
    testGstField.value = testGst;
    testTotalField.value = testTotal;
    
    // INSTALLMENT CALCULATIONS
    // Classroom installments
    if (parseFloat(classroomTotal) > 0) {
      const classroomInst1 = Math.round(parseFloat(classroomTotal) * 0.40);
      const classroomInst2 = Math.round(parseFloat(classroomTotal) * 0.30);
      const classroomInst3 = parseFloat(classroomTotal) - classroomInst1 - classroomInst2;
      
      document.getElementById('classroom_inst1').value = classroomInst1;
      document.getElementById('classroom_inst2').value = classroomInst2;
      document.getElementById('classroom_inst3').value = Math.round(classroomInst3);
    }
    
    // Live installments
    if (parseFloat(liveTotal) > 0) {
      const liveInst1 = Math.round(parseFloat(liveTotal) * 0.40);
      const liveInst2 = Math.round(parseFloat(liveTotal) * 0.30);
      const liveInst3 = parseFloat(liveTotal) - liveInst1 - liveInst2;
      
      document.getElementById('live_inst1').value = liveInst1;
      document.getElementById('live_inst2').value = liveInst2;
      document.getElementById('live_inst3').value = Math.round(liveInst3);
    }
    
    // Recorded installments
    if (parseFloat(recordedTotal) > 0) {
      const recordedInst1 = Math.round(parseFloat(recordedTotal) * 0.40);
      const recordedInst2 = Math.round(parseFloat(recordedTotal) * 0.30);
      const recordedInst3 = parseFloat(recordedTotal) - recordedInst1 - recordedInst2;
      
      document.getElementById('recorded_inst1').value = recordedInst1;
      document.getElementById('recorded_inst2').value = recordedInst2;
      document.getElementById('recorded_inst3').value = Math.round(recordedInst3);
    }
  }

  // Add event listeners for CREATE modal
  if (gstInput) gstInput.addEventListener('input', calculateCreateFees);
  if (classroomInput) classroomInput.addEventListener('input', calculateCreateFees);
  if (liveInput) liveInput.addEventListener('input', calculateCreateFees);
  if (recordedInput) recordedInput.addEventListener('input', calculateCreateFees);
  if (studyInput) studyInput.addEventListener('input', calculateCreateFees);
  if (testInput) testInput.addEventListener('input', calculateCreateFees);

  // EDIT MODAL CALCULATIONS
  const editGstInput = document.getElementById('edit_gst_percentage');
  const editClassroomInput = document.getElementById('edit_classroom_course');
  const editLiveInput = document.getElementById('edit_live_online_course');
  const editRecordedInput = document.getElementById('edit_recorded_online_course');
  const editStudyInput = document.getElementById('edit_study_material_only');
  const editTestInput = document.getElementById('edit_test_series_only');

  function calculateEditFees() {
    const gstPercent = parseFloat(editGstInput.value) || 0;
    
    const classroomFee = parseFloat(editClassroomInput.value) || 0;
    const classroomGst = (classroomFee * gstPercent / 100).toFixed(2);
    const classroomTotal = (classroomFee + parseFloat(classroomGst)).toFixed(2);
    document.getElementById('edit_classroom_gst').value = classroomGst;
    document.getElementById('edit_classroom_total').value = classroomTotal;
    
    const liveFee = parseFloat(editLiveInput.value) || 0;
    const liveGst = (liveFee * gstPercent / 100).toFixed(2);
    const liveTotal = (liveFee + parseFloat(liveGst)).toFixed(2);
    document.getElementById('edit_live_gst').value = liveGst;
    document.getElementById('edit_live_total').value = liveTotal;
    
    const recordedFee = parseFloat(editRecordedInput.value) || 0;
    const recordedGst = (recordedFee * gstPercent / 100).toFixed(2);
    const recordedTotal = (recordedFee + parseFloat(recordedGst)).toFixed(2);
    document.getElementById('edit_recorded_gst').value = recordedGst;
    document.getElementById('edit_recorded_total').value = recordedTotal;
    
    const studyFee = parseFloat(editStudyInput.value) || 0;
    const studyGst = (studyFee * gstPercent / 100).toFixed(2);
    const studyTotal = (studyFee + parseFloat(studyGst)).toFixed(2);
    document.getElementById('edit_study_gst').value = studyGst;
    document.getElementById('edit_study_total').value = studyTotal;
    
    const testFee = parseFloat(editTestInput.value) || 0;
    const testGst = (testFee * gstPercent / 100).toFixed(2);
    const testTotal = (testFee + parseFloat(testGst)).toFixed(2);
    document.getElementById('edit_test_gst').value = testGst;
    document.getElementById('edit_test_total').value = testTotal;
    
    // EDIT MODAL INSTALLMENTS
    if (parseFloat(classroomTotal) > 0) {
      const classroomInst1 = Math.round(parseFloat(classroomTotal) * 0.40);
      const classroomInst2 = Math.round(parseFloat(classroomTotal) * 0.30);
      const classroomInst3 = parseFloat(classroomTotal) - classroomInst1 - classroomInst2;
      
      document.getElementById('edit_classroom_inst1').value = classroomInst1;
      document.getElementById('edit_classroom_inst2').value = classroomInst2;
      document.getElementById('edit_classroom_inst3').value = Math.round(classroomInst3);
    }
    
    if (parseFloat(liveTotal) > 0) {
      const liveInst1 = Math.round(parseFloat(liveTotal) * 0.40);
      const liveInst2 = Math.round(parseFloat(liveTotal) * 0.30);
      const liveInst3 = parseFloat(liveTotal) - liveInst1 - liveInst2;
      
      document.getElementById('edit_live_inst1').value = liveInst1;
      document.getElementById('edit_live_inst2').value = liveInst2;
      document.getElementById('edit_live_inst3').value = Math.round(liveInst3);
    }
    
    if (parseFloat(recordedTotal) > 0) {
      const recordedInst1 = Math.round(parseFloat(recordedTotal) * 0.40);
      const recordedInst2 = Math.round(parseFloat(recordedTotal) * 0.30);
      const recordedInst3 = parseFloat(recordedTotal) - recordedInst1 - recordedInst2;
      
      document.getElementById('edit_recorded_inst1').value = recordedInst1;
      document.getElementById('edit_recorded_inst2').value = recordedInst2;
      document.getElementById('edit_recorded_inst3').value = Math.round(recordedInst3);
    }
  }

  if (editGstInput) editGstInput.addEventListener('input', calculateEditFees);
  if (editClassroomInput) editClassroomInput.addEventListener('input', calculateEditFees);
  if (editLiveInput) editLiveInput.addEventListener('input', calculateEditFees);
  if (editRecordedInput) editRecordedInput.addEventListener('input', calculateEditFees);
  if (editStudyInput) editStudyInput.addEventListener('input', calculateEditFees);
  if (editTestInput) editTestInput.addEventListener('input', calculateEditFees);

  // BUTTON HANDLERS
  document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const feeId = this.getAttribute('data-id');
      editFee(feeId);
    });
  });

  document.querySelectorAll('.btn-view').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const feeId = this.getAttribute('data-id');
      viewFee(feeId);
    });
  });

  // Alert auto-dismiss
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(function(alert) {
    setTimeout(function() {
      alert.classList.add('fade-out');
      setTimeout(function() {
        alert.remove();
      }, 300);
    }, 3000);
  });
});

function editFee(id) {
  fetch(`/fees-master/${id}`, {
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    console.log('Edit data received:', data);
    
    document.getElementById('edit_course').value = data.course;
    document.getElementById('edit_gst_percentage').value = data.gst_percent;
    document.getElementById('edit_classroom_course').value = data.classroom_fee;
    document.getElementById('edit_live_online_course').value = data.live_fee;
    document.getElementById('edit_recorded_online_course').value = data.recorded_fee;
    document.getElementById('edit_study_material_only').value = data.study_fee;
    document.getElementById('edit_test_series_only').value = data.test_fee;
    
    const event = new Event('input');
    document.getElementById('edit_gst_percentage').dispatchEvent(event);
    
    document.getElementById('editForm').action = `/fees-master/${id}`;
    
    var editModal = new bootstrap.Modal(document.getElementById('exampleModalTwo'));
    editModal.show();
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Failed to load fee details');
  });
}

function viewFee(id) {
  fetch(`/fees-master/${id}`, {
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    console.log('View data received:', data);
    
    const gstPercent = parseFloat(data.gst_percent) || 0;
    
    document.getElementById('view_course').value = data.course;
    document.getElementById('view_gst_percentage').value = gstPercent;
    
    // Classroom Course
    const classroomFee = parseFloat(data.classroom_fee) || 0;
    const classroomGst = (classroomFee * gstPercent / 100).toFixed(2);
    const classroomTotal = (classroomFee + parseFloat(classroomGst)).toFixed(2);
    document.getElementById('view_classroom_course').value = classroomFee.toFixed(2);
    document.getElementById('view_classroom_gst').value = classroomGst;
    document.getElementById('view_classroom_total').value = classroomTotal;
    
    const classroomInst1 = Math.round(parseFloat(classroomTotal) * 0.40);
    const classroomInst2 = Math.round(parseFloat(classroomTotal) * 0.30);
    const classroomInst3 = parseFloat(classroomTotal) - classroomInst1 - classroomInst2;
    document.getElementById('view_classroom_inst1').value = classroomInst1;
    document.getElementById('view_classroom_inst2').value = classroomInst2;
    document.getElementById('view_classroom_inst3').value = Math.round(classroomInst3);
    
    // Live Online Course
    const liveFee = parseFloat(data.live_fee) || 0;
    const liveGst = (liveFee * gstPercent / 100).toFixed(2);
    const liveTotal = (liveFee + parseFloat(liveGst)).toFixed(2);
    document.getElementById('view_live_online_course').value = liveFee.toFixed(2);
    document.getElementById('view_live_online_gst').value = liveGst;
    document.getElementById('view_live_online_total').value = liveTotal;
    
    const liveInst1 = Math.round(parseFloat(liveTotal) * 0.40);
    const liveInst2 = Math.round(parseFloat(liveTotal) * 0.30);
    const liveInst3 = parseFloat(liveTotal) - liveInst1 - liveInst2;
    document.getElementById('view_live_inst1').value = liveInst1;
    document.getElementById('view_live_inst2').value = liveInst2;
    document.getElementById('view_live_inst3').value = Math.round(liveInst3);
    
    // Recorded Online Course
    const recordedFee = parseFloat(data.recorded_fee) || 0;
    const recordedGst = (recordedFee * gstPercent / 100).toFixed(2);
    const recordedTotal = (recordedFee + parseFloat(recordedGst)).toFixed(2);
    document.getElementById('view_recorded_online_course').value = recordedFee.toFixed(2);
    document.getElementById('view_recorded_online_gst').value = recordedGst;
    document.getElementById('view_recorded_online_total').value = recordedTotal;
    
    const recordedInst1 = Math.round(parseFloat(recordedTotal) * 0.40);
    const recordedInst2 = Math.round(parseFloat(recordedTotal) * 0.30);
    const recordedInst3 = parseFloat(recordedTotal) - recordedInst1 - recordedInst2;
    document.getElementById('view_recorded_inst1').value = recordedInst1;
    document.getElementById('view_recorded_inst2').value = recordedInst2;
    document.getElementById('view_recorded_inst3').value = Math.round(recordedInst3);
    
    // Study Material Only
    const studyFee = parseFloat(data.study_fee) || 0;
    const studyGst = (studyFee * gstPercent / 100).toFixed(2);
    const studyTotal = (studyFee + parseFloat(studyGst)).toFixed(2);
    document.getElementById('view_study_material_only').value = studyFee.toFixed(2);
    document.getElementById('view_study_material_gst').value = studyGst;
    document.getElementById('view_study_material_total').value = studyTotal;
    
    // Test Series Only
    const testFee = parseFloat(data.test_fee) || 0;
    const testGst = (testFee * gstPercent / 100).toFixed(2);
    const testTotal = (testFee + parseFloat(testGst)).toFixed(2);
    document.getElementById('view_test_series_only').value = testFee.toFixed(2);
    document.getElementById('view_test_series_gst').value = testGst;
    document.getElementById('view_test_series_total').value = testTotal;
    
    var viewModal = new bootstrap.Modal(document.getElementById('exampleModalThree'));
    viewModal.show();
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Failed to load fee details');
  });
}

document.getElementById('searchInput').addEventListener('keyup', function() {
  const searchValue = this.value.toLowerCase();
  const tableRows = document.querySelectorAll('#table tbody tr');
  tableRows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchValue) ? '' : 'none';
  });
});

document.getElementById('toggleBtn')?.addEventListener('click', function() {
  const sidebar = document.getElementById('sidebar');
  sidebar.style.display = sidebar.style.display === 'none' ? 'block' : 'none';
});
</script>

</body>
</html>