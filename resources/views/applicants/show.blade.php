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
                <a class="btn btn-default btn-shadow btn-lg pager-previous pull-left" href="{{url('/a/'.$previous_applicant)}}" rel="previous"><i class="fa fa-chevron-circle-left"></i>&nbsp;Previous</a>
                @endif
            </div>
            <div class="col-xs-3">
                @if($next_applicant !== NULL)
                <a class="btn btn-default btn-shadow btn-lg pager-next pull-right" href="{{url('/a/'.$next_applicant)}}" rel="next">Next&nbsp;<i class="fa fa-chevron-circle-right"></i></a>
                @endif
            </div>
            <a href="#" class="btn btn-default btn-shadow pull-right close-applicant"><i class="fa fa-times"></i></a>
        </div>
        @endif
        <div class="mini-space"></div>
        <div class="applicant-posting-info hidden-lg hidden-md hidden-sm">
            <div class="row">
                <div class="col-md-12">
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                @if($applicant->photo !== '' && file_exists(public_path() . '/' . $applicant->photo))
                                <img class="img-thumbnail media-object applicant-photo edit-applicant is-upload-document" data-toggle="tooltip" title="Upload Photo" src="{{url($applicant->photo)}}" alt="Applicant Photo">
                                @else
                                <img class="img-thumbnail media-object applicant-photo edit-applicant is-upload-document" data-toggle="tooltip" title="Upload Photo" src="{{url('assets/user/avatar.png')}}" alt="Applicant Photo">
                                @endif
                                <input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
                                <input class="company_id" type="hidden" value="{{$job->company_id}}"/>
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
                                <a class="btn btn-shadow btn-delete pull-right" href="{{ url('/logout') }}"><i class="glyphicon glyphicon-off"></i> Logout</a>
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
                                                {{$statuses->tags}}
                                            @endif
                                </textarea>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            @if(Auth::user('user'))
            <div class="row single-applicant-pagination hidden-xs">
                <div class="col-xs-7">
                    <a href="{{url('job/'.$job->id)}}" id="job-title" data-toggle="tooltip" title="{{$job->title}}"  data-placement="bottom" class="btn btn-shadow btn-default bg-gray btn-lg pull-right"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;{{$job->title}}</a>
                </div>
                <div class="col-xs-3">
                    @if($previous_applicant !== NULL)
                    <a class="btn btn-shadow btn-default btn-lg pager-previous pull-left" href="{{url('/a/'.$previous_applicant)}}" rel="previous"><i class="fa fa-chevron-circle-left"></i>&nbsp;Previous</a>
                    @endif
                </div>
                <div class="col-xs-2">
                    @if($next_applicant !== NULL)
                    <a class="btn btn-shadow btn-default btn-lg pager-next pull-right" href="{{url('/a/'.$next_applicant)}}" rel="next">Next&nbsp;<i class="fa fa-chevron-circle-right"></i></a>
                    @endif
                </div>
                <div class="col-xs-1">
                    <!--a href="#" class="btn btn-default btn-lg close-applicant"><i class="fa fa-times"></i></a-->
                </div>
            </div>
            <div class="mini-space"></div>
            @endif
            <div id="applicant-{{$applicant->id}}" class="applicant-posting-info hidden-xs">
                <div class="row">
                    <div class="col-md-12">
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    @if($applicant->photo !== '' && file_exists(public_path() . '/' . $applicant->photo))
                                    <img class="img-thumbnail media-object applicant-photo edit-applicant is-upload-document" data-toggle="tooltip" title="Upload Photo" src="{{url($applicant->photo)}}" alt="Applicant Photo">
                                    @else
                                    <img class="img-thumbnail media-object applicant-photo edit-applicant is-upload-document" data-toggle="tooltip" title="Upload Photo" src="{{url('assets/user/avatar.png')}}" alt="Applicant Photo">
                                    @endif
                                    <input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
                                    <input class="company_id" type="hidden" value="{{$job->company_id}}"/>
                                </a>
                                @if(Auth::user())
                                <div class="rating text-center"></div>
                                @endif
                            </div>
                            <div class="media-body media-right">
                                {{--*/ $display_move_btn = $applicant->hired === 'Yes' ? 'display:inline;' : 'display:none;' /*--}}
                                @if(Auth::user('user'))
                                <text class="media-heading">
                                    {{$applicant->name}}&nbsp;
                                    @if(Auth::user('user')->user_id === $job->user_id)
                                        @if($applicant->hired === 'No')
                                        <a href="#" class='pull-right btn btn-edit btn-shadow bg-light-blue-gradient hire'>Hire</a>
                                        <input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
                                        <input class="company_id" type="hidden" value="{{$job->company_id}}"/>
                                        @else
                                        <a href="#" class='pull-right btn btn-shadow bg-green hire'><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Hired</a>
                                        <input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
                                        <input class="company_id" type="hidden" value="{{$job->company_id}}"/>
                                        @endif
                                    @endif
                                </text>
                                @else
                                    <text class="media-heading applicant-name"><span>{{$applicant->name}}</span></text>
                                    @if(Auth::user('applicant'))
                                    <a class="btn btn-shadow btn-delete pull-right" href="{{ url('/logout') }}"><i class="glyphicon glyphicon-off"></i> Logout</a>
                                    @endif
                                @endif

                                <br />
                                <a href="tel:{{$applicant->phone}}" class="applicant-phone">{{$applicant->phone}}</a>
                                <br />
                                <a href="mailto:{{$applicant->email}}" class="applicant-email">{{$applicant->email}}</a>
                                <br />
                                <text>{{date_format(date_create($applicant->created_at),'M d,Y')}}</text>
                                <br />
                                @if(Auth::user('applicant'))
                                <div class="applicant-options">
                                    <a class="btn btn-edit btn-shadow bg-light-blue-gradient edit-applicant" href="#"><i class="fa fa-pencil" aria-hidden="true"></i>  Edit </a>
                                    <a class="btn btn-edit btn-shadow bg-light-blue-gradient edit-applicant-password" href="#">Change Password</a>
                                    <input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
                                    <input class="company_id" type="hidden" value="{{$job->company_id}}"/>
                                </div>
                                @endif
                                {{--Admin Option--}}
                                @if($module_permissions->where('slug','edit.applicants')->count() === 1)
                                <div class="applicant-options">
                                    {{--<a class="btn btn-edit btn-shadow bg-light-blue-gradient edit-applicant is-upload-document" href="#"><i class="fa fa-pencil" aria-hidden="true"></i>  Upload </a>--}}
                                    <input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
                                    <input class="company_id" type="hidden" value="{{$job->company_id}}"/>
                                </div>
                                @endif

                                <br />
                                @if(Auth::user('user'))
                                <textarea class="status-container">
                                            @if(isset($statuses))
                                            {{$statuses->tags}}
                                        @endif    
                                </textarea>
                                @endif
                                <a href="#move_applicant_{{ $applicant->id }}" class="pull-right move-btn btn btn-shadow btn-edit" style="{{ $display_move_btn }}" data-toggle="modal">Move</a>
                            </div>
                        </div>
                    </div>
                </div>
                {{--region Move Applicant Modal--}}
                <div class="modal fade" id="move_applicant_{{ $applicant->id }}" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                <h4 class="modal-title">Move to Briefcase</h4>
                            </div>
                            {!!  Form::open(['method' => 'POST','route' => ['task.store'],'class' => 'task-form'])  !!}
                            <div class="modal-body">
                                {!!  Form::hidden('belongs_to','project')  !!}
                                <div class="form-group">
                                    {!!  Form::select('unique_id',$projects, '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Project Name', 'tabindex' => '7'] )  !!}
                                </div>
                                <div class="form-group">
                                    {!!  Form::input('text','task_title', $applicant->name,['class' => 'form-control', 'placeholder' => 'Title', 'tabindex' => '1']) !!}
                                </div>
                                <div class="form-group">
                                    {!!  Form::textarea('task_description','',['size' => '30x3', 'class' => 'form-control',
                                    'placeholder' => 'Description', 'tabindex' => '2']) !!}
                                </div>
                                <div class="form-group">
                                    {!!  Form::input('text','due_date','',['class' => 'form-control form-control-inline
                                    input-medium date-picker', 'placeholder' => 'Due Date', 'tabindex' => '3', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="form-group">
                                    {!!  Form::submit('Add',['class' => 'btn btn-shadow btn-edit', 'tabindex' => '5'])  !!}
                                </div>
                            </div>
                            {!!  Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            @if(Auth::check('user') || Auth::check('applicant'))
            <div class="mini-space"></div>
            {{--*/ $collapse = $applicant->notes ? 'in' : '' /*--}}
            {{--*/ $str = str_replace('\\','/',$applicant->resume) /*--}}
            {{--*/ $file = explode('/',$str) /*--}}
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div id="collapse-container-1" class="panel task-list">
                            <div class="panel-heading task-header" id="notes-{{$applicant->id}}" data-toggle="collapse" data-target="#notes-collapse-{{ $applicant->id }}">
                                <div class="row">
                                    <h4 class="panel-title task-list-header">Notes</h4>
                                </div>
                            </div>
                            <div id="notes-collapse-{{ $applicant->id }}" class="box-content collapse {{ $collapse }}">
                                <div class="panel-body">
                                    <div class="panel-content">
                                        <textarea id="applicant-notes" class="">{{$applicant->notes}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(file_exists(public_path() .'/' . $str))
            <div class="row">
                <div class="col-sm-12">
                    <a href="{{ url('downloadFile?file=' . $str)}}" class="btn btn-edit btn-shadow" data-toggle="tooltip"
                            title="Download {{end($file)}}" data-placement="right">
                            <i class="glyphicon glyphicon-file"></i> {{end($file)}}</a>
                </div>
            </div>
            @endif
            <div id="comment-list-{{$applicant->id}}" class="comment-list">
                @unless($comments->count())
                <div class="no-comment-notifier"></div>
                @else
                @foreach($comments as $comment)
                <div id="comment-item-{{$comment->comment_id}}" class="comment-item">
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                @if(isset($comment->user->photo) && file_exists(public_path().'/'.$comment->user->photo))
                                <img class="comment-photo" src="{{url($comment->user->photo)}}" alt="Employee Photo">
                                @elseif(isset($comment->applicant->photo) && file_exists(public_path().'/'.$comment->applicant->photo))
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
                    @if($user_info->commenter_id === $comment->user_id && Auth::check("user") 
                    || $comment->commenter_id === 0 && Auth::check("applicant"))
                    <table class="comment-utilities">
                        <tr>
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
            @endif
        </div>
        <div class="col-md-7">
            <div id="applicant-{{$count}}" class="applicant">
                <input class="token" name="_token" type="hidden" value="{{csrf_token()}}">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#resume-tab" aria-controls="home" role="tab" data-toggle="tab">Resume</a>
                    </li>
                    @if(Auth::check('user') || Auth::check('applicant'))
                    <li role="presentation">
                        <a href="#video-tab" aria-controls="profile" role="tab" data-toggle="tab">Video Conference</a>
                    </li>
                    <li role="presentation">
                        <a href="#video-archive-tab" aria-controls="profile" role="tab" data-toggle="tab">Video Archive</a>
                    </li>
                    <li role="presentation">
                        <a href="#tests-tab" aria-controls="profile" role="tab" data-toggle="tab">Tests</a>
                    </li>
                    @endif
                    @if(Auth::check() && Auth::user()->level() === 1)
                    <li role="presentation">
                        <a href="#criteria-tab" aria-controls="profile" role="tab" data-toggle="tab">Criteria</a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="resume-tab">
                        <iframe class="applicant-posting-resume" src="https://docs.google.com/viewer?url={{url($str)}}&embedded=true"></iframe>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="video-tab">
                        <div class="video-conference-container">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="localVideoContainer">
                                        <div id="localVideo"></div>
                                        <div id="localScreen"></div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div id="remotes">
                                        <div id="remoteVideo"></div>
                                        <div id="remoteScreen"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="video-options text-center">
                                        <button class="btn btn-default btn-shadow mute-button"><i class="fa fa-microphone"></i>&nbsp;<span>Mute</span></button>
                                        <button class="btn btn-default btn-shadow show-video-button"><i class="fa fa-eye"></i>&nbsp;<span>Stop Video</span></button>
                                        <button class="btn btn-default btn-shadow screen-share"><i class="fa fa-video-camera" aria-hidden="true"></i>&nbsp;<span>Share Screen</span></button>
                                        @if(Auth::user('user'))
                                        <button class="btn btn-default btn-shadow record-button"><i class="fa fa-circle"></i>&nbsp;<span>Start Recording</span></button>
                                        @endif
                                        <button href="#" class="btn btn-success btn-shadow interview-applicant"><i class="fa fa-phone"></i>&nbsp;<span>Join Conference</span></button>
                                        
                                        <div class="video-options-text pull-right">
                                            <text class="save-progress"></text>
                                            <text class="total-files"></text>
                                            @if(count($video_sessions) === 0) 
                                            <input class="is-recording" hidden="true" value="false" />
                                            <input class="session_id" hidden="true" value="" />
                                            @else
                                            <input class="is-recording" hidden="true" value="true" />
                                            <input class="session_id" hidden="true" value="{{$video_sessions->session_id}}" />
                                            @endif
                                        </div>
                                    </div>
                                    <audio controls class="download-complete-sound" src="{{url('assets/sounds/download_complete.wav')}}"></audio>
                                </div>
                            </div>
                            @if(count($video_questions) > 0)
                            <div class="row question-videos">
                                @foreach($video_questions as $v)
                                <div class="tests-container">
                                    <div class="box box-default">
                                        <div class="box-container">
                                            <div class="box-header" id="question-{{ $v->id }}" data-toggle="collapse" data-target="#question-collapse-{{ $v->id }}">
                                                <h3 class="box-title">
                                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>&nbsp;<?php
                                                    $v->question = preg_replace("/<\/*[a-z0-9\s\"'.=;:-]*>/i", "", $v->question);
                                                    echo $v->question;
                                                    ?>
                                                </h3>
                                                <div class="pull-right" style="margin-right: 10px;">
                                                    <strong>Time:</strong> {{ date('i:s', strtotime($v->length)) }}
                                                </div>
                                            </div>
                                            <div class="box-body">
                                                <div {{ Auth::check('applicant') ? '' : ('id=question-collapse-' . $v->id) }} class="box-content collapse">
                                                    {!! $v->note !!}
                                                    <div class="form-inline">
                                                        <label>Applicant Score</label>
                                                        <div class="input-group">
                                                            <input type="number" name="video-conference-points" id="{{ $v->result_id }}" value="{{ $v->result_points }}" step="1" max="{{ $v->max_point }}" class="form-control video-conference-points" style="width: 70px;">
                                                            <div class="input-group-addon">/{{ $v->max_point }}</div>
                                                        </div>
                                                        <button type="button" class="btn btn-shadow btn-submit btn-video hidden" data-status="1" data-test="{{ $v->test_id }}" data-unique="{{ $applicant->id }}" id="{{ $v->id }}">Record Answer</button>
                                                        <span class="time-limit-conference" data-length="{{ $v->length ? $v->length : '' }}">
                                                            <strong class="timer-area">{{ $v->length ? date('i:s', strtotime($v->length)) : '' }}</strong>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
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
                                        <div class="row">
                                            <div id="{{$video->nfo_id}}" class="col-xs-6">
                                                <!--video id="video-archive-item-{{$video->nfo_id}}" class="video-archive-item" controls>
                                                    Your browser does not support the video tag.
                                                    
                                                    <source src="{{$video->rec_dir.'/'.$video->session_id.'-'.$video->stream}}-final.webm" type="video/webm">
                                                </video-->
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <button class="btn btn-danger btn-shadow pull-right delete-video"><i class="fa fa-times"></i></button>
                                        <input class="video_id" type="hidden" value="{{$video->id}}"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <button href="#" class="btn btn-success btn-shadow play-record"><i class="fa fa-play-circle" aria-hidden="true"></i>&nbsp;<span>Play Record</span></button>
                                        <textarea class="video-status-container">
                                            {{$video->tags['tags']}}
                                        </textarea>
                                        <input class="video_id" type="hidden" value="{{$video->id}}"/>
                                        <input class="nfo_id" type="hidden" value="{{$video->nfo_id}}"/>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @foreach($quiz_videos as $video)
                            <div class="video-element-holder">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <video id="video-archive-remote-item-{{$video->id}}" class="video-archive-item" controls="controls"  preload="metadata" src="https://laravel.software/recordings/{{ $video->record_id }}.webm">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    <div class="col-xs-5">
                                        <video id="video-archive--local-item-{{$video->id}}" class="video-archive-item" controls="controls"  preload="metadata" src="https://laravel.software/recordings/{{ $video->local_record_id }}.webm">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    <div class="col-xs-2">
                                        <button class="btn btn-danger btn-shadow pull-right delete-quiz-video"><i class="fa fa-times"></i></button>
                                        <input class="video_id" type="hidden" value="{{$video->id}}"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>Applicant Score:</label>&nbsp;{{ $video->points }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tests-tab">
                        @include('applicants.partials._quizlist')
                    </div>
                    <div role="tabpanel" class="tab-pane" id="criteria-tab">
                        <textarea id="assessment-instruction" data-job-id="{{ $job->id }}">{{$job->criteria}}</textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="mini-space"></div>
<input class="applicant_score" type="hidden" value="{{$rating->score or ''}}"/>
<input class="page_applicant_id" type="hidden" value="{{$applicant->id}}"/>
<input class="job_id" type="hidden" value="{{$applicant->job_id}}"/>
<input class="page_type" type="hidden" value="applicant"/>
<input class="_token" type="hidden" value="{{csrf_token()}}"/>
@stop
