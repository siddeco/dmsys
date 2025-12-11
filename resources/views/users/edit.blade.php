@extends('layouts.admin')

@section('content')

<div class="container mt-4">

    <h3>Edit User</h3>

    <div class="card shadow-sm mt-3">
        <div class="card-body">

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label>Name</label>
                    <input name="name"
                           class="form-control"
                           value="{{ $user->name }}"
                           required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input name="email"
                           type="email"
                           value="{{ $user->email }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>New Password (optional)</label>
                    <input name="password"
                           type="password"
                           class="form-control"
                           placeholder="Leave empty to keep current password">
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ $user->roles->pluck('name')->first() == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary">Update User</button>

            </form>

        </div>
    </div>
</div>

@endsection
