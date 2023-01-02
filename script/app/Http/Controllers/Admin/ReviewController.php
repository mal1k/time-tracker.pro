<?php

namespace App\Http\Controllers\Admin;
use App\Models\Term;
use App\Models\Termmeta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!Auth()->user()->can('review.index'), 401);
        $reviews = Term::with('review')->where('type', 'review')->paginate(10);
        return view('admin.review.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!Auth()->user()->can('review.create'), 401);
        return view('admin.review.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'name'     => 'required',
            'position' => 'required',
            'image'     => 'required|image|max:1024',
        ]);

        // logo check
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image_path = 'uploads/1/' . date('y/m/');
            $image->move($image_path, $image_name);
        }

        // Data
        $data = [
            'position'     =>  $request->position,
            'rating'       =>  $request->rating,
            'comment'      =>  $request->comment,
            'image'        =>  $image_path . $image_name,
        ];


        DB::beginTransaction();
        try {
            // Term Data
            $review           = new Term();
            $review->title    = $request->name;
            $review->slug     = Str::slug($request->name, '-');
            $review->type     = 'review';
            $review->status   = $request->status;
            $review->featured = 1;
            $review->save();
    
            // Term Meta Data
            $review_meta          = new Termmeta();
            $review_meta->term_id = $review->id;
            $review_meta->key     = 'review_details';
            $review_meta->value   = json_encode($data);
            $review_meta->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        return response()->json('Review Added Successfully');
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(!Auth()->user()->can('review.edit'), 401);
        $review = Term::findOrFail($id);
        return view('admin.review.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate
        $request->validate([
            'name'     => 'required',
            'position' => 'required',
            'image'     => 'image|mimes:jpeg,png,jpg,gif,webp|max:1024',
        ]);

        $image = "";
         // Term Meta Data
        $review_meta  = Termmeta::where('term_id',$id)->first();
        $image_data = json_decode($review_meta->value);
        // logo check
        if ($request->hasFile('image')) {
            if (empty($image_data->image)) {
               if (file_exists($image_data->image)) {
                   unlink($image_data->image);
               }
            }

            $image      = $request->file('image');
            $image_name = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image_path = 'uploads/1/' . date('y/m/');
            $image->move($image_path, $image_name);
            $image =  $image_path . $image_name;
        }

        // Term Data
        $review           = Term::findOrFail($id);
        $review->title    = $request->name;
        $review->slug     = Str::slug($request->name, '-');
        $review->type     = 'review';
        $review->status   = $request->status;
        $review->featured = 1;
        $review->save();

       
       
        // Data
          $data = [
            'position'     =>  $request->position,
            'rating'       =>  $request->rating,
            'comment'      =>  $request->comment,
            'image'        =>  $image == "" ? $image_data->image : $image,
        ];

        $review_meta->term_id = $review->id;
        $review_meta->key     = 'review_details';
        $review_meta->value   = json_encode($data);
        $review_meta->save();

        return response()->json('Review Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(!Auth()->user()->can('review.delete'), 401);
        $delete = Term::with('review')->findOrFail($id);
        $image_data=json_decode($delete->review->value ?? '');
        if (empty($image_data->image)) {
         if (file_exists($image_data->image)) {
             unlink($image_data->image);
         }
        }

        $delete->delete();
        return redirect()->back()->with('success', 'Successfully Deleted'); 
    }
}
