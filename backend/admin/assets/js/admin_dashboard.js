(function ($) {
    "use strict";

  /*----------------
      Day Change
    ---------------------*/
  var period=$('#days').val();
  $('#days').on('change',()=>{
    period = $('#days').val();
  })

  /*---------------------
      dashboard statics
    ---------------------------*/
  var base_url=$("#base_url").val();
  var dashboard_static_url=$("#dashboard_static").val();
  loadStaticData();
  load_perfomace(30);
  dashboard_order_statics($('#month').val());
  $('#perfomace').on('change',function(){
    var period=$('#perfomace').val();
    load_perfomace(period);
  });

  $('.month').on('click',function(e){
    $('.month').removeClass('active');
    $(this).addClass("active");
    var month=e.currentTarget.dataset.month;
    $('#orders-month').html(month);
    dashboard_order_statics(month);
  });

  /*-----------------------------
      dashboard order statics
    ----------------------------------*/
  function dashboard_order_statics(month) {
    var url = base_url+'/admin/get_monthly_order';
    var gif_url= $('#gif_url').val();
    var html="<img height='40' src="+gif_url+">";
    $('#pending_order').html(html);
    $('#completed_order').html(html);
    $('#total_order').html(html);
    $('#cancelled').html(html);
    $.ajax({
      type: 'get',
      url:url+'/'+month,
      dataType: 'json',
      
      success: function(response){ 
        $('#pending_order').html(response.pending);
        $('#completed_order').html(response.complete);
        $('#total_order').html(response.total);
        $('#cancelled').html(response.cancel);
      }
    })
  }

  /*-------------------
      load perfomace
    ------------------------*/
  function load_perfomace(period) {
    $('#earning_performance').show();
    var url=base_url+'';
    $.ajax({
      type: 'get',
      url: url+'/admin/earning_performance/'+period,
      dataType: 'json',
      
      success: function(response){ 
        $('#earning_performance').hide();
        var month_year=[];
        var dates=[];
        var totals=[];
        
        if (period != 365) {
          $.each(response, function(index, value){
            var total=value.total;
            var dte=value.date;
            totals.push(total);
            dates.push(dte);
          });
          
          load_perfomace_chart(dates,totals);
        }
        else{
          $.each(response, function(index, value){
            var month=value.month;
            var total=value.total;
            month_year.push(month);
            totals.push(total);
          });
          load_perfomace_chart(month_year,totals);
        }
        
      }
    })
  }

  /*-------------------
      loadStaticData
    --------------------------*/
  function loadStaticData() {
    var url = base_url+'/admin/get_static_data';
    $.ajax({
      type: 'get',
      url: url,
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,
      success: function(response){ 
        $('#balance').html(response.balance);
        $('#sales').html(response.sales);
        
        var dates=[];
        var totals=[];
        $.each(response.earning_static, function(index, value){
          var dat=value.month+' '+value.year;
          var total=value.total;
          dates.push(dat);
          totals.push(total);
        });
        sales_of_earnings_chart(dates,totals);
        var dates=[];
        var sales=[];
        $.each(response.sale_static, function(index, value){
          var dat=value.month+' '+value.year;
          var sale=value.sales;
          dates.push(dat);
          sales.push(sale);
        });
        order_chart(dates,sales);
        
        
      },
      error: function(xhr, status, error){
        
        
      }
    })
  }

  /*------------------------------
      Earning Performance Chart
    --------------------------------*/
  var ctx = document.getElementById("earning_performance_chart").getContext('2d');
  function load_perfomace_chart(dates,totals) {
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: dates,
        datasets: [{
          label: 'Total Amount',
          data: totals,
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
          stepSize: 1500,
          callback: function(value, index, values) {
            return  value;
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
  }

  function lower(str) {
    var str= str.toLowerCase();
    var str=str.replace(' ',str);
    return str;
  }

  function number_format(number) {
    var num= new Intl.NumberFormat( { maximumSignificantDigits: 3 }).format(number);
    return num;
  }

  function percentage(partialValue, totalValue) {
      var n= (100 / totalValue) * partialValue;
    
      return parseInt(n);
  } 

  /*--------------------------
      Sales Of Earnings Chart
    -------------------------------*/
  var balance_chart = document.getElementById("sales_of_earnings_chart").getContext('2d');
  var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
  balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
  balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');
  function sales_of_earnings_chart(dates,totals) {
    var myChart = new Chart(balance_chart, {
      type: 'line',
      data: {
        labels: dates,
        datasets: [{
          label: 'Total Amount',
          data: totals,
          backgroundColor: balance_chart_bg_color,
          borderWidth: 3,
          borderColor: 'rgba(63,82,227,1)',
          pointBorderWidth: 0,
          pointBorderColor: 'transparent',
          pointRadius: 3,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(63,82,227,1)',
        }]
      },
      options: {
        layout: {
          padding: {
            bottom: -1,
            left: -1
          }
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              display: false,
              drawBorder: false,
            },
            ticks: {
              beginAtZero: true,
              display: false
            }
          }],
          xAxes: [{
            gridLines: {
              drawBorder: false,
              display: false,
            },
            ticks: {
              display: false
            }
          }]
        },
      }
    });
  }

  /*----------------------
      Total Sales Chart
    --------------------------*/
  var sales_chart = document.getElementById("total-sales-chart").getContext('2d');
  var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
  balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
  balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');
  function order_chart(dates,sales) {
    var myChart = new Chart(sales_chart, {
      type: 'line',
      data: {
        labels: dates,
        datasets: [{
          label: 'Orders',
          data: sales,
          borderWidth: 2,
          backgroundColor: balance_chart_bg_color,
          borderWidth: 3,
          borderColor: 'rgba(63,82,227,1)',
          pointBorderWidth: 0,
          pointBorderColor: 'transparent',
          pointRadius: 3,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(63,82,227,1)',
        }]
      },
      options: {
        layout: {
          padding: {
            bottom: -1,
            left: -1
          }
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              display: false,
              drawBorder: false,
            },
            ticks: {
              beginAtZero: true,
              display: false
            }
          }],
          xAxes: [{
            gridLines: {
              drawBorder: false,
              display: false,
            },
            ticks: {
              display: false
            }
          }]
        },
      }
    }); 
  }

})(jQuery); 