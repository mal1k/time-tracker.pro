<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Column;
use App\Models\Project;
use App\Models\Projectactivities;
use App\Models\Projectactivity;
use App\Models\Projectscreenshot;
use App\Models\Screenshot;
use App\Models\Task;
use App\Models\Taskscreenshot;
use App\Models\Taskuser;
use App\Models\Team;
use App\Models\Time;
use App\Models\Timetask;
use App\Models\Userplan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Support\Facades\DB;

class TimeTrackerController extends Controller
{
    public function index(){
        $projects = Project::with('projectuser')->where('status', 2)
        ->where(function($query){
            $query->where('user_id', Auth::user()->id)
            ->orWhereHas('projectuser', function($query){
            $query->where('user_id', Auth::user()->id);
            })->with('projectuser');
        }
        )->get();

		$data = Time::with('project:id,name')
		->where([
			['user_id', Auth::id()],
			['started_at', '>=', Carbon::now()->subWeek()]])
		->orderBy('id','DESC')
		->selectRaw('project_id,DATE(started_at) as date,sum(total_time) as time')
		->groupBy('project_id','date')
		->get();

		$project_ids = [];

		foreach($projects as $project){
			foreach($project->projectuser ?? [] as $projectuser){
				if(($projectuser->is_admin == 1 && $projectuser->user_id == Auth::id() || $project->user_id == Auth::id())){
					array_push($project_ids,$projectuser->project_id);
				}
			}
		}
		
        return view('user.timetracker.index',compact('projects', 'data','project_ids'));
    }

	public function getTasks(Request $request){
		$project_id = (int)$request->id;
		$project = Project::findOrFail($project_id);
		
		if ($project->user_id == Auth::id()) {
			$columns = Column::with('pending_task')->where('project_id', $project_id)->get();
		}else{
			$columns = Column::with('pending_task_with_user')->where('project_id', $project_id)->get();
		}

		return $columns;
	}

	public function taskTracker($project_id,$task_id){
		$task = Task::findOrFail($task_id);
		$project = Project::findOrFail($project_id);
		return view('user.timetracker.task_tracker',compact('task', 'project'));
	}

	public function gpsStore(Request $request){

		$activity = Activity::where([
			['user_id', Auth::id()],
		])->get();

		$project_activity = Projectactivity::where([
			['user_id', Auth::id()],
		])->get();

		if ($activity->count() == 0 && $request->task_id) {

			DB::beginTransaction();
			try {
				$store = new Activity;
				$store->task_id = $request->task_id;
				$store->latitude = $request->latitude;
				$store->longitude = $request->longitude;
				$store->project_id = $request->project_id;
				$store->user_id = Auth::id();
				$store->save();
				DB::commit();
			} catch (\Throwable $th) {
				DB::rollBack();
			}
		}

		if($project_activity->count() == 0 && $request->task_id == null){

			DB::beginTransaction();
			try {
				$store = new Projectactivity; 
				$store->latitude = $request->latitude;
				$store->longitude = $request->longitude;
				$store->project_id = $request->project_id;
				$store->user_id = Auth::id();
				$store->save();
				DB::commit();
			} catch (\Throwable $th) {
				DB::rollBack();
			}
		}
		
	}

