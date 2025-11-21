<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pay Fees - Enhanced</title>
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
      font-size: 1.2rem;
    }
    .form-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 15px;
    }
    .form-group {
      display: flex;
      flex-direction: column;
    }
    .form-group.full-width {
      grid-column: 1 / -1;
    }
    .form-group label {
      font-weight: 600;
      color: #555;
      font-size: 0.9rem;
      margin-bottom: 5px;
    }
    .form-control {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      background-color: #f8f9fa;
      color: #333;
      font-size: 1rem;
    }
    .form-control:focus {
      background-color: #fff;
      border-color: #ff6b35;
      outline: none;
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
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
    .btn-pay {
      background-color: #ff6b35;
      color: white;
      padding: 12px 40px;
      border: none;
      border-radius: 5px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
    }
    .btn-pay:hover {
      background-color: #e55a2b;
    }
    .radio-group {
      display: flex;
      gap: 20px;
      align-items: center;
      flex-wrap: wrap;
    }
    .radio-option {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 15px;
      border: 2px solid #ddd;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s;
    }
    .radio-option:hover {
      border-color: #ff6b35;
      background-color: #fff5f2;
    }
    .radio-option input[type="radio"]:checked + label {
      font-weight: bold;
    }
    .radio-option input[type="radio"]:checked {
      accent-color: #ff6b35;
    }
    .radio-option input[type="radio"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
    }
    .radio-option label {
      margin: 0;
      cursor: pointer;
      font-weight: 500;
    }
    select.form-control {
      cursor: pointer;
    }
    .installment-card {
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      transition: all 0.3s;
    }
    .installment-card.paid {
      background: #d4edda;
      border-color: #28a745;
    }
    .installment-card.partial {
      background: #fff3cd;
      border-color: #ffc107;
    }
    .installment-card.pending {
      background: #f8f9fa;
      border-color: #dee2e6;
    }
    .installment-card.selectable:hover {
      border-color: #ff6b35;
      box-shadow: 0 0 10px rgba(255, 107, 53, 0.2);
      cursor: pointer;
    }
    .installment-card.selected {
      border-color: #ff6b35;
      background: #fff5f2;
      box-shadow: 0 0 15px rgba(255, 107, 53, 0.3);
    }
    .installment-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }
    .installment-title {
      font-weight: 600;
      font-size: 1.1rem;
      color: #333;
    }
    .installment-badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }
    .badge-paid {
      background: #28a745;
      color: white;
    }
    .badge-partial {
      background: #ffc107;
      color: #000;
    }
    .badge-pending {
      background: #6c757d;
      color: white;
    }
    .installment-details {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid rgba(0,0,0,0.1);
    }
    .detail-item {
      text-align: center;
    }
    .detail-label {
      font-size: 0.85rem;
      color: #666;
      margin-bottom: 3px;
    }
    .detail-value {
      font-weight: 600;
      font-size: 1rem;
    }
    .custom-amount-section {
      display: none;
      background: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      margin-top: 15px;
    }
    .custom-amount-section.active {
      display: block;
    }
    .alert-custom {
      background: #e3f2fd;
      border-left: 4px solid #2196f3;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 4px;
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
                <li><a class="item active" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Pending Fees Students</a></li>
                <li><a class="item" href="{{ route('smstudents.index') }}"><i class="fa-solid fa-user-check" id="side-icon"></i>Students</a></li>
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
                <li>
    <a href="{{ route('test_series.index') }}">
        <i class="icon-class"></i> 
        <span>Test Master</span>
    </a>
</li>              </ul>
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

    <!-- Main Content -->
    <div class="right" id="right">
      <div class="container-fluid py-4">
        <a href="{{ route('student.pendingfees.pending') }}" class="back-btn">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <form action="{{ route('student.pendingfees.processPayment', $student->_id) }}" method="POST" id="paymentForm">
          @csrf

          <!-- Billing Information -->
          <div class="view-section">
            <h4>Billing Information</h4>
            <div class="form-row">
              <div class="form-group">
                <label>Student Name</label>
                <input type="text" class="form-control" value="{{ $student->name }}" readonly>
              </div>
              <div class="form-group">
                <label>Father Name</label>
                <input type="text" class="form-control" value="{{ $student->father ?? '—' }}" readonly>
              </div>
              <div class="form-group">
                <label>Course Name</label>
                <input type="text" class="form-control" value="{{ $student->courseName ?? '—' }}" readonly>
              </div>
              <div class="form-group">
                <label>Batch Name</label>
                <input type="text" class="form-control" value="{{ $student->batchName ?? '—' }}" readonly>
              </div>
            </div>
          </div>

          <!-- Fee Details -->
          <div class="view-section">
            <h4 class="text-danger">Fee Details</h4>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label">Total Fees (Including GST)</label>
                <input type="text" class="form-control fw-bold" value="₹{{ number_format($totalFeesWithGST, 2) }}" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label">Already Paid</label>
                <input type="text" class="form-control text-success fw-bold" value="₹{{ number_format($totalPaid, 2) }}" readonly>
              </div>
              <div class="col-md-12">
                <label class="form-label">Remaining Balance</label>
                <input type="text" class="form-control text-danger fw-bold fs-5" id="remainingBalance" value="₹{{ number_format($remainingBalance, 2) }}" readonly>
              </div>
            </div>
          </div>

          <!-- Scholarship Section (if applicable) -->
          @if($scholarshipData['eligible'] === 'Yes' || $scholarshipData['has_discretionary'])
          <div class="view-section">
              <h4 class="text-success">Applied Discounts</h4>
              <!-- Include scholarship details as before -->
          </div>
          @endif

          <!-- Payment Options -->
          <div class="view-section">
            <h4>Payment Options</h4>
            
            <div class="form-group full-width mb-4">
              <label class="fw-bold mb-3">How would you like to pay? <span class="text-danger">*</span></label>
              <div class="radio-group">
                <div class="radio-option">
                  <input type="radio" name="payment_mode" id="singlePayment" value="single" checked>
                  <label for="singlePayment">Pay Full Amount (₹{{ number_format($remainingBalance, 2) }})</label>
                </div>
                <div class="radio-option">
                  <input type="radio" name="payment_mode" id="installmentPayment" value="installment">
                  <label for="installmentPayment">Pay in Installments</label>
                </div>
                <div class="radio-option">
                  <input type="radio" name="payment_mode" id="customPayment" value="custom">
                  <label for="customPayment">Custom Amount</label>
                </div>
              </div>
            </div>

            <!-- Installment Cards -->
            <div id="installmentCards" style="display: none;">
              <div class="alert-custom">
                <i class="fas fa-info-circle"></i> <strong>Select an installment to pay:</strong>
                <p class="mb-0 mt-2 small">Original plan: 40%, 30%, 30%. You can pay any amount, and the remaining will adjust to future installments.</p>
              </div>

              <!-- Installment 1 (40%) -->
              <div class="installment-card {{ $adjustedInstallments['installment_1']['status'] }} selectable" 
                   data-installment="1" 
                   data-amount="{{ $adjustedInstallments['installment_1']['remaining'] }}">
                <div class="installment-header">
                  <div class="installment-title">
                    <i class="fas fa-calendar-check"></i> Installment 1 (40%)
                  </div>
                  <span class="installment-badge badge-{{ $adjustedInstallments['installment_1']['status'] }}">
                    {{ strtoupper($adjustedInstallments['installment_1']['status']) }}
                  </span>
                </div>
                <div class="installment-details">
                  <div class="detail-item">
                    <div class="detail-label">Original Amount</div>
                    <div class="detail-value">₹{{ number_format($adjustedInstallments['installment_1']['original'], 2) }}</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Paid</div>
                    <div class="detail-value text-success">₹{{ number_format($adjustedInstallments['installment_1']['paid'], 2) }}</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Remaining</div>
                    <div class="detail-value text-danger">₹{{ number_format($adjustedInstallments['installment_1']['remaining'], 2) }}</div>
                  </div>
                </div>
              </div>

              <!-- Installment 2 (30%) -->
              <div class="installment-card {{ $adjustedInstallments['installment_2']['status'] }} selectable" 
                   data-installment="2" 
                   data-amount="{{ $adjustedInstallments['installment_2']['remaining'] }}">
                <div class="installment-header">
                  <div class="installment-title">
                    <i class="fas fa-calendar-check"></i> Installment 2 (30%)
                  </div>
                  <span class="installment-badge badge-{{ $adjustedInstallments['installment_2']['status'] }}">
                    {{ strtoupper($adjustedInstallments['installment_2']['status']) }}
                  </span>
                </div>
                <div class="installment-details">
                  <div class="detail-item">
                    <div class="detail-label">Original Amount</div>
                    <div class="detail-value">₹{{ number_format($adjustedInstallments['installment_2']['original'], 2) }}</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Paid</div>
                    <div class="detail-value text-success">₹{{ number_format($adjustedInstallments['installment_2']['paid'], 2) }}</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Remaining</div>
                    <div class="detail-value text-danger">₹{{ number_format($adjustedInstallments['installment_2']['remaining'], 2) }}</div>
                  </div>
                </div>
              </div>

              <!-- Installment 3 (30%) - Final -->
              <div class="installment-card {{ $adjustedInstallments['installment_3']['status'] }} selectable" 
                   data-installment="3" 
                   data-amount="{{ $adjustedInstallments['installment_3']['remaining'] }}">
                <div class="installment-header">
                  <div class="installment-title">
                    <i class="fas fa-calendar-check"></i> Installment 3 (30%) - Final
                  </div>
                  <span class="installment-badge badge-{{ $adjustedInstallments['installment_3']['status'] }}">
                    {{ strtoupper($adjustedInstallments['installment_3']['status']) }}
                  </span>
                </div>
                <div class="installment-details">
                  <div class="detail-item">
                    <div class="detail-label">Original Amount</div>
                    <div class="detail-value">₹{{ number_format($adjustedInstallments['installment_3']['original'], 2) }}</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Paid</div>
                    <div class="detail-value text-success">₹{{ number_format($adjustedInstallments['installment_3']['paid'], 2) }}</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Remaining (Exact)</div>
                    <div class="detail-value text-danger">₹{{ number_format($adjustedInstallments['installment_3']['remaining'], 2) }}</div>
                  </div>
                </div>
              </div>

            <input type="hidden" name="installment_number" id="selectedInstallment" value="">
            </div>

            <!-- Custom Amount Section -->
            <div class="custom-amount-section" id="customAmountSection">
              <div class="alert-custom">
                <i class="fas fa-info-circle"></i> <strong>Custom Payment:</strong>
                <p class="mb-0 mt-2 small">Enter any amount you want to pay now. The remaining balance will be adjusted in future installments. The final (3rd) installment will always show the exact remaining balance.</p>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Enter Custom Amount</label>
                <input type="number" 
                       class="form-control" 
                       id="customAmountInput" 
                       min="1" 
                       max="{{ $remainingBalance }}" 
                       step="1"
                       placeholder="Enter amount (Max: ₹{{ number_format($remainingBalance, 2) }})">
                <small class="text-muted">You can pay any amount between ₹1 and ₹{{ number_format($remainingBalance, 2) }}</small>
              </div>
            </div>

            <!-- Payment Details -->
            <div class="form-row mt-4">
              <div class="form-group">
                <label>Payment Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                       name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
              </div>

              <div class="form-group">
                <label>Payment Type <span class="text-danger">*</span></label>
                <select class="form-control" name="payment_type" required>
                  <option value="">Select Payment Type</option>
                  <option value="cash">Cash</option>
                  <option value="online">Online</option>
                  <option value="cheque">Cheque</option>
                  <option value="card">Card</option>
                </select>
              </div>

              <div class="form-group">
                <label>Payment Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" 
                       name="payment_amount" id="paymentAmount" 
                       value="{{ old('payment_amount', $remainingBalance) }}" 
                       min="1" step="1" required readonly>
                <small class="text-muted">Remaining: ₹{{ number_format($remainingBalance, 2) }}</small>
              </div>

              <div class="form-group">
                <label>Other Charges</label>
                <input type="number" class="form-control" name="other_charges" id="otherCharges" 
                       value="0" min="0" step="1">
              </div>

              <div class="form-group">
                <label class="fw-bold">Total Amount to Pay</label>
                <input type="text" class="form-control form-control-lg text-danger fw-bold" 
                       id="totalAmountDisplay" readonly>
              </div>

              <div class="form-group">
                <label>Transaction ID / Reference</label>
                <input type="text" class="form-control" name="transaction_id">
              </div>

              <div class="form-group full-width">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks" rows="2"></textarea>
              </div>

              <div class="form-group full-width">
                <button type="submit" class="btn-pay">
                  <i class="fa-solid fa-check"></i> Pay Now
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('js/emp.js')}}"></script>
  
  <script>
    const remainingBalance = {{ $remainingBalance }};
    const adjustedInstallments = @json($adjustedInstallments);
    
    let selectedInstallmentNum = null;

    function updatePaymentMode() {
      const paymentMode = document.querySelector('input[name="payment_mode"]:checked').value;
      const installmentCards = document.getElementById('installmentCards');
      const customAmountSection = document.getElementById('customAmountSection');
      const paymentAmountField = document.getElementById('paymentAmount');
      const selectedInstallmentField = document.getElementById('selectedInstallment');
      
      // Reset selections
      document.querySelectorAll('.installment-card').forEach(card => {
        card.classList.remove('selected');
      });
      selectedInstallmentNum = null;
      selectedInstallmentField.value = '';
      
      if (paymentMode === 'single') {
        installmentCards.style.display = 'none';
        customAmountSection.classList.remove('active');
        paymentAmountField.value = Math.round(remainingBalance);
        paymentAmountField.readOnly = true;
      } else if (paymentMode === 'installment') {
        installmentCards.style.display = 'block';
        customAmountSection.classList.remove('active');
        paymentAmountField.value = 0;
        paymentAmountField.readOnly = true;
      } else if (paymentMode === 'custom') {
        installmentCards.style.display = 'none';
        customAmountSection.classList.add('active');
        paymentAmountField.value = Math.round(remainingBalance);
        paymentAmountField.readOnly = true;
      }
      
      calculateTotal();
    }

    // Handle installment card selection
    document.querySelectorAll('.installment-card.selectable').forEach(card => {
      card.addEventListener('click', function() {
        const installmentNum = this.dataset.installment;
        const amount = parseFloat(this.dataset.amount);
        
        // Skip if already paid
        if (this.classList.contains('paid')) {
          alert('This installment has already been paid.');
          return;
        }
        
        if (amount <= 0) {
          alert('This installment has no remaining amount to pay.');
          return;
        }
        
        // Deselect all cards
        document.querySelectorAll('.installment-card').forEach(c => {
          c.classList.remove('selected');
        });
        
        // Select this card
        this.classList.add('selected');
        selectedInstallmentNum = installmentNum;
        document.getElementById('selectedInstallment').value = installmentNum;
        document.getElementById('paymentAmount').value = Math.round(amount);
        
        calculateTotal();
      });
    });

    // Handle custom amount input
    document.getElementById('customAmountInput').addEventListener('input', function() {
      let value = parseFloat(this.value) || 0;
      
      // Validate
      if (value > remainingBalance) {
        value = remainingBalance;
        this.value = value;
      }
      if (value < 0) {
        value = 0;
        this.value = value;
      }
      
      document.getElementById('paymentAmount').value = Math.round(value);
      calculateTotal();
    });

    function calculateTotal() {
      const paymentAmount = parseFloat(document.getElementById('paymentAmount').value) || 0;
      const otherCharges = parseFloat(document.getElementById('otherCharges').value) || 0;
      
      const total = paymentAmount + otherCharges;
      
      document.getElementById('totalAmountDisplay').value = '₹' + total.toLocaleString('en-IN', {maximumFractionDigits: 0});
    }

    // Event listeners
    document.querySelectorAll('input[name="payment_mode"]').forEach(radio => {
      radio.addEventListener('change', updatePaymentMode);
    });

    document.getElementById('otherCharges').addEventListener('input', calculateTotal);

    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
  const paymentMode = document.querySelector('input[name="payment_mode"]:checked').value;
  const paymentType = document.querySelector('select[name="payment_type"]').value;
  const paymentAmount = parseFloat(document.getElementById('paymentAmount').value);
  const installmentNumber = document.getElementById('selectedInstallment').value;  // ✅ GET THIS
  
      if (!paymentType) {
        e.preventDefault();
        alert('Please select a payment type');
        return false;
      }
      
      if (paymentAmount <= 0) {
        e.preventDefault();
        alert('Payment amount must be greater than 0');
        return false;
      }
      
      if (paymentAmount > remainingBalance + 10000) {
        e.preventDefault();
        alert('Payment amount cannot exceed remaining balance significantly');
        return false;
      }
      
       if (paymentMode === 'installment' && !installmentNumber) {  // ✅ CHECK IT
    e.preventDefault();
    alert('Please select an installment to pay');
    return false;
  }
  
  // ✅ ADD THIS: Log for debugging
  console.log('Submitting payment:', {
    mode: paymentMode,
    installment: installmentNumber,
    amount: paymentAmount
  });
  
  return true;
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
      updatePaymentMode();
    });
  </script>
</body>
</html>