@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Fees</h4>
    <a href="{{ route('fees.index') }}" class="btn btn-secondary">Back</a>
  </div>

  <div class="modal fade fees-modal show d-block" id="editFeesModal"
       tabindex="-1" aria-labelledby="editFeesLabel" aria-hidden="true"
       style="position:relative;display:block;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

    <div class="modal-content shadow-sm border-0">
        <div class="modal-header bg-light">
          <h1 class="modal-title fs-5" id="editFeesLabel">Edit Fees</h1>
        </div>

        <form method="POST" action="{{ route('fees.update', $fee->id) }}">
          @csrf
          @method('PUT')

          <div class="modal-body fees-form">
            <!-- Course -->
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Course</label>
                <select name="course" class="form-select" required>
                  <option value="" disabled>Select Course</option>
                  @foreach(['Impulse','Momentum','Intensity','Thrust','Seedling 10th','Anthesis','Dynamic','Radical 8th','Plumule 9th','Pre Radical 7th'] as $course)
                    <option value="{{ $course }}" {{ old('course', $fee->course)===$course ? 'selected' : '' }}>
                      {{ $course }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Fees Configuration -->
            <div class="form-section">Fees Configuration</div>
            <div class="row g-3 align-items-end">
              <div class="col-12 col-md-4">
                <label class="form-label">GST %</label>
                <input type="number" step="0.01" min="0" max="100"
                       name="gst_percent" class="form-control"
                       value="{{ old('gst_percent', $fee->gst_percent) }}" required>
              </div>
              <div class="col-12 col-md-4 offset-md-4">
                <label class="form-label">Status</label>
                @php $sv = old('status', $fee->status); @endphp
                <select name="status" class="form-select" required>
                  <option value="Active"   {{ $sv==='Active' ? 'selected' : '' }}>Active</option>
                  <option value="Inactive" {{ $sv==='Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
              </div>
            </div>

            <!-- Fees (before GST) -->
            <div class="form-section mt-2">Fees (before GST)</div>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Class Room Course</label>
                <input type="number" step="0.01" min="0"
                       name="classroom_fee" class="form-control"
                       value="{{ old('classroom_fee', $fee->classroom_fee) }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Live online class course</label>
                <input type="number" step="0.01" min="0"
                       name="live_fee" class="form-control"
                       value="{{ old('live_fee', $fee->live_fee) }}">
              </div>
            
              <div class="col-12 col-md-6">
                <label class="form-label">Recorded online class course</label>
                <input type="number" step="0.01" min="0"
                       name="recorded_fee" class="form-control"
                       value="{{ old('recorded_fee', $fee->recorded_fee) }}">
              </div>
            
              <div class="col-12 col-md-6">
                <label class="form-label">Study Material only</label>
                <input type="number" step="0.01" min="0"
                       name="study_fee" class="form-control"
                       value="{{ old('study_fee', $fee->study_fee) }}">
              </div>
            
              <div class="col-12 col-md-6">
                <label class="form-label">Test series only</label>
                <input type="number" step="0.01" min="0"
                       name="test_fee" class="form-control"
                       value="{{ old('test_fee', $fee->test_fee) }}">
              </div>
            
            </div>
          </div>

          <div class="modal-footer bg-light">
            <a href="{{ route('fees.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection











