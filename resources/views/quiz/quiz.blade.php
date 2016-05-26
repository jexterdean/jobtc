<div class="row test-area">
    <div class="col-md-8">
        @include('quiz.' . $page)
    </div>
    <div class="col-md-4">
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title" style="width: 80%;" data-toggle="collapse" data-target="#test-library">Test Library</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ url('quiz') }}" class="btn btn-submit">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="box-body collapse in" id="test-library">
                    <div class="box-content">
                        <?php
                        foreach($test as $v){
                            $len = strlen($v->description);
                            $max = 90;
                            ?>
                            <div class="media">
                                <div class="media-left hidden">
                                    {!! HTML::image('/assets/img/test/' . $v->test_photo, '', array('style' => 'width: 64px;max-width: 64px!important;')) !!}
                                </div>
                                <div class="media-body">
                                    <h3 class="media-heading">{{ $v->title }}</h3>
                                    <em class="description-area">
                                    {{ substr($v->description, 0, $max) }}
                                    @if($len > $max)
                                        <span class="read-more collapse">
                                        {{ substr($v->description, $max, $len) }}
                                        </span>
                                        <a href="#" class="read-more-btn">[Read More]</a>
                                    @endif
                                    </em>
                                    <br />
                                    Question: {{ $v->num_question }}<br />

                                    <div class="row">
                                        <div class="col-sm-2">
                                            <a href="{{ url('quiz/' . $v->id) }}" class="btn btn-default">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="{{ url('quiz/' . $v->id . '/edit') }}" class="btn btn-edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-delete test-delete-btn" id="{{ $v->id }}">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .test-area, .form-control, .btn{
        font-size: 17px!important;
    }
    .description-area{
        text-align: justify;
    }
    .read-more.collapse.in{
        display: inline;
    }
</style>

<!--This needs to be revised so it can be placed in the page javascript file(quizzes.js)-->
@section('js_footer')
@parent
<script>
    $(function(e){
        //region Question
        var boxCounter = 1;
        var qBox = $('.question-default');
        var qBoxHtml =  $('<div class="box box-solid box-info box-question" />')
            .append(qBox.clone().removeClass('question-default'))
            .html();
        var qAHtml = $('.question-answer-1').html();
        var existingBoxIds = [];
        $('.box-question').each(function(e) {
                boxCounter = boxCounter < this.id ? this.id : boxCounter;
        });
        $(document).on('click', '.add-question-btn', function(e){
            $('.box-question .box-body').collapse('hide');

            boxCounter ++;

            var thisQBox = $(this).closest('.box-question');
            var thisBox = qBoxHtml;
            thisBox = thisBox.replace(/(\[[0-9]+\])/g, "[" + boxCounter + "]");
            thisBox = thisBox.replace('question_photo_1', "question_photo_" + boxCounter);
            thisBox = thisBox.replace('hidden', "");
            thisBox = thisBox.replace(/(disabled="disabled")/g, "");

            if(thisQBox.length == 0){
                $('.question-area').prepend(thisBox);
            }
            else{
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
        $(document).on('click', '.remove-question-btn', function(e){
            $(this).closest('.box-question').remove();
        });
        $(document).on('click', '.question-header', function(e){
            $(this).parent().next('.box-body').collapse('toggle');
        });
        $(document).on('change', '.question-type-dp', function(e){
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
        $(document).on('click', '.add-choice-btn', function(e){
            var thisChoice = '<div class="row">' + qAHtml + '</div>';
            $(this).parent().before(thisChoice);
        });
        $(document).on('click', '.remove-choice-btn', function(e){
            $(this).closest('.row').remove();
        });

        //sort question
        $('.question-area')
            .sortable({
                handle: ".box-header"
            });

        var delete_btn = $('.test-delete-btn');
        delete_btn.click(function(e){
            var thisId = this.id;
            var thisUrl = '{{ URL::to('quiz') }}/' + thisId;

            waitingDialog.show('Please wait...');
            $.ajax({
                url: thisUrl,
                method: "DELETE",
                success: function(doc) {
                    location.reload();
                }
            });
        });
        //endregion

        $('.read-more-btn').click(function(){
            var $this = $(this);
            $this.parent().find('.read-more').collapse('toggle');
            $this.toggleClass('read-more-btn');
            if($this.hasClass('read-more-btn')){
                $this.text('[Read More]');
            }
            else {
                $this.text('[Read Less]');
            }
        });
    });
</script>
@stop