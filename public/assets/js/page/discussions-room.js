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
                maxWidth: 652,
                maxHeight: 400
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
        video.style.width = '234px';
        // suppress contextmenu
        video.oncontextmenu = function () {
            return false;
        };

        var dom_id = webrtc.getDomId(peer);
        if (dom_id.includes('screen')) {
            //video.style.width = '652px';

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
    /*video.onclick = function () {
     video.style.width = video.videoWidth + 'px';
     video.style.height = video.videoHeight + 'px';
     };*/
    //document.getElementById('localVideo').appendChil(video);
    //$('#localScreenContainer').show();
    video.id = '';
    //Get the local screen media stream object
    localScreenStream = webrtc.getLocalScreen();
    console.log('This is the local screenshare stream: ' + localScreenStream);
    video.style.width = '234px';
    $(video).attr('controls', 'controls');
    $('#remoteScreen').append(video);
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
        $('#message-log').append('<text>' + data.payload.display_name+ " : " + data.payload.message + '</text><br />');
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

            var stop_button_exists = $('.stop-screen-share').length;

            if (stop_button_exists === 0) {
                $('<button class="btn stop-screen-share">Stop Screen Share</button>').insertAfter('.share-screen');
            }
        }
    });
});

$('body').on('click', '.stop-screen-share', function () {
    webrtc.stopScreenShare();
    $('#remoteScreen').children('video').remove();
    $('.stop-screen-share').remove();
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
    var message_object = '<text>' + webrtc.config.nick + " : "+ message + '</text><br />';
    $('#message-log').append(message_object);
    $("#message").val("");
    webrtc.sendToAll('chat', {message: message, display_name: webrtc.config.nick});
});

//Keypress events
$('body').keypress(function (e) {
    if (e.which == 13) {
        var message = $("#message").val();
        if (message !== "") {
            $('#message-log').append('<text>' + webrtc.config.nick + " : "+ message + '</text><br />');
            $("#message").val("");
            webrtc.sendToAll('chat', {message: message, display_name: webrtc.config.nick});
        }
        return false;
    }
});

$('.leave-discussion').click(function () {
    window.close();
});




