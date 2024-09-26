@extends('admin.layout')
@section('title')
    Invoices
@endSection
@section('content')
    <style>
        .table {
            width: 100%
        }

        .error {
            color: red;
        }

        @media print {
            .invoice-title {
                font-size: 55px;
                font-weight: bold;
                color: gray;
                text-align: center;
                margin: 0;
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

            .invoice-table th {
                background-color: #d3d3d3;
                /* Light background for table headers */
                font-weight: bold;
                /* Header bold font */
            }

            table tbody tr {
                background-color: #F8FAFC
            }
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper invoice-content">
        <!-- Content Header (Page header) -->


        <!-- search content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title float-left ">Search</h3>
                    </div>
                    <div class="card-body">
                        <form id="submit_form" method="GET" action="{{ url('admin/invoices') }}"
                            enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Invoice ID</label>
                                        <input type="text" name="id" id="id" value="{{ Request::get('id') }}"
                                            id="firstname" class="form-control" placeholder="Enter Invoice Id">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Invoice Number</label>
                                        <input type="text" name="invoice_number" id="invoice_number"
                                            value="{{ Request::get('invoice_number') }}" class="form-control"
                                            placeholder="Enter Invoice Number">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>User</label>
                                        <input type="text" name="user_id" id="user_id"
                                            value="{{ Request::get('user_id') }}" id="user_id" class="form-control"
                                            placeholder="Enter User Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" name="total" id="total" class="form-control"
                                            placeholder="Enter Amount" value="{{ Request::get('total') }}">
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-select">
                                            <option value="">--Filter By Payment--</option>
                                            <option value="Payment method 1"
                                                {{ Request::get('payment_method') == 'Payment method 1' ? 'selected' : '' }}>
                                                Payment method 1</option>
                                            <option value="Payment method 2"
                                                {{ Request::get('payment_method') == 'Payment method 2' ? 'selected' : '' }}>
                                                Payment method 2</option>
                                            <option value="Payment method 3"
                                                {{ Request::get('payment_method') == 'Payment method 3' ? 'selected' : '' }}>
                                                Payment method 3</option>
                                            <option value="Payment method 4"
                                                {{ Request::get('payment_method') == 'Payment method 4' ? 'selected' : '' }}>
                                                Payment method 4</option>
                                        </select>

                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Rate</label>
                                        <input type="number" class="form-control" step="any"
                                            value="{{ Request::get('rate') }}" name="rate" id="rate">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Invoice Start Date</label>
                                        <input type="date" name="invoice_start_date" id="invoice_start_date"
                                            class="form-control" value="{{ Request::get('invoice_start_date') }}" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Invoice End Date</label>
                                        <input type="date" name="invoice_end_date" id="invoice_end_date"
                                            class="form-control" value="{{ Request::get('invoice_end_date') }}" />
                                    </div>
                                </div>

                                <div class="col-12 d-flex align-items-center">
                                    <button type="submit"
                                        class="btn btn-primary btn-sm  float-start me-2 mt-2">Search</button>
                                    <a href="{{ url('admin/invoices') }}" class="">Clear Search</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.container-fluid -->
        </section>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-sm-12 d-flex justify-content-between align-items-center">
                        <h3>Invoices</h3>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" id="download-zip">Download Selected as
                                ZIP</button>

                            <a href="{{ url('/admin/invoices/create') }}" class="btn btn-sm btn-primary text-white">Add
                                Invoice</a>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="text-wrap:nowrap;">#</th>
                                        <th style="text-wrap:nowrap;">Invoice No.</th>
                                        <th style="text-wrap:nowrap;">Client</th>
                                        <th style="text-wrap:nowrap;">Description</th>
                                        <th style="text-wrap:nowrap;">Quantity</th>
                                        <th style="text-wrap:nowrap;">Rate</th>
                                        {{-- <th style="text-wrap:nowrap;">Amount</th> --}}
                                        <th style="text-wrap:nowrap;">Tax</th>
                                        <th style="text-wrap:nowrap;">Amount</th>
                                        {{-- <th style="text-wrap:nowrap;">Remarks</th> --}}
                                        <th style="text-wrap:nowrap;">Invoice status</th>
                                        <th style="text-wrap:nowrap;">Date</th>
                                        <th style="text-wrap:nowrap;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $invoice)
                                        <tr>
                                            {{-- <td>{{ $key + $data->firstItem() }}</td> --}}
                                            <td style="text-wrap:nowrap; display:flex; align-items:center;">
                                                <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}"
                                                    class="invoice-checkbox me-1" id=""> {{ $invoice->id }}
                                            </td>
                                            <td style="text-wrap:nowrap;">{{ $invoice->invoice_number }}</td>
                                            <td style="text-wrap:nowrap;">{{ $invoice->user->name }}</td>
                                            <td style="white-space: nowrap;">
                                                {{ Str::limit($invoice->description, 15, '...') }}</td>

                                            <td style="text-wrap:nowrap;">{{ $invoice->quantity }}</td>
                                            <td style="text-wrap:nowrap;">{{ $invoice->rate }}</td>
                                            {{-- <td style="text-wrap:nowrap;">{{ $invoice->amount }}</td>  --}}
                                            <td style="text-wrap:nowrap;">{{ Str::limit($invoice->tax->name, 6, '..') }}
                                            </td>
                                            <td style="text-wrap:nowrap;">{{ $invoice->amount }}</td>
                                            {{-- <td style="text-wrap:nowrap;">{{ $invoice->remarks }}</td> --}}
                                            <td style="text-wrap:nowrap;">
                                                <p class="badge badge-{{ $invoice->invoice_status->color }}">
                                                    {{ $invoice->invoice_status->name }}</p>
                                            </td>
                                            <td style="text-wrap:nowrap;">{{ date('d-m-Y', strtotime($invoice->date)) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <button class="btn btn-outline-dark btn-sm btn-print me-1"
                                                        data-id="{{ $invoice->id }}"> <i class="fas fa-print"></i>
                                                    </button>
                                                    <button class="btn btn-outline-dark btn-sm btn-download  me-1"
                                                        data-id="{{ $invoice->id }}">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                    <a class="btn btn-sm btn-outline-dark me-1"
                                                        href="{{ url('admin/invoices/' . $invoice->id) }}"
                                                        title="View Invoice">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-outline-dark btn-sm me-1"
                                                        href="{{ route('invoices.edit', $invoice->id) }}">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    <form action="{{ url('admin/invoices/' . $invoice->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-outline-dark btn-sm delete-button"
                                                            id="{{ $invoice->id }}"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $data->links() }}
                        </div>

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    
   
    <!-- /.content-wrapper -->

    @if (Session::has('message'))
        <script>
            $(document).ready(function() {
                toastr.success("{{ Session::get('message') }}");
            })
        </script>
    @endif
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $.validator.addMethod("checkEndDate", function(value, element, param) {
                let startDate = $(param).val();
                return startDate === "" || value !==
                    ""; // If start date is selected, end date must be selected
            });

            // Custom method to check if Start Date is selected before End Date
            $.validator.addMethod("checkStartDate", function(value, element, param) {
                let endDate = $(param).val();
                return endDate === "" || value !==
                    ""; // If end date is selected, start date must be selected
            });

            $.validator.addMethod("invoiceNumberFormat", function(value, element) {
                // Regular expression to match the format YYYY-YY/N
                return this.optional(element) || /^[0-9]{4}-[0-9]{2}\/[0-9]+$/.test(value);
            }, "Please enter a valid invoice number (e.g., 2023-24/42)");

            $.validator.addMethod("lettersOnly", function(value, element) {
                return this.optional(element) || /^[a-zA-Z]+$/.test(value); // Only allow letters
            }, "Please enter only letters.");

            $("#submit_form").validate({
                rules: {
                    invoice_start_date: {
                        date: true,
                        checkEndDate: "#invoice_end_date" // Custom rule to check if end date is selected
                    },
                    invoice_end_date: {
                        date: true,
                        checkStartDate: "#invoice_start_date" // Custom rule to check if start date is selected
                    },
                    id: {
                        number: true
                    },
                    invoice_number: {
                        invoiceNumberFormat: true // Custom rule for invoice number format
                    },
                    rate: {
                        number: true
                    },
                    amount: {
                        number: true
                    },
                    user_id: {
                        minlength: 3, // Minimum 3 characters for user ID
                        lettersOnly: true // Only letters allowed
                    }

                },
                messages: {
                    invoice_start_date: {
                        checkEndDate: "Please select an start date."
                    },
                    invoice_end_date: {
                        checkStartDate: "Please select a end date."
                    },
                    id: {
                        number: "Please enter a valid numeric Invoice ID."
                    },
                    total: {
                        number: "Please enter a valid numeric Amount."
                    },
                    rate: {
                        number: "Please enter a valid numeric Rate."
                    },
                    invoice_number: {
                        invoiceNumberFormat: "Please enter a valid invoice number (e.g., 2023-24/42)."
                    },
                    user_id: {
                        minlength: "User name must be at least 3 characters long.",
                        lettersOnly: "User name can only contain letters."
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    form.submit(); // Submit form if validation passes
                }
            });

            $('.btn-download').on('click', function() {
                var invoiceId = $(this).data('id'); // Get invoice ID from button data attribute
                window.location.href = '/admin/invoice_download/' + invoiceId + '/content';
                // Fetch the invoice content from the server

            });

            // $('.btn-print').on('click', function() {
            //     var invoiceId = $(this).data('id'); // Get invoice ID from button data attribute
            //     $.ajax({
            //         url: '/admin/invoice_print/' + invoiceId +
            //             '/content', // Route to fetch invoice content
            //         type: 'GET',
            //         success: function(response) {
            //             console.log(response.html);
            //             if (response.html) {
            //                 // Hide the current form and table content
            //                 $('.invoice-content').hide();

            //                 // Inject the HTML content into the print section
            //                 $('#invoice-content').html(response.html).show();

            //                 // Trigger the print function
            //                 window.print();

            //                 // After printing, restore the original content
            //                 $('#invoice-content').hide();
            //                 $('.invoice-content').show();
            //             } else {
            //                 console.error('No HTML content received.');
            //             }
            //         },
            //         error: function(xhr) {
            //             console.error('Error fetching invoice content:', xhr);
            //         }
            //     });
            // });

            $('.btn-print').on('click', function() {
                var invoiceId = $(this).data('id'); // Get invoice ID from button data attribute
                $.ajax({
                    url: '/admin/invoice_print/' + invoiceId + '/content', // Route to fetch invoice content
                    type: 'GET',
                    success: function(response) {
                        if (response.html) {
                            // Create a hidden iframe
                            var iframe = document.createElement('iframe');


                            document.body.appendChild(iframe); // Append iframe to body

                            // Get the iframe document
                            var doc = iframe.contentWindow.document;
                            doc.open();
                            var styles = '';
                            Array.from(document.styleSheets).forEach((styleSheet) => {
                                if (styleSheet.href) {
                                    styles += '<link rel="stylesheet" href="' +
                                        styleSheet.href + '">';
                                }
                            });
                            doc.write(`
                                <html>
                                    <head>
                                        <title>Invoice</title>
                                        ${styles} <!-- Include stylesheets -->
                                    </head>
                                    <body>
                                        ${response.html} <!-- Include the HTML content -->
                                    </body>
                                </html>
                            `);
                            // doc.write(response.html);    // Write the HTML response into the iframe
                            doc.close();

                            // Wait for the content to be fully loaded before printing
                            iframe.onload = function() {
                                iframe.contentWindow.focus();
                                iframe.contentWindow.print();
                                document.body.removeChild(
                                iframe); // Clean up the iframe after printing
                            }; // Trigger print in the iframe

                            // Optional: Remove the iframe after printing

                        } else {
                            console.error('No HTML content received.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching invoice content:', xhr);
                    }
                });
            });


            // $('#download-zip').click(function() {
            //     var selectedInvoices = [];
            //     $('.invoice-checkbox:checked').each(function() {
            //         selectedInvoices.push($(this).val());
            //     });

            //     if (selectedInvoices.length > 0) {
            //         var zip = new JSZip();
            //         var invoicesProcessed = 0;
            //         var pdfPromises = [];

            //         selectedInvoices.forEach(function(invoiceId) {
            //             // Assuming you have an AJAX call to fetch the HTML content for each invoice.
            //             var pdfPromise = $.ajax({
            //                     url: '/admin/invoice_download/' + invoiceId +
            //                     '/content', // Adjust URL as needed
            //                     type: 'GET',
            //                     success: function(response) {
            //                         if (response.html) {
            //                             return html2pdf()
            //                                 .from(response.html)
            //                                 .toPdf()
            //                                 .get('pdf');
            //                         } else {
            //                             console.error(
            //                                 'Error: No HTML content received for invoice ' +
            //                                 invoiceId);
            //                             return Promise.resolve(null);
            //                         }
            //                     },
            //                     error: function(err) {
            //                         console.error('Error fetching invoice:', err);
            //                         return Promise.resolve(null);
            //                     }
            //                 })
            //                 .then((pdf) => {
            //                     if (pdf) {
            //                         zip.file('invoice_' + invoiceId + '.pdf', pdf.output(
            //                             'blob'), {
            //                                 binary: true
            //                             });
            //                     }
            //                 });

            //             pdfPromises.push(pdfPromise);
            //         });

            //         Promise.all(pdfPromises)
            //             .then(() => {
            //                 return zip.generateAsync({
            //                     type: "blob"
            //                 });
            //             })
            //             .then((content) => {
            //                 if (content) {
            //                     let link = document.createElement('a');
            //                     link.href = window.URL.createObjectURL(content);
            //                     link.download = 'invoices.zip';
            //                     document.body.appendChild(link);
            //                     link.click();
            //                     document.body.removeChild(link);
            //                 }
            //             })
            //             .catch(function(err) {
            //                 console.error('Error generating PDF:', err); // Log errors
            //             });
            //     } else {
            //         alert('Please select at least one invoice.');
            //     }
            // });

            $('#download-zip').click(function() {
                var selectedInvoices = [];
                $('.invoice-checkbox:checked').each(function() {
                    selectedInvoices.push($(this).val()); // Collect selected invoice IDs
                });

                if (selectedInvoices.length > 0) {
                    // AJAX request to send selected invoice IDs to the backend for PDF generation and zipping
                    $.ajax({
                        url: '/admin/download_zip_invoice', // The URL that matches your Laravel route for zip download
                        type: 'POST',
                        data: {
                            invoice_ids: selectedInvoices, // Send the array of invoice IDs
                            _token: $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token
                        },
                        xhrFields: {
                            responseType: 'blob' // Ensure the response is treated as a blob (binary data)
                        },
                        success: function(response, status, xhr) {
                            // Create a temporary link element to trigger the download
                            var blob = new Blob([response], {
                                type: 'application/zip'
                            });
                            var link = document.createElement('a');

                            // Get filename from Content-Disposition header
                            var fileName = xhr.getResponseHeader('Content-Disposition') ?
                                xhr.getResponseHeader('Content-Disposition').split('filename=')[
                                    1].replace(/['"]/g, '') :
                                'invoices.zip';

                            link.href = window.URL.createObjectURL(blob);
                            link.download = fileName;
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link); // Clean up the DOM after download
                        },
                        error: function(err) {
                            console.error('Error downloading ZIP:', err);
                            alert(
                                'An error occurred while trying to download the ZIP file. Please try again.'
                            );
                        }
                    });
                } else {
                    alert('Please select at least one invoice.');
                }
            });

        });

        @if (session('message'))
            Swal.fire({
                icon: 'success'
                title: 'Success'
                text: '{{ session('message') }}'
                toast: true,
                position: 'top-end',
                timer: 4000,
                timeProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        @endif
    </script>
@endsection
