<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice - Order #{{ $order->order_no }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.4;
        }
        .invoice-container {
            margin: 0 auto;
            background-color: #fff;
            width: 100%;
        }
        .invoice-header {
            background-color: #3498db;
            color: white;
            padding: 25px 20px;
            margin-bottom: 0;
            page-break-inside: avoid;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            float: left;
            line-height: 1.2;
        }
        .invoice-header .order-info {
            float: right;
            text-align: right;
            margin-top: 5px;
        }
        .invoice-header .order-info p {
            margin: 3px 0;
            font-size: 14px;
            font-weight: 500;
        }
        .invoice-header::after {
            content: "";
            display: table;
            clear: both;
        }
        .invoice-body {
            padding: 20px;
            clear: both;
        }
        .billing-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .billing-info, .invoice-details {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .invoice-details {
            padding-right: 0;
        }
        .info-header {
            font-size: 16px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #3498db;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .invoice-table th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #3498db;
            font-weight: 600;
        }
        .invoice-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .invoice-table .text-right {
            text-align: right;
        }
        
        .invoice-summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-summary-table td {
            padding: 5px;
        }
        .summary-spacer {
            width: 70%;
        }
        .summary-label {
            text-align: right;
            font-weight: 600;
            width: 15%;
        }
        .summary-value {
            text-align: right;
            width: 15%;
        }
        .grand-total {
            font-weight: 700;
            font-size: 16px;
            color: #3498db;
            border-top: 1px solid #3498db;
        }
        .invoice-footer {
            background-color: #f8f9fa;
            padding: 15px 20px;
            text-align: center;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #777;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            margin-top: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .status-badge.paid {
            background-color: #27ae60;
            color: white;
        }
        .status-badge.pending {
            background-color: #f39c12;
            color: white;
        }
        .status-badge.canceled {
            background-color: #e74c3c;
            color: white;
        }
        .logo {
            height: 50px;
        }
        .company-details {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .notice {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <div class="order-info">
                <p><strong>Order #{{ $order->order_no }}</strong></p>
                <p>Date: {{ date('M d, Y', strtotime($order->created_at)) }}</p>
                <span class="status-badge {{ strtolower($order->status) }}">{{ $order->status }}</span>
            </div>
        </div>
        
        <div class="invoice-body">
            <div class="billing-section">
                <div class="billing-info">
                    <h2 class="info-header">Billing Information</h2>
                    <p>
                        <strong>{{ $order->user_name }}</strong><br>
                        Email: {{ $order->user_email }}<br>
                        Phone: {{ $order->user_phone }}<br>
                        @if(isset($order->address))
                            Address: {{ $order->address }}<br>
                        @endif
                    </p>
                </div>
                
                <div class="invoice-details">
                    <h2 class="info-header">Invoice Details</h2>
                    <p>
                        <strong>Invoice Number:</strong> INV-{{ sprintf('%06d', $order->id) }}<br>
                        <strong>Order Date:</strong> {{ date('F d, Y', strtotime($order->created_at)) }}<br>
                        <strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}<br>
                        <strong>Order Status:</strong> {{ $order->status }}<br>
                    </p>
                </div>
            </div>
            
            <h2>Order Items</h2>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th width="40%">Item</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->order_items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ number_format($item->quantity, 3) }}</td>
                            <td>Rs. {{ number_format($item->price / $item->quantity, 2) }}</td>
                            <td class="text-right">Rs. {{ number_format($item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <table class="invoice-summary-table">
                <tr>
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Subtotal:</td>
                    <td class="summary-value">Rs. {{ number_format($order->price_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Discount:</td>
                    <td class="summary-value">-Rs. {{ number_format($order->total_discount, 2) }}</td>
                </tr>
                @if(isset($order->tax) && $order->tax > 0)
                <tr>
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Tax ({{ isset($order->tax_rate) ? $order->tax_rate.'%' : '' }}):</td>
                    <td class="summary-value">Rs. {{ number_format($order->tax, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Delivery Charges:</td>
                    <td class="summary-value">Rs. {{ number_format($order->shipping ?? 0, 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Grand Total:</td>
                    <td class="summary-value">Rs. {{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
            
            <div class="notice">
                <strong>Thank you for your business!</strong><br>
                If you have any questions about this invoice, please contact our customer support team.
            </div>
            
            <!-- <div class="company-details">
                <strong>{{ Env('APP_NAME') }}</strong><br>
                123 Business Street, City, State, ZIP<br>
                Email: support@yourcompany.com | Phone: (123) 456-7890
            </div> -->
        </div>
        
        <div class="invoice-footer">
            <p>&copy; {{ date('Y') }} {{ Env('APP_NAME') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>