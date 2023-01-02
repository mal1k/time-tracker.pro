@extends('layouts.backend.app')

@section('title','Reports')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/daterangepicker.css') }}">
@endpush

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Report'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-9">
                        <h5>{{ __('Statistics') }}</h5>
                    </div>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control daterange-cus" name="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Earning') }}</h4>
                                </div>
                                <div class="card-body" id="total_earning">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('This Week Earning') }}</h4>
                                </div>
                                <div class="card-body" id="weekly_earning">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('This Month Earning') }}</h4>
                                </div>
                                <div class="card-body" id="monthly_earning">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('This Year Earning') }}</h4>
                                </div>
                                <div class="card-body" id="yearly_earning">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Sales') }}</h4>
                                </div>
                                <div class="card-body" id="total_sales">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Active Orders') }}</h4>
                                </div>
                                <div class="card-body" id="total_active">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Expired Orders') }}</h4>
                                </div>
                                <div class="card-body" id="total_expired">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Cancelled Orders') }}</h4>
                                </div>
                                <div class="card-body" id="total_cancelled">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Users') }}</h4>
                                </div>
                                <div class="card-body" id="total_users">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Tax') }}</h4>
                                </div>
                                <div class="card-body" id="tax">
                                    <div class="loader project-stats">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ __('Order List') }}</h5>
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <div class="table-responsive" id="order-list">
                    <table class="table table-striped" id="table-2">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Plan') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Tax') }}</th>
                                <th>{{ __('Payment ID') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('View') }}</th>
                            </tr>
                        </thead>
                        <tbody id="orders">
                            <tr>
                                <td colspan="7">
                                    <div class="loader text-center">
                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>  
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="report_data" value="{{ route('admin.report.data') }}">
@endsection

@push('js')
    <script src="{{ asset('backend/admin/assets/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/js/admin_report.js') }}"></script>
@endpush
