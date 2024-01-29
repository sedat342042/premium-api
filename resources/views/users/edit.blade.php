@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container mt-4">
    <form method="post" action="{{ route('users.update', $user->id) }}">
        @method('patch')
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input value="{{ $user->name }}" 
                type="text" 
                class="form-control" 
                name="name" 
                placeholder="Name" required>

            @if ($errors->has('name'))
                <span class="text-danger text-left">{{ $errors->first('name') }}</span>
            @endif
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input value="{{ $user->email }}"
                type="email" 
                class="form-control" 
                name="email" 
                placeholder="Email address" required>
            @if ($errors->has('email'))
                <span class="text-danger text-left">{{ $errors->first('email') }}</span>
            @endif
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input value="{{ $user->username }}"
                type="text" 
                class="form-control" 
                name="username" 
                placeholder="Username" required>
            @if ($errors->has('username'))
                <span class="text-danger text-left">{{ $errors->first('username') }}</span>
            @endif
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" 
                name="role" required>
                <option value="">Select role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ in_array($role->name, $userRole) 
                            ? 'selected'
                            : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('role'))
                <span class="text-danger text-left">{{ $errors->first('role') }}</span>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update user</button>
        <a href="{{ route('users.index') }}" class="btn btn-default">Cancel</button>
    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('[name="all_permission"]').on('click', function() {

            if($(this).is(':checked')) {
                $.each($('.permission'), function() {
                    $(this).prop('checked',true);
                });
            } else {
                $.each($('.permission'), function() {
                    $(this).prop('checked',false);
                });
            }
            
        });
    });
</script>
@stop