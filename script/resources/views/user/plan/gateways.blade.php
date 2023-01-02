@extends('layouts.backend.app')

@section('title','Select Payment Getway')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Select Payment Getway'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
         <div class="">
            <div class="">
                <div class="row">
                    <div class="col-lg-12">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="card w-100">
                            <ul class="nav nav-pills mx-auto getwaycard" id="myTab3" role="tablist">
                                @foreach ($gateways as $key => $gateway)
                                <li class="nav-item">
                                    <a class="nav-link {{ $key == 0 ? 'active' : '' }}" id="getway-tab{{ $gateway->id }}" data-toggle="tab" href="#getway{{ $gateway->id }}" role="tab" aria-controls="home" aria-selected="true">
                                        <div class="card-body">
                                            @if ($gateway->logo)
                                            <img src="{{ asset($gateway->logo) }}" alt="{{ $gateway->name }}" width="100">
                                            @else 
                                                {{ $gateway->name }}
                                            @endif
                                           
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="card-footer">
                                <div class="tab-content" id="myTabContent2">
                                @foreach ($gateways as $key => $gateway)
                                @php $data = json_decode($gateway->data) @endphp
                                    <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="getway{{ $gateway->id }}" role="tabpanel" aria-labelledby="getway-tab{{ $gateway->id }}">
                                        <div class="">
                                            <table class="table">
                                                <tr>
                                                    <td>{{ __('Amount') }}</td>
                                                    <td>{{ $plan->price }}</td>
                                                </tr>
                                                <tr>
                                                <td>{{ __('Tax') }}</td>
                                                <td>{{ (($plan->price / 100) * $tax->value), 2 }} ({{ $tax->value }} %) </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Rate') }}</td>
                                                    <td>{{ $gateway->rate }}</td>
                                                </tr>
                                                <tr>
                                                <td>{{ __('Total (Including Tax)') }}</td>
                                                <td>{{ $withTax = (round($plan->price + (($plan->price / 100) * $tax->value), 2) * $gateway->rate) + $gateway->charge }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Currency') }}</td>
                                                    <td>{{ $gateway->currency ?? '' }}</td>
                                                </tr>
                                            
                                                <tr>
                                                    <td>{{ __('Total') }}
                                                        ({{ $gateway->currency }})
                                                    </td>
                                                    <td>{{ $withTax }}</td>
                                                </tr>

                                                @if (isset($data->bank_name))
                                                
                                                <tr>
                                                    <td>{{ __('Bank Name') }}</td>
                                                    <td><b>{{ $data->bank_name }}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Branch Name') }}</td>
                                                    <td><b>{{ $data->branch_name }}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Account Holder') }}</td>
                                                    <td><b>{{ $data->account_holder_name }}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Account Number') }}</td>
                                                    <td><b>{{ $data->account_number }}</b></td>
                                                </tr>
                                                @endisset
                                            </table>
                                        </div>
                
                                        <form action="{{ route('user.plan.deposit') }}" method="post" id="payment-form" class="paymentform" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $gateway->id }}">
                                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                            <div class="form-row">
                                                @if ($gateway->phone_status == 1)
                                                    <table class="table">
                                                        <tr>
                                                            <td><label for="">Phone</label></td>
                                                            <td>
                                                                <input type="text" class="form-control" name="phone"
                                                                    required
                                                                    {{ Session::has('phone') ? 'is-invalid' : '' }}>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                @endif
                                                @if ($gateway->id == 10)
                                                    <table class="table">
                                                        <tr>
                                                            <td><label for="">{{ __('Screenshot') }}</label></td>
                                                            <td>
                                                                <input type="file" class="form-control" name="screenshot"
                                                                    required
                                                                    {{ Session::has('screenshot') ? 'is-invalid' : '' }}>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="">{{ __('Comment') }}</label></td>
                                                            <td>
                                                                <textarea class="form-control" name="comment" required id="" cols="30" rows="10"></textarea>
                                                                {{ Session::has('comment') ? 'is-invalid' : '' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                @endif
                                                <button type="submit" class="btn btn-primary mt-4 w-100 btn-lg" id="submit_btn">{{ __('Submit Payment') }}</button>
                                            </div>
                                        </form>
                                       </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    "use strict";
    $('#submit_btn').on('click', function(e) {
        $('#submit_btn').attr("disabled", "disabled");
        $('#submit_btn').text("Please wait...");
    });
</script>
@endpush

