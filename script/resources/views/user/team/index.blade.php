@extends('layouts.backend.app')

@section('title','Team')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Team'])
@endsection

@push('beforestyle.css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/selectric.css') }}">
@endpush

@section('content')
<div class="row">
  <div class="col-12 col-sm-12 col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="row mb-4">
          <div class="col-lg-12">
            @include('layouts.frontend.partials.teamhead')
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <form method="POST" action="">
             <div class="input-group">
                 <select class="form-control selectric" name="type">
                    <option value="user_name">{{ __('Only Me') }}</option>
                    <option value="trx">{{ __('Team') }}</option>
                 </select>
                 <div class="form-group">
                  <div class="input-group">
                    <input type="text" name="daterange" id="daterange" class="form-control" value="">
                  </div>
                </div>
              </div>
           </form>
          </div>
          <div class="col-md-8 text-right">
           <button class="btn btn-primary disabled">
             {{ __('Apply filter') }}
           </button>
          </div>
          </div>
      </div>
    </div>
  </div>
</div>  
@endsection

@push('js')
  <script src="{{ asset('backend/admin/assets/js/jquery.selectric.min.js') }}"></script>
@endpush