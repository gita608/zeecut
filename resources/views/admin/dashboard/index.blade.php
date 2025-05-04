<!-- eCommerce Admin Dashboard -->
<div class="page-content">

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="page-title">
            <h1>Dashboard Overview</h1>
            <p class="date-display">April 29, 2025</p>
        </div>

        <!-- Metric Cards -->
        <div class="metric-cards">
            <div class="metric-card sales">
                <div class="metric-info">
                    <h3>Total Products</h3>
                    <p class="metric-value">{{ number_format($product_count) }}</p>
                    <p class="metric-change positive"><i class="fas fa-arrow-up"></i> 12% from last month</p>
                </div>
                <div class="metric-icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>


            <div class="metric-card orders">
                <div class="metric-info">
                    <h3>Total Orders</h3>
                    <p class="metric-value">{{ number_format($order_count) }}</p>
                    <p class="metric-change positive"><i class="fas fa-arrow-up"></i> 8% from last month</p>
                </div>
                <div class="metric-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>

            <div class="metric-card revenue">
                <div class="metric-info">
                    <h3>Revenue</h3>
                    <p class="metric-value">₹{{ number_format($revenue, 2) }}</p>
                    <p class="metric-change positive"><i class="fas fa-arrow-up"></i> 5% from last month</p>
                </div>
                <div class="metric-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>

            <div class="metric-card customers">
                <div class="metric-info">
                    <h3>Total Customers</h3>
                    <p class="metric-value">{{ number_format($customer_count) }}</p>
                    <p class="metric-change positive"><i class="fas fa-arrow-up"></i> 15% from last month</p>
                </div>
                <div class="metric-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Sales Analytics Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <h2>Sales Analytics</h2>
                <div class="chart-period-selector">
                    <button class="period-btn active">Weekly</button>
                    <button class="period-btn">Monthly</button>
                    <button class="period-btn">Yearly</button>
                </div>
            </div>
            <div class="chart-wrapper" style="height: 400px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="section-header">
            <h2>Recent Orders</h2>
            <a href="{{ route('orders') }}" class="view-all-btn">View All Orders</a>
        </div>
        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latest_orders as $order)
                        <tr>
                            <td>#{{ $order->order_no }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->ordered_date ? date('M d, Y', strtotime($order->ordered_date)) : '' }}</td>
                            <td><span class="order-status {{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span></td>
                            <td>₹{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(74, 108, 247, 0.4)');
            gradient.addColorStop(1, 'rgba(74, 108, 247, 0.05)');

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales ($)',
                        data: [3200, 2800, 4100, 3800, 5200, 4800, 6200],
                        backgroundColor: gradient,
                        borderColor: '#4a6cf7',
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4a6cf7',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animations: {
                        tension: {
                            duration: 1000,
                            easing: 'linear',
                            from: 0.4,
                            to: 0.4,
                            loop: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#343a40',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return `$${context.parsed.y.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Period buttons functionality
            const periodBtns = document.querySelectorAll('.period-btn');
            periodBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    periodBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    let newData = [],
                        newLabels = [];
                    if (this.textContent === 'Weekly') {
                        newLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                        newData = [3200, 2800, 4100, 3800, 5200, 4800, 6200];
                    } else if (this.textContent === 'Monthly') {
                        newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        newData = [18500, 22000, 19800, 24500, 28000, 25400, 30200, 29800, 32000, 36500, 38200, 42000];
                    } else {
                        newLabels = ['2020', '2021', '2022', '2023', '2024', '2025'];
                        newData = [180000, 250000, 310000, 375000, 420000, 320000];
                    }

                    salesChart.data.labels = newLabels;
                    salesChart.data.datasets[0].data = newData;
                    salesChart.update();
                });
            });
        });
    </script>
    <style>
        :root {
            --primary-color: #4a6cf7;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --grey-color: #e9ecef;
            --white-color: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        /* Main Content */
        .main-content {
            grid-area: main;
            padding: 2rem;
            overflow-y: auto;
        }

        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .date-display {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        /* Metric Cards */
        .metric-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background-color: var(--white-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .metric-info h3 {
            font-size: 1rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .metric-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .metric-change {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .metric-change.positive {
            color: var(--success-color);
        }

        .metric-change.negative {
            color: var(--danger-color);
        }

        .metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: white;
        }

        .sales .metric-icon {
            background-color: #4a6cf7;
        }

        .orders .metric-icon {
            background-color: #28a745;
        }

        .revenue .metric-icon {
            background-color: #17a2b8;
        }

        .customers .metric-icon {
            background-color: #ffc107;
        }

        /* Chart Container */
        .chart-container {
            background-color: var(--white-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-period-selector {
            display: flex;
            gap: 0.5rem;
        }

        .period-btn {
            padding: 0.4rem 0.8rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            background-color: var(--light-color);
        }

        .period-btn.active {
            background-color: var(--primary-color);
            color: white;
        }

        .chart-wrapper {
            height: 300px;
        }

        /* Section Headers */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .view-all-btn {
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        .view-all-btn:hover {
            text-decoration: underline;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .product-card {
            background-color: var(--white-color);
            border-radius: var(--border-radius);
            padding: 1rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: var(--transition);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .product-img {
            display: flex;
            justify-content: center;
            padding: 0.5rem;
        }

        .product-img img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .product-info h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .product-price {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .status {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.8rem;
        }

        .in-stock {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .out-of-stock {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .product-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
        }

        .action-btn.edit {
            background-color: rgba(74, 108, 247, 0.1);
            color: var(--primary-color);
        }

        .action-btn.delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .action-btn:hover {
            opacity: 0.8;
        }

        /* Orders Table */
        .orders-table-container {
            background-color: var(--white-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow-x: auto;
            margin-bottom: 2rem;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th,
        .orders-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--grey-color);
        }

        .orders-table th {
            font-weight: 600;
            color: var(--secondary-color);
            background-color: var(--light-color);
        }

        .orders-table tbody tr:hover {
            background-color: rgba(245, 247, 250, 0.5);
        }

        .order-status {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.8rem;
        }

        .delivered {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .dispatched {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .packed {
            background-color: rgba(23, 162, 184, 0.1);
            color: var(--info-color);
        }

        .placed {
            background-color: rgba(34, 181, 230, 0.1);
            color: var(--info-color);
        }

        .view-btn {
            padding: 0.4rem 1rem;
            border-radius: var(--border-radius);
            background-color: var(--light-color);
            color: var(--dark-color);
            font-size: 0.8rem;
        }

        .view-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .metric-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .page-content {
                grid-template-columns: 1fr;
                grid-template-areas:
                    "header"
                    "main";
            }

            .sidebar {
                position: fixed;
                left: -240px;
                height: 100%;
            }

            .sidebar.active {
                left: 0;
            }

            .dashboard-header {
                padding: 1rem;
            }

            .search-container {
                width: 200px;
            }

            .chart-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .metric-cards {
                grid-template-columns: 1fr;
            }

            .search-container {
                display: none;
            }

            .orders-table th:nth-child(3),
            .orders-table td:nth-child(3) {
                display: none;
            }
        }
    </style>
</div>