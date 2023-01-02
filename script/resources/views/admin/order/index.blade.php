@extends('layouts.backend.app')

@section('title','All Orders')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Orders List','button_name'=>'Create
    Order','button_link'=>route('admin.order.create')])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-0">
                <div class="card-body">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{ $string == 'all' || $string == '' ? 'active' : '' }}" href="{{ route('admin.order.filter', 'all') }}">{{ __('All') }} <span class="badge 
                              {{ $string == 'all' || $string == '' ? 'badge-white' : 'badge-primary' }}">{{ $allorders }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $string == 'approved' ? 'active' : '' }}" href="{{ route('admin.order.filter', 'approved') }}">{{ __('Approved') }} <span class="badge {{ $string == 'approved' ? 'badge-white' : 'badge-primary' }}">{{ $approved }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $string == 'pending' ? 'active' : '' }}" href="{{ route('admin.order.filter', 'pending') }}">{{ __('Pending') }} <span class="badge {{ $string == 'pending' ? 'badge-white' : 'badge-primary' }}">{{ $pending }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $string == 'cancel' ? 'active' : '' }}" href="{{ route('admin.order.filter', 'cancel') }}">{{ __('Cancel') }} <span class="badge {{ $string == 'cancel' ? 'badge-white' : 'badge-primary' }}">{{ $cancelled }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $string == 'trash' ? 'active' : '' }}" href="{{ route('admin.order.filter', 'trash') }}">{{ __('Trash') }} <span class="badge {{ $string == 'trash' ? 'badge-white' : 'badge-primary' }}">{{ $trash }}</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        <form action="{{ route('admin.order.search') }}">
                            <div class="input-group">
                                <input type="text" value="{{ $request->q ?? '' }}" class="form-control" placeholder="Search" name="q">
                                <select name="filter" id="filter" class="form-control selectric">
                                    <option value="invoice">{{ __('Invoice No') }}</option>
                                    <option value="email">{{ __('User email') }}</option>
                                    <option value="payment_id">{{ __('Payment Id') }}</option>
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <form id="status_form" action="{{ route('admin.order.status') }}" method="post">
                      @csrf
                    <div class="float-left">
                        <select id="status" name="status" class="form-control selectric">
                            <option value="">{{ __('Action For Selected') }}</option>
                            <option value="3">{{ __('Move to Pending') }}</option>
                            <option value="0">{{ __('Move to cancel') }}</option>
                            @if($string != 'trash')
                            <option value="4">{{ __('Move to trash') }}</option>
                            @endif
                            @if($string == 'trash')
                            <option value="5">{{ __('Delete Pemanently') }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="clearfix mb-3"></div>
                    <div class="row">
                       <div class="col-md-12">
                        @if (Session::has('alert'))
                        <div class="alert alert-danger">
                            {{ Session::get('alert') }}
                        </div>
                        @endif
                       </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" 
                                                class="custom-control-input checkAll" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Payment Id') }}</th>
                                    <th>{{ __('Plan Name') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Order At') }}</th>
                                    <th>{{ __('Will Expire') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="custom-checkbox custom-control">
                                                <input name="ids[]" value="{{ $order->id }}" type="checkbox" data-checkboxes="mygroup"
                                                    class="custom-control-input" id="checkbox-{{ $order->id }}">
                                                <label for="checkbox-{{ $order->id }}" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </td>
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
                                        <td>
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item has-icon"
                                                        href="{{ route('admin.order.show', $order->id) }}"><i
                                                            class="fa fa-eye"></i>{{ __('View') }}</a>
                                                    <a class="dropdown-item has-icon"
                                                        href="{{ route('admin.order.edit', $order->id) }}"><i
                                                            class="fa fa-edit"></i>{{ __('Edit') }}</a>
                                                   
                                                    <a class="dropdown-item has-icon"
                                                        href="{{ route('admin.order.deactive', $order->id) }}"><i
                                                            class="fa fa-times"></i>{{ __('Deactive') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $orders->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<input type="hidden" id="type" value="{{ $request->filter ?? '' }}">
@endsection

@push('js')
<script>
    "use strict";
    
    var type=$('#type').val();
    if(type != ''){
        $('#filter').val(type)
    }
    $('#status').on('change',()=>{
        $('#status_form').submit();  
    })
  </script>
@endpush 

