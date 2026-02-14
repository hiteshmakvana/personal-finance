@extends('layouts.app')
@section('title', 'Create User')
@section('content')
<div class="container mt-4" style="max-width:540px;">
    <div class="card">
        <div class="card-header bg-white border-bottom">
            <span class="h5 mb-0">Add User</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" id="role-select" required>
                        <option value="super_admin" @if(old('role')==='super_admin') selected @endif>Super Admin</option>
                        <option value="admin" @if(old('role')==='admin') selected @endif>Admin</option>
                        <option value="manager" @if(old('role')==='manager') selected @endif>Manager</option>
                    </select>
                </div>
                <div class="mb-3" id="capability-group" style="display:{{ old('role')==='manager' ? 'block' : 'none' }};">
                    <label class="form-label">Manager Capability</label>
                    <select name="manager_capability" class="form-select">
                        <option value="income" @if(old('manager_capability')==='income') selected @endif>Only Income</option>
                        <option value="expense" @if(old('manager_capability')==='expense') selected @endif>Only Expense</option>
                        <option value="both" @if(old('manager_capability')==='both') selected @endif>Both</option>
                    </select>
                </div>
<div class="d-flex justify-content-end gap-2 mt-3">
    <button type="submit" class="btn btn-primary">Add User</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
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
