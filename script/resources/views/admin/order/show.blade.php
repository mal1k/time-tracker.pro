@extends('layouts.backend.app')

@section('title','Order View')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Order View'])
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
                <div class="table-responsive">
                    <table class="table table-striped" id="table-2">
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Details') }}</th>
                        </tr>
                        <tr>
                            <td>{{ __('Username') }}</td>
                            <td><a href="{{ route('admin.user.show', $order->user_id) }}">{{ $order->user->name }}</a></td>
                        </tr>
                        <tr>
                            <td>{{ __('Email') }}</td>
                            <td>{{ $order->user->email }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Invoice No') }}</td>
                            <td><a href="{{ route('admin.user.invoice', $order->id) }}">{{ $order->invoice_id }}</a></td>
                        </tr>
                        <tr>
                            <td>{{ __('Plan') }}</td>
                            <td>{{ $order->plan->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Gateway') }}</td>
                            <td>{{ $order->getway->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Tax') }}</td>
                            <td>{{ (($order->amount / 100) * $order->tax), 2 }} ({{ $order->tax }} %)</td>
                        </tr>
                        <tr>
                            <td>{{ __('Amount') }}</td>
                            <td>{{ $order->amount }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Transaction ID') }}</td>
                            <td>{{ $order->payment_id }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Status') }}</td>
                            @php
                            $status = [
                            0 => ['class' => 'badge-danger',  'text' => 'Rejected'],
                            1 => ['class' => 'badge-primary', 'text' => 'Accepted'],
                            2 => ['class' => 'badge-danger', 'text' => 'Expired'],
                            3 => ['class' => 'badge-warning', 'text' => 'Pending']
                            ][$order->status]
                            @endphp
                            <td>
                                <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            
                            <td>{{ __('Payment Status') }}</td>
                            @php
                            $pstatus = [
                                0 => ['class' => 'badge-danger', 'text' => 'Cancelled'],
                                1 => ['class' => 'badge-primary', 'text' => 'Success'],
                                2 => ['class' => 'badge-warning', 'text' => 'Pending'],
                            ][$order->payment_status];
                            @endphp
                            <td>
                                <span class="badge {{ $pstatus['class'] }}">{{ $pstatus['text'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Created At') }}</td>
                            <td>{{ $order->created_at->isoFormat('LL') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Expire At') }}</td>
                            <td class="text-danger"><strong>{{ \Carbon\Carbon::parse($order->will_expire)->isoFormat('LL') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

