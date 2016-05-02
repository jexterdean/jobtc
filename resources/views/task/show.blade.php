{!! Form::open(['url' => ['taskTimer/' . $task->task_id],'class' => 'task-form'])  !!}
{!! Form::hidden('task_id',$task->task_id) !!}
{!! Form::hidden('user_id',$task->user_id) !!}
<?php $_total = 0; ?>
@foreach($task_timer as $val)
<?php $_total += $val->time ?>
@endforeach
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-right">
                            <div class="progress-custom">
                                <span class="progress-val">{{ $percentage . '%' }}</span>
                                <span class="progress-bar-custom"><span class="progress-in" style="width: {{ $percentage . '%' }}"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="check-list-container">
                            <ul class="list-group" id="list_group_{{ $task->task_id }}">
                                @if(count($checkList) > 0)
                                @foreach($checkList as $val)
                                <li id="task_item_{{$val->id}}" class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-2">                                            
                                            <!--input type="checkbox" class="checkbox checklist-checkbox" name="is_finished" value="1" id="{{ $val->id }}" {{ $val->is_finished ? 'checked' : '' }}-->
                                            <div class="btn bg-gray">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="checkbox-inline checklist-label">
                                                <div class="checklist-item">
                                                    {!!nl2br(e($val->checklist))!!}
                                                </div>
                                            </label>
                                        </div>                                            
                                        <div class="col-md-3">
                                            <div class="pull-right">
                                                <a href="#" class="alert_delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                <input type="hidden" class="task_list_item_id" value="{{$val->id}}" />
                                                <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="#" class="btn btn-submit btn-shadow btn-sm check-list-btn" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> List Item </a><br/><br/>
                    </div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-edit btn-sm btn-shadow" data-toggle="modal" data-target="#add_link" data-placement="right" title="Add Links"><i class="fa fa-plus"></i> Link</a>&nbsp;
                    </div>
                    <div class="col-sm-2">
                        @foreach($links as $val)
                        <a href="{{ $val->url }}" target="_blank"><strong>{{ $val->title }}</strong></a><br/>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <a href="#" class="btn btn-edit btn-sm btn-shadow add-notes-btn" data-target="#firepad-column-{{ $task->task_id }}" data-toggle="collapse" aria-expanded="true"><i class="fa fa-plus"></i> Notes</a>
            </div>
            <div class="col-sm-7">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-3">
                            <h4 class="text-center text-bold bg-black timer-text" id="timer" style="font-size: 20px!important;padding: 0 5px;">
                                00:00:00
                            </h4>
                        </div>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-5">
                                    <h4 class="text-center text-bold bg-black" id="timer" style="font-size: 20px!important;">
                                        Time: <strong class="total-time">{{ $_total }}</strong>
                                    </h4>
                                </div>
                                <div class="col-sm-7" >
                                    <a href="#timer-table-{{ $task->task_id }}" class="btn btn-sm btn-black pull-right" aria-expanded="true" data-widget="collapse" data-toggle="collapse"><i class="fa fa-2x fa-chevron-down"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="collapse" id="timer-table-{{ $task->task_id }}">
                            <table class="table table-responsive">
                                <tbody class="task-table-body">
                                    @if(count($task_timer) > 0)
                                    <?php $total = 0; ?>
                                    @foreach($task_timer as $val)
                                    <?php $total += $val->time ?>
                                    <tr>
                                        <td>{{ $val->name }}</td>
                                        <td class="text-center">{{ $val->start_time != '0000-00-00 00:00:00' ? date('d/m/Y g:i:s A', strtotime($val->start_time)) : '&nbsp;'}}</td>
                                        <td class="text-center">{{ $val->end_time != '0000-00-00 00:00:00' ? date('d/m/Y g:i:s A', strtotime($val->end_time)) : '&nbsp;'}}</td>
                                        <td class="text-center">{{ $val->time ? $val->time : '0.00' }}</td>
                                        <td class="text-center" style="width: 5%;"><a href=' {{ url('deleteTaskTimer/' . $val->id) }}' class='alert_delete '> <i class='fa fa-trash-o fa-2x'></i> </a></td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="3"><strong>Total Time:</strong></td>
                                        <td class="text-center">{{ number_format($total,2) }}</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="5">No data was found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><br/>
        <div class="note-text">
        </div>
        <div class="firepad-column collapse" id="firepad-column-{{ $task->task_id }}">
            <div class="row">
                <div class="col-sm-12">
                    <div id="firepad-{{ $task->task_id }}" data-hash="task-list-{{ $task->task_id }}"></div>
                </div>
            </div><br/>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">

                    <div class="col-sm-8">
                        <a class="btn btn-shadow btn-assign">Assign</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-shadow btn-priority">Priority</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-shadow btn-submit">Comment</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-shadow btn-finish">Finish</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ url('task/' . $task->task_id .'/edit') }}" data-toggle='modal' data-target='#ajax1' class="btn btn-shadow btn-edit show_edit_form">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('task.destroy', $task->task_id) }}" class="alert_delete btn btn-shadow btn-delete">Delete</a>&nbsp;
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="pull-right">
                                    @if($current_time)
                                    <a class="btn btn-shadow btn-delete timer-btn stop_time" data-current="{{ $current_time->_time }}" id="{{ $current_time->id }}">Stop Time</a>
                                    @else
                                    <a class="btn btn-shadow btn-timer timer-btn start_time">Start Time</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
