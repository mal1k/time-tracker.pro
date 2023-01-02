@extends('layouts.frontend.app')

@section('content')
<!-- breadcrumb area start -->
@php $info = json_decode($feature->featuremeta->value) @endphp
<div class="slider-breadcrumb-area text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="breadcrumb-title">
                    <h4>{{ $feature->title }}</h4>
                </div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/feature') }}">{{ __('Features') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $feature->title }}</li>
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
            <div class="col-lg-12">
                <div class="blog-details-area">
                    <div class="blog-details-img d-block text-center">
                        <img class="img-fluid w-25" src="{{ asset($info->icon) }}" alt="{{ $feature->title }}">
                    </div>
                    <div class="blog-name">
                        <h2>{{ $feature->title }}</h2>
                    </div>
                    <div class="blog-des">
                        <p>{{ $info->short_description }}</p>
                        {{ content($info->page_content ?? '') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- blog area end -->
@endsection