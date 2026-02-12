



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  <title>Session</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('css/session.css')); ?>">


</head>

<body>
  <div class="flash-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <?php if(session('success')): ?>
      <div class="alert alert-success alert-dismissible" role="alert">
        <?php echo e(session('success')); ?>

      </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
      <div class="alert alert-danger alert-dismissible" role="alert">
        <?php echo e(session('error')); ?>

      </div>
    <?php endif; ?>
  </div>

  <div class="header">
    <div class="logo">
      <img src="<?php echo e(asset('images/logo.png.jpg')); ?>" class="img">
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

    <div class="left" id="sidebar">

      <div class="text" id="text">
        <h6>ADMIN</h6>
        <p>synthesisbikaner@gmail.com</p>
      </div>
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

    <!-- Div for right section starts here -->
    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
          <h4>SESSION ASSIGNMENT</h4>
        </div>

        <button type="button" class="btn btn-primary" id="liveToastBtn" data-bs-toggle="modal"
          data-bs-target="#createSessionModal">Create Session</button>


          <!-- Toast for (Session Limit Reached) -->

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
    <h6>Show Entries:</h6>
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <?php echo e(request('per_page', 10)); ?>

      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#" data-value="5">5</a></li>
        <li><a class="dropdown-item" href="#" data-value="10">10</a></li>
        <li><a class="dropdown-item" href="#" data-value="25">25</a></li>
        <li><a class="dropdown-item" href="#" data-value="50">50</a></li>
        <li><a class="dropdown-item" href="#" data-value="100">100</a></li>
      </ul>
    </div>
  </div>
  <div class="search">
    <form method="GET" action="<?php echo e(route('sessions.index')); ?>" id="searchForm">
      <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
      <input type="search" 
             name="search" 
             placeholder="Search" 
             class="search-holder" 
             value="<?php echo e(request('search')); ?>"
             id="searchInput">
      <i class="fa-solid fa-magnifying-glass"></i>
    </form>
  </div>
</div>

<!-- Update the table section to use pagination -->
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
    <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $sessionId = $session->_id ?? $session->id ?? null;
        if (is_object($sessionId)) {
          $sessionId = (string) $sessionId;
        }
      ?>
      <tr>
        <td><?php echo e($sessions->firstItem() + $index); ?></td>
        <td><?php echo e($session->name); ?></td>
        <td><?php echo e(\Carbon\Carbon::parse($session->start_date)->format('Y-m-d')); ?></td>
        <td><?php echo e(\Carbon\Carbon::parse($session->end_date)->format('Y-m-d')); ?></td>
        <td>
          <span class="badge <?php echo e($session->status === 'active' ? 'bg-success' : 'bg-danger'); ?>">
            <?php echo e(ucfirst($session->status)); ?>

          </span>
        </td>
        <td>
          <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                    type="button" 
                    id="actionDropdown<?php echo e($sessionId); ?>" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </button>
            
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown<?php echo e($sessionId); ?>">
              <li>
                <button class="dropdown-item" 
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#viewSessionModal<?php echo e($sessionId); ?>">
                        View Details
                </button>
              </li>

              <?php if($session->status === 'active'): ?>
                <li>
                  <button class="dropdown-item" 
                          type="button"
                          data-bs-toggle="modal"
                          data-bs-target="#editSessionModal<?php echo e($sessionId); ?>">
                          Edit Details
                  </button>
                </li>

                <li><hr class="dropdown-divider"></li>

                <li>
                  <form method="POST" action="<?php echo e(route('sessions.end', $sessionId)); ?>" class="d-inline w-100">
                    <?php echo csrf_field(); ?>
                    <button type="submit" 
                            class="dropdown-item text-danger" 
                            onclick="return confirm('Are you sure you want to end this session?')">
                            End Session
                    </button>
                  </form>
                </li>
              <?php else: ?>
                <li>
                  <span class="dropdown-item-text text-muted">
                    <i class="fas fa-info-circle me-2"></i> Session Ended
                  </span>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </td>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>
