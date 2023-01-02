<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;
use App\Models\Option;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
class FeatureController extends Controller
{
    public function show($slug)
    {
         $feature= Term::where('slug',$slug)->where('type','feature')->with('featuremeta')->where('status',1)->first();
         abort_if(empty($feature),404);
         $meta=json_decode($feature->featuremeta->value ?? '');
         JsonLdMulti::setTitle($feature->title);
         JsonLdMulti::setDescription($meta->short_description ?? null);
         JsonLdMulti::addImage(asset($meta->icon ?? null));
         SEOMeta::setTitle($feature->title);
         SEOMeta::setDescription($meta->short_description ?? null);
         SEOTools::setTitle($feature->title);
         SEOTools::setDescription($meta->short_description ?? null);
         SEOTools::opengraph()->addProperty('image', asset($meta->icon ?? null));
         SEOTools::twitter()->setTitle($feature->title);
         SEOTools::jsonLd()->addImage(asset($meta->icon ?? null));

         return view('feature.show',compact('feature'));
    }

    public function index()
    {
        $seo=Option::where('key','seo')->first();
        $seo=json_decode($seo->value ?? '');

        JsonLdMulti::setTitle('Features');
        JsonLdMulti::setDescription($seo->description ?? null);
        JsonLdMulti::addImage(asset('uploads/logo.png'));

        SEOMeta::setTitle('Features');
        SEOMeta::setDescription($seo->description ?? null);
        SEOMeta::addKeyword($seo->tags ?? null);

        SEOTools::setTitle('Features');
        SEOTools::setDescription($seo->description ?? null);
        SEOTools::setCanonical($seo->canonical ?? url('/'));
        SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
        SEOTools::opengraph()->addProperty('image', asset('uploads/logo.png'));
        SEOTools::twitter()->setTitle('Features');
        SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
        SEOTools::jsonLd()->addImage(asset('uploads/logo.png'));
        $features = Term::where('type', 'feature')->where('status', 1)->get();

         return view('feature.index',compact('features'));
    }


    
}
