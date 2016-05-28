<div class="col-md-12">
    <div class="row">
        <div class="col-md-8">
            <div class="panel-group test-group">
                <div class="box box-default">
                    <div class="box-container">
                        <div class="box-header">
                            <h3 class="box-title">{{ $tests_info->title }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="box-content">
                                <div class="slider-container">
                                    <div class="slider-div text-center active">
                                        <div class="slider-body">
                                            <span style="font-size: 23px;">{{ $tests_info->start_message }}</span>
                                            <br />
                                            <button type="button" class="btn btn-submit btn-next">Start</button>
                                        </div>
                                    </div>
                                    @foreach($questions_info as $ref=>$v)
                                    <div class="slider-div">
                                        <div class="slider-body">
                                            <div class="form-group">
                                                <span style="font-size: 23px;">{{ $v->question }}</span>
                                            </div>
                                            {!! $v->question_photo ?
                                                '<div class="form-group">' .
                                                HTML::image('/assets/img/question/' . $v->question_photo, '', array('style' => 'width: 100%;')) .
                                                '</div>' :
                                                ''
                                            !!}
                                            @if($v->question_type_id == 1)
                                                @foreach($v->question_choices as $k=>$c)
                                                    <div class="answer-area form-group">
                                                        <input type="radio" class="simple radio" name="answer[{{ $v->id }}]" id="radio-{{ $k }}-{{ $v->id }}" value="{{ $k }}" />
                                                        <label for="radio-{{ $k }}-{{ $v->id }}">{{ $c }}</label>
                                                    </div>
                                                @endforeach
                                            @elseif($v->question_type_id == 2)
                                                <div class="form-group">
                                                    <input type="text" name="answer[{{ $v->id }}]" class="form-control" placeholder="answer here..." />
                                                </div>
                                            @endif
                                            <div class="text-center">
                                                <button type="button" class="btn btn-submit btn-next" id="{{ $v->id }}">Next</button>
                                                <button type="button" class="btn btn-timer time-limit hidden" data-length="{{ $v->length ? $v->length : '' }}">
                                                    <span class="timer-area">{{ $v->length ? date('i:s', strtotime($v->length)) : '' }}</span>
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="slider-div text-center">
                                        <div class="slider-body">
                                            <span style="font-size: 23px;">{{ $tests_info->completion_message }}</span>
                                            <br />
                                            <a class="btn btn-finish" href="{{ url('quiz/' . $v->test_id . '?p=review') }}">Complete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @include('quiz.result')
        </div>
    </div>
</div>

<style>
    .slider-container{
        display: table;
        margin: 0 auto;
    }
    .slider-div{
        height: 300px;
        display: none;
    }
    .slider-div.active{
        display: table-row;
    }
    .slider-body{
        padding: 15px;
        /*width: 500px;*/
        vertical-align: middle;
        display: table-cell;
    }
    .answer-area{
        margin-left: 20px;
        font-size: 19px;
    }
</style>

@section('js_footer')
@parent
<script>
    var interval;
    $(function(e){
        var slider_div = $('.slider-div');
        var btn_next = $('.btn-next');
        var btn_prev = $('.btn-prev');

        btn_next.click(function(e){
            var thisId = this.id;
            var slider_div = $(this).closest('.slider-div');
            if(thisId){
                var thisElement = $('input[name="answer[' + thisId + ']"]');
                var answer = thisElement.attr('type') == "radio" ?
                    $('input[name="answer[' + thisId + ']"]:checked').val() :
                    thisElement.val();
                var data = {
                   question_id: thisId,
                   answer: answer == undefined ? '' : answer
                };
                $.ajax({
                    url: '{{ URL::to('quiz') . '?id=' . $tests_info->id }}&p=exam',
                    data: data,
                    method: "POST",
                    success: function(doc) {
                        slider_div.remove();
                    }
                });
            }

            clearInterval(interval);
            $(this)
                .closest('.slider-div')
                .removeClass('active')
                .next('.slider-div')
                .addClass('active');

            var time_limit = $(this)
                .closest('.slider-div')
                .next('.slider-div')
                .find('.time-limit');
            if(time_limit.length != 0){
                if(time_limit.data('length') != "00:00:00"){
                    time_limit.removeClass('hidden');
                    time_limit.timerStart();
                }
            }
        });
    });

    $.fn.timerStart = function(){
        var timer_btn = $(this);
        if(timer_btn.find('.timer-area').length == 0){
            timer_btn.prepend('<span class="timer-area" />');
        }
        var timer = timer_btn.find('.timer-area');
        var l = timer_btn.data('length');
        var a = l.split(':'); // split it at the colons

        var h = a[0];
        var m = parseInt(a[1]);
        var s = parseInt(a[2]);
        // minutes are worth 60 seconds. Hours are worth 60 minutes.
        var time_limit = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
        interval = setInterval(function(e){
            if(time_limit == 0){
                clearInterval(interval);
                timer_btn.parent().find('.btn-next').trigger('click');
            }

            m = Math.floor(time_limit/60); //Get remaining minutes
            s = time_limit - (m * 60);
            var time = (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s);
            timer.html(time);

            time_limit --;
        }, 1000);
    };
</script>
@stop