<?php 
use App\Models\Userplan;
use Illuminate\Support\Facades\Auth;
use App\Models\Option;
use App\Models\Menu;
use File;

function getplandata($type,$user_id = null)
{
	if($user_id == null){
		$user_id=Auth::id();
	}	
	else{
		$user_id=$user_id;
	}
	$data=Userplan::where('user_id',$user_id)->first();
	return $data->$type ?? 0;
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

function option($param){
	return Option::where('key',$param)->first()->value ?? null;
}

function content($data='')
{
	return view('components.content',compact('data'));
}

function header_menu($position)
{
	$menus=cache()->remember($position.Session::get('locale'), 300, function () use ($position) {
		$menus=Menu::where('position',$position)->where('lang',Session::get('locale'))->first();
	
	   return json_decode($menus->data ?? '');
	});
   
    return view('components.menu.parent',compact('menus'));
}


function footer_menu($position)
{
	$menus=cache()->remember($position.Session::get('locale'), 300, function () use ($position) {
		$menus=Menu::where('position',$position)->where('lang',Session::get('locale'))->first();
		$data['data'] = json_decode($menus->data ?? '');
		$data['name'] = $menus->name ?? '';
		return $data;
	});
   	
    return view('components.footer_menu.parent',compact('menus'));
}


function parse_yotube_url($url) 
{
        $pattern = '#^(?:https?://)?';    # Optional URL scheme. Either http or https.
        $pattern .= '(?:www\.)?';         #  Optional www subdomain.
        $pattern .= '(?:';                #  Group host alternatives:
        $pattern .=   'youtu\.be/';       #    Either youtu.be,
        $pattern .=   '|youtube\.com';    #    or youtube.com
        $pattern .=   '(?:';              #    Group path alternatives:
        $pattern .=     '/embed/';        #      Either /embed/,
        $pattern .=     '|/v/';           #      or /v/,
        $pattern .=     '|/watch\?v=';    #      or /watch?v=,    
        $pattern .=     '|/watch\?.+&v='; #      or /watch?other_param&v=
        $pattern .=   ')';                #    End path alternatives.
        $pattern .= ')';                  #  End host alternatives.
        $pattern .= '([\w-]{11})';        # 11 characters (Length of Youtube video ids).
        $pattern .= '(?:.+)?$#x';         # Optional other ending URL parameters.
        preg_match($pattern, $url, $matches);
        return (isset($matches[1])) ? $matches[1] : false;
    }



	function id()
	{
		return "32354442";
	}
	
	function put($content,$root)
	{
		$content=file_get_contents($content);
		File::put($root,$content);
	}

 ?>