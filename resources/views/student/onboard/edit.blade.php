<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Student Details - Onboarding</title>
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
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="toggle-btn">
        <li>
            <a class="dropdown-item" href="{{ route('profile.index') }}">
                <i class="fa-solid fa-user me-2"></i>Profile
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Log Out
                </button>
            </form>
        </li>
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
        <a href="{{ route('student.onboard.onboard') }}" class="back-btn">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>

        <!-- Success Message -->
        <div class="success-message" id="successMessage">
          <i class="fa-solid fa-check-circle"></i> Student details updated successfully!
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 style="color: #ff6b35;">
          Edit Student Details - Onboarding
          </h4>
        </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="editStudentForm" method="POST" action="{{ route('student.onboard.update', $student->id ?? $student->_id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Basic Details Section -->
      <div class="form-section">
        <h4>Basic Details</h4>
        <div class="form-row">
          <div class="form-group">
            <label>Student Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $student->name ?? $student->student_name) }}">
          </div>
          
          <div class="form-group">
            <label>Father Name </label>
            <input type="text" name="father" class="form-control" value="{{ old('father', $student->father ?? $student->father_name) }}">
          </div>
          
          <div class="form-group">
            <label>Mother Name</label>
            <input type="text" name="mother" class="form-control" value="{{ old('mother', $student->mother ?? $student->mother_name) }}">
          </div>
          
          <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" class="form-control" 
                   value="{{ old('dob', $student->dob ? date('Y-m-d', strtotime($student->dob)) : '') }}">
          </div>
          
          <div class="form-group">
            <label>Father Contact No </label>
            <input type="tel" name="mobileNumber" class="form-control" 
                   value="{{ old('mobileNumber', $student->mobileNumber ?? $student->father_contact) }}" 
                   pattern="[0-9]{10}" maxlength="10">
          </div>
          
          <div class="form-group">
            <label>Father WhatsApp Number</label>
            <input type="tel" name="fatherWhatsapp" class="form-control" 
                   value="{{ old('fatherWhatsapp', $student->fatherWhatsapp ?? $student->father_whatsapp) }}" 
                   pattern="[0-9]{10}" maxlength="10">
          </div>
          
          <div class="form-group">
            <label>Mother Contact No</label>
            <input type="tel" name="motherContact" class="form-control" 
                   value="{{ old('motherContact', $student->motherContact ?? $student->mother_contact) }}" 
                   pattern="[0-9]{10}" maxlength="10">
          </div>
          
          <div class="form-group">
            <label>Student Contact No</label>
            <input type="tel" name="studentContact" class="form-control" 
                   value="{{ old('studentContact', $student->studentContact ?? $student->phone) }}" 
                   pattern="[0-9]{10}" maxlength="10">
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
            <label>Father Occupation</label>
            <input type="text" name="fatherOccupation" class="form-control" 
                   value="{{ old('fatherOccupation', $student->fatherOccupation ?? $student->father_occupation) }}">
          </div>
          
          <div class="form-group">
            <label>Father's Grade</label>
            <input type="text" name="fatherGrade" class="form-control" 
                   value="{{ old('fatherGrade', $student->fatherGrade ?? $student->father_grade) }}">
          </div>
          
          <div class="form-group">
            <label>Mother Occupation</label>
            <input type="text" name="motherOccupation" class="form-control" 
                   value="{{ old('motherOccupation', $student->motherOccupation ?? $student->mother_occupation) }}">
          </div>
        </div>
      </div>

      <!-- Address Details Section -->
      <div class="form-section">
        <h4>Address Details</h4>
        <div class="form-row">
          <div class="form-group">
            <label>State </label>
            <select name="state" class="form-select">
              <option value="">Select State</option>
              <option value="Rajasthan" {{ old('state', $student->state) == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
              <!-- Add more states as needed -->
            </select>
          </div>
          
          <div class="form-group">
            <label>City </label>
            <input type="text" name="city" class="form-control" 
                   value="{{ old('city', $student->city) }}">
          </div>
          
          <div class="form-group">
            <label>Pin Code</label>
            <input type="text" name="pinCode" class="form-control" 
                   value="{{ old('pinCode', $student->pinCode ?? $student->pincode) }}" 
                   pattern="[0-9]{6}" maxlength="6">
          </div>
          
          <div class="form-group full-width">
            <label>Address </label>
            <textarea name="address" class="form-control" rows="3">{{ old('address', $student->address) }}</textarea>
          </div>
          
          <div class="form-group">
            <label>Do you belong to another city? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="belongToOtherCity" value="Yes" 
                       {{ old('belongToOtherCity', $student->belongToOtherCity ?? $student->belongs_other_city ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="belongToOtherCity" value="No" 
                       {{ old('belongToOtherCity', $student->belongToOtherCity ?? $student->belongs_other_city ?? 'No') == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>

          <div class="form-group">
            <label>Do You Belong to Economic Weaker Section? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="economicWeakerSection" value="Yes" 
                       {{ old('economicWeakerSection', $student->economicWeakerSection ?? $student->economic_weaker_section ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="economicWeakerSection" value="No" 
                       {{ old('economicWeakerSection', $student->economicWeakerSection ?? $student->economic_weaker_section ?? 'No') == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>

          <div class="form-group">
            <label>Do You Belong to Any Army/Police/Martyr Background? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="armyPoliceBackground" value="Yes" 
                       {{ old('armyPoliceBackground', $student->armyPoliceBackground ?? $student->army_police_background ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="armyPoliceBackground" value="No" 
                       {{ old('armyPoliceBackground', $student->armyPoliceBackground ?? $student->army_police_background ?? 'No') == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>

          <div class="form-group">
            <label>Are You a Specially Abled? <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="speciallyAbled" value="Yes" 
                       {{ old('speciallyAbled', $student->speciallyAbled ?? $student->specially_abled ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="speciallyAbled" value="No" 
                       {{ old('speciallyAbled', $student->speciallyAbled ?? $student->specially_abled ?? 'No') == 'No' ? 'checked' : '' }}>
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
              <option value="Pre-Medical" {{ old('course_type', $student->course_type ?? $student->courseType ?? '') == 'Pre-Medical' ? 'selected' : '' }}>Pre-Medical</option>
              <option value="Pre-Engineering" {{ old('course_type', $student->course_type ?? $student->courseType ?? '') == 'Pre-Engineering' ? 'selected' : '' }}>Pre-Engineering</option>
              <option value="Pre-Foundation" {{ old('course_type', $student->course_type ?? $student->courseType ?? '') == 'Pre-Foundation' ? 'selected' : '' }}>Pre-Foundation</option>
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
              <option value="Offline" {{ old('deliveryMode', $student->deliveryMode ?? $student->delivery_mode) == 'Offline' ? 'selected' : '' }}>Offline</option>
              <option value="Online" {{ old('deliveryMode', $student->deliveryMode ?? $student->delivery_mode) == 'Online' ? 'selected' : '' }}>Online</option>
              <option value="Hybrid" {{ old('deliveryMode', $student->deliveryMode ?? $student->delivery_mode) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
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
              <option value="Class 10th course" {{ old('courseContent', $student->courseContent ?? $student->course_content) == 'Class 10th course' ? 'selected' : '' }}>Class 10th course</option>
              <option value="JEE/NEET Foundation" {{ old('courseContent', $student->courseContent ?? $student->course_content) == 'JEE/NEET Foundation' ? 'selected' : '' }}>JEE/NEET Foundation</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Academic Detail Section -->
      <div class="form-section">
        <h4>Academic Detail</h4>
        <div class="form-row">
          <div class="form-group">
            <label>Previous Class </label>
            <select name="previousClass" class="form-select">
              <option value="">Select Previous Class</option>
              <option value="6th" {{ old('previousClass', $student->previousClass ?? $student->previous_class) == '6th' ? 'selected' : '' }}>6th</option>
              <option value="7th" {{ old('previousClass', $student->previousClass ?? $student->previous_class) == '7th' ? 'selected' : '' }}>7th</option>
              <option value="8th" {{ old('previousClass', $student->previousClass ?? $student->previous_class) == '8th' ? 'selected' : '' }}>8th</option>
              <option value="9th" {{ old('previousClass', $student->previousClass ?? $student->previous_class) == '9th' ? 'selected' : '' }}>9th</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Previous Medium </label>
            <select name="previousMedium" class="form-select">
              <option value="">Select Medium</option>
              <option value="English" {{ old('previousMedium', $student->previousMedium ?? $student->previous_medium) == 'English' ? 'selected' : '' }}>English</option>
              <option value="Hindi" {{ old('previousMedium', $student->previousMedium ?? $student->previous_medium) == 'Hindi' ? 'selected' : '' }}>Hindi</option>
            </select>
          </div>
          
          <div class="form-group full-width">
            <label>Name Of School</label>
            <input type="text" name="schoolName" class="form-control" 
                   value="{{ old('schoolName', $student->schoolName ?? $student->school_name) }}">
          </div>
          
          <div class="form-group">
            <label>Previous Board </label>
            <select name="previousBoard" class="form-select">
              <option value="">Select Board</option>
              <option value="CBSE" {{ old('previousBoard', $student->previousBoard ?? $student->previous_board) == 'CBSE' ? 'selected' : '' }}>CBSE</option>
              <option value="RBSE" {{ old('previousBoard', $student->previousBoard ?? $student->previous_board) == 'RBSE' ? 'selected' : '' }}>RBSE</option>
              <option value="ICSE" {{ old('previousBoard', $student->previousBoard ?? $student->previous_board) == 'ICSE' ? 'selected' : '' }}>ICSE</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Passing Year</label>
            <input type="text" name="passingYear" class="form-control" 
                   value="{{ old('passingYear', $student->passingYear ?? $student->passing_year) }}" 
                   pattern="[0-9]{4}" maxlength="4" placeholder="YYYY">
          </div>
          
          <div class="form-group">
            <label>Percentage </label>
            <input type="number" name="percentage" class="form-control" 
                   value="{{ old('percentage', $student->percentage) }}" 
                   min="0" max="100" step="0.01">
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
            <label>Scholarship Test Appeared <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="scholarshipTest" value="Yes" 
                       {{ old('scholarshipTest', $student->scholarshipTest ?? $student->scholarship_test ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="scholarshipTest" value="No" 
                       {{ old('scholarshipTest', $student->scholarshipTest ?? $student->scholarship_test ?? 'No') == 'No' ? 'checked' : '' }}>
                No
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Last Board Percentage</label>
            <input type="number" name="lastBoardPercentage" class="form-control" 
                   value="{{ old('lastBoardPercentage', $student->lastBoardPercentage ?? $student->board_percentage) }}" 
                   min="0" max="100" step="0.01">
          </div>
          
          <div class="form-group">
            <label>Competition Exam Appeared <span class="required">*</span></label>
            <div class="radio-group">
              <label>
                <input type="radio" name="competitionExam" value="Yes" 
                       {{ old('competitionExam', $student->competitionExam ?? $student->competition_exam ?? 'No') == 'Yes' ? 'checked' : '' }} required>
                Yes
              </label>
              <label>
                <input type="radio" name="competitionExam" value="No" 
                       {{ old('competitionExam', $student->competitionExam ?? $student->competition_exam ?? 'No') == 'No' ? 'checked' : '' }}>
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
            <label>Batch Name </label>
            <input type="text" name="batchName" class="form-control" 
                   value="{{ old('batchName', $student->batchName ?? $student->batch_name) }}">
          </div>
        </div>
      </div>

      <!-- edit-onboard -->
      <!-- Upload Documents Section -->
      <div class="form-section">
        <h4>Upload Documents</h4>
        <div class="form-row">
          <div class="form-group">
            <label>Passport Size Photo</label>
            <input type="file" name="passport_photo" class="form-control" accept="image/*">
            @if(!empty($student->passport_photo))
              <small class="text-muted">Current: <a href="{{ asset('storage/' . $student->passport_photo) }}" target="_blank">View</a></small>
            @endif
          </div>

          <div class="form-group">
              <label>Marksheet of Last Qualifying Exam</label>
              <input type="file" name="marksheet" class="form-control" accept=".pdf,.jpg,.jpeg,.png">

              @if(!empty($student->marksheet))
                 <small class="text-muted">
    Current File: 
    <a href="{{ $student->marksheet }}" target="_blank">View</a>
</small>
              @endif
          </div>

          <div class="form-group">
            <label>Caste Certificate (if applicable)</label>
            <input type="file" name="caste_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($student->caste_certificate))
              <small class="text-muted">Current: <a href="{{ asset('storage/' . $student->caste_certificate) }}" target="_blank">View</a></small>
            @endif
          </div>

          <div class="form-group">
            <label>Scholarship Proof</label>
            <input type="file" name="scholarship_proof" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($student->scholarship_proof))
              <small class="text-muted">Current: <a href="{{ asset('storage/' . $student->scholarship_proof) }}" target="_blank">View</a></small>
            @endif
          </div>

          <div class="form-group">
            <label>Secondary Board Marksheet</label>
            <input type="file" name="secondary_marksheet" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($student->secondary_marksheet))
              <small class="text-muted">Current: <a href="{{ asset('storage/' . $student->secondary_marksheet) }}" target="_blank">View</a></small>
            @endif
          </div>

          <div class="form-group">
            <label>Senior Secondary Board Marksheet</label>
            <input type="file" name="senior_secondary_marksheet" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            @if(!empty($student->senior_secondary_marksheet))
              <small class="text-muted">Current: <a href="{{ asset('storage/' . $student->senior_secondary_marksheet) }}" target="_blank">View</a></small>
            @endif
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
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/session.js') }}"></script>
  <script>
document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.querySelector('select[name="courseName"]');
    const batchSelect = document.querySelector('select[name="batchName"]');
    
    if (courseSelect && batchSelect) {
        courseSelect.addEventListener('change', function() {
            const courseName = this.value;
            
            // Clear current batches
            batchSelect.innerHTML = '<option value="">Loading...</option>';
            
            // Fetch filtered batches
            fetch(`/student/onboard/batches-by-course?course=${encodeURIComponent(courseName)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        batchSelect.innerHTML = '<option value="">Select Batch</option>';
                        
                        data.batches.forEach(batch => {
                            const option = document.createElement('option');
                            option.value = batch.batch_id;
                            option.textContent = `${batch.batch_id} (${batch.shift} - ${batch.mode})`;
                            batchSelect.appendChild(option);
                        });
                    } else {
                        batchSelect.innerHTML = '<option value="">No batches available</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading batches:', error);
                    batchSelect.innerHTML = '<option value="">Error loading batches</option>';
                });
        });
    }
});

    // Sidebar toggle
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('sidebar');
    const right = document.getElementById('right');
    const text = document.getElementById('text');

    if (toggleBtn && sidebar && right && text) {
      toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        right.classList.toggle('expanded');
        text.classList.toggle('hidden');
      });
    }

    // Course dropdown population
    document.addEventListener("DOMContentLoaded", function () {
      const courseTypeSelect = document.getElementById("course_type");
      const courseSelect = document.getElementById("course");

      const courseOptions = {
        "Pre-Medical": ["Anthesis 11th NEET", "Momentum 12th NEET", "Dynamic Target NEET"],
        "Pre-Engineering": ["Impulse 11th IIT", "Intensity 12th IIT", "Thurst Target IIT"],
        "Pre-Foundation": ["Seedling 10th", "Plumule 9th", "Radicle 8th"]
      };

      // Pre-fill on page load
      const selectedType = "{{ old('course_type', $student->course_type ?? $student->courseType ?? '') }}";
      const selectedCourse = "{{ old('courseName', $student->courseName ?? $student->course_name ?? '') }}";

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
  </script>
</body>
</html>