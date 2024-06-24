<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

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
        $data = [
            'title' => 'Manage Customer',
            'menu' => 'master',
            'sub_menu' => 'customer',
            'inc' => '1',
            'models' => Customer::orderBy('is_active', 'desc')
            ->get()
        ];
        return view('customer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Create Customer',
            'menu' => 'master',
            'sub_menu' => 'customer'
        ];
        return view('customer.form', $data);  
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
            'firstname'  => 'required|max:30',
            'lastname' => 'required|max:30',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors())->withInput();
        }else{
            $form_data = $request->all();
            $data = Customer::create($form_data);
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
            'title' => 'Edit Customer',
            'menu' => 'master',
            'sub_menu' => 'customer',
            'models' => Customer::findOrFail($id)
        ];
        return view('customer.form', $data);
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
        try {
            $form_data = $request->all();
            $customer->fill($form_data);
            $customer->save();
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
    public function deactivate(Customer $customer)
    {
        $customer->deactivate();
        return redirect()->back()->with('success', 'Deactivate Success');
    }

    //reactivate user
    public function reactivate(Customer $customer)
    {
        $customer->activate();
        return redirect()->back()->with('success', 'Reactivate Success');
    }  
}
