{!! Form::open(['url' => ['taskTimer/' . $task->task_id],'class' => 'task-form'])  !!}
{!! Form::hidden('task_id',$task->task_id) !!}
{!! Form::hidden('user_id',$task->user_id) !!}
<?php $_total = 0; ?>
@foreach($task_timer as $timer)
<?php $_total += $timer->time ?>
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
                            <ul class="tasklist-group list-group" id="list_group_{{ $task->task_id }}">
                                @if(count($checkList) > 0)
                                @foreach($checkList as $list_item)
                                <li id="task_item_{{$list_item->id}}" class="list-group-item task-list-item">
                                    <div class="row task-list-details">
                                        <div class="col-md-7">
                                            <a data-toggle="collapse" href="#task-item-collapse-{{$list_item->id}}" class="checklist-header">{!! $list_item->checklist_header !!}</a>
                                            <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                                            <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                        </div>
                                        <div class="pull-right">
                                            @if ($list_item->status === 'Default')
                                            <div class="btn btn-default btn-shadow bg-gray checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            @elseif($list_item->status === 'Ongoing')
                                            <div class="btn btn-default btn-shadow bg-orange checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            @elseif($list_item->status === 'Completed')
                                            <div class="btn btn-default btn-shadow bg-green checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            @elseif($list_item->status === 'Urgent')
                                            <div class="btn bg-red btn-shadow checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            @endif
                                            &nbsp;&nbsp;&nbsp;
                                            {{--<a href="#" class="icon icon-btn edit-task-list-item"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                                            <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                                            <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />--}}

                                            <a href="#" class="drag-handle icon icon-btn move-tasklist"><i class="fa fa-arrows"></i></a>&nbsp;&nbsp;&nbsp;
                                            <!--a href="#" class="icon icon-btn alert_delete"><i class="fa fa-times" aria-hidden="true"></i></a-->
                                            <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                                            <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="task-item-collapse-{{$list_item->id}}" class="task-item-collapse collapse">
                                            <div class="checklist-item">{!! $list_item->checklist !!}</div>
                                            <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                                            <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="pull-right" style="margin-right: 5px;">
                                                        <a href="#" class="btn-delete btn-shadow btn alert_delete view-btn-delete" style="font-size: 18px!important;"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>&nbsp;&nbsp;&nbsp;
                                                        <a href="#" class="btn-edit btn-shadow btn edit-task-list-item" style="font-size: 18px!important;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                                                        <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                                                        <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                                    </div>
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
                            <input class="project_id" type="hidden" value="{{$task->project_id}}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <a href="#" class="btn btn-submit btn-shadow btn-sm check-list-btn" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> Document </a>&nbsp;&nbsp;
                <a href="#" class="btn btn-submit btn-shadow btn-sm add-spreadsheet" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> Spreadsheet </a>&nbsp;&nbsp;

                <a href="#" class="btn btn-edit btn-sm btn-shadow" data-toggle="modal" data-target="#add_link" data-placement="right" title="Add Links"><i class="fa fa-plus"></i> Link</a>&nbsp;&nbsp;
                @if(Auth::user('user')->user_id === $task->user_id)
                <a href="{{ url('task/delete/'.$task->task_id) }}" class="delete-tasklist btn btn-delete btn-sm btn-shadow" style="font-size: 16px!important;"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>&nbsp;&nbsp;
                @endif
                @if($current_time)
                <a class="btn btn-shadow btn-sm btn-delete timer-btn stop_time" data-current="{{ $current_time->_time }}" id="{{ $current_time->id }}">Stop Time</a>
                @else
                <a class="btn btn-shadow btn-sm btn-timer timer-btn start_time">Start Time</a>
                @endif
                <div class="col-sm-4">
                    @foreach($links as $link)
                    <a href="{{ $link->url }}" target="_blank"><strong>{{ $link->title }}</strong></a><br/>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <h4 class="text-center text-bold bg-black timer-text" id="timer" style="font-size: 20px!important;padding: 0 5px;">
                                00:00:00
                            </h4>
                        </div>
                        <div class="col-sm-7">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="text-left text-bold bg-black" id="timer" style="font-size: 20px!important;">
                                        Time: <strong class="total-time">{{ $_total }}</strong>
                                    </h4>
                                </div>
                                <div class="col-sm-4" >
                                    <a href="#timer-table-{{ $task->task_id }}" class="btn btn-sm btn-black pull-right" aria-expanded="true" data-widget="collapse" data-toggle="collapse"><i class="fa fa-chevron-down"></i></a>
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
        </div>
    </div>
