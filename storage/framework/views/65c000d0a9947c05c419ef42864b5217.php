

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Batches</title>
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
          <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>""> <i class=" fa-solid fa-user"></i>Profile</a>
          </li>
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log In</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="main-container">
    <!-- Left Sidebar -->
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
          <li><a class="item" href="<?php echo e(route('test_series.index')); ?>"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
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
          <h4>BATCH ASSIGNMENT</h4>
        </div>
        <div class="buttons">
          <!-- Button to open Add Batch modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalOne"
            id="add">
            Create Batch
          </button>

          <button type="button" class="btn btn-success d-flex align-items-center justify-content-center"
            style="min-width: 140px; height: 38px;" data-bs-toggle="modal" data-bs-target="#uploadBatchModal" id="up">
            <i class="fa-solid fa-upload me-1"></i> Upload
          </button>
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
            <form method="GET" action="<?php echo e(route('batches.index')); ?>" id="searchForm">
              <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
              <input type="search" name="search" placeholder="Search by batch code, course, or class"
                class="search-holder" value="<?php echo e(request('search')); ?>" id="searchInput">
              <i class="fa-solid fa-magnifying-glass"></i>
            </form>
          </div>
        </div>
        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col" id="one">Serial No.</th>
              <th scope="col" id="one">Batch Code</th>
              <th scope="col" id="one">Class</th>
              <th scope="col" id="one">Course</th>
              <th scope="col" id="one">Course Type</th>
              <th scope="col" id="one">Delivery Mode</th>
              <th scope="col" id="one">Medium</th>
              <th scope="col" id="one">Shift</th>
              <th scope="col" id="one">Status</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
<tbody>
  <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
      <td><?php echo e($batches->firstItem() + $index); ?></td>
      <td><?php echo e($batch->batch_id ?? '—'); ?></td>
      <td><?php echo e($batch->class ?? '—'); ?></td>
      
      <!--  ED: Display course name properly -->
      <td>
        <?php if(!empty($batch->course)): ?>
          <?php echo e($batch->course); ?>

        <?php elseif($batch->courseRelation): ?>
          <?php echo e($batch->courseRelation->course_name ?? '—'); ?>

        <?php else: ?>
          —
        <?php endif; ?>
      </td>
      
      <td><?php echo e($batch->course_type ?? '—'); ?></td>
      <td><?php echo e($batch->mode ?? $batch->delivery_mode ?? '—'); ?></td>
      <td><?php echo e($batch->medium ?? '—'); ?></td>
      <td><?php echo e($batch->shift ?? '—'); ?></td>
      <td>
        <span class="badge <?php echo e($batch->status === 'Inactive' ? 'bg-danger' : 'bg-success'); ?>">
          <?php echo e($batch->status ?? 'Active'); ?>

        </span>
      </td>
      <td>
        <div class="dropdown">
          <button class="btn btn-sm btn-outline-secondary" type="button" 
                  id="dropdownMenu<?php echo e($loop->index); ?>"
                  data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow" 
              aria-labelledby="dropdownMenu<?php echo e($loop->index); ?>">
            <li>
              <a class="dropdown-item" href="#" data-bs-toggle="modal"
                 data-bs-target="#viewBatchModal<?php echo e($batch->_id); ?>">
                View Details
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="#" data-bs-toggle="modal"
                 data-bs-target="#editBatchModal<?php echo e($batch->_id); ?>">
                Edit Details
              </a>
            </li>
            <li>
              <form method="POST" action="<?php echo e(route('batches.toggleStatus', ['id' => $batch->_id])); ?>" class="d-inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="dropdown-item <?php echo e($batch->status === 'Active' ? 'text-danger' : 'text-success'); ?>">
                  <?php echo e($batch->status === 'Active' ? 'Deactivate' : 'Reactivate'); ?>

                </button>
              </form>
            </li>
          </ul>
        </div>
      </td>
    </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
      <td colspan="10" class="text-center">No batches found.</td>
    </tr>
  <?php endif; ?>
