"use strict";

/*----------------------------
      	Render Notification
    -------------------------------*/
function renderNotification() {
    let html = '';
    let notification = $('#notification_url').val();
    let page_url = $('#page_url').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: notification,
        dataType: 'json',
        success: function(response) {
            for (const notification of response) {
                let url = notification.type == 'project' ? page_url+notification.id: page_url+notification.project_id
                html += `<a href="${url}" target="_blank" class="dropdown-item dropdown-item-unread">
                    <div class="dropdown-item-icon bg-primary text-white">
                    <i class="fas fa-code"></i>
                    </div>
                    <div class="dropdown-item-desc">
                    ${notification.name}
                    <div class="time text-primary">${notification.time}</div>
                    </div>
                </a>`
            }
           
            $('#notifications').html("");
            $('#notifications').html(html);
         
        }
    })
}

renderNotification()