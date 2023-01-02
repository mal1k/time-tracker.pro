@extends('layouts.frontend.app')

@section('content')
 <!-- breadcrumb area start -->
<div class="slider-breadcrumb-area text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="breadcrumb-title">
                    <h4>{{ Str::limit($info->title,30) }}</h4>
                </div>
                <nav  aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/blog') }}">{{ __('blog') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($info->title,30) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb area end -->

<!-- blog area start -->
<div class="blog-main-area mt-100 mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="blog-details-area">
                    <div class="blog-details-img">
                        <img class="img-fluid" src="{{ asset($info->thum_image->value) }}" alt="{{ $info->title }}">
                    </div>
                    <div class="blog-name">
                        <h2>{{ $info->title }}</h2>
                    </div>
                    <div class="blog-des">
                        {{ content($info->description->value ?? '') }}
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="blog-card">
                    <div class="blog-card-header">
                        <h4>{{ __('Search') }}</h4>
                    </div>
                    <div class="blog-card-body">
                            <form action="{{ route('blog.index') }}">
                            <div class="search-input d-flex align-items-center justify-content-between">
                                <input name="src"  type="text" placeholder="{{ __('Search') }}">
                                <button type="submit">{{ __('Search') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="blog-card">
                    <div class="blog-card-header">
                        <h4>{{ __('Recent Posts') }}</h4>
                    </div>
                    <div class="blog-card-body">
                        @foreach($latest as $row)
                        <div class="single-card-blog d-flex align-items-center justify-content-between">
                            <div class="card-blog-img">
                                <a href="{{ route('blog.show',$row->slug) }}"><img src="{{ asset($row->thum_image->value ?? '') }}" alt="{{ $row->title }}"></a>
                            </div>
                            <div class="card-blog-name">
                                <a href="{{ route('blog.show',$row->slug) }}"><h6>{{ Str::limit($row->title,55) }}</h6></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- blog area end -->
@endsection