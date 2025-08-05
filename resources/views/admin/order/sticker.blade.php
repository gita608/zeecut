<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Order Item Stickers</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", Arial, sans-serif;
            background-color: #f5f5f5;
        }
        
        .page {
            width: 210mm;
            height: 287mm;
            padding: 5mm;
            box-sizing: border-box;
            background: white;
            position: relative;
            overflow: hidden;
        }
        
        .sticker {
            width: 59mm;
            height: 45mm;
            border: 1px solid #e0e0e0;
            padding: 2mm;
            box-sizing: border-box;
            margin: 1.5mm;
            background: white;
            float: left;
            position: relative;
            box-shadow: 0 0 3px rgba(0,0,0,0.1);
        }
        
        .sticker-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        .sticker-table td {
            padding: 1px 2px;
            vertical-align: top;
            font-size: 8px;
            line-height: 1.2;
        }
        
        .label {
            font-weight: bold;
            white-space: nowrap;
            width: 15mm;
            color: #333;
        }
        
        .value {
            word-break: break-word;
            color: #555;
        }
        
        .header {
            font-size: 9px;
            font-weight: bold;
            padding: 3px 0;
            text-align: center;
            background-color: #1976D2;
            color: white;
            margin: -2mm -2mm 2px -2mm;
        }
        
        .footer {
            font-size: 7px;
            text-align: center;
            padding: 3px 0;
            background-color: #f5f5f5;
            color: #666;
            margin: 2px -2mm -2mm -2mm;
            border-top: 1px solid #e0e0e0;
        }
        
        .logo-cell {
            text-align: center;
            padding: 2px;
        }
        
        .logo-img {
            max-width: 35mm;
            max-height: 15mm;
            width: auto;
            height: auto;
        }
        
        .row-highlight {
            background-color: #f9f9f9;
        }
        
        .row-break {
            clear: both;
            height: 0;
        }
        
        @media print {
            body {
                background-color: transparent;
            }
            .sticker {
                border: 1px solid #ddd;
                box-shadow: none;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        @foreach($order_items as $index => $item)
            <div class="sticker">
                <table class="sticker-table">
                    <tr>
                        <td colspan="2" class="header">ORDER #{{ $order->order_no }}</td>
                    </tr>
                    <tr class="row-highlight">
                        <td class="label">Customer:</td>
                        <td class="value">{{ $order->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Product:</td>
                        <td class="value">{{ Str::limit($item->product->name, 25) }}</td>
                    </tr>
                    <tr class="row-highlight">
                        <td class="label">Address:</td>
                        <td class="value">{{$order->user->place.','.$order->user->address}}</td>
                    </tr>
                    <tr>
                        <td class="label">Phone:</td>
                        <td class="value">{{$order->user->phone}}</td>
                    </tr>
                    <tr class="row-highlight">
                        <td class="label">Pincode:</td>
                        <td class="value">{{$order->user->pincode}}</td>
                    </tr>
                    <tr>
                        <td class="label">Qty × Price:</td>
                        <td class="value">{{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</td>
                    </tr>
                    <tr class="row-highlight">
                        <td class="label">Subtotal:</td>
                        <td class="value">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="footer">{{ date('M d, Y') }} • Item {{ $index+1 }} of {{ count($order_items) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="logo-cell">
                            <img src="{{ public_path('assets/stickerLogo.png') }}" alt="Logo" class="logo-img">
                        </td>
                    </tr>
                </table>
            </div>
            
            @if(($index + 1) % 3 == 0)
                <div class="row-break"></div>
            @endif
            
            @if(($index + 1) % 12 == 0 && ($index + 1) < count($order_items))
                </div><div class="page page-break">
            @endif
        @endforeach
    </div>
</body>
</html>