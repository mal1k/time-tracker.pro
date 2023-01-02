@extends('layouts.backend.app')

@section('title','Plans')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Plans','button_name'=>'Add
    Plan','button_link'=>route('admin.plan.create')])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('All Plans') }}</h4>
            </div>
            <div class="card-body">
                @if (Session::has('message'))
                    <div class="alert alert-warning">{{ Session::get('message') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped" id="table-2">
                        <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Total Orders') }}</th>
                                <th>{{ __('Total Sales Of Amount') }}</th>
                                <th>{{ __('Featured') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Last Update') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plans as $plan)
                                <tr>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ $plan->price }}</td>
                                    <td>{{ $plan->orders_count }}</td>
                                    <td>{{ $plan->orders_sum_amount ?? 0.00 }}</td>
                                    @if ($plan->is_featured == 1)
                                        <td><span class="badge badge-success">{{ __('Yes') }}</span></td>
                                    @endif
                                    @if ($plan->is_featured == 0)
                                        <td><span class="badge badge-danger">{{ __('No') }}</span></td>
                                    @endif

                                    <td><span
                                            class="badge badge-{{ $plan->status ? 'success' : 'danger' }}">{{ $plan->status ? 'Active' : 'Inactive' }}</span>
                                    </td>
                                    <td>{{ date('d-m-Y', strtotime($plan->created_at)) }}</td>
                                    <td>
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item has-icon"
                                                    href="{{ route('admin.plan.edit', $plan->id) }}"><i
                                                        class="fa fa-edit"></i>{{ __('edit') }}</a>
                                                <a class="dropdown-item has-icon delete-confirm"
                                                    href="javascript:void(0)" data-id={{ $plan->id }}><i
                                                        class="fa fa-trash"></i>{{ __('Delete') }}</a>
                                                <!-- Delete Form -->
                                                <form class="d-none" id="delete_form_{{ $plan->id }}"
                                                    action="{{ route('admin.plan.destroy', $plan->id) }}"
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
                    {{ $plans->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

