@extends('layouts.default')
@section('content')
<div id="collapse-{{ $task->task_id }}">
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
                                        {{--region Briefcase Item Add Link--}}
                                        <div class="modal fade add_link_modal" id="add_link_{{ $task->task_id . '-' . $list_item->id }}" tabindex="-1" role="basic" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                        <h4 class="modal-title">Add Link</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        {!! Form::open(['route' => 'links.store','class' => 'form-horizontal link-form'])  !!}
                                                        {!! Form::hidden('task_id',$task->task_id) !!}
                                                        {!! Form::hidden('task_item_id',$list_item->id) !!}
                                                        {!! Form::hidden('user_id',$user_id) !!}
                                                        {!! Form::hidden('company_id',$company_id) !!}
                                                        @include('links/partials/_form')
                                                        {!! Form::close()  !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--endregion--}}
                                        <div class="row task-list-details">
                                            <div class="col-md-7">
                                                <a data-toggle="collapse" href="#task-item-collapse-{{$list_item->id}}" class="checklist-header toggle-tasklistitem"><i class="glyphicon glyphicon-list"></i> {!! $list_item->checklist_header !!}</a>
                                                <input type="hidden" class="company_id" value="{{$company_id}}" />
                                                <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                                                <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                            </div>
                                            <div class="col-md-3">
                                                @forelse($list_item->timer as $timer)
                                                <div id='timer-options-{{$list_item->id}}' class="pull-right"> 
                                                    @if($timer->timer_status === 'Resumed' || $timer->timer_status === 'Started')
                                                    <text id='timer-{{$list_item->id}}' class='still-counting task-item-timer'>{{$timer->total_time}}</text>
                                                    <button id="timer-pause-{{$list_item->id}}" class="btn btn-primary pause-timer">Pause</button>
                                                    @elseif($timer->timer_status === 'Completed')
                                                    <text id='timer-{{$list_item->id}}' class="task-item-timer">{{$timer->total_time}}</text>
                                                    @else
                                                    <text id='timer-{{$list_item->id}}' class="task-item-timer">{{$timer->total_time}}</text>
                                                    <button id="timer-pause-{{$list_item->id}}" class="btn btn-primary resume-timer">Resume</button>
                                                    @endif
                                                    <input class="timer_id" type="hidden" value="{{$timer->timer_id}}">
                                                    <input class="task_checklist_id" type="hidden" value="{{$list_item->id}}">
                                                    <input class="total_time" type="hidden" value="{{$timer->total_time}}">
                                                    <input class="timer_status" type="hidden" value="{{$timer->timer_status}}">
                                                </div>
                                                @empty
                                                <div id='timer-options-{{$list_item->id}}' class="pull-right">
                                                    <text id='timer-{{$list_item->id}}' class="task-item-timer"></text>
                                                    <input class="timer_id" type="hidden" value="">
                                                </div>
                                                @endforelse
                                            </div>
                                            <div class="pull-right">
                                                @forelse($list_item->task_checklist_statuses->where('user_id',$user_id) as $task_checklist_status)
                                                @if ($task_checklist_status->status === 'Default')
                                                <div class="btn btn-default btn-shadow bg-gray checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                                @elseif($task_checklist_status->status === 'Ongoing')
                                                <div class="btn btn-default btn-shadow bg-orange checklist-status">&nbsp;<i class="glyphicon glyphicon-time"></i>&nbsp;</div>
                                                @elseif($task_checklist_status->status === 'Completed')
                                                <div class="btn btn-default btn-shadow bg-green checklist-status">&nbsp;<i class="glyphicon glyphicon glyphicon-ok"></i>&nbsp;</div>
                                                @elseif($task_checklist_status->status === 'Urgent')
                                                <div class="btn btn-default btn-shadow bg-red checklist-status">&nbsp;&nbsp;<i class="fa fa-exclamation"></i>&nbsp;&nbsp;&nbsp;</div>
                                                @endif
                                                @empty
                                                <div class="btn btn-default btn-shadow bg-gray checklist-status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                                @endforelse
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
                                                            <a target="_blank" href="{{url('taskitem/'.$list_item->id)}}" class="btn-edit btn-shadow btn"><i class="fa fa-external-link"></i> View</a>&nbsp;&nbsp;&nbsp;
                                                            @if($module_permissions->where('slug','edit.tasks')->count() === 1)
                                                            <a href="#" class="btn-edit btn-shadow btn edit-task-list-item" style="font-size: 18px!important;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>&nbsp;&nbsp;&nbsp;
                                                            @endif
                                                            @if($module_permissions->where('slug','delete.tasks')->count() === 1)
                                                            <a href="#" class="btn-delete btn-shadow btn alert_delete view-btn-delete" style="font-size: 18px!important;"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>
                                                            @endif
                                                            <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                                                            <input type="hidden" class="task_list_id" value="{{$list_item->task_id}}" />
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
                <ul class="list-group link-group">
                    {{--*/ $ref = 1 /*--}}
                    @foreach($links as $link)
                    @if($link->task_id == $task->task_id)
                    <li class="list-group-item" id="link-{{$link->id}}" style="{{ $ref == 1 ?  '' : 'border-top: none!important'}}">
                        <div class="row">
                            <div class="col-sm-4">
                                {{--*/ $parse_url = parse_url($link->url) /*--}}
                                <input type="hidden" class="task_list_id" value="{{$task->task_id}}" />
                                <input type="hidden" class="company_id" value="{{$company_id}}" />
                                @if(empty($parse_url['scheme']))
                                <a target="_blank" href="http://{{ $link->url }}"><strong>{{ $link->title }}</strong></a>
                                @else
                                <a target="_blank" href="{{ $link->url }}"><strong>{{ $link->title }}</strong></a>
                                @endif
                            </div>
                            <div class="col-sm-5" style="text-align: justify">{{ $link->descriptions }}</div>
                            <div class="col-sm-3 text-right">{{ $link->category_name }}&nbsp;&nbsp;&nbsp;
                                <a href="#" class="pull-right move-link"><i class="glyphicon glyphicon-move"></i></a>
                                @if($module_permissions->where('slug','delete.links')->count() === 1 || $link->user_id === Auth::user('user')->user_id)
                                <a href="{{ url('deleteLink/' . $link->id) }}" id="{{$link->id}}" class="remove-link pull-right" style="padding-right: 10px"><i class="glyphicon glyphicon-remove"></i></a>
                                @endif
                                @if($module_permissions->where('slug','edit.links')->count() === 1 || $link->user_id === Auth::user('user')->user_id)
                                <a href="{{ url('links/' . $link->id . '/edit') }}" id="{{$link->id}}" class="edit-link pull-right" style="padding-right: 10px"><i class="glyphicon glyphicon-pencil"></i></a>
                                @endif
                            </div>
                        </div>
                    </li>
                    {{--*/ $ref++ /*--}}
                    @endif
                    @endforeach
                </ul>
            </div>
            <div class="row">
                <div class="col-sm-8" style="white-space: nowrap!important">
                    @if($module_permissions->where('slug','create.tasks')->count() === 1 || $project_owner === Auth::user('user')->user_id)
                    <a href="#" class="btn btn-submit btn-shadow btn-sm check-list-btn" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> Document </a>&nbsp;&nbsp;
                    <a href="#" class="btn btn-submit btn-shadow btn-sm add-spreadsheet" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> Spreadsheet </a>&nbsp;&nbsp;
                    <a href="#" class="btn btn-submit btn-shadow btn-sm task-list-btn" id="{{ $task->task_id }}"><i class="glyphicon glyphicon-plus"></i> Task List </a>&nbsp;&nbsp;
                    @endif
                    <a href="#" class="btn-submit btn-shadow btn-sm btn" data-toggle="modal" data-target="#add_link_{{ $task->task_id }}" data-placement="right" title="Add Links"><i class="fa fa-plus"></i> Link</a>&nbsp;&nbsp;
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
    <div class="modal fade" id="edit_task_{{ $task->task_id }}" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @include('task/edit', ['task'=> $task] )
            </div>
        </div>
    </div>
    <input class="task_id" type="hidden" value="{{$task->task_id}}"/>
    <div class="modal fade edit-link-modal" id="edit_link" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
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
                    {!! Form::hidden('task_id',$task->task_id) !!}
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
    <div class="modal fade add_link_modal" id="add_link_{{ $task->task_id }}" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Add Link</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'links.store','class' => 'form-horizontal link-form'])  !!}
                    {!! Form::hidden('task_id',$task->task_id) !!}
                    @include('links/partials/_form')
                    {!! Form::close()  !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop