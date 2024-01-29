<div class="modal-content">
    <form action="{{$permission->id ? route('users.permissions.update', $permission->id) :  route('users.permissions.store')}}" method="post" id="formAction">
        @csrf
        @if($permission->id)
            @method('PUT')
        @endif
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title"  id="modalTitle"> {{ $permission->id ?  "Edit" : "Add New " }}  Permission</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body  bg-light">
            <div class="form-group">
                <label for="name" class="form-label">Route Name<span style="color:red;"> *</span></label>
                <input id="name"
                value="{{ $permission->name }}" 
                type="text" 
                class="form-control" 
                name="name" 
                placeholder="Route name" autofocus>    
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btnUpdate" class="btn btn-primary mr-auto" style="width:60%;"> {{ $permission->id ? "Update" : "Save" }}</button>
            <button type="button" class="btn btn-secondary" style="width:30%;" data-dismiss="modal">Close</button>
        </div>
    </form>
</div>