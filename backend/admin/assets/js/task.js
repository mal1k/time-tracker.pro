"use strict";

/*-----------------
     Load Tasks
  ---------------------*/
loadTask('all', 'status');
$('.filter a').on('click', function () {
  $('.filter a').removeClass('active');
  $(this).addClass('active');
  let type = $(this).data('type');
  let filter = $(this).data('filter');
  loadTask(filter, type);
});

function loadTask(filter = '', type = '', page = '', request_type = '') {
  let html = "", task = "";

  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
  $.ajax({
    type: 'POST',
    data: { filter: filter, type: type, request_type: request_type },
    url: page != '' ? page : $('#loadTask').val(),
    dataType: 'json',
    success: function (response) {
      let data = response.data ?? []
      let links = response.links
      let count = 0
      if (filter != 'all') {
        $('.taskajax div').html("");
      }

      if (response.no_overdue) {
        data = response.no_overdue.data
        links = response.no_overdue.links
        count = response.no_overdue.total
        renderHtml('no_overdue', data, links, filter, type, 'No Overdue', count)
      }

      if (response.today) {
        data = response.today.data
        links = response.today.links
        count = response.today.total
        renderHtml('today', data, links, filter, type, 'Today', count)
      }

      if (response.completed) {
        data = response.completed.data
        links = response.completed.links
        count = response.completed.total
        renderHtml('completed', data, links, filter, type, 'Completed', count)
      }

      if (response.upcoming) {
        data = response.upcoming.data
        links = response.upcoming.links
        count = response.upcoming.total
        renderHtml('upcoming', data, links, filter, type, 'Upcoming', count)
      }

      if (response.overdue) {
        data = response.overdue.data
        links = response.overdue.links
        count = response.overdue.total
        renderHtml('overdue', data, links, filter, type, 'Overdue', count)
      }

      if (response.project) {
        data = response.project.data
        links = response.project.links
        count = response.project.total
        renderHtml('project', data, links, filter, type, 'Project', count)
      }

      $('.task-details-modal').on('click', function () {
        let id = $(this).data('parent-id') ?? $(this).data('id')
        let project_id = $(this).data('project-id')
        loadModalData(id, project_id);
      })


      $('.completed-confirm').on('click', function (event) {
        const id = $(this).data('id');
        const project_id = $(this).data('project');
        document.getElementById('taskId').value = id;
        document.getElementById('project_id').value = project_id;
        Swal.fire({
          title: 'Are you sure?',
          text: "Did you complete this task!!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!'
        }).then((result) => {
          if (result.value) {
            event.preventDefault();
            document.getElementById('taskStatusform').submit();
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              'Cancelled',
              'Your Data is Save :)',
              'error'
            )
          }
        })
      });
    }
  });
}


/*--------------------------------------
     custom image select name preview 
  -----------------------------------------*/
$('.imageSelect').on('input', function () {
  var imgBox = $('#imageShow');
  var filename = $(this).val().split('\\').pop();
  var html = "";
  if (filename != "") {
    html += `<i class="fas fa-paperclip text-primary"></i> ` + filename;
    html += `<span class="closeFile"><i class="fas fa-times"></i></span>`;
  }
  imgBox.html(html);
})


/*----------------
     Render Html
  ---------------------*/
