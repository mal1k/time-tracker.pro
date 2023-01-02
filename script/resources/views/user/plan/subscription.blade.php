@extends('layouts.backend.app')

@section('title','Select your plan')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Select your plan'])
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif
    </div>
    @foreach ($plans as $plan)
        <div class="col-12 col-md-4 col-lg-4">
            <div class="pricing {{ $plan->is_featured ? 'pricing-highlight' : '' }}">
                <div class="pricing-title">
                    {{ $plan->name }}
                </div>
                <div class="pricing-padding">
                    <div class="pricing-price">
                        <div>{{ App\Models\Option::where('key','currency_icon')->first()->value }}{{ $plan->price }}</div>
                        <div>
                            @if ($plan->duration == 7)
                                {{ __('Per Week') }}
                            @elseif($plan->duration == 30)
                                {{ __('Per Month') }}
                            @elseif($plan->duration == 365)
                                {{ __('Per Year') }}
                            @else
                                {{ $plan->duration }} {{ __('Days') }}
                            @endif
                        </div>
                    </div>
                    <div class="pricing-details">
                        <div class="pricing-item">
                            <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                            <div class="pricing-item-label">{{ $plan->storage_size }} {{ __('MB Storage limit') }}
                            </div>
                        </div>
                        <div class="pricing-item">
                            <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                            <div class="pricing-item-label">{{ $plan->user_limit }} {{ __('Users limit') }}</div>
                        </div>
                        <div class="pricing-item">
                            <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                            <div class="pricing-item-label">{{ $plan->project_limit }} {{ __('Project limit') }}
                            </div>
                        </div>

                        <div class="pricing-item">
                            <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                            <div class="pricing-item-label">{{ $plan->group_limit }} {{ __('Group limit') }}</div>
                        </div>
                        <div class="pricing-item">
                            <div class="pricing-item-icon {{ !$plan->gps ? 'bg-danger text-white' : '' }}">
                                <i class="fas fa-{{ $plan->gps ? 'check' : 'times' }}"></i>
                            </div>
                            <div class="pricing-item-label">
                                {{ __('Gps Tracking') }}
                            </div>
                        </div>

                        <div class="pricing-item">
                            <div class="pricing-item-icon {{ !$plan->screenshot ? 'bg-danger text-white' : '' }}">
                                <i class="fas fa-{{ $plan->screenshot ? 'check' : 'times' }}"></i>
                            </div>
                            <div class="pricing-item-label">
                                {{ __('Screenshot Capture') }}
                            </div>
                        </div>
                        <div class="pricing-item">
                            <div class="pricing-item-icon {{ !$plan->adminable_project ? 'bg-danger text-white' : '' }}">
                                <i class="fas fa-{{ $plan->adminable_project ? 'check' : 'times' }}"></i>
                            </div>
                            <div class="pricing-item-label">
                            {{ __('Multi Admin Accessible Project') }}</div>
                        </div>
                        <div class="pricing-item">
                            <div class="pricing-item-icon {{ !$plan->mail_activity ? 'bg-danger text-white' : '' }}">
                                <i class="fas fa-{{ $plan->mail_activity ? 'check' : 'times' }}"></i>
                            </div>
                            <div class="pricing-item-label">
                            {{ __('Mail Activity') }}</div>
                        </div>                         
                    </div>
                </div>
                <div class="pricing-cta">
                    <a href="{{ route('user.plan.gateways', $plan->id) }}">{{ $plan->is_trial == 0 ? __('Activate') : __('Free Trial') }}<i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="card">
    <div class="card-header">
        <h4>{{ __('Transactions History') }}</h4>
    </div>
    <div class="card-body">
        <table class="table mt-2">
            <thead>
                <tr>
                    <th>{{ __('Order No') }}</th>
                    <th>{{ __('Payment Id') }}</th>
                    <th>{{ __('Plan') }}</th>
                    <th>{{ __('Method') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Payment Status') }}</th>
                    <th>{{ __('Ordered At') }}</th>
                    <th>{{ __('Will Expire') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->invoice_id }}</td>
                        <td>{{ $order->payment_id }}</td>
                        <td>{{ $order->plan->name }}</td>
                        <td>{{ $order->getway->name }}</td>
                        <td>{{ $order->amount }}</td>
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

                        @php
                        $pstatus = [
                            0 => ['class' => 'badge-danger', 'text' => 'Cancelled'],
                            1 => ['class' => 'badge-primary', 'text' => 'Success'],
                            2 => ['class' => 'badge-danger', 'text' => 'Pending'],
                        ][$order->payment_status];
                        @endphp
                    <td>
                        <span class="badge {{ $pstatus['class'] }}">{{ $pstatus['text'] }}</span>
                    </td>
                        <td>{{ $order->created_at->isoFormat('LL') }}</td>
                        <td>{{ date('F d, Y', strtotime($order->will_expire)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endsection
