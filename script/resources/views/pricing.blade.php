@extends('layouts.frontend.app')

@section('content')
<!-- breadcrumb area start -->
<div class="slider-breadcrumb-area text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="breadcrumb-title">
                    <h4>{{ __('Pricing') }}</h4>
                </div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                      <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                      <li class="breadcrumb-item active" aria-current="page">{{ __('Pricing') }}</li>
                  </ol>
              </nav>
          </div>
      </div>
  </div>
</div>
<!-- breadcrumb area end -->

<!-- blog area start -->
<div class="blog-main-area pricing-area pt-150 pb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="blog-details-area">
                    <div class="blog-des">
                        <div class="row">
                            @foreach ($posts as $plan)
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
            </div>
        </div>
    </div>
</div>
<!-- blog area end -->
@endsection