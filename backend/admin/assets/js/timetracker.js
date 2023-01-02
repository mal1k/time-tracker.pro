"use strict";



/*-------------------------------
      timetracker page starts
    ----------------------------------*/
var screenshot_status = '';
var getTasksRoute = $('#getTasksRoute').val();
$('#projects').on('change', function (e) {
    var selectedOption = $(this).find(':selected').text();
    var selectedProject = $(this).find(':selected').val();
    var screenshot = $(this).find(':selected').data('screenshot')
    var gps = $(this).find(':selected').data('gps')
    let html = '';
    $('#project_id').val(selectedProject)
    $('#gps').val(gps)
    $('#screenshot').val(screenshot)
    $('.btnText').text(selectedOption);
    setScreenshotStatus(screenshot);
    getLocation(gps)

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: getTasksRoute,
        data: {
            id: selectedProject
        },
        dataType: 'json',
        success: function (response) {
            html += `<option value="">Select Task</option>`
            for (let i in response) {
                let tasks = response[i].pending_task ?? response[i].pending_task_with_user
                for (let k in tasks) {
                    let task = tasks[k]
                    html += `<option value="${task.id}">${task.name}</option>`
                }
            }
            // $('#tasks').html("");
            $('#tasks').html(html);
            $('.selectric').selectric();
        }
    })
});

$('#tasks').on('change', function () {
    var selectedOption = $(this).find(':selected').text();
    var selectedTask = $(this).find(':selected').val();
    $('#task_id').val(selectedTask)
    $('.btnText').text(selectedOption);
});
$(document).on('click', '#filterdropdown', function (e) {
    e.stopPropagation();
    e.preventDefault();
});

/*-------------------------------
    timetracker page starts
  ----------------------------------*/
var screenshots = [];
var timeCountdown;
var network;
var title = document.title;


function setScreenshotStatus(status) {
    screenshot_status = status == 0 ? false : true
}


setScreenshotStatus(document.getElementById("screenshot").value);

/*
 *  Copyright (c) 2018 The WebRTC project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a BSD-style license
 *  that can be found in the LICENSE file in the root of the source
 *  tree.
 */

// Polyfill in Firefox.
// See https://blog.mozilla.org/webrtc/getdisplaymedia-now-available-in-adapter-js/
if (adapter.browserDetails.browser == "firefox") {
    adapter.browserShim.shimGetDisplayMedia(window, "screen");
}
const startButton = document.getElementById("startButton");
const spinner = startButton.childNodes[1];
var localMediaStream = null;
var dataURL = null;
var imageURL = null;
var screenshot = false;
var second = 5000;
var captured = {};
$("#canvas").attr("width", screen.width);
$("#canvas").attr("height", screen.height);
var time;

