@extends('layouts.default')
@section('content')
<div class="modal fade" id="add_client" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Company</h4>
            </div>
            <div class="modal-body">
                {!!  Form::open(['route' => 'company.store','class' => 'form-horizontal client-form'])  !!}
                @include('company/partials/_form')
                {!!  Form::close()  !!}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="client_show" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>


<!--div class="col-md-12">
    <div class="box box-default">
        <div class="box-header">
            <h3 class="box-title">Company List</h3>
            <div class="box-tools pull-right">
                <a data-toggle="modal" href="#add_client">
                    <button class="btn btn-shadow btn-submit btn-sm"><i class="fa fa-plus-circle"></i> Add New Company</button>
                </a>
                <button class="btn btn-sm btn-transparent" data-widget="collapse"><i class="fa fa-chevron-up"></i></button>
            </div>
        </div>
        <div class="box-body">
<?php
$DATA = array();
$QA = array();
foreach ($companies as $company) {
    $linkToEdit = "<a href='" . route('company.edit', $company->id) . "' data-toggle='modal' data-target='#client_edit'> <i class='fa fa-2x fa-edit'></i> </a>";
    $linkToView = "<a href='" . route('company.show', $company->id) . "' data-toggle='modal' data-target='#client_show'> <i class='fa fa-2x fa-external-link'></i> </a>";
    $linkToDelete = "<a href='" . route('company.destroy', $company->id) . "' class='alert_delete'> <i class='fa fa-2x fa-trash-o'></i> </a>";
    $Option = "$linkToView <span class=hspacer></span> $linkToEdit <span class=hspacer></span> $linkToDelete";
    $QA[] = array($company->name, $company->email, $company->address, $company->country, $Option);
}
$cacheKey = 'company.list.' . session()->getId();
Cache::put($cacheKey, $QA, 100);
?>
            <table class="table table-striped table-bordered table-hover datatableclass" id="client_table">
                <thead>
                <tr>
                    <th>
                        Company Name
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Address
                    </th>
                    <th>
                        Country
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div style="clear:both;"></div>
    </div>
</div-->


<div class="container"></div>
<div class="row">
    <div class="col-md-12">
        <a data-toggle="modal" href="#add_client">
            <button class="btn btn-shadow btn-submit btn-sm"><i class="fa fa-plus-circle"></i> Add New Company</button>
        </a>
    </div>
</div>
<br />
<div class="row">
    <div class="col-md-5">
        @foreach($projects as $project)
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">{{$project->project_title}}</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="list-group">
                            <li>No Employees assigned to this project.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-md-5">

    </div>
    <div class="col-md-2">
        <div class="box box-default">
            <div class="box-container">
                <div class="box-header">
                    <h3 class="box-title">Employees</h3>
                </div>
                <div class="box-body">
                    <div class="box-content">
                        <ul class="list-group">
                            @foreach($profiles as $profile)
                            <li class="list-group-item"><a href="#" class="drag-handle icon icon-btn move-tasklist"><i class="fa fa-arrows"></i></a>{{$profile->user->name}}</li>
                            @endforeach
                        </ul>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
@stop
