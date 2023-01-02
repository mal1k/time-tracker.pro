<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Term;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Newsletter;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            DB::select('SHOW TABLES');

            $plans = Plan::where('status', 1)->where('is_default',0)->latest()->get();
            $features = Term::where('type', 'feature')->where('status', 1)->get();
            $about = Term::where('type', 'aboutsection')->first();
            $analytic = Term::where('type', 'analytic')->first();
            $blogs=Term::where([['type','blog'],['status',1]])->with('excerpt','thum_image')->latest()->take(3)->get();
            $header=Option::where('key','header')->first();
            $header=json_decode($header->value ?? '');

            $seo=Option::where('key','seo')->first();
            $seo=json_decode($seo->value ?? '');

            JsonLdMulti::setTitle($seo->title ?? env('APP_NAME'));
            JsonLdMulti::setDescription($seo->description ?? null);
            JsonLdMulti::addImage(asset('uploads/logo.png'));

            SEOMeta::setTitle($seo->title ?? env('APP_NAME'));
            SEOMeta::setDescription($seo->description ?? null);
            SEOMeta::addKeyword($seo->tags ?? null);

            SEOTools::setTitle($seo->title ?? env('APP_NAME'));
            SEOTools::setDescription($seo->description ?? null);
            SEOTools::setCanonical($seo->canonical ?? url('/'));
            SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
            SEOTools::opengraph()->addProperty('image', asset('uploads/logo.png'));
            SEOTools::twitter()->setTitle($seo->title ?? env('APP_NAME'));
            SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
            SEOTools::jsonLd()->addImage(asset('uploads/logo.png'));


            return view('index', compact('plans','blogs','features','about','analytic','header'));

        } catch (\Exception $e) {
            return redirect()->route('install');
        }

    }

    public function check($planid){
        return view('user.planregister',compact('planid'));
    }

    //News letter subscription 
    public function subscribe(Request $request)
    {
        if ( ! Newsletter::isSubscribed($request->email) ) {
            Newsletter::subscribe($request->email);
        }else{
            return response()->json('Already Subscribed');
        }

        return response()->json('Subscribe Successful');
    }

    public function pricing(){
        $seo=Option::where('key','seo')->first();
        $seo=json_decode($seo->value ?? '');

        JsonLdMulti::setTitle('Prices');
        JsonLdMulti::setDescription($seo->description ?? null);
        JsonLdMulti::addImage(asset('uploads/logo.png'));

        SEOMeta::setTitle('Prices');
        SEOMeta::setDescription($seo->description ?? null);
        SEOMeta::addKeyword($seo->tags ?? null);

        SEOTools::setTitle('Prices');
        SEOTools::setDescription($seo->description ?? null);
        SEOTools::setCanonical($seo->canonical ?? url('/'));
        SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
        SEOTools::opengraph()->addProperty('image', asset('uploads/logo.png'));
        SEOTools::twitter()->setTitle('Prices');
        SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
        SEOTools::jsonLd()->addImage(asset('uploads/logo.png'));
        $posts = Plan::where('status', 1)->where('is_default',0)->latest()->get();
        return view('pricing',compact('posts'));
    }

    public function page($slug)
    {
        $info=Term::where([['type','page'],['slug',$slug],['status',1]])->with('page')->first();
        abort_if(empty($info),404);
        $data=json_decode($info->page->value ?? '');
        return view('page',compact('info','data'));
    }
}
