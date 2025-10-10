<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Managment</title>
   <link rel="stylesheet" href="{{ asset('css/inq.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>
    <div class="toast-container end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
                <img src="/images/tick.png" class="tick">
                Login Successfully
            </div>
        </div>
    </div>

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
            <i class="fa-solid fa-bell" style="color: rgb(233, 96, 47); font-size: 22px;"></i>

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user" style="color: rgb(233, 96, 47); font-size: 22px;"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="/pfp/pfp.html" class="dropdown-item"> <i class="fa-solid fa-user"
                                style="color: rgb(233, 96, 47); font-size: 15px;"></i>Profile</li></a>
                    <li><a href="/login page/login.html" class="dropdown-item"><i
                                class="fa-solid fa-arrow-right-from-bracket"
                                style="color: rgb(233, 96, 47); font-size: 15px;"></i>Log In</li></a>
                </ul>
            </div>

        </div>
    </div>

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
            <div class="up">
                <h1>Inquiry</h1>
                <div class="btns">
     <a href="{{ route('inquiries.create') }}" class="btn btn-sm btn-orange">Create Inquiry</a>

                    <button type="button" class="upload" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">Upload</button>
{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel"
     aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" style="max-width:720px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-semibold" id="uploadModalLabel">Upload</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      {{-- You can wire action later. For now it's a dummy form so the modal just shows. --}}
      <form id="uploadForm" method="POST" action="#" enctype="multipart/form-data" onsubmit="return false;">
        <div class="modal-body">

          <div class="d-flex justify-content-center mb-3">
            <a href="#" class="btn btn-warning">Download Sample File</a>
          </div>

          {{-- Dropzone-like box to click and select a file --}}
          <label for="inqFile" class="w-100" style="cursor:pointer;">
            <div class="border rounded-3 d-flex align-items-center justify-content-center py-4"
                 style="border-style:dashed; border-width:2px;">
              <span id="dropzoneLabel" class="text-muted">Upload File</span>
            </div>
          </label>

          <input id="inqFile" name="file" type="file" class="d-none" accept=".xlsx,.xls,.csv" />

          <div class="form-text mt-2">
            Allowed: .xlsx, .xls, .csv
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
          <button type="submit" id="uploadYesBtn" class="btn btn-primary" disabled>Yes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<button class="onboard">Onboard</button>                     
<div class="toast-container end-0 p-3">                         
    <div id="onboardToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">                             
        <div class="toast-body" id="onboardToastBody">                                 
            <i class="fa-regular fa-circle-xmark" id="xmark"></i>                                 
            <span>Please select at least one student</span>                             
        </div>                         
    </div>                     
</div>
          </div>
            </div>
            <div class="card">
                <div class="filter">
                    <div class="dropdown-center" id="dropdown">
                        <p>Show</p><button class="btn btn-secondary dropdown-toggle" id="dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">10</button>
                        <p>enteries</p>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">10</a></li>
                            <li><a class="dropdown-item" href="#">20</a></li>
                            <li><a class="dropdown-item" href="#">50</a></li>
                            <li><a class="dropdown-item" href="#">100</a></li>
                            <li><a class="dropdown-item" href="#">All</a></li>
                        </ul>
                    </div>
                    <div class="search">
                        <input type="search" placeholder="Search" class="search-input" required>
                        <button type="submit" class="search-btn" id="search-button"><i
                         class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                <table class="table table-hover" id="table">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                            </th>
                            <th scope="col" id="one">Serial No.</th>
                            <th scope="col" id="one">Student Name</th>
                            <th scope="col" id="one">Father Name</th>
                            <th scope="col" id="one">Father Contact No.</th>
                            <th scope="col" id="one">Course Name</th>
                            <th scope="col" id="one">Delivery Mode</th>
                            <th scope="col" id="one">Course Content</th>
                            <th scope="col" id="one">Status</th>
                            <th scope="col" id="one">Action</th>
                        </tr>
                    </thead>
    <tbody>
    @forelse ($inquiries as $i => $inq)
    <tr>
        <td>
        <input class="form-check-input" type="checkbox" value="{{ $inq->id }}">
        </td>
        {{-- Serial No. respecting pagination --}}
        <td>{{ ($inquiries->firstItem() ?? 1) + $i }}</td>

        <td>{{ $inq->student_name }}</td>
        <td>{{ $inq->father_name }}</td>
        <td>{{ $inq->father_contact }}</td>

        <td>{{ $inq->course_name ?? '—' }}</td>
        <td>{{ $inq->delivery_mode ?? '—' }}</td>
        <td>{{ $inq->course_content ?? '—' }}</td>
        <td>{{ $inq->status ?? 'Pending' }}</td>

        <td>
      <div class="dropdown">
        <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown" id="ellipsis">
          <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <ul class="dropdown-menu">
          <li>
            <a class="dropdown-item" href="{{ route('inquiries.show', $inq->id) }}">View details</a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('inquiries.edit', $inq->id) }}">Edit</a>
          </li>
          <li><a class="dropdown-item" href="#">Onboard</a></li>
          <li><a class="dropdown-item" href="#">History</a></li>
        </ul>
      </div>
    </td>
  </tr>
    @empty
    <tr>
        <td colspan="10" class="text-center text-muted py-4">No inquiries yet.</td>
    </tr>
    @endforelse
    </tbody>
                </table>
               <div class="footer d-flex flex-column flex-md-row align-items-md-center justify-content-between">
  <h6 class="mb-2 mb-md-0">
    @if ($inquiries->total() > 0)
      Showing {{ $inquiries->firstItem() }} to {{ $inquiries->lastItem() }} of {{ $inquiries->total() }} entries
    @else
      Showing 0 entries
    @endif
  </h6>

  <div>
    {{ $inquiries->onEachSide(1)->links() }}
  </div>
</div>
            </div>
        </div>
    </div>

   <div class="modal-dialog modal-dialog-centered">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Upload</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <a href="enquires.xlsx"><button class="sampleFile">Download Sample File</button></a>
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="file" id="file" class="form-control">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>
<script> document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.querySelector('.sidebar') || document.querySelector('#sidebar') || document.querySelector('#admin') || document.querySelector('#right');
  
  let isCollapsed = false;

  sidebar.style.transition = 'width 0.5s ease';
  sidebar.style.overflow = 'hidden';
  sidebar.style.width = '300px';

  const menuItems = sidebar.querySelectorAll('li, a, .nav-item');
  menuItems.forEach(item => {
    item.style.whiteSpace = 'nowrap';
  });

  toggleBtn.addEventListener('click', function () {
    console.log('Toggle button clicked! Current state:', isCollapsed ? 'collapsed' : 'expanded');

    if (isCollapsed) {
      sidebar.style.width = '25%';
      admin.style.visibility ='visible';
      right.style.width = '100%';
    } else {
      sidebar.style.width = '40px';
      admin.style.visibility ='hidden';
      right.style.width = '100%'; 
    }

    isCollapsed = !isCollapsed; 
  });
});


const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))


const myModal = document.getElementById('exampleModal');
const myInput = document.getElementById('file');

myModal.addEventListener('shown.bs.modal', () => {
  myInput.focus();
});

document.querySelector('#file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        console.log('File selected:', file.name);
        
        setTimeout(() => {
            showUploadToast('success', 'File uploaded successfully!');
        }, 1000);
    }
});    
</script>
</html>