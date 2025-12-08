<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Student Details</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    .container-custom {
      max-width: 1400px;
      margin: 0 auto;
      padding: 20px;
    }
    
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
      outline: none;
    }
    
    .sticky-footer {
      position: sticky;
      bottom: 0;
      background: #fff;
      padding: 20px;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      margin-top: 30px;
      border-radius: 8px;
      z-index: 100;
    }
    
    .btn-save {
      background: #28a745;
      color: white;
      padding: 12px 40px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .btn-save:hover {
      background: #218838;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .btn-save:disabled {
      background: #6c757d;
      cursor: not-allowed;
      transform: none;
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
    
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 6px;
    }
    
    .alert-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .alert-danger {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>

<body>
  <!-- Header Section -->
  <div class="header">
    <div class="logo">
      <img src="{{asset('images/logo.png.jpg')}}" class="img" alt="Logo">
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
    <!-- Sidebar -->
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
          <li><a class="item" href="{{ route('dispatch.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Dispatch Material</a></li>

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
          <li><a class="item" href="{{ route(name: 'test_series.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
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
          <li><a class="item" href="{{ route('reports.walkin.index') }}"><i class="fa-solid fa-user" id="side-icon"></i>Walk In</a></li>
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

    <!-- Main Content Area -->
       <div class="right" id="right">
      <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
          <h1 class="page-title">Update Student</h1>
          <a href="{{ route('student.pendingfees.pending') }}" class="back-btn" style="border: 1px solid #ff6b35; padding: 8px 16px; border-radius: 6px;">
            <i class="fa-solid fa-arrow-left"></i> Back
          </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
          <div class="alert alert-success">
            <i class="fa-solid fa-check-circle"></i>
            <span>{{ session('success') }}</span>
          </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
          <div class="alert alert-danger">
            <i class="fa-solid fa-exclamation-circle"></i>
            <div>
              <strong>Please fix the following errors:</strong>
              <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif

        <form id="editStudentForm" method="POST" action="{{ route('student.pendingfees.update', $student->_id) }}">
          @csrf
          @method('PUT')

          <!-- Basic Details Section -->
          <div class="form-section">
            <h4>Basic Details</h4>
            <div class="form-row-2col">
              <div class="form-group">
                <label>Student Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $student->name ?? '') }}" required>
              </div>
              
              <div class="form-group">
                <label>Father Name</label>
                <input type="text" name="father" class="form-control" value="{{ old('father', $student->father ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Mother Name</label>
                <input type="text" name="mother" class="form-control" value="{{ old('mother', $student->mother ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>DOB</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob', $student->dob ? date('Y-m-d', strtotime($student->dob)) : '') }}">
              </div>
              
              <div class="form-group">
                <label>Father Contact No</label>
                <input type="tel" name="mobileNumber" class="form-control" value="{{ old('mobileNumber', $student->mobileNumber ?? '') }}" maxlength="15">
              </div>
              
              <div class="form-group">
                <label>Father Whatsapp Number</label>
                <input type="tel" name="fatherWhatsapp" class="form-control" value="{{ old('fatherWhatsapp', $student->fatherWhatsapp ?? '') }}" maxlength="15">
              </div>
              
              <div class="form-group">
                <label>Mother Contact No</label>
                <input type="tel" name="motherContact" class="form-control" value="{{ old('motherContact', $student->motherContact ?? '') }}" maxlength="15">
              </div>
              
              <div class="form-group">
                <label>Student Contact No</label>
                <input type="tel" name="studentContact" class="form-control" value="{{ old('studentContact', $student->studentContact ?? '') }}" maxlength="15">
              </div>
              
              <div class="form-group">
                <label>Category</label>
                <div class="radio-group">
                  <div class="radio-option">
                    <input type="radio" name="category" value="OBC" id="cat-obc" {{ old('category', $student->category ?? '') == 'OBC' ? 'checked' : '' }}>
                    <label for="cat-obc">OBC</label>
                  </div>
                  <div class="radio-option">
                    <input type="radio" name="category" value="SC" id="cat-sc" {{ old('category', $student->category ?? '') == 'SC' ? 'checked' : '' }}>
                    <label for="cat-sc">SC</label>
                  </div>
                  <div class="radio-option">
                    <input type="radio" name="category" value="GENERAL" id="cat-general" {{ old('category', $student->category ?? '') == 'GENERAL' ? 'checked' : '' }}>
                    <label for="cat-general">GENERAL</label>
                  </div>
                  <div class="radio-option">
                    <input type="radio" name="category" value="ST" id="cat-st" {{ old('category', $student->category ?? '') == 'ST' ? 'checked' : '' }}>
                    <label for="cat-st">ST</label>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label>Gender</label>
                <div class="radio-group">
                  <div class="radio-option">
                    <input type="radio" name="gender" value="Male" id="gender-male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'checked' : '' }}>
                    <label for="gender-male">Male</label>
                  </div>
                  <div class="radio-option">
                    <input type="radio" name="gender" value="Female" id="gender-female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'checked' : '' }}>
                    <label for="gender-female">Female</label>
                  </div>
                  <div class="radio-option">
                    <input type="radio" name="gender" value="Others" id="gender-others" {{ old('gender', $student->gender ?? '') == 'Others' ? 'checked' : '' }}>
                    <label for="gender-others">Others</label>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label>Father Occupation</label>
                <input type="text" name="fatherOccupation" class="form-control" value="{{ old('fatherOccupation', $student->fatherOccupation ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Father's Grade</label>
                <input type="text" name="fatherGrade" class="form-control" value="{{ old('fatherGrade', $student->fatherGrade ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Mother Occupation</label>
                <input type="text" name="motherOccupation" class="form-control" value="{{ old('motherOccupation', $student->motherOccupation ?? '') }}">
              </div>
            </div>
          </div>

          <!-- Address Details Section -->
          <div class="form-section">
            <h4>Address Details</h4>
            <div class="form-row-2col">
              <div class="form-group">
                <label>State</label>
                <input type="text" name="state" class="form-control" value="{{ old('state', $student->state ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city', $student->city ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Pin Code</label>
                <input type="text" name="pinCode" class="form-control" value="{{ old('pinCode', $student->pinCode ?? '') }}" maxlength="10">
              </div>
              
              <div class="form-group full-width">
                <label>Address</label>
                <textarea name="address" class="form-control">{{ old('address', $student->address ?? '') }}</textarea>
              </div>
              
              <div class="form-group">
                <label>Belong to Other City</label>
                <select name="belongToOtherCity" class="form-select">
                  <option value="">Select</option>
                  <option value="Yes" {{ old('belongToOtherCity', $student->belongToOtherCity ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ old('belongToOtherCity', $student->belongToOtherCity ?? '') == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
              
              <div class="form-group">
                <label>Economic Weaker Section</label>
                <select name="economicWeakerSection" class="form-select">
                  <option value="">Select</option>
                  <option value="Yes" {{ old('economicWeakerSection', $student->economicWeakerSection ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ old('economicWeakerSection', $student->economicWeakerSection ?? '') == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
              
              <div class="form-group">
                <label>Army/Police/Martyr Background</label>
                <select name="armyPoliceBackground" class="form-select">
                  <option value="">Select</option>
                  <option value="Yes" {{ old('armyPoliceBackground', $student->armyPoliceBackground ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ old('armyPoliceBackground', $student->armyPoliceBackground ?? '') == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
              
              <div class="form-group">
                <label>Specially Abled</label>
                <select name="speciallyAbled" class="form-select">
                  <option value="">Select</option>
                  <option value="Yes" {{ old('speciallyAbled', $student->speciallyAbled ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ old('speciallyAbled', $student->speciallyAbled ?? '') == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Course Details Section -->
          <div class="form-section">
            <h4>Course Details</h4>
            <div class="form-row-2col">
              <div class="form-group">
                <label>Course Type</label>
                <input type="text" name="courseType" class="form-control" value="{{ old('courseType', $student->courseType ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Course Name</label>
                <input type="text" name="courseName" class="form-control" value="{{ old('courseName', $student->courseName ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Delivery Mode</label>
                <input type="text" name="deliveryMode" class="form-control" value="{{ old('deliveryMode', $student->deliveryMode ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Medium</label>
                <input type="text" name="medium" class="form-control" value="{{ old('medium', $student->medium ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Board</label>
                <input type="text" name="board" class="form-control" value="{{ old('board', $student->board ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Course Content</label>
                <input type="text" name="courseContent" class="form-control" value="{{ old('courseContent', $student->courseContent ?? '') }}">
              </div>
            </div>
          </div>

          <!-- Academic Details Section -->
          <div class="form-section">
            <h4>Academic Details</h4>
            <div class="form-row-2col">
              <div class="form-group">
                <label>Previous Class</label>
                <input type="text" name="previousClass" class="form-control" value="{{ old('previousClass', $student->previousClass ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Previous Medium</label>
                <input type="text" name="previousMedium" class="form-control" value="{{ old('previousMedium', $student->previousMedium ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>School Name</label>
                <input type="text" name="schoolName" class="form-control" value="{{ old('schoolName', $student->schoolName ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Previous Board</label>
                <input type="text" name="previousBoard" class="form-control" value="{{ old('previousBoard', $student->previousBoard ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Passing Year</label>
                <input type="text" name="passingYear" class="form-control" value="{{ old('passingYear', $student->passingYear ?? '') }}">
              </div>
              
              <div class="form-group">
                <label>Percentage</label>
                <input type="number" name="percentage" class="form-control" value="{{ old('percentage', $student->percentage ?? '') }}" min="0" max="100" step="0.01">
              </div>
            </div>
          </div>

          <!-- Scholarship Eligibility Section -->
          <div class="form-section">
            <h4>Scholarship Eligibility</h4>
            <div class="form-row-2col">
              <div class="form-group">
                <label>Is Repeater</label>
                <select name="isRepeater" class="form-select">
                  <option value="">Select</option>
                  <option value="Yes" {{ old('isRepeater', $student->isRepeater ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ old('isRepeater', $student->isRepeater ?? '') == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
              
              <div class="form-group">
                <label>Scholarship Test Appeared</label>
                <select name="scholarshipTest" class="form-select">
                  <option value="">Select</option>
                  <option value="Yes" {{ old('scholarshipTest', $student->scholarshipTest ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ old('scholarshipTest', $student->scholarshipTest ?? '') == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
              
              <div class="form-group">
                <label>Last Board Percentage</label>
                <input type="number" name="lastBoardPercentage" class="form-control" value="{{ old('lastBoardPercentage', $student->lastBoardPercentage ?? '') }}" min="0" max="100" step="0.01">
              </div>
              
              <div class="form-group">
                <label>Competition Exam Appeared</label>
                <select name="competitionExam" class="form-select">
                  <option value="">Select</option>
                  <option value="Yes" {{ old('competitionExam', $student->competitionExam ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ old('competitionExam', $student->competitionExam ?? '') == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Batch Allocation Section -->
          <div class="form-section">
            <h4>Batch Allocation</h4>
            <div class="form-row-2col">
              <div class="form-group">
                <label>Batch Name</label>
                <input type="text" name="batchName" class="form-control" value="{{ old('batchName', $student->batchName ?? '') }}">
              </div>
            </div>
          </div>

          <!-- Sticky Footer with Save Button -->
          <div class="sticky-footer">
            <button type="submit" class="btn-save" id="saveBtn">
              <i class="fa-solid fa-save"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('js/emp.js')}}"></script>
  
  <script>
    // Form submission handling
    document.getElementById('editStudentForm').addEventListener('submit', function(e) {
      const saveBtn = document.getElementById('saveBtn');
      saveBtn.disabled = true;
      saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    });

    // Re-enable button if there are errors
    @if($errors->any())
      window.addEventListener('DOMContentLoaded', function() {
        const saveBtn = document.getElementById('saveBtn');
        if (saveBtn) {
          saveBtn.disabled = false;
          saveBtn.innerHTML = '<i class="fa-solid fa-save"></i> Save Changes';
        }
      });
    @endif

    // Auto-hide success message after 5 seconds
    @if(session('success'))
      setTimeout(function() {
        const alert = document.querySelector('.alert-success');
        if (alert) {
          alert.style.transition = 'opacity 0.5s';
          alert.style.opacity = '0';
          setTimeout(() => alert.remove(), 500);
        }
      }, 5000);
    @endif
  </script>
</body>
</html>