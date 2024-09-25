@extends('admin.layout')
@section('title', 'Users')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12 d-flex justify-content-between align-items-center">
                        <h3>Users</h3>
                        <a href="{{ url('/admin/users/create') }}" class="btn btn-sm btn-primary text-white">Add
                            User</a>
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
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Country</th>
                                        <th>State</th>
                                        <th>Address</th>
                                        <th style="text-wrap:nowrap;">Postal Code</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            {{-- <td>{{ $key + $data->firstItem() }}</td> --}}
                                            <td>{{ $user->id }}</td>
                                            <td style="text-wrap:nowrap;">{{ $user->name }}</td>
                                            <td style="text-wrap:nowrap;">{{ $user->email }}</td>
                                            <td style="text-wrap:nowrap;">{{ $user->phone }}</td>
                                            <td style="text-wrap:nowrap;">{{ $user->country->name }}</td>
                                            <td style="text-wrap:nowrap;">{{ $user->state->name }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td style="text-wrap:nowrap;">{{ $user->postal_code }}</td>
                                            <td style="text-wrap:nowrap;" class="d-flex">
                                                <a class="btn btn-outline-dark btn-sm me-1"
                                                    href="{{ route('users.edit', $user->id) }}">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <form action="{{ url('admin/users/' . $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-dark btn-sm delete-button"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
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
    <script>
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
