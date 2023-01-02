<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    // Relation To TermsMeta
    public function page()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'page');
    }

       // Relation to Termsmeta
    public function meta()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'content');
    }

    // Relation To TermsMeta
    public function excerpt()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'excerpt');
    }

    // Relation To TermsMeta
    public function thum_image()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'thum_image');
    }

    // Relation To TermsMeta
    public function description()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'description');
    }

    // Relation To TermsMeta
    public function announcement()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'announcement_desc');
    }

    // Relation To TermsMeta
    public function review()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'review_details');
    }

     // Relation To TermsMeta
     public function featuremeta()
     {
         return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'feature_meta');
     }

      // Relation To TermsMeta
      public function aboutmeta()
      {
          return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'about_meta');
      }

    // Relation To TermsMeta
    public function analyticmeta()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'analytic_meta');
    }
      
}

