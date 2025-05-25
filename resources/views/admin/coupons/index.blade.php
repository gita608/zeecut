<div class="container">
    <h2>Coupon List</h2>
    <a href="{{ route('coupons.create') }}" class="btn btn-primary mb-3">Add Coupon</a>
    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Status</th>
                <th>Start</th>
                <th>End</th>
                <th>Usage Limit</th>
                <th>Per User Limit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->coupon_code }}</td>
                    <td>{{ ucfirst($coupon->status) }}</td>
                    <td>{{ $coupon->start_date->toDateString() }}</td>
                    <td>{{ $coupon->end_date->toDateString() }}</td>
                    <td>{{ $coupon->usage_limit }}</td>
                    <td>{{ $coupon->per_user_limit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>