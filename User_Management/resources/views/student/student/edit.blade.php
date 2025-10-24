<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Student Details - {{ $student->name }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    .form-section {
      background: #fff;
      padding: 25px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .form-section h4 {
      color: #ff6b35;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #ff6b35;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 15px;
    }
    
    .form-group.full-width {
      grid-column: 1 / -1;
    }
    
    .form-group label {
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
      display: block;
    }
    
    .form-group label .required {
      color: red;
      margin-left: 2px;
    }
    
    .form-control, .form-select {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      width: 100%;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #ff6b35;
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }
    
    .radio-group {
      display: flex;
      gap: 20px;
      align-items: center;
    }
    
    .radio-group label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: normal;
      margin-bottom: 0;
    }
    
    .radio-group input[type="radio"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
    }
    
    .sticky-footer {
      position: sticky;
      bottom: 0;
      background: #fff;
      padding: 20px;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      margin-top: 30px;
      border-radius: 8px;
    }
    
    .btn-save {
      background: #ff6b35;
      color: white;
      padding: 12px 40px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }
    
    .btn-save:hover {
      background: #e55a2b;
    }
    
    .btn-save:disabled {
      background: #ccc;
      cursor: not-allowed;
    }
    
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #ff6b35;
      text-decoration: none;
      margin-bottom: 20px;
      font-weight: 600;
    }
    
    .back-btn:hover {
      color: #e55a2b;
    }
    
    .success-message {
      display: none;
      background: #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 6px;
      margin-bottom: 20px;
      border: 1px solid #c3e6cb;
    }
    
    .error-message {
      color: red;
      font-size: 14px;
      margin-top: 5px;
      display: none;
    }
  </style>
</head>

<body>

