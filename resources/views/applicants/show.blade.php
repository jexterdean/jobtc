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
                    <li role="presentation">
                        <a href="#tests-tab" aria-controls="profile" role="tab" data-toggle="tab">Tests</a>
                    </li>
                    @if(Auth::check() && Auth::user()->level() === 1)
                    <li role="presentation">
                        <a href="#notes-tab" aria-controls="profile" role="tab" data-toggle="tab">Notes</a>
                    </li>
                    @endif
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
                                        <button class="btn btn-default btn-shadow mute-button"><i class="fa fa-microphone"></i>&nbsp;<span>Mute</span></button>
                                        <button class="btn btn-default btn-shadow show-video-button"><i class="fa fa-eye"></i>&nbsp;<span>Stop Video</span></button>
                                        @if(Auth::user('user'))
                                        <button class="btn btn-default btn-shadow record-button"><i class="fa fa-circle"></i>&nbsp;<span>Start Recording</span></button>
                                        @endif
                                        <button href="#" class="btn btn-success btn-shadow interview-applicant"><i class="fa fa-phone"></i>&nbsp;<span>Join Conference</span></button>
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
                                        <button class="btn btn-danger btn-shadow pull-right delete-video"><i class="fa fa-times"></i></button>
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
                    <div role="tabpanel" class="tab-pane" id="tests-tab">
                        @foreach($tests as $test)
                        <div class="tests-container">
                            <div class="box box-default">
                                <div class="box-container">
                                    <div class="box-header">
                                        <h3 class="box-title">{{ $test->title }}</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="box-content">
                                            <div class="slider-container">
                                                <div class="slider-div text-center active">
                                                    <div class="slider-body">
                                                        <h3 style="font-size: 23px;">{{ $test->start_message }}</h3>
                                                        <button class="btn btn-shadow btn-submit btn-next">Start</button>
                                                    </div>
                                                </div>
                                                @foreach($questions->where('test_id',$test->id) as $question)
                                                <div class="slider-div">
                                                    <div class="slider-body">
                                                        <div class="form-group">
                                                            <h3>{{ $question->question }}</h3>
                                                        </div>
                                                        {!! $question->question_photo ?
                                                        '<div class="form-group">' .
                                                            HTML::image('/assets/img/question/' . $question->question_photo, '') .
                                                            '</div>' :
                                                        ''
                                                        !!}
                                                        @if($question->question_type_id == 1)
                                                        @foreach($question->question_choices as $k=>$c)
                                                        <div class="answer-area form-group">
                                                            <input type="radio" class="simple radio" name="answer[{{ $question->id }}]" id="radio-{{ $k }}-{{ $question->id }}" value="{{ $k }}" />
                                                            <label for="radio-{{ $k }}-{{ $question->id }}">{{ $c }}</label>
                                                        </div>
                                                        @endforeach
                                                        @elseif($question->question_type_id == 2)
                                                        <div class="form-group">
                                                            <input type="text" name="answer[{{ $question->id }}]" class="form-control" placeholder="answer here..." />
                                                        </div>
                                                        @endif
                                                        <div class="text-center">
                                                            <button class="btn btn-shadow btn-delete btn-prev">Previous</button>
                                                            <button class="btn btn-shadow btn-submit btn-next">Next</button>
                                                            <button class="btn btn-shadow btn-timer time-limit hidden" data-length="{{ $question->length ? $question->length : '' }}">
                                                                <span class="timer-area">{{ $question->length ? date('i:s', strtotime($question->length)) : '' }}</span>
                                                                <span class="glyphicon glyphicon-time"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="slider-div text-center">
                                                    <div class="slider-body">
                                                        <h3>{{ $test->completion_message }}</h3>
                                                        <button class="btn btn-shadow btn-delete btn-prev">Back</button>
                                                        <button class="btn btn-shadow btn-finish">Complete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div role="tabpanel" class="tab-pane" id="notes-tab">
                        <textarea id="applicant-notes">{{$applicant->notes}}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            @if(Auth::user('user'))
            <div class="row single-applicant-pagination hidden-xs">
                <div class="col-xs-7">
                    <a href="{{url('job/'.$job->id)}}" id="job-title" class="btn btn-shadow btn-default bg-gray btn-lg pull-right"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;{{$job->title}}</a>
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
                                <text class="media-heading">
                                {{$applicant->name}}&nbsp;
                                @if(Auth::user('user')->user_id === $job->user_id)
                                @if($applicant->hired === 'No')
                                <a href="#" class='pull-right btn bg-light-blue-gradient hire'>Hire</a>
                                <input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
                                <input class="company_id" type="hidden" value="{{$job->company_id}}"/>
                                @else
                                <a href="#" class='pull-right btn bg-green hire'><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Hired</a>
                                <input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
                                <input class="company_id" type="hidden" value="{{$job->company_id}}"/>
                                @endif
                                @endif
                                </text>
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
            @if(Auth::check('user') || Auth::check('applicant'))
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
                                @if(isset($comment->user->photo))
                                <img class="comment-photo" src="{{url($comment->user->photo)}}" alt="Employee Photo">
                                @elseif(isset($comment->applicant->photo))
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
                    @if($user_info->user_id === $comment->user_id && Auth::check("user") 
                    || $comment->user_id === 0 && Auth::check("applicant"))
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
            @endif
        </div>
    </div>
