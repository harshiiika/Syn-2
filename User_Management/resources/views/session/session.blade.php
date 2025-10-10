<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  <title>Session</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/session.css') }}">


</head>

<body>
  <div class="flash-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
      </div>
    @endif
  </div>

  <div class="header">
    <div class="logo">
      <img src="{{ asset('images/logo.png.jpg') }}" class="img">
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
                <li><a class="item" href="{{ route('courses.index') }}"><i class="fa-solid fa-book-open"
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
          <h4>Sessions</h4>
        </div>

        <button type="button" class="btn btn-primary" id="liveToastBtn" data-bs-toggle="modal"
          data-bs-target="#createSessionModal">Create Session</button>

        <div class="toast-container end-0 p-3">
          <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body" id="toast">
              <i class="fa-regular fa-circle-xmark" style="color: #ff0000;"></i>Cannot create session. Limit reached
            </div>
          </div>
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
              <th scope="col" id="one">Session Name</th>
              <th scope="col" id="one">Start Date</th>
              <th scope="col" id="one">End Date</th>
              <th scope="col" id="one">Status</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sessions as $index => $session)
              @php
  $sessionId = $session->_id ?? $session->id ?? null;
  if (is_object($sessionId)) {
    $sessionId = (string) $sessionId;
  }
              @endphp
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $session->name }}</td>
                <td>{{ \Carbon\Carbon::parse($session->start_date)->format('Y-m-d') }}</td>
                <td>{{ \Carbon\Carbon::parse($session->end_date)->format('Y-m-d') }}</td>
                <td>
                  <span class="badge {{ $session->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($session->status) }}
                  </span>
                </td>
                <td>
  <div class="dropdown">
    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
            type="button" 
            id="actionDropdown{{ $sessionId }}" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
      <i class="fas fa-ellipsis-v"></i>
    </button>
    
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $sessionId }}">
      {{-- View is always available --}}
      <li>
        <button class="dropdown-item" 
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#viewSessionModal{{ $sessionId }}">
                View Details
        </button>
      </li>

      @if($session->status === 'active')
        {{-- Show Edit only for active --}}
        <li>
          <button class="dropdown-item" 
                  type="button"
                  data-bs-toggle="modal"
                  data-bs-target="#editSessionModal{{ $sessionId }}">
                  Edit Details
          </button>
        </li>

        <li><hr class="dropdown-divider"></li>

        {{-- Show End Session only for active --}}
        <li>
          <form method="POST" action="{{ route('sessions.end', $sessionId) }}" class="d-inline w-100">
            @csrf
            <button type="submit" 
                    class="dropdown-item text-danger" 
                    onclick="return confirm('Are you sure you want to end this session?')">
                    End Session
            </button>
          </form>
        </li>
      @else
        {{-- For inactive sessions --}}
        <li>
          <span class="dropdown-item-text text-muted">
            <i class="fas fa-info-circle me-2"></i> Session Ended
          </span>
        </li>
      @endif
    </ul>
  </div>
</td>

                <!-- <td>
                  <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenuButton"
                      data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa-solid fa-ellipsis-vertical" style="color: #000;"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="actionMenuButton">
                      {{-- View is always available --}}
                      <li>
                        <button class="dropdown-item" data-bs-toggle="modal"
                          data-bs-target="#viewSessionModal{{ $sessionId }}">
                          View Details
                        </button>
                      </li>

                      @if($session->status === 'active')
                        {{-- Show Edit only for active --}}
                        <li>
                          <button class="dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#editSessionModal{{ $sessionId }}">
                            Edit Details
                          </button>
                        </li>

                        {{-- Show End Session only for active --}}
                        <li>
                          <form method="POST" action="{{ route('sessions.end', $sessionId) }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                              End Session
                            </button>
                          </form>
                        </li>
                      @endif
                    </ul>
                  </div>
                </td> -->
              </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Create Session Modal -->
        @foreach($sessions as $session)
          @php
  $sessionId = $session->_id ?? $session->id ?? null;
  if (is_object($sessionId)) {
    $sessionId = (string) $sessionId;
  }
          @endphp
        @endforeach

        <!-- Create Session Modal -->
         <div class="modal fade" id="createSessionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <form action="{{ route('sessions.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                  <h5 class="modal-title">Create Session</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Session Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
                  </div>

                  <div class="form-text">
                    New sessions are created with status <strong>active</strong>. If an active session already exists,
                    you will get an error and creation will be blocked.
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Create Session</button>
                </div>
              </form>
            </div>
          </div>

        <!-- View Modal -->
        @foreach($sessions as $session)
          @php
  $sessionId = $session->_id ?? $session->id ?? null;
  if (is_object($sessionId)) {
    $sessionId = (string) $sessionId;
  }
          @endphp
          <div class="modal fade" id="viewSessionModal{{ $sessionId }}" tabindex="-1"
            aria-labelledby="viewSessionLabel{{ $sessionId }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="viewSessionLabel{{ $sessionId }}">Session Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Session Name</label>
                    <input type="text" class="form-control" value="{{ $session->name }}" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="text" class="form-control" value="{{ $session->start_date }}" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="text" class="form-control" value="{{ $session->end_date }}" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control" value="{{ ucfirst($session->status) }}" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach

        <!-- Edit Modal -->
        @foreach($sessions as $session)
          @php
  $sessionId = $session->_id ?? $session->id ?? null;
  if (is_object($sessionId)) {
    $sessionId = (string) $sessionId;
  }
          @endphp
          <div class="modal fade" id="editSessionModal{{ $sessionId }}" tabindex="-1"
            aria-labelledby="editSessionLabel{{ $sessionId }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <form method="POST" action="{{ route('sessions.update', $sessionId) }}">
                  @csrf
                  <!-- @method('PUT') -->
                  <div class="modal-header">
                    <h5 class="modal-title" id="editSessionLabel{{ $sessionId }}">Edit Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Session Name</label>
                      <input type="text" class="form-control" name="name" value="{{ $session->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Start Date</label>
                      <input type="date" class="form-control" name="start_date" value="{{ $session->start_date }}"
                        required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">End Date</label>
                      <input type="date" class="form-control" name="end_date" value="{{ $session->end_date }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Status</label>
                      <select class="form-select" name="status">
                        <option value="active" {{ $session->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $session->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @endforeach

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
          crossorigin="anonymous"></script>
        <script src="{{ asset('js/session.js') }}"></script>
</body>

</html>