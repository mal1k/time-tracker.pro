@extends('layouts.backend.app')

@section('title','Users')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'All Users','button_name'=>'Add User','button_link'=>route('admin.user.create')])
@endsection

@section('content')
  <div class="row">
        <div class="col-12">
            <div class="card mb-0">
                <div class="card-body">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{ $string == 'all' || $string == '' ? 'active' : '' }}" href="{{ route('admin.user.index') }}">{{ __('All') }} <span class="badge 
                              {{ $string == 'all' || $string == '' ? 'badge-white' : 'badge-primary' }}">{{ $all }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $string == '1' ? 'active' : '' }}" href="{{ route('admin.user.index','status='. 1) }}">{{ __('Active users') }} <span class="badge {{ $string == '1' ? 'badge-white' : 'badge-primary' }}">{{ $active_users }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $string == '0' ? 'active' : '' }}" href="{{ route('admin.user.index','status='. 0) }}">{{ __('Active users') }} <span class="badge {{ $string == '0' ? 'badge-white' : 'badge-primary' }}">{{ $deactive_users }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $string == '2' ? 'active' : '' }}" href="{{ route('admin.user.index','status='. 2) }}">{{ __('Trash') }} <span class="badge {{ $string == '2' ? 'badge-white' : 'badge-primary' }}">{{ $trash }}</span></a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                 @if (Session::has('success'))
                 <div class="card-header">                 
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>                   
                </div>
                 @endif
                <div class="card-body">
                    <div class="float-right">
                        <form>
                            <div class="input-group">
                                <input type="text" value="{{ $request->q ?? '' }}" class="form-control" placeholder="Search" name="q">
                                <select name="filter" id="filter" class="form-control selectric">
                                    <option value="name">{{ __('User Name') }}</option>
                                    <option value="email">{{ __('User Email') }}</option>
                                    <option value="id">{{ __('User Id') }}</option>
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <form id="status_form" action="{{ route('admin.users.status') }}" method="post">
                      @csrf
                    <div class="float-left">
                        <select id="status" name="status" class="form-control selectric">
                            <option value="">{{ __('Action For Selected') }}</option>
                            <option value="1">{{ __('Move To Active') }}</option>
                            <option value="0">{{ __('Move To Deactivate') }}</option>
                            @if($string != 2)
                            <option value="2">{{ __('Move To Trash') }}</option>
                            @endif
                            @if($string == 2)
                            <option value="3">{{ __('Delete Permanently') }}</option>
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
                                    <th>{{ __('Avatar') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @foreach ($users as $row)
                                    <tr>
                                        <td>
                                            <div class="custom-checkbox custom-control">
                                                <input name="ids[]" value="{{ $row->id }}" type="checkbox" data-checkboxes="mygroup"
                                                    class="custom-control-input" id="checkbox-{{ $row->id }}">
                                                <label for="checkbox-{{ $row->id }}" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td>
                                            <img class="rounded-circle w-25" src="{{ $row->avatar == '' ? 'https://ui-avatars.com/api/?name=' . $row->name : asset($row->avatar) }}" alt="">
                                        </td>
                                        <td>{{ $row->name }}</td>
                                        <td>
                                            {{ $row->email }}
                                        </td>
                                        @if ($row->status == 1)
                                            <td><div class="badge badge-success">{{ __('Active') }}</div></td>
                                        @elseif ($row->status == 0)
                                            <td><div class="badge badge-danger">{{ __('Disabled') }}</div></td>
                                        @else
                                            <td><div class="badge badge-danger">{{ __('Trash') }}</div></td>
                                        @endif
                                        <td>
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    {{ __('Action') }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item has-icon"
                                                        href="{{ route('admin.user.show', $row->id) }}"><i
                                                            class="fa fa-eye"></i>{{ __('View') }}</a>
                                                    <a class="dropdown-item has-icon"
                                                        href="{{ route('admin.user.edit', $row->id) }}"><i
                                                            class="fa fa-edit"></i>{{ __('edit') }}</a>
                                                    <a class="dropdown-item has-icon"
                                                        href="{{ route('admin.user.editplan', $row->id) }}"><i
                                                            class="fa fa-edit"></i>{{ __('Edit Plan') }}</a>
                                                    <a class="dropdown-item has-icon" href="{{ route('admin.user.login', $row->id) }}"><i class="fa fa-user"></i>{{ __('Login') }}</a>
                                                    <a class="dropdown-item has-icon delete-confirm"
                                                        href="javascript:void(0)" data-id={{ $row->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $users->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </form>
                @foreach ($users as $row)
                <form class="d-none" id="delete_form_{{ $row->id }}"
                    action="{{ route('admin.user.destroy', $row->id) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')
                </form>
                @endforeach
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
