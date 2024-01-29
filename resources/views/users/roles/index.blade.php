@extends('adminlte::page')

@section('title', 'Roles')

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
<div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="card-title"><h5>List</h5></div>
                @can('users.roles.store')
                <a class="float-right btn btn-primary btn-xs m-0" href="{{route('users.roles.create')}}">
                    <i class="fas fa-fw fa-plus"></i> Add
                </a>            
                @endcan 
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="tblData" class="table table-bordered table-striped data-table">
                    <thead>
                    <tr>
                      <!--<th></th>-->
                      <th>#ID</th>
                      <th>Name</th>
                      <th>Users</th>
                      <th>Permissions</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>    
    <!-- Delete  Modal -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Delete Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-light">
                    <div id="errorbox"></div>
                    Delete the permission?                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger col-4" id="SubmitDeleteForm">Yes</button>
                    <button type="button" class="btn btn-default col-4" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete  Modal -->
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    $('#modalAction').on('show.bs.modal', function (e) { 
           $(this).find('text[name="name"]').focus();
            $('.modal .modal-dialog').attr('class', 'modal-dialog fadeIn');
        });
        var hideDelay = true;
        $('#modalAction').on('hide.bs.modal', function(e) {
            if (hideDelay) {
                $('.modal-content').removeClass('animated fadeIn').addClass('animated fadeOut');
                hideDelay = false;
                setTimeout(function() {
                    $('#modalAction').modal('hide');
                    $('.modal-content').removeClass('animated fadeOut').addClass('animated fadeIn');
                }, 700);
                return false;
            }
            hideDelay = true;
            return true;
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
        ajax: "{{ route('users.roles.index') }}",
        columns : [
            {data:'id',name:'id'},
            {data:'name',name:'name'},
            {data:'users_count',name:'users_count', className: "dt-center"},
            {data:'permissions_count',name:'permissions_count', className: "dt-center"},
            {data:'action',name:'action', orderable: false, searchable: false, className: "dt-center"},
        ],
        "order": [[ 0, "desc" ]] 
    });
    // Delete Ajax request.
    var deleteID;
    $('body').on('click', '.delete', function(e){
        e.preventDefault();
        deleteID = $(this).attr('id');
        $('#deleteModal').modal('show');
    });
    $('#SubmitDeleteForm').click(function(e) {
        var url = "{{ route('users.roles.destroy', ":id") }}";
        url = url.replace(':id', deleteID);
        $.ajax({
                url:url,
                type: "delete", 
                beforeSend:function(){
                   $('#SubmitDeleteForm').text('Deleting...');
                },
                success:function(response){
                    $('#SubmitDeleteForm').text('Yes');
                    $('#deleteModal').modal('hide');
                    $('#tblData').DataTable().ajax.reload();                       
                },
                error:function(response){               
                    $('#errorbox').html('<div class="errorbox alert alert-danger">'+response.message+'</div>');      
                }
            });
    });
    $('#deleteModal').on('show.bs.modal', function (e) {
         $('.modal .modal-dialog').attr('class', 'modal-dialog fadeIn');
    });
    var hideDelay = true;
    $('#deleteModal').on('hide.bs.modal', function(e) {
        if (hideDelay) {
            $('.modal-content').removeClass('animated fadeIn').addClass('animated fadeOut');
            hideDelay = false;
            setTimeout(function() {
                $('#deleteModal').modal('hide');
                $('.modal-content').removeClass('animated fadeOut').addClass('animated fadeIn');
            }, 700);
            return false;
        }
        hideDelay = true;
        return true;
    });
});
</script>
@stop
@section('plugins.Datatables', true)