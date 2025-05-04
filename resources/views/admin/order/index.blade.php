<div class="page-content">
    <!-- Header Section -->
    <div class="card shadow-sm border-0 mb-4 bg-light rounded-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="card-title m-0 text-primary fw-bold">
                <i class="fas fa-box-open me-2 text-gradient"></i>{{ $page_title ?? 'Orders' }}
            </h4>
        </div>
    </div>

    <!-- Order Card View -->
    @if(count($list_items) > 0)
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($list_items as $order)
        <div class="col">
            <div class="card order-card h-100 shadow rounded-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-gradient">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-receipt text-white me-2"></i>
                        <span class="text-white"><strong>Order #{{ $order->order_no }}</strong></span>
                    </div>
                    <span class="badge text-uppercase" style="background-color: {{ $order->status == 'placed' ? '#f39c12' : ($order->status == 'packed' ? '#3498db' : ($order->status == 'dispatched' ? '#27ae60' : '#e74c3c')) }};">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-0">₹{{ number_format($order->total_amount, 2) }}</h5>
                            <small class="text-muted">Total Amount</small>
                        </div>
                        <div>
                            <p class="mb-0 text-muted">{{ date('d M Y, h:i A', strtotime($order->created_at)) }}</p>
                            <small class="text-muted">Ordered On</small>
                        </div>
                    </div>

                    <h6 class="text-secondary mb-3">Items</h6>
                    <div class="order-items">
                        @foreach ($order->order_items as $item)
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="fw-semibold mb-0">{{ $item->product_name }}</p>
                            </div>
                            <div>
                                <small class="text-muted">₹{{ number_format($item->product_price, 2) }} x {{ $item->quantity }}</small>
                            </div>
                            <div class="fw-bold text-success">₹{{ number_format($item->price, 2) }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer bg-white border-top-0 text-end p-3">
                    <div class="col-6">
                        <a href="{{ route('orders.details',$order->id) }}" class="btn btn-sm btn-primary">Details</a>
                    </div>
                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline" id="statusForm{{ $order->id }}">
                        @csrf
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="statusDropdown{{ $order->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                Update Status
                            </button>
                            @php
                            $statusOptions = [
                            'placed' => 'Placed',
                            'packed' => 'Packed',
                            'dispatched' => 'Dispatched',
                            'delivered' => 'Delivered'
                            ];
                            @endphp
                            <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $order->id }}">
                                @foreach ($statusOptions as $value => $label)
                                @if ($order->status != $value)
                                <li>
                                    <button type="submit" class="dropdown-item" name="status" value="{{ $value }}">{{ $label }}</button>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center mt-5">
        <img src="{{asset('assets/images/empty.png')}}" alt="No orders" width="130" class="img-fluid mb-3">
        <h5 class="text-muted">No orders found</h5>
    </div>
    @endif
</div>
<style>
    .order-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }

    .order-card:hover {
        transform: translateY(-10px);
        box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1);
    }

    .bg-gradient {
        background: linear-gradient(135deg, #4e73df, #1cc88a);
    }

    .badge {
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 50px;
        padding: 5px 15px;
    }

    .order-items div {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .order-items .fw-semibold {
        font-size: 1rem;
    }

    .order-items .fw-bold {
        font-size: 1.1rem;
    }

    .dropdown-menu {
        min-width: 150px;
    }

    .dropdown-item {
        font-size: 0.875rem;
        padding: 8px 15px;
    }

    .dropdown-item:hover {
        background-color: #4e73df;
        color: white;
    }

    .form-select-sm {
        font-size: 0.875rem;
        padding: 6px 12px;
    }

    .order-card .card-header {
        padding: 16px 20px;
        border-radius: 15px 15px 0 0;
    }

    .order-card .card-body {
        padding: 20px;
    }

    .order-card .card-footer {
        padding: 15px 20px;
        border-radius: 0 0 15px 15px;
    }

    /* Order status colors */
    .badge.bg-light {
        background-color: rgba(255, 255, 255, 0.8);
        color: #333;
    }

    /* Header Icon */
    .fa-box-open {
        color: #fff;
    }

    /* Hover transition */
    .order-card:hover {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.12);
        transform: translateY(-5px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .order-items div {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-card .card-body,
        .order-card .card-footer {
            padding: 1rem;
        }
    }
</style>