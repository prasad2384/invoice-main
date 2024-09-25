@extends('layouts.app')

@section('content')
<div class="container registration-page">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">{{ __('Sign Up') }}</div>

                        <div class="card-body">
                            <form id="quickForm" method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="name" for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="Enter name">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                   
                                <div class="form-group mb-3">
                                    <label for="email">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Enter email address">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror   
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Enter password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror  
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password-confirm">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Confirm password">   
                                </div>

                                <div class="form-group mb-3">
                                    <label for="country_id">Country <span class="text-danger">*</span></label>
                                    <select id="country_id" class="form-control form-select" name="country_id">
                                        <option value="">Select Country</option>
                                        @php $countries  = App\Models\Country::get() @endphp
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>   
                                </div>

                                <div class="form-group mb-3">
                                    <label for="city">City</label>
                                    <input id="city" class="form-control" name="city" autocomplete="city" placeholder="Enter city">   
                                </div>

                                <div class="form-group mb-3">
                                    <label for="address">Address</label>
                                    <input id="address" class="form-control" name="address" autocomplete="address" placeholder="Enter address">   
                                </div>

                                <div class="form-group mb-2">
                                    <label for="phone">Mobile Number</label>
                                    <input id="phone" class="form-control" name="phone" autocomplete="phone" placeholder="Enter mobile number">   
                                </div>

                                <div class="" style="margin-bottom:1px">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-muted mb-0">
                            Already have an account ?
                            <a href="{{ url('login') }}" class="text-primary fw-semibold">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // valiations
    $(function () {
      $('#quickForm').validate({
          rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
            },
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation: {
                required: true,
                minlength: 8,
            },
            country_id: {
                required: true
            },
            phone: {
                minlength: 9,
                maxlength: 15,
            },
          },
          messages: {
            
          },
          errorElement: 'span',
          errorPlacement: function (error, element) {
            error.addClass('invalid-feedback fs-6');
            element.closest('.form-group').append(error);
          },
          highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
          },
          unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
          }
      });
      $.validator.setDefaults({
        submitHandler: function () {
          $('#quickForm').submit();
        }
      });
    });
  </script>
@endsection



