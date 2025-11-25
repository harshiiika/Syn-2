<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch_material</title>
    <link rel="stylesheet" href="dispatch.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

        <style>* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    font-family: Arial, sans-serif;
    font-size: 14px;
    position: relative;
}


.top {
    display: flex;
    flex-direction: row;
}

#toggle-btn {
    background-color: transparent;
    justify-content: center;
    display: flex;
    border: none;
    cursor: pointer;
}

.dropdown-item {
    height: 20px;
    width: 70px;
    margin: 15px;
    border-radius: 5px;
    justify-content: center;
    text-align: center;
    align-items: center;
    display: flex;
}

.header {
    margin-top: 10px;
    display: flex;
    flex-direction: row;
    width: 400px;
    height: 70px;
    justify-content: space-between;
    border: 2px solid #f0ecec;
}

.logo {
    width: 170px;
    height: 50px;
    margin: 10px 0 0 10px;
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
 

.main-container {
    display: flex;
    flex-direction: row;
    max-width: 100% !important;
    justify-content: space-between;
}

.session {
    display: flex;
    flex-direction: row;
    width: 100%;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin-right: 45px;
    font-weight: bold;
    font-size: 20px;
}

.select {
    width: 120px;
    height: 30px;
    border: 2px solid rgb(233, 96, 47);
    border-radius: 10px;
    font-size: 15px;
}

.left {
    display: flex;
    flex-direction: column;
    width: 20%;
    height: 100vh;
}
.accordion button{
padding: 10px !important;
}
.accordion-flush, .accordion-header{
    border: none;
    justify-content: flex-start;
    align-items: center;
    height: 40px;
    width: 290px;
    font-size: 17px;
}
.menu li{
    padding: 0  !important;
    list-style: none;
    cursor: pointer;
    margin: 5px;
    font-size: medium;
    text-decoration: none;
}
.menu li a{
    text-decoration: none;
    color: #000;
}
.accordion-body, .menu{
    padding: 0 !important;
    margin-left: 5px;
}
.admin {
    margin-top: 25px;
    text-align: center;
}

.admin h2 {
    font-size: 17px;
    font-weight: normal;
    margin-bottom: 5px;
}

.admin h4 {
    font-size: 12px;
    font-weight: normal;
}

.fa-solid, .fa-regular{
    font-size: 15px;
    margin-right: 15px;
}
.fa-chevron-down {
    font-size: 12px;
}

.right {
    display: flex;
    flex-direction: column;
    background-color: rgb(246, 242, 242);
    width: 80%;
    height: 100vh;
    font-size: 20px;
    overflow-x: hidden;
}

.right h5 {
    margin: 20px;
    font-size: 20px;
    font-weight: bold;
    color: rgb(233, 96, 47);
}

.upper{
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    margin-top: 30px;
    gap: 10px;
    margin-left: 25px; 
}

#course,
#batch {
    width: 400px;
    height: 40px;
    border-radius: 7px;
    border: 1px solid rgb(228, 224, 224);
    font-size: 15px;
    padding: 10px;
    outline: rgb(233, 96, 47);
}

#course:focus,
#batch:focus {
    border: 2px solid rgb(233, 96, 47);
    }

.search{
    background-color: rgb(233, 96, 47);
    border: 1px solid rgb(233, 96, 47);
    color: rgb(255, 255, 255);
    width: 100px;
    height: 40px;
    border-radius: 7px;
    font-size: 16px;
}
.rw{
    display: flex;
    width: 100%;
    justify-content: flex-end;
}
.dispatch{
    background-color: rgb(233, 96, 47);
    color: white;
    font-size: 15px;
    width: 140px;
    margin-right: 15px;
    margin-bottom: 15px;
    height: 35px;
    border: none;
    border-radius: 5px;
}

#one {
    font-size: 12px;
    color: rgb(233, 96, 47);
}
#table{
    margin: 10px;
}

            </style>
</head>

<body>


    <div class="top">

        <div class="header">
            <img src="https://synthesisbikaner.org/synthesistest/assets/logo-big.png" class="logo">
            <i class="fa-solid fa-bars" id="toggleBtn"></i>

        </div>

        <div class="session">

            <label>Session:</label>
            <select class="select">
                <option>2026</option>
                <option>2024-25</option>
            </select>
            <i class="fa-solid fa-bell" style="color: rgb(233, 96, 47); font-size: 22px;"></i>

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user" style="color: rgb(233, 96, 47); font-size: 22px;"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="/pfp/pfp.html" class="dropdown-item"> <i class="fa-solid fa-user"
                                style="color: rgb(233, 96, 47); font-size: 15px;"></i>Profile</li></a>
                    <li><a href="/login page/login.html" class="dropdown-item"><i
                                class="fa-solid fa-arrow-right-from-bracket"
                                style="color: rgb(233, 96, 47); font-size: 15px;"></i>Log In</li></a>
                </ul>
            </div>

        </div>
    </div>

    <div class="main-container">

        <div class="left" id="sidebar">

            <div class="admin" id="admin">
                <h2>Admin</h2>
                <h4>synthesisbikaner@gmail.com</h4>
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
          <li><a class="item" href="{{ route('study_material.dispatch.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Dispatch Material</a></li>
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
          <li><a class="item" href="{{ route('test_series.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
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
          <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Walk In</a></li>
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



        <div class="right" id="right">
            <h5>Display Study Material</h5>


            <div class="upper">
                <select id="course" placeholder="Select Course" required>
                    <option value="" disabled selected>Select Course</option>
                    <option value="Anthesis">Anthesis</option>
                    <option value="Momentum">Momentum</option>
                    <option value="Dynamic">Dynamic</option>
                    <option value="Impulse">Impulse</option>
                    <option value="Intensity">Intensity</option>
                    <option value="Thurst">Thurst</option>
                    <option value="Seedling 10th">Seedling 10th</option>
                    <option value="Plumule 9th">plumule 9th</option>
                    <option value="Radicle 8th">Radicle 8th</option>
                    <option value="Pre Radicle 7th">Pre Radicle 7th</option>
                </select>

                <select id="batch" placeholder="Select Batch" required>
                    <option value="" disabled selected>Select Batch</option>
                    <option value="all">All</option>
                </select>
                <button type="button" class="search">Search</button>

            </div>


            <div class="bottom">
                <div class="rw"><button class="dispatch">Dispatch</button></div>
                    <table class="table table-hover" id="table">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                                </th>
                                <th scope="col" id="one">Roll Number</th>
                                <th scope="col" id="one">Student Name</th>
                                <th scope="col" id="one">Father Name</th>
                                <th scope="col" id="one">Batch Name</th>
                                <th scope="col" id="one">Action</th>
                            </tr>
                        </thead>
                    </table>
            </div>
        </div>


    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>

</html>