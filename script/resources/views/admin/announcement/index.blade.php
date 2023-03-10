@extends('layouts.backend.app')

@section('title','Announcements')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Announcement List','button_name'=> 'Add New','button_link'=> route('admin.announcement.create')])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                      <th>{{ __('Title') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($announcement as $row)

                    <tr>
                      <td>{{ $row->title }}</td>
                      @if($row->status == 1)
                      <td class="text-success">Active</td>
                      @endif
                      @if($row->status == 0)
                      <td class="text-danger">Inactive</td>
                      @endif
                      <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Action
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.announcement.edit', $row->id) }}"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $row->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $row->id }}" action="{{ route('admin.announcement.destroy', $row->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            </form>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $announcement->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection

