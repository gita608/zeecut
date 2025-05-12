<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            font-size: 14px;
        }
        .invoice-container {
            margin: 0 auto;
            background-color: #fff;
        }
        .invoice-header {
            background-color: #3498db;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .invoice-body {
            padding: 20px;
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
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
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
            <div>
                <h1>INVOICE</h1>
                <p>Order #{{ $order->id }}</p>
            </div>
            <div class="text-right">
                <span class="status-badge {{ strtolower($order->status) }}">{{ $order->status }}</span>
            </div>
        </div>
        
        <div class="invoice-body">
            <div>
                <h2 class="info-header">Billing Information</h2>
                <p>
                    <strong>{{ $order->user_name }}</strong><br>
                    Email: {{ $order->user_email }}<br>
                    Phone: {{ $order->user_phone }}<br>
                    @if(isset($order->address))
                        Address: {{ $order->address }}<br>
                    @endif
                </p>
                
                <h2 class="info-header">Invoice Details</h2>
                <p>
                    <strong>Invoice Number:</strong> INV-{{ sprintf('%06d', $order->id) }}<br>
                    <strong>Order Date:</strong> {{ date('F d, Y', strtotime($order->created_at)) }}<br>
                    <strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}<br>
                    <strong>Order Status:</strong> {{ $order->status }}<br>
                </p>
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
                    @php $subtotal = 0; @endphp
                    @foreach($order->order_items as $item)
                        @php 
                            $itemTotal = $item->quantity * $item->price;
                            $subtotal += $itemTotal;
                        @endphp
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td class="text-right">${{ number_format($itemTotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <table class="invoice-summary-table">
                <tr>
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Subtotal:</td>
                    <td class="summary-value">${{ number_format($subtotal, 2) }}</td>
                </tr>
                @if(isset($order->discount) && $order->discount > 0)
                <tr>
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Discount:</td>
                    <td class="summary-value">-${{ number_format($order->discount, 2) }}</td>
                </tr>
                @endif
                @if(isset($order->tax) && $order->tax > 0)
                <tr>
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Tax ({{ isset($order->tax_rate) ? $order->tax_rate.'%' : '' }}):</td>
                    <td class="summary-value">${{ number_format($order->tax, 2) }}</td>
                </tr>
                @endif
                @if(isset($order->shipping) && $order->shipping > 0)
                <tr>
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Shipping:</td>
                    <td class="summary-value">${{ number_format($order->shipping, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="summary-spacer"></td>
                    <td class="summary-label">Grand Total:</td>
                    <td class="summary-value">${{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
            
            <div class="notice">
                <strong>Thank you for your business!</strong><br>
                If you have any questions about this invoice, please contact our customer support team.
            </div>
            
            <div class="company-details">
                <strong>Your Company Name</strong><br>
                123 Business Street, City, State, ZIP<br>
                Email: support@yourcompany.com | Phone: (123) 456-7890
            </div>
        </div>
        
        <div class="invoice-footer">
            <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>