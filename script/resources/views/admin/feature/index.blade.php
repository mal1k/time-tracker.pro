@extends('layouts.backend.app')

@section('title','All Features')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Features List','button_name'=> 'Add New','button_link'=> route('admin.feature.create')])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if (Session::has('message'))
                    <div class="alert alert-warning">{{ Session::get('message') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped" id="table-2">
                        <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Icon') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($features ?? [] as $feature)
                                @php $info = json_decode($feature->featuremeta->value) @endphp
                                <tr>
                                    <td>{{ $feature->title }}</td>
                                    <td><img class="image-thumbnail" src="{{ asset($info->icon) }}" alt=""></td>
                                    @if ($feature->status == 1)
                                        <td><span class="badge badge-primary">{{ __('Success') }}</span></td>
                                    @endif
                                    @if ($feature->status == 0)
                                        <td><span class="badge badge-primary">{{ __('Inactive') }}</span></td>
                                    @endif
                                    <td>{{ date('d-m-Y', strtotime($feature->created_at)) }}</td>
                                    <td>
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item has-icon"
                                                    href="{{ route('admin.feature.edit', $feature->id) }}"><i
                                                        class="fa fa-edit"></i>{{ __('edit') }}</a>
                                                <a class="dropdown-item has-icon delete-confirm"
                                                    href="javascript:void(0)" data-id={{ $feature->id }}><i
                                                        class="fa fa-trash"></i>{{ __('Delete') }}</a>
                                                <!-- Delete Form -->
                                                <form class="d-none" id="delete_form_{{ $feature->id }}"
                                                    action="{{ route('admin.feature.destroy', $feature->id) }}"
                                                    method="POST">
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
                    {{ $features->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

