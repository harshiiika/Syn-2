

<!DOCTYPE html>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee</title>
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
    <button class="btn btn-secondary dropdown-toggle" 
            id="toggle-btn" 
            type="button" 
            data-bs-toggle="dropdown"
            aria-expanded="false">
        <i class="fa-solid fa-user"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="toggle-btn">
        <li>
            <a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>">
                <i class="fa-solid fa-user me-2"></i>Profile
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="dropdown-item text-danger">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Log Out
                </button>
            </form>
        </li>
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
          <li><a class="item" href="<?php echo e(route('smstudents.index')); ?>"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
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

    <!-- Success and Error Messages -->
<div class="container-fluid px-4 pt-3">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <strong>Success!</strong> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            <strong>Error!</strong> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('import_errors')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <strong>Import Issues:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = session('import_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

      <div class="top">
        <div class="top-text">
          <h4>EMPLOYEE</h4>
        </div>
        <div class="buttons">
          <!-- Button to open Add Employee modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalOne"
            id="add">
            Add Employee
          </button>

              <button type="button" 
            class="btn btn-success d-flex align-items-center justify-content-center" 
            style="min-width: 140px; height: 38px;" 
            data-bs-toggle="modal" 
            data-bs-target="#uploadBranchModal"
            id="up">
      <i class="fa-solid fa-upload me-1"></i> Upload
    </button>

        </div>
      </div>
      <div class="whole">
        <!-- 
<?php if(session('success')): ?>
  <div class="alert alert-success alert-dismissible fade show mt-3">
      <?php echo e(session('success')); ?>

      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>


<?php if(session('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show mt-3">
      <?php echo e(session('error')); ?>

      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>


<?php if(session('import_errors')): ?>
  <div class="alert alert-warning alert-dismissible fade show mt-3 shadow-sm border-0">
      <div class="d-flex justify-content-between align-items-center">
          <strong>
              ⚠ Import Issues (<?php echo e(count(session('import_errors'))); ?>)
          </strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>

      <div class="mt-2 small" style="max-height: 220px; overflow-y: auto;">
          <ul class="mb-0 ps-3">
              <?php $__currentLoopData = session('import_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li><?php echo e($error); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
      </div>
  </div>
<?php endif; ?> -->

        <!-- Table controls: entries dropdown and search -->
        <!-- <div class="dd">
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
                  <form method="GET" action="<?php echo e(route('user.emp.emp')); ?>" id="searchForm">
        <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
           <input type="search" 
               name="search" 
               placeholder="Search" 
               class="search-holder" 
               value="<?php echo e(request('search')); ?>"
               id="searchInput">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div> -->

<?php if(session('error')): ?>
  <div class="alert alert-danger mt-3">
      <?php echo e(session('error')); ?>

  </div>
<?php endif; ?>

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
    <form method="GET" action="<?php echo e(route('user.emp.emp')); ?>" id="searchForm">
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
        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col" id="one">Serial No.</th>
              <th scope="col" id="one">Name</th>
              <th scope="col" id="one">Email</th>
              <th scope="col" id="one">Mobile No.</th>
              <th scope="col" id="one">Department</th>
              <th scope="col" id="one">Role</th>
              <th scope="col" id="one">Status</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
          <tbody>
          <!-- Modal fillables where roles are assigned according to dept automatically -->

         <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <tr>
    <td><?php echo e($index + 1); ?></td>
    <td><?php echo e($user->name); ?></td>
    <td><?php echo e($user->email); ?></td>
    <td><?php echo e($user->mobileNumber ?? '—'); ?></td>
    
    <!--Use the accessor properly -->
    <td>
      <?php
        $deptNames = $user->departmentNames ?? collect();
      ?>
      <?php echo e($deptNames->isNotEmpty() ? $deptNames->implode(', ') : '—'); ?>

    </td>
    
    <td>
      <?php
        $roleNames = $user->roleNames ?? collect();
      ?>
      <?php echo e($roleNames->isNotEmpty() ? $roleNames->implode(', ') : '—'); ?>

    </td>

    <td>
      <span class="badge <?php echo e($user->status === 'Deactivated' ? 'bg-danger' : 'bg-success'); ?>">
        <?php echo e($user->status ?? 'Active'); ?>

      </span>
    </td>

              <td>
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary" type="button" id="dropdownMenu<?php echo e($loop->index); ?>"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu<?php echo e($loop->index); ?>">
                    <li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal"
                        data-bs-target="#viewModal<?php echo e($user->_id); ?>">
                        View Details
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal"
                        data-bs-target="#editModal<?php echo e($user->_id); ?>">
                        Edit Details
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal"
                        data-bs-target="#passwordModal<?php echo e($user->_id); ?>">
                        Password Update
                      </a>
                    </li>
                    <li>
                      <form method="POST" action="<?php echo e(route('users.toggleStatus', $user->_id)); ?>" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item">
                          <!-- <i class="fas fa-toggle-<?php echo e($user->status === 'Active' ? 'off' : 'on'); ?> me-2"></i> -->
                          <?php echo e($user->status === 'Active' ? 'Deactivate' : 'Reactivate'); ?>

                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</tbody>
        </table>

        <!-- Here options modals are present. -->
        <!-- View Modal -->
<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="modal fade" id="viewModal<?php echo e($user->_id); ?>" tabindex="-1"
    aria-labelledby="viewModalLabel<?php echo e($user->_id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel<?php echo e($user->_id); ?>">Employee Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
          <!-- Profile Picture Display Section - THIS IS NEW! -->
          <div class="text-center mb-4">
          <div class="text-center mb-4">
  <?php
    $profilePicture = $user->profile_picture ?? null;
  ?>

  <?php if(!empty($profilePicture)): ?>
    <!-- User has uploaded profile picture -->
    <div style="margin-bottom: 1rem;">
      <img src="<?php echo e(asset('storage/' . $profilePicture)); ?>" 
           alt="<?php echo e($user->name); ?>" 
           class="rounded-circle"
           style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #007bff; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"
           onerror="console.error('Failed to load:', this.src); this.style.display='none'; document.getElementById('fallback_<?php echo e($user->_id); ?>').style.display='flex';">
      <!-- Fallback initial avatar -->
      <div id="fallback_<?php echo e($user->_id); ?>" class="rounded-circle d-none align-items-center justify-content-center mx-auto" 
           style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 4px solid #007bff; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <span style="font-size: 60px; color: white; font-weight: bold;">
          <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

        </span>
      </div>
    </div>
  <?php else: ?>
    <!-- Show initial-based avatar if no picture -->
    <div style="margin-bottom: 1rem;">
      <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto" 
           style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 4px solid #007bff; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <span style="font-size: 60px; color: white; font-weight: bold;">
          <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

        </span>
      </div>
    </div>
  <?php endif; ?>
  
  <h5 class="mt-3 mb-0"><?php echo e($user->name); ?></h5>
  <small class="text-muted"><?php echo e($user->email); ?></small>
</div>
            
            <h5 class="mt-3 mb-0"><?php echo e($user->name); ?></h5>
            <small class="text-muted"><?php echo e($user->email); ?></small>
          </div>
          <!-- End Profile Picture Section -->

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-muted small">MOBILE</label>
              <p class="mb-0"><?php echo e($user->mobileNumber ?? '—'); ?></p>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-muted small">ALTERNATE MOBILE</label>
              <p class="mb-0"><?php echo e($user->alternateNumber ?? '—'); ?></p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-muted small">BRANCH</label>
              <p class="mb-0"><?php echo e($user->branch ?? '—'); ?></p>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-muted small">DEPARTMENT</label>
              <p class="mb-0"><?php echo e($user->departmentNames ? $user->departmentNames->join(', ') : '—'); ?></p>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold text-muted small">ROLE</label>
            <p class="mb-0">
              <span class="badge bg-primary"><?php echo e($user->roleNames ? $user->roleNames->join(', ') : '—'); ?></span>
            </p>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold text-muted small">STATUS</label>
            <p class="mb-0">
              <span class="badge <?php echo e($user->status === 'Deactivated' ? 'bg-danger' : 'bg-success'); ?>">
                <?php echo e($user->status ?? 'Active'); ?>

              </span>
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Edit Modal -->
<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="modal fade" id="editModal<?php echo e($user->_id); ?>" tabindex="-1"
    aria-labelledby="editModalLabel<?php echo e($user->_id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <form method="POST" action="<?php echo e(route('users.update', $user->_id)); ?>" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel<?php echo e($user->_id); ?>">Edit Employee Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            
            <!-- Profile Picture Edit Section - THIS IS NEW! -->
            <div class="mb-4">
              <label for="edit_profile_picture<?php echo e($user->_id); ?>" class="form-label fw-bold">Profile Picture</label>
              <div class="text-center mb-3">
                <?php
                  $profilePicture = $user->profile_picture ?? null;
                  $hasProfilePicture = !empty($profilePicture) && \Storage::disk('public')->exists($profilePicture);
                ?>

                <?php if($hasProfilePicture): ?>
                  <!-- Display existing profile picture -->
                  <img id="editProfilePreview<?php echo e($user->_id); ?>" 
                       src="<?php echo e(asset('storage/' . $profilePicture)); ?>" 
                       alt="Profile Preview" 
                       class="rounded-circle"
                       style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #007bff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                       onerror="this.style.display='none'; document.getElementById('editProfilePreview<?php echo e($user->_id); ?>_fallback').style.display='flex';">
                  <!-- Fallback initial avatar -->
                  <div id="editProfilePreview<?php echo e($user->_id); ?>_fallback" 
                       class="rounded-circle d-none align-items-center justify-content-center mx-auto" 
                       style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 3px solid #007bff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <span style="font-size: 48px; color: white; font-weight: bold;">
                      <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                    </span>
                  </div>
                <?php else: ?>
                  <!-- Show initial-based avatar if no profile picture -->
                  <img id="editProfilePreview<?php echo e($user->_id); ?>" 
                       src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->name)); ?>&size=120&background=667eea&color=fff&bold=true" 
                       alt="Profile Preview" 
                       class="rounded-circle"
                       style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #007bff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <?php endif; ?>
              </div>
              
              <!-- File input for changing picture -->
              <input type="file" 
                     name="profile_picture" 
                     id="edit_profile_picture<?php echo e($user->_id); ?>"
                     class="form-control" 
                     accept="image/jpeg,image/jpg,image/png,image/gif"
                     onchange="previewImage(event, 'editProfilePreview<?php echo e($user->_id); ?>')">
              <small class="form-text text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Leave empty to keep current picture. Accepted: JPG, PNG, GIF (Max: 2MB)
              </small>
            </div>
            <!-- End Profile Picture Section -->

            <div class="mb-3">
              <label class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="name" value="<?php echo e($user->name); ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" name="email" value="<?php echo e($user->email); ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Mobile <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="mobileNumber" value="<?php echo e($user->mobileNumber ?? ''); ?>"
                pattern="[0-9]{10}" maxlength="10" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Alternate Mobile</label>
              <input type="text" class="form-control" name="alternateNumber"
                value="<?php echo e($user->alternateNumber ?? ''); ?>" pattern="[0-9]{10}" maxlength="10">
            </div>

            <div class="mb-3">
              <label class="form-label">Branch <span class="text-danger">*</span></label>
              <select class="form-select" name="branch" required>
                <option value="Bikaner" <?php echo e($user->branch == 'Bikaner' ? 'selected' : ''); ?>>Bikaner</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Department <span class="text-danger">*</span></label>
              <select class="form-select" name="department" required>
                <?php
                  $currentDepartment = $user->departmentNames->first() ?? '';
                ?>
                <option value="Front Office" <?php echo e($currentDepartment == 'Front Office' ? 'selected' : ''); ?>>Front Office</option>
                <option value="Back Office" <?php echo e($currentDepartment == 'Back Office' ? 'selected' : ''); ?>>Back Office</option>
                <option value="Office" <?php echo e($currentDepartment == 'Office' ? 'selected' : ''); ?>>Office</option>
                <option value="Test Management" <?php echo e($currentDepartment == 'Test Management' ? 'selected' : ''); ?>>Test Management</option>
                <option value="Admin" <?php echo e($currentDepartment == 'Admin' ? 'selected' : ''); ?>>Admin</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Current Role</label>
              <input type="text" class="form-control" value="<?php echo e($user->roleNames->join(', ') ?? '—'); ?>" readonly>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Update
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<!-- Password Update Modal-->
<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="modal fade" id="passwordModal<?php echo e($user->_id); ?>" tabindex="-1"
    aria-labelledby="passwordModalLabel<?php echo e($user->_id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <form method="POST" action="<?php echo e(route('users.password.update', $user->_id)); ?>"
          id="passwordForm<?php echo e($user->_id); ?>">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>
          <div class="modal-header">
            <h5 class="modal-title" id="passwordModalLabel<?php echo e($user->_id); ?>">Update Password for <?php echo e($user->name); ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            
            <!-- Display validation errors -->
            <div id="errorContainer<?php echo e($user->_id); ?>" style="display: none;" class="alert alert-danger">
              <ul id="errorList<?php echo e($user->_id); ?>" class="mb-0"></ul>
            </div>

            <!-- Current Password -->
            <div class="mb-3">
              <label class="form-label">Current Password <span class="text-danger">*</span></label>
              <input type="password" 
                     name="current_password" 
                     id="current_password<?php echo e($user->_id); ?>"
                     class="form-control"
                     placeholder="Enter current password" 
                     required>
              <span class="text-danger" id="error-current_password<?php echo e($user->_id); ?>"></span>
            </div>

            <!-- New Password -->
            <div class="mb-3">
              <label class="form-label">New Password <span class="text-danger">*</span></label>
              <input type="password" 
                     name="new_password" 
                     id="new_password<?php echo e($user->_id); ?>" 
                     class="form-control"
                     placeholder="Enter new password" 
                     minlength="8" 
                     required>
              <small class="form-text text-muted">Minimum 8 characters, must include uppercase, lowercase, and number</small>
              <span class="text-danger" id="error-new_password<?php echo e($user->_id); ?>"></span>
            </div>

            <!-- Confirm New Password -->
            <div class="mb-3">
              <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
              <input type="password" 
                     name="confirm_new_password" 
                     id="confirm_password<?php echo e($user->_id); ?>" 
                     class="form-control"
                     placeholder="Re-enter new password"
                     required>
              <span class="text-danger" id="password-match-error<?php echo e($user->_id); ?>"></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="submitBtn<?php echo e($user->_id); ?>">Update Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  <div class="left-footer">
    <p>Showing <?php echo e($users->firstItem() ?? 0); ?> to <?php echo e($users->lastItem() ?? 0); ?> of <?php echo e($users->total()); ?> entries
      <?php if(request('search')): ?>
        <span class="text-muted">(filtered from <?php echo e(\App\Models\User\User::count()); ?> total entries)</span>
      <?php endif; ?>
    </p>
  </div>
  <div class="right-footer">
    <nav aria-label="Page navigation example" id="bottom">
      <ul class="pagination" id="pagination">
        
        <?php if($users->onFirstPage()): ?>
          <li class="page-item disabled">
            <span class="page-link" id="pg1">Previous</span>
          </li>
        <?php else: ?>
          <li class="page-item">
            <a class="page-link" 
               href="<?php echo e($users->previousPageUrl()); ?>" 
               id="pg1">Previous</a>
          </li>
        <?php endif; ?>

        
        <?php
          $start = max($users->currentPage() - 2, 1);
          $end = min($start + 4, $users->lastPage());
          $start = max($end - 4, 1);
        ?>

        <?php if($start > 1): ?>
          <li class="page-item" id="pg2">
            <a class="page-link" href="<?php echo e($users->url(1)); ?>">1</a>
          </li>
          <?php if($start > 2): ?>
            <li class="page-item disabled">
              <span class="page-link">...</span>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <?php for($i = $start; $i <= $end; $i++): ?>
          <li class="page-item <?php echo e($users->currentPage() == $i ? 'active' : ''); ?>">
            <a class="page-link" 
               href="<?php echo e($users->url($i)); ?>"
               id="pg<?php echo e($i); ?>"><?php echo e($i); ?></a>
          </li>
        <?php endfor; ?>

        <?php if($end < $users->lastPage()): ?>
          <?php if($end < $users->lastPage() - 1): ?>
            <li class="page-item disabled">
              <span class="page-link">...</span>
            </li>
          <?php endif; ?>
          <li class="page-item">
            <a class="page-link" href="<?php echo e($users->url($users->lastPage())); ?>"><?php echo e($users->lastPage()); ?></a>
          </li>
        <?php endif; ?>

        
        <?php if($users->hasMorePages()): ?>
          <li class="page-item">
            <a class="page-link" 
               href="<?php echo e($users->nextPageUrl()); ?>" 
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
  <!-- Modal Form with fillables for add employee starts here -->

  <!-- Add Employee Modal -->

<div class="modal fade" id="exampleModalOne" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" id="content-one">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Employee</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo e(route('users.add')); ?>" id="addEmployeeForm" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          
          <!-- Show validation errors -->
          <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong><i class="fa-solid fa-circle-exclamation me-2"></i>Please fix the following errors:</strong>
              <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <!-- Profile Picture Upload Section -->
          <div class="mb-4">
            <label for="profile_picture" class="form-label fw-bold">Profile Picture</label>
            <div class="text-center mb-3">
              <div id="profilePreviewContainer">
                <img id="profilePreview" 
                     src="https://ui-avatars.com/api/?name=User&size=120&background=667eea&color=fff&bold=true" 
                     alt="Profile Preview" 
                     class="rounded-circle"
                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #007bff;">
              </div>
            </div>
            <input type="file" 
                   name="profile_picture" 
                   id="profile_picture"
                   class="form-control <?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   accept="image/jpeg,image/jpg,image/png,image/gif"
                   onchange="previewImage(event, 'profilePreview')">
            <small class="form-text text-muted">Accepted formats: JPG, JPEG, PNG, GIF (Max: 2MB)</small>
            <?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" 
                   name="name" 
                   class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   placeholder="Enter Your Name" 
                   value="<?php echo e(old('name')); ?>" 
                   required>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="mb-3">
            <label for="mobileNumber" class="form-label">Mobile No. <span class="text-danger">*</span></label>
            <input type="tel" 
                   name="mobileNumber" 
                   class="form-control <?php $__errorArgs = ['mobileNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   placeholder="Enter 10 digit mobile number"
                   pattern="[0-9]{10}" 
                   maxlength="10" 
                   value="<?php echo e(old('mobileNumber')); ?>" 
                   required>
            <small class="form-text text-muted">Enter exactly 10 digits</small>
            <?php $__errorArgs = ['mobileNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="mb-3">
            <label for="alternateNumber" class="form-label">Alternate Mobile No.</label>
            <input type="tel" 
                   name="alternateNumber" 
                   class="form-control <?php $__errorArgs = ['alternateNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Enter 10 digit alternate number" 
                   pattern="[0-9]{10}" 
                   maxlength="10" 
                   value="<?php echo e(old('alternateNumber')); ?>">
            <?php $__errorArgs = ['alternateNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" 
                   name="email" 
                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   placeholder="Enter Your Email" 
                   value="<?php echo e(old('email')); ?>" 
                   required>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="mb-3">
            <label for="branch" class="form-label">Select Branch <span class="text-danger">*</span></label>
            <select class="form-select <?php $__errorArgs = ['branch'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="branch" required>
              <option value="">Select Branch</option>
              <option value="Bikaner" <?php echo e(old('branch') == 'Bikaner' ? 'selected' : ''); ?>>Bikaner</option>
            </select>
            <?php $__errorArgs = ['branch'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="mb-3">
            <label for="department" class="form-label">Select Role <span class="text-danger">*</span></label>
            <select class="form-select <?php $__errorArgs = ['department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="department" required>
              <option value="">Select Role</option>
              <option value="Front Office" <?php echo e(old('department') == 'Front Office' ? 'selected' : ''); ?>>Front Office</option>
              <option value="Back Office" <?php echo e(old('department') == 'Back Office' ? 'selected' : ''); ?>>Back Office</option>
              <option value="Office" <?php echo e(old('department') == 'Office' ? 'selected' : ''); ?>>Office</option>
              <option value="Test Management" <?php echo e(old('department') == 'Test Management' ? 'selected' : ''); ?>>Test Management</option>
              <option value="Admin" <?php echo e(old('department') == 'Admin' ? 'selected' : ''); ?>>Admin</option>
            </select>
            <?php $__errorArgs = ['department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" 
                   name="password" 
                   id="password" 
                   class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   placeholder="Enter Password"
                   minlength="6" 
                   required>
            <small class="form-text text-muted">Minimum 6 characters</small>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" 
                   name="password_confirmation" 
                   id="password_confirmation" 
                   class="form-control" 
                   placeholder="Confirm Password" 
                   required>
          </div>

          <div class="modal-footer" id="footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="add">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

  <!-- Upload Modal -->
<div class="modal fade" id="uploadBranchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #ed5b00ff; color: white;">
        <h5 class="modal-title">Upload Employees</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- Export Current Data Section -->
      
        <div class="mb-3">
  <label class="form-label fw-bold">Export Current Data</label>
  <p class="text-muted small">Download all current employee data as Excel file.</p>

  <a href="<?php echo e(route('users.export')); ?>?search=<?php echo e(request('search')); ?>&per_page=<?php echo e(request('per_page', 10)); ?>"
     class="btn btn-success w-100"
     style="background-color: #ed5b00ff; border-color: #ed5b00ff;">
     
     <i class="fa-solid fa-download"></i> Download Current Employees
  </a>
</div>

<a href="<?php echo e(route('users.sample')); ?>" 
   class="btn btn-secondary w-100 mb-2">
   <i class="fa-solid fa-file"></i> Download Sample File
</a>

        <hr>

        <!-- Step 2: Upload File -->
        <div class="mb-3">
          <label class="form-label fw-bold">Upload Your File</label>          
          <form id="uploadEmployeeForm" action="<?php echo e(route('users.import')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="mb-3">
              <input type="file" id="importEmployeeFile" name="import_file" class="form-control" 
                accept=".xlsx,.xls,.csv" required>
            </div>

            <div id="employeeFilePreview" class="alert alert-light d-none" style="border: 1px solid #ddd;">
              <strong>File Selected:</strong>
              <div id="employeePreviewText"></div>
            </div>

            <button type="submit" class="btn btn-success w-100" id="uploadEmployeeBtn">
              <i class="fa-solid fa-upload"></i> Import Employees
            </button>
          </form>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
<!-- AJAX Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

<!--custom JS (must come after jQuery + Bootstrap) -->
<script src="<?php echo e(asset(path: 'js/emp.js')); ?>"></script>

<script>

  document.addEventListener('DOMContentLoaded', function() {
    <?php if($errors->any() && session('show_add_modal')): ?>
        var addModal = new bootstrap.Modal(document.getElementById('exampleModalOne'));
        addModal.show();
    <?php endif; ?>
});
document.addEventListener('DOMContentLoaded', function() {
  <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  (function() {
    const userId = '<?php echo e($user->_id); ?>';
    const form = document.getElementById('passwordForm' + userId);
    const currentPassword = document.getElementById('current_password' + userId);
    const newPassword = document.getElementById('new_password' + userId);
    const confirmPassword = document.getElementById('confirm_password' + userId);
    const submitBtn = document.getElementById('submitBtn' + userId);
    const matchError = document.getElementById('password-match-error' + userId);
    const newPasswordError = document.getElementById('error-new_password' + userId);
    const errorContainer = document.getElementById('errorContainer' + userId);
    const errorList = document.getElementById('errorList' + userId);

    // Real-time password strength validation
    if (newPassword) {
      newPassword.addEventListener('input', function() {
        const password = this.value;
        let errors = [];

        if (password.length > 0 && password.length < 8) {
          errors.push('Password must be at least 8 characters');
        }
        if (password.length > 0 && !/[a-z]/.test(password)) {
          errors.push('Must contain a lowercase letter');
        }
        if (password.length > 0 && !/[A-Z]/.test(password)) {
          errors.push('Must contain an uppercase letter');
        }
        if (password.length > 0 && !/\d/.test(password)) {
          errors.push('Must contain a number');
        }

        newPasswordError.textContent = errors.join(', ');
        
        // Also check if passwords match when typing in new password
        if (confirmPassword.value && password !== confirmPassword.value) {
          matchError.textContent = 'Passwords do not match';
        } else {
          matchError.textContent = '';
        }
      });
    }

    // Real-time password match validation
    if (confirmPassword) {
      confirmPassword.addEventListener('input', function() {
        if (newPassword.value !== this.value) {
          matchError.textContent = 'Passwords do not match';
        } else {
          matchError.textContent = '';
        }
      });
    }

    // Form submission validation
    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        matchError.textContent = '';
        newPasswordError.textContent = '';
        errorContainer.style.display = 'none';
        errorList.innerHTML = '';
        
        let isValid = true;
        let errors = [];

        // Validate current password
        if (!currentPassword.value) {
          errors.push('Current password is required');
          isValid = false;
        }

        // Validate new password
        if (!newPassword.value) {
          errors.push('New password is required');
          isValid = false;
        } else {
          if (newPassword.value.length < 8) {
            errors.push('New password must be at least 8 characters');
            isValid = false;
          }
          if (!/[a-z]/.test(newPassword.value)) {
            errors.push('New password must contain a lowercase letter');
            isValid = false;
          }
          if (!/[A-Z]/.test(newPassword.value)) {
            errors.push('New password must contain an uppercase letter');
            isValid = false;
          }
          if (!/\d/.test(newPassword.value)) {
            errors.push('New password must contain a number');
            isValid = false;
          }
          if (currentPassword.value === newPassword.value) {
            errors.push('New password must be different from current password');
            isValid = false;
          }
        }

        // Validate password confirmation
        if (!confirmPassword.value) {
          errors.push('Password confirmation is required');
          isValid = false;
        } else if (newPassword.value !== confirmPassword.value) {
          matchError.textContent = 'Passwords do not match';
          errors.push('Passwords do not match');
          isValid = false;
        }

        if (!isValid) {
          // Show errors
          errorList.innerHTML = errors.map(err => '<li>' + err + '</li>').join('');
          errorContainer.style.display = 'block';
          return false;
        }

        // If validation passes, submit the form
        submitBtn.disabled = true;
        submitBtn.textContent = 'Updating...';
        form.submit();
      });
    }

    // Reset form when modal is closed
    const modal = document.getElementById('passwordModal' + userId);
    if (modal) {
      modal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        matchError.textContent = '';
        newPasswordError.textContent = '';
        errorContainer.style.display = 'none';
        errorList.innerHTML = '';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update Password';
      });
    }
  })();
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
});

//import employee file preview
document.addEventListener('DOMContentLoaded', function() {
  const fileInput = document.getElementById('importEmployeeFile');
  const preview = document.getElementById('employeeFilePreview');
  const previewText = document.getElementById('employeePreviewText');
  
  if (fileInput) {
    fileInput.addEventListener('change', function(e) {
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
  const uploadModal = document.getElementById('uploadBranchModal');
  if (uploadModal) {
    uploadModal.addEventListener('hidden.bs.modal', function() {
      const form = document.getElementById('uploadEmployeeForm');
      if (form) {
        form.reset();
        preview.classList.add('d-none');
      }
    });
  }
});

//pfp
function previewImage(event, previewId) {
    const input = event.target;
    const preview = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
        // Validate file size (2MB = 2048KB)
        const fileSize = input.files[0].size / 1024; // Convert to KB
        if (fileSize > 2048) {
            alert('File size must be less than 2MB!');
            input.value = ''; // Clear the input
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(input.files[0].type)) {
            alert('Only JPG, JPEG, PNG, and GIF files are allowed!');
            input.value = ''; // Clear the input
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            
            // Hide fallback if it exists
            const fallback = document.getElementById(previewId + '_fallback');
            if (fallback) {
                fallback.style.display = 'none';
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

//show enteries per page
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
</html><?php /**PATH C:\Users\Priyanshi Rathore\Syn-2\resources\views/user/emp/emp.blade.php ENDPATH**/ ?>