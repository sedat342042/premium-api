@extends('adminlte::page')

@section('title', 'Profile | '.config('app.name'))

@section('content_header')
<!-- Content Header (Page header) -->
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="">Profile</a></li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
<!-- /.content-header -->
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <!-- <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">
                    </div> -->
                    <h3 class="profile-username text-center">{{$user->first_name.' '. $user->last_name}}</h3>
                    <p class="text-muted text-center">
                        @foreach ($user->roles as $item)
                            <span class="badge badge-primary"> {{ ucfirst($item ->name) }} </span> 
                        @endforeach
                    </p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> 
                            <a class="float-right" href="mailto:{{$user->email}}">{{$user->email}} 
                                @if($user->email_verified_at!="") 
                                <i class="fas fa-check-circle text-success" data-toggle="tooltip" data-placement="top" title="Verified"> </i> 
                                @else <i class="fa fa-exclamation-circle text-danger" data-toggle="tooltip" data-placement="top" title="Not Verified"> </i> 
                                @endif
                            </a>
                        </li>
                        @if(isset($user->phone))
                            <li class="list-group-item">
                                <b>Phone</b> <a class="float-right" href="tel:{{$user->phone}}">{{$user->phone}}</a>
                            </li>
                        @endif
                    </ul>
                    <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                </div>    
            </div>         
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop