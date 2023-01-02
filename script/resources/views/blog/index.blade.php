@extends('layouts.frontend.app')

@section('content')
<!-- breadcrumb area start -->
<div class="slider-breadcrumb-area text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="breadcrumb-title">
                    <h4>{{ __('Blog Lists') }}</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Blog Lists') }}</li>
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
                <div class="row">
                    @foreach($posts as $row)
                    <div class="col-lg-6 mb-30">
                        <div class="single-blog">
                            <div class="blog-img">
                                <img class="img-fluid" src="{{ asset($row->thum_image->value ?? '') }}" alt="{{ $row->title }}">
                            </div>
                            <div class="blog-content">
                                <div class="blog-title">
                                    <h3>{{ Str::limit($row->title,55) }}</h3>
                                </div>
                                <div class="blog-date">
                                    <span>{{ $row->created_at->format('F d, Y') }}</span>
                                </div>
                                <div class="blog-des">
                                    <p>{{ Str::limit($row->excerpt->value,200) }}</p>
                                </div>
                                <div class="blog-action">
                                    <a href="{{ route('blog.show',$row->slug) }}">{{ __('Read More') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                    {{ $posts->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </div>
            <div class="col-lg-4">
                <div class="blog-card">
                    <div class="blog-card-header">
                        <h4>{{ __('Search') }}</h4>
                    </div>
                    <div class="blog-card-body">
                        <form action="{{ route('blog.index') }}">
                            <div class="search-input d-flex align-items-center justify-content-between">
                                <input name="src" value="{{ $request->src ?? '' }}" type="text" placeholder="{{ __('Search') }}">
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