</div>
{!! Form::close() !!}
<div class="modal fade" id="ajax" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Add Task</h4>
            </div>
        </div>
        <div class="modal-body">
        </div>
    </div>
</div>
<div class="modal fade" id="ajax1" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Edit Subproject</h4>
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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
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

        //region For Draggability
        $('.tasklist-group').sortable({
            dropOnEmpty: true,
            connectWith: ".tasklist-group",
            handle: '.drag-handle',
            placeholder: "ui-state-highlight",
            receive: function (event, ui) {
                //For receiving
                var itemText = ui.item.attr('id');

                var list_group_id = $(this).attr('id').split('_').pop();

                var task_list_id = $(this).find('.task_list_id').val();

                var task_list_item_id = ui.item.attr('id').split('_').pop();

                var data = $(this).sortable('serialize');
                //data.push({'name': '_token', 'value': _body.find('input[name="_token"]').val()})

                url = public_path + '/changeCheckList/' + list_group_id + '/' + task_list_item_id;

                //Remove warning that no data is found if dragged to an empty list
                $(this).find('li:contains("No data was found.")').remove();

                $.post(url, data);

            },
            update: function (event, ui) {

                //For Sorting within lists
                var list_group_id = $(this).attr('id').split('_').pop();

                var task_list_id = $(this).find('.task_list_id').val();

                var task_list_item_id = $(this).find('.task_list_item_id').val();

                var data = $(this).sortable('serialize');

                var url;

                url = public_path + '/sortCheckList/' + list_group_id;

                $.post(url, data);

            }
        });
        //endregion
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

        //region For Delete Hover
        _body.find('.list-group .alert_delete').hover(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var index = $(this).parent().parent().parent().index();
            var checklist_item_id = $(this).parent().parent().parent().parent().attr('id');
            //console.log(index);
            $('#' + checklist_item_id + ' .list-group-item:eq(' + index + ')').addClass('has-border');

        }, function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var index = $(this).parent().parent().parent().index();
            var checklist_item_id = $(this).parent().parent().parent().parent().attr('id');
            //console.log(checklist_item_id);
            $('#' + checklist_item_id + ' .list-group-item:eq(' + index + ')').removeClass('has-border');
        });

        //endregion

        var finish_checklist = function () {
            var count = 0;
            var over_all = 0;
            _body.find('.checklist-status').each(function () {
                over_all++;
                if ($(this).hasClass('bg-green')) {
                    count++;
                }
            });
            var _percentage = (count / over_all) * 100;
            _body.find(".progress-in")
                    .animate({
                        width: _percentage.toFixed(0) + '%'
                    }, 100);
            _body.find('.progress-val').html(_percentage.toFixed(0) + '%');
        };

        var update_checklist_status = function (id, status) {

            var data = [];
            data.push(
                    {'name': '_token', 'value': _body.find('input[name="_token"]').val()},
            {'name': 'task_id', 'value': _body.find('input[name="task_id"]').val()},
            {'name': 'user_id', 'value': _body.find('input[name="user_id"]').val()},
            {'name': 'status', 'value': status}
            );

            $.post(public_path + 'updateCheckList/' + id, data, function (e) {
            });

        };

        var update_checklist_data = function (id, header, details, checklist_header, checklist_item) {

            var data = [];
            data.push(
                    {'name': '_token', 'value': _body.find('input[name="_token"]').val()},
            {'name': 'task_id', 'value': _body.find('input[name="task_id"]').val()},
            {'name': 'user_id', 'value': _body.find('input[name="user_id"]').val()},
            {'name': 'checklist_header', 'value': header},
            {'name': 'checklist', 'value': details}
            );

            $.post(public_path + 'updateCheckList/' + id, data, function (_data) {
                var _return_data = jQuery.parseJSON(_data);
                $('.text-area-content').remove();

                var header = _return_data.checklist_header;
                var content = _return_data.checklist;


                checklist_header.removeAttr('style').html(header);
                checklist_item.removeAttr('style').html(content);

            });

        };

        /*region Check List*/
        //For Checklist Status
        _body.on('click', '.checklist-status', function (e) {
            //$('.checklist-status').click(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var index = $(this).parent().parent().parent().index();
            var id = $(this).parent().siblings().find('.task_list_item_id').val();

            /*From Default, Change to ongoing*/
            if ($(this).hasClass('bg-gray')) {
                $(this).switchClass('bg-gray', 'bg-orange', function () {
                    update_checklist_status(id, 'Ongoing');
                });
            }
            /*From Ongoing, Change to Completed, Update the progress bar, increase the value*/
            if ($(this).hasClass('bg-orange')) {
                $(this).switchClass('bg-orange', 'bg-green', function () {
                    finish_checklist();
                    update_checklist_status(id, 'Completed');
                });
            }
            /*From Completed, Change to Urgent, Update the progress bar, decrease the value*/
            if ($(this).hasClass('bg-green')) {
                $(this).switchClass('bg-green', 'bg-red', function () {
                    finish_checklist();
                    update_checklist_status(id, 'Urgent');
                });
            }
            /*From Urgent, Change to back to Default*/
            if ($(this).hasClass('bg-red')) {
                $(this).switchClass('bg-red', 'bg-gray', function () {
                    update_checklist_status(id, 'Default');
                });
            }
        });

        _body.on('click', '.check-list-btn', function () {
            var text_area_ele = '<li id="add-new-task" class="list-group-item text-area-content area-content">';
            text_area_ele += '<input class="form-control" name="checklist_header" placeholder="Title" value="" />';
            text_area_ele += '<textarea id="add-new-task-textarea" class=" form-control" name="checklist" placeholder="New Task" rows="3"></textarea><br/>';
            text_area_ele += '<button class="btn btn-submit btn-shadow btn-sm submit-checklist" type="button">Save</button>&nbsp;&nbsp;&nbsp;';
            text_area_ele += '<button class="btn btn-delete btn-shadow btn-sm cancel-checklist" type="button">Cancel</button>';
            text_area_ele += '</li>';

            var _this = $(this);
            var check_list_container = $('#list_group_' + this.id);
            _this.addClass('disabled');
            check_list_container.append(text_area_ele);

            //Immediately add an entry into the database
            var task_check_list_id;
            var new_task_url = public_path + 'addNewTask';
            var blank_task = new FormData();
            blank_task.append('_token', _body.find('input[name="_token"]').val());
            blank_task.append('task_id', _body.find('input[name="task_id"]').val());
            blank_task.append('user_id', _body.find('input[name="user_id"]').val());

            $.ajax({
                url: new_task_url,
                type: "POST",
                data: blank_task,
                // THIS MUST BE DONE FOR FILE UPLOADING
                contentType: false,
                processData: false,
                beforeSend: function () {

                },
                success: function (data) {
                    task_check_list_id = data;
                },
                error: function (xhr, status, error) {

                }
            }); //ajax


            var add_new_task_textarea = CKEDITOR.replace('add-new-task-textarea');

            CKEDITOR.on('fileUploadRequest', function (evt) {
                var fileLoader = evt.data.fileLoader,
                        formData = new FormData(),
                        xhr = fileLoader.xhr;

                xhr.open('PUT', fileLoader.uploadUrl, true);
                formData.append('upload', fileLoader.file, fileLoader.fileName);
                fileLoader.xhr.send(formData);

                // Prevented default behavior.
                evt.stop();
            }, null, null, 4); // Listener with priority 4 will be executed before priority 5.


            _body.find('input[name="checklist_header"]').on('change', function () {
                var ajaxurl = public_path + 'saveTaskCheckListHeader';

                var formData = new FormData();
                formData.append('task_check_list_id', task_check_list_id);
                formData.append('checklist_header', $(this).val());

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: formData,
                    // THIS MUST BE DONE FOR FILE UPLOADING
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                    },
                    success: function (data) {
                    },
                    error: function (xhr, status, error) {

                    }
                }); //ajax
            });

            add_new_task_textarea.on('change', function (evt) {
                var ajaxurl = public_path + 'saveTaskCheckList';

                var formData = new FormData();
                formData.append('task_check_list_id', task_check_list_id);
                formData.append('checklist_content', evt.editor.getData());

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: formData,
                    // THIS MUST BE DONE FOR FILE UPLOADING
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                    },
                    success: function (data) {
                    },
                    error: function (xhr, status, error) {

                    }
                }); //ajax

            });

            /*if (check_list_container.length > 10) {
             
             $('body').animate({
             scrollTop: _body.get(0).scrollHeight / 2
             }, 500);
             
             } else {
             $('body').animate({
             scrollTop: 0
             }, 500);
             }*/

            check_list_container.on('click', '.submit-checklist', function (e) {
                _this.removeClass('disabled');
                e.preventDefault();
                e.stopImmediatePropagation();
                //var data = _body.find('.task-form').serializeArray();
                var data = [];
                data.push(
                        {'name': '_token', 'value': _body.find('input[name="_token"]').val()},
                {'name': 'task_check_list_id', 'value': task_check_list_id},
                {'name': 'task_id', 'value': _body.find('input[name="task_id"]').val()},
                {'name': 'user_id', 'value': _body.find('input[name="user_id"]').val()},
                {'name': 'checklist_header', 'value': _body.find('input[name="checklist_header"]').val()},
                {'name': 'checklist', 'value': CKEDITOR.instances['add-new-task-textarea'].getData()}
                );

                $.post(public_path + 'checkList', data, function (d) {
                    var _return_data = jQuery.parseJSON(d);

                    var ele = '';
                    $.each(_return_data, function (index, val) {
                        var status = val.status;
                        var statusClass;

                        switch (status) {
                            case 'Default':
                                statusClass = 'bg-gray'
                                break;
                            case 'Ongoing':
                                statusClass = 'bg-orange'
                                break;
                            case 'Completed':
                                statusClass = 'bg-green'
                                break;
                            case 'Urgent':
                                statusClass = 'bg-red'
                                break;
                        }

                        ele += '<li id="task_item_' + val.id + '" class="list-group-item task-list-item">';
                        ele += '<div class="row task-list-details">';
                        ele += '<div class="col-md-7">';
                        ele += '<a data-toggle="collapse" href="#task-item-collapse-' + val.id + '" class="checklist-header">' + val.checklist_header + '</a>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>';
                        ele += '<div class="pull-right">';
                        ele += '<div class="btn btn-default btn-shadow ' + statusClass + ' checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>&nbsp;&nbsp;&nbsp;';
                        //ele += '<a href="#" class="icon icon-btn edit-task-list-item"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.id + '" />';
                        ele += '<a href="#" class="drag-handle icon icon-btn move-tasklist"><i class="fa fa-arrows"></i></a>&nbsp;&nbsp;&nbsp;';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '<div class="row">';
                        ele += '<div id="task-item-collapse-' + val.id + '" class="task-item-collapse collapse">';
                        ele += '<div class="checklist-item">' + val.checklist + '</div>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';

                        ele += '<hr/>';
                        ele += '<div class="row">';
                        ele += '<div class="col-md-12">';
                        ele += '<div class="pull-right" style="margin-right: 5px">';
                        ele += '<a href="#" class="btn-delete btn-shadow btn alert_delete" style="font-size: 18px!important;"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>&nbsp;&nbsp;&nbsp;';
                        ele += '<a href="#" class="btn-edit btn-shadow btn edit-task-list-item" style="font-size: 18px!important;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '</li>';

                    });

                    console.log(_body.find('input[class="project_id"]').val());
                    socket.emit('add-task-list-item', {'room_name': '/project/' + _body.find('input[class="project_id"]').val(), 'list_group_id': _body.find('input[name="task_id"]').val(), 'task_check_list_id': task_check_list_id});



                    //Remove Text area
                    $('#add-new-task').remove();
                    check_list_container.children('li:contains("No data was found.")').remove();
                    check_list_container.html(ele);
                    _this.removeAttr('disabled');
                });
            }).on('click', '.cancel-checklist', function () {
                _this.removeClass('disabled');
                $('#add-new-task').remove();

                var delete_new_task = public_path + 'cancelAddNewTask';
                var delete_task = new FormData();
                delete_task.append('task_check_list_id', task_check_list_id);

                $.ajax({
                    url: delete_new_task,
                    type: "POST",
                    data: delete_task,
                    // THIS MUST BE DONE FOR FILE UPLOADING
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                    },
                    success: function (data) {
                    },
                    error: function (xhr, status, error) {

                    }
                }); //ajax

                //$('.text-area-content').remove();
            });
        });

        _body.on('click', '.edit-task-list-item', function (e) {
            //Prevents default behavior
            e.preventDefault();

            //Get list item index
            var index = $(this).parent().parent().parent().parent().parent().parent().index();

            //Get the list group id
            var list_group_id = $(this).parent().parent().parent().parent().parent().parent().parent().attr('id');

            var task_list_id = $(this).siblings('.task_list_id').val();

            var task_list_item_id = $(this).siblings('.task_list_item_id').val();

            //Header Element
            var task_item_header = $(this).parent().parent().parent().parent().parent().parent().find('.checklist-header');
            //Content Element
            var task_item_content = $(this).parent().parent().parent().parent().parent().parent().find('.checklist-item');

            //Get Header Text
            var header_text = $(this).parent().parent().parent().parent().parent().parent().find('.checklist-header').text();

            //Get Text
            var content_text = $(this).parent().parent().parent().parent().parent().parent().find('.checklist-item').html();

            //Header Editor
            var header_text_area_ele = '<div class="text-area-content">';
            header_text_area_ele += '<div class="form-group">';
            header_text_area_ele += '<input type="text" name="checklist_header" class="form-control edit-checklist-header" placeholder="Task Header" value="' + header_text + '"/>';
            header_text_area_ele += '</div>'; //form-group
            header_text_area_ele += '</div>';

            //Content Editor
            var content_text_area_ele = '<div class="text-area-content area-content">';
            content_text_area_ele += '<div class="form-group">';
            content_text_area_ele += '<textarea id="editChecklistItem' + task_list_item_id + '" class="form-control edit-checklist-item" name="checklist" placeholder="Checklist" rows="3">' + content_text + '</textarea><br/>';
            content_text_area_ele += '</div>'; //form-group
            content_text_area_ele += '<button class="btn btn-submit btn-shadow btn-sm update-checklist" type="button">Save & Close</button>&nbsp;&nbsp;&nbsp;';
            content_text_area_ele += '<a href="#" class="btn-delete btn-shadow btn alert_delete pull-right" style="margin-right:0;font-size: 18px!important;"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>';
            content_text_area_ele += '<input type="hidden" class="task_list_item_id" value="' + task_list_item_id + '" />';
            content_text_area_ele += '<input type="hidden" class="task_list_id" value="' + task_list_id + '" />';
            content_text_area_ele += '</div>';


            task_item_header.css({'display': 'none'}).before(header_text_area_ele);

            task_item_content.css({'display': 'none'}).before(content_text_area_ele);

            //var textarea_id = $('#' + list_group_id + ' .list-group-item').eq(index).find('textarea').attr('id');
            var textarea_id = $(this).parent().parent().parent().parent().parent().parent().find('.edit-checklist-item').attr('id');

            CKEDITOR.replace(textarea_id);

            //Toggle the content area to show
            $('#task-item-collapse-' + task_list_item_id).collapse('show');
            $(this)
                    .css({'display': 'none'});
            $(this).siblings('.alert_delete').css({'display': 'none'});
        });

        _body.on('click', '.update-checklist', function (e) {
            //Stops multiple calls to the this event
            e.stopImmediatePropagation();
            //Get Task item header
            var task_item_id = $(this).parent().parent().find('.task_list_item_id').val(),
                    _task_item = $('#task_item_' + task_item_id);
            _task_item.removeClass('is-task-item-selected');
            //Get list item index
            var index = $(this).parent().parent().parent().parent().parent().index();

            //Get the list group id
            var list_group_id = $(this).parent().parent().parent().parent().parent().parent().attr('id');

            //Get checklist item with the list group id
            var checklist_item = $(this).parent().parent().find('.checklist-item');

            //Get checklist header with the list group id
            var checklist_header = $(this).parent().parent().parent().parent().find('.checklist-header');

            //Get task item id
            var task_list_item_id = $(this).parent().parent().parent().find('.task_list_item_id').val();

            //Get Data from CKEditor
            var textarea_id = $(this).parent().find('textarea').attr('id');

            var task_list_header = $(this).parent().parent().parent().parent().find('.edit-checklist-header').val();

            var task_list_data = CKEDITOR.instances[textarea_id].getData();

            //update_checklist_header(task_list_item_id, task_list_header, checklist_header);

            update_checklist_data(task_list_item_id, task_list_header, task_list_data, checklist_header, checklist_item);

            //Hide the content area
            $('#task-item-collapse-' + task_list_item_id).collapse('hide');

            $('.edit-task-list-item')
                    .removeAttr('style');
            $('.alert_delete').css({'display': 'inline'});
        });


        _body.on('click', '.alert_delete', function (e) {
            e.preventDefault();

            //var index = $(this).parent().parent().parent().index();

            var task_list_item_id = $(this).siblings('.task_list_item_id').val();
            //Get the list group id
            //var list_group_id = $(this).parent().parent().parent().parent().attr('id');

            var list_item = $('#task_item_' + task_list_item_id);

            list_item.remove();

            var url = public_path + 'deleteCheckList/' + task_list_item_id;

            $.post(url);

        });
        $('.task-list').on('show.bs.collapse', function () {
            var id = this.id.match(/\d+/);
            var task_list = $('#collapse-container-' + id);
            task_list.addClass('is-selected');
        });
        $('.task-list .panel-heading .col-xs-6')
                .click(function () {
                    var data_target = $(this).parent().parent().parent().find('.task-header').data('target');
                    var id = data_target.match(/\d+/);
                    var task_list = $('#collapse-container-' + id);
                    task_list.removeClass('is-selected');
                });
        $('.task-list-item .checklist-header, .task-list-details .edit-task-list-item').click(function (event) {
            var href = !$(this).hasClass('icon') ? $(this).attr('href') : $(this).parent().parent().find('.checklist-header').attr('href');
            var id = href.match(/\d+/);
            var task_list_item = $('#task_item_' + parseInt(id));
            task_list_item.addClass('is-task-item-selected');
        });
        $('.list-group-item.task-list-item').on('hidden.bs.collapse', function () {
            var id = this.id.match(/\d+/);
            var task_list = $('#task_item_' + id);
            task_list.removeClass('is-task-item-selected');
        });
        /*$('.task-list-item').bind('dblclick', function () {
         var edit_btn = $(this).find('.icon-btn.edit-task-list-item');
         edit_btn.bind().trigger('click');
         console.log('trigger');
         });*/
        //region For Tasklist Delete
        $('#accordion').on('click', '.delete-tasklist', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var task_id = url.split('/').pop();

            //Remove the collapse panel immediately
            $('#collapse-container-' + task_id).remove();

            $.post(url);
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

        //Add Spreadsheet
        _body.on('click', '.add-spreadsheet', function () {

            var spreadsheet_name = 'task-spreadsheet-' + makeid();

            //Create a new spreadsheet page in ethercalc
            var request = new XMLHttpRequest();
            request.open('POST', 'https://job.tc:9000/');
            request.setRequestHeader('Content-Type', 'application/json');
            request.onreadystatechange = function () {
                if (this.readyState === 4) {
                    console.log('Status:', this.status);
                    console.log('Headers:', this.getAllResponseHeaders());
                    console.log('Body:', this.responseText);
                }
            };
            var body = {
                'room': spreadsheet_name
            };
            request.send(JSON.stringify(body));

            var text_area_ele = '<li id="add-new-spreadsheet" class="list-group-item text-area-content area-content">';
            text_area_ele += '<input class="form-control" name="spreadsheet_header" placeholder="Title" value="" />';
            text_area_ele += '<iframe style="height: 800px;" id="spreadsheet_iframe" class="spreadsheet_iframe" src="https://job.tc:9000/' + spreadsheet_name + '"></iframe>';
            text_area_ele += '<button class="btn btn-submit btn-shadow btn-sm submit-checklist" type="button">Save</button>&nbsp;&nbsp;&nbsp;';
            text_area_ele += '<button class="btn btn-delete btn-shadow btn-sm cancel-checklist" type="button">Cancel</button>';
            text_area_ele += '</li>';

            var _this = $(this);
            var check_list_container = $('#list_group_' + this.id);
            _this.addClass('disabled');
            check_list_container.append(text_area_ele);

            //Immediately add an entry into the database
            var task_check_list_id;
            var new_task_url = public_path + 'addNewTask';
            var blank_task = new FormData();
            blank_task.append('_token', _body.find('input[name="_token"]').val());
            blank_task.append('task_id', _body.find('input[name="task_id"]').val());
            blank_task.append('user_id', _body.find('input[name="user_id"]').val());

            $.ajax({
                url: new_task_url,
                type: "POST",
                data: blank_task,
                // THIS MUST BE DONE FOR FILE UPLOADING
                contentType: false,
                processData: false,
                beforeSend: function () {

                },
                success: function (data) {
                    task_check_list_id = data;
                },
                error: function (xhr, status, error) {

                }
            }); //ajax


            check_list_container.on('click', '.submit-checklist', function (e) {
                _this.removeClass('disabled');
                e.preventDefault();
                e.stopImmediatePropagation();
                //var data = _body.find('.task-form').serializeArray();

                var spreadsheet_html = '<iframe style="height: 800px;" id="spreadsheet_iframe" class="spreadsheet_iframe" src="https://job.tc:9000/' + spreadsheet_name + '"></iframe>';

                var data = [];
                data.push(
                        {'name': '_token', 'value': _body.find('input[name="_token"]').val()},
                {'name': 'task_check_list_id', 'value': task_check_list_id},
                {'name': 'task_id', 'value': _body.find('input[name="task_id"]').val()},
                {'name': 'user_id', 'value': _body.find('input[name="user_id"]').val()},
                {'name': 'checklist_header', 'value': _body.find('input[name="spreadsheet_header"]').val()},
                {'name': 'checklist', 'value': spreadsheet_html}
                );

                $.post(public_path + 'checkList', data, function (d) {
                    var _return_data = jQuery.parseJSON(d);

                    var ele = '';
                    $.each(_return_data, function (index, val) {
                        var status = val.status;
                        var statusClass;

                        switch (status) {
                            case 'Default':
                                statusClass = 'bg-gray';
                                break;
                            case 'Ongoing':
                                statusClass = 'bg-orange';
                                break;
                            case 'Completed':
                                statusClass = 'bg-green';
                                break;
                            case 'Urgent':
                                statusClass = 'bg-red';
                                break;
                        }

                        ele += '<li id="task_item_' + val.id + '" class="list-group-item task-list-item">';
                        ele += '<div class="row task-list-details">';
                        ele += '<div class="col-md-7">';
                        ele += '<a data-toggle="collapse" href="#task-item-collapse-' + val.id + '" class="checklist-header">' + val.checklist_header + '</a>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>';
                        ele += '<div class="pull-right">';
                        ele += '<div class="btn btn-default btn-shadow ' + statusClass + ' checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>&nbsp;&nbsp;&nbsp;';
                        ele += '<a href="#" class="icon icon-btn edit-task-list-item"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.id + '" />';
                        ele += '<a href="#" class="drag-handle icon icon-btn move-tasklist"><i class="fa fa-arrows"></i></a>&nbsp;&nbsp;&nbsp;';
                        ele += '<a href="#" class="icon icon-btn alert_delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '<div class="row">';
                        ele += '<div id="task-item-collapse-' + val.id + '" class="task-item-collapse collapse">';
                        ele += '<div class="checklist-item">' + val.checklist + '</div>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '<hr/>';
                        ele += '<div class="row">';
                        ele += '<div class="col-md-12">';
                        ele += '<div class="pull-right" style="margin-right: 5px">';
                        ele += '<a href="#" class="btn-delete btn-shadow btn alert_delete" style="font-size: 18px!important;"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>&nbsp;&nbsp;&nbsp;';
                        ele += '<a href="#" class="btn-edit btn-shadow btn edit-task-list-item" style="font-size: 18px!important;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '</div>';
                        ele += '</li>';

                    });

                    $('#add-new-task').remove();
                    check_list_container.children('li:contains("No data was found.")').remove();
                    check_list_container.html(ele);
                    _this.removeAttr('disabled');
                });
            }).on('click', '.cancel-checklist', function () {
                _this.removeClass('disabled');
                $('#add-new-spreadsheet').remove();
                //$('.text-area-content').remove();
            });
        });


        function makeid()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for (var i = 0; i < 5; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }

    })
</script>
