@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-xs-8 align-center">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Video Conference</div>
                    <div class="panel-body">
                        <div class="col-md-6" id="local-video"></div>
                        <div class="col-md-6" id="remote-video">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <input type="text" name="username" id="username" />
                <button class="btn btn-default btn-shadow publish-btn">
                    <i class="fa fa-play"></i>&nbsp;
                    <span>Publish</span>
                </button>
                <button class="btn btn-default btn-shadow record-btn">
                    <i class="fa fa-circle"></i>&nbsp;
                    <span>Record</span>
                </button>
                <button class="btn btn-default btn-shadow save-btn">
                    <i class="fa fa-square"></i>&nbsp;
                    <span>Save</span>
                </button>
                <button class="btn btn-default btn-shadow replay-save-btn">
                    <i class="fa fa-square"></i>&nbsp;
                    <span>Replay</span>
                </button>
            </div>
        </div>
    </div>
    <div class="col-xs-4 align-center">
        <div class="panel panel-default">
            <div class="panel-heading">Video Replay</div>
            <div class="panel-body">
                <video id="replay-video" width="100%" controls="controls" autoplay="true">
                    Your browser does not support the video tag.
                </video>
                <div id="replay-janus-video"></div>
            </div>
        </div>
        <input type="text" name="video" value="101143085728269" id="video" />
        <button class="btn btn-default btn-shadow replay-btn">
            <i class="fa fa-play"></i>&nbsp;
            <span>Load</span>
        </button>
    </div>
</div>
@stop

@section('js_footer')
@parent

<style>
    #quiz-video,
    #quiz-video-play{
        height: 600px;
        width: 100%;
    }
</style>

<script>
    $(function(e){
        $.janusApi({
            btnPublish: $('.publish-btn'),
            btnRecord: $('.record-btn'),
            btnSave: $('.save-btn'),
            btnReplay: $('.replay-save-btn'),
            btnStop: $('.stop-btn'),
            userNameInput: $('#username'),
            replayVideo: $('#replay-janus-video'),
            //replayInput: $('#video'),
            roomId: 8888,
            roomPin: "8888"
        });
    });
</script>
@stop