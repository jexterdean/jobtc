@extends('layouts.default')
@section('content')


    <div class="row">

        <div class="col-md-6 col-sm-12">
            <!--If Role is Administrator, Employer or Manager-->
            @if(Auth::user('user')->user_type === 1 || Auth::user('user')->user_type === 2 || Auth::user('user')->user_type === 3)
                <div class="col-lg-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>
                                {{ count($clients) }}
                            </h3>
                            <p>
                                Clients
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>
                                {{ count($users) }}
                            </h3>
                            <p>
                                Users
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>
                                {{ count($estimates) }}
                            </h3>
                            <p>
                                Estimates
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-file-o"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>
                                {{ count($invoices) }}
                            </h3>
                            <p>
                                Invoices
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 text-center" style="border-right: 1px solid #f4f4f4">
                        @if($payable->totalSales>0)
                            <input type="text" class="knob" data-readonly="true"
                                   value="{{ round(100-(($paid->totalPaid/$payable->totalSales)*100),2) }}"
                                   data-width="120" data-height="120" data-fgColor="{{ \App\Helpers\Helper::getRandomHexColor
                                   () }}"/>
                        @else
                            <input type="text" class="knob" data-readonly="true" value="0" data-width="120"
                                   data-height="120" data-fgColor="{{ \App\Helpers\Helper::getRandomHexColor() }}"/>
                        @endif
                        <div class="knob-label">Percentage Amount Due</div>
                    </div>
                    <div class="col-xs-6 text-center" style="border-right: 1px solid #f4f4f4">
                        <input type="text" class="knob" data-readonly="true" value="{{ $inCompletProjects }}"
                               data-width="120" data-height="120" data-fgColor="{{ \App\Helpers\Helper::getRandomHexColor() }}"/>
                        <div class="knob-label">Pending Projects</div>
                    </div>
                </div>
            @else
                <div class="col-lg-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>
                                {{ count($total_projects) }}
                            </h3>
                            <p>
                                Projects Completed
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-lightbulb-o"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>
                                {{ count($total_bugs) }}
                            </h3>
                            <p>
                                Bugs Resolved
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-bug"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>
                                {{ count($total_tickets) }}
                            </h3>
                            <p>
                                Tickets Resolved
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-ticket"></i>
                        </div>
                    </div>
                </div>
            @endif
            <div style="clear:both;"></div>
            <br/>
            <div class="box">
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

            @if(!Auth::check('client'))
                <div class="box">
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


        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-body no-padding">
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-bug"></i> Pending Bugs</h3>
                </div>
                <div class="box-body">
                    @if(count($bugs))
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    Ref No
                                </th>
                                <th>
                                    Reported On
                                </th>
                                <th>
                                    Priority
                                </th>
                                <th>
                                    View
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bugs as $bug)
                                <tr>
                                    <td>{{ $bug->ref_no }}</td>
                                    <td>{{ date("d M Y",strtotime($bug->reported_on)) }}
                                    <td>{{ $bug->bug_priority }}</td>
                                    <td>
                                        <a href="{{ url('bug/'.$bug->bug_id) }}"><i class="fa fa-external-link"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class='alert alert-danger'>
                            <i class='fa fa-ban'></i>
                            <strong>No bugs found!!</strong>
                        </div>
                    @endif
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-ticket"></i> Opened Tickets</h3>
                </div>
                <div class="box-body">
                    @if(count($tickets))
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    Subject
                                </th>
                                <th>
                                    Date Requested
                                </th>
                                <th>
                                    Priority
                                </th>
                                <th>
                                    View
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->ticket_subject }}</td>
                                    <td>{{ date("d M Y",strtotime($ticket->created_at)) }}
                                    <td>{{ $ticket->ticket_priority }}</td>
                                    <td>
                                        <a href="{{ url('ticket/'.$ticket->ticket_id) }}"><i
                                                    class="fa fa-external-link"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class='alert alert-danger'>
                            <i class='fa fa-ban'></i>
                            <strong>No tickets found!!</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <?php
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
    ?>

@stop

@section('js_footer')
@parent

    <script>
        Validate.init();

    </script>

@stop