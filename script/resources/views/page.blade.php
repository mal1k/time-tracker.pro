@extends('layouts.frontend.app')

@section('content')
<!-- breadcrumb area start -->
<div class="slider-breadcrumb-area text-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-3">
        <div class="breadcrumb-title">
          <h4>{{ Str::limit($info->name,50) }}</h4>
        </div>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
          <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($info->slug,50) }}</li>
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
          <div class="blog-des">
           
            {{ content($data->page_content ?? '') }}
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- blog area end -->
@endsection