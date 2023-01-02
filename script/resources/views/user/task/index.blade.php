@extends('layouts.backend.app')

@section('title','Assigned Tasks')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Assigned Tasks'])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-9 taskajax">
            <div class="loader d-flex justify-content-center">
              <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
            </div>
            <div id="error"></div>
            <div id="today" class="mb-2"></div>
            <div id="upcoming" class="mb-2"></div>
            <div id="completed" class="mb-2"></div>
            <div id="overdue" class="mb-2"></div>
            <div id="no_overdue" class="mb-2"></div>
            <div id="project"></div>
          </div>
          <div class="col-md-3">
            <ul class="nav nav-pills flex-column" role="tablist">
              <li class="nav-item filter">
                <a href="javascript:void(0)" class="nav-link active" data-type="status" data-filter="all">{{ __('Inbox') }}</a>
              </li> 
              <li class="nav-item filter">
                <a href="javascript:void(0)" class="nav-link" data-type="status" data-filter="today">{{ __('Today') }}</a>
              </li> 
              <li class="nav-item filter">
                <a href="javascript:void(0)" class="nav-link" data-type="status" data-filter="upcoming">{{ __('Upcoming') }}</a>
              </li> 
              <li class="nav-item filter">
                <a href="javascript:void(0)" class="nav-link" data-type="status" data-filter="overdue">{{ __('Overdue') }}</a>
              </li> 
              <li class="nav-item filter">
                <a href="javascript:void(0)" class="nav-link" data-type="status" data-filter="no_overdue">{{ __('No Overdue') }}</a>
              </li> 
              <li class="nav-item filter">
                <a href="javascript:void(0)" class="nav-link" data-type="status" data-filter="completed">{{ __('Completed') }}</a>
              </li> 
            </ul>
            <ul class="nav nav-pills flex-column pt-5" role="tablist">
              <li class="nav-item border-bottom mb-2">
                <h5>{{ __('Projects') }}</h5>
              </li>
              @foreach ($projects as $project)
              <li class="nav-item filter">
                <a href="javascript:void(0)" class="nav-link" data-type="project" data-filter="{{ $project->id }}">{{ $project->name }}</a>
              </li>
              @endforeach 
            </ul>
          </div>
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
<form class="d-none" id="taskStatusform" action="{{ route('user.project.update.task.status') }}" method="POST">
  @csrf
  <input type="hidden" value="" id="taskId" name="id">
  <input type="hidden" value="" id="project_id" name="project_id">
  <input type="hidden" value="1" name="status">
</form>

<input type="hidden" id="loadTask" value="{{ route("user.task.loadTasks") }}">
<input type="hidden" id="url" value="{{ url('/') }}">
<input type="hidden" id="task_data_get" value="{{ route("user.task.modal.data") }}">
<input type="hidden" id="add_comment_task" value="{{ route('user.task.addCommentOnTask') }}">
<input type="hidden" id="url_encode" value="{!! json_encode(url('/')) !!}">
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/task.js') }}"></script>
@endpush