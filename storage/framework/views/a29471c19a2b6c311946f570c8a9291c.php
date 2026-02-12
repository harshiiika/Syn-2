

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Batches</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="<?php echo e(asset('css/batchesa.css')); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

</head>

<body>
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
          <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>"> <i class="fa-solid fa-user"></i>Profile</a></li>
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

    <!-- right side content -->
    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
          <h4>BATCHES ASSIGNMENT</h4>
        </div>
        <div class="buttons">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignBatchModal"
            id="add">
            Assign Batches
          </button>
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
    <form method="GET" action="<?php echo e(route('user.batches.batches')); ?>" id="searchForm">
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
        <!-- Table starts here -->

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

            <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
      <td><?php echo e($batches->firstItem() + $index); ?></td>
      <td><?php echo e($batch->batch_id ?? '—'); ?></td>
      <td><?php echo e($batch->start_date); ?></td>
      <td><?php echo e($batch->username ?? '—'); ?></td>
      <td><?php echo e($batch->shift ?? '—'); ?></td>
      <td>
        <span class="badge <?php echo e($batch->status === 'Deactivated' ? 'bg-danger' : 'bg-success'); ?>">
          <?php echo e($batch->status ?? 'Active'); ?>

        </span>
      </td>
      <td>
        <div class="dropdown">
          <button class="btn btn-sm btn-outline-secondary" type="button" 
                  id="dropdownMenu<?php echo e($loop->index); ?>" 
                  data-bs-toggle="dropdown" 
                  aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" 
              aria-labelledby="dropdownMenu<?php echo e($loop->index); ?>">
            <li>
              <form method="POST" action="<?php echo e(route('batches.toggleStatus', $batch->_id)); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="dropdown-item">
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
      <td colspan="7" class="text-center">No batch assignments found.</td>
    </tr>
  <?php endif; ?>
  </tbody>
</table>

      </div>
<div class="footer">
  <div class="left-footer">
    <p>Showing <?php echo e($batches->firstItem() ?? 0); ?> to <?php echo e($batches->lastItem() ?? 0); ?> of <?php echo e($batches->total()); ?> entries
      <?php if(request('search')): ?>
        <span class="text-muted">(filtered from <?php echo e(\App\Models\User\BatchAssignment::count()); ?> total entries)</span>
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
            <a class="page-link" 
               href="<?php echo e($batches->previousPageUrl()); ?>" 
               id="pg1">Previous</a>
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
            <a class="page-link" 
               href="<?php echo e($batches->url($i)); ?>"><?php echo e($i); ?></a>
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
            <a class="page-link" 
               href="<?php echo e($batches->nextPageUrl()); ?>" 
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

  <!-- Assign Batch Modal -->
<div class="modal fade" id="assignBatchModal" tabindex="-1" aria-labelledby="assignBatchModalLabel"
  data-bs-target="#assignBatchModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="content">
      <form method="POST" action="<?php echo e(route('batches.assign')); ?>" id="assignBatchForm">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
          <h1 class="modal-title fs-5">Assign Batches</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
          <!-- Dynamic Floor Incharge Dropdown -->
          <div class="mb-3">
            <label for="role" class="form-label">Select Floor Incharge</label>
            <div class="input-group">
              <select name="username" class="form-select" required>
                <option value="">Select Floor Incharge</option>
                <?php if(isset($floorIncharges)): ?>
                  <?php $__currentLoopData = $floorIncharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($employee->name); ?>"><?php echo e($employee->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                  <!-- Fallback to hardcoded if floorIncharges not passed -->
                  <option value="Floor Inch Evng (UG)">Floor Inch Evng (UG)</option>
                  <option value="Floor Inch Mrng(UG)">Floor Inch Mrng(UG)</option>
                  <option value="Preeti Acharya">Preeti Acharya</option>
                  <option value="Rajendra Kumar">Rajendra Kumar</option>
                  <option value="Omprakash Jyani">Omprakash Jyani</option>
                  <option value="Test Series Executive">Test Series Executive</option>
                <?php endif; ?>
              </select>
            </div>
          </div>

          <!-- Dynamic Batch Dropdown -->
<div class="mb-3">
  <label for="batch" class="form-label">Select Batch <span class="text-danger">*</span></label>
  <div class="input-group">
    <select name="batch_id" id="batch_id" class="form-select" required>
      <option value="">Select Batch</option>
      <?php if(isset($availableBatches) && count($availableBatches) > 0): ?>
        <?php $__currentLoopData = $availableBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($batch->batch_id); ?>" 
                  data-shift="<?php echo e($batch->shift); ?>"
                  data-start-date="<?php echo e($batch->start_date); ?>">
            <?php echo e($batch->batch_id); ?> - <?php echo e($batch->course); ?> (<?php echo e($batch->shift); ?> - <?php echo e($batch->medium); ?>)
          </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php else: ?>
        <option value="" disabled>No active batches available</option>
      <?php endif; ?>
    </select>
  </div>
  <small class="form-text text-muted">Shift will be automatically set based on selected batch</small>
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

