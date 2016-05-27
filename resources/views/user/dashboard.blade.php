@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div style="clear:both;"></div>
            <br/>
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-lightbulb-o"></i> Pending Projects</h3>
                </div>
                <div class="box-body">
                    @if(count($projects))
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    Ref No
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Due Date
                                </th>
                                <th>
                                    Progress
                                </th>
                                <th>
                                    Label
                                </th>
                                <th>
                                    View
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>{{ $project->ref_no }}</td>
                                    <td>{{ $project->project_title }}</td>
                                    <td>{{ date("d M Y",strtotime($project->deadline)) }}
                                    <td>
                                        <div class="progress xs progress-striped active">
                                            <div class="progress-bar progress-bar-primary"
                                                 style="width: {{ $project->project_progress }}%"></div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-blue">{{ $project->project_progress }} %</span></td>
                                    <td>
                                        <a href="{{ url('project/'.$project->project_id) }}"><i
                                                    class="fa fa-external-link"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class='alert alert-danger'>
                            <i class='fa fa-ban'></i>
                            <strong>No pending projects found!!</strong>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
        <div class='col-md-6 col-sm-12'>
            @if(Auth::user('user'))
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-tasks"></i> Pending Tasks</h3>
                    </div>
                    <div class="box-body" id="task-list-box">
                        @if(count($tasks))
                            <ul class="todo-list">
                                @foreach($tasks as $task)
                                    <li>
                                <span class="handle">
                                    <i class="fa fa-ellipsis-v"></i>
                                    <i class="fa fa-ellipsis-v"></i>
                                </span>
                                        <span class="text">{{ $task->task_title }}</span>
                                        <span class="badge bg-light-blue"><i
                                                    class="fa fa-clock-o"></i> Till {{ date("d M Y",strtotime($task->due_date)) }}</span>
                                        @if($task->task_status == 'pending')
                                            <span class="badge bg-red">Pending</span>
                                        @elseif($task->task_status == 'progress')
                                            <span class="badge bg-green">Progress</span>
                                        @endif
                                        <div class="tools">
                                            <a href="{{ url($task->belongs_to.'/'.$task->unique_id) }}"><i
                                                        class="fa fa-external-link"></i></a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                        @else
                            <div class='alert alert-success'>
                                <i class='fa fa-ban'></i>
                                <strong>You have completed all your tasks!!</strong>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            {{-- include the meeting calendar --}}
            {{-- replace the event calendar --}}
            {{--@include('meeting.calendar')--}}
        </div>
    </div>

    <?php
    if(in_array('calendar', $assets)){
        $EVENTS = array();
        foreach ($events as $event) {
            $startDate = date("Y-m-d", strtotime($event->start_date));
            $endDate = date("Y-m-d", strtotime($event->end_date));
            $color = \App\Helpers\Helper::getRandomHexColor();

            $EVENTS[] = [
                'title'=> $event->event_title,
                'start' => $startDate,
                'end' => $endDate,
                'color' => $color,
                'allDay' => true
            ];
        }
        $EVENTS = json_encode($EVENTS);
    }
    ?>
@stop
@section('js_footer')
@parent
<script>
    Validate.init();
</script>
@stop
