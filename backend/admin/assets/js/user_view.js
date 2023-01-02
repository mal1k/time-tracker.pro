"use strict";

/*--------------------
       User Details 
    -----------------------*/
let user_id = $("#user_id").val();
let user_details = $("#user_details").val();
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$.ajax({
    type: "POST",
    data: { user_id: user_id },
    url: user_details,
    dataType: "json",
    success: function (response) {
        let html = "";
        for (let order of response.order_list) {
            let status = {
                0: { class: "badge-danger", text: "Rejected" },
                1: { class: "badge-primary", text: "Accepted" },
                2: { class: "badge-danger", text: "Expired" },
                3: { class: "badge-warning", text: "Pending" },
            }[order.status];
            html += `<tr>`;
            html += `<td>${order.plan.name}</td>`;
            html += `<td><span class="badge ${status.class}">${status.text}</span></td>`;
            html += `<td>${moment(order.created_at).format("MM-DD-YYYY")}</td>`;
            html += `<td><a href="/admin/order/${order.id}" class="btn btn-primary">View</a></td>`;
            html += `</tr>`;
        }
        $("#total_spent").html(response.amount.toFixed(2));
        $("#total_group").html(response.groups);
        $("#total_members").html(response.members);
        $("#total_orders").html(response.orders);
        $("#storage_used").html(response.percentage + "%");
        $("#storage").html(response.storage);
        $("#team").html(response.team);
        $("#order_list").html(html);
    },
});
