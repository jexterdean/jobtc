@extends('layouts.default')
@section('content')
<div class="row">
@foreach($results as $result)
@if($type === 'project')
    <div class="col-md-6 search-column">
        <a target="_blank" href="{{url('project/'.$result->project_id)}}">
            {{$result->project_title}}
        </a>
    </div>
@endif
@endforeach
</div>
@stop