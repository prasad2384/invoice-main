@extends('admin.layout')
@section('title')
    Settings
@endSection
@section('content')
    <style>
        .table {
            width: 100%
        }
        @media print {
            .invoice-table th {
            font-size: 18px;
            background: lightgray;
        }
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12 d-flex justify-content-between align-items-center">
                        <h3>Settings</h3>
                        {{-- <a href="{{ url('/admin/invoices/create') }}" class="btn btn-sm btn-primary text-white">Add
                            Invoice</a> --}}
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="text-wrap:nowrap;">#</th>
                                        <th style="text-wrap:nowrap;">Company Name</th>
                                        <th style="text-wrap:nowrap;">Company Address</th>
                                        <th style="text-wrap:nowrap;">Trade Name</th>
                                        <th style="text-wrap:nowrap;">GST No</th>
                                        <th style="text-wrap:nowrap;">LUT</th>
                                        <th style="text-wrap:nowrap;">Company Logo</th>
                                        
                                        <th style="text-wrap:nowrap;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $setting)
                                        <tr>
                                            {{-- <td>{{ $key + $data->firstItem() }}</td> --}}
                                            <td style="text-wrap:nowrap;">{{ $setting->id }}</td>
                                            <td style="text-wrap:nowrap;">{{ $setting->company_name }}</td>
                                            <td style="text-wrap:nowrap;">{{ Str::limit($setting->company_address,15 )}}</td>
                                            <td style="text-wrap:nowrap;">{{ Str::limit($setting->trade_name,15 )}}</td>
                                            <td style="text-wrap:nowrap;">{{ $setting->gst_number }}</td>
                                            <td style="text-wrap:nowrap;">{{ $setting->lut }}</td>
                                            <td style="text-wrap:nowrap;"><img src="{{asset('/images/'.$setting->update_logo)}}" height="80px" alt="" width="80px"></td>
                                            
                                            <td>
                                                <div class="d-flex">
                                                    
                                                    <a class="btn btn-outline-dark btn-sm me-1"
                                                        href="{{ route('settings.edit', $setting->id) }}">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                   

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
            <div id="print-section" style="display: none;"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-download').on('click', function() {
                var invoiceId = $(this).data('id'); // Get invoice ID from button data attribute

                // Fetch the invoice content from the server
                $.ajax({
                    url: '/admin/invoice_download/' + invoiceId +
                        '/content', // Route to fetch invoice content
                    type: 'GET',
                    success: function(response) {
                        // Define PDF options
                        var options = {
                            margin: [0.5, 0.5, 0.5, 0.5],
                            filename: 'invoice_' + invoiceId +
                                '.pdf', // Use invoice ID in the filename
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

                        // Generate and download the PDF
                        html2pdf().from(response.html).set(options).save();
                    },
                    error: function(xhr) {
                        console.error('Error fetching invoice content:', xhr);
                    }
                });
            });
            $('.btn-print').on('click', function() {
                var invoiceId = $(this).data('id'); // Get invoice ID from button data attribute
                $.ajax({
                    url: '/admin/invoice_print/' + invoiceId +
                        '/content', // Route to fetch invoice content
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
                            iframe.contentWindow.focus(); // Focus on the iframe window
                            iframe.contentWindow.print(); // Trigger print in the iframe

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
