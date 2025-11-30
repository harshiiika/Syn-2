<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fees Management - Synthesis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #F5F5F5; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.08); position: sticky; top: 0; z-index: 100; }
        .header .logo { display: flex; align-items: center; gap: 0; }
        .header .logo img { height: 40px; }
        .header .pfp { display: flex; align-items: center; gap: 15px; }
        .header .session { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #333; }
        .header .session strong { color: #333; font-weight: 500; }
        .header select { padding: 5px 10px; border: 1px solid #DDD; border-radius: 4px; font-size: 13px; }
        .header i.fa-bell { font-size: 20px; color: #E66A2C; cursor: pointer; }
        .main-container { display: flex; min-height: calc(100vh - 64px); }
        .left { width: 220px; background: white; box-shadow: 1px 0 3px rgba(0,0,0,0.06); overflow-y: auto; height: calc(100vh - 64px); }
        .left .text { padding: 20px 15px; border-bottom: 1px solid #E8E8E8; background: white; }
        .left .text h6 { color: #333; font-weight: 600; margin-bottom: 4px; font-size: 14px; }
        .left .text p { font-size: 12px; color: #777; margin: 0; }
        .accordion { border: none !important; }
        .accordion-item { border: none !important; background: white; }
        .accordion-button { background: white !important; border: none !important; color: #555 !important; padding: 12px 15px !important; font-size: 14px !important; font-weight: 400 !important; box-shadow: none !important; border-left: 3px solid transparent !important; }
        .accordion-button:not(.collapsed) { background: white !important; color: #E66A2C !important; border-left-color: #E66A2C !important; font-weight: 500 !important; }
        .accordion-button:focus { box-shadow: none !important; }
        .accordion-button:hover { background: #FAFAFA !important; }
        .accordion-button::after { width: 0.7rem; height: 0.7rem; background-size: 0.7rem; margin-left: auto; }
        .accordion-button i { margin-right: 10px; width: 18px; font-size: 14px; color: #666; }
        .accordion-button:not(.collapsed) i { color: #E66A2C; }
        .accordion-body { padding: 0 !important; background: #FAFAFA; }
        .menu { list-style: none; margin: 0; padding: 0; }
        .menu .item { display: flex; align-items: center; padding: 10px 15px 10px 40px; color: #666; text-decoration: none; transition: all 0.2s; font-size: 13px; border-left: 3px solid transparent; background: white; }
        .menu .item:hover { background: #F5F5F5; color: #E66A2C; }
        .item.active { background: #FFF5F0; color: #E66A2C; border-left-color: #E66A2C; font-weight: 500; }
        .menu .item i { margin-right: 10px; width: 16px; font-size: 13px; }
        .right { flex: 1; background: #F5F5F5; overflow-y: auto; height: calc(100vh - 64px); }
        .page-header { padding: 20px; background: #F5F5F5; }
        .page-title { font-size: 26px; color: #E66A2C; font-weight: 700; margin: 0; }
        .tabs-wrapper { background: white; margin: 0 20px 20px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; position: relative; }
        .btn-export { padding: 10px 20px; background: #28A745; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; font-size: 13px; position: absolute; right: 20px; top: 15px; z-index: 10; transition: all 0.3s; }
        .btn-export:hover { background: #218838; transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .tabs-header { display: flex; border-bottom: 2px solid #DDD; background: #FAFAFA; border-radius: 8px 8px 0 0; }
        .tab-btn { padding: 14px 28px; border: none; background: transparent; cursor: pointer; font-weight: 600; font-size: 14px; color: #666; transition: all 0.3s; border-bottom: 3px solid transparent; position: relative; }
        .tab-btn:hover { background: #F0F0F0; color: #E66A2C; }
        .tab-btn.active { background: #E66A2C; color: white; border-bottom-color: #D85A1C; }
        .tab-panel { padding: 25px; display: none; }
        .tab-panel.active { display: block; }
        .search-area { margin-bottom: 20px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center; background: linear-gradient(135deg, #F8F9FA 0%, #E9ECEF 100%); padding: 18px; border-radius: 8px; border: 1px solid #DEE2E6; }
        .search-input { flex: 1; min-width: 300px; padding: 11px 15px; border: 2px solid #DDD; border-radius: 6px; font-size: 14px; transition: all 0.3s; }
        .search-input:focus { outline: none; border-color: #E66A2C; box-shadow: 0 0 0 3px rgba(230, 106, 44, 0.1); }
        .dropdown-filter { min-width: 200px; padding: 11px 15px; border: 2px solid #DDD; border-radius: 6px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s; }
        .dropdown-filter:focus { outline: none; border-color: #E66A2C; }
        .btn-search { padding: 11px 30px; background: #E66A2C; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s; }
        .btn-search:hover { background: #D85A1C; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(230, 106, 44, 0.3); }
        .btn-reset { padding: 11px 30px; background: #6C757D; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s; }
        .btn-reset:hover { background: #5A6268; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3); }
        .action-menu-btn { background: none; border: none; cursor: pointer; font-size: 18px; color: #666; padding: 8px 12px; position: relative; border-radius: 4px; transition: all 0.3s; }
        .action-menu-btn:hover { background: #F0F0F0; color: #E66A2C; }
        .action-dropdown { position: absolute; right: 0; top: 100%; background: white; border: 1px solid #DDD; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 150px; z-index: 9999; display: none; margin-top: 2px; }
        .action-dropdown.show { display: block; animation: dropDown 0.2s ease; }
        @keyframes dropDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .action-dropdown-item { padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #F0F0F0; font-size: 13px; transition: all 0.2s; display: flex; align-items: center; color: #333; }
        .action-dropdown-item:hover { background: #FFF5F0; color: #E66A2C; }
        .action-dropdown-item:last-child { border-bottom: none; }
        .action-dropdown-item i { margin-right: 8px; width: 14px; font-size: 13px; }
        .empty-state { text-align: center; padding: 60px 20px; color: #999; }
        .empty-state i { font-size: 48px; color: #CCC; margin-bottom: 15px; display: block; }
        .empty-state p { margin: 0; font-size: 15px; font-weight: 500; }
        .loading-state { text-align: center; padding: 60px 20px; }
        .spinner { border: 5px solid #f3f3f3; border-top: 5px solid #E66A2C; border-radius: 50%; width: 50px; height: 50px; animation: spin 0.8s linear infinite; margin: 0 auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .dataTables_wrapper { padding: 0; }
        table.dataTable { width: 100% !important; border-collapse: collapse; background: white; }
        table.dataTable thead th { background: #E66A2C; color: white; font-weight: 600; padding: 12px; font-size: 13px; text-align: left; border: none; }
        table.dataTable tbody td { padding: 12px; font-size: 13px; border-bottom: 1px solid #E5E5E5; vertical-align: middle; color: #333; background: white; }
        table.dataTable tbody tr { background: white; }
        table.dataTable tbody tr:hover { background: #FAFAFA; }
        .dataTables_info { font-size: 13px; color: #666; padding: 15px 0; font-weight: 500; }
        .dataTables_paginate { padding: 15px 0; }
        .dataTables_filter { margin-bottom: 15px; }
        .dataTables_filter label { font-weight: 600; color: #333; }
        .dataTables_filter input { border: 2px solid #DDD; border-radius: 4px; padding: 8px 12px; font-size: 14px; margin-left: 10px; transition: all 0.3s; }
        .dataTables_filter input:focus { outline: none; border-color: #E66A2C; }
        .dataTables_length select { border: 2px solid #DDD; border-radius: 4px; padding: 8px 12px; font-weight: 500; }
        .status-paid { color: #28A745; font-weight: 600; background: white; padding: 4px 8px; font-size: 12px; }
        .status-pending { color: #DC3545; font-weight: 600; background: white; padding: 4px 8px; font-size: 12px; }
        .status-installment { color: #FFC107; font-weight: 600; background: white; padding: 4px 8px; font-size: 12px; }
        .fees-details-container { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin: 20px; }
        .fees-header { background: white; padding: 20px; border-bottom: 1px solid #E5E5E5; }
        .fees-header h2 { color: #E66A2C; font-size: 24px; font-weight: 600; margin: 0; }
        .back-link { color: #E66A2C; text-decoration: none; font-size: 14px; font-weight: 500; float: right; }
        .billing-info-section { background: #F8F9FA; padding: 20px; margin-bottom: 20px; }
        .billing-info-section h5 { color: #E66A2C; font-size: 16px; font-weight: 600; margin-bottom: 20px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .info-row { display: flex; align-items: center; margin-bottom: 12px; }
        .info-label { font-weight: 600; color: #333; min-width: 140px; font-size: 14px; }
        .info-value { color: #666; font-size: 14px; }
        .detail-nav-tabs { display: flex; gap: 10px; padding: 15px 20px; background: white; border-bottom: 1px solid #E5E5E5; }
        .detail-nav-btn { padding: 8px 20px; background: white; border: 1px solid #DDD; border-radius: 4px; color: #666; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.3s; }
        .detail-nav-btn.active { background: #E66A2C; color: white; border-color: #E66A2C; }
        .detail-nav-btn:hover:not(.active) { background: #F5F5F5; }
        .btn-add-charges { padding: 8px 20px; background: #E66A2C; color: white; border: none; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; margin-left: auto; }
        .btn-refund { padding: 8px 20px; background: #E66A2C; color: white; border: none; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; position: relative; }
        .refund-dropdown { position: absolute; top: 100%; right: 0; background: white; border: 1px solid #DDD; border-radius: 4px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); min-width: 200px; display: none; margin-top: 5px; z-index: 1000; }
        .refund-dropdown.show { display: block; }
        .refund-dropdown-item { padding: 10px 15px; cursor: pointer; font-size: 13px; color: #333; transition: all 0.2s; }
        .refund-dropdown-item:hover { background: #FFF5F0; color: #E66A2C; }
        .payment-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .payment-table thead { background: #F8F9FA; }
        .payment-table th { padding: 12px; text-align: left; font-size: 13px; font-weight: 600; color: #E66A2C; border-bottom: 2px solid #DDD; }
        .payment-table td { padding: 12px; font-size: 13px; color: #333; border-bottom: 1px solid #F0F0F0; }
        .status-badge { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .status-badge.paid { background: #D4EDDA; color: #28A745; }
        .status-badge.due { background: #F8D7DA; color: #DC3545; }
        .modal-header-custom { background: white; padding: 20px; border-bottom: 1px solid #E5E5E5; display: flex; justify-content: space-between; align-items: center; }
        .modal-header-custom h3 { color: #E66A2C; font-size: 20px; font-weight: 600; margin: 0; }
        .modal-body-custom { padding: 25px; }
        .form-group-custom { margin-bottom: 20px; }
        .form-group-custom label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 14px; }
        .form-group-custom input, .form-group-custom select, .form-group-custom textarea { width: 100%; padding: 10px 15px; border: 1px solid #DDD; border-radius: 4px; font-size: 14px; }
        .form-group-custom input:focus, .form-group-custom select:focus, .form-group-custom textarea:focus { outline: none; border-color: #E66A2C; box-shadow: 0 0 0 3px rgba(230, 106, 44, 0.1); }
        .btn-add-more { padding: 8px 20px; background: #6C757D; color: white; border: none; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; margin-top: 10px; }
        .btn-add-more:hover { background: #5A6268; }
        .modal-footer-custom { padding: 20px; border-top: 1px solid #E5E5E5; display: flex; justify-content: flex-end; gap: 10px; }
        .btn-cancel-custom { padding: 10px 25px; background: white; color: #666; border: 1px solid #DDD; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; }
        .btn-submit-custom { padding: 10px 25px; background: #E66A2C; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; }
        .btn-submit-custom:hover { background: #D85A1C; }
        .scholarship-info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #F0F0F0; }
        .scholarship-info-row span:first-child { font-weight: 600; color: #333; }
        .scholarship-info-row span:last-child { color: #666; }
        .installment-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; }
        .installment-box { text-align: center; padding: 15px; background: #F8F9FA; border-radius: 8px; }
        .installment-box label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 14px; }
        .installment-box input { width: 100%; padding: 8px; border: 1px solid #DDD; border-radius: 4px; text-align: center; }
        .history-summary { background: #F8F9FA; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .history-summary h5 { color: #E66A2C; font-size: 16px; font-weight: 600; margin-bottom: 15px; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
        .summary-card { background: white; padding: 15px; border-radius: 6px; text-align: center; border: 1px solid #E5E5E5; }
        .summary-card .label { font-size: 12px; color: #666; margin-bottom: 5px; }
        .summary-card .value { font-size: 20px; font-weight: 600; color: #E66A2C; }
        .history-table-wrapper { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{asset('images/logo.png.jpg')}}" alt="Synthesis">
        </div>
        <div class="pfp">
            <div class="session">
                <strong>Session:</strong>
                <select>
                    <option>2025-2026</option>
                    <option>2024-2025</option>
                    <option>2023-2024</option>
                </select>
            </div>
            <i class="fa-solid fa-bell"></i>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" style="padding: 5px 12px; font-size: 13px;">
                    <i class="fa-solid fa-user"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{route('profile.index')}}"><i class="fa-solid fa-user"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="left">
            <div class="text">
                <h6>Admin</h6>
                <p>synthesisbikaner@gmail.com</p>
            </div>

            <div class="accordion accordion-flush" id="sidebarAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#userMgmt">
                            <i class="fa-solid fa-user-group"></i> User Management
                        </button>
                    </h2>
                    <div id="userMgmt" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user"></i> Employee</a></li>
                                <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group"></i> Batches Assignment</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#master">
                            <i class="fa-solid fa-database"></i> Master
                        </button>
                    </h2>
                    <div id="master" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a class="item" href="{{ route('courses.index') }}"><i class="fa-solid fa-book-open"></i> Courses</a></li>
                                <li><a class="item" href="{{ route('batches.index') }}"><i class="fa-solid fa-users"></i> Batches</a></li>
                                <li><a class="item" href="{{ route('master.scholarship.index') }}"><i class="fa-solid fa-graduation-cap"></i> Scholarship</a></li>
                                <li><a class="item" href="{{ route('fees.index') }}"><i class="fa-solid fa-credit-card"></i> Fees Master</a></li>
                                <li><a class="item" href="{{ route('master.other_fees.index') }}"><i class="fa-solid fa-wallet"></i> Other Fees Master</a></li>
                                <li><a class="item" href="{{ route('branches.index') }}"><i class="fa-solid fa-diagram-project"></i> Branch Management</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#session">
                            <i class="fa-solid fa-calendar"></i> Session Management
                        </button>
                    </h2>
                    <div id="session" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a class="item" href="{{ route('sessions.index') }}"><i class="fa-solid fa-calendar-day"></i> Session</a></li>
                                <li><a class="item" href="{{ route('calendar.index') }}"><i class="fa-solid fa-calendar-days"></i> Calendar</a></li>
                                <li><a class="item" href="#"><i class="fa-solid fa-user-check"></i> Student Migrate</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#student">
                            <i class="fa-solid fa-user-graduate"></i> Student Management
                        </button>
                    </h2>
                    <div id="student" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info"></i> Inquiry Management</a></li>
                                <li><a class="item" href="{{ route('student.student.pending') }}"><i class="fa-solid fa-user-check"></i> Student Onboard</a></li>
                                <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-clock"></i> Pending Fees Students</a></li>
                                <li><a class="item" href="{{ route('smstudents.index') }}"><i class="fa-solid fa-users"></i> Students</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#fees">
                            <i class="fa-solid fa-credit-card"></i> Fees Management
                        </button>
                    </h2>
                    <div id="fees" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a class="item active" href="{{ route('fees.management.index') }}"><i class="fa-solid fa-money-bill-wave"></i> Fee Collection</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#attendance">
                            <i class="fa-solid fa-calendar-check"></i> Attendance Management
                        </button>
                    </h2>
                    <div id="attendance" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a class="item" href="{{ route('attendance.employee.index') }}"><i class="fa-solid fa-user-tie"></i> Employee</a></li>
                                <li><a class="item" href="{{ route('attendance.student.index') }}"><i class="fa-solid fa-user-graduate"></i> Student</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#studyMaterial">
                            <i class="fa-solid fa-book-open"></i> Study Material
                        </button>
                    </h2>
                    <div id="studyMaterial" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a class="item" href="#"><i class="fa-solid fa-book"></i> Units</a></li>
                                <li><a class="item" href="#"><i class="fa-solid fa-truck"></i> Dispatch Material</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#testSeries">
                            <i class="fa-solid fa-chart-column"></i> Test Series Management
                        </button>
                    </h2>
                    <div id="testSeries" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a class="item" href="{{ route('test_series.index') }}"><i class="fa-solid fa-file-lines"></i> Test Master</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#reports">
                            <i class="fa-solid fa-square-poll-horizontal"></i> Reports
                        </button>
                    </h2>
                    <div id="reports" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="menu" id="dropdown-body">
                                <li><a class="item" href="#"><i class="fa-solid fa-person-walking" id="side-icon"></i>Walk In</a></li>
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

        <div class="right">
            <div class="page-header">
                <h2 class="page-title">Fees Management</h2>
            </div>

            <div class="tabs-wrapper">
                <button class="btn-export" onclick="exportPendingFees()">
                    <i class="fas fa-download"></i> Export Pending Fees
                </button>

                <div class="tabs-header">
                    <button class="tab-btn active" data-tab="collect">Collect Fees</button>
                    <button class="tab-btn" data-tab="status">Fee Status</button>
                    <button class="tab-btn" data-tab="transaction">Daily Transaction</button>
                </div>

                <div id="collect" class="tab-panel active">
                    <div class="search-area">
                        <input type="text" id="collectSearchInput" class="search-input" placeholder="Search by name or roll number">
                        <button class="btn-search" onclick="performCollectSearch()">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <button class="btn-reset" onclick="resetCollectSearch()">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                    <div id="collectFeesResults">
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <p>Enter a name or roll number and click Search</p>
                        </div>
                    </div>
                </div>

                <div id="status" class="tab-panel">
                    <div class="search-area">
                        <select id="courseSelect" class="dropdown-filter">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
                            @endforeach
                        </select>
                        <select id="batchSelect" class="dropdown-filter" disabled>
                            <option value="">Select Batch</option>
                        </select>
                        <select id="feeStatusSelect" class="dropdown-filter">
                            <option value="">Select Fee Status</option>
                            <option value="All">All</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                        </select>
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

                <div id="transaction" class="tab-panel">
                    <div class="search-area">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label style="font-weight: 500;">From:</label>
                            <input type="date" id="fromDate" class="dropdown-filter" style="min-width: 180px;">
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label style="font-weight: 500;">To:</label>
                            <input type="date" id="toDate" class="dropdown-filter" style="min-width: 180px;">
                        </div>
                        <button class="btn-search" onclick="filterTransactions()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                    <div id="transactionResults">
                        <div class="loading-state">
                            <div class="spinner"></div>
                            <p>Loading transactions...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 90%;">
            <div class="modal-content" style="border: none; border-radius: 8px;">
                <div class="fees-details-container">
                    <div class="fees-header">
                        <h2>Fees Details</h2>
                        <a href="#" class="back-link" data-bs-dismiss="modal">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <div style="clear: both;"></div>
                    </div>

                    <div class="billing-info-section">
                        <h5>Billing Information</h5>
                        <div class="info-grid">
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Student Name</span>
                                    <span class="info-value" id="modal-student-name">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Course Type</span>
                                    <span class="info-value" id="modal-course-type">Pre-Medical</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Course Content</span>
                                    <span class="info-value" id="modal-course-content">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Batch Name</span>
                                    <span class="info-value" id="modal-batch-name">D2</span>
                                </div>
                            </div>
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Father Name</span>
                                    <span class="info-value" id="modal-father-name">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Course Name</span>
                                    <span class="info-value" id="modal-course-name">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Batch Start Date</span>
                                    <span class="info-value" id="modal-batch-start">2025-04-14</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Delivery Mode</span>
                                    <span class="info-value" id="modal-delivery-mode">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-nav-tabs">
                        <button class="detail-nav-btn active" onclick="switchDetailTab('view')">View Detail</button>
                        <button class="detail-nav-btn" onclick="switchDetailTab('installment')">Installment History</button>
                        <button class="detail-nav-btn" onclick="switchDetailTab('other')">Other Charge History</button>
                        <button class="detail-nav-btn" onclick="switchDetailTab('transaction')">Transaction History</button>
                        <button class="btn-add-charges" onclick="openAddOtherChargesModal()">Add Other Charges</button>
                        <button class="btn-refund" onclick="toggleRefundDropdown(event)">
                            Refund Amount ▼
                            <div class="refund-dropdown" id="refundDropdown">
                                <div class="refund-dropdown-item" onclick="openRefundModal()">Refund</div>
                                <div class="refund-dropdown-item" onclick="openScholarshipModal()">Scholarship Dis.</div>
                            </div>
                        </button>
                    </div>

                    <div style="padding: 20px;">
                        <div id="view-tab" class="detail-tab-content">
                            <h5 style="color: #E66A2C; font-weight: 600; margin-bottom: 20px;">Current Payment Details</h5>
                            <table class="payment-table">
                                <thead>
                                    <tr>
                                        <th>Installment</th>
                                        <th>Actual Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Date</th>
                                        <th>Payment Date</th>
                                        <th>Status</th>
                                        <th>Single Installment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="viewDetailsTableBody">
                                    <tr>
                                        <td colspan="8" style="text-align: center;">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="installment-tab" class="detail-tab-content" style="display: none;">
                            <div class="history-summary">
                                <h5>Payment History Summary</h5>
                                <div class="summary-grid">
                                    <div class="summary-card">
                                        <div class="label">Total Installments</div>
                                        <div class="value" id="hist-total-installments">0</div>
                                    </div>
                                    <div class="summary-card">
                                        <div class="label">Paid Amount</div>
                                        <div class="value" id="hist-paid-amount">₹0</div>
                                    </div>
                                    <div class="summary-card">
                                        <div class="label">Pending Amount</div>
                                        <div class="value" id="hist-pending-amount">₹0</div>
                                    </div>
                                    <div class="summary-card">
                                        <div class="label">Last Payment Date</div>
                                        <div class="value" id="hist-last-payment" style="font-size: 14px;">-</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="history-table-wrapper">
                                <h5 style="color: #E66A2C; font-weight: 600; margin-bottom: 15px;">Complete Payment History</h5>
                                <table class="payment-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Installment</th>
                                            <th>Amount</th>
                                            <th>Payment Date</th>
                                            <th>Payment Type</th>
                                            <th>Transaction ID</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="installmentHistoryTableBody">
                                        <tr>
                                            <td colspan="8" style="text-align: center;">No payment history available</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="other-tab" class="detail-tab-content" style="display: none;">
                            <h5 style="color: #E66A2C;">Other Charge History</h5>
                            <p>No other charges found.</p>
                        </div>

                        <div id="transaction-tab" class="detail-tab-content" style="display: none;">
                            <h5 style="color: #E66A2C;">Transaction History</h5>
                            <p>No transactions found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addOtherChargesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header-custom">
                    <h3>Other Fees</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body-custom">
                    <form id="otherFeesForm">
                        <div class="form-group-custom">
                            <label>Payment Date</label>
                            <input type="date" id="otherFeesDate" class="form-control">
                        </div>
                        <div class="form-group-custom">
                            <label>Payment Type</label>
                            <select id="otherFeesPaymentType" class="form-control">
                                <option value="">Select Payment Type</option>
                                <option value="Cash">Cash</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Debit Card">Debit Card</option>
                                <option value="Online Transfer">Online Transfer</option>
                                <option value="DD">DD (Demand Draft)</option>
                            </select>
                        </div>
                        <div class="form-group-custom">
                            <label>Fee Type</label>
                            <select id="otherFeeType" class="form-control">
                                <option value="">Select Fee Type</option>
                                <option value="Registration">Registration Fee</option>
                                <option value="Exam">Exam Fee</option>
                                <option value="Library">Library Fee</option>
                                <option value="Sports">Sports Fee</option>
                                <option value="Transport">Transport Fee</option>
                                <option value="Lab">Lab Fee</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group-custom">
                            <label>Amount</label>
                            <input type="number" id="otherFeesAmount" class="form-control" placeholder="Enter amount">
                        </div>
                        <button type="button" class="btn-add-more" onclick="addMoreOtherFees()">Add More</button>
                    </form>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-submit-custom" onclick="submitOtherFees()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="refundModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header-custom">
                    <h3>Refund Information</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body-custom">
                    <form id="refundForm">
                        <div class="form-group-custom">
                            <label>Refund Type</label>
                            <select id="refundType" class="form-control">
                                <option value="">Select Refund Type</option>
                                <option value="Full">Full Refund</option>
                                <option value="Partial">Partial Refund</option>
                                <option value="Withdrawal">Withdrawal Refund</option>
                            </select>
                        </div>
                        <div class="form-group-custom">
                            <label>Discount Percentage</label>
                            <input type="number" id="discountPercentage" class="form-control" placeholder="Enter percentage" max="100" min="0">
                        </div>
                    </form>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-submit-custom" onclick="submitRefund()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="scholarshipModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header-custom">
                    <h3>Scholarship Discount</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body-custom">
                    <div class="scholarship-info-row">
                        <span>Total Paid Amount</span>
                        <span id="scholarship-total-paid">41536</span>
                    </div>
                    <div class="scholarship-info-row">
                        <span>Eligible For Scholarship</span>
                        <span id="scholarship-eligible">No</span>
                    </div>
                    <div class="scholarship-info-row">
                        <span>Discretionary Discount</span>
                        <span id="scholarship-discretionary">No</span>
                    </div>
                    <div class="scholarship-info-row">
                        <span>Discount Percentage</span>
                        <span id="scholarship-discount-percent">0</span>
                    </div>
                    <form id="scholarshipForm" style="margin-top: 20px;">
                        <div class="form-group-custom">
                            <label>Discount Percentage</label>
                            <input type="number" id="scholarshipDiscountInput" class="form-control" placeholder="Enter percentage" max="100" min="0">
                        </div>
                        <div class="form-group-custom">
                            <label>Reason Of Refund</label>
                            <textarea id="scholarshipReason" class="form-control" rows="3" placeholder="Enter reason for scholarship discount"></textarea>
                        </div>
                        <div class="installment-grid">
                            <div class="installment-box">
                                <label>Installment1</label>
                                <input type="text" value="0" readonly>
                            </div>
                            <div class="installment-box">
                                <label>Installment2</label>
                                <input type="text" value="0" readonly>
                            </div>
                            <div class="installment-box">
                                <label>Installment3</label>
                                <input type="text" value="0" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-submit-custom" onclick="submitScholarship()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
    const coursesBatchesMapping = {!! json_encode($coursesBatchesMapping ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
    let collectFeesTable, feeStatusTable, transactionTable;
    let currentTableData = [];
    let currentStudentId = null;
    let transactionsLoaded = false;

    $(document).ready(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $('.tab-btn').click(function() {
            const tabName = $(this).data('tab');
            $('.tab-btn').removeClass('active');
            $('.tab-panel').removeClass('active');
            $(this).addClass('active');
            $('#' + tabName).addClass('active');
            if (tabName === 'transaction' && !transactionsLoaded) loadAllTransactions();
        });
        $('#courseSelect').change(function() {
            const courseId = $(this).val();
            const $batch = $('#batchSelect');
            if (courseId && coursesBatchesMapping[courseId]) {
                let opts = '<option value="">All Batches</option>';
                coursesBatchesMapping[courseId].forEach(b => opts += `<option value="${b.id}">${b.name}</option>`);
                $batch.html(opts).prop('disabled', false);
            } else {
                $batch.html('<option value="">Select batch</option>').prop('disabled', true);
            }
        });
        $('#collectSearchInput').keypress(e => { if (e.which === 13) performCollectSearch(); });
        const today = new Date(), firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        $('#fromDate').val(formatDate(firstDay));
        $('#toDate').val(formatDate(today));
        $(document).click(function(e) {
            if (!$(e.target).closest('.btn-refund').length) $('.refund-dropdown').removeClass('show');
            if (!$(e.target).closest('.action-menu-btn').length) $('.action-dropdown').removeClass('show');
        });
    });

    function formatDate(d) {
        return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    }

    function performCollectSearch() {
        const term = $('#collectSearchInput').val().trim();
        if (!term) return alert('Please enter search term');
        $('#collectFeesResults').html('<div class="loading-state"><div class="spinner"></div><p>Searching...</p></div>');
        $.post('{{ route("fees.collect.search") }}', { search: term }, function(r) {
            if (r.success && r.data && r.data.length) {
                currentTableData = r.data;
                renderTable('collect', r.data);
            } else {
                $('#collectFeesResults').html(`<div class="empty-state"><i class="fas fa-info-circle"></i><p>No results found</p></div>`);
            }
        }).fail(() => $('#collectFeesResults').html(`<div class="empty-state"><i class="fas fa-times-circle"></i><p>Error searching</p></div>`));
    }

    function resetCollectSearch() {
        $('#collectSearchInput').val('');
        $('#collectFeesResults').html('<div class="empty-state"><i class="fas fa-search"></i><p>Enter a name or roll number and click Search</p></div>');
    }

    function searchByStatus() {
        const status = $('#feeStatusSelect').val();
        if (!status) return alert('Please select a fee status');
        console.log('🔍 Search:', $('#courseSelect').val(), $('#batchSelect').val(), status);
        $('#feeStatusResults').html('<div class="loading-state"><div class="spinner"></div><p>Loading...</p></div>');
        $.post('{{ route("fees.status.search") }}', {
            course_id: $('#courseSelect').val(),
            batch_id: $('#batchSelect').val(),
            fee_status: status
        }, r => {
            console.log('📊 Response:', r);
            if (r.success && r.data && r.data.length) {
                console.log('✅ Rendering', r.data.length, 'students');
                currentTableData = r.data;
                renderTable('status', r.data);
            } else {
                console.log('❌ No data');
                $('#feeStatusResults').html('<div class="empty-state"><i class="fas fa-info-circle"></i><p>No students found</p></div>');
            }
        }).fail(() => $('#feeStatusResults').html('<div class="empty-state"><i class="fas fa-times-circle"></i><p>Error loading data</p></div>'));
    }

    function renderTable(type, data) {
        console.log('🎨 Rendering', type, 'table with', data.length, 'rows');
        if (type === 'collect' && collectFeesTable) { collectFeesTable.destroy(); collectFeesTable = null; }
        if (type === 'status' && feeStatusTable) { feeStatusTable.destroy(); feeStatusTable = null; }
        let html = '<table id="'+type+'Table" class="table table-striped w-100"><thead><tr>';
        html += '<th>#</th><th>Roll No</th><th>Student Name</th><th>Father Name</th>';
        html += '<th>Course Content</th><th>Course Name</th><th>Delivery Mode</th>';
        html += '<th>Fee Status</th><th>Action</th></tr></thead><tbody>';
        data.forEach((s, i) => {
            const stat = s.fee_status && s.fee_status.toLowerCase() === 'paid' ? 'status-paid' : 'status-pending';
            html += `<tr><td><strong>${i+1}</strong></td><td>${s.roll_no || 'N/A'}</td><td><strong>${s.name || 'N/A'}</strong></td>`;
            html += `<td>${s.father_name || 'N/A'}</td><td>${s.course_content || 'N/A'}</td><td>${s.course_name || 'N/A'}</td>`;
            html += `<td>${s.delivery_mode || 'N/A'}</td><td><span class="${stat}">${s.fee_status || 'Pending'}</span></td>`;
            html += `<td style="position: relative;"><button class="action-menu-btn" onclick="toggleMenu(event,'${type}-${s.id}')">`;
            html += `<i class="fas fa-ellipsis-v"></i></button><div class="action-dropdown" id="menu-${type}-${s.id}">`;
            html += `<div class="action-dropdown-item" onclick="viewDetails('${s.id}')"><i class="fas fa-eye"></i> View Details</div></div></td></tr>`;
        });
        html += '</tbody></table>';
        const targetDiv = type === 'collect' ? '#collectFeesResults' : '#feeStatusResults';
        $(targetDiv).html(html);
        try {
            const table = $(`#${type}Table`).DataTable({ pageLength: 10, order: [[0, 'asc']], destroy: true });
            if (type === 'collect') collectFeesTable = table;
            else feeStatusTable = table;
            console.log('✅ Table rendered successfully');
        } catch(e) {
            console.error('❌ DataTable error:', e);
        }
    }

    function loadAllTransactions() {
        const from = $('#fromDate').val(), to = $('#toDate').val();
        $('#transactionResults').html('<div class="loading-state"><div class="spinner"></div><p>Loading...</p></div>');
        $.post('{{ route("fees.transaction.filter") }}', { from_date: from, to_date: to }, function(r) {
            if (r.success && r.data && r.data.length) {
                renderTransactions(r.data);
                transactionsLoaded = true;
            } else {
                $('#transactionResults').html('<div class="empty-state"><i class="fas fa-info-circle"></i><p>No transactions found</p></div>');
            }
        }).fail(() => $('#transactionResults').html('<div class="empty-state"><i class="fas fa-times-circle"></i><p>Error loading</p></div>'));
    }

    function filterTransactions() {
        const from = $('#fromDate').val(), to = $('#toDate').val();
        if (!from || !to) return alert('Please select both dates');
        $('#transactionResults').html('<div class="loading-state"><div class="spinner"></div><p>Loading...</p></div>');
        $.post('{{ route("fees.transaction.filter") }}', { from_date: from, to_date: to }, r => {
            if (r.success && r.data && r.data.length) {
                renderTransactions(r.data);
                transactionsLoaded = true;
            } else {
                $('#transactionResults').html('<div class="empty-state"><i class="fas fa-info-circle"></i><p>No transactions found</p></div>');
            }
        }).fail(() => $('#transactionResults').html('<div class="empty-state"><i class="fas fa-times-circle"></i><p>Error loading</p></div>'));
    }

    function renderTransactions(data) {
        if (transactionTable) transactionTable.destroy();
        let html = '<table id="transactionTable" class="table w-100"><thead><tr>';
        html += '<th>#</th><th>Student Name</th><th>Roll No</th><th>Course</th><th>Session</th><th>Amount</th><th>Payment Type</th><th>Transaction #</th>';
        html += '</tr></thead><tbody>';
        data.forEach((t, i) => {
            html += `<tr><td><strong>${i+1}</strong></td><td>${t.student_name || 'N/A'}</td><td>${t.student_roll_no || t.roll_no || 'N/A'}</td>`;
            html += `<td>${t.course || 'N/A'}</td><td>${t.session || '2025-2026'}</td><td><strong>₹${t.amount || 0}</strong></td>`;
            html += `<td>${t.payment_type || 'Cash'}</td><td>${t.transaction_number || t.transaction_id || 'N/A'}</td></tr>`;
        });
        html += '</tbody></table>';
        $('#transactionResults').html(html);
        transactionTable = $('#transactionTable').DataTable({ pageLength: 10 });
    }

    function toggleMenu(e, id) {
        e.stopPropagation();
        $('.action-dropdown').removeClass('show');
        $(`#menu-${id}`).toggleClass('show');
    }

    function viewDetails(id) {
        currentStudentId = id;
        $.ajax({
            url: `/fees-management/student-details/${id}`,
            method: 'GET',
            success: function(r) {
                if (r.success && r.data) {
                    const d = r.data;
                    $('#modal-student-name').text(d.student_name || '-');
                    $('#modal-father-name').text(d.father_name || '-');
                    $('#modal-course-type').text(d.course_type || 'Pre-Medical');
                    $('#modal-course-name').text(d.course_name || '-');
                    $('#modal-course-content').text(d.course_content || '-');
                    $('#modal-batch-name').text(d.batch_name || 'D2');
                    $('#modal-batch-start').text(d.batch_start_date || '2025-04-14');
                    $('#modal-delivery-mode').text(d.delivery_mode || '-');
                    $('#scholarship-total-paid').text(d.paid_fees || '0');
                    $('#scholarship-eligible').text(d.scholarship_eligible || 'No');
                    $('#scholarship-discretionary').text(d.discretionary_discount || 'No');
                    $('#scholarship-discount-percent').text(d.discount_percent || '0');
                    if (d.installments) {
                        $('.installment-box:eq(0) input').val(d.installments[0] || '0');
                        $('.installment-box:eq(1) input').val(d.installments[1] || '0');
                        $('.installment-box:eq(2) input').val(d.installments[2] || '0');
                    }
                    loadViewDetails(id);
                    $('#viewDetailsModal').modal('show');
                }
            },
            error: () => alert('Error loading student details')
        });
    }

    function switchDetailTab(tabName) {
        $('.detail-tab-content').hide();
        $('.detail-nav-btn').removeClass('active');
        $('#' + tabName + '-tab').show();
        $('.detail-nav-btn').each(function() { if ($(this).attr('onclick').includes(tabName)) $(this).addClass('active'); });
        if (currentStudentId) {
            if (tabName === 'view') loadViewDetails(currentStudentId);
            else if (tabName === 'installment') loadInstallmentHistory(currentStudentId);
            else if (tabName === 'other') loadOtherCharges(currentStudentId);
            else if (tabName === 'transaction') loadTransactionHistory(currentStudentId);
        }
    }

    function loadViewDetails(studentId) {
        $.ajax({
            url: `/fees-management/installment-history/${studentId}`,
            method: 'GET',
            success: function(r) {
                if (r.success && r.data && r.data.length > 0) {
                    let html = '';
                    r.data.forEach(function(inst) {
                        const statusClass = inst.status === 'Paid' ? 'paid' : 'due';
                        html += `<tr><td>${inst.installment_no}</td><td>₹${inst.actual_amount}</td><td>₹${inst.paid_amount}</td>`;
                        html += `<td>${inst.due_date || '-'}</td><td>${inst.payment_date || '-'}</td>`;
                        html += `<td><span class="status-badge ${statusClass}">${inst.status}</span></td>`;
                        html += `<td>${inst.single_installment || 'No'}</td><td><i class="fas fa-ellipsis-v" style="cursor: pointer; color: #666;"></i></td></tr>`;
                    });
                    $('#viewDetailsTableBody').html(html);
                } else {
                    $('#viewDetailsTableBody').html('<tr><td colspan="8" style="text-align: center;">No payment details available</td></tr>');
                }
            },
            error: () => $('#viewDetailsTableBody').html('<tr><td colspan="8" style="text-align: center; color: #DC3545;">Error loading payment details</td></tr>')
        });
    }

    function loadInstallmentHistory(studentId) {
        $.ajax({
            url: `/fees-management/installment-history/${studentId}`,
            method: 'GET',
            success: function(r) {
                if (r.success && r.data && r.data.length > 0) {
                    let totalInstallments = r.data.length, totalPaid = 0, totalPending = 0, lastPaymentDate = '-';
                    r.data.forEach(function(inst) {
                        totalPaid += parseFloat(inst.paid_amount) || 0;
                        if (inst.status === 'Paid' && inst.payment_date && inst.payment_date !== '-') lastPaymentDate = inst.payment_date;
                        else totalPending += parseFloat(inst.actual_amount) || 0;
                    });
                    $('#hist-total-installments').text(totalInstallments);
                    $('#hist-paid-amount').text('₹' + totalPaid.toLocaleString());
                    $('#hist-pending-amount').text('₹' + totalPending.toLocaleString());
                    $('#hist-last-payment').text(lastPaymentDate);
                    let html = '';
                    r.data.forEach(function(inst, index) {
                        const statusClass = inst.status === 'Paid' ? 'paid' : 'due';
                        html += `<tr><td><strong>${index + 1}</strong></td><td>${inst.installment_no}</td><td><strong>₹${inst.paid_amount || inst.actual_amount}</strong></td>`;
                        html += `<td>${inst.payment_date || '-'}</td><td>${inst.payment_type || 'Cash'}</td><td>${inst.transaction_id || '-'}</td>`;
                        html += `<td><span class="status-badge ${statusClass}">${inst.status}</span></td><td>${inst.remarks || '-'}</td></tr>`;
                    });
                    $('#installmentHistoryTableBody').html(html);
                } else {
                    $('#hist-total-installments, #hist-paid-amount, #hist-pending-amount').text('0');
                    $('#hist-last-payment').text('-');
                    $('#installmentHistoryTableBody').html('<tr><td colspan="8" style="text-align: center;">No payment history available</td></tr>');
                }
            },
            error: function() {
                $('#hist-total-installments, #hist-paid-amount, #hist-pending-amount').text('0');
                $('#hist-last-payment').text('-');
                $('#installmentHistoryTableBody').html('<tr><td colspan="8" style="text-align: center; color: #DC3545;">Error loading payment history</td></tr>');
            }
        });
    }

    function loadOtherCharges(studentId) {
        $.ajax({
            url: `/fees-management/other-charges/${studentId}`,
            method: 'GET',
            success: function(r) {
                let html = '<h5 style="color: #E66A2C; margin-bottom: 20px;">Other Charge History</h5>';
                if (r.success && r.data && r.data.length > 0) {
                    html += '<table class="payment-table"><thead><tr><th>S.No.</th><th>Payment Date</th><th>Fee Type</th><th>Amount</th></tr></thead><tbody>';
                    r.data.forEach(function(charge, index) {
                        html += `<tr><td>${index + 1}</td><td>${charge.payment_date}</td><td>${charge.fee_type}</td><td>₹${charge.amount}</td></tr>`;
                    });
                    html += '</tbody></table>';
                } else {
                    html += '<p style="text-align: center; margin: 40px 0; color: #999;">No other charges found</p>';
                }
                $('#other-tab').html(html);
            },
            error: () => $('#other-tab').html('<h5 style="color: #E66A2C;">Other Charge History</h5><p style="text-align: center; margin: 40px 0; color: #DC3545;">Error loading data</p>')
        });
    }

    function loadTransactionHistory(studentId) {
        $.ajax({
            url: `/fees-management/transaction-history/${studentId}`,
            method: 'GET',
            success: function(r) {
                let html = '<h5 style="color: #E66A2C; margin-bottom: 20px;">Transaction History</h5>';
                if (r.success && r.data && r.data.length > 0) {
                    html += '<table class="payment-table"><thead><tr><th>S.No.</th><th>Transaction Id</th><th>Transaction Type</th><th>Payment Date</th><th>Amount</th></tr></thead><tbody>';
                    r.data.forEach(function(t) {
                        html += `<tr><td>${t.sr_no}</td><td>${t.transaction_id}</td><td>${t.transaction_type}</td><td>${t.payment_date}</td><td>₹${t.amount}</td></tr>`;
                    });
                    html += '</tbody></table>';
                } else {
                    html += '<p style="text-align: center; margin: 40px 0; color: #999;">No transactions found</p>';
                }
                $('#transaction-tab').html(html);
            },
            error: () => $('#transaction-tab').html('<h5 style="color: #E66A2C;">Transaction History</h5><p style="text-align: center; margin: 40px 0; color: #DC3545;">Error loading data</p>')
        });
    }

    function toggleRefundDropdown(e) {
        e.stopPropagation();
        $('#refundDropdown').toggleClass('show');
    }

    function openAddOtherChargesModal() {
        $('#addOtherChargesModal').modal('show');
        $('#otherFeesDate').val(new Date().toISOString().split('T')[0]);
    }

    function openRefundModal() {
        $('#refundDropdown').removeClass('show');
        $('#refundModal').modal('show');
    }

    function openScholarshipModal() {
        $('#refundDropdown').removeClass('show');
        $('#scholarshipModal').modal('show');
    }

    function addMoreOtherFees() {
        alert('Add more functionality to be implemented');
    }

    function submitOtherFees() {
        if (!currentStudentId) return alert('No student selected');
        const data = {
            student_id: currentStudentId,
            payment_date: $('#otherFeesDate').val(),
            payment_type: $('#otherFeesPaymentType').val(),
            fee_type: $('#otherFeeType').val(),
            amount: $('#otherFeesAmount').val()
        };
        if (!data.payment_date || !data.payment_type || !data.fee_type || !data.amount) return alert('Please fill all fields');
        $.post('/fees-management/add-other-charges', data, function(r) {
            if (r.success) {
                alert('Other charges added successfully');
                $('#addOtherChargesModal').modal('hide');
                if ($('#other-tab').is(':visible')) loadOtherCharges(currentStudentId);
            }
        }).fail(() => alert('Error adding other charges'));
    }

    function submitRefund() {
        if (!currentStudentId) return alert('No student selected');
        const data = {
            student_id: currentStudentId,
            refund_type: $('#refundType').val(),
            discount_percentage: $('#discountPercentage').val()
        };
        if (!data.refund_type || !data.discount_percentage) return alert('Please fill all fields');
        $.post('/fees-management/process-refund', data, function(r) {
            if (r.success) {
                alert('Refund processed successfully');
                $('#refundModal').modal('hide');
                viewDetails(currentStudentId);
            }
        }).fail(() => alert('Error processing refund'));
    }

    function submitScholarship() {
        if (!currentStudentId) return alert('No student selected');
        const data = {
            student_id: currentStudentId,
            discount_percentage: $('#scholarshipDiscountInput').val(),
            reason: $('#scholarshipReason').val()
        };
        if (!data.discount_percentage || !data.reason) return alert('Please fill all fields');
        $.post('/fees-management/apply-scholarship', data, function(r) {
            if (r.success) {
                alert('Scholarship discount applied successfully');
                $('#scholarshipModal').modal('hide');
                viewDetails(currentStudentId);
            }
        }).fail(() => alert('Error applying scholarship'));
    }

    function exportPendingFees() {
        window.location.href = '{{ route("fees.export") }}';
    }

    $(document).click(() => {
        $('.action-dropdown').removeClass('show');
        $('.refund-dropdown').removeClass('show');
    });
    </script>
</body>
</html>