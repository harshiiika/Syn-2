

<!DOCTYPE html>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pending Inquiries</title>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo e(asset('css/emp.css')); ?>">
   <!-- Bootstrap 5.3.6 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

</head>

<body>
  <!-- Header Section: Contains logo, sidebar toggle, session selector, notifications, and user menu -->
 
  <div class="header">
    <div class="logo">
      <img src="<?php echo e(asset('images/logo.png.jpg')); ?>" class="img">

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
          <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>""> <i class="fa-solid fa-user"></i>Profile</a></li>
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
  <!-- User Management -->
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
          <li><a class="item" href="<?php echo e(route('user.emp.emp')); ?>"><i class="fa-solid fa-user" id="side-icon"></i> Employee</a></li>     
          <li><a class="item" href="<?php echo e(route('user.batches.batches')); ?>"><i class="fa-solid fa-user-group" id="side-icon"></i> Batches Assignment</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Master -->
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
          <li><a class="item" href="<?php echo e(route('courses.index')); ?>"><i class="fa-solid fa-book-open" id="side-icon"></i> Courses</a></li>
          <li><a class="item" href="<?php echo e(route('batches.index')); ?>"><i class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i> Batches</a></li>
          <li><a class="item" href="<?php echo e(route('master.scholarship.index')); ?>"><i class="fa-solid fa-graduation-cap" id="side-icon"></i> Scholarship</a></li>
          <li><a class="item" href="<?php echo e(route('fees.index')); ?>"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Master</a></li>
          <li><a class="item" href="<?php echo e(route('master.other_fees.index')); ?>"><i class="fa-solid fa-wallet" id="side-icon"></i> Other Fees Master</a></li>
          <li><a class="item" href="<?php echo e(route('branches.index')); ?>"><i class="fa-solid fa-diagram-project" id="side-icon"></i> Branch Management</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Session Management -->
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
          <li><a class="item" href="<?php echo e(route('sessions.index')); ?>"><i class="fa-solid fa-calendar-day" id="side-icon"></i> Session</a></li>
          <li><a class="item" href="<?php echo e(route('calendar.index')); ?>"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Calendar</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-user-check" id="side-icon"></i> Student Migrate</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Student Management -->
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
          <li><a class="item" href="<?php echo e(route('inquiries.index')); ?>"><i class="fa-solid fa-circle-info" id="side-icon"></i> Inquiry Management</a></li>
          <li><a class="item" href="<?php echo e(route('student.student.pending')); ?>"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a></li>
          <li><a class="item" href="<?php echo e(route('student.pendingfees.pending')); ?>"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Fees Students</a></li>
          <li><a class="item active" href="<?php echo e(route('smstudents.index')); ?>"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Fees Management -->
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
          <li><a class="item" href="<?php echo e(route('fees.management.index')); ?>"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Collection</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Attendance Management -->
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
          <li><a class="item" href="<?php echo e(route('attendance.employee.index')); ?>"><i class="fa-solid fa-circle-info" id="side-icon"></i> Employee</a></li>
          <li><a class="item" href="<?php echo e(route('attendance.student.index')); ?>"><i class="fa-solid fa-circle-info" id="side-icon"></i> Student</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Study Material -->
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
          <li><a class="item" href="<?php echo e(route('units.index')); ?>"><i class="fa-solid fa-user" id="side-icon"></i>Units</a></li>
          <li><a class="item" href="<?php echo e(route('dispatch.index')); ?>"><i class="fa-solid fa-user" id="side-icon"></i>Dispatch Material</a></li>

        </ul>
      </div>
    </div>
  </div>

  <!-- Test Series Management -->
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
          <li><a class="item" href="<?php echo e(route(name: 'test_series.index')); ?>"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Reports -->
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
          <li><a class="item" href="<?php echo e(route('reports.walkin.index')); ?>"><i class="fa-solid fa-user" id="side-icon"></i>Walk In</a></li>
          <li><a class="item" href="<?php echo e(route('reports.attendance.student.index')); ?>"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Attendance</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Test Series</a></li>
          <li><a class="item" href="<?php echo e(route('inquiries.index')); ?>"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry History</a></li>
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
               <a href="<?php echo e(route('student.student.pending')); ?>"><button type="button" class="pendingbtn">Pending Inquiries</button></a>
              <a class="item" href="<?php echo e(route('student.onboard.onboard')); ?>"><button type="button" class="onboardbtn">Onboarding Students</button></a>
            </div>

      </div>
     <div class="whole">
  <!-- Table controls: entries dropdown and search -->
