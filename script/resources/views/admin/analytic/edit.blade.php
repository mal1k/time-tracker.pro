@extends('layouts.backend.app')

@section('title','Analytic')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Analytic Section'])
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('Edit Analytic Section') }}</h4>
            </div>
            <form method="POST" action="{{ route('admin.analytic.store') }}" enctype="multipart/form-data"
                    class="basicform_with_reload">
                @csrf
                @php $info = !empty($analytic) ? json_decode($analytic->analyticmeta->value) : '' @endphp
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ __('Title') }}</label>
                        <input type="text" class="form-control" placeholder="Title" required name="title"
                            value="{{ $analytic->title ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Short Title') }}</label>
                        <input type="text" class="form-control" placeholder="Short Title" required name="short_title"
                            value="{{ $info->short_title ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('image') }}</label>
                        <input type="file" class="form-control" placeholder="file" name="image">
                        <img class="image-thumbnail" src="{{ !empty($info->image) ? asset($info->image) : '' }}"
                            alt="">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Short Description') }}</label>
                        <textarea name="short_description" cols="30" rows="10"
                            class="form-control">{{ $info->short_description ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Page Content') }}</label>
                        <textarea name="page_content" class="summernote form-control" id="summernote">{{ $info->page_content ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-file mb-3">
                            <label>{{ __('Status') }}</label>
                            <select name="status" class="form-control">
                                <option value="1"
                                    {{ $analytic->status == 1 ? 'selected' : '' }}>
                                    {{ __('Active') }}</option>
                                <option value="0"
                                    {{ $analytic->status == 0 ? 'selected' : '' }}>
                                    {{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                            <label>{{ __('Button Text') }}</label>
                            <input type="text" class="form-control" placeholder="Text" name="button_text" value="{{ $info->button_text ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                            <div class="custom-file mb-3">
                                <label>{{ __('Show Details Button') }}</label>
                                <select name="button_status" class="form-control">
                                <option value="1" {{ $info->button_status == 1 ? 'selected': '' }}>{{ __('Yes') }}</option>
                                <option value="0" {{ $info->button_status == 0 ? 'selected': '' }}>{{ __('No') }}</option>
                                </select>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit"
                                    class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ __('Submit') }}</button>
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