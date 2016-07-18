@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-xs-8 align-center">
        <div class="row">
            <div class="col-md-12" id="quiz-video"></div>
            <video class="hidden" id="quiz-video-play" controls="controls" preload="metadata" src="">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button class="btn btn-default btn-shadow record-btn">
                    <i class="fa fa-circle"></i>&nbsp;
                    <span>Recording</span>
                </button>
                <button class="btn btn-default btn-shadow stop-btn">
                    <i class="fa fa-square"></i>&nbsp;
                    <span>Stop</span>
                </button>
                <button class="btn btn-default btn-shadow play-btn">
                    <i class="fa fa-play"></i>&nbsp;
                    <span>Play</span>
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js_footer')
@parent

<style>
    #quiz-video,
    #quiz-video-play{
        height: 600px;
        width: 100%;
    }
</style>

{!!  HTML::script('assets/js/erizo.js')  !!}
<script>
    $(function(e){
        var recordingUrl = "https://laravel.software/recordings/";
        var serverUrl = "https://laravel.software:3333/";
        var record = $('.record-btn');
        var stop = $('.stop-btn');
        var play = $('.play-btn');
        var quiz_video = $('#quiz-video');
        var quiz_video_play = $('#quiz-video-play');

        var createToken = function (room_id, username, role, callback) {
            var req = new XMLHttpRequest();
            var url = serverUrl + 'createToken/';
            var body = {room_id: room_id, username: 'user', role: 'presenter'};
            req.onreadystatechange = function () {
                if (req.readyState === 4) {
                    callback(req.responseText);
                }
            };
            req.open('POST', url, true);
            req.setRequestHeader('Content-Type', 'application/json');
            req.send(JSON.stringify(body));
        };
        var createRoom = function (room_name, callback) {
            var req = new XMLHttpRequest();
            var url = serverUrl + 'createRoom/';
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
        var saveVideo = function(localStreamId){
            var file_extension = '.webm';
            var video_url = recordingUrl + localStreamId + file_extension;

            var ajaxurl = public_path + 'quizSaveVideo';
            var formData = new FormData();
            formData.append('stream_id', localStreamId);
            $.ajax({
                url: ajaxurl,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {

                },
                success: function (data) {
                    console.log('save video');
                },
                complete: function () {

                },
                error: function (xhr, status, error) {
                    console.log('Error: retrying');
                }
            }); //ajax
        };

        createRoom("quiz", function (room_id) {
            createToken(room_id, "user", "presenter", function (token) {
                var room = Erizo.Room({ token: token });

                //region Record and Save Video
                var localStream = Erizo.Stream({ audio: true, video: true, data: true });
                localStream.addEventListener("access-accepted", function () {
                    var subscribeToStreams = function (streams) {
                        for (var index in streams) {
                            var stream = streams[index];
                            if (localStream.getID() !== stream.getID()) {
                                room.subscribe(stream);
                            }
                        }
                    };

                    room.addEventListener("room-connected", function (roomEvent) {
                        room.publish(localStream);
                        subscribeToStreams(roomEvent.streams);
                    });

                    room.addEventListener("stream-subscribed", function(streamEvent) {
                        var stream = streamEvent.stream;
                        var div = document.createElement('div');
                        div.setAttribute("style", "width: 320px; height: 240px;");
                        div.setAttribute("id", "test" + stream.getID());

                        document.body.appendChild(div);
                        stream.play("test" + stream.getID());
                    });

                    room.addEventListener("stream-added", function (streamEvent) {
                        var streams = [];
                        streams.push(streamEvent.stream);
                        subscribeToStreams(streams);
                    });

                    room.addEventListener("stream-removed", function (streamEvent) {
                        // Remove stream from DOM
                        var stream = streamEvent.stream;
                        if (stream.elementID !== undefined) {
                            var element = document.getElementById(stream.elementID);
                            document.body.removeChild(element);
                        }
                    });

                    room.connect();
                    localStream.play("quiz-video");
                });
                localStream.init();

                var rId = '';
                record.click(function(e){
                    room.startRecording(localStream, function(recordingId, error) {
                        if (recordingId === undefined){
                            console.log("Error", error);
                        } else {
                            rId = recordingId;
                            console.log("Recording started, the id of the recording is ", recordingId);
                        }
                    });
                });
                stop.click(function(e){
                    if(rId){
                        room.stopRecording(rId, function(result, error){
                            if (result === undefined){
                                console.log("Error", error);
                            } else {
                                saveVideo(rId);
                            }
                        });
                    }
                    else{
                        localStream.close();
                    }
                });
                //endregion

                play.click(function(e){
                    if(rId){
                        quiz_video.addClass('hidden');
                        quiz_video_play
                            .attr('src', recordingUrl + rId + '.webm')
                            .removeClass('hidden')
                            .get(0).play();
                    }
                    else{
                        localStream.play("quiz-video");
                    }
                });
            });
        });
    });
</script>
@stop