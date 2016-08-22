@extends('layouts.default')
@section('content')
    {{--*/ $ref = 1 /*--}}
    @foreach($links as $category=>$links_data)
    {!! ($ref % 4) != 1 ? '' : '<div class="row">'!!}
    <div class="column" style="padding-bottom: 0;">
        <div class="portlet">
            <div class="portlet-header">{{ $category }}</div>
            <div class="portlet-content">
                <ul style="padding-left: 0!important;">
                    @foreach($links_data as $link)
                    <li class='list-group-item link-{{$link->id}}'>
                        {{--*/ $parse_url = parse_url($link->url) /*--}}
                        <div class="row">
                            <div class="col-sm-9">
                                @if(empty($parse_url['scheme']))
                                <a target="_blank" href="http://{{ $link->url }}">{{ $link->title }}</a>
                                @else
                                <a target="_blank" href="{{ $link->url }}">{{ $link->title }}</a>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($module_permissions->where('slug','delete.links')->count() === 1 || $link->user_id === Auth::user('user')->user_id)
                                <a href="{{ url('deleteLink/' . $link->id) }}" id="{{$link->id}}" class="remove-link pull-right"><i class="glyphicon glyphicon-remove"></i></a>
                                @endif
                                @if($module_permissions->where('slug','edit.links')->count() === 1 || $link->user_id === Auth::user('user')->user_id)
                                <a href="{{ url('links/' . $link->id . '/edit') }}" id="{{$link->id}}" class="edit-link pull-right" style="padding-right: 10px;"><i class="glyphicon glyphicon-pencil"></i></a>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    {!! ($ref % 4) != 0 ? '' : '</div>'!!}
    {{--*/ $ref++ /*--}}
    @endforeach
    <div class="modal fade edit-link-modal" id="edit_link" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
@stop