 <!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .header {
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: space-between;
            flex-direction: row;
        }

        .pfp {
            justify-content: center;
            flex-direction: row;
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .pfp select {
            height: 30px;
            width: 110px;
            border: 0.5px solid orangered;
            border-radius: 5px;
        }

        .session {
            align-items: center;
            flex-direction: row;
            display: flex;
            margin: 10px;
        }

        .session h5 {
            color: black;
            margin: 7px;
            font-size: 15px;
        }

        .fa-bell,
        .fa-user {
            margin: 5px;
            color: rgb(224, 83, 1);
            font-size: 18px;
        }

        .logo {
            height: 60px;
            width: 300px;
            border: 0.1px solid #d8d8d8;
            margin: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .img {
            height: 50px;
            width: 150px;
            margin: 4px;
        }

        .main-container {
            max-width: 100%;
            flex-direction: row;
            display: flex !important;
        }

        .left {
            height: 100vh;
            width: 20%;
            overflow-y: scroll;
            display: flex !important;
            flex-direction: column !important;
            background-color: #ffffff !important;
            border-right: 1px solid #e0e0e0 !important;
        }

        .left h6 {
            display: flex;
            margin: 5px;
            justify-content: center;
            align-items: center;
        }

        .left p {
            display: flex;
            margin-bottom: 20px;
            justify-content: center;
            align-items: center;
        }

        .text {
            justify-content: center;
            align-items: center;
        }

        .fa-solid,
        .fa-regular {
            margin: 0px 10px 0px 5px;
            font-size: 15px;
        }

        .accordion-flush {
            justify-content: flex-start;
            align-items: center;
        }

        .accordion-item {
            border: none !important;
            background-color: transparent !important;
        }

        .accordion-button {
            font-size: 17px;
            border: none !important;
            padding: 10px !important;
            background-color: transparent !important;
            box-shadow: none !important;
            min-height: 38px;
            display: flex !important;
            align-items: center !important;
            gap: 5px !important;
            width: 100% !important;
        }

        .accordion-button:not(.collapsed) {
            background-color: transparent !important;
            color: inherit !important;
            box-shadow: none !important;
        }

        .accordion-button:focus {
            box-shadow: none !important;
            border: none !important;
        }

        .accordion-button::after {
            margin-left: auto;
            flex-shrink: 0;
        }

        .accordion-collapse {
            border: none !important;
        }

        .accordion-body {
            padding: 10px !important;
            margin: 0 !important;
        }

        .accordion-button i,
        .accordion-button .fa-solid,
        .accordion-button .fa-regular {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            flex-shrink: 0 !important;
            min-width: 16px !important;
            margin-right: 8px !important;
        }

        #dropdown-body {
            padding: 0 !important;
            display: flex;
            align-items: flex-start;
            flex-direction: column;
            justify-content: flex-start;
            margin: 0 !important;
        }

        .menu {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-direction: column;
            padding: 0;
            margin: 0;
        }

        .item {
            text-decoration: none;
            color: #000;
            cursor: pointer;
            display: flex !important;
            align-items: center;
            width: 100%;
            padding: 5px 8px;
        }

        .item i {
            margin-right: 8px;
            flex-shrink: 0;
            display: inline-flex !important;
            visibility: visible !important;
        }

        .menu li {
            list-style: none;
            cursor: pointer;
            font-size: 17px;
            text-decoration: none;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            width: 100%;
        }

        .menu li:hover {
            background-color: #f0f0f0;
        }

        .right {
            height: 90vh;
            width: 80%;
            display: flex !important;
            align-items: center;
            flex-direction: column;
            overflow-y: scroll;
            background-color: #e7e7e7;
        }

        .whole {
            margin: 5px;
            padding: 20px;
            gap: 25px;
            width: 97%;
            display: flex;
            align-items: center;
            flex-direction: column;
            background-color: #ffffff;
            border: 1px solid #d6d6d6;
            overflow: visible !important;
        }

        .right h1 {
            color: rgb(190, 51, 0);
            margin: 10px;
        }

        .toggleBtn {
            border: none;
            background: transparent;
            cursor: pointer;
        }

        #table {
            font-size: 12px;
            flex-direction: column;
            overflow: visible;
        }

        #tableBody {
            overflow: visible;
        }

        #tableBody tr {
            overflow: visible;
        }

        #tableBody td {
            overflow: visible;
        }

        .top {
            justify-content: space-between;
            flex-direction: row;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .top h4 {
            font-size: 20px;
            color: rgb(224, 83, 1);
            margin: 10px;
        }

        .buttons {
            flex-direction: row;
            margin: 10px;
            display: flex;
            gap: 15px;
        }

        #add {
            background-color: rgb(224, 83, 1);
            color: white;
            height: 38px;
            padding: 0 24px;
            border-radius: 5px;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        #add:hover {
            background-color: rgb(190, 51, 0);
            transform: translateY(-1px);
        }

        .btn-upload {
            background-color: #28a745;
            color: white;
            height: 38px;
            padding: 0 24px;
            border-radius: 5px;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-upload:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        .btn-onboard {
            background-color: rgb(224, 83, 1);
            color: white;
            height: 38px;
            padding: 0 24px;
            border-radius: 5px;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-onboard:hover {
            background-color: rgb(190, 51, 0);
            transform: translateY(-1px);
        }

        .btn-secondary,
        #ellipsis {
            margin: 5px 0px 10px 5px;
            align-items: center;
            outline: none;
            border: 1px solid #000;
            background-color: #ffffff;
            border: none;
        }

        .dd {
            margin: 5px;
            width: 100%;
            flex-direction: row;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #toggle-btn,
        #ellipsis {
            background-color: transparent;
            justify-content: center;
            display: flex;
            border: none;
            cursor: pointer;
        }

        .fa-bars:hover {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .fa-bars {
            margin-right: 35px;
        }

        .dropdown-item {
            height: auto;
            width: auto;
            margin: 5px 10px;
            border-radius: 5px;
            justify-content: flex-start;
            align-items: center;
            display: flex;
            cursor: pointer;
            padding: 8px 12px;
        }

        .line {
            flex-direction: row;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-left: 10px;
        }

        .search {
            flex-direction: row;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .search-text {
            font-size: 15px;
            margin: 5px;
        }

        #one {
            color: rgb(224, 83, 1);
        }

        .search-holder {
            border: 1px solid rgb(224, 83, 1);
            border-radius: 5px;
            outline: none;
            padding: 5px;
        }

        #bottom {
            display: flex;
            align-items: center;
            width: 98%;
            justify-content: flex-end;
        }

        #pagination {
            border: 1px solid #d4d4d4;
        }

        #pg1 {
            height: 30px;
            width: 70px;
            display: flex;
            justify-content: center;
            color: #838383;
            align-items: center;
        }

        #pg2 {
            height: 30px;
            width: 50px;
            display: flex;
            color: #fff;
            justify-content: center;
            align-items: center;
            background-color: rgb(224, 83, 1);
        }

        .footer {
            flex-direction: row;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 95%;
        }

        .left-footer p {
            font-size: 12px;
        }

        .modal-header {
            background-color: rgb(224, 83, 1);
            color: white;
        }

        .modal-title {
            color: white;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .btn-primary {
            background-color: rgb(224, 83, 1) !important;
            border-color: rgb(224, 83, 1) !important;
        }

        .btn-primary:hover {
            background-color: rgb(190, 51, 0) !important;
            border-color: rgb(190, 51, 0) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgb(224, 83, 1);
            box-shadow: 0 0 0 0.2rem rgba(224, 83, 1, 0.25);
        }

        .table tbody tr:hover {
            background-color: #f5f5f5;
        }

        .dropdown-menu {
            z-index: 1050 !important;
            min-width: 120px;
        }

        .dropdown-menu.show {
            display: block;
        }

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

        .status-Pending {
            color: #28a745;
            font-weight: 600;
        }

        .status-inactive {
            color: #dc3545;
            font-weight: 600;
        }

        .page-link {
            color: rgb(224, 83, 1);
        }

        .page-item.active .page-link {
            background-color: rgb(224, 83, 1);
            border-color: rgb(224, 83, 1);
        }
    </style>
</head>
<body>
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
                    <option>2025-2026</option>
                </select>
            </div>
            <i class="fa-solid fa-bell"></i>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i>Profile</a></li>
                    <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</a></li>
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

               <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"
              id="accordion-button">
              <i class="fa-solid fa-user-group" id="side-icon"></i>User Management </button>
          </h2>
          <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('user.emp.emp') }}"> <i class="fa-solid fa-user"
                      id="side-icon"></i> Employee</a></li>
                <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group"
                      id="side-icon"></i> Batches Assignment</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo"
              id="accordion-button">
              <i class="fa-solid fa-user-group" id="side-icon"></i> Master </button>
          </h2>
          <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="{{ route('courses.index') }}"><i class="fa-solid fa-book-open"
                      id="side-icon"></i> Courses</a></li>
                <li><a class="item" href="{{ route('batches.index') }}"><i
                      class="fa-solid fa-user-group fa-flip-horizontal" id="side-icon"></i>
                    Batches</a></li>
                <li><a class="item" href="{{ route('master.scholarship.index') }}"><i class="fa-solid fa-graduation-cap"
                      id="side-icon"></i> Scholarship</a>
                </li>
                <li><a class="item" href="{{ route('fees.index') }}">
