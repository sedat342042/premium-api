@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
<!-- Content Header (Page header) -->
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Permissions</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="">Users</a></li>
                <li class="breadcrumb-item active"><a href="">Permissions</a></li>
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
                @can('users.permissions.store')
                <a class="float-right btn btn-primary btn-xs m-0 btnAdd" data-toggle="modal"  data-target="#actionModal">
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
                      <th>Route Name</th>
                      <th>Guard</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>    
    <!-- Edit Modal-->
    <div class="modal fade" id="modalAction" data-backdrop="static" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
           
        </div>       
    </div>
    <!-- Edit Modal-->
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

@section('css')
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
            //pageLength:15,
            //lengthMenu: [15,30, 50, 100],
            ajax: "{{ route('users.permissions.index') }}",
            columns : [
                {data:'id',name:'id'},
                {data:'name',name:'name'},
                {data:'guard_name',name:'guard_name'},            
                {data:'action',name:'action', orderable: false, searchable: false, className: "dt-center"},
            ],
            "order": [[ 0, "desc" ]]  
        });
       // Delete Ajax request.
       var deleteID;
        $('body').on('click', '.delete', function(e){
            e.preventDefault();
            deleteID = $(this).attr('id');
            //alert(deleteID);
            //console.log(deleteID);
            $('#deleteModal').modal('show');
        });
        $('#SubmitDeleteForm').click(function(e) {
            var url = "{{ route('users.permissions.destroy', ":id") }}";
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
        $('body').on('click', '.btnAdd',function () { 
            $.ajax({
                url: "{{route('users.permissions.create')}}",
                method:"get",
                success:function(response){
                    $('#modalAction').find('.modal-dialog').html(response);
                    $('#modalAction').modal({ show: true });
                    updateOrCreate();
                }
            });   
        }); 
        //When edit  button clicked
        $('body').on('click', '.edit',function () {  
            var url = "{{ route('users.permissions.edit', ":id") }}";
            url = url.replace(':id', $(this).data("id"));
            $.ajax({
                url: url,
                method:"get",
                success:function(response){
                    $('#modalAction').find('.modal-dialog').html(response);
                    $('#modalAction').modal({ show: true });
                    updateOrCreate();
                }
            });              
        });
        function updateOrCreate(){
            $('#formAction').on('submit', function(e){
                e.preventDefault();
                var _formData = $("#formAction").serialize();                           
                var url = $(this).attr('action');
                //url = url.replace(':id', $('#id').val());
                console.log(url);
                $.ajax({
                    method:"POST", 
                    url, 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: 'application/x-www-form-urlencoded',
                    processData: false,   
                    cache: false,                       
                    data: _formData,
                    success:function(response){
                        $('#tblData').DataTable().ajax.reload();   
                        setTimeout( function(){
                            $("#modalAction").modal('hide')
                            $('.modal-backdrop').hide();
                            $("body").removeClass("modal-open");
                        }, 500 );    
                    },
                    error:function(response){
                        var errors = response.responseJSON?.errors;
                        $('#formAction').find('.text-danger.text-small').remove();
                        if(errors)
                        {
                            $.each(errors, function (key, val){
                                $(`[name=${key}]`).parent().append(`<span class="text-danger text-small">${val}</span>`);
                            });
                        }
                    }

                })
            });
        }
        
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