(function ($) {
    "use strict";

    /*-----------------------
      	Render Task Route
    -------------------------------*/
    var alltasks = $("#renderTaskRoute").val();
    var project_id = $("#project_id").val();
    var member_workload = [];
    var labels = [];

    $("#myEvent").fullCalendar({
        height: "auto",
        displayEventTime: false,
        header: {
            left: "prev,next today",
            center: "title",
            right: "month,agendaWeek,agendaDay,listWeek",
        },
        defaultView: "month",
        events: function (start, end, timezone, callback) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: alltasks,
                type: "POST",
                data: { project_id: project_id },
                dataType: "json",
                success: function (response) {
                    var events = [];
                    if (response) {
                        $.map(response, function (result) {
                            var task = result.task;
                            var statusColor = {
                                1: "#007bff",
                                2: "#c53a3a",
                            }[task.status];

                            var status = {
                                1: "Completed",
                                2: "Pending",
                            }[task.status];

                            events.push({
                                id: task.id,
                                parentId: task.task_id,
                                user_name: result.user.name,
                                projectId: result.project_id,
                                title: task.name,
                                start: moment(task.due_date, "YYYY-MM-DD"),
                                backgroundColor: statusColor,
                                status: status,
                                borderColor: "#fff",
                                textColor: "#fff",
                            });
                        });
                    }
                    callback(events);
                },
            });
        },
        eventMouseover: function (event, jsEvent) {
            var tooltip =
                '<div class="tooltipevent">' + event.status + "</div>";
            var $tooltip = $(tooltip).appendTo("body");

            $(this)
                .on("mouseover", function (e) {
                    $(this).css("z-index", 10000);
                    $tooltip.fadeIn("500");
                    $tooltip.fadeTo("10", 1.9);
                })
                .on("mousemove", function (e) {
                    $tooltip.css("top", e.pageY + 10);
                    $tooltip.css("left", e.pageX + 20);
                });
        },

        eventMouseout: function (event, jsEvent) {
            $(this).css("z-index", 8);
            $(".tooltipevent").remove();
        },
        eventRender: function (event, element) {
            element.find(".fc-title").append("<br/> <strong>" + event.user_name + "</strong>");
        },
        eventClick: function (info) {},
    });

     /*----------------------
      	    Task Progress 
        -------------------------*/
    var id = $("#project_id").val();
    var completed_task_count = 0;
    var pending_task_count = 0;
    var total_task_count = 0;
    var completed_column_count = 0;
    var total_hours = 0;
    var total_spent = 0;
    var member_html = "";
    var column_html = "";
    var url = $("#url").val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $("#statslink").val(),
        data: { id: id },
        dataType: "json",
        success: function (response) {
            let task_progress = 0;
            for (let i in response.columns) {
                if (response.columns[i].status == 1) {
                    completed_column_count++;
                }
                completed_task_count += response.columns[i].completed_task_count;
                pending_task_count += response.columns[i].pending_task_count;
                total_task_count += response.columns[i].task_count;
                let column_id = response.columns[i].id;
                let column_name = response.columns[i].name;
                let date = response.columns[i].created_at;
                task_progress = parseFloat(((response.columns[i].completed_task_count / response.columns[i].task_count) *100).toFixed(2));
                column_html += `<li class="col-lg-6 media">`;
                column_html += `<div class="media-body rounded p-3 shadow-sm">`;
                column_html += `<div class="float-right text-primary">${moment(
                    date
                ).format("MM/DD/YYYY")}</div>`;
                column_html += `<div class="media-title"><a href="${url}/user/report/column/${column_id}"><h4>${column_name}</h4></a></div>`;
                column_html += `<div>`;
                column_html += `<div class="progress custom-progress my-4" data-height="10" data-toggle="tooltip" title="" data-original-title="${
                    !isNaN(task_progress) ? task_progress : 0
                }%" style="height: 15px;">
               <div class="progress-bar bg-success" id="progress_bar_${
                   response.columns[i].id
               }" data-width="${
                    !isNaN(task_progress) ? task_progress : 0
                }" style="width: ${
                    !isNaN(task_progress) ? task_progress : 0
                }%"></div>
               </div>`;
                column_html += ` <h6>Tasks <i class="fas fa-check-circle"></i> ${response.columns[i].completed_task_count}/${response.columns[i].task_count}</h6></div>`;
                column_html += `</div>`;
                column_html += `</li>`;
            }

            for (let i in response.user_stats) {
                total_hours += response.user_stats[i].time;
                total_spent += response.user_stats[i].price;
                member_workload.push(toHours(response.user_stats[i].time))
                labels.push(response.user_stats[i].user.name)
            }

            myChart.data.datasets[0].data = member_workload;
            myChart.data.labels = labels;
            myChart.update();

            for (let i in response.user) {
                let avatar = response.user[i].user.avatar;
                let userId = response.user[i].user.id;
                let name = response.user[i].user.name;
                let avatarCustom =
                    "https://ui-avatars.com/api/?background=random&name=" +
                    name;
                let imgUrl =
                    avatar != null ? `${url + "/" + avatar}` : avatarCustom;
                member_html += `<li class="d-flex align-items-center media">`;
                member_html += `<img class="mr-3 rounded-circle" alt="image" width="50" src="${imgUrl}" alt="avatar">`;
                member_html += `<div class="media-body"><a href="${url}/user/report/user/${userId}/${id}">`;
                member_html += `<div class="float-right text-primary"></div>`;
                member_html += `<div class="media-title">${name}</div>`;
                member_html += `<a></div>`;
                member_html += `</li>`;
            }

            let percentage = (completed_column_count / response.columns.length) * 100;
            $("#totalColumn").html(response.columns.length);
            $("#totalMember").html(response.user.length);
            $("#completed_task_count").html(completed_task_count);
            $("#pending_task_count").html(pending_task_count);
            $("#total_task_count").html(total_task_count);
            $("#completed_column_count").html(completed_column_count);
            $("#total_hours").html(toTimeString(total_hours));
            $("#total_spent").html(total_spent.toFixed(3));
            $("#members").html(member_html);
            $("#columns").html(column_html);
            $("#storage").html(response.storage + "MB");
            $("#attachment").html(response.files);

            $("#percentage").html(
                !isNaN(percentage) ? percentage.toFixed(2) + "%" : 0
            );

            $("#progress_project").attr( "data-original-title",percentage.toFixed(2) + "%");
            $("#progress_width").attr("data-width", percentage.toFixed(2));
            $("#progress_width").css("width", percentage.toFixed(2) + "%");
        },
    }).done(function (msg) {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function toTimeString(seconds) {
        return new Date(seconds * 1000)
            .toUTCString()
            .match(/(\d\d:\d\d:\d\d)/)[0];
    }

    function toHours(seconds) {
        let s = parseFloat(seconds);
        return (s / (60 * 60)).toFixed(3);
    }

    function toTimeStringFromHour(hours){
        var decimalTimeString = hours;
        var n = new Date(0,0);
        n.setSeconds(+decimalTimeString * 60 * 60);
        return n.toTimeString().slice(0, 8);
    }


    /*----------------------
      	    Chart Active
        -------------------------*/
    var ctx = document.getElementById("myChart2");
    var myChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: [],
            datasets: [
                {
                    label: "",
                    data: [],
                    borderWidth: 2,
                    backgroundColor: "#6777ef",
                    borderColor: "#6777ef",
                    borderWidth: 2.5,
                    pointBackgroundColor: "#ffffff",
                    pointRadius: 4,
                },
            ],
        },
        options: {
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return toTimeStringFromHour(tooltipItem.yLabel);
                    }
                }
            },
            legend: {
                display: false,
            },
            scales: {
                yAxes: [
                    {
                        gridLines: {
                            drawBorder: false,
                            color: "#f2f2f2",
                        },
                        ticks: {
                            beginAtZero: false,
                            stepSize: 1,
                        },

                    },
                ],
                xAxes: [
                    {
                        ticks: {
                            display: true,
                        },
                        gridLines: {
                            display: false,
                        },
                    },
                ],
            },
        },
    });


})(jQuery);
