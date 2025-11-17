<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Update Student - {{ $student->student_name ?? $student->name }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/smstudents.css') }}">
  <style>
    .edit-container {
      background-color: #f5f5f5;
      min-height: 100vh;
      padding: 20px;
    }
    .page-header {
      padding: 20px 30px;
      margin: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 90%;
    }
    .page-title {
      color: #e05301;
      font-size: 24px;
      font-weight: 600;
      margin: 0;
    }
    .back-link {
      color: #e05301;
      text-decoration: none;
      font-size: 16px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .back-link:hover {
      color: #c04501;
    }
    .form-container {
      background-color: #ffffff;
      margin: 0 20px 20px 20px;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      width: 80%;
    }
    .section-title {
      color: #e05301;
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #e05301;
    }
    .form-section {
      margin-bottom: 40px;
    }
    .form-label {
      font-weight: 500;
      color: #333;
      margin-bottom: 8px;
    }
    .form-control, .form-select {
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 10px 12px;
    }
    .form-control:focus, .form-select:focus {
      border-color: #e05301;
      box-shadow: 0 0 0 0.2rem rgba(224, 83, 1, 0.25);
    }
    .radio-group {
      display: flex;
      gap: 20px;
      align-items: center;
    }
    .radio-group .form-check {
      margin-bottom: 0;
    }
    .upload-area {
      border: 2px dashed #dee2e6;
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s;
      background-color: #f8f9fa;
    }
    .upload-area:hover {
      border-color: #e05301;
      background-color: #fff5f0;
    }
    .upload-area i {
      font-size: 40px;
      color: #e05301;
      margin-bottom: 10px;
    }
    .btn-update {
      background-color: #e05301;
      color: white;
      padding: 12px 40px;
      border: none;
      border-radius: 5px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    .btn-update:hover {
      background-color: #c04501;
      color: white;
    }
    .current-file-info {
      margin-top: 8px;
      padding: 8px 12px;
      background-color: #f8f9fa;
      border-radius: 4px;
      border-left: 3px solid #28a745;
    }
  </style>
</head>

<body>
  <div class="flash-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  <div class="header">
    <div class="logo">
      <img src="{{ asset('images/logo.png.jpg') }}" class="img">
      <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="pfp">
      <div class="session">
        <h5>Session:</h5>
        <select>
          <option>2024-2025</option>
          <option selected>2025-2026</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="fa-solid fa-user"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <div class="left" id="sidebar">
      <div class="text" id="text">
        <h6>Admin</h6>
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
           <li><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i> Employee</a></li>     
           <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group" id="side-icon"></i> Batches Assignment</a></li>
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
                <li><a class="item" href="{{ route('attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Employee</a></li>
                <li><a class="item" href="#"><i class="fa-solid fa-user" id="side-icon"></i>Student</a></li>
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

    <div class="right" id="right">
      <!-- Page Header -->
      <div class="page-header">
        <h1 class="page-title">Update Student</h1>
        <a href="{{ route('smstudents.index') }}" class="back-link">
          <i class="fas fa-arrow-left"></i> Back
        </a>
      </div>

      <!-- Form Container -->
      <div class="form-container">
        <form method="POST" action="{{ route('smstudents.update', $student->_id ?? $student->id) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT') 
          <!-- Basic Details Section -->
          <div class="form-section">
            <h5 class="section-title">Basic Details</h5>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Student Name</label>
                <input type="text" name="student_name" class="form-control" value="{{ old('student_name', $student->student_name ?? $student->name) }}" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Father Name</label>
                <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $student->father_name) }}">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Mother Name</label>
                <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $student->mother_name) }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">DOB</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob', $student->dob) }}">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Father Contact No</label>
                <input type="text" name="father_contact" class="form-control" value="{{ old('father_contact', $student->father_contact) }}" maxlength="15">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Father Whatsapp Number</label>
                <input type="text" name="father_whatsapp" class="form-control" value="{{ old('father_whatsapp', $student->father_whatsapp) }}" maxlength="15">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Mother Contact No</label>
                <input type="text" name="mother_contact" class="form-control" value="{{ old('mother_contact', $student->mother_contact) }}" maxlength="15">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Student Contact No</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}" required maxlength="15">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Gender</label>
                <div class="radio-group">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" value="Male" {{ ($student->gender ?? '') == 'Male' ? 'checked' : '' }}>
                    <label class="form-check-label">Male</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" value="Female" {{ ($student->gender ?? '') == 'Female' ? 'checked' : '' }}>
                    <label class="form-check-label">Female</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" value="Others" {{ ($student->gender ?? '') == 'Others' ? 'checked' : '' }}>
                    <label class="form-check-label">Others</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Father Occupation</label>
                <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation', $student->father_occupation) }}">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Father's Caste</label>
                <input type="text" name="father_caste" class="form-control" value="{{ old('father_caste', $student->father_caste ?? 'TEACHER') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Mother Occupation</label>
                <input type="text" name="mother_occupation" class="form-control" value="{{ old('mother_occupation', $student->mother_occupation) }}">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">State</label>
                <select name="state" class="form-select">
                  <option value="">Select State</option>
                  <option value="Rajasthan" {{ ($student->state ?? '') == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                  <option value="Delhi" {{ ($student->state ?? '') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                  <option value="Maharashtra" {{ ($student->state ?? '') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                  <option value="Gujarat" {{ ($student->state ?? '') == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                  <!-- Add more states as needed -->
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">City</label>
                <select name="city" class="form-select">
                  <option value="">Select City</option>
                  <option value="Bikaner" {{ ($student->city ?? '') == 'Bikaner' ? 'selected' : '' }}>Bikaner</option>
                  <option value="Jaipur" {{ ($student->city ?? '') == 'Jaipur' ? 'selected' : '' }}>Jaipur</option>
                  <option value="Jodhpur" {{ ($student->city ?? '') == 'Jodhpur' ? 'selected' : '' }}>Jodhpur</option>
                  <!-- Add more cities as needed -->
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Pin Code</label>
                <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $student->pincode) }}" maxlength="6">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Address Name</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address', $student->address) }}</textarea>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 mb-3">
                <label class="form-label">Do you belong to another city</label>
                <div class="radio-group">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="belongs_other_city" value="Yes" {{ ($student->belongs_other_city ?? '') == 'Yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="belongs_other_city" value="No" {{ ($student->belongs_other_city ?? 'No') == 'No' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Academic Detail Section -->
          <div class="form-section">
            <h5 class="section-title">Academic Detail</h5>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Previous Class</label>
                <select name="previous_class" class="form-select">
                  <option value="">Select Class</option>
                  <option value="12th (XII)" {{ ($student->previous_class ?? '') == '12th (XII)' ? 'selected' : '' }}>12th (XII)</option>
                  <option value="11th (XI)" {{ ($student->previous_class ?? '') == '11th (XI)' ? 'selected' : '' }}>11th (XI)</option>
                  <option value="10th (X)" {{ ($student->previous_class ?? '') == '10th (X)' ? 'selected' : '' }}>10th (X)</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Medium</label>
                <select name="academic_medium" class="form-select">
                  <option value="">Select Medium</option>
                  <option value="English" {{ ($student->academic_medium ?? '') == 'English' ? 'selected' : '' }}>English</option>
                  <option value="Hindi" {{ ($student->academic_medium ?? '') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Name of School</label>
                <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $student->school_name) }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Board</label>
                <select name="academic_board" class="form-select">
                  <option value="">Select Board</option>
                  <option value="RBSE" {{ ($student->academic_board ?? '') == 'RBSE' ? 'selected' : '' }}>RBSE</option>
                  <option value="CBSE" {{ ($student->academic_board ?? '') == 'CBSE' ? 'selected' : '' }}>CBSE</option>
                  <option value="ICSE" {{ ($student->academic_board ?? '') == 'ICSE' ? 'selected' : '' }}>ICSE</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Passing Year</label>
                <input type="text" name="passing_year" class="form-control" value="{{ old('passing_year', $student->passing_year) }}" maxlength="4">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Percentage</label>
                <input type="text" name="percentage" class="form-control" value="{{ old('percentage', $student->percentage) }}">
              </div>
            </div>
          </div>

          <!-- Upload Documents Section -->
          <div class="form-section">
            <h5 class="section-title">Upload Documents</h5>
            
            <!-- Passport Photo -->
            <div class="mb-3">
                <label class="form-label">Passport Size Photo</label>
                <div class="upload-area" onclick="document.getElementById('passport_photo').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload</p>
                    <input type="file" id="passport_photo" name="passport_photo" class="d-none" accept="image/*">
                </div>
                @if(isset($student->passport_photo) && !empty($student->passport_photo))
                    <div class="current-file-info">
                        <small class="text-muted">
                            <i class="fas fa-check-circle text-success"></i> Current file: 
                            <a href="{{ is_array($student->passport_photo) ? $student->passport_photo[0] : $student->passport_photo }}" target="_blank" class="text-primary">View</a>
                        </small>
                    </div>
                @endif
            </div>

            <!-- Marksheet -->
            <div class="mb-3">
                <label class="form-label">Marksheet of Last Qualifying Exam</label>
                <div class="upload-area" onclick="document.getElementById('marksheet').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload</p>
                    <input type="file" id="marksheet" name="marksheet" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                @if(isset($student->marksheet) && !empty($student->marksheet))
                    <div class="current-file-info">
                        <small class="text-muted">
                            <i class="fas fa-check-circle text-success"></i> Current file: 
                            <a href="{{ is_array($student->marksheet) ? $student->marksheet[0] : $student->marksheet }}" target="_blank" class="text-primary">View</a>
                        </small>
                    </div>
                @endif
            </div>

            <!-- Caste Certificate -->
            <div class="mb-3">
                <label class="form-label">Caste Certificate</label>
                <div class="upload-area" onclick="document.getElementById('caste_certificate').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload</p>
                    <input type="file" id="caste_certificate" name="caste_certificate" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                @if(isset($student->caste_certificate) && !empty($student->caste_certificate))
                    <div class="current-file-info">
                        <small class="text-muted">
                            <i class="fas fa-check-circle text-success"></i> Current file: 
                            <a href="{{ is_array($student->caste_certificate) ? $student->caste_certificate[0] : $student->caste_certificate }}" target="_blank" class="text-primary">View</a>
                        </small>
                    </div>
                @endif
            </div>

            <!-- Scholarship Proof -->
            <div class="mb-3">
                <label class="form-label">Proof of Scholarship</label>
                <div class="upload-area" onclick="document.getElementById('scholarship_proof').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload</p>
                    <input type="file" id="scholarship_proof" name="scholarship_proof" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                @if(isset($student->scholarship_proof) && !empty($student->scholarship_proof))
                    <div class="current-file-info">
                        <small class="text-muted">
                            <i class="fas fa-check-circle text-success"></i> Current file: 
                            <a href="{{ is_array($student->scholarship_proof) ? $student->scholarship_proof[0] : $student->scholarship_proof }}" target="_blank" class="text-primary">View</a>
                        </small>
                    </div>
                @endif
            </div>

            <!-- Secondary Marksheet -->
            <div class="mb-3">
                <label class="form-label">Secondary Board Marksheet</label>
                <div class="upload-area" onclick="document.getElementById('secondary_marksheet').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload</p>
                    <input type="file" id="secondary_marksheet" name="secondary_marksheet" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                @if(isset($student->secondary_marksheet) && !empty($student->secondary_marksheet))
                    <div class="current-file-info">
                        <small class="text-muted">
                            <i class="fas fa-check-circle text-success"></i> Current file: 
                            <a href="{{ is_array($student->secondary_marksheet) ? $student->secondary_marksheet[0] : $student->secondary_marksheet }}" target="_blank" class="text-primary">View</a>
                        </small>
                    </div>
                @endif
            </div>

            <!-- Senior Secondary Marksheet -->
            <div class="mb-3">
                <label class="form-label">Senior Secondary Board Marksheet</label>
                <div class="upload-area" onclick="document.getElementById('senior_secondary_marksheet').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload</p>
                    <input type="file" id="senior_secondary_marksheet" name="senior_secondary_marksheet" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                @if(isset($student->senior_secondary_marksheet) && !empty($student->senior_secondary_marksheet))
                    <div class="current-file-info">
                        <small class="text-muted">
                            <i class="fas fa-check-circle text-success"></i> Current file: 
                            <a href="{{ is_array($student->senior_secondary_marksheet) ? $student->senior_secondary_marksheet[0] : $student->senior_secondary_marksheet }}" target="_blank" class="text-primary">View</a>
                        </small>
                    </div>
                @endif
            </div>
          </div>

          <!-- Submit Button -->
          <div class="text-center mt-4">
            <button type="submit" class="btn btn-update">
              <i class="fas fa-save me-2"></i>Update Student
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Sidebar toggle functionality
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('sidebar');
    const right = document.getElementById('right');
    const text = document.getElementById('text');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      right.classList.toggle('expanded');
      text.classList.toggle('hidden');
    });

    // File upload preview — show file name when selected
    document.querySelectorAll('input[type="file"]').forEach(input => {
      input.addEventListener('change', function (e) {
        const uploadArea = e.target.closest('.upload-area');
        if (!uploadArea) return;
        const fileName = e.target.files[0]?.name;
        const previewText = uploadArea.querySelector('p');
        if (fileName) {
          previewText.innerHTML = `<strong>${fileName}</strong>`;
          uploadArea.style.borderColor = "#28a745";
          uploadArea.style.backgroundColor = "#f6fff8";
        } else {
          previewText.innerHTML = "Click to upload";
          uploadArea.style.borderColor = "#dee2e6";
          uploadArea.style.backgroundColor = "#f8f9fa";
        }
      });
    });

    // Auto-hide flash messages after a few seconds
    const flashMessages = document.querySelectorAll('.alert');
    if (flashMessages.length > 0) {
      setTimeout(() => {
        flashMessages.forEach(alert => {
          const bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        });
      }, 4000);
    }
  </script>
</body>
</html>