</div>
<div class="mini-space"></div>
<input class="applicant_score" type="hidden" value="{{$rating->score or ''}}"/>
<input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
<input class="job_id" type="hidden" value="{{$applicant->job_id}}"/>
@stop
<!--This needs to be revised so that it can be put into the page javascript file (quizzes.js)-->
@section('js_footer')
@parent
<script>
    $(function (e) {
        //region Question
        var boxCounter = 1;
        var qBox = $('.question-default');
        var qBoxHtml = $('<div class="box box-solid box-info box-question" />')
                .append(qBox.clone().removeClass('question-default'))
                .html();
        var qAHtml = $('.question-answer-1').html();
        var existingBoxIds = [];
        $('.box-question').each(function (e) {
            boxCounter = boxCounter < this.id ? this.id : boxCounter;
        });
        $(document).on('click', '.add-question-btn', function (e) {
            $('.box-question .box-body').collapse('hide');

            boxCounter++;

            var thisQBox = $(this).closest('.box-question');
            var thisBox = qBoxHtml;
            thisBox = thisBox.replace(/(\[[0-9]+\])/g, "[" + boxCounter + "]");
            thisBox = thisBox.replace('question_photo_1', "question_photo_" + boxCounter);
            thisBox = thisBox.replace('hidden', "");
            thisBox = thisBox.replace(/(disabled="disabled")/g, "");

            if (thisQBox.length == 0) {
                $('.question-area').prepend(thisBox);
            }
            else {
                thisQBox.after(thisBox);
            }
            $('.time-form').inputmask("59:59", {
                placeholder: '0',
                definitions: {
                    '5': {
                        validator: "[0-5]",
                        cardinality: 1
                    }
                }
            });
        });
        $('.time-form').inputmask("59:59", {
            placeholder: '0',
            definitions: {
                '5': {
                    validator: "[0-5]",
                    cardinality: 1
                }
            }
        });
        $(document).on('click', '.remove-question-btn', function (e) {
            $(this).closest('.box-question').remove();
        });
        $(document).on('click', '.question-header', function (e) {
            $(this).parent().next('.box-body').collapse('toggle');
        });
        $(document).on('change', '.question-type-dp', function (e) {
            var showThisQArea = $(this).val();
            var qArea = $(this).closest('.box-body');
            qArea.find('.question-type-area')
                    .css('display', 'none')
                    .find('.q-form')
                    .attr('disabled', 'disabled');
            qArea.find('.question-type-area[data-type="' + showThisQArea + '"]')
                    .css('display', 'block')
                    .find('.q-form')
                    .removeAttr('disabled');
        });
        $(document).on('click', '.add-choice-btn', function (e) {
            var thisChoice = '<div class="row">' + qAHtml + '</div>';
            $(this).parent().before(thisChoice);
        });
        $(document).on('click', '.remove-choice-btn', function (e) {
            $(this).closest('.row').remove();
        });

        //sort question
        $('.question-area')
                .sortable({
                    handle: ".box-header"
                });

        var delete_btn = $('.test-delete-btn');
        delete_btn.click(function (e) {
            var thisId = this.id;
            var thisUrl = '{{ URL::to('quiz') }}/' + thisId;

            waitingDialog.show('Please wait...');
            $.ajax({
                url: thisUrl,
                method: "DELETE",
                success: function (doc) {
                    location.reload();
                }
            });
        });
        //endregion

        $('.read-more-btn').click(function () {
            var $this = $(this);
            $this.parent().find('.read-more').collapse('toggle');
            $this.toggleClass('read-more-btn');
            if ($this.hasClass('read-more-btn')) {
                $this.text('[Read More]');
            }
            else {
                $this.text('[Read Less]');
            }
        });
    });
</script>
@stop