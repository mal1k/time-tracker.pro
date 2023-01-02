(function ($) { 
    "use strict";

    /*-------------------------
        Project attachments
    -----------------------------*/
    if ($('#project_attachments_url').length) {
    let id = $('#column_id').val();
    let arr = [];
    let html = '';
    let url = $('#url').val();
    var attachment_url = $('#project_attachments_url').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url:  attachment_url,
        data: {id: id},
        dataType: 'json',
        success: function (response) {
            console.log(response)
            if (response) {
                for(let i in response){
                    let comment = response[i].comment
                    for(let k in comment){
                        if (comment[k].attachment != null) {
                            arr.push({img: comment[k].attachment.file_name, time: comment[k].created_at})
                        }
                    }
                }
            }
            for (let i in arr) {
                let img = arr[i].img.substr(arr[i].img.lastIndexOf('/') + 1)
                html += `<ul class="list-group">
                <li class="list-group-item">
                <a target='_blank' href="${url}/${arr[i].img}">${img}</a>
                <span class="float-right">${moment(arr[i].time).format('YYYY-MM-DD hh:mm A')}</span>
                </li>
                </ul>`   
            }
            
            $('#attachments').html(html);
        },
        error: function (error) {
            var id = document.getElementById('loader'); 
            if (id) {
                setInterval(() => {
                    document.getElementById('loader').classList.add('d-none')
                }, 1000);
            }
        }
    })
    }
    

    /*------------------
        Progress Bar
    -----------------------*/
    $.each($('.progress-bar'), function(e, val) {
        let id = $(this).attr('id')
        let width = $(this).data('width')
        $('#' + id).css('width', width + 'px')
    })

    /*---------------------
        Filter Dropdown
    -------------------------*/
    $(document).on('click', '.filterdropdown', function(e) {
        e.stopPropagation();
        e.preventDefault();
    });

    /*----------------------
        Daterange Active
    ---------------------------*/
    
    if ($('#daterange').length) {
        $('#daterange').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            drops: 'down',
            opens: 'left',
            ranges: {
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            },
            "alwaysShowCalendars": true
        });
    }


    })(jQuery);

    /*---------------------
        Validate file
    -------------------------*/
    function validate(file) {
        var ext = file.split(".");
        ext = ext[ext.length - 1].toLowerCase();
        var arrayExtensions = ["jpg" , "jpeg", "png", "bmp", "gif"];
        if (arrayExtensions.lastIndexOf(ext) == -1) {
            return false;
        }
        return true;
    }

    /*--------------
        Get file
    -----------------*/
    function getFile(file) {
        var ext = file.split("/");
        return ext[ext.length - 1].toLowerCase();
    }
    
    let locations = [];
    $('.gps').each(function(i, element){
        locations.push({0 : $(this).data('lat'), 1: $(this).data('lng')})    
    })

    console.log(locations);
    /*---------------
        Map Active
    ------------------*/
    function initMap() {

        var map = new google.maps.Map(document.getElementById('gpsmap'), {
            zoom: 13,
            center: new google.maps.LatLng(locations[0][0], locations[0][1]),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;
            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                    map: map
                });
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
        }
        // let lat = $('#lat').val();
        // let lng = $('#lng').val();
        // The location of Uluru
        // const uluru = {
        //     lat: 22.3948,
        //     lng: 91.8365
        // };
        // // The map, centered at Uluru
        // const map = new google.maps.Map(document.getElementById("gpsmap"), {
        //     zoom: 15,
        //     center: uluru,
        // });
        // // The marker, positioned at Uluru
        // const marker = new google.maps.Marker({
        //     position: uluru,
        //     map: map,
        // });
    }
    