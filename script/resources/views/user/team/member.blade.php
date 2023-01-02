@extends('layouts.backend.app')

@section('title','Team Members')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Team Members'])
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
              <form action="{{ route('user.team.member.search') }}" method="get">
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
          <div class="col-md-8 text-right">
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
                    <th scope="col">{{ __('User Email') }}</th>
                    <th scope="col">{{ __('User Name') }}</th>
                    <th scope="col">{{ __('Hourly Rate') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                  </tr>
                </thead>
                  <tbody>
                  @foreach ($members ?? [] as $key => $member)
                    <tr>
                      <th scope="row">{{$key+1}}</th>
                      <td>{{ $member->user->email}}</td>
                      <td>{{ $member->user->name }}</td>
                      <td>{{ $member->h_rate}}</td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          {{ __('Action') }}
                          </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item has-icon" href="{{ route('user.team.edit', $member->id) }}"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
                          <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $member->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                          <!-- Delete Form -->
                          <form class="d-none" id="delete_form_{{ $member->id }}" action="{{ route('user.team.delete', $member->id) }}" method="POST">
                          @csrf
                          </form>
                        </div>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
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


