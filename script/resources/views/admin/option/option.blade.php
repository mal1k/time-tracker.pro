@extends('layouts.backend.app')

@section('title','Edit Option')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Option'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="col-12">
                    <h4>{{ __('Edit Option') }}</h4> 
                </div>
            </div>
            <div class="card-body">
            <form method="POST" action="{{ route('admin.option.update', 'all') }}" class="basicform"
            enctype="multipart/form-data">
                @csrf
                <div class="form-group row mb-4">
                    <label
                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Auto Order Approve') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select name="auto_enroll_after_payment" class="form-control">
                            <option value="on" @if ($auto_enroll_after_payment->value == 'on') selected="" @endif>{{ __('On') }}</option>
                            <option value="off" @if ($auto_enroll_after_payment->value == 'off') selected="" @endif>{{ __('Off') }}</option>
                        </select>
                        <small>{{ __('Automatic Order Approved After Payment Success') }}</small>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Currency') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" name="currency" class="form-control" value="{{ $currency->value }}">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Currency Icon') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" name="currency_icon" class="form-control"
                            value="{{ option('currency_icon') }}">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Tax') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" name="tax" class="form-control" value="{{ $tax->value }}">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Invoice Prefix') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" name="invoice_prefix" class="form-control"
                            value="{{ $invoice_prefix->value }}">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Support Email') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" name="support_email" class="form-control"
                            value="{{ $support_email->value }}">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Dark Logo') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="file" name="logo" accept="image/*" class="form-control">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                    <div class="col-sm-12 col-md-7">
                        <button type="submit" class="btn btn-primary basicbtn">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
