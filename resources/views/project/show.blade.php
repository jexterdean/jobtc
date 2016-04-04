@extends('layouts.default')
@section('content')

<div class="col-md-12">
    <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="panel panel-{{ \App\Helpers\Helper::getRandomColor() }}">
                        <div class="panel-heading">
                            <h3 class="panel-title">Details <span class="pull-right">Project # {{ $project->ref_no }}</span></h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>Title:</strong></td>
                                        <td>{{ $project->project_title }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Description:</strong></td>
                                        <td>{{ $project->project_description }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Account:</strong></td>
                                        <td>{{ $project->account }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Company:</strong></td>
                                        <td>{{ $clients[$project->client_id] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Project Type:</strong></td>
                                        <td>{{ $project->project_type }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Start & Deadline:</strong></td>
                                        <td>
                                            {{ date("d M Y, h:ia", strtotime($project->start_date)) }}
                                            <strong>To</strong>
                                            {{ date("d M Y, h:ia", strtotime($project->deadline)) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Currency & Rate:</strong></td>
                                        <td>
                                            {{ $project->currency }}
                                            {{ $project->rate_value }}
                                        </td>
                                    </tr>
                                    @role('admin')
                                    <tr>
                                        <td><strong>Rate Type:</strong></td>
                                        <td>
                                            {{ $project->rate_type }}
                                        </td>
                                    </tr>
                                    @endrole
                                    @role('admin|staff')
                                    <tr>
                                        <td><strong>Project Completion:</strong></td>
                                        <td>
                                        {!!  Form::open(['method' => 'POST','url' => 'updateProgress'])  !!}
                                            {!!  Form::select('project_progress', $progress_option, isset
                                                ($project->project_progress) ? $project->project_progress : '', ['class' => 'form-control', 'placeholder' => 'Select One', "onchange" => "this.form.submit()"] )  !!}
                                        {!!  Form::hidden('project_id',$project->project_id) !!}
                                        {!!  Form::close()  !!}
                                        </td>
                                    </tr>
                                    @endrole
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <a href="{{ route('project.destroy',$project->project_id) }}" class="alert_delete"><i class='fa-2x fa fa-trash-o'></i></a>&nbsp;&nbsp;&nbsp;
                            <a href="{{ route('project.edit',$project->project_id) }}" class="show_edit_form" data-toggle='modal' data-target='#ajax'><i class='fa-2x fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;
                            <a role="menuitem" tabindex="-1" href="{{ url('project') }}" class="pull-right"><i class='fa-2x fa fa-arrow-left'></i></a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    @include('common.attachment',['attachments' => $attachments, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
                </div>
                @role('admin')
                    @include('common.assign',['assignedUsers' => $assignedUsers, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
                @endrole

                @include('common.note',['note' => $note, 'belongs_to' => 'project', 'unique_id' => $project->project_id])

                @include('common.comment',['comments' => $comments, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
            </div>
            <div class="col-md-6">
                @include('common.task',['tasks' => $tasks, 'belongs_to' => 'project', 'unique_id' => $project->project_id])
                <div class="panel panel-{{ \App\Helpers\Helper::getRandomColor() }}">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-3">
                                <h3 class="panel-title">Project Timer</h3>
                            </div>
                            <div class="col-sm-9">
                                @if(count($timer_check))
                                    {!!  Form::open(['method' => 'POST','url' => 'endTimer']) !!}
                                    <button type="submit" class="pull-right btn btn-danger btn-sm">End Timer <i
                                                class="fa fa-font"></i></button>
                                    {!!  Form::hidden('project_id',$project->project_id) !!}
                                    {!!  Form::hidden('timer_id',$timer_check->timer_id)  !!}
                                    {!!  Form::close()  !!}
                                @else
                                    {!!  Form::open(['method' => 'POST','url' => 'startTimer'])  !!}
                                    <button type="submit" class="pull-right btn btn-success btn-sm">Start Timer <i
                                                class="fa fa-font"></i></button>
                                    {!!  Form::hidden('project_id',$project->project_id) !!}
                                    {!!  Form::close()  !!}
                                @endif
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
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
                            @if(count($timers) > 0)
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
                            @else
                                <tr>
                                    <td colspan="4">No data was found.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

