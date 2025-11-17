<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Student Details - Pending Fees</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .view-section {
      background: #fff;
      padding: 25px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .view-section h4 {
      color: #ff6b35;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #ff6b35;
    }

    .info-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 15px;
    }

    .info-item {
      padding: 12px;
      background: #f8f9fa;
      border-radius: 5px;
      border-left: 3px solid #ff6b35;
    }

    .info-label {
      font-weight: 600;
      color: #555;
      font-size: 0.9rem;
      margin-bottom: 5px;
    }

    .info-value {
      color: #333;
      font-size: 1rem;
    }

    .back-btn {
      color: #ff6b35;
      text-decoration: none;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 20px;
    }

    .back-btn:hover {
      color: #e55a2b;
    }

    .btn-edit {
      background-color: #ff6b35;
      color: white;
      padding: 10px 30px;
      border: none;
      border-radius: 5px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
    }

    .btn-edit:hover {
      background-color: #e55a2b;
      color: white;
    }

    .badge-status {
      padding: 8px 15px;
      border-radius: 20px;
      font-weight: 600;
      display: inline-block;
      background-color: #ffc107;
      color: #000;
    }
  </style>
</head>

<body>
  <!-- Header Section -->
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
                <li><a class="item" href="{{ route('attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Employee</a></li>
                <li><a class="item" href="{{ route(name: 'attendance.student.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Student</a></li>

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
        <a href="{{ route('inquiries.index') }}" class="back-btn">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <div class="d-flex justify-content-between align-items-center mb-4">
        </div>

        <!-- Basic Details Section -->
        <div class="view-section">
          <h4>View Basic Details</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Student Name</label>
              <input type="text" class="form-control" value="{{ $inquiry->student_name ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Father Name</label>
              <input type="text" class="form-control" value="{{ $inquiry->father_name ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Mother Name</label>
              <input type="text" class="form-control" value="{{ $inquiry->mother ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Date of Birth</label>
              <input type="text" class="form-control"
                value="{{ $inquiry->dob ? date('d-m-Y', strtotime($inquiry->dob)) : 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Father Contact No</label>
              <input type="text" class="form-control" value="{{ $inquiry->father_contact ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Father WhatsApp Number</label>
              <input type="text" class="form-control" value="{{ $inquiry->father_whatsapp ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Mother Contact No</label>
              <input type="text" class="form-control" value="{{ $inquiry->motherContact ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Student Contact No</label>
              <input type="text" class="form-control" value="{{ $inquiry->student_contact ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Category</label>
              <input type="text" class="form-control" value="{{ $inquiry->category ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Gender</label>
              <input type="text" class="form-control" value="{{ $inquiry->gender ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Father Occupation</label>
              <input type="text" class="form-control" value="{{ $inquiry->fatherOccupation ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Father's Grade</label>
              <input type="text" class="form-control" value="{{ $inquiry->fatherGrade ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Mother Occupation</label>
              <input type="text" class="form-control" value="{{ $inquiry->motherOccupation ?? 'N/A' }}" readonly>
            </div>
          </div>
        </div>

        <!-- Address Details Section -->
        <div class="view-section">
          <h4>Address Details</h4>
          <div class="form-row">
            <div class="form-group">
              <label>State</label>
              <input type="text" class="form-control" value="{{ $inquiry->state ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>City</label>
              <input type="text" class="form-control" value="{{ $inquiry->city ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Pin Code</label>
              <input type="text" class="form-control" value="{{ $inquiry->pinCode ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group full-width">
              <label>Address</label>
              <textarea class="form-control" rows="2" readonly>{{ $inquiry->address ?? 'N/A' }}</textarea>
            </div>

            <div class="form-group">
              <label>Belong to Other City</label>
              <input type="text" class="form-control" value="{{ $inquiry->belongToOtherCity ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Economic Weaker Section</label>
              <input type="text" class="form-control" value="{{ $inquiry->economicWeakerSection ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Army/Police/Martyr Background</label>
              <input type="text" class="form-control" value="{{ $inquiry->armyPoliceBackground ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Specially Abled</label>
              <input type="text" class="form-control" value="{{ $inquiry->speciallyAbled ?? 'N/A' }}" readonly>
            </div>
          </div>
        </div>

        <!-- Course Details Section -->
        <div class="view-section">
          <h4>Course Details</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Course Type</label>
              <input type="text" class="form-control" value="{{ $inquiry->courseType ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Course Name</label>
              <input type="text" class="form-control" value="{{ $inquiry->course_name ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Delivery Mode</label>
              <input type="text" class="form-control" value="{{ $inquiry->delivery_mode ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Medium</label>
              <input type="text" class="form-control" value="{{ $inquiry->medium ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Board</label>
              <input type="text" class="form-control" value="{{ $inquiry->board ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Course Content</label>
              <input type="text" class="form-control" value="{{ $inquiry->course_content ?? 'N/A' }}" readonly>
            </div>
          </div>
        </div>

        <!-- Academic Details Section -->
        <div class="view-section">
          <h4>Academic Details</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Previous Class</label>
              <input type="text" class="form-control" value="{{ $inquiry->previousClass ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Previous Medium</label>
              <input type="text" class="form-control" value="{{ $inquiry->previousMedium ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>School Name</label>
              <input type="text" class="form-control" value="{{ $inquiry->schoolName ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Previous Board</label>
              <input type="text" class="form-control" value="{{ $inquiry->previousBoard ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Passing Year</label>
              <input type="text" class="form-control" value="{{ $inquiry->passingYear ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
              <label>Percentage</label>
              <input type="text" class="form-control"
                value="{{ $inquiry->percentage ?? 'N/A' }}{{ $inquiry->percentage ? '%' : '' }}" readonly>
            </div>
          </div>
        </div>

