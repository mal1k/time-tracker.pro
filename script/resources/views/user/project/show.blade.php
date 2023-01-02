@extends('layouts.backend.app')

@section('title','Project Details')

@push('css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/daterangepicker.css') }}">
@endpush

@section('content')
<div class="row refresh">
  <div class="col-12">

    <div class="card project-view-section">
      <div class="card-body">
        <div class="row align-items-center mb-5">
          <div class="col-6">
            <h5 class="font">{{ ucwords($project->name) }}
              @if ($project->user_id == Auth::id() ||  $isAdmin == 1)
              <div class="dropdown d-inline">
                <span class="px-3" type="button" id="dropdownMenuButton2" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-v"></i>
                </span>
                <div class="dropdown-menu">
                  <a data-toggle="modal" data-target="#assign_admin" class="dropdown-item has-icon" href="javascript:void(0)"><i class="fa fa-edit"></i>{{ __('Assign Admin') }}</a> 
                  <a data-toggle="modal" data-target="#change_status" class="dropdown-item has-icon" href="javascript:void(0)"><i class="fa fa-edit"></i>{{ __('Change Project Status') }}</a> 
                </div>
              </div>
              @endif
            </h5>
          </div>
          <div class="col-6 text-right">
            @if ($project->user_id == Auth::id() || $isAdmin == 1)
            <button type="button" class="btn btn-outline-primary btn-lg" data-toggle="modal" data-target="#exampleModal">
              {{ __('Add Column') }}
            </button>
            @endif
            {{-- Add Column Modal  --}}
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form class="basicform_with_reload" action="{{ route('user.project.addColumn') }}" method="POST">
                  @csrf
                  <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Column') }}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <input type="text" name="name" class="form-control" placeholder="Column Name"></br>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary basicbtn">{{ __('Add') }}</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        @if (Session::has('message'))
          <div class="alert alert-danger">{{ Session::get('message') }}</div>
        @endif
        <div class="row ">
            <div class="col-lg-12">
              <div class="column">
                @foreach ($project->column ?? [] as $column)
                  <div class="col-12 col-md-6 col-lg-4" id="id_{{ $column->id }}">
                    <div class="card column-section-height bg-light column-card card-success">
                        <div class="card-header bg-white column-header row p-2">
                            <div class="col-md-8">
                                <h5>{{ $column->name }}</h5>
                            </div>
                            @if ($project->user_id == Auth::id() || $isAdmin == 1)
                            <div class="col-md-4">
                                <div class="d-flex">
                                  <button type="button" class="btn btn-outline-primary rounded" data-toggle="modal" data-target="#addtask{{ $column->id }}">
                                      <i class="fas fa-plus"></i>
                                  </button>
                                  <div class="dropdown d-inline">
                                  <span class="px-3" type="button" id="dropdownMenuButton2" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                  </span>
                                  <div class="dropdown-menu">
                                    <a data-toggle="modal" data-target="#editTask{{ $column->id }}" class="dropdown-item has-icon" href="javascript:void(0)"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
                                    <!-- Column Delete button -->
                                    <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $column->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                                    <!-- Column Delete Form -->
                                    <form class="d-none" id="delete_form_{{ $column->id }}" action="{{ route('user.project.deleteColumn', $column->id) }}" method="POST">
                                    @csrf
                                    </form>
                                  </div>
                                  </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        {{-- Edit Column Name Modal  --}}
                        <div class="modal fade" id="editTask{{ $column->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <form class="basicform_with_reload" id="editTaskForm{{ $column->id }}" action="{{ route('user.project.updateColumn', $column->id) }}" method="POST">
                              @csrf
                              @method('PUT')
                              <input type="hidden" name="column_id" value="{{ $column->id }}">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <div class="input-group columnEdit">
                                    <div class="round">
                                      <input class="columncheckbox" {{ $column->status == 1 ? "checked" : "" }}  data-column-id="{{ $column->id }}" type="checkbox" id="columnCheck{{ $column->id }}">
                                      <label class="edit-label-left" for="columnCheck{{ $column->id }}"></label>
                                    </div>
                                    <h5 class="modal-title ml-4" id="exampleModalLabel">{{ __(' Edit Column') }}</h5>
                                    <span class="btn btn-outline-primary submit" data-column-id="{{ $column->id }}" style="visibility: hidden;">
                                      <i class="fas fa-check"></i>
                                    </span>
                                  </div>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <input type="text" name="name" value="{{ $column->name }}" class="form-control" placeholder="Task Name"></br>
                                </div>
                                <div class="modal-footer">
                                  <button type="submit" class="btn btn-primary basicbtn">{{ __('Update') }}</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      <div class="card-body task" data-col-id="{{ $column->id }}">
                        @foreach ($column->task ?? [] as $task)
                            @if (empty($task->task_id))
                              @if ( !empty($task->user) && $task->user->user_id == Auth::id() || $project->user_id == (Auth::id()) || $isAdmin == 1)
                              <div class="card task-card rounded modalTask" data-id="{{ $task->id }}" data-toggle="modal" data-target="#task{{ $task->id }}">
                                <div class="custom_alert-task_{{ $task->id }}">
                                  @if ($task->status == 1)
                                  <div class="alert alert-success task-complete-alert" role="alert">
                                    <i class="fas fa-check-circle"></i> {{ __('Completed!') }}
                                  </div>
                                  @endif
                                </div>
                              <p class="p-3" id="taskName_{{ $task->id }}">{{ $task->name }}</p>
  
                              <div id="duedatecard_{{ $task->id }}">
                                <div class="pb-4 pl-3">
                                    <span class="mr-2">
                                      @php
                                      $priority_text = [
                                        0 => 'text-dark',
                                        1 => 'text-danger',
                                        2 => 'text-warning',
                                        3 => 'text-primary',
                                        4 => 'text-success',
                                      ][$task->priority];
                                      @endphp
                                      <i class="fas fa-flag {{ $priority_text }}"></i>
                                    </span>
                                    <span class="ml-1">
                                      @if ($task->due_date)
                                      <i class='fas fa-calendar mr-1'></i>
                                        {{ $task->due_date }}
                                      @endif
                                    </span>
                                    <span class="ml-1 task_counter_{{ $task->id }}"> 
                                      <i class="fas fa-check-circle"></i> {{ $task->completed_subtask_count }} / {{ count($task->task) }}
                                    </span>
  
                                    <span class="custom-avatar">
                                      <div class="task_img_{{ $task->id }}">
                                        @php 
                                        $username = $task->user->user->name ?? ''; 
                                        $img = $task->user->user->avatar ?? '';
                                        @endphp
    
                                        @if ($username)
                                        <img data-toggle="tooltip" data-original-title="{{ $username }}" alt="image" src="{{ $img  != null ? asset($img) : 'https://ui-avatars.com/api/?background=random&name='.$username }}" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="">
                                        @endif
                                      </div>
                                     
                                    </span>
                                  </div>
                                </div>
                              </div>
                            @endif
                            {{-- Task Details Modal  --}}
                            <div class="modal fade taskModal" data-modal-task="{{ $task->id }}" id="task{{ $task->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-custom">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h4>
                                         <div class="taskEditForm">
                                          <div class="input-group">
                                            <div class="round">
                                              <input data-task-check-id="{{ $task->id }}" type="checkbox" {{ $task->status == 1 ? 'checked' : '' }} id="checkbox{{ $task->id }}" />
                                              <label for="checkbox{{ $task->id }}"></label>
                                            </div>
                                            <input type="text" name="name" class="taskInput name_{{ $task->id }}" value="{{ $task->name }}">
                                            <span class="btn btn-outline-primary submit" data-task-id="{{ $task->id }}">
                                              <i class="fas fa-check"></i>
                                            </span>
                                          </div>
                                         </div>
                                      </h4>
                                      <div class="d-flex">
                                        {{-- Delete task  --}}
                                        <div class="dropdown d-inline">
                                        @if ($project->user_id == Auth::id() || $isAdmin == 1)
                                        <span class="px-3" type="button" id="dropdownMenuButton2" data-toggle="dropdown">
                                          <i class="fas fa-ellipsis-v"></i>
                                        </span>
                                        <div class="dropdown-menu">
                                          <!-- Task Delete button -->
                                          <a class="dropdown-item has-icon delete_form_task" href="javascript:void(0)" data-id={{ $task->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                                          <!-- Task Delete Form -->
                                          <form class="d-none" id="delete_form_task_{{ $task->id }}" action="{{ route('user.project.deleteTask', $task->id) }}" method="POST">
                                          @csrf
                                          </form>
                                        </div>
                                        @endif
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card">
                                      <div class="card-body">                                      
                                        @if ($project->user_id == Auth::id() || $isAdmin == 1)
                                        <div class="p-3">
                                          <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="users">
                                              <span class="user-select-icon"><i class="fas fa-user"></i></span>
                                              <select class="custom-user-select select-user" data-task-id="{{ $task->id }}" data-project-id="{{ $project->id }}">
                                                <option value="">{{ __('Select User') }}</option>
                                                @foreach ($project->projectuser ?? [] as $projectuser)
                                                    @php $select = '' @endphp
                                                    @foreach ($taskusers ?? [] as $assignedUser)
                                                        @if ($assignedUser->user_id == $projectuser->user->id && $assignedUser->task_id == $task->id) 
                                                        @php $select = 'selected' @endphp 
                                                        @endif
                                                    @endforeach
                                                    <option data-url="{{url('/')}}/" data-image="{{ $projectuser->user->avatar }}" value="{{ $projectuser->user->id }}" {{ $select }} >
                                                      {{ $projectuser->user->name }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                            <div class="date">
                                              <div class="input-group">
                                                <div class="input-group-prepend">
                                                  <span class="input-group-text p-0 m-0 border-0"><i class="fas fa-calendar"></i></span>
                                                    <a href="javascript:;" data-date-id="{{ $task->id }}" type="button" class="duedate daterange-btn align-self-center pl-2 text-dark">
                                                      <span id="duedate_{{ $task->id }}">
                                                          {{ !empty($task->due_date) ? Carbon\Carbon::parse($task->due_date)->isoFormat('ll') : 'Due Date' }}
                                                      </span>
                                                    </a>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="priority">
                                              <div class="input-group">
                                                <div class="input-group-prepend">
                                                  <span class="input-group-text p-0 m-0 border-0"> 
                                                    <i class="fas fa-flag border-0"></i>
                                                  </span>
                                                </div>
                                                <div class="dropdown d-inline mr-2 ml-2 align-self-center">
                                                  <span class="dropdown-toggle" id="priority_{{ $task->id }}" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    @php
                                                        $priority_text = [
                                                          0 => 'No priority',
                                                          1 => 'Urgent',
                                                          2 => 'High',
                                                          3 => 'Medium',
                                                          4 => 'Low',
                                                        ][$task->priority]
                                                    @endphp
                                                    {{ $priority_text }}
                                                  </span>
                                                  <div class="dropdown-menu priority">
                                                    <a class="dropdown-item" href="#" data-priority-task="{{ $task->id }}" data-priority='1'>{{ __('Urgent') }}</a>
                                                    <a class="dropdown-item" href="#" data-priority-task="{{ $task->id }}" data-priority='2'>{{ __('High') }}</a>
                                                    <a class="dropdown-item" href="#" data-priority-task="{{ $task->id }}" data-priority='3'>{{ __('Medium') }}</a>
                                                    <a class="dropdown-item" href="#" data-priority-task="{{ $task->id }}" data-priority='4'>{{ __('Low') }}</a>
                                                    <a class="dropdown-item" href="#" data-priority-task="{{ $task->id }}" data-priority='0'>{{ __('No priority') }}</a>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        @endif
                                        <div>
                                          <ul class="nav nav-pills justify-content-center task-nav" id="myTab3{{ $task->id }}" role="tablist">
                                            <li class="nav-item">
                                              <a class="nav-link active show" id="home-tab3{{ $task->id }}" data-toggle="tab" href="#home3{{ $task->id }}" role="tab" aria-controls="home" aria-selected="true">{{ __('Comments') }}</a>
                                            </li>
                                            <li class="nav-item">
                                              <a class="nav-link datatasktab" data-tasktab="{{ $task->id }}"  id="profile-tab3{{ $task->id }}" data-toggle="tab" href="#profile3{{ $task->id }}" role="tab" aria-controls="profile" aria-selected="false">{{ __('Task') }}</a>
                                            </li>
                                            <li class="nav-item">
                                              <a class="nav-link" id="contact-tab3{{ $task->id }}" data-toggle="tab" href="#contact3{{ $task->id }}" role="tab" aria-controls="contact" aria-selected="false">{{ __('Attachment') }}</a>
                                            </li>
                                          </ul>
                                          <div class="tab-content p-3 pb-0" id="myTabContent2{{ $task->id }}">
                                            <div class="tab-pane fade active show" id="home3{{ $task->id }}" role="tabpanel" aria-labelledby="home-tab3{{ $task->id }}">
                                              <div class="comment_box mb-3">
                                                <form class="commentForm" enctype="multipart/form-data">
                                                  <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                  <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                  <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                  <textarea name="comment" class="form-control comment"></textarea>
                                                  <div class="submittaskform">
                                                    <div>
                                                      <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-paper-plane"></i> <span>
                                                          {{ __('Send') }}
                                                        </span>

                                                      </button>
                                                    </div>
                                                    <div class="imageIcon text-right">
                                                      <input class="d-none imageSelect" type="file" name="attachment" data-id="{{ $task->id }}" id="file{{ $task->id }}">
                                                      <label class="upload-logo" for="file{{ $task->id }}">
                                                      <i class="fas fa-paperclip text-primary"></i>
                                                    </label>
                                                    </div>
                                                    <div class="imageshow" id="imageShow{{ $task->id }}"></div>
                                                  </div>
                                                </form>
                                                <div class="all_comments">
                                                  
                                                  <ul class="list-group" id="comment_id_{{ $task->id  }}">
                                                    @foreach ($task->comment ?? [] as $comment)
                                                        <li class="list-group-item border-0 shadow-sm p-3 mb-2 bg-white rounded commentOverflow"> 
                                                         
                                                            <div class="row">
                                                              <div class="col-md-8">{{ $comment->user->name }}</div>
                                                              <div class="col-md-4 text-right">{{ $comment->created_at->diffForHumans() }}</div>
                                                              <div class="col-md-12">
                                                                <i>
                                                                  <strong>{{ $comment->comment }}</strong>
                                                                </i>
                                                                @if (!empty($comment->attachment->file_name))
                                                                <a target="_blank" href="{{ asset($comment->attachment->file_name) }}"><i class="fas fa-paperclip"></i></a> 
                                                                @endif   
                                                              </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                  </ul>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="tab-pane fade" id="profile3{{ $task->id }}" role="tabpanel" aria-labelledby="profile-tab3{{ $task->id }}">
                                              @if ($project->user_id == Auth::id() || $isAdmin == 1)
                                              <form class="tasktodoform">
                                                <div class="form-group">
                                                <div class="input-group mb-3">

                                                  <input placeholder="Enter a new task..." type="text" name="name" class="form-control">
                                                  <input type="hidden" name="column_id" value="{{ $column->id }}">
                                                  <input type="hidden" name="parent_id" value="{{ $task->id }}">
                                                  <div class="input-group-append">
                                                  <button class="btn btn-outline-info" type="submit">
                                                    <i class="fas fa-plus"></i> {{ __('Add') }}
                                                  </button>
                                                </div>  
                                                </div>
                                                </div>
                                              </form>
                                              @endif
                                              <div class="all_todos" id="all_todos{{ $task->id }}" data-id="{{ $task->id }}">
                                                  <div class="loader d-flex justify-content-center">
                                                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="contact3{{ $task->id }}" role="tabpanel" aria-labelledby="contact-tab3{{ $task->id }}">
                                              <div class="all_attachment">
                                                <ul class="list-group" id="all_attachment{{ $task->id }}">
                                                  @foreach ($task->comment ?? [] as $comment)
                                                  @if (!empty($comment->attachment->file_name))
                                                  <li class="list-group-item border-0 shadow-sm p-3 mb-2 bg-white rounded"> 
                                                    <div class="row">
                                                      <div class="col-md-12">
                                                        @php 
                                                        $path = explode('/', $comment->attachment->file_name)
                                                        @endphp
                                                        <a target="_blank" href="{{ asset($comment->attachment->file_name) }}"><i class="fas fa-paperclip"></i> {{ end($path) }} </a> 
                                                      </div>
                                                    </div>
                                                  </li>     
                                                  @endif
                                                @endforeach
                                                </ul>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                              </div>
                            </div>
                            @endif
                        @endforeach
                      </div>
                    </div>
                  </div>
                  {{-- Add Task Modal  --}}
                  <div class="modal fade" id="addtask{{ $column->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <form class="basicform_with_reload taskForm" id="taskForm{{ $column->id }}" action="{{ route('user.project.addTask') }}" method="POST">
                        @csrf
                        <input type="hidden" name="column_id" value="{{ $column->id }}">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Task') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <input type="text" name="name" class="form-control" placeholder="Task Name"></br>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary basicbtn">{{ __('Add') }}</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="assign_admin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="basicform" action="{{ route('user.project.assignAdmin') }}" method="POST">
      @csrf
      <input type="hidden" name="project_id" value="{{ $project->id }}">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Assign as admin') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @foreach ($project->projectuser as $key => $project_user)
              @if ($project->user_id != $project_user->user_id)
                  
             
              @php $checked = '' @endphp
              @if ($project_user->is_admin == 1)
                  @php $checked = 'checked' @endphp
              @endif
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                <input type="checkbox" id="a{{ $key }}" {{ $checked }} value="{{ $project_user->id }}" name="project_user_id[]" class="custom-control-input">
                <label class="custom-control-label" for="a{{ $key }}"><figure class="avatar mr-2 avatar-sm">
                    <img src="{{ asset($project_user->user->avatar != null ? $project_user->user->avatar : 'uploads/avatar.png') }}" alt="...">
                </figure><b>{{ $project_user->user->name }}</b></label>
                </div>
            </div>
            @endif
          @endforeach
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary basicbtn">{{ __('Add') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="change_status" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="basicform_with_reload" action="{{ route('user.project.status') }}" method="POST">
      @csrf
      <input type="hidden" name="project_id" value="{{ $project->id }}">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Project Status') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
              <select name="status" class="form-control">
                <option value="1" {{ $project->status == 1 ? 'selected': '' }}>{{ __('Finished') }}</option>
                <option value="2" {{ $project->status == 2 ? 'selected': '' }}>{{ __('Pending') }}</option>
              </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary basicbtn">{{ __('Submit') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>


<input type="hidden" id="allTodoRoute" value="{{ route("user.project.allTodos") }}">
<input type="hidden" id="baseUrl" value="{{url('/')}}/">
<input type="hidden" id="assignUserTaskRoute" value="{{ route("user.project.assignUserTask") }}">
<input type="hidden" id="updateTaskDueDateRoute" value="{{ route("user.project.updateTaskDueDate") }}">
<input type="hidden" id="updateTodoTaskRoute" value="{{ route("user.project.updateTodoTask") }}">
<input type="hidden" id="addTaskRoute" value="{{ route("user.project.addTask") }}">
<input type="hidden" id="updateTaskPriorityRoute" value="{{ route("user.project.updateTaskPriority") }}">
<input type="hidden" id="addCommentOnTaskRoute" value="{{ route("user.project.addCommentOnTask") }}">
<input type="hidden" id="updateTaskStatusRoute" value="{{ route("user.project.updateTaskStatus") }}">
<input type="hidden" id="updateColumnStatusRoute" value="{{ route("user.project.updateColumnStatus") }}">
<input type="hidden" id="sortTaskRoute" value="{{ route("user.project.sortTask") }}">
<input type="hidden" id="sortColumnRoute" value="{{ route("user.project.sortColumn") }}">
<input type="hidden" id="updateTaskName" value="{{ route("user.project.update_task") }}">
<input type="hidden" id="userid" value="{{ ($project->user_id == Auth::id() || $isAdmin == 1) ? 1 : 0 }}">
<input type="hidden" id="authid" value="{{ Auth::id() }}">
@endsection

@push('js')   
<script src="{{ asset('backend/admin/assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/project.js?v=1.0.0') }}"></script>
@endpush
