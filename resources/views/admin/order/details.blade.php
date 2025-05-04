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
                    <i class="bi bi-arrow-left me-2"></i>Back to Orders
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
                        <div class="status-icon"><i class="bi bi-house-check"></i></div>
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
                                            <div class="product-img-placeholder bg-light rounded">
                                                <div class="text-center text-muted">
                                                    <i class="bi bi-box fs-4"></i>
                                                </div>
                                            </div>
                                            <div class="ms-3">
                                                <h3 class="fs-6 fw-bold mb-1">{{ $item->product_name }}</h3>
                                                <p class="small text-muted mb-0">Unit: {{ $item->unit }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-md-center mt-2 mt-md-0">
                                        <span class="quantity-badge">Qty: {{ $item->quantity }}</span>
                                    </div>
                                    <div class="col-md-3 text-md-end mt-2 mt-md-0">
                                        <p class="fw-bold mb-0">₹{{ number_format($item->product_price * $item->quantity, 2) }}</p>
                                        <p class="small text-muted mb-0">₹{{ number_format($item->product_price, 2) }} each</p>
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
                        <div class="customer-info mb-3">
                            <i class="bi bi-person-circle text-primary me-2"></i>
                            <span>{{ $order->user_name }}</span>
                        </div>
                        <div class="customer-info mb-3">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span>{{ $order->user_email }}</span>
                        </div>
                        <div class="customer-info">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <span>{{ $order->user_phone }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Card -->
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-white py-3 border-0">
                        <h2 class="fs-5 fw-bold mb-0">Order Summary</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Order Status:</span>
                            <span class="badge rounded-pill status-badge-{{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items Total:</span>
                            <span>₹{{ number_format($order->total_amount - ($order->total_amount * 0.12), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Taxes (12%):</span>
                            <span>₹{{ number_format($order->total_amount * 0.12, 2) }}</span>
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
            <p class="text-muted mb-3">Need help with your order?</p>
            <button class="btn btn-primary rounded-3 px-4 me-2">
                <i class="bi bi-chat-text me-2"></i>Chat Support
            </button>
            <a href="#" class="btn btn-outline-secondary rounded-3 px-4">
                <i class="bi bi-question-circle me-2"></i>Help Center
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap Icons (Required for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

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
    .status-badge-ordered {
        background-color: #6c757d;
    }

    .status-badge-packed {
        background-color: #ffc107;
    }

    .status-badge-dispatched {
        background-color: #0dcaf0;
    }

    .status-badge-delivered {
        background-color: #198754;
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