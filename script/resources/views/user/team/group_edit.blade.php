@extends('layouts.backend.app')

@section('title','Edit Group')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Edit Group'])
@endsection

@push('beforestyle.css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/selectric.css') }}">
@endpush

@section('content')
<div class="row">
  <div class="col-12 col-sm-12 col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="offset-3 col-md-6">
            <form class="basicform_with_reload" action="{{route('user.group.update', $group->id)}}" method="POST">
                @csrf
                @method('put')
                <div class="form-group">
                  <label for="">{{ __('Group Name') }}</label>
                  <input type="text" name="group" value="{{ $group->name }}" class="form-control" placeholder="Group Name">
                </div>
                <p>{{ __('Add Group Members') }}</p>
                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th>
                        <div class="custom-checkbox custom-control">
                            <input type="checkbox" data-checkboxes="group_members" data-checkbox-role="dad" class="custom-control-input parent" id="checkbox-all">
                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                        </div>
                        </th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Hourly Rate') }}</th>
                    </tr>
                    </thead>
                    @foreach ($members as $key => $member)
                        @php $select = 0 @endphp 
                        @foreach ($group->members as $groupmember)
                            @if ($groupmember->user_id == $member->user->id)
                                @php $select = 1 @endphp 
                            @endif
                        @endforeach
                    <tbody>
                    <tr>
                        <td>
                        <div class="custom-checkbox custom-control">
                          <input {{ $select ? 'checked' : '' }} type="checkbox" name="group_members[{{ $member->user->id }}]" data-checkboxes="group_members" class="custom-control-input child" id="checkbox-1{{ $member->user->id }}">
                            <label for="checkbox-1{{ $member->user->id }}" class="custom-control-label">&nbsp;</label>
                        </div>
                        </td>
                        <td>{{$member->user->email}}</td>
                        <td>{{$member->user->name}}</td>
                        <td>{{$member->h_rate}}</td>
                    </tr>
                  </tbody>
                @endforeach
              </table>
            <button type="submit" class="btn btn-primary basicbtn btn-block">{{ __('Update') }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>  
@endsection

@push('js')
  <script src="{{ asset('backend/admin/assets/js/jquery.selectric.min.js') }}"></script>
  <script src="{{ asset('backend/admin/assets/js/page/components-table.js') }}"></script>
@endpush