{!! Form::open(array('files' => true, 'url' => 'quiz/' . $tests_info->id, 'method' => 'PATCH')) !!}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title" style="width: 80%;" data-toggle="collapse" data-target="#test-area">{{ $tests_info->title }}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-success btn-sm add-question-btn">
                        <i class="fa fa-plus"></i> Add Question
                    </button>
                </div>
            </div>
            <div class="box-body collapse" id="test-area">
                <div class="row form-group">
                    <div class="col-md-6">
                        <input type="text" name="title" class="form-control" placeholder="Test Title" value="{{ $tests_info->title }}" />
                    </div>
                    <div class="col-md-6">
                        <textarea name="description" rows="5" class="form-control" placeholder="Description">{{ $tests_info->description }}</textarea>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <textarea name="start_message" class="form-control" placeholder="Test Introduction">{{ $tests_info->start_message }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <textarea name="completion_message" class="form-control" placeholder="Completion Message">{{ $tests_info->completion_message }}</textarea>
                    </div>
                </div>
                <div class="row form-group hidden">
                    <div class="col-md-12">
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
                <div class="row form-group">
                    <div class="col-md-12 text-right">
                        <input type="submit" name="submit" class="btn btn-primary" value="Save" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box-body question-area">
            <div class="box box-info box-question question-default hidden">
                <div class="box-header">
                    <h3 class="box-title question-header" style="width: 80%;height: 40px;">Question</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-success btn-sm add-question-btn">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm remove-question-btn">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body collapse in">
                    <div class="row">
                        <div class="col-md-5">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Time Limit:</label>
                                <input type='text' name="length[1]" class="q-form time-form form-control" disabled="disabled"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Points:</label>
                                <input type="number" name="points[1]" class="q-form form-control" value="1" disabled="disabled" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Question:</label>
                        <textarea name="question[1]" class="q-form form-control" disabled="disabled"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Question Photo:</label>
                        <input type="file" name="question_photo_1" class="form-control" disabled="disabled"/>
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
                                    <a href="#" class="alert_delete remove-choice-btn">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endfor
                        <div class="text-right" style="margin-top: 10px;">
                            <input type="button" value="Add Choice" class="add-choice-btn btn btn-success" />
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
                <div class="box box-info box-question" id="{{ $v->id }}">
                    <div class="box-header">
                        <h3 class="box-title question-header" style="width: 80%;height: 40px;">{{ $v->question ? $v->question : 'Question' }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-success btn-sm add-question-btn">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm remove-question-btn{{ $ref == 0 ? ' hidden' : '' }}">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body collapse in">
                        <div class="row">
                            <div class="col-md-5">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Time Limit:</label>
                                    <input type='text' name="length[e_{{ $v->id }}]" class="q-form time-form form-control" value="{{ $v->length ? date('i:s', strtotime($v->length)) : '' }}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Points:</label>
                                    <input type="number" name="points[e_{{ $v->id }}]" class="q-form form-control" value="{{ $v->points }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Question:</label>
                            <textarea name="question[e_{{ $v->id }}]" class="q-form form-control">{{ $v->question }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Question Photo:</label>
                            <div class="media">
                                <div class="media-left">
                                    {!! $v->question_photo ?
                                        HTML::image('/assets/img/question/' . $v->question_photo, '', array('style' => 'width: 64px;max-width: 64px!important;')) :
                                        ''
                                    !!}
                                </div>
                                <div class="media-body">
                                    <input type="file" name="question_photo_e_{{ $v->id }}" class="form-control" />
                                </div>
                            </div>
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
                                        <a href="#" class="alert_delete remove-choice-btn">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="text-right" style="margin-top: 10px;">
                                <input type="button" value="Add Choice" class="add-choice-btn btn btn-success" />
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
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <input type="submit" name="submit" class="btn btn-primary" value="Save" />
    </div>
</div>
{!! Form::close() !!}