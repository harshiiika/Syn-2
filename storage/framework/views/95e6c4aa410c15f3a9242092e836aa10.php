<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Pending Fees Students</title>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <!-- Bootstrap 5.3.6 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

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
.icons{
    display: flex;
    justify-content: center;
    align-items: baseline;
}
#side-icon{
    color: #838383;
    font-size: 16px;
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
    color: rgb(190, 51, 0);
    font-size: 18px;
}

.text {
    justify-content: center;
    align-items: center;
}
#dropdown-body{
    padding: 0 !important;
    display: flex;
    align-items: flex-start;
    flex-direction: column;
    justify-content: flex-start;
    margin: 0 !important;
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
#toggle-btn {
    background-color: transparent;
    justify-content: center;
    display: flex;
    border: none;
    cursor: pointer; 
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

.fa-solid,
.fa-regular {
    margin: 0px 10px 0px 5px;
    font-size: 15px;
}

 .accordion-flush {
    justify-content: flex-start;
    align-items: center;
    height: 10px;
} 
.accordion-body{
    padding: 10px !important;
}
.menu {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-direction: column;

}

.fa-bars {
    margin-top: 15px;
    cursor: pointer;
    font-size: large;
    width: 40px;
    height: 40px;
    text-align: center;
    justify-content: center;
    align-items: center;
    display: flex;
    flex-wrap: wrap;
    align-content: center;
}

.fa-bars:hover {
    background-color: rgb(212, 208, 207);
    transition: 0.3s;
    border-radius: 50%;
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
.whole{
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
.right h2 , .right p{
    color: rgb(224, 83, 1);
    margin: 10px;
}

.toggleBtn {
    border: none;
}

#table{
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
    width: 130px;
    border-radius: 7px;
    border: 0;
    margin: 10px;
}
#submit{
     border:1px solid rgb(249, 193, 161);
    background-color: #ff6200;
    color: #fff;
    height: 40px;
    width: 100px;
    border-radius: 7px; 
    margin: 10px;
}
#up{
    background-color: rgb(8, 90, 0);
    color: #edfeff;
    height: 40px;
    width: 130px;
    border-radius: 7px;
    border: 0;
    margin: 10px;

}

.menu li {
    list-style: none;
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

#number {
    margin: 5px 0px 10px 5px;
    align-items: center;
    border: 1px solid #000;
    background-color: #ffffff;
    color: #000;
}

.dd {
    margin: 5px;
    width: 100%;
    flex-direction: row;
    display: flex;
    align-items: center;
    justify-content: space-between;

}

#ellipsis {
    background-color: transparent;
    justify-content: center;
    display: flex;
    border: none;
    cursor: pointer; 
}


#serial{
    margin-left: 15px;
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

#one{
    color: rgb(224, 83, 1); 
}
.search-holder{
    border: 1px solid rgb(224, 83, 1);
    border-radius: 5px;
    outline: none;
    padding: 5px;
}


#bottom{
    display: flex;
    align-items: center;
    width: 98%;
    justify-content: flex-end;
}

#pagination{
    border: 1px solid #d4d4d4;
}
#pg1{
    height: 30px;
    width: 70px;
    display: flex;
    justify-content: center;
    color: #838383;
    align-items: center;
}
#pg2{
height: 30px;
    width: 50px;
    display: flex;
    color: #fff;
    justify-content: center;
    align-items: center;
    background-color:  rgb(224, 83, 1);
    border:none;
}
#pg3{
    height: 30px;
    color:  rgb(224, 83, 1);
    width: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

#form{
    overflow-y: scroll;
    height: 400px;
    width: 700px;
}

.modal-dialog-scrollable , .modal-content{
    height: 90vh;
    display: flex;
    flex-direction: column;
}

#inputGroupFile01{
    width: 500px;
    border: 1px dashed #d8d8d8;
}

.modal-body{
    overflow-y: auto;
    flex: 1 1 auto;
}

.modal-footer{
    position: sticky;
    bottom: 0;
    background-color: #fff;
    z-index: 10; 
}
#content-one{
    height: 500px;
    width: 600px;
     margin-top: 80px;
}
#modal-two{
    height: 300px;
    width: 600px;
    
}
#sample-body{
      display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
 #file{
    height: 34px;
    width: 400px;
    border: 2px solid #d8d8d8;
    border-radius: 5px;
    display: flex;
    align-items: center; 
}

#xlsx{
    margin-bottom: 20px;
    height: 40px;
    width: 200px;
    border-radius: 10px;
    background-color:  rgb(224, 83, 1);
    border: 0;
    color: #fff;
}

.footer{
    flex-direction: row;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 95%;
}
.left-footer p{
    font-size: 12px;
}
 
