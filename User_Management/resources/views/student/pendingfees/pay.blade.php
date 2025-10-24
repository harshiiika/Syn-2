<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pay Fees</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .payment-form {
      max-width: 900px;
      margin: 20px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .billing-info, .fee-details {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      padding: 8px 0;
      border-bottom: 1px solid #dee2e6;
    }
    .info-label {
      font-weight: 600;
      color: #495057;
    }
    .info-value {
      color: #212529;
    }
    .total-amount {
      font-size: 1.5rem;
      font-weight: bold;
      color: #28a745;
    }
    .form-section {
      margin-top: 30px;
    }
    .payment-type-selector {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }
    .payment-type-btn {
      flex: 1;
      padding: 15px;
      border: 2px solid #dee2e6;
      border-radius: 8px;
      background: white;
      cursor: pointer;
      transition: all 0.3s;
    }
    .payment-type-btn.active {
      border-color: #007bff;
      background: #e7f3ff;
    }
    .payment-type-btn:hover {
      border-color: #007bff;
    }
    .error-message {
      color: #dc3545;
      font-size: 0.875rem;
      margin-top: 5px;
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
          <option>2025-2026</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown">
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
    <!-- Sidebar (same as other pages) -->
    <div class="left" id="sidebar">

      <div class="text" id="text">
        <h6>ADMIN</h6>
        <p>synthesisbikaner@gmail.com</p>
      </div>

      <!-- Left side bar accordian -->
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
                <li><a class="item" href="{{ route('master.scholarship.index') }}"><i class="fa-solid fa-graduation-cap"
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

    <!-- Main Content -->
    <div class="right" id="right">
      <div class="payment-form">
        <h2 class="mb-4">
          <i class="fa-solid fa-credit-card"></i> Fee Payment
          <a href="{{ route('student.pendingfees.pending') }}" class="btn btn-secondary btn-sm float-end">
            <i class="fa-solid fa-arrow-left"></i> Back
          </a>
        </h2>

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

        <!-- Billing Information -->
        <div class="billing-info">
          <h4 class="mb-3">Billing Information</h4>
          <div class="row">
            <div class="col-md-6">
              <div class="info-row">
                <span class="info-label">Student Name</span>
                <span class="info-value">{{ $student->name }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Father Name</span>
                <span class="info-value">{{ $student->father ?? '—' }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-row">
                <span class="info-label">Course Name</span>
                <span class="info-value">{{ $student->courseName ?? '—' }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Course Type</span>
                <span class="info-value">{{ $student->courseType ?? 'Pre-Medical' }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-row">
                <span class="info-label">Course Content</span>
                <span class="info-value">{{ $student->courseContent ?? 'Class room course' }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Batch Name</span>
                <span class="info-value">{{ $student->batchName ?? '—' }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-row">
                <span class="info-label">Batch Start Date</span>
                <span class="info-value">{{ $student->batchStartDate ?? '2025-06-11' }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Delivery Mode</span>
                <span class="info-value">{{ $student->deliveryMode ?? 'Offline' }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Form -->
        <form action="{{ route('student.payment.process', $student->_id) }}" method="POST" id="paymentForm">
          @csrf

          <!-- Fee Details -->
          <div class="fee-details">
            <h4 class="mb-3">Fee Details</h4>
            
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="info-row">
                  <span class="info-label">Total Fees (Before GST)</span>
                  <span class="info-value">₹<span id="displayTotalFees">100000</span></span>
                </div>
              </div>
            </div>

            <!-- Payment Type Selection -->
            <div class="mb-3">
              <label class="form-label fw-bold">Do you want to pay fees</label>
              <div class="payment-type-selector">
                <div class="payment-type-btn active" data-type="single" onclick="selectPaymentType('single')">
                  <input type="radio" name="payment_type" value="single" checked hidden>
                  <div class="text-center">
                    <i class="fa-solid fa-money-bill-wave fa-2x mb-2"></i>
                    <div class="fw-bold">Single Payment</div>
                  </div>
                </div>
                <div class="payment-type-btn" data-type="installment" onclick="selectPaymentType('installment')">
                  <input type="radio" name="payment_type" value="installment" hidden>
                  <div class="text-center">
                    <i class="fa-solid fa-calendar-days fa-2x mb-2"></i>
                    <div class="fw-bold">In Installment</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Installment Amount (shown when installment selected) -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">First Installment (With GST)</label>
                <input type="number" class="form-control" id="installmentAmount" value="47200" readonly>
              </div>
            </div>

            <!-- Add Other Charges Button -->
            <div class="mb-3">
              <button type="button" class="btn btn-warning" onclick="toggleOtherCharges()">
                <i class="fa-solid fa-plus"></i> Add Other Charges
              </button>
            </div>

            <!-- Other Charges Input (hidden by default) -->
            <div class="row mb-3" id="otherChargesRow" style="display: none;">
              <div class="col-md-6">
                <label class="form-label">Other Charges Amount</label>
                <input type="number" class="form-control" name="other_charges" id="otherCharges" 
                       value="0" min="0" step="0.01" oninput="calculateTotal()">
              </div>
            </div>

            <!-- Total Amount Display -->
            <div class="info-row">
              <span class="info-label">Total Amount</span>
              <span class="total-amount">₹<span id="totalAmount">47200</span></span>
            </div>
          </div>

          <!-- Hidden field for total fees -->
          <input type="hidden" name="total_fees" id="totalFeesInput" value="100000">

          <!-- Payment Details -->
          <div class="form-section">
            <h4 class="mb-3">Payment Details</h4>
            
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                       name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                @error('payment_date')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="col-md-6">
                <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                <select class="form-control @error('payment_method') is-invalid @enderror" 
                        name="payment_method" required>
                  <option value="">Select Payment Type</option>
                  <option value="cash">Cash</option>
                  <option value="online">Online Transfer</option>
                  <option value="cheque">Cheque</option>
                  <option value="card">Card</option>
                </select>
                @error('payment_method')
                  <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('payment_amount') is-invalid @enderror" 
                       name="payment_amount" id="paymentAmount" 
                       value="{{ old('payment_amount') }}" 
                       min="1" step="0.01" required>
                @error('payment_amount')
                  <div class="error-message">{{ $message }}</div>
                @enderror
                <small class="text-muted">Enter the amount being paid now</small>
              </div>
            </div>

            <div class="d-flex gap-3">
              <button type="submit" class="btn btn-success btn-lg">
                <i class="fa-solid fa-check"></i> Pay
              </button>
              <a href="{{ route('student.pendingfees.pending') }}" class="btn btn-secondary btn-lg">
                <i class="fa-solid fa-times"></i> Cancel
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('js/emp.js')}}"></script>
  
  <script>
    let otherChargesVisible = false;
    const baseFees = 100000;
    const gstRate = 0.18;
    const firstInstallment = 47200;

    function selectPaymentType(type) {
      // Update UI
      document.querySelectorAll('.payment-type-btn').forEach(btn => {
        btn.classList.remove('active');
      });
      document.querySelector(`[data-type="${type}"]`).classList.add('active');
      
      // Update radio button
      document.querySelector(`input[value="${type}"]`).checked = true;
      
      // Update payment amount field
      if (type === 'single') {
        const totalWithGst = baseFees * (1 + gstRate);
        document.getElementById('paymentAmount').value = totalWithGst.toFixed(2);
      } else {
        document.getElementById('paymentAmount').value = firstInstallment;
      }
      
      calculateTotal();
    }

    function toggleOtherCharges() {
      otherChargesVisible = !otherChargesVisible;
      const row = document.getElementById('otherChargesRow');
      
      if (otherChargesVisible) {
        row.style.display = 'block';
      } else {
        row.style.display = 'none';
        document.getElementById('otherCharges').value = 0;
      }
      
      calculateTotal();
    }

    function calculateTotal() {
      const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
      const otherCharges = parseFloat(document.getElementById('otherCharges').value) || 0;
      
      let baseAmount;
      if (paymentType === 'single') {
        baseAmount = baseFees * (1 + gstRate);
      } else {
        baseAmount = firstInstallment;
      }
      
      const total = baseAmount + otherCharges;
      
      document.getElementById('totalAmount').textContent = total.toFixed(2);
      document.getElementById('displayTotalFees').textContent = baseFees.toLocaleString();
      
      // Update payment amount if not manually changed
      const paymentAmountField = document.getElementById('paymentAmount');
      if (!paymentAmountField.value || paymentAmountField.value == baseAmount.toFixed(2)) {
        paymentAmountField.value = total.toFixed(2);
      }
    }

    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
      const paymentMethod = document.querySelector('select[name="payment_method"]').value;
      const paymentAmount = parseFloat(document.getElementById('paymentAmount').value);
      const totalAmount = parseFloat(document.getElementById('totalAmount').textContent);
      
      if (!paymentMethod) {
        e.preventDefault();
        alert('Please select a payment method');
        return false;
      }
      
      if (paymentAmount <= 0) {
        e.preventDefault();
        alert('Payment amount must be greater than 0');
        return false;
      }
      
      if (paymentAmount > totalAmount) {
        e.preventDefault();
        alert('Payment amount cannot exceed total amount');
        return false;
      }
      
      return true;
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
      calculateTotal();
    });
  </script>
</body>
</html>