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
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <i class="fa-solid fa-user-group" style="color: #b8bdc7;"></i>User Management </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/user management/emp/emp.html" class="item"> <i class="fa-solid fa-user" style="color: #b8bdc7;"></i>
                                        Employee</a></li>
                                <li><a href="/user management/batches a/batchesa.html" class="item"><i class="fa-solid fa-user-group" style="color: #b8bdc7;"></i>
                                        Batches Assignment</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            <i class="fa-solid fa-user-group" style="color: #b8bdc7;"></i> Master </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/master/courses/course.html" class="item"><i class="fa-solid fa-book-open" style="color: #b8bdc7;"></i>
                                        Courses</a></li>
                                <li><a href="/master/batches/batches.html" class="item"><i class="fa-solid fa-user-group fa-flip-horizontal"
                                            style="color: #c2c2c2;"></i> Batches</a></li>
                                <li><a href="/master/scholarship/scholar.html" class="item"><i class="fa-solid fa-graduation-cap" style="color: #b8bdc7;"></i>
                                        Scholarship</a></li>
                                <li><a href="/master/feesm/fees.html" class="item"><i class="fa-solid fa-credit-card" style="color: #b8bdc7;"></i> Fees
                                        Master</a></li>
                                <li><a href="/master/other fees/other.html" class="item"><i class="fa-solid fa-wallet" style="color: #b8bdc7;"></i> Other
                                        Fees Master</a></li>
                                <li><a href="/master/branch/branch.html" class="item"><i class="fa-solid fa-diagram-project" style="color: #b8bdc7;"></i>
                                        Branch Management</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseThree" aria-expanded="false"
                            aria-controls="flush-collapseThree">
                            <i class="fa-solid fa-user-group" style="color: #b8bdc7;"></i>Session Management
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/session mana/session/session.html" class="item"><i class="fa-solid fa-calendar-day" style="color: #b8bdc7;"></i>
                                        Session</a></li>
                                <li><a href="/session mana/calendar/cal.html" class="item"><i class="fa-solid fa-calendar-days" style="color: #b8bdc7;"></i>
                                        Calendar</a></li>
                                <li><a href="/session mana/student/student.html" class="item"><i class="fa-solid fa-user-check" style="color: #b8bdc7;"></i>
                                        Student Migrate</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseFour" aria-expanded="false"
                            aria-controls="flush-collapseFour">
                            <i class="fa-solid fa-user-group" style="color: #b8bdc7;"></i>Student Management

                        </button>
                    </h2>
                    <div id="flush-collapseFour" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/student management/inq/inq.html" class="item"><i class="fa-solid fa-circle-info" style="color: #b8bdc7;"></i>
                                        Inquiry Management</a></li>
                                <li><a href="/student management/stu onboard/onstu.html" class="item"><i class="fa-solid fa-user-check"
                                            style="color: #b8bdc7;"></i>Student Onboard</a></li>
                                <li><a href="/student management/pending/pending.html" class="item"><i class="fa-solid fa-user-check"
                                            style="color: #b8bdc7;"></i>Pending Fees Students</a></li>
                                <li><a href="/student management/students/stu.html" class="item"><i class="fa-solid fa-user-check"
                                            style="color: #b8bdc7;"></i>Students</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseFive" aria-expanded="false"
                            aria-controls="flush-collapseFive">
                            <i class="fa-solid fa-credit-card" style="color: #b8bdc7"></i> Fees Management
                        </button>
                    </h2>
                    <div id="flush-collapseFive" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/fees management/collect/collect.html" class="item"><i class="fa-solid fa-credit-card" style="color: #b8bdc7;"></i> Fees
                                        Collection</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                            <i class="fa-solid fa-user-check" style="color: #b8bdc7;"></i> Attendance Managment
                        </button>
                    </h2>
                    <div id="flush-collapseSix" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/attendance management/students/student.html" class="item"> <i class="fa-solid fa-user" style="color: #b8bdc7;">
                                        </i>Student</a></li>
                                <li><a href="/attendance management/employee/employee.html" class="item"> <i class="fa-solid fa-user" style="color: #b8bdc7;">
                                        </i>Employee</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseSeven" aria-expanded="false"
                            aria-controls="flush-collapseSeven">
                            <i class="fa-solid fa-book-open" style="color: #b8bdc7;"></i> Study Material
                        </button>
                    </h2>
                    <div id="flush-collapseSeven" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/study material/units/units.html" class="item"> <i class="fa-solid fa-user" style="color: #b8bdc7;"> </i>Units</a>
                                </li>
                                <li><a href="/study material/dispatch/dispatch.html" class="item"> <i class="fa-solid fa-user" style="color: #b8bdc7;"> </i>Dispatch
                                        Material</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseEight" aria-expanded="false"
                            aria-controls="flush-collapseEight">
                            <i class="fa-solid fa-chart-column" style="color: #b8bdc7;"></i> Test Series Managment
                        </button>
                    </h2>
                    <div id="flush-collapseEight" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/testseries/test.html" class="item"> <i class="fa-solid fa-user" style="color: #b8bdc7;"> </i>Test
                                        Master</i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseNine" aria-expanded="false"
                            aria-controls="flush-collapseNine"><i class="fa-solid fa-square-poll-horizontal" style="color: #b8bdc7;"></i> Reports</i>
                        </button>
                    </h2>
                    <div id="flush-collapseNine" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <ul class="menu">
                                <li><a href="/reports/walkin/walkin.html" class="item"> <i class="fa-solid fa-user" style="color: #b8bdc7;"></i>Walk
                                        In</a></li>
                                <li><a href="/reports/att/att.html" class="item"><i class="fa-solid fa-calendar-days" style="color: #b8bdc7;"></i>Attendance</a></li>
                                <li><a href="/reports/test/test.html" class="item"><i class="fa-solid fa-file" style="color: #b8bdc7;"></i>Test Series</a></li>
                                <li><a href="/reports/inq/inq.html" class="item"><i class="fa-solid fa-file" style="color: #b8bdc7;"></i>Inquiry History</a></li>
                                <li><a href="/reports/onboard/onboard.html" class="item"><i class="fa-solid fa-file" style="color: #b8bdc7;"></i>Onboard History</a></li>
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