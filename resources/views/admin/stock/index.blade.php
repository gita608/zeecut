<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="card-title mb-0 fw-semibold text-gray-800">{{ $page_title ?? 'Inventory
                                Management' }}</h5>
                            <p class="text-muted mb-0">Track and manage your product quantities</p>
                        </div>
                    </div>

                    <div class="table-responsive rounded-3">
                        <table id="stock-table" class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Product</th>
                                    <th class="text-center">Stock Level</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse ($list_items as $key => $item)
                                <tr class="hover-shadow">
                                    <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-3">
                                                <div class="symbol-label bg-light-primary">
                                                    <span class="text-primary fs-4">{{ substr($item->name, 0, 1)
                                                        }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $item->name }}</h6>
                                             </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <button class="btn btn-icon btn-sm btn-outline-danger rounded-circle me-2"
                                                onclick="updateQuantity({{ $item->id }}, 'decrease')">
                                                <i class="fas fa-minus"></i>
                                            </button>

                                            <div class="position-relative" style="width: 120px;">
                                                <div class="progress" style="height: 8px;">
                                                    @php
                                                    $quantity = $item->stock->quantity ?? 0;
                                                    $max = max($quantity, 10); // Just for visualization
                                                    $percentage = min(($quantity / $max) * 100, 100);
                                                    $color = $percentage < 20 ? 'bg-danger' : ($percentage < 50
                                                        ? 'bg-warning' : 'bg-success' ); @endphp <div
                                                        class="progress-bar {{ $color }}" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $quantity }}" aria-valuemin="0"
                                                        aria-valuemax="{{ $max }}">
                                                </div>
                                            </div>
                                            <span id="quantity-{{ $item->id }}"
                                                class="position-absolute top-0 start-50 translate-middle badge bg-dark">
                                                {{ $quantity }} in stock
                                            </span>
                                        </div>

                                        <button class="btn btn-icon btn-sm btn-outline-success rounded-circle ms-2"
                                            onclick="updateQuantity({{ $item->id }}, 'increase')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                    </div>
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <img src="{{ asset('images/empty.svg') }}" alt="Empty"
                                    style="width: 120px; opacity: 0.7;" class="mb-3">
                                <h6 class="text-muted">No products found</h6>
                                <p class="text-muted mb-0">Add your first product to get started</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                    </table>
                </div>

                {{-- @if($list_items->count())
                <div class="d-flex justify-content-between align-items-center mt-3 px-2">
                    <div class="text-muted">
                        Showing {{ $list_items->firstItem() }} to {{ $list_items->lastItem() }} of {{
                        $list_items->total() }} entries
                    </div>
                    <div>
                        {{ $list_items->links() }}
                    </div>
                </div>
                @endif --}}

            </div>
        </div>
    </div>
</div>
</div>

<script>
    function updateQuantity(productId, action) {
    // Add loading state
    const quantityElement = document.getElementById(`quantity-${productId}`);
    quantityElement.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span>`;
    
    fetch(`/admin/stocks/update-quantity`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ product_id: productId, action: action }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update quantity with animation
            quantityElement.innerHTML = `${data.quantity} in stock`;
            quantityElement.classList.add('animate__animated', 'animate__bounceIn');
            
            // Remove animation class after it completes
            setTimeout(() => {
                quantityElement.classList.remove('animate__animated', 'animate__bounceIn');
            }, 1000);
            
            // Show toast notification
            showToast(`${action === 'increase' ? 'Increased' : 'Decreased'} quantity successfully`, 'success');
        } else {
            showToast(data.message || 'Error updating quantity', 'danger');
        }
    })
    .catch(error => {
        showToast('Something went wrong!', 'danger');
        console.error(error);
    });
}

function showToast(message, type) {
    // Implement a toast notification system here
    // Example with Bootstrap toasts:
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    const toastContainer = document.getElementById('toast-container') || (() => {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '11';
        document.body.appendChild(container);
        return container;
    })();
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<style>
    .symbol {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 0.475rem;
    }

    .symbol-50px {
        width: 50px;
        height: 50px;
    }

    .symbol-label {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--kt-symbol-label-color);
        background-color: var(--kt-symbol-label-bg);
        width: 100%;
        height: 100%;
        border-radius: inherit;
    }

    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
</style>