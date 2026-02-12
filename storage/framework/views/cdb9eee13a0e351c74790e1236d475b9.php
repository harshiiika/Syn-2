


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Courses Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('css/emp.css')); ?>">
</head>

<body>
  <!-- Flash Messages -->
  <?php if(session('success')): ?>
    <div class="flash-container">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  <?php endif; ?>

  <?php if(session('error')): ?>
    <div class="flash-container">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  <?php endif; ?>

  <!-- Header -->
  <div class="header">
    <div class="logo">
      <img src="<?php echo e(asset('images/logo.png.jpg')); ?>" class="img" alt="Logo">
      <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="pfp">
      <div class="session">
        <h5>Session:</h5>
        <select>
          <option>2025-2026</option>
          <option>2024-2025</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>">
        <i class="fa-solid fa-user"></i> Profile
    </a></li>
    <li>
        <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
            <?php echo csrf_field(); ?>
            <button type="submit" class="dropdown-item" style="border: none; background: none; cursor: pointer; width: 100%; text-align: left;">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
            </button>
        </form>
    </li>
</ul>
      </div>
    </div>
  </div>

  <!-- Main Container -->
  <div class="main-container">
    <!-- Sidebar -->
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

  <!-- master -->
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
          <li><a class="item" href="<?php echo e(route('fees.index')); ?>"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees master</a></li>
          <li><a class="item" href="<?php echo e(route('master.other_fees.index')); ?>"><i class="fa-solid fa-wallet" id="side-icon"></i> Other Fees master</a></li>
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
          <li><a class="item" href="<?php echo e(route(name: 'test_series.index')); ?>"><i class="fa-solid fa-user" id="side-icon"></i>Test master</a></li>
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
  <div class="top d-flex justify-content-between align-items-center flex-wrap">
  <div class="top-text">
    <h4>Courses</h4>
  </div>

  <div class="d-flex gap-2 align-items-center">
    <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center" id="liveToastBtn" data-bs-toggle="modal"
    style="min-width: 140px; height: 38px;" 
      data-bs-target="#createCourseModal">
      Create Course
    </button>

  <button type="button" class="btn btn-success d-flex align-items-center justify-content-center" 
          style="min-width: 140px; height: 38px;" 
          data-bs-toggle="modal" data-bs-target="#uploadCourseModal">
    <i class="fa-solid fa-upload me-1"></i> Upload
  </button>
</div>

  <div class="toast-container end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-body" id="toast">
        <i class="fa-regular fa-circle-xmark" style="color: #ff0000;"></i>Cannot create course. Error occurred
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
  
  <div class="search mb-3">
    <form method="GET" action="<?php echo e(route('courses.index')); ?>" id="searchForm">
      <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
      <div class="input-group">
        <input 
          type="search" 
          name="search" 
          id="searchInput" 
          class="form-control" 
          placeholder="Search courses..." 
          value="<?php echo e(request('search')); ?>"
        >
        <button type="submit" class="btn btn-primary" style="background-color: #ff6600; color: white;">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
      </div>
    </form>
  </div>
</div>
    <table class="table table-hover" id="table">
      <thead>
        <tr>
          <th scope="col" id="one">Serial No.</th>
          <th scope="col" id="one">Course Name</th>
          <th scope="col" id="one">Course Type</th>
          <th scope="col" id="one">Class</th>
          <th scope="col" id="one">Course Code</th>
          <th scope="col" id="one">Status</th>
          <th scope="col" id="one">Action</th>
        </tr>
      </thead>
      <tbody id="coursesTable">
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $courseId = $course->_id ?? $course->id ?? null;
            if (is_object($courseId)) {
              $courseId = (string) $courseId;
            }
          ?>
          <tr>
            <td><?php echo e($index + 1); ?></td>
            <td><?php echo e($course->course_name); ?></td>
            <td><?php echo e(ucfirst($course->course_type)); ?></td>
            <td><?php echo e($course->class_name); ?></td>
            <td><?php echo e($course->course_code); ?></td>
            <td>
              <span class="badge <?php echo e($course->status === 'active' ? 'bg-success' : 'bg-danger'); ?>">
                <?php echo e(ucfirst($course->status)); ?>

              </span>
            </td>
            <td>
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                        type="button" 
                        id="actionDropdown<?php echo e($courseId); ?>" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown<?php echo e($courseId); ?>">
                  <li>
                    <button class="dropdown-item" 
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#viewCourseModal<?php echo e($courseId); ?>">
                            View Details
                    </button>
                  </li>

                  <li>
                    <button class="dropdown-item" 
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#editCourseModal<?php echo e($courseId); ?>">
                            Edit Details
                    </button>
                  </li>

                  <li><hr class="dropdown-divider"></li>

                  <li>
                    <form method="POST" action="<?php echo e(route('courses.destroy', $courseId)); ?>" class="d-inline w-100">
                      <?php echo csrf_field(); ?>
                      <?php echo method_field('DELETE'); ?>
                      <button type="submit" 
                              class="dropdown-item text-danger" 
                              onclick="return confirm('Are you sure you want to delete this course?')">
                              Delete Course
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
    <!-- showing entries -->
    <!-- Pagination Info & Controls -->
