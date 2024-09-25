<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>INVOICE#{{ $data->invoice_number }}</title>

        <style>
            /* General content container */
            * {
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }

            .content {
                padding: 5px;
            }

            /* ------------------------------------------------- Invoice title styling ----------------------------*/
            .invoice-title {
                font-size: 55px;
                font-weight: bold;
                color: gray;
                text-align: center;
                margin: 0;
            }

            .invoice-header {
                display: table;
                width: 100%;
                margin-bottom: 20px;
                margin-top: 50px;
            }

            .invoice-row {
                display: table-row;
            }

            .invoice-logo,
            .invoice-info {
                display: table-cell;
                vertical-align: middle;
            }

            .invoice-logo {
                width: 50%;
                /* Adjust width as needed */
            }

            .invoice-logo img {
                width: 250px;
                /* Adjust image size as needed */
            }

            .invoice-info {
                width: 50%;
                text-align: right;
            }

            .invoice-number,
            .invoice-date {
                font-size: 1.5rem;
                font-weight: bold;
                color: #000;
                margin: 12px;
            }

            .invoice-number-value,
            .invoice-date-value {
                font-weight: normal;
            }

            /* --------------------------------   billin by and billing to container show   -------------------------------- */
            .billing-container {
                display: table;
                width: 100%;
                margin-bottom: 1rem;
                /* Adjust margin as needed */
            }

            .billed-center {
                background-color: white;
                width: 10%
            }

            .billed-from,
            .billed-to {
                display: table-cell;
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                width: 45%;
                padding: 0 1rem;
                /* Adjusted width to fit side by side */
                /* Padding for spacing */
                box-sizing: border-box;

            }



            .billed-title {
                font-weight: bold;
                /* Equivalent to Bootstrap's fw-bold */
                font-size: 1.35rem;
                /* Adjusted font size, equivalent to h5 */
                margin-bottom: 0.5rem;
                margin-top: 0.5rem;
                /* Space below title */
            }

            .billed-info {
                font-size: 1.15rem;
                /* Same size as the billed title */
                font-weight: normal;
                margin: 8px 0;
                /* Remove bold from h5 */
                /* Space below each info line */
            }


            /*-------------------------------- start the table css ----------------------------------  */

            .invoice-container {
                margin-top: 3rem;
                margin-bottom: 8rem;
                /* Equivalent to mt-5 */
            }

            .table-responsive {
                overflow-x: auto;
                /* Ensures responsiveness */
            }

            .invoice-table {
                width: 100%;
                /* Full width table */
                border-collapse: collapse;
                /* Ensures proper border spacing */
            }

            .invoice-table th,
            .invoice-table td {
                padding: 0.75rem;
                /* Padding for table cells */
                border-top: 1px solid #dee2e6;
                /* Horizontal border for top */
                border-bottom: 1px solid #dee2e6;
                /* Horizontal border for bottom */
            }

            /* Remove vertical borders */
            .invoice-table th:first-child,
            .invoice-table td:first-child {
                border-left: none;
                /* Remove left border for the first cell */
            }

            .invoice-table th:last-child,
            .invoice-table td:last-child {
                border-right: none;
                /* Remove right border for the last cell */
            }

            .invoice-table th {
                background-color: #d3d3d3;
                /* Light background for table headers */
                font-weight: bold;
                /* Header bold font */
            }

            .table-info {
                margin: 0 0 5px 0;
                font-size: 1.15rem;
                font-weight: normal;
                /* Remove default margin */
            }

            .total-title {
                font-weight: bold;
                /* Equivalent to fw-bold */
                font-size: 1.4rem;
                margin: 0;
                /* Increased font size for TOTAL */
            }

            .total-amount {
                font-weight: bold;
                /* Bold for total amount */
                font-size: 1.4rem;
                margin: 0
                    /* Right align total */
            }

            /* Additional styles for text alignment */
            .text-left {
                text-align: left;
                /* Align text to the left */
            }

            .text-right {
                text-align: right;
                /* Align text to the right */
            }

            .table-description {
                width: 75%;
                /* Fixed width for description column */
            }

            .payment-method {
                margin: 0 0 5px 0;
                font-size: 1.15rem;
                font-weight: normal;
            }

            table thead th {
                font-size: 16px;
            }

            table tbody tr {
                background-color: #F8FAFC
            }

            /* ------------------------------------ footer invoice css start --------------------------------- */
            .invoice-footer {
                margin-top: 0rem;
                /* Equivalent to mt-5 */
            }

            .footer-content {
                width: 100%;
                /* Full width */
            }

            .text-gray {
                color: gray;
                /* Set text color to gray */
            }
        </style>
    </head>


    <body>
        <div class="content">
            <h1 class="invoice-title">Invoice</h1>
            <div class="invoice-header">
                <div class="invoice-row">
                    <div class="invoice-logo">
                        <img
                            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/webroot-infosoft-logo.jpg'))) }}">
                    </div>
                    <div class="invoice-info">
                        <h4 class="invoice-number">Invoice No # <span
                                class="invoice-number-value">{{ $data->invoice_number }}</span>
                        </h4>
                        <h4 class="invoice-date">Invoice Date <span
                                class="invoice-date-value">{{ date('d-m-Y', strtotime($data->date)) }}</span>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="billing-container">
                <div class="billed-from">
                    <h5 class="billed-title">Billed by</h5>
                    <h5 class="billed-info">{{ auth()->user()->name }}</h5>
                    <h5 class="billed-info">{{ auth()->user()->address }}</h5>
                    <h5 class="billed-info">{{ auth()->user()->state->name }},
                        {{ auth()->user()->country->name }}</h5>
                    <h5 class="billed-info">GSTIN: {{ auth()->user()->gstin }}</h5>
                    <h5 class="billed-info">Export without payment of tax</h5>
                    <h5 class="billed-info">LUT: AD030323009765D</h5>
                </div>
                <div class="billed-center"></div>
                <div class="billed-to">
                    <h5 class="billed-title">Billed to</h5>
                    <h5 class="billed-info">{{ $data->user->name }}</h5>
                    <h5 class="billed-info">{{ $data->user->address }}</h5>
                    <h5 class="billed-info">
                        {{ $data->user->state->name }}@if ($data->user->state->name != 0)
                            , {{ $data->user->country->name }}
                        @else
                            {{ $data->user->country->name }}
                        @endif
                        @if ($data->user->postal_code != null)
                            - {{ $data->user->postal_code }}
                        @endif
                    </h5>
                    <h5 class="billed-info">
                        @if ($data->user->gstin != null)
                            {{ $data->user->gstin }}
                        @endif
                    </h5>
                </div>
            </div>

            <div class="invoice-container mt-5">
                <input type="hidden" id="taxtype" name="tax_name" value="" />
                <input type="hidden" id="taxrate" name="tax_rate" value="" />

                <div class="table-responsive table-hover">
                    <table class="invoice-table" id="invoiceTable">
                        <thead>
                            <tr>
                                <th class="text-left">Description</th>
                                <th class="text-right">Quantity</th>
                                <th class="text-right">Rate</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:75% table-description">
                                    <h5 class="table-info text-left">{{ $data->description }}</h5>
                                    {{-- HSN Code - 998314 --}}
                                </td>
                                <td>
                                    <h5 class="table-info text-right">{{ $data->quantity }}</h5>
                                </td>
                                <td>
                                    <h5 class="table-info text-right">{{ $data->rate }}</h5>
                                </td>
                                <td>
                                    <h5 class="table-info text-right" id="amount">{{ $data->amount }}</h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="additional-info-table">
                        <table class="invoice-table">
                            <tr style="border-top: 0;">
                                <td colspan="2">
                                    <h5 class="payment-method">Payment Method:</h5>
                                </td>
                                <td class="text-right" colspan="2">
                                    <h5 class="payment-method">{{ $data->payment_method }}</h5>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h4 class="total-title">TOTAL :</h4>
                                </td>
                                <td colspan="2">
                                    <h4 class="text-right total-amount" id="total_amount">{{ $data->total }}
                                    </h4>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="invoice-footer">
                <div class="footer-content">
                    <p><strong>Thank you for your business with us.</strong></p>
                    <p class="text-gray">(This is a computer-generated invoice, no signature required)</p>
                </div>
            </div>


        </div>
    </body>

</html>
