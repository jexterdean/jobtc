@extends('layouts.default')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    </div>
                </div>
            </div>

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Details</a></li>
                    <li><a href="#tab_2" data-toggle="tab">Attachments</a></li>
                    <li><a href="#tab_3" data-toggle="tab">Timer</a></li>
                    <li><a href="#tab_4" data-toggle="tab">Task</a></li>
                    <li class="dropdown pull-right">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Options <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="{{ url('project') }}">Back</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="{{ url('project/'.$project->project_id.'/edit') }}"
                                                       data-toggle='modal' data-target='#ajax'>Edit</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="{{ url('project/'.$project->project_id.'/delete') }}">Delete</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-solid box-primary">
                                    <div class="box-header">
                                        <h3 class="box-title">Project Ref # {{ $project->ref_no }}</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row static-info">
                                            <div class="col-md-5 name">
                                                Project Ref No:
                                            </div>
                                            <div class="col-md-7 value">
                                                {{ $project->ref_no }}
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-5 name">
                                                Title:
                                            </div>
                                            <div class="col-md-7 value">
                                                {{ $project->project_title }}
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-5 name">
                                                Company:
                                            </div>
                                            <div class="col-md-7 value">
                                                {{ $clients[$project->client_id] }}
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-5 name">
                                                Description:
                                            </div>
                                            <div class="col-md-7 value">
                                                {{ $project->project_description }}
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-5 name">
                                                Start Date & Time:
                                            </div>
                                            <div class="col-md-7 value">
                                                {{ date("d M Y, h:ia", strtotime($project->start_date)) }}
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-md-5 name">
                                                Deadline:
                                            </div>
                                            <div class="col-md-7 value">
                                                {{ date("d M Y, h:ia", strtotime($project->deadline)) }}
                                            </div>
                                        </div>
                                        @role('admin')
                                            <div class="row static-info">
                                                <div class="col-md-5 name">
                                                    Rate Type:
                                                </div>
                                                <div class="col-md-7 value">
                                                    {{ $project->rate_type }}
                                                    {{ $project->rate_value }}
                                                </div>
                                            </div>
                                        @endrole

                                        @role('admin|staff')
                                            <div class="row static-info">
                                                {!!  Form::open(['method' => 'POST','url' => 'updateProgress'])  !!}
                                                <div class="form-group">
                                                    <label class="col-md-5 name">Project Completion</label>
                                                    <div class="col-md-3 value">
                                                        {!!  Form::select('project_progress', $progress_option, isset
                                                        ($project->project_progress) ? $project->project_progress : '', ['class' => 'form-control', 'placeholder' => 'Select One', "onchange" => "this.form.submit()"] )  !!}
                                                    </div>
                                                </div>
                                                {!!  Form::hidden('project_id',$project->project_id) !!}
                                                {!!  Form::close()  !!}
                                            </div>
                                        @endrole

                                    </div>
                                </div>

                                @include('common.note',['note' => $note, 'belongs_to' => 'project', 'unique_id' => $project->project_id])

                            </div>

                            @role('admin')
                                @include('common.assign',['assignedUsers' => $assignedUsers, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
                            @endrole

                            @include('common.comment',['comments' => $comments, 'belongs_to' => 'project', 'unique_id' => $project->project_id])

                        </div>
                    </div>
                    <div class="tab-pane" id="tab_2">
                        @include('common.attachment',['attachments' => $attachments, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
                    </div>
                    <div class="tab-pane" id="tab_3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-solid box-primary">
                                    <div class="box-header">
                                        <h3 class="box-title">Project Timer</h3>
                                        <div class="box-tools pull-right">
                                            @if(count($timer_check))
                                                {!!  Form::open(['method' => 'POST','url' => 'endTimer','class' =>
                                                'form-horizontal']) !!}
                                                <button type="submit" class="btn btn-danger btn-sm">End Timer <i
                                                            class="fa fa-font"></i></button>
                                                {!!  Form::hidden('project_id',$project->project_id) !!}
                                                {!!  Form::hidden('timer_id',$timer_check->timer_id)  !!}
                                                {!!  Form::close()  !!}
                                            @else
                                                {!!  Form::open(['method' => 'POST','url' => 'startTimer','class' =>
                                                'form-horizontal'])  !!}
                                                <button type="submit" class="btn btn-success btn-sm">Start Timer <i
                                                            class="fa fa-font"></i></button>
                                                {!!  Form::hidden('project_id',$project->project_id) !!}
                                                {!!  Form::close()  !!}                                            @endif
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>
                                                    Username
                                                </th>
                                                <th>
                                                    Start Time
                                                </th>
                                                <th>
                                                    End Time
                                                </th>
                                                <th>
                                                    Delete
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($timers!='')
                                                @foreach($timers as $timer)
                                                    <tr>
                                                        <td>{{ $timer->username }}</td>
                                                        <td>{{ date("d M Y h:i:s a",strtotime($timer->start_time)) }}</td>
                                                        <td>
                                                            @if($timer->end_time)
                                                                {{ date("d M Y h:i:s a",strtotime($timer->end_time)) }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {!!  Form::open(['method' => 'POST','url' => 'deleteTimer',
                                                            'class' => 'form-horizontal'])  !!}
                                                            <button type="submit" class="btn btn-danger btn-xs"><i
                                                                        class="icon-trash"></i> Delete
                                                            </button>
                                                            {!! Form::hidden('timer_id',$timer->timer_id)  !!}
                                                            {!!  Form::close()  !!}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_4">
                        @include('common.task',['tasks' => $tasks, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
                    </div>
                </div>
            </div>
        </div>
    <</div>

@stop

