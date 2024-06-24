<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class CustomerController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:View customer');
         $this->middleware('permission:Create customer', ['only' => ['create','store']]);
         $this->middleware('permission:Edit customer', ['only' => ['edit','update']]);
         $this->middleware('permission:Activate/deactivate customer', ['only' => ['deactivate','reactivate']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Customer::orderBy('created_at', 'desc')->get();

        if($data->isEmpty())
        {
            $message = 'empty';
        } else {
            $message = 'success';
        }
        return response()->json([
            'message'    => $message,
            'data'      => $data
        ], Response::HTTP_OK);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'firstname'  => 'required|max:30',
            'lastname' => 'required|max:30',
        ];

        $this->validate($request, $rules);

        $form_data = $request->all();
        $data = Customer::create($form_data);

        return response()->json([
            'message'   => 'create success',
            'data'      => $data,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Customer::find($id);

        if(!$data)
        {
            $message = 'empty';
        } else {
            $message = 'success';
        }
        
        return response()->json([
            'message'    => $message,
            'data'      => $data
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $rules = [
            'firstname'  => 'required|max:30',
            'lastname' => 'required|max:30',
        ];

        $this->validate($request, $rules);

        $form_data = $request->all();
        $customer->fill($form_data);
        $customer->save();

        return response()->json([
            'message'   => 'update success',
            'data'      => $customer,
        ], Response::HTTP_OK);
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
    public function deactivate(Customer $customer)
    {

    }

    //reactivate user
    public function reactivate(Customer $customer)
    {

    }  
}
