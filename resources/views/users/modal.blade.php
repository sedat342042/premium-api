<form action="{{ $user->id ? route('users.update', $user->id) :  route('users.store') }}" method="post" id="formAction" style="height:99.90% !important;">
    @csrf
    @if($user->id)
        @method('PUT')
    @endif
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $user->id ?  "Edit" : "Add New " }} User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body bg-light">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name<span style="color:red;"> *</span></label>
                        <input id="first_name"  type="text" class="form-control"
                            name="first_name" value="{{ $user->first_name }}" placeholder="First name" autofocus>                
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name<span style="color:red;"> *</span></label>
                        <input id="last_name" value="{{ $user->last_name }}" type="text" class="form-control"
                            name="last_name" placeholder="Last name">               
                    </div>
                </div>
            </div>           
            
            <div class="form-group">
                <label for="email" class="form-label">Phone</label>
                <input id="phone" value="{{ old('phone') }}" type="phone" class="form-control" name="phone"
                    placeholder="Phone number">               
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email<span style="color:red;"> *</span></label>
                <input id="email" value="{{ $user->email }}" type="email" class="form-control" name="email"
                    placeholder="Email address">               
            </div>
            <div class="form-group">
                <label for="{{ $user->id ? 'new_password' : 'password'}}" class="form-label">{{ $user->id ? 'New Password' : 'Password' }}</label>
                <input id="{{ $user->id ? 'new_password' : 'password'}}" value="{{ $user->id ? $user->new_password : $user->password}}"
                        type="password" class="form-control" name="{{ $user->id ? 'new_password' : 'password'}}" placeholder="{{ $user->id ? 'New Password' : 'Password'}}">
                @if($user->id)
                    <small class="pull-right float-right font-italic mb-2">Leave Empty if you dont want to update password </small>
                @endif                    
            </div>
            <div class="form-group">
                <label for="roles" class="form-label">Roles</label>
                @if(!$user->id) 
                    @can('users.roles.create')
                        <small class="form-label pull-right float-right">
                            <a   href="{{route('users.roles.create')}}">Add Roles</a>
                        </small>
                    @endcan
                @endif     
                <select class="form-control select2" multiple="multiple" id="select2"
                    data-placeholder="Select User Roles" name="roles[]" style="width:100%;">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->id ? (in_array($role->name, $userRole) 
                            ? 'selected'
                            : ''):"" }}> {{ ucFirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="default" class="form-label">Default</label>
                <div class="row">
                    <div class="col-6">
                        <div class="custom-control custom-radio">
                            <input
                                class="custom-control-input custom-control-input-primary custom-control-input-outline"
                                type="radio" id="customRadio5" name="status" value="1" {{ $user->status ? "checked":""}}>
                            <label for="customRadio5" class="custom-control-label">Active</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="custom-control custom-radio">
                            <input
                                class="custom-control-input custom-control-input-primary custom-control-input-outline"
                                type="radio" id="customRadio6" name="status" value="0"  {{ $user->status ==0 ? "checked":""}}>
                            <label for="customRadio6" class="custom-control-label">Offline</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary mr-auto" style="width:60%;">Save changes</button>
            <button type="button" class="btn btn-secondary" style="width:30%;" data-dismiss="modal">Close</button>
        </div>
    </div>
</form>
<script>
    $('#select2').select2({
        dropdownParent: $('#modalAction')
    });
</script>
