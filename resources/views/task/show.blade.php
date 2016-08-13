{!! Form::open(['url' => ['taskTimer/' . $task->task_id],'class' => 'task-form'])  !!}
{!! Form::hidden('task_id',$task->task_id) !!}
{!! Form::hidden('user_id',$task->user_id) !!}
{!! Form::close()  !!}
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
                                        <div class="col-sm-9">
                                            <a data-toggle="collapse" href="#task-item-collapse-{{$list_item->id}}" class="checklist-header toggle-tasklistitem">{!! $list_item->checklist_header !!}</a>
                                            <input type="hidden" class="company_id" value="{{$company_id}}" />
                                            <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                                            <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                        </div>
                                        <div class="col-sm-3" style="white-space: nowrap">
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
                                    </div>
                                    <div class="row">
                                        <div id="task-item-collapse-{{$list_item->id}}" class="task-item-collapse collapse">
                                            <div class="checklist-item">{!! $list_item->checklist !!}</div>
                                            <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                                            <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                            <div class="link-column">
                                                @foreach($links as $link)
                                                @if($link->task_item_id == $list_item->id)
                                                <div class="col-md-12" id="link-{{$link->id}}">
                                                    <div class="col-md-3">
                                                        {{--*/ $parse_url = parse_url($link->url) /*--}}
                                                        @if(empty($parse_url['scheme']))
                                                        <a target="_blank" href="http://{{ $link->url }}"><strong>{{ $link->title }}</strong></a>
                                                        @else
                                                        <a target="_blank" href="{{ $link->url }}"><strong>{{ $link->title }}</strong></a>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6" style="text-align: justify">{{ $link->descriptions }}</div>
                                                    <div class="col-md-3 text-right">{{ $link->category_name }}&nbsp;&nbsp;&nbsp;
                                                        @if($user_id == $link->user_id)
                                                        <a href="{{ url('deleteLink/' . $link->id) }}" id="{{$link->id}}" class="remove-link pull-right"><i class="glyphicon glyphicon-remove"></i></a>
                                                        @endif
                                                    </div>
                                                    <hr/>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="pull-right" style="margin-right: 5px;">
                                                        <a target="_blank" href="{{url('taskitem/'.$list_item->id)}}" class="btn-edit btn-shadow btn"><i class="fa fa-external-link"></i> View</a>&nbsp;&nbsp;&nbsp;
                                                        @if($module_permissions->where('slug','edit.tasks')->count() === 1)
                                                        <a href="#" class="btn-edit btn-shadow btn edit-task-list-item" style="font-size: 18px!important;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>&nbsp;&nbsp;&nbsp;
                                                        @endif
                                                        @if($module_permissions->where('slug','delete.tasks')->count() === 1)
                                                        <a href="#" class="btn-delete btn-shadow btn alert_delete view-btn-delete" style="font-size: 18px!important;"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>
                                                        @endif
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
        <div class="link-column">
            @foreach($links as $link)
            @if($link->task_id == $task->task_id)
            <div class="col-md-12" id="link-{{$link->id}}">
                <div class="col-sm-4">
                    {{--*/ $parse_url = parse_url($link->url) /*--}}
                    @if(empty($parse_url['scheme']))
                    <a target="_blank" href="http://{{ $link->url }}"><strong>{{ $link->title }}</strong></a>
                    @else
                    <a target="_blank" href="{{ $link->url }}"><strong>{{ $link->title }}</strong></a>
                    @endif
                </div>
                <div class="col-sm-5" style="text-align: justify">{{ $link->descriptions }}</div>
                <div class="col-sm-3 text-right">{{ $link->category_name }}&nbsp;&nbsp;&nbsp;
                    @if($user_id == $link->user_id)
                    <a href="{{ url('deleteLink/' . $link->id) }}" id="{{$link->id}}" class="remove-link pull-right"><i class="glyphicon glyphicon-remove"></i></a>
                    @endif
                </div>
                <hr />
            </div>
            @endif
            @endforeach
        </div>
        <div class="row">
            <div class="col-sm-12">
                @if($module_permissions->where('slug','create.tasks')->count() === 1 || $project_owner === Auth::user('user')->user_id)
                <a href="#" class="btn btn-submit btn-shadow btn-sm check-list-btn" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> Document </a>&nbsp;&nbsp;
                <a href="#" class="btn btn-submit btn-shadow btn-sm add-spreadsheet" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> Spreadsheet </a>&nbsp;&nbsp;
                @endif
                <a href="#" class="btn-edit btn-shadow btn-sm btn add-link-modal" data-toggle="modal" data-target="#add_link_{{ $task->task_id }}" id="{{$task->task_id}}"><i class="fa fa-plus"></i> Link</a>&nbsp;&nbsp;
                @if($module_permissions->where('slug','edit.briefcases')->count() === 1 || $project_owner === Auth::user('user')->user_id)
                <a href="#" data-toggle="modal" data-target="#edit_task_{{ $task->task_id }}" class="btn btn-edit btn-sm btn-shadow"><i class="fa fa-pencil"></i> Edit</a>&nbsp;&nbsp;
                @endif
                @if($module_permissions->where('slug','delete.briefcases')->count() === 1 || $project_owner === Auth::user('user')->user_id)
                <a href="{{ url('task/delete/'.$task->task_id) }}" class="delete-tasklist btn btn-delete btn-sm btn-shadow" style="font-size: 16px!important;"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>&nbsp;&nbsp;
                @endif
            </div>
            <div class="col-sm-4">

            </div>
        </div>
    </div>
</div>
{{--region Briefcase Item Add Link--}}
<div class="modal fade add_link_modal" id="add_link_{{ $task->task_id }}" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Add Link</h4>
            </div>
            <div class="modal-body">
                {!!  Form::open(['route' => 'links.store','class' => 'form-horizontal link-form'])  !!}
                {!! Form::hidden('task_id',$list_item->task_id) !!}
                {!! Form::hidden('user_id',$user_id) !!}
                {!! Form::hidden('company_id',$company_id) !!}
                @include('links/partials/_add_form')
                {!! Form::close()  !!}
            </div>
        </div>
    </div>
</div>
{{--endregion--}}
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
                <h4 class="modal-title">Edit Briefcase</h4>
            </div>
        </div>
        <div class="modal-body">
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

        //region Check List
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

            add_new_task_textarea.on('fileUploadRequest', function (evt) {
                var fileLoader = evt.data.fileLoader,
                        xhr = fileLoader.xhr;

                //xhr.open('PUT', fileLoader.uploadUrl, true);

                //fileLoader.xhr.send(formData);

                // Prevented default behavior.
                //evt.stop();

                //saveChecklistContent(task_check_list_id,CKEDITOR.instances['add-new-task-textarea'].getData());
                var ajaxurl = fileLoader.uploadUrl;
                formData = new FormData();
                formData.append('upload', fileLoader.file, fileLoader.fileName);
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

                        console.log('file upload finished');
                    },
                    error: function (xhr, status, error) {

                    }
                }); //ajax

            }); // Listener with priority 4 will be executed before priority 5.

            add_new_task_textarea.on('fileUploadResponse', function (evt) {
                console.log('task_check_list_id', task_check_list_id);
                console.log('editor data', CKEDITOR.instances['add-new-task-textarea'].getData());
                //saveChecklistContent(task_check_list_id, evt.editor.getData());
            });

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

            var edit_task_list_editor = CKEDITOR.replace(textarea_id);
            edit_task_list_editor.on('change', function (evt) {

                var ajaxurl = public_path + 'autoSaveEditChecklist';

                var formData = new FormData();
                formData.append('task_check_list_id', task_list_item_id);
                formData.append('checklist', evt.editor.getData());

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

            //Toggle the content area to show
            $('#task-item-collapse-' + task_list_item_id).collapse('show');
            $(this).css({'display': 'none'});
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
        //endregion
        //region For Tasklist Delete
        $('.task-list').on('click', '.delete-tasklist', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var task_id = url.split('/').pop();

            //Remove the collapse panel immediately
            $('#collapse-container-' + task_id).remove();

            $.post(url);
        });
        //endregion

        //region Timer
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
        //endregion

        //region Add Spreadsheet
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

                $.post(public_path + 'saveSpreadsheet', data, function (d) {
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
        //endregion

        //region Auto Change and Select Category Name
        var _category_name = '';
        $('.category-name')
                .bind('keyup keypress blur', function () {
                    _category_name = $(this).val();
                    var myStr = $(this).val();
                    myStr = myStr.toLowerCase();
                    myStr = myStr.replace(/\s+/g, "-");
                    $(this).val(myStr);
                })
                .focusout(function () {
                    var cat_form = $('.category-form');
                    var form_data = [];
                    var url = public_path + 'linkCategory';
                    var cat_value = $(this).val();
                    if ($(this).val()) {
                        form_data.push(
                                {name: 'slug', value: $(this).val()},
                        {name: 'name', value: _category_name},
                        {name: 'request_from_link_page', value: '1'}
                        );
                        $.post(url, form_data, function (data) {
                            var _return_data = jQuery.parseJSON(data);
                            var option_ele = '<option value>Select Category</option>';
                            $.each(_return_data, function (key, val) {
                                var is_selected = cat_value == val.name ? 'selected' : '';
                                option_ele += '<option value="' + val.id + '" ' + is_selected + '>' + val.name + '</option>';
                            });
                            $('select.category').html(option_ele);
                        });
                    }

                    $(this).val('');
                });


        $('.load-task-assign')
                .on('click', '.remove-link', function (e) {
                    e.preventDefault();
                    $.post($(this).attr('href'));
                    $('#link-' + this.id + ',.link-' + this.id).remove();
                })
                .on('click', '.add-link-btn', function (e) {
                    e.preventDefault();
                    var _this = $(this);
                    var _link_modal = _this.parents('.add_link_modal');
                    var _form = _this.parents('.add_link_modal').find('form');
                    var _user_id = _form.find('input[name="user_id"]').val();
                    var _data = _form.serializeArray();
                    $.post(_form.attr('action'), _data, function (res) {
                        var _return_data = jQuery.parseJSON(res);
                        console.log(_return_data);
                        console.log(_user_id);
                        $.each(_return_data, function (key, val) {
                            var ele = '<div class="col-md-12" id="link-' + val.id + '">';
                            ele += '<div class="col-md-4">';
                            ele += '<a href="' + val.url + '" target="_blank"><strong>' + val.title + '</strong></a>';
                            ele += '</div>';
                            ele += '<div class="col-md-5" style="text-align: justify">' + val.descriptions + '</div>';
                            ele += '<div class="col-md-3 text-right">' + val.category_name + '&nbsp;&nbsp;&nbsp;';
                            ele += '<a href="' + public_path + 'deleteLink/' + val.id + '" id="' + val.id + '" class="remove-link pull-right"><i class="glyphicon glyphicon-remove"></i></a>';
                            ele += '</div>';
                            ele += '<hr/>';
                            ele += '</div>';
                            ele += '<hr/>';
                            var _link_column = $('#collapse-' + val.task_id).find('.link-column');
                            _link_column.append(ele);
                        });

                        _link_modal.modal('hide');
                    });
                });
        $('.check-list-container')
            .on('click', '.toggle-tasklistitem', function () {

                    var task_list_item_id = $(this).siblings('.task_list_item_id').val();
                    var company_id = $(this).siblings('.company_id').val();
                    var task_list_id = $(this).siblings('.task_list_id').val();

                    var task_checklist_url = public_path + 'getTaskChecklistItem/' + task_list_item_id + '/' + company_id + '/' + task_list_id;

                    $('#task-item-collapse-' + task_list_item_id).load(task_checklist_url, function (e) {
                        $('#task_item_' + task_list_item_id).find('a').removeClass('toggle-tasklistitem');
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
    });
</script>
