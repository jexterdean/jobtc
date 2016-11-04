@extends('layouts.default')
@section('content')
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Tasks</th>
            <th>Total Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            <td>{{$employee->user->name}}</td>
            <td>
                @foreach($tasks->where('user_id',$employee->user_id) as $task)
                <div class="row">
                    <div class="col-md-3">{{$task->task_checklist->checklist_header}}</div>
                    <div class="col-md-3">{{$task->total_time}}</div>
                </div>
                @endforeach
            </td>
            <td>
                <div class="row">
                    @foreach($total_time->where('user_id',$employee->user_id) as $total)
                    <div class="col-md-3">{{$total->timeSum}}</div>
                    @endforeach
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop