@extends('admin.layout')
@section('title', 'Create User')
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
                        <h3 class="card-title float-left ">Add User</h3>
                    </div>
                    <div class="card-body">
                        <form id="submit_form" method="POST" action="{{ url('admin/users') }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="firstname" value="{{ old('firstname') }}" id="firstname"
                                            class="form-control" placeholder="Enter First Name">
                                        <span style="color:red" id=""></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="lastname" id="lastname" value="{{ old('lastname') }}"
                                            class="form-control" placeholder="Enter Last Name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}" id="email"
                                            class="form-control" placeholder="Enter Email">
                                        @error('email')
                                            <span class="text-danger font-weight-bold error-email">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone No</label>
                                        <input type="number" name="phone" id="phone" class="form-control"
                                            placeholder="Enter Phone No" value="{{old('phone')}}">
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Enter Password" value="{{ old('password') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <select name="country" class="form-select" id="country">
                                            <option value="">---- Select Country -----</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ old('country') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select name="state" class="form-select" id="state">
                                            <option value="">---- Select State -----</option>

                                        </select>

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Postal Code</label>
                                        <input type="number" class="form-control" value="{{ old('postal_code') }}"
                                            name="postal_code" id="postal_code">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="2">{{ old('address') }}</textarea>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary  float-right mt-2">Submit</button>
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
        jQuery.validator.addMethod("noSpace", function(value, element) {
            return value.indexOf(" ") < 0 && value != "";
        }, "No space please and don't leave it empty");
        $.validator.addMethod("noDigits", function(value, element) {
            return this.optional(element) || !/\d/.test(value);
        }, "No digits allowed");
        $.validator.addMethod("noSpecialChars", function(value, element) {
            return this.optional(element) || /^[a-zA-Z\s]*$/.test(value);
        }, "No special characters allowed");
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        }, "Only letters and numbers are allowed");
        jQuery.validator.addMethod("phoneStart", function(value, element) {
            return this.optional(element) || /^[6-9]\d{9}$/.test(value);
        }, "Please enter a valid phone number and 10 digits long.");
        $('#submit_form').validate({
            rules: {
                firstname: {
                    required: true,
                    noSpace: true,
                    noDigits: true,
                    noSpecialChars: true,
                },
                lastname: {
                    required: true,
                    noSpace: true,
                    noDigits: true,
                    noSpecialChars: true,
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10,
                    phoneStart: true
                },
                password: {
                    required: true,
                    minlength: 5
                },
                country: {
                    required: true
                },
                state: {
                    required: true
                },
                postal_code: {
                    required: true,
                    number: true,
                    digits: true,
                    minlength: 6, // Requires the input to be at least 6 digits long
                    maxlength: 6

                },
                address: {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                firstname: {
                    required: "Please enter your first name",
                    minlength: "First name must be at least 2 characters long"
                },
                lastname: {
                    required: "Please enter your last name",
                    minlength: "Last name must be at least 2 characters long"
                },
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address"
                },
                phone: {
                    required: "Please enter your phone number",
                    number: "Phone number must be a valid number",
                    minlength: "Phone number must be exactly 10 digits",
                    maxlength: "Phone number must be exactly 10 digits"
                },
                password: {
                    required: "Please enter a password",
                    minlength: "Password must be at least 6 characters long"
                },
                country: {
                    required: "Please select your country"
                },
                state: {
                    required: "Please select your state"
                },
                postal_code: {
                    required: "Please enter your postal code",
                    number: "Postal code must be a number"
                },
                address: {
                    required: "Please enter your address",
                    minlength: "Address must be at least 5 characters long"
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
            }

        });
        $(document).ready(function() {

            $('#email').on('input', function() {
                $('.error-email').remove();
            });
            // Trigger country change event if there's an old value
            var oldCountry = "{{ old('country') }}";
            var oldState = "{{ old('state') }}";

            if (oldCountry) {
                $('#country').val(oldCountry).trigger('change');

                $.ajax({
                    url: "/api/states/" + oldCountry,
                    type: "GET",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#state').html('<option value=""> ---- Select State ----</option>');
                        $.each(result, function(key, value) {
                            $('#state').append('<option value="' + value.id + '" ' + (
                                    oldState == value.id ? 'selected' : '') + '>' + value
                                .name + '</option>');
                        });
                    },
                    error: function(error) {
                        $('#state').html('<option value="">----- Select State ----- </option>');
                    }
                });
            }

            // Rest of your existing AJAX logic for dynamically loading states
            $('#country').on('change', function() {
                var idcountry = this.value;
                $('#state').html('');
                $.ajax({
                    url: "/api/states/" + idcountry,
                    type: "GET",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#state').html('<option value=""> ---- Select State ----</option>');
                        $.each(result, function(key, value) {
                            $('#state').append('<option value="' + value.id + '">' +
                                value.name + '</option>')
                        });
                    },
                    error: function(error) {
                        $('#state').html('<option value="">----- Select State ----- </option>');
                    }
                });
            });
        });
    </script>

@endsection
