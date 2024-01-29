<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Role $role)
    {   
        if($request->ajax())
        {
            return $this->getPermissionsList($request->role_id);
        }
        return view('users.permissions.index');
    }

    /**
     * Show form for creating permissions
     * 
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {   
        return view('users.permissions.modal', ['permission' => new Permission() ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, [
            'name' => 'required|unique:permissions,name'
        ]);
        $permission = Permission::create(["name"=>strtolower($request->name)]);
        if($permission){                    
            return response(['success'=>"New Permission Added"], 200);
        }
        else{
                return response(['errors'=>"Server Error"], 201);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Permission  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('users.permissions.modal', [
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {        
        $this->validate($request, [
            'name' => 'required|unique:permissions,name,'.$request->name
        ]);
       
        if($permission->update($request->only('name'))){          
            return response(['success'=>"Permissions updated"], 200);
        }
        else{
                return response(['errors'=>"Server Error"], 201);
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if($permission->delete())
        {
            return response(['message'=>"Permissons Delete Successfully"], 200);
        }
        else{
                return response(['message'=>"Server Error"], 201);
        }
    }

    public function getPermissionsList($role_id)
    {
        $me = Auth::user();
        $data = Permission::where('name','!=', 'login')->where('name','!=', 'logout')->orderby('id', 'desc')->get(); 
        return DataTables::of($data, $role_id, $me)
            ->addColumn('chkbox', function($row) use ($role_id){   
                
                $chkBox='<div class="form-group">';                
                if($row->name=="dashboard" || $row->name=="users.show" || $row->name=="users.profile"){
                    return $chkBox='<div class="form-group"><div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-primary custom-control-input-outline permission" 
                        name="permission['.$row->name.']" 
                        type="checkbox" id="'.$row->id.'" value="'.$row->name.'" checked onclick="return false;">
                        <label for="'.$row->id.'" class="custom-control-label"></label>
                    </div>';
                }else{
                        if($role_id!=""){
                            $role = Role::where('id', $role_id)->first();
                            $rolePermissions = $role->permissions->pluck('name')->toArray();
                            if(in_array($row->name, $rolePermissions))
                            {
                                return '<div class="form-group"><div class="custom-control custom-checkbox">
                                <input class="custom-control-input custom-control-input-primary custom-control-input-outline permission" 
                                name="permission['.$row->name.']" 
                                type="checkbox" id="'.$row->id.'" value="'.$row->name.'" checked>
                                <label for="'.$row->id.'" class="custom-control-label"></label>
                            </div>';
                            }
                        }
                            return '<div class="form-group"><div class="custom-control custom-checkbox">
                                    <input class="custom-control-input custom-control-input-primary custom-control-input-outline permission" 
                                    name="permission['.$row->name.']" 
                                    type="checkbox" id="'.$row->id.'" value="'.$row->name.'">
                                    <label for="'.$row->id.'" class="custom-control-label"></label>
                            </div>';
                        
                }
                //$chkBox .="</div>";
                return $chkBox;
                
            })         
            ->addColumn('action', function($row)use ($me){
                $action = "";
                if($me->can('users.permissions.update')){
                    $action .='<button class="btn btn-warning btn-xs edit"  id="btnEdit"
                        data-id="'.$row->id.'"><i class="fas fa-fw fa-edit"></i></button>  ';
                }
                if($me->can('users.permissions.destroy')){
                    $action .='<button  id='.$row->id.' class="delete btn btn-outline-danger btn-xs">
                        <i class="fas fa-fw fa-trash"></i>
                    </button>';
                }
                return $action;
            
            })
        ->rawColumns(['chkbox', 'users_count','action'])->make(true);
    }
}