<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\OwnerCategory;
use Illuminate\Support\Facades\Validator;
use DB;

class OwnerController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:View owner');
         $this->middleware('permission:Create owner', ['only' => ['create','store']]);
         $this->middleware('permission:Edit owner', ['only' => ['edit','update']]);
         $this->middleware('permission:Activate/deactivate owner', ['only' => ['deactivate','reactivate']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => 'Manage Owner',
            'menu' => 'master',
            'sub_menu' => 'owner',
            'inc' => '1',
            'models' => Owner::getOwnerList()
        ];
        return view('owner.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = DB::table('cities')->select(DB::raw('cities.id as city_id'), DB::raw('cities.name as city_name'), DB::raw('states.name as state_name'), DB::raw('countries.name as country_name'))
        ->leftJoin('states', 'states.id', '=', 'cities.state_id')
        ->leftJoin('countries', 'countries.id', '=', 'cities.country_id')
        // ->where('cities.name','LIKE', 'surabaya')
        // ->orWhere('cities.name','LIKE', 'malang')
        // ->orWhere('cities.name','LIKE', 'jakarta')
        // ->orWhere('cities.name','LIKE', 'denpasar')
        // ->orWhere('cities.name','LIKE', 'yogyakarta')
        // ->orWhere('cities.name','LIKE', 'semarang')
        // ->orWhere('cities.name','LIKE', 'bandung')
        ->where('cities.country_code','ID')
        ->get();

        $owner_category = OwnerCategory::where('is_active', '1')->get();

        $data = [
            'title' => 'Create Owner',
            'menu' => 'master',
            'sub_menu' => 'owner',
            'cities' => $cities,
            'owner_category' => $owner_category
        ];
        return view('owner.form', $data);
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
            'name'  => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors())->withInput();
        }else{
            $form_data = $request->all();
            $form_data['group_id'] = Auth::user()->group_id;
            $data = Owner::create($form_data);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cities = DB::table('cities')->select(DB::raw('cities.id as city_id'), DB::raw('cities.name as city_name'), DB::raw('states.name as state_name'), DB::raw('countries.name as country_name'))
        ->leftJoin('states', 'states.id', '=', 'cities.state_id')
        ->leftJoin('countries', 'countries.id', '=', 'cities.country_id')
        ->where('cities.country_code','ID')
        ->get();

        $owner = Owner::findOrFail($id);
        Owner::checkOwnerBelongsToUser($owner->created_by, $owner->group_id);

        $owner_category = OwnerCategory::where('is_active', '1')->get();

        $data = [
            'title' => 'Edit Owner',
            'menu' => 'master',
            'sub_menu' => 'owner',
            'models' => $owner,
            'cities' => $cities,
            'owner_category' => $owner_category
        ];
        return view('owner.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Owner $owner)
    {
        try {
            $form_data = $request->all();
            $owner->fill($form_data);
            $owner->save();
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
    public function deactivate(Owner $owner)
    {
        $owner->deactivate();
        return redirect()->back()->with('success', 'Deactivate Success');
    }

    //reactivate user
    public function reactivate(Owner $owner)
    {
        $owner->activate();
        return redirect()->back()->with('success', 'Reactivate Success');
    }
}