<!-- Scholarship Details Section -->
        <div class="view-section">
          <h4>Scholarship Details</h4>
          <div class="form-row">
            <div class="form-group">
              <label>Eligible For Scholarship</label>
              <input type="text" class="form-control" value="{{ $feesData['eligible_for_scholarship'] }}" readonly>
            </div>

            <div class="form-group">
              <label>Name of Scholarship</label>
              <input type="text" class="form-control" value="{{ $feesData['scholarship_name'] }}" readonly>
            </div>

            <div class="form-group">
              <label>Total Fee Before Discount</label>
              <input type="text" class="form-control" value="₹{{ number_format($feesData['total_fee_before_discount']) }}" readonly>
            </div>

            <div class="form-group">
              <label>Discretionary Discount</label>
              <input type="text" class="form-control" value="{{ $feesData['discretionary_discount'] }}" readonly>
            </div>

            <div class="form-group">
              <label>Discount Percentage</label>
              <input type="text" class="form-control" value="{{ $feesData['discount_percentage'] }}%" readonly>
            </div>

            <div class="form-group">
              <label>Discounted Fee</label>
              <input type="text" class="form-control" value="₹{{ number_format($feesData['discounted_fee']) }}" readonly>
            </div>
          </div>
        </div>

        <!-- Fees and Available Batches Details Section -->
        <div class="view-section">
          <h4>Fees and Available Batches Details</h4>
          <div class="form-row">
            <div class="form-group full-width">
              <label>Fees Breakup</label>
              <input type="text" class="form-control" value="{{ $feesData['fees_breakup'] }}" readonly style="color: #ff6b35; font-weight: 600;">
            </div>

            <div class="form-group">
              <label>Total Fees</label>
              <input type="text" class="form-control" value="₹{{ number_format($feesData['total_fees']) }}" readonly>
            </div>

            <div class="form-group">
              <label>GST Amount</label>
              <input type="text" class="form-control" value="₹{{ number_format($feesData['gst_amount']) }}" readonly>
            </div>

            <div class="form-group">
              <label>Total Fees Inclusive Tax</label>
              <input type="text" class="form-control" value="₹{{ number_format($feesData['total_fees_inclusive_tax']) }}" readonly style="color: #ff6b35; font-weight: 600;">
            </div>

            <div class="form-group">
              <label>If Fees Deposited In Single Installment</label>
              <input type="text" class="form-control" value="₹{{ number_format($feesData['single_installment_amount']) }}" readonly style="color: #ff6b35; font-weight: 600;">
            </div>

            <div class="form-group full-width" style="margin-top: 15px; margin-bottom: 5px;">
              <label style="font-weight: 700; color: #ff6b35;">If Fees Deposited In Three Installments</label>
            </div>

            <div class="form-group">
              <label>Installment 1 (40%)</label>
              <input type="text" class="form-control" value="₹{{ number_format($feesData['installment_1']) }}" readonly>
            </div>

            <div class="form-group">
              <label>Installment 2 (30%)</label>
              <input type="text" class="form-control" value="₹{{ number_format($feesData['installment_2']) }}" readonly>
            </div>

            <div class="form-group">
              <label>Installment 3 (30%)</label>
              <input type="text" class="form-control" value="₹{{ number_format($feesData['installment_3']) }}" readonly>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('js/emp.js')}}"></script>
</body>

</html>