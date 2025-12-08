<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Inquiry History Reports - Synthesis</title>
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Custom CSS -->
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }

    .main-container {
      padding: 20px;
      max-width: 100%;
      margin: 0 auto;
    }

    .page-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 25px 30px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .page-header h1 {
      font-size: 28px;
      font-weight: 600;
      margin: 0;
    }

    .filter-card {
      background: white;
      border-radius: 10px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .filter-card h5 {
      color: #667eea;
      font-weight: 600;
      margin-bottom: 20px;
      font-size: 18px;
    }

    .form-label {
      font-weight: 500;
      color: #495057;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .form-control, .form-select {
      border: 1px solid #dee2e6;
      border-radius: 6px;
      padding: 10px 15px;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 10px 25px;
      border-radius: 6px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-success {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      border: none;
      padding: 10px 25px;
      border-radius: 6px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    .btn-secondary {
      background: #6c757d;
      border: none;
      padding: 10px 25px;
      border-radius: 6px;
      font-weight: 500;
    }

    .table-card {
      background: white;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .table-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .entries-control {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-control {
      display: flex;
      align-items: center;
      gap: 10px;
      flex: 1;
      max-width: 400px;
    }

    .search-control input {
      flex: 1;
    }

    .table-responsive {
      margin-top: 20px;
      border-radius: 8px;
      overflow: hidden;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    .data-table thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .data-table thead th {
      padding: 15px;
      text-align: left;
      font-weight: 600;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .data-table tbody tr {
      border-bottom: 1px solid #e9ecef;
      transition: all 0.3s ease;
    }

    .data-table tbody tr:hover {
      background-color: #f8f9fa;
    }

    .data-table tbody td {
      padding: 15px;
      color: #495057;
    }

    .badge {
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 500;
    }

    .badge-primary {
      background-color: #667eea;
      color: white;
    }

    .badge-success {
      background-color: #28a745;
      color: white;
    }

    .badge-info {
      background-color: #17a2b8;
      color: white;
    }

    .badge-warning {
      background-color: #ffc107;
      color: #212529;
    }

    .btn-view {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      padding: 6px 15px;
      border-radius: 5px;
      font-size: 13px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-view:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
      color: white;
    }

    .pagination-wrapper {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .pagination {
      margin: 0;
    }

    .pagination .page-item.active .page-link {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-color: #667eea;
    }

    .pagination .page-link {
      color: #667eea;
      border: 1px solid #dee2e6;
      padding: 8px 15px;
      margin: 0 3px;
      border-radius: 5px;
    }

    .pagination .page-link:hover {
      background-color: #f8f9fa;
      border-color: #667eea;
    }

    .no-data {
      text-align: center;
      padding: 40px;
      color: #6c757d;
      font-size: 16px;
    }

    .loading {
      text-align: center;
      padding: 40px;
      color: #667eea;
      font-size: 16px;
    }

    .spinner-border {
      width: 3rem;
      height: 3rem;
      border-width: 0.3rem;
    }

    @media (max-width: 768px) {
      .table-controls {
        flex-direction: column;
        align-items: stretch;
      }

      .search-control {
        max-width: 100%;
      }

      .pagination-wrapper {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>

<body>
  <div class="main-container">
    <!-- Page Header -->
    <div class="page-header">
      <h1><i class="fas fa-history me-2"></i>Inquiry History Reports</h1>
    </div>

    <!-- Filters Card -->
    <div class="filter-card">
      <h5><i class="fas fa-filter me-2"></i>Filters</h5>
      <form id="filterForm">
        <div class="row">
          <!-- Session Filter -->
          <div class="col-md-3 mb-3">
            <label class="form-label">Session</label>
            <select class="form-select" name="session" id="sessionFilter">
              <option value="">All Sessions</option>
              <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($session); ?>"><?php echo e($session); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <!-- Role Filter -->
          <div class="col-md-3 mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" id="roleFilter">
              <option value="">All Roles</option>
              <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($role); ?>"><?php echo e($role); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <!-- Staff Filter -->
          <div class="col-md-3 mb-3">
            <label class="form-label">Staff Member</label>
            <select class="form-select" name="staff" id="staffFilter">
              <option value="">All Staff</option>
              <?php $__currentLoopData = $staffMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($staff->name); ?>"><?php echo e($staff->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <!-- Branch Filter -->
          <div class="col-md-3 mb-3">
            <label class="form-label">Branch</label>
            <select class="form-select" name="branch" id="branchFilter">
              <option value="">All Branches</option>
              <option value="Bikaner">Bikaner</option>
              <option value="Jaipur">Jaipur</option>
              <option value="Jodhpur">Jodhpur</option>
            </select>
          </div>

          <!-- Date From -->
          <div class="col-md-3 mb-3">
            <label class="form-label">Date From</label>
            <input type="date" class="form-control" name="from_date" id="dateFrom">
          </div>

          <!-- Date To -->
          <div class="col-md-3 mb-3">
            <label class="form-label">Date To</label>
            <input type="date" class="form-control" name="to_date" id="dateTo">
          </div>

          <!-- Buttons -->
          <div class="col-md-6 mb-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-search me-2"></i>Apply Filters
            </button>
            <button type="button" class="btn btn-secondary" id="resetBtn">
              <i class="fas fa-redo me-2"></i>Reset
            </button>
            <button type="button" class="btn btn-success" id="exportBtn">
              <i class="fas fa-file-csv me-2"></i>Export CSV
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Table Card -->
    <div class="table-card">
      <!-- Table Controls -->
      <div class="table-controls">
        <div class="entries-control">
          <label class="form-label mb-0">Show</label>
          <select class="form-select form-select-sm" id="entriesPerPage" style="width: 80px;">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <span class="form-label mb-0">entries</span>
        </div>

        <div class="search-control">
          <label class="form-label mb-0">Search:</label>
          <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search by name, contact...">
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>S.No</th>
              <th>Date</th>
              <th>Student Name</th>
              <th>Father Name</th>
              <th>Contact No</th>
              <th>Course Name</th>
              <th>Delivery Mode</th>
              <th>Staff</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <tr>
              <td colspan="10" class="loading">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2">Loading inquiries...</div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper">
        <div class="showing-info" id="showingInfo">
          Showing 0 to 0 of 0 entries
        </div>
        <nav>
          <ul class="pagination" id="pagination">
            <!-- Pagination will be dynamically generated -->
          </ul>
        </nav>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
      let currentPage = 1;
      let perPage = 10;
      let searchTerm = '';

      // CSRF Token Setup
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // Load data on page load
      loadInquiries();

      // Filter form submission
      $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        currentPage = 1;
        loadInquiries();
      });

      // Reset filters
      $('#resetBtn').on('click', function() {
        $('#filterForm')[0].reset();
        $('#searchInput').val('');
        searchTerm = '';
        currentPage = 1;
        loadInquiries();
      });

      // Entries per page change
      $('#entriesPerPage').on('change', function() {
        perPage = $(this).val();
        currentPage = 1;
        loadInquiries();
      });

      // Search input
      let searchTimeout;
      $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTerm = $(this).val();
        searchTimeout = setTimeout(function() {
          currentPage = 1;
          loadInquiries();
        }, 500);
      });

      // Export CSV
      $('#exportBtn').on('click', function() {
        const formData = $('#filterForm').serialize();
        window.location.href = "<?php echo e(route('reports.inquiry-history.export')); ?>?" + formData;
      });

      // Load inquiries function
      function loadInquiries() {
        const formData = $('#filterForm').serialize();
        
        $.ajax({
          url: "<?php echo e(route('reports.inquiry-history.getData')); ?>",
          method: 'GET',
          data: formData + '&page=' + currentPage + '&per_page=' + perPage + '&search=' + searchTerm,
          beforeSend: function() {
            $('#tableBody').html(`
              <tr>
                <td colspan="10" class="loading">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <div class="mt-2">Loading inquiries...</div>
                </td>
              </tr>
            `);
          },
          success: function(response) {
            if (response.data && response.data.length > 0) {
              renderTable(response.data, response);
              renderPagination(response);
            } else {
              $('#tableBody').html(`
                <tr>
                  <td colspan="10" class="no-data">
                    <i class="fas fa-inbox fa-3x mb-3" style="color: #dee2e6;"></i>
                    <div>No inquiries found</div>
                  </td>
                </tr>
              `);
              $('#showingInfo').text('Showing 0 to 0 of 0 entries');
              $('#pagination').html('');
            }
          },
          error: function(xhr) {
            console.error('Error:', xhr);
            $('#tableBody').html(`
              <tr>
                <td colspan="10" class="no-data text-danger">
                  <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                  <div>Error loading inquiries. Please try again.</div>
                </td>
              </tr>
            `);
          }
        });
      }

      // Render table function
      function renderTable(data, pagination) {
        let html = '';
        const startIndex = (pagination.current_page - 1) * pagination.per_page;
        
        data.forEach((inquiry, index) => {
          const serialNo = startIndex + index + 1;
          const date = inquiry.created_at ? formatDate(inquiry.created_at) : '-';
          const studentName = inquiry.student_name || '-';
          const fatherName = inquiry.father_name || '-';
          const contact = inquiry.father_contact_no || inquiry.student_contact_no || '-';
          const courseName = inquiry.course_name || '-';
          const deliveryMode = inquiry.delivery_mode || '-';
          const staff = inquiry.staff_name || '-';
          const status = inquiry.status || 'Pending';
          const statusColor = getStatusColor(status);
          const viewUrl = "<?php echo e(url('reports/inquiry-history')); ?>/" + inquiry._id + "/view";
          
          html += `
            <tr>
              <td>${serialNo}</td>
              <td>${date}</td>
              <td>${studentName}</td>
              <td>${fatherName}</td>
              <td>${contact}</td>
              <td>${courseName}</td>
              <td>${deliveryMode}</td>
              <td>${staff}</td>
              <td><span class="badge badge-${statusColor}">${status}</span></td>
              <td>
                <a href="${viewUrl}" class="btn-view">
                  <i class="fas fa-eye me-1"></i>View
                </a>
              </td>
            </tr>
          `;
        });
        
        $('#tableBody').html(html);
        
        // Update showing info
        const from = pagination.from || 0;
        const to = pagination.to || 0;
        const total = pagination.total || 0;
        $('#showingInfo').text(`Showing ${from} to ${to} of ${total} entries`);
      }

      // Render pagination function
      function renderPagination(pagination) {
        let html = '';
        const currentPage = pagination.current_page;
        const lastPage = pagination.last_page;
        
        // Previous button
        html += `
          <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
          </li>
        `;
        
        // Page numbers
        for (let i = 1; i <= lastPage; i++) {
          if (
            i === 1 || 
            i === lastPage || 
            (i >= currentPage - 2 && i <= currentPage + 2)
          ) {
            html += `
              <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
              </li>
            `;
          } else if (i === currentPage - 3 || i === currentPage + 3) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
          }
        }
        
        // Next button
        html += `
          <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
          </li>
        `;
        
        $('#pagination').html(html);
        
        // Bind pagination clicks
        $('.page-link').on('click', function(e) {
          e.preventDefault();
          if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
            currentPage = parseInt($(this).data('page'));
            loadInquiries();
          }
        });
      }

      // Helper functions
      function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        const options = { day: '2-digit', month: 'short', year: 'numeric' };
        return date.toLocaleDateString('en-GB', options);
      }

      function getStatusColor(status) {
        const statusLower = status.toLowerCase();
        if (statusLower.includes('complete') || statusLower.includes('success')) {
          return 'success';
        } else if (statusLower.includes('progress') || statusLower.includes('ongoing')) {
          return 'info';
        } else if (statusLower.includes('pending') || statusLower.includes('waiting')) {
          return 'warning';
        } else {
          return 'primary';
        }
      }
    });
  </script>
</body>

</html><?php /**PATH C:\Users\DELL\Syn-2\resources\views/reports/inquiry-history/index.blade.php ENDPATH**/ ?>