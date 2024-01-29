@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<!-- Content Header (Page header) -->
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Roles</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="">Users</a></li>
                <li class="breadcrumb-item active"><a href="">Roles</a></li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
<!-- /.content-header -->
@stop
@section('content')
<div class="container mt-4">
    <form method="POST" action="{{ route('users.roles.update', $role->id) }}">
        <div class="card">
        <div class="card-body">
                @method('patch')
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input value="{{ $role->name }}" 
                        type="text" 
                        class="form-control" 
                        name="name" 
                        placeholder="Name" required>
                </div>
                
                <label for="permissions" class="form-label">Assign Permissions</label>
                <div class="table-responsive">
                    <table id="tblData" class="table table-bordered table-striped data-table">
                        <thead>
                        <tr>
                            <th><div class="custom-control custom-checkbox">
                                <input class="custom-control-input custom-control-input-primary custom-control-input-outline" 
                                type="checkbox" id="all_permission" name="all_permission">
                                <label for="all_permission" class="custom-control-label"></label>
                                </div>
                            <th>Name</th>
                            <th>Guard</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="card-footer sticky-bottom">
                <button type="submit" class="btn btn-primary"  style="width:69%;">Update Role
                </button>
                <a href="{{ route('users.roles.index') }}" class="btn btn-default"  style="width:30%;">Back</a>
            </div>
        </div>   
    </form>
</div>
@stop


@section('css')
<style>   
    .custom-control {
        transform: scale(1.4);
    }
    .form-group {
        margin-bottom: 0.1rem; 
    }
    table.dataTable tbody td {
        padding-top: 4px;
        padding-bottom: 4px;
    }
    .sticky-bottom {
        z-index:1;
        position: sticky;
        bottom: 0;
    }
</style>
@stop

@section('js')

<script type="text/javascript">
    $(document).ready(function() {
        $('[name="all_permission"]').on('click', function() {

            if($(this).is(':checked')) {
                $.each($('.permission'), function() {
                    if($(this).val()!="dashboard" || $(this).val()!="users.show" || $(this).val()!="users.profile") 
                    {
                        $(this).prop('checked',true);
                    }
                });
            } else {
                $.each($('.permission'), function() {
                    if($(this).val()!="dashboard" || $(this).val()!="users.show" || $(this).val()!="users.profile") 
                    {
                        $(this).prop('checked',false);
                    }
                });
            }
            
        });
    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#tblData').DataTable({    
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            bPaginate: false,
            bLengthChange : false, 
            bFilter: true,
            searching: false,
            ajax: {
                url: "{{ route('users.permissions.index', ['role_id'=>$role->id]) }}"
            },
            columns : [
                {data:'chkbox',name:'chkbox', orderable: false, searchable: false, className: "dt-center"},
                {data:'name',name:'name'},
                {data:'guard_name',name:'guard_name', className: "dt-center"}
            ],
            "order": [[ 0, "desc" ]]  
        });
        
    });
</script>
@stop
@section('plugins.Datatables', true)