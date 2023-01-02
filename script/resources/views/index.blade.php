@extends('layouts.frontend.app')

@section('title',config('app.name'))

@section('content')
 <!-- slider area start -->
<section>
    <div class="slider-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="slider-content">
                        <h2>{{ $header->title ?? '' }}</h2>
                        <p>{{ $header->short_title ?? '' }}</p>
                        @isset($header->get_start_form)
                        @if($header->get_start_form == 'show')
                        <div class="slider-form">
                            <form action="{{ route('user.register') }}">
                                <div class="form-group">
                                    <input type="text" name="email" placeholder="Enter Your Email Address" class="form-control">
                                </div>
                                <div class="button-btn">
                                <button type="submit"><span class="iconify" data-icon="akar-icons:circle-plus" data-inline="false"></span> {{ __('Get Started') }}</button>
                                </div>
                            </form>
                        </div>
                        @endif
                        @endisset
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="slider-right-area" style="background-image: url('{{ asset('uploads/header.png') }}');">
                        @isset($header->youtube_link)
                        @if(!empty($header->youtube_link))
                        <div class="slider-main-content">
                            <div class="slider-play-btn">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <span class="iconify" data-icon="clarity:play-solid" data-inline="false"></span>
                                </a>
                            </div>
                        </div>
                        @endif
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
    @isset($header->youtube_link)
    @if(!empty($header->youtube_link))
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <iframe width="auto" height="482" src="https://www.youtube.com/embed/{{ parse_yotube_url($header->youtube_link ?? '') }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    @endif
    @endisset
</section>
<!-- slider area end -->

<!-- service area start -->
<div class="service-area pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="service-header-area text-center">
                    <div class="service-short-title">
                        <h5>{{ __('features_short_title') }}</h5>
                    </div>
                    <div class="service-title">
                        <h2>{{ __('features_long_title') }}</h2>
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
<!-- service area end -->

<!-- feature area start -->
<div class="feature-area pb-100">
    @php $about_info = json_decode($about->aboutmeta->value) @endphp
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="feature-img">
                    <img class="img-fluid" src="{{ asset($about_info->image) }}" alt="">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="feature-right-content-area">
                    <div class="feature-shot-title">
                        <h5>{{ $about_info->short_title }}</h5>
                    </div>
                    <div class="feature-title">
                        <h2>{{ $about->title }}</h2>
                    </div>
                    <div class="feature-des">
                        <p>{{ Str::limit($about_info->short_description?? '',250) }}</p>
                    </div>
                    @if ($about_info->button_status == 1)
                    <div class="feature-btn">
                        <a href="{{ route('about.index') }}">{{ $about_info->button_text }}<span class="iconify" data-icon="dashicons:arrow-right-alt2" data-inline="false"></span></a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- feature area end -->

<!-- about area start -->
<div class="about-area pb-100">
    @php $analytic_info = json_decode($analytic->analyticmeta->value) @endphp
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-content-area">
                    <h2>{{ $analytic->title }}</h2>
                    <p>{{ Str::limit($analytic_info->short_description ?? '',250) }}</p>
                    @if ($analytic_info->button_status == 1)
                    <div class="feature-btn">
                        <a href="{{ route('analytic.index') }}">{{ $analytic_info->button_text }}<span class="iconify" data-icon="dashicons:arrow-right-alt2" data-inline="false"></span></a>
                    </div>
                    @endif
                    
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-are-img">
                    @if (!empty($analytic_info->image))
                        <img class="img-fluid" src="{{ $analytic_info->image }}" alt="">
                    @else
                    <img class="img-fluid" src="{{ asset('uploads/about.svg') }}" alt="">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- about area start -->
