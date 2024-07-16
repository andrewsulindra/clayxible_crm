<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use DB;
use Auth;
use App\Models\Group;

class UsersController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:View user', ['except' => ['showSetpassword','setpassword']]);
         $this->middleware('permission:Create user', ['only' => ['create','store']]);
         $this->middleware('permission:Edit user', ['only' => ['edit','update']]);
         $this->middleware('permission:Activate/deactivate user', ['only' => ['deactivate','reactivate']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => 'Manage User',
            'menu' => 'users',
            'sub_menu' => 'user',
            'inc' => '1',
            'models' => User::orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get()
        ];
        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        $data = [
            'title' => 'Create User',
            'menu' => 'users',
            'sub_menu' => 'user',
            'roles' => Role::get(),
            'groups' => Group::get(),
        ];
        return view('users.form', $data);
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
            'name'  => 'required|max:30',
            'email' => 'required|max:100|email|unique:users',
            //'password' => 'required|string|min:8|confirmed',
            'roles' => 'required',
            'group_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors())->withInput();
        }else{
            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'image' => generateUserImage($request->name),
                'group_id' => $request->group_id
            ]);
            $data->assignRole($request->input('roles'));
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::get();
        $groups = Group::get();
        //$userRole = $user->roles->pluck('name','name')->all();
        $userRole = DB::table("model_has_roles")->where("model_has_roles.model_id",$id)
                    ->pluck('model_has_roles.role_id','model_has_roles.role_id')
                    ->all();

        $data = [
            'title' => 'Edit User',
            'menu' => 'users',
            'sub_menu' => 'user',
            'models' => $user,
            'roles' => $roles,
            'groups' => $groups,
            'userRole' => $userRole
        ];
        return view('users.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $users)
    {
        try {
            $form_data = $request->all();
            $form_data['image'] = generateUserImage($form_data['name']);
            $users->fill($form_data);
            $users->save();

            DB::table('model_has_roles')->where('model_id',$users->id)->delete();
            $users->assignRole($request->input('roles'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
            return redirect()->back()->with('success', 'Successfully save data.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //deactivate user
    public function deactivate(User $users)
    {
        $users->deactivate();
        return redirect()->back()->with('success', 'Deactivate Success');
    }

    //reactivate user
    public function reactivate(User $users)
    {
        $users->activate();
        return redirect()->back()->with('success', 'Reactivate Success');
    }

    public function showSetpassword(User $users)
    {
        $data = [
            'id' => $users->id
        ];
        return view('auth.setpassword', $data);
    }

    public function setpassword(Request $request, User $users)
    {
        $validator = Validator::make($request->all(), [
    		'password' => ['required',Rule::notIn(['password', 'admin']),],
    		'password_confirmation' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            if (Auth::user()->email == $users->email) {
                $users->password = Hash::make($request->password);
                $users->save();
                return redirect('/')->with('success', 'Change Password Success');
            }else{
                return redirect()->back()->withErrors('ERROR')->withInput();
            }
        }
    }

    public function resetpassword(User $users){
        $users->password = Hash::make("password");
        $users->save();
        return redirect()->back()->with('success', 'Password Reset Success. Default Password: password');
    }

}
