@extends('layouts.backend.app')

@section('title','Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-statistic-2">
        <div class="card-stats">
          <div class="card-stats-title">{{ __('Order Statistics') }} -
           <div class="dropdown d-inline">
            <a class="font-weight-600 dropdown-toggle" data-toggle="dropdown" href="#" id="orders-month" id="orders-month">{{ Date('F') }}</a>
            <ul class="dropdown-menu dropdown-menu-sm">
              <li class="dropdown-title">{{ __('Select Month') }}</li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='January') active @endif" data-month="January" >{{ __('January') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='February') active @endif" data-month="February" >{{ __('February') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='March') active @endif" data-month="March" >{{ __('March') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='April') active @endif" data-month="April" >{{ __('April') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='May') active @endif" data-month="May" >{{ __('May') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='June') active @endif" data-month="June" >{{ __('June') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='July') active @endif" data-month="July" >{{ __('July') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='August') active @endif" data-month="August" >{{ __('August') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='September') active @endif" data-month="September" >{{ __('September') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='October') active @endif" data-month="October" >{{ __('October') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='November') active @endif" data-month="November" >{{ __('November') }}</a></li>
              <li><a href="#" class="dropdown-item month @if(Date('F')=='December') active @endif" data-month="December" >{{ __('December') }}</a></li>
            </ul>
          </div>
          </div>
          <div class="card-stats-items">
            <div class="card-stats-item">
              <div class="card-stats-item-count" id="pending_order"><img src="{{ asset('frontend/assets/img/loader.gif') }}" height="40" alt=""></div>
              <div class="card-stats-item-label">{{ __('Pending') }}</div>
            </div>
            <div class="card-stats-item">
              <div class="card-stats-item-count" id="cancelled"><img src="{{ asset('frontend/assets/img/loader.gif') }}" height="40" alt=""></div>
              <div class="card-stats-item-label">{{ __('Cancelled') }}</div>
            </div>
            <div class="card-stats-item">
              <div class="card-stats-item-count" id="completed_order"><img src="{{ asset('frontend/assets/img/loader.gif') }}" height="40" alt=""></div>
              <div class="card-stats-item-label">{{ __('Completed') }}</div>
            </div>
          </div>
        </div>
        <div class="card-icon shadow-primary bg-primary">
          <i class="fas fa-archive"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>{{ __('Total Orders') }}</h4>
          </div>
          <div class="card-body" id="total_order">
            <img height="40" src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-statistic-2">
        <div class="card-chart">
          <canvas id="sales_of_earnings_chart" height="80"></canvas>
        </div>
        <div class="card-icon shadow-primary bg-primary">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>{{ __('Balance') }} - {{ date('Y') }}</h4>
          </div>
          <div class="card-body" id="balance">
            <img src="{{ asset('frontend/assets/img/loader.gif') }}" height="40" alt="">
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-statistic-2">
        <div class="card-chart">
          <canvas id="total-sales-chart" height="80"></canvas>
        </div>
        <div class="card-icon shadow-primary bg-primary">
          <i class="fas fa-shopping-bag"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>{{ __('Sales') }} - {{ date('Y') }}</h4>
          </div>
          <div class="card-body" id="sales">
            <img src="{{ asset('frontend/assets/img/loader.gif') }}" height="40" alt="">
          </div>
        </div>
      </div>
    </div> 
</div>   
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="row">
            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-header-title">{{ __('Earnings performance') }} <img src="{{ asset('frontend/assets/img/loader.gif') }}" height="40" id="earning_performance"></h4>
                        <div class="card-header-action">                            
                        <select class="form-control" id="perfomace">
                            <option value="7">{{ __('Last 7 Days') }}</option>
                            <option value="15">{{ __('Last 15 Days') }}</option>
                            <option value="30" selected>{{ __('Last 30 Da') }}ys</option>
                            <option value="365">{{ __('Last 365 Days') }}</option>
                        </select>
                        </div>
                    </div>
                    <div class="card-body">
                      <canvas id="earning_performance_chart" height="158"></canvas> 
                </div>
            </div>
        </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-12 col-12 col-sm-12">
    <div class="card mt-4">
      <div class="card-header">
        <h4>{{ __('Recent Orders') }}</h4>
      </div>
      <div class="card-body">
        <ul class="list-unstyled list-unstyled-border">
          @foreach($orders as $order)
          <li class="media">
            <a href="{{ route('admin.user.show',$order->user_id) }}"><img class="mr-3 rounded-circle" width="50" src="{{ asset(!empty($order->user->avatar) ? $order->user->avatar : 'https://ui-avatars.com/api/?name='.$order->user->name.'&background=random') }}" alt="avatar"></a>
            <div class="media-body">
              <div class="float-right text-primary mt-3"><a href="{{ url('/admin/order',$order->id) }}">{{ $order->created_at->diffForHumans() }}</a></div>
              <div class="media-title  mt-3"><a href="{{ route('admin.order.edit',$order->id) }}">{{ $order->invoice_id }}</a></div>
            </div>
          </li>
          @endforeach
        </ul>
        @if(count($orders) > 0)
        <div class="text-center pt-1 pb-1">
          <a href="{{ route('admin.order.index') }}" class="btn btn-primary btn-lg btn-round">
            {{ __('View All') }}
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Expired Orders') }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
              <thead>
                  <tr>
                     
                      <th>{{ __('Id') }}</th>
                      <th>{{ __('Payment Id') }}</th>
                      <th>{{ __('Plan Name') }}</th>
                      <th>{{ __('Amount') }}</th>
                      <th>{{ __('User') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Order At') }}</th>
                      <th>{{ __('Will Expire') }}</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($expired_orders as $order)
                      <tr>
                         
                          <td><a href="{{ route('admin.user.invoice', $order->id) }}">{{ $order->invoice_id }}</a>
                          </td>
                          <td>{{ $order->payment_id }}</td>
                          <td>{{ $order->plan->name }}</td>
                          <td>{{ $order->amount }}</td>
                          <td><a href="{{ route('admin.user.show', $order->user->id) }}">{{ $order->user->name }}</a>
                          </td>
                          @php
                              $status = [
                                  0 => ['class' => 'badge-danger', 'text' => 'Rejected'],
                                  1 => ['class' => 'badge-primary', 'text' => 'Accepted'],
                                  2 => ['class' => 'badge-danger', 'text' => 'Expired'],
                                  3 => ['class' => 'badge-warning', 'text' => 'Pending'],
                                  4 => ['class' => 'badge-danger', 'text' => 'Trash'],
                              ][$order->status];
                          @endphp
                          <td>
                              <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                          </td>
                          <td>{{ date('Y-m-d', strtotime($order->created_at)) }}</td>
                          <td>{{ $order->will_expire }}</td>
                      </tr>
                  @endforeach
              </tbody>
          </table>
      </div>
      </div>
    </div>
</div>
</div>
<input type="hidden" id="base_url" value="{{ url('/') }}">
<input type="hidden" id="month" value="{{ date('F') }}">
<input type="hidden" id="gif_url" value="{{ asset('frontend/assets/img/loader.gif') }}">
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/chart.min.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/admin_dashboard.js') }}"></script>
@endpush
