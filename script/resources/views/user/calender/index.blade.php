@extends('layouts.backend.app')

@section('title','Calender')

@push('beforestyle.css')
<link rel="stylesheet" href="{{ asset('backend/admin/assets/css/fullcalendar.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/admin/assets/css/daterangepicker.css') }}">
@endpush

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Calender'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="fc-overflow">
                  <input type="hidden" name="renderTaskRoute" value="{{ route('user.calender.renderTask') }}">
                  <input type="hidden" name="renderModalTaskRoute" value="{{ route("user.task.modal.data") }}">
                  <input type="hidden" name="baseUrl" value="{{ url('/') }}">
                  <input type="hidden" name="addCommentUrl" value="{{ route("user.task.addCommentOnTask") }}">
                  <div id="myEvent"></div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mb-2"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <ul class="nav nav-pills justify-content-center task-nav" id="myTab3" role="tablist">
          <li class="nav-item">
            <a class="nav-link active show" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true">{{ __('Comments') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact-tab3" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact" aria-selected="false">{{ __('Attachment') }}</a>
          </li>
        </ul>
        <div class="tab-content p-3" id="myTabContent2">
          <div class="tab-pane fade active show" id="home3" role="tabpanel" aria-labelledby="home-tab3">
            <div class="comment_box mb-3">
              <form class="commentForm" enctype="multipart/form-data">
                <input type="hidden" name="task_id" value="">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="project_id" value="">
                <textarea name="comment" class="form-control comment"></textarea>
                <div class="submittaskform">
                  <div>
                    <button type="submit" class="btn btn-primary">
                      <i class="fas fa-paper-plane"></i> {{ __('Send') }}
                    </button>
                  </div>
                  <div class="imageIcon text-right">
                    <input class="d-none imageSelect" type="file" name="attachment" data-id="" id="file">
                    <label class="upload-logo" for="file">
                    <i class="fas fa-paperclip text-primary"></i>
                  </label>
                  </div>
                  <div class="imageshow" id="imageShow"></div>
                </div>
              </form>
              <div>
                <ul class="list-group all_comments">
                </ul>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
          </div>
          <div class="tab-pane fade" id="contact3" role="tabpanel" aria-labelledby="contact-tab3">
              <ul class="list-group all_attachments">
               </ul>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/fullcalendar.min.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/calendar.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/daterangepicker.min.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/daterange.js') }}"></script>
@endpush
