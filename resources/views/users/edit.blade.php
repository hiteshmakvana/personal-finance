@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
<div class="container mt-4" style="max-width:540px;">
    <div class="card">
        <div class="card-header bg-primary text-white">Edit User</div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" id="role-select" required>
                        <option value="super_admin" @if($user->role==='super_admin') selected @endif>Super Admin</option>
                        <option value="admin" @if($user->role==='admin') selected @endif>Admin</option>
                        <option value="manager" @if($user->role==='manager') selected @endif>Manager</option>
                    </select>
                </div>
                <div class="mb-3" id="capability-group" style="display:{{ $user->role==='manager' ? 'block' : 'none' }};">
                    <label class="form-label">Manager Capability</label>
                    <select name="manager_capability" class="form-select">
                        <option value="income" @if($user->manager_capability==='income') selected @endif>Only Income</option>
                        <option value="expense" @if($user->manager_capability==='expense') selected @endif>Only Expense</option>
                        <option value="both" @if($user->manager_capability==='both') selected @endif>Both</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('role-select').addEventListener('change', function(){
        document.getElementById('capability-group').style.display = this.value==='manager' ? 'block' : 'none';
    });
});
</script>
@endsection