function renderHtml(target, data, links = '', filter = '', type = '', header = '', count) {
  let html = '', task = '';
  $('#error').html("");
  if (data.length > 0) {
    $('.loader').html("");
    html += `<div class="table-responsive">`
    html += `<table class="table table-striped"><tbody>`
    if (header != '') {
      html += `<tr class="taskheader"><td colspan="4">${header}</td></tr>`;
      html += `<tr><th>Name</th><th>Priority</th><th>Details</th><th>Start Task</th></tr>`;
    }
    for (let i in data) {
      task = data[i];
      let name = task.task.name
      let due_date = task.task.due_date
      let project_id = task.project.id
      let project_name = task.project.name
      let task_id = task.task.id
      let parent_task_id = task.task.task_id
      let user = task.user.name
      let user_id = task.user.id
      let avatar = task.user.avatar
      let created_at = task.created_at
      let status = task.task.status
      let priority = {
        0: { "class": "badge badge-secondary", "text": "No priority" },
        1: { "class": "badge badge-danger", "text": "Urgent" },
        2: { "class": "badge badge-success", "text": "High" },
        3: { "class": "badge badge-primary", "text": "Medium" },
        4: { "class": "badge badge-warning", "text": "Low" },
      }[task.task.priority]
      html += `<tr>`
      html += `<td class="task-details-modal" data-project-id=${project_id} data-parent-id=${parent_task_id} data-id="${task_id}">`
      html += `${name}`
      html += `</td>`
      html += `<td><span class="${priority.class}">${priority.text}</span></td>`
      html += `<td><a href="/user/project/${project_id}">${project_name}</a>`
      html += `<br>`
      html += `${due_date ?? 'Date not assigned'}`
      html += `</td>`
      html += `<td>`
      if (status == 2) {
        html += `<div class="task-btn">`
        html += `<a class="btn btn-primary" href="/user/task/start/${project_id}/${task_id}">StartTask</a>`
        html += ` <a class="btn btn-danger completed-confirm" href="javascript:void(0)" data-project=${project_id} data-id=${task_id}">Completed!!</a>`
        html += `</div>`
      }
      html += '</td>'
      html += `</tr>`
    }
    html += `</tbody></table>`
    html += `</div>`
    $("#project").html("");
    $("#" + target).html("");
    html += `<nav aria-label="Page navigation example"><ul class="pagination" id='page_${target}'></ul></nav>`
    $("#" + target).html(html);

    if (count > 20) {
      render_pagination('#page_' + target, links, filter, type, target)
    }
  } else {
    $('.loader').html("");
    $('.pagination').html("");
  }
}


/*--------------------
    Load ModalData 
 ------------------------*/
function loadModalData(id, project_id = '') {
  var modal = $('#exampleModal');
  var commentForm = $('.commentForm');
  var html = "", file = "";
  var URL = $('#url').val();
  var input_task_id = commentForm.find('input[name="task_id"]')
  var input_project_id = commentForm.find('input[name="project_id"]')
  var task_data_get = $('#task_data_get').val();
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
  $.ajax({
    type: 'POST',
    url: task_data_get,
    data: { id: id },
    dataType: 'json',
    success: function (response) {
      if (response) {
        let taskname = response.task.name
        if (response.comment.length > 0) {
          for (let i in response.comment) {
            let comment = response.comment[i]
            html += `<li class="list-group-item border-0 shadow-sm p-3 mb-2 bg-white rounded">`
            html += `<div class="row">`
            html += `<div class="col-md-8">${comment.user.name}</div>`
            html += `<div class="col-md-4 text-right">${comment.created_at ? moment(comment.created_at).format('MM/DD/YYYY') : 'None'} </div>`
            html += `<div class="col-md-12">`
            html += `<i>`
            html += `<strong>${comment.comment}</strong>`
            html += `</i>`
            if (comment.attachment != null && comment.attachment.file_name != "") {
              let attachment = comment.attachment.file_name
              file += `<li class="list-group-item border-0 shadow-sm p-3 mb-2 bg-white rounded">`
              file += `<div class="row">`
              file += `<div class="col-md-12">`
              file += `<a target="_blank" href="${URL}/${attachment}">`
              file += `<i class="fas fa-paperclip"></i>`
              file += attachment.substring(attachment.lastIndexOf('/') + 1)
              file += `</a>`
              file += `</div>`
              file += `</div>`
              file += `</li>`
              html += `<a target="_blank" href="${URL}/${attachment}"><i class="fas fa-paperclip"></i></a>`
            }
            html += `</div>`
            html += `</div>`
            html += `</li>`
          }
        }
        input_task_id.val(id);
        input_project_id.val(project_id);
        modal.find('.modal-title').html(taskname);
        modal.find('.all_comments').html(html);
        modal.find('.all_attachments').html(file);
        modal.modal('show');

      }
    }
  });
}


