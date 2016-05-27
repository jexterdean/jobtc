<div class="col-md-12">
    <div class="modal fade test-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
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
                                        {{ $val->title }}
                                    </h4>
                                </div>
                                <div class="col-xs-2">
                                    <strong>Questions:</strong> {{ count($val->question) }}
                                </div>
                                <div class="col-xs-2">
                                    <strong>Time:</strong> {{ date('i:s', $val->total_time) }}
                                </div>
                                <div class="col-xs-3">
                                    <div class="btn-group pull-right">
                                        <a href="{{ url('quiz/' . $val->id) }}" class="tc-icons">
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
                                                <div class="col-md-9">
                                                    <a data-toggle="collapse" href="#question-collapse-{{ $q->id }}" class="checklist-header">
                                                        {{ substr($q->question, 0, 70) . (strlen($q->question) > 70 ? '...' : '') }}
                                                    </a>
                                                </div>
                                                <div class="col-md-1" style="white-space: nowrap;">
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
                                                    <a href="#" class="icon icon-btn alert_delete delete-question">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div id="question-collapse-{{ $q->id }}" class="question-collapse collapse">
                                                    <div class="checklist-item">
                                                        <span style="font-size: 24px;">{{ $q->question }}</span>
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
        </div>
        <div class="col-md-4">
            <div class="panel-group" id="accordion_" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-container">
                        <div class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-target="#task-details" data-parent="#accordion_" aria-expanded="true">
                            <h4 class="panel-title">
                                Test List
                                <a class="pull-right trigger-links" href="{{ url('quiz/create?p=test') }}" data-method="GET" data-title="Add Test">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="task-details" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <div class="panel-content">
                                    <table class="table table-hover table-striped">
                                        @if(count($test) > 0)
                                            @foreach($test as $v)
                                                <tr>
                                                    <td>{{ $v->title }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                             <tr>
                                                <td>No data was found.</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .test-modal textarea:not(.active){
        height: 39px!important;
        transition: height 0.25s ease-in;
    }
    .test-modal textarea.active{
        height: 200px!important;
        transition: height 0.25s ease-in;
    }
</style>
@section('js_footer')
@parent
<script>
    $(function(e){
        var testModal = $('.test-modal');

        $(document).on('click', '.trigger-links', function(e){
            e.preventDefault();

            var link = e.currentTarget.href;
            var title = $(this).data('title');
            var method = $(this).data('method');
            $.ajax({
                method: method ? method : 'get',
                url: link,
                success: function(data) {
                    testModal.find('.modal-title').html(title);
                    testModal.find('.modal-body').html(data);
                    testModal.modal('show');
                }
            });
        });

        //region Test Sort
        var t = $('.test-group');
        t.sortable({
            revert: "invalid",
            connectWith: ".test-group",
            handle: '.drag-test',
            stop: function (event, ui) {
                var sortId = [];
                t.find('.test-list').each(function(e){
                    sortId.push($(this).data('id'));
                });

                var url = public_path + '/testSort';
                $.post(url, { id: sortId });
            }
        });
        //endregion

        //region Test Delete
        var test_delete_btn = $('.test-delete-btn');
        test_delete_btn.click(function(e){
            var thisId = this.id;
            var thisTest = $(this).closest('.test-list');
            $.ajax({
                url: '{{ URL::to('quiz') }}/' + thisId + '?t=1',
                method: "DELETE",
                success: function(doc) {
                    thisTest.remove();
                }
            });
        });
        //endregion

        //region Quest Sort
        var q = $('.question-group');
        q.sortable({
            revert: "invalid",
            connectWith: ".question-group",
            handle: '.drag-question',
            stop: function (event, ui) {
                var sortId = [];
                $(this).find('.question-list').each(function(e){
                    sortId.push($(this).data('id'));
                });

                var url = public_path + '/questionSort';
                $.post(url, { id: sortId });
            }
        });
        //endregion

        //region Question Highlight
        $('.question-collapse')
            .on('shown.bs.collapse', function (e) {
                $(this).closest('.question-list').addClass('is-task-item-selected');
            })
            .on('hidden.bs.collapse', function (e) {
                $(this).closest('.question-list').removeClass('is-task-item-selected');
            });
        //endregion

        //region Question Type
        $(document).on('change', '.question-type-dp', function(e){
            var showThisQArea = $(this).val();
            var qArea = $(this).closest('.modal-body');
            qArea.find('.question-type-area')
                .css('display', 'none')
                .find('.q-form')
                .attr('disabled', 'disabled');
            qArea.find('.question-type-area[data-type="' + showThisQArea + '"]')
                .css('display', 'block')
                .find('.q-form')
                .removeAttr('disabled');
        });
        //endregion

        //region Choices Events
        $(document).on('click', '.add-choice-btn', function(e){
            var qAHtml = testModal.find('.modal-body .question-answer').html();
            qAHtml = qAHtml.replace('checked', '');
            var thisChoice = '<div class="row question-answer">' + qAHtml + '</div>';
            $(this).parent().before(thisChoice);
            var question_answer = $(this).parent().prev('.question-answer');
            question_answer.find('.question_choices').val('');
            question_answer.find('.radio').removeAttr('checked');
        });
        $(document).on('click', '.remove-choice-btn', function(e){
            $(this).closest('.row').remove();
        });
        //endregion

        //region Textarea folder
        $(document).on('click', '.test-modal .form-control', function() {
            var t = $('.test-modal textarea');
            t.removeClass('active');
            if($(this).is('textarea')){
                $(this).addClass('active');
            }
        });
        //endregion
    });
</script>
@stop