<div class="dd">
  <div class="line">
    <h6>Show Entries:</h6>
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <?php echo e(request('per_page', 10)); ?>

      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo e(route('student.student.pending', ['per_page' => 5, 'search' => request('search')])); ?>">5</a></li>
        <li><a class="dropdown-item" href="<?php echo e(route('student.student.pending', ['per_page' => 10, 'search' => request('search')])); ?>">10</a></li>
        <li><a class="dropdown-item" href="<?php echo e(route('student.student.pending', ['per_page' => 25, 'search' => request('search')])); ?>">25</a></li>
        <li><a class="dropdown-item" href="<?php echo e(route('student.student.pending', ['per_page' => 50, 'search' => request('search')])); ?>">50</a></li>
        <li><a class="dropdown-item" href="<?php echo e(route('student.student.pending', ['per_page' => 100, 'search' => request('search')])); ?>">100</a></li>
      </ul>
    </div>
  </div>
  <div class="search">
    <form method="GET" action="<?php echo e(route('student.student.pending')); ?>" id="searchForm">
      <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
      <input type="search" 
             name="search" 
             placeholder="Search by name, father, mobile, course..." 
             class="search-holder" 
             value="<?php echo e(request('search')); ?>"
             id="searchInput">
      <i class="fa-solid fa-magnifying-glass"></i>
    </form>
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
      <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr>
        <!-- Serial number with pagination support -->
        <td><?php echo e(($students->currentPage() - 1) * $students->perPage() + $index + 1); ?></td>
        <td><?php echo e($student->name); ?></td>
        <td><?php echo e($student->father); ?></td>
        <td><?php echo e($student->mobileNumber ?? '—'); ?></td>
        <td><?php echo e($student->courseName ?? '—'); ?></td>
        <td><?php echo e($student->deliveryMode ?? '—'); ?></td>
        <td><?php echo e($student->courseContent ?? '—'); ?></td>
        <td>
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenuButton"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical" style="color: #000000;"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="actionMenuButton">
              <li>
                <a href="<?php echo e(route('student.student.edit', $student->_id)); ?>">
                  <button class="dropdown-item">Edit Details</button>
                </a>
              </li>
            </ul>
          </div>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr>
        <td colspan="8" class="text-center py-4">
          <?php if(request('search')): ?>
            <p class="mb-0">No students found matching "<?php echo e(request('search')); ?>"</p>
            <a href="<?php echo e(route('student.student.pending')); ?>" class="btn btn-sm btn-outline-secondary mt-2">
              <i class="fa-solid fa-times"></i> Clear Search
            </a>
          <?php else: ?>
            <p class="mb-0">No pending students found</p>
          <?php endif; ?>
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="footer">
  <div class="left-footer">
    <p>Showing <?php echo e($students->firstItem() ?? 0); ?> to <?php echo e($students->lastItem() ?? 0); ?> of <?php echo e($students->total()); ?> entries
      <?php if(request('search')): ?>
        <span class="text-muted">(filtered from <?php echo e(\App\Models\Student\Pending::count()); ?> total entries)</span>
      <?php endif; ?>
    </p>
  </div>
  <div class="right-footer">
    <nav aria-label="Page navigation example" id="bottom">
      <ul class="pagination" id="pagination">
        
        <?php if($students->onFirstPage()): ?>
          <li class="page-item disabled">
            <span class="page-link" id="pg1">Previous</span>
          </li>
        <?php else: ?>
          <li class="page-item">
            <a class="page-link" 
               href="<?php echo e($students->previousPageUrl()); ?>" 
               id="pg1">Previous</a>
          </li>
        <?php endif; ?>

        
        <?php
          $start = max($students->currentPage() - 2, 1);
          $end = min($start + 4, $students->lastPage());
          $start = max($end - 4, 1);
        ?>

        <?php if($start > 1): ?>
          <li class="page-item" id="pg2">
            <a class="page-link" href="<?php echo e($students->url(1)); ?>">1</a>
          </li>
          <?php if($start > 2): ?>
            <li class="page-item disabled">
              <span class="page-link">...</span>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <?php for($i = $start; $i <= $end; $i++): ?>
          <li class="page-item <?php echo e($students->currentPage() == $i ? 'active' : ''); ?>">
            <a class="page-link" 
               href="<?php echo e($students->url($i)); ?>"
               id="pg<?php echo e($i); ?>"><?php echo e($i); ?></a>
          </li>
        <?php endfor; ?>

        <?php if($end < $students->lastPage()): ?>
          <?php if($end < $students->lastPage() - 1): ?>
            <li class="page-item disabled">
              <span class="page-link">...</span>
            </li>
          <?php endif; ?>
          <li class="page-item">
            <a class="page-link" href="<?php echo e($students->url($students->lastPage())); ?>"><?php echo e($students->lastPage()); ?></a>
          </li>
        <?php endif; ?>

        
        <?php if($students->hasMorePages()): ?>
          <li class="page-item">
            <a class="page-link" 
               href="<?php echo e($students->nextPageUrl()); ?>" 
               id="pg4">Next</a>
          </li>
        <?php else: ?>
          <li class="page-item disabled">
            <span class="page-link" id="pg4">Next</span>
          </li>
        <?php endif; ?>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="<?php echo e(asset('js/emp.js')); ?>"></script>


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
      url: "<?php echo e(route('users.add')); ?>",
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
            $(#error-${field}).text(errors[field][0]);
          }
        }
      }
    });
  });

document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit search on Enter key
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Search icon click handler
    const searchIcon = document.querySelector('.search i.fa-magnifying-glass');
    const searchForm = document.getElementById('searchForm');
    
    if (searchIcon && searchForm) {
        searchIcon.addEventListener('click', function() {
            searchForm.submit();
        });
    }

    // Search input enter key handler
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    }
});
</script>

</html><?php /**PATH C:\Users\Priyanshi Rathore\Syn-2\resources\views/student/student/pending.blade.php ENDPATH**/ ?>