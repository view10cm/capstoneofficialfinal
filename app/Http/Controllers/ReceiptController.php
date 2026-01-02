<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffToKitchenTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    /**
     * Generate and download a receipt for a transaction
     */
    public function generateReceipt(Request $request)
    {
        $request->validate([
            'orderID' => 'required|string',
            'paymentNumber' => 'required|string',
            'referenceNumber' => 'nullable|string'
        ]);
        
        $orderID = $request->orderID;
        $paymentNumber = $request->paymentNumber;
        $referenceNumber = $request->referenceNumber;
        
        // Get transaction data
        $transactions = StaffToKitchenTransaction::where('orderID', $orderID)
            ->where('paymentNumber', $paymentNumber)
            ->when($referenceNumber, function($query) use ($referenceNumber) {
                return $query->where('referenceNumber', $referenceNumber);
            })
            ->orderBy('paymentProcessedAt', 'desc')
            ->get();
        
        if ($transactions->isEmpty()) {
            abort(404, 'Transaction not found');
        }
        
        // Get the first transaction for order details
        $firstTransaction = $transactions->first();
        
        // Calculate totals
        $subtotal = $transactions->sum('totalPrice');
        $taxTotal = $transactions->sum('taxAmount');
        $grandTotal = $subtotal + $taxTotal;
        
        // Prepare receipt data - optimized for 5-inch thermal paper
        $receiptData = [
            'orderID' => $orderID,
            'paymentNumber' => $paymentNumber,
            'orderType' => ucfirst(str_replace('-', ' ', $firstTransaction->orderType)),
            'paymentMethod' => ucfirst($firstTransaction->paymentMethod),
            'referenceNumber' => $firstTransaction->referenceNumber,
            'staffName' => $firstTransaction->staffName,
            'paymentDate' => $firstTransaction->paymentProcessedAt->format('M d, Y h:i A'),
            'items' => $transactions,
            'subtotal' => $subtotal,
            'taxTotal' => $taxTotal,
            'grandTotal' => $grandTotal,
            'amountPaid' => $firstTransaction->amountPaid,
            'changeAmount' => $firstTransaction->changeAmount,
            'businessName' => 'CAFFE ARABICA',
            'businessAddress' => '123 Coffee Street, Manila',
            'businessContact' => 'Tel: (02) 1234-5678',
            'receiptNumber' => 'REC-' . date('md') . '-' . str_pad($transactions->first()->id % 10000, 4, '0', STR_PAD_LEFT),
            'footerNote' => 'Thank you for your purchase!'
        ];
        
        // Standard 5-inch thermal receipt paper size (5 inches = 360 points)
        // Typical thermal receipt: 5" x varies (we'll use 5" x auto)
        $widthInPoints = 340; // 5 inches in points
        $paperHeight = max(300, count($transactions) * 15 + 300); // Reduced height calculation
        
        // Generate PDF optimized for thermal printers
        $pdf = Pdf::loadView('receipts.electronic', $receiptData)
            ->setPaper([0, 0, $widthInPoints, $paperHeight], 'portrait')
            ->setOptions([
                'defaultFont' => 'monospace',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'dpi' => 203, // Standard thermal printer DPI
                'fontHeightRatio' => 0.8 // Tighter line height for receipts
            ]);
        
        $filename = 'receipt-' . $orderID . '-' . $paymentNumber . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Generate receipt from transaction ID
     */
    public function generateReceiptFromId($transactionId)
    {
        $transaction = StaffToKitchenTransaction::findOrFail($transactionId);
        
        // Get all transactions for this order and payment number
        $transactions = StaffToKitchenTransaction::where('orderID', $transaction->orderID)
            ->where('paymentNumber', $transaction->paymentNumber)
            ->where('paymentProcessedAt', $transaction->paymentProcessedAt)
            ->get();
        
        // Calculate totals
        $subtotal = $transactions->sum('totalPrice');
        $taxTotal = $transactions->sum('taxAmount');
        $grandTotal = $subtotal + $taxTotal;
        
        $receiptData = [
            'orderID' => $transaction->orderID,
            'paymentNumber' => $transaction->paymentNumber,
            'orderType' => ucfirst(str_replace('-', ' ', $transaction->orderType)),
            'paymentMethod' => ucfirst($transaction->paymentMethod),
            'referenceNumber' => $transaction->referenceNumber,
            'staffName' => $transaction->staffName,
            'paymentDate' => $transaction->paymentProcessedAt->format('M d, Y h:i A'),
            'items' => $transactions,
            'subtotal' => $subtotal,
            'taxTotal' => $taxTotal,
            'grandTotal' => $grandTotal,
            'amountPaid' => $transaction->amountPaid,
            'changeAmount' => $transaction->changeAmount,
            'businessName' => 'CAFFE ARABICA',
            'businessAddress' => '123 Coffee Street, Manila',
            'businessContact' => 'Tel: (02) 1234-5678',
            'receiptNumber' => 'REC-' . date('md') . '-' . str_pad($transaction->id % 10000, 4, '0', STR_PAD_LEFT),
            'footerNote' => 'Thank you for your purchase!'
        ];
        
        // 5-inch thermal receipt size
        $widthInPoints = 360;
        $paperHeight = max(400, count($transactions) * 20 + 400);
        
        $pdf = Pdf::loadView('receipts.electronic', $receiptData)
            ->setPaper([0, 0, $widthInPoints, $paperHeight], 'portrait')
            ->setOptions([
                'defaultFont' => 'monospace',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'dpi' => 203,
                'fontHeightRatio' => 0.8
            ]);
        
        $filename = 'receipt-' . $transaction->orderID . '-' . $transaction->paymentNumber . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Generate thermal receipt (simplified version for thermal printers)
     */
    public function generateThermalReceipt(Request $request)
    {
        $request->validate([
            'orderID' => 'required|string',
            'paymentNumber' => 'required|string'
        ]);
        
        $orderID = $request->orderID;
        $paymentNumber = $request->paymentNumber;
        
        // Get transaction data
        $transactions = StaffToKitchenTransaction::where('orderID', $orderID)
            ->where('paymentNumber', $paymentNumber)
            ->orderBy('paymentProcessedAt', 'desc')
            ->get();
        
        if ($transactions->isEmpty()) {
            abort(404, 'Transaction not found');
        }
        
        $firstTransaction = $transactions->first();
        $subtotal = $transactions->sum('totalPrice');
        $taxTotal = $transactions->sum('taxAmount');
        $grandTotal = $subtotal + $taxTotal;
        
        $receiptData = [
            'orderID' => $orderID,
            'paymentNumber' => $paymentNumber,
            'orderType' => $firstTransaction->orderType,
            'paymentMethod' => $firstTransaction->paymentMethod,
            'referenceNumber' => $firstTransaction->referenceNumber,
            'staffName' => $firstTransaction->staffName,
            'paymentDate' => $firstTransaction->paymentProcessedAt->format('M d, Y h:i A'),
            'items' => $transactions,
            'subtotal' => $subtotal,
            'taxTotal' => $taxTotal,
            'grandTotal' => $grandTotal,
            'amountPaid' => $firstTransaction->amountPaid,
            'changeAmount' => $firstTransaction->changeAmount,
            'businessName' => 'CAFFE ARABICA',
            'businessAddress' => '123 Coffee Street, Manila',
            'businessContact' => 'Tel: (02) 1234-5678',
            'receiptNumber' => 'REC' . date('mdHi'),
            'footerNote' => 'Thank you!'
        ];
        
        // Return raw text for thermal printers (optional)
        if ($request->has('format') && $request->format === 'text') {
            return $this->generateReceiptText($receiptData);
        }
        
        // PDF for thermal receipt (5 inches)
        $widthInPoints = 360;
        $paperHeight = max(400, count($transactions) * 20 + 400);
        
        $pdf = Pdf::loadView('receipts.thermal', $receiptData)
            ->setPaper([0, 0, $widthInPoints, $paperHeight], 'portrait')
            ->setOptions([
                'defaultFont' => 'monospace',
                'dpi' => 203
            ]);
        
        $filename = 'thermal-receipt-' . $orderID . '-' . $paymentNumber . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Generate receipt in plain text format for thermal printers
     */
    private function generateReceiptText($data)
    {
        $text = "";
        
        // Header
        $text .= str_pad($data['businessName'], 48, " ", STR_PAD_BOTH) . "\n";
        $text .= str_pad($data['businessAddress'], 48, " ", STR_PAD_BOTH) . "\n";
        $text .= str_pad($data['businessContact'], 48, " ", STR_PAD_BOTH) . "\n";
        $text .= str_repeat("=", 48) . "\n";
        
        // Receipt header
        $text .= "SALES RECEIPT\n";
        $text .= str_repeat("-", 48) . "\n";
        $text .= "Receipt #: " . $data['receiptNumber'] . "\n";
        $text .= "Order ID: " . $data['orderID'] . "\n";
        $text .= "Payment #: " . $data['paymentNumber'] . "\n";
        if ($data['referenceNumber']) {
            $text .= "Ref #: " . $data['referenceNumber'] . "\n";
        }
        $text .= "Date: " . $data['paymentDate'] . "\n";
        $text .= "Staff: " . $data['staffName'] . "\n";
        $text .= str_repeat("-", 48) . "\n";
        
        // Items
        $text .= "QTY  DESCRIPTION" . str_pad("AMOUNT", 20, " ", STR_PAD_LEFT) . "\n";
        $text .= str_repeat("-", 48) . "\n";
        
        foreach ($data['items'] as $item) {
            $name = substr($item->productName, 0, 25);
            $qty = str_pad($item->quantity, 3, " ", STR_PAD_LEFT);
            $price = str_pad("₱" . number_format($item->totalPrice, 2), 20, " ", STR_PAD_LEFT);
            $text .= $qty . "  " . $name . $price . "\n";
            
            if ($item->productNotes) {
                $text .= "     Note: " . substr($item->productNotes, 0, 35) . "\n";
            }
        }
        
        $text .= str_repeat("-", 48) . "\n";
        
        // Totals
        $text .= str_pad("Subtotal:", 35, " ", STR_PAD_LEFT) . str_pad("₱" . number_format($data['subtotal'], 2), 13, " ", STR_PAD_LEFT) . "\n";
        $text .= str_pad("Tax (12%):", 35, " ", STR_PAD_LEFT) . str_pad("₱" . number_format($data['taxTotal'], 2), 13, " ", STR_PAD_LEFT) . "\n";
        $text .= str_repeat("=", 48) . "\n";
        $text .= str_pad("TOTAL:", 35, " ", STR_PAD_LEFT) . str_pad("₱" . number_format($data['grandTotal'], 2), 13, " ", STR_PAD_LEFT) . "\n";
        $text .= str_repeat("=", 48) . "\n";
        
        // Payment
        $text .= "Payment: " . $data['paymentMethod'] . "\n";
        $text .= "Amount Paid: ₱" . number_format($data['amountPaid'], 2) . "\n";
        if ($data['changeAmount'] > 0) {
            $text .= "Change: ₱" . number_format($data['changeAmount'], 2) . "\n";
        }
        
        $text .= str_repeat("=", 48) . "\n";
        $text .= $data['footerNote'] . "\n";
        $text .= str_pad("Generated: " . date('Y-m-d H:i:s'), 48, " ", STR_PAD_BOTH) . "\n";
        $text .= str_repeat("*", 48) . "\n";
        
        return response($text)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="receipt-' . $data['orderID'] . '.txt"');
    }
}