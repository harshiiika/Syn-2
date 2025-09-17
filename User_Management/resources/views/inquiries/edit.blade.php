<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Inquiry</title>

  <!-- Icons + Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
    crossorigin="anonymous"
  />

  <!-- Reuse the same stylesheet as create page -->
  <link rel="stylesheet" href="{{ asset('css/createinq.css') }}" />
</head>

<body>
  <!-- Toast (optional) -->
  <div class="toast-container end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-body">
        <img src="/images/tick.png" class="tick" />
        Login Successfully
      </div>
    </div>
  </div>

  <!-- Top bar -->
  <div class="top">
    <div class="header">
      <img src="https://synthesisbikaner.org/synthesistest/assets/logo-big.png" class="logo" />
      <i class="fa-solid fa-bars" id="toggleBtn"></i>
    </div>

    <div class="session">
      <label>Session:</label>
      <select class="select">
        <option>2026</option>
        <option>2024-25</option>
      </select>

      <i class="fa-solid fa-bell" style="color: rgb(233, 96, 47); font-size: 22px"></i>

      <div class="dropdown">
        <button
          class="btn btn-secondary dropdown-toggle"
          id="toggle-btn"
          type="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="fa-solid fa-user" style="color: rgb(233, 96, 47); font-size: 22px"></i>
        </button>
        <ul class="dropdown-menu">
          <li>
            <a href="/pfp/pfp.html" class="dropdown-item">
              <i class="fa-solid fa-user" style="color: rgb(233, 96, 47); font-size: 15px"></i
              >Profile</a
            >
          </li>
          <li>
            <a href="/login page/login.html" class="dropdown-item">
              <i
                class="fa-solid fa-arrow-right-from-bracket"
                style="color: rgb(233, 96, 47); font-size: 15px"
              ></i
              >Log In</a
            >
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Main layout -->
  <div class="main-container">
    <!-- LEFT: full accordion (same as your create page) -->
    <div class="left" id="sidebar">
      <div class="admin" id="admin">
        <h2>Admin</h2>
        <h4>synthesisbikaner@gmail.com</h4>
      </div>

      <div class="accordion accordion-flush" id="accordionFlushExample">
        <!-- User Management -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#flush-collapseOne"
              aria-expanded="false"
              aria-controls="flush-collapseOne"
            >
              <i class="fa-solid fa-user-group" style="color: #b8bdc7"></i> User Management
            </button>
          </h2>
          <div
            id="flush-collapseOne"
            class="accordion-collapse collapse"
            data-bs-parent="#accordionFlushExample"
          >
            <div class="accordion-body">
              <ul class="menu">
                <li>
                  <a href="/1.user management/emp/emp.html" class="item">
                    <i class="fa-solid fa-user" style="color: #b8bdc7"></i> Employee
                  </a>
                </li>
                <li>
                  <a href="/1.user management/batches a/batchesa.html" class="item">
                    <i class="fa-solid fa-user-group" style="color: #b8bdc7"></i> Batches Assignment
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Master -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#flush-collapseTwo"
              aria-expanded="false"
              aria-controls="flush-collapseTwo"
            >
              <i class="fa-solid fa-user-group" style="color: #b8bdc7"></i> Master
            </button>
          </h2>
          <div
            id="flush-collapseTwo"
            class="accordion-collapse collapse"
            data-bs-parent="#accordionFlushExample"
          >
            <div class="accordion-body">
              <ul class="menu">
                <li>
                  <a href="/2.master/courses/course.html" class="item">
                    <i class="fa-solid fa-book-open" style="color: #b8bdc7"></i> Courses
                  </a>
                </li>
                <li>
                  <a href="/2.master/batches/batches.html" class="item">
                    <i class="fa-solid fa-user-group fa-flip-horizontal" style="color: #c2c2c2"></i>
                    Batches
                  </a>
                </li>
                <li>
                  <a href="/2.master/scholarship/scholar.html" class="item">
                    <i class="fa-solid fa-graduation-cap" style="color: #b8bdc7"></i> Scholarship
                  </a>
                </li>
                <li>
                  <a href="/2.master/feesm/fees.html" class="item">
                    <i class="fa-solid fa-credit-card" style="color: #b8bdc7"></i> Fees Master
                  </a>
                </li>
                <li>
                  <a href="/2.master/other fees/other.html" class="item">
                    <i class="fa-solid fa-wallet" style="color: #b8bdc7"></i> Other Fees Master
                  </a>
                </li>
                <li>
                  <a href="/2.master/branch/branch.html" class="item">
                    <i class="fa-solid fa-diagram-project" style="color: #b8bdc7"></i> Branch Management
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Session Management -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#flush-collapseThree"
              aria-expanded="false"
              aria-controls="flush-collapseThree"
            >
              <i class="fa-solid fa-user-group" style="color: #b8bdc7"></i> Session Management
            </button>
          </h2>
          <div
            id="flush-collapseThree"
            class="accordion-collapse collapse"
            data-bs-parent="#accordionFlushExample"
          >
            <div class="accordion-body">
              <ul class="menu">
                <li>
                  <a href="/3.session mana/session/session.html" class="item">
                    <i class="fa-solid fa-calendar-day" style="color: #b8bdc7"></i> Session
                  </a>
                </li>
                <li>
                  <a href="/3.session mana/calendar/cal.html" class="item">
                    <i class="fa-solid fa-calendar-days" style="color: #b8bdc7"></i> Calendar
                  </a>
                </li>
                <li>
                  <a href="/3.session mana/student/student.html" class="item">
                    <i class="fa-solid fa-user-check" style="color: #b8bdc7"></i> Student Migrate
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Student Management -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#flush-collapseFour"
              aria-expanded="false"
              aria-controls="flush-collapseFour"
            >
              <i class="fa-solid fa-user-group" style="color: #b8bdc7"></i> Student Management
            </button>
          </h2>
          <div
            id="flush-collapseFour"
            class="accordion-collapse collapse"
            data-bs-parent="#accordionFlushExample"
          >
            <div class="accordion-body">
              <ul class="menu">
                <li>
                  <a href="/4.student management/inq/inq.html" class="item">
                    <i class="fa-solid fa-circle-info" style="color: #b8bdc7"></i> Inquiry Management
                  </a>
                </li>
                <li>
                  <a href="/4.student management/stu onboard/onstu.html" class="item">
                    <i class="fa-solid fa-user-check" style="color: #b8bdc7"></i> Student Onboard
                  </a>
                </li>
                <li>
                  <a href="/4.student management/pending/pending.html" class="item">
                    <i class="fa-solid fa-user-check" style="color: #b8bdc7"></i> Pending Fees Students
                  </a>
                </li>
                <li>
                  <a href="/4.student management/students/stu.html" class="item">
                    <i class="fa-solid fa-user-check" style="color: #b8bdc7"></i> Students
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Fees Management -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#flush-collapseFive"
              aria-expanded="false"
              aria-controls="flush-collapseFive"
            >
              <i class="fa-solid fa-credit-card" style="color: #b8bdc7"></i> Fees Management
            </button>
          </h2>
          <div
            id="flush-collapseFive"
            class="accordion-collapse collapse"
            data-bs-parent="#accordionFlushExample"
          >
            <div class="accordion-body">
              <ul class="menu">
                <li>
                  <a href="/5.fees management/collect/collect.html" class="item">
                    <i class="fa-solid fa-credit-card" style="color: #b8bdc7"></i> Fees Collection
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Attendance Management -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#flush-collapseSix"
              aria-expanded="false"
              aria-controls="flush-collapseSix"
            >
              <i class="fa-solid fa-user-check" style="color: #b8bdc7"></i> Attendance Management
            </button>
          </h2>
          <div
            id="flush-collapseSix"
            class="accordion-collapse collapse"
            data-bs-parent="#accordionFlushExample"
          >
            <div class="accordion-body">
              <ul class="menu">
                <li>
                  <a href="/6.attendance management/students/student.html" class="item">
                    <i class="fa-solid fa-user" style="color: #b8bdc7"></i> Student
                  </a>
                </li>
                <li>
                  <a href="/6.attendance management/employee/employee.html" class="item">
                    <i class="fa-solid fa-user" style="color: #b8bdc7"></i> Employee
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Study Material -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#flush-collapseSeven"
              aria-expanded="false"
              aria-controls="flush-collapseSeven"
            >
              <i class="fa-solid fa-book-open" style="color: #b8bdc7"></i> Study Material
            </button>
          </h2>
          <div
            id="flush-collapseSeven"
            class="accordion-collapse collapse"
            data-bs-parent="#accordionFlushExample"
          >
            <div class="accordion-body">
              <ul class="menu">
                <li>
                  <a href="/7.study material/units/units.html" class="item">
                    <i class="fa-solid fa-user" style="color: #b8bdc7"></i> Units
                  </a>
                </li>
                <li>
                  <a href="/7.study material/dispatch/dispatch.html" class="item">
                    <i class="fa-solid fa-user" style="color: #b8bdc7"></i> Dispatch Material
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Test Series Management -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#flush-collapseEight"
              aria-expanded="false"
              aria-controls="flush-collapseEight"
            >
              <i class="fa-solid fa-chart-column" style="color: #b8bdc7"></i> Test Series Management
            </button>
          </h2>
          <div
            id="flush-collapseEight"
            class="accordion-collapse collapse"
            data-bs-parent="#accordionFlushExample"
          >
            <div class="accordion-body">
              <ul class="menu">
                <li>
                  <a href="/8.testseries/test.html" class="item">
                    <i class="fa-solid fa-user" style="color: #b8bdc7"></i> Test Master
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Reports -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#flush-collapseNine"
              aria-expanded="false"
              aria-controls="flush-collapseNine"
            >
              <i class="fa-solid fa-square-poll-horizontal" style="color: #b8bdc7"></i> Reports
            </button>
          </h2>
          <div
            id="flush-collapseNine"
            class="accordion-collapse collapse"
            data-bs-parent="#accordionFlushExample"
          >
            <div class="accordion-body">
              <ul class="menu">
                <li>
                  <a href="/9.reports/walkin/walkin.html" class="item">
                    <i class="fa-solid fa-user" style="color: #b8bdc7"></i> Walk In
                  </a>
                </li>
                <li>
                  <a href="/9.reports/att/att.html" class="item">
                    <i class="fa-solid fa-calendar-days" style="color: #b8bdc7"></i> Attendance
                  </a>
                </li>
                <li>
                  <a href="/9.reports/test/test.html" class="item">
                    <i class="fa-solid fa-file" style="color: #b8bdc7"></i> Test Series
                  </a>
                </li>
                <li>
                  <a href="/9.reports/inq/inq.html" class="item">
                    <i class="fa-solid fa-file" style="color: #b8bdc7"></i> Inquiry History
                  </a>
                </li>
                <li>
                  <a href="/9.reports/onboard/onboard.html" class="item">
                    <i class="fa-solid fa-file" style="color: #b8bdc7"></i> Onboard History
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div> <!-- /accordion -->
    </div> <!-- /left -->

    <!-- RIGHT: edit form -->
   <div class="right" id="right">
  {{-- Top line: title on left, Back on right --}}
  <div class="page-header">
    <h1 class="page-title m-0">Edit Inquiry</h1>
    <a href="{{ route('inquiries.index') }}" class="btn-back-link">
      <i class="fa-solid fa-angle-left me-1"></i> Back
    </a>
  </div>

      <div class="card mt-3">
        <div class="card-header border-0">
          <h2 class="section-title m-0">Edit Inquiry</h2>
        </div>

        <div class="card-body">
          @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              <strong>Fix the following:</strong>
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form
            class="row g-3 needs-validation"
            method="POST"
            action="{{ route('inquiries.update', $inquiry->id) }}"
            enctype="multipart/form-data"
            novalidate
          >
            @csrf
            @method('PUT')

            <!-- Row 1 -->
            <div class="col-md-6">
              <label class="form-label">Student Name</label>
              <input type="text" class="form-control" name="student_name"
                     value="{{ old('student_name', $inquiry->student_name) }}" required />
              <div class="invalid-feedback">Please provide the student name.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Father Name</label>
              <input type="text" class="form-control" name="father_name"
                     value="{{ old('father_name', $inquiry->father_name) }}" required />
              <div class="invalid-feedback">Please provide the father name.</div>
            </div>

            <!-- Row 2 -->
            <div class="col-md-6">
              <label class="form-label">Father Contact Number</label>
              <input type="text" class="form-control" name="father_contact"
                     value="{{ old('father_contact', $inquiry->father_contact) }}" required />
              <div class="invalid-feedback">Please provide a valid contact number.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Father WhatsApp Number</label>
              <input type="text" class="form-control" name="father_whatsapp"
                     value="{{ old('father_whatsapp', $inquiry->father_whatsapp) }}" />
            </div>

            <!-- Row 3 -->
            <div class="col-md-6">
              <label class="form-label">Student Contact Number</label>
              <input type="text" class="form-control" name="student_contact"
                     value="{{ old('student_contact', $inquiry->student_contact) }}" />
            </div>

            <div class="col-md-6">
              <label class="form-label d-block">Category</label>
              @php $cat = old('category', $inquiry->category); @endphp
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="category" id="cat_obc" value="OBC" {{ $cat==='OBC'?'checked':'' }} required />
                <label class="form-check-label" for="cat_obc">OBC</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="category" id="cat_sc" value="SC" {{ $cat==='SC'?'checked':'' }} />
                <label class="form-check-label" for="cat_sc">SC</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="category" id="cat_gen" value="GENERAL" {{ $cat==='GENERAL'?'checked':'' }} />
                <label class="form-check-label" for="cat_gen">GENERAL</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="category" id="cat_st" value="ST" {{ $cat==='ST'?'checked':'' }} />
                <label class="form-check-label" for="cat_st">ST</label>
              </div>
              <div class="invalid-feedback d-block">Please choose a category.</div>
            </div>

            <!-- Row 4 -->
            <div class="col-md-6">
              <label class="form-label">State</label>
              <input type="text" class="form-control" name="state"
                     value="{{ old('state', $inquiry->state) }}" required />
              <div class="invalid-feedback">Please enter a state.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">City</label>
              <input type="text" class="form-control" name="city"
                     value="{{ old('city', $inquiry->city) }}" required />
              <div class="invalid-feedback">Please provide a city.</div>
            </div>

            <!-- Row 5 -->
            <div class="col-md-6">
              <label class="form-label">Address Name</label>
              <textarea class="form-control" name="address" rows="2">{{ old('address', $inquiry->address) }}</textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">Select Branch Name</label>
              @php $bn = old('branch_name', $inquiry->branch_name); @endphp
              <select class="form-select" name="branch_name" required>
                <option value="" disabled {{ $bn ? '' : 'selected' }}>Choose...</option>
                <option {{ $bn==='Branch 1'?'selected':'' }}>Branch 1</option>
                <option {{ $bn==='Branch 2'?'selected':'' }}>Branch 2</option>
                <option {{ $bn==='Branch 3'?'selected':'' }}>Branch 3</option>
                <option {{ $bn==='Branch 4'?'selected':'' }}>Branch 4</option>
              </select>
              <div class="invalid-feedback">Please select a branch.</div>
            </div>

            <!-- Yes/No groups -->
            <div class="col-12">
              <div class="soft-row d-flex align-items-center justify-content-between">
                <span>Do You Belong to Economic Weaker Section ?</span>
                @php $ews = old('ews', $inquiry->ews); @endphp
                <span>
                  <label class="me-3">
                    <input class="form-check-input me-1" type="radio" name="ews" value="yes" {{ $ews==='yes'?'checked':'' }} required />
                    Yes
                  </label>
                  <label>
                    <input class="form-check-input me-1" type="radio" name="ews" value="no" {{ $ews==='no'?'checked':'' }} />
                    No
                  </label>
                </span>
              </div>
              <div class="invalid-feedback d-block">Select Yes/No for EWS.</div>
            </div>

            <div class="col-12">
              <div class="soft-row d-flex align-items-center justify-content-between">
                <span>Do You Belong to Any Army/Police/Martyr Background?</span>
                @php $srv = old('service_background', $inquiry->service_background); @endphp
                <span>
                  <label class="me-3">
                    <input class="form-check-input me-1" type="radio" name="service_background" value="yes" {{ $srv==='yes'?'checked':'' }} required />
                    Yes
                  </label>
                  <label>
                    <input class="form-check-input me-1" type="radio" name="service_background" value="no" {{ $srv==='no'?'checked':'' }} />
                    No
                  </label>
                </span>
              </div>
              <div class="invalid-feedback d-block">Select Yes/No for service background.</div>
            </div>

            <div class="col-12">
              <div class="soft-row d-flex align-items-center justify-content-between">
                <span>Are You a Specially Abled ?</span>
                @php $sa = old('specially_abled', $inquiry->specially_abled); @endphp
                <span>
                  <label class="me-3">
                    <input class="form-check-input me-1" type="radio" name="specially_abled" value="yes" {{ $sa==='yes'?'checked':'' }} required />
                    Yes
                  </label>
                  <label>
                    <input class="form-check-input me-1" type="radio" name="specially_abled" value="no" {{ $sa==='no'?'checked':'' }} />
                    No
                  </label>
                </span>
              </div>
              <div class="invalid-feedback d-block">Select Yes/No for specially abled.</div>
            </div>

            <!-- Course Details divider -->
            <div class="col-12"><hr class="my-4" /></div>
            <div class="col-12">
              <h5 class="mb-3">Course Details</h5>
            </div>

            <div class="col-md-6">
              <label class="form-label">Course Type</label>
              <input name="course_type" class="form-control" value="{{ old('course_type', $inquiry->course_type) }}" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Course Name</label>
              <input name="course_name" class="form-control" value="{{ old('course_name', $inquiry->course_name) }}" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Delivery Mode</label>
              @php $dm = old('delivery_mode', $inquiry->delivery_mode); @endphp
              <select name="delivery_mode" class="form-select">
                <option value="" disabled {{ $dm ? '' : 'selected' }}>Choose…</option>
                <option value="Offline"  {{ $dm==='Offline'?'selected':'' }}>Offline</option>
                <option value="Online"   {{ $dm==='Online'?'selected':'' }}>Online</option>
                <option value="Hybrid"   {{ $dm==='Hybrid'?'selected':'' }}>Hybrid</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Medium</label>
              @php $med = old('medium', $inquiry->medium); @endphp
              <select name="medium" class="form-select">
                <option value="" disabled {{ $med ? '' : 'selected' }}>Choose…</option>
                <option value="Hindi"     {{ $med==='Hindi'?'selected':'' }}>Hindi</option>
                <option value="English"   {{ $med==='English'?'selected':'' }}>English</option>
                <option value="Bilingual" {{ $med==='Bilingual'?'selected':'' }}>Bilingual</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Board</label>
              <input name="board" class="form-control" value="{{ old('board', $inquiry->board) }}" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Course Content</label>
              <input name="course_content" class="form-control" value="{{ old('course_content', $inquiry->course_content) }}" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Status</label>
              <input name="status" class="form-control" value="{{ old('status', $inquiry->status) }}" />
            </div>

            <!-- Buttons -->
            <div class="col-12 d-flex justify-content-end mt-3">
              <a href="{{ route('inquiries.index') }}" class="btn btn-outline-secondary me-2">Back</a>
              <button class="btn btn-primary" type="submit">Update</button>
            </div>
          </form>
        </div>
      </div>

    </div> <!-- /right -->
  </div> <!-- /main-container -->

  <!-- Scripts -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"
  ></script>

  <script>
    // Same sidebar toggle behavior you used elsewhere
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

    // Enable bootstrap popovers if any
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    [...popoverTriggerList].map(el => new bootstrap.Popover(el));
  </script>
</body>
</html>
