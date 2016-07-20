@extends('layouts.default')
@section('content')
<div class="row">
    @foreach($links as $category=>$links_data)
    <div class="column">
        <div class="portlet">
            <div class="portlet-header">{{ $category }}</div>
            <div class="portlet-content">
                <ul style="padding-left: 0!important;">
                    @foreach($links_data as $link)
                    <li class='list-group-item'>
                        <a target="_blank" href="{{ $link->title }}">{{ $link->title }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endforeach

</div>
@stop