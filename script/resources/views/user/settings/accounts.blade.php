@extends('layouts.backend.app')

@section('title','Workspace Settings')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Workspace Settings'])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
          <div class="row mb-4">
            <div class="col-lg-12">
              @include('layouts.frontend.partials.settingshead')
            </div>
            <div class="col-lg-12">
            @if(Session::has('message'))
              <p class="alert alert-danger">
                {{ Session::get('message') }}
              </p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


