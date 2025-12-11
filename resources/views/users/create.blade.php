@extends('layouts.admin')

@section('content')

<div class="container mt-4">

    <h3>Add User</h3>

    <div class="card shadow-sm mt-3">
        <div class="card-body">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary">Create User</button>

            </form>

        </div>
    </div>
</div>

@endsection
