"use strict";

/*--------------------------
    Selected Project Type
  -------------------------------*/
var selected_project_type = $('#selected_project_type').val();
project_type_trigger(selected_project_type);   

var startDate = $('input[name="project_time"]').data('start')
var endDate = $('input[name="project_time"]').data('end')
  $('.daterange-cus').daterangepicker({
  locale: {format: 'YYYY-MM-DD'},
  drops: 'down',
  opens: 'left',
  startDate: startDate,
  endDate: endDate,
});

$('.project_type').on('click',function(){
  var project_type= $(this).val();
  project_type_trigger(project_type)
});

function project_type_trigger(project_type){
  if(project_type == 2){
    $('.team').removeClass('disable_cursor');
    $('.group').addClass('disable_cursor');
  }
  else if(project_type == 3){
    $('.group').removeClass('disable_cursor');
    $('.team').addClass('disable_cursor');
  }
  else{
    $('.team').addClass('disable_cursor');
    $('.group').addClass('disable_cursor');
  }
}

/*--------------------------
    Progress Bar Active
  -------------------------------*/
$.each($('.progress-bar'), function(e, val){
    let id = $(this).attr('id')
    let width = $(this).data('width')
    $('#'+id).css('width', width + 'px')
})

/*--------------------------
    Filter Dropdown Active
  -------------------------------*/
$(document).on('click', '.filterdropdown', function(e) {
    e.stopPropagation();
    e.preventDefault();
});

/*----------------------
    Daterange Active
  ----------------------------*/
$('.daterange-cus-left').daterangepicker({
    locale: {format: 'YYYY-MM-DD'},
    drops: 'down',
    opens: 'left'
});


$('#filter').on('keyup', function(){
  searchUser();
})

function searchUser(){
  $('.user_info .form-group').show();
  var val = $('#filter').val().toLowerCase();
  if (val == ''){
    return
  }
  $('.user_info .form-group').each(function(i, elem){
    var str = $(this).data('name').toLowerCase();
    if(str.indexOf(val) !== -1){
      $(this).show()
    }else{
      $(this).hide()
    }
  })
  
}

/*----------------------
    Project Type
  ----------------------------*/
$('.project_type').on('click',function(){
    var project_type= $(this).val();
   if(project_type == 2){
     $('.team').removeClass('disable_cursor');
     $('.group').addClass('disable_cursor');
   }
   else if(project_type == 3){
     $('.group').removeClass('disable_cursor');
     $('.team').addClass('disable_cursor');
   }
   else{
     $('.team').addClass('disable_cursor');
     $('.group').addClass('disable_cursor');
   }
});

