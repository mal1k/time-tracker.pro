<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
class AboutController extends Controller
{
    public function index()
    {
         $about=Term::where('type','aboutsection')->with('aboutmeta')->where('status',1)->first();
         abort_if(empty($about),404);
         $meta=json_decode($about->aboutmeta->value ?? '');

         JsonLdMulti::setTitle($about->title);
         JsonLdMulti::setDescription($meta->short_description ?? null);
         JsonLdMulti::addImage(asset('uploads/logo.png'));

         SEOMeta::setTitle($about->title);
         SEOMeta::setDescription($meta->short_description ?? null);
         

         SEOTools::setTitle($about->title);
         SEOTools::setDescription($meta->short_description ?? null);
        
         SEOTools::opengraph()->addProperty('image', asset($meta->image ?? ''));
         SEOTools::twitter()->setTitle($about->title);
         
         SEOTools::jsonLd()->addImage(asset($meta->image ?? ''));
         return view('about.show',compact('about'));
    }
}