<div class="header">
    <div class="logo">
      <img src="{{asset('images/logo.png.jpg')}}" class="img">
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
                <li><a class="item" href="/master/scholarship/scholar.html"><i class="fa-solid fa-graduation-cap"
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

    <!-- Main Content Area -->
    <div class="right" id="right">
      <div class="container-fluid py-4">
        <a href="{{ route('student.student.pending') }}" class="back-btn">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>

        <!-- Success Message -->
        <div class="success-message" id="successMessage">
          <i class="fa-solid fa-check-circle"></i> Student details updated successfully!
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 style="color: #ff6b35;">
          Edit Student Details
          </h4>
        </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="editStudentForm" method="POST" action="{{ route('student.pendingfees.update', $student->_id) }}">
      @csrf
      @method('PUT')

      <!-- Basic Details Section -->
      <div class="form-section">
        <h4>Basic Details</h4>
        <div class="form-row">
          <div class="form-group">
            <label>Student Name <span class="required">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
          </div>
          
          <div class="form-group">
            <label>Father Name <span class="required">*</span></label>
            <input type="text" name="father" class="form-control" value="{{ old('father', $student->father) }}" required>
          </div>
          
          <div class="form-group">
            <label>Mother Name</label>
            <input type="text" name="mother" class="form-control" value="{{ old('mother', $student->mother) }}">
          </div>
          
          <div class="form-group">
            <label>Date of Birth <span class="required">*</span></label>
            <input type="date" name="dob" class="form-control" 
                   value="{{ old('dob', $student->dob ? date('Y-m-d', strtotime($student->dob)) : '') }}" required>
          </div>
          
          <div class="form-group">
            <label>Father Contact No <span class="required">*</span></label>
            <input type="tel" name="mobileNumber" class="form-control" 
                   value="{{ old('mobileNumber', $student->mobileNumber) }}" 
                   pattern="[0-9]{10}" maxlength="10" required>
          </div>
          
          <div class="form-group">
            <label>Father WhatsApp Number</label>
            <input type="tel" name="fatherWhatsapp" class="form-control" 
                   value="{{ old('fatherWhatsapp', $student->fatherWhatsapp) }}" 
                   pattern="[0-9]{10}" maxlength="10">
          </div>
          
          <div class="form-group">
            <label>Mother Contact No</label>
            <input type="tel" name="motherContact" class="form-control" 
                   value="{{ old('motherContact', $student->motherContact) }}" 
                   pattern="[0-9]{10}" maxlength="10">
          </div>
          
          <div class="form-group">
            <label>Student Contact No</label>
            <input type="tel" name="studentContact" class="form-control" 
                   value="{{ old('studentContact', $student->studentContact) }}" 
                   pattern="[0-9]{10}" maxlength="10">
          </div>
          
          <div class="form-group">
            <label>Category <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="category" value="GENERAL" 
                       {{ old('category', $student->category) == 'GENERAL' ? 'checked' : '' }} required>
                GENERAL
              </label>
              <label>
                <input type="radio" name="category" value="OBC" 
                       {{ old('category', $student->category) == 'OBC' ? 'checked' : '' }}>
                OBC
              </label>
              <label>
                <input type="radio" name="category" value="SC" 
                       {{ old('category', $student->category) == 'SC' ? 'checked' : '' }}>
                SC
              </label>
              <label>
                <input type="radio" name="category" value="ST" 
                       {{ old('category', $student->category) == 'ST' ? 'checked' : '' }}>
                ST
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Gender <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="gender" value="Male" 
                       {{ old('gender', $student->gender) == 'Male' ? 'checked' : '' }} required>
                Male
              </label>
              <label>
                <input type="radio" name="gender" value="Female" 
                       {{ old('gender', $student->gender) == 'Female' ? 'checked' : '' }}>
                Female
              </label>
              <label>
                <input type="radio" name="gender" value="Others" 
                       {{ old('gender', $student->gender) == 'Others' ? 'checked' : '' }}>
                Others
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Father Occupation</label>
            <input type="text" name="fatherOccupation" class="form-control" 
                   value="{{ old('fatherOccupation', $student->fatherOccupation) }}">
          </div>
          
          <div class="form-group">
            <label>Father's Grade</label>
            <input type="text" name="fatherGrade" class="form-control" 
                   value="{{ old('fatherGrade', $student->fatherGrade) }}">
          </div>
          
          <div class="form-group">
            <label>Mother Occupation</label>
            <input type="text" name="motherOccupation" class="form-control" 
                   value="{{ old('motherOccupation', $student->motherOccupation) }}">
          </div>
        </div>
      </div>

      <!-- Address Details Section -->
      <div class="form-section">
        <h4>Address Details</h4>
        <div class="form-row">
          <div class="form-group">
            <label>State <span class="required">*</span></label>
            <select name="state" class="form-select" required>
              <option value="">Select State</option>
              <option value="Rajasthan" {{ old('state', $student->state) == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
              <!-- Add more states as needed -->
            </select>
          </div>
          
          <div class="form-group">
            <label>City <span class="required">*</span></label>
            <input type="text" name="city" class="form-control" 
                   value="{{ old('city', $student->city) }}" required>
          </div>
          
          <div class="form-group">
            <label>Pin Code <span class="required">*</span></label>
            <input type="text" name="pinCode" class="form-control" 
                   value="{{ old('pinCode', $student->pinCode) }}" 
                   pattern="[0-9]{6}" maxlength="6" required>
          </div>
          
          <div class="form-group full-width">
            <label>Address <span class="required">*</span></label>
            <textarea name="address" class="form-control" rows="3" required>{{ old('address', $student->address) }}</textarea>
          </div>
          
          <div class="form-group">
            <label>Do you belong to another city? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="belongToOtherCity" value="Yes" 
                       {{ old('belongToOtherCity', $student->belongToOtherCity) == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="belongToOtherCity" value="No" 
                       {{ old('belongToOtherCity', $student->belongToOtherCity) == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Do You Belong to Economic Weaker Section? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="economicWeakerSection" value="Yes" 
                       {{ old('economicWeakerSection', $student->economicWeakerSection) == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="economicWeakerSection" value="No" 
                       {{ old('economicWeakerSection', $student->economicWeakerSection) == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Do You Belong to Any Army/Police/Martyr Background? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="armyPoliceBackground" value="Yes" 
                       {{ old('armyPoliceBackground', $student->armyPoliceBackground) == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="armyPoliceBackground" value="No" 
                       {{ old('armyPoliceBackground', $student->armyPoliceBackground) == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Are You a Specially Abled? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="speciallyAbled" value="Yes" 
                       {{ old('speciallyAbled', $student->speciallyAbled) == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="speciallyAbled" value="No" 
                       {{ old('speciallyAbled', $student->speciallyAbled) == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Details Section -->
      <div class="form-section">
        <h4>Course Details</h4>
        <div class="form-row">
          <div class="form-group">
            <label>Course Type <span class="required">*</span></label>
            <select name="courseType" class="form-select" required>
              <option value="">Select Course Type</option>
              <option value="Pre Foundation" {{ old('courseType', $student->courseType) == 'Pre Foundation' ? 'selected' : '' }}>Pre Foundation</option>
              <option value="Foundation" {{ old('courseType', $student->courseType) == 'Foundation' ? 'selected' : '' }}>Foundation</option>
              <option value="Target" {{ old('courseType', $student->courseType) == 'Target' ? 'selected' : '' }}>Target</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Course Name <span class="required">*</span></label>
            <select name="courseName" class="form-select" required>
              <option value="">Select Course</option>
              <option value="Nucleus 7th" {{ old('courseName', $student->courseName) == 'Nucleus 7th' ? 'selected' : '' }}>Nucleus 7th</option>
              <option value="Nucleus 8th" {{ old('courseName', $student->courseName) == 'Nucleus 8th' ? 'selected' : '' }}>Nucleus 8th</option>
              <option value="Nucleus 9th" {{ old('courseName', $student->courseName) == 'Nucleus 9th' ? 'selected' : '' }}>Nucleus 9th</option>
              <option value="Nucleus 10th" {{ old('courseName', $student->courseName) == 'Nucleus 10th' ? 'selected' : '' }}>Nucleus 10th</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Delivery Mode <span class="required">*</span></label>
            <select name="deliveryMode" class="form-select" required>
              <option value="">Select Mode</option>
              <option value="Offline" {{ old('deliveryMode', $student->deliveryMode) == 'Offline' ? 'selected' : '' }}>Offline</option>
              <option value="Online" {{ old('deliveryMode', $student->deliveryMode) == 'Online' ? 'selected' : '' }}>Online</option>
              <option value="Hybrid" {{ old('deliveryMode', $student->deliveryMode) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Medium <span class="required">*</span></label>
            <select name="medium" class="form-select" required>
              <option value="">Select Medium</option>
              <option value="English" {{ old('medium', $student->medium) == 'English' ? 'selected' : '' }}>English</option>
              <option value="Hindi" {{ old('medium', $student->medium) == 'Hindi' ? 'selected' : '' }}>Hindi</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Board <span class="required">*</span></label>
            <select name="board" class="form-select" required>
              <option value="">Select Board</option>
              <option value="CBSE" {{ old('board', $student->board) == 'CBSE' ? 'selected' : '' }}>CBSE</option>
              <option value="RBSE" {{ old('board', $student->board) == 'RBSE' ? 'selected' : '' }}>RBSE</option>
              <option value="ICSE" {{ old('board', $student->board) == 'ICSE' ? 'selected' : '' }}>ICSE</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Course Content <span class="required">*</span></label>
            <select name="courseContent" class="form-select" required>
              <option value="">Select Content</option>
              <option value="Class 10th course" {{ old('courseContent', $student->courseContent) == 'Class 10th course' ? 'selected' : '' }}>Class 10th course</option>
              <option value="JEE/NEET Foundation" {{ old('courseContent', $student->courseContent) == 'JEE/NEET Foundation' ? 'selected' : '' }}>JEE/NEET Foundation</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Academic Detail Section -->
      <div class="form-section">
        <h4>Academic Detail</h4>
        <div class="form-row">
          <div class="form-group">
            <label>Previous Class <span class="required">*</span></label>
            <select name="previousClass" class="form-select" required>
              <option value="">Select Previous Class</option>
              <option value="6th" {{ old('previousClass', $student->previousClass) == '6th' ? 'selected' : '' }}>6th</option>
              <option value="7th" {{ old('previousClass', $student->previousClass) == '7th' ? 'selected' : '' }}>7th</option>
              <option value="8th" {{ old('previousClass', $student->previousClass) == '8th' ? 'selected' : '' }}>8th</option>
              <option value="9th" {{ old('previousClass', $student->previousClass) == '9th' ? 'selected' : '' }}>9th</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Previous Medium <span class="required">*</span></label>
            <select name="previousMedium" class="form-select" required>
              <option value="">Select Medium</option>
              <option value="English" {{ old('previousMedium', $student->previousMedium) == 'English' ? 'selected' : '' }}>English</option>
              <option value="Hindi" {{ old('previousMedium', $student->previousMedium) == 'Hindi' ? 'selected' : '' }}>Hindi</option>
            </select>
          </div>
          
          <div class="form-group full-width">
            <label>Name Of School</label>
            <input type="text" name="schoolName" class="form-control" 
                   value="{{ old('schoolName', $student->schoolName) }}">
          </div>
          
          <div class="form-group">
            <label>Previous Board <span class="required">*</span></label>
            <select name="previousBoard" class="form-select" required>
              <option value="">Select Board</option>
              <option value="CBSE" {{ old('previousBoard', $student->previousBoard) == 'CBSE' ? 'selected' : '' }}>CBSE</option>
              <option value="RBSE" {{ old('previousBoard', $student->previousBoard) == 'RBSE' ? 'selected' : '' }}>RBSE</option>
              <option value="ICSE" {{ old('previousBoard', $student->previousBoard) == 'ICSE' ? 'selected' : '' }}>ICSE</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Passing Year <span class="required">*</span></label>
            <input type="text" name="passingYear" class="form-control" 
                   value="{{ old('passingYear', $student->passingYear) }}" 
                   pattern="[0-9]{4}" maxlength="4" placeholder="YYYY" required>
          </div>
          
          <div class="form-group">
            <label>Percentage <span class="required">*</span></label>
            <input type="number" name="percentage" class="form-control" 
                   value="{{ old('percentage', $student->percentage) }}" 
                   min="0" max="100" step="0.01" required>
          </div>
        </div>
      </div>

      <!-- Scholarship Eligibility Section -->
      <div class="form-section">
        <h4>Scholarship Eligibility</h4>
        <div class="form-row">
          <div class="form-group">
            <label>Is Repeater <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="isRepeater" value="Yes" 
                       {{ old('isRepeater', $student->isRepeater) == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="isRepeater" value="No" 
                       {{ old('isRepeater', $student->isRepeater) == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Scholarship Test Appeared <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="scholarshipTest" value="Yes" 
                       {{ old('scholarshipTest', $student->scholarshipTest) == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="scholarshipTest" value="No" 
                       {{ old('scholarshipTest', $student->scholarshipTest) == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Last Board Percentage</label>
            <input type="number" name="lastBoardPercentage" class="form-control" 
                   value="{{ old('lastBoardPercentage', $student->lastBoardPercentage) }}" 
                   min="0" max="100" step="0.01">
          </div>
          
          <div class="form-group">
            <label>Competition Exam Appeared <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="competitionExam" value="Yes" 
                       {{ old('competitionExam', $student->competitionExam) == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="competitionExam" value="No" 
                       {{ old('competitionExam', $student->competitionExam) == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Batch Allocation Section -->
      <div class="form-section">
        <h4>Batch Allocation</h4>
        <div class="form-row">
          <div class="form-group">
            <label>Batch Name <span class="required">*</span></label>
            <input type="text" name="batchName" class="form-control" 
                   value="{{ old('batchName', $student->batchName) }}" required>
          </div>
        </div>
      </div>

      <!-- Sticky Footer with Save Button -->
      <div class="sticky-footer">
        <button type="submit" class="btn-save" id="saveBtn">
          <i class="fa-solid fa-check"></i> Save Changes
        </button>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
          crossorigin="anonymous"></script>
        <script src="{{ asset('js/session.js') }}"></script>
  <script>
    // Form submission handling
    document.getElementById('editStudentForm').addEventListener('submit', function(e) {
      const saveBtn = document.getElementById('saveBtn');
      saveBtn.disabled = true;
    });

    // Re-enable button if there are validation errors
    @if($errors->any())
      const saveBtn = document.getElementById('saveBtn');
      saveBtn.disabled = false;
      saveBtn.innerHTML = '<i class="fa-solid fa-check"></i> Save';
    @endif
  </script>
</body>
</html>