<i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Master</a></li>
                <li><a class="item" href="{{ route('master.other_fees.index') }}
"><i class="fa-solid fa-wallet"
                      id="side-icon"></i> Other Fees Master</a>
                </li>
                <li><a class="item" href="{{ route('branches.index') }}"><i class="fa-solid fa-diagram-project"
                      id="side-icon"></i> Branch
                    Management</a></li>
              </ul>
            </div>
          </div>
        </div>
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
                <li><a class="item" href="{{ route('sessions.index') }}"><i class="fa-solid fa-calendar-day"
                      id="side-icon"></i> Session</a></li>
                <li><a class="item" href="/session mana/calendar/cal.html"><i class="fa-solid fa-calendar-days"
                      id="side-icon"></i> Calendar</a></li>
                <li><a class="item" href="/session mana/student/student.html"><i class="fa-solid fa-user-check"
                      id="side-icon"></i> Student Migrate</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
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
                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info"
                      id="side-icon"></i> Inquiry Management </a></li>
                <li><a class="item" href="{{ route('student.student.pending') }}">
    <i class="fa-solid fa-user-check" id="side-icon"></i> Student Onboard
</a></li>
                <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check"
                      id="side-icon"></i>Pending Fees
                    Students</a></li>
                <li><a class="item" href="/student management/students/stu.html"><i class="fa-solid fa-user-check"
                      id="side-icon"></i>Students</a></li>
              </ul>
            </div>
          </div>  
        </div>
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
                <li><a class="item" href="/fees management/collect/collect.html"><i class="fa-solid fa-credit-card"
                      id="side-icon"></i> Fees Collection</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix"
              id="accordion-button">
              <i class="fa-solid fa-user-check" id="side-icon"></i> Attendance Managment
            </button>
          </h2>
          <div id="flush-collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="/attendance management/students/student.html"> <i class="fa-solid fa-user"
                      id="side-icon"> </i>Student</a></li>
                <li><a class="item" href="/attendance management/employee/employee.html"> <i class="fa-solid fa-user"
                      id="side-icon"> </i>Employee</a></li>
              </ul>
            </div>
          </div>
        </div>
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
                <li><a class="item" href="/study material/units/units.html"> <i class="fa-solid fa-user" id="side-icon">
                    </i>Units</a></li>
                <li><a class="item" href="/study material/dispatch/dispatch.html"> <i class="fa-solid fa-user"
                      id="side-icon"> </i>Dispatch Material</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight"
              id="accordion-button">
              <i class="fa-solid fa-chart-column" id="side-icon"></i> Test Series Managment
            </button>
          </h2>
          <div id="flush-collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="/testseries/test.html"> <i class="fa-solid fa-user" id="side-icon"> </i>Test
                    Master</i></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine"
              id="accordion-button">
              <i class="fa-solid fa-square-poll-horizontal" id="side-icon"></i> Reports</i>
            </button>
          </h2>
          <div id="flush-collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li><a class="item" href="/reports/walk in/walk.html"> <i class="fa-solid fa-user" id="side-icon">
                    </i>Walk In</a></li>
                <li><a class="item" href="/reports/att/att.html"><i class="fa-solid fa-calendar-days"
                      id="side-icon"></i> Attendance</a>
                </li>
                <li><a class="item" href="/reports/test/test.html"><i class="fa-solid fa-file" id="side-icon"></i>Test
                    Series</a></li>
                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-file" id="side-icon"></i>Inquiry
                    History</a></li>
                <li><a class="item" href="/reports/onboard/onboard.html"><i class="fa-solid fa-file"
                      id="side-icon"></i>Onboard History</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
        </div>

        <div class="right" id="right">
            <div class="top">
                <div class="top-text">
                    <h4>INQUIRY</h4>
                </div>
                <div class="buttons">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inquiryModal" id="add">
                        Create Inquiry
                    </button>
                    <button type="button" class="btn-upload" onclick="document.getElementById('fileUpload').click()">
                        Upload
                    </button>
                    <input type="file" id="fileUpload" style="display: none;" accept=".csv,.xlsx">
                    <button type="button" class="btn-onboard">
                        Onboard
                    </button>
                </div>
            </div>

            <div class="whole">
                <div class="dd">
                    <div class="line">
                        <h6>Show Entries:</h6>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown" aria-expanded="false">10</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">10</a></li>
                                <li><a class="dropdown-item" href="#">25</a></li>
                                <li><a class="dropdown-item" href="#">50</a></li>
                                <li><a class="dropdown-item" href="#">100</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="search">
                        <h4 class="search-text">Search</h4>
                        <input type="search" placeholder="" class="search-holder" id="searchInput">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </div>

                <table class="table table-hover" id="table">
                    <thead>
                        <tr>
                            <th scope="col" id="one"><input type="checkbox" id="selectAll"></th>
                            <th scope="col" id="one">Serial No.</th>
                            <th scope="col" id="one">Name</th>
                            <th scope="col" id="one">Father Name</th>
                            <th scope="col" id="one">Father Contact No</th>
                            <th scope="col" id="one">Course Name</th>
                            <th scope="col" id="one">Delivery Mode</th>
                            <th scope="col" id="one">Course Content</th>
                            <th scope="col" id="one">Status</th>
                            <th scope="col" id="one">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>

            <div class="footer">
                <div class="left-footer">
                    <p id="showingInfo">Showing 0 of 0 Entries</p>
                </div>
                <div class="right-footer">
                    <nav aria-label="Page navigation" id="bottom">
                        <ul class="pagination" id="pagination">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inquiryModalLabel">Create Inquiry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="inquiryForm">
                        <input type="hidden" id="inquiry_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Student Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="student_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="father_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father Contact <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="father_contact" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father WhatsApp</label>
                                <input type="tel" class="form-control" id="father_whatsapp">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Student Contact</label>
                                <input type="tel" class="form-control" id="student_contact">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category_obc" value="OBC">
                                        <label class="form-check-label" for="category_obc">OBC</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category_general" value="General" checked>
                                        <label class="form-check-label" for="category_general">GENERAL</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category_sc" value="SC">
                                        <label class="form-check-label" for="category_sc">SC</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category_st" value="ST">
                                        <label class="form-check-label" for="category_st">ST</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State</label>
                                <select class="form-select" id="state">
                                    <option value="">Select State</option>
                                    <option value="Rajasthan">Rajasthan</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Gujarat">Gujarat</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <select class="form-select" id="city">
                                    <option value="">Select City</option>
                                    <option value="Bikaner">Bikaner</option>
                                    <option value="Jaipur">Jaipur</option>
                                    <option value="Delhi">Delhi</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address Name</label>
                            <textarea class="form-control" id="address_name" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Branch Name</label>
                            <select class="form-select" id="branch_name">
                                <option value="">Select Branch</option>
                                <option value="Bikaner">Bikaner</option>
                                <option value="Jaipur">Jaipur</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <select class="form-select" id="course_name" required>
                                    <option value="">Select...</option>
                                    <option value="Anthesis 11th NEET">Anthesis 11th NEET</option>
                                    <option value="Momentum 12th NEET">Momentum 12th NEET</option>
                                    <option value="Dynamic Target NEET">Dynamic Target NEET</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Delivery Mode</label>
                                <select class="form-select" id="delivery_mode">
                                    <option value="Offline">Offline</option>
                                    <option value="Online">Online</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course Content</label>
                            <select class="form-select" id="course_content">
                                <option value="Class Room Course">Class Room Course</option>
                                <option value="Online Course">Online Course</option>
                                <option value="Study Material">Study Material</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Do You Belong to Economic Weaker Section?</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="economic_weaker" id="economic_yes" value="Yes">
                                    <label class="form-check-label" for="economic_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="economic_weaker" id="economic_no" value="No" checked>
                                    <label class="form-check-label" for="economic_no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Do You Belong to Any Army/Police/Martyr Background?</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="army_background" id="army_yes" value="Yes">
                                    <label class="form-check-label" for="army_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="army_background" id="army_no" value="No" checked>
                                    <label class="form-check-label" for="army_no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Are You a Specially Abled?</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="specially_abled" id="specially_yes" value="Yes">
                                    <label class="form-check-label" for="specially_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="specially_abled" id="specially_no" value="No" checked>
                                    <label class="form-check-label" for="specially_no">No</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Save Inquiry</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inquiry Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ENDPOINT = '/inquiries';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        let state = { page: 1, per_page: 10, search: '' };
        const elements = {
            tableBody: document.getElementById('tableBody'),
            searchInput: document.getElementById('searchInput'),
            perPage: document.getElementById('number'),
            pagination: document.getElementById('pagination'),
            showingInfo: document.getElementById('showingInfo'),
            saveBtn: document.getElementById('saveBtn'),
            form: document.getElementById('inquiryForm')
        };

        const modals = {
            inquiry: new bootstrap.Modal(document.getElementById('inquiryModal')),
            view: new bootstrap.Modal(document.getElementById('viewModal'))
        };

        document.getElementById('toggleBtn').addEventListener('click', () => {
            document.getElementById('sidebar').style.display = 
                document.getElementById('sidebar').style.display === 'none' ? 'flex' : 'none';
        });

        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        function debounce(fn, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        async function loadData() {
            try {
                const url = `${ENDPOINT}/data?page=${state.page}&per_page=${state.per_page}&search=${encodeURIComponent(state.search)}`;
                console.log('Loading data from:', url);
                
                const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                
                if (!response.ok) throw new Error('Failed to load data');
                
                const json = await response.json();
                console.log('Response received:', json);
                
                if (!json.success) throw new Error(json.message || 'Error loading data');
                
                renderTable(json.data || []);
                renderPagination(json);
                updateShowingInfo(json);
            } catch (error) {
                console.error('Load error:', error);
                elements.tableBody.innerHTML = '<tr><td colspan="10" class="text-center text-danger">Failed to load data: ' + error.message + '</td></tr>';
            }
        }

        function renderTable(data) {
            console.log('Rendering table with data:', data);
            
            if (!data || data.length === 0) {
                elements.tableBody.innerHTML = '<tr><td colspan="10" class="text-center">No data available</td></tr>';
                return;
            }

            elements.tableBody.innerHTML = data.map((item, index) => {
                const serialNo = (state.page - 1) * state.per_page + index + 1;
                const id = item._id ? (typeof item._id === 'object' ? item._id.$oid : item._id) : '';
                
                console.log('Rendering item:', item, 'ID:', id);
                
                return `
                    <tr>
                        <td><input type="checkbox" class="row-checkbox" data-id="${id}"></td>
                        <td>${serialNo}</td>
                        <td>${escapeHtml(item.student_name || '')}</td>
                        <td>${escapeHtml(item.father_name || '')}</td>
                        <td>${escapeHtml(item.father_contact || '')}</td>
                        <td>${escapeHtml(item.course_name || '')}</td>
                        <td>${escapeHtml(item.delivery_mode || 'Offline')}</td>
                        <td>${escapeHtml(item.course_content || 'Class Room Course')}</td>
                        <td><span class="status-${item.status === 'Pending' ? 'Pending' : 'inactive'}">${item.status || 'Pending'}</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" onclick="viewInquiry('${id}'); return false;">View</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="editInquiry('${id}'); return false;">Edit</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="onboardSingle('${id}'); return false;">Onboard</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="deleteInquiry('${id}'); return false;">Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderPagination(json) {
            const currentPage = json.current_page || 1;
            const lastPage = json.last_page || 1;
            
            let html = '';
            
            html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">Previous</a>
            </li>`;
            
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(lastPage, currentPage + 2);
            
            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>`;
            }
            
            html += `<li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Next</a>
            </li>`;
            
            elements.pagination.innerHTML = html;
        }

        function updateShowingInfo(json) {
            const from = json.data.length ? (json.current_page - 1) * json.per_page + 1 : 0;
            const to = json.data.length ? (json.current_page - 1) * json.per_page + json.data.length : 0;
            elements.showingInfo.textContent = `Showing ${from} to ${to} of ${json.total || 0} entries`;
        }

        window.viewInquiry = async function(id) {
            try {
                console.log('Viewing inquiry:', id);
                const response = await fetch(`${ENDPOINT}/${id}`, { headers: { 'Accept': 'application/json' } });
                const json = await response.json();
                if (!json.success) throw new Error(json.message);
                
                const item = json.data;
                document.getElementById('viewModalBody').innerHTML = `
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Student Name:</strong><span>${escapeHtml(item.student_name || '')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Father Name:</strong><span>${escapeHtml(item.father_name || '')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Father Contact:</strong><span>${escapeHtml(item.father_contact || '')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Father WhatsApp:</strong><span>${escapeHtml(item.father_whatsapp || 'N/A')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Student Contact:</strong><span>${escapeHtml(item.student_contact || 'N/A')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Category:</strong><span>${escapeHtml(item.category || 'General')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>State:</strong><span>${escapeHtml(item.state || 'N/A')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>City:</strong><span>${escapeHtml(item.city || 'N/A')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Address:</strong><span>${escapeHtml(item.address || 'N/A')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Branch Name:</strong><span>${escapeHtml(item.branch || 'N/A')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Course Name:</strong><span>${escapeHtml(item.course_name || '')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Delivery Mode:</strong><span>${escapeHtml(item.delivery_mode || 'Offline')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Course Content:</strong><span>${escapeHtml(item.course_content || 'Class Room Course')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Economic Weaker:</strong><span>${escapeHtml(item.ews || 'No')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Army Background:</strong><span>${escapeHtml(item.defense || 'No')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <strong>Specially Abled:</strong><span>${escapeHtml(item.specially_abled || 'No')}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 12px; padding: 8px 0;">
                        <strong>Status:</strong><span class="status-${item.status === 'Pending' ? 'Pending' : 'inactive'}">${item.status || 'Pending'}</span>
                    </div>
                `;
                modals.view.show();
            } catch (error) {
                console.error('View error:', error);
                alert('Failed to load inquiry details: ' + error.message);
            }
        };

        window.editInquiry = async function(id) {
            try {
                console.log('Editing inquiry:', id);
                const response = await fetch(`${ENDPOINT}/${id}`, { headers: { 'Accept': 'application/json' } });
                const json = await response.json();
                if (!json.success) throw new Error(json.message);
                
                const item = json.data;
                document.getElementById('inquiry_id').value = id;
                document.getElementById('student_name').value = item.student_name || '';
                document.getElementById('father_name').value = item.father_name || '';
                document.getElementById('father_contact').value = item.father_contact || '';
                document.getElementById('father_whatsapp').value = item.father_whatsapp || '';
                document.getElementById('student_contact').value = item.student_contact || '';
                
                const categoryValue = item.category || 'General';
                const categoryInput = document.querySelector(`input[name="category"][value="${categoryValue}"]`);
                if (categoryInput) categoryInput.checked = true;
                
                document.getElementById('state').value = item.state || '';
                document.getElementById('city').value = item.city || '';
                document.getElementById('address_name').value = item.address || '';
                document.getElementById('branch_name').value = item.branch || '';
                document.getElementById('course_name').value = item.course_name || '';
                document.getElementById('delivery_mode').value = item.delivery_mode || 'Offline';
                document.getElementById('course_content').value = item.course_content || 'Class Room Course';
                
                const economicValue = item.ews || 'No';
                const economicInput = document.querySelector(`input[name="economic_weaker"][value="${economicValue}"]`);
                if (economicInput) economicInput.checked = true;
                
                const armyValue = item.defense || 'No';
                const armyInput = document.querySelector(`input[name="army_background"][value="${armyValue}"]`);
                if (armyInput) armyInput.checked = true;
                
                const speciallyValue = item.specially_abled || 'No';
                const speciallyInput = document.querySelector(`input[name="specially_abled"][value="${speciallyValue}"]`);
                if (speciallyInput) speciallyInput.checked = true;
                
                document.getElementById('inquiryModalLabel').textContent = 'Edit Inquiry';
                modals.inquiry.show();
            } catch (error) {
                console.error('Edit error:', error);
                alert('Failed to load inquiry for editing: ' + error.message);
            }
        };

        window.deleteInquiry = async function(id) {
            if (!confirm('Are you sure you want to delete this inquiry?')) return;
            
            try {
                console.log('Deleting inquiry:', id);
                const response = await fetch(`${ENDPOINT}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                const json = await response.json();
                if (!json.success) throw new Error(json.message);
                
                alert('Inquiry deleted successfully');
                loadData();
            } catch (error) {
                console.error('Delete error:', error);
                alert('Failed to delete inquiry: ' + error.message);
            }
        };

window.onboardSingle = async function(id) {
    if (!confirm('Are you sure you want to onboard this student?')) {
        return;
    }

    try {
        console.log('Onboarding single inquiry:', id);
        
        const response = await fetch('/inquiries/bulk-onboard', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ inquiry_ids: [id] })
        });

        const json = await response.json();
        console.log('Onboard response:', json);

        if (!json.success) {
            throw new Error(json.message || 'Onboarding failed');
        }

        alert(json.message);
        
        // ✅ Just reload the table, no redirect
        loadData();

    } catch (error) {
        console.error('Onboard error:', error);
        alert('Failed to onboard student: ' + error.message);
    }
};
        // ✅ Save button click handler
        elements.saveBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            
            console.log('Save button clicked');
            
            const id = document.getElementById('inquiry_id').value;
            
            const studentName = document.getElementById('student_name').value.trim();
            const fatherName = document.getElementById('father_name').value.trim();
            const fatherContact = document.getElementById('father_contact').value.trim();
            const courseName = document.getElementById('course_name').value;
            
            if (!studentName || !fatherName || !fatherContact || !courseName) {
                alert('Please fill all required fields (Student Name, Father Name, Father Contact, Course Name)');
                return;
            }
            
            const payload = {
                student_name: studentName,
                father_name: fatherName,
                father_contact: fatherContact,
                father_whatsapp: document.getElementById('father_whatsapp').value.trim(),
                student_contact: document.getElementById('student_contact').value.trim(),
                category: document.querySelector('input[name="category"]:checked').value,
                state: document.getElementById('state').value,
                city: document.getElementById('city').value,
                address: document.getElementById('address_name').value.trim(),
                branch: document.getElementById('branch_name').value,
                course_name: courseName,
                delivery_mode: document.getElementById('delivery_mode').value,
                course_content: document.getElementById('course_content').value,
                ews: document.querySelector('input[name="economic_weaker"]:checked').value,
                defense: document.querySelector('input[name="army_background"]:checked').value,
                specially_abled: document.querySelector('input[name="specially_abled"]:checked').value,
                status: 'Pending'
            };

            try {
                const url = id ? `${ENDPOINT}/${id}` : ENDPOINT;
                const method = id ? 'PUT' : 'POST';
                
                console.log('Saving inquiry:', method, url, payload);
                
                elements.saveBtn.disabled = true;
                elements.saveBtn.textContent = 'Saving...';
                
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await response.json();
                console.log('Save response:', json);
                
                if (!json.success) throw new Error(json.message || 'Save failed');

                alert(id ? 'Inquiry updated successfully' : 'Inquiry created successfully');
                modals.inquiry.hide();
                elements.form.reset();
                document.getElementById('inquiry_id').value = '';
                document.getElementById('inquiryModalLabel').textContent = 'Create Inquiry';
                loadData();
            } catch (error) {
                console.error('Save error:', error);
                alert('Save failed: ' + error.message);
            } finally {
                elements.saveBtn.disabled = false;
                elements.saveBtn.textContent = 'Save Inquiry';
            }
        });

document.querySelector('.btn-onboard').addEventListener('click', async function() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('Please select at least one inquiry to onboard');
        return;
    }

    if (!confirm(`Are you sure you want to onboard ${checkedBoxes.length} student(s)?`)) {
        return;
    }

    const inquiryIds = Array.from(checkedBoxes).map(cb => cb.dataset.id);
    
    console.log('Onboarding inquiries:', inquiryIds);

    try {
        this.disabled = true;
        this.textContent = 'Onboarding...';
        
        const response = await fetch('/inquiries/bulk-onboard', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ inquiry_ids: inquiryIds })
        });

        const json = await response.json();
        console.log('Onboard response:', json);

        if (!json.success) {
            throw new Error(json.message || 'Onboarding failed');
        }

        alert(json.message);
        
        // ✅ Just reload the table, no redirect
        loadData();

    } catch (error) {
        console.error('Onboard error:', error);
        alert('Failed to onboard students: ' + error.message);
    } finally {
        this.disabled = false;
        this.textContent = 'Onboard';
    }
});
        window.changePage = function(page) {
            state.page = page;
            loadData();
        };

        elements.searchInput.addEventListener('input', debounce(() => {
            state.search = elements.searchInput.value.trim();
            state.page = 1;
            loadData();
        }, 400));

        elements.perPage.addEventListener('click', (e) => {
            if (e.target.tagName === 'A') {
                state.per_page = parseInt(e.target.textContent);
                state.page = 1;
                elements.perPage.textContent = state.per_page;
                loadData();
            }
        });

        document.getElementById('inquiryModal').addEventListener('hidden.bs.modal', () => {
            elements.form.reset();
            document.getElementById('inquiry_id').value = '';
            document.getElementById('inquiryModalLabel').textContent = 'Create Inquiry';
        });

        document.getElementById('fileUpload').addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);

            try {
                const response = await fetch(`${ENDPOINT}/upload`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const json = await response.json();
                if (!json.success) throw new Error(json.message || 'Upload failed');

                alert('File uploaded successfully');
                loadData();
            } catch (error) {
                console.error('Upload error:', error);
                alert('Upload failed: ' + error.message);
            } finally {
                e.target.value = '';
            }
        });

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Initial load
        console.log('Page loaded, loading data...');
        loadData();
    });
</script>

</body>
</html>
