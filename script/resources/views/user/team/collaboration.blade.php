@extends('layouts.backend.app')

@section('title','Collaboration')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Collaboration'])
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
        @if (Session::has('message'))
        <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif
        <div class="row">
          <div class="col-md-4">
            <div class="form">
              <form action="{{ route('user.team.collab.search') }}" method="get">
                <div class="input-group mb-2">
                    <input type="text" name="q" id="src" class="form-control" placeholder="Search..." required="" autocomplete="off" value="">
                    <select class="form-control" name="type" id="type">
                        <option value="username">{{ __('Search By Name') }}</option>
                        <option value="email">{{ __('Search By Email') }}</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
              </form>
            </div>
          </div>
          <div class="col-lg-8 text-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
              {{ __('Add Members') }}
            </button>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form class="basicform" action="{{route('user.team.store')}}" method="POST">
                  @csrf
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Members') }}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <input type="text" name="email" class="form-control" placeholder="please enter user email.."></br>
                      <input type="number" name="hourly_rate" class="form-control" placeholder="please enter hourly rate..">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                      <button type="submit" class="btn btn-primary basicbtn">{{ __('Save Members') }}</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
           </button>
          </div>
          <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">{{ __('ID') }}</th>
                    <th scope="col">{{ __('Creator Name') }}</th>
                    <th scope="col">{{ __('Creator Email') }}</th>
                    <th scope="col">{{ __('Hourly Rate') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                  </tr>
                </thead>
                @foreach ($collaborations ?? [] as $key => $collaboration)
                <tbody>
                  <tr>
                    <th scope="row">{{$key+1}}</th>
                    <td>{{ $collaboration->creator->name }}</td>
                    <td>{{ $collaboration->creator->email }}</td>
                    <td>{{ $collaboration->h_rate }}</td>
                    @php 
                        $status = [
                            0 => [ 'class' => 'badge-danger', 'text' => 'Inactive' ],
                            1 => [ 'class' => 'badge-primary', 'text' => 'Active' ],
                            2 => [ 'class' => 'badge-warning', 'text' => 'Pending' ],
                        ][$collaboration->status]
                    @endphp
                    <td><span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span></td>
                    <td>
                        <a class="btn btn-danger delete-confirm" href="javascript:void(0)" data-id={{ $collaboration->id }}><i class="fa fa-trash"></i>&nbsp;{{ __('Leave') }}</a>
                         <!-- Delete Form -->
                         <form class="d-none" id="delete_form_{{ $collaboration->id }}" action="{{ route('user.team.collaboration.delete', $collaboration->id) }}" method="POST">
                            @csrf
                        </form>
                      </div>
                    </td>
                  </tr>
                </tbody>
                @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>  
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/jquery.selectric.min.js') }}"></script>
<script>
  "use strict";
  function success(res){
    location.reload();
  }

  
</script> 
@endpush


