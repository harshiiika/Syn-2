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
      background: #ff6513ff;
      color: white;
      padding: 12px 40px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
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
                <li>><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i> Employee</a></li>
                <li>><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group" id="side-icon"></i> Batches Assignment</a></li>
              </ul>
            </div>
          </div>
        </div>

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

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour"
              id="accordion-button">
              <i class="fa-solid fa-user-group" id="side-icon"></i>Student Management
            </button>
          </h2>
          <div id="flush-collapseFour" class="accordion-collapse collapse show" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu" id="dropdown-body">
                <li>><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Inquiry Management</a></li>
                <li><a class="item" href="{{ route('student.student.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a></li>
                <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Fees Students</a></li>
                <li><a class="item active" href="{{ route('smstudents.index') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
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
                <li><a class="item" href="#"><i class="fa-solid fa-credit-card" id="side-icon"></i> Fees Collection</a></li>
              </ul>
            </div>
          </div>
        </div>

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
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Student</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Employee</a></li>
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
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Units</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Dispatch Material</a></li>
              </ul>
            </div>
          </div>
        </div>

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
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Test Master</a></li>
              </ul>
            </div>
          </div>
        </div>

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

    <!-- Main Content Area -->
       <div class="right" id="right">
      <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header"> 
          <a href="{{ route('inquiries.index') }}" class="back-btn" style="border: 1px solid #ff6b35; padding: 8px 16px; border-radius: 6px;">
            <i class="fa-solid fa-arrow-left"></i> Back
          </a>
          <h3 class="page-title" style="color: orangered;">Update Student</h3>
         
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

