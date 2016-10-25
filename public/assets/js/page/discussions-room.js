/* 
 * Discussion Room scripts
 */

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

//Display name
var display_name = $('.display_name').val();

//Get Room name
var room_name_tmp = window.location.pathname;
var room_name = parseInt(room_name_tmp.substr(room_name_tmp.lastIndexOf('/') + 1));

console.log(room_name_tmp);

//For ScreenShare
var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
var hasExtension = false;


var screenshare_count = 0;
var participant_count = 0;

var peerStream;
//Video 

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
                //maxWidth: 652,
                maxWidth: 1920,
                //maxHeight: 400
                maxHeight: 1080
            }
        },
        audio: true
    },
    enableDataChannels: true,
    nick: display_name,
    url: 'https://laravel.software:8888'
});


webrtc.on('localStream', function (stream) {
    console.log('this is the localstream : ' + stream);
    localStream = stream;
});

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
        console.log(video.id);
        video.style.width = '234px';
        // suppress contextmenu
        video.oncontextmenu = function () {
            return false;
        };

        var dom_id = webrtc.getDomId(peer);
        if (dom_id.includes('screen')) {
            screenshare_count++;
            localScreenStream = peer.stream;

            //video.style.width = '652px';
            $(video).attr('controls', 'controls');
            var screenShareOptions = '<div class="screenshare_options">' +
                    '<button id="set-video-' + peer.stream.id + '" class="btn btn-small set-video">Set as Main Video</button>' +
                    '<button class="btn btn-small full-screen"><i class="fa fa-arrows-alt" aria-hidden="true"></i></button>' +
                    '<input class="screenshare_id" type="hidden" value="' + screenshare_count + '"/>' +
                    '<input class="stream_id" type="hidden" value="' + webrtc.getDomId(peer) + '">' +
                    '</div>';
            var screenContainer = "<div class='col-md-4' id='screenContainer-" + screenshare_count + "'>" + screenShareOptions + "</div>";

            $("#remoteScreen").append(screenContainer);
            $('#screenContainer-' + screenshare_count).prepend(video);

            $('#set-video-' + peer.stream.id).click(function () {
                //video.style.height = '639px';
                video.style.width = '600px';
                //$('#localVideo video').remove();
                //$('#localVideo').append(video);
            });

        } else {

            participant_count++;

            var remoteVideoOptions = '<div class="remote_video_options">' +
                    '<button class="btn btn-small set-video">Set as Main Video</button>' +
                    '<button class="btn btn-small full-screen"><i class="fa fa-arrows-alt" aria-hidden="true"></i></button>' +
                    '<input class="participant_id" type="hidden" value="' + participant_count + '"/>' +
                    '<input class="stream_id" type="hidden" value="' + webrtc.getDomId(peer) + '">' +
                    '</div>';
            var remoteVideoContainer = "<div class='col-md-3' id='remoteVideo-" + participant_count + "'>" + remoteVideoOptions + "</div>";

            $("#remoteVideo").append(remoteVideoContainer);
            $("#remoteVideo-" + participant_count).prepend(video);
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
                    break;
                case 'connected':
                case 'completed': // on caller side
                    connstate.innerText = 'Connection established.';
                    connstate.remove();
                    simpleRtcConnected = 1;
                    break;
                case 'disconnected':
                    connstate.innerText = 'Disconnected.';
                    break;
                case 'failed':
                    break;
                case 'closed':
                    connstate.innerText = 'Connection closed.';
                    connstate.remove();
                    break;
            }
        });
    }

    // receiving an incoming filetransfer
    peer.on('fileTransfer', function (metadata, receiver) {
        console.log('incoming filetransfer', metadata.name, metadata);
        receiver.on('progress', function (bytesReceived) {
            console.log('receive progress', bytesReceived, 'out of', metadata.size);
        });
        // get notified when file is done
        receiver.on('receivedFile', function (file, metadata) {
            console.log('received file', metadata.name, metadata.size);
            console.log("file:" + file);

            var file_url = window.URL.createObjectURL(file);

            $("#message-log").prepend('<a href="' + file_url + '" download="' + metadata.name + '"><i class="fa fa-file" aria-hidden="true"></i>' + metadata.name + '</a><br />');

            // close the channel
            receiver.channel.close();
        });
        //filelist.appendChild(item);
    });

    // send a file
    $('#sendFile').change(function () {

        var file = this.files[0];
        var name = file.name;
        var size = file.size;
        var type = file.type;
        console.log("Sending File: " + name);
        console.log("Size: " + size);
        console.log("Type: " + type);
        //webrtc.sendToAll('fileTransfer', {name: name, size: size, type: type});
        $("#message-log").prepend('<text>Sending file: ' + name + ' ' + size + ' bytes</text>');
        var sender = peer.sendFile(file);
    });
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
            //$('#remoteVideo').find('#remoteVideo-'+participant_count).remove();
            var remote_video_id = $('#container_' + webrtc.getDomId(peer)).parent().attr('id');
            console.log('remote_video_id:' + remote_video_id);
            $('#' + remote_video_id).remove();
        }

        if (dom_id.includes('screen') && el) {
            //remoteScreen.removeChild(el);
            var remote_video_id = $('#container_' + webrtc.getDomId(peer)).parent().attr('id');
            console.log('remote_video_id:' + remote_video_id);
            $('#' + remote_video_id).remove();
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

// called when a peer is created
webrtc.on('createdPeer', function (peer) {
    console.log('createdPeer', peer.stream);

});

webrtc.on('localScreen', function (video) {
    console.log('created localScreen');

});

/*For Screensharing*/
// local screen obtained
webrtc.on('localScreenAdded', function (video) {
    //Get the local screen media stream object

    screenshare_count++;
    video.id = "localScreen-" + screenshare_count;
    video.class = "localScreen";
    localScreenStream = webrtc.getLocalScreen();
    console.log('This is the local screenshare stream: ' + localScreenStream);
    video.style.width = '234px';
    $(video).attr('controls', 'controls');

    var screenShareOptions = '<div class="screenshare_options">' +
            '<button class="btn btn-small set-video">Set as Main Video</button>' +
            '<button class="btn btn-small full-screen"><i class="fa fa-arrows-alt" aria-hidden="true"></i></button>' +
            '<button class="btn btn-small stop-screen-share">' +
            '<i class="fa fa-times" aria-hidden="true"></i>' +
            '</button>' +
            '<input class="screenshare_id" type="hidden" value="' + screenshare_count + '"/>' +
            '</div>';
    var screenContainer = "<div class='col-md-4' id='screenContainer-" + screenshare_count + "'>" + screenShareOptions + "</div>";

    $("#remoteScreen").append(screenContainer);
    $('#screenContainer-' + screenshare_count).prepend(video);
    //$('#screenContainer-'+screenshare_count).append('<button class="btn btn-small stop-screen-share"><i class="fa fa-times" aria-hidden="true"></i></button>');
    //$('#screenContainer-'+screenshare_count).append('<input class="screenshare_id" type="hidden" value="'+screenshare_count+'"/>');
    hasShareScreen = 1;

});
// local screen removed
webrtc.on('localScreenRemoved', function (video) {
    document.getElementById('localScreen').removeChild(video);
    //$('#localScreenContainer').hide();
    $('#localScreen').html('');
    hasShareScreen = 0;
});

// we have to wait until it's ready
webrtc.on('readyToCall', function () {
    // you can name it anything
    webrtc.joinRoom(room_name_tmp, function () {
        $('#localVideoOptions').removeClass('hidden');
    });

});

webrtc.connection.on('message', function (data) {
    if (data.type === 'chat') {
        console.log('chat received' + JSON.stringify(data));
        $('#message-log').prepend('<text>' + data.payload.display_name + " : " + data.payload.message + '</text><br />');
    }
});

//Immediately start the local video upon entering this discussion room
webrtc.startLocalVideo();

//Remove video when the tab closes
window.addEventListener("beforeunload", function (e) {
    webrtc.leaveRoom(room_name_tmp);
    webrtc.stopLocalVideo();
});

$('.mute').clickToggle(function () {
    webrtc.mute();
    $(this).text('Unmute');
}, function () {
    webrtc.unmute();
    $(this).text('Mute');
});

$('.stop-video').clickToggle(function () {
    webrtc.pauseVideo();
    $(this).text('Start Video');
}, function () {
    webrtc.resumeVideo();
    $(this).text('Stop Video');
});

$('.share-screen').click(function () {
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
});

$('body').on('click', '.stop-screen-share', function () {

    var screenshare_id = $('.screenshare_id').val();
    $('#screenContainer-' + screenshare_id).remove();
    webrtc.stopScreenShare();
    //$('.localScreen').remove();
    //$('.stop-screen-share').remove();
});

$('#message').keyup(function () {
    var message = $("#message").val().length;
    if (message != 0) {
        $('#send-message').attr('disabled', false);
    } else {
        $('#send-message').attr('disabled', true);
    }
});

$('#send-message').click(function () {
    var message = $("#message").val();
    var message_object = '<text>' + webrtc.config.nick + " : " + message + '</text><br />';
    $('#message-log').prepend(message_object);
    $("#message").val("");
    webrtc.sendToAll('chat', {message: message, display_name: webrtc.config.nick});
});

//Keypress events
$('body').keypress(function (e) {
    if (e.which == 13) {
        var message = $("#message").val();
        if (message !== "") {
            $('#message-log').prepend('<text>' + webrtc.config.nick + " : " + message + '</text><br />');
            $("#message").val("");
            webrtc.sendToAll('chat', {message: message, display_name: webrtc.config.nick});
        }
        return false;
    }
});

$('body').on('click', '.full-screen', function () {
    var video_id = $(this).parent().parent().find('video').attr('id');
    var localVideo = document.getElementById(video_id);

    // go full-screen
    if (localVideo.requestFullscreen) {
        localVideo.requestFullscreen();
    } else if (localVideo.webkitRequestFullscreen) {
        localVideo.webkitRequestFullscreen();
    } else if (localVideo.mozRequestFullScreen) {
        localVideo.mozRequestFullScreen();
    } else if (localVideo.msRequestFullscreen) {
        localVideo.msRequestFullscreen();
    }

    console.log('full screen video id: ' + video_id);
});

$('.add-participant').click(function (e) {
    e.preventDefault();
    
    var add_participant_form = public_path + '/addParticipantForm';

    BootstrapDialog.show({
        title: 'Add Participant',
        size: 'size-normal',
        message: function (dialog) {
            var $message = $('<div></div>');
            var pageToLoad = dialog.getData('pageToLoad');
            $message.load(pageToLoad);
            return $message;
        },
        buttons: [{
                label: 'Send Invitation',
                cssClass: 'btn-edit btn-shadow',
                action: function (dialog) {
                    var ajaxurl = public_path + 'addParticipant';

                    var formData = new FormData();
                    var email = $('.email').val();
                    var room_url = window.location.href;
                    
                    formData.append('email', email);
                    formData.append('room_url',room_url);
                    console.log(room_url);

                    var $button = this; // 'this' here is a jQuery object that wrapping the <button> DOM element.
                    $button.disable();
                    $button.spin();

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
                            dialog.close();
                        },
                        error: function (xhr, status, error) {

                        }
                    }); //ajax
                }
            }, {
                label: 'Cancel',
                cssClass: 'btn-delete btn-shadow',
                action: function (dialog) {
                    dialog.close();
                }
            }],
        data: {
            'pageToLoad': add_participant_form
        },
        onshown: function (ref) {
            //initCkeditor(ref);
        },
        closable: false
    });

});


$('.leave-discussion').click(function () {
    window.close();
});

