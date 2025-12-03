<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scholarship Details</title>
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
      padding: 30px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .form-section h4 {
      color: #ff6b35;
      margin-bottom: 25px;
      padding-bottom: 12px;
      border-bottom: 2px solid #ff6b35;
      font-weight: 600;
    }
    
    .detail-row {
      display: grid;
      grid-template-columns: 300px 1fr;
      gap: 20px;
      padding: 15px 0;
      border-bottom: 1px solid #f0f0f0;
    }
    
    .detail-row:last-child {
      border-bottom: none;
    }
    
    .detail-label {
      font-weight: 600;
      color: #333;
    }
    
    .detail-value {
      color: #666;
    }
    
    .detail-value.highlight {
      color: #ff6b35;
      font-weight: 600;
      font-size: 1.1em;
    }
    
    .radio-group {
      display: flex;
      gap: 30px;
      margin-top: 10px;
    }
    
    .radio-group label {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      font-weight: normal;
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
      z-index: 100;
      display: flex;
      justify-content: flex-end;
    }
    
    .btn-next {
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
    
    .btn-next:hover {
      background: #e55a2b;
      transform: translateY(-2px);
    }
    
    .btn-next:disabled {
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
      border: 1px solid #ff6b35;
      padding: 8px 16px;
      border-radius: 6px;
      transition: all 0.3s;
    }
    
    .back-btn:hover {
      background: #ff6b35;
      color: white;
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
    
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    
    .page-title {
      color: #ff6b35;
      font-size: 24px;
      font-weight: 600;
      margin: 0;
    }

    .discount-input-section {
      display: none;
      margin-top: 20px;
      padding: 20px;
      background: #f8f9fa;
      border-radius: 6px;
    }

    .discount-input-section.active {
      display: block;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
      display: block;
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
    <!-- Sidebar (same as edit page) -->
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
        <!-- Page Header -->
        <div class="page-header"> 
          <a href="{{ route('inquiries.edit', $inquiry->_id) }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Back
          </a>
          <h3 class="page-title">Update Inquiry</h3>
        </div>

        @if(session('success'))
          <div class="alert alert-success">
            <i class="fa-solid fa-check-circle"></i>
            <span>{{ session('success') }}</span>
          </div>
        @endif
<form method="POST" action="{{ route('inquiries.scholarship.update', $inquiry->_id) }}" id="scholarshipForm">
    @csrf
    @method('PUT')

    <!-- Scholarship Details Section -->
    <div class="form-section">
        <h4>Scholarship Details</h4>
        
        <div class="detail-row">
            <div class="detail-label">Eligible For Scholarship</div>
            <div class="detail-value">
                @if($eligibleForScholarship)
                    <span style="color: #28a745; font-weight: 600;">Yes</span>
                @else
                    <span style="color: #dc3545; font-weight: 600;">No</span>
                @endif
            </div>
        </div>

        @if($eligibleForScholarship && $scholarship)
            <div class="detail-row">
                <div class="detail-label">Name of Scholarship</div>
                <div class="detail-value">{{ $scholarship->scholarship_name ?? 'N/A' }}</div>
            </div>
        @endif

        <div class="detail-row">
            <div class="detail-label">Total Fee Before Discount</div>
            <div class="detail-value highlight">₹{{ number_format($totalFeeBeforeDiscount, 2) }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Discount Percentage</div>
            <div class="detail-value highlight" id="discountPercentageDisplay">{{ $discountPercentage }}%</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Discounted Fees</div>
            <div class="detail-value highlight" id="discountedFeesDisplay">₹{{ number_format($scholarshipDiscountedFees, 2) }}</div>
        </div>
    </div>

    <!-- Discretionary Discount Section -->
    <div class="form-section">
        <h4>Discretionary Discount</h4>
        
       <div class="form-group">
            <label>Do You Want Add discretionary discount</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="add_discretionary_discount" value="Yes" id="discountYes" 
                           {{ $inquiry->discretionary_discount === 'Yes' ? 'checked' : '' }}>
                    Yes
                </label>
                <label>
                    <input type="radio" name="add_discretionary_discount" value="No" id="discountNo" 
                           {{ $inquiry->discretionary_discount !== 'Yes' ? 'checked' : '' }}>
                    No
                </label>
            </div>
        </div>

        <!--   CRITICAL: Ensure these fields have correct names matching validation -->
        <div class="discount-input-section {{ $inquiry->discretionary_discount === 'Yes' ? 'active' : '' }}" id="discountInputSection">
            <div class="form-group">
                <label>Discount Type</label>
                <select name="discretionary_discount_type" class="form-select" id="discountType">
                    <option value="percentage" {{ $inquiry->discretionary_discount_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="fixed" {{ $inquiry->discretionary_discount_type === 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Discount Value</label>
                <input type="number" 
                       name="discretionary_discount_value" 
                       class="form-control" 
                       id="discountValue" 
                       min="0" 
                       step="0.01"
                       value="{{ $inquiry->discretionary_discount_value ?? '' }}"
                       placeholder="Enter discount value">
            </div>

            <div class="form-group">
                <label>Reason for Discretionary Discount</label>
                <textarea name="discretionary_discount_reason" 
                          class="form-control" 
                          rows="3" 
                          id="discountReason"
                          placeholder="Please provide a reason for this discount">{{ $inquiry->discretionary_discount_reason ?? '' }}</textarea>
            </div>

            <div class="detail-row" style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-top: 15px;">
                <div class="detail-label">Final Fees After All Discounts</div>
                <div class="detail-value highlight" style="font-size: 1.3em;" id="finalFeesDisplay">
                    ₹{{ number_format($finalFees, 2) }}
                </div>
            </div>
        </div>

   <!-- Hidden fields with correct names -->
        <input type="hidden" name="total_fee_before_discount" value="{{ $totalFeeBeforeDiscount }}">
        <input type="hidden" name="scholarship_discount_percentage" value="{{ $discountPercentage }}">
        <input type="hidden" name="scholarship_discounted_fees" value="{{ $scholarshipDiscountedFees }}">
        <input type="hidden" name="final_fees" id="finalFeesInput" value="{{ $finalFees }}">
      </div>

    <!-- Footer -->
    <div class="sticky-footer">
        <button type="submit" class="btn-next">
             Save & Continue <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
</form>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('js/emp.js')}}"></script>
  
  <script>
document.addEventListener('DOMContentLoaded', function() {
  const discountYes = document.getElementById('discountYes');
  const discountNo = document.getElementById('discountNo');
  const discountInputSection = document.getElementById('discountInputSection');
  const discountType = document.getElementById('discountType');
  const discountValue = document.getElementById('discountValue');
  const discountReason = document.getElementById('discountReason');
  const finalFeesDisplay = document.getElementById('finalFeesDisplay');
  const finalFeesInput = document.getElementById('finalFeesInput');
  const scholarshipForm = document.querySelector('form');

  const scholarshipDiscountedFees = {{ $scholarshipDiscountedFees }};
  const savedFinalFees = {{ $finalFees }};

  // 🔥 FIX: Initialize with saved values on page load
  console.log('🔄 Initializing with saved data:', {
    scholarshipDiscountedFees: scholarshipDiscountedFees,
    savedFinalFees: savedFinalFees,
    discretionary_discount: '{{ $inquiry->discretionary_discount }}',
    has_saved_values: {{ $inquiry->discretionary_discount === 'Yes' ? 'true' : 'false' }}
  });

  // Set initial final fees display
  finalFeesDisplay.textContent = '₹' + savedFinalFees.toLocaleString('en-IN', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
  finalFeesInput.value = savedFinalFees.toFixed(2);

  // Toggle discount section
  discountYes.addEventListener('change', function() {
    if (this.checked) {
      discountInputSection.classList.add('active');
      discountType.setAttribute('required', 'required');
      discountValue.setAttribute('required', 'required');
      discountReason.setAttribute('required', 'required');
      
      // Recalculate if values exist
      if (discountValue.value) {
        calculateFinalFees();
      }
    }
  });

  discountNo.addEventListener('change', function() {
    if (this.checked) {
      discountInputSection.classList.remove('active');
      discountType.removeAttribute('required');
      discountValue.removeAttribute('required');
      discountReason.removeAttribute('required');
      resetDiscountCalculation();
    }
  });

  // Calculate final fees
  function calculateFinalFees() {
    if (!discountYes.checked) return;

    const type = discountType.value;
    const value = parseFloat(discountValue.value) || 0;

    let finalFees = scholarshipDiscountedFees;

    if (value > 0) {
      if (type === 'percentage') {
        const additionalDiscount = (scholarshipDiscountedFees * value) / 100;
        finalFees = scholarshipDiscountedFees - additionalDiscount;
        console.log('💰 Percentage discount:', {
          base: scholarshipDiscountedFees,
          percentage: value,
          discount_amount: additionalDiscount,
          final: finalFees
        });
      } else if (type === 'fixed') {
        finalFees = scholarshipDiscountedFees - value;
        console.log('💰 Fixed discount:', {
          base: scholarshipDiscountedFees,
          discount_amount: value,
          final: finalFees
        });
      }
    }

    finalFees = Math.max(0, finalFees);

    finalFeesDisplay.textContent = '₹' + finalFees.toLocaleString('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
    finalFeesInput.value = finalFees.toFixed(2);

    console.log('✅ Final fees calculated:', finalFees);
  }

  function resetDiscountCalculation() {
    finalFeesDisplay.textContent = '₹' + scholarshipDiscountedFees.toLocaleString('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
    finalFeesInput.value = scholarshipDiscountedFees.toFixed(2);
  }

  discountType.addEventListener('change', calculateFinalFees);
  discountValue.addEventListener('input', calculateFinalFees);

  // Form validation before submit
  scholarshipForm.addEventListener('submit', function(e) {
    console.log('📝 Form submitting...');
    
    if (discountYes.checked) {
      const value = parseFloat(discountValue.value) || 0;
      const reason = discountReason.value.trim();

      if (value > 0 && !reason) {
        e.preventDefault();
        alert('⚠️ Please provide a reason for the discretionary discount.');
        discountReason.focus();
        return false;
      }

      if (discountType.value === 'percentage' && value > 100) {
        e.preventDefault();
        alert('⚠️ Discount percentage cannot exceed 100%.');
        discountValue.focus();
        return false;
      }

      if (discountType.value === 'fixed' && value > scholarshipDiscountedFees) {
        e.preventDefault();
        alert('⚠️ Discount amount cannot exceed the discounted fees.');
        discountValue.focus();
        return false;
      }

      console.log('✅ Discretionary discount validated:', {
        type: discountType.value,
        value: value,
        reason: reason
      });
    }

    console.log('✅ Form validation passed - submitting...');
    return true;
  });
});
  </script>

</body>
</html>