#pass{
    display: flex;
    flex-direction: row;
    width: 100%;
    justify-content: space-around;
    align-items: center;
}

#exampleInputEmail1, #exampleInputPassword1{
    height: 35px;
    width: 110%;
    margin-right: 20px;
}

#active{
    background-color: transparent;
}

/* Fixed Action Button - Three Dots with NO background color */
.btn-action-dots {
    background-color: transparent !important;
    border: none !important;
    color: #000 !important;
    font-size: 18px;
    padding: 5px 10px;
    cursor: pointer;
    transition: color 0.2s ease;
}

.btn-action-dots:hover {
    color: rgb(224, 83, 1) !important;
}

.btn-action-dots:focus {
    outline: none !important;
    box-shadow: none !important;
}

.accordion-flush, .accordion-header{
    border: none;
    justify-content: flex-start;
    align-items: center;
    height: 40px;
    width: 290px;
    font-size: 17px;
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
.menu li a{
    text-decoration: none;
    color: #000;
}
.accordion-body, .menu{
    padding: 0 !important;
    margin-left: 5px;
}

.btns{
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    gap: 40px;
    width: 77vw;
    font-size: 17px;
    align-items: center;
    margin: 25px 15px 25px 10px;
}
.pendingbtn{
    color: rgb(233, 96, 47);
    background-color: white;
    border: none;
    width: 200px;
    height: 40px;
    border-radius: 5px;
    cursor: pointer;
}
.onboardbtn{
    border: none;
    background-color: rgb(233, 96, 47);
    color: white;
    width: 200px;
    height: 40px;
    border-radius: 5px; 
    cursor: pointer;
}

/* Transfer button styling */
.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background-color: #ffca2c;
    border-color: #ffc720;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
}

/* Success button */
.btn-success {
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(25, 135, 84, 0.3);
}

/* Dropdown item icons */
.dropdown-item i {
    margin-right: 8px;
    width: 16px;
    text-align: center;
}

/* Action Dropdown Menu Styling */
.action-dropdown-menu {
    min-width: 150px !important;
    padding: 5px 0 !important;
}

.action-item {
    padding: 10px 20px !important;
    font-size: 14px !important;
    color: #000 !important;
    white-space: nowrap !important;
    display: block !important;
    width: 100% !important;
    text-align: left !important;
    background: none !important;
    border: none !important;
    cursor: pointer !important;
    transition: background-color 0.2s ease !important;
}

.action-item:hover {
    background-color: #f8f9fa !important;
    color: rgb(224, 83, 1) !important;
}

.action-item:focus {
    outline: none !important;
    box-shadow: none !important;
}
   </style> 
</head>

<body>
  <!-- Header Section: Contains logo, sidebar toggle, session selector, notifications, and user menu -->
  <div class="header">
    <div class="logo">
      <img src="<?php echo e(asset('images/logo.png.jpg')); ?>" class="img">
      <!-- Sidebar toggle button -->
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
          <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>""> <i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log In</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <!-- Left Sidebar: Navigation menu with collapsible accordion sections -->
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

    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
          <h4>Pending Fees Students</h4>
        </div>
      </div>

      <div class="whole">
        <!-- Table controls: entries dropdown and search -->
<!-- Table controls: entries dropdown and search -->
<div class="dd">
  <div class="line">
    <h6>Show Entries:</h6>
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <?php echo e(request('per_page', 10)); ?>

      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo e(route('student.pendingfees.pending', ['per_page' => 5, 'search' => request('search')])); ?>">5</a></li>
        <li><a class="dropdown-item" href="<?php echo e(route('student.pendingfees.pending', ['per_page' => 10, 'search' => request('search')])); ?>">10</a></li>
        <li><a class="dropdown-item" href="<?php echo e(route('student.pendingfees.pending', ['per_page' => 25, 'search' => request('search')])); ?>">25</a></li>
        <li><a class="dropdown-item" href="<?php echo e(route('student.pendingfees.pending', ['per_page' => 50, 'search' => request('search')])); ?>">50</a></li>
        <li><a class="dropdown-item" href="<?php echo e(route('student.pendingfees.pending', ['per_page' => 100, 'search' => request('search')])); ?>">100</a></li>
      </ul>
    </div>
  </div>
  <div class="search">
    <form method="GET" action="<?php echo e(route('student.pendingfees.pending')); ?>" id="searchForm">
      <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
      <input type="search" 
             name="search" 
             placeholder="Search by name, father, mobile, course..." 
             class="search-holder" 
             value="<?php echo e(request('search')); ?>"
             id="searchInput">
      <i class="fa-solid fa-magnifying-glass"></i>
    </form>
  </div>
