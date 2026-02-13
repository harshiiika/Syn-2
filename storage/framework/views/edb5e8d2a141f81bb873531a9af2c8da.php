

<!DOCTYPE html>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Branch Assignment</title>
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
          <h4>BRANCH MANAGEMENT</h4>
        </div>
        <div class="buttons">
          <!-- Button to open Add Branch modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalOne"
            id="add">
            Add Branch
          </button>
          <!-- Button to open Upload modal -->
          <button type="button" class="btn btn-success d-flex align-items-center justify-content-center"
            style="min-width: 140px; height: 38px;" data-bs-toggle="modal" data-bs-target="#uploadBranchModal" id="up">
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
            <form method="GET" action="<?php echo e(route('branches.index')); ?>" id="searchForm">
              <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
              <input type="search" name="search" placeholder="Search" class="search-holder"
                value="<?php echo e(request('search')); ?>" id="searchInput">
              <i class="fa-solid fa-magnifying-glass"></i>
            </form>
          </div>
        </div>
        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col" id="one">Serial No.</th>
              <th scope="col" id="one">Branch Name</th>
              <th scope="col" id="one">Branch City</th>
              <th scope="col" id="one">Branch Status</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
          <tbody>

            <tr>
            </tr>
          </tbody>
          <!-- Modal fillables where roles are assigned according to dept automatically -->

          <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td><?php echo e($branches->firstItem() + $index); ?></td>
              <td><?php echo e($branch->name); ?></td>
              <td><?php echo e($branch->city); ?></td>
              <td>
                <span class="badge <?php echo e(($branch->status ?? 'Active') === 'Deactivated' ? 'bg-danger' : 'bg-success'); ?>">
                  <?php echo e($branch->status ?? 'Active'); ?>

                </span>
              </td>

              <td>
                <div class="dropdown">
                  <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                    id="actionMenuButton<?php echo e($branch->_id); ?>" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="actionMenuButton<?php echo e($branch->_id); ?>">
                    <li>
                      <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo e($branch->_id); ?>">
                        View Details
                      </button>
                    </li>
                    <li>
                      <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($branch->_id); ?>">
                        Edit Details
                      </button>
                    </li>
                    <li>
                      <form method="POST" action="<?php echo e(route('branches.toggleStatus', $branch->_id)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item">
                          <?php echo e(($branch->status ?? 'Active') === 'Active' ? 'Deactivate' : 'Reactivate'); ?>

                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="5" class="text-center">
                <?php if(request('search')): ?>
                  No branches found matching "<?php echo e(request('search')); ?>"
                <?php else: ?>
                  No branches available
                <?php endif; ?>
              </td>
            </tr>
          <?php endif; ?>

        </table>

        <!-- Here options modals are present. -->

        <!-- View Modal -->


        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="modal fade" id="viewModal<?php echo e($branch->_id); ?>" tabindex="-1"
            data-bs-target="#viewModal<?php echo e($branch->_id); ?>" aria-labelledby="viewModalLabel<?php echo e($branch->_id); ?>"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="viewModalLabel<?php echo e($branch->_id); ?>">Branch Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Branch Name</label>
                    <input type="text" class="form-control" value="<?php echo e($branch->name); ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Branch City</label>
                    <input type="text" class="form-control" value="<?php echo e($branch->city); ?>" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Edit Modal -->
        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="modal fade" id="editModal<?php echo e($branch->_id); ?>" tabindex="-1"
            aria-labelledby="editModalLabel<?php echo e($branch->_id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <form method="POST" action="<?php echo e(route('branches.update', $branch->_id)); ?>">
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('PUT'); ?>
                  <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?php echo e($branch->_id); ?>">Edit Branch Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Branch Name</label>
                      <input type="text" class="form-control" name="name" value="<?php echo e($branch->name); ?>" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Branch City</label>
                      <input type="text" class="form-control" name="city" value="<?php echo e($branch->city); ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </div>
      <div class="footer">
        <div class="left-footer">
          <p>Showing <?php echo e($branches->firstItem() ?? 0); ?> to <?php echo e($branches->lastItem() ?? 0); ?> of <?php echo e($branches->total()); ?>

            entries
            <?php if(request('search')): ?>
              <span class="text-muted">(filtered from <?php echo e(\App\Models\Master\Branch::count()); ?> total entries)</span>
            <?php endif; ?>
          </p>
        </div>
        <div class="right-footer">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              
              <?php if($branches->onFirstPage()): ?>
                <li class="page-item disabled">
                  <span class="page-link" id="pg1">Previous</span>
                </li>
              <?php else: ?>
                <li class="page-item">
                  <a class="page-link" href="<?php echo e($branches->previousPageUrl()); ?>" id="pg1">Previous</a>
                </li>
              <?php endif; ?>

              
              <?php
                $start = max($branches->currentPage() - 2, 1);
                $end = min($start + 4, $branches->lastPage());
                $start = max($end - 4, 1);
              ?>

              <?php if($start > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="<?php echo e($branches->url(1)); ?>">1</a>
                </li>
                <?php if($start > 2): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
              <?php endif; ?>

              <?php for($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?php echo e($branches->currentPage() == $i ? 'active' : ''); ?>">
                  <a class="page-link" href="<?php echo e($branches->url($i)); ?>" id="pg<?php echo e($i); ?>"><?php echo e($i); ?></a>
                </li>
              <?php endfor; ?>

              <?php if($end < $branches->lastPage()): ?>
                <?php if($end < $branches->lastPage() - 1): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
                <li class="page-item">
                  <a class="page-link" href="<?php echo e($branches->url($branches->lastPage())); ?>"><?php echo e($branches->lastPage()); ?></a>
                </li>
              <?php endif; ?>

              
              <?php if($branches->hasMorePages()): ?>
                <li class="page-item">
                  <a class="page-link" href="<?php echo e($branches->nextPageUrl()); ?>" id="pg4">Next</a>
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

<!-- Add Branch Modal -->
<div class="modal fade" id="exampleModalOne" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Branch</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo e(route('branches.add')); ?>" id="addBranchForm">
          <?php echo csrf_field(); ?>
          <div class="mb-3">
            <label for="branch-name" class="form-label">Branch Name</label>
            <input type="text" name="name" class="form-control" id="branch-name"
              placeholder="Enter Branch Name" required>
          </div>
          <div class="mb-3">
            <label for="branch-city" class="form-label">City</label>
            <input type="text" name="city" class="form-control" id="branch-city"
              placeholder="Enter Branch City" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Upload Branch Modal -->
<div class="modal fade" id="uploadBranchModal" tabindex="-1" aria-labelledby="uploadBranchModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #28a745; color: white;">
        <h5 class="modal-title" id="uploadBranchModalLabel">Upload Branches</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold">Step 1: Download Sample File</label>
          <p class="text-muted small">Get a pre-formatted Excel file with dummy data to understand the required format.</p>
          <a href="<?php echo e(route('branches.downloadSample')); ?>" class="btn btn-warning w-100" style="background-color: rgb(224, 83, 1);">
            <i class="fa-solid fa-download"></i> Download Sample File
          </a>
        </div>

        <hr>

        <div class="mb-3">
          <label class="form-label fw-bold">Step 2: Upload Your File</label>
          <p class="text-muted small">Select the edited Excel file to import branches in bulk.</p>
          
          <form id="uploadBranchForm" action="<?php echo e(route('branches.import')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="mb-3">
              <label for="importBranchFile" class="form-label">Select File</label>
              <input type="file" id="importBranchFile" name="import_file" class="form-control" 
                accept=".xlsx,.xls,.csv" required>
              <small class="form-text text-muted d-block mt-2">
                Supported formats: Excel (.xlsx, .xls) or CSV. Max size: 2MB
              </small>
            </div>

            <div id="branchFilePreview" class="alert alert-light d-none" style="border: 1px solid #ddd;">
              <strong>File Selected:</strong>
              <div id="branchPreviewText"></div>
            </div>

            <button type="submit" class="btn btn-success w-100" id="uploadBranchBtn">
              <i class="fa-solid fa-upload"></i> Import Branches
            </button>
          </form>
        </div>

        <hr>

        <div class="alert alert-secondary" role="alert">
          <strong>Format Guide:</strong>
          <ul class="mb-0 mt-2 small">
            <li><strong>Branch Name:</strong> Full name of the branch (e.g., Synthesis Bikaner Main)</li>
            <li><strong>City:</strong> City where the branch is located (e.g., Bikaner, Jaipur)</li>
            <li><strong>Status:</strong> active or inactive (optional, defaults to active)</li>
          </ul>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('Page loaded - checking modals...');
  
  // Check if modals exist
  const uploadModal = document.getElementById('uploadBranchModal');
  const addModal = document.getElementById('exampleModalOne');
  
  console.log('Upload Modal exists:', !!uploadModal);
  console.log('Add Modal exists:', !!addModal);
  
  //Clean up backdrop on modal hide
  if (uploadModal) {
    uploadModal.addEventListener('hidden.bs.modal', function () {
      // Remove all modal backdrops
      const backdrops = document.querySelectorAll('.modal-backdrop');
      backdrops.forEach(backdrop => backdrop.remove());
      
      // Reset body classes
      document.body.classList.remove('modal-open');
      document.body.style.overflow = '';
      document.body.style.paddingRight = '';
      
      console.log('Upload modal closed - backdrop cleaned');
    });
  }
  
  if (addModal) {
    addModal.addEventListener('hidden.bs.modal', function () {
      // Remove all modal backdrops
      const backdrops = document.querySelectorAll('.modal-backdrop');
      backdrops.forEach(backdrop => backdrop.remove());
      
      // Reset body classes
      document.body.classList.remove('modal-open');
      document.body.style.overflow = '';
      document.body.style.paddingRight = '';
      
      console.log('Add modal closed - backdrop cleaned');
    });
  }
  
  // Manual modal trigger for upload button
  const uploadBtn = document.getElementById('up');
  if (uploadBtn && uploadModal) {
    uploadBtn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      console.log('Upload button clicked - opening modal');
      
      const modalInstance = new bootstrap.Modal(uploadModal, {
        backdrop: true, // Changed from 'static' to allow clicking outside to close
        keyboard: true
      });
      modalInstance.show();
    });
  } else {
    console.error('Upload button or modal not found!');
  }
  
  // File preview functionality
  const fileInput = document.getElementById('importBranchFile');
  if (fileInput) {
    fileInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      const preview = document.getElementById('branchFilePreview');
      const previewText = document.getElementById('branchPreviewText');
      
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

  // Auto-submit search form on input (with debounce)
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    let searchTimeout;
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        document.getElementById('searchForm').submit();
      }, 500);
    });
  }

  // Handle entries dropdown
  const dropdownItems = document.querySelectorAll('.line .dropdown-menu .dropdown-item');
  dropdownItems.forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      const perPage = this.textContent.trim();
      const currentSearch = new URLSearchParams(window.location.search).get('search') || '';
      
      let url = '<?php echo e(route("branches.index")); ?>?per_page=' + perPage;
      if (currentSearch) {
        url += '&search=' + encodeURIComponent(currentSearch);
      }
      
      window.location.href = url;
    });
  });

  // Auto-dismiss alerts
  const alerts = document.querySelectorAll('.alert-dismissible');
  alerts.forEach(alert => {
    setTimeout(() => {
      const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
      if (bsAlert) {
        bsAlert.close();
      }
    }, 5000);
  });
  
  // Global backdrop cleanup (backup safety measure)
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
      e.target.remove();
      document.body.classList.remove('modal-open');
      document.body.style.overflow = '';
      document.body.style.paddingRight = '';
      console.log('Backup: Removed orphaned backdrop on click');
    }
  });
});
</script>

</html><?php /**PATH C:\Users\DELL\Syn-2\resources\views/master/branch/branch.blade.php ENDPATH**/ ?>