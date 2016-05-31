<div class="col-md-12">
    <div class="row">
        <div class="col-md-8">
            <div class="panel-group test-group" id="accordion" role="tablist" aria-multiselectable="true">
                @if(count($test) > 0)
                    @foreach($test as $val)
                    <div id="collapse-container-{{ $val->id }}" data-id="{{ $val->id }}" class="panel test-list task-list">
                        <div class="panel-heading task-header" data-target="#collapse-{{ $val->id }}" role="tab" id="headingOne" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
                            <div class="row">
                                <div class="col-xs-5">
                                    <h4 class="panel-title task-list-header">
                                        {{ substr($val->title, 0, 25) . (strlen($val->title) > 25 ? '...' : '') }}
                                    </h4>
                                </div>
                                <div class="col-xs-7">
                                    <div class="btn-group pull-right">
                                        <strong>Questions:</strong> {{ count($val->question) }}&nbsp;&nbsp;&nbsp;&nbsp;
                                        <strong>Time:</strong> {{ date('i:s', $val->total_time) }}&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="{{ url('quiz/' . $val->id . ($val->review_only ? '?p=review' : '')) }}" class="tc-icons">
                                            <i class="fa fa-eye"></i>
                                        </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="{{ url('quiz/' . $val->id .'/edit?p=test') }}" data-method="GET" data-title="Edit Test" class="trigger-links tc-icons">
                                            <i class="fa fa-pencil"></i>
                                        </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="#" class="drag-test move-test tc-icons">
                                            <i class="fa fa-arrows" aria-hidden="true"></i>
                                        </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="#" id="{{ $val->id }}" class="test-delete-btn tc-icons">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="collapse-{{ $val->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <div class="panel-content">
                                    <ul class="list-group question-group">
                                        @if(count($val->question) > 0)
                                        @foreach($val->question as $q)
                                        <li id="question-{{ $q->id }}" data-id="{{ $q->id }}" class="list-group-item task-list-item question-list">
                                            <div class="row task-list-details">
                                                <div class="col-md-8">
                                                    <a data-toggle="collapse" href="#question-collapse-{{ $q->id }}" class="checklist-header" style="font-size: 22px;">
                                                        {{ substr($q->question, 0, 50) . (strlen($q->question) > 50 ? '...' : '') }}
                                                    </a>
                                                </div>
                                                <div class="col-md-2" style="white-space: nowrap;font-size: 22px;">
                                                    <strong>Time:</strong> {{ date('i:s', strtotime($q->length)) }}
                                                </div>
                                                <div class="pull-right">
                                                    <a href="{{ url('quiz/' . $q->id .'/edit?p=question') }}" data-method="GET" data-title="Edit Question" class="icon icon-btn edit-task-list-item trigger-links">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a href="#" class="drag-question icon icon-btn move-tasklist">
                                                        <i class="fa fa-arrows"></i>
                                                    </a>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a href="#" id="{{ $q->id }}" class="icon icon-btn alert_delete delete-question-btn">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div id="question-collapse-{{ $q->id }}" class="question-collapse collapse">
                                                    <div class="checklist-item">
                                                        <span style="font-size: 22px;">{{ $q->question }}</span>
                                                        {!! $q->question_photo ?
                                                            '<div class="form-group">' .
                                                            HTML::image('/assets/img/question/' . $q->question_photo, '', array('style' => 'width: 100%;')) .
                                                            '</div>' :
                                                            ''
                                                        !!}
                                                        <div class="form-group">
                                                            <ul class="list-group">
                                                            @if($q->question_type_id == 1)
                                                                @foreach($q->question_choices as $k=>$c)
                                                                    <li class="list-group-item">
                                                                        <div class="row">
                                                                            <div class="col-md-11" style="font-size: 22px;">
                                                                                {{ $c }}
                                                                            </div>
                                                                            <div class="col-md-1 text-center">
                                                                                <input type="radio" class="q-form radio" id="radio-view-{{ $q->id }}" name="q[{{ $q->id }}]" {{ $k == $q->question_answer ? 'checked' : '' }} />
                                                                                <label for="radio-view-{{ $k }}">&nbsp;</label>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            @elseif($q->question_type_id == 2)
                                                            <li class="list-group-item" style="font-size: 22px;">
                                                                {{ $q->question_answer }}
                                                            </li>
                                                            @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                        @else
                                        <li class="list-group-item">
                                            No data was found.
                                        </li>
                                        @endif
                                    </ul>
                                    <a class="btn btn-submit btn-shadow btn-sm check-list-btn trigger-links" href="{{ url('quiz/create?p=question&id=' . $val->id) }}" data-method="GET" data-title="Add Question">
                                        <i class="glyphicon glyphicon-plus"></i> Question
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            <br/>
            <div class="row">
                <a class="btn btn-shadow btn-default trigger-links" href="{{ url('quiz/create?p=test') }}" data-method="GET" data-title="Add Test">
                    <i class="fa fa-plus"></i>
                    <strong>New Test</strong>
                </a>
            </div>

        </div>
        <div class="col-md-4">
            @include('quiz.result')
        </div>
    </div>
</div>