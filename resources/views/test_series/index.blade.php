<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Test Series Management</title>
  <link rel="stylesheet" href="{{ asset('css/emp.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .test-master-card {
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 20px;
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }
    
    .test-master-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: linear-gradient(180deg, #fd550dff 0%, #ff7d3d 100%);
    }
    
    .test-master-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(253, 85, 13, 0.2);
      border-color: #fd550dff;
    }
    
    .test-master-header {
      font-size: 1.25rem;
      font-weight: 700;
      color: #212529;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .test-master-header i {
      color: #fd550dff;
      font-size: 1.5rem;
    }
    
    .test-master-title {
      color: #fd550dff;
      font-weight: 600;
      font-size: 1.1rem;
    }
    
    .test-counts-container {
      display: flex;
      gap: 12px;
      margin-top: 16px;
      flex-wrap: wrap;
    }
    
    .test-count {
      flex: 1;
      min-width: 140px;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      padding: 12px 16px;
      border-radius: 8px;
      border: 1px solid #dee2e6;
      transition: all 0.2s ease;
    }
    
    .test-count:hover {
      background: linear-gradient(135deg, #fff5f0 0%, #ffe5d9 100%);
      border-color: #fd550dff;
    }
    
    .test-count-label {
      color: #6c757d;
      font-weight: 500;
      font-size: 0.85rem;
      display: block;
      margin-bottom: 4px;
    }
    
    .test-count-value {
      color: #fd550dff;
      font-weight: 800;
      font-size: 1.5rem;
    }
    
    .search-container {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      margin-bottom: 24px;
    }
  </style>
</head>
<body>
  <!-- Flash Messages -->
  <div class="flash-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  <!-- Header -->
  <div class="header">
    <div class="logo">
      <img src="{{ asset('images/logo.png.jpg') }}" class="img" alt="Logo">
      <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="pfp">
      <div class="session">
        <h5>Session:</h5>
        <select>
          <option>2024-2025</option>
          <option selected>2025-2026</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <!-- Sidebar (include your sidebar partial here) -->
     <div class="left" id="sidebar">
      <div class="text" id="text">
        <h6>Admin</h6>
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
          <li><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i> Employee</a></li>     
          <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group" id="side-icon"></i> Batches Assignment</a></li>
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
          <li><a class="item" href="{{ route('courses.index') }}"><i class="fa-solid fa-book-open" id="side-icon"></i> Courses</a></li>
          <li><a class="item" href="{{ route('batches.index') }}"><i class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i> Batches</a></li>
          <li><a class="item" href="{{ route('master.scholarship.index') }}"><i class="fa-solid fa-graduation-cap" id="side-icon"></i> Scholarship</a></li>
          <li><a class="item" href="{{ route('fees.index') }}"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Master</a></li>
          <li><a class="item" href="{{ route('master.other_fees.index') }}"><i class="fa-solid fa-wallet" id="side-icon"></i> Other Fees Master</a></li>
          <li><a class="item" href="{{ route('branches.index') }}"><i class="fa-solid fa-diagram-project" id="side-icon"></i> Branch Management</a></li>
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
          <li><a class="item" href="{{ route('sessions.index') }}"><i class="fa-solid fa-calendar-day" id="side-icon"></i> Session</a></li>
          <li><a class="item" href="{{ route('calendar.index') }}"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Calendar</a></li>
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
          <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Inquiry Management</a></li>
          <li><a class="item" href="{{ route('student.student.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a></li>
          <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Fees Students</a></li>
          <li><a class="item active" href="{{ route('smstudents.index') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
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
          <li><a class="item" href="{{ route('fees.management.index') }}"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Collection</a></li>
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
          <li><a class="item" href="{{ route('attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Employee</a></li>
          <li><a class="item" href="{{ route('attendance.student.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Student</a></li>
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
          <li><a class="item" href="{{ route('units.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Units</a></li>
          <li><a class="item" href="{{ route('dispatch.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Dispatch Material</a></li>

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
          <li><a class="item" href="{{ route(name: 'test_series.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
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
          <li><a class="item" href="{{ route('reports.walkin.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Walk In</a></li>
          <li><a class="item" href="{{ route('reports.attendance.student.index') }}"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Attendance</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Test Series</a></li>
          <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry History</a></li>
          <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Onboard History</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
    </div>


    <!-- Main Content -->
    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
          <h4><i class="fas fa-book-open me-2"></i>TEST MASTER</h4>
        </div>
      </div>

      <div class="whole">
        <!-- Search Bar -->
        <div class="search-container">
          <div class="d-flex align-items-center gap-3">
            <i class="fas fa-search text-muted" style="font-size: 1.2rem;"></i>
            <input 
              type="search" 
              placeholder="Search courses..." 
              class="form-control border-0 shadow-none" 
              id="searchInput"
              style="font-size: 1.1rem;">
          </div>
        </div>

        <!-- Test Master Cards Grid -->
        <div class="row" id="testMasterGrid">
          @forelse($testMasters as $key => $master)
            <div class="col-md-6 col-lg-4 mb-4 test-master-item" data-searchable="{{ strtolower($master['name']) }}">
              <div class="test-master-card" onclick="window.location.href='{{ route('test_series.show', urlencode($master['name'])) }}'">
                <div class="test-master-header">
                  <i class="fas fa-graduation-cap"></i>
                  <div>
                    <div class="test-master-title">{{ $master['name'] }}</div>
                  </div>
                </div>
                
                <div class="test-counts-container">
                  <div class="test-count">
                    <span class="test-count-label">
                      <i class="fas fa-clipboard-list me-1"></i>Type1 TestSeries
                    </span>
                    <span class="test-count-value">{{ $master['type1_count'] }}</span>
                  </div>
                  <div class="test-count">
                    <span class="test-count-label">
                      <i class="fas fa-clipboard-check me-1"></i>Type2 TestSeries
                    </span>
                    <span class="test-count-value">{{ $master['type2_count'] }}</span>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-info text-center">
                <i class="fas fa-folder-open fa-3x mb-3"></i>
                <h4>No Test Series Found</h4>
                <p class="text-muted mb-4">Click on any course card above to start creating test series!</p>
              </div>
            </div>
          @endforelse
        </div>

        <!-- No Search Results -->
        <div id="noResultsMessage" class="alert alert-warning text-center" style="display: none;">
          <i class="fas fa-search me-2"></i>No test masters match your search.
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
          <nav>
            <ul class="pagination" id="pagination">
              <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset(path: 'js/emp.js') }}"></script>

  <script>
