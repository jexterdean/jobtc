<div class="box box-solid box-primary">
    <div class="box-header">
        <h3 class="box-title">Test Add</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-success btn-sm add-question-btn">
                <i class="fa fa-plus"></i> Add Question
            </button>
        </div>
    </div>
    <div class="box-body">
        {!! Form::open(array('files' => true, 'url' => 'quiz', 'method' => 'POST')) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" name="title" class="form-control" placeholder="Test Title" />
                </div>
                <div class="form-group">
                    <textarea name="description" class="form-control" placeholder="Description"></textarea>
                </div>
                <div class="form-group">
                    <textarea name="start_message" class="form-control" placeholder="Test Introduction"></textarea>
                </div>
                <div class="form-group">
                    <textarea name="completion_message" class="form-control" placeholder="Completion Message"></textarea>
                </div>
                <div class="form-group">
                    <label>Test Photo</label>
                    <input type="file" name="test_photo" class="form-control" />
                </div>
            </div>
            <div class="col-md-6 question-area">
                <div class="box box-solid box-info box-question question-default">
                    <div class="box-header">
                        <h3 class="box-title">Question</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm add-question-btn">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm remove-question-btn hidden">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm collapse-btn"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body collapse in">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Question Type:</label>
                                    <?php
                                    echo Form::select(
                                        'question_type_id[1]',
                                        $question_type, '',
                                        array('class' => 'q-form question-type-dp form-control')
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time Limit:</label> <span style="font-size: 10px;color: #f00;">(Zero for no time limit)</span>
                                    <input type="number" name="length[1]" class="q-form form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Question:</label>
                            <textarea name="question[1]" class="q-form form-control"></textarea>
                        </div>

                        <div class="question-type-area" data-type="1">
                            @for($i = 0;$i < 4;$i ++)
                            <div class="row question-answer-1">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="question_choices[1][]" class="q-form form-control" placeholder="Choices" />
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">
                                    <input type="radio" class="q-form simple" name="question_answer[1]" value="{{ $i }}" />
                                </div>
                                <div class="col-md-1 text-center">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-sm remove-choice-btn">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endfor
                            <div class="text-right" style="margin-top: 10px;">
                                <input type="button" value="Add Choice" class="add-choice-btn btn btn-primary" />
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
        <div class="form-group text-right">
            <input type="submit" name="submit" class="btn btn-primary" value="Save" />
            <input type="reset" name="reset" class="btn btn-danger" value="Clear" />
        </div>
        {!! Form::close() !!}
    </div>
</div>