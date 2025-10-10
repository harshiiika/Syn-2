@extends('layouts.app')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>View Fees</h4>
    <a href="{{ route('fees.index') }}" class="btn btn-secondary">Back</a>
  </div>

  <table class="table table-bordered">
    <tr><th>Course</th><td>{{ $fee->course }}</td></tr>
    <tr><th>GST %</th><td>{{ $fee->gst_percent }}</td></tr>
    <tr><th>Classroom Fee</th><td>{{ $fee->classroom_fee ?? '-' }}</td></tr>
    <tr><th>Live Fee</th><td>{{ $fee->live_fee ?? '-' }}</td></tr>
    <tr><th>Recorded Fee</th><td>{{ $fee->recorded_fee ?? '-' }}</td></tr>
    <tr><th>Study Material Fee</th><td>{{ $fee->study_material_fee ?? '-' }}</td></tr>
    <tr><th>Test Series Fee</th><td>{{ $fee->test_series_fee ?? '-' }}</td></tr>
    <tr><th>Status</th><td>{{ $fee->status }}</td></tr>
  </table>

  <div class="mt-3 d-flex gap-2">
    <a href="{{ route('fees.edit', $fee) }}" class="btn btn-primary">Edit</a>
    @if($fee->status === 'Active')
      <a href="{{ route('fees.deactivate.form', $fee) }}" class="btn btn-warning">Deactivate</a>
    @endif
  </div>
</div>
@endsection
