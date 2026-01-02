<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $businessName }}</title>
    <style>
        /* 
         * Optimized for 5-inch thermal receipt paper with 1-inch margins
         * 5 inches = 360 points total width
         * 1-inch left margin = 72 points
         * 1-inch right margin = 72 points
         * Printable content width = 360 - 144 = 216 points
         */
        
        @page {
            margin: 0;
            padding: 0;
            size: 360pt auto; /* 5 inches width, auto height */
        }
        
        body {
            width: 216pt !important; /* 3 inches printable area (360 - 144) */
            max-width: 216pt !important;
            margin: 0 72pt !important; /* 1-inch left and right margins */
            padding: 5pt 0 !important;
            font-family: 'Courier New', Courier, monospace;
            font-size: 7.5pt; /* Smaller font to fit content */
            line-height: 1.0;
            background-color: white;
            color: black;
            -webkit-print-color-adjust: exact;
        }
        
        /* Prevent unwanted page breaks */
        .receipt-section {
            page-break-inside: avoid;
        }
        
        /* Header - Ultra compact for 3-inch width */
        .header {
            text-align: center;
            margin-bottom: 4pt;
            padding-bottom: 3pt;
            width: 100%;
        }
        
        .business-name {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 2pt;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
            line-height: 1.0;
        }
        
        .business-info {
            font-size: 6.5pt;
            margin-bottom: 1pt;
            line-height: 1.0;
        }
        
        /* Receipt title */
        .receipt-title {
            text-align: center;
            font-weight: bold;
            margin: 4pt 0;
            padding: 2pt 0;
            border-top: 0.7pt solid #000;
            border-bottom: 0.7pt solid #000;
            font-size: 8pt;
            text-transform: uppercase;
            width: 100%;
        }
        
        /* Transaction info - Single column layout for narrow width */
        .transaction-info {
            margin: 3pt 0;
            padding: 2pt 0;
            width: 100%;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5pt;
            font-size: 6.5pt;
            width: 100%;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 40%;
            text-align: left;
        }
        
        .info-value {
            text-align: right;
            font-weight: normal;
            min-width: 60%;
        }
        
        /* Items table - Optimized for narrow width */
        .items-section {
            margin: 4pt 0;
            width: 100%;
        }
        
        .items-header {
            font-weight: bold;
            border-bottom: 0.7pt solid #000;
            padding-bottom: 1.5pt;
            margin-bottom: 2pt;
            font-size: 6.5pt;
        }
        
        .item-row {
            display: flex;
            margin-bottom: 2pt;
            padding-bottom: 1.5pt;
            border-bottom: 0.3pt dashed #ccc;
            width: 100%;
        }
        
        .item-row:last-child {
            border-bottom: none;
        }
        
        .item-name {
            flex: 2;
            font-weight: bold;
            font-size: 7pt;
            word-break: break-word;
            max-width: 100pt; /* Limit name width for narrow layout */
            line-height: 1.0;
        }
        
        .item-qty {
            width: 15pt;
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
        }
        
        .item-price {
            width: 50pt;
            text-align: right;
            font-weight: bold;
            font-size: 7pt;
        }
        
        .product-notes {
            font-style: italic;
            font-size: 6pt;
            color: #555;
            margin-top: 1pt;
            margin-left: 0;
            padding-left: 15pt; /* Align with item name */
            word-break: break-word;
            max-width: 180pt;
            line-height: 1.0;
        }
        
        /* Totals section */
        .totals-section {
            margin: 5pt 0;
            padding-top: 3pt;
            border-top: 0.7pt solid #000;
            width: 100%;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2pt;
            font-size: 7pt;
            width: 100%;
        }
        
        .subtotal-row {
            border-bottom: 0.3pt dashed #ccc;
            padding-bottom: 1.5pt;
        }
        
        .tax-row {
            color: #006;
            border-bottom: 0.3pt dashed #ccc;
            padding-bottom: 1.5pt;
        }
        
        .grand-total-row {
            font-weight: bold;
            font-size: 8pt;
            margin-top: 3pt;
            padding-top: 3pt;
            border-top: 0.7pt solid #000;
            border-bottom: 0.7pt solid #000;
            padding-bottom: 3pt;
        }
        
        /* Payment info */
        .payment-section {
            margin: 5pt 0;
            padding: 3pt;
            border: 0.7pt solid #000;
            background-color: #f8f8f8;
            width: 100%;
        }
        
        .payment-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 3pt;
            text-transform: uppercase;
            font-size: 7pt;
        }
        
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2pt;
            font-size: 7pt;
            width: 100%;
        }
        
        .payment-change-row {
            font-weight: bold;
            color: #090;
            margin-top: 2pt;
            padding-top: 2pt;
            border-top: 0.3pt solid #000;
        }
        
        /* Barcode - Smaller for narrow width */
        .barcode-section {
            text-align: center;
            margin: 5pt 0;
            padding: 3pt 0;
            border-top: 0.5pt dashed #000;
            border-bottom: 0.5pt dashed #000;
            width: 100%;
        }
        
        .barcode-text {
            font-family: 'Monospace';
            font-size: 11pt; /* Smaller for narrow width */
            letter-spacing: 0.8pt;
            line-height: 1;
        }
        
        .barcode-number {
            font-size: 6pt;
            margin-top: 1pt;
            letter-spacing: 0.3pt;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 5pt;
            padding-top: 3pt;
            border-top: 0.7pt solid #000;
            font-size: 6pt;
            line-height: 1.0;
            width: 100%;
        }
        
        .thank-you {
            font-weight: bold;
            margin-bottom: 2pt;
            font-size: 6.5pt;
        }
        
        .footer-note {
            font-style: italic;
            margin: 2pt 0;
        }
        
        .generated-time {
            color: #666;
            margin-top: 3pt;
            font-size: 5.5pt;
        }
        
        /* Separators */
        .separator {
            border-top: 0.5pt dashed #000;
            margin: 3pt 0;
            width: 100%;
        }
        
        .double-separator {
            border-top: 1.5pt double #000;
            margin: 4pt 0;
            width: 100%;
        }
        
        /* Utility classes */
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-bold {
            font-weight: bold;
        }
        
        /* Receipt number styling */
        .receipt-number {
            font-family: 'Courier New', monospace;
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 0.3pt;
            text-align: center;
            margin: 2pt 0;
            width: 100%;
        }
        
        /* Print optimization */
        @media print {
            body {
                width: 216pt !important;
                max-width: 216pt !important;
                margin: 0 72pt !important;
                padding: 5pt 0 !important;
            }
            
            .no-print {
                display: none !important;
            }
            
            /* Force black text for thermal printers */
            * {
                color: black !important;
                background-color: transparent !important;
            }
            
            /* Remove backgrounds for better thermal printing */
            .payment-section {
                background-color: transparent !important;
                border: 0.7pt solid #000 !important;
            }
        }
        
        /* Clean layout for thermal printing */
        * {
            box-sizing: border-box;
        }
        
        /* Ensure all elements stay within the 216pt width */
        .content-container {
            width: 216pt;
            max-width: 216pt;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <!-- Header -->
        <div class="header">
            <div class="business-name">{{ $businessName }}</div>
            <div class="business-info">{{ $businessAddress }}</div>
            <div class="business-info">{{ $businessContact }}</div>
        </div>
        
        <div class="double-separator"></div>
        
        <!-- Receipt Title -->
        <div class="receipt-title">SALES RECEIPT</div>
        
        <!-- Receipt Number -->
        <div class="receipt-number">{{ $receiptNumber }}</div>
        
        <div class="separator"></div>
        
        <!-- Transaction Information -->
        <div class="transaction-info">
            <div class="info-row">
                <span class="info-label">Order ID:</span>
                <span class="info-value">{{ $orderID }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment #:</span>
                <span class="info-value">{{ $paymentNumber }}</span>
            </div>
            @if(!empty($referenceNumber))
            <div class="info-row">
                <span class="info-label">Ref #:</span>
                <span class="info-value">{{ $referenceNumber }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Type:</span>
                <span class="info-value">{{ $orderType }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ $paymentDate }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Staff:</span>
                <span class="info-value">{{ $staffName }}</span>
            </div>
        </div>
        
        <div class="separator"></div>
        
        <!-- Items Section -->
        <div class="items-section">
            <div class="items-header">
                <div class="item-row">
                    <div class="item-name">ITEM</div>
                    <div class="item-qty">QTY</div>
                    <div class="item-price">AMOUNT</div>
                </div>
            </div>
            
            @foreach($items as $item)
            <div class="item-row">
                <div class="item-name">{{ $item->productName }}</div>
                <div class="item-qty">{{ $item->quantity }}</div>
                <div class="item-price">₱{{ number_format($item->totalPrice, 2) }}</div>
            </div>
            @if(!empty($item->productNotes))
            <div class="product-notes">Note: {{ $item->productNotes }}</div>
            @endif
            @endforeach
        </div>
        
        <div class="double-separator"></div>
        
        <!-- Totals Section -->
        <div class="totals-section">
            <div class="total-row subtotal-row">
                <span>Subtotal:</span>
                <span class="text-bold">₱{{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="total-row tax-row">
                <span>Tax (12%):</span>
                <span class="text-bold">₱{{ number_format($taxTotal, 2) }}</span>
            </div>
            <div class="total-row grand-total-row">
                <span>TOTAL:</span>
                <span class="text-bold">₱{{ number_format($grandTotal, 2) }}</span>
            </div>
        </div>
        
        <div class="separator"></div>
        
        <!-- Payment Information -->
        <div class="payment-section">
            <div class="payment-title">PAYMENT</div>
            <div class="payment-row">
                <span>Method:</span>
                <span class="text-bold">{{ $paymentMethod }}</span>
            </div>
            <div class="payment-row">
                <span>Amount Paid:</span>
                <span class="text-bold">₱{{ number_format($amountPaid, 2) }}</span>
            </div>
            @if($changeAmount > 0)
            <div class="payment-row payment-change-row">
                <span>Change:</span>
                <span class="text-bold">₱{{ number_format($changeAmount, 2) }}</span>
            </div>
            @else
            <div class="payment-row">
                <span>Change:</span>
                <span class="text-bold">Exact Amount</span>
            </div>
            @endif
        </div>
        
        <div class="separator"></div>
        
        <!-- Barcode -->
        <div class="barcode-section">
            <div class="barcode-text">*{{ $receiptNumber }}*</div>
            <div class="barcode-number">{{ $receiptNumber }}</div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">{{ $footerNote }}</div>
            <div class="footer-note">Please keep this receipt for your records</div>
            <div class="generated-time">
                Generated: {{ date('Y-m-d H:i:s') }}<br>
                Receipt ID: {{ $receiptNumber }}
            </div>
        </div>
        
        <!-- Thermal printer feed -->
        <div style="height: 15pt;"></div>
    </div>
</body>
</html>