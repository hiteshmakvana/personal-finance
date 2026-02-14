@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<div class="container mt-4" style="max-width:560px;">
    <div class="card mb-4">
        <div class="card-header bg-white border-bottom">
            <span class="h5 mb-0">Profile Information</span>
        </div>
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header bg-white border-bottom">
            <span class="h5 mb-0">Update Password</span>
        </div>
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header bg-white border-bottom">
            <span class="h5 mb-0">Delete Account</span>
        </div>
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
