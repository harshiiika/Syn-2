<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fees Management - Synthesis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/emp.css')}}">
</head>
<style>
    .right { padding: 0; background: #F5F5F5; }
    .page-header { padding: 12px 20px 10px 20px; background: #F5F5F5; margin-bottom: 0; }
    .page-title { font-size: 24px; color: #E66A2C; font-weight: 600; margin: 0; }
    .tabs-wrapper { background: white; overflow: visible; position: relative; margin: 0 15px 20px 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .btn-export { padding: 8px 18px; background: #28A745; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 500; font-size: 14px; transition: all 0.3s; position: absolute; right: 20px; top: 12px; z-index: 10; }
    .btn-export:hover { background: #218838; }
    .tabs-header { display: flex; border-bottom: 2px solid #DDD; background: #FAFAFA; padding: 0; }
    .tab-btn { padding: 11px 26px; border: none; background: transparent; cursor: pointer; font-weight: 500; font-size: 14px; color: #666; transition: all 0.3s; border-bottom: 3px solid transparent; border-radius: 0; }
    .tab-btn:hover { background: #F0F0F0; }
    .tab-btn.active { background: #E66A2C; color: white; border-bottom-color: #D85A1C; }
    .tab-panel { padding: 12px 15px; display: none; }
    .tab-panel.active { display: block; }
    .search-area { margin-bottom: 12px; display: flex; gap: 10px; flex-wrap: nowrap; align-items: flex-start; }
    .filter-group { display: flex; flex-direction: column; gap: 2px; }
    .search-box { flex: 1; min-width: 250px; padding: 8px 12px; border: 1px solid #DDD; border-radius: 4px; font-size: 14px; }
    .dropdown-filter { min-width: 180px; padding: 8px 12px; border: 1px solid #DDD; border-radius: 4px; font-size: 14px; background: white; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 30px; transition: all 0.3s; }
    .dropdown-filter:hover { border-color: #E66A2C; }
    .dropdown-filter:focus { outline: none; border-color: #E66A2C; box-shadow: 0 0 0 2px rgba(230, 106, 44, 0.1); }
    .dropdown-filter.error { border-color: #dc3545; }
    .error-message { color: #dc3545; font-size: 12px; margin-top: 2px; display: block; }
    .btn-search { padding: 8px 24px; background: #E66A2C; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; transition: all 0.3s; font-size: 14px; white-space: nowrap; align-self: flex-start; }
    .btn-search:hover { background: #D85A1C; }
    .results-table { width: 100%; border-collapse: collapse; margin-top: 0; background: white; }
    .results-table thead { background: #F5F5F5; }
    .results-table th { padding: 8px 10px; text-align: left; font-weight: 600; color: #E66A2C; border-bottom: 2px solid #DDD; font-size: 13px; white-space: nowrap; }
    .results-table td { padding: 8px 10px; border-bottom: 1px solid #DDD; font-size: 13px; }
    .results-table tbody tr:hover { background: #F9F9F9; }
    .action-btn { padding: 5px 10px; background: #E66A2C; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; white-space: nowrap; }
    .action-btn:hover { background: #D85A1C; }
    .date-filter-row { display: flex; align-items: center; gap: 10px; flex-wrap: nowrap; }
    .date-filter-row > div { display: flex; align-items: center; gap: 6px; }
    .date-filter-row label { font-size: 14px; font-weight: 500; white-space: nowrap; margin: 0; }
    .date-field { padding: 8px 12px; border: 1px solid #DDD; border-radius: 4px; font-size: 14px; min-width: 150px; }
    .empty-state { text-align: center; padding: 40px 20px; color: #999; }
    .empty-state i { font-size: 42px; color: #CCC; margin-bottom: 12px; display: block; }
    .empty-state p { margin: 0; font-size: 14px; }
    .loading-state { text-align: center; padding: 40px 20px; }
    .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #E66A2C; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    #collectFeesResults, #feeStatusResults, #transactionResults { overflow-x: auto; width: 100%; }
</style>
<body>
    <!-- Header Section -->
    <div class="header">
        <div class="logo">
            <img src="{{asset('images/logo.png.jpg')}}" class="img">
            <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
        </div>
        <div class="pfp">
            <div class="session">
                <h5>Session:</h5>
                <select>
                    <option>2025-2026</option>
                    <option>2024-2025</option>
                    <option>2023-2024</option>
                </select>
            </div>
            <i class="fa-solid fa-bell"></i>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{route('profile.index') }}"><i class="fa-solid fa-user"></i>Profile</a></li>
                    <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</a></li>
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

            <!-- Sidebar Accordion -->
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <!-- User Management -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne" id="accordion-button">
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
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo" id="accordion-button">
                            <i class="fa-solid fa-database" id="side-icon"></i> Master
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
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree" id="accordion-button">
                            <i class="fa-solid fa-calendar" id="side-icon"></i>Session Management
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
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour" id="accordion-button">
                            <i class="fa-solid fa-user-graduate" id="side-icon"></i>Student Management
                        </button>
                    </h2>
                    <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu" id="dropdown-body">
                                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Inquiry Management</a></li>
                                <li><a class="item" href="{{ route('student.student.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a></li>
                                <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Fees Students</a></li>
                                <li><a class="item" href="{{ route('smstudents.index') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Fees Management (ACTIVE) -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="true" aria-controls="flush-collapseFive" id="accordion-button">
                            <i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Management
                        </button>
                    </h2>
                    <div id="flush-collapseFive" class="accordion-collapse collapse show" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu" id="dropdown-body">
                                <li>
                                    <a class="item active" href="{{ route('fees.management.index') }}">
                                        <i class="fa-solid fa-money-bill-wave" id="side-icon"></i> Fee Collection
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Attendance Management -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix" id="accordion-button">
                            <i class="fa-solid fa-calendar-check" id="side-icon"></i> Attendance Management
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

                <!-- Study Material Collection -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven" id="accordion-button">
                            <i class="fa-solid fa-book-open" id="side-icon"></i> Study Material
                        </button>
                    </h2>
                    <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu" id="dropdown-body">
                                <li><a class="item" href="#"><i class="fa-solid fa-book" id="side-icon"></i>Units</a></li>
                                <li><a class="item" href="#"><i class="fa-solid fa-truck" id="side-icon"></i>Dispatch Material</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Test Series Management -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight" id="accordion-button">
                            <i class="fa-solid fa-chart-column" id="side-icon"></i> Test Series Management
                        </button>
                    </h2>
                    <div id="flush-collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu" id="dropdown-body">
                                <li><a class="item" href="{{ route('test_series.index') }}"><i class="fa-solid fa-file-lines" id="side-icon"></i>Test Master</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Reports -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine" id="accordion-button">
                            <i class="fa-solid fa-square-poll-horizontal" id="side-icon"></i> Reports
                        </button>
                    </h2>
                    <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu" id="dropdown-body">
                                <li><a class="item" href="#"><i class="fa-solid fa-person-walking" id="side-icon"></i>Walk In</a></li>
                                <li><a class="item" href="#"><i class="fa-solid fa-calendar-days" id="side-icon"></i> Attendance</a></li>
                                <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Test Series</a></li>
                                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry History</a></li>
                                <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Onboard History</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="right" id="right">
            <div class="page-header">
                <h2 class="page-title">Fees Management</h2>
            </div>

            <div class="tabs-wrapper">
                <button class="btn-export" onclick="exportPendingFees()">
                    <i class="fas fa-download"></i> Pending Fees List Export
                </button>

                <div class="tabs-header">
                    <button class="tab-btn active" data-tab="collect">Collect Fees</button>
                    <button class="tab-btn" data-tab="status">Fee Status</button>
                    <button class="tab-btn" data-tab="transaction">Daily Transaction</button>
                </div>

                <!-- Collect Fees Tab -->
                <div id="collect" class="tab-panel active">
                    <div class="search-area">
                        <input type="text" id="studentSearch" class="search-box" placeholder="Search by name or roll number">
                        <button class="btn-search" onclick="searchStudent()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>

                    <div id="collectFeesResults">
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <p>Enter a name or roll number to search</p>
                        </div>
                    </div>
                </div>

                <!-- Fee Status Tab -->
                <div id="status" class="tab-panel">
                    <div class="search-area">
                        <div class="filter-group">
                            <select id="courseSelect" class="dropdown-filter">
                                <option value="">Select Course</option>
                                <option value="intensity_12th_iit">Intensity 12th IIT</option>
                                <option value="plumule_9th">Plumule 9th</option>
                                <option value="radicle_8th">Radicle 8th</option>
                                <option value="anthesis_11th_neet">Anthesis 11th NEET</option>
                                <option value="dynamic_target_neet">Dynamic Target NEET</option>
                                <option value="thurst_target_iit">Thurst Target IIT</option>
                                <option value="seedling_10th">Seedling 10th</option>
                                <option value="nucleus_7th">Nucleus 7th</option>
                                <option value="momentum_12th_neet">Momentum 12th NEET</option>
                                <option value="impulse_11th_iit">Impulse 11th IIT</option>
                                <option value="atom_6th">Atom 6th</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <select id="batchSelect" class="dropdown-filter" disabled>
                                <option value="">Select batch</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <select id="feeStatusSelect" class="dropdown-filter">
                                <option value="">Select Fee Status</option>
                                <option value="All">All</option>
                                <option value="Paid">Paid</option>
                                <option value="2nd Installment due">2nd Installment due</option>
                                <option value="3rd Installment due">3rd Installment due</option>
                                <option value="Pending">Pending</option>
                            </select>
                            <span id="feeStatusError" class="error-message" style="display: none;">Status Is Required</span>
                        </div>
                        <button class="btn-search" onclick="searchByStatus()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>

                    <div id="feeStatusResults">
                        <div class="empty-state">
                            <i class="fas fa-filter"></i>
                            <p>Select filters and search to view fee status</p>
                        </div>
                    </div>
                </div>

                <!-- Daily Transaction Tab -->
                <div id="transaction" class="tab-panel">
                    <div class="search-area date-filter-row">
                        <div>
                            <label>From</label>
                            <input type="date" id="fromDate" class="date-field">
                        </div>
                        <div>
                            <label>To</label>
                            <input type="date" id="toDate" class="date-field">
                        </div>
                        <button class="btn-search" onclick="filterTransactions()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>

                    <div id="transactionResults">
                        <div class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <p>Select date range to view transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{asset('js/emp.js')}}"></script>

    <script>
        // Courses-Batches Mapping from Backend
        const coursesBatchesMapping = {!! json_encode($coursesBatchesMapping ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

        $(document).ready(function() {
            console.log('✅ Fees Management Page Loaded');
            console.log('📦 Courses-Batches Mapping:', coursesBatchesMapping);

            // Setup CSRF Token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Tab Switching Logic
            document.querySelectorAll('.tab-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Remove active from all tabs
                    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
                    
                    // Add active to clicked tab
                    this.classList.add('active');
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // ✅ INSTANT BATCH LOADING (Client-side)
            $('#courseSelect').on('change', function() {
                const courseId = $(this).val();
                const $batchSelect = $('#batchSelect');
                
                console.log('📚 Course selected:', courseId);
                
                if (courseId && coursesBatchesMapping[courseId]) {
                    const batches = coursesBatchesMapping[courseId];
                    console.log('✅ Found batches:', batches);
                    
                    let options = '<option value="">Select batch</option>';
                    
                    batches.forEach(batch => {
                        options += `<option value="${batch.id}">${batch.name} (${batch.mode})</option>`;
                    });
                    
                    $batchSelect.html(options).prop('disabled', false);
                } else if (courseId) {
                    $batchSelect.html('<option value="">No batches available</option>').prop('disabled', true);
                } else {
                    $batchSelect.html('<option value="">Select batch</option>').prop('disabled', true);
                }
            });

            // Real-time Search Input
            $('#studentSearch').on('input', function() {
                const searchTerm = $(this).val().trim();
                
                if (searchTerm.length >= 2) {
                    searchStudent();
                } else if (searchTerm.length === 0) {
                    $('#collectFeesResults').html(
                        '<div class="empty-state">' +
                        '<i class="fas fa-search"></i>' +
                        '<p>Enter a name or roll number to search</p>' +
                        '</div>'
                    );
                }
            });

            // Enter Key Support
            $('#studentSearch').keypress(function(e) {
                if (e.which === 13) {
                    searchStudent();
                }
            });

            // Hide error on fee status change
            $('#feeStatusSelect').on('change', function() {
                if ($(this).val()) {
                    $('#feeStatusError').hide();
                    $(this).removeClass('error');
                }
            });

            // Load all students on page load
            loadAllStudents();
        });

        // ✅ LOAD ALL STUDENTS ON PAGE LOAD
        function loadAllStudents() {
            showLoading('collectFeesResults');
            
            $.ajax({
                url: '{{ route("fees.collect.search") }}',
                method: 'POST',
                data: { search: '' },
                success: function(response) {
                    console.log('📊 Initial load:', response);
                    if (response.success && response.data && response.data.length > 0) {
                        renderCollectFeesTable(response.data);
                    } else {
                        showNoData('collectFeesResults', 'No students found');
                    }
                },
                error: function(xhr) {
                    console.error('❌ Error loading students:', xhr);
                    showNoData('collectFeesResults', 'Error loading data');
                }
            });
        }

        // ✅ SEARCH STUDENTS
        function searchStudent() {
            const searchTerm = $('#studentSearch').val().trim();
            
            showLoading('collectFeesResults');

            $.ajax({
                url: '{{ route("fees.collect.search") }}',
                method: 'POST',
                data: { search: searchTerm },
                success: function(response) {
                    console.log('🔍 Search results:', response);
                    if (response.success && response.data && response.data.length > 0) {
                        renderCollectFeesTable(response.data);
                    } else {
                        showNoData('collectFeesResults', 'No students found matching "' + searchTerm + '"');
                    }
                },
                error: function(xhr) {
                    console.error('❌ Search error:', xhr);
                    showNoData('collectFeesResults', 'Error searching students');
                }
            });
        }

        // ✅ SEARCH BY STATUS
        function searchByStatus() {
            const courseId = $('#courseSelect').val();
            const batchId = $('#batchSelect').val();
            const feeStatus = $('#feeStatusSelect').val();

            // Clear previous errors
            $('#feeStatusError').hide();
            $('#feeStatusSelect').removeClass('error');

            // Validate fee status is selected
            if (!feeStatus) {
                $('#feeStatusError').show();
                $('#feeStatusSelect').addClass('error');
                return;
            }

            showLoading('feeStatusResults');

            $.ajax({
                url: '{{ route("fees.status.search") }}',
                method: 'POST',
                data: {
                    course_id: courseId,
                    batch_id: batchId,
                    fee_status: feeStatus
                },
                success: function(response) {
                    console.log('📊 Status search results:', response);
                    if (response.success && response.data && response.data.length > 0) {
                        renderFeeStatusTable(response.data);
                    } else {
                        showNoData('feeStatusResults', 'No students found with selected criteria');
                    }
                },
                error: function(xhr) {
                    console.error('❌ Status search error:', xhr);
                    showNoData('feeStatusResults', 'Error loading data');
                }
            });
        }

        // ✅ FILTER TRANSACTIONS
        function filterTransactions() {
            const fromDate = $('#fromDate').val();
            const toDate = $('#toDate').val();

            if (!fromDate || !toDate) {
                alert('Please select both From and To dates');
                return;
            }

            showLoading('transactionResults');

            $.ajax({
                url: '{{ route("fees.transaction.filter") }}',
                method: 'POST',
                data: {
                    from_date: fromDate,
                    to_date: toDate
                },
                success: function(response) {
                    console.log('📅 Transaction results:', response);
                    if (response.success && response.data && response.data.length > 0) {
                        renderTransactionTable(response.data);
                    } else {
                        showNoData('transactionResults', 'No transactions found for selected dates');
                    }
                },
                error: function(xhr) {
                    console.error('❌ Transaction filter error:', xhr);
                    showNoData('transactionResults', 'Error loading transactions');
                }
            });
        }

        // ✅ RENDER COLLECT FEES TABLE
        function renderCollectFeesTable(data) {
            let html = '<table class="results-table"><thead><tr>';
            html += '<th>Serial No.</th><th>Roll No.</th><th>Student Name</th><th>Father Name</th>';
            html += '<th>Course Content</th><th>Course Name</th><th>Delivery Mode</th>';
            html += '<th>Fees Status</th><th>Action</th>';
            html += '</tr></thead><tbody>';

            data.forEach((student, index) => {
                // Fee status badge color
                let statusClass = '';
                if (student.fee_status === 'Paid') statusClass = 'text-success';
                else if (student.fee_status === 'Pending') statusClass = 'text-danger';
                else statusClass = 'text-warning';
                
                html += '<tr>';
                html += `<td>${index + 1}</td>`;
                html += `<td>${student.roll_no || '-'}</td>`;
                html += `<td>${student.name || '-'}</td>`;
                html += `<td>${student.father_name || '-'}</td>`;
                html += `<td>${student.course_content || '-'}</td>`;
                html += `<td>${student.course_name || '-'}</td>`;
                html += `<td>${student.delivery_mode || '-'}</td>`;
                html += `<td><span class="${statusClass} fw-bold">${student.fee_status || 'Pending'}</span></td>`;
                html += `<td><button class="action-btn" onclick="collectFee('${student.id}', '${student.roll_no}')">Collect</button></td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            html += `<div style="padding: 10px; text-align: left; color: #666;">Showing ${data.length} entries</div>`;
            $('#collectFeesResults').html(html);
        }

        // ✅ RENDER FEE STATUS TABLE
        function renderFeeStatusTable(data) {
            let html = '<table class="results-table"><thead><tr>';
            html += '<th>Serial No.</th><th>Roll No.</th><th>Student Name</th><th>Father Name</th>';
            html += '<th>Course Content</th><th>Course Name</th><th>Delivery Mode</th>';
            html += '<th>Fees Status</th><th>Action</th>';
            html += '</tr></thead><tbody>';

            data.forEach((student, index) => {
                let statusClass = '';
                if (student.fee_status === 'Paid') statusClass = 'text-success';
                else if (student.fee_status === 'Pending') statusClass = 'text-danger';
                else statusClass = 'text-warning';

                html += '<tr>';
                html += `<td>${index + 1}</td>`;
                html += `<td>${student.roll_no || '-'}</td>`;
                html += `<td>${student.name || '-'}</td>`;
                html += `<td>${student.father_name || '-'}</td>`;
                html += `<td>${student.course_content || '-'}</td>`;
                html += `<td>${student.course_name || '-'}</td>`;
                html += `<td>${student.delivery_mode || '-'}</td>`;
                html += `<td><span class="${statusClass} fw-bold">${student.fee_status || 'Pending'}</span></td>`;
                html += `<td><button class="action-btn" onclick="viewDetails('${student.id}')">View</button></td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            html += `<div style="padding: 10px; text-align: left; color: #666;">Showing ${data.length} entries</div>`;
            $('#feeStatusResults').html(html);
        }

        // ✅ RENDER TRANSACTION TABLE
        function renderTransactionTable(data) {
            let html = '<table class="results-table"><thead><tr>';
            html += '<th>Serial No.</th><th>Student Name</th><th>Student Roll no.</th>';
            html += '<th>Course</th><th>Session</th><th>Amount</th>';
            html += '<th>Payment type</th><th>Transaction Number</th>';
            html += '</tr></thead><tbody>';

            data.forEach((transaction, index) => {
                html += '<tr>';
                html += `<td>${index + 1}</td>`;
                html += `<td>${transaction.student_name || '-'}</td>`;
                html += `<td>${transaction.student_roll_no || '-'}</td>`;
                html += `<td>${transaction.course || '-'}</td>`;
                html += `<td>${transaction.session || '-'}</td>`;
                html += `<td>₹${transaction.amount || '0'}</td>`;
                html += `<td>${transaction.payment_type || '-'}</td>`;
                html += `<td>${transaction.transaction_number || '-'}</td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            html += `<div style="padding: 10px; text-align: left; color: #666;">Showing ${data.length} transactions</div>`;
            $('#transactionResults').html(html);
        }

        // ✅ UTILITY FUNCTIONS
        function showLoading(elementId) {
            $(`#${elementId}`).html(
                '<div class="loading-state">' +
                '<div class="spinner"></div>' +
                '<p>Loading...</p>' +
                '</div>'
            );
        }

        function showNoData(elementId, message) {
            $(`#${elementId}`).html(
                '<div class="empty-state">' +
                '<i class="fas fa-info-circle"></i>' +
                `<p>${message}</p>` +
                '</div>'
            );
        }

        function exportPendingFees() {
            window.location.href = '{{ route("fees.export") }}';
        }

        function collectFee(studentId, rollNo) {
            alert(`Collect fee functionality for Student ID: ${studentId}\nRoll No: ${rollNo}`);
            // TODO: Implement actual fee collection - redirect to payment page
            // window.location.href = '/fees/collect/' + studentId;
        }

        function viewDetails(studentId) {
            alert(`View details for Student ID: ${studentId}`);
            // TODO: Implement view details - open modal or redirect
            // window.location.href = '/students/' + studentId;
        }
    </script>
</body>
</html>