<div class="footer">
  <div class="left-footer">
    <p>Showing <?php echo e($sessions->firstItem() ?? 0); ?> to <?php echo e($sessions->lastItem() ?? 0); ?> of <?php echo e($sessions->total()); ?> entries
      <?php if(request('search')): ?>
        <span class="text-muted">(filtered from <?php echo e(\App\Models\User\User::count()); ?> total entries)</span>
      <?php endif; ?>
    </p>
  </div>
  <div class="right-footer">
    <nav aria-label="Page navigation example" id="bottom">
      <ul class="pagination" id="pagination">
        
        <?php if($sessions->onFirstPage()): ?>
          <li class="page-item disabled">
            <span class="page-link" id="pg1">Previous</span>
          </li>
        <?php else: ?>
          <li class="page-item">
            <a class="page-link" 
               href="<?php echo e($sessions->previousPageUrl()); ?>" 
               id="pg1">Previous</a>
          </li>
        <?php endif; ?>

        
        <?php
          $start = max($sessions->currentPage() - 2, 1);
          $end = min($start + 4, $sessions->lastPage());
          $start = max($end - 4, 1);
        ?>

        <?php if($start > 1): ?>
          <li class="page-item" id="pg2">
            <a class="page-link" href="<?php echo e($sessions->url(1)); ?>">1</a>
          </li>
          <?php if($start > 2): ?>
            <li class="page-item disabled">
              <span class="page-link">...</span>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <?php for($i = $start; $i <= $end; $i++): ?>
          <li class="page-item <?php echo e($sessions->currentPage() == $i ? 'active' : ''); ?>">
            <a class="page-link" 
               href="<?php echo e($sessions->url($i)); ?>"
               id="pg<?php echo e($i); ?>"><?php echo e($i); ?></a>
          </li>
        <?php endfor; ?>

        <?php if($end < $sessions->lastPage()): ?>
          <?php if($end < $sessions->lastPage() - 1): ?>
            <li class="page-item disabled">
              <span class="page-link">...</span>
            </li>
          <?php endif; ?>
          <li class="page-item">
            <a class="page-link" href="<?php echo e($sessions->url($sessions->lastPage())); ?>"><?php echo e($sessions->lastPage()); ?></a>
          </li>
        <?php endif; ?>

        
        <?php if($sessions->hasMorePages()): ?>
          <li class="page-item">
            <a class="page-link" 
               href="<?php echo e($sessions->nextPageUrl()); ?>" 
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
        <!-- Create Session Modal -->
        <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
  $sessionId = $session->_id ?? $session->id ?? null;
  if (is_object($sessionId)) {
    $sessionId = (string) $sessionId;
  }
          ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Create Session Modal -->
         <div class="modal fade" id="createSessionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <form action="<?php echo e(route('sessions.store')); ?>" method="POST" class="modal-content">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                  <h5 class="modal-title">Create Session</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Session Name</label>
                    <input type="text" name="name" class="form-control" required value="<?php echo e(old('name')); ?>">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required value="<?php echo e(old('start_date')); ?>">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" required value="<?php echo e(old('end_date')); ?>">
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
        <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
  $sessionId = $session->_id ?? $session->id ?? null;
  if (is_object($sessionId)) {
    $sessionId = (string) $sessionId;
  }
          ?>
          <div class="modal fade" id="viewSessionModal<?php echo e($sessionId); ?>" tabindex="-1"
            aria-labelledby="viewSessionLabel<?php echo e($sessionId); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="viewSessionLabel<?php echo e($sessionId); ?>">Session Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Session Name</label>
                    <input type="text" class="form-control" value="<?php echo e($session->name); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="text" class="form-control" value="<?php echo e($session->start_date); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="text" class="form-control" value="<?php echo e($session->end_date); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control" value="<?php echo e(ucfirst($session->status)); ?>" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Edit Modal -->
        <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
  $sessionId = $session->_id ?? $session->id ?? null;
  if (is_object($sessionId)) {
    $sessionId = (string) $sessionId;
  }
          ?>
          <div class="modal fade" id="editSessionModal<?php echo e($sessionId); ?>" tabindex="-1"
            aria-labelledby="editSessionLabel<?php echo e($sessionId); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <form method="POST" action="<?php echo e(route('sessions.update', $sessionId)); ?>">
                  <?php echo csrf_field(); ?>
                  <!-- <?php echo method_field('PUT'); ?> -->
                  <div class="modal-header">
                    <h5 class="modal-title" id="editSessionLabel<?php echo e($sessionId); ?>">Edit Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Session Name</label>
                      <input type="text" class="form-control" name="name" value="<?php echo e($session->name); ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Start Date</label>
                      <input type="date" class="form-control" name="start_date" value="<?php echo e($session->start_date); ?>"
                        required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">End Date</label>
                      <input type="date" class="form-control" name="end_date" value="<?php echo e($session->end_date); ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Status</label>
                      <select class="form-select" name="status">
                        <option value="active" <?php echo e($session->status === 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e($session->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
          crossorigin="anonymous"></script>
        <script src="<?php echo e(asset('js/session.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('number');
    const dropdownItems = document.querySelectorAll('.dropdown-item[data-value]');
    
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedValue = this.getAttribute('data-value');
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('per_page', selectedValue);
            
            const currentSearch = '<?php echo e($search); ?>';
            if (currentSearch) {
                newUrl.searchParams.set('search', currentSearch);
            }
            
            newUrl.searchParams.delete('page');
            window.location.href = newUrl.toString();
        });
    });

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
</body>

</html><?php /**PATH C:\Users\Priyanshi Rathore\Syn-2\resources\views/session/session.blade.php ENDPATH**/ ?>