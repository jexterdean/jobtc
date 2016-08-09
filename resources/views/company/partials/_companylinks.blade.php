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
                    <li class='list-group-item'>
                        {{--*/ $parse_url = parse_url($link->url) /*--}}
                        @if(empty($parse_url['scheme']))
                        <a target="_blank" href="http://{{ $link->url }}">{{ $link->title }}</a>
                        @else
                        <a target="_blank" href="{{ $link->url }}">{{ $link->title }}</a>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    {!! ($ref % 4) != 0 ? '' : '</div>'!!}
    {{--*/ $ref++ /*--}}
    @endforeach
@stop