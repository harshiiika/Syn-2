<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Batches Assignment</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
 <link rel="stylesheet" href="{{asset('css/batchesa.css')}}">
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

      <!-- left side bar accordian from bootstrap -->
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
                <!-- <li><a class="item" href="/user management/emp/emp.html "> <i class="fa-solid fa-user"
                      id="side-icon"></i> Employee</a></li>
                <li><a class="item" href="/user management/batches a/batchesa.html"><i class="fa-solid fa-user-group"
                      id="side-icon"></i> Batches
                    Assignment</a></li> -->
                   <li>
    <a class="item" href="{{ route('user.emp.emp') }}">
        <i class="fa-solid fa-user" id="side-icon"></i> Employee
    </a>
</li>
<li>
    <a class="item" href="{{ route('user.batches.batches') }}">
        <i class="fa-solid fa-user-group" id="side-icon"></i> Batches Assignment
    </a>
</li>
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
                <li>><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Inquiry
                    Management</a></li>
                <li><a class="item" href="/student management/stu onboard/onstu.html"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a>
                </li>
                <li><a class="item" href="/student management/pending/pending.html"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Fees
                    Students</a></li>
                <li><a class="item" href="/student management/students/stu.html"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
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
                <li><a class="item" href="/fees management/collect/collect.html"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Collection</a>
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
                <li><a class="item" href="/attendance management/students/student.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Student</a></li>
                <li><a class="item" href="/attendance management/employee/employee.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Employee</a></li>
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
                <li><a class="item" href="/study material/units/units.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Units</a></li>
                <li><a class="item" href="/study material/dispatch/dispatch.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Dispatch Material</a></li>
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
                <li><a class="item" href="/testseries/test.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Test Master</i></a></li>
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
                <li><a class="item" href="/reports/walk in/walk.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Walk In</a></li>
                <li><a class="item" href="/reports/att/att.html"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Attendance</a>
                </li>
                <li><a class="item" href="/reports/test/test.html"><i class="fa-solid fa-file" id="side-icon"></i>Test Series</a></li>
                <li><a class="item" href="/reports/inq/inq.html"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry History</a></li>
                <li><a class="item" href="/reports/onboard/onboard.html"><i class="fa-solid fa-file" id="side-icon"></i>Onboard History</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- right side content -->
    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
          <h4>BATCHES ASSIGNMENT</h4>
        </div>
        <div class="buttons">
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignBatchModal" id="add">
  Assign Batches
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
                            <th scope="col" id="one">Start Date</th>
                            <th scope="col" id="one">Username</th>
                            <th scope="col" id="one">Shift</th> 
                            <th scope="col" id="one">Status</th>
                            <th scope="col" id="one">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                  
                        
               
                 <!-- Table fillables are present here -->

@foreach($batches as $index => $batch)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $batch->batch_id ?? '—' }}</td>
    <td>{{ $batch->start_date }}</td>
    <td>{{ $batch->username }}</td>
    <td>{{ $batch->shift ?? '—' }}</td>
    <td>
        <span class="badge {{ $batch->status === 'Deactivated' ? 'bg-danger' : 'bg-success' }}">
            {{ $batch->status ?? 'Active' }}
        </span>
    </td>
    <td>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenuButton"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical" style="color: #000000;"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="actionMenuButton">
                <li>
                    <form method="POST" action="{{ route('batches.toggleStatus', $batch->_id) }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            {{ $batch->status === 'Active' ? 'Deactivate' : 'Reactivate' }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </td>
</tr>
@endforeach

      </tbody>
        </table>
</div>
         <div class="footer">
      <div class="left-footer">
  <p>Showing 1 to 1 of 1 Enteries</p>
      </div>
      <div class="right-footer">

      <!-- Pagination -->
         <nav aria-label="Page navigation example" id="bottom">
  <ul class="pagination" id="pagination">
    <li class="page-item"><a class="page-link" href id="pg1">Previous</a></li>
    <li class="page-item"><a class="page-link" href="#" id="pg2">1</a></li>
    <li class="page-item"><a class="page-link" href="#" id="pg1">Next</a></li>
  </ul>
</nav></div>
</div>
</div>
</div>
</div>

       <!-- Assign Batch Modal -->
        <div class="modal fade" id="assignBatchModal" tabindex="-1" aria-labelledby="assignBatchModalLabel" data-bs-target="#assignBatchModal" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content" id="content">
              <form method="POST" action="{{ route('batches.assign') }}" id="assignBatchForm">
                @csrf
                <div class="modal-header">
                  <h1 class="modal-title fs-5">Assign Batches</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="role" class="form-label">Select Role</label>
                    <div class="input-group">
                      <select name="username" class="form-select" required>
                        <option value="">Select Floor Incharge</option>
                        <option value="Floor Inch Evng (UG)">Floor Inch Evng (UG)</option>
                        <option value="Floor Inch Mrng(UG)">Floor Inch Mrng(UG)</option>
                        <option value="Preeti Acharya">Preeti Acharya</option>
                        <option value="Rajendra Kumar">Rajendra Kumar</option>
                        <option value="Omprakash Jyani">Omprakash Jyani</option>
                        <option value="Test Series Executive">Test Series Executive</option>
                      </select>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="batch" class="form-label">Select Batch</label>
                    <div class="input-group">
                      <select name="batch_id" class="form-select" required>
    <option value="">Select Batch</option>
                        <option value="L1">L1</option>
                        <option value="L2">L2</option>
                        <option value="L3">L3</option>
                        <option value="L4">L4</option>
</select>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="submit">Cancel</button>
                  <button type="submit" class="btn btn-primary" id="add">Assign</button>
                </div>
              </form>
            </div>
          </div>
        </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>
<script src="{{asset('js/emp.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

  // Ajax for dynamic user addition without page reload
$('form[action="{{ route('batches.assign') }}"]').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: "{{ route('batches.assign') }}",
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if(response.status === 'success') {
                $('#assignBatchModal').modal('hide');
                $('form[action="{{ route('batches.assign') }}"]')[0].reset();

                // Append new batch to table
                $('#table tbody').append(`
                    <tr>
                        <td>${$('#table tbody tr').length + 1}</td>
                        <td>${response.batch.batch_id}</td>
                        <td>${response.batch.start_date}</td>
                        <td>${response.batch.username}</td>
                        <td>${response.batch.shift}</td>
                        <td>
                            <span class="badge ${response.batch.status === 'Deactivated' ? 'bg-danger' : 'bg-success'}">
                                ${response.batch.status}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="actionMenuButton"
          data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-three-dots-vertical" style="color: #000000;"></i>
  </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form method="POST" action="/batches/toggle-status/${response.batch.id}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                ${response.batch.status === 'Active' ? 'Deactivate' : 'Activate'}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `);
            }
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                console.log(errors);
                // Optional: show validation errors on modal
            }
        }
    });
});

</script>
</html>