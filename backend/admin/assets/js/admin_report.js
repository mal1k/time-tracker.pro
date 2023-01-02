(function ($) {
    "use strict";

    /*--------------------------
            Daterange Active
        ----------------------------*/
    $('.daterange-cus').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        },
        drops: 'down',
        opens: 'left'
    }).on('change', function(e){
    let date = e.target.value.split(" - ");
    let start = date[0]
    let end = date[1]
        renderReport(start, end);
    });

})(jQuery); 

/*---------------------
    Render Report
---------------------------*/
function renderReport(start = '', end = '', page = '') {
    let reportRoute = $('#report_data').val();
    let html = '', pagination = '', links = '';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        data: { start:start, end:end },
        url: page != '' ? page : reportRoute,
        dataType: 'json',
        success: function(response) {
            $('#total_earning').html(response.total_earning.toFixed(2));
            $('#total_sales').html(response.total_sales);
            $('#total_users').html(response.total_users);
            $('#total_cancelled').html(response.total_cancelled);
            $('#tax').html(response.tax);
            $('#total_active').html(response.total_active);
            $('#total_expired').html(response.total_expired);
            $('#weekly_earning').html(response.weekly_earning.toFixed(2));
            $('#monthly_earning').html(response.monthly_earning.toFixed(2));
            $('#yearly_earning').html(response.yearly_earning.toFixed(2));
            
            if (response.orders) {
            links = response.orders.links
                for (let order of response.orders.data) {
                    let status = {
                        1: "Active",
                        2: "Expired",
                        3: "Pending",
                        0: 'Rejected'
                    }[order.status];
                    let color = {
                        1: "badge-primary",
                        2: "badge-secondary",
                        3: "badge-warning",
                        0: "badge-danger",
                    } [order.status];
                    html += `<tr>
                                <td><a target="_blank" href="/admin/user/invoice/${order.id}">${order.invoice_id}</a></td>
                                <td>${order.plan.name}</td>
                                <td><a target="_blank" href="/admin/user/${order.user.id}">${order.user.name}</a></td>
                                <td>${order.amount}</td>
                                <td>${order.tax}%</td>
                                <td>${order.payment_id}</td>
                                <td>${moment(order.created_at).format('DD-MM-YYYY')}</td>
                                <td>
                                    <span class="badge ${color}">${status}</span>
                                </td>
                                <td><a class="btn btn-primary" target="_blank" href="/admin/order/${order.id}">View</a></td>
                                </tr>` 
                }

                pagination += `<nav aria-label="Page navigation example"><ul class="pagination" id='page_order'></ul></nav>`

            }

            $('#orders').html("")
            $('#orders').html(html);
            $("#order-list").append(pagination);
            if (response.orders.data.length > 0) {
                render_pagination('#page_order', links, start, end)
            }
        }
    })
}
renderReport();

$(document).on('click', $('.page-link'), function(e) {
    if (e.target.dataset.url != undefined) {
        let url = e.target.dataset.url
        let start = e.target.dataset.start
        let end = e.target.dataset.end
        renderReport(start, end, url)
    }
}) 

/*---------------------
    Render Pagination
---------------------------*/
function render_pagination(target, data, start, end){
    $('.page-item').remove();
    if (data.length > 3) {
        $.each(data, function(key,value){
            if(value.label === '&laquo; Previous'){
                if(value.url === null){
                    var is_disabled="disabled";
                    var is_active=null;
                }
                else{
                    var is_active='page-link-no'+key;
                }
                var html='<li class="page-item"><a data-end="'+end+'" data-start="'+start+'" class="fas fa-angle-left page-link '+is_active+'" href="javascript:void(0)" data-url="'+value.url+'"></a></li>';
            }
            else if(value.label === 'Next &raquo;'){
                if(value.url === null){
                    var is_disabled="disabled";
                    var is_active=null;
                }
                else{
                    var is_active='page-link-no'+key;
                }
                var html='<li class="page-item"><a data-end="'+end+'" data-start="'+start+'" class="fas fa-angle-right page-link '+is_active+'" href="javascript:void(0)" data-url="'+value.url+'"></a></li>';
            }
            else{
                if(value.active==true){
                    var is_active="active";
                    var is_disabled="disabled";
                    var url=null;
                }
                else{
                    var is_active='page-link-no'+key;

                    var url=value.url;
                }
                var html='<li class="page-item" ><a data-end="'+end+'" data-start="'+start+'" class="page-link '+is_active+'" href="javascript:void(0)"  data-url="'+url+'">'+value.label+'</a></li>';
            }
            if(value.url !== null){
            $(target).append(html);
            }
        });
    }
}