<form id="editStudentForm" method="POST" action="{{ route('inquiries.update', $inquiry->_id) }}">
  @csrf
  @method('PUT')

  <!-- Basic Details Section -->
  <div class="form-section">
    <h4>Basic Details</h4>
    <div class="form-row">
      <div class="form-group">
        <label>Student Name <span class="required">*</span></label>
        <input type="text" name="name" class="form-control" 
               value="{{ old('name', $inquiry->student_name ?? '') }}" required>
      </div>
      
      <div class="form-group">
        <label>Father Name <span class="required">*</span></label>
        <input type="text" name="father" class="form-control" 
               value="{{ old('father', $inquiry->father_name ?? '') }}" required>
      </div>
      
      <div class="form-group">
        <label>Mother Name</label>
        <input type="text" name="mother" class="form-control" 
               value="{{ old('mother', $inquiry->mother ?? '') }}">
      </div>
      
      <div class="form-group">
        <label>Date of Birth</label>
        <input type="date" name="dob" class="form-control" 
               value="{{ old('dob', $inquiry->dob ?? '') }}">
      </div>
      
      <div class="form-group">
        <label>Father Contact No <span class="required">*</span></label>
        <input type="tel" name="mobileNumber" class="form-control" 
               value="{{ old('mobileNumber', $inquiry->father_contact ?? '') }}" 
               maxlength="15" required>
      </div>
      
      <div class="form-group">
        <label>Father WhatsApp Number</label>
        <input type="tel" name="fatherWhatsapp" class="form-control" 
               value="{{ old('fatherWhatsapp', $inquiry->father_whatsapp ?? '') }}" 
               maxlength="15">
      </div>
      
      <div class="form-group">
        <label>Mother Contact No</label>
        <input type="tel" name="motherContact" class="form-control" 
               value="{{ old('motherContact', $inquiry->motherContact ?? '') }}" 
               maxlength="15">
      </div>
      
      <div class="form-group">
        <label>Student Contact No</label>
        <input type="tel" name="studentContact" class="form-control" 
               value="{{ old('studentContact', $inquiry->student_contact ?? '') }}" 
               maxlength="15">
      </div>
      
      <div class="form-group">
        <label>Category <span class="required">*</span></label>
        <div class="radio-group">
          @php
            $categoryValue = old('category', $inquiry->category ?? 'GENERAL');
          @endphp
          <label>
            <input type="radio" name="category" value="GENERAL" 
                   {{ $categoryValue == 'GENERAL' ? 'checked' : '' }} required>
            GENERAL
          </label>
          <label>
            <input type="radio" name="category" value="OBC" 
                   {{ $categoryValue == 'OBC' ? 'checked' : '' }}>
            OBC
          </label>
          <label>
            <input type="radio" name="category" value="SC" 
                   {{ $categoryValue == 'SC' ? 'checked' : '' }}>
            SC
          </label>
          <label>
            <input type="radio" name="category" value="ST" 
                   {{ $categoryValue == 'ST' ? 'checked' : '' }}>
            ST
          </label>
        </div>
      </div>
      
      <div class="form-group">
        <label>Gender <span class="required">*</span></label>
        <div class="radio-group">
          @php
            $genderValue = old('gender', $inquiry->gender ?? 'Male');
          @endphp
          <label>
            <input type="radio" name="gender" value="Male" 
                   {{ $genderValue == 'Male' ? 'checked' : '' }} required>
            Male
          </label>
          <label>
            <input type="radio" name="gender" value="Female" 
                   {{ $genderValue == 'Female' ? 'checked' : '' }}>
            Female
          </label>
          <label>
            <input type="radio" name="gender" value="Others" 
                   {{ $genderValue == 'Others' ? 'checked' : '' }}>
            Others
          </label>
        </div>
      </div>
      
      <div class="form-group">
        <label>Father Occupation</label>
        <input type="text" name="fatherOccupation" class="form-control" 
               value="{{ old('fatherOccupation', $inquiry->fatherOccupation ?? '') }}">
      </div>
      
      <div class="form-group">
        <label>Father's Grade</label>
        <input type="text" name="fatherGrade" class="form-control" 
               value="{{ old('fatherGrade', $inquiry->fatherGrade ?? '') }}">
      </div>
      
      <div class="form-group">
        <label>Mother Occupation</label>
        <input type="text" name="motherOccupation" class="form-control" 
               value="{{ old('motherOccupation', $inquiry->motherOccupation ?? '') }}">
      </div>
    </div>
  </div>

  <!-- Address Details Section -->
  <div class="form-section">
    <h4>Address Details</h4>
    <div class="form-row">
      <div class="form-group">
        <label>State</label>
        <select name="state" class="form-select">
          <option value="">Select State</option>
          @php
            $stateValue = old('state', $inquiry->state ?? '');
          @endphp
          <option value="Rajasthan" {{ $stateValue == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
          <option value="Delhi" {{ $stateValue == 'Delhi' ? 'selected' : '' }}>Delhi</option>
          <option value="Maharashtra" {{ $stateValue == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>City</label>
        <input type="text" name="city" class="form-control" 
               value="{{ old('city', $inquiry->city ?? '') }}">
      </div>
      
      <div class="form-group">
        <label>Pin Code</label>
        <input type="text" name="pinCode" class="form-control" 
               value="{{ old('pinCode', $inquiry->pinCode ?? '') }}" 
               maxlength="6">
      </div>
      
      <div class="form-group full-width">
        <label>Address</label>
        <textarea name="address" class="form-control" rows="3">{{ old('address', $inquiry->address ?? '') }}</textarea>
      </div>
      
      <div class="form-group">
        <label>Do you belong to another city?</label>
        <div class="radio-group">
          @php
            $belongValue = old('belongToOtherCity', $inquiry->belongToOtherCity ?? 'No');
          @endphp
          <label>
            <input type="radio" name="belongToOtherCity" value="Yes" 
                   {{ $belongValue == 'Yes' ? 'checked' : '' }}>
            Yes
          </label>
          <label>
            <input type="radio" name="belongToOtherCity" value="No" 
                   {{ $belongValue == 'No' ? 'checked' : '' }}>
            No
          </label>
        </div>
      </div>
      
      <div class="form-group">
        <label>Economic Weaker Section?</label>
        <div class="radio-group">
          @php
            $ewsValue = old('economicWeakerSection', $inquiry->economicWeakerSection ?? 'No');
          @endphp
          <label>
            <input type="radio" name="economicWeakerSection" value="Yes" 
                   {{ $ewsValue == 'Yes' ? 'checked' : '' }}>
            Yes
          </label>
          <label>
            <input type="radio" name="economicWeakerSection" value="No" 
                   {{ $ewsValue == 'No' ? 'checked' : '' }}>
            No
          </label>
        </div>
      </div>
      
      <div class="form-group">
        <label>Army/Police/Martyr Background?</label>
        <div class="radio-group">
          @php
            $armyValue = old('armyPoliceBackground', $inquiry->armyPoliceBackground ?? 'No');
          @endphp
          <label>
            <input type="radio" name="armyPoliceBackground" value="Yes" 
                   {{ $armyValue == 'Yes' ? 'checked' : '' }}>
            Yes
          </label>
          <label>
            <input type="radio" name="armyPoliceBackground" value="No" 
                   {{ $armyValue == 'No' ? 'checked' : '' }}>
            No
          </label>
        </div>
      </div>
      
      <div class="form-group">
        <label>Specially Abled?</label>
        <div class="radio-group">
          @php
            $speciallyValue = old('speciallyAbled', $inquiry->speciallyAbled ?? 'No');
          @endphp
          <label>
            <input type="radio" name="speciallyAbled" value="Yes" 
                   {{ $speciallyValue == 'Yes' ? 'checked' : '' }}>
            Yes
          </label>
          <label>
            <input type="radio" name="speciallyAbled" value="No" 
                   {{ $speciallyValue == 'No' ? 'checked' : '' }}>
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
        <select class="form-select" name="courseType" id="course_type" required>
          <option value="">Select Course Type</option>
          @php
            $courseTypeValue = old('courseType', $inquiry->courseType ?? '');
          @endphp
          <option value="Pre-Medical" {{ $courseTypeValue == 'Pre-Medical' ? 'selected' : '' }}>Pre-Medical</option>
          <option value="Pre-Engineering" {{ $courseTypeValue == 'Pre-Engineering' ? 'selected' : '' }}>Pre-Engineering</option>
          <option value="Pre-Foundation" {{ $courseTypeValue == 'Pre-Foundation' ? 'selected' : '' }}>Pre-Foundation</option>
        </select>
      </div>

      <div class="form-group">
        <label>Course Name <span class="required">*</span></label>
        <select class="form-select" name="courseName" id="course" required>
          <option value="">Select Course</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>Delivery Mode <span class="required">*</span></label>
        <select name="deliveryMode" class="form-select" required>
          <option value="">Select Mode</option>
          @php
            $deliveryValue = old('deliveryMode', $inquiry->delivery_mode ?? '');
          @endphp
          <option value="Offline" {{ $deliveryValue == 'Offline' ? 'selected' : '' }}>Offline</option>
          <option value="Online" {{ $deliveryValue == 'Online' ? 'selected' : '' }}>Online</option>
          <option value="Hybrid" {{ $deliveryValue == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>Medium <span class="required">*</span></label>
        <select name="medium" class="form-select" required>
          <option value="">Select Medium</option>
          @php
            $mediumValue = old('medium', $inquiry->medium ?? '');
          @endphp
          <option value="English" {{ $mediumValue == 'English' ? 'selected' : '' }}>English</option>
          <option value="Hindi" {{ $mediumValue == 'Hindi' ? 'selected' : '' }}>Hindi</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>Board <span class="required">*</span></label>
        <select name="board" class="form-select" required>
          <option value="">Select Board</option>
          @php
            $boardValue = old('board', $inquiry->board ?? '');
          @endphp
          <option value="CBSE" {{ $boardValue == 'CBSE' ? 'selected' : '' }}>CBSE</option>
          <option value="RBSE" {{ $boardValue == 'RBSE' ? 'selected' : '' }}>RBSE</option>
          <option value="ICSE" {{ $boardValue == 'ICSE' ? 'selected' : '' }}>ICSE</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>Course Content <span class="required">*</span></label>
        <select name="courseContent" class="form-select" required>
          <option value="">Select Content</option>
          @php
            $contentValue = old('courseContent', $inquiry->course_content ?? '');
          @endphp
          <option value="Class Room Course" {{ $contentValue == 'Class Room Course' ? 'selected' : '' }}>Class Room Course</option>
          <option value="Test Series Only" {{ $contentValue == 'Test Series Only' ? 'selected' : '' }}>Test Series Only</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Scholarship Eligibility Section -->
  <div class="form-section">
    <h4>Scholarship Eligibility</h4>
    <div class="form-row">
      
      <div class="form-group">
        <label>Scholarship Test Appeared</label>
        <div class="radio-group">
          @php
            $scholarshipValue = old('scholarshipTest', $inquiry->scholarshipTest ?? 'No');
          @endphp
          <label>
            <input type="radio" name="scholarshipTest" value="Yes" 
                   {{ $scholarshipValue == 'Yes' ? 'checked' : '' }}>
            Yes
          </label>
          <label>
            <input type="radio" name="scholarshipTest" value="No" 
                   {{ $scholarshipValue == 'No' ? 'checked' : '' }}>
            No
          </label>
        </div>
      </div>
      
      <div class="form-group">
        <label>Competition Exam Appeared</label>
        <div class="radio-group">
          @php
            $competitionValue = old('competitionExam', $inquiry->competitionExam ?? 'No');
          @endphp
          <label>
            <input type="radio" name="competitionExam" value="Yes" 
                   {{ $competitionValue == 'Yes' ? 'checked' : '' }}>
            Yes
          </label>
          <label>
            <input type="radio" name="competitionExam" value="No" 
                   {{ $competitionValue == 'No' ? 'checked' : '' }}>
            No
          </label>
        </div>
      </div>
    </div>
  </div>

  <!-- Save Button -->
  <div class="sticky-footer">
    <button type="submit" class="btn-save" id="saveBtn">
      Save
    </button>
  </div>
</form>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('js/emp.js')}}"></script>
  
<script>
document.addEventListener("DOMContentLoaded", function () {
    const courseTypeSelect = document.getElementById("course_type");
    const courseSelect = document.getElementById("course");

    const courseOptions = {
        "Pre-Medical": ["Anthesis 11th NEET", "Momentum 12th NEET", "Dynamic Target NEET"],
        "Pre-Engineering": ["Impulse 11th IIT", "Intensity 12th IIT", "Thurst Target IIT"],
        "Pre-Foundation": ["Seedling 10th", "Plumule 9th", "Radicle 8th"]
    };

    // Get values from server - FIXED to use correct DB fields
    const selectedType = "{{ old('courseType', $inquiry->courseType ?? '') }}";
    const selectedCourse = "{{ old('courseName', $inquiry->course_name ?? '') }}";

    console.log('🔍 Page load - Course Type:', selectedType, 'Course Name:', selectedCourse);

    // Pre-fill on page load
    if (selectedType && courseOptions[selectedType]) {
        updateCourses(selectedType);
        
        setTimeout(() => {
            if (selectedCourse) {
                courseSelect.value = selectedCourse;
                console.log('✅ Pre-filled course name:', selectedCourse);
            }
        }, 100);
    }

    courseTypeSelect.addEventListener("change", function () {
        updateCourses(this.value);
    });

    function updateCourses(type) {
        courseSelect.innerHTML = '<option value="">Select Course</option>';
        
        if (!type || !courseOptions[type]) {
            return;
        }

        courseOptions[type].forEach(course => {
            const option = document.createElement("option");
            option.value = course;
            option.textContent = course;
            
            if (course === selectedCourse) {
                option.selected = true;
            }
            
            courseSelect.appendChild(option);
        });
        
        console.log(' Updated courses for type:', type);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const courseTypeSelect = document.getElementById("course_type");
    const courseSelect = document.getElementById("course");
    const form = document.getElementById("editStudentForm");

    // Remove HTML5 required validation for course fields
    courseTypeSelect.removeAttribute('required');
    courseSelect.removeAttribute('required');
    document.querySelector('[name="deliveryMode"]').removeAttribute('required');
    document.querySelector('[name="medium"]').removeAttribute('required');
    document.querySelector('[name="board"]').removeAttribute('required');
    document.querySelector('[name="courseContent"]').removeAttribute('required');

    const courseOptions = {
        "Pre-Medical": ["Anthesis 11th NEET", "Momentum 12th NEET", "Dynamic Target NEET"],
        "Pre-Engineering": ["Impulse 11th IIT", "Intensity 12th IIT", "Thurst Target IIT"],
        "Pre-Foundation": ["Seedling 10th", "Plumule 9th", "Radicle 8th"]
    };

    // Get values from server
    const selectedType = "{{ old('courseType', $inquiry->courseType ?? '') }}";
    const selectedCourse = "{{ old('courseName', $inquiry->course_name ?? '') }}";

    console.log('🔍 Page load - Course Type:', selectedType, 'Course Name:', selectedCourse);

    // Pre-fill on page load
    if (selectedType && courseOptions[selectedType]) {
        updateCourses(selectedType);
        
        setTimeout(() => {
            if (selectedCourse) {
                courseSelect.value = selectedCourse;
                console.log('✅ Pre-filled course name:', selectedCourse);
            }
        }, 100);
    }

    courseTypeSelect.addEventListener("change", function () {
        updateCourses(this.value);
    });

    function updateCourses(type) {
        courseSelect.innerHTML = '<option value="">Select Course</option>';
        
        if (!type || !courseOptions[type]) {
            return;
        }

        courseOptions[type].forEach(course => {
            const option = document.createElement("option");
            option.value = course;
            option.textContent = course;
            
            if (course === selectedCourse) {
                option.selected = true;
            }
            
            courseSelect.appendChild(option);
        });
        
        console.log('📋 Updated courses for type:', type);
    }

    // Allow form submission even if course fields are empty
    form.addEventListener('submit', function(e) {
        console.log('✅ Form submitting - allowing partial data');
        return true;
    });
});
</script>

</body>
</html>