@extends('layouts.default')
@section('content')
<div class="modal fade" id="add_project" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-{{ \App\Helpers\Helper::getRandomColor() }}">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Project</h4>
            </div>
            <div class="modal-body">
                @if(Auth::user('user')->user_type === 1 || Auth::user('user')->user_type === 2 || Auth::user('user')->user_type === 3)
                {!!  Form::open(['route' => 'project.store','class' => 'form-horizontal project-form'])  !!}
                @include('project/partials/_form')
                {!! Form::close()  !!}
                @else
                <div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                    <strong>You dont have to perform this action!!</strong>
                </div>
                @endrole
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ajax" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<<<<<<< HEAD


<<<<<<< HEAD
<<<<<<< HEAD
<div class="col-md-12">
    <div class="box box-solid box-{{ \App\Helpers\Helper::getRandomColor() }}">
        <div class="box-header">
            <h3 class="box-title">Project List</h3>
            <div class="box-tools pull-right">
                <a data-toggle="modal" href="#add_project">
                    <button class="btn btn-sm"><i class="fa fa-plus-circle"></i> Add Project</button>
                </a>
                <button class="btn btn-{{ \App\Helpers\Helper::getRandomColor() }} btn-sm" data-widget="collapse"><i
                        class="fa fa-minus"></i></button>
=======
=======
>>>>>>> project_update
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Project List</h3>
                <div class="box-tools pull-right">
                    <a data-toggle="modal" href="#add_project">
                        <button class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Add Project</button>
                    </a>
                    <button class="btn btn-sm btn-danger" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
<<<<<<< HEAD
>>>>>>> 7961e7ff7602b9e3394a2c9c4880dfe48422af76
=======
=======
=======
>>>>>>> project-merge-04-19-2016
<div class="col-md-12">
    <div class="box box-default">
        <div class="box-header">
            <h3 class="box-title">Project List</h3>
            <div class="box-tools pull-right">
                <a data-toggle="modal" href="#add_project">
                    <button class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Add Project</button>
                </a>
<<<<<<< HEAD
                <button class="btn btn-{{ \App\Helpers\Helper::getRandomColor() }} btn-sm" data-widget="collapse"><i
                        class="fa fa-minus"></i></button>
>>>>>>> 9c35634d6341f4119334b566861bca0dd430be62
>>>>>>> project_update
=======
                <button class="btn btn-sm btn-danger" data-widget="collapse"><i class="fa fa-minus"></i></button>
>>>>>>> project-merge-04-19-2016
            </div>
        </div>
        <div class="box-body">

            <?php
            $DATA = array();
            $QA = array();
            foreach ($projects as $project) {
                $linkToEdit = "<a href='" . route('project.edit', $project->project_id) . "' data-toggle='modal'
                    data-target='#ajax' class='show_edit_form'> <i class='fa-2x fa fa-edit'></i></a>";
                $linkToView = "<a href='project/$project->project_id'><i class='fa fa-external-link fa-2x'></i></a>";
                $linkStatus = \App\Helpers\Helper::getProgressStatus($project->project_progress);
                $linkToDelete = "<a href='" . route('project.destroy', $project->project_id) . "' class='alert_delete'> <i class='fa-2x
                    fa fa-trash-o'></i> </a>";
                $Option = "$linkToView <span class='hspacer'></span> $linkToEdit <span class='hspacer'></span> $linkToDelete";
                $QA[] = array(
                    $project->project_title,
                    isset($clients[$project->client_id]) ? $clients[$project->client_id] : '',
                    $project->ref_no, date("d M Y", strtotime($project->start_date)),
                    date("d M Y", strtotime($project->deadline)),
                    $project->rate_type,
                    $project->currency,
                    $linkStatus, $Option
                );
            }

            $cacheKey = md5('project.list.' . session()->getId());
            Cache::put($cacheKey, $QA, 100);
            ?>
            <table class="table table-striped table-bordered table-hover datatableclass" id="project_table">
                <thead>
                    <tr>
                        <th>Project Title</th>
                        <th>Company Name</th>
                        <th>Ref No</th>
                        <th>Start Date</th>
                        <th>Deadline</th>
                        <th>Rate Type</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
@stop