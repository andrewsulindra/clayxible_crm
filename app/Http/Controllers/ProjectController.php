<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Owner;
use App\Models\ProjectLog;
use App\Models\User;
use App\Models\ProjectCategory;
use App\Models\OwnerCategory;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

class ProjectController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:View project');
        //  $this->middleware('permission:View owner', ['only' => ['create','store']]);
         $this->middleware('permission:Create project', ['only' => ['create','store']]);
         $this->middleware('permission:Edit project', ['only' => ['edit','update']]);
         $this->middleware('permission:Activate/deactivate project', ['only' => ['deactivate','reactivate']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Project::where('is_active', '1')->orderBy('created_at', 'desc')->with(['sales'])->get();
        if (Auth::user()->hasAnyRole('Sales')) {
            $project = Project::where('sales_id', Auth::user()->id)->where('is_active', '1')->orderBy('created_at', 'desc')->with(['sales'])->get();
        }

        $data = [
            'title' => 'Manage Project',
            'menu' => 'master',
            'sub_menu' => 'project',
            'inc' => '1',
            'models' => $project
        ];
        return view('project.index', $data);
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
        ->where('cities.country_code','ID')
        ->get();

        $project_category = ProjectCategory::where('is_active', '1')->get();
        $owner_category = OwnerCategory::where('is_active', '1')->get();

        $data = [
            'title' => 'Create Project',
            'menu' => 'master',
            'sub_menu' => 'project',
            'owners' => Owner::getOwnerList(),
            'cities' => $cities,
            'project_category' => $project_category,
            'owner_category' => $owner_category
        ];
        return view('project.form', $data);
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

            if ($form_data['owner_id'] == NULL) {

                $validator = Validator::make($request->all(), [
                    'owner_name'  => 'required',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors())->withInput();
                }

                $form_data_owner['name'] = $form_data['owner_name'];
                $form_data_owner['address1'] = $form_data['owner_address1'];
                $form_data_owner['city'] = $form_data['owner_city'];
                $form_data_owner['phone'] = $form_data['owner_phone'];
                $form_data_owner['email'] = $form_data['owner_email'];
                $form_data_owner['owner_category_id'] = $form_data['owner_category_id'];
                $data = Owner::create($form_data_owner);

                $form_data['owner_id'] = $data->id;
            }

            $form_data['sales_id'] = Auth()->user()->id;
            $data = Project::create($form_data);

            ProjectLog::createLog(
                $data->id,
                config('constants.PROJECT_LOG_TYPE_CREATE'),
                NULL,
                'Project Created'
            );

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
        $project = Project::where('id', $id)->where('is_active', '1')->first();
        Project::checkProjectBelongsToUser($project->sales_id);
        $owner = Owner::where('id', $project->owner_id)->where('is_active', '1')->first();
        $cities = DB::table('cities')
            ->select(DB::raw('cities.id as city_id'), DB::raw('cities.name as city_name'), DB::raw('states.name as state_name'), DB::raw('countries.name as country_name'))
            ->leftJoin('states', 'states.id', '=', 'cities.state_id')
            ->leftJoin('countries', 'countries.id', '=', 'cities.country_id')
            ->where('cities.country_code','ID')
            ->where('cities.id', $project->city)
            ->first();
        $cities_owner = DB::table('cities')
            ->select(DB::raw('cities.id as city_id'), DB::raw('cities.name as city_name'), DB::raw('states.name as state_name'), DB::raw('countries.name as country_name'))
            ->leftJoin('states', 'states.id', '=', 'cities.state_id')
            ->leftJoin('countries', 'countries.id', '=', 'cities.country_id')
            ->where('cities.country_code','ID')
            ->where('cities.id', $owner->city)
            ->first();

        // if ($project->project_status == 1) {
        //     $project_status = 'Open';
        // } else if ($project->project_status == 2) {
        //     $project_status = 'Confirm';
        // } else if ($project->project_status == 3) {
        //     $project_status = 'Need Follow Up';
        // } else if ($project->project_status == 4) {
        //     $project_status = 'Close Paid';
        // } else if ($project->project_status == 5) {
        //     $project_status = 'Close Unpaid';
        // }
        $project_status = projectStatusName($project->project_status);

        $project_log = ProjectLog::where('project_id', $id)->whereNotIn('type', [config('constants.PROJECT_LOG_TYPE_UPDATE')])->orderBy('created_at', 'DESC')->get();

        $project_category = ProjectCategory::where('id', $project->project_category_id)->first();
        $owner_category = OwnerCategory::where('id', $owner->owner_category_id)->first();

        $data = [
            'title' => '',
            'menu' => 'master',
            'sub_menu' => 'project',
            'projects' => $project,
            'owners' => $owner,
            'cities' => $cities,
            'cities_owner' => $cities_owner,
            'project_status' => $project_status,
            'project_log' => $project_log,
            'project_category' => $project_category,
            'owner_category' => $owner_category
        ];
        return view('project.detail', $data);
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

        $project = Project::findOrFail($id);
        Project::checkProjectBelongsToUser($project->sales_id);
        $project_category = ProjectCategory::where('is_active', '1')->get();

        $data = [
            'title' => 'Edit Project',
            'menu' => 'master',
            'sub_menu' => 'project',
            'models' => $project,
            'owners' => Owner::getOwnerList(),
            'cities' => $cities,
            'users' => User::where('is_active', '1')->get(),
            'project_category' => $project_category
        ];
        return view('project.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        Project::checkProjectBelongsToUser($project->sales_id);

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'owner_id'  => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors())->withInput();
        }

        try {
            $form_data = $request->all();

            $old_data = $project;
            $new_data = $form_data;

            // Get the differences
            $differences = $this->getDifferences($old_data, $new_data);
            $old_diff = $differences['old_diff'];
            $new_diff = $differences['new_diff'];

            if (!empty($old_diff) || !empty($new_diff)) {
                $project_log = ProjectLog::createLog(
                    $project->id,
                    config('constants.PROJECT_LOG_TYPE_UPDATE'),
                    json_encode($old_diff),
                    json_encode($new_diff)
                );
            }
            if (isset($form_data['sales_id'])) {
                if ($project->sales_id != $form_data['sales_id']) {
                    $project_log = ProjectLog::createLog(
                        $project->id,
                        config('constants.PROJECT_LOG_TYPE_CHANGE_SALES'),
                        $project->sales_id,
                        $form_data['sales_id']
                    );
                }
            }

            $project->fill($form_data);
            $project->save();
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
    public function deactivate(Project $project)
    {
        $project->deactivate();
        return redirect()->back()->with('success', 'Deactivate Success');
    }

    //reactivate user
    public function reactivate(Project $project)
    {
        $project->activate();
        return redirect()->back()->with('success', 'Reactivate Success');
    }

    public function change_status(Project $project, $status)
    {
        ProjectLog::createLog(
            $project->id,
            config('constants.PROJECT_LOG_TYPE_CHANGE_STATUS'),
            $project->project_status,
            $status
        );

        $project->change_status($status);
        return redirect()->back()->with('success', 'Status Changed');
    }



    public function submit_progress(Request $request, Project $project)
    {
        $form_data = $request->all();

        ProjectLog::createLog(
            $project->id,
            config('constants.PROJECT_LOG_TYPE_ADD_PROGRESS'),
            NULL,
            $form_data['progress']
        );

        // $project->change_status($status);
        return redirect()->back()->with('success', 'Progress Submitted');
    }

    function getDifferences($old, $new) {
        $old_diff = [];
        $new_diff = [];

        // Keys to exclude from comparison
        $exclude_keys = ['_token', '_method', 'sales_id'];

        foreach ($new as $key => $value) {
            // Skip excluded keys
            if (in_array($key, $exclude_keys)) {
                continue;
            }

            // Convert both $old->$key and $value to strings for comparison
            $old_value_str = strval(isset($old->$key) ? $old->$key : null);
            $new_value_str = strval($value);

            if ($old_value_str !== $new_value_str) {
                $old_diff[$key] = $old_value_str;
                $new_diff[$key] = $new_value_str;
            }
        }

        return ['old_diff' => $old_diff, 'new_diff' => $new_diff];
    }


}