<div class="d-flex justify-content-between align-items-center mt-3">
  <div class="show" id="paginationInfo">
    Showing <span id="showingFrom">1</span> to <span id="showingTo"><?php echo e($courses->count()); ?></span> of <span id="totalEntries"><?php echo e($courses->total()); ?></span> entries
  </div>
  <nav>
    <ul class="pagination" id="pagination">
      <!-- Pagination buttons will be generated by JavaScript -->
    </ul>
  </nav>
</div>



      <!-- UPLOAD MODAL -->
<div class="modal fade" id="uploadCourseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #28a745; color: white;">
        <h5 class="modal-title">Upload</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold">Step 1: Download Sample File</label>
          <p class="text-muted small">Get a pre-formatted Excel file with dummy data to understand the required format.</p>
          <a href="<?php echo e(route('courses.downloadSample')); ?>" class="btn btn-warning w-100" style= "background-color: rgb(224, 83, 1);">
            <i class="fa-solid fa-download"></i> Download Sample File
          </a>
        </div>

        <hr>

        <div class="mb-3">
          <label class="form-label fw-bold">Step 2: Upload Your File</label>
          <p class="text-muted small">Select the edited Excel file to import courses in bulk.</p>
          
          <form id="uploadForm" action="<?php echo e(route('courses.import')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="mb-3">
              <input type="file" id="importFile" name="import_file" class="form-control" 
                accept=".xlsx,.xls,.csv" required>
              <small class="form-text text-muted d-block mt-2">
                Supported formats: Excel (.xlsx, .xls) or CSV. Max size: 2MB
              </small>
            </div>

            <div id="filePreview" class="alert alert-light d-none" style="border: 1px solid #ddd;">
              <strong>File Selected:</strong>
              <div id="previewText"></div>
            </div>

            <button type="submit" class="btn btn-success w-100" id="uploadBtn">
              <i class="fa-solid fa-upload"></i> Import Courses
            </button>
          </form>
        </div>

        <hr>

        <div class="alert alert-secondary" role="alert">
          <strong>Format Guide:</strong>
          <ul class="mb-0 mt-2 small">
            <li><strong>Course Name:</strong> Full name of the course</li>
            <li><strong>Course Type:</strong> Pre - Foundation | Pre - Medical | Pre - Engineering</li>
            <li><strong>Class Name:</strong> e.g., 11th (XI), 12th (XII)</li>
            <li><strong>Course Code:</strong> Unique code for the course</li>
            <li><strong>Subjects:</strong> Separate multiple subjects with semicolons (;)</li>
            <li><strong>Status:</strong> active or inactive</li>
          </ul>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <!-- Create Course Modal -->
    <div class="modal fade" id="createCourseModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="<?php echo e(route('courses.store')); ?>" method="POST" class="modal-content">
          <?php echo csrf_field(); ?>
          <div class="modal-header">
            <h5 class="modal-title">Create Course</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Course Name</label>
              <input type="text" name="course_name" class="form-control" required value="<?php echo e(old('course_name')); ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Course Type</label>
              <select name="course_type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="Pre - Foundation" <?php echo e(old('course_type') == 'Pre - Foundation' ? 'selected' : ''); ?>>Pre - Foundation</option>
                <option value="Pre - Medical" <?php echo e(old('course_type') == 'Pre - Medical' ? 'selected' : ''); ?>>Pre - Medical</option>
                <option value="Pre - Engineering" <?php echo e(old('course_type') == 'Pre - Engineering' ? 'selected' : ''); ?>>Pre - Engineering</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Class Name</label>
              <input type="text" name="class_name" class="form-control" required value="<?php echo e(old('class_name')); ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Course Code</label>
              <input type="text" name="course_code" class="form-control" required value="<?php echo e(old('course_code')); ?>">
            </div>

            <div class="mb-3">
     <label class="form-label">Subjects</label>
      <div class="subject-input-wrapper position-relative">
        <input type="text" 
              id="subjectInput" 
              class="form-control" 
              placeholder="Subject name"
              autocomplete="off">
        
        <!-- Autocomplete dropdown -->
        <div id="subjectSuggestions" 
            class="list-group position-absolute w-100" 
            style="z-index: 1050; max-height: 200px; overflow-y: auto; display: none;">
        </div>
        
        <div id="subjectTags" class="subject-tags mt-2"></div>
        <small class="form-text text-muted">
          Start typing to see suggestions. Press Enter or click to add.
        </small>
      </div>
    </div>

            <div class="mb-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-control" required>
                <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
              </select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" style="background-color: #ff6600; border-color: #ff6600;">Create Course</button>
          </div>
        </form>
      </div>
    </div>

    <!-- View Modal -->
    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $courseId = $course->_id ?? $course->id ?? null;
        if (is_object($courseId)) {
          $courseId = (string) $courseId;
        }
        $subjects = is_array($course->subjects) ? $course->subjects : json_decode($course->subjects, true) ?? [];
      ?>
      <div class="modal fade" id="viewCourseModal<?php echo e($courseId); ?>" tabindex="-1"
        aria-labelledby="viewCourseLabel<?php echo e($courseId); ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="viewCourseLabel<?php echo e($courseId); ?>">Course Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Course Name</label>
                <input type="text" class="form-control" value="<?php echo e($course->course_name); ?>" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Course Type</label>
                <input type="text" class="form-control" value="<?php echo e(ucfirst($course->course_type)); ?>" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Class Name</label>
                <input type="text" class="form-control" value="<?php echo e($course->class_name); ?>" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Course Code</label>
                <input type="text" class="form-control" value="<?php echo e($course->course_code); ?>" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Subjects</label>
                <div class="subject-tags-readonly">
                  <?php if(count($subjects) > 0): ?>
                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <span class="subject-tag-readonly"><?php echo e($subject); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php else: ?>
                    <span class="text-muted">No subjects assigned</span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" class="form-control" value="<?php echo e(ucfirst($course->status)); ?>" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Edit Modal -->
    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $courseId = $course->_id ?? $course->id ?? null;
        if (is_object($courseId)) {
          $courseId = (string) $courseId;
        }
        $subjects = is_array($course->subjects) ? $course->subjects : json_decode($course->subjects, true) ?? [];
      ?>
      <div class="modal fade" id="editCourseModal<?php echo e($courseId); ?>" tabindex="-1"
        aria-labelledby="editCourseLabel<?php echo e($courseId); ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <form method="POST" action="<?php echo e(route('courses.update', $courseId)); ?>">
              <?php echo csrf_field(); ?>
              <?php echo method_field('PUT'); ?>
              <div class="modal-header">
                <h5 class="modal-title" id="editCourseLabel<?php echo e($courseId); ?>">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Course Name</label>
                  <input type="text" class="form-control" name="course_name" value="<?php echo e($course->course_name); ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Course Type</label>
                  <select name="course_type" class="form-control" required>
                    <option value="Pre - Foundation" <?php echo e($course->course_type == 'Pre - Foundation' ? 'selected' : ''); ?>>Pre - Foundation</option>
                    <option value="Pre - Medical" <?php echo e($course->course_type == 'Pre - Medical' ? 'selected' : ''); ?>>Pre - Medical</option>
                    <option value="Pre - Engineering" <?php echo e($course->course_type == 'Pre - Engineering' ? 'selected' : ''); ?>>Pre - Engineering</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Class Name</label>
                  <input type="text" class="form-control" name="class_name" value="<?php echo e($course->class_name); ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Course Code</label>
                  <input type="text" class="form-control" name="course_code" value="<?php echo e($course->course_code); ?>" required>
                </div>
                <div class="mb-3">
  <label class="form-label">Subjects</label>
  <div class="subject-input-wrapper position-relative">
    <input type="text" 
           id="editSubjectInput<?php echo e($courseId); ?>" 
           class="form-control" 
           placeholder="Subject name"
           autocomplete="off">
    
    <div id="editSubjectSuggestions<?php echo e($courseId); ?>" 
         class="list-group position-absolute w-100" 
         style="z-index: 1050; max-height: 200px; overflow-y: auto; display: none;">
    </div>
    
    <div id="editSubjectTags<?php echo e($courseId); ?>" 
         class="subject-tags mt-2" 
         data-subjects='<?php echo json_encode($subjects, 15, 512) ?>' 
         data-course-id="<?php echo e($courseId); ?>">
    </div>
  </div>
