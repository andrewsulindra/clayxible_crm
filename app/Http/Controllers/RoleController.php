<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\User;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         //$this->middleware('permission:role-list');
         //$this->middleware('permission:role-create', ['only' => ['create','store']]);
         //$this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         //$this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $data = [
            'title' => 'Manage Role',
            'menu' => 'users',
            'sub_menu' => 'role',
            'inc' => '1',
            'models' => Role::get()
        ];
        return view('roles.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $module = Permission::select('module_name')->orderBy('module_name', 'asc')->distinct()->get(); 
        $data = [
            'title' => 'Create Role',
            'menu' => 'users',
            'sub_menu' => 'role',
            'permission' => Permission::get(),
            'module' => $module,
        ];
        return view('roles.form', $data);  
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors())->withInput();
        }else{
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permission'));
            return redirect()->back()->with('success', 'Successfully save data.');
        }

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$role = Role::find($id);
        //$rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        //    ->where("role_has_permissions.role_id",$id)
        //    ->get();


        //return view('roles.show',compact('role','rolePermissions'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
                    ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                    ->all();
        $module = Permission::select('module_name')->orderBy('module_name', 'asc')->distinct()->get();            

        $data = [
            'title' => 'Edit Role',
            'menu' => 'users',
            'sub_menu' => 'role',
            'role' => $role,
            'permission' => $permission,
            'rolePermissions' => $rolePermissions,
            'module' => $module,

        ];
        return view('roles.form', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);


        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();


        $role->syncPermissions($request->input('permission'));


        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = DB::table("roles")->where('id',$id)->first();
        $users = User::role($role->name)->get();
        if ($users->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'Failed to delete role! This role is being used by user.');
        }
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')->with('success', 'Successfully delete role.');
    }
}