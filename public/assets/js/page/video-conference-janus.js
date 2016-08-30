/* 
 * Janus Web Gateway Client for Video Conferencing and Recording
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

//var server = "https://ubuntu-server.com:8089/janus";
var server = "https://laravel.software:8089/janus";

var janus = null;
var sfutest = null;
var started = false;

var myusername = null;
var myid = null;
var mystream = null;
var screentest = null;

var feeds = [];
var bitrateTimer = [];
var bandwidth = 1024 * 1024;

var display_name = $('.add-comment-form .media-heading').text();
console.log(display_name);
var room_name_tmp = window.location.pathname;
var room_name = parseInt(room_name_tmp.substr(room_name_tmp.lastIndexOf('/') + 1));


jQuery.janusApiMedia = function(k){
    var thisOption = janusVideoResolutionList[k];
    var constraint = {
        audio: false,
        video: {
            deviceId: undefined,
            width: { exact: thisOption.width },
            height: { exact: thisOption.height }
        }
    };
    navigator
        .mediaDevices
        .getUserMedia(constraint)
        .then(function(mediaStream){
            console.log('success');
        })
        .catch(function (error) {
            delete janusVideoResolutionList[k];
            console.log('failed');
        });
    //https://webrtchacks.github.io/WebRTC-Camera-Resolution/
};

var janusVideoResolutionList = [
    {
        "label": "hires",
        "width": 1280,
        "height": 720
    },
    {
        "label": "stdres",
        "width": 640,
        "height": 480
    },
    {
        "label": "stdres-16:9",
        "width": 640,
        "height": 360
    },
    {
        "label": "lowres",
        "width": 320,
        "height": 240
    },
    {
        "label": "lowres-16:9",
        "width": 320,
        "height": 180
    }
];

$(document).ready(function () {
    // Initialize the library (all console debuggers enabled)
    Janus.init({debug: "all", callback: function () {
            // Use a button to start the demo
            $('.interview-applicant').clickToggle(function () {
                $('.interview-applicant').addClass('btn-warning');
                $('.interview-applicant').removeClass('btn-success');
                $('.interview-applicant').children('span').text('Leave Conference');
                $('.interview-applicant').siblings('button').show();
                if (started)
                    return;
                started = true;
                // Make sure the browser supports WebRTC
                if (!Janus.isWebrtcSupported()) {
                    bootbox.alert("No WebRTC support... ");
                    return;
                }
                // Create session
                janus = new Janus(
                        {
                            server: server,
                            success: function () {
                                // Attach to video room test plugin
                                janus.attach(
                                        {
                                            plugin: "janus.plugin.videoroom",
                                            success: function (pluginHandle) {
                                                $('#details').remove();
                                                sfutest = pluginHandle;
                                                Janus.log("Plugin attached! (" + sfutest.getPlugin() + ", id=" + sfutest.getId() + ")");
                                                Janus.log("  -- This is a publisher/manager");
                                                var createRoom = {
                                                    "request": "create",
                                                    "record": true,
                                                    "publishers": 2,
                                                    "room": room_name,
                                                    "bitrate": bandwidth,
                                                };
                                                sfutest.send({"message": createRoom});
                                                var register = {"request": "join", "room": room_name, "ptype": "publisher", "display": display_name};
                                                myusername = display_name;
                                                sfutest.send({"message": register});

                                            },
                                            error: function (error) {
                                                Janus.error("  -- Error attaching plugin...", error);

                                            },
                                            consentDialog: function (on) {
                                                Janus.debug("Consent dialog should be " + (on ? "on" : "off") + " now");

                                            },
                                            mediaState: function (medium, on) {
                                                Janus.log("Janus " + (on ? "started" : "stopped") + " receiving our " + medium);
                                            },
                                            webrtcState: function (on) {
                                                Janus.log("Janus says our WebRTC PeerConnection is " + (on ? "up" : "down") + " now");

                                            },
                                            onmessage: function (msg, jsep) {
                                                Janus.debug(" ::: Got a message (publisher) :::");
                                                Janus.debug(JSON.stringify(msg));
                                                var event = msg["videoroom"];
                                                Janus.debug("Event: " + event);
                                                if (event != undefined && event != null) {
                                                    if (event === "joined") {
                                                        // Publisher/manager created, negotiate WebRTC and attach to existing feeds, if any
                                                        myid = msg["id"];
                                                        Janus.log("Successfully joined room " + msg["room"] + " with ID " + myid);
                                                        publishOwnFeed(true);
                                                        // Any new feed to attach to?
                                                        if (msg["publishers"] !== undefined && msg["publishers"] !== null) {
                                                            var list = msg["publishers"];
                                                            Janus.debug("Got a list of available publishers/feeds:");
                                                            Janus.debug(list);
                                                            for (var f in list) {
                                                                var id = list[f]["id"];
                                                                var display = list[f]["display"];
                                                                Janus.debug("  >> [" + id + "] " + display);
                                                                newRemoteFeed(id, display)
                                                            }
                                                        }
                                                    } else if (event === "destroyed") {
                                                        // The room has been destroyed
                                                        Janus.warn("The room has been destroyed!");
                                                        bootbox.alert(error, function () {
                                                            window.location.reload();
                                                        });
                                                    } else if (event === "event") {
                                                        // Any new feed to attach to?
                                                        if (msg["publishers"] !== undefined && msg["publishers"] !== null) {
                                                            var list = msg["publishers"];
                                                            Janus.debug("Got a list of available publishers/feeds:");
                                                            Janus.debug(list);
                                                            for (var f in list) {
                                                                var id = list[f]["id"];
                                                                var display = list[f]["display"];
                                                                Janus.debug("  >> [" + id + "] " + display);
                                                                newRemoteFeed(id, display);
                                                            }
                                                        } else if (msg["leaving"] !== undefined && msg["leaving"] !== null) {
                                                            // One of the publishers has gone away?
                                                            var leaving = msg["leaving"];
                                                            Janus.log("Publisher left: " + leaving);
                                                            var remoteFeed = null;
                                                            for (var i = 1; i < 6; i++) {
                                                                if (feeds[i] != null && feeds[i] != undefined && feeds[i].rfid == leaving) {
                                                                    remoteFeed = feeds[i];
                                                                    break;
                                                                }
                                                            }
                                                            if (remoteFeed != null) {
                                                                Janus.debug("Feed " + remoteFeed.rfid + " (" + remoteFeed.rfdisplay + ") has left the room, detaching");
                                                                feeds[remoteFeed.rfindex] = null;
                                                                remoteFeed.detach();
                                                                $('#remoteVideo').children().remove();
                                                            }
                                                        } else if (msg["unpublished"] !== undefined && msg["unpublished"] !== null) {
                                                            // One of the publishers has unpublished?
                                                            var unpublished = msg["unpublished"];
                                                            Janus.log("Publisher left: " + unpublished);
                                                            if (unpublished === 'ok') {
                                                                // That's us
                                                                sfutest.hangup();
                                                                return;
                                                            }
                                                            var remoteFeed = null;
                                                            for (var i = 1; i < 6; i++) {
                                                                if (feeds[i] != null && feeds[i] != undefined && feeds[i].rfid == unpublished) {
                                                                    remoteFeed = feeds[i];
                                                                    break;
                                                                }
                                                            }
                                                            if (remoteFeed != null) {
                                                                Janus.debug("Feed " + remoteFeed.rfid + " (" + remoteFeed.rfdisplay + ") has left the room, detaching");
                                                                Janus.debug("Feed " + remoteFeed.rfid + " (" + remoteFeed.rfdisplay + ") has left the room, detaching");
                                                                feeds[remoteFeed.rfindex] = null;
                                                                remoteFeed.detach();
                                                                $('#remoteVideo').children().remove();
                                                            }
                                                        } else if (msg["error"] !== undefined && msg["error"] !== null) {
                                                            bootbox.alert(msg["error"]);
                                                        }
                                                    }
                                                }
                                                if (jsep !== undefined && jsep !== null) {
                                                    Janus.debug("Handling SDP as well...");
                                                    Janus.debug(jsep);
                                                    sfutest.handleRemoteJsep({jsep: jsep});
                                                }
                                            },
                                            onlocalstream: function (stream) {
                                                Janus.debug(" ::: Got a local stream :::");
                                                mystream = stream;
                                                Janus.debug(JSON.stringify(stream));
                                                $('#localVideo').append('<video class="rounded centered" id="myvideo" width="100%" autoplay muted="muted"/>');
                                                attachMediaStream($('#myvideo').get(0), stream);
                                                var videoTracks = stream.getVideoTracks();
                                                if (videoTracks === null || videoTracks === undefined || videoTracks.length === 0) {
                                                    // No webcam
                                                    $('#localVideo').append(
                                                            '<div class="no-video-container">' +
                                                            '<i class="fa fa-video-camera fa-5 no-video-icon" style="height: 100%;"></i>' +
                                                            '<span class="no-video-text" style="font-size: 16px;">No webcam available</span>' +
                                                            '</div>');
                                                }

                                            },
                                            onremotestream: function (stream) {
                                                // The publisher stream is sendonly, we don't expect anything here
                                            },
                                            oncleanup: function () {
                                                Janus.log(" ::: Got a cleanup notification: we are unpublished now :::");
                                                mystream = null;
                                            }
                                        });
                            },
                            error: function (error) {
                                Janus.error(error);
                                bootbox.alert(error, function () {
                                    window.location.reload();
                                });
                            },
                            destroyed: function () {
                                //window.location.reload();
                            }
                        });
            }, function () {
                $(this).addClass('btn-success');
                $(this).removeClass('btn-warning');
                $(this).children('span').text('Join Conference');
                $(this).siblings('button').hide();
                $('#myvideo').remove();
                unpublishOwnFeed();
                removeRemoteFeed();
                janus.destroy();
                started = false;
            });
        }});


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
    });

    $('.mute-button').clickToggle(function () {
        $(this).find('span').text('Unmute');
        sfutest.muteAudio();
    }, function () {
        $(this).find('span').text('Mute');
        sfutest.unmuteAudio();
    });

    $('.show-video-button').clickToggle(function () {
        $(this).find('span').text('Show Video');
        sfutest.muteVideo();
    }, function () {
        $(this).find('span').text('Stop Video');
        sfutest.unmuteVideo();
    });

    $('.screen-share').clickToggle(function () {
        $(this).find('span').text('Stop Screen Share');
        startScreenShare();
    }, function () {
        $(this).find('span').text('Share Screen');
        stopScreenShare();
    });
});

function checkEnter(field, event) {
    var theCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
    if (theCode == 13) {
        registerUsername();
        return false;
    } else {
        return true;
    }
}

function registerUsername() {
    if ($('#username').length === 0) {
        // Create fields to register
        $('#register').click(registerUsername);
        $('#username').focus();
    } else {
        // Try a registration
        $('#username').attr('disabled', true);
        $('#register').attr('disabled', true).unbind('click');
        var username = $('#username').val();
        if (username === "") {
            $('#you')
                    .removeClass().addClass('label label-warning')
                    .html("Insert your display name (e.g., pippo)");
            $('#username').removeAttr('disabled');
            $('#register').removeAttr('disabled').click(registerUsername);
            return;
        }
        if (/[^a-zA-Z0-9]/.test(username)) {
            $('#you')
                    .removeClass().addClass('label label-warning')
                    .html('Input is not alphanumeric');
            $('#username').removeAttr('disabled').val("");
            $('#register').removeAttr('disabled').click(registerUsername);
            return;
        }
        var register = {"request": "join", "room": room_name, "ptype": "publisher", "display": username};
        myusername = username;
        sfutest.send({"message": register});
    }
}

function publishOwnFeed(useAudio) {
    // Publish our stream
    $('#publish').attr('disabled', true).unbind('click');
    sfutest.createOffer(
            {
                media: {audioRecv: false, videoRecv: false, audioSend: useAudio, videoSend: true, video: 'stdres'}, // Publishers are sendonly
                success: function (jsep) {
                    Janus.debug("Got publisher SDP!");
                    Janus.debug(jsep);
                    var publish = {"request": "configure", "audio": useAudio, "video": true};
                    sfutest.send({"message": publish, "jsep": jsep});
                },
                error: function (error) {
                    Janus.error("WebRTC error:", error);
                    if (useAudio) {
                        publishOwnFeed(false);
                    } else {
                        bootbox.alert("WebRTC error... " + JSON.stringify(error));
                        $('#publish').removeAttr('disabled').click(function () {
                            publishOwnFeed(true);
                        });
                    }
                }
            });
}

function unpublishOwnFeed() {
    // Unpublish our stream
    var unpublish = {"request": "unpublish"};
    sfutest.send({"message": unpublish});
}

function removeRemoteFeed() {

    $('#remoteVideo').children().remove();

}

function newRemoteFeed(id, display) {
    // A new feed has been published, create a new plugin handle and attach to it as a listener
    var remoteFeed = null;
    janus.attach(
            {
                plugin: "janus.plugin.videoroom",
                success: function (pluginHandle) {
                    remoteFeed = pluginHandle;
                    Janus.log("Plugin attached! (" + remoteFeed.getPlugin() + ", id=" + remoteFeed.getId() + ")");
                    Janus.log("  -- This is a subscriber");
                    // We wait for the plugin to send us an offer
                    var listen = {"request": "join", "room": room_name, "ptype": "listener", "feed": id};
                    remoteFeed.send({"message": listen});
                },
                error: function (error) {
                    Janus.error("  -- Error attaching plugin...", error);
                },
                onmessage: function (msg, jsep) {
                    Janus.debug(" ::: Got a message (listener) :::");
                    Janus.debug(JSON.stringify(msg));
                    var event = msg["videoroom"];
                    Janus.debug("Event: " + event);
                    if (event != undefined && event != null) {
                        if (event === "attached") {
                            // Subscriber created and attached
                            for (var i = 1; i < 6; i++) {
                                if (feeds[i] === undefined || feeds[i] === null) {
                                    feeds[i] = remoteFeed;
                                    remoteFeed.rfindex = i;
                                    break;
                                }
                            }
                            remoteFeed.rfid = msg["id"];
                            remoteFeed.rfdisplay = msg["display"];
                            var target = document.getElementById('remoteVideo');
                            Janus.log("Successfully attached to feed " + remoteFeed.rfid + " (" + remoteFeed.rfdisplay + ") in room " + msg["room"]);
                            $('#remote' + remoteFeed.rfindex).removeClass('hide').html(remoteFeed.rfdisplay).show();
                        } else if (msg["error"] !== undefined && msg["error"] !== null) {
                            bootbox.alert(msg["error"]);
                        } else {
                            // What has just happened?
                        }
                    }
                    if (jsep !== undefined && jsep !== null) {
                        Janus.debug("Handling SDP as well...");
                        Janus.debug(jsep);
                        // Answer and attach
                        remoteFeed.createAnswer(
                                {
                                    jsep: jsep,
                                    media: {audioSend: false, videoSend: false}, // We want recvonly audio/video
                                    success: function (jsep) {
                                        Janus.debug("Got SDP!");
                                        Janus.debug(jsep);
                                        var body = {"request": "start", "room": room_name};
                                        remoteFeed.send({"message": body, "jsep": jsep});
                                    },
                                    error: function (error) {
                                        Janus.error("WebRTC error:", error);
                                        bootbox.alert("WebRTC error... " + JSON.stringify(error));
                                    }
                                });
                    }
                },
                webrtcState: function (on) {
                    Janus.log("Janus says this WebRTC PeerConnection (feed #" + remoteFeed.rfindex + ") is " + (on ? "up" : "down") + " now");
                },
                onlocalstream: function (stream) {
                    // The subscriber stream is recvonly, we don't expect anything here
                },
                onremotestream: function (stream) {
                    Janus.debug("Remote feed #" + remoteFeed.rfindex);
                    Janus.debug(JSON.stringify(stream));
                    console.log('Here appending the remote stream');
                    $('#remoteVideo').append('<video class="rounded centered" id="remote-' + remoteFeed.rfindex + '" width="100%" height="100%" autoplay/>');
                    attachMediaStream($('#remote-' + remoteFeed.rfindex).get(0), stream);
                    var videoTracks = stream.getVideoTracks();

                },
                oncleanup: function () {
                    Janus.log(" ::: Got a cleanup notification (remote feed " + id + ") :::");
                }
            });
}

function startRecording() {
    // bitrate and keyframe interval can be set at any time: 
    // before, after, during recording
    sfutest.send({
        'message': {
            "request": "configure",
            "room": room_name,
            "record": true,
            "filename": "/var/www/html/recordings/" + sfutest.getId()
        }
    });
}

function stopRecording() {
    sfutest.send({
        'message': {
            "request": "configure",
            "room": room_name,
            "record": false,
            "filename": "/var/www/html/recordings/" + sfutest.getId()
        }
    });
}

function shareScreen() {
    // Create a new room
    var desc = $('#desc').val();
    role = "publisher";
    var create = {"request": "create", "description": desc, "bitrate": 0, "publishers": 1};
    screentest.send({"message": create, success: function (result) {
            var event = result["videoroom"];
            Janus.debug("Event: " + event);
            if (event != undefined && event != null) {
                // Our own screen sharing session has been created, join it
                room = result["room"];
                Janus.log("Screen sharing session created: " + room);
                myusername = randomString(12);
                var register = {"request": "join", "room": room, "ptype": "publisher", "display": myusername};
                screentest.send({"message": register});
            }
        }});
}

function attachScreen() {
    // Attach to video room test plugin
    janus.attach(
            {
                plugin: "janus.plugin.videoroom",
                success: function (pluginHandle) {
                    $('#details').remove();
                    screentest = pluginHandle;
                    Janus.log("Plugin attached! (" + screentest.getPlugin() + ", id=" + screentest.getId() + ")");
                    // Prepare the username registration
                    $('#screenmenu').removeClass('hide').show();
                    $('#createnow').removeClass('hide').show();
                    $('#create').click(preShareScreen);
                    $('#joinnow').removeClass('hide').show();
                    $('#join').click(joinScreen);
                    $('#desc').focus();
                    $('#start').removeAttr('disabled').html("Stop")
                            .click(function () {
                                $(this).attr('disabled', true);
                                janus.destroy();
                            });
                },
                error: function (error) {
                    Janus.error("  -- Error attaching plugin...", error);
                    bootbox.alert("Error attaching plugin... " + error);
                },
                consentDialog: function (on) {
                    Janus.debug("Consent dialog should be " + (on ? "on" : "off") + " now");
                    if (on) {
                        // Darken screen
                        $.blockUI({
                            message: '',
                            css: {
                                border: 'none',
                                padding: '15px',
                                backgroundColor: 'transparent',
                                color: '#aaa'
                            }});
                    } else {
                        // Restore screen
                        $.unblockUI();
                    }
                },
                webrtcState: function (on) {
                    Janus.log("Janus says our WebRTC PeerConnection is " + (on ? "up" : "down") + " now");
                    $("#screencapture").parent().unblock();
                    bootbox.alert("Your screen sharing session just started: pass the <b>" + room + "</b> session identifier to those who want to attend.");
                },
                onmessage: function (msg, jsep) {
                    Janus.debug(" ::: Got a message (publisher) :::");
                    Janus.debug(JSON.stringify(msg));
                    var event = msg["videoroom"];
                    Janus.debug("Event: " + event);
                    if (event != undefined && event != null) {
                        if (event === "joined") {
                            myid = msg["id"];
                            $('#session').html(room);
                            $('#title').html(msg["description"]);
                            Janus.log("Successfully joined room " + msg["room"] + " with ID " + myid);
                            if (role === "publisher") {
                                // This is our session, publish our stream
                                Janus.debug("Negotiating WebRTC stream for our screen (capture " + capture + ")");
                                screentest.createOffer(
                                        {
                                            media: {video: capture, audio: false, videoRecv: false}, // Screen sharing doesn't work with audio, and Publishers are sendonly
                                            success: function (jsep) {
                                                Janus.debug("Got publisher SDP!");
                                                Janus.debug(jsep);
                                                var publish = {"request": "configure", "audio": true, "video": true};
                                                screentest.send({"message": publish, "jsep": jsep});
                                            },
                                            error: function (error) {
                                                Janus.error("WebRTC error:", error);
                                                bootbox.alert("WebRTC error... " + JSON.stringify(error));
                                            }
                                        });
                            } else {
                                // We're just watching a session, any feed to attach to?
                                if (msg["publishers"] !== undefined && msg["publishers"] !== null) {
                                    var list = msg["publishers"];
                                    Janus.debug("Got a list of available publishers/feeds:");
                                    Janus.debug(list);
                                    for (var f in list) {
                                        var id = list[f]["id"];
                                        var display = list[f]["display"];
                                        Janus.debug("  >> [" + id + "] " + display);
                                        newRemoteFeed(id, display)
                                    }
                                }
                            }
                        } else if (event === "event") {
                            // Any feed to attach to?
                            if (role === "listener" && msg["publishers"] !== undefined && msg["publishers"] !== null) {
                                var list = msg["publishers"];
                                Janus.debug("Got a list of available publishers/feeds:");
                                Janus.debug(list);
                                for (var f in list) {
                                    var id = list[f]["id"];
                                    var display = list[f]["display"];
                                    Janus.debug("  >> [" + id + "] " + display);
                                    newRemoteFeed(id, display)
                                }
                            } else if (msg["leaving"] !== undefined && msg["leaving"] !== null) {
                                // One of the publishers has gone away?
                                var leaving = msg["leaving"];
                                Janus.log("Publisher left: " + leaving);
                                if (role === "listener" && msg["leaving"] === source) {
                                    bootbox.alert("The screen sharing session is over, the publisher left", function () {
                                        window.location.reload();
                                    });
                                }
                            } else if (msg["error"] !== undefined && msg["error"] !== null) {
                                bootbox.alert(msg["error"]);
                            }
                        }
                    }
                    if (jsep !== undefined && jsep !== null) {
                        Janus.debug("Handling SDP as well...");
                        Janus.debug(jsep);
                        screentest.handleRemoteJsep({jsep: jsep});
                    }
                },
                onlocalstream: function (stream) {
                    Janus.debug(" ::: Got a local stream :::");
                    Janus.debug(JSON.stringify(stream));
                    $('#screenmenu').hide();
                    $('#room').removeClass('hide').show();
                    if ($('#screenvideo').length === 0) {
                        $('#screencapture').append('<video class="rounded centered" id="screenvideo" width="100%" height="100%" autoplay muted="muted"/>');
                    }
                    attachMediaStream($('#screenvideo').get(0), stream);
                    $("#screencapture").parent().block({
                        message: '<b>Publishing...</b>',
                        css: {
                            border: 'none',
                            backgroundColor: 'transparent',
                            color: 'white'
                        }
                    });
                },
                onremotestream: function (stream) {
                    // The publisher stream is sendonly, we don't expect anything here
                },
                oncleanup: function () {
                    Janus.log(" ::: Got a cleanup notification :::");
                    $('#screencapture').empty();
                    $("#screencapture").parent().unblock();
                    $('#room').hide();
                }
            });
}

function preShareScreen() {
    // Make sure HTTPS is being used
    if (window.location.protocol !== 'https:') {
        bootbox.alert('Sharing your screen only works on HTTPS: click <b><a href="#" onclick="return switchToHttps();">here</a></b> to try the https:// version of this page');
        $('#start').attr('disabled', true);
        return;
    }
    if (!Janus.isExtensionEnabled()) {
        bootbox.alert("You're using a recent version of Chrome but don't have the screensharing extension installed: click <b><a href='https://chrome.google.com/webstore/detail/janus-webrtc-screensharin/hapfgfdkleiggjjpfpenajgdnfckjpaj' target='_blank'>here</a></b> to do so", function () {
            window.location.reload();
        });
        return;
    }
    // Create a new room
    $('#desc').attr('disabled', true);
    $('#create').attr('disabled', true).unbind('click');
    $('#roomid').attr('disabled', true);
    $('#join').attr('disabled', true).unbind('click');
    if ($('#desc').val() === "") {
        bootbox.alert("Please insert a description for the room");
        $('#desc').removeAttr('disabled', true);
        $('#create').removeAttr('disabled', true).click(preShareScreen);
        $('#roomid').removeAttr('disabled', true);
        $('#join').removeAttr('disabled', true).click(joinScreen);
        return;
    }
    capture = "screen";
    if (navigator.mozGetUserMedia) {
        // Firefox needs a different constraint for screen and window sharing
        bootbox.dialog({
            title: "Share whole screen or a window?",
            message: "Firefox handles screensharing in a different way: are you going to share the whole screen, or would you rather pick a single window/application to share instead?",
            buttons: {
                screen: {
                    label: "Share screen",
                    className: "btn-primary",
                    callback: function () {
                        capture = "screen";
                        shareScreen();
                    }
                },
                window: {
                    label: "Pick a window",
                    className: "btn-success",
                    callback: function () {
                        capture = "window";
                        shareScreen();
                    }
                }
            },
            onEscape: function () {
                $('#desc').removeAttr('disabled', true);
                $('#create').removeAttr('disabled', true).click(preShareScreen);
                $('#roomid').removeAttr('disabled', true);
                $('#join').removeAttr('disabled', true).click(joinScreen);
            }
        });
    } else {
        shareScreen();
    }
}

function joinScreen() {
    // Join an existing screen sharing session
    $('#desc').attr('disabled', true);
    $('#create').attr('disabled', true).unbind('click');
    $('#roomid').attr('disabled', true);
    $('#join').attr('disabled', true).unbind('click');
    var roomid = $('#roomid').val();
    if (isNaN(roomid)) {
        bootbox.alert("Session identifiers are numeric only");
        $('#desc').removeAttr('disabled', true);
        $('#create').removeAttr('disabled', true).click(preShareScreen);
        $('#roomid').removeAttr('disabled', true);
        $('#join').removeAttr('disabled', true).click(joinScreen);
        return;
    }
    room = parseInt(roomid);
    role = "listener";
    myusername = randomString(12);
    var register = {"request": "join", "room": room, "ptype": "publisher", "display": myusername};
    screentest.send({"message": register});
}

function startScreenShare() {
    // Create another session for screen sharing(The screen takes up one user space in the room)
    janus = new Janus(
            {
                server: server,
                success: function () {
                    // Attach to video room test plugin
                    janus.attach(
                            {
                                plugin: "janus.plugin.videoroom",
                                success: function (pluginHandle) {
                                    $('#details').remove();
                                    screentest = pluginHandle;
                                    Janus.log("Plugin attached! (" + screentest.getPlugin() + ", id=" + screentest.getId() + ")");
                                    // Prepare the username registration
                                    preShareScreen();
                                },
                                error: function (error) {
                                    Janus.error("  -- Error attaching plugin...", error);
                                    bootbox.alert("Error attaching plugin... " + error);
                                },
                                consentDialog: function (on) {
                                    Janus.debug("Consent dialog should be " + (on ? "on" : "off") + " now");
                                },
                                webrtcState: function (on) {
                                    Janus.log("Janus says our WebRTC PeerConnection is " + (on ? "up" : "down") + " now");
                                },
                                onmessage: function (msg, jsep) {
                                    Janus.debug(" ::: Got a message (publisher) :::");
                                    Janus.debug(JSON.stringify(msg));
                                    var event = msg["videoroom"];
                                    Janus.debug("Event: " + event);
                                    if (event != undefined && event != null) {
                                        if (event === "joined") {
                                            myid = msg["id"];
                                            $('#session').html(room);
                                            $('#title').html(msg["description"]);
                                            Janus.log("Successfully joined room " + msg["room"] + " with ID " + myid);
                                            if (role === "publisher") {
                                                // This is our session, publish our stream
                                                Janus.debug("Negotiating WebRTC stream for our screen (capture " + capture + ")");
                                                screentest.createOffer(
                                                        {
                                                            media: {video: capture, audio: false, videoRecv: false}, // Screen sharing doesn't work with audio, and Publishers are sendonly
                                                            success: function (jsep) {
                                                                Janus.debug("Got publisher SDP!");
                                                                Janus.debug(jsep);
                                                                var publish = {"request": "configure", "audio": true, "video": true};
                                                                screentest.send({"message": publish, "jsep": jsep});
                                                            },
                                                            error: function (error) {
                                                                Janus.error("WebRTC error:", error);
                                                                bootbox.alert("WebRTC error... " + JSON.stringify(error));
                                                            }
                                                        });
                                            } else {
                                                // We're just watching a session, any feed to attach to?
                                                if (msg["publishers"] !== undefined && msg["publishers"] !== null) {
                                                    var list = msg["publishers"];
                                                    Janus.debug("Got a list of available publishers/feeds:");
                                                    Janus.debug(list);
                                                    for (var f in list) {
                                                        var id = list[f]["id"];
                                                        var display = list[f]["display"];
                                                        Janus.debug("  >> [" + id + "] " + display);
                                                        newRemoteFeed(id, display)
                                                    }
                                                }
                                            }
                                        } else if (event === "event") {
                                            // Any feed to attach to?
                                            if (role === "listener" && msg["publishers"] !== undefined && msg["publishers"] !== null) {
                                                var list = msg["publishers"];
                                                Janus.debug("Got a list of available publishers/feeds:");
                                                Janus.debug(list);
                                                for (var f in list) {
                                                    var id = list[f]["id"];
                                                    var display = list[f]["display"];
                                                    Janus.debug("  >> [" + id + "] " + display);
                                                    newRemoteFeed(id, display)
                                                }
                                            } else if (msg["leaving"] !== undefined && msg["leaving"] !== null) {
                                                // One of the publishers has gone away?
                                                var leaving = msg["leaving"];
                                                Janus.log("Publisher left: " + leaving);
                                                if (role === "listener" && msg["leaving"] === source) {
                                                    bootbox.alert("The screen sharing session is over, the publisher left", function () {
                                                        window.location.reload();
                                                    });
                                                }
                                            } else if (msg["error"] !== undefined && msg["error"] !== null) {
                                                bootbox.alert(msg["error"]);
                                            }
                                        }
                                    }
                                    if (jsep !== undefined && jsep !== null) {
                                        Janus.debug("Handling SDP as well...");
                                        Janus.debug(jsep);
                                        screentest.handleRemoteJsep({jsep: jsep});
                                    }
                                },
                                onlocalstream: function (stream) {
                                    Janus.debug(" ::: Got a local stream :::");
                                    Janus.debug(JSON.stringify(stream));
                                    $('#localVideo').append('<video class="rounded centered" id="myscreenshare" width="100%" autoplay muted="muted"/>');
                                    attachMediaStream($('#myscreenshare').get(0), stream);
                                },
                                onremotestream: function (stream) {
                                    // The publisher stream is sendonly, we don't expect anything here
                                },
                                oncleanup: function () {
                                    Janus.log(" ::: Got a cleanup notification :::");
                                }
                            });
                },
                error: function (error) {
                    Janus.error(error);
                    bootbox.alert(error, function () {
                        window.location.reload();
                    });
                },
                destroyed: function () {
                    //window.location.reload();
                }
            });
}

function stopScreenShare() {
    // Unpublish screensharing
    var unpublish = {"request": "unpublish"};
    screentest.send({"message": unpublish});
    $('#myscreenshare').remove();
}

// Just an helper to generate random usernames
function randomString(len, charSet) {
    charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
    	var randomPoz = Math.floor(Math.random() * charSet.length);
    	randomString += charSet.substring(randomPoz,randomPoz+1);
    }
    return randomString;
}