document.querySelector('#createTestSeriesModal form').addEventListener('submit', function(e) {
    const testType = document.getElementById('testSeriesType').value;
    const checkboxes = document.querySelectorAll('input[name="subjects[]"]:checked');
    
    if ((testType === 'Type1' || testType === 'Type2') && checkboxes.length === 0) {
        e.preventDefault();
        alert('Please select at least one subject');
        return false;
    }
});
    document.addEventListener('DOMContentLoaded', function () {
      // Sidebar toggle
      const toggleBtn = document.getElementById('toggleBtn');
      const sidebar = document.getElementById('sidebar');
      const right = document.getElementById('right');
      const text = document.getElementById('text');

      if (toggleBtn && sidebar && right && text) {
        toggleBtn.addEventListener('click', () => {
          sidebar.classList.toggle('collapsed');
          right.classList.toggle('expanded');
          text.classList.toggle('hidden');
        });
      }

      // Auto-hide flash messages
      setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
          alert.classList.remove('show');
          setTimeout(() => alert.remove(), 150);
        });
      }, 5000);

      // Search functionality
      const searchInput = document.getElementById('searchInput');
      const testMasterItems = document.querySelectorAll('.test-master-item');
      const noResultsMessage = document.getElementById('noResultsMessage');
      const testMasterGrid = document.getElementById('testMasterGrid');

      if (searchInput) {
        searchInput.addEventListener('input', function () {
          const searchTerm = this.value.toLowerCase().trim();
          let visibleCount = 0;

          testMasterItems.forEach(item => {
            const searchableText = item.dataset.searchable;
            if (searchableText.includes(searchTerm)) {
              item.style.display = '';
              visibleCount++;
            } else {
              item.style.display = 'none';
            }
          });

          if (testMasterItems.length > 0) {
            noResultsMessage.style.display = visibleCount === 0 ? 'block' : 'none';
          }
        });
      }
    });
  </script>
</body>
</html>