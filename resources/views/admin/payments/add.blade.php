<div class="container p-1">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="bg-success text-white p-3 rounded shadow-sm">
                <h4 class="mb-0">Add Payment</h4>
                <p class="small mb-0">Order #{{ $payment->order->order_no ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="text-center mb-3">
                <div class="d-inline-block rounded-circle bg-light p-3 mb-2 shadow-sm border">
                    <span class="h3 mb-0 text-success">{{ substr($payment->user->name ?? 'NA', 0, 1) }}</span>
                </div>
                <h5 class="mb-0">{{ $payment->user->name ?? 'N/A' }}</h5>
                <p class="text-muted small">Customer</p>
            </div>
        </div>
    </div>

    <form action="{{ route('payments.store_payment', $payment->id) }}" method="POST">
        @csrf
        <div class="row g-3">
            <!-- Pending Amount Display -->
            <div class="col-md-12 text-center mb-3">
                <label class="form-label text-muted">Total Pending</label>
                <h3 class="text-success">₹{{ number_format(($payment->pending_amount + $payment->pay_later_credit) - $payment->paid, 2) }}</h3>
            </div>

            <!-- Payment Input -->
            <div class="col-md-12">
                <label class="form-label">Amount to Pay <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input type="number" name="amount" id="amount" class="form-control"
                        placeholder="0" step="1" min="1"
                        max="{{ ($payment->pending_amount + $payment->pay_later_credit) - $payment->paid }}" required>
                    <button type="button" class="btn btn-outline-secondary"
                        onclick="document.getElementById('amount').value='{{ ($payment->pending_amount + $payment->pay_later_credit) - $payment->paid }}'">
                        MAX
                    </button>
                </div>
            </div>

            <div class="col-md-12 mt-4 text-end">
                <button type="button" onclick="window.history.back()" class="btn btn-outline-secondary me-2">
                    Cancel
                </button>
                <button type="submit" class="btn btn-success">
                    Complete Payment
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const amountInput = document.getElementById('amount');
        const amount = parseFloat(amountInput.value);
        const maxAmount = parseInt(amountInput.getAttribute('max'));

        if (isNaN(amount) || amount <= 0 || !Number.isInteger(amount)) {
            e.preventDefault();
            amountInput.classList.add('is-invalid');
            alert('Please enter a valid whole number greater than zero.');
        } else if (amount > maxAmount) {
            e.preventDefault();
            amountInput.classList.add('is-invalid');
            alert('Amount cannot exceed the pending amount of ₹' + maxAmount);
        }
    });

    document.getElementById('amount').addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
</script>
