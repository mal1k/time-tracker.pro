@extends('layouts.backend.app')

@section('title','Edit Header Section')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Edit Header Section'])
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="col-12">
                    <h4>{{ __('Header') }}</h4>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.header.store') }}" class="basicform" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row mb-4">
                        <label
                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Highlight Title') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control"
                                placeholder="Highlight Title" value="{{ $header->title }}" required name="title" required="">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label
                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Short Title') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control"
                                placeholder="title" value="{{ $header->short_title }}" required name="short_title" required="">                                
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label
                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Show Get Start Button') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <select class="form-control" name="get_start_form">
                                <option value="show" @if($header->get_start_form == 'show') selected  @endif>{{ __('Show') }}</option>
                                <option value="hide" @if($header->get_start_form == 'hide') selected  @endif>{{ __('Hide') }}</option>
                            </select>                                
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label
                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Youtube Video Link') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control"
                                placeholder="youtube video url" name="youtube_link" value="{{ $header->youtube_link }}">                               
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label
                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Hero Image') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="file" class="form-control" name="file" accept="image/*">                               
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
    </div>    
</div>
@endsection
