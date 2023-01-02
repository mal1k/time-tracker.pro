<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use Carbon\Carbon;
class DashboardController extends Controller
{
	public function index()
	{
		abort_if(!Auth()->user()->can('dashboard.index'), 401);
		$orders=Orders::with('user')->latest()->take(5)->get();
		$expired_orders=Orders::with('user','plan')->where('status', 2)->latest()->take(50)->get();
		return view('admin.dashboard',compact('orders','expired_orders'));
	}

	public function getMonthlyOrders($month)
	{
		$month=Carbon::parse($month)->month;
		$year=Carbon::parse(date('Y'))->year;
		$total= Orders::whereMonth('created_at', '=',$month)->whereYear('created_at', '=',$year)->count();
		$complete= Orders::whereMonth('created_at', '=',$month)->whereYear('created_at', '=',$year)->where('status',1)->count();
		$pending= Orders::whereMonth('created_at', '=',$month)->whereYear('created_at', '=',$year)->where('status',3)->count();
		$cancel= Orders::whereMonth('created_at', '=',$month)->whereYear('created_at', '=',$year)->where('status',0)->count();

		$data['total']=$total;
		$data['complete']=$complete;
		$data['pending']=$pending;
		$data['cancel']=$cancel;
		return response()->json($data);
	}

	public function getStaticData()
	{
		$year=Carbon::parse(date('Y'))->year;
		$today=Carbon::today();

		$balance= Orders::whereYear('created_at', '=',$year)->where('status','!=',0)->sum('amount');

		$sales= Orders::whereYear('created_at', '=',$year)->count();

		$earning_static=Orders::whereYear('created_at', '=',$year)->where('status','!=',0)->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, sum(amount) total')
                ->groupBy('year', 'month')
                ->get();
        $sale_static=Orders::whereYear('created_at', '=',$year)->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, count(*) sales')
                ->groupBy('year', 'month')
                ->get();


		$data['balance']=number_format($balance,2);
		$data['sales']=number_format($sales);
		$data['earning_static']=$earning_static;
		$data['sale_static']=$sale_static;

		return response()->json($data);

	}

	public function earningPerformance($period)
	{
		if ($period != 365) {
            $earnings=Orders::whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status','!=',0)->orderBy('id', 'asc')->selectRaw('year(created_at) year, date(created_at) date, sum(amount) total')->groupBy('year','date')->get();
        }
        else{
            $earnings=Orders::whereDate('created_at', '>', Carbon::now()->subDays($period))->where('status','!=',0)->orderBy('id', 'asc')->selectRaw('year(created_at) year, monthname(created_at) month, sum(amount) total')->groupBy('year','month')->get();
        }
       
        
        return response()->json($earnings); 
	}

}
