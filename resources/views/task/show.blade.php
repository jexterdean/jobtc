@extends('layouts.default')
@section('content')
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
        <div class="col-sm-8">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">
                    {{ $task->task_title }}
                    </h3>
                    <div class="text-right">
                        <div class="col-sm-7 col-sm-offset-3">
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
                            <a href="#" class="btn btn-success btn-shadow btn-sm check-list-btn"><i class="glyphicon glyphicon-plus"></i> Checklist</a><br/><br/>
                            <div class="check-list-container">
                                <ul class="list-group">
                                    @if(count($checkList) > 0)
                                        @foreach($checkList as $val)
                                        <li class="list-group-item">
                                            <div class="checklist-item">
                                                <label class="checklist-label">
                                                    <input type="checkbox" class="checkbox checklist-checkbox" name="is_finished" value="1" id="{{ $val->id }}" {{ $val->is_finished ? 'checked' : '' }}> {{ $val->checklist }}
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
                            <a href="{{ route('task.destroy', $task->task_id) }}" class="alert_delete btn btn-shadow btn-danger">Delete</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url('project/' . $task->project_id) }}" class="btn btn-shadow btn-default"><i class='fa-1x fa fa-arrow-left'></i> Back</a>
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
        <div class="col-sm-4">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Comments</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" placeholder="Comment"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-shadow">Submit</button>
                            </div>
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
                        <button type="button" class="btn btn-sm btn-primary" data-widget="collapse" data-target="#box-body"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body collapse" id="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th>Account</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Time Rendered</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
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
                                            <td colspan="4">No data was found.</td>
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
@stop

