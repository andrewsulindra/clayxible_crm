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
use App\Mail\sendEmail;
use Illuminate\Support\Facades\Mail;

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
        // DB::enableQueryLog();

        $project = Project::where('is_active', '1')->orderBy('created_at', 'desc')->with(['sales'])->get();
        if (Auth::user()->hasAnyRole('Sales')) {
            $project = Project::
                where('sales_id', Auth::user()->id)
                ->where('group_id', Auth::user()->group_id)
                ->where('is_active', '1')
                ->orderBy('created_at', 'desc')
                ->with(['sales'])
                ->get();
        } else if (Auth::user()->hasAnyRole('Manager')){
            $project = Project::
                where('group_id', Auth::user()->group_id)
                ->where('is_active', '1')
                ->orderBy('created_at', 'desc')
                ->with(['sales'])
                ->get();
        }

        // dd(DB::getQueryLog());

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
                $form_data_owner['group_id'] = Auth()->user()->group_id;
                $data = Owner::create($form_data_owner);

                $form_data['owner_id'] = $data->id;
            }

            $form_data['sales_id'] = Auth()->user()->id;
            $form_data['group_id'] = Auth()->user()->group_id;
            $data = Project::create($form_data);

            ProjectLog::createLog(
                $data->id,
                config('constants.PROJECT_LOG_TYPE_CREATE'),
                NULL,
                'Project Created'
            );

            //send email start
            $data = [
                'view' => 'emails.new_project',
                'subject' => '[Clayxible] New Project Added',
                'project_name' => $form_data['name']
            ];
            $recipients = config('constants.PRINCIPAL_EMAILS');
            $bcc_recipients = config('constants.DEV_EMAILS');
            Mail::to($recipients)->bcc($bcc_recipients)->send(new sendEmail($data));
            //send email end

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

        if ($project == NULL) {
            abort(404);
        }

        Project::checkProjectBelongsToUser($project->sales_id, $project->group_id);
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
        Project::checkProjectBelongsToUser($project->sales_id, $project->group_id);
        $project_category = ProjectCategory::where('is_active', '1')->get();

        $user = User::where('is_active', '1')->get();
        if (Auth::user()->hasAnyRole('Manager')) {
            $user = User::where('is_active', '1')->where('group_id', $project->group_id)->get();
        }

        $data = [
            'title' => 'Edit Project',
            'menu' => 'master',
            'sub_menu' => 'project',
            'models' => $project,
            'owners' => Owner::getOwnerList(),
            'cities' => $cities,
            'users' => $user,
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
        Project::checkProjectBelongsToUser($project->sales_id, $project->group_id);

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
        $record_status_open = ProjectLog::where('project_id', $project->id)->where('type', config('constants.PROJECT_LOG_TYPE_CREATE'))->first();
        if ($record_status_open && $status == config('constants.PROJECT_STATUS_OPEN')) {
            return redirect()->back()->with('error', 'Project already open.');
        }
        $record_status_cut = ProjectLog::where('project_id', $project->id)->where('type', config('constants.PROJECT_LOG_TYPE_CHANGE_STATUS'))->where('new', config('constants.PROJECT_STATUS_CUT'))->first();
        if ($record_status_cut) {
            return redirect()->back()->with('error', 'Project already cut.');
        }
        $record_status_closed = ProjectLog::where('project_id', $project->id)->where('type', config('constants.PROJECT_LOG_TYPE_CHANGE_STATUS'))->where('new', config('constants.PROJECT_STATUS_CLOSED'))->first();
        if ($record_status_closed) {
            return redirect()->back()->with('error', 'Project already closed.');
        }
        $record_status_confirm = ProjectLog::where('project_id', $project->id)->where('type', config('constants.PROJECT_LOG_TYPE_CHANGE_STATUS'))->where('new', config('constants.PROJECT_STATUS_CONFIRM'))->first();

        ProjectLog::createLog(
            $project->id,
            config('constants.PROJECT_LOG_TYPE_CHANGE_STATUS'),
            $project->project_status,
            $status
        );

        //send email start
        if ($status == config('constants.PROJECT_STATUS_CONFIRM') && $record_status_confirm == null) {
            $data = [
                'view' => 'emails.confirm_project',
                'subject' => '[Clayxible] Project Confirmed - '.$project->name,
                'project_name' => $project->name
            ];
            $recipients = $project->sales->email;
            $email_managers = User::where('group_id', $project->group_id)->where('is_active', 1)->whereHas('roles', function($query) {
                $query->where('name', 'Manager');
            })->pluck('email')->toArray();
            $cc_recipients = $email_managers;
            $bcc_recipients = array_merge(config('constants.PRINCIPAL_EMAILS'), config('constants.DEV_EMAILS'));
            Mail::to($recipients)->cc($cc_recipients)->bcc($bcc_recipients)->send(new sendEmail($data));
        } else if ($status == config('constants.PROJECT_STATUS_FOLLOW_UP') || $status == config('constants.PROJECT_STATUS_NEED_FOLLOW_UP')) {
            $last_detail_submitted = ProjectLog::where('type', config('constants.PROJECT_LOG_TYPE_ADD_PROGRESS'))
                ->where('project_id', $project->id)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($last_detail_submitted != null) {
                $last_detail_submitted = $last_detail_submitted->created_at;
            } else {
                $last_detail_submitted = NULL;
            }

            $data = [
                'view' => 'emails.follow_up_project',
                'subject' => '[Clayxible] Project '.($status == config('constants.PROJECT_STATUS_NEED_FOLLOW_UP') ? 'Need ' : '').'Follow Up - '.$project->name,
                'project_name' => $project->name,
                'last_date_detail_submitted' => $last_detail_submitted
            ];

            $recipients = $project->sales->email;
            $email_managers = User::where('group_id', $project->group_id)->where('is_active', 1)->whereHas('roles', function($query) {
                $query->where('name', 'Manager');
            })->pluck('email')->toArray();
            $cc_recipients = $email_managers;
            $bcc_recipients = array_merge(config('constants.PRINCIPAL_EMAILS'), config('constants.DEV_EMAILS'));
            Mail::to($recipients)->cc($cc_recipients)->bcc($bcc_recipients)->send(new sendEmail($data));
        } else if ($status == config('constants.PROJECT_STATUS_CUT')) {
            $data = [
                'view' => 'emails.cut_project',
                'subject' => '[Clayxible] Project Has Been Cut -' . $project->name,
                'project_name' => $project->name
            ];
            $recipients = $project->sales->email;
            $email_managers = User::where('group_id', $project->group_id)->where('is_active', 1)->whereHas('roles', function($query) {
                $query->where('name', 'Manager');
            })->pluck('email')->toArray();
            $cc_recipients = $email_managers;
            $bcc_recipients = array_merge(config('constants.PRINCIPAL_EMAILS'), config('constants.DEV_EMAILS'));
            Mail::to($recipients)->cc($cc_recipients)->bcc($bcc_recipients)->send(new sendEmail($data));
        } else if ($status == config('constants.PROJECT_STATUS_CLOSED')) {
            $data = [
                'view' => 'emails.closed_project',
                'subject' => '[Clayxible] Project Closed -' . $project->name,
                'project_name' => $project->name
            ];
            $recipients = $project->sales->email;
            $email_managers = User::where('group_id', $project->group_id)->where('is_active', 1)->whereHas('roles', function($query) {
                $query->where('name', 'Manager');
            })->pluck('email')->toArray();
            $cc_recipients = $email_managers;
            $bcc_recipients = array_merge(config('constants.PRINCIPAL_EMAILS'), config('constants.DEV_EMAILS'));
            Mail::to($recipients)->cc($cc_recipients)->bcc($bcc_recipients)->send(new sendEmail($data));
        }

        //send email end

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
