@extends('layouts.backend.app')

@section('title','All Attachments')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'All Attachments'])
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/daterangepicker.css') }}">
@endpush

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
        <div class="col-lg-12">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">{{ __('Sl.') }}</th>
                <th scope="col">{{ __('Uploaded By') }}</th>
                <th scope="col">{{ __('Date') }}</th>
                <th scope="col">{{ __('Attachment') }}</th>
              </tr>
            </thead>
            @foreach ($data ?? [] as $item)
              <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->user }}</td>
                  <td>{{ $item->date }}</td>
                  <td><a href="{{ asset($item->attachment) }}"><img class="image-thumbnail" src="{{ asset($item->attachment) }}"></a></td>
              </tr>
            @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection



