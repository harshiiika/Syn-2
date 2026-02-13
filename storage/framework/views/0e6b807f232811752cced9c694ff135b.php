

<!DOCTYPE html>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Onboarding Students</title>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo e(asset('css/onboard.css')); ?>">
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
                  <a href="<?php echo e(route('student.onboard.onboard')); ?>"><button type="button" class="onboardbtn">Onboarding Students</button></a>
                </div>


      </div>
      <div class="whole">
         <!-- Table controls: entries dropdown and search -->
        <div class="dd">
          <div class="line">
            <h6>Show Enteries:</h6>
            <div class="dropdown">
             <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <?php echo e(request('per_page', 10)); ?>

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
            <tr>
            </tr>
          </tbody>
<!-- Modal fillables where roles are assigned according to dept automatically -->

<?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
  <td><?php echo e($index + 1); ?></td>
  <td><?php echo e($student->name); ?></td>
  <td><?php echo e($student->father); ?></td>
  <td><?php echo e($student->mobileNumber ?? '—'); ?></td>
  <td><?php echo e($student->courseName ?? '—'); ?></td>
  <td><?php echo e($student->deliveryMode ?? '—'); ?></td>
  <td><?php echo e($student->courseContent ?? '—'); ?></td>
  <td>
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenuButton-<?php echo e($student->_id); ?>"
              data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical" style="color: #000000;"></i>
      </button>
      <ul class="dropdown-menu" aria-labelledby="actionMenuButton-<?php echo e($student->_id); ?>">
        <li>
          <a href="<?php echo e(route('student.onboard.edit', $student->_id)); ?>" class="dropdown-item">
            Edit Details
          </a>
        </li>
        <li>
          <a href="<?php echo e(route('student.onboard.show', $student->_id)); ?>" class="dropdown-item">
            View Details
          </a>
        </li>
        <li>
          <form action="<?php echo e(route('student.onboard.transfer', $student->_id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to transfer <?php echo e($student->name); ?> to Pending Fees?')">
            <?php echo csrf_field(); ?>
            <button type="submit" class="dropdown-item">
              Transfer to Pending Fees
            </button>
          </form>
        </li>
        <li>
          <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#historyModal-<?php echo e($student->_id); ?>">
            History
          </button>
        </li>
      </ul>
    </div>
  </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>

      </div>
      <div class="footer">
        <div class="left-footer">
          <p>Showing 1 to 10 of 10 Enteries</p>
        </div>
        <div class="right-footer">
          <nav aria-label="...">
            <ul class="pagination">
              <li class="page-item"><a href="#" class="page-link" id="pg1">Previous</a></li>
              <li class="page-item active">
                <a class="page-link" href="#" aria-current="page" id="pg2">1</a>
              </li>
              <li class="page-item"><a class="page-link" href="/user management/emp/emp2.html" id="pg3">2</a></li>
              <li class="page-item"><a class="page-link" href="#" id="pg1">Next</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
  </div>

<!-- History Modals for each student -->
<?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="historyModal-<?php echo e($student->_id); ?>" tabindex="-1" aria-labelledby="historyModalLabel-<?php echo e($student->_id); ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #e05301 0%, #ff7733 100%);">
        <h5 class="modal-title text-white" id="historyModalLabel-<?php echo e($student->_id); ?>">
          <i class="fa-solid fa-clock-rotate-left me-2"></i>Activity History - <?php echo e($student->name); ?>

        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="background: #f8f9fa;">
        <?php if(isset($student->history) && is_array($student->history) && count($student->history) > 0): ?>
          <div class="timeline" style="position: relative; padding: 10px;">
            <?php $__currentLoopData = $student->history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $historyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="history-item" style="background: white; border-left: 4px solid #e05301; padding: 15px; margin-bottom: 15px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div class="flex-grow-1">
                    <h6 class="mb-1" style="color: #e05301; font-weight: 600;">
                      <?php
                        $actionIcons = [
                          'Created' => 'fa-plus-circle',
                          'New Student Enquiry Created' => 'fa-plus-circle',
                          'Student Enquiry Transferred' => 'fa-arrow-right',
                          'Transferred' => 'fa-arrow-right',
                          'Student Onboarded' => 'fa-check-circle',
                          'Student Details Updated' => 'fa-pen',
                          'Transferred to Pending Fees' => 'fa-exchange-alt'
                        ];
                        $icon = $actionIcons[$historyItem['action'] ?? ''] ?? 'fa-circle-info';
                      ?>
                      <i class="fas <?php echo e($icon); ?> me-2"></i>
                      <?php echo e($historyItem['action'] ?? 'Action'); ?>

                    </h6>
                    
                    <?php if(isset($historyItem['description'])): ?>
                      <p class="mb-2 ms-4" style="color: #495057; font-size: 14px;">
                        <?php echo e($historyItem['description']); ?>

                      </p>
                    <?php endif; ?>
                    
                    <?php if(isset($historyItem['changed_by'])): ?>
                      <small class="ms-4" style="color: #666; font-size: 13px;">
                        <i class="fas fa-user me-1"></i>
                        by <?php echo e($historyItem['changed_by']); ?>

                      </small>
                    <?php endif; ?>
                  </div>
                  
                  <div class="text-end" style="min-width: 150px;">
                    <?php if(isset($historyItem['date'])): ?>
                      <small style="color: #6c757d; font-size: 12px;">
                        <i class="far fa-clock me-1"></i>
                        <?php echo e($historyItem['date']); ?>

                      </small>
                    <?php elseif(isset($historyItem['timestamp'])): ?>
                      <small style="color: #6c757d; font-size: 12px;">
                        <i class="far fa-clock me-1"></i>
                        <?php echo e(\Carbon\Carbon::parse($historyItem['timestamp'])->format('d M Y, h:i A')); ?>

                      </small>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <i class="fas fa-clock-rotate-left fa-3x mb-3" style="color: #e0e0e0;"></i>
            <h5 style="color: #6c757d;">No History Yet</h5>
            <p class="text-muted">No activity recorded for this student.</p>
          </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- External JavaScript Libraries -->
<!-- Bootstrap Bundle JS (includes Popper) -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="<?php echo e(asset('js/emp.js')); ?>"></script>


<!-- AJAX Script: Handles dynamic user addition without page reload -->
<script>
  $('#addUserForm').on('submit', function (e) {
    e.preventDefault();
    $('.text-danger').text('');

    $.ajax({
      url: "<?php echo e(route('users.add')); ?>",
      method: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        if (response.status === 'success') {
          $('#addUserModal').modal('hide');
          $('#addUserForm')[0].reset();

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
            $(`#error-${field}`).text(errors[field][0]); //   Corrected
          }
        }
      }
    });
  });
</script>

</body>
</html><?php /**PATH C:\Users\DELL\Syn-2\resources\views/student/onboard/onboard.blade.php ENDPATH**/ ?>