<div class="modal fade" id="ajax" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Task</h4>
            </div>
    </div>
            <div class="modal-body">
            </div>
        </div>
</div>
<div class="modal fade" id="add_link" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Link</h4>
            </div>
            <div class="modal-body">
                {!!  Form::open(['route' => 'links.store','class' => 'form-horizontal link-form'])  !!}
                {!! Form::hidden('task_id',$task->task_id) !!}
                @include('links/partials/_form')
                {!! Form::close()  !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(function (e) {
        
        //For Draggability
        $('.list-group').sortable({
            update: function(event,ui){
                var task_list_id = $(this).find('.task_list_id').val();
                var data = $(this).sortable('serialize');
                var url = public_path + '/sortCheckList/' + task_list_id; 
                $.post(url,data);
            }
        });
        
        //For Delete Hover
       $('.list-group .alert_delete').hover(function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
           var index = $(this).parent().parent().parent().parent().index();
           var checklist_item_id = $(this).parent().parent().parent().parent().parent().attr('id');
           
           $('#'+checklist_item_id+' li:eq('+index+')').css('border','1px solid #ff0000');
           
       },function(e){
           e.preventDefault();
            e.stopImmediatePropagation();
           var index = $(this).parent().parent().parent().parent().index();
           var checklist_item_id = $(this).parent().parent().parent().parent().parent().attr('id');
           
           $('#'+checklist_item_id+' li:eq('+index+')').css('border','1px solid #ddd');
       }); 
       
       
        
        var _body = $('#collapse-' + '{{ $task->task_id }}');
        var task_id = '{{ $task->task_id }}';
        var alert_msg = function (msg, _class) {
            var alert = '<div class="alert ' + _class + ' alert-dismissable">';
            alert += '<i class="fa fa-check"></i>';
            alert += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
            alert += '<strong>' + msg + '</strong>';
            alert += '</div>';
            $('section.content').prepend(alert);
        };
        var finish_checklist = function () {
            var count = 0;
            var over_all = 0;
            _body.find('.checklist-checkbox').each(function () {
                over_all++;
                if ($(this).is(':checked')) {
                    count++;
                }
            });
            var _percentage = (count / over_all) * 100;
            _body.find(".progress-in")
                    .animate({
                        width: _percentage.toFixed(2) + '%'
                    }, 100);
            _body.find('.progress-val').html(_percentage.toFixed(2) + '%');
        };

        /*region Check List*/
        _body.on('click', '.check-list-btn', function () {
            var text_area_ele = '<li id="add-new-task" class="list-group-item text-area-content">';
            text_area_ele += '<textarea class="form-control" name="checklist" placeholder="New Task" rows="3"></textarea><br/>';
            text_area_ele += '<button class="btn btn-submit btn-shadow btn-sm submit-checklist" type="button">Save</button>&nbsp;&nbsp;&nbsp;';
            text_area_ele += '<button class="btn btn-delete btn-shadow btn-sm cancel-checklist" type="button">Cancel</button>';
            text_area_ele += '</li>';

            var _this = $(this);
            var check_list_container = $('#list_group_' + this.id);
            _this.addClass('disabled');
            check_list_container.prepend(text_area_ele);
            _body.find('textarea[name="checklist"]').focus();

            check_list_container.on('click', '.submit-checklist', function (e) {
                _this.removeClass('disabled');
                e.preventDefault();
                e.stopImmediatePropagation();
                var data = _body.find('.task-form').serializeArray();
                $.post(public_path + 'checkList', data, function (d) {
                    var _return_data = jQuery.parseJSON(d);
                    var ele = '';
                    $.each(_return_data, function (index, val) {
                        var is_finished = val.is_finished ? 'checked' : '';
                        ele += '<li class="list-group-item">';
                        ele += '<div class="row">';
                        ele += '<div class="col-md-2">';
                        ele += '<input type="checkbox" class="checkbox checklist-checkbox" name="is_finished" value="1" id="' + val.id + '" ' + is_finished + '>';
                        ele += '</div>'; //col-md-2
                        ele += '<div class="col-md-7">';
                        ele += '<label class="checkbox-inline checklist-label">';
                        ele += '<div class="checklist-item">';
                        ele += val.checklist;
                        ele += '</div>';
                        ele += '</label>';
                        ele += '</div>'; //col-md-7
                        ele += '<div class="col-md-3">';
                        ele += '<div class="pull-right">';
                        //ele += '<a href="/updateCheckList/' + val.id + '"><i class="glyphicon glyphicon-pencil glyphicon-lg"></i></a>&nbsp;';
                        ele += '<a href="#" class="alert_delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                        ele += '<input type="hidden" class="task_list_item_id" value="'+val.id+'">';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>'; //pull-right
                        ele += '</div>'; //col-md-2
                        ele += '</div>'; //row
                        ele += '</li>';
                    });
                    check_list_container.html(ele);
                    _this.removeAttr('disabled');
                });
            }).on('click', '.cancel-checklist', function () {
                _this.removeClass('disabled');
                $('#add-new-task').remove();
                //$('.text-area-content').remove();
            });
        });
        //_body.on('click', '.update-checklist', function (e) {
        _body.on('click', '.checklist-item', function (e) {
            //Prevents default behavior
            e.preventDefault();
            
            //Get list item index
            var index = $(this).parent().parent().parent().parent().index();
            //Get the list group id
            var list_group_id = $(this).parent().parent().parent().parent().parent().attr('id');
            
            var task_list_id = $('#' +list_group_id+' .checklist-item').eq(index).parent().parent().siblings().children().find('.task_list_id').val();
            
            var checklist_label = $('#' +list_group_id+' .alert_delete').eq(index).parent().parent().parent().find('.checklist-label');
            var checklist_item = $('#' +list_group_id+' .alert_delete').eq(index).parent().parent().parent().parent().find('.checklist-item');
            var _text = checklist_label.text().replace(/(^\s+|[^a-zA-Z0-9 ]+|\s+$)/g, '');
            var text_area_ele = '<div class="text-area-content">';
            text_area_ele += '<div class="form-group">';
            text_area_ele += '<textarea class="form-control edit-checklist-item" name="checklist" placeholder="Checklist" rows="3">' + _text + '</textarea><br/>';
            text_area_ele += '</div>'; //form-group
            text_area_ele += '<button class="btn btn-submit btn-shadow btn-sm update-checklist" type="button">Save</button>&nbsp;&nbsp;&nbsp;';
            text_area_ele += '</div>';

            checklist_item
                    .css({'display': 'none'})
                    .before(text_area_ele);

            _body.on('click', '.cancel-checklist', function () {
                $('.text-area-content').remove();
                checklist_item.removeAttr('style');
            });


        });

        _body.on('click', '.update-checklist', function (e) {
            //Stops multiple calls to the this event
            e.stopImmediatePropagation();
            
            //Get list item index
            var index = $(this).parent().parent().parent().parent().parent().index();
            
            //Get the list group id
            var list_group_id = $(this).parent().parent().parent().parent().parent().parent().attr('id');
            
            //Get checklist item with the list group id
            var checklist_item = $('#'+list_group_id+' .alert_delete').eq(index).parent().parent().parent().parent().find('.checklist-item');

            //Get task item id
            var task_list_item_id = $('#'+list_group_id+' .checklist-item').eq(index).parent().parent().siblings().children().find('.task_list_item_id').val();

            var data = _body.find('.task-form').serializeArray();

            url = public_path + '/updateCheckList/' + task_list_item_id;

            $.post(url, data, function (_data) {
                var _return_data = jQuery.parseJSON(_data);
                $('.text-area-content').remove();

                var ele = _return_data.checklist;

                checklist_item
                        .removeAttr('style')
                        .html(ele);

            });
        });


        _body.on('click', '.alert_delete', function (e) {
            e.preventDefault();

            var index = $(this).parent().parent().parent().parent().index();

            var task_list_item_id = $(this).siblings('.task_list_item_id').val();
            
            //Get the list group id
            var list_group_id = $(this).parent().parent().parent().parent().parent().attr('id');
            
            $('#'+list_group_id+' li').eq(index).remove();

            var url = public_path + 'deleteCheckList/' + task_list_item_id;

            $.post(url);

        });

        _body.on('click', '.checklist-label,.checklist-checkbox,.iCheck-helper', function (e) {
            finish_checklist();
            var checkbox = $(this).parent().parent().find('.checklist-checkbox');
            var _id = checkbox.attr('id');
            var data = [];
            data.push(
                    {'name': '_token', 'value': _body.find('input[name="_token"]').val()},
            {'name': 'task_id', 'value': _body.find('input[name="task_id"]').val()},
            {'name': 'user_id', 'value': _body.find('input[name="user_id"]').val()}
            );
            if (checkbox.is(':checked')) {
                data.push({'name': 'is_finished', 'value': checkbox.val()});
            }
            $.post(public_path + 'updateCheckList/' + _id, data, function (e) {
            });
        });
        /*endregion*/
        /*region Timer*/
        var element = _body.find('.timer-text');
        function startEditTimer(s) {
            var timerStart = parseInt(0) + parseInt(s);
            var $minutes = parseInt(timerStart / 60);
            var $hoursValue = parseInt($minutes / 60);
            var $minutesValue = $minutes - ($hoursValue * 60);
            var $secondsValue = timerStart - (($hoursValue * 3600) + ($minutesValue * 60));

            $.countDownTimer(element, {
                includeTimer: {
                    hour: 1,
                    minutes: 1,
                    seconds: 1
                },
                isMilitaryTime: 0,
                isCountUp: 1,
                hours: $hoursValue,
                minutes: $minutesValue,
                seconds: $secondsValue
            });
        }
        var timer = function () {
            var current = _body.find('.stop_time').attr('data-current');
            if (current) {
                startEditTimer(current);
            }
        };

        timer();

        var _post_timer = function (url, _data, _this) {
            var tbody = _body.find('.task-table-body');

            $.post(url, _data, function (return_data) {
                var ele = '';
                var _return_data = jQuery.parseJSON(return_data);
                var total = 0;
                $.each(_return_data.table, function (index, value) {
                    ele += '<tr>';
                    ele += '<td>' + value.name + '</td>';
                    ele += '<td class="text-center">' + $.format.date(value.start_time, "dd/MM/yyyy hh:mm:ss a") + '</td>';
                    ele += '<td class="text-center">' + (value.end_time != '0000-00-00 00:00:00' ? $.format.date(value.end_time, "dd/MM/yyyy hh:mm:ss a") : '&nbsp;') + '</td>';
                    ele += '<td class="text-center">' + (value.time ? value.time : '0.00') + '</td>';
                    ele += '<td class="text-center" style="width: 5%;"><a href="/deleteTaskTimer/' + value.id + '" class="alert_delete"> <i class="fa fa-trash-o fa-2x"></i> </a></td>';
                    ele += '</tr>';
                    total += (value.time ? parseFloat(value.time) : 0);
                });
                total = total.toFixed(2);
                ele += '<tr>';
                ele += '<td class="text-right" colspan="3"><strong>Total Time:</strong></td>';
                ele += '<td class="text-center">' + total + '</td>';
                ele += '<td>&nbsp;</td>';
                ele += '</tr>';
                _body.find('.total-time').html(total);
                if (_this) {
                    var _return_latest_task_timer = _return_data.return_task_timer;
                    _this.attr('id', _return_latest_task_timer);
                }
                tbody.html(ele);
            });
        };

        _body
                .on('click', '.timer-btn', function (e) {
                    var form = _body.find('.task-form');
                    var data = form.serializeArray();
                    var date = $.now();
                    var now = $.format.date(date, "yyyy-MM-dd HH:mm:ss");
                    var tbody = _body.find('.task-table-body');
                    var _this = $(this);

                    if ($(this).hasClass('start_time')) {
                        startEditTimer(0);
                        _this
                                .html('Stop Time')
                                .removeClass('btn-timer start_time')
                                .addClass('btn-delete stop_time');
                        element
                                .removeClass('bg-red-gradient')
                                .addClass('bg-green');
                        data.push({'name': 'start_time', 'value': now});
                        _post_timer(form.attr('action'), data, _this);
                        //alert_msg('Successfully added start time!!', 'alert-success');
                    }
                    else {
                        var url = public_path + 'updateTaskTimer/' + this.id;
                        $(this)
                                .html('Start Time')
                                .removeClass('btn-delete stop_time')
                                .addClass('btn-timer start_time');
                        element
                                .removeClass('bg-green')
                                .addClass('bg-red-gradient');
                        $.stopCountDownTimer();
                        data.push({'name': 'end_time', 'value': now});
                        _post_timer(url, data);
                        //alert_msg('Successfully stop timer!!', 'alert-success');
                    }

                });
        /*endregion*/
        /*region Firepad*/
        var firepad_task = 'firepad-{{ $task->task_id }}';
        function init() {
            //// Initialize Firebase.
            var firepadRef = getExampleRef();
            // TODO: Replace above line with:
            // var firepadRef = new Firebase('<YOUR FIREBASE URL>');
            //// Create CodeMirror (with lineWrapping on).
            var codeMirror = CodeMirror(document.getElementById(firepad_task), {lineWrapping: true});
            //// Create Firepad (with rich text toolbar and shortcuts enabled).
            var firepad = Firepad.fromCodeMirror(firepadRef, codeMirror,
                    {richTextToolbar: true, richTextShortcuts: true});

            firepad.on('ready', function () {
                var _note_text_area = _body.find('.note-text');
                var _this = $(this);
                var ele = '<strong>Note: <span class="bg-red" style="font-weight: normal!important;padding: 0 5px;">Double click to edit.</span></strong>';
                ele += firepad.getHtml();
                if (firepad.isHistoryEmpty()) {
                    firepad.setText('');
                    _body.find('.add-notes-btn').css({display: 'inline'});
                }
                else {
                    _note_text_area.html(ele);
                    _body.find('.add-notes-btn').css({display: 'none'});
                }

                _this.focusout(function () {
                    _note_text_area
                            .html(ele)
                            .css({display: 'inline'});
                    _body.find('.firepad-column').css({display: 'none'})
                });
            });

        }
        // Helper to get hash from end of URL or generate a random one.
        function getExampleRef() {
            var ref = new Firebase('https://jobprojectmanager.firebaseio.com/');
            var firepad = $('#' + firepad_task);
            var hash = firepad.data('hash');

            if (hash) {
                ref = ref.child(hash);
            } else {
                ref = ref.push(); // generate unique location.
            }
            if (typeof console !== 'undefined')
                //console.log('Firebase data: ', ref.toString());

                return ref;
        }
        init();
        _body
                .on('click', '.add-notes-btn', function () {
                    $(this).css({display: 'none'});
                    _body.find('.firepad-column').css({display: 'inline'});
                })
                .on('dblclick', '.note-text', function () {
                    $(this).css({display: 'none'});
                    _body.find('.firepad-column').css({display: 'inline'});
                });

        /*endregion*/
    })
</script>
