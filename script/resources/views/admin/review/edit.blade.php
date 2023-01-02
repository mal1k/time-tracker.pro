@extends('layouts.backend.app')

@section('title','Edit Review')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Edit','prev'=>route('admin.review.index')])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
     <div class="card-header">
      <div class="col-12">
        <h4>{{ __('Edit Review') }}</h4>
        @if(Session::has('message'))
        <p class="alert alert-danger">
          {{ Session::get('message') }}
        </p>
        @endif
      </div>
    </div>
    <div class="card-body">
     <form method="POST" action="{{ route('admin.review.update', $review->id) }}" class="basicform" enctype="multipart/form-data">
      @csrf
      @method('put')
      @php
      $details = json_decode($review->review->value);
      @endphp
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Name') }}</label>
         <div class="col-sm-12 col-md-7">
           <input type="text" class="form-control" placeholder="Name" required name="name" value="{{ $review->title }}">
         </div>
       </div>
       <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Position') }}</label>
         <div class="col-sm-12 col-md-7">
           <input type="text" class="form-control" placeholder="Position" required name="position" value="{{ $details->position }}">
         </div>
       </div>
       <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Avatar') }}</label>
         <div class="col-sm-12 col-md-7">
           <input type="file" class="form-control" name="image">
           <img class="mt-2" src="{{ asset($details->image?? '') }}" height="50" alt="">
        </div>
      </div>
      <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Comment') }}</label>
       <div class="col-sm-12 col-md-7">
        <textarea name="comment" cols="30" rows="10" class="form-control">{{ $details->comment }}</textarea>
      </div>
      </div>
      <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Rating') }}</label>
         <div class="col-sm-12 col-md-7">
          <select name="rating" class="form-control">
            <option value="1" {{ $details->rating == 1 ? 'selected':''}}>{{ __('1') }}</option>
            <option value="2" {{ $details->rating == 2 ? 'selected':''}}>{{ __('2') }}</option>
            <option value="3" {{ $details->rating == 3 ? 'selected':''}}>{{ __('3') }}</option>
            <option value="4" {{ $details->rating == 4 ? 'selected':''}}>{{ __('4') }}</option>
            <option value="5" {{ $details->rating == 5 ? 'selected':''}}>{{ __('5') }}</option>
          </select>
        </div>
      </div>
      <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
       <div class="col-sm-12 col-md-7">
         <select name="status" class="form-control">
          <option value="1" {{ $review->status == 1 ? 'selected':''}}>{{ __('Active') }}</option>
          <option value="0" {{ $review->status == 0 ? 'selected':''}}>{{ __('Inactive') }}</option>
        </select>
      </div>
    </div>
       <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
         <div class="col-sm-12 col-md-7">
          <button type="submit" class="btn btn-primary basicbtn">{{ __('Update') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

