<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Term;
use App\Models\Option;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $seo=Option::where('key','seo')->first();
        $seo=json_decode($seo->value ?? '');

        JsonLdMulti::setTitle('Blog');
        JsonLdMulti::setDescription($seo->description ?? null);
        JsonLdMulti::addImage(asset('uploads/logo.png'));

        SEOMeta::setTitle('Blog');
        SEOMeta::setDescription($seo->description ?? null);
        SEOMeta::addKeyword($seo->tags ?? null);

        SEOTools::setTitle('Blog');
        SEOTools::setDescription($seo->description ?? null);
        SEOTools::setCanonical($seo->canonical ?? url('/'));
        SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
        SEOTools::opengraph()->addProperty('image', asset('uploads/logo.png'));
        SEOTools::twitter()->setTitle('Blog');
        SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
        SEOTools::jsonLd()->addImage(asset('uploads/logo.png'));

        $posts=Term::where('type','blog')->with('thum_image','excerpt');
        if(!empty($request->src)){
             $posts=$posts->where('title','LIKE','%'.$request->src.'%');
        }      
        $posts=$posts->where('status',1)->latest()->paginate(6);
        $latest=Term::where('type','blog')->with('thum_image')->latest()->where('status',1)->take(3)->get();
        return view('blog.index',compact('posts','latest','request'));
    }

    

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
         $info=Term::where('type','blog')->with('thum_image','excerpt','description')->where('status',1)->latest()->first();
         abort_if(empty($info),404);

         $latest=Term::where('type','blog')->with('thum_image')->latest()->where('status',1)->take(3)->get();

         JsonLdMulti::setTitle($info->title);
         JsonLdMulti::setDescription($info->excerpt->value ?? null);
         JsonLdMulti::addImage(asset($info->thum_image->value ?? null));

         SEOMeta::setTitle($info->title);
         SEOMeta::setDescription($info->excerpt->value ?? null);
        

         SEOTools::setTitle($info->title);
         SEOTools::setDescription($info->excerpt->value ?? null);
        
         SEOTools::opengraph()->addProperty('image', asset($info->thum_image->value ?? null));
         SEOTools::twitter()->setTitle($info->title);
        
         SEOTools::jsonLd()->addImage(asset($info->thum_image->value ?? null));
         return view('blog.show',compact('info','latest'));
    }

}
