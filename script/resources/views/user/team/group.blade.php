@extends('layouts.backend.app')

@section('title','All Groups')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'All Groups'])
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
            <div class="form">
              <form action="{{ route('user.team.group.search') }}" method="get">
                <div class="input-group mb-2">
                    <input type="text" name="q" id="src" class="form-control" placeholder="Search..." required="" autocomplete="off" value="">
                    <select class="form-control" name="type" id="type">
                        <option value="group">{{ __('Group Name') }}</option>
                        <option value="member">{{ __('Member Email') }}</option>
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
              {{ __('Add Group') }}
            </button>
            <div class="modal fade text-left" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form action="{{route('user.group.store')}}" method="POST" class="basicform_with_reload">
                  @csrf
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">{{ __('Create Group') }}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="">{{ __('Group Name') }}</label>
                        <input type="text" name="group" class="form-control" placeholder="Group Name">
                      </div>
                      <p>{{ __('Add Group Members') }}</p>
                      <table class="table table-striped table-sm">
                        <thead>
                          <tr>
                            <th>
                              <div class="custom-checkbox custom-control">
                                <input type="checkbox" data-checkboxes="group_members" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                              </div>
                            </th>
                            <th scope="col">{{ __('Email') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Hourly Rate') }}</th>
                          </tr>
                        </thead>
                        @foreach ($members ?? [] as $key => $member)
                        <tbody>
                          <tr>
                            <td>
                              <div class="custom-checkbox custom-control">
                                <input type="checkbox" name="group_members[{{ $member->user->id }}]" data-checkboxes="group_members" class="custom-control-input" id="checkbox{{ $member->user->id }}">
                                <label for="checkbox{{ $member->user->id }}" class="custom-control-label">&nbsp;</label>
                              </div>
                            </td>
                            <td>{{$member->user->email}}</td>
                            <td>{{$member->user->name}}</td>
                            <td>{{$member->h_rate}}</td>
                          </tr>
                        </tbody>
                        @endforeach
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                      <button type="submit" class="btn btn-primary basicbtn">{{ __('Create') }}</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
           </button>
          </div>
          </div>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>{{ __('Sl.') }}</th>
                  <th>{{ __('Group Name') }}</th>
                  <th>{{ __('Members') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($groups as $key => $group)
                <tr>
                  <th scope="row">{{$key+1}}</th>
                  <td>{{ $group->name }}</td>
                  <td>@foreach ($group->groupmembers ?? [] as $memberkey => $member)
                  <img data-toggle="tooltip" data-original-title="{{ $member->name }}" alt="image" src="{{ $member->avatar != null ? asset($member->avatar) : 'https://ui-avatars.com/api/?background=random&name='.$member->name }}" class="rounded-circle" width="25" data-toggle="tooltip" title="" data-original-title="">
                  @php
                  if($memberkey+1 == 12){
                    $br=$group->groupmembers->count()-$memberkey.'+';
                  }
                  else{
                    continue;
                    $br='';
                  }                  
                  @endphp
                  @if($br != null)
                  {{ $br }}
                  @php
                  break;
                  @endphp
                  @endif
                  @endforeach
                </td>
                  <td>
                    <div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{ __('Action') }}
                      </button>
                     <div class="dropdown-menu">
                      <a class="dropdown-item has-icon" href="{{ route('user.group.edit', $group->id) }}"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
                      <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $group->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                      <!-- Delete Form -->
                      <form class="d-none" id="delete_form_{{ $group->id }}" action="{{ route('user.group.delete', $group->id) }}" method="POST">
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
@endsection

@push('js')
  <script src="{{ asset('backend/admin/assets/js/jquery.selectric.min.js') }}"></script>
  <script src="{{ asset('backend/admin/assets/js/page/components-table.js') }}"></script>
@endpush