(function () {
    var screenshot = screenshot_status;

    // Our element ids.
    var options = {
        video: "#gum-local",
        canvas: "#canvas",
        captureBtn: "#capture-btn",
        imageURLInput: "#image-url-input",
    };
    $("#canvas").hide();
    // Our object that will hold all of the functions.
    var App = {
        // Get the video element.
        video: document.querySelector(options.video),
        // Get the canvas element.
        canvas: document.querySelector(options.canvas),
        // Get the canvas context.
        ctx: canvas.getContext("2d"),
        // Get the capture button.
        captureBtn: document.querySelector(options.captureBtn),
        // This will hold the video stream.
        localMediaStream: null,
        // This will hold the screenshot base 64 data url.
        dataURL: null,
        // This will hold the converted PNG url.
        imageURL: null,
        // Get the input field to paste in the imageURL.
        imageURLInput: document.querySelector(options.imageURLInput),

        initialize: function () {
            var that = this;
            // Check if navigator object contains getUserMedia object.
            var video = document.querySelector("#gum-local");

            function handleSuccess(stream) {
                if (screenshot == true) {
                    video.srcObject = stream;
                    localMediaStream = stream;
                    start = true;
                    $('#share_screen_enable').removeClass('d-none');
                } else {
                    $('#share_screen_enable').addClass('d-none');
                }
                // demonstrates how to detect that the user has stopped
                // sharing the screen via the browser UI.

                stream.getVideoTracks()[0].addEventListener("ended", () => {
                    // Resetting the stream
                    // Reset data object
                    resetStream();
                    data = {};
                });

                second = randTime()
                screenshotTimer(second);
            }

            function handleError(error) {
                errorMsg(`getDisplayMedia error: ${error.name}`, error);
            }

            function errorMsg(msg, error) {
                resetStream();
                const errorElement = document.querySelector("#errorMsg");
            }

            startButton.addEventListener("click", () => {
                console.log('click!');
                if ((!$('#projects').val() && !$('#task').val()) && !$('#task_id').val()) {
                    Sweet('error', 'Please select any task or project!');
                } else {

                    var btn_target = startButton.dataset.task;
                    if (btn_target == "stop") {
                        // Resetting the stream and remove screenshots
                        resetStream();
                    } else {
                        // Start or restart the stream
                        startStream(handleSuccess, handleError);
                    }
                }

            });

            if (
                navigator.mediaDevices &&
                "getDisplayMedia" in navigator.mediaDevices
            ) {
                startButton.disabled = false;
            } else {
                errorMsg("getDisplayMedia is not supported");
            }
        },

        // Capture frame from live video stream.
        capture: function () {
            var that = this;
            // Check if has stream.
            if (localMediaStream) {
                // Draw whatever is in the video element on to the canvas.
                var video = document.querySelector("#gum-local");

                that.ctx.drawImage(video, 0, 0);
                // Create a data url from the canvas image.
                dataURL = canvas.toDataURL("image/png");
                // Call our method to save the data url to an image.
                that.saveDataUrlToImage();
            }
        },

        saveDataUrlToImage: function () {
            var that = this;
            var captured = {};
            var options = {
                // Change this to your own url.
                url: "http://example.com/dataurltoimage",
            };
            var current = new Date();
            var local_time = new Date().toLocaleTimeString();

            var prev =
                '<div class="col-lg-2 mt-1"> <div class="screenshot-area"> <img class="screenshot-img" src="' +
                dataURL +
                '"><span>' +
                local_time +
                "</span></div></div>";
            $("#screenshot_area").append(prev);

            captured["local_time"] = local_time;
            captured["file"] = dataURL;
            captured["full_activity"] = current;

            uploadScreenshot(captured);
        },
    };

    function randTime() {
        var arr = [5, 6, 7];
        var rand = arr[(Math.random() * arr.length) | 0];
        return second = rand * 60000;
    }
    // Initialize our application.
    App.initialize();

    // Expose to window object for testing purposes.
    window.App = App;

    $("#add_track").on("click", function () {
        var action_url = $("#action_url").val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            url: action_url,
            type: "POST",
            dataType: "json",
            data: {
                useractivity: screenshots
            },
            complete: function (xhr, textStatus) {
                // Request complete.
            },
            // Request was successful.
            success: function (response, textStatus, xhr) {

            },
            error: function (xhr, textStatus, errorThrown) { },
        });
    });

    function uploadScreenshot(screenshot) {
        var screenshoturl = $("#screenshoturl").val();
        var project_id = $("#project_id").val();
        var task_id = $("#task_id").val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            url: screenshoturl,
            type: "POST",
            dataType: "json",
            data: {
                project_id: project_id,
                task_id: task_id,
                screenshots: screenshot,
            },
            complete: function (xhr, textStatus) {
                // Request complete.
            },
            // Request was successful.
            success: function (response, textStatus, xhr) {

            },
            error: function (xhr, textStatus, errorThrown) { },
        });
    }

    function timeStop() {
        
        //DB format current time string
        var endtime = getCurrentTimeDb();
        var time_stop_url = $("#time_stop").val();
        var project_id = $("#project_id").val();
        var task_id = $("#task_id").val();
        var started_at = localStorage.getItem("started_at");
        var end_at = endtime;
        startButton.disabled = true;
        spinner.classList.add('spinner-border')
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            url: time_stop_url,
            type: "POST",
            dataType: "json",
            data: {
                project_id: project_id,
                task_id: task_id,
                end_at: end_at,
                started_at: started_at,
            },
            complete: function (xhr, textStatus) {
                // Request complete.
            },
            // Request was successful.
            success: function (response, textStatus, xhr) {
                spinner.classList.remove('spinner-border')
                localStorage.removeItem("started_at");
                localStorage.removeItem("end_at");
                Sweet('success', response)
                startButton.disabled = false;
                $('#share_screen_enable').addClass('d-none');
                $('.showRecentProjects').load(' .showRecentProjects');
            },
            error: function (xhr, textStatus, errorThrown) { },
        });
    }

    function btnChange(trigger) {
        if (trigger == "stop") {
            startButton.dataset.task = "start";
            startButton.querySelector('.text').innerHTML = "START";
            startButton.style.background = "#6777ef";
        } else {
            startButton.dataset.task = "stop";
            startButton.querySelector('.text').innerHTML = "STOP";
            startButton.style.background = "#dc3545";
        }
    }

    function startTimer(reset = true) {
        var timer = document.querySelector("#timer");
        var seconds = 0;
        var limit = 999999;
        if (reset == false) {
            document.title = title;
            clearInterval(timeCountdown);
            clearInterval(time);
            time = null;
            timer.innerHTML = toTimeString(0);
        } else {
            timeCountdown = setInterval(function () {
                seconds++;
                detectNetwork()
                if (seconds <= limit) {
                    timer.innerHTML = toTimeString(seconds);
                    document.title = toTimeString(seconds) + " | " + title;
                }
            }, 1000);
        }
    }

    //audio object
    function audio() {
        var audio = document.createElement("AUDIO")
        audio.setAttribute('allow', 'autoplay')
        audio.setAttribute('autoplay', 'autoplay')
        document.body.appendChild(audio);
        audio.src = $('#alertfile').val();
        audio.currentTime = 0;
        return audio
    }

    //prev state of network
    let prevstatus = window.navigator.onLine

    //Detect currenct state of netwrok
    function detectNetwork() {
        var newstatus = window.navigator.onLine
        let flag;

        if (newstatus == true) {
            flag = 1
        } else if (newstatus == false) {
            flag = 0
        }

        //swap status values
        if (prevstatus != newstatus) {
            showAlert(flag, network)
            let temp = prevstatus
            prevstatus = newstatus
            newstatus = temp
        }

    }

    //screenshot timer
    function screenshotTimer(seconds) {
        if (!time) {
            time = setInterval(function () {
                App.capture()
            }, seconds);
        }
    }

    //show network status alert and reset status
    function showAlert(flag) {
        if (flag == 1) {
            Swal.close();
            screenshot = true
            startButton.disabled = false;
            screenshotTimer(randTime());
        } else if (flag == 0) {
            startButton.disabled = true;
            screenshot = false
            clearInterval(time);
            time = null;
            // play audio
            audio().play
            Swal.fire('You Are Offline! 😭😢😪', 'Please re-connect to save your data!', 'error')
        }
    }

    detectNetwork();

    function toTimeString(seconds) {
        //HH:MM:SS format
        return new Date(seconds * 1000).toUTCString().match(/(\d\d:\d\d:\d\d)/)[0];
    }

    function toSeconds(timeString) {
        var hms = timeString; // your input string
        var a = hms.split(":"); // split it at the colons
        // minutes are worth 60 seconds. Hours are worth 60 minutes.
        var seconds = +a[0] * 60 * 60 + +a[1] * 60 + +a[2];
        return seconds;
    }

    function resetStream() {
        screenshot = false;
        startTimer(false);
        btnChange("stop");
        $("#gum-local").css("display", "none");
        $("#screenshot_area").css("visibility", "hidden");
        $("#screenshot_area").html("");
        localMediaStream?.getTracks().forEach((track) => track.stop());
       
        if (start == true || screenshot_status == 0) {
            timeStop();
        }
    }

    var start = false;
    function startStream(handleSuccess, handleError) {
        screenshot = screenshot_status;
        btnChange("start");
        if (screenshot == true) {
            navigator.mediaDevices.getDisplayMedia({
                video: true
            }).then(handleSuccess, handleError);
            $("#gum-local").css("display", "block");
            $("#screenshot_area").css("visibility", "visible");
        }
        var currentDateTime = getCurrentTimeDb();
        localStorage.setItem("started_at", currentDateTime);
        startTimer();
    }

    function getCurrentTimeDb() {
        return new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60 * 1000).toJSON().slice(0, 19).replace("T", " ")
    }


    window.addEventListener("beforeunload", function (e) {


        if (startButton.dataset.task == "stop") {
            e.preventDefault();
            e.returnValue = "";
        }
    });
})();
