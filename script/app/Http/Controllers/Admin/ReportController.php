<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Time;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!Auth()->user()->can('report'), 401);
        $total_earning = Time::sum('price');
        return view('admin.report.index', compact('total_earning'));
    }

    public function reportData(Request $request){
        abort_if(!Auth()->user()->can('report'), 401);
        $start = $request->start ? Carbon::parse($request->start) : "";
        $end = $request->end ? Carbon::parse($request->end) : "";

        // if start and end date range found then filter else get all   
        $data['total_earning'] = $start && $end ? Orders::whereBetween('created_at', [$start, $end])->sum('amount') : Orders::sum('amount');
        $data['monthly_earning'] = Orders::whereMonth('created_at', Carbon::now()->month)->sum('amount');
        $data['yearly_earning'] = Orders::whereYear('created_at', Carbon::now()->year)->sum('amount');
        $data['weekly_earning'] = Orders::whereBetween('created_at', [
            Carbon::parse('last monday')->startOfDay(),
            Carbon::parse('next friday')->endOfDay(),
        ])->sum('amount');
        $data['total_sales'] = $start && $end ? Orders::whereBetween('created_at', [$start, $end])->count() : Orders::count();

        $data['total_active'] = $start && $end ? Orders::where('status', 1)->whereBetween('created_at', [$start, $end])->count() : Orders::where('status', 1)->count(); // Status 1 for active

        $data['total_cancelled'] = $start && $end ? Orders::where('status', 0)->whereBetween('created_at', [$start, $end])->count() : Orders::where('status', 0)->count();  // Status 0 for rejected

        $data['total_expired'] = $start && $end ? Orders::where('status', 0)->whereBetween('created_at', [$start, $end])->count() : Orders::where('status', 2)->count(); // Status 2 for expired

        $data['total_users'] = User::where('role_id', 2)->count();
        $tax = Orders::selectRaw('sum((amount/100)*tax) as total')->first();
        $data['tax'] = $tax->total;
        $data['orders'] = $start && $end ? Orders::with('plan','user')->whereBetween('created_at', [$start, $end])->latest()->paginate(10) : Orders::with('plan','user')->latest()->paginate(10);

        return $data;
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.report.create');
    }

    
}
