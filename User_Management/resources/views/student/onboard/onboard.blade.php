<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Onboarded Students</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/emp.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .nav-tabs .nav-link {
      color: #495057;
      font-weight: 500;
    }
    .nav-tabs .nav-link.active {
      color: #007bff;
      font-weight: 600;
    }
    .badge-success {
      background-color: #28a745;
    }
    .badge-warning {
      background-color: #ffc107;
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
          <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container">
    <!-- Sidebar (same as other pages) -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="right" id="right">
      <div class="top">
        <div class="top-text">
          <h3>Onboarded Students</h3>
        </div>
        <div class="btns">
          <a href="{{ route('student.student.pending') }}">
            <button type="button" class="btn btn-primary">
              <i class="fa-solid fa-clock"></i> Pending Inquiries
            </button>
          </a>
          <a href="{{ route('student.pendingfees.pending') }}">
            <button type="button" class="btn btn-warning">
              <i class="fa-solid fa-money-bill"></i> Pending Fees
            </button>
          </a>
        </div>
      </div>

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

      <div class="whole">
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="onboardTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" 
                    type="button" role="tab" aria-controls="pending" aria-selected="true">
              <i class="fa-solid fa-clock"></i> Pending Payment ({{ $partiallyPaid->count() }})
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="onboarded-tab" data-bs-toggle="tab" data-bs-target="#onboarded" 
                    type="button" role="tab" aria-controls="onboarded" aria-selected="false">
              <i class="fa-solid fa-check-circle"></i> Fully Onboarded ({{ $fullyPaid->count() }})
            </button>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="onboardTabsContent">
          <!-- Pending Payment Tab -->
          <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <div class="dd">
              <div class="line">
                <h6>Show Entries:</h6>
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" id="number" type="button" data-bs-toggle="dropdown">
                    10
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item">10</a></li>
                    <li><a class="dropdown-item">25</a></li>
                    <li><a class="dropdown-item">50</a></li>
                    <li><a class="dropdown-item">100</a></li>
                  </ul>
                </div>
              </div>
              <div class="search">
                <h4 class="search-text">Search</h4>
                <input type="search" placeholder="" class="search-holder" required>
                <i class="fa-solid fa-magnifying-glass"></i>
              </div>
            </div>

            <table class="table table-hover" id="table">
              <thead>
                <tr>
                  <th scope="col">S.No.</th>
                  <th scope="col">Student Name</th>
                  <th scope="col">Father Name</th>
                  <th scope="col">Contact</th>
                  <th scope="col">Course</th>
                  <th scope="col">Total Fees</th>
                  <th scope="col">Paid Amount</th>
                  <th scope="col">Remaining</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($partiallyPaid as $index => $student)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $student->name }}</td>
                  <td>{{ $student->father ?? '—' }}</td>
                  <td>{{ $student->mobileNumber ?? '—' }}</td>
                  <td>{{ $student->courseName ?? '—' }}</td>
                  <td>₹{{ number_format($student->totalFees ?? 0, 2) }}</td>
                  <td>₹{{ number_format($student->paidAmount ?? 0, 2) }}</td>
                  <td>₹{{ number_format($student->remainingAmount ?? 0, 2) }}</td>
                  <td><span class="badge badge-warning">Partial Payment</span></td>
                  <td>
                    <div class="dropdown">
                      <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item" href="{{ route('student.onboard.show', $student->_id) }}">
                            <i class="fa-solid fa-eye"></i> View Details
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="{{ route('student.onboard.edit', $student->_id) }}">
                            <i class="fa-solid fa-edit"></i> Edit
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="{{ route('student.payment.history', $student->_id) }}">
                            <i class="fa-solid fa-history"></i> Payment History
                          </a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="10" class="text-center">No students with partial payment found</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Fully Onboarded Tab -->
          <div class="tab-pane fade" id="onboarded" role="tabpanel" aria-labelledby="onboarded-tab">
            <div class="dd">
              <div class="line">
                <h6>Show Entries:</h6>
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" id="number2" type="button" data-bs-toggle="dropdown">
                    10
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item">10</a></li>
                    <li><a class="dropdown-item">25</a></li>
                    <li><a class="dropdown-item">50</a></li>
                    <li><a class="dropdown-item">100</a></li>
                  </ul>
                </div>
              </div>
              <div class="search">
                <h4 class="search-text">Search</h4>
                <input type="search" placeholder="" class="search-holder" required>
                <i class="fa-solid fa-magnifying-glass"></i>
              </div>
            </div>

            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">S.No.</th>
                  <th scope="col">Student Name</th>
                  <th scope="col">Father Name</th>
                  <th scope="col">Contact</th>
                  <th scope="col">Course</th>
                  <th scope="col">Total Fees</th>
                  <th scope="col">Paid Amount</th>
                  <th scope="col">Onboarded Date</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($fullyPaid as $index => $student)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $student->name }}</td>
                  <td>{{ $student->father ?? '—' }}</td>
                  <td>{{ $student->mobileNumber ?? '—' }}</td>
                  <td>{{ $student->courseName ?? '—' }}</td>
                  <td>₹{{ number_format($student->totalFees ?? 0, 2) }}</td>
                  <td>₹{{ number_format($student->paidAmount ?? 0, 2) }}</td>
                  <td>{{ $student->onboardedAt ? $student->onboardedAt->format('d-m-Y') : '—' }}</td>
                  <td><span class="badge badge-success">Fully Paid</span></td>
                  <td>
                    <div class="dropdown">
                      <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item" href="{{ route('student.onboard.show', $student->_id) }}">
                            <i class="fa-solid fa-eye"></i> View Details
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="{{ route('student.onboard.edit', $student->_id) }}">
                            <i class="fa-solid fa-edit"></i> Edit
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="{{ route('student.payment.history', $student->_id) }}">
                            <i class="fa-solid fa-history"></i> Payment History
                          </a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="10" class="text-center">No fully onboarded students found</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination -->
        <div class="footer">
          <div class="left-footer">
            <p>Showing entries</p>
          </div>
          <div class="right-footer">
            <nav aria-label="...">
              <ul class="pagination">
                <li class="page-item"><a href="#" class="page-link">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('js/emp.js')}}"></script>
</body>
</html>