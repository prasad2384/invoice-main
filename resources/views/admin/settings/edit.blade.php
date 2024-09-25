@extends('admin.layout')
@section('title')
    Edit Settings
@endSection
@section('content')
    <style>
        .error {
            color: red;
        }
    </style>
    <div class="content-wrapper">
        <section class="content mt-3">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title float-left ">Update Setting</h3>
                    </div>
                    <div class="card-body">
                        <form id="submit_form" method="POST" action="{{ url('admin/settings/' . $data->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="">Update Logo</label>
                                    <div class="d-flex justify-content-center">
                                        @if ($data->update_logo)
                                            <img src="{{ asset('images/' . $data->update_logo) }}" width="150px"
                                                height="150px" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" name="company_name"
                                            value="{{ old('company_name', $data->company_name) }}" id="company_name"
                                            class="form-control" placeholder="Enter Company Name">
                                        <span style="color:red" id=""></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Trade Name</label>
                                        <input type="text" name="trade_name" id="trade_name"
                                            value="{{ old('trade_name', $data->trade_name) }}" class="form-control"
                                            placeholder="Enter Trade Name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>GST No</label>
                                        <input type="text" name="gst_number"
                                            value="{{ old('gst_number', $data->gst_number) }}" id="gst_number"
                                            class="form-control" placeholder="Enter Gst Number">

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Update Logo</label>
                                        <input type="file" name="update_logo" id="update_logo" class="form-control"
                                            placeholder="Enter Phone No">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>LUT</label>
                                        <input type="text" class="form-control" value="{{ old('lut', $data->lut) }}"
                                            name="lut" id="lut">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Company Address</label>
                                        <textarea name="company_address" id="company_address" class="form-control" cols="30" rows="2">{{ old('company_address', $data->company_address) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary float-right mt-2">Update</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function() {
            // console.log("Document is ready and validation script loaded!");
            // Initialize jQuery validation
            $.validator.addMethod("validFile", function(value, element, param) {
                // Check if the input is empty or not (to allow empty input if not updating logo)
                if (element.files.length === 0) {
                    return true; // Pass validation if no file is selected
                }

                var fileExtension = value.split('.').pop().toLowerCase();
                return $.inArray(fileExtension, param) !== -1; // Check if extension is allowed
            }, "Please upload a valid image file (jpg, jpeg, png, gif).");
            $.validator.addMethod("validGST", function(value, element) {
                // Regular expression for GST number validation
                var gstPattern = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

                // Check if GST number matches the pattern
                return this.optional(element) || gstPattern.test(value);
            }, "Please enter a valid GST Number.");
            $('#submit_form').validate({
                rules: {
                    company_name: {
                        required: true,
                        minlength: 3
                    },
                    trade_name: {
                        required: true,
                        minlength: 2
                    },
                    gst_number: {
                        required: true,
                        validGST: true,
                        minlength: 15,
                        maxlength: 15,
                    },
                    update_logo: {
                        required: false,
                        validFile: ["jpg", "jpeg", "png", "gif"]
                    },
                    lut: {
                        required: true
                    },
                    company_address: {
                        required: true,
                        minlength: 10
                    }
                },
                messages: {
                    company_name: {
                        required: "Company Name is required.",
                        minlength: "Company Name should be at least 3 characters."
                    },
                    trade_name: {
                        required: "Trade Name is required.",
                        minlength: "Trade Name should be at least 2 characters."
                    },
                    gst_number: {
                        required: "GST Number is required.",
                        minlength: "GST Number should be 15 characters long.",
                        maxlength: "GST Number should not exceed 15 characters.",
                        validGST: "Please enter a valid GST Number."
                    },
                    update_logo: {
                        validFile: "Please upload a valid image file (jpg, jpeg, png, gif)."
                    },
                    lut: {
                        required: "LUT is required."
                    },
                    company_address: {
                        required: "Company Address is required.",
                        minlength: "Company Address should be at least 10 characters."
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function() {
                    $('#submit_form')[0].submit();
                    // form.submit(); // Submit the form if all validations pass
                }
            });
            // $('button[type="submit"]').click(function(e) {
            //     console.log("Submit button clicked!");
            //     e.preventDefault(); // Prevent form submission for debugging
            //     if ($('#submit_form').valid()) {
            //         console.log("Form is valid, now submitting...");
            //         $('#submit_form').submit(); // Submit the form only if it's valid
            //     } else {
            //         console.log("Form is not valid, please check the inputs.");
            //     }
            // });
        });
    </script>
@endsection
