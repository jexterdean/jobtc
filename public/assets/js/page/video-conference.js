/* 
 * Licode Client for Video Conferencing and Recording
 */

//var serverUrl = "https://128.199.97.94:3333/";
//var recordingUrl = "https://hirefit.co/recordings/";
//var serverUrl = "https://hirefit.co:3333/";
var recordingUrl = "https://laravel.software/recordings/";
var serverUrl = "https://laravel.software:3333/";
var localStream, remoteStream, room, recording, localRecordingId, remoteRecordingId;
var room_id;
var room;
var recordingId;

//var room_name_tmp = $('title').text();
var room_name_tmp = window.location.pathname;
var room_name = room_name_tmp.replace(/[^\w\s]/gi, '');

var streams = [];
var stream;
var room_exists;

$.fn.clickToggle = function (func1, func2) {
    var funcs = [func1, func2];
    this.data('toggleclicked', 0);
    this.click(function () {
        var data = $(this).data();
        var tc = data.toggleclicked;
        $.proxy(funcs[tc], this)();
        data.toggleclicked = (tc + 1) % 2;
    });
    return this;
};
//Check if browser is firefox
var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

$('.nav-tabs a[href="#video-tab"]').click(function () {
    var applicant_text = $('.interview-applicant span').text();
    if (applicant_text === 'Join Conference') {
        $('.interview-applicant').siblings('button').hide();
    }
});

/*Erizo Nodejs socket.io functions*/

