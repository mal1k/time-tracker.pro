(function ($) {
    "use strict";

    /*--------------------
       deletescreenshot 
    -------------------------*/
    let projectdeleteIds = [];
    let taskdeleteIds = [];
    let id = $("#user_id").val();
    let url = $("#url").val();
    let statsroute = $("#statsroute").val();
    let getStats = $("#getStats").val();
    let project_id = $("#project_id").val();
    let arr = [];
    let html = "";
    let deletescreenshot = $("#deletescreenshot").val();
    var time = [];
    var labels = [];
    function renderUserDetails(url = '', target='') {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            type: "GET",
            url: url == '' ? statsroute : url,
            data: { id: id, project_id: project_id, target: target },
            dataType: "json",
            success: function (response) {
                var totalTime = 0;
                $("#total_task_count").html(response.total);
                $("#completed_task_count").html(response.completed);
                $("#pending_task_count").html(response.pending);
                let taskscrHtml = "";
                let projectscrHtml = "";
                let html = "";
                let taskmodal = "";
                let modal = "";
                let projectmodal = "";
                time = [];
                labels = [];

                response.seconds.forEach((val) => {
                    totalTime += val.totaltime;
                    time.push(toHours(val.totaltime));
                    labels.push(val.date);
                });

                $("#total_hours").html(toTimeString(totalTime));
                myChart.data.datasets[0].data = time;
                myChart.data.labels = labels;
                myChart.update();
               
                $(".taskscrtable").html("");
                $(".projectscrtable").html("");

                if (target != '') {
                    $(`.${target}modalAppend`).html("");
                    let obj = eval(`response.${target}_screenshots`)
                    html = renderHtmlTable(obj, target, response.has_access);
                    $(`#${target}scrtable`).html(html);
                    modal = renderHtmlModal(obj.data);
                    $(`.${target}modalAppend`).append(modal);
                } else {
                    $(".projectmodalAppend").html("");
                    $(".taskmodalAppend").html("");
                    projectscrHtml = renderHtmlTable(response.project_screenshots, 'project', response.has_access);
                    taskscrHtml = renderHtmlTable(response.task_screenshots, 'task', response.has_access);
                    projectmodal = renderHtmlModal(response.project_screenshots.data);
                    taskmodal = renderHtmlModal(response.task_screenshots.data);
                    $("#projectscrtable").html(projectscrHtml);
                    $("#taskscrtable").html(taskscrHtml);
                    $(".projectmodalAppend").append(projectmodal);
                    $(".taskmodalAppend").append(taskmodal);
                }

               

                $('.projectcheckbox').on('change', function () {
                    $('.projectdelete').prop('selectedIndex', 0);
                });

                $('.taskcheckbox').on('change', function () {
                    $('.taskdelete').prop('selectedIndex', 0);
                });

                $('.deletesingle').on('click', function () {
                    deleteFunction([$(this).data('id')], project_id)
                })
            },
        });
    }

    /*--------------
       deleteScr  project
    --------------------*/
    $(".projectdelete").on("change", function (e) {
        projectdeleteIds = []
        $.each($(".projectcheckbox"), function () {
            if ($(this).filter(":checked").val()) {
                projectdeleteIds.push($(this).filter(":checked").val())
            }
        })
        if ($(this).val() == '0' && projectdeleteIds.length > 0) {
            deleteFunction(projectdeleteIds, project_id)
        }
    });


    /*--------------
      deleteScr  task
   --------------------*/
    $(".taskdelete").on("change", function (e) {
        taskdeleteIds = []
        $.each($(".taskcheckbox"), function () {
            if ($(this).filter(":checked").val()) {
                taskdeleteIds.push($(this).filter(":checked").val())
            }
        })
        if ($(this).val() == '0' && taskdeleteIds.length > 0) {
            deleteFunction(taskdeleteIds, project_id)
        }
    });

    /*--------------
       toHours 
    --------------------*/
    function toHours(seconds) {
        let s = parseFloat(seconds);
        return (s / (60 * 60)).toFixed(3);
    }

    /*--------------
       toTimeString 
    --------------------*/
    function toTimeString(seconds) {
        return new Date(seconds * 1000)
            .toUTCString()
            .match(/(\d\d:\d\d:\d\d)/)[0];
    }

    renderUserDetails();

    /*--------------
       Chart Active 
    --------------------*/
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
                    label: function (tooltipItem, data) {
                        return toTimeStringFromHour(tooltipItem.yLabel);
                    },
                },
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

    $(".checkAllproject").on('click', function () {
        $('input.projectcheckbox:checkbox').not(this).prop('checked', this.checked);
    });


    $(".checkAlltask").on('click', function () {
        $('input.taskcheckbox:checkbox').not(this).prop('checked', this.checked);
    });
    /*------------------------
       toTimeStringFromHour 
    -------------------------------*/
    function toTimeStringFromHour(hours) {
        var decimalTimeString = hours;
        var n = new Date(0, 0);
        n.setSeconds(+decimalTimeString * 60 * 60);
        return n.toTimeString().slice(0, 8);
    }


    function deleteFunction(ids, project_id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to recover this file!",
            icon: "warning",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            showCancelButton: true,
        }).then((result) => {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                $.ajax({
                    type: "POST",
                    url: deletescreenshot,
                    data: { ids: ids, project_id: project_id },
                    dataType: "json",
                    success: function (response) {
                        Sweet("success", response);
                        renderUserDetails();
                    },
                });
            }
        });
    }

    function renderHtmlModal(data) {
        let html = ''
        for (const element of data) {
            html += `<div class="modal fade" id="modal${element.screenshot.id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <img class="w-100" src="${url}/${element.screenshot.file}">
                Taken : ${element.screenshot.full_activity}<br>
                IP : ${element.screenshot.ip}<br>
                </div>
            </div>
            </div>
            </div>`;
        }
        return html;
    }

    function renderHtmlTable(screenshots, target, has_access) {
        let html = ''
        screenshots.data.forEach((element, key) => {
            let dateObj = new Date(element.screenshot.created_at);
            html += `<tr>`
            html += `<td><div class="custom-checkbox custom-control">
                <input type="checkbox" data-checkboxes="mygroup" value="${element.screenshot.id}"
                class="custom-control-input ${target}checkbox" id="checkbox-${element.screenshot.id}">
                <label for="checkbox-${element.screenshot.id}" class="custom-control-label">&nbsp;</label>
            </div>
            </td>`
            html += `<td>${key + 1}</td>`
            html += `<td><img src="${url}/${element.screenshot.file}" class="image-thumbnail"></td>`
            html += `<td>${moment(dateObj).format("DD-MM-YYYY")} ${element.screenshot.time}</td>`
            html += `<td><button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#modal${element.screenshot.id}"><i class="fa fa-eye"></i></button>`
            if (has_access) {
                html += `<button type="button" class="btn btn-danger deletesingle" data-id="${element.screenshot.id}"><i class="fa fa-trash"></i></button>`
            }
            html += `</td></tr>`
        });
        if (screenshots.total > screenshots.per_page) {
            render_pagination(target, screenshots.links)
        }
        return html;
    }

    $(document).on('click', $('.page-link'), function(e) {
        if (e.target.dataset.url != undefined) {
            let url = e.target.dataset.url
            let target = e.target.dataset.type
            renderUserDetails(url,target)
        }
      }) 

    /*----------------------
        Render Pagination 
      -------------------------*/
    function render_pagination(target, data) {
        $(`.page-item.${target}_paginate`).remove();
        var html = ''
        $.each(data, function (key, value) {
            if (value.label === '&laquo; Previous') {
                if (value.url === null) {
                    var is_disabled = "disabled";
                    var is_active = null;
                }
                else {
                    var is_active = 'page-link-no' + key;
                }
                var html = '<li class="page-item ' + target + '_paginate"><a data-type="'+ target +'" class="fas page-link ' + is_active + '" href="javascript:void(0)" data-url="' + value.url + '">' + value.label + '</a></li>';
            }
            else if (value.label === 'Next &raquo;') {
                if (value.url === null) {
                    var is_disabled = "disabled";
                    var is_active = null;
                }
                else {
                    var is_active = 'page-link-no' + key;
                }
                var html = '<li class="page-item ' + target + '_paginate"><a  data-type="'+ target +'" class="fas page-link ' + is_active + '" href="javascript:void(0)" data-url="' + value.url + '">' + value.label + '</a></li>';
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
                var html = '<li class="page-item ' + target + '_paginate ' + is_active + '"><a class="fas page-link ' + is_active + '" href="javascript:void(0)" data-type="'+ target +'" data-url="' + value.url + '">' + value.label + '</a></li>';
            }
            if (value.url !== null) {
                $('#'+target+ '_paginate').append(html);
            }
        });
        
    };

})(jQuery);
