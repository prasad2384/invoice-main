@extends('admin.layout')
@section('title')
    INVOICE#{{ $data->invoice_number }}
@endSection
@section('content')
    <style>
        @media print {
            #print-donwnload-section {
                display: none !important;
            }

            .gst-rate-header,
            .igst-amount-header,
            .cgst-header,
            .sgst-header,
            .total-header {
                white-space: nowrap;
            }

            .invoice-table th {
                font-size: 18px;
                background-color: lightgray !important;
                /* Ensure background color appears in print */
                -webkit-print-color-adjust: exact;
                /* Ensure color is printed */
                color: black !important;
            }

            .invoice-table td,
            .invoice-table-1 td {
                background-color: rgba(248, 249, 250, 1) !important;
                /* Ensure billed section's background color */
                -webkit-print-color-adjust: exact;
            }

            .billed-from,
            .billed-to {
                background-color: rgba(248, 249, 250, 1) !important;
                /* Ensure billed section's background color */
                -webkit-print-color-adjust: exact;
            }
        }

        .gst-rate-header {
            white-space: nowrap;
            /* Correct property */
        }

        .igst-amount-header,
        .cgst-header,
        .sgst-header,
        .total-header {
            white-space: nowrap;
            /* Ensure other columns also follow this */
        }

        .table {
            width: 100%
        }

        .invoice-table thead th {
            font-size: 18px;
            background: lightgray;
        }

        .content-wrapper {
            background: white;
        }

        .billed-from {
            padding: 15px
        }

        .billed-to {
            padding: 15px
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container">
                <div class="float-end mb-2" id="print-donwnload-section">
                    <button class="btn btn-outline-dark btn-sm btn-print"> <i class="fas fa-print"></i> </button> <button
                        class="btn btn-outline-dark btn-sm btn-download"><i class="fas fa-download"></i> </button>
                </div>
                <div class="download print">
                    <h1 style="font-size:55px;" class="fw-bold text-gray text-center">Invoice</h1>
                    <div class="row mb-2 mt-5 d-flex align-items-center">
                        <div class="col-sm-6">
                            <img style="width:250px" class="img-responsive"
                                src="{{ asset('images/webroot-infosoft-logo.jpg') }}" alt="Megaline Newswire"><br>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="fw-bold text-right">Invoice No # <span
                                    class="fw-normal">{{ $data->invoice_number }}<span></h4>
                                        <h5 class="fw-bold text-right">Invoice Date <span
                                                class="fw-normal">{{ date('d-m-Y', strtotime($data->date)) }}<span></h4>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-sm-5 bg-light billed-from border">
                            <h5 class="fw-bold">Billed by</h5>
                            <h5>{{ auth()->user()->name }}</h5>
                            <h5>{{ auth()->user()->address }}</h5>
                            <h5>{{ auth()->user()->state->name }}, {{ auth()->user()->country->name }}</h5>
                            <h5>GSTIN: {{ auth()->user()->gstin }}</h5>
                            <h5>Export without payment of tax</h5>
                            <h5>LUT: AD030323009765D</h5>
                        </div>

                        <div class="col-sm-5 bg-light billed-to border">
                            <h5 class="fw-bold">Billed to</h5>
                            <h5>{{ $data->user->name }}</h5>
                            <h5>{{ $data->user->address }}</h5>
                            <h5>
                                {{ $data->user->state->name }}@if ($data->user->state->name != 0)
                                    , {{ $data->user->country->name }}
                                @else
                                    {{ $data->user->country->name }}
                                @endif
                                @if ($data->user->postal_code != null)
                                    - {{ $data->user->postal_code }}
                                @endif
                            </h5>
                            <h5>
                                @if ($data->user->gstin != null)
                                    {{ $data->user->gstin }}
                                @endif
                            </h5>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="taxtype" name="tax_name" value="{{ $data->tax->name }}" />
                                <input type="hidden" id="taxrate" name="tax_rate" value="{{ $data->tax->rate }}" />
                                <div class="table-responsive table-hover">
                                    <table class="table invoice-table mb-0" id="invoiceTable">
                                        <thead>
                                            <tr class="background-color-light-gray">
                                                <th>Desciption</th>
                                                <th class="text-right">Quantity</th>
                                                <th class="text-right">Rate</th>
                                                <th class="text-right">Amount</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width:75%">
                                                    <h5>{{ $data->description }}</h5> {{-- HSN Code - 998314 --}}
                                                </td>
                                                <td>
                                                    <h5 class="text-right quantity">{{ $data->quantity }}</h5>
                                                </td>
                                                <td>
                                                    <h5 class="text-right rate">{{ $data->rate }}</h5>
                                                </td>
                                                <td>
                                                    <h5 class="text-right amount-cell" id="amount"></h5>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="table table-responsive">
                                        <table class="table mt-0 invoice-table-1">
                                            <tr style="border-top: 0;">
                                                <td style="width: 50%" colspan="2">
                                                    <h5>Payment Method:</h5>
                                                </td>
                                                <td style="width: 50%" class="text-right" colspan="2">
                                                    <h5> {{ $data->payment_method }}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:75%">
                                                    <h4 class="fw-bold">TOTAL:</h4>
                                                </td>
                                                <td colspan="2">
                                                    <h4 class="text-right fw-bold" id="total_amount"></h4>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>

                    <div class="mt-5">
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Thank you for your business with us.</strong></p>
                                <p class="text-gray">(This is a computer generated invoice, no signature required)</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.6.0/jspdf.umd.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <script>
        $('.refresh-users').click(function() {
            $('.refresh-users').attr('disabled', 'disabled');
            user_id = $('#user_id').val();
            $.ajax({
                type: "GET",
                url: "{{ url('api/users') }}",
                success: function(result) {
                    $('.refresh-users').removeAttr('disabled');
                    $('#user_id').html('');
                    $.each(result, function(key, value) {
                        if (value.id == user_id) {
                            $('#user_id').append("<option value=" + value.id + " selected>" +
                                value.name + "</option>");
                        } else {
                            $('#user_id').append("<option value=" + value.id + ">" + value
                                .name + "</option>");
                        }
                    });
                },
            });
        });

        $(document).ready(function() {

            function initializeTable() {
                const gstType = $('#taxtype').val();
                const gstRate = parseFloat($('#taxrate').val());

                if (gstType === 'IGST') {
                    addIGSTColumns(gstRate);
                } else if (gstType === 'CGST & SGST') {
                    addCGSTSGSTColumns(gstRate);
                } else if (gstType === 'None') {
                    removeGSTColumns();
                }
            }

            function addIGSTColumns(gstRate) {
                $('#invoiceTable thead tr').append(
                    '<th class="gst-rate-header">GST Rate</th><th class="igst-amount-header text-center">IGST</th><th class="total-header text-center">Total</th>'
                );

                $('#invoiceTable tbody tr').each(function() {
                    $(this).append(
                        '<td class="gst-rate text-right"><h5 class="gst-rate-h5"></h5></td><td class="igst-amount"><h5 class="igst-amount-h5"></h5></td><td class="total-amount"><h5 class="total-amount-h5"></h5></td>'
                    );
                });

                calculateGST(gstRate, 'IGST');
            }

            function addCGSTSGSTColumns(gstRate) {
                $('#invoiceTable thead tr').append(
                    '<th class="gst-rate-header" >GST Rate</th><th class="cgst-header">CGST</th><th class="sgst-header">SGST</th><th class="total-header">Total</th>'
                );

                $('#invoiceTable tbody tr').each(function() {
                    $(this).append(
                        '<td class="gst-rate text-right"><h5 class="gst-rate-h5"></h5></td><td class="cgst-amount"><h5 class="cgst-amount-h5"></h5></td><td class="sgst-amount"><h5 class="sgst-amount-h5"></h5></td><td class="total-amount"><h5 class="total-amount-h5"></h5></td>'
                    );
                });

                calculateGST(gstRate, 'CGST & SGST');
            }

            function removeGSTColumns() {
                $('#invoiceTable thead tr .gst-rate-header, #invoiceTable thead tr .igst-amount-header, #invoiceTable thead tr .total-header, #invoiceTable thead tr .cgst-header, #invoiceTable thead tr .sgst-header')
                    .remove();
                $('#invoiceTable tbody tr .gst-rate, #invoiceTable tbody tr .igst-amount, #invoiceTable tbody tr .cgst-amount, #invoiceTable tbody tr .sgst-amount, #invoiceTable tbody tr .total-amount')
                    .remove();
                calculateGST(0, 'None');
            }

            // Function to calculate GST based on rate and quantity
            function calculateGST(gstRate, gstType) {
                let overallTotal = 0; // Initialize overall total

                $('#invoiceTable tbody tr').each(function() {
                    const rate = parseFloat($(this).find('.rate').text()) || 0;
                    const quantity = parseFloat($(this).find('.quantity').text()) || 0;
                    const gstRateCell = $(this).find('.gst-rate-h5');
                    const igstAmountCell = $(this).find('.igst-amount-h5');
                    const cgstAmountCell = $(this).find('.cgst-amount-h5');
                    const sgstAmountCell = $(this).find('.sgst-amount-h5');
                    const amountCell = $(this).find('.amount-cell');
                    const totalAmountCell = $(this).find('.total-amount-h5');

                    // Check if rate and quantity are valid
                    if (!isNaN(rate) && !isNaN(quantity) && quantity > 0) {
                        const baseAmount = rate * quantity;
                        amountCell.text(baseAmount.toFixed(2)); // Display base amount

                        let rowTotal = baseAmount; // Initialize row total as base amount

                        // Apply GST calculation based on the selected type
                        if (gstType === 'IGST') {
                            const gstAmount = (gstRate / 100) * baseAmount;
                            rowTotal = baseAmount + gstAmount; // Add GST to row total
                            gstRateCell.text(gstRate + "%");
                            igstAmountCell.text(gstAmount.toFixed(2));
                            cgstAmountCell.text("0.00");
                            sgstAmountCell.text("0.00");
                            totalAmountCell.text(rowTotal.toFixed(2)); // Set total with GST
                        } else if (gstType === 'CGST & SGST') {
                            const gstAmount = (gstRate / 100) * baseAmount;
                            const cgst = gstAmount / 2;
                            const sgst = gstAmount / 2;
                            rowTotal = baseAmount + gstAmount; // Add GST to row total
                            gstRateCell.text(gstRate + "%");
                            cgstAmountCell.text(cgst.toFixed(2));
                            sgstAmountCell.text(sgst.toFixed(2));
                            igstAmountCell.text("0.00");
                            totalAmountCell.text(rowTotal.toFixed(2)); // Set total with GST
                        } else if (gstType === 'None') {
                            // No GST, just show base amount as total
                            gstRateCell.text("0%"); // No GST rate displayed
                            igstAmountCell.text("0.00");
                            cgstAmountCell.text("0.00");
                            sgstAmountCell.text("0.00");
                            totalAmountCell.text(baseAmount.toFixed(2)); // Set total as base amount only
                            rowTotal = baseAmount; // Ensure row total is only base amount
                        }

                        // Add row total to the overall total
                        overallTotal += rowTotal;
                    } else {
                        // If rate or quantity is invalid, reset the cells
                        amountCell.text("");
                        gstRateCell.text("");
                        igstAmountCell.text("0.00");
                        cgstAmountCell.text("0.00");
                        sgstAmountCell.text("0.00");
                        totalAmountCell.text("0.00");
                    }
                });

                // Update overall total in the UI
                $('#total_amount').text(overallTotal.toFixed(2)); // Display overall total
            }

            initializeTable();

        });

        document.addEventListener('DOMContentLoaded', function() {
            // Hide the print/download section initially

            // Check if the current URL contains 'invoice'
            if (window.location.href.includes('invoice')) {
                // Show the section if it's on the invoice page
                $('#print-donwnload-section').show();
            }

            // Handle print button click
            document.querySelector('.btn-print').addEventListener('click', function() {
                window.print();
            });
        });

        $(document).ready(function() {
            $('.btn-download').on('click', function() {
                var element = document.querySelector('.download'); // Select the content to download as PDF

                // Customize the PDF generation process
                var options = {
                    margin: [0.5, 0.5, 0.5, 0.5],
                    filename: 'invoice_{{ $data->invoice_number }}.pdf', // Use invoice number in filename
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    }, // Higher scale for better resolution
                    jsPDF: {
                        unit: 'in',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                // Generate PDF and trigger download
                html2pdf().from(element).set(options).save();
            });
        });
    </script>
@endsection
