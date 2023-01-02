@extends('layouts.frontend.app')

@section('content')
<!-- breadcrumb area start -->
<div class="slider-breadcrumb-area text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="breadcrumb-title">
                    <h4>{{ __('Feature List') }}</h4>
                </div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                      <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                      <li class="breadcrumb-item active" aria-current="page">{{ __('Features') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- service area start -->
  <div class="service-area pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="service-header-area text-center">
                    <div class="service-short-title">
                        <h5>{{ __('What We Do') }}</h5>
                    </div>
                    <div class="service-title">
                        <h2>{{ __('Awesome solutions for saas management') }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            @foreach ($features ?? [] as $feature)
            @php $info = json_decode($feature->featuremeta->value) @endphp
            <div class="col-lg-3">
                <div class="single-service text-center">
                    <div class="service-icon icon-thumnail">
                       @if (!empty($info->icon))
                       <img style="background:{{ $info->color }}" src="{{ asset($info->icon) }}" alt="">
                       @else
                       <span class="iconify" data-icon="carbon:manage-protection" data-inline="false"></span>
                       @endif
                    </div>
                    <div class="service-name">
                        <h4>{{ $feature->title }}</h4>
                    </div>
                    <div class="service-des">
                        <p>{{ Str::limit($info->short_description?? '',75) }}</p>
                    </div>
                    <div class="service-action">
                        <a href="{{ route('feature.show',$feature->slug ) }}"><span class="iconify" data-icon="bytesize:arrow-right" data-inline="false"></span></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- blog area end -->
@endsection