@if(count($plans) > 0)
<!-- pricing area start -->
<div class="pricing-area mb-100 pt-150 pb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="pricing-header-area text-center">
                    <div class="pricing-header">
                        <h2>{{ __('pricing_title') }}</h2>
                        <p>{{ __('pricing_description') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            @foreach ($plans as $plan)
            @php
            if ($plan->duration == 30) {
                $duration='Monthly';
            }
            elseif($plan->duration == 365){
                    $duration='Yearly';
            }
            else{
                $duration=$plan->duration.' Days';
            }
            @endphp
            <div class="col-lg-3">
                <div class="single-pricing {{ $plan->is_featured == 1 ? 'active' : '' }}">
                    <div class="pricing-type">
                        <h6>{{ $plan->name }}</h6>
                    </div>
                    <div class="pricing-price">
                        <sub>{{ option('currency_icon') }}</sub> {{ number_format($plan->price,2) }} <sub>/ {{ $duration }}</sub>
                    </div>
                    <div class="pricing-list">
                        <ul>
                            <li><span class="iconify" data-icon="akar-icons:check" data-inline="false"></span> {{ $plan->project_limit  }} {{ __('Project') }}</li>
                            <li><span class="iconify" data-icon="akar-icons:check" data-inline="false"></span> {{ number_format($plan->storage_size,2) }} {{ __('MB') }}</li>
                            <li><span class="iconify" data-icon="akar-icons:check" data-inline="false"></span> {{ $plan->user_limit }} {{ __('Members') }}</li>
                            <li><span class="iconify" data-icon="akar-icons:check" data-inline="false"></span> {{ $plan->group_limit }} {{ __('Groups') }}</li>

                            <li><span class="iconify {{ $plan->screenshot == 1 ? '' : 'text-danger' }}" data-icon="akar-icons:{{ $plan->screenshot == 1 ? 'check' : 'cross' }}" data-inline="false"></span>{{ __('Screenshot Capture') }}</li>

                            <li><span class="iconify {{ $plan->gps == 1 ? '' : 'text-danger' }}" data-icon="akar-icons:{{ $plan->gps == 1 ? 'check' : 'cross' }}" data-inline="false"></span> {{ __('Gps Tracking') }}</li>

                                <li><span class="iconify {{ $plan->adminable_project == 1 ? '' : 'text-danger' }}" data-icon="akar-icons:{{ $plan->adminable_project == 1 ? 'check' : 'cross' }}" data-inline="false"></span> {{ __('Multi Admin Accessible Project') }}</li>
                            
                            <li><span class="iconify {{ $plan->mail_activity == 1 ? '' : 'text-danger' }}" data-icon="akar-icons:{{ $plan->mail_activity == 1 ? 'check' : 'cross' }}" data-inline="false"></span> {{ __('Mail Activity') }}</li>
                        </ul>
                    </div>
                    <div class="pricing-btn">
                        <a href="{{ route('plan.check', $plan->id) }}">{{ __('Get Started') }}</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- pricing area end -->
@endif
<!-- blog area start -->
@if(count($blogs) > 0)
<section>
    <div class="blog-area mb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="blog-header-area text-center">
                        <h4>{{ __('news_title') }}</h4>
                        <p>{{ __('news_description') }}</p>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                @foreach($blogs as $row)
                <div class="col-lg-4">
                    <div class="single-blog">
                        <div class="blog-img">
                            <img class="img-fluid" src="{{ asset($row->thum_image->value ?? '') }}" alt="">
                        </div>
                        <div class="blog-content">
                            <div class="blog-title">
                                <h3>{{ Str::limit($row->title,55) }}</h3>
                            </div>
                            <div class="blog-date">
                                <span>{{ $row->updated_at->format('F d, Y') }}</span>
                            </div>
                            <div class="blog-des">
                                <p>{{ Str::limit($row->excerpt->value ?? '',200) }}</p>
                            </div>
                            <div class="blog-action">
                                <a href="{{ url('/blog',$row->slug) }}">{{ __('Read More') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- blog area end -->
@endif
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/home.js') }}"></script>
@endpush