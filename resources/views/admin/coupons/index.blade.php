<div class="page-content">
    <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0">{{ $page_title ?? '' }}</h5>
            <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                onclick="show_small_modal('{{ route('coupons.create') }}', 'Add {{ $page_title ?? '' }}')">
                <i class="fas fa-plus"></i> Add {{ $page_title ?? '' }}
            </a>
        </div>
    </div>
    <!-- Filter Form -->
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table1" class="table table-striped">`
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Usage Limit</th>
                            <th>Per User Limit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach($coupons as $coupon)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $coupon->coupon_code }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-status" type="checkbox"
                                            data-id="{{ $coupon->id }}" {{ $coupon->status === 'active' ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>{{ $coupon->start_date ? date('d-m-Y', strtotime($coupon->start_date)) : '' }}</td>
                                <td>{{ $coupon->end_date ? date('d-m-Y', strtotime($coupon->end_date)) : '' }}</td>
                                <td>{{ $coupon->usage_limit }}</td>
                                <td>{{ $coupon->per_user_limit }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                                        onclick="delete_modal('{{ route('coupons.delete',$coupon->id) }}')"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php    $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-status').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const couponId = this.getAttribute('data-id');
            fetch(`/coupons/${couponId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    console.log('Status changed to:', data.new_status);
                } else {
                    alert('Failed to change status');
                }
            });
        });
    });
});
</script>
