<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Students Management</title>
  <link rel="stylesheet" href="{{ asset('css/emp.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>

    /* Enhanced Timeline Styles */
.timeline-item {
  transition: all 0.3s ease;
}

.timeline-item .card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.timeline-item .card.hover-shadow:hover {
  transform: translateX(5px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Payment Details Card Styling */
.bg-success-subtle {
  background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-warning-subtle {
  background-color: rgba(255, 193, 7, 0.1) !important;
}

/* Badge styling */
.badge {
  font-weight: 500;
  padding: 0.35em 0.65em;
}

/* Timeline dots animation */
.timeline-item .position-absolute {
  animation: pulse 2s infinite;
}
<style>
.card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.text-white-50 {
  opacity: 0.8;
}

canvas {
  max-height: 400px;
}

@media print {
  .btn, .top {
    display: none !important;
  }
  
  .card {
    break-inside: avoid;
  }
}

@keyframes pulse {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
  }
  50% {
    box-shadow: 0 0 0 6px rgba(13, 110, 253, 0);
  }
}
    /* Activity Timeline Styles */
    .activity-timeline {
      max-height: 400px;
      overflow-y: auto;
      padding-right: 10px;
    }
    .activity-item {
      border-left: 3px solid #fd550dff;
      padding-left: 20px;
      padding-bottom: 20px;
      position: relative;
      margin-bottom: 10px;
    }
    .activity-item:last-child {
      border-left-color: transparent;
      padding-bottom: 0;
    }
    .activity-item::before {
      content: '';
      position: absolute;
      left: -7px;
      top: 5px;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #fd550dff;
      border: 2px solid white;
    }
    .activity-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 8px;
    }
    .activity-title {
      font-weight: 600;
      color: #212529;
      margin: 0;
    }
    .activity-time {
      font-size: 0.875rem;
      color: #6c757d;
      white-space: nowrap;
    }
    .activity-description {
      color: #6c757d;
      font-size: 0.9rem;
      margin: 0;
    }
    .activity-user {
      color: #fd550dff;
      font-weight: 500;
    }

    #history{
      background-color: #fd550dff;
    }

    .card {
  border-radius: 8px;
}

.card-header {
  font-weight: 600;
}

.table td {
  color: #495057;
  font-size: 14px;
}

.table td.fw-bold {
  color: #212529;
}

canvas {
  max-height: 300px;
}

.whole {
  background-color: #f8f9fa;
}
  </style>
</head>

<body>
  <!-- Flash Messages -->
  <div class="flash-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  <!-- Header -->
  <div class="header">
    <div class="logo">
      <img src="{{ asset('images/logo.png.jpg') }}" class="img" alt="Logo">
      <button class="toggleBtn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="pfp">
      <div class="session">
        <h5>Session:</h5>
        <select>
          <option>2024-2025</option>
          <option selected>2025-2026</option>
        </select>
      </div>
      <i class="fa-solid fa-bell"></i>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="toggle-btn" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
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
        <h6>Admin</h6>
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
                <li>><a class="item" href="{{ route('user.emp.emp') }}"><i class="fa-solid fa-user" id="side-icon"></i>
                    Employee</a></li>
                <li>><a class="item" href="{{ route('user.batches.batches') }}"><i class="fa-solid fa-user-group"
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
                <li><a class="item" href="{{ route(name: 'attendance.employee.index') }}"><i class="fa-solid fa-circle-info" id="side-icon"></i> Employee</a></li>
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