</tbody>
        </table>

        <!-- Here options modals are present. -->
        <!-- View Modal -->
        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="modal fade" id="viewBatchModal<?php echo e($batch->_id); ?>" tabindex="-1"
            aria-labelledby="viewBatchModalLabel<?php echo e($batch->_id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="viewBatchModalLabel<?php echo e($batch->_id); ?>">Batch Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Batch Code</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->batch_id ?? '—'); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Course</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->course ?? '—'); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Course Type</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->course_type ?? '—'); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Branch Name</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->branch_name ?? '—'); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->start_date ?? '—'); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Delivery Mode</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->mode ?? '—'); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Medium</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->medium ?? '—'); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Shift</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->shift ?? '—'); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Installment Date 2</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->installment_date_2 ?? 'Not Set'); ?>"
                      readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Installment Date 3</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->installment_date_3 ?? 'Not Set'); ?>"
                      readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <input type="text" class="form-control" value="<?php echo e($batch->status ?? 'Active'); ?>" readonly>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Edit Batch Modal -->
        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="modal fade" id="editBatchModal<?php echo e($batch->_id); ?>" tabindex="-1"
            aria-labelledby="editBatchModalLabel<?php echo e($batch->_id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <form method="POST" action="<?php echo e(route('batches.update', $batch->_id)); ?>">
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('PUT'); ?>
                  <div class="modal-header">
                    <h5 class="modal-title" id="editBatchModalLabel<?php echo e($batch->_id); ?>">Edit Batch Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">

                    <!-- Batch Code -->
                    <div class="mb-3">
                      <label class="form-label">Batch Code</label>
                      <input type="text" class="form-control" name="batch_id" value="<?php echo e($batch->batch_id ?? ''); ?>"
                        required>
                    </div>

                    <!-- Course -->
                    <div class="mb-3">
                      <label class="form-label">Course</label>
                      <select class="form-select" name="course" required>
                        <option value="Anthesis 11th NEET" <?php echo e(($batch->course ?? '') == 'Anthesis 11th NEET' ? 'selected' : ''); ?>>Anthesis 11th NEET</option>
                        <option value="Momentum 12th NEET" <?php echo e(($batch->course ?? '') == 'Momentum 12th NEET' ? 'selected' : ''); ?>>Momentum 12th NEET</option>
                        <option value="Dynamic Target NEET" <?php echo e(($batch->course ?? '') == 'Dynamic Target NEET' ? 'selected' : ''); ?>>Dynamic Target NEET</option>
                        <option value="Impulse 11th IIT" <?php echo e(($batch->course ?? '') == 'Impulse 11th IIT' ? 'selected' : ''); ?>>Impulse 11th IIT</option>
                        <option value="Intensity 12th IIT" <?php echo e(($batch->course ?? '') == 'Intensity 12th IIT' ? 'selected' : ''); ?>>Intensity 12th IIT</option>
                        <option value="Thurst Target IIT" <?php echo e(($batch->course ?? '') == 'Thurst Target IIT' ? 'selected' : ''); ?>>Thurst Target IIT</option>
                        <option value="Seedling 10th" <?php echo e(($batch->course ?? '') == 'Seedling 10th' ? 'selected' : ''); ?>>
                          Seedling 10th</option>
                        <option value="Plumule 9th" <?php echo e(($batch->course ?? '') == 'Plumule 9th' ? 'selected' : ''); ?>>Plumule
                          9th</option>
                        <option value="Radicle 8th" <?php echo e(($batch->course ?? '') == 'Radicle 8th' ? 'selected' : ''); ?>>Radicle
                          8th</option>
                        <option value="Nucleus 7th" <?php echo e(($batch->course ?? '') == 'Nucleus 7th' ? 'selected' : ''); ?>>Nucleus
                          7th</option>
                        <option value="Atom 6th" <?php echo e(($batch->course ?? '') == 'Atom 6th' ? 'selected' : ''); ?>>Atom 6th
                        </option>
                      </select>
                    </div>

                    <!-- Course Type -->
                    <div class="mb-3">
                      <label class="form-label">Course Type</label>
                      <select class="form-select" name="course_type" required>
                        <option value="Pre-Medical" <?php echo e(($batch->course_type ?? '') == 'Pre-Medical' ? 'selected' : ''); ?>>
                          Pre-Medical</option>
                        <option value="Pre-Engineering" <?php echo e(($batch->course_type ?? '') == 'Pre-Engineering' ? 'selected' : ''); ?>>Pre-Engineering</option>
                        <option value="Pre-Foundation" <?php echo e(($batch->course_type ?? '') == 'Pre-Foundation' ? 'selected' : ''); ?>>Pre-Foundation</option>
                      </select>
                    </div>

                    <!-- Branch Name -->
                    <div class="mb-3">
                      <label class="form-label">Branch Name</label>
                      <select class="form-select" name="branch_name" required>
                        <option value="Bikaner" <?php echo e(($batch->branch_name ?? '') == 'Bikaner' ? 'selected' : ''); ?>>Bikaner
                        </option>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-3">
                      <label class="form-label">Start Date</label>
                      <input type="date" class="form-control" name="start_date" value="<?php echo e($batch->start_date ?? ''); ?>"
                        required>
                    </div>

                    <!-- Delivery Mode -->
                    <div class="mb-3">
                      <label class="form-label">Delivery Mode</label>
                      <select class="form-select" name="mode" required>
                        <option value="Distance Learning" <?php echo e(($batch->mode ?? '') == 'Distance Learning' ? 'selected' : ''); ?>>Distance Learning</option>
                        <option value="Online" <?php echo e(($batch->mode ?? '') == 'Online' ? 'selected' : ''); ?>>Online</option>
                        <option value="Offline" <?php echo e(($batch->mode ?? '') == 'Offline' ? 'selected' : ''); ?>>Offline</option>
                      </select>
                    </div>

                    <!-- Medium -->
                    <div class="mb-3">
                      <label class="form-label">Medium</label>
                      <select class="form-select" name="medium" required>
                        <option value="English" <?php echo e(($batch->medium ?? '') == 'English' ? 'selected' : ''); ?>>English</option>
                        <option value="Hindi" <?php echo e(($batch->medium ?? '') == 'Hindi' ? 'selected' : ''); ?>>Hindi</option>
                      </select>
                    </div>

                    <!-- Shift -->
                    <div class="mb-3">
                      <label class="form-label">Shift</label>
                      <select class="form-select" name="shift" required>
                        <option value="Evening" <?php echo e(($batch->shift ?? '') == 'Evening' ? 'selected' : ''); ?>>Evening</option>
                        <option value="Morning" <?php echo e(($batch->shift ?? '') == 'Morning' ? 'selected' : ''); ?>>Morning</option>
                      </select>
                    </div>

                    <!-- Installment Date 2 -->
                    <div class="mb-3">
                      <label class="form-label">Installment Date 2</label>
                      <input type="date" class="form-control" name="installment_date_2"
                        value="<?php echo e($batch->installment_date_2 ?? ''); ?>">
                    </div>

                    <!-- Installment Date 3 -->
                    <div class="mb-3">
                      <label class="form-label">Installment Date 3</label>
                      <input type="date" class="form-control" name="installment_date_3"
                        value="<?php echo e($batch->installment_date_3 ?? ''); ?>">
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                      <label class="form-label">Status</label>
                      <select class="form-select" name="status">
                        <option value="Active" <?php echo e(($batch->status ?? 'Active') == 'Active' ? 'selected' : ''); ?>>Active
                        </option>
                        <option value="Inactive" <?php echo e(($batch->status ?? '') == 'Inactive' ? 'selected' : ''); ?>>Inactive
                        </option>
                      </select>
                    </div>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Batch</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Add Batch Modal -->
        <div class="modal fade" id="exampleModalOne" tabindex="-1" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content" id="content-one">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create Batch</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form method="POST" action="<?php echo e(route('batches.add')); ?>" id="createBatchForm">
                  <?php echo csrf_field(); ?>

                  <!-- Course Dropdown - This will auto-fill Class Name & Course Type -->
                  <div class="mb-3">
                    <label for="course" class="form-label">Course <span class="text-danger">*</span></label>
                    <select class="form-select" name="course" id="courseSelect" required>
                      <option selected disabled>Select Course</option>
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

                  <!-- Auto-filled fields (Read-only) -->
                  <div class="mb-3">
                    <label class="form-label">Class Name</label>
                    <input type="text" class="form-control bg-light" id="classNameDisplay">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Course Type</label>
                    <input type="text" class="form-control bg-light" id="courseTypeDisplay">
                  </div>

                  <!-- Batch Code -->
                  <div class="mb-3">
                    <label for="batch_id" class="form-label">Batch Code <span class="text-danger">*</span></label>
                    <input type="text" name="batch_id" class="form-control" placeholder="e.g., 20T1, 19L1" required>
                  </div>

                  <!-- Branch Name -->
                  <div class="mb-3">
                    <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                    <select class="form-select" name="branch_name" required>
                      <option selected disabled>Select Branch</option>
                      <option value="Bikaner">Bikaner</option>
                    </select>
                  </div>

                  <!-- Start Date -->
                  <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" required>
                  </div>

                  <!-- Delivery Mode -->
                  <div class="mb-3">
                    <label for="mode" class="form-label">Delivery Mode <span class="text-danger">*</span></label>
                    <select class="form-select" name="mode" required>
                      <option selected disabled>Select Delivery Mode</option>
                      <option value="Offline">Offline</option>
                      <option value="Online">Online</option>
                    </select>
                  </div>

                  <!-- Medium -->
                  <div class="mb-3">
                    <label for="medium" class="form-label">Medium <span class="text-danger">*</span></label>
                    <select class="form-select" name="medium" required>
                      <option selected disabled>Select Medium</option>
                      <option value="English">English</option>
                      <option value="Hindi">Hindi</option>
                    </select>
                  </div>

                  <!-- Shift -->
                  <div class="mb-3">
                    <label for="shift" class="form-label">Shift <span class="text-danger">*</span></label>
                    <select class="form-select" name="shift" required>
                      <option selected disabled>Select Shift</option>
                      <option value="Morning">Morning</option>
                      <option value="Evening">Evening</option>
                    </select>
                  </div>

                  <!-- Installment Dates -->
                  <div class="mb-3">
                    <label class="form-label fw-bold">Installment Dates</label>
                  </div>

                  <div class="mb-3">
                    <label for="installment_date_2" class="form-label">Installment Date 2</label>
                    <input type="date" name="installment_date_2" class="form-control">
                  </div>

                  <div class="mb-3">
                    <label for="installment_date_3" class="form-label">Installment Date 3</label>
                    <input type="date" name="installment_date_3" class="form-control">
                  </div>

                  <!-- Status -->
                  <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status">
                      <option value="Active" selected>Active</option>
                      <option value="Inactive">Inactive</option>
                    </select>
                  </div>

                  <div class="modal-footer" id="footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="doneBtn" class="btn btn-primary">Create</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Upload Batch Modal -->
        <div class="modal fade" id="uploadBatchModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color: #ed5b00ff; color: white;">
                <h5 class="modal-title">Upload Batches</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                  aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- Export Current Data Section -->
                <div class="mb-3">
                  <label class="form-label fw-bold">Export Current Data</label>
                  <p class="text-muted small">Download all current batch data as Excel file.</p>
                  <a href="<?php echo e(route('batches.export')); ?>?search=<?php echo e(request('search')); ?>&per_page=<?php echo e(request('per_page', 10)); ?>"
                    class="btn btn-info w-100" style="background-color: #ed5b00ff; color: white;">
                    <i class="fa-solid fa-download"></i> Download Current Batches
                  </a>
                </div>
                <hr>
                <!-- Step 2: Upload File -->
                <div class="mb-3">
                  <label class="form-label fw-bold">Step 2: Upload Your File</label>
                  <p class="text-muted small">Select the edited Excel file to import batches in bulk.</p>

                  <form id="uploadBatchForm" action="<?php echo e(route('batches.import')); ?>" method="POST"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                      <input type="file" id="importBatchFile" name="import_file" class="form-control"
                        accept=".xlsx,.xls,.csv" required>
                      <small class="form-text text-muted d-block mt-2">
                        Supported formats: Excel (.xlsx, .xls) or CSV. Max size: 2MB
                      </small>
                    </div>

                    <div id="batchFilePreview" class="alert alert-light d-none" style="border: 1px solid #ddd;">
                      <strong>File Selected:</strong>
                      <div id="batchPreviewText"></div>
                    </div>

                    <button type="submit" class="btn btn-success w-100" id="uploadBatchBtn">
                      <i class="fa-solid fa-upload"></i> Import Batches
                    </button>
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="footer">
          <div class="left-footer">
            <p>Showing <?php echo e($batches->firstItem() ?? 0); ?> to <?php echo e($batches->lastItem() ?? 0); ?> of <?php echo e($batches->total()); ?>

              entries
              <?php if(request('search')): ?>
                <span class="text-muted">(filtered from <?php echo e(\App\Models\Master\Batch::count()); ?> total entries)</span>
              <?php endif; ?>
            </p>
          </div>
          <div class="right-footer">
            <nav aria-label="Page navigation example" id="bottom">
              <ul class="pagination" id="pagination">
                
                <?php if($batches->onFirstPage()): ?>
                  <li class="page-item disabled">
                    <span class="page-link" id="pg1">Previous</span>
                  </li>
                <?php else: ?>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo e($batches->previousPageUrl()); ?>" id="pg1">Previous</a>
                  </li>
                <?php endif; ?>

                
                <?php
                  $start = max($batches->currentPage() - 2, 1);
                  $end = min($start + 4, $batches->lastPage());
                  $start = max($end - 4, 1);
                ?>

                <?php if($start > 1): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo e($batches->url(1)); ?>">1</a>
                  </li>
                  <?php if($start > 2): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                <?php endif; ?>

                <?php for($i = $start; $i <= $end; $i++): ?>
                  <li class="page-item <?php echo e($batches->currentPage() == $i ? 'active' : ''); ?>">
                    <a class="page-link" href="<?php echo e($batches->url($i)); ?>"><?php echo e($i); ?></a>
                  </li>
                <?php endfor; ?>

                <?php if($end < $batches->lastPage()): ?>
                  <?php if($end < $batches->lastPage() - 1): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo e($batches->url($batches->lastPage())); ?>"><?php echo e($batches->lastPage()); ?></a>
                  </li>
                <?php endif; ?>

                
                <?php if($batches->hasMorePages()): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo e($batches->nextPageUrl()); ?>" id="pg4">Next</a>
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
  </div>
  </div>
  </div>
  </div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Auto-fill class name and course type based on selected course
  document.getElementById('courseSelect').addEventListener('change', function () {
    const courseMapping = {
      'Anthesis 11th NEET': { class: '11th (XI)', type: 'Pre-Medical' },
      'Momentum 12th NEET': { class: '12th (XII)', type: 'Pre-Medical' },
      'Dynamic Target NEET': { class: 'Target (XII +)', type: 'Pre-Medical' },
      'Impulse 11th IIT': { class: '11th (XI)', type: 'Pre-Engineering' },
      'Intensity 12th IIT': { class: '12th (XII)', type: 'Pre-Engineering' },
      'Thrust Target IIT': { class: 'Target (XII +)', type: 'Pre-Engineering' },
      'Seedling 10th': { class: '10th (X)', type: 'Pre-Foundation' },
      'Plumule 9th': { class: '9th (IX)', type: 'Pre-Foundation' },
      'Radicle 8th': { class: '8th (VIII)', type: 'Pre-Foundation' },
      'Nucleus 7th': { class: '7th (VII)', type: 'Pre-Foundation' },
      'Atom 6th': { class: '6th (VI)', type: 'Pre-Foundation' }
    };

    const selectedCourse = this.value;
    const courseData = courseMapping[selectedCourse];

    if (courseData) {
      document.getElementById('classNameDisplay').value = courseData.class;
      document.getElementById('courseTypeDisplay').value = courseData.type;
    }
  });

  // <!-- AJAX Script: Handles dynamic user addition without page reload -->
  // AJAX for dynamic batch addition without page reload
  $('#createBatchForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    $('.text-danger').text(''); // Clear previous errors

    // AJAX POST request to add batch
    $.ajax({
      url: "<?php echo e(route('batches.add')); ?>",
      method: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        // Close the modal
        $('#exampleModalOne').modal('hide');

        // Reset form
        $('#createBatchForm')[0].reset();

        // Force page reload to show new batch
        window.location.href = "<?php echo e(route('batches.index')); ?>";
      },
      error: function (xhr) {
        if (xhr.status === 422) {
          // Display validation errors
          const errors = xhr.responseJSON.errors;
          for (let field in errors) {
            $('#error-' + field).text(errors[field][0]);
          }
        } else {
          alert('An error occurred. Please try again.');
        }
      }
    });
  });

  // Auto-fill class name and course type based on selected course
  document.getElementById('courseSelect').addEventListener('change', function () {
    const courseMapping = {
      'Anthesis 11th NEET': { class: '11th (XI)', type: 'Pre-Medical' },
      'Momentum 12th NEET': { class: '12th (XII)', type: 'Pre-Medical' },
      'Dynamic Target NEET': { class: 'Target (XII +)', type: 'Pre-Medical' },
      'Impulse 11th IIT': { class: '11th (XI)', type: 'Pre-Engineering' },
      'Intensity 12th IIT': { class: '12th (XII)', type: 'Pre-Engineering' },
      'Thrust Target IIT': { class: 'Target (XII +)', type: 'Pre-Engineering' },
      'Seedling 10th': { class: '10th (X)', type: 'Pre-Foundation' },
      'Plumule 9th': { class: '9th (IX)', type: 'Pre-Foundation' },
      'Radicle 8th': { class: '8th (VIII)', type: 'Pre-Foundation' },
      'Nucleus 7th': { class: '7th (VII)', type: 'Pre-Foundation' },
      'Atom 6th': { class: '6th (VI)', type: 'Pre-Foundation' }
    };

    const selectedCourse = this.value;
    const courseData = courseMapping[selectedCourse];

    if (courseData) {
      document.getElementById('classNameDisplay').value = courseData.class;
      document.getElementById('courseTypeDisplay').value = courseData.type;
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    // File preview functionality
    const fileInput = document.getElementById('importBatchFile');
    const preview = document.getElementById('batchFilePreview');
    const previewText = document.getElementById('batchPreviewText');

    if (fileInput) {
      fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];

        if (file) {
          preview.classList.remove('d-none');
          const fileSize = (file.size / 1024).toFixed(2);
          const fileIcon = file.name.endsWith('.csv') ? 'fa-file-csv' : 'fa-file-excel';

          previewText.innerHTML = `
          <div class="d-flex align-items-center">
            <i class="fa-solid ${fileIcon} text-success me-2 fs-4"></i>
            <div>
              <div><strong>${file.name}</strong></div>
              <small class="text-muted">${fileSize} KB</small>
            </div>
          </div>
        `;
        } else {
          preview.classList.add('d-none');
        }
      });
    }

    // Reset form when modal closes
    const uploadModal = document.getElementById('uploadBatchModal');
    if (uploadModal) {
      uploadModal.addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('uploadBatchForm');
        if (form) {
          form.reset();
          preview.classList.add('d-none');
        }
      });
    }

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.flash-container .alert');
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.classList.add('auto-dismiss');
      }, 100);

      setTimeout(() => {
        const container = alert.closest('.flash-container');
        if (container) {
          container.remove();
        }
      }, 5500);
    });
  });
</script>

</html><?php /**PATH C:\Users\Priyanshi Rathore\Syn-2\resources\views/master/batch/index.blade.php ENDPATH**/ ?>