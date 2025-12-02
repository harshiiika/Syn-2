@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('reports.inquiry-history.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="text-orange">View Inquiry History</h2>
        </div>
    </div>

    <!-- Basic Details -->
    <div class="card mb-4">
        <div class="card-header bg-orange text-white">
            <h5 class="mb-0">Basic Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Student Name</label>
                    <p class="form-control-plaintext">{{ $inquiry->student_name ?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Father Name</label>
                    <p class="form-control-plaintext">{{ $inquiry->father_name ?? '-' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Father Contact No</label>
                    <p class="form-control-plaintext">{{ $inquiry->father_contact_no ?? '-' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Father WhatsApp No</label>
                    <p class="form-control-plaintext">{{ $inquiry->father_whatsapp_no ?? '-' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Student Contact No</label>
                    <p class="form-control-plaintext">{{ $inquiry->student_contact_no ?? '-' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-secondary">{{ $inquiry->category ?? '-' }}</span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Branch Name</label>
                    <p class="form-control-plaintext">{{ $inquiry->branch_name ?? 'Bikaner' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">State</label>
                    <p class="form-control-plaintext">{{ $inquiry->state ?? '-' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">City</label>
                    <p class="form-control-plaintext">{{ $inquiry->city ?? '-' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Address</label>
                    <p class="form-control-plaintext">{{ $inquiry->address ?? '-' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Do You Belong to Economic Weaker Section?</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $inquiry->economic_weaker_section ? 'bg-success' : 'bg-danger' }}">
                            {{ $inquiry->economic_weaker_section ? 'Yes' : 'No' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Do You Belong to Any Army/Police/Martyr Background?</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $inquiry->army_police_martyr_background ? 'bg-success' : 'bg-danger' }}">
                            {{ $inquiry->army_police_martyr_background ? 'Yes' : 'No' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Are You a Specially Abled?</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $inquiry->specially_abled ? 'bg-success' : 'bg-danger' }}">
                            {{ $inquiry->specially_abled ? 'Yes' : 'No' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Details -->
    <div class="card mb-4">
        <div class="card-header bg-orange text-white">
            <h5 class="mb-0">Course Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Course Type</label>
                    <p class="form-control-plaintext">{{ $inquiry->course_type ?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Course Name</label>
                    <p class="form-control-plaintext">{{ $inquiry->course_name ?? '-' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Delivery Mode</label>
                    <p class="form-control-plaintext">{{ $inquiry->delivery_mode ?? '-' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Medium</label>
                    <p class="form-control-plaintext">{{ $inquiry->medium ?? '-' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Board</label>
                    <p class="form-control-plaintext">{{ $inquiry->board ?? '-' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Course Content</label>
                    <p class="form-control-plaintext">{{ $inquiry->course_content ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scholarship Eligibility -->
    <div class="card mb-4">
        <div class="card-header bg-orange text-white">
            <h5 class="mb-0">Scholarship Eligibility</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Are you a Repeater From the Foundation Batch?</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $inquiry->is_repeater ? 'bg-success' : 'bg-danger' }}">
                            {{ $inquiry->is_repeater ? 'Yes' : 'No' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Have You Appeared For the Synthesis Scholarship test?</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $inquiry->appeared_scholarship_test ? 'bg-success' : 'bg-danger' }}">
                            {{ $inquiry->appeared_scholarship_test ? 'Yes' : 'No' }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Percentage Of Marks In last Board Exam</label>
                    <p class="form-control-plaintext">{{ $inquiry->last_board_exam_percentage ?? '-' }}%</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Have You Appeared For any of the competition exam?</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $inquiry->appeared_competition_exam ? 'bg-success' : 'bg-danger' }}">
                            {{ $inquiry->appeared_competition_exam ? 'Yes' : 'No' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scholarship Details -->
    <div class="card mb-4">
        <div class="card-header bg-orange text-white">
            <h5 class="mb-0">Scholarship Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Eligible For Scholarship</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $inquiry->eligible_for_scholarship ? 'bg-success' : 'bg-danger' }}">
                            {{ $inquiry->eligible_for_scholarship ? 'Yes' : 'No' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Name of Scholarship</label>
                    <p class="form-control-plaintext">{{ $inquiry->scholarship_name ?? '-' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Total Fee Before Discount</label>
                    <p class="form-control-plaintext">₹{{ number_format($inquiry->total_fee_before_discount ?? 0, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Discount Percentage</label>
                    <p class="form-control-plaintext">{{ $inquiry->discount_percentage ?? 0 }}%</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Discounted Fees</label>
                    <p class="form-control-plaintext text-success fw-bold">₹{{ number_format($inquiry->discounted_fees ?? 0, 2) }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Discretionary Discount</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $inquiry->discretionary_discount ? 'bg-success' : 'bg-danger' }}">
                            {{ $inquiry->discretionary_discount ? 'Yes' : 'No' }}
                        </span>
                        @if($inquiry->discretionary_discount && $inquiry->discretionary_discount_amount)
                            <span class="ms-2">Amount: ₹{{ number_format($inquiry->discretionary_discount_amount, 2) }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Fees and Available Batches Details -->
    <div class="card mb-4">
        <div class="card-header bg-orange text-white">
            <h5 class="mb-0">Fees and Available Batches Details</h5>
        </div>
        <div class="card-body">
            @if($inquiry->fees_breakup && count($inquiry->fees_breakup) > 0)
                <div class="mb-3">
                    <label class="form-label fw-bold">Fees Breakup</label>
                    <ul class="list-group">
                        @foreach($inquiry->fees_breakup as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $item['description'] ?? '-' }}</span>
                                <span class="fw-bold">₹{{ number_format($item['amount'] ?? 0, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Total Fees</label>
                    <p class="form-control-plaintext">₹{{ number_format($inquiry->total_fees ?? 0, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">GST Amount</label>
                    <p class="form-control-plaintext">₹{{ number_format($inquiry->gst_amount ?? 0, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Total Fees inclusive tax</label>
                    <p class="form-control-plaintext text-primary fw-bold">₹{{ number_format($inquiry->total_fees_inclusive_tax ?? 0, 2) }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">If Fees Deposited In Single Installment</label>
                    <p class="form-control-plaintext">₹{{ number_format($inquiry->single_installment_amount ?? 0, 2) }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">If Fees Deposited In Three Installments</label>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <p><strong>Installment 1:</strong> ₹{{ number_format($inquiry->installment_1 ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Installment 2:</strong> ₹{{ number_format($inquiry->installment_2 ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Installment 3:</strong> ₹{{ number_format($inquiry->installment_3 ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="card mb-4">
        <div class="card-header bg-orange text-white">
            <h5 class="mb-0">Additional Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-info">{{ $inquiry->status ?? 'Onboard' }}</span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Created At</label>
                    <p class="form-control-plaintext">{{ $inquiry->created_at ? $inquiry->created_at->format('d-m-Y H:i:s') : '-' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Updated At</label>
                    <p class="form-control-plaintext">{{ $inquiry->updated_at ? $inquiry->updated_at->format('d-m-Y H:i:s') : '-' }}</p>
                </div>
            </div>
            @if($inquiry->remarks)
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Remarks</label>
                    <p class="form-control-plaintext">{{ $inquiry->remarks }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ route('reports.inquiry-history.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

<style>
.text-orange {
    color: #ff6600;
}

.bg-orange {
    background-color: #ff6600 !important;
}

.form-control-plaintext {
    padding-top: 0.375rem;
    padding-bottom: 0.375rem;
    margin-bottom: 0;
    font-size: inherit;
    line-height: 1.5;
}
</style>
@endsection