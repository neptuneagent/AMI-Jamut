<!-- resources/views/admin/view_users.blade.php -->

@extends('adminlte::page')

@section('title', 'Manage Users')

@section('content')
    <div class="pt-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">List of Users</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-start mb-3">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUserModal">
                        <span class="fas fa-fw fa-plus"></span> Add User
                    </button>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                            <br/>
                        @endforeach
                    </div>
                @endif
                <table class="table" id="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th> <!-- Add the new column -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#showUserModal{{ $user->id }}">
                                        <span class="fas fa-fw fa-eye"></span> Show
                                    </button>
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal{{ $user->id }}">
                                        <span class="fas fa-fw fa-trash"></span> Delete
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteUserModalLabel">Confirm Deletion</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the user {{ $user->name }}?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('admin.delete-user', ['id' => $user->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="showUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="showUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="showUserModalLabel">User #{{ $user->id }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" disabled>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" class="form-control" value="{{ $user->email }}" disabled>
                                            </div>

                                            <!-- Form for updating roles -->
                                            <form action="{{ route('admin.update-roles', ['id' => $user->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="form-group">
                                                    <label><strong>Roles</strong></label><br>
                                                    <div class="d-flex justify-content-between align-items-center container">
                                                        @foreach($roles as $role)
                                                            <div class="form-check d-flex align-items-center">
                                                                <input type="checkbox" class="form-check-input" name="roles[]" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                                <label class="form-check-label">{{ $role->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Update Roles</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your user creation form here -->
                    <form action="{{ route('admin.add-user') }}" method="POST">
                        @csrf
                        <!-- Add form fields for user data (name, email, etc.) -->
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <!-- Add other form fields as needed -->

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#users-table').DataTable();
        });
    </script>
@stop
