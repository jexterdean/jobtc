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

var server = null;
if (window.location.protocol === 'http:')
    server = "http://" + window.location.hostname + ":8088/janus";
else
    server = "https://" + window.location.hostname + ":8089/janus";

var janus = null;
var recordplay = null;
var started = false;
var spinner = null;
var bandwidth = 1024 * 1024;

var myname = null;
var recording = false;
var playing = false;
var recordingId = null;
var selectedRecording = null;
var selectedRecordingInfo = null;

$('.interview-applicant').clickToggle(function () {
    $('.nav-tabs a[href="#video-tab"]').tab('show');
    $('.interview-applicant').addClass('btn-warning');
    $('.interview-applicant').removeClass('btn-success');
    $('.interview-applicant').children('span').text('Leave Conference');
    $('.interview-applicant').siblings('button').show();


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
                                    sfutest = pluginHandle;
                                    Janus.log("Plugin attached! (" + sfutest.getPlugin() + ", id=" + sfutest.getId() + ")");
                                    Janus.log("  -- This is a publisher/manager");
                                   
                                },
                                error: function (error) {
                                    Janus.error("  -- Error attaching plugin...", error);
                                    bootbox.alert("Error attaching plugin... " + error);
                                },
                                consentDialog: function (on) {
                                  
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
                                },
                                onlocalstream: function (stream) {
                                    Janus.debug(" ::: Got a local stream :::");
                                    mystream = stream;
                                    Janus.debug(JSON.stringify(stream));
                                    attachMediaStream($('#localVideo').get(0), stream);
                                    var videoTracks = stream.getVideoTracks();
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
                    
                },
                destroyed: function () {
                    
                }
            });

},
        function () {
            $(this).addClass('btn-success');
            $(this).removeClass('btn-warning');
            $(this).children('span').text('Join Conference');
            $(this).siblings('button').hide();
        });