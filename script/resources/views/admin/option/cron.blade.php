@extends('layouts.backend.app')

@section('title','Cron Jobs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('Cron Jobs Settings') }}</h4>
            </div>
            <form method="POST" action="{{ route('admin.option.update', $option->key) }}" class="basicform">
                @csrf
                @php 
                $option = json_decode($option->value);
                @endphp
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-12">
                                <label>{{ __('Make Alert To Customer The Subscription Will Ending Soon') }}</label>   
                                <input placeholder="Enter number of days" class="form-control @error('days') is-invalid @enderror" name="days" type="text" value="{{ $option->days }}">
                                @error('days') 
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>{{ __('Assign Default Plan') }}</label>
                                <select name="assign_default_plan" class="form-control">
                                    <option value="on" {{ $option->assign_default_plan == 'on' ? 'selected' : '' }}>{{ __('ON') }}</option>
                                    <option value="off" {{ $option->assign_default_plan == 'off' ? 'selected' : '' }}>{{ __('OFF') }}</option>
                                </select>
                                @error('assign_default_plan') 
                                {{ $message }}
                                @enderror
                            </div> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Alert message') }}</label>
                        <textarea class="form-control @error('alert_message') is-invalid @enderror" name="alert_message" cols="30" rows="10">{{ $option->alert_message }}</textarea>
                        @error('alert_message') 
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Expire message') }}</label>
                        <textarea class="form-control @error('expire_message') is-invalid @enderror" name="expire_message" cols="30" rows="10">{{ $option->expire_message }}</textarea>
                        @error('expire_message') 
                        {{ $message }}
                        @enderror
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-circle"></i>Make Expired Membership <code>Once/day</code></h4>
            </div>
            <div class="card-body">
                <div class="code"><p>curl -s {{ route('alert.after.order.expired') }}</p></div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-circle"></i>Make Alert To The Customer Before Expired The Membership <code>Once/day</code></h4>
            </div>
            <div class="card-body">
                <div class="code"><p>curl -s {{ route('alert.before.order.expired') }}</p></div>
            </div>
        </div>
    </div>
    
</div>
@endsection

