<div class="checklist-item">{!! $list_item->checklist !!}</div>
<input type="hidden" class="task_list_item_id" value="{{$list_item->id}}" />
<input type="hidden" class="task_list_id" value="{{$list_item->task_id}}" />
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
    <hr/>
    @endif
    @endforeach
</div>
<hr/>
<div class="row">
    <div class="col-md-12">
        <div class="pull-right" style="margin-right: 5px;">
            <a target="_blank" href="{{url('taskitem/'.$list_item->id)}}" class="btn-edit btn-shadow btn"><i class="fa fa-external-link"></i> View</a>&nbsp;&nbsp;&nbsp;
            <a href="#" class="btn-edit btn-shadow btn add-link-modal" data-toggle="modal" data-target="#add_link_{{ $list_item->task_id . '-' . $list_item->id }}" id="{{$list_item->id}}"><i class="fa fa-plus"></i> Link</a>&nbsp;&nbsp;&nbsp;
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