var deleteRoom = function (room_id, callback) {

    var req = new XMLHttpRequest();
    var url = serverUrl + 'deleteRoom/';
    //var url = serverUrl;
    var body = {room_id: room_id};

    req.onreadystatechange = function () {
        if (req.readyState === 4) {
            callback(req.responseText);
        }
    };

    req.open('POST', url, true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.send(JSON.stringify(body));

};

var getRoom = function (room_name, callback) {

    var req = new XMLHttpRequest();
    var url = serverUrl + 'getRoom/';
    //var url = serverUrl;
    var body = {room_name: room_name};

    req.onreadystatechange = function () {
        if (req.readyState === 4) {
            callback(req.responseText);
        }
    };

    req.open('POST', url, true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.send(JSON.stringify(body));

};

//Function for telling the server to create a room
var createRoom = function (room_name, callback) {

    var req = new XMLHttpRequest();
    var url = serverUrl + 'createRoom/';
    //var url = serverUrl;
    var body = {room_name: room_name};

    req.onreadystatechange = function () {
        if (req.readyState === 4) {
            callback(req.responseText);
        }
    };

    req.open('POST', url, true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.send(JSON.stringify(body));

};

//Function for telling the server to create a token to connect to a room
var createToken = function (room_id, username, role, callback) {

    var req = new XMLHttpRequest();
    var url = serverUrl + 'createToken/';
    //var url = serverUrl;

    console.log(room_id);
    var body = {room_id: room_id, username: username, role: role};

    req.onreadystatechange = function () {
        if (req.readyState === 4) {
            callback(req.responseText);
        }
    };

    req.open('POST', url, true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.send(JSON.stringify(body));

};

var subscribeToStreams = function (streams) {
    var remote_streams = room.remoteStreams;
    for (var i in remote_streams) {

        console.log(remote_streams[i]);
        var s = remote_streams[i];

        if (localStream.getID() !== s.getID()) {
            room.subscribe(s);
        }

    }
};


var saveRecordingsToDatabase = function (localStreamId, remoteStreamId, video_type) {

    var page_type = $('.page_type').val();

    var stream_id = localStreamId;
    //var file_extension = '.mkv';
    var file_extension = '.webm';
    //var file_extension = '.mp4';

    var video_url = recordingUrl + stream_id + file_extension;

    if (page_type === 'applicant') {
        var applicant_id = $('.page_applicant_id').val();

        console.log('applicant_id: ' + applicant_id);

        var job_id = $('.job_id').val();

        formData = new FormData();
        formData.append('applicant_id', applicant_id);
        formData.append('job_id', job_id);
        formData.append('stream_id', stream_id);
        formData.append('local_stream_id', localStreamId);
        formData.append('remote_stream_id', remoteStreamId);
        formData.append('video_type', video_type);
        formData.append('video_url', video_url);
        formData.append('page_type', page_type);
    }

    if (page_type === 'employee') {
        var employee_id = $('.employee_id').val();
        
        console.log('employee_id: ' + employee_id);

        formData = new FormData();
        formData.append('employee_id', employee_id);
        formData.append('stream_id', stream_id);
        formData.append('local_stream_id', localStreamId);
        formData.append('remote_stream_id', remoteStreamId);
        formData.append('video_type', video_type);
        formData.append('video_url', video_url);
        formData.append('page_type', page_type);
    }

    var ajaxurl = public_path + 'saveVideo';

    $.ajax({
        url: ajaxurl,
        type: "POST",
        data: formData,
        // THIS MUST BE DONE FOR FILE UPLOADING
        contentType: false,
        processData: false,
        beforeSend: function () {

        },
        success: function (data) {
            //$('.save-progress').text(data);
            socket.emit('add-video', data);
            $('.download-complete-sound').get(0).play();

        },
        complete: function () {

        },
        error: function (xhr, status, error) {
            console.log('Error: retrying');

        }
    }); //ajax

}

//Toggle the button to start the video conference
$('.interview-applicant').clickToggle(function () {
    //var room_name_tmp = $('title').text();
    var room_name_tmp = window.location.pathname;
    var room_name = room_name_tmp.replace(/[^\w\s]/gi, '');

    // Select tab by name
    $('.nav-tabs a[href="#video-tab"]').tab('show');
    var config = {audio: true, video: true, data: true, videoSize: [752, 720, 752, 720]};
    localStream = Erizo.Stream(config);

    getRoom(room_name, function (res) {
        console.log(res);
        if (res === "no-room") {
            createRoom(room_name, function (response) {
                room_id = response;

                createToken(room_id, "user", "presenter", function (response) {
                    var token = response;
                    console.log(token);
                    room = Erizo.Room({token: token});
                    localStream.addEventListener("access-accepted", function () {
                        console.log('Mic and Cam OK');

                        room.addEventListener("room-connected", function (roomEvent) {
                            console.log('Connected to the room OK');
                            room.publish(localStream, {maxVideoBW: 3000});
                        });

                        room.addEventListener("room-disconnected", function (roomEvent) {
                            room.unpublish(localStream, {maxVideoBW: 3000});
                        });

                        room.addEventListener("room-error", function (roomEvent) {
                            console.log('Room Error videoconf.js');
                        });


                        room.addEventListener("stream-subscribed", function (streamEvent) {
                            console.log('Subscribed to your local stream OK');
                            stream = streamEvent.stream;
                            remoteStream = stream;
                            stream.play("remoteVideo");
                        });

                        room.addEventListener("stream-added", function (streamEvent) {
                            console.log('Local stream published OK');
                            //streams = [];
                            streams.push(streamEvent.stream);
                            subscribeToStreams(streams);
                        });

                        room.addEventListener("stream-removed", function (streamEvent) {
                            // Remove stream from DOM
                            var stream = streamEvent.stream;
                            if (stream.elementID !== undefined) {
                                var element = document.getElementById(stream.elementID);
                                //document.body.removeChild(element);
                                $("#" + stream.elementID).remove();
                            }
                        });

                        room.addEventListener("stream-failed", function (streamEvent) {
                            console.log("STREAM FAILED, DISCONNECTION");
                            //room.disconnect();
                        });

                        room.connect();

                        localStream.play("localVideo");

                        $('.interview-applicant').addClass('btn-warning');
                        $('.interview-applicant').removeClass('btn-success');
                        $('.interview-applicant').children('span').text('Leave Conference');
                        $('.interview-applicant').siblings('button').show();
                    });
                    localStream.init();
                });
            });
        } else {

            room_id = res;

            createToken(room_id, "user", "presenter", function (response) {
                var token = response;
                console.log(token);
                room = Erizo.Room({token: token});
                localStream.addEventListener("access-accepted", function () {
                    console.log('Mic and Cam OK');
                    room.addEventListener("room-connected", function (roomEvent) {
                        console.log('Connected to the room OK');
                        room.publish(localStream, {maxVideoBW: 3000});
                    });

                    room.addEventListener("room-disconnected", function (roomEvent) {
                        room.unpublish(localStream, {maxVideoBW: 3000});
                    });

                    room.addEventListener("room-error", function (roomEvent) {
                        console.log('Room Error videoconf.js');
                    });

                    room.addEventListener("stream-subscribed", function (streamEvent) {
                        console.log('Subscribed to your local stream OK');
                        var stream = streamEvent.stream;
                        stream.play("remoteVideo");
                    });

                    room.addEventListener("stream-added", function (streamEvent) {
                        console.log('Local stream published OK');
                        //var streams = [];
                        console.log("is local? :" + streamEvent.stream.local);
                        streams.push(streamEvent.stream);
                        subscribeToStreams(streams);
                    });

                    room.addEventListener("stream-removed", function (streamEvent) {
                        // Remove stream from DOM
                        var stream = streamEvent.stream;
                        if (stream.elementID !== undefined) {
                            var element = document.getElementById(stream.elementID);
                            //document.body.removeChild(element);
                            $(stream.elementID).remove();
                        }
                        room.disconnect();
                    });

                    room.addEventListener("stream-failed", function (streamEvent) {
                        console.log("STREAM FAILED, DISCONNECTION");
                        //room.disconnect();
                        streams.push(streamEvent.stream);
                        subscribeToStreams(streams);
                    });

                    room.connect();

                    localStream.play("localVideo");

                    $('.interview-applicant').addClass('btn-warning');
                    $('.interview-applicant').removeClass('btn-success');
                    $('.interview-applicant').children('span').text('Leave Conference');
                    $('.interview-applicant').siblings('button').show();

                });
                localStream.init();
            });
        }
    });
},
        function () {
            $(this).addClass('btn-success');
            $(this).removeClass('btn-warning');
            $(this).children('span').text('Join Conference');
            $(this).siblings('button').hide();

            localStream.close();

            if (remoteStream !== undefined) {
                //remoteStream.stop();
                remoteStream.close();
            }
        });

//Start/Stop Recording 
$('.record-button').clickToggle(function () {
    $(this).addClass('btn-danger');
    $(this).removeClass('btn-default');
    $(this).find('i').css('color', 'orange');
    $(this).children('span').text('Stop Recording');
    $('.save-progress').text("Recording");

    console.log("streams: " + streams.length);

    var remote_streams = room.remoteStreams;
    for (var i in remote_streams) {

        console.log(remote_streams[i]);
        var s = remote_streams[i];

        if (localStream.getID() === s.getID()) {
            //room.subscribe(s);
            room.startRecording(s, function (id) {
                recording = true;
                //recordingId = id;
                localRecordingId = id;
                console.log("local id: " + id);
            });
        } else {
            room.startRecording(s, function (id) {
                recording = true;
                //recordingId = id;
                remoteRecordingId = id;
                console.log("remote id: " + id);
            });
        }

    }


}, function () {
    $(this).addClass('btn-default');
    $(this).removeClass('btn-danger');
    $(this).find('i').css('color', 'green');
    $(this).children('span').text('Start Recording');

    //Stop local Recording
    if (localRecordingId !== undefined) {
        room.stopRecording(localRecordingId);
    }
    if (remoteRecordingId !== undefined) {
        room.stopRecording(remoteRecordingId);
        //console.log('remote recording id: ' + remoteRecordingId);
        //console.log('Saving remote video to database');
        saveRecordingsToDatabase(localRecordingId, remoteRecordingId, 'remote');
    } else {
        saveRecordingsToDatabase(localRecordingId, remoteRecordingId, 'local');
    }

    recording = false;

    $('.save-progress').text("Saving");
});

$('.delete-video').click(function () {
    var video_element = $(this).parent().parent().parent();
    var video_id = $(this).siblings('.video_id').val();

    var ajaxurl = public_path + 'deleteVideo';
    var formData = new FormData();

    formData.append('video_id', video_id);

    $.ajax({
        url: ajaxurl,
        type: "POST",
        data: formData,
        // THIS MUST BE DONE FOR FILE UPLOADING
        contentType: false,
        processData: false,
        beforeSend: function () {

        },
        success: function (data) {
            video_element.remove();
        },
        complete: function () {

        },
        error: function (xhr, status, error) {
        }

    });
});


window.onbeforeunload = function () {
    deleteRoom(room_id, function () {
        console.log('Leaving Conference');
    });
}

/*When video is successfully recorded, place it on the video archive*/
socket.on('add-video', function (data) {
    console.log(data);
    var json_data = JSON.parse(data);

    var element = '<div class="video-element-holder">' +
            '<div class="row">' +
            '<div class="col-xs-10">' +
            '<video id="video-archive-item-' + json_data.video_id + '" class="video-archive-item" controls="controls"  preload="metadata" src="' + json_data.video_url + '">' +
            'Your browser does not support the video tag.' +
            '</video>' +
            '</div>' +
            '<div class="col-xs-2">' +
            '<button class="btn btn-danger pull-right delete-video"><i class="fa fa-times"></i></button>' +
            '<input class="video_id" type="hidden" value="' + json_data.video_id + '"/>' +
            '</div>' +
            '</div>' +
            '<div class="row">' +
            '<div class="col-xs-12">' +
            '<textarea class="video-status-container">' +
            '</textarea>' +
            '<input class="video_id" type="hidden" value="' + json_data.video_id + '"/>' +
            '</div>' +
            '</div>' +
            '</div>';

    $('.video-page-container').prepend(element);

    $('.save-progress').text("Video Recorded");

});

/*When video is deleted, delete it for all open browsers that are connected to the room*/
socket.on('delete-video', function (video_id) {
    var video_element = $('#video-archive-item-' + video_id).parent().parent().parent();
    video_element.remove();
});


$('.nav-tabs a[href="#video-archive-tab"]').click(function () {
    $('.video-status-container').tagEditor('destroy');
    $('.video-status-container').tagEditor({
        maxTags: 9999,
        clickDelete: true,
        placeholder: 'Enter video tags ...',
        autocomplete: {
            delay: 0, // show suggestions immediately
            position: {collision: 'flip'}, // automatic menu position up/down
            source: public_path + 'getAvailableVideoTags'
        },
        onChange: function (field, editor, tags) {
            var ajaxurl = public_path + 'addVideoTag';

            var job_id;
            var applicant_id;
            var video_id = $(field).siblings('.video_id').val();

            //For Single page applicant
            if ($('.add-comment-form').length > 0) {

                job_id = $('input[name=job_id]').val();
                applicant_id = $('input[name=applicant_id]').val();
            }

            var token = $('input[name=_token]').val();
            var formData = new FormData();
            formData.append('job_id', job_id);
            formData.append('applicant_id', applicant_id);
            formData.append('video_id', video_id);
            formData.append('video_status', tags);
            //formData.append('_token', token);
            $.ajax({
                url: ajaxurl,
                type: "POST",
                data: formData,
                // THIS MUST BE DONE FOR FILE UPLOADING
                contentType: false,
                processData: false,
                beforeSend: function () {
                },
                success: function (data) {
                },
                error: function (xhr, status, error) {

                }
            }); //ajax
            //alert(tags);
        }
    });


    $('.delete-video').click(function () {
        var video_element = $(this).parent().parent().parent();
        var video_id = $(this).siblings('.video_id').val();

        var ajaxurl = public_path + 'deleteVideo';
        var formData = new FormData();

        formData.append('video_id', video_id);

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: formData,
            // THIS MUST BE DONE FOR FILE UPLOADING
            contentType: false,
            processData: false,
            beforeSend: function () {

            },
            success: function (data) {
                socket.emit('delete-video', data);
                video_element.remove();
            },
            complete: function () {

            },
            error: function (xhr, status, error) {
            }

        });
    });
});