</div>

        <table class="table table-hover" id="table">
          <thead>
            <tr>
              <th scope="col" id="one">Serial No.</th>
              <th scope="col" id="one">Student Name</th>
              <th scope="col" id="one">Father Name</th>
              <th scope="col" id="one">Father Contact No.</th>
              <th scope="col" id="one">Course Name</th>
              <th scope="col" id="one">Delivery Mode</th>
              <th scope="col" id="one">Course Content</th>
              <th scope="col" id="one">Action</th>
            </tr>
          </thead>
        <tbody>
  <?php $__empty_1 = true; $__currentLoopData = $pendingFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pending): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  <tr>
    <!-- Serial number with pagination support -->
    <td><?php echo e(($pendingFees->currentPage() - 1) * $pendingFees->perPage() + $index + 1); ?></td>
    <td><?php echo e($pending->name); ?></td>
    <td><?php echo e($pending->father); ?></td>
    <td><?php echo e($pending->mobileNumber ?? '—'); ?></td>
    <td><?php echo e($pending->courseName ?? '—'); ?></td>
    <td><?php echo e($pending->deliveryMode ?? '—'); ?></td>
    <td><?php echo e($pending->courseContent ?? '—'); ?></td>
    <td>
      <div class="dropdown">
        <button class="btn-action-dots" type="button" id="actionMenuButton-<?php echo e($pending->_id); ?>"
          data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <ul class="dropdown-menu action-dropdown-menu" aria-labelledby="actionMenuButton-<?php echo e($pending->_id); ?>">
          <li>
            <a class="dropdown-item action-item" href="<?php echo e(route('student.pendingfees.view', $pending->_id)); ?>">
              View Details
            </a>
          </li>
          <li>
            <a class="dropdown-item action-item" href="<?php echo e(route('student.pendingfees.edit', $pending->_id)); ?>">
              Edit
            </a>
          </li>
          <li>
            <a class="dropdown-item action-item" href="<?php echo e(route('student.pendingfees.pay', $pending->_id)); ?>">
              Pay Fees
            </a>
          </li>
          <li>
            <button class="dropdown-item action-item" onclick="loadStudentHistory('<?php echo e($pending->_id); ?>'); return false;">
              History
            </button>
          </li>
        </ul>
      </div>
    </td>
  </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  <tr>
    <td colspan="8" class="text-center py-4">
      <?php if(request('search')): ?>
        <p class="mb-0">No students found matching "<?php echo e(request('search')); ?>"</p>
        <a href="<?php echo e(route('student.pendingfees.pending')); ?>" class="btn btn-sm btn-outline-secondary mt-2">
          <i class="fa-solid fa-times"></i> Clear Search
        </a>
      <?php else: ?>
        <p class="mb-0">No pending fees students found</p>
      <?php endif; ?>
    </td>
  </tr>
  <?php endif; ?>