/*--------------------
    Comment Form 
 ------------------------*/
$('.commentForm').on('submit', function (e) {
  e.preventDefault();
  var modal = $('#exampleModal');
  var form = this;
  var html = '', file = '';
  var data = new FormData(this);
  var URL = $('#url_encode').val();
  var comment = $(this).find('textarea[name="comment"]');
  var add_comment_task = $('#add_comment_task').val();
  if (comment.val() === "") {
    return
  }
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    type: 'POST',
    url: add_comment_task,
    data: data,
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function (response) {
      $('#imageShow').html("");
      form.reset();
      html += `<li class="list-group-item border-0 shadow-sm p-3 mb-2 bg-white rounded">`
      html += `<div class="row">`
      html += `<div class="col-md-8">${response.name}</div>`
      html += `<div class="col-md-4 text-right">${moment(response.created_at).format('MM/DD/YYYY')} </div>`
      html += `<div class="col-md-12">`
      html += `<i>`
      html += `<strong>${response.comment}</strong>`
      html += `</i>`
      if (response.file != '') {
        let attachment = response.file
        file += `<li class="list-group-item border-0 shadow-sm p-3 mb-2 bg-white rounded">`
        file += `<div class="row">`
        file += `<div class="col-md-12">`
        file += `<a target="_blank" href="${URL}/${attachment}">`
        file += `<i class="fas fa-paperclip"></i>`
        file += attachment.substring(attachment.lastIndexOf('/') + 1)
        file += `</a>`
        file += `</div>`
        file += `</div>`
        file += `</li>`
        html += `<a target="_blank" href="${URL}/${attachment}"><i class="fas fa-paperclip"></i></a>`
      }

      html += `</div>`
      html += `</div>`
      html += `</li>`

      modal.find('.all_comments').prepend(html);
      modal.find('.all_attachments').prepend(file);
    }
  })
})


/*----------------
    Page Link 
  --------------------*/
$(document).on('click', $('.page-link'), function (e) {
  if (e.target.dataset.url != undefined) {
    let filter = e.target.dataset.filter
    let type = e.target.dataset.type
    let url = e.target.dataset.url
    let request_type = e.target.dataset.request
    loadTask(filter, type, url, request_type)
  }
})

/*----------------------
    Render Pagination 
  -------------------------*/
function render_pagination(target, data, filter, type, dynamic_target) {
  $('.page-item' + dynamic_target).remove();
  $.each(data, function (key, value) {
    if (value.label === '&laquo; Previous') {
      if (value.url === null) {
        var is_disabled = "disabled";
        var is_active = null;
      }
      else {
        var is_active = 'page-link-no' + key;
      }
      var html = '<li class="page-item' + dynamic_target + '"><a class="fas fa-angle-left page-link ' + is_active + '" href="javascript:void(0)" data-type="' + type + '" data-filter="' + filter + '" data-request="' + dynamic_target + '" data-url="' + value.url + '"></a></li>';
    }
    else if (value.label === 'Next &raquo;') {
      if (value.url === null) {
        var is_disabled = "disabled";
        var is_active = null;
      }
      else {
        var is_active = 'page-link-no' + key;
      }
      var html = '<li class="page-item' + dynamic_target + '"><a data-type="' + type + '" data-filter="' + filter + '" class="fas fa-angle-right page-link ' + is_active + '" href="javascript:void(0)" data-request="' + dynamic_target + '" data-url="' + value.url + '"></a></li>';
    }
    else {
      if (value.active == true) {
        var is_active = "active";
        var is_disabled = "disabled";
        var url = null;
      }
      else {
        var is_active = 'page-link-no' + key;
        var url = value.url;
      }
      var html = '<li class="page-item' + dynamic_target + ' ' + is_active + '" ><a class="page-link ' + is_active + '" data-type="' + type + '" data-filter="' + filter + '" href="javascript:void(0)" data-request="' + dynamic_target + '" data-url="' + url + '">' + value.label + '</a></li>';
    }
    if (value.url !== null) {
      $(target).append(html);
    }
  });
}