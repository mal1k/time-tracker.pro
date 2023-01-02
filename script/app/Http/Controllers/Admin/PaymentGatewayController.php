<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Getway;
use App\Models\Term;
use App\Models\Termmeta;
use Illuminate\Http\Request;
use Illuminate\Support\str;

class PaymentGatewayController extends Controller
{
    // payment gateway index
    public function index()
    {
        abort_if(!Auth()->user()->can('getway.index'), 401);
        $gateways = Getway::latest()->paginate(10);
        return view('admin.paymentgateway.index', compact('gateways'));
    }

    public function show($id)
    {
    }

    // payment gateway create
    public function create()
    {
    }

    // Payment Gateway Store
    public function store(Request $request)
    {
    }

    // Payment Gateway Edit
    public function edit($id)
    {
        abort_if(!Auth()->user()->can('getway.edit'), 401);
        $gateway = Getway::findOrFail($id);
        return view('admin.paymentgateway.edit', compact('gateway'));
    }

    // Payment Gateway Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'            => 'required|unique:getways,name,' . $id,
            'logo'            => 'nullable|mimes:jpeg,png,jpg,svg,gif|max:1000',
            'rate'            => 'required',
            'status'          => 'required',
            'phone_status'    => 'required',
            'currency'        => 'required',
            'charge'          => 'required',
            'namespace'       => 'required',
        ]);

        $getway = Getway::findOrFail($id);
        // logo check
        if ($request->hasFile('logo')) {
            if (!empty($getway->logo)) {
                if (file_exists($getway->logo)) {
                    unlink($getway->logo);
                }
            }
            $logo      = $request->file('logo');
            $logo_name = hexdec(uniqid()) . '.' . $logo->getClientOriginalExtension();
            $logo_path = 'uploads/1/' . date('y/m/');
            $logo->move($logo_path, $logo_name);
            $getway->logo    = $logo_path . $logo_name;
        }

        // Automatic Payment Gateway update
        $getway->name    = $request->name;
        $getway->rate = $request->rate;
        $getway->data = json_encode($request->data);
        $getway->status = $request->status;
        $getway->currency = strtoupper($request->currency);
        $getway->charge = $request->charge;
        $getway->phone_status = $request->phone_status;
        $getway->namespace = $request->namespace;
        $getway->save();

        return response()->json('Payment Gateway Updated Successfully');
    }

    public function destroy($id)
    {
    }
}
