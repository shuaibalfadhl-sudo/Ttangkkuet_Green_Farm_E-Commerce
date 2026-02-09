<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        /* Define the Korean font */
        @font-face {
            font-family: 'nanumgothic';
            src: url('{{ storage_path('fonts/NanumGothic-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        /* Apply the font to the whole document */
        body {
            font-family: 'nanumgothic', sans-serif;
            line-height: 1.6;
            font-size: 14px;
        }

        /* Add other invoice styles here */
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-header">
            <p style="font-size: 24px;">주문서 <b>(Invoice)</b></p>
            <p>주문 번호 (Order ID): #{{ $order->id }}</p>
            <p>주문 날짜 (Order Date): {{ $order->created_at->format('Y-m-d') }}</p>
        </div>

        <p>배송 정보 <strong>(Shipping Information)</strong></p>
        <p>
            이름 <strong>(Name):</strong> {{ $order->name }}<br>
            주소 <strong>(Address):</strong> {{ $order->address }}, {{ $order->locality }}, {{ $order->city }}, {{ $order->state }} {{ $order->zip }}<br>
            연락처 <strong>(Phone):</strong> {{ $order->phone }}
        </p>

        <p>주문 내역 <strong>(Order Items)</strong></p>
        <table>
            <thead>
                <tr>
                    <td>제품<b>(Product)</b></td>
                    <td>수량 <b>(Quantity)</b></td>
                    <td>가격 <b>(Price)</b></td>
                    <td>합계 <b>(Total)</b></td>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">원{{ number_format($item->price) }}</td>
                    <td class="text-right">원{{ number_format($item->price * $item->quantity) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p>결제 요약 <strong>(Payment Summary)</strong></p>
        <table>
            <tr>
                <td>소계 (Subtotal)</td>
                <td class="text-right">원{{ number_format($order->subtotal) }}</td>
            </tr>
            <tr>
                <td>배송비 (Delivery Fee)</td>
                <td class="text-right">원{{ number_format($order->delivery_fee) }}</td>
            </tr>
            <tr>
                <td>할인 (Discount)</td>
                <td class="text-right">- 원{{ number_format($order->discount) }}</td>
            </tr>
            <tr>
                <td>총계 <strong>(Total)</strong></td>
                <td class="text-right">원<strong>{{ number_format($order->total) }}</strong></td>
            </tr>
        </table>
    </div>
</body>
</html>