@extends('layouts.backend.app')

@section('title','Clients')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Clients'])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <form method="POST" action="">
             <div class="input-group">
                 <select class="form-control" name="type">
                    <option value="user_name">{{ __('Show Active') }}</option>
                    <option value="trx">{{ __('Team') }}</option>
                 </select>
                 <div class="form-group">
                  <div class="input-group">
                    <input type="text" name="daterange" id="daterange" class="form-control" value="">
                  </div>
                </div>
              </div>
           </form>
          </div>
          <div class="offset-4 col-md-4 text-right">
              <div class="card-header-action">
                <form>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search">
                    <div class="input-group-btn">
                      <button class="btn btn-primary h-100">{{ __('Add') }}</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
