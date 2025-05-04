<div class="page-content">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-4">
        <div>
            <h4 class="mb-3 mb-md-0 text-primary">Dashboard Overview</h4>
            <p class="text-muted mb-0">Welcome back! Here's what's happening with your store today.</p>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker dashboard-date me-2 mb-2 mb-md-0 d-flex align-items-center">
                <span class="input-group-text input-group-addon bg-white border-end-0"><i data-feather="calendar" class="text-primary"></i></span>
                <input type="text" class="form-control border-start-0 ps-0 bg-white" value="{{ now()->format('F j, Y') }}" readonly>
                <button class="btn btn-outline-primary ms-2 d-flex align-items-center">
                    <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>
    </div>

    <!-- First Row - Key Metrics -->
    <div class="row mb-4">
        <!-- Total Customers -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary-light">
                                <i data-feather="users" class="text-primary"></i>
                            </div>
                            <h6 class="card-title mb-0 ms-3">Total Customers</h6>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="icon-lg text-muted" data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="users" class="me-2"></i>View All</a>
                                <a class="dropdown-item" href="#"><i data-feather="plus" class="me-2"></i>Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">{{ number_format($customer_count) }}</h3>
                        <div class="badge bg-primary-light text-primary rounded-pill">
                            <i data-feather="trending-up" class="icon-xs me-1"></i>
                            <span>5.2%</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress progress-md">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-info-light">
                                <i data-feather="shopping-cart" class="text-info"></i>
                            </div>
                            <h6 class="card-title mb-0 ms-3">Total Orders</h6>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="icon-lg text-muted" data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="shopping-cart" class="me-2"></i>View Orders</a>
                                <a class="dropdown-item" href="#"><i data-feather="file-text" class="me-2"></i>Generate Report</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">{{ number_format($order_count) }}</h3>
                        <div class="badge bg-info-light text-info rounded-pill">
                            <i data-feather="trending-up" class="icon-xs me-1"></i>
                            <span>12.7%</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress progress-md">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-success-light">
                                <i data-feather="dollar-sign" class="text-success"></i>
                            </div>
                            <h6 class="card-title mb-0 ms-3">Total Revenue</h6>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="icon-lg text-muted" data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="dollar-sign" class="me-2"></i>View Payments</a>
                                <a class="dropdown-item" href="#"><i data-feather="download" class="me-2"></i>Export Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">₹{{ number_format($revenue, 2) }}</h3>
                        <div class="badge bg-success-light text-success rounded-pill">
                            <i data-feather="trending-up" class="icon-xs me-1"></i>
                            <span>8.3%</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress progress-md">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row - Additional Metrics -->
    <div class="row mb-4">
        <!-- Products -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Products</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="icon-lg text-muted" data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="package" class="me-2"></i>View Products</a>
                                <a class="dropdown-item" href="#"><i data-feather="plus" class="me-2"></i>Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="display-4 text-primary me-3">{{ number_format($product_count) }}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-primary-light text-primary rounded-pill me-2">Active</span>
                                <small class="text-muted">Last 30 days</small>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Inventory</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="icon-lg text-muted" data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="box" class="me-2"></i>View Inventory</a>
                                <a class="dropdown-item" href="#"><i data-feather="alert-triangle" class="me-2"></i>Low Stock</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="display-4 text-info me-3">{{ number_format($stock_count) }}</div>
                        <div class="flex-grow-1">
                            @if($low_stock_items > 0)
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-danger-light text-danger rounded-pill me-2">
                                    <i data-feather="alert-triangle" class="icon-xs me-1"></i>
                                    {{ $low_stock_items }} Low Stock
                                </span>
                            </div>
                            @else
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-success-light text-success rounded-pill me-2">
                                    <i data-feather="check-circle" class="icon-xs me-1"></i>
                                    Good
                                </span>
                            </div>
                            @endif
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Payments -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Payments</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="icon-lg text-muted" data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="credit-card" class="me-2"></i>View Payments</a>
                                <a class="dropdown-item" href="#"><i data-feather="dollar-sign" class="me-2"></i>Process Refund</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="display-4 text-success me-3">{{ number_format($payment_completed_count) }}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-success-light text-success rounded-pill me-2">Completed</span>
                                <small class="text-muted">This month</small>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Pending Orders</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="icon-lg text-muted" data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="clock" class="me-2"></i>View Pending</a>
                                <a class="dropdown-item" href="#"><i data-feather="truck" class="me-2"></i>Shipping Status</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="display-4 text-warning me-3">{{$order_pending_count}}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-warning-light text-warning rounded-pill me-2">Processing</span>
                                <small class="text-muted">Need action</small>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
</div>

<style>
    .card-statistics {
        border-left: 4px solid;
    }
    .card-statistics:nth-child(1) {
        border-left-color: #4e73df;
    }
    .card-statistics:nth-child(2) {
        border-left-color: #1cc88a;
    }
    .card-statistics:nth-child(3) {
        border-left-color: #36b9cc;
    }
    .icon-box {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .progress.progress-sm {
        height: 6px;
    }
    .activity-feed {
        max-height: 350px;
        overflow-y: auto;
    }
    .avatar-xs {
        width: 32px;
        height: 32px;
    }
    .chart-container {
        position: relative;
        height: 250px;
    }
</style>