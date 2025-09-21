<div class="page-content">
    <div class="container py-5">
        <!-- Order Header -->
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h1 class="fs-3 fw-bold text-primary mb-0">Order #{{ $order->order_number }}</h1>
                <p class="text-muted mb-0">Placed on {{ date('d M Y, h:i A', strtotime($order->created_at)) }}</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('orders') }}" class="btn btn-outline-primary rounded-3 px-4">
                    <i class="fas fa-arrow-left me-2"></i>Back to Orders
                </a>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card shadow-sm rounded-4 border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between position-relative status-timeline">
                    <div class="status-point {{ in_array(strtolower($order->status), ['ordered', 'packed', 'dispatched', 'delivered']) ? 'active' : '' }}">
                        <div class="status-icon"><i class="bi bi-cart-check"></i></div>
                        <p class="small mt-2">Ordered</p>
                    </div>
                    <div class="status-point {{ in_array(strtolower($order->status), ['packed', 'dispatched', 'delivered']) ? 'active' : '' }}">
                        <div class="status-icon"><i class="bi bi-box-seam"></i></div>
                        <p class="small mt-2">Packed</p>
                    </div>
                    <div class="status-point {{ in_array(strtolower($order->status), ['dispatched', 'delivered']) ? 'active' : '' }}">
                        <div class="status-icon"><i class="bi bi-truck"></i></div>
                        <p class="small mt-2">Dispatched</p>
                    </div>
                    <div class="status-point {{ strtolower($order->status) == 'delivered' ? 'active' : '' }}">
                        <div class="status-icon"><i class="bi bi-check-circle"></i></div>
                        <p class="small mt-2">Delivered</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column - Order Items -->
            <div class="col-lg-8">
                <div class="card shadow-sm rounded-4 border-0 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h2 class="fs-5 fw-bold mb-0">Order Items</h2>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach ($order->order_items as $index => $item)
                            <div class="list-group-item border-0 py-3 px-4">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <div class="d-flex align-items-center">
                                            <div class="product-img-placeholder bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="fas fa-box text-muted fs-4"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h3 class="fs-6 fw-bold mb-1">{{ $item->product_name }}</h3>
                                                <p class="small text-muted mb-0">Unit: {{ $item->unit }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-md-center mt-2 mt-md-0">
                                        <span class="quantity-badge">Qty: {{ number_format($item->quantity, 3) }}</span>
                                    </div>
                                    <div class="col-md-3 text-md-end mt-2 mt-md-0">
                                        <p class="fw-bold mb-0">₹{{ number_format($item->price, 2) }}</p>
                                        <p class="small text-muted mb-0">₹{{ number_format($item->price / $item->quantity, 2) }} each</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Customer Info & Order Summary -->
            <div class="col-lg-4">
                <!-- Customer Info Card -->
                <div class="card shadow-sm rounded-4 border-0 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h2 class="fs-5 fw-bold mb-0">Customer Details</h2>
                    </div>
                    <div class="card-body">
                        <div class="customer-info mb-3 d-flex align-items-center">
                            <i class="fas fa-user text-primary me-2"></i>
                            <span>{{ $order->user_name }}</span>
                        </div>
                        <div class="customer-info mb-3 d-flex align-items-center">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <span>{{ $order->user_email }}</span>
                        </div>
                        <div class="customer-info d-flex align-items-center">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <span>{{ $order->user_phone }}</span>
                        </div>
                        <div class="customer-info d-flex align-items-center mt-3">
                            <i class="fas fa-credit-card text-primary me-2"></i>
                            <span><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Card -->
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-white py-3 border-0">
                        <h2 class="fs-5 fw-bold mb-0">Order Summary</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Order Status:</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill status-badge-{{ strtolower($order->status) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @php
                                        $statusOptions = [
                                            'placed' => 'Placed',
                                            'packed' => 'Packed',
                                            'dispatched' => 'Dispatched',
                                            'delivered' => 'Delivered'
                                        ];
                                        @endphp
                                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" id="statusForm">
                                            @csrf
                                            @foreach ($statusOptions as $value => $label)
                                            @if ($order->status != $value)
                                            <li>
                                                <button type="submit" class="dropdown-item" name="status" value="{{ $value }}">
                                                    {{ $label }}
                                                </button>
                                            </li>
                                            @endif
                                            @endforeach
                                        </form>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₹{{ number_format($order->price_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <span class="text-success">-₹{{ number_format($order->total_discount, 2) }}</span>
                        </div>
                        @if(isset($order->tax) && $order->tax > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>₹{{ number_format($order->tax, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Charges:</span>
                            <span>₹{{ number_format($order->shipping ?? 0, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total Amount:</span>
                            <span class="fw-bold text-primary fs-5">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help and Support -->
        <div class="support-section text-center mt-5">
            <a href="{{ route('order-invoice', $order->id) }}" target="_blank" class="btn btn-primary rounded-3 px-4 me-2">
                <i class="fas fa-print me-2"></i>Print Invoice
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap Icons (Required for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

<script>
// Fallback for Bootstrap Icons
document.addEventListener('DOMContentLoaded', function() {
    // Check if Bootstrap Icons are loaded
    if (!document.querySelector('link[href*="bootstrap-icons"]')) {
        // Load Bootstrap Icons if not already loaded
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css';
        document.head.appendChild(link);
    }
    
    // Ensure icons are visible after load
    setTimeout(function() {
        var statusIcons = document.querySelectorAll('.status-icon .bi');
        statusIcons.forEach(function(icon) {
            if (icon.offsetWidth === 0 || icon.offsetHeight === 0) {
                icon.style.fontSize = '1.25rem';
                icon.style.display = 'inline-block';
            }
        });
    }, 100);
});
</script>

<style>
    .container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
    }

    /* Status Timeline */
    .status-timeline {
        position: relative;
    }

    .status-timeline::before {
        content: "";
        position: absolute;
        top: 35px;
        left: 10%;
        width: 80%;
        height: 4px;
        background-color: #e9ecef;
        z-index: 1;
    }

    .status-point {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 60px;
    }

    .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #f8f9fa;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #6c757d;
        margin: 0 auto;
    }

    .status-point.active .status-icon {
        background-color: #e3f2fd;
        border-color: #0d6efd;
        color: #0d6efd;
    }

    /* Status Badges */
    .status-badge-placed {
        background-color: #ffc107;
        color: #000;
    }

    .status-badge-packed {
        background-color: #0d6efd;
        color: #fff;
    }

    .status-badge-dispatched {
        background-color: #198754;
        color: #fff;
    }

    .status-badge-delivered {
        background-color: #dc3545;
        color: #fff;
    }

    /* Product Image Placeholder */
    .product-img-placeholder {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Customer Info */
    .customer-info {
        display: flex;
        align-items: center;
    }
    
    /* Bootstrap Icons fixes */
    .bi {
        font-family: "bootstrap-icons" !important;
        font-style: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    /* Ensure status icons are visible */
    .status-icon .bi {
        font-size: 1.25rem;
        display: inline-block;
    }
    
    /* Icon fixes for Font Awesome (if used elsewhere) */
    .fas, .far, .fab {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands" !important;
        font-weight: 900;
    }
    
    .fas {
        font-weight: 900;
    }
    
    .far {
        font-weight: 400;
    }
    
    .fab {
        font-weight: 400;
    }

    /* Quantity Badge */
    .quantity-badge {
        background-color: #f8f9fa;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.9rem;
    }

    /* Card hover effects */
    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-3px);
    }

    /* Support Section */
    .support-section {
        margin-top: 40px;
        padding: 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .status-timeline::before {
            left: 5%;
            width: 90%;
        }

        .status-point {
            width: 50px;
        }

        .status-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>