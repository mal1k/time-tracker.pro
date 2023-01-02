@extends('layouts.backend.app')

@section('title','User Profile')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'User Profile'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          @if (Session::has('message'))
              <div class="alert alert-danger">
                {{ Session::get('message') }}
              </div>
          @endif
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('Total Spent') }}</h4>
                        </div>
                        <div class="card-body" id="total_spent">
                            <div class="loader project-stats">
                                <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                      <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('Total Groups') }}</h4>
                        </div>
                        <div class="card-body" id="total_group">
                            <div class="loader project-stats">
                                <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('Total Members') }}</h4>
                        </div>
                        <div class="card-body" id="total_members">
                            <div class="loader project-stats">
                                <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                  <div class="card-icon bg-info">
                      <i class="fas fa-circle"></i>
                  </div>
                  <div class="card-wrap">
                      <div class="card-header">
                          <h4>{{ __('Total Orders') }}</h4>
                      </div>
                      <div class="card-body" id="total_orders">
                          <div class="loader project-stats">
                              <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                          </div>
                      </div>
                  </div>
              </div>
          </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('Total Storage Used') }}</h4>
                        </div>
                        <div class="card-body" id="storage_used">
                            <div class="loader project-stats">
                                <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-hdd"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('Storage') }}</h4>
                        </div>
                        <div class="card-body" id="storage">
                            <div class="loader project-stats">
                                <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                  <div class="card-icon bg-danger">
                    <i class="fas fa-user-friends"></i>
                  </div>
                  <div class="card-wrap">
                      <div class="card-header">
                          <h4>{{ __('Team') }}</h4>
                      </div>
                      <div class="card-body" id="team">
                          <div class="loader project-stats">
                              <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
            <div class="row">
            <div class="table-responsive col-md-12 mb-2">
                <table class="table table-striped" id="table-2">
                    <tr>
                        <td ><img class="profile-img" src="{{ $user->avatar != null ? asset($user->avatar) : 'https://ui-avatars.com/api/?background=random&name='.$user->name }}" alt=""></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>{{ __('Name') }}</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                      <td>{{ __('Email') }}</td>
                      <td>{{ $user->email }}</td>
                    </tr>

                    <tr>
                        <td>{{ __('Active Plan') }}</td>
                        <td>{{ $user->plan->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Joined On') }}</td>
                        <td>{{ $user->created_at->isoFormat('LL') }}</td>
                    </tr>
                    <tr>
                        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">{{ __('Email Send') }}</button></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="table-responsive col-md-12">
                <h4>{{ __('Orders') }}</h4>
                <table class="table table-striped" id="table-2">
                    <thead>
                      <tr>
                        <th>{{ __('Plan') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th>{{ __('View') }}</th>
                      </tr>
                    </thead>
                    <tbody id="order_list">
                    </tbody>
                  </table>
                </div>
            </div>      
        </div>
      </div>
    </div>
  </div>
  <!-- Model start -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Send Email message') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('admin.user.mail', $user->id) }}" class="basicform">
            @csrf
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">{{ __('Subject') }}</label>
              <input type="text" class="form-control" id="recipient-name" name="subject">
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">{{ __('Message') }}</label>
              <textarea name="msg" class="form-control" id="message-text"></textarea>
            </div>
            <button type="submit" class="btn btn-primary basicbtn">{{ __('Send message') }}</button>
          </form>
        </div>
      </div>
    </div>
  </div> 
<input type="hidden" id="user_id" value="{{ $user->id }}">
<input type="hidden" id="user_details" value="{{ route('admin.user.details') }}">
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/user_view.js') }}"></script>
@endpush
