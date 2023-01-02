@extends('layouts.backend.app')

@section('title','Edit Feature')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Edit Feature','prev'=> route('admin.feature.index')])
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Edit Feature') }}</h4>
                </div>
                <form method="POST" action="{{ route('admin.feature.update', $feature->id) }}"
                    enctype="multipart/form-data" class="basicform">
                    @csrf
                    @method('PUT')
                    @php $info = json_decode($feature->featuremeta->value) @endphp
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Title') }}</label>
                                    <input type="text" class="form-control" placeholder="Title" required name="title"
                                        value="{{ $feature->title }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('Color') }}</label>
                                    <input type="color" class="form-control" placeholder="Color" required name="color"
                                        value="{{ $info->color }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Icon') }}</label>
                            <input type="file" class="form-control" placeholder="file" name="icon">
                            <img class="image-thumbnail" src="{{ asset($info->icon) }}" alt="">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Short Description') }}</label>
                            <textarea name="short_description" cols="30" rows="10"
                                class="form-control">{{ $info->short_description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Page Content') }}</label>
                            <textarea name="page_content" class="summernote form-control">{{ $info->page_content }}</textarea>
                        </div>
                        <div class="form-group">
                            <div class="custom-file mb-3">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $feature->status == 1 ? 'selected' : '' }}>{{ __('Active') }}
                                    </option>
                                    <option value="0" {{ $feature->status == 0 ? 'selected' : '' }}>
                                        {{ __('Inactive') }}</option>
                                </select>
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
