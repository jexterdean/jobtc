@if(Auth::check('applicant'))
@foreach($tests as $test)
@if($tests_completed->contains('test_id',$test->id))
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
                                @include('applicants.partials._quizreview')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
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
                                @foreach($question->question_choices as $key=>$value)
                                <div class="answer-area form-group">
                                    <input type="radio" class="simple radio" name="answer[{{ $question->id }}]" id="radio-{{ $key }}-{{ $question->id }}" value="{{ $key }}" />
                                    <label for="radio-{{ $key }}-{{ $question->id }}">{{ $value }}</label>
                                </div>
                                @endforeach
                                @elseif($question->question_type_id == 2)
                                <div class="form-group">
                                    <input type="text" name="answer[{{ $question->id }}]" class="form-control" placeholder="answer here..." />
                                </div>
                                @endif
                                <div class="text-center">
                                    <button type="button" data-type="{{ $question->question_type_id }}" class="btn btn-submit btn-next" id="{{ $question->id }}">Next</button>
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
                                <a class="btn btn-finish">Complete</a>
                                <input class="quiz_id" type="hidden" value="{{$test->id}}"/>
                                <input class="applicant_id" type="hidden" value="{{$applicant->id}}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif <!--Check if test completed condition-->
@endforeach
@endif <!--Check if user is applicant-->

@if(Auth::check('user'))
@foreach($tests as $test)
@if($tests_completed->contains('test_id',$test->id))
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
                                @include('applicants.partials._quizreview')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="tests-container">
    <div class="box box-default">
        <div class="box-container">
            <div class="box-header">
                <h3 class="box-title">{{ $test->title }}</h3>
            </div>
            <div class="box-body">
                <div class="box-content">
                    Applicant has not taken this test yet.
                </div>
            </div>
        </div>
    </div>
</div>
@endif <!--Check if test is already completed by this applicant-->
@endforeach
@endif

