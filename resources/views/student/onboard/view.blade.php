<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Student Onboarding - {{ $student->name ?? $student->student_name }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    .view-section {
      background: #fff;
      padding: 25px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .view-section h4 {
      color: #ff6b35;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #ff6b35;
    }
    
    .detail-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 15px;
    }
    
    .detail-group {
      padding: 10px;
      border-left: 3px solid #ff6b35;
      background: #f8f9fa;
      border-radius: 4px;
    }
    
    .detail-group.full-width {
      grid-column: 1 / -1;
    }
    
    .detail-label {
      font-weight: 600;
      color: #666;
      font-size: 0.9rem;
      margin-bottom: 5px;
    }
    
    .detail-value {
      color: #333;
      font-size: 1rem;
      word-break: break-word;
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
    
    .action-buttons {
      display: flex;
      gap: 15px;
      margin-top: 30px;
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-edit {
      background: #ff6b35;
      color: white;
      padding: 12px 30px;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    
    .btn-edit:hover {
      background: #e55a2b;
      color: white;
    }
    
    .btn-transfer {
      background: #28a745;
      color: white;
      padding: 12px 30px;
      border: none;
      border-radius: 6px;
      font-weight: 600;
    }
    
    .btn-transfer:hover {
      background: #218838;
    }

    .document-preview {
      max-width: 200px;
      max-height: 200px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-top: 10px;
    }

    .document-link {
      color: #ff6b35;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      margin-top: 5px;
    }

    .document-link:hover {
      color: #e55a2b;
    }

    .badge-status {
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .badge-yes {
      background: #d4edda;
      color: #155724;
    }

    .badge-no {
      background: #f8d7da;
      color: #721c24;
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
        <a href="{{ route('student.onboard.onboard') }}" class="back-btn">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 style="color: #ff6b35;">View Student Onboarding</h4>
        </div>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Basic Details Section -->
        <div class="view-section">
          <h4>Basic Details</h4>
          <div class="detail-row">
            <div class="detail-group">
              <div class="detail-label">Student Name</div>
              <div class="detail-value">{{ $student->name ?? $student->student_name ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Father Name</div>
              <div class="detail-value">{{ $student->father ?? $student->father_name ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Mother Name</div>
              <div class="detail-value">{{ $student->mother ?? $student->mother_name ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">DOB</div>
              <div class="detail-value">{{ $student->dob ? date('d M Y', strtotime($student->dob)) : 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Father Contact No</div>
              <div class="detail-value">{{ $student->mobileNumber ?? $student->father_contact ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Father WhatsApp Number</div>
              <div class="detail-value">{{ $student->fatherWhatsapp ?? $student->father_whatsapp ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Mother Contact No</div>
              <div class="detail-value">{{ $student->motherContact ?? $student->mother_contact ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Student Contact No</div>
              <div class="detail-value">{{ $student->studentContact ?? $student->phone ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Category</div>
              <div class="detail-value">
                <span class="badge bg-primary">{{ $student->category ?? 'GENERAL' }}</span>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Gender</div>
              <div class="detail-value">
                <span class="badge bg-info">{{ $student->gender ?? 'N/A' }}</span>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Father Occupation</div>
              <div class="detail-value">{{ $student->fatherOccupation ?? $student->father_occupation ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Mother Occupation</div>
              <div class="detail-value">{{ $student->motherOccupation ?? $student->mother_occupation ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">State</div>
              <div class="detail-value">{{ $student->state ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">City</div>
              <div class="detail-value">{{ $student->city ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Pin Code</div>
              <div class="detail-value">{{ $student->pinCode ?? $student->pincode ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group full-width">
              <div class="detail-label">Address</div>
              <div class="detail-value">{{ $student->address ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Belong to Another City</div>
              <div class="detail-value">
                <span class="badge {{ ($student->belongToOtherCity ?? $student->belongs_other_city ?? 'No') == 'Yes' ? 'badge-yes' : 'badge-no' }}">
                  {{ $student->belongToOtherCity ?? $student->belongs_other_city ?? 'No' }}
                </span>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Economic Weaker Section</div>
              <div class="detail-value">
                <span class="badge {{ ($student->economicWeakerSection ?? $student->economic_weaker_section ?? 'No') == 'Yes' ? 'badge-yes' : 'badge-no' }}">
                  {{ $student->economicWeakerSection ?? $student->economic_weaker_section ?? 'No' }}
                </span>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Army/Police/Martyr Background</div>
              <div class="detail-value">
                <span class="badge {{ ($student->armyPoliceBackground ?? $student->army_police_background ?? 'No') == 'Yes' ? 'badge-yes' : 'badge-no' }}">
                  {{ $student->armyPoliceBackground ?? $student->army_police_background ?? 'No' }}
                </span>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Specially Abled</div>
              <div class="detail-value">
                <span class="badge {{ ($student->speciallyAbled ?? $student->specially_abled ?? 'No') == 'Yes' ? 'badge-yes' : 'badge-no' }}">
                  {{ $student->speciallyAbled ?? $student->specially_abled ?? 'No' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Course Details Section -->
        <div class="view-section">
          <h4>Course Details</h4>
          <div class="detail-row">
            <div class="detail-group">
              <div class="detail-label">Course Type</div>
              <div class="detail-value">{{ $student->course_type ?? $student->courseType ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Course Name</div>
              <div class="detail-value">{{ $student->courseName ?? $student->course_name ?? $student->course->name ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Delivery Mode</div>
              <div class="detail-value">{{ $student->deliveryMode ?? $student->delivery_mode ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Medium</div>
              <div class="detail-value">{{ $student->medium ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Board</div>
              <div class="detail-value">{{ $student->board ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Course Content</div>
              <div class="detail-value">{{ $student->courseContent ?? $student->course_content ?? 'N/A' }}</div>
            </div>
          </div>
        </div>

        <!-- Academic Detail Section -->
        <div class="view-section">
          <h4>Academic Detail</h4>
          <div class="detail-row">
            <div class="detail-group">
              <div class="detail-label">Previous Class</div>
              <div class="detail-value">{{ $student->previousClass ?? $student->previous_class ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Medium</div>
              <div class="detail-value">{{ $student->previousMedium ?? $student->previous_medium ?? $student->academic_medium ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group full-width">
              <div class="detail-label">Name Of School</div>
              <div class="detail-value">{{ $student->schoolName ?? $student->school_name ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Board</div>
              <div class="detail-value">{{ $student->previousBoard ?? $student->previous_board ?? $student->academic_board ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Passing Year</div>
              <div class="detail-value">{{ $student->passingYear ?? $student->passing_year ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Percentage/Grade</div>
              <div class="detail-value">{{ $student->percentage ?? 'N/A' }}</div>
            </div>
          </div>
        </div>

        <!-- Scholarship Eligibility Section -->
        <div class="view-section">
          <h4>Scholarship Eligibility</h4>
          <div class="detail-row">
            <div class="detail-group">
              <div class="detail-label">Scholarship Test Appeared</div>
              <div class="detail-value">
                <span class="badge {{ ($student->scholarshipTest ?? $student->scholarship_test ?? 'No') == 'Yes' ? 'badge-yes' : 'badge-no' }}">
                  {{ $student->scholarshipTest ?? $student->scholarship_test ?? 'No' }}
                </span>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Competition Exam Appeared</div>
              <div class="detail-value">
                <span class="badge {{ ($student->competitionExam ?? $student->competition_exam ?? 'No') == 'Yes' ? 'badge-yes' : 'badge-no' }}">
                  {{ $student->competitionExam ?? $student->competition_exam ?? 'No' }}
                </span>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Last Board Percentage</div>
              <div class="detail-value">{{ $student->lastBoardPercentage ?? $student->last_board_percentage ?? $student->board_percentage ?? 'N/A' }}</div>
            </div>
          </div>
        </div>

        <!-- Batch Allocation Section -->
        <div class="view-section">
          <h4>Batch Allocation</h4>
          <div class="detail-row">
            <div class="detail-group">
              <div class="detail-label">Batch Name</div>
              <div class="detail-value">{{ $student->batchName ?? $student->batch_name ?? $student->batch->name ?? 'N/A' }}</div>
            </div>
          </div>
        </div>

        <!-- View Documents Section -->
        <div class="view-section">
          <h4>View Documents</h4>
          <div class="detail-row">
            @if(!empty($student->passport_photo))
              <div class="detail-group">
                <div class="detail-label">Passport Size Photo</div>
                <a href="{{ asset('storage/' . $student->passport_photo) }}" target="_blank" class="document-link">
                  <i class="fas fa-file-image"></i> View Document
                </a>
              </div>
            @endif
            
            @if(!empty($student->marksheet))
              <div class="detail-group">
                <div class="detail-label">Marksheet of Last Qualifying Exam</div>
                <a href="{{ asset('storage/' . $student->marksheet) }}" target="_blank" class="document-link">
                  <i class="fas fa-file-pdf"></i> View Document
                </a>
              </div>
            @endif
            
            @if(!empty($student->caste_certificate))
              <div class="detail-group">
                <div class="detail-label">Caste Certificate</div>
                <a href="{{ asset('storage/' . $student->caste_certificate) }}" target="_blank" class="document-link">
                  <i class="fas fa-file-pdf"></i> View Document
                </a>
              </div>
            @endif
            
            @if(!empty($student->scholarship_proof))
              <div class="detail-group">
                <div class="detail-label">Scholarship Proof</div>
                <a href="{{ asset('storage/' . $student->scholarship_proof) }}" target="_blank" class="document-link">
                  <i class="fas fa-file-pdf"></i> View Document
                </a>
              </div>
            @endif
            
            @if(!empty($student->secondary_marksheet))
              <div class="detail-group">
                <div class="detail-label">Secondary Board Marksheet</div>
                <a href="{{ asset('storage/' . $student->secondary_marksheet) }}" target="_blank" class="document-link">
                  <i class="fas fa-file-pdf"></i> View Document
                </a>
              </div>
            @endif
            
            @if(!empty($student->senior_secondary_marksheet))
              <div class="detail-group">
                <div class="detail-label">Senior Secondary Board Marksheet</div>
                <a href="{{ asset('storage/' . $student->senior_secondary_marksheet) }}" target="_blank" class="document-link">
                  <i class="fas fa-file-pdf"></i> View Document
                </a>
              </div>
            @endif
          </div>
        </div>

        <!-- Scholarship Details Section -->
        <div class="view-section">
          <h4>Scholarship Details</h4>
          <div class="detail-row">
            <div class="detail-group">
              <div class="detail-label">Eligible For Scholarship</div>
              <div class="detail-value">
                <span class="badge {{ $scholarshipEligible['eligible'] ? 'badge-yes' : 'badge-no' }}">
                  {{ $scholarshipEligible['eligible'] ? 'Yes' : 'No' }}
                </span>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Name of Scholarship</div>
              <div class="detail-value">{{ $student->scholarship_name ?? $scholarshipEligible['reason'] ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Total Fee Before Discount</div>
              <div class="detail-value">₹{{ number_format($student->total_fee_before_discount ?? $student->total_fees ?? 88000, 2) }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Discount Percentage</div>
              <div class="detail-value">{{ $student->discount_percentage ?? $scholarshipEligible['discountPercent'] ?? 0 }}%</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Discounted Fees</div>
              <div class="detail-value">₹{{ number_format($student->discounted_fees ?? $student->total_fees ?? 88000, 2) }}</div>
            </div>
          </div>
        </div>

        <!-- Discretionary Form Section -->
        <div class="view-section">
          <h4>Discretionary Form</h4>
          <div class="detail-row">
            <div class="detail-group">
              <div class="detail-label">Discretionary Discount</div>
              <div class="detail-value">
                <span class="badge {{ ($student->discretionary_discount ?? 'No') == 'Yes' ? 'badge-yes' : 'badge-no' }}">
                  {{ $student->discretionary_discount ?? 'No' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Fees and Available Batches Details Section -->
        <div class="view-section">
          <h4>Fees and Available Batches Details</h4>
          <div class="detail-row">
            <div class="detail-group full-width">
              <div class="detail-label">Fees Breakup</div>
              <div class="detail-value">
                <table class="table table-sm">
                  <tbody>
                    <tr>
                      <td>Classroom course (with test series & study material)</td>
                      <td class="text-end">₹{{ number_format($student->course_fee ?? 88000, 2) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Total Fees</div>
              <div class="detail-value">₹{{ number_format($student->total_fees ?? 88000, 2) }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">GST Amount</div>
              <div class="detail-value">₹{{ number_format($student->gst_amount ?? 15840, 2) }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Total Fees (inclusive tax)</div>
              <div class="detail-value">₹{{ number_format($student->total_fees_with_gst ?? 103840, 2) }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Single Installment</div>
              <div class="detail-value">₹{{ number_format($student->single_installment ?? 103840, 2) }}</div>
            </div>
            
            <div class="detail-group full-width">
              <div class="detail-label">Three Installments</div>
              <div class="detail-value">
                <ul class="list-unstyled mb-0">
                  <li>Installment 1: ₹{{ number_format($student->installment_1 ?? 41536, 2) }}</li>
                  <li>Installment 2: ₹{{ number_format($student->installment_2 ?? 31152, 2) }}</li>
                  <li>Installment 3: ₹{{ number_format($student->installment_3 ?? 31152, 2) }}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
          <a href="{{ route('student.onboard.edit', $student->_id ?? $student->id) }}" class="btn-edit">
            <i class="fa-solid fa-edit"></i> Edit Details
          </a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/session.js') }}"></script>
  <script>
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
  </script>
</body>
</html>