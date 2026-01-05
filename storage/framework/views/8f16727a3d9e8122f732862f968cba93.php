<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scholarship</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo e(asset('css/scholarship.css')); ?>">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
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

    .fa-bell, .fa-user {
      margin: 5px;
      color: rgb(190, 51, 0);
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
      display: flex;
    }

    .left {
      height: 100vh;
      width: 20%;
      overflow-y: scroll;
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

    .fa-solid, .fa-regular {
      margin: 0px 10px 0px 5px;
      font-size: 15px;
    }

    .accordion-body {
      padding: 10px !important;
    }

    .menu {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      flex-direction: column;
    }

    #accordion-button {
      font-size: 17px;
      border: none;
      height: 38px;
      padding: 10px !important;
    }

    .item {
      text-decoration: none;
      color: #000;
      cursor: pointer;
    }

    .menu li {
      list-style: none;
      cursor: pointer;
      margin: 5px;
      font-size: 17px;
      text-decoration: none;
      display: flex;
      justify-content: flex-start;
    }

    .right {
      height: 90vh;
      width: 80%;
      display: flex;
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
    }

    .right h1 {
      color: rgb(190, 51, 0);
      margin: 10px;
    }

    #table {
      font-size: 12px;
      flex-direction: column;
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
    }

    #add {
      background-color: rgb(224, 83, 1);
      color: #edfeff;
      height: 40px;
      width: 190px;
      border-radius: 7px;
      border: 0;
      margin: 10px;
    }

    .dd {
      margin: 5px;
      width: 100%;
      flex-direction: row;
      display: flex;
      align-items: center;
      justify-content: space-between;
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

    .data {
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 13px;
    }

    .action-dots-btn {
      background: none;
      border: none;
      font-size: 18px;
      color: #333;
      cursor: pointer;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 30px;
      height: 30px;
    }

    .action-dots-btn:hover {
      color: rgb(224, 83, 1);
    }

    .dropdown-menu {
      min-width: 150px;
    }

    .dropdown-item {
      padding: 10px 16px;
      font-size: 13px;
      cursor: pointer;
      color: #333;
    }

    .dropdown-item:hover {
      background-color: #f8f9fa;
      color: rgb(224, 83, 1);
    }

    .detail-row {
      display: flex;
      padding: 12px 0;
      border-bottom: 1px solid #e9ecef;
    }

    .detail-label {
      font-weight: 600;
      width: 150px;
      color: #495057;
      font-size: 14px;
    }

    .detail-value {
      flex: 1;
      color: #212529;
      font-size: 14px;
    }

    .status-badge {
      font-size: 12px;
      font-weight: 500;
      color: #333;
    }

    .text {
      justify-content: center;
      align-items: center;
    }

    #dropdown-body {
      padding: 0 !important;
      display: flex;
      align-items: flex-start;
      flex-direction: column;
      justify-content: flex-start;
      margin: 0 !important;
    }

    .btn-secondary {
      margin: 5px 0px 10px 5px;
      align-items: center;
      outline: none;
      background-color: #ffffff;
      border: none;
    }

    #number {
      margin: 5px 0px 10px 5px;
      align-items: center;
      border: 1px solid #000;
      background-color: #ffffff;
      color: #000;
    }

    /* Modal Styling Improvements */
    .modal-header {
      background-color: rgb(224, 83, 1);
      color: white;
      padding: 15px 20px;
      border-bottom: none;
    }

    .modal-header .modal-title {
      font-size: 20px;
      font-weight: 600;
    }

    .modal-header .btn-close {
      filter: brightness(0) invert(1);
      opacity: 1;
    }

    .modal-body {
      padding: 25px;
      background-color: #f8f9fa;
      max-height: 70vh;
      overflow-y: auto;
    }

    .modal-body .form-label {
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .modal-body .form-control,
    .modal-body .form-select {
      padding: 10px 12px;
      border: 1px solid #ced4da;
      border-radius: 5px;
      font-size: 14px;
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
      border-color: rgb(224, 83, 1);
      box-shadow: 0 0 0 0.2rem rgba(224, 83, 1, 0.25);
    }

    .modal-footer {
      padding: 15px 20px;
      background-color: #fff;
      border-top: 1px solid #dee2e6;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    /* Button Styling - Making them visible */
    .modal-footer .btn-secondary {
      background-color: #6c757d !important;
      color: white !important;
      border: 1px solid #6c757d !important;
      padding: 8px 20px;
      border-radius: 5px;
      font-size: 14px;
      font-weight: 500;
      margin: 0 !important;
      cursor: pointer;
    }

    .modal-footer .btn-secondary:hover {
      background-color: #5a6268 !important;
      border-color: #545b62 !important;
    }

    .modal-footer .btn-primary {
      background-color: rgb(224, 83, 1) !important;
      color: white !important;
      border: 1px solid rgb(224, 83, 1) !important;
      padding: 8px 20px;
      border-radius: 5px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
    }

    .modal-footer .btn-primary:hover {
      background-color: rgb(200, 70, 0) !important;
      border-color: rgb(200, 70, 0) !important;
    }

    /* View Modal Specific Styling */
    #viewModalBody {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
    }

    #viewModalBody .detail-row:last-child {
      border-bottom: none;
    }

    /* Form spacing */
    .mb-3 {
      margin-bottom: 20px !important;
    }

    .text-danger {
      color: #dc3545;
    }

    /* Error message styling */
    .error-message {
      background-color: #f8d7da;
      color: #721c24;
      padding: 12px;
      border-radius: 5px;
      border: 1px solid #f5c6cb;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 12px;
      border-radius: 5px;
      border: 1px solid #c3e6cb;
      margin-bottom: 15px;
      font-size: 14px;
    }
  </style>
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
          <option>2025-2026</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>"><i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <div class="left" id="sidebar">
      <div class="text" id="text">
        <h6>ADMIN</h6>
        <p><?php echo e(Auth::user()->email ?? 'synthesisbikaner@gmail.com'); ?></p>
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
                <li><a class="item active" href="<?php echo e(route('master.scholarship.index')); ?>"><i class="fa-solid fa-graduation-cap" id="side-icon"></i> Scholarship</a></li>
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
                <li><a class="item" href="<?php echo e(route('test_series.index')); ?>"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
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
      <div class="top">
        <div class="top-text">
          <h4>SCHOLARSHIP</h4>
        </div>
        <div class="buttons">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scholarshipModal" id="add">
            Create Scholarship
          </button>
        </div>
      </div>

      <div class="whole">
        <div class="dd">
          <div class="line">
            <h6>Show Entries:</h6>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">10</button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item">5</a></li>
                <li><a class="dropdown-item">10</a></li>
                <li><a class="dropdown-item">15</a></li>
                <li><a class="dropdown-item">25</a></li>
              </ul>
            </div>
          </div>
          <div class="search">
            <h4 class="search-text">Search</h4>
            <input type="search" id="searchInput" placeholder="" class="search-holder" required>
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>

        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col" id="one">Serial No.</th>
              <th scope="col" id="one">Scholarship Name</th>
              <th scope="col" id="one">Short Name</th>
              <th scope="col" id="one">Type</th>
              <th scope="col" id="one">Description</th>
              <th scope="col" id="one">Status</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <p class="data" style="display:none;">No data available in the table</p>
      </div>

      <div class="footer">
        <div class="left-footer">
          <p>Showing 0 to 0 of 0 Entries</p>
        </div>
        <div class="right-footer">
          <nav aria-label="Page navigation example" id="bottom">
            <ul class="pagination" id="pagination"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <div class="modal fade" id="scholarshipModal" tabindex="-1" aria-labelledby="scholarshipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="scholarshipModalLabel">Create Scholarship</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div id="errorContainer"></div>
          
          <form id="scholarshipForm">
            <input type="hidden" id="scholarship_id" name="scholarship_id" value="">

            <div class="mb-3">
              <label for="scholarship_type" class="form-label">Scholarship Type <span class="text-danger">*</span></label>
              <select id="scholarship_type" name="scholarship_type" class="form-select" required>
                <option value="">Select Type</option>
                <option value="Board Examination Scholarship">Board Examination Scholarship</option>
                <option value="Continuing Education Scholarship">Continuing Education Scholarship</option>
                <option value="Special Scholarship">Special Scholarship</option>
                <option value="Competition Examination Scholarship">Competition Examination Scholarship</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="scholarship_name" class="form-label">Scholarship Name <span class="text-danger">*</span></label>
              <input type="text" id="scholarship_name" name="scholarship_name" class="form-control" 
                     placeholder="Enter Scholarship Name" required maxlength="200">
            </div>

            <div class="mb-3">
              <label for="short_name" class="form-label">Short Name <span class="text-danger">*</span></label>
              <input type="text" id="short_name" name="short_name" class="form-control" 
                     placeholder="Enter Short Name" required maxlength="50">
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea id="description" name="description" class="form-control" 
                        placeholder="Enter Description" rows="3" maxlength="500"></textarea>
            </div>

            <div class="mb-3">
              <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
              <select id="category" name="category" class="form-select" required>
                <option value="">Select Category</option>
                <option value="OBC">OBC</option>
                <option value="SC">SC</option>
                <option value="GENERAL">GENERAL</option>
                <option value="ST">ST</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="applicable_for" class="form-label">Applicable For</label>
              <select id="applicable_for" name="applicable_for" class="form-select">
                <option value="">Select Applicability</option>
                <option value="all">All Students</option>
                <option value="undergraduate">Undergraduate</option>
                <option value="postgraduate">Postgraduate</option>
                <option value="specific_course">Specific Course</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <select id="status" name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-secondary" data-bs-dismiss="modal" id="cancelBtn">Cancel</button>
          <button type="button" class="btn-primary" id="saveScholarshipBtn">Save Scholarship</button>
        </div>
      </div>
    </div>
  </div>

  <!-- View Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">Scholarship Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="viewModalBody">
          <!-- Details populated dynamically -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <script src="<?php echo e(asset('js/emp.js')); ?>"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const ENDPOINT_BASE = '/master/scholarship';
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      const tableBody = document.querySelector('#table tbody');
      const dataMsg = document.querySelector('.data');
      const saveBtn = document.getElementById('saveScholarshipBtn');
      const form = document.getElementById('scholarshipForm');
      const scholarshipModalEl = document.getElementById('scholarshipModal');
      const bsScholarshipModal = new bootstrap.Modal(scholarshipModalEl);
      const viewModalEl = document.getElementById('viewModal');
      const bsViewModal = new bootstrap.Modal(viewModalEl);
      const searchInput = document.getElementById('searchInput');
      const perPageBtn = document.getElementById('number');
      const perPageItems = document.querySelectorAll('.dropdown-menu .dropdown-item');
      const errorContainer = document.getElementById('errorContainer');

      const footerLeft = document.querySelector('.left-footer p');
      const paginationContainer = document.querySelector('#pagination');

      let state = {
        page: 1,
        per_page: 10,
        search: '',
      };

      // Show error message
      function showError(message) {
        errorContainer.innerHTML = `<div class="error-message">${escapeHtml(message)}</div>`;
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }

      // Show success message
      function showSuccess(message) {
        errorContainer.innerHTML = `<div class="success-message">${escapeHtml(message)}</div>`;
        setTimeout(() => {
          errorContainer.innerHTML = '';
        }, 3000);
      }

      // Clear error messages
      function clearErrors() {
        errorContainer.innerHTML = '';
      }

      perPageItems.forEach(it => {
        it.addEventListener('click', (e) => {
          const value = parseInt(e.currentTarget.textContent.trim());
          if (isNaN(value)) return;
          state.per_page = value;
          perPageBtn.textContent = value;
          state.page = 1;
          loadData();
        });
      });

      searchInput?.addEventListener('input', debounce(() => {
        state.search = searchInput.value.trim();
        state.page = 1;
        loadData();
      }, 400));

      function debounce(fn, wait) {
        let t;
        return (...args) => {
          clearTimeout(t);
          t = setTimeout(() => fn.apply(this, args), wait);
        };
      }

      async function loadData() {
        try {
          const url = `${ENDPOINT_BASE}?per_page=${state.per_page}&search=${encodeURIComponent(state.search)}&page=${state.page}`;
          const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
          
          if (!res.ok) {
            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
          }
          
          const json = await res.json();
          
          if (!json.success) {
            throw new Error(json.message || 'Failed to load data');
          }

          const items = json.data || [];
          renderTable(items);
          renderFooter(json);
        } catch (err) {
          console.error('Load error:', err);
          tableBody.innerHTML = '';
          dataMsg.style.display = 'block';
          dataMsg.textContent = 'Failed to load data: ' + err.message;
        }
      }

      function renderTable(items) {
        tableBody.innerHTML = '';
        if (!items || items.length === 0) {
          dataMsg.style.display = 'block';
          dataMsg.textContent = 'No data available in the table';
          return;
        }
        dataMsg.style.display = 'none';

        items.forEach((it, idx) => {
          const id = it._id || it.id || '';
          const serialNo = (state.page - 1) * state.per_page + idx + 1;
          
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${serialNo}</td>
            <td>${escapeHtml(it.scholarship_name || it.scholarships_name || '')}</td>
            <td>${escapeHtml(it.short_name || '')}</td>
            <td>${escapeHtml(it.scholarship_type || '')}</td>
            <td>${escapeHtml(truncate(it.description || '', 50))}</td>
            <td>
              ${it.status === 'active' 
                ? '<span class="status-badge">Active</span>' 
                : '<span class="status-badge">Inactive</span>'}
            </td>
            <td>
              <div class="dropdown">
                <button class="action-dots-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item view-btn" href="#" data-id="${id}">View</a></li>
                  <li><a class="dropdown-item edit-btn" href="#" data-id="${id}">Edit</a></li>
                  <li><a class="dropdown-item toggle-btn" href="#" data-id="${id}" data-status="${it.status}">
                    ${it.status === 'active' ? 'Deactivate' : 'Activate'}
                  </a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item text-danger delete-btn" href="#" data-id="${id}">Delete</a></li>
                </ul>
              </div>
            </td>
          `;
          tableBody.appendChild(tr);
        });

        tableBody.querySelectorAll('.view-btn').forEach(b => b.addEventListener('click', onView));
        tableBody.querySelectorAll('.edit-btn').forEach(b => b.addEventListener('click', onEdit));
        tableBody.querySelectorAll('.delete-btn').forEach(b => b.addEventListener('click', onDelete));
        tableBody.querySelectorAll('.toggle-btn').forEach(b => b.addEventListener('click', onToggleStatus));
      }

      function renderFooter(json) {
        const from = json.data.length ? ((json.current_page - 1) * json.per_page + 1) : 0;
        const to = json.data.length ? ((json.current_page - 1) * json.per_page + json.data.length) : 0;
        footerLeft && (footerLeft.textContent = `Showing ${from} to ${to} of ${json.total} Entries`);

        paginationContainer.innerHTML = '';

        const prevLi = document.createElement('li');
        prevLi.className = 'page-item' + (json.current_page <= 1 ? ' disabled' : '');
        prevLi.innerHTML = `<a class="page-link" href="#">Previous</a>`;
        paginationContainer.appendChild(prevLi);
        if (json.current_page > 1) {
          prevLi.addEventListener('click', (e) => {
            e.preventDefault();
            state.page = json.current_page - 1;
            loadData();
          });
        }

        const last = json.last_page || 1;
        const current = json.current_page || 1;
        const start = Math.max(1, current - 2);
        const end = Math.min(last, current + 2);

        for (let p = start; p <= end; p++) {
          const li = document.createElement('li');
          li.className = 'page-item';
          li.innerHTML = `<a class="page-link ${p === current ? 'active' : ''}" href="#" style="${p === current ? 'background-color: rgb(224, 83, 1); color: white;' : ''}">${p}</a>`;
          li.addEventListener('click', (e) => {
            e.preventDefault();
            state.page = p;
            loadData();
          });
          paginationContainer.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = 'page-item' + (json.current_page >= last ? ' disabled' : '');
        nextLi.innerHTML = `<a class="page-link" href="#">Next</a>`;
        paginationContainer.appendChild(nextLi);
        if (json.current_page < last) {
          nextLi.addEventListener('click', (e) => {
            e.preventDefault();
            state.page = json.current_page + 1;
            loadData();
          });
        }
      }

      function escapeHtml(s) {
        if (!s) return '';
        return String(s)
          .replaceAll('&', '&amp;')
          .replaceAll('<', '&lt;')
          .replaceAll('>', '&gt;')
          .replaceAll('"', '&quot;')
          .replaceAll("'", '&#039;');
      }

      function truncate(str, len) {
        if (!str) return '';
        return str.length > len ? str.substring(0, len) + '...' : str;
      }

      async function onView(e) {
        e.preventDefault();
        const id = e.currentTarget.dataset.id;
        try {
          const res = await fetch(`${ENDPOINT_BASE}/${id}`, {
            headers: { 'Accept': 'application/json' }
          });
          
          if (!res.ok) {
            throw new Error(`HTTP ${res.status}: Failed to load scholarship`);
          }
          
          const json = await res.json();
          
          if (!json.success) {
            throw new Error(json.message || 'Could not load scholarship');
          }
          
          const it = json.data;
          const viewBody = document.getElementById('viewModalBody');
          viewBody.innerHTML = `
            <div class="detail-row">
              <div class="detail-label">Scholarship Type:</div>
              <div class="detail-value">${escapeHtml(it.scholarship_type || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Scholarship Name:</div>
              <div class="detail-value">${escapeHtml(it.scholarship_name || it.scholarships_name || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Short Name:</div>
              <div class="detail-value">${escapeHtml(it.short_name || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Description:</div>
              <div class="detail-value">${escapeHtml(it.description || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Category:</div>
              <div class="detail-value">${escapeHtml(it.category || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Applicable For:</div>
              <div class="detail-value">${escapeHtml(it.applicable_for || 'N/A')}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Status:</div>
              <div class="detail-value">${it.status === 'active' ? 'Active' : 'Inactive'}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Created At:</div>
              <div class="detail-value">${it.created_at ? new Date(it.created_at).toLocaleString() : 'N/A'}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Updated At:</div>
              <div class="detail-value">${it.updated_at ? new Date(it.updated_at).toLocaleString() : 'N/A'}</div>
            </div>
          `;
          bsViewModal.show();
        } catch (err) {
          console.error('View error:', err);
          alert('Failed to load scholarship details: ' + err.message);
        }
      }

      async function onEdit(e) {
        e.preventDefault();
        const id = e.currentTarget.dataset.id;
        clearErrors();
        
        try {
          const res = await fetch(`${ENDPOINT_BASE}/${id}`, {
            headers: { 'Accept': 'application/json' }
          });
          
          if (!res.ok) {
            throw new Error(`HTTP ${res.status}: Failed to load scholarship`);
          }
          
          const json = await res.json();
          
          if (!json.success) {
            throw new Error(json.message || 'Could not load scholarship');
          }
          
          const it = json.data;
          document.getElementById('scholarship_id').value = it._id || it.id || '';
          document.getElementById('scholarship_type').value = it.scholarship_type || '';
          document.getElementById('scholarship_name').value = it.scholarship_name || it.scholarships_name || '';
          document.getElementById('short_name').value = it.short_name || '';
          document.getElementById('description').value = it.description || '';
          document.getElementById('category').value = it.category || '';
          document.getElementById('applicable_for').value = it.applicable_for || '';
          document.getElementById('status').value = it.status || 'active';
          
          document.getElementById('scholarshipModalLabel').textContent = 'Edit Scholarship';
          bsScholarshipModal.show();
        } catch (err) {
          console.error('Edit error:', err);
          alert('Failed to load record for editing: ' + err.message);
        }
      }

      async function onDelete(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete this scholarship? This action cannot be undone.')) return;
        
        const id = e.currentTarget.dataset.id;
        try {
          const res = await fetch(`${ENDPOINT_BASE}/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
          });
          
          if (!res.ok) {
            throw new Error(`HTTP ${res.status}: Delete request failed`);
          }
          
          const json = await res.json();
          
          if (!json.success) {
            throw new Error(json.message || 'Delete failed');
          }
          
          alert('Scholarship deleted successfully');
          await loadData();
        } catch (err) {
          console.error('Delete error:', err);
          alert('Delete failed: ' + err.message);
        }
      }

      async function onToggleStatus(e) {
        e.preventDefault();
        const id = e.currentTarget.dataset.id;
        const currentStatus = e.currentTarget.dataset.status;
        const action = currentStatus === 'active' ? 'deactivate' : 'activate';
        
        if (!confirm(`Are you sure you want to ${action} this scholarship?`)) return;
        
        try {
          const res = await fetch(`${ENDPOINT_BASE}/${id}/toggle`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
          });
          
          if (!res.ok) {
            throw new Error(`HTTP ${res.status}: Toggle request failed`);
          }
          
          const json = await res.json();
          
          if (!json.success) {
            throw new Error(json.message || 'Toggle failed');
          }
          
          alert(`Scholarship ${action}d successfully`);
          await loadData();
        } catch (err) {
          console.error('Toggle error:', err);
          alert('Toggle status failed: ' + err.message);
        }
      }

      saveBtn.addEventListener('click', async () => {
        clearErrors();
        const id = document.getElementById('scholarship_id').value || null;
        
        // Collect form data
        const payload = {
          scholarship_type: document.getElementById('scholarship_type').value.trim(),
          scholarship_name: document.getElementById('scholarship_name').value.trim(),
          short_name: document.getElementById('short_name').value.trim(),
          description: document.getElementById('description').value.trim(),
          category: document.getElementById('category').value.trim(),
          applicable_for: document.getElementById('applicable_for').value.trim(),
          status: document.getElementById('status').value.trim()
        };

        // Client-side validation
        if (!payload.scholarship_type) {
          showError('Please select a scholarship type');
          return;
        }
        
        if (!payload.scholarship_name) {
          showError('Please enter scholarship name');
          return;
        }
        
        if (!payload.short_name) {
          showError('Please enter short name');
          return;
        }

        if (!payload.category) {
          showError('Category is Required');
          return;
        }

        // Disable button to prevent double submission
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';

        try {
          const method = id ? 'PUT' : 'POST';
          const url = id ? `${ENDPOINT_BASE}/${id}` : `${ENDPOINT_BASE}`;
          
          console.log('Sending request:', { method, url, payload });
          
          const res = await fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          });

          console.log('Response status:', res.status);
          
          // Try to parse response
          let json;
          try {
            const text = await res.text();
            console.log('Response text:', text);
            json = text ? JSON.parse(text) : {};
          } catch (parseErr) {
            console.error('JSON parse error:', parseErr);
            throw new Error('Invalid JSON response from server');
          }

          if (!res.ok) {
            // Handle HTTP errors
            if (json.errors) {
              // Validation errors
              const errorMessages = Object.values(json.errors).flat().join('\n');
              throw new Error(errorMessages);
            }
            throw new Error(json.message || `HTTP ${res.status}: ${res.statusText}`);
          }

          if (!json.success) {
            // Handle application-level errors
            const msg = json.message || (json.errors ? JSON.stringify(json.errors) : 'Save failed');
            throw new Error(msg);
          }

          // Success!
          showSuccess(id ? 'Scholarship updated successfully' : 'Scholarship created successfully');
          
          setTimeout(() => {
            bsScholarshipModal.hide();
            form.reset();
            document.getElementById('scholarship_id').value = '';
            document.getElementById('scholarshipModalLabel').textContent = 'Create Scholarship';
            clearErrors();
            loadData();
          }, 1500);

        } catch (err) {
          console.error('Save error:', err);
          showError('Failed to save scholarship: ' + err.message);
        } finally {
          // Re-enable button
          saveBtn.disabled = false;
          saveBtn.textContent = 'Save Scholarship';
        }
      });

      document.querySelectorAll('#add').forEach(btn => {
        btn.addEventListener('click', () => {
          form.reset();
          document.getElementById('scholarship_id').value = '';
          document.getElementById('scholarshipModalLabel').textContent = 'Create Scholarship';
          clearErrors();
        });
      });

      document.getElementById('cancelBtn')?.addEventListener('click', () => {
        form.reset();
        document.getElementById('scholarship_id').value = '';
        document.getElementById('scholarshipModalLabel').textContent = 'Create Scholarship';
        clearErrors();
      });

      scholarshipModalEl.addEventListener('hidden.bs.modal', () => {
        form.reset();
        document.getElementById('scholarship_id').value = '';
        document.getElementById('scholarshipModalLabel').textContent = 'Create Scholarship';
        clearErrors();
      });

      // Initial load
      loadData();
    });
  </script>
</body>

</html><?php /**PATH C:\Users\dhamu\Syn-2\resources\views/master/scholarship/index.blade.php ENDPATH**/ ?>