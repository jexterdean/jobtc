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

var server = "https://ubuntu-server.com:8089/janus";

var janus = null;
var sfutest = null;
var started = false;

var myusername = null;
var myid = null;
var mystream = null;

var feeds = [];
var bitrateTimer = [];
var bandwidth = 1024 * 1024;

var display_name = $('.add-comment-form media-heading').text();

var room_name_tmp = window.location.pathname;
var room_name = parseInt(room_name_tmp.substr(room_name_tmp.lastIndexOf('/') + 1));

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
                                                    "record": false,
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
                                                                newRemoteFeed(id, display)
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
                                                $('#localVideo').append('<video class="rounded centered" id="myvideo" width="100%" height="100%" autoplay muted="muted"/>');
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
    
    
    $('.record-button').clickToggle(function(){
        
    },function(){
        
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
                media: {audioRecv: false, videoRecv: false, audioSend: useAudio, videoSend: true, video: 'hires'}, // Publishers are sendonly
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

function toggleMute() {
    var muted = sfutest.isAudioMuted();
    Janus.log((muted ? "Unmuting" : "Muting") + " local stream...");
    if (muted)
        sfutest.unmuteAudio();
    else
        sfutest.muteAudio();
    muted = sfutest.isAudioMuted();
    $('#mute').html(muted ? "Unmute" : "Mute");
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
    
}
