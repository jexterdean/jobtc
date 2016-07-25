@extends('layouts.default')
@section('content')
<div id="collapse-{{ $task_id }}">
    <ul class="tasklist-group list-group" id="list_group_{{ $task_id }}">
        <li id="task_item_{{ $list_item->id }}" class="list-group-item task-list-item">
            {{--region Briefcase Item Add Link--}}
            <div class="modal fade add_link_modal" id="add_link_{{ $list_item->task_id . '-' . $list_item->id }}" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            <h4 class="modal-title">Add Link</h4>
                        </div>
                        <div class="modal-body">
                            {!!  Form::open(['route' => 'links.store','class' => 'form-horizontal link-form'])  !!}
                            {!! Form::hidden('task_id',$list_item->task_id) !!}
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
                    <a href="#task-item-collapse-{{$list_item->id}}" class="checklist-header toggle-tasklistitem">{!! $list_item->checklist_header !!}</a>
                    <input type="hidden" class="company_id" value="{{$company_id}}" />
                    <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                    <input type="hidden" class="task_list_id" value="{{$list_item->task_id}}" />
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
                    <input type="hidden" class="task_list_id" value="{{$list_item->task_id}}" />--}}

                    <!--a href="#" class="icon icon-btn alert_delete"><i class="fa fa-times" aria-hidden="true"></i></a-->
                    <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                    <input type="hidden" class="task_list_id" value="{{$list_item->task_id}}" />
                </div>
            </div>
            <div class="row">
                <div id="task-item-collapse-{{$list_item->id}}" class="task-item-collapse">
                    <div class="checklist-item">{!! $list_item->checklist !!}</div>
                    <input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
                    <input type="hidden" class="task_list_id" value="{{$list_item->task_id}}" />
                    @foreach($links as $link)
                    <hr/>
                    @if($link->task_item_id == $list_item->id)
                    <div class="col-md-12" id="link-{{$link->id}}">
                        <div class="col-md-3">
                            <a href="{{ $link->url }}" target="_blank"><strong>{{ $link->title }}</strong></a>
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
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right" style="margin-right: 5px;">
                                <a target="_blank" href="{{url('taskitem/'.$list_item->id)}}" class="btn-edit btn-shadow btn"><i class="fa fa-external-link"></i> View</a>&nbsp;&nbsp;&nbsp;
                                <a href="#" class="btn-edit btn-shadow btn" data-toggle="modal" data-target="#add_link_{{ $list_item->task_id . '-' . $list_item->id }}" data-placement="right" title="Add Links"><i class="fa fa-plus"></i> Link</a>&nbsp;&nbsp;&nbsp;
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
    </ul>
    <input class="task_id" type="hidden" value="{{$task_id}}"/>
</div>
@stop