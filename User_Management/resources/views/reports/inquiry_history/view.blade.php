<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Inquiry History - Synthesis</title>
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
      padding: 20px;
    }

    .main-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .page-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 20px 30px;
      border-radius: 10px;
      margin-bottom: 25px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .page-header h1 {
      font-size: 24px;
      font-weight: 600;
      margin: 0;
    }

    .btn-back {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 8px 20px;
      border-radius: 6px;
      text-decoration: none;
      transition: all 0.3s ease;
      font-size: 14px;
    }

    .btn-back:hover {
      background: rgba(255, 255, 255, 0.3);
      color: white;
      transform: translateY(-2px);
    }

    .section-card {
      background: white;
      border-radius: 10px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .section-title {
      color: #667eea;
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #e9ecef;
    }

    .detail-row {
      display: flex;
      margin-bottom: 15px;
      padding: 12px;
      background-color: #f8f9fa;
      border-radius: 6px;
      transition: all 0.3s ease;
    }

    .detail-row:hover {
      background-color: #e9ecef;
    }

    .detail-label {
      flex: 0 0 280px;
      font-weight: 600;
      color: #495057;
      font-size: 14px;
    }

    .detail-value {
      flex: 1;
      color: #212529;
      font-size: 14px;
    }

    .badge {
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 500;
      display: inline-block;
    }

    .badge-success {
      background-color: #28a745;
      color: white;
    }

    .badge-danger {
      background-color: #dc3545;
      color: white;
    }

    .badge-primary {
      background-color: #667eea;
      color: white;
    }

    .badge-warning {
      background-color: #ffc107;
      color: #212529;
    }

    .badge-info {
      background-color: #17a2b8;
      color: white;
    }

    .table-card {
      background: white;
      border-radius: 10px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .info-table {
      width: 100%;
      border-collapse: collapse;
    }

    .info-table tr {
      border-bottom: 1px solid #e9ecef;
    }

    .info-table tr:last-child {
      border-bottom: none;
    }

    .info-table td {
      padding: 12px;
      font-size: 14px;
    }

    .info-table td:first-child {
      font-weight: 600;
      color: #495057;
      width: 50%;
    }

    .info-table td:last-child {
      color: #212529;
    }

    .btn-close-action {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }

    .btn-close-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
      color: white;
    }

    .action-footer {
      background: white;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    @media (max-width: 768px) {
      .page-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }

      .detail-row {
        flex-direction: column;
      }

      .detail-label {
        margin-bottom: 5px;
      }

      .info-table td:first-child {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="main-container">
    <!-- Page Header -->
    <div class="page-header">
      <h1><i class="fas fa-file-alt me-2"></i>View Inquiry History</h1>
      <a href="{{ route('reports.inquiry-history.index') }}" class="btn-back">
        <i class="fas fa-arrow-left me-2"></i>Back
      </a>
    </div>

    <!-- Basic Details Section -->
    <div class="section-card">
      <h2 class="section-title"><i class="fas fa-user me-2"></i>Basic Details</h2>
      
      <div class="detail-row">
        <div class="detail-label">Student Name</div>
        <div class="detail-value">{{ $inquiry->student_name ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Father Name</div>
        <div class="detail-value">{{ $inquiry->father_name ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Father Contact No</div>
        <div class="detail-value">{{ $inquiry->father_contact_no ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Father WhatsApp No</div>
        <div class="detail-value">{{ $inquiry->father_whatsapp_no ?? ($inquiry->father_contact_no ?? '-') }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Student Contact No</div>
        <div class="detail-value">{{ $inquiry->student_contact_no ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Category</div>
        <div class="detail-value">
          <span class="badge badge-primary">{{ strtoupper($inquiry->category ?? 'GENERAL') }}</span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">State</div>
        <div class="detail-value">{{ $inquiry->state ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">City</div>
        <div class="detail-value">{{ $inquiry->city ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Address</div>
        <div class="detail-value">{{ $inquiry->address ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Branch Name</div>
        <div class="detail-value">
          <span class="badge badge-info">{{ $inquiry->branch ?? 'Bikaner' }}</span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Do You Belong to Economic Weaker Section?</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->is_ews ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->is_ews ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Do You Belong to Any Army/Police/Martyr Background?</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->is_army_background ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->is_army_background ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Are You a Specially Abled?</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->is_specially_abled ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->is_specially_abled ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>
    </div>

    <!-- Course Details Section -->
    <div class="section-card">
      <h2 class="section-title"><i class="fas fa-book me-2"></i>Course Details</h2>
      
      <div class="detail-row">
        <div class="detail-label">Course Type</div>
        <div class="detail-value">
          <span class="badge badge-primary">{{ $inquiry->course_type ?? '-' }}</span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Course Name</div>
        <div class="detail-value">{{ $inquiry->course_name ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Delivery Mode</div>
        <div class="detail-value">{{ $inquiry->delivery_mode ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Medium</div>
        <div class="detail-value">{{ $inquiry->medium ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Board</div>
        <div class="detail-value">{{ $inquiry->board ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Course Content</div>
        <div class="detail-value">{{ $inquiry->course_content ?? '-' }}</div>
      </div>
    </div>

    <!-- Scholarship Eligibility Section -->
    <div class="section-card">
      <h2 class="section-title"><i class="fas fa-graduation-cap me-2"></i>Scholarship Eligibility</h2>
      
      <div class="detail-row">
        <div class="detail-label">Are you a Repeater From the Foundation Batch?</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->is_repeater ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->is_repeater ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Have You Appeared For the Synthesis Scholarship test?</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->scholarship_test_appeared ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->scholarship_test_appeared ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Percentage Of Marks In last Board Exam</div>
        <div class="detail-value">
          @if(isset($inquiry->last_exam_percentage))
            <span class="badge badge-warning">{{ $inquiry->last_exam_percentage }}%</span>
          @else
            -
          @endif
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Have You Appeared For any of the competition exam?</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->competition_exam_appeared ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->competition_exam_appeared ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>
    </div>

    <!-- Scholarship Details Section -->
    <div class="section-card">
      <h2 class="section-title"><i class="fas fa-award me-2"></i>Scholarship Details</h2>
      
      <div class="detail-row">
        <div class="detail-label">Eligible For Scholarship</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->eligible_for_scholarship ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->eligible_for_scholarship ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Name of Scholarship</div>
        <div class="detail-value">{{ $inquiry->scholarship_name ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Total Fee Before Discount</div>
        <div class="detail-value">
          <strong>₹{{ number_format($inquiry->total_fee_before_discount ?? 100000, 2) }}</strong>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Discount Percentage</div>
        <div class="detail-value">
          <span class="badge badge-success">{{ $inquiry->discount_percentage ?? 0 }}%</span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Discounted Fees</div>
        <div class="detail-value">
          <strong style="color: #28a745;">₹{{ number_format($inquiry->discounted_fee ?? 100000, 2) }}</strong>
        </div>
      </div>
    </div>

    <!-- Discretionary Discount Section -->
    <div class="section-card">
      <h2 class="section-title"><i class="fas fa-percentage me-2"></i>Discretionary Discount</h2>
      
      <div class="detail-row">
        <div class="detail-label">Do You Want Add discretionary discount</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->has_discretionary_discount ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->has_discretionary_discount ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>

      @if($inquiry->has_discretionary_discount ?? false)
        <div class="detail-row">
          <div class="detail-label">Discretionary Discount Amount</div>
          <div class="detail-value">
            <strong style="color: #28a745;">₹{{ number_format($inquiry->discretionary_discount_amount ?? 0, 2) }}</strong>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Discretionary Discount Percentage</div>
          <div class="detail-value">
            <span class="badge badge-success">{{ $inquiry->discretionary_discount_percentage ?? 0 }}%</span>
          </div>
        </div>
      @endif
    </div>

    <!-- Fees and Available Batches Details Section -->
    <div class="section-card">
      <h2 class="section-title"><i class="fas fa-rupee-sign me-2"></i>Fees and Available Batches Details</h2>
      
      <div class="detail-row">
        <div class="detail-label">Eligible For Scholarship</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->eligible_for_scholarship ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->eligible_for_scholarship ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Name of Scholarship</div>
        <div class="detail-value">{{ $inquiry->scholarship_name ?? '-' }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Total Fee Before Discount</div>
        <div class="detail-value">
          <strong>₹{{ number_format($inquiry->total_fee_before_discount ?? 100000, 2) }}</strong>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Discretionary Discount</div>
        <div class="detail-value">
          <span class="badge badge-{{ ($inquiry->has_discretionary_discount ?? false) ? 'success' : 'danger' }}">
            {{ ($inquiry->has_discretionary_discount ?? false) ? 'Yes' : 'No' }}
          </span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Discount Percentage</div>
        <div class="detail-value">
          <span class="badge badge-success">{{ $inquiry->discount_percentage ?? 0 }}%</span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Discounted Fee</div>
        <div class="detail-value">
          <strong style="color: #28a745;">₹{{ number_format($inquiry->discounted_fee ?? 100000, 2) }}</strong>
        </div>
      </div>
    </div>

    <!-- Fees Breakup Table -->
    <div class="table-card">
      <h2 class="section-title"><i class="fas fa-calculator me-2"></i>Fees Breakup</h2>
      
      <table class="info-table">
        <tr>
          <td>Class room course (with test series & study material)</td>
          <td><strong>₹{{ number_format($inquiry->course_fee ?? 100000, 2) }}</strong></td>
        </tr>
        <tr>
          <td>Total Fees</td>
          <td><strong>₹{{ number_format($inquiry->total_fees ?? 100000, 2) }}</strong></td>
        </tr>
        <tr>
          <td>GST Amount</td>
          <td><strong>₹{{ number_format($inquiry->gst_amount ?? 18000, 2) }}</strong></td>
        </tr>
        <tr>
          <td>Total Fees inclusive tax</td>
          <td><strong style="color: #667eea;">₹{{ number_format($inquiry->total_fees_with_tax ?? 118000, 2) }}</strong></td>
        </tr>
        <tr>
          <td>If Fees Deposited In Single Installment</td>
          <td><strong style="color: #28a745;">₹{{ number_format($inquiry->single_installment ?? 118000, 2) }}</strong></td>
        </tr>
      </table>
    </div>

    <!-- Installments Section -->
    <div class="section-card">
      <h2 class="section-title"><i class="fas fa-calendar-alt me-2"></i>If Fees Deposited In Three Installments</h2>
      
      <div class="detail-row">
        <div class="detail-label">Installment 1</div>
        <div class="detail-value">
          <strong>₹{{ number_format($inquiry->installment_1 ?? 47200, 2) }}</strong>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Installment 2</div>
        <div class="detail-value">
          <strong>₹{{ number_format($inquiry->installment_2 ?? 35400, 2) }}</strong>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Installment 3</div>
        <div class="detail-value">
          <strong>₹{{ number_format($inquiry->installment_3 ?? 35400, 2) }}</strong>
        </div>
      </div>
    </div>

    <!-- Action Footer -->
    <div class="action-footer">
      <a href="{{ route('reports.inquiry-history.index') }}" class="btn-close-action">
        <i class="fas fa-times"></i>Close
      </a>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>