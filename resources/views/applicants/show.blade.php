@extends('layouts.default')
@section('content')
<div class="applicant-posting-container container-fluid">
    <div class="row">
        @if(Auth::user('user'))
        <div class="row single-applicant-pagination hidden-lg hidden-md hidden-sm">                   
            <div class="col-xs-7">
            </div>
            <div class="col-xs-2">
                @if($previous_applicant !== NULL)
                <a class="btn btn-default btn-lg pager-previous pull-left" href="{{url('/a/'.$previous_applicant)}}" rel="previous"><i class="fa fa-chevron-circle-left"></i>&nbsp;Previous</a>
                @endif
            </div>
            <div class="col-xs-3">
                @if($next_applicant !== NULL)
                <a class="btn btn-default btn-lg pager-next pull-right" href="{{url('/a/'.$next_applicant)}}" rel="next">Next&nbsp;<i class="fa fa-chevron-circle-right"></i></a>
                @endif
            </div>
            <a href="#" class="btn btn-default pull-right close-applicant"><i class="fa fa-times"></i></a>
        </div>
        @endif
        <div class="mini-space"></div>
        <div class="applicant-posting-info hidden-lg hidden-md hidden-sm">
            <div class="row">
                <div class="col-md-12">
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                @if($applicant->photo !== '')
                                <img class="img-thumbnail media-object applicant-photo " src="{{url($applicant->photo)}}" alt="Applicant Photo">
                                @else
                                <img class="img-thumbnail media-object applicant-photo " src="{{url('assets/user/avatar.png')}}" alt="Applicant Photo">
                                @endif
                            </a>
                            @if(Auth::user('user'))
                            <div class="rating text-center"></div>
                            @endif
                        </div>
                        <div class="media-body media-right">
                            @if(Auth::user('user'))

                            <a href="#" class="btn btn-default pull-right interview-applicant"><i class="fa fa-comment-o"></i></a>
                            <text class="media-heading">{{$applicant->name}}&nbsp;<a href="{{$applicant->id}}" class="delete-applicant"><i class="fa fa-trash"></i></a></text>
                            @else
                            <text class="media-heading">{{$applicant->name}}</text>
                            @endif
                            <br />
                            <a href="tel:{{$applicant->phone}}" class="applicant-phone">{{$applicant->phone}}</a>
                            <br />
                            <a href="mailto:{{$applicant->email}}" class="applicant-email">{{$applicant->email}}</a>
                            <br />
                            <text class="applicant-job-title">{{$job->title}}</text>
                            <br />
                            <text>{{date_format(date_create($applicant->created_at),'M d,Y')}}</text>
                            @if(Auth::user('user'))
                            <br />
                            <textarea class="status-container">
                                        @if(isset($statuses))
                                            {{$statuses->status}}
                                        @endif    
                            </textarea>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div id="applicant-{{$count}}" class="applicant">
                <input class="token" name="_token" type="hidden" value="{{csrf_token()}}">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#resume-tab" aria-controls="home" role="tab" data-toggle="tab">Resume</a>
                    </li>
                    <li role="presentation">
                        <a href="#video-tab" aria-controls="profile" role="tab" data-toggle="tab">Video Conference</a>
                    </li>
                    <li role="presentation">
                        <a href="#video-archive-tab" aria-controls="profile" role="tab" data-toggle="tab">Video Archive</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="resume-tab">
                        <iframe class="applicant-posting-resume" src="https://docs.google.com/viewer?url={{url($applicant->resume)}}&embedded=true"></iframe>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="video-tab">
                        <div class="video-conference-container">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="localVideoContainer">
                                        <div id="localVideo"></div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div id="remotes">
                                        <div id="remoteVideo"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="video-options text-center">
                                        <button class="btn btn-default mute-button"><i class="fa fa-microphone"></i>&nbsp;<span>Mute</span></button>
                                        <button class="btn btn-default show-video-button"><i class="fa fa-eye"></i>&nbsp;<span>Stop Video</span></button>
                                        @if(Auth::user('user'))
                                        <button class="btn btn-default record-button"><i class="fa fa-circle"></i>&nbsp;<span>Start Recording</span></button>
                                        @endif
                                        <button href="#" class="btn btn-success interview-applicant"><i class="fa fa-phone"></i>&nbsp;<span>Join Conference</span></button>
                                        <div class="video-options-text pull-right">
                                            <text class="save-progress"></text>
                                            <text class="total-files"></text>
                                        </div>
                                    </div>
                                    <audio controls class="download-complete-sound" src="{{url('assets/sounds/download_complete.wav')}}"></audio>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="preview-video text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="video-archive-tab">
                        <div class="video-page-container">
                            @foreach($videos as $video)
                            <div class="video-element-holder">
                                <div class="row">
                                    <div class="col-xs-10">
                                        <video id="video-archive-item-{{$video->id}}" class="video-archive-item" controls="controls"  preload="metadata" src="{{url($video->video_url)}}">
                                            Your browser does not support the video tag.
                                            <!--source src="{{url($video->video_url)}}"-->
                                        </video>
                                    </div>
                                    <div class="col-xs-2">
                                        <button class="btn btn-danger pull-right delete-video"><i class="fa fa-times"></i></button>
                                        <input class="video_id" type="hidden" value="{{$video->id}}"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <textarea class="video-status-container">
                                                {{$video->video_statuses['video_status']}}
                                        </textarea>
                                        <input class="video_id" type="hidden" value="{{$video->id}}"/>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            @if(Auth::user('user'))
            <div class="row single-applicant-pagination hidden-xs">
                <div class="col-xs-7">
                    <div id="job-title" class="btn btn-default bg-gray btn-lg pull-right"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;{{$job->title}}</div>
                </div>
                <div class="col-xs-2">
                    @if($previous_applicant !== NULL)
                    <a class="btn btn-default btn-lg pager-previous pull-left" href="{{url('/a/'.$previous_applicant)}}" rel="previous"><i class="fa fa-chevron-circle-left"></i>&nbsp;Previous</a>
                    @endif
                </div>
                <div class="col-xs-2">
                    @if($next_applicant !== NULL)
                    <a class="btn btn-default btn-lg pager-next pull-right" href="{{url('/a/'.$next_applicant)}}" rel="next">Next&nbsp;<i class="fa fa-chevron-circle-right"></i></a>
                    @endif
                </div>
                <div class="col-xs-1">
                    <a href="#" class="btn btn-default btn-lg close-applicant"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="mini-space"></div>
            @endif
            <div class="applicant-posting-info hidden-xs">
                <div class="row">
                    <div class="col-md-12">
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    @if($applicant->photo !== '')
                                    <img class="img-thumbnail media-object applicant-photo " src="{{url($applicant->photo)}}" alt="Applicant Photo">
                                    @else
                                    <img class="img-thumbnail media-object applicant-photo " src="{{url('user/avatar.png')}}" alt="Applicant Photo">
                                    @endif
                                </a>
                                @if(Auth::user())
                                <div class="rating text-center"></div>
                                @endif
                            </div>
                            <div class="media-body media-right">
                                @if(Auth::user('user'))
                                <text class="media-heading">{{$applicant->name}}&nbsp;<a href="{{$applicant->id}}" class="delete-applicant"><i class="fa fa-trash"></i></a></text>
                                @else
                                <text class="media-heading">{{$applicant->name}}</text>
                                @endif
                                <br />
                                <a href="tel:{{$applicant->phone}}" class="applicant-phone">{{$applicant->phone}}</a>
                                <br />
                                <a href="mailto:{{$applicant->email}}" class="applicant-email">{{$applicant->email}}</a>
                                <br />
                                <text>{{date_format(date_create($applicant->created_at),'M d,Y')}}</text>
                                <br />
                                @if(Auth::user('user'))
                                <textarea class="status-container">
                                            @if(isset($statuses))
                                            {{$statuses->tags}}
                                            @endif
                                </textarea>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--div class="comment-list-header">Comments</div-->
            <div class="mini-space"></div>
            <div id="comment-list-{{$applicant->id}}" class="comment-list">
                @unless($comments->count())
                <div class="no-comment-notifier"></div>
                @else
                @foreach($comments as $comment)
                <div id="comment-item-{{$comment->comment_id}}" class="comment-item">
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                @if($comment->user->photo !== '' && Auth::user()->user_id === $comment->user->user_id)
                                <img class="comment-photo" src="{{url($comment->user->photo)}}" alt="Employee Photo">
                                @elseif ($comment->user->photo === '' && Auth::user()->user_id === $comment->user->user_id)
                                <img class="comment-photo" src="{{url('assets/user/avatar.png')}}" alt="Employee Photo">
                                @elseif ($comment->applicant->photo !== '')
                                <img class="comment-photo" src="{{url($comment->applicant->photo)}}" alt="Employee Photo">
                                @else
                                <img class="comment-photo" src="{{url('assets/user/avatar.png')}}" alt="Employee Photo">
                                @endif
                            </a>
                            @if(isset($comment->user->name))
                            <text class="media-heading">{{$comment->user->name}}</text>
                            @else
                            <text class="media-heading">{{$comment->applicant->name}}</text>
                            @endif
                        </div>
                        <div class="media-body media-right">

                            <p class="comment">{!!nl2br(e($comment->comment))!!}</p>
                        </div>
                        <input class="comment_id" type="hidden" value="{{$comment->comment_id}}">
                        <input class="applicant_id" type="hidden" value="{{$comment->applicant->applicant_id}}">
                    </div>
                    @if($user_info->user_id === $comment->user_id && Auth::check("user") || $comment->user_id === 0 && Auth::check("applicant"))
                    <table class="comment-utilities">
                        <tr>
                            <td><a href="#" class="edit-comment"><i class="fa fa-pencil"></i></a></td>
                            <td>&nbsp;</td>
                            <td><a href="#" class="delete-comment"><i class="fa fa-times"></i></a></td>
                        </tr>
                    </table>
                    @endif
                </div>
                <!--div class="mini-space"></div-->
                @endforeach
                @endunless
            </div>
            <div class="mini-space"></div>
            @include('forms.addCommentForm')
        </div>
    </div>
</div>
<div class="mini-space"></div>
<input class="applicant_score" type="hidden" value="{{$rating->score or ''}}"/>
<input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
<input class="job_id" type="hidden" value="{{$applicant->job_id}}"/>
@stop


