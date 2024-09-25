<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use ZipArchive;

class DownloadZipController extends Controller
{
    public function downloadZip(Request $request)
    {
        // Set a reasonable time limit for generating PDFs

        $invoiceIds = $request->input('invoice_ids'); // Get the selected invoice IDs from the request

        // Check if invoiceIds is an array and not null
        if (is_null($invoiceIds) || !is_array($invoiceIds)) {
            return response()->json(['error' => 'No invoices selected.'], 400);
        }

        // Create a temporary file for the ZIP
        $zipFileName = 'invoices_' . time() . '.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);
        $zip = new ZipArchive;

        // Create ZIP file and add the PDFs for each invoice
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($invoiceIds as $invoiceId) {
                $data = Invoice::find($invoiceId);

                // Skip if invoice doesn't exist
                if (!$data) {
                    continue;
                }

                // Calculate amounts and taxes based on GST type
                if ($data->tax->name == "IGST") {
                    $gst_rate = $data->tax->rate;
                    $amount = $data->rate * $data->quantity;
                    $amount = number_format($amount, 2);
                    $gst_amount = $amount * ($gst_rate / 100);
                    $gst_amount = number_format($gst_amount, 2);
                    $total_amount = $amount + $gst_amount;
                    // Ensure amounts are formatted to two decimal places

                    $total_amount = number_format($total_amount, 2);

                    $view = view('admin.zippdfinvoiceformat.IGSTview', compact('data', 'amount', 'gst_rate', 'gst_amount', 'total_amount'))->render();

                } elseif ($data->tax->name == "CGST & SGST") {

                    $amount = $data->rate * $data->quantity;
                    $gst_rate = $data->tax->rate / 2;
                    $cgst_amount = $amount * ($gst_rate / 100);
                    $sgst_amount = $amount * ($gst_rate / 100);
                    $total_amount = $amount + $cgst_amount + $sgst_amount;

                    $amount = number_format($amount, 2);
                    $cgst_amount = number_format($cgst_amount, 2);
                    $sgst_amount = number_format($sgst_amount, 2);
                    $total_amount = number_format($total_amount, 2);

                    
                    $view = view('admin.zippdfinvoiceformat.CGSTSGSTview', compact('data', 'amount', 'gst_rate', 'cgst_amount', 'sgst_amount', 'total_amount'))->render();
                } else {
                    $view = view('admin.zippdfinvoiceformat.Noneview', compact('data'))->render();
                }

                // Generate the PDF using Dompdf
                $dompdf = new Dompdf();
                $dompdf->getOptions()->set('isRemoteEnabled', true);
                $dompdf->loadHtml($view); // Load the HTML content
                $dompdf->setPaper('A4', 'portrait'); // Set paper size and orientation
                $dompdf->render(); // Render the PDF

                // Save the PDF to a temporary location
                $pdfFilePath = storage_path("app/invoice_{$invoiceId}.pdf");
                file_put_contents($pdfFilePath, $dompdf->output());

                // Add the generated PDF to the ZIP archive
                $zip->addFile($pdfFilePath, "invoice_{$invoiceId}.pdf");
            }

            $zip->close(); // Close the zip file

            // Return the download response for the ZIP file and delete after sending
            return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Unable to create zip file'], 500);
        }
    }
}
