'use strict';
$(function() {

    let width = $('.progress-bar').data('width');
    $('.progress-bar').css('width', width);
    /*--------------------
            User Details 
        -----------------------*/
    let user_details = $('#user_details').val();
    let html = '',
        pagination = '',
        start ='',
        end='',
        links = '';
    $('#daterange').daterangepicker({
        drops: 'down',
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                'month').endOf('month')]
        },
        "alwaysShowCalendars": true
    }).on('change', function(e){
    let date = e.target.value.split(" - ");
    let start = date[0]
    let end = date[1]
    renderDashboard('',start, end);
    });


    /*--------------------
           renderDashboard 
        -----------------------*/
    function renderDashboard(page = '',start='',end='') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            data: { start:start, end:end },
            url: page != '' ? page : user_details,
            dataType: 'json',
            success: function(response) {
                $('#total_time').html(toTimeString(response.total_time.toFixed(2)))
                $('#total_task').html(response.total_task)
                $('#completed_task').html(response.completed_task)
                $('#pending_task').html(response.pending_task)
                $('#completed_project').html(response.completed_project)
                $('#running_project').html(response.running_project.total)
                $('#tax').html(response.tax)
                $('#total_earning').html(response.total_earning.toFixed(2))
                $('.expire_loader').hide();
                $('.plan_name').html(response.user_plan.name);
                $('.user_limit').html(response.user_limit + ' / ' + response.user_plan.user_limit);
                $('.group_limit').html(response.group_limit + ' / ' + response.user_plan.group_limit);
                $('.project_limit').html(response.project_limit + ' / ' + response.user_plan.project_limit);
                $('.storage').html(response.storage + ' MB');
                $('.plan_expire').html(moment(response.expired_at).format('LL'));
                html = "";
                links = response.running_project.links
                let counter = 1;
                for (let i in response.running_project.data) {
                    links.length;
                    let running_project = response.running_project.data[i].running_project
                    html += `<tr>`
                    html += `<td><a href="/user/project/${running_project.id}">${running_project.name}</a></td>`
                    html += `<td>${running_project.description.slice(0, 15)}...</td>`

                    html +=
                        `<td>${moment(running_project.starting_date).format('MM-DD-YYYY')}</td>`
                    html +=
                        `<td>${moment(running_project.ending_date).format('MM-DD-YYYY')}</td>`
                    html +=
                        `<td>${moment(running_project.created_at).format('MM-DD-YYYY')}</td>`
                    html += `</tr>`
                }
                if (response.running_project.from != response.running_project.last_page) {
                    pagination +=`<nav aria-label="Page navigation example"><ul class="pagination" id='page'></ul></nav>`
                }
                $('#projects').html("")
                $('#projects').html(html);
                $("#pagination-container").html(pagination);
                render_pagination('#page', links)
            }
        })
    }
    renderDashboard()

     /*--------------------
           toTimeString 
        -----------------------*/
    function toTimeString(seconds) {
        return new Date(seconds * 1000)
            .toUTCString()
            .match(/(\d\d:\d\d:\d\d)/)[0];
    }

    $(document).on('click', $('.page-link'), function(e) {
        if (e.target.dataset.url != undefined) {
            let url = e.target.dataset.url
            renderDashboard(url)
        }
    })

    /*-------------------------
           Render Pagination 
        --------------------------*/
    function render_pagination(target, data) {
        if(data.length > 3){
        $('.page-item').remove();
        $.each(data, function(key, value) {
            if (value.label === '&laquo; Previous') {
                if (value.url === null) {
                    var is_disabled = "disabled";
                    var is_active = null;
                } else {
                    var is_active = 'page-link-no' + key;
                }
                var html = '<li class="page-item"><a  class="fas fa-angle-left page-link ' +
                    is_active + '" href="javascript:void(0)" data-url="' + value.url +
                    '"></a></li>';
            } else if (value.label === 'Next &raquo;') {
                if (value.url === null) {
                    var is_disabled = "disabled";
                    var is_active = null;
                } else {
                    var is_active = 'page-link-no' + key;
                    // var is_disabled='onClick="PaginationClicked()"';
                }
                var html = '<li class="page-item"><a  class="fas fa-angle-right page-link ' +
                    is_active + '" href="javascript:void(0)" data-url="' + value.url +
                    '"></a></li>';
            }
            if (value.active == true) {
                var is_active = "active";
                var is_disabled = "disabled";
                var url = null;
            } else {
                var is_active = 'page-link-no' + key;
                var url = value.url;
            }
            var html = '<li class="page-item" ><a  class="page-link ' + is_active +
                '" href="javascript:void(0)"  data-url="' + url + '">' + value.label + '</a></li>';


        if (value.url !== null) {
            $(target).append(html);
        }

    });

    }
    }
    

    /*-------------------------
           Render Chart 
        --------------------------*/
    var labels = [];
    var data = [];
    var total_hours = 0;
    function renderChart() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: $('#recent_stats').val(),
            dataType: 'json',
            success: function(response) {
                for (let i of response) {
                    total_hours += i.time;
                    data.push(toHours(i.time))
                    labels.push(i.project)
                }

                myChart.data.datasets[0].data = data;
                myChart.data.labels = labels;
                myChart.update();
            }
        })
    }
    
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
        data: [],
        borderWidth: 2,
        backgroundColor: 'rgba(63,82,227,.8)',
        borderWidth: 0,
        borderColor: 'transparent',
        pointBorderWidth: 0,
        pointRadius: 3.5,
        pointBackgroundColor: 'transparent',
        pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
        }]
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
        display: false
        },
        scales: {
        yAxes: [{
            gridLines: {
            drawBorder: false,
            color: '#f2f2f2',
            },
            ticks: {
            beginAtZero: true,
            stepSize: 1,
            callback: function(value, index, values) {
                return value;
            }
            }
        }],
        xAxes: [{
            gridLines: {
            display: false,
            tickMarkLength: 15,
            }
        }]
        },
    }
    });
    renderChart()
    
    /*----------------
           toHours 
        -----------------*/
    function toHours(seconds) {
        let s = parseFloat(seconds);
        return (s / (60 * 60)).toFixed(3);
    }
    
    /*-----------------------------
           toTimeStringFromHour 
        -------------------------------*/
    function toTimeStringFromHour(hours){
        var decimalTimeString = hours;
        var n = new Date(0,0);
        n.setSeconds(+decimalTimeString * 60 * 60);
        return n.toTimeString().slice(0, 8);
    }
});