</div>

                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select class="form-select" name="status">
                    <option value="active" <?php echo e($course->status === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e($course->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
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
  </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo e(asset('js/courses.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // SHOW ENTRIES DROPDOWN FUNCTIONALITY
    // ========================================
    const dropdownButton = document.getElementById('number');
    const dropdownItems = document.querySelectorAll('.dropdown-menu .dropdown-item[data-value]');
    const urlParams = new URLSearchParams(window.location.search);
    const currentPerPage = urlParams.get('per_page') || '10';
    
    // Update button text to show current selection
    if (dropdownButton) {
        dropdownButton.textContent = currentPerPage;
    }
    
    // Highlight current selection and handle clicks
    dropdownItems.forEach(item => {
        const itemValue = item.getAttribute('data-value');
        
        // Highlight active item
        if (itemValue === currentPerPage) {
            item.classList.add('active');
            item.style.backgroundColor = '#ff6600';
            item.style.color = 'white';
        }
        
        // Handle click event
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedValue = this.getAttribute('data-value');
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('per_page', selectedValue);
            
            // Preserve search parameter if exists
            const currentSearch = urlParams.get('search');
            if (currentSearch) {
                newUrl.searchParams.set('search', currentSearch);
            }
            
            // Reset to page 1 when changing entries per page
            newUrl.searchParams.delete('page');
            
            // Redirect to new URL
            window.location.href = newUrl.toString();
        });
    });

    // ========================================
    // SEARCH FUNCTIONALITY
    // ========================================
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput && searchForm) {
        // Submit on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
        
        // Optional: Auto-submit after typing stops (debounced)
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Uncomment if you want auto-search
                // searchForm.submit();
            }, 500);
        });
    }

    // ========================================
    // FILE UPLOAD PREVIEW
    // ========================================
    const importFile = document.getElementById('importFile');
    const filePreview = document.getElementById('filePreview');
    const previewText = document.getElementById('previewText');
    
    if (importFile) {
        importFile.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const fileName = file.name;
                const fileSize = (file.size / 1024).toFixed(2); // KB
                
                previewText.innerHTML = `
                    <i class="fa-solid fa-file-excel text-success"></i> 
                    <strong>${fileName}</strong> 
                    <span class="text-muted">(${fileSize} KB)</span>
                `;
                filePreview.classList.remove('d-none');
            } else {
                filePreview.classList.add('d-none');
            }
        });
    }

    // ========================================
    // AUTO-DISMISS FLASH MESSAGES
    // ========================================
    const flashMessages = document.querySelectorAll('.flash-container .alert');
    flashMessages.forEach(alert => {
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 5000); // Auto-dismiss after 5 seconds
    });

    console.log('Courses page initialized successfully');
});
</script>
</body>
</html>
<?php /**PATH C:\Users\Priyanshi Rathore\Syn-2\resources\views/master/courses/index.blade.php ENDPATH**/ ?>