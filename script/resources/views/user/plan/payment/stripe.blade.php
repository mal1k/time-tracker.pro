@extends('layouts.backend.app')

@section('title', 'Select Payment Getway')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Select Payment Getway'])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="px-4">
                        <table class="table">
                            <tr>
                                <td>{{ __('Amount') }} ({{ $Info['currency'] }})</td>
                                <td class="float-right">{{ $Info['amount'] }}</td>
                            </tr>
                        </table>
                        <form action="{{ url('user/stripe/payment') }}" method="post" id="payment-form"
                            class="paymentform p-4">
                            @csrf
                            <div class="form-row">
                                <label for="card-element">
                                    {{ __('Credit or debit card') }}
                                </label>
                                <div id="card-element">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <!-- Used to display form errors. -->
                                <div id="card-errors" role="alert"></div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 mt-4"
                                    id="submit_btn">{{ __('Submit Payment') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<input type="hidden" id="publishable_key" value="{{ $Info['publishable_key'] }}">

@push('js')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('backend/admin/assets/js/stripe.js') }}"></script>
@endpush
