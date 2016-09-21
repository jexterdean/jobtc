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
                maxFrameRate: 15,
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
                    break;
                case 'connected':
                case 'completed': // on caller side
                    connstate.innerText = 'Connection established.';
                    connstate.remove();
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
    video.onclick = function () {
        video.style.width = video.videoWidth + 'px';
        video.style.height = video.videoHeight + 'px';
    };
    //document.getElementById('localVideo').appendChil(video);
    //$('#localScreenContainer').show();
    $('#localVideo').append(video);

});
// local screen removed
webrtc.on('localScreenRemoved', function (video) {
    document.getElementById('localVideo').removeChild(video);
    //$('#localScreenContainer').hide();
});

$('.interview-applicant').clickToggle(function () {
    $('.interview-applicant').addClass('btn-warning');
    $('.interview-applicant').removeClass('btn-success');
    $('.interview-applicant').children('span').text('Leave Conference');
    $('.interview-applicant').siblings('button').show();
    webrtc.joinRoom(room_name_tmp);
    //connection.open(room_name);
    //connection.join(room_name);
    webrtc.startLocalVideo();



}, function () {
    $(this).addClass('btn-success');
    $(this).removeClass('btn-warning');
    $(this).children('span').text('Join Conference');
    $(this).siblings('button').hide();
    //$('#localVideo').children().remove();
    webrtc.leaveRoom(room_name_tmp);
    webrtc.stopLocalVideo();
    $('#localVideo video').remove();
});

$('.screen-share').clickToggle(function () {
    console.log('Starting Screensharing');
    $(this).find('span').text('Stop Screen Share');
    webrtc.shareScreen(function (err) {
        if (err) {
            console.log("Screensharing error :" + err);
            $('.screen-share').click();
            if (err == 'EXTENSION_UNAVAILABLE: NavigatorUserMediaError' || err == 'PERMISSION_DENIED: NavigatorUserMediaError') {
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

}, function () {
    $(this).addClass('btn-default');
    $(this).removeClass('btn-danger');
    $(this).find('i').css('color', 'green');
    $(this).children('span').text('Start Recording');
    $('.save-progress').text("");
});

//Check if we have the Job.tc chrome extension if it's chrome
checkExtension();
function checkExtension() {
    if (typeof chrome !== "undefined" && typeof chrome.app !== "undefined" && chrome.app.isInstalled) {
        console.log('Job.tc extension is installed');
        hasExtension = true;
    }
}