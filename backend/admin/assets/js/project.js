(function ($) {
  "use strict";


  var allTodoRoute = $('#allTodoRoute').val();
  var project_id = $('#project_id').val();
  var baseUrl = $('#baseUrl').val();
  var assignUserTaskRoute = $('#assignUserTaskRoute').val();
  var updateTaskDueDateRoute = $('#updateTaskDueDateRoute').val();
  var updateTodoTaskRoute = $('#updateTodoTaskRoute').val();
  var addTaskRoute = $('#addTaskRoute').val();
  var updateTaskPriorityRoute = $('#updateTaskPriorityRoute').val();
  var addCommentOnTaskRoute = $('#addCommentOnTaskRoute').val();
  var updateTaskStatusRoute = $('#updateTaskStatusRoute').val();
  var updateColumnStatusRoute = $('#updateColumnStatusRoute').val();
  var sortTaskRoute = $('#sortTaskRoute').val();
  var sortColumnRoute = $('#sortColumnRoute').val();
  var updateTaskName = $('#updateTaskName').val();
  var userid = $('#userid').val();

  /*-----------------------------------
    custom image select name preview 
  -----------------------------------------*/
  $('.imageSelect').on('input', function () {
    var id = $(this).attr('data-id');
    var imgBox = $('#imageShow' + id);
    var filename = $(this).val().split('\\').pop();
    var html = "";
    if (filename != "") {
      html += `<i class="fas fa-paperclip text-primary"></i> ` + filename;
      html += `<span class="closeFile" data-id=${id}><i class="fas fa-times"></i></span>`;
    }
    imgBox.html(html);
  })

  $(document).on('click', '.closeFile', closeFile);

  function closeFile() {
    var id = $(this).attr('data-id');
    var imgBox = $('#imageShow' + id);
    $('.imageSelect').val("");
    imgBox.html("");
  }

  /*-----------------------
    load todo on tabclick 
  ------------------------------*/
  $('.datatasktab').on('click', function () {
    var id = $(this).attr('data-tasktab');
    loadTodo(id);
  })

  /*-----------------
    todo load ajax 
  ----------------------*/
  function loadTodo(id) {
    var html = [];
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: allTodoRoute,
      data: { id: id, project_id: project_id },
      dataType: 'json',
      success: function (response) {
        if (response) {
          let select = "";
          html[id] = `<ul class="list-group" id="todo${id}">`;
          for (var i in response.data) {
            if (response.data[i].task_id == id) {
              var assigned_user = response.data[i].user !== null ? response.data[i].user.user_id : '';
              var status = response.data[i].status == 1 ? "line-through" : "";
              html[id] += ``
              if (userid == 1) { //check if admin 
                html[id] += `<li class="list-group-item border-0 shadow-sm mb-2 bg-white rounded">
                              <div class="row">
                                <div class="checkboxround col-md-1">
                                  <div class="round todocheckboxround">
                                  <input data-todo-id="${response.data[i].id}" class="todocheckbox" type="checkbox" ${response.data[i].status == 1 ? `checked` : ``} id="todo${response.data[i].id}" data-parent="${response.data[i].task_id}"/>
                                  <label for="todo${response.data[i].id}"></label>
                                  </div> 
                                  </div>
                                    <div class="todoform col-md-6" id="todo-text_${response.data[i].id}">
                                    <form class="todoformupdate">
                                    <div>
                                    <input type="hidden" name="id" value="${response.data[i].id}">
                                    <input type="text" data-todo-id=${response.data[i].id} id="todotext${response.data[i].id}" style="text-decoration: ${status};" name="name" class="todotext" value="${response.data[i].name}">
                                    <button class="btn btn-outline-primary taskEditSubmit" id="taskEditSubmit${response.data[i].id}" data-edittask-id="${response.data[i].id}" type="submit">
                                    <i class="fas fa-check"></i>
                                    </button>
                                    <br>${response.data[i].due_date ?? ""}
                                    </div>
                                    </form>
                                  </div>
                                  `
                html[id] += `<div class="users col-md-4 d-flex justify-content-center align-items-center">
                                  <span class="user-select-icon"></span>`;
                html[id] += `<select class="custom-user-select-ajax" data-task-id="${response.data[i].id}" data-project-id="${project_id}">`;
                html[id] += `<option value="">Select</option>`
                for (let i in response.users) {
                  if (assigned_user == response.users[i].user.id) {
                    select = 'selected';
                  } else {
                    select = '';
                  }
                  let url = response.users[i].user.avatar != null ? baseUrl : 'https://ui-avatars.com/api/?background=random&name=' + response.users[i].user.name;

                  html[id] += `<option ${select} data-url="${url}" data-image="${response.users[i].user.avatar ?? response.users[i].user.name}" value="${response.users[i].user.id}">${response.users[i].user.name}</option>`;

                }

                html[id] += `</select></div>
                            <div class="date col-md-1 d-flex justify-content-center align-items-center">
                              <span class="custom-date-icon"><i class="fas fa-calendar"></i></span>
                              <input type="date" class="form-control daterange-btn-custom date_range${response.data[i].id}" name="date" data-parent-id="${response.data[i].task_id}"  data-id="${response.data[i].id}" value="${response.data[i].due_date ?? ""}" />
                            </div>
                            
                        </div>
                      </li>`;
              } else if (assigned_user == $('#authid').val()) { //check if assigned user 
                html[id] += `<li class="list-group-item border-0 shadow-sm mb-2 bg-white rounded">
                              <div class="row">
                              <div class="checkboxround col-md-1">
                                  <div class="round todocheckboxround">
                                  <input data-todo-id="${response.data[i].id}" class="todocheckbox" type="checkbox" ${response.data[i].status == 1 ? `checked` : ``} id="todo${response.data[i].id}" data-parent="${response.data[i].task_id}"/>
                                  <label for="todo${response.data[i].id}"></label>
                                  </div> 
                              </div>
                              <div class="col-md-6"><span>${response.data[i].name}</span></div>`
                html[id] += `<div class="col-md-4"><span>${response.data[i].due_date ?? "--"}</span></div>
                              </div></li>`;
              }



            }

          }

          html[id] += `</ul>`;
          $('#all_todos' + id).html("");
          $('#all_todos' + id).html(html)

        }

        /*-----------------------
          custom ajax select2 
        -----------------------------*/
        var placeholder = "<i class='fas fa-user'></i>";
        $(".custom-user-select-ajax").select2({
          placeholder: placeholder,
          templateResult: formatState,
          templateSelection: formatState,
          dropdownCssClass: "ajax-select",
          escapeMarkup: function (m) {
            return m;
          }
        });

        /*---------------------------------
          todo task user update with ajax 
        ----------------------------------------*/
        $(".custom-user-select-ajax").on('change', function () {
          todoUserUpdate($(this));
        })

        /*-------------------------------------------
          todo text focus and blur effects trigger 
        -------------------------------------------------*/
        $('.todotext').on('focus', function (e) {
          $(e.target).next().css('visibility', 'visible');
        }).on('blur', function (e) {
          submitbtnhide($(e.target).next())
        })

        /*-------------------------
          todo text form update
        ---------------------------------*/
        $('.todoformupdate').on('submit', function (e) {
          e.preventDefault();
          todoUpdate($(this));
        })

        //task todo due date
        $('.daterange-btn-custom').on('change', function () {

          dateTodoUpdate($(this));
        })

      }


    })

    //load_date_range();
  }

  /*--------------------
    task user update 
  --------------------------*/
  $('.custom-user-select').on('change', function () {
    todoUserUpdate($(this));
  });

  /*------------------------------
    user assign update function
  ------------------------------------*/
  function todoUserUpdate(data) {
    var user_id = data.val();
    var task_id = data.data('task-id');
    var project_id = data.data('project-id');

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
      type: 'POST',
      url: assignUserTaskRoute,
      data: { user_id: user_id, task_id: task_id, project_id: project_id },
      dataType: 'json',
      success: function (response) {
        if (response) {
          $('.task_img_' + task_id).load(' .task_img_' + task_id)
          loadTodo(task_id);
        }
      }
    });
  }

  /*--------------------------
    task todo date update
  -------------------------------*/
  function dateTodoUpdate(data) {
    var due_date = data.val();
    var task_id = data.attr('data-id');
    var parent = data.attr('data-parent-id');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
      type: 'POST',
      url: updateTaskDueDateRoute,
      data: { due_date: due_date, task_id: task_id },
      dataType: 'json',
      success: function (response) {
        if (response) {
          loadTodo(parent);
        }
      }
    });


  }

  /*-------------------
    task todo update
  -----------------------*/
  function todoUpdate(data) {
    var id = $("input[name='id']", data).val()
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: updateTodoTaskRoute,
      data: data.serialize(),
      dataType: 'json',
      success: function (response) {
        if (response) {
          loadTodo(id);
        }

      }
    })
  }


  /*-----------------------
    task todo date add
  ---------------------------*/
  $.each($('.tasktodoform'), function () {
    $(this).on('submit', function (e) {
      e.preventDefault();
      var namefield = $(this).find('input[name="name"]');
      var task_id = $(this).find('input[name="parent_id"]').val();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'POST',
        url: addTaskRoute,
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
          namefield.val("");
          if (response) {
            $('#duedatecard_' + task_id).load(' #duedatecard_' + task_id);
            loadTodo(task_id);
          }

        }
      })
    })
  });

  /*-------------------------
    task priority update
  -----------------------------*/
  $('.priority a').on('click', function () {
    var task_id = $(this).data('priority-task');
    var priority = $(this).data('priority');
    var priority_text = $(this).text();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: updateTaskPriorityRoute,
      data: { task_id: task_id, priority: priority },
      dataType: 'json',
      success: function (response) {
        if (response) {
          $('#priority_' + task_id).text(priority_text);
          $('#duedatecard_' + task_id).load(' #duedatecard_' + task_id);
        }
      }
    })
  });

  /*----------------------
    task todo due date
  ---------------------------*/
  $('.daterange-btn').daterangepicker({
    locale: { format: 'YYYY-MM-DD' },
    singleDatePicker: true,
    showDropdowns: true,
  }, function (start, end, label) {
    var task_id = this.element.data('date-id');
    var due_date = end.format('YYYY-MM-DD');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
      type: 'POST',
      url: updateTaskDueDateRoute,
      data: { due_date: due_date, task_id: task_id },
      dataType: 'json',
      success: function (response) {
        if (response) {
          $('#duedate_' + task_id).load(' #duedate_' + task_id);
          $('#duedatecard_' + task_id).load(' #duedatecard_' + task_id);
        }
      }
    })
  });

  /*--------------------------------
    task comment & image upload
  ---------------------------------------*/
  $.each($('.commentForm'), function () {
    $(this).on('submit', function (e) {
      e.preventDefault();
      var form = this;
      var data = new FormData(this);
      var task_id = $(this).find('input[name="task_id"]').val();
      var comment = $(this).find('textarea[name="comment"]');
      var submit = $(this).find('button[type="submit"]');
      var submitText = $(this).find('button[type="submit"] .text');

      if (comment.val() === "") {
        Sweet('error', "Add a comment!!");
        return;
      }
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'POST',
        url: addCommentOnTaskRoute,
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function () {
          // setting a timeout
          submit.prop('disabled', true);
          submitText.text('Please Wait...');
        },
        success: function (response) {
          submit.prop('disabled', false);
          submitText.text('Send');
          $('#imageShow' + task_id).html("");
          form.reset();
          $('#comment_id_' + task_id).load(' #comment_id_' + task_id);
          $('#all_attachment' + task_id).load(' #all_attachment' + task_id);
        },
        error: function (xhr) { // if error occured
          // alert("Error occured.please try again");
          // $(placeholder).append(xhr.statusText + xhr.responseText);
          submit.prop('disabled', false);
          submitText.text('Send');
          $.each(xhr.responseJSON.errors, function (key, item) {
            Sweet('error', item)
          });
        },
      })
    })
  })

  /*--------------------------
    task todo status update
  ---------------------------------*/
  $(document).on('change', $('.todocheckbox'), function (e) {

    var id = $(e.target).data('todo-id');
    var parent = $(e.target).data('parent');
    if (parent == undefined) {
      return;
    }
    var status = e.target.checked ? 1 : 2;
    var css = 'none';
    if (status == 1) {
      css = 'line-through';
    }

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: updateTaskStatusRoute,
      data: { id: id, status: status },
      dataType: 'json',
      success: function (response) {
        // $('.all_todos').load(' #todo' + parent);
        // $('.all_todos' + parent).load(' #todo' + parent);
        $('.task_counter_' + parent ?? id).load(' .task_counter_' + parent ?? id);
        $('#todotext' + id).css('text-decoration', css);
      }
    })
  });

  /*--------------------------
    task todo status update
  ---------------------------------*/
  $('.columncheckbox').on('change', function (e) {
    var id = $(e.target).data('column-id');
    var status = e.target.checked ? 1 : 0;
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: updateColumnStatusRoute,
      data: { id: id, status: status },
      dataType: 'json',
      success: function (response) {

      }
    })
  });


  /*------------------
    task edit form
  ------------------------*/
  $.each($('.taskEditForm'), function () {
    var submitbtn = $(this).find('.submit');
    var checkbox = $(this).find('input[type="checkbox"]');
    checkbox.on('change', function (e) {
      var id = $(this).attr('data-task-check-id');
      var status = e.target.checked ? 1 : 0;
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'POST',
        url: updateTaskStatusRoute,
        data: { id: id, status: status },
        dataType: 'json',
        success: function (response) {
          $('.custom_alert-task_' + id).load(' .custom_alert-task_' + id);
        }
      })

    })

    $(this).find('input').on('focus', function (e) {
      submitbtn.css('visibility', 'visible');
      $(this).removeClass('taskInput')
    }).on('blur', function () {
      submitbtnhide(submitbtn);
      $(this).addClass('taskInput')
    })

    submitbtn.on('click', function () {
      submitbtnhide(submitbtn);
      var id = $(this).attr('data-task-id');
      var name = $('.name_' + id).val();
      if (name === "") {
        return;
      }
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'POST',
        url: updateTaskName,
        data: { 'id': id, 'name': name },
        dataType: 'json',
        success: function (response) {
          $('#taskName_' + id).text(name);
        }
      })
    })

  });


  $('.column').attr('height', screen.height);


  /*------------------
    task card sort
  ------------------------*/
  if ($('.task').length && jQuery().sortable) {
    $('.task').sortable({
      opacity: .8,
      tolerance: 'pointer',
      cancel: ".modal",
      connectWith: '.task',
      update: function (event, ui) {
        let tasks = new Array();
        let col_id = '';
        $(this).each(function () {
          tasks = tasks.concat($(this).sortable('toArray', { attribute: 'data-id' }));
          col_id = $(this).attr('data-col-id');
        })
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: 'POST',
          url: sortTaskRoute,
          data: { 'task_id': tasks, 'column_id': col_id },
          dataType: 'json',

        })
      }
    });
  }

  /*----------------
    column update
  ----------------------*/
  if (userid == 1) {
    if ($('.column').length && jQuery().sortable) {
      $('.column').sortable({
        handle: '.column-header',
        opacity: .8,
        tolerance: 'pointer',
        update: function (event, ui) {
          var data = $(".column").sortable("serialize");
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.ajax({
            type: 'POST',
            url: sortColumnRoute,
            data: data,
            dataType: 'json',
            success: function (response) {
            },
            error: function (xhr, status, error) {
            }
          })
        }
      });
    }
  }

  /*------------
    Task From
  ------------------*/
  $.each($('.taskForm'), function () {
    $($(this).attr('id')).on('submit', function (e) {
      e.preventDefault();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'POST',
        url: addTaskRoute,
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {

        },
        error: function (xhr, status, error) {
          console.log(error);
        }
      })
    })
  })

  /*-----------------
    Delete Confirm
  ----------------------*/
  $('.delete-confirm').on('click', function (event) {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Are you sure?',
      text: "You want to delete this task!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        event.preventDefault();
        document.getElementById('delete_form_' + id).submit();
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


  /*-----------------
    Delete Confirm
  ----------------------*/
  $('.delete_form_task').on('click', function (event) {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Are you sure?',
      text: "You want to delete this column!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        event.preventDefault();
        document.getElementById('delete_form_task_' + id).submit();
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

  /*----------------------
    on blur button hide
  ---------------------------*/
  function submitbtnhide(submitbtn) { setTimeout(function () { submitbtn.css('visibility', 'hidden') }, 500) }

  /*-------------------------------------
    select2 custom thumbnail for user
  ------------------------------------------*/
  function formatState(opt) {
    if (!opt.id) {
      return opt.text;
    }
    var optimage = $(opt.element).attr('data-image');
    var url = $(opt.element).attr('data-url');
    if (!optimage) {

      return opt.text

    } else {

      var $opt = $(
        '<span><img src="' + url + optimage + '" class="user-task-image"/> ' + opt.text + '</span>'
      );
      return $opt;
    }
  };

})(jQuery);