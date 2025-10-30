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
          <option>2024-2025</option>
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
              data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
              <i class="fa-solid fa-user-group" id="side-icon"></i>User Management
            </button>
          </h2>
          <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu">
                <li><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user"></i> Employee</a></li>
                <li><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group"></i> Batches Assignment</a></li>
              </ul>
            </div>
          </div>
        </div>
        
        <!-- Student Management -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#flush-collapseFour" aria-expanded="false">
              <i class="fa-solid fa-user-group" id="side-icon"></i>Student Management
            </button>
          </h2>
          <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
              <ul class="menu">
                <li><a class="item" href="{{ route('inquiries.index') }}"><i class="fa-solid fa-circle-info"></i> Inquiry Management</a></li>
                <li><a class="item" href="{{ route('student.student.pending') }}"><i class="fa-solid fa-user-check"></i> Student Onboard</a></li>
                <li><a class="item" href="{{ route('student.pendingfees.pending') }}"><i class="fa-solid fa-user-check"></i>Pending Fees Students</a></li>
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
              <div class="info-row">
                <span class="info-label">Contact</span>
                <span class="info-value">{{ $student->mobileNumber ?? '—' }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-row">
                <span class="info-label">Course Name</span>
                <span class="info-value">{{ $student->courseName ?? '—' }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Delivery Mode</span>
                <span class="info-value">{{ $student->deliveryMode ?? 'Offline' }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Batch Name</span>
                <span class="info-value">{{ $student->batchName ?? '—' }}</span>
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
                  <span class="info-value">₹<span id="displayTotalFees">{{ number_format($totalFees ?? 100000) }}</span></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-row">
                  <span class="info-label">GST (18%)</span>
                  <span class="info-value">₹<span id="displayGST">{{ number_format($gstAmount ?? 18000) }}</span></span>
                </div>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <div class="info-row">
                  <span class="info-label">Already Paid</span>
                  <span class="info-value" style="color: #28a745;">₹<span id="displayPaid">{{ number_format($totalPaid ?? 0) }}</span></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-row">
                  <span class="info-label">Remaining Balance</span>
                  <span class="info-value" style="color: #dc3545;">₹<span id="displayRemaining">{{ number_format($remainingBalance ?? 118000) }}</span></span>
                </div>
              </div>
            </div>

            <!-- Payment Type Selection -->
            <div class="mb-3">
              <label class="form-label fw-bold">Payment Type</label>
              <div class="payment-type-selector">
                <div class="payment-type-btn active" data-type="single" onclick="selectPaymentType('single')">
                  <input type="radio" name="payment_type" value="single" checked hidden>
                  <div class="text-center">
                    <i class="fa-solid fa-money-bill-wave fa-2x mb-2"></i>
                    <div class="fw-bold">Single Payment</div>
                    <small>Pay full amount</small>
                  </div>
                </div>
                <div class="payment-type-btn" data-type="installment" onclick="selectPaymentType('installment')">
                  <input type="radio" name="payment_type" value="installment" hidden>
                  <div class="text-center">
                    <i class="fa-solid fa-calendar-days fa-2x mb-2"></i>
                    <div class="fw-bold">In Installment</div>
                    <small>Pay 40% now (₹{{ number_format($firstInstallment ?? 47200) }})</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Add Other Charges Button -->
            <div class="mb-3">
              <button type="button" class="btn btn-warning" onclick="toggleOtherCharges()">
                <i class="fa-solid fa-plus"></i> Add Other Charges
              </button>
            </div>

            <!-- Other Charges Input -->
            <div class="row mb-3" id="otherChargesRow" style="display: none;">
              <div class="col-md-6">
                <label class="form-label">Other Charges Amount</label>
                <input type="number" class="form-control" name="other_charges" id="otherCharges" 
                       value="0" min="0" step="0.01" oninput="calculateTotal()">
              </div>
            </div>

            <!-- Total Amount Display -->
            <div class="info-row">
              <span class="info-label">Amount to Pay Now</span>
              <span class="total-amount">₹<span id="totalAmount">{{ number_format($remainingBalance ?? 118000) }}</span></span>
            </div>
          </div>

          <!-- Hidden fields -->
          <input type="hidden" name="total_fees" id="totalFeesInput" value="{{ $totalFees ?? 100000 }}">

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
                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                <select class="form-control @error('payment_method') is-invalid @enderror" 
                        name="payment_method" required>
                  <option value="">Select Payment Method</option>
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
                       value="{{ old('payment_amount', $remainingBalance ?? 118000) }}" 
                       min="1" step="0.01" required>
                @error('payment_amount')
                  <div class="error-message">{{ $message }}</div>
                @enderror
                <small class="text-muted">Enter the amount being paid now</small>
              </div>

              <div class="col-md-6">
                <label class="form-label">Transaction ID / Reference</label>
                <input type="text" class="form-control" name="transaction_id" 
                       value="{{ old('transaction_id') }}">
                <small class="text-muted">For online payments</small>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-12">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" name="remarks" rows="2">{{ old('remarks') }}</textarea>
              </div>
            </div>

            <div class="d-flex gap-3">
              <button type="submit" class="btn btn-success btn-lg">
                <i class="fa-solid fa-check"></i> Process Payment
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
    const baseFees = {{ $totalFees ?? 100000 }};
    const gstAmount = {{ $gstAmount ?? 18000 }};
    const totalWithGst = {{ $totalFeesWithGST ?? 118000 }};
    const firstInstallment = {{ $firstInstallment ?? 47200 }};
    const alreadyPaid = {{ $totalPaid ?? 0 }};
    const remainingBalance = {{ $remainingBalance ?? 118000 }};

    function selectPaymentType(type) {
      document.querySelectorAll('.payment-type-btn').forEach(btn => {
        btn.classList.remove('active');
      });
      document.querySelector(`[data-type="${type}"]`).classList.add('active');
      document.querySelector(`input[value="${type}"]`).checked = true;
      
      if (type === 'single') {
        document.getElementById('paymentAmount').value = remainingBalance.toFixed(2);
      } else {
        const installmentAmount = Math.min(firstInstallment, remainingBalance);
        document.getElementById('paymentAmount').value = installmentAmount.toFixed(2);
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
        baseAmount = remainingBalance;
      } else {
        baseAmount = Math.min(firstInstallment, remainingBalance);
      }
      
      const total = baseAmount + otherCharges;
      
      document.getElementById('totalAmount').textContent = total.toFixed(2);
      
      const paymentAmountField = document.getElementById('paymentAmount');
      paymentAmountField.value = total.toFixed(2);
    }

    document.getElementById('paymentForm').addEventListener('submit', function(e) {
      const paymentMethod = document.querySelector('select[name="payment_method"]').value;
      const paymentAmount = parseFloat(document.getElementById('paymentAmount').value);
      const totalAmount = parseFloat(document.getElementById('totalAmount').textContent.replace(/,/g, ''));
      
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
      
      if (paymentAmount > remainingBalance + 1000) {
        e.preventDefault();
        alert('Payment amount cannot exceed remaining balance significantly');
        return false;
      }
      
      return true;
    });

    document.addEventListener('DOMContentLoaded', function() {
      calculateTotal();
    });
  </script>
</body>
</html>