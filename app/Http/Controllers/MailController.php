<?php

namespace App\Http\Controllers;

use App\Mail\weeksEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Project;
use DB;
use Illuminate\Http\Response;
use App\Traits\Discord;

class MailController extends Controller
{
    use Discord;

    public function sendEmail()
    {
        $data = [
            'title' => 'Mail from Laravel 6',
            'body' => 'This is a test email.'
        ];

        $recipients = ['andrew.sulindra@gmail.com', 'blizzard_endru@yahoo.com'];

        Mail::to($recipients)->send(new weeksEmail($data));

        return 'Email sent!';
    }

    public function check_project_activity(Request $request)
    {
        $this->SendMessageToDiscord("cron", "project_activity func started", '', "cron_log");
        $all_project = DB::table('project as p')
        ->select(
            'p.id',
            'p.name',
            'p.sales_id',
            'p.group_id',
            'p.project_status',
            DB::raw('MAX(pl.created_at) as last_submit'),
            DB::raw('MAX(pl2.created_at) as last_confirm')
        )
        ->leftJoin('project_log as pl', function($join) {
            $join->on('p.id', '=', 'pl.project_id')
                 ->where('pl.type', '=', 5);
        })
        ->leftJoin('project_log as pl2', function($join) {
            $join->on('p.id', '=', 'pl2.project_id')
                 ->where('pl2.type', '=', 4)
                 ->where('pl2.new', '=', 2);
        })
        ->where('p.is_active', '=', 1)
        ->where('p.project_status', '=', 2)
        ->groupBy('p.id')
        ->get();

        $one_month_notice = DB::table('project as p')
        ->select(
            'p.id',
            'p.name',
            'p.sales_id',
            'p.group_id',
            'p.project_status',
            DB::raw('MAX(pl.created_at) as last_submit'),
            DB::raw('MAX(pl2.created_at) as last_confirm')
        )
        ->leftJoin('project_log as pl', function($join) {
            $join->on('p.id', '=', 'pl.project_id')
                 ->where('pl.type', '=', 5);
        })
        ->leftJoin('project_log as pl2', function($join) {
            $join->on('p.id', '=', 'pl2.project_id')
                 ->where('pl2.type', '=', 4)
                 ->where('pl2.new', '=', 2);
        })
        ->where('p.is_active', '=', 1)
        ->where('p.project_status', '=', 2)
        ->groupBy('p.id')
        ->having(DB::raw('MAX(pl.created_at)'), '<=', Carbon::now()->subMonth())
        ->orHaving(DB::raw('MAX(pl2.created_at)'), '<=', Carbon::now()->subMonth())
        ->get();

        $two_month_notice = DB::table('project as p')
        ->select(
            'p.id',
            'p.name',
            'p.sales_id',
            'p.group_id',
            'p.project_status',
            DB::raw('MAX(pl.created_at) as last_submit'),
            DB::raw('MAX(pl2.created_at) as last_confirm')
        )
        ->leftJoin('project_log as pl', function($join) {
            $join->on('p.id', '=', 'pl.project_id')
                 ->where('pl.type', '=', 5);
        })
        ->leftJoin('project_log as pl2', function($join) {
            $join->on('p.id', '=', 'pl2.project_id')
                 ->where('pl2.type', '=', 4)
                 ->where('pl2.new', '=', 2);
        })
        ->where('p.is_active', '=', 1)
        ->where('p.project_status', '=', 3)
        ->groupBy('p.id')
        ->having(DB::raw('MAX(pl.created_at)'), '<=', Carbon::now()->subMonth(2))
        ->orHaving(DB::raw('MAX(pl2.created_at)'), '<=', Carbon::now()->subMonth(2))
        ->get();


        $debug = $request->input('debug') ? true : false;
        if (!$debug) {
            if ($one_month_notice->count() > 0 )
            {
                $this->SendMessageToDiscord("cron", "project_activity one month notice", $one_month_notice, "cron_log");
            }
            if ($two_month_notice->count() > 0) {
                $this->SendMessageToDiscord("cron", "project_activity two month notice", $two_month_notice, "cron_log");
            }

            foreach ($one_month_notice as $key => $value) {
                $project = Project::find($value->id);
                $projectController = new ProjectController();
                $result = $projectController->change_status($project, config('constants.PROJECT_STATUS_FOLLOW_UP'));
            }

            foreach ($two_month_notice as $key => $value) {
                $project = Project::find($value->id);
                $projectController = new ProjectController();
                $result = $projectController->change_status($project, config('constants.PROJECT_STATUS_NEED_FOLLOW_UP'));
            }
        }

        return response()->json([
            'one_month_notice' => $one_month_notice,
            'two_month_notice' => $two_month_notice,
            'all_project'   => $all_project,
            'debug' => $debug
        ], Response::HTTP_OK);

    }

}
