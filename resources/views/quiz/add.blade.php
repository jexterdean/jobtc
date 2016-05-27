{!! Form::open(array('files' => true, 'url' => 'quiz', 'method' => 'POST')) !!}
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title" style="width: 80%;font-size: 26px;" data-toggle="collapse" data-target="#test-area">Test Add</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-submit btn-sm add-question-btn">
                            <i class="fa fa-plus"></i> Add Question
                        </button>
                    </div>
                </div>
                <div class="box-body collapse in" id="test-area">
                    <div class="box-content">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <input type="text" name="title" class="form-control" placeholder="Test Title" />
                            </div>
                            <div class="col-md-6">
                                <textarea name="description" rows="5" class="form-control" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <textarea name="start_message" class="form-control" placeholder="Test Introduction"></textarea>
                            </div>
                            <div class="col-md-6">
                                <textarea name="completion_message" class="form-control" placeholder="Completion Message"></textarea>
                            </div>
                        </div>
                        <div class="row form-group hidden">
                            <div class="col-md-12">
                                <label>Test Photo</label>
                                <input type="file" name="test_photo" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="question-area">
            <div class="box box-default box-question question-default">
                <div class="box-container">
                    <div class="box-header">
                        <h3 class="box-title question-header" style="width: 80%;height: 40px;font-size: 22px;margin-left:10px;">Question</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-submit btn-sm add-question-btn">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-delete btn-sm remove-question-btn hidden">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body collapse in">
                        <div class="box-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="col-md-3">Question Type:</label>
                                        <div class="col-md-9">
                                            <?php
                                            echo Form::select(
                                                'question_type_id[1]',
                                                $question_type, '',
                                                array('class' => 'q-form question-type-dp form-control')
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-6">Time Limit:</label>
                                        <div class="col-md-6">
                                            <input type='text' name="length[1]" class="q-form time-form form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="col-md-5">Points:</label>
                                        <div class="col-md-7">
                                            <input type="number" name="points[1]" class="q-form form-control" value="1" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Question:</label>
                                <textarea name="question[1]" class="q-form form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Question Photo:</label>
                                <div class="col-md-9">
                                    <input type="file" name="question_photo_1" class="form-control" /><br/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="question-type-area" data-type="1">
                                    @for($i = 0;$i < 4;$i ++)
                                    <div class="row question-answer-1">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="text" name="question_choices[1][]" class="q-form form-control" placeholder="Choices" />
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            {{--<input type="radio" class="q-form simple" name="question_answer[1]" value="{{ $i }}" />--}}
                                            <input type="radio" class="q-form simple radio" name="question_answer[1]" id="radio-{{ $i }}" value="{{ $i }}" />
                                            <label for="radio-{{ $i }}">&nbsp;</label>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <a href="#" class="alert_delete remove-choice-btn" style="font-size: 25px">
                                                <i class="fa fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endfor
                                    <div class="text-right" style="margin-top: 10px;">
                                        <input type="button" value="Add Choice" class="add-choice-btn btn btn-submit" />
                                    </div>
                                </div>
                                <div class="question-type-area" data-type="2" style="display: none;">
                                    <div class="form-group">
                                        <label>Answer:</label>
                                        <input type="text" name="question_answer[1]" class="q-form form-control" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><br/>
<div class="row">
    <div class="col-md-12 text-right">
        <input type="submit" name="submit" class="btn btn-submit" value="Save" />
    </div>
</div>
{!! Form::close() !!}