	public function timestop(Request $request){
		$h_rate = Team::where('user_id', $request->user_id)->first();
		$rate = $h_rate ? (float) $h_rate->h_rate : 0;
		$start = Carbon::parse($request->started_at);
		$end = Carbon::parse($request->end_at);
		$work_time_in_second = $end->diffInSeconds($start, true);

		//Calculate price in seconds
		$price = ($rate / 3600) * $work_time_in_second;


        DB::beginTransaction();
        try {
			$time = new Time;
			$time->total_time = $work_time_in_second;
			$time->started_at = $request->started_at;
			$time->user_id = Auth::id();
			$time->end_at = $request->end_at;
			$time->project_id = $request->project_id;
			$time->price = number_format((float) $price, 3, '.', '');
			$time->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

		$time_id = $time->id;

		if ($request->task_id) {

			DB::beginTransaction();
			try {
				$task = new Timetask;
				$task->task_id = $request->task_id;
				$task->time_id = $time_id;
				$task->save();
				DB::commit();
			} catch (\Throwable $th) {
				DB::rollBack();
			}
		}


		return response()->json('Data Saved Successfully!!');	
	}

	public function uploadScreenshot(Request $request)
	{
		$screenshot = $request->screenshots;
		$screenshot_status = 1;
		$project = Project::findOrFail($request->project_id);
		$cleintIp = $this->getIp();
		$data = [];

		//Check if screenshot is already disabled
		if ($project->screenshot == 1) {
			//Check if storage limit exceeded
			//if storage limit exceeds 95% then disable screenshot status 
			if($this->storageUsed($project->user_id) > 95){
				$screenshot_status = 0;
                $project->screenshot = 0;
				$project->save();
				return 'Storage limit exceeded!!';
            };


			if ($screenshot_status == 1) {
		
				$time = $screenshot['local_time'];
				$image = str_replace('data:image/png;base64,', '', $screenshot['file']);
				$image = str_replace(' ', '+', $image);
				$imageName = \Str::random(10).'.'.'png';
				$path='uploads/' . $project->user_id .'/' .$project->id;
	
				if(!File::exists($path)) {
					File::makeDirectory($path, 0777, true, true);
				}	
	
				\File::put($path.'/'.$imageName, base64_decode($image));
				$filename = $path.'/'.$imageName;
				$ids = [];
				$task_screenshots = [];
				
				$data[] = [
					'file' => $filename,
					'time' => $time,
					'ip' => $cleintIp,
					'user_id' => $project->user_id,
					'project_id' => $project->id,
					'full_activity' => $screenshot['full_activity'],
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				];
				
		
				foreach ($data as $value) {
					$ids[] = $this->getlastIdArr($value);
				}
		
				foreach ($ids as $id) {
					if ($request->task_id) {
						$task_screenshots[] = [
							'project_id' => $request->project_id,
							'task_id' => $request->task_id,
							'user_id' => Auth::id(),
							'screenshot_id' => $id,
						];
					}else{
						$task_screenshots[] = [
							'project_id' => $request->project_id,
							'user_id' => Auth::id(),
							'screenshot_id' => $id,
						];
					}
				}

				if ($request->task_id) {
					DB::beginTransaction();
					try {
						Taskscreenshot::insert($task_screenshots);
						DB::commit();
					} catch (\Exception $exception) {
						DB::rollBack();
					}
				}else{
					DB::beginTransaction();
					try {
						Projectscreenshot::insert($task_screenshots);
						DB::commit();
					} catch (\Throwable $exception) {
						DB::rollBack();
					}	
				}
			}

			return true;
		}
	}

	public function getlastIdArr($value){
		$id = DB::transaction(function () use ($value){
			return Screenshot::insertGetId($value);
		});
		return $id;
	}

    public function trackuser(Request $request){
    	foreach($request->useractivity as $useractivity){
    		$image = str_replace('data:image/png;base64,', '', $useractivity['file']);
    		$image = str_replace(' ', '+', $image);
    		$imageName = \Str::random(10).'.'.'png';
    		$path='uploads/'.Auth::id();
    		if(!File::exists($path)) {
    			File::makeDirectory($path, 0777, true, true);
    		}	
    		\File::put($path.'/'.$imageName, base64_decode($image));
    	}
    }


	public function getIp(){
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
			if (array_key_exists($key, $_SERVER) === true){
				foreach (explode(',', $_SERVER[$key]) as $ip){
					$ip = trim($ip); // just to be safe
					if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
						return $ip;
					}
				}
			}
		}
		return request()->ip(); // it will return server ip when no client ip found
	}


	function storageUsed($user_id = 0){
        $dir = 'uploads/' . $user_id  . '/';
        $storage = $this->folderSize($dir);
        $storage_limit = getplandata('storage_size', $user_id);
        $storage = (float) $storage;
        return $storage_limit > 0 ? (($storage / $storage_limit) * 100) : 0;
    }


	
    function folderSize($dir){
		$file_size = 0;
		if (!file_exists($dir)) {
		return $file_size;
		}
		
		foreach(\File::allFiles($dir) as $file)
		{
		    $file_size += $file->getSize();
		}
		return $file_size = str_replace(',', '', number_format($file_size / 1048576,2));
	}


}
