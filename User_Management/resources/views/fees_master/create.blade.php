@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Create Fees</h4>
    <a href="{{ route('fees.index') }}" class="btn btn-secondary">Back</a>
  </div>

  <!-- CREATE FEES MODAL (standalone or triggered via button) -->
  <div class="modal fade fees-modal show d-block" id="createFeesModal" tabindex="-1" aria-labelledby="createFeesLabel" aria-hidden="true" style="position:relative; display:block;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content shadow-sm border-0">

        <div class="modal-header bg-light">
          <h1 class="modal-title fs-5" id="createFeesLabel">Create Fees</h1>
        </div>

        <form method="POST" action="{{ route('fees.store') }}">
          @csrf

          <div class="modal-body fees-form">
            <!-- Course -->
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Course</label>
                <select name="course" class="form-select" required>
                  <option value="" disabled selected>Select Course</option>
                  <option value="Impulse">Impulse</option>
                  <option value="Momentum">Momentum</option>
                  <option value="Intensity">Intensity</option>
                  <option value="Thrust">Thrust</option>
                  <option value="Seedling 10th">Seedling 10th</option>
                  <option value="Anthesis">Anthesis</option>
                  <option value="Dynamic">Dynamic</option>
                  <option value="Radical 8th">Radical 8th</option>
                  <option value="Plumule 9th">Plumule 9th</option>
                  <option value="Pre Radical 7th">Pre Radical 7th</option>
                </select>
              </div>
            </div>

            <!-- Fees Configuration -->
            <div class="form-section">Fees Configuration</div>
            <div class="row g-3 align-items-end">
              <div class="col-12 col-md-4">
                <label class="form-label">GST %</label>
                <input type="number" step="0.01" min="0" max="100" name="gst_percent" class="form-control" value="18" required>
              </div>
              <div class="col-12 col-md-4 offset-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                  <option value="Active" selected>Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>
            </div>

            <!-- Fees (before GST) -->
            <div class="form-section mt-2">Fees (before GST)</div>
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Class Room Course</label>
                <input type="number" step="0.01" min="0" name="classroom_fee" class="form-control">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Live online class course</label>
                <input type="number" step="0.01" min="0" name="live_fee" class="form-control">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Recorded online class course</label>
                <input type="number" step="0.01" min="0" name="recorded_fee" class="form-control">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Study Material only</label>
                <input type="number" step="0.01" min="0" name="study_fee" class="form-control">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Test series only</label>
                <input type="number" step="0.01" min="0" name="test_fee" class="form-control">
              </div>
            </div>
          </div>

          <div class="modal-footer bg-light">
            <a href="{{ route('fees.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
