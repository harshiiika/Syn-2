<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>View Inquiry</title>

  <!-- Icons + Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />

  <!-- Reuse same stylesheet as create/edit page -->
  <link rel="stylesheet" href="{{ asset('css/createinq.css') }}" />

  <style>
    /* small helpers to make read-only look correct */
    .form-control[readonly], .form-select[disabled] {
      background-color: #fff;
      opacity: 1;
      cursor: default;
    }
    .radio-row {
      display: flex; gap: 24px; align-items: center;
    }
    .section-title { color: #e86d1e; font-weight: 700; }
    .kv { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; }
    .kv .label { color: #7a7a7a; }
    .kv .value { font-weight: 500; }
  </style>
</head>

<body>
  <!-- Top bar -->
  <div class="top">
    <div class="header">
      <img src="https://synthesisbikaner.org/synthesistest/assets/logo-big.png" class="logo" />
      <i class="fa-solid fa-bars" id="toggleBtn"></i>
    </div>

    <div class="session">
      <label>Session:</label>
      <select class="select">
        <option>2025-2026</option>
        <option>2024-2025</option>
      </select>

      <i class="fa-solid fa-bell" style="color: rgb(233, 96, 47); font-size: 22px"></i>

      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-solid fa-user" style="color: rgb(233, 96, 47); font-size: 22px"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a href="#" class="dropdown-item"><i class="fa-solid fa-user" style="color: rgb(233, 96, 47); font-size: 15px"></i> Profile</a></li>
          <li><a href="#" class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket" style="color: rgb(233, 96, 47); font-size: 15px"></i> Log In</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Main layout -->
  <div class="main-container">
    <!-- LEFT: full accordion -->
    <div class="left" id="sidebar">
      <div class="admin" id="admin">
        <h2>Admin</h2>
        <h4>synthesisbikaner@gmail.com</h4>
      </div>

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
                <li><a class="item {{ request()->routeIs('calendar.index') ? 'active' : '' }}" 
                  href="{{ route('calendar.index') }}"><i class="fa-solid fa-calendar-days"
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
                    <i class="fa-solid fa-user-check" id="side-icon"></i>Student Onboard</a>
                </li>
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
      </div><!-- /accordion -->
    </div> <!-- /left -->

    <!-- RIGHT: content -->
   <div class="right" id="right">
  {{-- Top line: title on left, Back on right --}}
  <div class="page-header">
    <h1 class="page-title m-0">view Inquiry</h1>
    <a href="{{ route('inquiries.index') }}" class="btn-back-link">
      <i class="fa-solid fa-angle-left me-1"></i> Back
    </a>
  </div>

      <!-- BASIC DETAILS -->
      <div class="card">
        <div class="card-header border-0">
          <h2 class="section-title m-0">Basic Details</h2>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Student Name</label>
              <input class="form-control" value="{{ $inquiry->student_name }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Father Name</label>
              <input class="form-control" value="{{ $inquiry->father_name }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label">Father Contact No</label>
              <input class="form-control" value="{{ $inquiry->father_contact }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Father WhatsApp No</label>
              <input class="form-control" value="{{ $inquiry->father_whatsapp ?? '—' }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label">Student Contact No</label>
              <input class="form-control" value="{{ $inquiry->student_contact ?? '—' }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label d-block">Category</label>
              <div class="radio-row">
                <label><input type="radio" disabled {{ $inquiry->category==='OBC'?'checked':'' }}> OBC</label>
                <label><input type="radio" disabled {{ $inquiry->category==='GENERAL'?'checked':'' }}> GENERAL</label>
                <label><input type="radio" disabled {{ $inquiry->category==='SC'?'checked':'' }}> SC</label>
                <label><input type="radio" disabled {{ $inquiry->category==='ST'?'checked':'' }}> ST</label>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">State</label>
              <input class="form-control" value="{{ $inquiry->state }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">City</label>
              <input class="form-control" value="{{ $inquiry->city }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label">Address Name</label>
              <textarea class="form-control" rows="2" readonly>{{ $inquiry->address ?? '—' }}</textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Branch Name</label>
              <input class="form-control" value="{{ $inquiry->branch_name ?? '—' }}" readonly>
            </div>

            <div class="col-12">
              <div class="radio-row justify-content-between">
                <span>Do You Belong to Economic Weaker Section ?</span>
                <span>
                  <label class="me-3"><input type="radio" disabled {{ ($inquiry->ews ?? 'no')==='yes'?'checked':'' }}> Yes</label>
                  <label><input type="radio" disabled {{ ($inquiry->ews ?? 'no')==='no'?'checked':'' }}> No</label>
                </span>
              </div>
            </div>

            <div class="col-12">
              <div class="radio-row justify-content-between">
                <span>Do You Belong to Any Army/Police/Martyr Background?</span>
                <span>
                  <label class="me-3"><input type="radio" disabled {{ ($inquiry->service_background ?? 'no')==='yes'?'checked':'' }}> Yes</label>
                  <label><input type="radio" disabled {{ ($inquiry->service_background ?? 'no')==='no'?'checked':'' }}> No</label>
                </span>
              </div>
            </div>

            <div class="col-12">
              <div class="radio-row justify-content-between">
                <span>Are You a Specially Abled ?</span>
                <span>
                  <label class="me-3"><input type="radio" disabled {{ ($inquiry->specially_abled ?? 'no')==='yes'?'checked':'' }}> Yes</label>
                  <label><input type="radio" disabled {{ ($inquiry->specially_abled ?? 'no')==='no'?'checked':'' }}> No</label>
                </span>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- COURSE DETAILS -->
      <div class="card mt-4">
        <div class="card-header border-0">
          <h2 class="section-title m-0">Course Details</h2>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Course Type</label>
              <input class="form-control" value="{{ $inquiry->course_type ?? '—' }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Course Name</label>
              <input class="form-control" value="{{ $inquiry->course_name ?? '—' }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label">Delivery Mode</label>
              <input class="form-control" value="{{ $inquiry->delivery_mode ?? '—' }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Medium</label>
              <input class="form-control" value="{{ $inquiry->medium ?? '—' }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label">Board</label>
              <input class="form-control" value="{{ $inquiry->board ?? '—' }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Course Content</label>
              <input class="form-control" value="{{ $inquiry->course_content ?? '—' }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label">Status</label>
              <input class="form-control" value="{{ $inquiry->status ?? 'Pending' }}" readonly>
            </div>
          </div>
        </div>
      </div>

      <!-- SCHOLARSHIP ELIGIBILITY -->
      <div class="card mt-4">
        <div class="card-header border-0">
          <h2 class="section-title m-0">Scholarship Eligibility</h2>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12">
              <div class="radio-row justify-content-between">
                <span>Have You Appeared for the Synthesis Scholarship test?</span>
                <span>
                  @php $appeared = $inquiry->scholarship_test_appeared ?? 'no'; @endphp
                  <label class="me-3"><input type="radio" disabled {{ $appeared==='yes'?'checked':'' }}> Yes</label>
                  <label><input type="radio" disabled {{ $appeared==='no'?'checked':'' }}> No</label>
                </span>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Percentage Of Marks In Last Board Exam</label>
              <input class="form-control" value="{{ $inquiry->last_board_percentage ?? '—' }}" readonly>
            </div>

            <div class="col-12">
              <div class="radio-row justify-content-between">
                <span>Have You Appeared For any of the competition exam?</span>
                <span>
                  @php $comp = $inquiry->competition_exam_appeared ?? 'no'; @endphp
                  <label class="me-3"><input type="radio" disabled {{ $comp==='yes'?'checked':'' }}> Yes</label>
                  <label><input type="radio" disabled {{ $comp==='no'?'checked':'' }}> No</label>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- FEES & SCHOLARSHIP DETAILS (with safe calculations) -->
      @php
        // If you don't have these cols, the UI still shows placeholders.
        $totalBefore    = (float) ($inquiry->total_fee_before_discount ?? 0);
        $discPercent    = (float) ($inquiry->discount_percentage ?? 0);
        $discretionary  = (float) ($inquiry->discretionary_discount ?? 0); // absolute
        $eligible       = $inquiry->eligible_for_scholarship ?? 'no';
        $nameScholar    = $inquiry->scholarship_name ?? '';
        $discountFromPct = round($totalBefore * ($discPercent/100), 2);
        $discountTotal   = $discountFromPct + $discretionary;
        $discountedFee   = max(0, round($totalBefore - $discountTotal, 2));

        // GST 18% example (change % if your org uses different)
        $gstPercent    = 18;
        $gstAmount     = round($discountedFee * ($gstPercent/100), 2);
        $totalWithGst  = round($discountedFee + $gstAmount, 2);

        // Installments example: single = all; 3-part split (first a bit higher to cover rounding)
        $inst1 = round($totalWithGst / 3, 0);
        $inst2 = round(($totalWithGst - $inst1) / 2, 0);
        $inst3 = $totalWithGst - $inst1 - $inst2;
      @endphp

      <div class="card mt-4">
        <div class="card-header border-0">
          <h2 class="section-title m-0">Scholarship Details</h2>
        </div>
        <div class="card-body">
          <div class="kv">
            <div class="label">Eligible For Scholarship</div>
            <div class="value">{{ $eligible === 'yes' ? 'Yes' : 'No' }}</div>

            <div class="label">Name of Scholarship</div>
            <div class="value">{{ $nameScholar ?: '—' }}</div>

            <div class="label">Total Fee Before Discount</div>
            <div class="value">{{ $totalBefore ? number_format($totalBefore, 0) : '—' }}</div>

            <div class="label">Discount Percentage</div>
            <div class="value">{{ $discPercent ? $discPercent.'%' : '0%' }}</div>

            <div class="label">Discretionary Discount</div>
            <div class="value">{{ $discretionary ? number_format($discretionary, 0) : '0' }}</div>

            <div class="label">Discounted Fee</div>
            <div class="value">{{ $totalBefore ? number_format($discountedFee, 0) : '—' }}</div>
          </div>
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-header border-0">
          <h2 class="section-title m-0">Fees and Available Batches Details</h2>
        </div>
        <div class="card-body">
          <div class="kv">
            <div class="label">Fees Breakup</div>
            <div class="value">Class room course (with test series &amp; study material)</div>

            <div class="label">Total Fees</div>
            <div class="value">{{ $totalBefore ? number_format($discountedFee, 0) : '—' }}</div>

            <div class="label">GST Amount ({{ $gstPercent }}%)</div>
            <div class="value">{{ $totalBefore ? number_format($gstAmount, 0) : '—' }}</div>

            <div class="label">Total Fees inclusive tax</div>
            <div class="value">{{ $totalBefore ? number_format($totalWithGst, 0) : '—' }}</div>

            <div class="label">If Fees Deposited In Single Installment</div>
            <div class="value">{{ $totalBefore ? number_format($totalWithGst, 0) : '—' }}</div>

            <div class="label">If Fees Deposited In Three Installments</div>
            <div class="value">{{ $totalBefore ? number_format($totalWithGst, 0) : '—' }}</div>

            <div class="label">Installment 1</div>
            <div class="value">{{ $totalBefore ? number_format($inst1, 0) : '—' }}</div>

            <div class="label">Installment 2</div>
            <div class="value">{{ $totalBefore ? number_format($inst2, 0) : '—' }}</div>

            <div class="label">Installment 3</div>
            <div class="value">{{ $totalBefore ? number_format($inst3, 0) : '—' }}</div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('inquiries.index') }}" class="btn btn-orange btn-sm">Close</a>
          </div>
        </div>
      </div>

    </div> <!-- /right -->
  </div> <!-- /main-container -->

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
          crossorigin="anonymous"></script>
  <script>
    // sidebar toggle (same behavior as other pages)
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.querySelector('#sidebar');
      const toggleBtn = document.getElementById('toggleBtn');
      const admin = document.getElementById('admin');
      const right = document.getElementById('right');

      let isCollapsed = false;

      if (sidebar) {
        sidebar.style.transition = 'width 0.5s ease';
        sidebar.style.overflow = 'hidden';
        sidebar.style.width = '300px';
      }

      toggleBtn?.addEventListener('click', function () {
        if (!sidebar) return;
        if (isCollapsed) {
          sidebar.style.width = '25%';
          admin && (admin.style.visibility = 'visible');
          right && (right.style.width = '100%');
        } else {
          sidebar.style.width = '40px';
          admin && (admin.style.visibility = 'hidden');
          right && (right.style.width = '100%');
        }
        isCollapsed = !isCollapsed;
      });
    });
  </script>
</body>
</html>
