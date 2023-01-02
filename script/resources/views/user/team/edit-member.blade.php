@extends('layouts.backend.app')

@section('title','Edit Member')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Edit Member'])
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
          <div class="col-lg-8">
            @include('layouts.frontend.partials.teamhead')
          </div>
          <div class="col-md-4 text-right">
            <h3>
              <a href="{{route('user.team.member')}}" class="btn btn-primary">{{ __('Member List') }}</a>
            </h3>
          </div>
          </div>
            <form method="POST" action="{{route('user.team.update', $edit->id)}}" class="basicform">
              @csrf
              <div class="form-group col-md-8 offset-2">
                  <label for="email">{{ __('User Email') }}</label>
                  <input type="text" name="email" id="email" class="form-control" value="{{$edit->user->email}}">
                  <font style="color: red">
                      {{($errors->has('email'))?($errors->first('email')):""}}
                  </font>
              </div>
              <div class="form-group col-md-8 offset-2">
                  <label for="hourly_rate">{{ __('Hourly Rate') }}</label>
                  <input type="number" name="hourly_rate" id="hourly_rate" class="form-control" value="{{$edit->h_rate}}">
                  <font style="color: red">
                      {{($errors->has('hourly_rate'))?($errors->first('hourly_rate')):""}}
                  </font>
              </div>
              <div class="form-group col-md-8 offset-2">
                  <input type="submit" class="btn btn-sm btn-primary basicbtn" value="Update">
              </div>
            </form>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>  
@endsection

@push('js')
  <script src="{{ asset('backend/admin/assets/js/jquery.selectric.min.js') }}"></script>
@endpush