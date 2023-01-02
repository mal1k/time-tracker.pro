@extends('layouts.backend.app')

@section('title','Payment Gateway List')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Payment Gateway List'])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="table-2">
            <thead>
              <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Logo') }}</th>
                <th>{{ __('Last Update') }}</th>
                <th>{{ __('Action') }}</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($gateways as $gateway)   
                <tr>
                <td>{{ $gateway->name }}</td>
                <td class="align-middle"> 
                    <img width="80" src="{{ asset($gateway->logo) }}" alt="{{ $gateway->logo }}">
                </td>
                <td>{{ date('d-m-Y', strtotime($gateway->updated_at)) }}</td>
                <td>
                  <div class="dropdown d-inline">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>            
                    <div class="dropdown-menu">
                      <a class="dropdown-item has-icon" href="{{ route('admin.paymentgateway.edit', $gateway->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="float-right">
            {{ $gateways->links('vendor.pagination.bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
