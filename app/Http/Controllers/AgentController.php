<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => 'Manage Agent',
            'menu' => 'master',
            'sub_menu' => 'agent',
            'inc' => '1',
            'models' => Agent::orderBy('is_active', 'desc')
            ->get()
        ];
        return view('agent.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Create Agent',
            'menu' => 'master',
            'sub_menu' => 'agent'
        ];
        return view('agent.form', $data);  
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
            'agent_name'  => 'required|max:30',
            //'lastname' => 'required|max:30',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors())->withInput();
        }else{
            $form_data = $request->all();
            $data = Agent::create($form_data);
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
        $data = [
            'title' => 'Edit Agent',
            'menu' => 'master',
            'sub_menu' => 'agent',
            'models' => Agent::findOrFail($id)
        ];
        return view('agent.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agent $agent)
    {
        try {
            $form_data = $request->all();
            $agent->fill($form_data);
            $agent->save();
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
    public function deactivate(Agent $agent)
    {
        $agent->deactivate();
        return redirect()->back()->with('success', 'Deactivate Success');
    }

    //reactivate user
    public function reactivate(Agent $agent)
    {
        $agent->activate();
        return redirect()->back()->with('success', 'Reactivate Success');
    } 


}
