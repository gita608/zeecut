<div class="page-content">
    <!-- Header Section -->
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="card-title m-0 text-primary fw-bold">
                <i class="fas fa-shopping-bag me-2"></i>{{ $page_title ?? 'Orders' }}
            </h4>
            <span class="badge bg-primary fs-6">{{ count($list_items) }} Orders</span>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('orders') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="placed" {{ $filters['status'] == 'placed' ? 'selected' : '' }}>Placed</option>
                        <option value="packed" {{ $filters['status'] == 'packed' ? 'selected' : '' }}>Packed</option>
                        <option value="dispatched" {{ $filters['status'] == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                        <option value="delivered" {{ $filters['status'] == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_range" class="form-label">Date Range</label>
                    <select name="date_range" id="date_range" class="form-select" onchange="handleDateRangeChange()">
                        <option value="">Select Date Range</option>
                        <option value="today" {{ $filters['date_range'] == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ $filters['date_range'] == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="last_7_days" {{ $filters['date_range'] == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="this_month" {{ $filters['date_range'] == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ $filters['date_range'] == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="two_months_ago" {{ $filters['date_range'] == 'two_months_ago' ? 'selected' : '' }}>2 Months Ago</option>
                        <option value="custom" {{ $filters['date_range'] == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <div class="col-md-2" id="custom_date_from" style="display: none;">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $filters['date_from'] }}">
                </div>
                <div class="col-md-2" id="custom_date_to" style="display: none;">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $filters['date_to'] }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('orders') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Card View -->
    @if(count($list_items) > 0)
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        @foreach ($list_items as $order)
        <div class="col">
            <div class="card order-card h-100 shadow-sm">
                <!-- Card Header -->
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <div>
                        <h6 class="mb-0 fw-bold">#{{ $order->order_no }}</h6>
                        <small class="text-muted">{{ date('M d, Y', strtotime($order->created_at)) }}</small>
                        <br>
                        <small class="text-muted">{{ date('h:i A', strtotime($order->created_at)) }}</small>
                    </div>
                    <span class="badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <!-- Card Body -->
                <div class="card-body p-3">
                    <!-- Customer Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user text-primary me-2"></i>
                            <strong>{{ $order->user_name ?? 'Unknown User' }}</strong>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-phone text-success me-2"></i>
                            <small class="text-muted">{{ $order->user_phone ?? 'N/A' }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-info me-2"></i>
                            <small class="text-muted">{{ $order->user_email ?? 'N/A' }}</small>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="mb-0 text-success">₹{{ number_format($order->total_amount, 2) }}</h6>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-0 text-primary">{{ count($order->order_items) }}</h6>
                            <small class="text-muted">Items</small>
                        </div>
                    </div>
                    <div class="row text-center mb-2">
                        <div class="col-12">
                            <small class="text-muted">Items Total: ₹{{ number_format($order->price_amount, 2) }}</small>
                        </div>
                    </div>

                    <!-- Items Preview -->
                    <div class="mb-3">
                        <h6 class="text-secondary mb-2">Items</h6>
                        @foreach ($order->order_items->take(2) as $item)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="item-name text-truncate" style="max-width: 120px;" title="{{ $item->product_name }}">{{ $item->product_name }}</span>
                            <small class="text-muted">x{{ number_format($item->quantity, 3) }}</small>
                            <small class="fw-bold text-success">₹{{ number_format($item->price, 2) }}</small>
                        </div>
                        @endforeach
                        @if(count($order->order_items) > 2)
                        <small class="text-muted">+{{ count($order->order_items) - 2 }} more items</small>
                        @endif
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light p-3">
                    <div class="d-flex gap-2">
                        <a href="{{ route('orders.details', $order->id) }}" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-eye"></i> Details
                        </a>
                        <a href="{{ route('sticker.print', $order->id) }}" target="_blank" class="btn btn-success btn-sm" title="Print Sticker">
                            <i class="fas fa-print"></i>
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="statusDropdown{{ $order->id }}" data-bs-toggle="dropdown">
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
                                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" id="statusForm{{ $order->id }}">
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
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center mt-5">
        <i class="fas fa-shopping-bag text-muted" style="font-size: 4rem;"></i>
        <h5 class="text-muted mt-3">No orders found</h5>
        <p class="text-muted">There are no orders matching your current filters.</p>
        <a href="{{ route('orders') }}" class="btn btn-primary">
            <i class="fas fa-refresh me-2"></i>Clear Filters
        </a>
    </div>
    @endif
</div>
<style>
    /* Order Card Styling */
    .order-card {
        border: 1px solid #e9ecef;
    }


    /* Status Badge Colors */
    .status-placed {
        background-color: #ffc107;
        color: #000;
    }

    .status-packed {
        background-color: #0d6efd;
        color: #fff;
    }

    .status-dispatched {
        background-color: #198754;
        color: #fff;
    }

    .status-delivered {
        background-color: #dc3545;
        color: #fff;
    }

    /* Card Header */
    .card-header {
        border-bottom: 1px solid #e9ecef;
    }

    /* Card Footer */
    .card-footer {
        border-top: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }

    /* Item name styling */
    .item-name {
        position: relative;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .row-cols-md-2 .col {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .row-cols-1 .col {
            margin-bottom: 1rem;
        }
    }
</style>

<script>
function handleDateRangeChange() {
    const dateRange = document.getElementById('date_range').value;
    const customFrom = document.getElementById('custom_date_from');
    const customTo = document.getElementById('custom_date_to');
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    
    // Hide custom date inputs by default
    customFrom.style.display = 'none';
    customTo.style.display = 'none';
    
    if (dateRange === 'custom') {
        // Show custom date inputs
        customFrom.style.display = 'block';
        customTo.style.display = 'block';
    } else if (dateRange) {
        // Calculate dates based on selection
        const today = new Date();
        let fromDate, toDate;
        
        switch(dateRange) {
            case 'today':
                fromDate = toDate = today;
                break;
            case 'yesterday':
                fromDate = toDate = new Date(today);
                fromDate.setDate(today.getDate() - 1);
                break;
            case 'last_7_days':
                fromDate = new Date(today);
                fromDate.setDate(today.getDate() - 6);
                toDate = today;
                break;
            case 'this_month':
                fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                toDate = today;
                break;
            case 'last_month':
                fromDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                toDate = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
            case 'two_months_ago':
                fromDate = new Date(today.getFullYear(), today.getMonth() - 2, 1);
                toDate = new Date(today.getFullYear(), today.getMonth() - 1, 0);
                break;
        }
        
        if (fromDate && toDate) {
            // Format dates as YYYY-MM-DD
            const formatDate = (date) => {
                return date.getFullYear() + '-' + 
                       String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                       String(date.getDate()).padStart(2, '0');
            };
            
            dateFrom.value = formatDate(fromDate);
            dateTo.value = formatDate(toDate);
        }
    } else {
        // Clear dates if no range selected
        dateFrom.value = '';
        dateTo.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    handleDateRangeChange();
    
    // Add form submission debugging
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const dateRange = document.getElementById('date_range').value;
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;
            
            console.log('Form submission:', {
                dateRange: dateRange,
                dateFrom: dateFrom,
                dateTo: dateTo
            });
        });
    }
});
</script>