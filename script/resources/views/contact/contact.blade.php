@extends('layouts.frontend.app')

@section('content')
<!-- breadcrumb area start -->
<div class="slider-breadcrumb-area text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="breadcrumb-title">
                    <h4>{{ __('Contact Us') }}</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                      <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                      <li class="breadcrumb-item active" aria-current="page">{{ __('Contact Us') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb area start -->

<!-- contact area start -->
<section>
    <div class="contact-area pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="header-title-section text-center">
                        <h3>{{ __('Contact Us') }}</h3>
                        <p>{{ __('contact_us_description') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-form">
                        <form action="{{ route('contact.send') }}" method="POST" id="basicform">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Name') }}</label>
                                        <input type="text" placeholder="{{ __('Enter Your Name') }}" class="form-control" name="name" id="name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Email') }}</label>
                                        <input type="email" placeholder="{{ __('Enter Your Email') }}" class="form-control" name="email" id="email">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>{{ __('Subject') }}</label>
                                        <input type="text" placeholder="{{ __('Enter Your Subject') }}" class="form-control" name="subject" id="subject">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>{{ __('Message') }}</label>
                                        <textarea name="message" class="form-control" placeholder="{{ __('Message') }}" id="message"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-btn">
                                        <button type="submit" class="f-right basicbtn">{{ __('Send Message') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- contact area end -->

@endsection
@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/form.js') }}"></script>
@endpush
