<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Owner;
use App\Models\Group;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $project_open = Project::where('is_active', '1')->where('project_status', 1)->with(['sales'])->get();
        // $project_open_count = Project::where('is_active', '1')->where('project_status', 1)->count();
        // $owner_count = Owner::where('is_active', '1')->count();

        // if (Auth::user()->hasAnyRole('Sales')) {
        //     $project_count = Project::where('sales_id', Auth::user()->id)->where('is_active', '1')->count();
        //     $owner_count = Owner::where('created_by', Auth::user()->id)->where('is_active', '1')->count();
        // }

        // $data_dashboard = DB::raw

        $project_open_count = Project::where('is_active', '1')->where('project_status', 1)->where('sales_id', Auth::user()->id)->count();
        $project_confirm_count = Project::where('is_active', '1')->where('project_status', 2)->where('sales_id', Auth::user()->id)->count();
        $project_follow_up_count = Project::where('is_active', '1')->where('project_status', 3)->where('sales_id', Auth::user()->id)->count();
        $project_need_follow_up_count = Project::where('is_active', '1')->where('project_status', 4)->where('sales_id', Auth::user()->id)->count();
        $project_cut_count = Project::where('is_active', '1')->where('project_status', 5)->where('sales_id', Auth::user()->id)->count();
        $project_close_count = Project::where('is_active', '1')->where('project_status', 6)->where('sales_id', Auth::user()->id)->count();

        $data_dashboard = Group::
            where('is_active', '1')
            ->where('id', '!=', 1)
            ->get()->load(['Project', 'Project.Sales']);

        if (Auth::user()->hasAnyRole('Manager')){
            $data_dashboard = Group::
                where('is_active', '1')
                ->where('id', '=', Auth::user()->group_id)
                ->get()->load(['Project', 'Project.Sales']);
        }

        // Decode the JSON data
        $data = json_decode($data_dashboard, true);

        // Initialize an array to hold the updated data with grouped projects
        $updatedData = [];

        // Iterate through each agent
        foreach ($data as $agent => $info) {
            $groupedProjects = [];
            $groupedSales = [];

            // Check if 'projects' key exists
            if (isset($info['project'])) {
                foreach ($info['project'] as $project) {
                    $status = $project['project_status'];
                    $salesId = $project['sales']['id'];
                    $salesName = $project['sales']['name'];

                    // Group projects by status
                    if (!isset($groupedProjects[$status])) {
                        $groupedProjects[$status] = [];
                    }
                    $groupedProjects[$status][] = $project;

                    // Group sales by project status and count projects for each sales agent
                    if (!isset($groupedSales[$status])) {
                        $groupedSales[$status] = [];
                    }

                    // Initialize or update sales data
                    if (!isset($groupedSales[$status][$salesId])) {
                        $groupedSales[$status][$salesId] = [
                            'sales_id' => $salesId,
                            'name' => $salesName,
                            'count' => 0
                        ];
                    }
                    $groupedSales[$status][$salesId]['count']++;
                }
            }

            // Format the grouped sales data
            foreach ($groupedSales as $status => $sales) {
                $groupedSales[$status] = array_values($sales); // Convert associative array to indexed array
            }

            // Add the agent and its projects with grouped projects and sales as new fields
            $updatedData[] = [
                'agent' => $info,
                'projects' => $info['project'],
                'project_status' => $groupedProjects,
                'sales' => $groupedSales
            ];
        }

        $data_dashboard = $updatedData;

        $data = [
            'title' => 'Dashboard',
            'menu' => 'dashboard',
            'sub_menu' => 'dashboard',
            // 'project_open' => $project_open,
            'data_dashboard' => $data_dashboard,
            'project_open_count' => $project_open_count,
            'project_confirm_count' => $project_confirm_count,
            'project_follow_up_count' => $project_follow_up_count,
            'project_need_follow_up_count' => $project_need_follow_up_count,
            'project_cut_count' => $project_cut_count,
            'project_close_count' => $project_close_count,
            // 'owner_count' => $owner_count
        ];

        if (Auth::user()->hasAnyRole('Manager')){
            return view('dashboard.manager', $data);
        } else if (Auth::user()->hasAnyRole('Sales')){
            return view('dashboard.sales', $data);
        }

        return view('dashboard.principal', $data);
    }
}
