<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        //return Role::withCount(['users', 'permissions'])->get();
        if($request->ajax())
        {
            return $this->getRolesData();
        }
        return view('users.roles.index');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $permissions = Permission::get();
        return view('users.roles.create', compact('permissions'));
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
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        
        $role = Role::create(['name' => $request->get('name')]);
        $role->syncPermissions($request->get('permission'));
        if($role){
            toast('New Role '. $request->get('name').' Created Successfully!','success')->timerProgressBar();            
            return view('users.roles.index');
        }
        toast('Something Wrong!  Please Try Again.','error')->timerProgressBar();  
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
       
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Role $role)
    {
        if($request->ajax())
        {           
            return $this->getPermissionsList($role);
        }
        return view('users.roles.edit')->with(['role' => $role]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Role $role, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        
        $role->update($request->only('name'));
    
        $role->syncPermissions($request->get('permission'));

        toast('Role '. $request->get('name').' Updated Successfully!','success')->timerProgressBar();       
        return view('users.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if($role->delete())
        {
            return response(['message'=>"role Delete Successfully"], 200);
        }
        else{
                return response(['message'=>"Server Error"], 201);
        }
        return redirect()->route('users.roles.index')->with('success','Role deleted successfully');
    }

    public function getRolesData()
    {
        $me = Auth::user();
        $data = Role::withCount(['users'])->get();
        return DataTables::of($data, $me)           
            ->addColumn('name', function($row){   
                return ucfirst($row->name);
            })    
            ->addColumn('users_count', function($row){
                return '<span class="badge badge-primary">'.$row->users_count.'</span>';          
            })
            ->addColumn('permissions_count', function($row){
                if($row->name=="superuser")
                {
                    return '<span class="badge badge-primary">All</span>'; 
                }
                return '<span class="badge badge-primary">'.$row->permissions->count().'</span>';          
            })
            ->addColumn('action', function($row) use ($me){
                $action = "";
                if($me->hasRole($row->name))
                {
                    return  '<span class="badge badge-primary"> Your current role.</span>';      
                }
                if($me->can('users.roles.update') && $row->name!="superuser"){
                    $action .= '
                    <a class="btn btn-warning btn-xs" href="'.route('users.roles.edit', $row->id).'">
                    <i class="fas fa-fw fa-edit"></i></a>';
                }             
                if($me->can('users.roles.destroy') && $row->name!="superuser"){
                    $action .= '<meta name="csrf-token" content="{{ csrf_token() }}">
                    <button  id='.$row->id.' class="delete btn btn-outline-danger btn-xs delete-user">
                        <i class="fas fa-fw fa-trash"></i>
                    </button>';
                }
                return $action;
            
            })
        ->rawColumns(['users_count','permissions_count','action'])->make(true);
    }
}