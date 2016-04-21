<div class="modal fade" id="ajax" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Task</h4>
            </div>
            <div class="modal-body">
            </div>
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
{!! Form::open(['url' => ['taskTimer/' . $task->task_id],'class' => 'task-form'])  !!}
{!! Form::hidden('task_id',$task->task_id) !!}
{!! Form::hidden('user_id',$task->user_id) !!}
<div class="col-md-12">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">
                    &nbsp;
                    </h3>
                    <div class="text-right">
                        <div class="col-sm-8 col-sm-offset-3">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage . '%' }};">
                                {{ $percentage . '%' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label">Description:</label>
                            <p class="text-justify">{{ $task->task_description }}</p>

                        </div>
                        <div class="col-sm-8">
                            <a href="#" class="btn btn-success btn-shadow btn-sm check-list-btn" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> Checklist</a><br/><br/>
                            <div class="check-list-container">
                                <ul class="list-group" id="list_group_{{ $task->task_id }}">
                                    @if(count($checkList) > 0)
                                        @foreach($checkList as $val)
                                        <li class="list-group-item">
                                            <div class="checklist-item">
                                                <label class="checkbox-inline checklist-label">
                                                    <input type="checkbox" class="checkbox checklist-checkbox" name="is_finished" value="1" id="{{ $val->id }}" {{ $val->is_finished ? 'checked' : '' }}>{{ $val->checklist }}
                                                </label>
                                                <div class="pull-right">
                                                    <a href="{{ url('updateCheckList/' . $val->id ) }}" class="update-checklist"><i class="glyphicon glyphicon-lg glyphicon-pencil"></i></a>&nbsp;
                                                    <a href="{{ url('deleteCheckList/' . $val->id ) }}" class="alert_delete"><i class="glyphicon glyphicon-lg glyphicon-trash"></i></a>
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
                    <div class="row">
                        <div class="col-sm-1">
                            <div class="">
                                <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_link" data-placement="right" title="Add Links"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="col-sm-11" style="margin-left: -25px!important;">
                            @foreach($links as $val)
                                <a href="{{ $val->url }}" target="_blank"><strong>{{ $val->title }}</strong></a><br/>
                            @endforeach
                        </div>
                    </div><br/>
                    <div class="row">
                        <div class="col-sm-12">
                            <a class="btn btn-shadow btn-info">Assign</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="btn btn-shadow btn-priority">Priority</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="btn btn-shadow btn-success">Comment</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="btn btn-shadow btn-warning">Finish</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('task/' . $task->task_id .'/edit') }}" data-toggle='modal' data-target='#ajax1' class="btn btn-shadow btn-primary show_edit_form">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ route('task.destroy', $task->task_id) }}" class="alert_delete btn btn-shadow btn-danger">Delete</a>&nbsp;
                            @if($current_time)
                                <a class="pull-right btn btn-shadow btn-danger timer-btn stop_time" data-current="{{ $current_time->_time }}" id="{{ $current_time->id }}">Stop Time</a>
                                <div class="pull-right col-sm-offset-1" style="margin-right: 10px;">
                                     <h4 class="text-center text-bold bg-green" id="timer" style="font-size: 20px!important;padding: 0 5px;">
                                        00:00:00
                                     </h4>
                                </div>
                            @else
                                <a class="pull-right btn btn-shadow btn-stop timer-btn start_time">Start Time</a>
                                <div class="pull-right col-sm-offset-1" style="margin-right: 10px;">
                                     <h4 class="text-center text-bold  bg-green" id="timer" style="font-size: 20px!important;padding: 0 5px;">
                                        00:00:00
                                     </h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $_total = 0; ?>
    @foreach($task_timer as $val)
            <?php $_total += $val->time ?>
    @endforeach
    <div class="row">
        <div class="col-md-8">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Total Time : <strong class="total-time">{{ $_total }}</strong></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-sm btn-transparent" data-widget="collapse" data-target="#box-body-{{ $task->task_id }}"><i class="fa fa-chevron-down"></i></button>
                    </div>
                </div>
                <div class="box-body collapse" id="box-body-{{ $task->task_id }}">
                    <div class="row">
                        <div class="col-sm-12">
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
        <div class="col-md-4">

        </div>
    </div>
