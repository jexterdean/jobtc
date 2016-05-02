<div class="box box-primary">
    <div class="box-container">
        <div class="box-header">
            <h3 class="box-title">{{ $tests_info->title }}</h3>
        </div>
        <div class="box-body">
            <div class="box-content">
                <div class="slider-container">
                    <div class="slider-div text-center active">
                        <div class="slider-body">
                            <h1>{{ $tests_info->start_message }}</h1>
                            <button class="btn btn-success btn-next">Start</button>
                        </div>
                    </div>
                    @foreach($questions_info as $ref=>$v)
                    <div class="slider-div">
                        <div class="slider-body">
                            <div class="form-group">
                                <h1>{{ $v->question }}</h1>
                            </div>
                            {!! $v->question_photo ?
                                '<div class="form-group">' .
                                HTML::image('/assets/img/question/' . $v->question_photo, '') .
                                '</div>' :
                                ''
                            !!}
                            @if($v->question_type_id == 1)
                                @foreach($v->question_choices as $k=>$c)
                                    <div class="answer-area form-group">
                                        <input type="radio" class="simple" name="answer[{{ $v->id }}]" value="{{ $k }}" />
                                        {{ $c }}
                                    </div>
                                @endforeach
                            @elseif($v->question_type_id == 2)
                                <div class="form-group">
                                    <input type="text" name="answer[{{ $v->id }}]" class="form-control" placeholder="answer here..." />
                                </div>
                            @endif
                            <div class="text-center">
                                <button class="btn btn-warning btn-prev">Previous</button>
                                <button class="btn btn-success btn-next">Next</button>
                                <button class="btn btn-info time-limit hidden" data-length="{{ $v->length ? $v->length : '' }}">
                                    <span class="timer-area">{{ $v->length ? $v->length : '' }}</span>
                                    <span class="glyphicon glyphicon-time"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="slider-div text-center">
                        <div class="slider-body">
                            <h1>{{ $tests_info->completion_message }}</h1>
                            <button class="btn btn-warning btn-prev">Back</button>
                            <button class="btn btn-success">Complete</button>
                        </div>
                    </div>
                </div>
            </div>
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
        width: 500px;
        vertical-align: middle;
        display: table-cell;
    }
    .answer-area{
        margin-left: 20px;
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
        btn_prev.click(function(e){
            clearInterval(interval);
            $(this)
                .closest('.slider-div')
                .removeClass('active')
                .prev('.slider-div')
                .addClass('active');

            var time_limit = $(this)
                .closest('.slider-div')
                .prev('.slider-div')
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
            var time = h + ":" + (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s);
            timer.html(time);

            time_limit --;
        }, time_limit);
    };
</script>
@stop