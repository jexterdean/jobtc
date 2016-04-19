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
                    <div class="box box-default">
                        <div class="box-header">
                            <h3 class="box-title">Details</h3>
                            <div class="pull-right">
                                <h3 class="box-title">Project # {{ $project->ref_no }}</h3>
                            </div>
                        </div>
                        <div class="box-body">
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
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
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
                @include('common.task',['tasks' => $tasks, 'belongs_to' => 'project', 'unique_id' => $project->project_id,'project_id' => $project->project_id])
            </div>
        </div>
    </div>
@stop

