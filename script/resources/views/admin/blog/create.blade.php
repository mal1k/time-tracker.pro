@extends('layouts.backend.app')

@section('title','Add New Blog')

@push('css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Add New Post') }}</h4>
      </div>
      @if ($errors->any())
        <div class="alert alert-danger">
            <strong>{{ __('Whoops-') }}!</strong> {{ __('There were some problems with your input') }}.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif
      <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="basicform_with_reset">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label>{{ __('Name') }}</label>
            <input type="text" class="form-control" placeholder="Name" required name="name">
          </div>
          <div class="form-group">
              <label>{{ __('Short Content') }}</label>
              <textarea name="excerpt" cols="30" rows="10" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <label>{{ __('Description') }}</label>
            <textarea name="description" class="summernote form-control"></textarea>
          </div>
          <div class="form-group">
            <div class="custom-file mb-3">
              <input type="file" class="custom-file-input" id="customFile" name="thum_image">
              <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
            </div>
          </div>
          <div class="form-group">
            <div class="custom-file mb-3">
              <label>{{ __('Status') }}</label>
              <select name="status" class="form-control">
                <option value="1">{{ 'Active' }}</option>
                <option value="0">{{ 'Inactive' }}</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ 'Submit' }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@if(env('CONTENT_EDITOR') == true)
@push('js')
  <script src="{{ asset('backend/admin/assets/js/summernote-bs4.js') }}"></script>
  <script src="{{ asset('backend/admin/assets/js/summernote.js') }}"></script>
@endpush
@endif