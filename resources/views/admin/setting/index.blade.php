<div class="page-content">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">General Settings</h4>
          </div>
          <div class="card-body">
            <form action="{{ route('setting.update') }}" method="POST">
              @csrf
  
              <div class="mb-3">
                <label for="delivery_charge" class="form-label">Delivery Charge</label>
                <input type="number"  name="delivery_charge" id="delivery_charge" class="form-control"
                  value="{{$delivery_charge}}" required>
              </div>
  
              {{-- <div class="mb-3">
                <label for="tax_percentage" class="form-label">Tax (%)</label>
                <input type="number" step="0.01" name="tax_percentage" id="tax_percentage" class="form-control"
                  value="{{ old('tax_percentage', get_setting('tax_percentage')) }}">
              </div>
  
              <div class="mb-3">
                <label for="currency" class="form-label">Currency</label>
                <input type="text" name="currency" id="currency" class="form-control"
                  value="{{ old('currency', get_setting('currency', 'AED')) }}">
              </div> --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary ">Update Settings</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>