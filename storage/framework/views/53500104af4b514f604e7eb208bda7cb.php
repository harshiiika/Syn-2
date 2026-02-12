<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Fees Management - Synthesis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        /* ===== RESET & BASE ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f0f0; }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            background: #fff;
            padding: 0;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-left {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0 15px;
            width: 240px;
            min-width: 240px;
            border-right: 1px solid #e0e0e0;
            background: #fff;
        }
        .header-left img { height: 45px; max-width: 130px; object-fit: contain; }
        .header-left .toggle-btn {
            background: none;
            border: none;
            font-size: 22px;
            color: #333;
            cursor: pointer;
            margin-left: auto;
            padding: 5px 10px;
        }
        .header-left .toggle-btn:hover { color: #E66A2C; }
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-right: 25px;
        }
        .session-select {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .session-select span { font-size: 14px; color: #333; font-weight: 500; }
        .session-select select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            background: #fff;
        }
        .header-icons { display: flex; align-items: center; gap: 18px; }
        .header-icons .fa-bell { font-size: 20px; color: #666; cursor: pointer; }
        .header-icons .fa-bell:hover { color: #E66A2C; }
        .header-icons .user-dropdown .btn {
            background: #6c757d;
            border: none;
            padding: 10px 14px;
            border-radius: 4px;
        }
        .header-icons .user-dropdown .btn:hover { background: #5a6268; }

        /* ===== MAIN LAYOUT ===== */
        .main-wrapper {
            display: flex;
            min-height: calc(100vh - 70px);
            background: #f0f0f0;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            min-width: 240px;
            background: #fff;
            border-right: 1px solid #e0e0e0;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        .sidebar-admin {
            padding: 18px 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
            background: #fff;
        }
        .sidebar-admin h6 { font-size: 15px; font-weight: 700; margin: 0 0 4px 0; color: #333; }
        .sidebar-admin p { font-size: 12px; color: #888; margin: 0; word-break: break-all; }

        /* Sidebar Menu */
        .sidebar-menu { padding: 0; }
        .menu-item { border-bottom: 1px solid #f0f0f0; }
        .menu-header {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .menu-header:hover { background: #f5f5f5; }
        .menu-header i.menu-icon { width: 22px; font-size: 15px; color: #666; margin-right: 12px; flex-shrink: 0; }
        .menu-header span {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .menu-header i.arrow { font-size: 11px; color: #999; transition: transform 0.2s; flex-shrink: 0; }
        .menu-header.active i.arrow { transform: rotate(180deg); }
        .menu-submenu {
            display: none;
            background: #fafafa;
        }
        .menu-submenu.show { display: block; }
        .menu-submenu a {
            display: block;
            padding: 12px 18px 12px 52px;
            font-size: 13px;
            color: #666;
            text-decoration: none;
            transition: all 0.2s;
        }
        .menu-submenu a:hover,
        .menu-submenu a.active {
            background: #E66A2C;
            color: #fff;
        }

        /* Sidebar Collapsed */
        .sidebar.collapsed { width: 65px; min-width: 65px; }
        .sidebar.collapsed .sidebar-admin { display: none; }
        .sidebar.collapsed .menu-header span,
        .sidebar.collapsed .menu-header i.arrow { display: none; }
        .sidebar.collapsed .menu-header { justify-content: center; padding: 16px 10px; }
        .sidebar.collapsed .menu-header i.menu-icon { margin: 0; font-size: 20px; }
        .sidebar.collapsed .menu-submenu { display: none !important; }

        /* ===== CONTENT AREA ===== */
        .content-area {
            flex: 1;
            background: #f0f0f0;
            min-width: 0;
            padding: 0;
        }

        /* Page Title Section */
        .page-title-section {
            padding: 25px 30px 15px;
            background: #f0f0f0;
        }
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #E66A2C;
            margin: 0;
        }

        /* Tabs Row */
        .tabs-row {
            display: flex;
            align-items: center;
            padding: 0 30px 25px;
            gap: 12px;
            background: #f0f0f0;
            flex-wrap: wrap;
        }
        .tab-btn {
            padding: 12px 28px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.2s;
        }
        .tab-btn:hover { 
            border-color: #E66A2C; 
            color: #E66A2C; 
            background: #fff5f0;
        }
        .tab-btn.active {
            background: #E66A2C;
            color: #fff;
            border-color: #E66A2C;
        }
        .export-btn {
            margin-left: auto;
            padding: 12px 24px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .export-btn:hover { background: #218838; }

        /* Tab Content - WHITE CARD on grey background */
        .tab-content {
            margin: 0 30px 30px;
            padding: 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* Search Row */
        .search-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .search-input {
            flex: 1;
            min-width: 280px;
            max-width: 550px;
            padding: 12px 18px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
        }
        .search-input:focus { outline: none; border-color: #E66A2C; }
        .btn-search {
            padding: 12px 28px;
            background: #E66A2C;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-search:hover { background: #d55a1c; }
        .btn-reset {
            padding: 12px 28px;
            background: #6c757d;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-reset:hover { background: #5a6268; }
        .dropdown-filter {
            padding: 12px 18px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            min-width: 200px;
            cursor: pointer;
            background: #fff;
        }
        .dropdown-filter:focus { outline: none; border-color: #E66A2C; }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table thead th {
            background: #fff;
            color: #E66A2C;
            font-size: 13px;
            font-weight: 600;
            padding: 14px 12px;
            text-align: left;
            border-bottom: 2px solid #eee;
            white-space: nowrap;
        }
        .data-table tbody td {
            padding: 14px 12px;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        .data-table tbody tr:hover { background: #fafafa; }

        /* Status Badges */
        .status-paid { color: #28a745; font-weight: 600; }
        .status-pending { color: #dc3545; font-weight: 600; }
        .status-badge {
            padding: 5px 14px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-badge.paid { background: #d4edda; color: #28a745; }
        .status-badge.due { background: #f8d7da; color: #dc3545; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 70px 20px;
            color: #999;
        }
        .empty-state i { font-size: 52px; margin-bottom: 18px; color: #ddd; display: block; }
        .empty-state p { font-size: 15px; margin: 0; }

        /* Action Menu */
        .action-menu { position: relative; display: inline-block; }
        .action-btn {
            background: none;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            color: #666;
            font-size: 18px;
        }
        .action-btn:hover { color: #E66A2C; }
        .action-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            min-width: 160px;
            z-index: 1000;
            display: none;
        }
        .action-dropdown.show { display: block; }
        .action-dropdown a {
            display: block;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
        }
        .action-dropdown a:hover { background: #fff5f0; color: #E66A2C; }
        .action-dropdown a i { margin-right: 10px; width: 16px; }

        /* ===== MODAL STYLES ===== */
        .modal-header-custom {
            background: #fff;
            padding: 22px;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header-custom h3 { color: #E66A2C; font-size: 22px; font-weight: 600; margin: 0; }
        .modal-body-custom { padding: 28px; }
        .modal-footer-custom {
            padding: 22px;
            border-top: 1px solid #e5e5e5;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: #fafafa;
        }

        /* Form Styles */
        .form-group-custom { margin-bottom: 22px; }
        .form-group-custom label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .form-group-custom input,
        .form-group-custom select,
        .form-group-custom textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
        }
        .form-group-custom input:focus,
        .form-group-custom select:focus,
        .form-group-custom textarea:focus {
            outline: none;
            border-color: #E66A2C;
            box-shadow: 0 0 0 3px rgba(230, 106, 44, 0.1);
        }

        /* Button Styles */
        .btn-cancel-custom {
            padding: 12px 28px;
            background: #fff;
            color: #666;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-cancel-custom:hover { background: #f5f5f5; }
        .btn-submit-custom {
            padding: 12px 28px;
            background: #E66A2C;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-submit-custom:hover { background: #d55a1c; }

        /* Fees Details Container */
        .fees-details-container { background: #fff; border-radius: 8px; }
        .fees-header {
            background: #fff;
            padding: 22px;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .fees-header h2 { color: #E66A2C; font-size: 26px; font-weight: 600; margin: 0; }
        .back-link {
            color: #E66A2C;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        .back-link:hover { text-decoration: underline; }

        /* Billing Info Section */
        .billing-info-section {
            background: #f8f9fa;
            padding: 22px;
            margin: 22px;
            border-radius: 8px;
        }
        .billing-info-section h5 {
            color: #E66A2C;
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 22px;
        }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; }
        .info-row { display: flex; margin-bottom: 14px; }
        .info-label { font-weight: 600; color: #333; min-width: 150px; font-size: 14px; }
        .info-value { color: #666; font-size: 14px; }

        /* Detail Nav Tabs */
        .detail-nav-tabs {
            display: flex;
            gap: 12px;
            padding: 18px 22px;
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            flex-wrap: wrap;
            align-items: center;
        }
        .detail-nav-btn {
            padding: 10px 22px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #666;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .detail-nav-btn.active {
            background: #E66A2C;
            color: #fff;
            border-color: #E66A2C;
        }
        .detail-nav-btn:hover:not(.active) { background: #f5f5f5; border-color: #E66A2C; color: #E66A2C; }
        .btn-add-charges {
            padding: 10px 22px;
            background: #E66A2C;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            margin-left: auto;
        }
        .btn-add-charges:hover { background: #d55a1c; }
        .btn-refund {
            padding: 10px 22px;
            background: #E66A2C;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            position: relative;
        }
        .btn-refund:hover { background: #d55a1c; }
        .refund-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            min-width: 200px;
            display: none;
            margin-top: 5px;
            z-index: 1000;
        }
        .refund-dropdown.show { display: block; }
        .refund-dropdown-item {
            padding: 12px 18px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
            transition: all 0.2s;
        }
        .refund-dropdown-item:hover { background: #fff5f0; color: #E66A2C; }

        /* Payment Table */
        .payment-table { width: 100%; border-collapse: collapse; margin-top: 22px; }
        .payment-table thead { background: #f8f9fa; }
        .payment-table th {
            padding: 14px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #E66A2C;
            border-bottom: 2px solid #ddd;
        }
        .payment-table td {
            padding: 14px;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
        }

        /* History Summary */
        .history-summary {
            background: #f8f9fa;
            padding: 22px;
            border-radius: 8px;
            margin-bottom: 28px;
        }
        .history-summary h5 {
            color: #E66A2C;
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 18px;
        }
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
        .summary-card {
            background: #fff;
            padding: 18px;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #e5e5e5;
        }
        .summary-card .label { font-size: 12px; color: #666; margin-bottom: 6px; }
        .summary-card .value { font-size: 22px; font-weight: 600; color: #E66A2C; }

        /* Scholarship Info */
        .scholarship-info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .scholarship-info-row span:first-child { font-weight: 600; color: #333; }
        .scholarship-info-row span:last-child { color: #666; }

        /* Installment Grid */
        .installment-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; margin-top: 22px; }
        .installment-box {
            text-align: center;
            padding: 18px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .installment-box label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .installment-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        /* Detail Tab Content */
        .detail-tab-content { padding: 22px; }

        /* DataTables Override */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 14px;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-left { width: 180px; min-width: 180px; }
            .sidebar { width: 200px; min-width: 200px; }
            .sidebar.collapsed { width: 55px; min-width: 55px; }
            .info-grid { grid-template-columns: 1fr; }
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
            .installment-grid { grid-template-columns: 1fr; }
            .tab-content { margin: 0 15px 15px; padding: 20px; }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <img src="<?php echo e(asset('images/logo.png.jpg')); ?>" alt="Synthesis">
            <button class="toggle-btn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
        </div>
        <div class="header-right">
            <div class="session-select">
                <span>Session:</span>
                <select id="sessionSelect">
                    <option>2025-2026</option>
                    <option>2024-2025</option>
                </select>
            </div>
            <div class="header-icons">
                <i class="fa-solid fa-bell"></i>
                <div class="dropdown user-dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>"><i class="fa-solid fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Log Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        <!-- SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-admin">
                <h6>Admin</h6>
                <p>synthesisbikaner@gmail.com</p>
            </div>
            <div class="sidebar-menu">
                <!-- User Management -->
                <div class="menu-item">
                    <div class="menu-header" onclick="toggleMenu(this)">
                        <i class="fa-solid fa-users menu-icon"></i>
                        <span>User Management</span>
                        <i class="fa-solid fa-chevron-down arrow"></i>
                    </div>
                    <div class="menu-submenu">
                        <a href="<?php echo e(route('user.emp.emp')); ?>">Employee</a>
                        <a href="<?php echo e(route('user.batches.batches')); ?>">Batches Assignment</a>
                    </div>
                </div>
                <!-- Master -->
                <div class="menu-item">
                    <div class="menu-header" onclick="toggleMenu(this)">
                        <i class="fa-solid fa-database menu-icon"></i>
                        <span>Master</span>
                        <i class="fa-solid fa-chevron-down arrow"></i>
                    </div>
                    <div class="menu-submenu">
                        <a href="<?php echo e(route('courses.index')); ?>">Courses</a>
                        <a href="<?php echo e(route('batches.index')); ?>">Batches</a>
                        <a href="<?php echo e(route('master.scholarship.index')); ?>">Scholarship</a>
                        <a href="<?php echo e(route('fees.index')); ?>">Fees Master</a>
                        <a href="<?php echo e(route('master.other_fees.index')); ?>">Other Fees Master</a>
                        <a href="<?php echo e(route('branches.index')); ?>">Branch Management</a>
                    </div>
                </div>
                <!-- Session Management -->
                <div class="menu-item">
                    <div class="menu-header" onclick="toggleMenu(this)">
                        <i class="fa-solid fa-calendar menu-icon"></i>
                        <span>Session Managem...</span>
                        <i class="fa-solid fa-chevron-down arrow"></i>
                    </div>
                    <div class="menu-submenu">
                        <a href="<?php echo e(route('sessions.index')); ?>">Session</a>
                        <a href="<?php echo e(route('calendar.index')); ?>">Calendar</a>
                        <a href="#">Student Migrate</a>
                    </div>
                </div>
                <!-- Student Management -->
                <div class="menu-item">
                    <div class="menu-header" onclick="toggleMenu(this)">
                        <i class="fa-solid fa-user-graduate menu-icon"></i>
                        <span>Student Managem...</span>
                        <i class="fa-solid fa-chevron-down arrow"></i>
                    </div>
                    <div class="menu-submenu">
                        <a href="<?php echo e(route('inquiries.index')); ?>">Inquiry Management</a>
                        <a href="<?php echo e(route('student.student.pending')); ?>">Student Onboard</a>
                        <a href="<?php echo e(route('student.pendingfees.pending')); ?>">Pending Fees Students</a>
                        <a href="<?php echo e(route('smstudents.index')); ?>">Students</a>
                    </div>
                </div>
                <!-- Fees Management -->
                <div class="menu-item">
                    <div class="menu-header" onclick="toggleMenu(this)">
                        <i class="fa-solid fa-credit-card menu-icon"></i>
                        <span>Fees Management</span>
                        <i class="fa-solid fa-chevron-down arrow"></i>
                    </div>
                    <div class="menu-submenu">
                        <a href="<?php echo e(route('fees.management.index')); ?>" class="active">Fees Collection</a>
                    </div>
                </div>
                <!-- Attendance Management -->
                <div class="menu-item">
                    <div class="menu-header" onclick="toggleMenu(this)">
                        <i class="fa-solid fa-clipboard-check menu-icon"></i>
                        <span>Attendance Mana...</span>
                        <i class="fa-solid fa-chevron-down arrow"></i>
                    </div>
                    <div class="menu-submenu">
                        <a href="<?php echo e(route('attendance.employee.index')); ?>">Employee</a>
                        <a href="<?php echo e(route('attendance.student.index')); ?>">Student</a>
                    </div>
                </div>
                <!-- Study Material -->
                <div class="menu-item">
                    <div class="menu-header" onclick="toggleMenu(this)">
                        <i class="fa-solid fa-book menu-icon"></i>
                        <span>Study Material Co...</span>
                        <i class="fa-solid fa-chevron-down arrow"></i>
                    </div>
                    <div class="menu-submenu">
                        <a href="<?php echo e(route('units.index')); ?>">Units</a>
                        <a href="<?php echo e(route('dispatch.index')); ?>">Dispatch Material</a>
                    </div>
                </div>
                <!-- Test Series Management -->
                <div class="menu-item">
                    <div class="menu-header" onclick="toggleMenu(this)">
                        <i class="fa-solid fa-chart-bar menu-icon"></i>
                        <span>Test Series Manag...</span>
                        <i class="fa-solid fa-chevron-down arrow"></i>
                    </div>
                    <div class="menu-submenu">
                        <a href="<?php echo e(route('test_series.index')); ?>">Test Master</a>
                    </div>
                </div>
                <!-- Reports -->
                <div class="menu-item">
                    <div class="menu-header" onclick="toggleMenu(this)">
                        <i class="fa-solid fa-file-alt menu-icon"></i>
                        <span>Reports</span>
                        <i class="fa-solid fa-chevron-down arrow"></i>
                    </div>
                    <div class="menu-submenu">
                        <a href="<?php echo e(route('reports.walkin.index')); ?>">Walk In</a>
                        <a href="<?php echo e(route('reports.attendance.student.index')); ?>">Attendance</a>
                        <a href="#">Test Series</a>
                        <a href="<?php echo e(route('inquiries.index')); ?>">Inquiry History</a>
                        <a href="#">Onboard History</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="content-area">
            <div class="page-title-section">
                <h2 class="page-title">Fees Management</h2>
            </div>

            <div class="tabs-row">
                <button class="tab-btn active" data-tab="collect">Collect Fees</button>
                <button class="tab-btn" data-tab="status">Fee Status</button>
                <button class="tab-btn" data-tab="transaction">Daily Transaction</button>
                <button class="export-btn" onclick="exportPendingFees()">
                    <i class="fa-solid fa-download me-1"></i> Pending Fees List Export
                </button>
            </div>

            <div class="tab-content">
                <!-- Collect Fees Tab -->
                <div id="collect" class="tab-panel active">
                    <div class="search-row">
                        <input type="text" id="collectSearchInput" class="search-input" placeholder="Search by name">
                        <button class="btn-search" onclick="performCollectSearch()">
                            <i class="fa-solid fa-search me-1"></i> Search
                        </button>
                        <button class="btn-reset" onclick="resetCollectSearch()">
                            <i class="fa-solid fa-redo me-1"></i> Reset
                        </button>
                    </div>
                    <div id="collectTableWrapper">
                        <table class="data-table" id="collectTable">
                            <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Roll No.</th>
                                    <th>Student Name</th>
                                    <th>Father Name</th>
                                    <th>Course Content</th>
                                    <th>Course Name</th>
                                    <th>Delivery Mode</th>
                                    <th>Fees Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="collectTableBody"></tbody>
                        </table>
                    </div>
                    <div id="collectEmptyState" class="empty-state">
                        <i class="fa-solid fa-search"></i>
                        <p>Enter a name or roll number and click Search</p>
                    </div>
                </div>

                <!-- Fee Status Tab -->
                <div id="status" class="tab-panel">
                    <div class="search-row">
                        <select id="courseSelect" class="dropdown-filter">
                            <option value="">Select Course</option>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course['id']); ?>"><?php echo e($course['name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <i class="fa-solid fa-search me-1"></i> Search
                        </button>
                    </div>
                    <div id="statusTableWrapper" style="display: none;">
                        <table class="data-table" id="statusTable">
                            <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Roll No.</th>
                                    <th>Student Name</th>
                                    <th>Father Name</th>
                                    <th>Course Content</th>
                                    <th>Course Name</th>
                                    <th>Delivery Mode</th>
                                    <th>Fees Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="statusTableBody"></tbody>
                        </table>
                    </div>
                    <div id="statusEmptyState" class="empty-state">
                        <i class="fa-solid fa-filter"></i>
                        <p>Select filters and search to view fee status</p>
                    </div>
                </div>

                <!-- Daily Transaction Tab -->
                <div id="transaction" class="tab-panel">
                    <div class="search-row">
                        <label style="font-weight: 500;">From:</label>
                        <input type="date" id="fromDate" class="dropdown-filter">
                        <label style="font-weight: 500;">To:</label>
                        <input type="date" id="toDate" class="dropdown-filter">
                        <button class="btn-search" onclick="filterTransactions()">
                            <i class="fa-solid fa-search me-1"></i> Search
                        </button>
                    </div>
                    <div id="transactionTableWrapper" style="display: none;">
                        <table class="data-table" id="transactionTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Roll No</th>
                                    <th>Course</th>
                                    <th>Session</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
                                    <th>Transaction #</th>
                                </tr>
                            </thead>
                            <tbody id="transactionTableBody"></tbody>
                        </table>
                    </div>
                    <div id="transactionEmptyState" class="empty-state">
                        <i class="fa-solid fa-exchange-alt"></i>
                        <p>Select date range and click Search</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 90%;">
            <div class="modal-content" style="border: none; border-radius: 8px;">
                <div class="fees-details-container">
                    <div class="fees-header">
                        <h2>Fees Details</h2>
                        <a href="#" class="back-link" data-bs-dismiss="modal">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
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
                                    <span class="info-value" id="modal-course-type">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Course Content</span>
                                    <span class="info-value" id="modal-course-content">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Batch Name</span>
                                    <span class="info-value" id="modal-batch-name">-</span>
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
                                    <span class="info-value" id="modal-batch-start">-</span>
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
                                <tr><td colspan="8" style="text-align: center;">Loading...</td></tr>
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
                                <tr><td colspan="8" style="text-align: center;">No payment history available</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="other-tab" class="detail-tab-content" style="display: none;">
                        <h5 style="color: #E66A2C; font-weight: 600; margin-bottom: 20px;">Other Charge History</h5>
                        <p style="text-align: center; color: #999;">No other charges found.</p>
                    </div>

                    <div id="transaction-detail-tab" class="detail-tab-content" style="display: none;">
                        <h5 style="color: #E66A2C; font-weight: 600; margin-bottom: 20px;">Transaction History</h5>
                        <p style="text-align: center; color: #999;">No transactions found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Other Charges Modal -->
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
                    </form>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-submit-custom" onclick="submitOtherFees()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Refund Modal -->
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

    <!-- Scholarship Modal -->
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
                        <span id="scholarship-total-paid">0</span>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Data from Laravel
        const coursesBatchesMapping = <?php echo json_encode($coursesBatchesMapping ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
        let currentStudentId = null;
        let transactionsLoaded = false;

        $(document).ready(function() {
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // Sidebar toggle
            $('#toggleBtn').click(function() {
                $('#sidebar').toggleClass('collapsed');
            });

            // Tab switching
            $('.tab-btn').click(function() {
                const tab = $(this).data('tab');
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');
                $('.tab-panel').removeClass('active');
                $('#' + tab).addClass('active');
                if (tab === 'transaction' && !transactionsLoaded) {
                    loadAllTransactions();
                }
            });

            // Course/Batch dropdown dependency
            $('#courseSelect').change(function() {
                const courseId = $(this).val();
                const $batchSelect = $('#batchSelect');
                if (courseId && coursesBatchesMapping[courseId]) {
                    let options = '<option value="">All Batches</option>';
                    coursesBatchesMapping[courseId].forEach(function(batch) {
                        options += `<option value="${batch.id}">${batch.name}</option>`;
                    });
                    $batchSelect.html(options).prop('disabled', false);
                } else {
                    $batchSelect.html('<option value="">Select Batch</option>').prop('disabled', true);
                }
            });

            // Enter key for search
            $('#collectSearchInput').keypress(function(e) {
                if (e.which === 13) performCollectSearch();
            });

            // Set default dates for transactions
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            $('#fromDate').val(formatDate(firstDay));
            $('#toDate').val(formatDate(today));

            // Close dropdowns on outside click
            $(document).click(function(e) {
                if (!$(e.target).closest('.btn-refund').length) {
                    $('.refund-dropdown').removeClass('show');
                }
                if (!$(e.target).closest('.action-menu').length) {
                    $('.action-dropdown').removeClass('show');
                }
            });
        });

        // Toggle sidebar menu
        function toggleMenu(el) {
            const submenu = el.nextElementSibling;
            const isOpen = submenu.classList.contains('show');
            
            // Close all submenus
            document.querySelectorAll('.menu-submenu').forEach(m => m.classList.remove('show'));
            document.querySelectorAll('.menu-header').forEach(h => h.classList.remove('active'));
            
            // Open clicked one if it was closed
            if (!isOpen) {
                submenu.classList.add('show');
                el.classList.add('active');
            }
        }

        // Format date
        function formatDate(d) {
            return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
        }

        // Collect Fees Search
        function performCollectSearch() {
            const term = $('#collectSearchInput').val().trim();
            if (!term) {
                alert('Please enter a search term');
                return;
            }
            
            $('#collectEmptyState').html('<i class="fa-solid fa-spinner fa-spin"></i><p>Searching...</p>').show();
            $('#collectTableWrapper').hide();

            $.post('<?php echo e(route("fees.collect.search")); ?>', { search: term }, function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    renderCollectTable(response.data);
                    $('#collectTableWrapper').show();
                    $('#collectEmptyState').hide();
                } else {
                    $('#collectTableBody').html('');
                    $('#collectTableWrapper').hide();
                    $('#collectEmptyState').html('<i class="fa-solid fa-info-circle"></i><p>No results found</p>').show();
                }
            }).fail(function() {
                $('#collectEmptyState').html('<i class="fa-solid fa-times-circle"></i><p>Error searching. Please try again.</p>').show();
            });
        }

        // Reset Collect Search
        function resetCollectSearch() {
            $('#collectSearchInput').val('');
            $('#collectTableBody').html('');
            $('#collectTableWrapper').hide();
            $('#collectEmptyState').html('<i class="fa-solid fa-search"></i><p>Enter a name or roll number and click Search</p>').show();
        }

        // Render Collect Table
        function renderCollectTable(data) {
            let html = '';
            data.forEach(function(student, index) {
                const statusClass = student.fee_status && student.fee_status.toLowerCase() === 'paid' ? 'status-paid' : 'status-pending';
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${student.roll_no || 'N/A'}</td>
                        <td><strong>${student.name || 'N/A'}</strong></td>
                        <td>${student.father_name || 'N/A'}</td>
                        <td>${student.course_content || 'N/A'}</td>
                        <td>${student.course_name || 'N/A'}</td>
                        <td>${student.delivery_mode || 'N/A'}</td>
                        <td><span class="${statusClass}">${student.fee_status || 'Pending'}</span></td>
                        <td>
                            <div class="action-menu">
                                <button class="action-btn" onclick="toggleActionMenu(event, 'collect-${student.id}')">
                                    <i class="fa-solid fa-ellipsis-v"></i>
                                </button>
                                <div class="action-dropdown" id="action-collect-${student.id}">
                                    <a href="#" onclick="viewDetails('${student.id}'); return false;">
                                        <i class="fa-solid fa-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });
            $('#collectTableBody').html(html);
        }

        // Search by Status
        function searchByStatus() {
            const status = $('#feeStatusSelect').val();
            if (!status) {
                alert('Please select a fee status');
                return;
            }

            $('#statusEmptyState').html('<i class="fa-solid fa-spinner fa-spin"></i><p>Loading...</p>').show();
            $('#statusTableWrapper').hide();

            $.post('<?php echo e(route("fees.status.search")); ?>', {
                course_id: $('#courseSelect').val(),
                batch_id: $('#batchSelect').val(),
                fee_status: status
            }, function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    renderStatusTable(response.data);
                    $('#statusTableWrapper').show();
                    $('#statusEmptyState').hide();
                } else {
                    $('#statusTableBody').html('');
                    $('#statusTableWrapper').hide();
                    $('#statusEmptyState').html('<i class="fa-solid fa-info-circle"></i><p>No students found</p>').show();
                }
            }).fail(function() {
                $('#statusEmptyState').html('<i class="fa-solid fa-times-circle"></i><p>Error loading data</p>').show();
            });
        }

        // Render Status Table
        function renderStatusTable(data) {
            let html = '';
            data.forEach(function(student, index) {
                const statusClass = student.fee_status && student.fee_status.toLowerCase() === 'paid' ? 'status-paid' : 'status-pending';
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${student.roll_no || 'N/A'}</td>
                        <td><strong>${student.name || 'N/A'}</strong></td>
                        <td>${student.father_name || 'N/A'}</td>
                        <td>${student.course_content || 'N/A'}</td>
                        <td>${student.course_name || 'N/A'}</td>
                        <td>${student.delivery_mode || 'N/A'}</td>
                        <td><span class="${statusClass}">${student.fee_status || 'Pending'}</span></td>
                        <td>
                            <div class="action-menu">
                                <button class="action-btn" onclick="toggleActionMenu(event, 'status-${student.id}')">
                                    <i class="fa-solid fa-ellipsis-v"></i>
                                </button>
                                <div class="action-dropdown" id="action-status-${student.id}">
                                    <a href="#" onclick="viewDetails('${student.id}'); return false;">
                                        <i class="fa-solid fa-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });
            $('#statusTableBody').html(html);
        }

        // Load All Transactions
        function loadAllTransactions() {
            const fromDate = $('#fromDate').val();
            const toDate = $('#toDate').val();

            $('#transactionEmptyState').html('<i class="fa-solid fa-spinner fa-spin"></i><p>Loading transactions...</p>').show();
            $('#transactionTableWrapper').hide();

            $.post('<?php echo e(route("fees.transaction.filter")); ?>', {
                from_date: fromDate,
                to_date: toDate
            }, function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    renderTransactionTable(response.data);
                    $('#transactionTableWrapper').show();
                    $('#transactionEmptyState').hide();
                    transactionsLoaded = true;
                } else {
                    $('#transactionTableBody').html('');
                    $('#transactionTableWrapper').hide();
                    $('#transactionEmptyState').html('<i class="fa-solid fa-info-circle"></i><p>No transactions found</p>').show();
                }
            }).fail(function() {
                $('#transactionEmptyState').html('<i class="fa-solid fa-times-circle"></i><p>Error loading transactions</p>').show();
            });
        }

        // Filter Transactions
        function filterTransactions() {
            const fromDate = $('#fromDate').val();
            const toDate = $('#toDate').val();
            if (!fromDate || !toDate) {
                alert('Please select both dates');
                return;
            }
            loadAllTransactions();
        }

        // Render Transaction Table
        function renderTransactionTable(data) {
            let html = '';
            data.forEach(function(transaction, index) {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${transaction.student_name || 'N/A'}</td>
                        <td>${transaction.student_roll_no || transaction.roll_no || 'N/A'}</td>
                        <td>${transaction.course || 'N/A'}</td>
                        <td>${transaction.session || '2025-2026'}</td>
                        <td><strong>₹${transaction.amount || 0}</strong></td>
                        <td>${transaction.payment_type || 'Cash'}</td>
                        <td>${transaction.transaction_number || transaction.transaction_id || 'N/A'}</td>
                    </tr>
                `;
            });
            $('#transactionTableBody').html(html);
        }

        // Toggle Action Menu
        function toggleActionMenu(event, id) {
            event.stopPropagation();
            $('.action-dropdown').removeClass('show');
            $('#action-' + id).toggleClass('show');
        }

        // View Details
        function viewDetails(studentId) {
            currentStudentId = studentId;
            $('.action-dropdown').removeClass('show');

            $.ajax({
                url: `/fees-management/student-details/${studentId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        const data = response.data;
                        $('#modal-student-name').text(data.student_name || '-');
                        $('#modal-father-name').text(data.father_name || '-');
                        $('#modal-course-type').text(data.course_type || '-');
                        $('#modal-course-name').text(data.course_name || '-');
                        $('#modal-course-content').text(data.course_content || '-');
                        $('#modal-batch-name').text(data.batch_name || '-');
                        $('#modal-batch-start').text(data.batch_start_date || '-');
                        $('#modal-delivery-mode').text(data.delivery_mode || '-');
                        $('#scholarship-total-paid').text(data.paid_fees || '0');
                        $('#scholarship-eligible').text(data.scholarship_eligible || 'No');
                        $('#scholarship-discretionary').text(data.discretionary_discount || 'No');
                        $('#scholarship-discount-percent').text(data.discount_percent || '0');

                        if (data.installments) {
                            $('.installment-box:eq(0) input').val(data.installments[0] || '0');
                            $('.installment-box:eq(1) input').val(data.installments[1] || '0');
                            $('.installment-box:eq(2) input').val(data.installments[2] || '0');
                        }

                        loadViewDetails(studentId);
                        $('#viewDetailsModal').modal('show');
                    }
                },
                error: function() {
                    alert('Error loading student details');
                }
            });
        }

        // Switch Detail Tab
        function switchDetailTab(tabName) {
            $('.detail-tab-content').hide();
            $('.detail-nav-btn').removeClass('active');
            $('#' + tabName + '-tab').show();
            
            $('.detail-nav-btn').each(function() {
                if ($(this).attr('onclick') && $(this).attr('onclick').includes(tabName)) {
                    $(this).addClass('active');
                }
            });

            if (currentStudentId) {
                if (tabName === 'view') loadViewDetails(currentStudentId);
                else if (tabName === 'installment') loadInstallmentHistory(currentStudentId);
                else if (tabName === 'other') loadOtherCharges(currentStudentId);
                else if (tabName === 'transaction-detail') loadTransactionHistory(currentStudentId);
            }
        }

        // Load View Details
        function loadViewDetails(studentId) {
            $.ajax({
                url: `/fees-management/installment-history/${studentId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        let html = '';
                        response.data.forEach(function(inst) {
                            const statusClass = inst.status === 'Paid' ? 'paid' : 'due';
                            html += `
                                <tr>
                                    <td>${inst.installment_no}</td>
                                    <td>₹${inst.actual_amount}</td>
                                    <td>₹${inst.paid_amount}</td>
                                    <td>${inst.due_date || '-'}</td>
                                    <td>${inst.payment_date || '-'}</td>
                                    <td><span class="status-badge ${statusClass}">${inst.status}</span></td>
                                    <td>${inst.single_installment || 'No'}</td>
                                    <td><i class="fa-solid fa-ellipsis-v" style="cursor: pointer; color: #666;"></i></td>
                                </tr>
                            `;
                        });
                        $('#viewDetailsTableBody').html(html);
                    } else {
                        $('#viewDetailsTableBody').html('<tr><td colspan="8" style="text-align: center;">No payment details available</td></tr>');
                    }
                },
                error: function() {
                    $('#viewDetailsTableBody').html('<tr><td colspan="8" style="text-align: center; color: #dc3545;">Error loading payment details</td></tr>');
                }
            });
        }

        // Load Installment History
        function loadInstallmentHistory(studentId) {
            $.ajax({
                url: `/fees-management/installment-history/${studentId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        let totalInstallments = response.data.length;
                        let totalPaid = 0;
                        let totalPending = 0;
                        let lastPaymentDate = '-';

                        response.data.forEach(function(inst) {
                            totalPaid += parseFloat(inst.paid_amount) || 0;
                            if (inst.status === 'Paid' && inst.payment_date && inst.payment_date !== '-') {
                                lastPaymentDate = inst.payment_date;
                            } else {
                                totalPending += parseFloat(inst.actual_amount) || 0;
                            }
                        });

                        $('#hist-total-installments').text(totalInstallments);
                        $('#hist-paid-amount').text('₹' + totalPaid.toLocaleString());
                        $('#hist-pending-amount').text('₹' + totalPending.toLocaleString());
                        $('#hist-last-payment').text(lastPaymentDate);

                        let html = '';
                        response.data.forEach(function(inst, index) {
                            const statusClass = inst.status === 'Paid' ? 'paid' : 'due';
                            html += `
                                <tr>
                                    <td><strong>${index + 1}</strong></td>
                                    <td>${inst.installment_no}</td>
                                    <td><strong>₹${inst.paid_amount || inst.actual_amount}</strong></td>
                                    <td>${inst.payment_date || '-'}</td>
                                    <td>${inst.payment_type || 'Cash'}</td>
                                    <td>${inst.transaction_id || '-'}</td>
                                    <td><span class="status-badge ${statusClass}">${inst.status}</span></td>
                                    <td>${inst.remarks || '-'}</td>
                                </tr>
                            `;
                        });
                        $('#installmentHistoryTableBody').html(html);
                    } else {
                        $('#hist-total-installments').text('0');
                        $('#hist-paid-amount').text('₹0');
                        $('#hist-pending-amount').text('₹0');
                        $('#hist-last-payment').text('-');
                        $('#installmentHistoryTableBody').html('<tr><td colspan="8" style="text-align: center;">No payment history available</td></tr>');
                    }
                }
            });
        }

        // Load Other Charges
        function loadOtherCharges(studentId) {
            $.ajax({
                url: `/fees-management/other-charges/${studentId}`,
                method: 'GET',
                success: function(response) {
                    let html = '<h5 style="color: #E66A2C; font-weight: 600; margin-bottom: 20px;">Other Charge History</h5>';
                    if (response.success && response.data && response.data.length > 0) {
                        html += '<table class="payment-table"><thead><tr><th>S.No.</th><th>Payment Date</th><th>Fee Type</th><th>Amount</th></tr></thead><tbody>';
                        response.data.forEach(function(charge, index) {
                            html += `<tr><td>${index + 1}</td><td>${charge.payment_date}</td><td>${charge.fee_type}</td><td>₹${charge.amount}</td></tr>`;
                        });
                        html += '</tbody></table>';
                    } else {
                        html += '<p style="text-align: center; margin: 40px 0; color: #999;">No other charges found</p>';
                    }
                    $('#other-tab').html(html);
                }
            });
        }

        // Load Transaction History
        function loadTransactionHistory(studentId) {
            $.ajax({
                url: `/fees-management/transaction-history/${studentId}`,
                method: 'GET',
                success: function(response) {
                    let html = '<h5 style="color: #E66A2C; font-weight: 600; margin-bottom: 20px;">Transaction History</h5>';
                    if (response.success && response.data && response.data.length > 0) {
                        html += '<table class="payment-table"><thead><tr><th>S.No.</th><th>Transaction Id</th><th>Transaction Type</th><th>Payment Date</th><th>Amount</th></tr></thead><tbody>';
                        response.data.forEach(function(t) {
                            html += `<tr><td>${t.sr_no}</td><td>${t.transaction_id}</td><td>${t.transaction_type}</td><td>${t.payment_date}</td><td>₹${t.amount}</td></tr>`;
                        });
                        html += '</tbody></table>';
                    } else {
                        html += '<p style="text-align: center; margin: 40px 0; color: #999;">No transactions found</p>';
                    }
                    $('#transaction-detail-tab').html(html);
                }
            });
        }

        // Toggle Refund Dropdown
        function toggleRefundDropdown(event) {
            event.stopPropagation();
            $('#refundDropdown').toggleClass('show');
        }

        // Open Add Other Charges Modal
        function openAddOtherChargesModal() {
            $('#addOtherChargesModal').modal('show');
            $('#otherFeesDate').val(new Date().toISOString().split('T')[0]);
        }

        // Open Refund Modal
        function openRefundModal() {
            $('#refundDropdown').removeClass('show');
            $('#refundModal').modal('show');
        }

        // Open Scholarship Modal
        function openScholarshipModal() {
            $('#refundDropdown').removeClass('show');
            $('#scholarshipModal').modal('show');
        }

        // Submit Other Fees
        function submitOtherFees() {
            if (!currentStudentId) {
                alert('No student selected');
                return;
            }
            const data = {
                student_id: currentStudentId,
                payment_date: $('#otherFeesDate').val(),
                payment_type: $('#otherFeesPaymentType').val(),
                fee_type: $('#otherFeeType').val(),
                amount: $('#otherFeesAmount').val()
            };
            if (!data.payment_date || !data.payment_type || !data.fee_type || !data.amount) {
                alert('Please fill all fields');
                return;
            }
            $.post('/fees-management/add-other-charges', data, function(response) {
                if (response.success) {
                    alert('Other charges added successfully');
                    $('#addOtherChargesModal').modal('hide');
                    if ($('#other-tab').is(':visible')) loadOtherCharges(currentStudentId);
                }
            }).fail(function() {
                alert('Error adding other charges');
            });
        }

        // Submit Refund
        function submitRefund() {
            if (!currentStudentId) {
                alert('No student selected');
                return;
            }
            const data = {
                student_id: currentStudentId,
                refund_type: $('#refundType').val(),
                discount_percentage: $('#discountPercentage').val()
            };
            if (!data.refund_type || !data.discount_percentage) {
                alert('Please fill all fields');
                return;
            }
            $.post('/fees-management/process-refund', data, function(response) {
                if (response.success) {
                    alert('Refund processed successfully');
                    $('#refundModal').modal('hide');
                    viewDetails(currentStudentId);
                }
            }).fail(function() {
                alert('Error processing refund');
            });
        }

        // Submit Scholarship
        function submitScholarship() {
            if (!currentStudentId) {
                alert('No student selected');
                return;
            }
            const data = {
                student_id: currentStudentId,
                discount_percentage: $('#scholarshipDiscountInput').val(),
                reason: $('#scholarshipReason').val()
            };
            if (!data.discount_percentage || !data.reason) {
                alert('Please fill all fields');
                return;
            }
            $.post('/fees-management/apply-scholarship', data, function(response) {
                if (response.success) {
                    alert('Scholarship discount applied successfully');
                    $('#scholarshipModal').modal('hide');
                    viewDetails(currentStudentId);
                }
            }).fail(function() {
                alert('Error applying scholarship');
            });
        }

        // Export Pending Fees
        function exportPendingFees() {
            window.location.href = '<?php echo e(route("fees.export")); ?>';
        }
    </script>
</body>
</html><?php /**PATH C:\Users\dhamu\Syn-2\resources\views/fees_management/index.blade.php ENDPATH**/ ?>