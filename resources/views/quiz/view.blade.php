<div class="box box-solid box-primary">
    <div class="box-header">
        <h3 class="box-title">Test Edit - <em>{{ $tests_info->title }}</em></h3>
    </div>
    <div class="box-body">
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
                    <h1>{{ $v->question }}</h1>
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
    $(function(e){
        var slider_div = $('.slider-div');
        var btn_next = $('.btn-next');
        var btn_prev = $('.btn-prev');

        btn_next.click(function(e){
            $(this)
                .closest('.slider-div')
                .removeClass('active')
                .next('.slider-div')
                .addClass('active');
        });
        btn_prev.click(function(e){
            $(this)
                .closest('.slider-div')
                .removeClass('active')
                .prev('.slider-div')
                .addClass('active');
        });
    });
</script>
@stop