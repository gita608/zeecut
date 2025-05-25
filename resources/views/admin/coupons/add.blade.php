<div class="container">
    <h2>Create Coupon</h2>
    <form action="{{ route('coupons.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Coupon Code</label>
            <input type="text" name="coupon_code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Usage Limit (Total)</label>
            <input type="number" name="usage_limit" class="form-control">
        </div>
        <div class="mb-3">
            <label>Per User Limit</label>
            <input type="number" name="per_user_limit" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Save Coupon</button>
    </form>
</div>