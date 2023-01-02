@extends('layouts.backend.app')

@section('title','Payment Gateway Edit')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
        <div class="card-header">
          <h4>{{ __('Payment Gateway Edit') }}</h4>
        </div>
        @if ($errors->any())
          <div class="alert alert-danger">
              <strong>{{ __('Whoops') }}!</strong> {{ __('There were some problems with your input') }}.<br><br>
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        @endif
        <form method="POST" action="{{ route('admin.paymentgateway.update', $gateway->id) }}" enctype="multipart/form-data" class="basicform_with_reload">
          @csrf
          @method('PUT')
          @php
              $info_data = json_decode($gateway->data);
          @endphp
          <div class="card-body">
            <div class="form-row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                      <label>{{ __('Name') }}</label>
                      <input type="text" class="form-control" placeholder="Name"  name="name" value="{{ $gateway->name }}">
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label>{{ __('Logo') }}</label>
                    <input type="file" class="form-control" name="logo">
                    <br>
                    <img width="100" src="{{ asset($gateway->logo) }}" alt="{{ $gateway->logo }}">
                  </div>
                </div>
            </div>
            <div class="form-row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                  <label>{{ __('Status') }}</label>
                  <select name="status" class="form-control">
                      <option value="1" {{ $gateway->status == 1 ? 'selected' : ''}}>{{ __('Active') }}</option>
                      <option value="0" {{ $gateway->status == 0 ? 'selected' : ''}}>{{ __('Inactive') }}</option>
                  </select>
                </div>
              </div>    
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                  <label>{{ __('Phone Status') }}</label>
                  <select name="phone_status" class="form-control">
                      <option value="1" {{ $gateway->phone_status == 1 ? 'selected' : ''}}>{{ __('Active') }}</option>
                      <option value="0" {{ $gateway->phone_status == 0 ? 'selected' : ''}}>{{ __('Inactive') }}</option>
                  </select>
                </div>
              </div>   
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                <label>{{ __('Namespace') }}</label>
                <input type="text" class="form-control" placeholder="Namespace" required name="namespace" value="{{ $gateway->namespace }}">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                <label>{{ __('Rate') }}</label>
                <input type="number" step="any" class="form-control" placeholder="Rate (1USD= ? USD)" required name="rate" value="{{ $gateway->rate }}">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                <label>{{ __('Charge') }}</label>
                <input type="number" step="any" class="form-control" placeholder="Charge" required name="charge" value="{{ $gateway->charge }}">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                <label>{{ __('Currency') }}</label>
                <input type="text" class="form-control" placeholder="Currency" required name="currency" value="{{ $gateway->currency }}">
                </div>
              </div>
            </div>
            <div class="form-row">
                @foreach ($info_data ?? [] as $key => $data)
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ ucwords(str_replace("_"," ",$key)) }}</label>
                      <input type="text" class="form-control" placeholder="" required name="data[{{$key}}]" value="{{ $data }}">
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row">
              <div class="col-lg-12">
                <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ __('Update') }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
