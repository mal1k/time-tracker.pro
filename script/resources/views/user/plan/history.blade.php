@extends('layouts.backend.app')

@section('title','Plan Payment history')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Plan Payment history'])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('type') ?? '' }}">
          {{ Session::get('message') }}
        </div>
        @endif
        <div class="row">
          <div class="offset-4 col-md-4 text-right">
            <div class="card-header-action"> 
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>   
@endsection
