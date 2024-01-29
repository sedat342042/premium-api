<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;



class UsersController extends Controller
{
    /**
     * Display all users
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getUserData();
        }
        return view('users.index');
    }
    /**
     * Show form for creating user
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.modal', ['user' => new User(), 'roles' => Role::get()]);
    }

    /**
     * Store a newly created user
     * 
     * @param User $user     
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email',
        ]);

        if ($request->has('roles')) {
            $user->create($request->all())->roles()->sync($request->input('roles'));
        } else {
            $user->create($request->all());
        }

        if ($user) {
            $user = User::latest()->first();
            $user->sendEmailVerificationNotification();
            return response(['success' => "New Account Created Successfully."], 200);
        } else {
            return response(['errors' => "Server Error"], 201);
        }
    }

    /**
     * Show user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id=null)
    {
        if(isset($id))
        {           
           return view('users.profile', ['user' => User::with(['roles'])->where('id', $id)->first()]);
        }
        return view('users.profile', ['user' => Auth::user()]);
        
    }

    /**
     * Edit user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.modal', [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get()
        ]);
    }

    /**
     * Update user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email,'.$user->id,
        ]);

        $data = $request->except('new_password');
        // Check if the new_password field is present and not empty
        if ($request->has('new_password') && $request->input('new_password') != null || $request->input('new_password') != '') {
            $data['password'] = $request->new_password;
        }
        if ($request->has('roles')) {
            $user->update($data); 
            $user->roles()->sync($request->input('roles'));
        } else {
            $user->update($request->all());
        }
        if ($user) {
            return response(['success' => "Account Updated Successfully."], 200);
        } else {
            return response(['errors' => "Server Error"], 201);
        }

        return view('users.index');
    }

    /**
     * Delete user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->delete())
        {
            return response(['message'=>"User Delete Successfully"], 200);
        }
        else{
                return response(['message'=>"Server Error"], 201);
        }
        return redirect()->route('users.index')->with('success','User deleted successfully');
    }

    public function getUserData()
    {
        $me = Auth::user();
        $data = User::with('roles')->where('id', "!=", $me->id)->where('email', "!=", "superuser@gmail.com")->get();
        return DataTables::of($data, $me)
            ->addColumn('guard', function ($row) {
                if(count($row->roles))
                {
                    return $row->roles[0]->guard_name;
                }
                return "";
            })
            ->addColumn('contact', function ($row) {
                $status="";
                if($row->email_verified_at!="")
                {
                    $status.= '<i class="fas fa-check-circle text-success" data-toggle="tooltip" data-placement="top" title="Verified"> </i>';
                }else{
                    $status.= '<i class="fa fa-exclamation-circle text-danger" data-toggle="tooltip" data-placement="top" title="Not Verified. Email sent."> </i>';
                }

                $contact = '<a href="mailto:'.$row->email.'">'.$row->email.'</a> '.$status;
                if(isset($row->phone) && $row->phone!="")
                {
                    $contact.='<br/><a href="mailto:'.$row->phone.'">'.$row->phone.'</a>';
                }
                return $contact;
            })
            ->addColumn('date', function ($row) {
                return  Carbon::parse($row->created_at)->format('d M, Y h:i:s A');
            })
            ->addColumn('name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })->addColumn('rolesData', function ($row) {
                $role = "";
                if ($row->roles != null) {
                    foreach ($row->roles as $next) {
                        $role .= '<span class="badge badge-primary"> ' . ucfirst($next->name) . ' </span> ';
                    }
                }
                return $role;
            })->addColumn('status', function ($row) {
                $status="";                                
                if ($row->status == 1) {
                    $status.= '<span class="badge badge-success"> Active </span>';
                } elseif ($row->status == 0) {
                    $status.= '<span class="badge badge-danger" style="background-color:#a9a9a9;"> Offline </span>';
                }
                return $status;
            })->addColumn('action', function ($row) use ($me) {
                $action = "";
                if($me->can('users.show')){
                    $action .= '
                    <a class="btn btn-success btn-xs" id="show-user"  href="' . route('users.show', $row->id) . '">
                    <i class="fas fa-fw fa-eye"></i></a>'; 
                }
                if($me->can('users.update')){
                    $action .='
                        <button class="btn btn-warning btn-xs edit"  id="btnEdit"
                        data-id="' . $row->id . '">
                        <i class="fas fa-fw fa-edit"></i></button>';
                }
                if($me->can('users.destroy')){
                    $action .='                  
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <button  id=' . $row->id . ' class="delete btn btn-outline-danger btn-xs delete-user">
                            <i class="fas fa-fw fa-trash"></i>
                        </button>
                    ';
                }
                return $action;
            })
            ->rawColumns(['contact','guard','rolesData', 'date', 'status', 'action'])->make(true);
    }
}