</div>
{!! Form::close() !!}
<script>
$(function(e){
    var _body = $('#collapse-' + '{{ $task->task_id }}');
    var alert_msg = function(msg,_class){
        var alert = '<div class="alert ' + _class + ' alert-dismissable">';
                alert += '<i class="fa fa-check"></i>';
                alert += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
                alert +=  '<strong>' + msg + '</strong>';
            alert += '</div>';
        $('section.content').prepend(alert);
    };
    var finish_checklist = function(){
        var count = 0;
        var over_all = 0;
        _body.find('.checklist-checkbox').each(function(){
            over_all++;
            if($(this).is(':checked')){
                count++;
            }
        });
    var _percentage = (count / over_all) * 100;
    _body.find(".progress-bar")
        .animate({
            width: _percentage.toFixed(2) + '%'
        }, 100)
        .html(_percentage.toFixed(2) + '%');
    };

    /*region Check List*/

    _body.on('click','.check-list-btn',function(){
        var text_area_ele = '<li class="list-group-item text-area-content">';
            text_area_ele += '<textarea class="form-control" name="checklist" placeholder="Checklist" rows="3"></textarea><br/>';
            text_area_ele += '<button class="btn btn-success btn-shadow btn-sm submit-checklist" type="button">Submit</button>&nbsp;&nbsp;&nbsp;';
            text_area_ele += '<button class="btn btn-danger btn-shadow btn-sm cancel-checklist" type="button">Cancel</button>';
            text_area_ele += '</li>';

        var _this = $(this);
        var check_list_container = $('#list_group_' + this.id);
        _this.attr('disabled','disabled');
        check_list_container.prepend(text_area_ele);
        $('textarea[name="checklist"]').focus();
        check_list_container.on('click','.submit-checklist',function(){
            var data = $('.task-form').serializeArray();
            $.post(public_path + 'checkList', data,function(d){
                var _return_data = jQuery.parseJSON(d);
                var ele = '';
                alert_msg('Successfully added checklist!!','alert-success');
                $.each(_return_data,function(index,val){
                    ele += '<li class="list-group-item">';
                        ele += '<label class="checklist-label">';
                        ele += '<div class="icheckbox_minimal" aria-checked="false" aria-disabled="false" style="position: relative;">';
                            ele += '<input type="checkbox" class="checkbox" style="position: absolute; opacity: 0;">';
                            ele += '<ins class="iCheck-helper" style="position: absolute; top: 0; left: 0; display: block; width: 100%; height: 100%; margin: 0; padding: 0; border: 0; opacity: 0; background: rgb(255, 255, 255);"></ins>';
                        ele += '</div>&nbsp;';
                        ele += val.checklist;
                        ele += '</label>';
                        ele += '<div class="pull-right">';
                            ele += '<a href="/updateCheckList/' + val.id + '"><i class="glyphicon glyphicon-pencil glyphicon-lg"></i></a>&nbsp;';
                            ele += '<a href="/deleteCheckList/' + val.id + '" class="alert_delete"><i class="glyphicon glyphicon-trash glyphicon-lg"></i></a>';
                        ele += '</div>';
                    ele += '</li>';
                });
                check_list_container.html(ele);
            });
        })
        .on('click','.cancel-checklist',function(){
            _this.removeAttr('disabled');
            _this.parent().find('.text-area-content').remove();
        });
    });
    _body.on('click','.update-checklist',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var checklist_label = $(this).parent().parent().find('.checklist-label');
        var checklist_item = $(this).parent().parent().parent().find('.checklist-item');
        var _text = checklist_label.text().replace(/(^\s+|[^a-zA-Z0-9 ]+|\s+$)/g,'');
        var text_area_ele = '<div class="text-area-content">';
            text_area_ele += '<textarea class="form-control" name="checklist" placeholder="Checklist" rows="3">' + _text + '</textarea><br/>';
            text_area_ele += '<button class="btn btn-success btn-shadow btn-sm submit-checklist" type="button">Submit</button>&nbsp;&nbsp;&nbsp;';
            text_area_ele += '<button class="btn btn-danger btn-shadow btn-sm cancel-checklist" type="button">Cancel</button>';
            text_area_ele += '</div>';

        checklist_item
            .css({'display':'none'})
            .before(text_area_ele);

        _body
            .on('click','.cancel-checklist',function(){
                $('.text-area-content').remove();
                checklist_item.removeAttr('style');
            });

        _body.on('click','.submit-checklist',function(){
                var data = _body.find('.task-form').serializeArray();
                $.post(url,data,function(_data){
                    var _return_data = jQuery.parseJSON(_data);
                    $('.text-area-content').remove();

                    var ele = '<label class="checklist-label">';
                          ele += '<div class="icheckbox_minimal" aria-checked="false" aria-disabled="false" style="position: relative;">';
                              ele += '<input type="checkbox" class="checkbox" style="position: absolute; opacity: 0;">';
                              ele += '<ins class="iCheck-helper" style="position: absolute; top: 0; left: 0; display: block; width: 100%; height: 100%; margin: 0; padding: 0; border: 0; opacity: 0; background: rgb(255, 255, 255);"></ins>';
                          ele += '</div>&nbsp;';
                          ele += _return_data.checklist;
                          ele += '</label>';
                          ele += '<div class="pull-right">';
                              ele += '<a href="/updateCheckList/' + _return_data.id + '"><i class="glyphicon glyphicon-pencil glyphicon-lg"></i></a>&nbsp;';
                              ele += '<a href="/deleteCheckList/' + _return_data.id + '" class="alert_delete"><i class="glyphicon glyphicon-trash glyphicon-lg"></i></a>';
                          ele += '</div>';

                    checklist_item
                              .removeAttr('style')
                              .html(ele);

                });
            });
    });

    _body.on('click','.checklist-label,.checklist-checkbox,.iCheck-helper',function(e){
        finish_checklist();
        var checkbox = $(this).parent().parent().find('.checklist-checkbox');
        var _id = checkbox.attr('id');
        var data = [];
        data.push(
            {'name':'_token','value':$('input[name="_token"]').val()},
            {'name':'task_id','value':$('input[name="task_id"]').val()},
            {'name':'user_id','value':$('input[name="user_id"]').val()}
        );
        if(checkbox.is(':checked')){
            data.push({'name':'is_finished','value':checkbox.val()});
        }
        $.post(public_path + 'updateCheckList/' + _id,data,function(e){});
    });
    /*endregion*/
    /*region Timer*/
        var element = _body.find('.bg-green');
        function startEditTimer(s){
            var timerStart = parseInt(0) + parseInt(s);
            var $minutes = parseInt(timerStart/60);
            var $hoursValue = parseInt($minutes/60);
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
        var timer = function(){
            var current = _body.find('.stop_time').attr('data-current');
            if(current){
                startEditTimer(current);
            }
        };

        timer();

        var _post_timer = function(url,_data,_this){
            var tbody = _body.find('.task-table-body');

            $.post(url,_data,function(return_data){
                var ele = '';
                var _return_data = jQuery.parseJSON(return_data);
                var total = 0;
                $.each(_return_data.table,function(index,value){
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
                if(_this){
                    var _return_latest_task_timer = _return_data.return_task_timer;
                    _this.attr('id',_return_latest_task_timer);
                }
                tbody.html(ele);
            });
        };

        _body
            .on('click','.timer-btn',function(e){
                var form = _body.find('.task-form');
                var data = form.serializeArray();
                var date = $.now();
                var now = $.format.date(date, "yyyy-MM-dd HH:mm:ss");
                var tbody = _body.find('.task-table-body');
                var _this = $(this);

                if($(this).hasClass('start_time')){
                    startEditTimer(0);
                    _this
                        .html('Stop Time')
                        .removeClass('btn-stop start_time')
                        .addClass('btn-danger stop_time');
                    element
                        .removeClass('bg-red-gradient')
                        .addClass('bg-green');
                    data.push({'name':'start_time','value':now});
                    _post_timer(form.attr('action'),data,_this);
                    alert_msg('Successfully added start time!!','alert-success');
                }
                else{
                    var url = public_path + 'updateTaskTimer/' + this.id;
                    $(this)
                        .html('Start Time')
                        .removeClass('btn-danger stop_time')
                        .addClass('btn-stop start_time');
                    element
                        .removeClass('bg-green')
                        .addClass('bg-red-gradient');
                    $.stopCountDownTimer();
                    data.push({'name':'end_time','value':now});
                    _post_timer(url,data);
                    alert_msg('Successfully stop timer!!','alert-success');
                }

            });
        /*endregion*/
})
</script>
