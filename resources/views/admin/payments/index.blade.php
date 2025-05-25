<div class="page-content">
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <div class="container">
                <h2>Payment List</h2>
                <div class="table-responsive">
                    <table id="table1" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Order</th>
                                <th>Method</th>
                                <th>Cash Amount</th>
                                <th>Pay Later</th>
                                <th>Amount Paid</th>
                                <th>Status</th>
                                <th>Paid Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                <td>#{{ $payment->order->order_no ?? 'N/A' }}</td>
                                <td>{{ $payment->payment_method == 1 ? 'COD' : 'Pay Later' }}</td>
                                <td>{{ $payment->pending_amount }}</td>
                                <td>{{ $payment->pay_later_credit ?? '' }}</td>
                                <td>{{ $payment->paid ?? '' }}</td>
                                <td>{{ ucfirst($payment->status) }}</td>
                                <td>{{ $payment->paid_date ? date('d-m-Y', strtotime($payment->paid_date)) : '' }}</td>
                                <td>
                                    <a href="javascript:void(0);" onclick="show_ajax_modal('{{ route('payments.history', $payment->id) }}', 'History')"  class="btn btn-sm btn-info">View History</a>
                                    <a href="javascript:void(0);" onclick="show_small_modal('{{ route('payments.add_payment', $payment->id) }}', 'Add Payment')" class="btn btn-sm btn-success">Add Payment</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>