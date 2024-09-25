<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PrintInvoiceController extends Controller
{
    //
    public function print_invoice($id){
        $data = Invoice::where('id', $id)->first();

        // Get GST rate
        $gst_rate = $data->tax->rate;

        if ($data->tax->name == "IGST") {
            $amount = $data->rate * $data->quantity;
            $amount = number_format($amount, 2);
            $gst_amount = $amount * ($gst_rate / 100);
            $gst_amount = number_format($gst_amount, 2);
            $total_amount = $amount + $gst_amount;

            // Ensure amounts are formatted to two decimal places
            $total_amount = number_format($total_amount, 2);
            return response()->json([
                'html' => view('admin.PdfInvoice.IGSTview', compact('data', 'gst_rate', 'amount', 'gst_amount', 'total_amount'))->render()
            ]);
        } elseif ($data->tax->name == "CGST & SGST") {
            // Calculate raw amounts first
            $amount = $data->rate * $data->quantity;

            // Divide GST rate equally for CGST and SGST
            $rate = $gst_rate / 2;

            // Calculate CGST and SGST amounts
            $cgst_amount = $amount * ($rate / 100);
            $sgst_amount = $amount * ($rate / 100);

            // Calculate total amount (amount + CGST + SGST)
            $total_amount = $amount + $cgst_amount + $sgst_amount;

            // Format amounts to two decimal places AFTER the calculation
            $amount = number_format($amount, 2);
            $cgst_amount = number_format($cgst_amount, 2);
            $sgst_amount = number_format($sgst_amount, 2);
            $total_amount = number_format($total_amount, 2);

            return response()->json([
                'html' => view('admin.PdfInvoice.CGSTSGSTview', compact('data', 'gst_rate', 'amount', 'cgst_amount', 'sgst_amount', 'total_amount'))->render()
            ]);
            
        } else {
            // Handle other cases if needed
            return response()->json([
                'html' => view('admin.PdfInvoice.Noneview', compact('data'))->render()
            ]);
        }
    }
}