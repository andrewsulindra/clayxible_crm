<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Owner;

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
        $project_count = Project::where('is_active', '1')->count();
        $owner_count = Owner::where('is_active', '1')->count();

        $data = [
            'title' => 'Dashboard',
            'menu' => 'dashboard',
            'sub_menu' => 'dashboard',
            'project_count' => $project_count,
            'owner_count' => $owner_count
        ];
        return view('dashboard.index', $data);
    }
}
