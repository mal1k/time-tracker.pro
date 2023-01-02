@extends('layouts.backend.app')

@section('title','Create Reiew')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Create Review','prev'=>route('admin.review.index')])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
     <div class="card-header">
      <div class="col-12">
        <h4>{{ __('Create New Review') }}</h4>
        @if(Session::has('message'))
        <p class="alert alert-danger">
          {{ Session::get('message') }}
        </p>
        @endif
      </div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('admin.review.store') }}" class="basicform_with_reset" enctype="multipart/form-data">
        @csrf
        <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Name') }}</label>
         <div class="col-sm-12 col-md-7">
           <input type="text" class="form-control" placeholder="Name" required name="name">
         </div>
       </div>
       <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Position') }}</label>
         <div class="col-sm-12 col-md-7">
          <input type="text" class="form-control" placeholder="Position" required name="position">
         </div>
       </div>
       <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Avatar') }}</label>
         <div class="col-sm-12 col-md-7">
          <input type="file" class="form-control" name="image">
        </div>
      </div>
      <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Comment') }}</label>
       <div class="col-sm-12 col-md-7">
        <textarea name="comment" cols="30" rows="10" class="form-control"></textarea>
      </div>
      </div>
      <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Rating') }}</label>
         <div class="col-sm-12 col-md-7">
           <select name="rating" class="form-control">
            <option value="1">{{ __('1') }}</option>
            <option value="2">{{ __('2') }}</option>
            <option value="3">{{ __('3') }}</option>
            <option value="4">{{ __('4') }}</option>
            <option value="5">{{ __('5') }}</option>
          </select>
        </div>
      </div>
      <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
       <div class="col-sm-12 col-md-7">
        <select name="status" class="form-control">
          <option value="1">{{ __('Active') }}</option>
          <option value="0">{{ __('Inactive') }}</option>
        </select>
      </div>
    </div>
       <div class="form-group row mb-4">
         <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
         <div class="col-sm-12 col-md-7">
          <button type="submit" class="btn btn-primary basicbtn">{{ __('Create Post') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

