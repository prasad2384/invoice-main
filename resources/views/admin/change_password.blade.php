@extends('admin.layout')
@section('title')
    Change Password
@endSection
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3>Change Password</h3>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7">
                    <div class="card profile-card">
                        <div class="card-body">
                            <form id="changepassword" action="{{ route('update_password_admin') }}" method="POST">
                                @csrf
                                
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @elseif (session('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="oldPasswordInput" class="form-label">Old Password</label>
                                        <input name="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" id="oldPasswordInput"
                                            placeholder="Enter Old Password">
                                        @error('old_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>    
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="newPasswordInput" class="form-label">New Password</label>
                                        <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" id="newPasswordInput"
                                            placeholder="Enter New Password">
                                        @error('new_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>    
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="confirmNewPasswordInput" class="form-label">Confirm New Password</label>
                                        <input name="new_password_confirmation" type="password" class="form-control" id="confirmNewPasswordInput"
                                            placeholder="Confirm New Password">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-rounded">Change Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    @if(Session::has('message'))
        <script>
            $(document).ready(function(){
                toastr.success("{{ Session::get('message') }}");
            })
        </script>
    @endif
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize form validation
    $('#changepassword').validate({
        rules: {
            old_password: {
                required: true,
            },
            new_password: {
                required: true,
               
            },
            new_password_confirmation: {
                required: true,
                equalTo: '#newPasswordInput'
            }
        },
        messages: {
            old_password: {
                required: "Please enter your old password.",
            },
            new_password: {
                required: "Please provide a new password.",
            },
            new_password_confirmation: {
                required: "Please confirm your new password.",
            }
        },
        errorElement: 'span',
        errorClass: 'text-danger',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>

@endsection