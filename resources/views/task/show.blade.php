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
                                <li id="task_item_{{$val->id}}" class="list-group-item task-list-item">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="checklist-header">{!! $val->checklist_header !!}</label>
                                            <input type="hidden" class="task_list_item_id" value="{{$val->id}}" />
                                            <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                        </div>                                            
                                        <div class="col-md-1">                                            
                                            <a class="btn btn-shadow" data-toggle="collapse" href="#task-item-collapse-{{$val->id}}"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="col-md-1">
                                            <a class="btn edit-task-list-item"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <input type="hidden" class="task_list_item_id" value="{{$val->id}}" />
                                            <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                        </div>
                                        <div class="col-md-1">
                                            <!--input type="checkbox" class="checkbox checklist-checkbox" name="is_finished" value="1" id="{{ $val->id }}" {{ $val->is_finished ? 'checked' : '' }}-->
                                            <img class="drag-handle" src='{{ url('/assets/img/draggable-handle-2.png') }}'/>
                                        </div>
                                        <div class="col-md-1">
                                            @if ($val->status === 'Default')
                                            <div class="btn bg-gray checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            @elseif($val->status === 'Ongoing')
                                            <div class="btn bg-orange checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            @elseif($val->status === 'Completed')
                                            <div class="btn bg-green checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            @elseif($val->status === 'Urgent')
                                            <div class="btn bg-red checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="pull-right">
                                                <a href="#" class="alert_delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                <input type="hidden" class="task_list_item_id" value="{{$val->id}}" />
                                                <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="task-item-collapse-{{$val->id}}" class="task-item-collapse collapse">
                                            <div class="checklist-item">{!! $val->checklist !!}</div>
                                            <input type="hidden" class="task_list_item_id" value="{{$val->id}}" />
                                            <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
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
                        <a href="#" class="btn btn-submit btn-shadow btn-sm check-list-btn" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> Task </a><br/><br/>
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
                
            </div>
            <div class="col-sm-2">
                @if($current_time)
                <a class="btn btn-shadow btn-delete timer-btn stop_time" data-current="{{ $current_time->_time }}" id="{{ $current_time->id }}">Stop Time</a>
                @else
                <a class="btn btn-shadow btn-timer timer-btn start_time">Start Time</a>
                @endif
            </div>
            <div class="col-sm-5">
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
            dropOnEmpty: true,
            connectWith: ".list-group",
            handle: '.drag-handle',
            receive: function (event, ui) {
                //For receiving 
                var itemText = ui.item.attr('id');

                var list_group_id = $(this).attr('id').split('_').pop();

                var task_list_id = $(this).find('.task_list_id').val();

                //var task_list_item_id = $(this).find('.task_list_item_id').val();
                var task_list_item_id = ui.item.attr('id').split('_').pop();

                var data = $(this).sortable('serialize');

                //alert('list_group_id :' + list_group_id + ' task_list_id :' +task_list_id);

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
        
        //For Delete Hover
        _body.find('.list-group .alert_delete').hover(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var index = $(this).parent().parent().parent().parent().index();
            var checklist_item_id = $(this).parent().parent().parent().parent().parent().attr('id');

            $('#' + checklist_item_id + ' .list-group-item:eq(' + index + ')').css('border', '1px solid #ff0000');
            
        }, function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var index = $(this).parent().parent().parent().parent().index();
            var checklist_item_id = $(this).parent().parent().parent().parent().parent().attr('id');

            $('#' + checklist_item_id + ' .list-group-item:eq(' + index + ')').css('border', 'none');
        });
        
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
                        width: _percentage.toFixed(2) + '%'
                    }, 100);
            _body.find('.progress-val').html(_percentage.toFixed(2) + '%');
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

        var update_checklist_data = function (id, header, details, checklist_header ,checklist_item) {

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

        var update_checklist_header = function (id, header, checklist_header) {

            var data = [];
            data.push(
                    {'name': '_token', 'value': _body.find('input[name="_token"]').val()},
            {'name': 'task_id', 'value': _body.find('input[name="task_id"]').val()},
            {'name': 'user_id', 'value': _body.find('input[name="user_id"]').val()},
            {'name': 'checklist_header', 'value': header}
            );

            $.post(public_path + 'updateCheckList/' + id, data, function (_data) {
                var _return_data = jQuery.parseJSON(_data);
                $('.text-area-content').remove();

                var ele = _return_data.checklist_header;

                checklist_header.removeAttr('style').html(ele);
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
            var text_area_ele = '<li id="add-new-task" class="list-group-item text-area-content">';
            text_area_ele += '<input class="form-control" name="checklist_header" placeholder="New Task Header" value="" />';
            text_area_ele += '<textarea id="add-new-task-textarea" class=" form-control" name="checklist" placeholder="New Task" rows="3"></textarea><br/>';
            text_area_ele += '<button class="btn btn-submit btn-shadow btn-sm submit-checklist" type="button">Save</button>&nbsp;&nbsp;&nbsp;';
            text_area_ele += '<button class="btn btn-delete btn-shadow btn-sm cancel-checklist" type="button">Cancel</button>';
            text_area_ele += '</li>';

            var _this = $(this);
            var check_list_container = $('#list_group_' + this.id);
            _this.addClass('disabled');
            check_list_container.append(text_area_ele);
            _body.find('textarea[name="checklist_header"]').focus();

            CKEDITOR.replace('add-new-task-textarea');

            check_list_container.on('click', '.submit-checklist', function (e) {
                _this.removeClass('disabled');
                e.preventDefault();
                e.stopImmediatePropagation();
                //var data = _body.find('.task-form').serializeArray();
                var data = [];
                data.push(
                        {'name': '_token', 'value': _body.find('input[name="_token"]').val()},
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

                        ele += '<li id="task_item_' + val.id + '" class="list-group-item">';
                        ele += '<div class="row">';
                        ele += '<div class="col-md-5">';
                        ele += '<label class="checklist-header">' + val.checklist_header + '</label>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>'; //col-md-5
                        ele += '<div class="col-md-1">';
                        ele += '<a class="btn btn-shadow" data-toggle="collapse" href="#task-item-collapse-' + val.id + '"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>';
                        ele += '</div>';
                        ele += '<div class="col-md-1">';
                        ele += '<img class="drag-handle" src="' + public_path + 'assets/img/draggable-handle-2.png"/>';
                        ele += '</div>';
                        ele += '<div class="col-md-1">';
                        ele += '<a class="btn edit-task-list-item"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '">';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>'
                        ele += '<div class="col-md-1">';
                        ele += '<div class="btn ' + statusClass + ' checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                        ele += '</div>'; //col-md-1
                        ele += '<div class="col-md-3">';
                        ele += '<div class="pull-right">';
                        //ele += '<a href="/updateCheckList/' + val.id + '"><i class="glyphicon glyphicon-pencil glyphicon-lg"></i></a>&nbsp;';
                        ele += '<a href="#" class="alert_delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '">';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>'; //pull-right
                        ele += '</div>'; //col-md-3
                        ele += '</div>'; //row-1
                        ele += '<div class="row">';
                        ele += '<div id="task-item-collapse-' + val.id + '" class="task-item-collapse collapse">';
                        ele += '<div class="checklist-item">';
                        ele += val.checklist;
                        ele += '</div>';
                        ele += '<input type="hidden" class="task_list_item_id" value="' + val.id + '" />';
                        ele += '<input type="hidden" class="task_list_id" value="' + val.task_id + '" />';
                        ele += '</div>'; //task-item-collapse
                        ele += '</div>'; //row2
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

        _body.on('click', '.edit-task-list-item', function (e) {
            //Prevents default behavior
            e.preventDefault();

            //Get list item index
            var index = $(this).parent().parent().parent().index();

            //Get the list group id
            var list_group_id = $(this).parent().parent().parent().parent().attr('id');

            var task_list_id = $(this).siblings('.task_list_id').val();
            
            var task_list_item_id = $(this).siblings('.task_list_item_id').val();

            //Header Element
            var task_item_header = $(this).parent().parent().parent().find('.checklist-header');
            //Content Element
            var task_item_content = $(this).parent().parent().parent().find('.checklist-item');

            //Get Header Text 
            var header_text = $(this).parent().parent().parent().find('.checklist-header').text();
            
            //Get Text
            var content_text = $(this).parent().parent().parent().find('.checklist-item').html();
            
            //Header Editor
            var header_text_area_ele = '<div class="text-area-content">';
            header_text_area_ele += '<div class="form-group">';
            header_text_area_ele += '<input type="text" name="checklist_header" class="form-control edit-checklist-header" placeholder="Task Header" value="' + header_text + '"/>';
            header_text_area_ele += '</div>'; //form-group
            header_text_area_ele += '</div>';
            
            //Content Editor
            var content_text_area_ele = '<div class="text-area-content">';
            content_text_area_ele += '<div class="form-group">';
            content_text_area_ele += '<textarea id="editChecklistItem' + task_list_item_id + '" class="form-control edit-checklist-item" name="checklist" placeholder="Checklist" rows="3">' + content_text + '</textarea><br/>';
            content_text_area_ele += '</div>'; //form-group
            content_text_area_ele += '<button class="btn btn-submit btn-shadow btn-sm update-checklist" type="button">Save</button>&nbsp;&nbsp;&nbsp;';
            content_text_area_ele += '</div>';

            
            task_item_header.css({'display': 'none'}).before(header_text_area_ele);
            
            task_item_content.css({'display': 'none'}).before(content_text_area_ele);

            //var textarea_id = $('#' + list_group_id + ' .list-group-item').eq(index).find('textarea').attr('id');
            var textarea_id = $(this).parent().parent().parent().find('.edit-checklist-item').attr('id');
            
            CKEDITOR.replace(textarea_id);
            
            //Toggle the content area to show
            $('#task-item-collapse-'+task_list_item_id).collapse('show');
        });

        _body.on('click', '.update-checklist', function (e) {
            //Stops multiple calls to the this event
            e.stopImmediatePropagation();

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

            update_checklist_data(task_list_item_id, task_list_header, task_list_data, checklist_header ,checklist_item);
            
            //Hide the content area
            $('#task-item-collapse-'+task_list_item_id).collapse('hide');
        });


        _body.on('click', '.alert_delete', function (e) {
            e.preventDefault();

            var index = $(this).parent().parent().parent().parent().index();

            var task_list_item_id = $(this).siblings('.task_list_item_id').val();

            //Get the list group id
            var list_group_id = $(this).parent().parent().parent().parent().parent().attr('id');

            $('#' + list_group_id + ' .list-group-item').eq(index).remove();

            var url = public_path + 'deleteCheckList/' + task_list_item_id;

            $.post(url);

        });

        //For Tasklist Delete
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
        /*region Firepad*/
        /*var firepad_task = 'firepad-{{ $task->task_id }}';*/
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
        //init();
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
