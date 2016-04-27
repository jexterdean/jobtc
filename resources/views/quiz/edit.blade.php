<div class="box box-solid box-primary">
    <div class="box-header">
        <h3 class="box-title">Test Edit - <em>{{ $tests_info->title }}</em></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-success btn-sm add-question-btn">
                <i class="fa fa-plus"></i> Add Question
            </button>
        </div>
    </div>
    <div class="box-body">
        {!! Form::open(array('files' => true, 'url' => 'quiz/' . $tests_info->id, 'method' => 'PATCH')) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" name="title" class="form-control" placeholder="Test Title" value="{{ $tests_info->title }}" />
                </div>
                <div class="form-group">
                    <textarea name="description" class="form-control" placeholder="Description">{{ $tests_info->description }}</textarea>
                </div>
                <div class="form-group">
                    <textarea name="start_message" class="form-control" placeholder="Test Introduction">{{ $tests_info->start_message }}</textarea>
                </div>
                <div class="form-group">
                    <textarea name="completion_message" class="form-control" placeholder="Completion Message">{{ $tests_info->completion_message }}</textarea>
                </div>
                <div class="form-group">
                    <label>Test Photo</label>
                    <div class="media">
                        <div class="media-left">
                            {!! HTML::image('/assets/img/test/' . $tests_info->test_photo, '', array('style' => 'width: 64px;max-width: 64px!important;')) !!}
                        </div>
                        <div class="media-body">
                            <input type="file" name="test_photo" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 question-area">
                <div class="box box-solid box-info box-question question-default hidden">
                    <div class="box-header">
                        <h3 class="box-title">Question</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm add-question-btn">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm remove-question-btn">
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
                                        array(
                                            'class' => 'q-form question-type-dp form-control',
                                            'disabled' => 'disabled'
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time Limit:</label>
                                    <input type="number" name="length[1]" class="q-form form-control" disabled="disabled"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Question:</label>
                            <textarea name="question[1]" class="q-form form-control" disabled="disabled"></textarea>
                        </div>

                        <div class="question-type-area" data-type="1">
                            @for($i = 0;$i < 4;$i ++)
                            <div class="row question-answer-1">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="question_choices[1][]" class="q-form form-control" placeholder="Choices" disabled="disabled" />
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">
                                    <input type="radio" class="q-form simple" name="question_answer[1]" value="{{ $i }}" disabled="disabled" />
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

                @foreach($questions_info as $ref=>$v)
                    <div class="box box-solid box-info box-question">
                        <div class="box-header">
                            <h3 class="box-title">Question</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm add-question-btn">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm remove-question-btn{{ $ref == 0 ? ' hidden' : '' }}">
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
                                            'question_type_id[e_' . $v->id . ']',
                                            $question_type, $v->question_type_id,
                                            array('class' => 'q-form question-type-dp form-control')
                                        );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Time Limit:</label>
                                        <input type="number" name="length[e_{{ $v->id }}]" class="q-form form-control" value="{{ date('s', strtotime($v->length)) }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Question:</label>
                                <textarea name="question[e_{{ $v->id }}]" class="q-form form-control">{{ $v->question }}</textarea>
                            </div>

                            <div class="question-type-area" data-type="1" <?php echo $v->question_type_id == 1 ? '' : 'style="display: none;"'; ?>>
                                <?php
                                $choices = $v->question_type_id == 1 ? json_decode($v->question_choices) : array();
                                ?>
                                @foreach($choices as $k=>$c)
                                <div class="row question-answer-1">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="text" name="question_choices[e_{{ $v->id }}][]" class="q-form form-control" placeholder="Choices" value="{{ $c }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <input type="radio" class="q-form simple" name="question_answer[e_{{ $v->id }}]" value="{{ $k }}" {{ $k == $v->question_answer ? 'checked' : '' }}/>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary btn-sm remove-choice-btn">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="text-right" style="margin-top: 10px;">
                                    <input type="button" value="Add Choice" class="add-choice-btn btn btn-primary" />
                                </div>
                            </div>
                            <div class="question-type-area" data-type="2" <?php echo $v->question_type_id == 2 ? '' : 'style="display: none;"'; ?>>
                                <div class="form-group">
                                    <label>Answer:</label>
                                    <input type="text" name="question_answer[e_{{ $v->id }}]" class="q-form form-control" <?php
                                        echo $v->question_type_id == 1 ? 'disabled' : 'value="' . $v->question_answer . '"';
                                        ?> />
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group text-right">
            <input type="submit" name="submit" class="btn btn-primary" value="Save" />
            <input type="reset" name="reset" class="btn btn-danger" value="Clear" />
        </div>
        {!! Form::close() !!}
    </div>
</div>