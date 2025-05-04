<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="card-title mb-0 fw-bold text-gray-800">Product Stock Management</h5>
                            <p class="text-muted mb-0">Track and manage your product quantities</p>
                        </div>
                    </div>

                    <div class="table-responsive rounded-3">
                        <table id="table1" class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Product</th>
                                    <th class="text-center">Stocks</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach ($list_items as $item)
                                <tr class="hover-shadow inventory-row">
                                    <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-3">
                                                <div class="symbol-label bg-light">
                                                    <span class="text-primary fs-4 fw-bold">{{ substr($item->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $item->name }}</h6>
                                                <small class="text-muted">Product ID: #{{ $item->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php 
                                            $quantity = $item->stock->quantity ?? 0;
                                            $stockPercentage = min(100, ($quantity / ($item->ideal_stock ?? 100)) * 100);
                                        @endphp
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="stock-progress-container mb-2" style="width: 120px;">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar 
                                                        @if($quantity > 10) bg-success
                                                        @elseif($quantity > 3) bg-warning
                                                        @else bg-danger
                                                        @endif" 
                                                        role="progressbar" 
                                                        style="width: {{ $stockPercentage }}%" 
                                                        aria-valuenow="{{ $quantity }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="{{ $item->ideal_stock ?? 100 }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <span id="quantity-{{ $item->id }}" class="fw-bold 
                                                @if($quantity > 10) text-success
                                                @elseif($quantity > 3) text-warning
                                                @else text-danger
                                                @endif">
                                                {{ $quantity }} units
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span id="status-badge-{{ $item->id }}" class="badge px-3 py-2
                                            @if($quantity > 10) bg-success bg-opacity-10 text-success
                                            @elseif($quantity > 3) bg-warning bg-opacity-10 text-warning
                                            @else bg-danger bg-opacity-10 text-danger
                                            @endif">
                                            @if($quantity > 10) In Stock
                                            @elseif($quantity > 3) Low Stock
                                            @else Critical
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-sm btn-success me-2 stock-action-btn" 
                                                onclick="showStockEditor({{ $item->id }}, 'add')"
                                                data-bs-toggle="tooltip" title="Add Stock">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger stock-action-btn" 
                                                onclick="showStockEditor({{ $item->id }}, 'subtract')"
                                                data-bs-toggle="tooltip" title="Remove Stock">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Stock Editor (Hidden by Default) -->
                                        <div id="stock-editor-{{ $item->id }}" class="stock-editor d-none mt-2">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input type="number" id="stock-input-{{ $item->id }}" 
                                                    class="form-control text-center stock-input me-2" 
                                                    placeholder="0" value="1" min="1" style="width: 80px;">
                                                <button class="btn btn-sm btn-primary" 
                                                    onclick="submitStockUpdate({{ $item->id }})">
                                                    <i class="fas fa-check"></i> Confirm
                                                </button>
                                                <button class="btn btn-sm btn-light ms-1" 
                                                    onclick="cancelStockEdit({{ $item->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1090"></div>

<script>
    // Initialize tooltips
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    function showStockEditor(productId, action) {
        // Hide all other open editors first
        $('.stock-editor').addClass('d-none');
        
        // Show the editor for this product
        const editor = $(`#stock-editor-${productId}`);
        editor.removeClass('d-none');
        
        // Reset input value and focus
        $(`#stock-input-${productId}`).val(1).focus();
        
        // Store the action type in the editor for later use
        editor.data('action', action);
    }
    
    function cancelStockEdit(productId) {
        $(`#stock-editor-${productId}`).addClass('d-none');
    }
    
    function submitStockUpdate(productId) {
        const editor = $(`#stock-editor-${productId}`);
        const action = editor.data('action');
        const inputElement = $(`#stock-input-${productId}`);
        const quantity = parseInt(inputElement.val());
        const quantityElement = $(`#quantity-${productId}`);
        
        // Validate input
        if (isNaN(quantity) || quantity <= 0) {
            showToast('Please enter a valid quantity (minimum 1)', 'warning');
            inputElement.focus();
            return;
        }
        
        // Set loading state
        const originalQuantity = quantityElement.text();
        quantityElement.html(`<span class="spinner-border spinner-border-sm" role="status"></span>`);
        
        // Hide the editor
        editor.addClass('d-none');
        
        $.ajax({
            url: '{{ route("stocks.update_quantity") }}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                product_id: productId,
                quantity: quantity,
                action: action
            },
            success: function (data) {
                if (data.success) {
                    // Update quantity display
                    quantityElement.text(data.quantity);
                    
                    // Update status badge
                    updateStockStatus(productId, data.quantity);
                    
                    // Show success message
                    showToast(`Stock ${action === 'add' ? 'increased' : 'decreased'} successfully`, 'success');
                } else {
                    quantityElement.text(originalQuantity);
                    showToast(data.message || 'Error updating quantity', 'danger');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                quantityElement.text(originalQuantity);
                showToast('Something went wrong!', 'danger');
            }
        });
    }
    
    function updateStockStatus(productId, newQuantity) {
        // Update the quantity text color
        const quantityElement = $(`#quantity-${productId}`);
        quantityElement.removeClass('text-success text-warning text-danger');
        
        if (newQuantity > 10) {
            quantityElement.addClass('text-success');
        } else if (newQuantity > 3) {
            quantityElement.addClass('text-warning');
        } else {
            quantityElement.addClass('text-danger');
        }
        
        // Update the status badge
        const statusBadge = $(`#status-badge-${productId}`);
        statusBadge.removeClass('bg-success bg-warning bg-danger text-success text-warning text-danger bg-opacity-10');
        
        if (newQuantity > 10) {
            statusBadge.addClass('bg-success bg-opacity-10 text-success').text('In Stock');
        } else if (newQuantity > 3) {
            statusBadge.addClass('bg-warning bg-opacity-10 text-warning').text('Low Stock');
        } else {
            statusBadge.addClass('bg-danger bg-opacity-10 text-danger').text('Critical');
        }
        
        // Add pulse animation to highlight the change
        quantityElement.addClass('animate__animated animate__pulse');
        setTimeout(() => {
            quantityElement.removeClass('animate__animated animate__pulse');
        }, 1000);
    }
    
    function showToast(message, type) {
        const iconMap = {
            'success': 'check-circle',
            'warning': 'exclamation-triangle',
            'danger': 'exclamation-circle',
            'info': 'info-circle'
        };
        
        const toast = $(`
            <div class="toast show mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-${type} text-white">
                    <i class="fas fa-${iconMap[type]} me-2"></i>
                    <strong class="me-auto">Notification</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `);
        
        $('#toast-container').append(toast);
        
        // Auto-remove toast after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
</script>

<style>
    /* .card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        background-color: #ffffff;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }
    
    .card:hover {
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    } */
    
    .symbol {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 0.5rem;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
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
        width: 100%;
        height: 100%;
        border-radius: inherit;
    }

    .inventory-row {
        border-radius: 8px;
        transition: all 0.25s ease;
    }

    .inventory-row:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    
    .stock-progress-container {
        height: 8px;
    }
    
    .stock-input {
        font-weight: 500;
        border-radius: 5px;
        border: 1px solid #e2e2e2;
        transition: all 0.2s;
    }
    
    .stock-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .stock-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .stock-action-btn:hover {
        transform: scale(1.1);
    }
    
    /* Animation classes */
    .animate__animated {
        animation-duration: 0.5s;
    }
    
    .animate__pulse {
        animation-name: pulse;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    /* Custom scrollbar for table */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Search input style */
    .search-input {
        border-radius: 20px;
        padding-left: 40px;
        border: 1px solid #e2e2e2;
        transition: all 0.3s;
    }
    
    .search-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Toast styles */
    .toast {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .toast-header {
        border-bottom: none;
    }
</style>