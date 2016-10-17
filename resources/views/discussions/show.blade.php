@extends('layouts.default')
@section('content')
<?php
/*
 * Discussions room page
 */
?>
<div class="row">
    <div class="col-md-6 localVideoContainer">
        <div id="localVideo"></div>
        <div id="localVideoOptions" class="hidden">            
            <button class="btn mute">Mute</button>
            <button class="btn stop-video">Stop Video</button>
            <button class="btn share-screen">Share Screen</button>
        </div>
    </div>
    <div class="col-md-6 remoteVideoContainer">
        <div class="center-block">Participants</div>
        <div id="remotes">
            <div id="remoteVideo">

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 ">
        <div class="chat-box">
            <div class="panel panel-default">
                <div id="message-log" class="panel-body">
                    
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="message" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" disabled="disabled" id="send-message">Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 remoteScreenContainer">
        <div class="center-block">Shared Screens</div>
        <div id="remoteScreen">
        </div>
    </div>
</div>

@stop