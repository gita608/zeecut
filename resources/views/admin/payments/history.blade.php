<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section with improved styling -->
            <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded shadow-sm">
                <div>
                    <h3 class="card-title mb-1 text-primary">
                        <i class="fas fa-receipt mr-2"></i>
                        Payment History
                    </h3>
                    <small class="text-muted">Order #{{ $payment->order->order_no ?? 'N/A' }}</small>
                </div>
                <div class="card-tools">
                    <span class="badge badge-pill badge-lg badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'partial' ? 'warning' : 'danger') }} text-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'partial' ? 'warning' : 'danger') }}">
                        <i class="fas fa-{{ $payment->status === 'completed' ? 'check-circle' : ($payment->status === 'partial' ? 'clock' : 'times-circle') }} mr-1"></i>
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>

            <!-- Payment Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="text-primary mb-2">
                                <i class="fas fa-user-circle fa-2x"></i>
                            </div>
                            <h6 class="card-title text-muted mb-1">Customer</h6>
                            <p class="card-text font-weight-bold">{{ $payment->user->name ?? 'N/A' }}</p>
                            <small class="text-muted">
                                <i class="fas fa-credit-card mr-1"></i>
                                {{ $payment->payment_method == 1 ? 'Cash on Delivery' : 'Pay Later' }}
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="text-info mb-2">
                                <i class="fas fa-calculator fa-2x"></i>
                            </div>
                            <div class="row">
                                <div class="col-6 border-right">
                                    <h6 class="text-muted mb-1">Total Amount</h6>
                                    <p class="font-weight-bold text-dark mb-0">{{ format_price($payment->total_amount) }}</p>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted mb-1">Paid Amount</h6>
                                    <p class="font-weight-bold text-success mb-0">{{ format_price($payment->paid) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="text-warning mb-2">
                                <i class="fas fa-hourglass-half fa-2x"></i>
                            </div>
                            <h6 class="card-title text-muted mb-1">Pending Amount</h6>
                            <p class="card-text font-weight-bold text-warning h5">{{ format_price($payment->calculated_pending_amount) }}</p>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Last Payment: {{ $payment->paid_date ? $payment->paid_date->format('d M Y') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history mr-2"></i>
                        Payment Transaction History
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3">
                                        <i class="fas fa-hashtag mr-1 text-muted"></i>
                                        Serial No.
                                    </th>
                                    <th class="border-0 px-4 py-3">
                                        <i class="fas fa-money-bill-wave mr-1 text-muted"></i>
                                        Amount Paid
                                    </th>
                                    <th class="border-0 px-4 py-3">
                                        <i class="fas fa-calendar-check mr-1 text-muted"></i>
                                        Payment Date & Time
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($histories as $history)
                                    <tr class="border-bottom">
                                        <td class="px-4 py-3">
                                            <span class="badge badge-outline-primary text-dark">
                                                {{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-weight-bold text-success">
                                                <i class="fas fa-rupee-sign mr-1"></i>
                                                {{ format_price($history->amount) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($history->paid_at)
                                                <div>
                                                    <span class="font-weight-bold">{{ \Carbon\Carbon::parse($history->paid_at)->format('d M Y') }}</span>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($history->paid_at)->format('h:i A') }}
                                                    </small>
                                                </div>
                                            @else
                                                <span class="text-muted font-italic">
                                                    <i class="fas fa-question-circle mr-1"></i>
                                                    Not Available
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                <h6>No Payment History Found</h6>
                                                <p class="mb-0">There are no payment transactions recorded for this order yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination if needed -->
                @if($histories->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-center">
                        {{ $histories->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>