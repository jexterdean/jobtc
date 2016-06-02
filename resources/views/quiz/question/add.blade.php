{!! Form::open(array('files' => true, 'url' => 'quiz?p=question&id=' . $test_id, 'method' => 'POST')) !!}
<div class="row">
    <div class="col-md-6 form-group">
        <label class="col-sm-4 text-right">Question Type:</label>
        <div class="col-md-8">
            <?php
            echo Form::select(
                'question_type_id',
                $question_type, '',
                array('class' => 'q-form question-type-dp form-control')
            );
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-inline">
            <label>Time Limit:</label>
            <input type='text' name="length" style="width: 100px;" class="q-form time-form form-control" value="{{ $default_time ? date('i:s', strtotime($default_time)) : '' }}" />
        </div>
    </div>
    <div class="col-md-3 question-points-area" data-type="">
        <div class="form-inline">
            <label>Points:</label>
            <input type="number" name="points" style="width: 100px;" class="q-form points-form form-control" />
        </div>
    </div>
    <div class="col-md-3 question-points-area hidden" data-type="3">
        <div class="form-inline" style="padding: 5px;">
            <label>Maximum Score:</label>
            <input type="number" name="max_point" style="width: 70px;" class="q-form points-form form-control" />
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-2 text-right">Question:</label>
        <div class="col-md-10">
            <textarea name="question" class="q-form form-control summernote-editor"></textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-2 text-right">Explanation:</label>
        <div class="col-md-10">
            <textarea name="explanation" class="q-form form-control summernote-editor"></textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label class="col-sm-2 text-right">Question Photo:</label>
        <div class="col-md-10">
            <input type="file" name="question_photo" class="form-control" />
        </div>
    </div>
</div>
<div class="form-group question-answer-area" data-type="">
    <div class="row">
        <label class="col-sm-2 text-right">Question Answers:</label>
        <div class="col-md-10">
            <div class="question-type-area" data-type="1">
                @for($i = 0; $i < 4; $i ++)
                <div class="row question-answer">
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" name="question_choices[]" class="question_choices q-form form-control" placeholder="Choices" />
                        </div>
                    </div>
                    <div class="col-md-1 text-center">
                        <input type="radio" class="q-form radio" id="radio-{{ $i }}" name="question_answer" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} />
                        <label for="radio-{{ $i }}"></label>
                    </div>
                    <div class="col-md-1 text-center">
                        <div class="form-group">
                            <a href="#" class="alert_delete remove-choice-btn" style="font-size: 25px">
                                <i class="fa fa fa-times" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endfor
                <div class="text-right" style="margin-top: 10px;">
                    <input type="button" value="Add Choice" class="add-choice-btn btn btn-submit btn-shadow" />
                </div>
            </div>
            <div class="question-type-area hidden" data-type="2">
                <input type="text" name="question_answer" class="q-form form-control" disabled />
            </div>
        </div>
    </div>
</div>
<div class="form-group question-answer-area hidden" data-type="3">
    <div class="row">
        <label class="col-sm-2 text-right">Marking Criteria:</label>
        <div class="col-md-10">
            <textarea name="marking_criteria" class="q-form form-control summernote-editor" rows="3"></textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <input type="submit" name="submit" class="btn btn-submit btn-shadow" value="Save" />
        <input type="button" name="cancel" class="btn btn-delete btn-shadow" value="Cancel" data-dismiss="modal" />
    </div>
</div>
{!! Form::close() !!}

<script>
    $(function(e){
        $('.time-form').inputmask("59:59", {
            placeholder: '0',
            definitions: {
                '5': {
                    validator: "[0-5]",
                    cardinality: 1
                }
            }
        });

        //region summer note
        var options = $.extend(true,
            {
                lang: '' ,
                codemirror: {
                    theme: 'monokai',
                    mode: 'text/html',
                    htmlMode: true,
                    lineWrapping: true
                }
            } ,
            {
                "toolbar": [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "italic", "clear"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link", "picture", "video"]],
                    ["view", ["fullscreen", "codeview", "help"]]
                ]
            }
        );
        $("textarea.summernote-editor").summernote(options);
        //endregion
    });
</script>