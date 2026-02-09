<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Summary Report</title>
    <style>
        @font-face {
            font-family:'NanumGothic';
            src: url('{{ storage_path('fonts/NanumGothic-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: "Noto Sans KR", Arial, sans-serif, 'NanumGothic';
            margin: 25px;
            color: #333;
            font-size: 11px;
        }

        h1, h2, h3, h4 {
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: 700;
        }

        .header p {
            margin-top: 5px;
            font-size: 10px;
            color: #555;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 11px;
            border-bottom: 1px solid #ccc;
            display: inline-block;
            padding-bottom: 2px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fafafa;
            padding: 10px;
            margin-bottom: 10px;
        }

        .card-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .card-value {
            font-size: 11px;
            font-weight: bold;
            color: #000;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .report-table th, .report-table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: center;
        }

        .report-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 10px;
        }

        .report-table td {
            font-size: 10px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #777;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <p style="font-size: 24px;">월간 요약 보고서 (Monthly Summary Report)</p>
        <p>보고 기간 (Report Period): <strong>{{ $dateLabel }}</strong></p>
    </div>

    <div class="section-title">주요 지표 (Key Metrics)</div>
    <div class="grid-container">
        <div class="card m-3">
            <div class="card-label">총 주문 (Total Orders)</div>
            <div class="card-label">{{ $metrics['total_orders'] }}</div>
        </div>
        <div class="card">
            <div class="card-label">배송 완료 (Delivered Orders)</div>
            <div class="card-label">{{ $metrics['delivered_orders'] }}</div>
        </div>
        <div class="card">
            <div class="card-label">총 금액 (Total Amount)</div>
            <div class="card-label">원 {{ number_format($metrics['total_amount'], 2) }}</div>
        </div>
        <div class="card">
            <div class="card-label">배송 완료 금액 (Delivered Amount)</div>
            <div class="card-label">원 {{ number_format($metrics['delivered_amount'], 2) }}</div>
        </div>
        <div class="card">
            <div class="card-label">보류 중 주문 (Pending Orders)</div>
            <div class="card-label">{{ $metrics['pending_orders'] }}</div>
        </div>
        <div class="card">
            <div class="card-label">취소된 주문 (Canceled Orders)</div>
            <div class="card-label">{{ $metrics['canceled_orders'] }}</div>
        </div>
        <div class="card">
            <div class="card-label">보류 금액 (Pending Amount)</div>
            <div class="card-label">원 {{ number_format($metrics['pending_amount'], 2) }}</div>
        </div>
        <div class="card">
            <div class="card-label">취소 금액 (Canceled Amount)</div>
            <div class="card-label">원 {{ number_format($metrics['canceled_amount'], 2) }}</div>
        </div>
    </div> 
    <div class="footer">
        Generated on {{ now()->format('Y-m-d') }}
    </div>

</body>
</html>