<!-- JS Section starts here -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script src="<?php echo e(asset('js/emp.js')); ?>"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  console.log('Batch assignment page loaded');

  // Show shift when batch is selected (optional visual feedback)
  $('#batch_id').on('change', function() {
    const selectedOption = $(this).find('option:selected');
    const shift = selectedOption.data('shift');
    const startDate = selectedOption.data('start-date');
    
    if (shift && $('#shift_display').length) {
      $('#shift_display').val(shift);
      console.log('Selected batch shift:', shift, 'Start date:', startDate);
    }
  });

  // AJAX for batch assignment with page reload (cleanest solution)
  $('#assignBatchForm').on('submit', function (e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Assigning...');

    $.ajax({
      url: "<?php echo e(route('batches.assign')); ?>",
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (response) {
        console.log('Success:', response);
        
        if (response.status === 'success') {
          // Close modal
          $('#assignBatchModal').modal('hide');
          
          // Show success message
          alert('Batch assigned successfully!\nBatch: ' + response.batch.batch_id + '\nShift: ' + response.batch.shift);
          
          // Reload page to show new entry
          window.location.reload();
        }
      },
      error: function (xhr, status, error) {
        console.error('Error:', xhr.responseJSON);
        
        let errorMessage = 'An error occurred. Please try again.';
        
        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
          errorMessage = 'Validation errors:\n';
          for (let field in xhr.responseJSON.errors) {
            errorMessage += `- ${xhr.responseJSON.errors[field][0]}\n`;
          }
        } else if (xhr.status === 404) {
          errorMessage = 'Selected batch not found. Please refresh the page and try again.';
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        
        alert(errorMessage);
        submitBtn.prop('disabled', false).text(originalText);
      }
    });
  });

  // Reset modal when closed
  $('#assignBatchModal').on('hidden.bs.modal', function () {
    $('#assignBatchForm')[0].reset();
    if ($('#shift_display').length) {
      $('#shift_display').val('');
    }
  });
});

// jQuery ready function for batch assignment
$(document).ready(function() {
  console.log('Batch assignment page loaded');

  // Show shift when batch is selected (optional visual feedback)
  $('#batch_id').on('change', function() {
    const selectedOption = $(this).find('option:selected');
    const shift = selectedOption.data('shift');
    const startDate = selectedOption.data('start-date');
    
    if (shift && $('#shift_display').length) {
      $('#shift_display').val(shift);
      console.log('Selected batch shift:', shift, 'Start date:', startDate);
    }
  });

  // AJAX for batch assignment with page reload
  $('#assignBatchForm').on('submit', function (e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Assigning...');

    $.ajax({
      url: "<?php echo e(route('batches.assign')); ?>",
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (response) {
        console.log('Success:', response);
        
        if (response.status === 'success') {
          $('#assignBatchModal').modal('hide');
          alert('Batch assigned successfully!\nBatch: ' + response.batch.batch_id + '\nShift: ' + response.batch.shift);
          window.location.reload();
        }
      },
      error: function (xhr, status, error) {
        console.error('Error:', xhr.responseJSON);
        
        let errorMessage = 'An error occurred. Please try again.';
        
        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
          errorMessage = 'Validation errors:\n';
          for (let field in xhr.responseJSON.errors) {
            errorMessage += `- ${xhr.responseJSON.errors[field][0]}\n`;
          }
        } else if (xhr.status === 404) {
          errorMessage = 'Selected batch not found. Please refresh the page and try again.';
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        
        alert(errorMessage);
        submitBtn.prop('disabled', false).text(originalText);
      }
    });
  });

  // Reset modal when closed
  $('#assignBatchModal').on('hidden.bs.modal', function () {
    $('#assignBatchForm')[0].reset();
    if ($('#shift_display').length) {
      $('#shift_display').val('');
    }
  });
});

// Vanilla JavaScript for pagination and search (no Blade variables!)
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('number');
    const dropdownItems = document.querySelectorAll('.dropdown-item[data-value]');
    
    // Get URL parameters using JavaScript
    const urlParams = new URLSearchParams(window.location.search);
    const currentPerPage = urlParams.get('per_page') || '10';
    
    // Highlight current selection
    dropdownItems.forEach(item => {
        const itemValue = item.getAttribute('data-value');
        
        if (itemValue === currentPerPage) {
            item.classList.add('active');
            item.style.backgroundColor = '#ed5b00';
            item.style.color = 'white';
        }
        
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedValue = this.getAttribute('data-value');
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('per_page', selectedValue);
            
            // Get search from URL, not Blade
            const currentSearch = urlParams.get('search');
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
    const searchInput = document.getElementById('searchInput');
    
    if (searchIcon && searchForm) {
        searchIcon.style.cursor = 'pointer';
        searchIcon.addEventListener('click', function() {
            searchForm.submit();
        });
    }

    // Search input enter key handler
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
</html><?php /**PATH C:\Users\Priyanshi Rathore\Syn-2\resources\views/user/batches/batches.blade.php ENDPATH**/ ?>