<div class="right" id="right">
  <div class="top">
    <div class="top-text">
      <h4>Walk-in / Onboarding</h4>
    </div>
  </div>

  <div class="whole p-4">
    <div class="row">
      <!-- Left Side: Chart -->
      <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title mb-4">Course Wise Conversion</h5>
            @if(count($courseWiseData) > 0)
              <canvas id="courseWiseChart" height="100"></canvas>
            @else
              <div class="text-center py-5 text-muted">
                <i class="fas fa-chart-bar fa-3x mb-3" style="opacity: 0.3;"></i>
                <p class="mb-0">No walk-in or onboarding data with matching course names</p>
                <small class="d-block mt-2">
                  Walk-ins: {{ $analytics['total_walkin'] }} | 
                  Onboarded: {{ $analytics['total_onboarding'] }}
                </small>
                <small class="d-block mt-1 text-warning">
                    Check browser console (F12) for course name details
                </small>
              </div>
            @endif
          </div>
        </div>

        <!-- Bottom Row: 3 Donut Charts -->
        <div class="row g-3">
          <!-- Course Type Onboarding -->
          <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body text-center">
                <h6 class="card-title mb-3">Course Type Onboarding</h6>
                <canvas id="courseTypeChart" width="200" height="200"></canvas>
                <p class="mt-3 mb-0 small text-muted">Avg. Onboarding Percentage</p>
              </div>
            </div>
          </div>

          <!-- Board Type Onboarding -->
          <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body text-center">
                <h6 class="card-title mb-3">Board Type Onboarding</h6>
                <canvas id="boardTypeChart" width="200" height="200"></canvas>
                <p class="mt-3 mb-0 small text-muted">Avg. Board Percentage</p>
              </div>
            </div>
          </div>

          <!-- Medium Type Onboarding -->
          <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body text-center">
                <h6 class="card-title mb-3">Medium Type Onboarding</h6>
                <canvas id="mediumTypeChart" width="200" height="200"></canvas>
                <p class="mt-3 mb-0 small text-muted">Avg. Medium Percentage</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side: Analytics Table -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Analytics</h5>
          </div>
          <div class="card-body p-0">
            <table class="table table-borderless mb-0">
              <tbody>
                <tr class="border-bottom">
                  <td class="py-3 ps-3">Total Walk-in</td>
                  <td class="py-3 pe-3 text-end fw-bold">{{ number_format($analytics['total_walkin']) }}</td>
                </tr>
                <tr class="border-bottom">
                  <td class="py-3 ps-3">Total Onboarding</td>
                  <td class="py-3 pe-3 text-end fw-bold">{{ number_format($analytics['total_onboarding']) }}</td>
                </tr>
                <tr class="border-bottom">
                  <td class="py-3 ps-3">Test series only</td>
                  <td class="py-3 pe-3 text-end fw-bold">{{ number_format($analytics['test_series_only']) }}</td>
                </tr>
                <tr class="border-bottom">
                  <td class="py-3 ps-3">Class room course</td>
                  <td class="py-3 pe-3 text-end fw-bold">{{ number_format($analytics['class_room_course']) }}</td>
                </tr>
                <tr class="border-bottom">
                  <td class="py-3 ps-3">Study Material only</td>
                  <td class="py-3 pe-3 text-end fw-bold">{{ number_format($analytics['study_material_only']) }}</td>
                </tr>
                <tr class="border-bottom">
                  <td class="py-3 ps-3">Live online class course</td>
                  <td class="py-3 pe-3 text-end fw-bold">{{ number_format($analytics['live_online_class']) }}</td>
                </tr>
                <tr class="border-bottom">
                  <td class="py-3 ps-3">Test series & Study Material</td>
                  <td class="py-3 pe-3 text-end fw-bold">{{ number_format($analytics['test_series_and_study_material']) }}</td>
                </tr>
                <tr>
                  <td class="py-3 ps-3">Recorded online class course</td>
                  <td class="py-3 pe-3 text-end fw-bold">{{ number_format($analytics['recorded_online_class']) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/emp.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('  Walk-in Report - Initializing Charts');
  
  // Course Wise Conversion Bar Chart
  const courseWiseData = @json($courseWiseData);
  console.log('  Course Wise Data:', courseWiseData);
  console.log('  Course Wise Data Length:', courseWiseData ? courseWiseData.length : 0);
  
  if (courseWiseData && courseWiseData.length > 0) {
    const ctx1 = document.getElementById('courseWiseChart');
    if (ctx1) {
      console.log('  Creating bar chart with data:', courseWiseData);
      new Chart(ctx1, {
        type: 'bar',
        data: {
          labels: courseWiseData.map(item => item.course || 'Unknown'),
          datasets: [
            {
              label: 'Walk-in',
              data: courseWiseData.map(item => item.walkin || 0),
              backgroundColor: '#000000',
              borderColor: '#000000',
              borderWidth: 1,
              barThickness: 30
            },
            {
              label: 'Onboarding',
              data: courseWiseData.map(item => item.onboarding || 0),
              backgroundColor: '#17a2b8',
              borderColor: '#17a2b8',
              borderWidth: 1,
              barThickness: 30
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              position: 'top',
              labels: {
                usePointStyle: true,
                padding: 15
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              max: 900,
              ticks: {
                stepSize: 100
              },
              grid: {
                display: true,
                color: 'rgba(0, 0, 0, 0.05)'
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          }
        }
      });
      console.log('  Bar chart created successfully');
    }
  } else {
    console.warn('  No course wise data - checking what we have:');
    console.log('Analytics data:', @json($analytics));
  }

  // Course Type Donut Chart (ORIGINAL WORKING CODE)
  const courseTypeData = @json($courseTypeData);
  console.log('  Course Type Data:', courseTypeData);
  const courseTypeTotal = Object.values(courseTypeData).reduce((a, b) => a + b, 0);
  
  const ctx2 = document.getElementById('courseTypeChart');
  if (ctx2) {
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: Object.keys(courseTypeData),
        datasets: [{
          data: Object.values(courseTypeData),
          backgroundColor: [
            '#e91e63',
            '#9c27b0',
            '#3f51b5'
          ],
          borderWidth: 0,
          cutout: '70%'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              usePointStyle: true,
              padding: 10,
              font: {
                size: 11
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const value = context.parsed;
                const percentage = courseTypeTotal > 0 ? ((value / courseTypeTotal) * 100).toFixed(1) : 0;
                return context.label + ': ' + value + ' (' + percentage + '%)';
              }
            }
          }
        }
      }
    });
    console.log('  Course type chart created');
  }

  // Board Type Donut Chart (ORIGINAL WORKING CODE)
  const boardTypeData = @json($boardTypeData);
  console.log('  Board Type Data:', boardTypeData);
  const boardTypeTotal = Object.values(boardTypeData).reduce((a, b) => a + b, 0);
  
  const ctx3 = document.getElementById('boardTypeChart');
  if (ctx3) {
    new Chart(ctx3, {
      type: 'doughnut',
      data: {
        labels: Object.keys(boardTypeData),
        datasets: [{
          data: Object.values(boardTypeData),
          backgroundColor: [
            '#e91e63',
            '#9c27b0'
          ],
          borderWidth: 0,
          cutout: '70%'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              usePointStyle: true,
              padding: 10,
              font: {
                size: 11
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const value = context.parsed;
                const percentage = boardTypeTotal > 0 ? ((value / boardTypeTotal) * 100).toFixed(1) : 0;
                return context.label + ': ' + value + ' (' + percentage + '%)';
              }
            }
          }
        }
      }
    });
    console.log('  Board type chart created');
  }

  // Medium Type Donut Chart (ORIGINAL WORKING CODE)
  const mediumTypeData = @json($mediumTypeData);
  console.log('  Medium Type Data:', mediumTypeData);
  const mediumTypeTotal = Object.values(mediumTypeData).reduce((a, b) => a + b, 0);
  
  const ctx4 = document.getElementById('mediumTypeChart');
  if (ctx4) {
    new Chart(ctx4, {
      type: 'doughnut',
      data: {
        labels: Object.keys(mediumTypeData),
        datasets: [{
          data: Object.values(mediumTypeData),
          backgroundColor: [
            '#e91e63',
            '#9c27b0'
          ],
          borderWidth: 0,
          cutout: '70%'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              usePointStyle: true,
              padding: 10,
              font: {
                size: 11
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const value = context.parsed;
                const percentage = mediumTypeTotal > 0 ? ((value / mediumTypeTotal) * 100).toFixed(1) : 0;
                return context.label + ': ' + value + ' (' + percentage + '%)';
              }
            }
          }
        }
      }
    });
    console.log('  Medium type chart created');
  }
  
  console.log('  All charts initialized');
});
</script>
</body>
</html>