</tbody>
        </table>
      </div>

      <div class="footer">
        <div class="left-footer">
          <p>Showing 1 to 10 of 10 Entries</p>
        </div>
        <div class="right-footer">
          <nav aria-label="...">
            <ul class="pagination">
              <li class="page-item"><a href="#" class="page-link" id="pg1">Previous</a></li>
              <li class="page-item active">
                <a class="page-link" href="#" aria-current="page" id="pg2">1</a>
              </li>
              <li class="page-item"><a class="page-link" href="#" id="pg3">2</a></li>
              <li class="page-item"><a class="page-link" href="#" id="pg1">Next</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- History Modal -->
  <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header" style="background-color: rgb(224, 83, 1); color: white;">
          <h5 class="modal-title" id="historyModalLabel">
            <i class="fa-solid fa-clock-rotate-left me-2"></i>Student Activity History
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="historyModalBody" style="min-height: 300px; background-color: #f8f9fa;">
          <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading history...</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark me-1"></i>Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- External JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <script src="<?php echo e(asset('js/emp.js')); ?>"></script>

  <!-- History Modal JavaScript -->
  <script>
    // Initialize Bootstrap modal
    let historyModal;

    document.addEventListener('DOMContentLoaded', function() {
      historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
      console.log('  History Modal initialized');
    });

    // Load student history function
    function loadStudentHistory(studentId) {
      console.log('  Loading history for student:', studentId);

      // Show loading spinner
      document.getElementById('historyModalBody').innerHTML = `
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-3 text-muted">Loading student history...</p>
        </div>
      `;

      // Show modal
      historyModal.show();

      // Fetch history from server
      fetch(`/student/pendingfees/${studentId}/history`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => {
          console.log('📡 Response status:', response.status);
          if (!response.ok) {
            throw new Error(`HTTP ${response.status}: Failed to load history`);
          }
          return response.json();
        })
        .then(json => {
          console.log('  History response:', json);

          if (!json.success) {
            throw new Error(json.message || 'Failed to load history');
          }

          const history = json.data || [];

          // If no history exists
          if (history.length === 0) {
            document.getElementById('historyModalBody').innerHTML = `
              <div class="text-center text-muted py-5">
                <i class="fa-solid fa-clock-rotate-left fa-4x mb-3" style="color: #ddd;"></i>
                <h5 class="mb-2">No History Available</h5>
                <p class="text-muted">Activity will appear here once changes are made to this student</p>
              </div>
            `;
            return;
          }

          // Render history timeline
          let historyHtml = '<div class="timeline p-3">';

          history.forEach((item, index) => {
            const date = new Date(item.timestamp);
            const formattedDate = date.toLocaleString('en-IN', {
              day: '2-digit',
              month: 'short',
              year: 'numeric',
              hour: '2-digit',
              minute: '2-digit',
              hour12: true
            });

            // Format changes if they exist
            let changesHtml = '';
            if (item.changes && typeof item.changes === 'object' && Object.keys(item.changes).length > 0) {
              const changesList = Object.entries(item.changes).map(([key, value]) => {
                if (typeof value === 'object' && value.from !== undefined && value.to !== undefined) {
                  return `<li><strong>${formatKey(key)}:</strong> <span class="text-muted">"${escapeHtml(value.from)}"</span> → <span class="text-success">"${escapeHtml(value.to)}"</span></li>`;
                }
                return `<li><strong>${formatKey(key)}:</strong> ${escapeHtml(JSON.stringify(value))}</li>`;
              }).join('');

              if (changesList) {
                changesHtml = `
                  <div class="mt-3 p-3 bg-warning-subtle border-start border-warning border-3 rounded">
                    <strong class="text-warning-emphasis">
                      <i class="fa-solid fa-pen-to-square me-1"></i>Changes Made:
                    </strong>
                    <ul class="mb-0 mt-2 ps-3">
                      ${changesList}
                    </ul>
                  </div>
                `;
              }
            }

            historyHtml += `
              <div class="timeline-item position-relative mb-4 pb-3 border-start border-3 border-primary ps-4">
                <div class="position-absolute start-0 translate-middle bg-primary rounded-circle" 
                     style="width: 15px; height: 15px; top: 20px; left: 0; border: 3px solid white;"></div>
                
                <div class="card shadow-sm">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <div>
                        <h6 class="text-primary mb-1">
                          <i class="fa-solid fa-circle-check me-1"></i>
                          ${escapeHtml(item.action || 'Activity')}
                        </h6>
                        <small class="text-muted">
                          <i class="fa-solid fa-user me-1"></i>
                          ${escapeHtml(item.user || 'Admin')}
                        </small>
                      </div>
                      <small class="badge bg-secondary">
                        <i class="fa-solid fa-clock me-1"></i>
                        ${formattedDate}
                      </small>
                    </div>
                    
                    <p class="text-secondary mb-2">
                      ${escapeHtml(item.description || 'Activity recorded')}
                    </p>
                    
                    ${changesHtml}
                  </div>
                </div>
              </div>
            `;
          });

          historyHtml += '</div>';

          document.getElementById('historyModalBody').innerHTML = historyHtml;

        })
        .catch(error => {
          console.error(' History error:', error);
          document.getElementById('historyModalBody').innerHTML = `
            <div class="text-center text-danger py-5">
              <i class="fa-solid fa-exclamation-triangle fa-4x mb-3"></i>
              <h5 class="mb-2">Failed to Load History</h5>
              <p class="text-muted">${escapeHtml(error.message)}</p>
              <small class="text-muted">Please try again or check the console for details</small>
            </div>
          `;
        });
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // Helper function to format keys
    function formatKey(key) {
      return key
        .replace(/_/g, ' ')
        .replace(/([A-Z])/g, ' $1')
        .trim()
        .replace(/\b\w/g, l => l.toUpperCase());
    }
    document.addEventListener('DOMContentLoaded', function() {
    // Search icon click handler
    const searchIcon = document.querySelector('.search i.fa-magnifying-glass');
    const searchForm = document.getElementById('searchForm');
    
    if (searchIcon && searchForm) {
        searchIcon.addEventListener('click', function() {
            searchForm.submit();
        });
    }

    // Search input enter key handler
    const searchInput = document.getElementById('searchInput');
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

</body>

</html><?php /**PATH C:\Users\Priyanshi Rathore\Syn-2\resources\views/student/pendingfees/pending.blade.php ENDPATH**/ ?>