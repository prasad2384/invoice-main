@extends('admin.layout')
@section('title')
    INVOICE#{{ $data->invoice_number }}
@endSection
@section('content')
    <style>
        .table {
            width: 100%
        }

        .error {
            color: red;
            text-wrap: nowrap;
        }

        .invoice-table th {
            font-size: 18px;
            background: lightgray;
        }

        .content-wrapper {
            background: white;
        }

        .billed-from {
            margin: 15px
        }

        .billed-to {
            margin: 15px
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
                <h1 style="font-size:55px;" class="fw-bold text-gray text-center">Invoice</h1>
                <form action="{{ url('admin/invoices/' . $data->id) }}" method="POST" id="invoiceform">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2 mt-5 justify-content-between">
                        <div class="col-sm-6">
                            <img style="width:250px" class="img-responsive"
                                src="{{ asset('images/webroot-infosoft-logo.jpg') }}" alt="Megaline Newswire"><br>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="invoice_number">Invoice No #</label>
                                <input type="text" class="form-control" name="invoice_number" readonly
                                    value="{{ $data->invoice_number }}">
                            </div>
                            <div class="form-group">
                                <label for="invoice_number">Invoice Date</label>
                                <input type="date" class="form-control" name="invoice_date" value="{{ $data->date }}">
                            </div>
                        </div>
                        <div class="mt-5">
                            <div class="row justify-content-between">
                                <div class="col-sm-5 bg-light billed-from border">
                                    <div class="billed-from">
                                        <h5 class="fw-bold">Billed by</h5>
                                        <h5>{{ auth()->user()->name }}</h5>
                                        <h5>{{ auth()->user()->address }}</h5>
                                        <h5>{{ auth()->user()->state->name }}, {{ auth()->user()->country->name }}</h5>
                                        <h5>GSTIN: {{ auth()->user()->gstin }}</h5>
                                        <h5>Export without payment of tax</h5>
                                        <h5>LUT: AD030323009765D</h5>
                                    </div>
                                </div>

                                <div class="col-sm-5 bg-light border">
                                    <div class="billed-to">
                                        <select name="user_id" id="user_id" class="form-control form-select">
                                            @foreach ($users as $user)
                                                @if ($user->id == $data->user->id)
                                                    <option value="{{ $user->id }}" selected>{{ $user->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="float-right">
                                            <button type="button"
                                                class="mt-1 nav-link text-primary refresh-users">Refresh</button>
                                        </div>
                                        <br>
                                        <h5 class="fw-bold billed-to-text">Billed to</h5>
                                        <div class="billed-to-user">
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
                                            @if ($data->user->gstin != null)
                                                <h5>{{ $data->user->gstin }}</h5>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary mb-2" id="gst-configure-btn"
                                        data-bs-toggle="modal" data-bs-target="#gstConfigureModal">GST Configure</button>
                                    <div class="table-responsive">
                                        <table class="table invoice-table" id="invoiceTable">
                                            <thead>
                                                <tr>
                                                    <th>Desciption</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">Rate</th>
                                                    <th class="text-center">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="width:20%">
                                                        <h5>
                                                            <textarea name="description" rows="2" class="form-control">{{ $data->description }}</textarea>
                                                        </h5>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="any" name="quantity" id="quantity"
                                                            min='1' class="form-control "
                                                            value="{{ $data->quantity }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="any" name="rate" id="rate"
                                                            min='1' class="form-control "
                                                            value="{{ $data->rate }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" step="any" name="amount" id="amount"
                                                            min="0" class="form-control">
                                                    </td>
                                                </tr>
                                                <input type="hidden" name="total" id="hidden_total_amount">
                                            </tbody>
                                        </table>
                                        <div class="table table-responsive"></div>
                                        <table class="table ">
                                            <tr class="align-items-center">
                                                <td colspan="">Payment Method</td>
                                                <td colspan="3">
                                                    <select name="payment_method" id="payment_method" class="form-select">
                                                        <option value="">------Select Payment Method--------</option>
                                                        <option value="Payment method 1"
                                                            {{ isset($data->payment_method) && $data->payment_method == 'Payment method 1' ? 'selected' : '' }}>
                                                            Payment method 1</option>
                                                        <option value="Payment method 2"
                                                            {{ isset($data->payment_method) && $data->payment_method == 'Payment method 2' ? 'selected' : '' }}>
                                                            Payment method 2</option>
                                                        <option value="Payment method 3"
                                                            {{ isset($data->payment_method) && $data->payment_method == 'Payment method 3' ? 'selected' : '' }}>
                                                            Payment method 3</option>
                                                        <option value="Payment method 4"
                                                            {{ isset($data->payment_method) && $data->payment_method == 'Payment method 4' ? 'selected' : '' }}>
                                                            Payment method 4</option>
                                                    </select>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="width:75%">
                                                    <h4 class="fw-bold">TOTAL:</h4>
                                                </td>
                                                <td>
                                                    <h4 class="text-right fw-bold" id="total_amount"></h4>
                                                </td>
                                            </tr>
                                        </table>
                                        <button type="submit" class="btn  btn-primary float-end">Update Invoice</button>
                                    </div>

                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- GST Configure Modal -->
                        <div class="modal fade" id="gstConfigureModal" tabindex="-1"
                            aria-labelledby="gstConfigureModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="gstConfigureModalLabel">GST Configure</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Table or List for Taxes -->
                                        <div class="form-group">
                                            <select name="tax_id" id="gstSelect" class="form-control">
                                                @foreach ($taxes as $tax)
                                                    <option value="{{ $tax->id }}"
                                                        data-tax-name="{{ $tax->name }}"
                                                        data-tax-rate="{{ $tax->rate }}"
                                                        {{ $data->tax_id == $tax->id ? 'selected' : '' }}>
                                                        {{ $tax->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
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
                </form>
                <!-- /.container-fluid -->
        </section>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        $("#invoiceform").validate({
            rules: {
                invoice_date: {
                    required: true,
                    date: true
                },
                user_id: {
                    required: true
                },
                description: {
                    required: true
                },
                quantity: {
                    required: true,
                    digits: true,
                    min: 1
                },
                rate: {
                    required: true,
                    number: true,
                    min: 0
                },
                amount: {
                    required: true,
                    number: true,
                    min: 1
                },
                payment_method: {
                    required: true,
                },

            },
            messages: {
                invoice_number: {
                    required: "Please select an invoice date.",
                    date: "Please enter a valid date."
                },
                user_id: {
                    required: "Please select a client."
                },
                description: {
                    required: "Please enter a description."
                },
                quantity: {
                    required: "Please enter the quantity.",
                    digits: "Quantity must be a positive integer.",
                    min: "Quantity must be at least 1."
                },
                rate: {
                    required: "Please enter the rate.",
                    number: "Please enter a valid number.",
                    min: "Rate cannot be negative."
                },
                amount: {
                    required: "Please enter the amount.",
                    number: "Please enter a valid number.",
                    min: "Amount cannot be negative."
                }

            },
            submitHandler: function(form) {
                // Perform the form submission or any other logic here
                form.submit();
            }
        });
        $('#user_id').change(function() {
            user_id = $('#user_id').val();
            $.ajax({
                type: "GET",
                url: "{{ url('api/user') }}/" + user_id,
                success: function(result) {
                    console.log(result);
                    if (result.state_id != 0) {
                        stateCountry = "<h5>" + result.state.name + ", " + result.country.name +
                            "</h5>";
                    } else {
                        stateCountry = "<h5>" + result.country.name + "</h5>";
                    }

                    (result.name != null) ? name = "<h5>" + result.name + "</h5>": name = "";
                    (result.address != null) ? address = "<h5>" + result.address + "</h5>": address =
                        "";
                    (result.gstin != null) ? gstin = "<h5>" + result.gstin + "</h5>": gstin = "";

                    $('.billed-to-user').html(
                        name +
                        address +
                        stateCountry +
                        gstin
                    );
                },
            });
        });
        $('.refresh-users').click(function() {
            // Disable the refresh button
            $('.refresh-users').attr('disabled', 'disabled');

            // Remove any content inside the billed-to-user div
            $('.billed-to-user').html('');

            // Make an AJAX request to get the updated user list
            $.ajax({
                type: "GET",
                url: "{{ url('api/users') }}",
                success: function(result) {
                    // Re-enable the refresh button after success
                    $('.refresh-users').removeAttr('disabled');

                    // Clear the current options in the user_id select box
                    $('#user_id').html('');

                    // Add the first default option as selected
                    $('#user_id').append('<option value="">------Select Client---------</option>');

                    // Loop through the returned users and append them to the select box
                    $.each(result, function(key, value) {
                        $('#user_id').append("<option value=" + value.id + ">" + value.name +
                            "</option>");
                    });

                    // If there was a previously selected user, reset it to the default option
                    $('#user_id').val(''); // This ensures the first option is selected
                },
            });
        });
        $(document).ready(function() {
            function initializeTable() {
                const gstType = $('#gstSelect option:selected').data('tax-name');
                const gstRate = parseFloat($('#gstSelect option:selected').data('tax-rate'));

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
                    '<th class="gst-rate-header text-center" style="text-wrap:nowrap;">GST Rate</th><th class="igst-amount-header text-center">IGST</th><th class="total-header text-center">Total</th>'
                );

                $('#invoiceTable tbody tr').each(function() {
                    $(this).append(
                        '<td class="gst-rate" ></td><td class="igst-amount"></td><td class="total-amount"><input type="text" name="total-amount" class="form-control" /></td>'
                    );
                });

                calculateGST(gstRate, 'IGST');
            }

            function addCGSTSGSTColumns(gstRate) {
                $('#invoiceTable thead tr').append(
                    '<th class="gst-rate-header" style="text-wrap:nowrap;">GST Rate</th><th class="cgst-header">CGST</th><th class="sgst-header">SGST</th><th class="total-header">Total</th>'
                );

                $('#invoiceTable tbody tr').each(function() {
                    $(this).append(
                        '<td class="gst-rate"></td><td class="cgst-amount"></td><td class="sgst-amount"></td><td class="total-amount"><input type="text" name="total-amount" class="form-control" /></td>'
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
            // Handle Save changes button click in the GST configure modal
            $('#gstSelect').change(function() {
                const selectedOption = $('#gstConfigureModal select option:selected');
                const gstType = selectedOption.data('tax-name'); // Get the selected GST type name
                const gstRate = parseFloat(selectedOption.data('tax-rate')); // Get the selected GST rate

                // Remove any existing GST Rate or related amount columns
                $('#invoiceTable thead tr .gst-rate-header, #invoiceTable thead tr .igst-amount-header, #invoiceTable thead tr .total-header, #invoiceTable thead tr .cgst-header, #invoiceTable thead tr .sgst-header')
                    .remove();
                $('#invoiceTable tbody tr .gst-rate, #invoiceTable tbody tr .igst-amount, #invoiceTable tbody tr .total-amount, #invoiceTable tbody tr .cgst-amount, #invoiceTable tbody tr .sgst-amount')
                    .remove();
                removeGSTColumns();
                if (gstType === 'IGST') {
                    addIGSTColumns(gstRate);
                } else if (gstType === 'CGST & SGST') {
                    addCGSTSGSTColumns(gstRate);
                } else {
                    removeGSTColumns();
                }

                calculateGST(gstRate, gstType);

                // Close the modal after applying changes
                $('#gstConfigureModal').modal('hide');
            });

            initializeTable();

            // Function to calculate GST based on rate and quantity
            function calculateGST(gstRate, gstType) {
                let overallTotal = 0; // Initialize overall total

                $('#invoiceTable tbody tr').each(function() {
                    const rate = parseFloat($(this).find('input[name="rate"]').val()) || 0;
                    const quantity = parseFloat($(this).find('input[name="quantity"]').val()) || 0;
                    const gstRateCell = $(this).find('.gst-rate');
                    const igstAmountCell = $(this).find('.igst-amount');
                    const cgstAmountCell = $(this).find('.cgst-amount');
                    const sgstAmountCell = $(this).find('.sgst-amount');
                    const amountCell = $(this).find('input[name="amount"]');
                    const totalAmountCell = $(this).find('input[name="total-amount"]');

                    // Check if rate and quantity are valid
                    if (!isNaN(rate) && !isNaN(quantity) && quantity > 0) {
                        const baseAmount = rate * quantity;
                        amountCell.val(baseAmount.toFixed(2)); // Display base amount

                        let rowTotal = baseAmount; // Initialize row total as base amount

                        // Apply GST calculation based on the selected type
                        if (gstType === 'IGST') {
                            const gstAmount = (gstRate / 100) * baseAmount;
                            rowTotal = baseAmount + gstAmount; // Add GST to row total
                            gstRateCell.text(gstRate + "%");
                            igstAmountCell.text(gstAmount.toFixed(2));
                            cgstAmountCell.text("0.00");
                            sgstAmountCell.text("0.00");
                            totalAmountCell.val(rowTotal.toFixed(2)); // Set total with GST
                        } else if (gstType === 'CGST & SGST') {
                            const gstAmount = (gstRate / 100) * baseAmount;
                            const cgst = gstAmount / 2;
                            const sgst = gstAmount / 2;
                            rowTotal = baseAmount + gstAmount; // Add GST to row total
                            gstRateCell.text(gstRate + "%");
                            cgstAmountCell.text(cgst.toFixed(2));
                            sgstAmountCell.text(sgst.toFixed(2));
                            igstAmountCell.text("0.00");
                            totalAmountCell.val(rowTotal.toFixed(2)); // Set total with GST
                        } else if (gstType === 'None') {
                            // No GST, just show base amount as total
                            gstRateCell.text("0%"); // No GST rate displayed
                            igstAmountCell.text("0.00");
                            cgstAmountCell.text("0.00");
                            sgstAmountCell.text("0.00");
                            totalAmountCell.val(baseAmount.toFixed(2)); // Set total as base amount only
                            rowTotal = baseAmount; // Ensure row total is only base amount
                        }

                        // Add row total to the overall total
                        overallTotal += rowTotal;
                    } else {
                        // If rate or quantity is invalid, reset the cells
                        amountCell.val("");
                        gstRateCell.text("");
                        igstAmountCell.text("0.00");
                        cgstAmountCell.text("0.00");
                        sgstAmountCell.text("0.00");
                        totalAmountCell.val("0.00");
                    }
                });

                // Update overall total in the UI
                $('#total_amount').text(overallTotal.toFixed(2)); // Display overall total
                $('#hidden_total_amount').val(overallTotal.toFixed(2));
            }

            function recalculateFromAmount(row) {
                const amount = parseFloat(row.find('input[name="amount"]').val()) || 0;
                const quantity = parseFloat(row.find('input[name="quantity"]').val()) || 0;

                if (!isNaN(amount) && !isNaN(quantity) && quantity > 0) {
                    const newRate = amount / quantity;
                    row.find('input[name="rate"]').val(newRate.toFixed(2)); // Ensure proper formatting
                }
            }

            // Recalculate GST when rate or quantity changes
            $(document).on('input', 'input[name="rate"], input[name="quantity"], input[name="amount"]', function() {
                const selectedOption = $('#gstConfigureModal select option:selected');
                const gstType = selectedOption.data('tax-name');
                const gstRate = parseFloat(selectedOption.data('tax-rate'));
                const row = $(this).closest('tr');

                // If the amount field is changed, recalculate the rate
                if ($(this).attr('name') === 'amount') {
                    recalculateFromAmount(row);
                }
                // After recalculating rate or amount, recalculate GST
                calculateGST(gstRate, gstType);
            });

            $(document).on('input', 'input[name="total-amount"]', function() {
                const row = $(this).closest('tr');
                const totalAmount = parseFloat($(this).val()) || 0;

                if (!isNaN(totalAmount)) {
                    // Get current values for rate, quantity, and GST
                    const rate = parseFloat(row.find('input[name="rate"]').val()) || 0;
                    const quantity = parseFloat(row.find('input[name="quantity"]').val()) || 0;
                    const gstRate = parseFloat($('#gstConfigureModal select option:selected').data(
                        'tax-rate')) || 0;
                    const gstType = $('#gstConfigureModal select option:selected').data(
                        'tax-name'); // Use GST type from modal

                    let baseAmount;
                    let recalculatedRate;

                    if (quantity > 0) {
                        // Calculate base amount from total amount, accounting for GST
                        baseAmount = totalAmount / (1 + gstRate / 100);
                        recalculatedRate = baseAmount / quantity;

                        // Update amount and rate fields
                        row.find('input[name="amount"]').val(baseAmount.toFixed(2));
                        row.find('input[name="rate"]').val(recalculatedRate.toFixed(2));
                    } else {
                        // If quantity is 0, set the amount and rate fields accordingly
                        baseAmount = totalAmount;
                        recalculatedRate = 0;

                        row.find('input[name="amount"]').val(baseAmount.toFixed(2));
                        row.find('input[name="rate"]').val(recalculatedRate.toFixed(2));
                    }
                    // Recalculate GST based on updated values
                    calculateGST(gstRate, gstType);
                }
            });
        });
    </script>
@endsection
