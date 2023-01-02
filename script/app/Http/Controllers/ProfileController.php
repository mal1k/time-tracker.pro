<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Hash;

class ProfileController extends Controller
{
	public function profile()
	{
		return view('profile');
	}

	public function update(Request $request)
	{
		$user=User::find(Auth::id());

		if ($request->password) {
			$validatedData = $request->validate([
				'password' => ['required', 'string', 'min:8', 'confirmed'],
			]);  


			$check=Hash::check($request->password_current,auth()->user()->password);

			if ($check==true) {
				$user->password= Hash::make($request->password);
			}
			else{

				$returnData['errors']['password']=array(0=>"Enter Valid Password");
				$returnData['message']="given data was invalid.";

				return response()->json($returnData, 401);

			}        
		}
		else{
			$validatedData = $request->validate([
				'name' => 'required|max:255',
				'email'  =>  'required|email|unique:users,email,'.Auth::id(),
				'avatar'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
			]);
			$user->name=$request->name;
			$user->email=$request->email;  
	
			// $attachment_path = $attachment_name = '';
	
			if ($request->hasFile('avatar')) {
				if (empty($user->avatar)) {
					if (file_exists($user->avatar)) {
						unlink($user->avatar);
					}
				}
				$attachment      = $request->file('avatar');
				$attachment_name = hexdec(uniqid()) . '.' . $attachment->getClientOriginalExtension();
				$attachment_path = 'uploads/' . date('y/m/');
				$attachment->move($attachment_path, $attachment_name);
			}
			
			$user->avatar = $attachment_path != '' ?  $attachment_path . $attachment_name : $user->avatar;
		}

		$user->save();

		return response()->json(['Profile Updated Successfully']); 
	}
}
