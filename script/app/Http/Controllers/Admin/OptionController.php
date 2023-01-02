<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    
    public function edit($key){
        abort_if(!Auth()->user()->can('option'), 401);
        if ($key == 'cron_option') {
            $option = Option::where('key', $key)->first();
            return view('admin.option.cron', compact('option'));
        }
        else{
            $auto_enroll_after_payment = Option::where('key', 'auto_enroll_after_payment')->first();
            $currency = Option::where('key', 'currency')->first();
            $tax = Option::where('key', 'tax')->first();
            $invoice_prefix = Option::where('key', 'invoice_prefix')->first();
            $support_email = Option::where('key', 'support_email')->first();

            return view('admin.option.option', compact('auto_enroll_after_payment','currency','tax','invoice_prefix','support_email'));
        }
        abort(404);
        
    }

    public function update(Request $request, $key){
        abort_if(!Auth()->user()->can('option'), 401);
        if ($key == 'cron_option') {
            $request->validate([
               
                'days' => 'required',
                'assign_default_plan' => 'required',
                'alert_message' => 'required',
                'expire_message' => 'required',  
            ]);

            $option = Option::where('key', $key)->first();
        
        
            $data = [
                "status" => $request->status ?? 'on',
                "days" => $request->days,
                "assign_default_plan" => $request->assign_default_plan,
                "alert_message" => $request->alert_message,
                "expire_message" => $request->expire_message,
            ];
            $value = json_encode($data);
            $option->value = $value;
            $option->save();
        }else{
            $request->validate([
                'logo' => 'image|max:500'
            ]);

            DB::beginTransaction();
            try {
                $auto_enroll_after_payment = Option::where('key', 'auto_enroll_after_payment')->first();
                $auto_enroll_after_payment->value=$request->auto_enroll_after_payment;
                $auto_enroll_after_payment->save();
    
                $currency = Option::where('key', 'currency')->first();
                $currency->value=$request->currency;
                $currency->save();
    
                $tax = Option::where('key', 'tax')->first();
                $tax->value=$request->tax;
                $tax->save();
                
                $currency_icon = Option::where('key', 'currency_icon')->first();
                $currency_icon->value=$request->currency_icon;
                $currency_icon->save();
    
                $invoice_prefix = Option::where('key', 'invoice_prefix')->first();
                $invoice_prefix->value=$request->invoice_prefix;
                $invoice_prefix->save();
    
                $support_email = Option::where('key', 'support_email')->first();
                $support_email->value=$request->support_email;
                $support_email->save();
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }

            if ($request->hasFile('logo')) {
                $file      = $request->file('logo');
                $logo = 'dark_logo.png';
                $thum_image_path = 'uploads/';
                $file->move($thum_image_path, $logo);
            }
            
        }

        return response()->json('Successfully Updated');

    }


    public function settingsEdit(){
        $theme = Option::where('key', 'theme_settings')->first();
        return view('admin.option.theme', compact('theme'));
    }


    public function settingsUpdate($id, Request $request){
        foreach ($request->social as $value) {
            $social[] = [
                'icon' => $value['icon'],
                'link' => $value['link']
            ];
        };

        // logo check
        if ($request->hasFile('logo')) {
            $logo      = $request->file('logo');
            $logo_name = 'logo.png';
            $logo_path = 'uploads/';
            $logo->move($logo_path, $logo_name);
            
        }

        if ($request->hasFile('favicon')) {
            $favicon      = $request->file('favicon');
            $favicon_name = 'favicon.ico';
            $favicon_path = 'uploads/';
            $favicon->move($favicon_path, $favicon_name);
        }

        $data = [
            'footer_description' => $request->footer_description,
            'newsletter_address' => $request->newsletter_address,
            'social'      => $social,
            'top_left_text' => $request->top_left_text,
            'top_left_link' => $request->top_left_link,
            'top_right_text' => $request->top_right_text,
            'top_right_link' => $request->top_right_link,
        ];

      $theme = Option::findOrFail($id); 
      $theme->key = 'theme_settings';
      $theme->value = json_encode($data);
      $theme->save();
      return response()->json('Theme Settings Updated!!');
  }




}
