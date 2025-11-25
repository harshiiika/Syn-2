<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="your-csrf-token-here">
    <title>Dispatch Material</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            position: relative;
            overflow-x: hidden;
        }

        .top {
            display: flex;
            flex-direction: row;
            border-bottom: 1px solid #e0e0e0;
        }

        .header {
            display: flex;
            flex-direction: row;
            width: 300px;
            height: 60px;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px;
        }

        .logo {
            width: 150px;
            height: 45px;
        }

        .fa-bars {
            cursor: pointer;
            font-size: 20px;
            width: 35px;
            height: 35px;
            text-align: center;
            justify-content: center;
            align-items: center;
            display: flex;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .fa-bars:hover {
            background-color: #f0f0f0;
        }

        .main-container {
            display: flex;
            flex-direction: row;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }

        .session {
            display: flex;
            flex-direction: row;
            width: 100%;
            align-items: center;
            justify-content: flex-end;
            gap: 15px;
            margin-right: 30px;
            font-weight: 600;
            font-size: 16px;
        }

        .select {
            width: 100px;
            height: 32px;
            border: 2px solid rgb(233, 96, 47);
            border-radius: 5px;
            font-size: 14px;
            padding: 0 5px;
        }

        .left {
            display: flex;
            flex-direction: column;
            width: 250px;
            min-width: 250px;
            max-width: 250px;
            height: calc(100vh - 60px);
            background-color: #fff;
            border-right: 1px solid #e0e0e0;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .admin {
            padding: 15px 0;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .admin h2 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 3px;
            color: #333;
        }

        .admin h4 {
            font-size: 11px;
            font-weight: normal;
            color: #666;
        }

        .accordion {
            border: none;
        }

        .accordion-item {
            border: none;
            margin-bottom: 0;
        }

        .accordion-header {
            margin-bottom: 0;
        }

        .accordion-button {
            padding: 12px 15px;
            font-size: 14px;
            background-color: #fff;
            border: none;
            box-shadow: none !important;
            color: #333;
            font-weight: 400;
        }

        .accordion-button:not(.collapsed) {
            background-color: #fff;
            color: #333;
            box-shadow: none;
        }

        .accordion-button.collapsed {
            background-color: #fff;
        }

        .accordion-button::after {
            content: none !important;
            display: none !important;
        }

        .accordion-button:focus {
            box-shadow: none;
            border: none;
        }

        .accordion-button:hover {
            background-color: #f8f8f8;
        }

        .accordion-body {
            padding: 0;
        }

        /* Critical: Always show icons */
        .accordion-button i {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            color: #666 !important;
            font-size: 16px !important;
            margin-right: 10px !important;
            min-width: 20px;
        }

        .accordion-button.collapsed i {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            color: #666 !important;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            padding: 0;
            margin: 0;
        }

        .menu li a {
            display: flex;
            align-items: center;
            padding: 8px 15px 8px 30px;
            text-decoration: none;
            color: #555;
            font-size: 13px;
        }

        .menu li a:hover {
            background-color: #f5f5f5;
            color: rgb(233, 96, 47);
        }

        .menu li a i {
            font-size: 13px;
            margin-right: 8px;
            color: #888;
        }

        .fa-solid,
        .fa-regular {
            font-size: 14px;
        }

        .right {
            display: flex;
            flex-direction: column;
            background-color: #f6f6f6;
            flex: 1;
            height: calc(100vh - 60px);
            overflow-y: auto;
            padding: 20px;
        }

        .right h5 {
            margin: 0 0 20px 0;
            font-size: 20px;
            font-weight: 600;
            color: rgb(233, 96, 47);
        }

        .upper {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .upper > div {
            display: flex;
            flex-direction: column;
        }

        #course,
        #batch {
            width: 300px;
            height: 40px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
            padding: 8px 12px;
            background-color: #fff;
        }

        #course:focus,
        #batch:focus {
            border: 2px solid rgb(233, 96, 47);
            outline: none;
        }

        .error-message {
            color: rgb(233, 96, 47);
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        #course.error,
        #batch.error {
            border: 2px solid rgb(233, 96, 47);
        }

        .search {
            background-color: rgb(233, 96, 47);
            border: none;
            color: #fff;
            width: 100px;
            height: 40px;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search:hover {
            background-color: rgb(210, 80, 40);
        }

        .bottom {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
        }

        .rw {
            display: flex;
            width: 100%;
            justify-content: flex-end;
            margin-bottom: 15px;
        }

        .dispatch {
            background-color: rgb(233, 96, 47);
            color: white;
            font-size: 14px;
            width: 120px;
            height: 35px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dispatch:hover {
            background-color: rgb(210, 80, 40);
        }

        .table {
            margin: 0;
            background-color: #fff;
        }

        .table thead th {
            font-size: 12px;
            color: rgb(233, 96, 47);
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
            padding: 12px 8px;
            background-color: #fff;
        }

        .table tbody td {
            padding: 10px 8px;
            font-size: 13px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f8f8;
        }

        .btn-sm {
            font-size: 12px;
            padding: 4px 10px;
            margin-right: 5px;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
            color: white;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000;
        }

        #toggle-btn {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .dropdown-menu {
            min-width: 150px;
        }

        .dropdown-item {
            padding: 10px 15px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item i {
            width: 20px;
        }

        /* Scrollbar styling */
        .left::-webkit-scrollbar,
        .right::-webkit-scrollbar {
            width: 6px;
        }

        .left::-webkit-scrollbar-track,
        .right::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .left::-webkit-scrollbar-thumb,
        .right::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .left::-webkit-scrollbar-thumb:hover,
        .right::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body>

    <div class="top">
        <div class="header">
            <img src="https://synthesisbikaner.org/synthesistest/assets/logo-big.png" class="logo" alt="Logo">
            <i class="fa-solid fa-bars" id="toggleBtn"></i>
        </div>

        <div class="session">
            <label>Session:</label>
            <select class="select">
                <option>2026</option>
                <option>2024-25</option>
            </select>
            <i class="fa-solid fa-bell" style="color: rgb(233, 96, 47); font-size: 20px;"></i>

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user" style="color: rgb(233, 96, 47); font-size: 20px;"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="/pfp/pfp.html" class="dropdown-item">
                            <i class="fa-solid fa-user" style="color: rgb(233, 96, 47);"></i>Profile
                        </a></li>
                    <li><a href="/login page/login.html" class="dropdown-item">
                            <i class="fa-solid fa-arrow-right-from-bracket"
                                style="color: rgb(233, 96, 47);"></i>Log Out
                        </a></li>
                </ul>
            </div>
        </div>
<<<<<<< HEAD

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
                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i>Inquiry Management</a></li>
                <li><a class="item" href="{{ route('student.student.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a></li>
                <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Fees Students</a></li>
                <li><a class="item" href="{{ route('smstudents.index') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
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
              <i class="fa-solid fa-credit-card" id="side-icon"></i>Fees Management
            </button>
          </h2>
          <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('fees.management.index') }}"><i class="fa-solid fa-money-bill-wave" id="side-icon"></i>Fees Collection</a></li>
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
              <i class="fa-solid fa-user-check" id="side-icon"></i>Attendance Management
            </button>
          </h2>
          <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i>Employee</a></li>
                <li><a class="item" href="{{ route('attendance.student.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i>Student</a></li>
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
              <i class="fa-solid fa-book-open" id="side-icon"></i>Study Material Co...
            </button>
          </h2>
          <div id="flush-collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('units.index') }}"><i class="fa-solid fa-book" id="side-icon"></i>Units</a></li>
                <li><a class="item active" href="{{ route('study_material.dispatch.index') }}"><i class="fa-solid fa-truck" id="side-icon"></i>Dispatch Material</a></li>
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
              <i class="fa-solid fa-chart-column" id="side-icon"></i>Test Series Manag...
            </button>
          </h2>
          <div id="flush-collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
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
              <i class="fa-solid fa-square-poll-horizontal" id="side-icon"></i>Reports
            </button>
          </h2>
          <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('reports.walkin.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Walk In</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-calendar-days" id="side-icon"></i>Attendance</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Test Series</a></li>
                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry History</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-file" id="side-icon"></i>Onboard History</a></li>
              </ul>
            </div>
          </div>
        </div>

      </div>
=======
>>>>>>> 25ebacb57ae821fb9249969068d766e6e5144c4e
    </div>

    <div class="main-container">
        <div class="left" id="sidebar">
            <div class="admin" id="admin">
                <h2>Admin</h2>
                <h4>synthesisbikaner@gmail.com</h4>
            </div>

            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <i class="fa-solid fa-user-group"></i>User Management
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/user management/emp/emp.html">
                                        <i class="fa-solid fa-user"></i>Employee
                                    </a></li>
                                <li><a href="/user management/batches a/batchesa.html">
                                        <i class="fa-solid fa-user-group"></i>Batches Assignment
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            <i class="fa-solid fa-layer-group"></i>Master
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/master/courses/course.html">
                                        <i class="fa-solid fa-book-open"></i>Courses
                                    </a></li>
                                <li><a href="/master/batches/batches.html">
                                        <i class="fa-solid fa-user-group"></i>Batches
                                    </a></li>
                                <li><a href="/master/scholarship/scholar.html">
                                        <i class="fa-solid fa-graduation-cap"></i>Scholarship
                                    </a></li>
                                <li><a href="/master/feesm/fees.html">
                                        <i class="fa-solid fa-credit-card"></i>Fees Master
                                    </a></li>
                                <li><a href="/master/other fees/other.html">
                                        <i class="fa-solid fa-wallet"></i>Other Fees Master
                                    </a></li>
                                <li><a href="/master/branch/branch.html">
                                        <i class="fa-solid fa-diagram-project"></i>Branch Management
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseThree" aria-expanded="false"
                            aria-controls="flush-collapseThree">
                            <i class="fa-solid fa-calendar-days"></i>Session Management
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/session mana/session/session.html">
                                        <i class="fa-solid fa-calendar-day"></i>Session
                                    </a></li>
                                <li><a href="/session mana/calendar/cal.html">
                                        <i class="fa-solid fa-calendar-days"></i>Calendar
                                    </a></li>
                                <li><a href="/session mana/student/student.html">
                                        <i class="fa-solid fa-user-check"></i>Student Migrate
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseFour" aria-expanded="false"
                            aria-controls="flush-collapseFour">
                            <i class="fa-solid fa-user-graduate"></i>Student Management
                        </button>
                    </h2>
                    <div id="flush-collapseFour" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/student management/inq/inq.html">
                                        <i class="fa-solid fa-circle-info"></i>Inquiry Management
                                    </a></li>
                                <li><a href="/student management/stu onboard/onstu.html">
                                        <i class="fa-solid fa-user-check"></i>Student Onboard
                                    </a></li>
                                <li><a href="/student management/pending/pending.html">
                                        <i class="fa-solid fa-clock"></i>Pending Fees Students
                                    </a></li>
                                <li><a href="/student management/students/stu.html">
                                        <i class="fa-solid fa-users"></i>Students
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseFive" aria-expanded="false"
                            aria-controls="flush-collapseFive">
                            <i class="fa-solid fa-credit-card"></i>Fees Management
                        </button>
                    </h2>
                    <div id="flush-collapseFive" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/fees management/collect/collect.html">
                                        <i class="fa-solid fa-credit-card"></i>Fees Collection
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                            <i class="fa-solid fa-clipboard-check"></i>Attendance Management
                        </button>
                    </h2>
                    <div id="flush-collapseSix" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/attendance management/students/student.html">
                                        <i class="fa-solid fa-user"></i>Student
                                    </a></li>
                                <li><a href="/attendance management/employee/employee.html">
                                        <i class="fa-solid fa-user-tie"></i>Employee
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseSeven" aria-expanded="false"
                            aria-controls="flush-collapseSeven">
                            <i class="fa-solid fa-book-open"></i>Study Material
                        </button>
                    </h2>
                    <div id="flush-collapseSeven" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/study material/units/units.html">
                                        <i class="fa-solid fa-book"></i>Units
                                    </a></li>
                                <li><a href="/study material/dispatch/dispatch.html">
                                        <i class="fa-solid fa-truck"></i>Dispatch Material
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseEight" aria-expanded="false"
                            aria-controls="flush-collapseEight">
                            <i class="fa-solid fa-chart-column"></i>Test Series Management
                        </button>
                    </h2>
                    <div id="flush-collapseEight" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/testseries/test.html">
                                        <i class="fa-solid fa-file-lines"></i>Test Master
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseNine" aria-expanded="false"
                            aria-controls="flush-collapseNine">
                            <i class="fa-solid fa-square-poll-horizontal"></i>Reports
                        </button>
                    </h2>
                    <div id="flush-collapseNine" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/reports/walkin/walkin.html">
                                        <i class="fa-solid fa-person-walking"></i>Walk In
                                    </a></li>
                                <li><a href="/reports/att/att.html">
                                        <i class="fa-solid fa-calendar-days"></i>Attendance
                                    </a></li>
                                <li><a href="/reports/test/test.html">
                                        <i class="fa-solid fa-file"></i>Test Series
                                    </a></li>
                                <li><a href="/reports/inq/inq.html">
                                        <i class="fa-solid fa-file"></i>Inquiry History
                                    </a></li>
                                <li><a href="/reports/onboard/onboard.html">
                                        <i class="fa-solid fa-file"></i>Onboard History
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="right" id="right">
            <h5>Display Study Material</h5>

            <div class="upper">
                <div>
                    <select id="course" required>
                        <option value="" disabled selected>Loading courses...</option>
                    </select>
                    <div class="error-message" id="course-error">Course is required</div>
                </div>

                <div>
                    <select id="batch" required>
                        <option value="" disabled selected>Select Batch</option>
                        <option value="all">All</option>
                    </select>
                    <div class="error-message" id="batch-error">Batch is required</div>
                </div>

                <button type="button" class="search">Search</button>
            </div>

            <div class="bottom">
                <div class="rw">
                    <button class="dispatch">Dispatch</button>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                            </th>
                            <th scope="col">Roll Number</th>
                            <th scope="col">Student Name</th>
                            <th scope="col">Father Name</th>
                            <th scope="col">Batch Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <script>
        // Load courses on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
        });

        // Function to load courses from API
        function loadCourses() {
            const courseSelect = document.getElementById('course');
            courseSelect.innerHTML = '<option value="" disabled selected>Loading courses...</option>';
            
            // TODO: In Laravel blade, replace with actual API call:
            // fetch('/study_material/dispatch') - controller returns courses in view
            // Or: fetch('/api/courses') - if you create a dedicated API endpoint
            
            // For demo purposes with static HTML, using hardcoded courses
            const courses = [
                { _id: 'intensity-12th-iit', name: 'Intensity 12th IIT' },
                { _id: 'plumule-9th', name: 'Plumule 9th' },
                { _id: 'radicle-8th', name: 'Radicle 8th' },
                { _id: 'anthesis-11th-neet', name: 'Anthesis 11th NEET' },
                { _id: 'dynamic-target-neet', name: 'Dynamic Target NEET' },
                { _id: 'thurst-target-iit', name: 'Thurst Target IIT' },
                { _id: 'seedling-10th', name: 'Seedling 10th' },
                { _id: 'nucleus-7th', name: 'Nucleus 7th' },
                { _id: 'momentum-12th-neet', name: 'Momentum 12th NEET' },
                { _id: 'impulse-11th-iit', name: 'Impulse 11th IIT' },
                { _id: 'atom-6th', name: 'Atom 6th' }
            ];
            
            courseSelect.innerHTML = '<option value="" disabled selected>Select Course</option>';
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course._id;
                option.textContent = course.name;
                courseSelect.appendChild(option);
            });
        }

        // Course dropdown change event - Fetch batches dynamically from API
        document.getElementById('course').addEventListener('change', function() {
            const selectedCourse = this.value;
            const batchSelect = document.getElementById('batch');
            
            // Clear error state
            document.getElementById('course-error').style.display = 'none';
            this.classList.remove('error');
            
            // Clear existing batch options
            batchSelect.innerHTML = '<option value="" disabled selected>Select Batch</option>';
            
            if (!selectedCourse) {
                return;
            }
            
            // Show loading state
            batchSelect.innerHTML = '<option value="" disabled selected>Loading batches...</option>';
            
            // Fetch batches from backend
            // Laravel Route: GET /study_material/dispatch/get-batches
            // Controller: DispatchController@getBatches
            fetch('/study_material/dispatch/get-batches?course_id=' + encodeURIComponent(selectedCourse))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.batches) {
                        // Clear loading message
                        batchSelect.innerHTML = '<option value="" disabled selected>Select Batch</option>';
                        
                        // Add "All" option
                        const allOption = document.createElement('option');
                        allOption.value = 'all';
                        allOption.textContent = 'All';
                        batchSelect.appendChild(allOption);
                        
                        // Add batches from API
                        data.batches.forEach(batch => {
                            const option = document.createElement('option');
                            option.value = batch._id;
                            option.textContent = batch.name;
                            batchSelect.appendChild(option);
                        });
                    } else {
                        batchSelect.innerHTML = '<option value="" disabled selected>No batches found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching batches:', error);
                    batchSelect.innerHTML = '<option value="" disabled selected>Error loading batches</option>';
                });
        });

        // Batch dropdown change event
        document.getElementById('batch').addEventListener('change', function() {
            document.getElementById('batch-error').style.display = 'none';
            this.classList.remove('error');
        });

        // Search button click event
        document.querySelector('.search').addEventListener('click', function() {
            const course = document.getElementById('course').value;
            const batch = document.getElementById('batch').value;
            const courseError = document.getElementById('course-error');
            const batchError = document.getElementById('batch-error');
            const courseSelect = document.getElementById('course');
            const batchSelect = document.getElementById('batch');
            
            // Reset error states
            courseError.style.display = 'none';
            batchError.style.display = 'none';
            courseSelect.classList.remove('error');
            batchSelect.classList.remove('error');
            
            let hasError = false;
            
            if (!course) {
                courseError.style.display = 'block';
                courseSelect.classList.add('error');
                hasError = true;
            }
            
            if (!batch) {
                batchError.style.display = 'block';
                batchSelect.classList.add('error');
                hasError = true;
            }
            
            if (hasError) {
                return;
            }
            
            loadStudents(course, batch);
        });

        // Function to load students from API
        function loadStudents(courseId, batchId) {
            const tableBody = document.querySelector('.table tbody');
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;">Loading students...</td></tr>';
            
            // Laravel Route: GET /study_material/dispatch/get-students
            // Controller: DispatchController@getStudents
            let url = '/study_material/dispatch/get-students?course_id=' + encodeURIComponent(courseId);
            
            if (batchId !== 'all') {
                url += '&batch_id=' + encodeURIComponent(batchId);
            }
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    
                    if (data.success && data.students && data.students.length > 0) {
                        data.students.forEach(student => {
                            const row = tableBody.insertRow();
                            row.innerHTML = `
                                <td><input class="form-check-input" type="checkbox" value="${student._id}" data-roll="${student.roll_no}"></td>
                                <td>${student.roll_no || 'N/A'}</td>
                                <td>${student.name || 'N/A'}</td>
                                <td>${student.father_name || 'N/A'}</td>
                                <td>${student.batch_name || 'N/A'}</td>
                                <td>
                                    ${student.is_dispatched 
                                        ? '<span class="badge bg-success">Dispatched</span>' 
                                        : '<span class="badge bg-warning">Pending</span>'}
                                </td>
                            `;
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;">No students found</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching students:', error);
                    tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;color:red;">Error loading students</td></tr>';
                });
        }

        // Dispatch button functionality
        document.querySelector('.dispatch').addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.table tbody input[type="checkbox"]:checked');
            
            if (checkedBoxes.length === 0) {
                alert('Please select at least one student to dispatch material');
                return;
            }
            
            const studentIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (!confirm(`Are you sure you want to dispatch material to ${studentIds.length} student(s)?`)) {
                return;
            }
            
            // Disable button during API call
            const dispatchBtn = this;
            dispatchBtn.disabled = true;
            dispatchBtn.textContent = 'Dispatching...';
            
            // Laravel Route: POST /study_material/dispatch/dispatch-material
            // Controller: DispatchController@dispatchMaterial
            fetch('/study_material/dispatch/dispatch-material', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    student_ids: studentIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Reload the student list to update dispatch status
                    const course = document.getElementById('course').value;
                    const batch = document.getElementById('batch').value;
                    loadStudents(course, batch);
                    // Uncheck all checkboxes
                    document.getElementById('checkDefault').checked = false;
                } else {
                    alert('Error: ' + (data.message || 'Failed to dispatch material'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error dispatching material. Please try again.');
            })
            .finally(() => {
                dispatchBtn.disabled = false;
                dispatchBtn.textContent = 'Dispatch';
            });
        });

        // Toggle sidebar functionality
        document.getElementById('toggleBtn').addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            const right = document.getElementById('right');
            
            if (sidebar.style.display === 'none') {
                sidebar.style.display = 'flex';
            } else {
                sidebar.style.display = 'none';
            }
        });

        // Select all checkbox functionality
        document.getElementById('checkDefault').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.table tbody input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>

</html>