<div class="container">
    <form action="{{ route('coupons.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Coupon Code</label>
            <input type="text" name="coupon_code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" min="<?= date('Y-m-d') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>End Date</label>
            <input type="date" name="end_date" min="<?= date('Y-m-d') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Usage Limit (Total)</label>
            <input type="number" name="usage_limit" class="form-control">
        </div>
        <div class="mb-3">
            <label>Per User Limit</label>
            <input type="number" name="per_user_limit" class="form-control">
        </div>
        <div class="mb-3">
            <label>Discount Percentage (%)</label>
            <input type="number" name="percentage" class="form-control" min="0" max="100" step="0.01" placeholder="Enter discount percentage">
        </div>
        <button type="submit" class="btn btn-success">Save Coupon</button>
    </form>
</div>