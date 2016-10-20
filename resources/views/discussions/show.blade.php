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

        </div>
    </div>
    <div class="col-md-6 remoteVideoContainer">
        <div class="center-block"><button class="btn add-participant"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Participant</button></div>
        <div id="remotes">
            <div class="row" id="remoteVideo">

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
                            <label class="btn btn-sm">
                                <i class="fa fa-file" aria-hidden="true" for="sendFile"></i>
                                <input id="sendFile" type="file" style="display:none" class="btn btn-warning btn-sm" value="Send File" />
                            </label>
                        </span>
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" disabled="disabled" id="send-message">Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 remoteScreenContainer">
        <div class="center-block"><button class="btn share-screen"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Share Screen</button></div>
        <div class="row" id="remoteScreen">
        </div>
    </div>
</div>
<input class="display_name" type="hidden" value="{{$display_name}}"/>
@stop