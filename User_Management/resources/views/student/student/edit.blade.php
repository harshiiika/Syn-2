<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Onboarding</title>
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
      flex-wrap: wrap;
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
    
    .file-upload-wrapper {
      position: relative;
      margin-top: 8px;
    }
    
    .file-upload-label {
      display: inline-block;
      padding: 10px 20px;
      background: #f8f9fa;
      border: 2px dashed #ddd;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .file-upload-label:hover {
      border-color: #ff6b35;
      background: #fff5f2;
    }
    
    .file-upload-label i {
      margin-right: 8px;
      color: #ff6b35;
    }
    
    input[type="file"] {
      display: none;
    }
    
    .file-preview {
      margin-top: 10px;
      padding: 10px;
      background: #f8f9fa;
      border-radius: 6px;
      display: none;
    }
    
    .file-preview.active {
      display: block;
    }
    
    .file-preview img {
      max-width: 150px;
      max-height: 150px;
      border-radius: 4px;
      margin-top: 10px;
    }
    
    .file-name {
      font-size: 14px;
      color: #666;
      margin-top: 5px;
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
    
    .success-message.show {
      display: block;
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
     <!-- Left side bar accordian -->
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
    <!-- Main Content Area -->
    <div class="right" id="right">
      <div class="container-fluid py-4">
        <a href="{{ route('student.student.pending') }}" class="back-btn">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 style="color: #ff6b35;">Student Onboarding</h4>
        </div>

    <form id="editStudentForm" method="POST" action="{{ route('student.student.update', $student->_id) }}" enctype="multipart/form-data">
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
            <label>Mother Name <span class="required">*</span></label>
            <input type="text" name="mother" class="form-control" value="{{ old('mother', $student->mother) }}" required>
          </div>
          
          <div class="form-group">
            <label>Date of Birth <span class="required">*</span></label>
            <input type="date" name="dob" class="form-control" 
                   value="{{ old('dob', $student->dob ? $student->dob->format('Y-m-d') : '') }}" required>
          </div>
          
          <div class="form-group">
            <label>Father Contact No <span class="required">*</span></label>
            <input type="tel" name="mobileNumber" class="form-control" 
                   value="{{ old('mobileNumber', $student->mobileNumber) }}" 
                   pattern="[0-9]{10}" maxlength="10" required>
          </div>
          
          <div class="form-group">
            <label>Father WhatsApp Number <span class="required">*</span></label>
            <input type="tel" name="fatherWhatsapp" class="form-control" 
                   value="{{ old('fatherWhatsapp', $student->fatherWhatsapp) }}" 
                   pattern="[0-9]{10}" maxlength="10" required>
          </div>
          
          <div class="form-group">
            <label>Mother Contact No <span class="required">*</span></label>
            <input type="tel" name="motherContact" class="form-control" 
                   value="{{ old('motherContact', $student->motherContact) }}" 
                   pattern="[0-9]{10}" maxlength="10" required>
          </div>
          
          <div class="form-group">
            <label>Student Contact No <span class="required">*</span></label>
            <input type="tel" name="studentContact" class="form-control" 
                   value="{{ old('studentContact', $student->studentContact) }}" 
                   pattern="[0-9]{10}" maxlength="10" required>
          </div>
          
          <div class="form-group">
            <label>Category <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="category" value="GENERAL" 
                       {{ old('category', $student->category ?? 'GENERAL') == 'GENERAL' ? 'checked' : '' }} required>
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
                       {{ old('gender', $student->gender ?? 'Male') == 'Male' ? 'checked' : '' }} required>
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
            <label>Father Occupation <span class="required">*</span></label>
            <select name="fatherOccupation" class="form-select" required>
              <option value="">Select Occupation</option>
              <option value="Pvt. Service" {{ old('fatherOccupation', $student->fatherOccupation) == 'Pvt. Service' ? 'selected' : '' }}>Pvt. Service</option>
              <option value="Agriculture" {{ old('fatherOccupation', $student->fatherOccupation) == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
              <option value="Business" {{ old('fatherOccupation', $student->fatherOccupation) == 'Business' ? 'selected' : '' }}>Business</option>
              <option value="Govt. Service" {{ old('fatherOccupation', $student->fatherOccupation) == 'Govt. Service' ? 'selected' : '' }}>Govt. Service</option>
              <option value="Pvt. Professional" {{ old('fatherOccupation', $student->fatherOccupation) == 'Pvt. Professional' ? 'selected' : '' }}>Pvt. Professional</option>
              <option value="Other" {{ old('fatherOccupation', $student->fatherOccupation) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Mother Occupation <span class="required">*</span></label>
            <select name="motherOccupation" class="form-select" required>
              <option value="">Select Occupation</option>
              <option value="Pvt. Service" {{ old('motherOccupation', $student->motherOccupation) == 'Pvt. Service' ? 'selected' : '' }}>Pvt. Service</option>
              <option value="Business" {{ old('motherOccupation', $student->motherOccupation) == 'Business' ? 'selected' : '' }}>Business</option>
              <option value="Govt. Service" {{ old('motherOccupation', $student->motherOccupation) == 'Govt. Service' ? 'selected' : '' }}>Govt. Service</option>
              <option value="House Wife" {{ old('motherOccupation', $student->motherOccupation) == 'House Wife' ? 'selected' : '' }}>House Wife</option>
              <option value="Other" {{ old('motherOccupation', $student->motherOccupation) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
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
              <option value="Madhya Pradesh" {{ old('state', $student->state) == 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
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
                       {{ old('belongToOtherCity', $student->belongToOtherCity ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="belongToOtherCity" value="No" 
                       {{ old('belongToOtherCity', $student->belongToOtherCity ?? 'No') == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>

          <div class="form-group">
            <label>Do You Belong to Economic Weaker Section? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="economicWeakerSection" value="Yes" 
                       {{ old('economicWeakerSection', $student->economicWeakerSection ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="economicWeakerSection" value="No" 
                       {{ old('economicWeakerSection', $student->economicWeakerSection ?? 'No') == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>

          <div class="form-group">
            <label>Do You Belong to Any Army/Police/Martyr Background? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="armyPoliceBackground" value="Yes" 
                       {{ old('armyPoliceBackground', $student->armyPoliceBackground ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="armyPoliceBackground" value="No" 
                       {{ old('armyPoliceBackground', $student->armyPoliceBackground ?? 'No') == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Are You a Specially Abled? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="speciallyAbled" value="Yes" 
                       {{ old('speciallyAbled', $student->speciallyAbled ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="speciallyAbled" value="No" 
                       {{ old('speciallyAbled', $student->speciallyAbled ?? 'No') == 'No' ? 'checked' : '' }}>
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
            <select class="form-select" name="course_type" id="course_type" required>
              <option value="">Select Course Type</option>
              <option value="Pre-Medical" {{ old('course_type', $student->course_type ?? $student->courseType) == 'Pre-Medical' ? 'selected' : '' }}>Pre-Medical</option>
              <option value="Pre-Engineering" {{ old('course_type', $student->course_type ?? $student->courseType) == 'Pre-Engineering' ? 'selected' : '' }}>Pre-Engineering</option>
              <option value="Pre-Foundation" {{ old('course_type', $student->course_type ?? $student->courseType) == 'Pre-Foundation' ? 'selected' : '' }}>Pre-Foundation</option>
            </select>
          </div>

          <div class="form-group">
            <label>Course Name <span class="required">*</span></label>
            <select class="form-select" name="courseName" id="courseName" required>
              <option value="">Select Course</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Delivery Mode <span class="required">*</span></label>
            <select name="deliveryMode" class="form-select" required>
              <option value="">Select Mode</option>
              <option value="Offline" {{ old('deliveryMode', $student->deliveryMode) == 'Offline' ? 'selected' : '' }}>Offline</option>
              <option value="Online" {{ old('deliveryMode', $student->deliveryMode) == 'Online' ? 'selected' : '' }}>Online</option>
              <option value="Distance Learning" {{ old('deliveryMode', $student->deliveryMode) == 'Distance Learning' ? 'selected' : '' }}>Distance Learning</option>
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
            </select>
          </div>
          
          <div class="form-group">
            <label>Course Content <span class="required">*</span></label>
            <select name="courseContent" class="form-select" required>
              <option value="">Select Content</option>
              <option value="Class room course" {{ old('courseContent', $student->courseContent) == 'Class room course' ? 'selected' : '' }}>Class room course</option>
              <option value="Study Material only" {{ old('courseContent', $student->courseContent) == 'Study Material only' ? 'selected' : '' }}>Study Material only</option>
              <option value="Test series & Study Material" {{ old('courseContent', $student->courseContent) == 'Test series & Study Material' ? 'selected' : '' }}>Test series & Study Material</option>
              <option value="Test series only" {{ old('courseContent', $student->courseContent) == 'Test series only' ? 'selected' : '' }}>Test series only</option>
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
              <option value="10th (X)" {{ old('previousClass', $student->previousClass) == '10th (X)' ? 'selected' : '' }}>10th (X)</option>
              <option value="11th (XI)" {{ old('previousClass', $student->previousClass) == '11th (XI)' ? 'selected' : '' }}>11th (XI)</option>
              <option value="12th (XII)" {{ old('previousClass', $student->previousClass) == '12th (XII)' ? 'selected' : '' }}>12th (XII)</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Medium <span class="required">*</span></label>
            <select name="previousMedium" class="form-select" required>
              <option value="">Select Medium</option>
              <option value="English" {{ old('previousMedium', $student->previousMedium) == 'English' ? 'selected' : '' }}>English</option>
              <option value="Hindi" {{ old('previousMedium', $student->previousMedium) == 'Hindi' ? 'selected' : '' }}>Hindi</option>
            </select>
          </div>
          
          <div class="form-group full-width">
            <label>Name Of School <span class="required">*</span></label>
            <input type="text" name="schoolName" class="form-control" 
                   value="{{ old('schoolName', $student->schoolName) }}" required>
          </div>
          
          <div class="form-group">
            <label>Board <span class="required">*</span></label>
            <select name="previousBoard" class="form-select" required>
              <option value="">Select Board</option>
              <option value="CBSE" {{ old('previousBoard', $student->previousBoard) == 'CBSE' ? 'selected' : '' }}>CBSE</option>
              <option value="RBSE" {{ old('previousBoard', $student->previousBoard) == 'RBSE' ? 'selected' : '' }}>RBSE</option>
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
            <label>Are you a Repeater From the Foundation Batch? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="isRepeater" value="Yes" 
                       {{ old('isRepeater', $student->isRepeater ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="isRepeater" value="No" 
                       {{ old('isRepeater', $student->isRepeater ?? 'No') == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Have You Appeared For the Synthesis Scholarship test? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="scholarshipTest" value="Yes" 
                       {{ old('scholarshipTest', $student->scholarshipTest ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="scholarshipTest" value="No" 
                       {{ old('scholarshipTest', $student->scholarshipTest ?? 'No') == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Percentage Of Marks In last Board Exam <span class="required">*</span></label>
            <input type="number" name="lastBoardPercentage" class="form-control" 
                   value="{{ old('lastBoardPercentage', $student->lastBoardPercentage) }}" 
                   min="0" max="100" step="0.01" required>
          </div>
          
          <div class="form-group">
            <label>Have You Appeared For any of the competition exam? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="competitionExam" value="Yes" 
                       {{ old('competitionExam', $student->competitionExam ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="competitionExam" value="No" 
                       {{ old('competitionExam', $student->competitionExam ?? 'No') == 'No' ? 'checked' : '' }}>
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
            <select name="batchName" class="form-select" required>
              <option value="">Select Batch</option>
              <option value="15D5" {{ old('batchName', $student->batchName) == '15D5' ? 'selected' : '' }}>15D5</option>
              <option value="20T1" {{ old('batchName', $student->batchName) == '20T1' ? 'selected' : '' }}>20T1</option>
              <option value="19L1" {{ old('batchName', $student->batchName) == '19L1' ? 'selected' : '' }}>19L1</option>
              <option value="14D4" {{ old('batchName', $student->batchName) == '14D4' ? 'selected' : '' }}>14D4</option>
              <option value="13D3" {{ old('batchName', $student->batchName) == '13D3' ? 'selected' : '' }}>13D3</option>
              <option value="12D2" {{ old('batchName', $student->batchName) == '12D2' ? 'selected' : '' }}>12D2</option>
              <option value="11D1" {{ old('batchName', $student->batchName) == '11D1' ? 'selected' : '' }}>11D1</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Upload Documents Section -->
      <div class="form-section">
        <h4>Upload Documents</h4>
        <div class="form-row">
          <!-- Passport Photo -->
          <div class="form-group">
            <label>Passport Size Photo <span class="required">*</span></label>
            <div class="file-upload-wrapper">
              <label for="passport_photo" class="file-upload-label">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                Choose File
              </label>
              <input type="file" id="passport_photo" name="passport_photo" accept="image/*" onchange="previewFile(this, 'passport_preview')">
              <div id="passport_preview" class="file-preview">
                @if(isset($student->passport_photo))
                  <img src="{{ $student->passport_photo }}" alt="Passport Photo">
                  <p class="file-name">Current photo uploaded</p>
                @endif
              </div>
            </div>
          </div>

          <!-- Marksheet -->
          <div class="form-group">
            <label>Marksheet of Last qualifying Exam <span class="required">*</span></label>
            <div class="file-upload-wrapper">
              <label for="marksheet" class="file-upload-label">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                Choose File
              </label>
              <input type="file" id="marksheet" name="marksheet" accept="image/*,.pdf" onchange="previewFile(this, 'marksheet_preview')">
              <div id="marksheet_preview" class="file-preview">
                @if(isset($student->marksheet))
                  <p class="file-name">✓ Marksheet uploaded</p>
                @endif
              </div>
            </div>
          </div>

          <!-- Ex-Synthesian ID -->
          <div class="form-group">
            <label>If you are an Ex-Synthesian, upload Identity card issued by Synthesis</label>
            <div class="file-upload-wrapper">
              <label for="caste_certificate" class="file-upload-label">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                Choose File
              </label>
              <input type="file" id="caste_certificate" name="caste_certificate" accept="image/*,.pdf" onchange="previewFile(this, 'caste_preview')">
              <div id="caste_preview" class="file-preview">
                @if(isset($student->caste_certificate))
                  <p class="file-name">✓ ID card uploaded</p>
                @endif
              </div>
            </div>
          </div>

          <!-- Scholarship Proof -->
          <div class="form-group">
            <label>Upload Proof of Scholarship to avail Concession</label>
            <div class="file-upload-wrapper">
              <label for="scholarship_proof" class="file-upload-label">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                Choose File
              </label>
              <input type="file" id="scholarship_proof" name="scholarship_proof" accept="image/*,.pdf" onchange="previewFile(this, 'scholarship_preview')">
              <div id="scholarship_preview" class="file-preview">
                @if(isset($student->scholarship_proof))
                  <p class="file-name">✓ Scholarship proof uploaded</p>
                @endif
              </div>
            </div>
          </div>

          <!-- Secondary Marksheet -->
          <div class="form-group">
            <label>Secondary Board Marksheet</label>
            <div class="file-upload-wrapper">
              <label for="secondary_marksheet" class="file-upload-label">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                Choose File
              </label>
              <input type="file" id="secondary_marksheet" name="secondary_marksheet" accept="image/*,.pdf" onchange="previewFile(this, 'secondary_preview')">
              <div id="secondary_preview" class="file-preview">
                @if(isset($student->secondary_marksheet))
                  <p class="file-name">✓ Secondary marksheet uploaded</p>
                @endif
              </div>
            </div>
          </div>

          <!-- Senior Secondary Marksheet -->
          <div class="form-group">
            <label>Senior Secondary Board Marksheet</label>
            <div class="file-upload-wrapper">
              <label for="senior_secondary_marksheet" class="file-upload-label">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                Choose File
              </label>
              <input type="file" id="senior_secondary_marksheet" name="senior_secondary_marksheet" accept="image/*,.pdf" onchange="previewFile(this, 'senior_preview')">
              <div id="senior_preview" class="file-preview">
                @if(isset($student->senior_secondary_marksheet))
                  <p class="file-name">✓ Senior secondary marksheet uploaded</p>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sticky Footer with Save Button -->
      <div class="sticky-footer">
        <button type="submit" class="btn-save" id="saveBtn">
          <i class="fa-solid fa-check"></i> Save & Complete Onboarding
        </button>
      </div>
    </form>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/session.js') }}"></script>
  
  <script>
    // Course Type and Name Dynamic Dropdown
    document.addEventListener("DOMContentLoaded", function () {
      const courseTypeSelect = document.getElementById("course_type");
      const courseSelect = document.getElementById("courseName");

      const courseOptions = {
        "Pre-Medical": ["Anthesis 11th NEET", "Momentum 12th NEET", "Dynamic Target NEET"],
        "Pre-Engineering": ["Impulse 11th IIT", "Intensity 12th IIT", "Thurst Target IIT"],
        "Pre-Foundation": ["Seedling 10th", "Plumule 9th", "Radicle 8th"]
      };

      // Pre-fill on page load
      const selectedType = "{{ old('course_type', $student->course_type ?? $student->courseType ?? '') }}";
      const selectedCourse = "{{ old('courseName', $student->courseName ?? '') }}";

      if (selectedType) {
        updateCourses(selectedType);
        setTimeout(() => {
          if (selectedCourse) {
            courseSelect.value = selectedCourse;
          }
        }, 100);
      }

      courseTypeSelect.addEventListener("change", function () {
        updateCourses(this.value);
      });

      function updateCourses(type) {
        courseSelect.innerHTML = '<option value="">Select Course</option>';
        if (!type || !courseOptions[type]) return;

        courseOptions[type].forEach(course => {
          const option = document.createElement("option");
          option.value = course;
          option.textContent = course;
          courseSelect.appendChild(option);
        });
      }
    });

    // File Preview Function
    function previewFile(input, previewId) {
      const preview = document.getElementById(previewId);
      const file = input.files[0];
      
      if (file) {
        preview.classList.add('active');
        
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function(e) {
            preview.innerHTML = `
              <img src="${e.target.result}" alt="Preview">
              <p class="file-name">${file.name}</p>
            `;
          };
          reader.readAsDataURL(file);
        } else {
          preview.innerHTML = `<p class="file-name">✓ ${file.name}</p>`;
        }
      }
    }

    // Form Submission Handler
    document.getElementById('editStudentForm').addEventListener('submit', function(e) {
      const btn = document.getElementById('saveBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    });
  </script>
</body>
</html>