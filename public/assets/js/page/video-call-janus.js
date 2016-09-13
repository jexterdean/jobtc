/* 
 * Janus Web Gateway Client for Video Call and Recording
 */

//var server = "https://laravel.software:8089/janus";
var server = "https://ubuntu-server.com:8089/janus";
var janus = null;
var videocall = null;
var started = false;
var bitrateTimer = null;
var spinner = null;

var audioenabled = false;
var videoenabled = false;

var myusername = null;
var yourusername = null;

var display_name = $('.add-comment-form .media-heading').text();
var applicant_name = $('.applicant-posting-info .media-heading').text();
console.log(display_name);
console.log('Applicant: '+applicant_name);
var room_name_tmp = window.location.pathname;
var room_name = parseInt(room_name_tmp.substr(room_name_tmp.lastIndexOf('/') + 1));

var bandwidth = 0; //0 is unlimited

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


$(document).ready(function () {
    //Initialize the Janus Object
    Janus.init({debug: true, callback: function () {

        }});
});


$('.interview-applicant').clickToggle(function () {
    $('.interview-applicant').addClass('btn-warning');
    $('.interview-applicant').removeClass('btn-success');
    $('.interview-applicant').children('span').text('Leave Conference');
    $('.interview-applicant').siblings('button').show();
    joinConference();
}, function () {
    $(this).addClass('btn-success');
    $(this).removeClass('btn-warning');
    $(this).children('span').text('Join Conference');
    $(this).siblings('button').hide();
    leaveConference();
});

function joinConference() {
    // Create session
    janus = new Janus(
            {
                server: server,
                success: function () {
                    // Attach to echo test plugin
                    janus.attach(
                            {
                                plugin: "janus.plugin.videocall",
                                success: function (pluginHandle) {
                                    videocall = pluginHandle;
                                    Janus.log("Plugin attached! (" + videocall.getPlugin() + ", id=" + videocall.getId() + ")");
                                    publishOwnFeed();
                                    /*var createRoom = {
                                     "request": "create",
                                     "record": false,
                                     "publishers": 10,
                                     "room": room_name,
                                     "bitrate": bandwidth
                                     };
                                     videocall.send({"message": createRoom});
                                     var register = {"request": "join", "room": room_name, "ptype": "publisher", "display": display_name};
                                     myusername = display_name;
                                     videocall.send({"message": register});*/
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
                                    Janus.debug(" ::: Got a message :::");
                                    Janus.debug(JSON.stringify(msg));
                                    var result = msg["result"];
                                    if (result !== null && result !== undefined) {
                                        if (result["list"] !== undefined && result["list"] !== null) {
                                            var list = result["list"];
                                            Janus.debug("Got a list of registered peers:");
                                            Janus.debug(list);
                                            for (var mp in list) {
                                                Janus.debug("  >> [" + list[mp] + "]");
                                            }
                                        } else if (result["event"] !== undefined && result["event"] !== null) {
                                            var event = result["event"];
                                            if (event === 'registered') {
                                                myusername = result["username"];
                                                Janus.log("Successfully registered as " + myusername + "!");
                                                $('#youok').removeClass('hide').show().html("Registered as '" + myusername + "'");
                                                // Get a list of available peers, just for fun
                                                videocall.send({"message": {"request": "list"}});
                                                // TODO Enable buttons to call now
                                                $('#phone').removeClass('hide').show();
                                                $('#call').unbind('click').click(doCall);
                                                $('#peer').focus();
                                            } else if (event === 'calling') {
                                                Janus.log("Waiting for the peer to answer...");
                                                // TODO Any ringtone?
                                            } else if (event === 'incomingcall') {
                                                Janus.log("Incoming call from " + result["username"] + "!");
                                                $('#peer').val(result["username"]).attr('disabled');
                                                yourusername = result["username"];
                                                // TODO Enable buttons to answer
                                                videocall.createAnswer(
                                                        {
                                                            jsep: jsep,
                                                            // No media provided: by default, it's sendrecv for audio and video
                                                            media: {data: true}, // Let's negotiate data channels as well
                                                            success: function (jsep) {
                                                                Janus.debug("Got SDP!");
                                                                Janus.debug(jsep);
                                                                var body = {"request": "accept"};
                                                                videocall.send({"message": body, "jsep": jsep});
                                                            },
                                                            error: function (error) {
                                                                Janus.error("WebRTC error:", error);
                                                                bootbox.alert("WebRTC error... " + JSON.stringify(error));
                                                            }
                                                        });
                                            } else if (event === 'accepted') {
                                                var peer = result["username"];
                                                if (peer === null || peer === undefined) {
                                                    Janus.log("Call started!");
                                                } else {
                                                    Janus.log(peer + " accepted the call!");
                                                    yourusername = peer;
                                                }
                                                // TODO Video call can start
                                                if (jsep !== null && jsep !== undefined)
                                                    videocall.handleRemoteJsep({jsep: jsep});
                                            } else if (event === 'hangup') {
                                                Janus.log("Call hung up by " + result["username"] + " (" + result["reason"] + ")!");
                                                // TODO Reset status
                                                videocall.hangup();
                                            }
                                        }
                                    } else {
                                        // FIXME Error?
                                        var error = msg["error"];
                                        bootbox.alert(error);
                                    }
                                },
                                onlocalstream: function (stream) {
                                    Janus.debug(" ::: Got a local stream :::");
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
                                    Janus.debug(" ::: Got a remote stream :::");
                                    Janus.debug(JSON.stringify(stream));
                                    console.log('Here appending the remote stream');
                                    $('#remoteVideo').append('<video class="rounded centered" id="remote" width="100%" autoplay/>');
                                    attachMediaStream($('#remote').get(0), stream);
                                    var videoTracks = stream.getVideoTracks();
                                    if (videoTracks === null || videoTracks === undefined || videoTracks.length === 0) {
                                        // No webcam
                                        $('#remoteVideo').append(
                                                '<div class="no-video-container">' +
                                                '<i class="fa fa-video-camera fa-5 no-video-icon"></i>' +
                                                '<span class="no-video-text">No webcam available</span>' +
                                                '</div>');
                                    }
                                },
                                ondataopen: function (data) {
                                    Janus.log("The DataChannel is available!");
                                },
                                ondata: function (data) {
                                    Janus.debug("We got data from the DataChannel! " + data);
                                },
                                oncleanup: function () {
                                    Janus.log(" ::: Got a cleanup notification :::");
                                }
                            });
                },
                error: function (error) {
                    Janus.error(error);
                },
                destroyed: function () {

                }
            });
}

function leaveConference() {
    unpublishOwnFeed();
}

function publishOwnFeed(useAudio) {
    // Publish our stream
    //$('#publish').attr('disabled', true).unbind('click');
    videocall.createOffer(
            {
                media: {audioRecv: true, videoRecv: true, audioSend: useAudio}, // Publishers are sendonly
                success: function (jsep) {
                    Janus.debug("Got publisher SDP!");
                    //Janus.debug(jsep);
                    //var publish = {"request": "configure", "audio": useAudio, "video": true};
                    var publish = {"audio": true, "video": true};
                    videocall.send({"message": publish, "jsep": jsep});
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
    videocall.detach();
    $('#localVideo').children().remove();
    var unpublish = {"request": "unpublish"};
    videocall.send({"message": unpublish});
}