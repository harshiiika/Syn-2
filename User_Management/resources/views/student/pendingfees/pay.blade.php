<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pay Fees - {{ $student->name }}</title>
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
    }
    .radio-option {
      display: flex;
      align-items: center;
      gap: 8px;
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
  </style>
</head>
<body>
  <!-- Header -->
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

      <!-- Left side bar accordion -->
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
          <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
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
                <label>Course Type</label>
                <input type="text" class="form-control" value="{{ $student->courseType ?? 'Pre-Medical' }}" readonly>
              </div>
              <div class="form-group">
                <label>Course Name</label>
                <input type="text" class="form-control" value="{{ $student->courseName ?? '—' }}" readonly>
              </div>
              <div class="form-group">
                <label>Course Content</label>
                <input type="text" class="form-control" value="{{ $student->courseContent ?? 'Class room course' }}" readonly>
              </div>
              <div class="form-group">
                <label>Batch Name</label>
                <input type="text" class="form-control" value="{{ $student->batchName ?? '—' }}" readonly>
              </div>
              <div class="form-group">
                <label>Batch Start Date</label>
                <input type="text" class="form-control" value="{{ $student->batchStartDate ? date('d-m-Y', strtotime($student->batchStartDate)) : '—' }}" readonly>
              </div>
              <div class="form-group">
                <label>Delivery Mode</label>
                <input type="text" class="form-control" value="{{ $student->deliveryMode ?? 'Offline' }}" readonly>
              </div>
            </div>
          </div>

          <!-- Fee Details -->
          <div class="view-section">
            <h4>Fee Details</h4>
            <div class="form-row">
              <div class="form-group">
                <label>Total Fees (Before GST)</label>
                <input type="text" class="form-control" value="₹{{ number_format($totalFees, 0) }}" readonly id="totalFeesDisplay">
                <input type="hidden" name="total_fees" value="{{ $totalFees }}">
              </div>
              <div class="form-group">
                <label>GST (18%)</label>
                <input type="text" class="form-control" value="₹{{ number_format($gstAmount, 0) }}" readonly>
              </div>
              <div class="form-group">
                <label>Already Paid</label>
                <input type="text" class="form-control" value="₹{{ number_format($totalPaid, 0) }}" readonly style="color: #28a745; font-weight: 600;">
              </div>
              <div class="form-group">
                <label>Remaining Balance</label>
                <input type="text" class="form-control" value="₹{{ number_format($remainingBalance, 0) }}" readonly style="color: #dc3545; font-weight: 600;">
              </div>
              
              <div class="form-group full-width">
                <label>Do you want to pay fees</label>
                <div class="radio-group">
                  <div class="radio-option">
                    <input type="radio" id="singlePayment" name="do_you_want_to_pay_fees" value="single_payment" checked onchange="updatePaymentAmount()">
                    <label for="singlePayment">Single Payment</label>
                  </div>
                  <div class="radio-option">
                    <input type="radio" id="inInstallment" name="do_you_want_to_pay_fees" value="in_installment" onchange="updatePaymentAmount()">
                    <label for="inInstallment">In installment</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Other Charges Amount</label>
                <input type="number" class="form-control" name="other_charges" id="otherCharges" value="0" min="0" step="1" oninput="calculateTotal()">
              </div>

              <div class="form-group">
                <label>Total Amount</label>
                <input type="text" class="form-control" id="totalAmountDisplay" value="₹{{ number_format($remainingBalance, 0) }}" readonly style="color: #ff6b35; font-weight: 600; font-size: 1.1rem;">
              </div>
            </div>
          </div>

          <!-- Payment Details -->
          <div class="view-section">
            <h4>Payment Details</h4>
            <div class="form-row">
              <div class="form-group">
                <label>Payment Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                       name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                @error('payment_date')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label>Payment Type <span class="text-danger">*</span></label>
                <select class="form-control @error('payment_type') is-invalid @enderror" name="payment_type" required>
                  <option value="">Select Payment Type</option>
                  <option value="cash">Cash</option>
                  <option value="online">Online</option>
                  <option value="cheque">Cheque</option>
                  <option value="card">Card</option>
                </select>
                @error('payment_type')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label>Payment Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('payment_amount') is-invalid @enderror" 
                       name="payment_amount" id="paymentAmount" 
                       value="{{ old('payment_amount', $remainingBalance) }}" 
                       min="1" step="1" required readonly>
                @error('payment_amount')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label>Transaction ID / Reference</label>
                <input type="text" class="form-control" name="transaction_id" value="{{ old('transaction_id') }}">
              </div>

              <div class="form-group full-width">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks" rows="2">{{ old('remarks') }}</textarea>
              </div>

              <div class="form-group full-width">
                <button type="submit" class="btn-pay">
                  <i class="fa-solid fa-check"></i> Pay
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
    const totalFees = {{ $totalFees }};
    const gstAmount = {{ $gstAmount }};
    const totalFeesWithGST = {{ $totalFeesWithGST }};
    const totalPaid = {{ $totalPaid }};
    const remainingBalance = {{ $remainingBalance }};
    const firstInstallment = {{ $firstInstallment }};

    function updatePaymentAmount() {
      const paymentType = document.querySelector('input[name="do_you_want_to_pay_fees"]:checked').value;
      
      let baseAmount;
      if (paymentType === 'single_payment') {
        baseAmount = remainingBalance;
      } else {
        baseAmount = Math.min(firstInstallment, remainingBalance);
      }
      
      document.getElementById('paymentAmount').value = Math.round(baseAmount);
      calculateTotal();
    }

    function calculateTotal() {
      const paymentAmount = parseFloat(document.getElementById('paymentAmount').value) || 0;
      const otherCharges = parseFloat(document.getElementById('otherCharges').value) || 0;
      
      const total = paymentAmount + otherCharges;
      
      document.getElementById('totalAmountDisplay').value = '₹' + total.toLocaleString('en-IN', {maximumFractionDigits: 0});
    }

    document.getElementById('paymentForm').addEventListener('submit', function(e) {
      const paymentType = document.querySelector('select[name="payment_type"]').value;
      const paymentAmount = parseFloat(document.getElementById('paymentAmount').value);
      
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
      
      return true;
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
      updatePaymentAmount();
    });
  </script>
</body>
</html>