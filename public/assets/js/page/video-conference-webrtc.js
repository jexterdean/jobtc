/* 
 * Video Conferencing and Janus Web Gateway Recording
 */
//Hide Conference buttons
$('.nav-tabs a[href="#video-tab"]').click(function () {
    var applicant_text = $('.interview-applicant span').text();
    if (applicant_text === 'Join Conference') {
        $('.interview-applicant').siblings('button').hide();
    }
});

//Load Video Archive Functions
$('.nav-tabs a[href="#video-archive-tab"]').click(function () {
    $('.video-status-container').tagEditor('destroy');
    $('.video-status-container').tagEditor({
        maxTags: 9999,
        clickDelete: true,
        placeholder: 'Enter video tags ...',
        autocomplete: {
            delay: 0, // show suggestions immediately
            position: {collision: 'flip'}, // automatic menu position up/down
            source: public_path + 'getTags/' + $(this).siblings('.video_id') + '/video'
        },
        onChange: function (field, editor, tags) {
            var ajaxurl = public_path + 'addNewTag';

            var unique_id = $(field).siblings('.video_id').val();
            var tag_type = 'video';
            var formData = new FormData();
            formData.append('unique_id', unique_id);
            formData.append('tag_type', tag_type);
            formData.append('tags', tags);
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
});


//Click Toggle Function
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

var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
var hasExtension = false;

var room_name_tmp = window.location.pathname;
var room_name = parseInt(room_name_tmp.substr(room_name_tmp.lastIndexOf('/') + 1));
var csrf_token = $('._token').val();

var webrtc = new SimpleWebRTC({
    // the id/element dom element that will hold "our" video
    localVideoEl: 'localVideo',
    // the id/element dom element that will hold remote videos
    remoteVideosEl: '',
    // immediately ask for camera access
    autoRequestMedia: false,
    debug: true,
    localVideo: {
        autoplay: true, // automatically play the video stream on the page
        mirror: false, // flip the local video to mirror mode (for UX)
        muted: true // mute local video stream to prevent echo
    },
    media: {
        video: {
            mandatory: {
                maxFrameRate: 60,
                maxWidth: 535,
                maxHeight: 480
            }
        },
        audio: true
    },
    url: 'https://laravel.software:8888'
});

var screenshare = new SimpleWebRTC({
    // the id/element dom element that will hold "our" video
    localVideoEl: 'localVideo',
    // the id/element dom element that will hold remote videos
    remoteVideosEl: '',
    // immediately ask for camera access
    autoRequestMedia: false,
    debug: true,
    localVideo: {
        autoplay: true, // automatically play the video stream on the page
        mirror: false, // flip the local video to mirror mode (for UX)
        muted: true // mute local video stream to prevent echo
    },
    media: {
        video: {
            mandatory: {
                chromeMediaSource: 'screen',
                maxWidth: 480,
                maxHeight: 480
            }
        },
        audio: false
    },
    peerConnectionConfig: {
        iceTransports: 'relay' //relay means it will rea
    },
    url: 'https://laravel.software:8888'
});

var janus, sfutest, screentest, isLocal = 0, recordingId, session, formData;
var hasShareScreen = 0;
var janus_btn = $('.btn-video');
var currentRecordData, currentRecordUrl, interval;

var server = "https://laravel.software:8089/janus";
var media_server_url = "laravel.software";
var rec_dir = 'https://laravel.software/recordings';
/*var server = "https://linux.me:8089/janus";
var media_server_url = "linux.me";
var rec_dir = 'https://linux.me/recordings';*/

var bandwidth = 1024 * 1024;
var janusConnected = 0, simpleRtcConnected = 0;
$.fn.timerStart = function () {
    var timer_btn = $(this);
    if (timer_btn.find('.timer-area').length == 0) {
        timer_btn.prepend('<span class="timer-area" />');
    }
    var timer = timer_btn.find('.timer-area');
    var l = timer_btn.data('length');
    var a = l.split(':'); // split it at the colons

    var h = a[0];
    var m = parseInt(a[1]);
    var s = parseInt(a[2]);
    // minutes are worth 60 seconds. Hours are worth 60 minutes.
    var time_limit = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
    interval = setInterval(function (e) {
        if (time_limit == 0) {
            clearInterval(interval);
            timer_btn.parent().find('.btn-next').trigger('click');
            timer_btn.parent().find('.btn-video').trigger('click');
        }

        m = Math.floor(time_limit / 60); //Get remaining minutes
        s = time_limit - (m * 60);
        var time = (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s);
        timer.html(time);
        time_limit--;
    }, 1000);
};
function randomString(len, charSet) {
    charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
        var randomPoz = Math.floor(Math.random() * charSet.length);
        randomString += charSet.substring(randomPoz, randomPoz + 1);
    }
    return randomString;
}

/*For Video Sharing*/
// a peer video has been added
webrtc.on('videoAdded', function (video, peer) {
    console.log('video added', peer);
    peerStream = peer.stream;
    var remotes = document.getElementById('remotes');
    var remoteVideo = document.getElementById('remoteVideo');
    var remoteScreen = document.getElementById('remoteScreen');
    if (remotes) {
        video.id = 'container_' + webrtc.getDomId(peer);

        // suppress contextmenu
        video.oncontextmenu = function () {
            return false;
        };

        var dom_id = webrtc.getDomId(peer);
        if (dom_id.includes('screen')) {
            video.style.width = '535px';
            $(video).attr('controls', 'controls');
            remoteScreen.appendChild(video);
        } else {
            remoteVideo.appendChild(video);
        }

        //remotes.appendChild(container);
    }
    // show the ice connection state
    if (peer && peer.pc) {
        var connstate = document.createElement('div');
        connstate.className = 'connectionstate';
        remotes.appendChild(connstate);
        peer.pc.on('iceConnectionStateChange', function (event) {
            switch (peer.pc.iceConnectionState) {
                case 'checking':
                    connstate.innerText = 'Connecting to peer...';
                    janus_btn.addClass('hidden');
                    break;
                case 'connected':
                case 'completed': // on caller side
                    connstate.innerText = 'Connection established.';
                    connstate.remove();
                    if(janusConnected == 1) {
                        janus_btn.removeClass('hidden');
                        $('.janus-waiting').remove();
                    }
                    simpleRtcConnected = 1;
                    break;
                case 'disconnected':
                    connstate.innerText = 'Disconnected.';
                    janus_btn.addClass('hidden');
                    break;
                case 'failed':
                    break;
                case 'closed':
                    connstate.innerText = 'Connection closed.';
                    connstate.remove();
                    janus_btn.addClass('hidden');
                    break;
            }
        });
    }
});

// a peer video was removed
webrtc.on('videoRemoved', function (video, peer) {
    console.log('video removed ', peer);
    var remotes = document.getElementById('remotes');
    var remoteVideo = document.getElementById('remoteVideo');
    var remoteScreen = document.getElementById('remoteScreen');
    var el = document.getElementById(peer ? 'container_' + webrtc.getDomId(peer) : 'localScreenContainer');

    if (remotes && el) {
        var dom_id = webrtc.getDomId(peer);
        if (dom_id.includes('video') && el) {
            remoteVideo.removeChild(el);
        }

        if (dom_id.includes('screen') && el) {
            remoteScreen.removeChild(el);
        }
    }

    /*if (remotes && el) {
     remotes.removeChild(el);
     }*/
});

//local mute/unmute events
webrtc.on('audioOn', function () {
    // your local audio just turned on

});
webrtc.on('audioOff', function () {
    // your local audio just turned off

});
webrtc.on('videoOn', function () {
    // local video just turned on
});
webrtc.on('videoOff', function () {
    // local video just turned off
});

webrtc.on('stunservers', function () {
    //console.log('using a stun server');
});

webrtc.on('turnservers', function () {
    //console.log('using a turn server');
});

// local p2p/ice failure
webrtc.on('iceFailed', function (peer) {
    var pc = peer.pc;
    console.log('had local relay candidate', pc.hadLocalRelayCandidate);
    console.log('had remote relay candidate', pc.hadRemoteRelayCandidate);
});

// remote p2p/ice failure
webrtc.on('connectivityError', function (peer) {
    var pc = peer.pc;
    console.log('had local relay candidate', pc.hadLocalRelayCandidate);
    console.log('had remote relay candidate', pc.hadRemoteRelayCandidate);
});

// listen for mute and unmute events
webrtc.on('mute', function (data) { // show muted symbol
    webrtc.getPeers(data.id).forEach(function (peer) {
        if (data.name == 'audio') {
            $('#videocontainer_' + webrtc.getDomId(peer) + ' .muted').show();
        } else if (data.name == 'video') {
            $('#videocontainer_' + webrtc.getDomId(peer) + ' .paused').show();
            $('#videocontainer_' + webrtc.getDomId(peer) + ' video').hide();
        }
    });
});

webrtc.on('unmute', function (data) { // hide muted symbol
    webrtc.getPeers(data.id).forEach(function (peer) {
        if (data.name == 'audio') {
            $('#videocontainer_' + webrtc.getDomId(peer) + ' .muted').hide();
        } else if (data.name == 'video') {
            $('#videocontainer_' + webrtc.getDomId(peer) + ' video').show();
            $('#videocontainer_' + webrtc.getDomId(peer) + ' .paused').hide();
        }
    });
});


/*For Screensharing*/
// local screen obtained
webrtc.on('localScreenAdded', function (video) {
    video.onclick = function () {
        video.style.width = video.videoWidth + 'px';
        video.style.height = video.videoHeight + 'px';
    };
    //document.getElementById('localVideo').appendChil(video);
    //$('#localScreenContainer').show();
    video.id = '';
    $('#localScreen').append(video);
    hasShareScreen = 1;
});
// local screen removed
webrtc.on('localScreenRemoved', function (video) {
    document.getElementById('localScreen').removeChild(video);
    //$('#localScreenContainer').hide();
    $('#localScreen').html('');
    hasShareScreen = 0;
});

//region Recording Area
$(document).ready(function() {
    // Initialize the library (all console debuggers enabled)
    Janus.init({debug: "all", callback: function() {
        if(!Janus.isWebrtcSupported()) {
            bootbox.alert("No WebRTC support... ");
            return;
        }
        janusConnected = 1;
        // Create session for Local Video
        janus = new Janus({
            server: server,
            success: function() {
                //Local Video
                janus.attach({
                    plugin: "janus.plugin.recordplay",
                    success: function(pluginHandle) {
                        sfutest = pluginHandle;
                        console.log('simpleRtcConnected: ' + simpleRtcConnected);
                        if(simpleRtcConnected == 1) {
                            janus_btn.removeClass('hidden');
                            $('.janus-waiting').remove();
                        }
                        janusConnected = 1;
                    },
                    error: function(error) {
                        Janus.error("  -- Error attaching plugin...", error);
                        bootbox.alert("  -- Error attaching plugin... " + error);
                    },
                    consentDialog: function(on) {
                        Janus.debug("Consent dialog should be " + (on ? "on" : "off") + " now");
                    },
                    webrtcState: function(on) {
                        Janus.log("Janus says our WebRTC PeerConnection is " + (on ? "up" : "down") + " now");
                    },
                    onmessage: function(msg, jsep) {
                        Janus.debug(" ::: Got a message :::");
                        Janus.debug(JSON.stringify(msg));
                        var result = msg["result"];
                        if(result !== null && result !== undefined) {
                            if(result["status"] !== undefined && result["status"] !== null) {
                                var event = result["status"];
                                if(event === 'preparing') {
                                    Janus.log("Preparing the recording playout");
                                    sfutest.createAnswer({
                                        jsep: jsep,
                                        media: { audioSend: false, videoSend: false },	// We want recvonly audio/video
                                        success: function(jsep) {
                                            Janus.debug("Got SDP!");
                                            Janus.debug(jsep);
                                            var body = { "request": "start" };
                                            sfutest.send({"message": body, "jsep": jsep});
                                        },
                                        error: function(error) {
                                            Janus.error("WebRTC error:", error);
                                            bootbox.alert("WebRTC error... " + JSON.stringify(error));
                                        }
                                    });
                                    if(result["warning"])
                                        bootbox.alert(result["warning"]);
                                }
                                else if(event === 'recording') {
                                    // Got an ANSWER to our recording OFFER
                                    if(jsep !== null && jsep !== undefined)
                                        sfutest.handleRemoteJsep({jsep: jsep});
                                    var id = result["id"];
                                    if(id !== null && id !== undefined) {
                                        Janus.log("The ID of the current recording is " + id);


                                    }
                                }
                                else if(event === 'slow_link') {
                                    var uplink = result["uplink"];
                                    if(uplink !== 0) {
                                        // Janus detected issues when receiving our media, let's slow down
                                        bandwidth = parseInt(bandwidth / 1.5);
                                        sfutest.send({
                                            'message': {
                                                'request': 'configure',
                                                'video-bitrate-max': bandwidth, // Reduce the bitrate
                                                'video-keyframe-interval': 15000 // Keep the 15 seconds key frame interval
                                            }
                                        });
                                    }
                                }
                                else if(event === 'playing') {
                                    Janus.log("Playout has started!");
                                }
                                else if(event === 'stopped') {
                                    Janus.log("Session has stopped!");
                                }
                            }
                        }
                        else {
                            // FIXME Error?
                            var error = msg["error"];
                            bootbox.alert(error);
                        }
                    },
                    onlocalstream: function(stream) {
                        Janus.debug(" ::: Got a local stream :::");
                        Janus.debug(JSON.stringify(stream));

                        attachMediaStream($('#localVideo').find('video').get(0), stream);
                    },
                    onremotestream: function(stream) {

                    },
                    oncleanup: function() {
                        Janus.log(" ::: Got a cleanup notification :::");
                    }
                });
            },
            error: function(error) {
                Janus.error(error);
                bootbox.alert(error, function() {
                    location.reload();
                });
            },
            destroyed: function() {
                location.reload();
            }
        });
    }});
});

socket.on('start-interview', function (data) {
    var n = $.now();
    var f = (isLocal ? data.local : data.remote);
    sfutest.send({
        'message': {
            'request': 'configure',
            'video-bitrate-max': bandwidth, // a quarter megabit
            'video-keyframe-interval': 15000 // 15 seconds
        }
    });

    sfutest.createOffer({
        // By default, it's sendrecv for audio and video..
        success: function(jsep) {
            Janus.debug(jsep);
            var body = {
                "request": "record",
                "name": n.toString(),
                "video": "stdres",
                "filename": f.toString()
            };
            sfutest.send({"message": body, "jsep": jsep});
        },
        error: function(error) {
            sfutest.hangup();
        }
    });
});
socket.on('stop-interview', function (data) {
    var stop = { "request": "stop" };
    sfutest.send({ "message": stop });

    if(isLocal) {
        $.ajax({
            url: public_path + 'convertJanusVideo',
            data: data,
            type: "POST",
            beforeSend: function () {

            },
            success: function (e) {
                console.log('Files Converted to webm');
                $.ajax({
                    url: currentRecordUrl,
                    data: currentRecordData,
                    method: "POST",
                    success: function (doc) {
                        socket.emit('add-interview', doc);
                        $('.download-complete-sound').get(0).play();
                        $('.janus-waiting').remove();
                    },
                    error: function (a, b, c) {

                    }
                });
            },
            complete: function () {

            },
            error: function (xhr, status, error) {
                console.log('Error: retrying');
            }
        });
        $.ajax({
            url: public_path + 'saveNfoJanus',
            data: data,
            type: "POST",
            beforeSend: function () {

            },
            success: function (e) {
                console.log(e);
                console.log('NFO generated');
            },
            complete: function () {

            },
            error: function (xhr, status, error) {
                console.log('Error: retrying');
            }
        });
    }
});

socket.on('start-recording', function (data) {
    var n = $.now();
    recordingId = n + '-' + room_name;
    var f = data + '-' + recordingId;
    sfutest.send({
        'message': {
            'request': 'configure',
            'video-bitrate-max': bandwidth, // a quarter megabit
            'video-keyframe-interval': 15000 // 15 seconds
        }
    });
    sfutest.createOffer({
        // By default, it's sendrecv for audio and video..
        success: function(jsep) {
            Janus.debug(jsep);
            var body = {
                "request": "record",
                "name": n.toString(),
                "video": "stdres",
                "filename": f.toString()
            };
            sfutest.send({"message": body, "jsep": jsep});
        },
        error: function(error) {
            sfutest.hangup();
        }
    });

    //Get Page type to determine if it's a company employee or applicant
    var room_type = $('.page_type').val();

    formData = new FormData();
    formData.append('session', data);
    formData.append('room_name', room_name);
    formData.append('room_type', room_type);
    formData.append('stream', recordingId);
    formData.append('rec_dir', rec_dir);
    formData.append('_token',csrf_token);

    var ajaxurl = public_path + 'startRecording';

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
            //socket.emit('add-video', data);
            //$('.download-complete-sound').get(0).play();
            console.log('Added Session Data to database, Starting Recording');
        },
        complete: function () {

        },
        error: function (xhr, status, error) {
            $('.save-progress').text('Recording failed');
        }
    }); //ajax

});
socket.on('stop-recording', function (data) {
    var stop = { "request": "stop" };
    sfutest.send({ "message": stop });
});
socket.on('save-video', function (data) {
    var ajaxurl = public_path + 'saveVideo';

    //Get Page type to determine if it's a company employee or applicant
    var room_type = $('.page_type').val();

    formData = new FormData();
    formData.append('session', data);
    formData.append('room_name', room_name);
    formData.append('room_type', room_type);
    formData.append('stream', recordingId);
    formData.append('rec_dir', rec_dir);
    formData.append('_token',csrf_token);

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
            $('.save-progress').text('Recording failed');
            console.log('Error: retrying');
            socket.emit('stop-recording', sfutest);
        }
    }); //ajax

    $.ajax({
        url: public_path + 'saveNfoJanus',
        data: {
            local: data + '-' + recordingId
        },
        type: "POST",
        beforeSend: function () {

        },
        success: function (e) {
            console.log(e);
            console.log('NFO generated');
        },
        complete: function () {

        },
        error: function (xhr, status, error) {
            console.log('Error: retrying');
        }
    });
});
/*When video is successfully recorded, place it on the video archive*/
socket.on('add-video', function (data) {
    console.log(data);
    var json_data = JSON.parse(data);

    var element = '<div class="video-element-holder">' +
            '<div class="row">' +
            '<div class="col-xs-10">' +
            '<video id="video-archive-item-' + json_data.video_id + '" class="video-archive-item" controls="controls"  preload="metadata">' +
            'Your browser does not support the video tag.' +
            '<source src="' + json_data.video + '" type="video/webm">' +
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

//endregion

$('.interview-applicant').clickToggle(function () {
    $('.interview-applicant').addClass('btn-warning');
    $('.interview-applicant').removeClass('btn-success');
    $('.interview-applicant').children('span').text('Leave Conference');
    $('.interview-applicant').siblings('button').show();
    webrtc.joinRoom(room_name_tmp);
    //connection.open(room_name);
    //connection.join(room_name);
    webrtc.startLocalVideo();

    $('.time-limit-conference').each(function(e){
        $(this).after('<span class="janus-waiting" style="color: #f00;">Waiting for Remote...</span>');
    });
}, function () {
    $(this).addClass('btn-success');
    $(this).removeClass('btn-warning');
    $(this).children('span').text('Join Conference');
    $(this).siblings('button').hide();
    //$('#localVideo').children().remove();
    webrtc.leaveRoom(room_name_tmp);
    webrtc.stopLocalVideo();
    $('#localVideo video').remove();
    $('.janus-waiting').remove();
});

$('.screen-share').clickToggle(function () {
    console.log('Starting Screensharing');
    $(this).find('span').text('Stop Screen Share');
    webrtc.shareScreen(function (err) {
        if (err) {
            console.log("Screensharing error :" + err);
            $('.screen-share').click();
            if (err == 'EXTENSION_UNAVAILABLE: NavigatorUserMediaError') {
                if (isChrome) {
                    if (hasExtension == false) {
                        BootstrapDialog.show({
                            title: 'Extension not installed',
                            message: 'Please install the plugin for screensharing. <a target="_blank" href="https://chrome.google.com/webstore/detail/jobtc-screensharing-exten/eciifjfhlbmnofcnnjhodcbjnfhjcelp/related">Install<a>',
                            buttons: [{
                                    label: 'Close',
                                    action: function (dialog) {
                                        dialog.close();
                                    }
                                }]
                        });
                    }
                }
            }
        } else {
            console.log("Screensharing active");
        }
    });
}, function () {
    $(this).find('span').text('Share Screen');
    webrtc.stopScreenShare();
    $('#localScreen').remove();
});

$('.mute-button').clickToggle(function () {
    $(this).find('span').text('Unmute');
    webrtc.mute();
}, function () {
    $(this).find('span').text('Mute');
    webrtc.unmute();
});

$('.show-video-button').clickToggle(function () {
    $(this).find('span').text('Show Video');
    webrtc.pauseVideo();
}, function () {
    $(this).find('span').text('Stop Video');
    webrtc.resumeVideo();
});

$('.record-button').clickToggle(function () {
    $(this).addClass('btn-danger');
    $(this).removeClass('btn-default');
    $(this).find('i').css('color', 'orange');
    $(this).children('span').text('Stop Recording');
    $('.save-progress').text("Recording");
    startRecording();
}, function () {
    $(this).addClass('btn-default');
    $(this).removeClass('btn-danger');
    $(this).find('i').css('color', 'green');
    $(this).children('span').text('Start Recording');
    $('.save-progress').text("");
    stopRecording();
    saveVideo();
});

function startRecording() {
    // bitrate and keyframe interval can be set at any time:
    // before, after, during recording
    session = randomString(12);
    socket.emit('start-recording', session);
}
function stopRecording() {
    socket.emit('stop-recording', session);
}
function saveVideo() {
    socket.emit('save-video', session);
}

$('body').on('click', '.btn-video', function (e) {
    var video_btn = $(this);
    var time_limit = $(this).parent().find('.time-limit-conference');
    var question_point = $(this).parent().find('.video-conference-points');

    if ($(this).data('status') == 1) {
        isLocal = 1;
        recordingId = $.now() + '-' + room_name;
        socket.emit('set-remote-id', 'remote-' + recordingId);
        socket.emit('start-interview', 'local-' + recordingId);

        time_limit.timerStart();
        video_btn.data('status', 2);
        video_btn.html('Score Answer');
    }
    else if ($(this).data('status') == 2) {
        var test_id = $(this).data('test');
        var unique_id = $(this).data('unique');
        currentRecordUrl = public_path + 'quiz?id=' + test_id + '&p=exam';
        currentRecordData = {
            local_record_id: 'local-' + recordingId,
            record_id: 'remote-' + recordingId,
            question_id: this.id,
            answer: '',
            result: 1,
            unique_id: unique_id,
            points: question_point.val(),
            video_conference: 1
        };

        $(this)
            .parent()
            .find('.time-limit-conference')
            .after('<span class="janus-waiting" style="color: #f00;">Please wait...</span>');

        socket.emit('stop-interview', 'local-' + recordingId);

        clearInterval(interval);
        $(this).html('Record Answer');
        $(this).data('status', 1);
    }
});

//Check if we have the Job.tc chrome extension if it's chrome
checkExtension();
function checkExtension() {
    if (typeof chrome !== "undefined" && typeof chrome.app !== "undefined" && chrome.app.isInstalled) {
        console.log('Job.tc extension is installed');
        hasExtension = true;
    }
}
