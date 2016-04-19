@extends('layouts.default')
@section('content')
    <div class="modal fade" id="add_client" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Client</h4>
                </div>
                <div class="modal-body">
                    {!!  Form::open(['route' => 'client.store','class' => 'form-horizontal client-form'])  !!}
                    @include('client/partials/_form')
                    {!!  Form::close()  !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="client_edit" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <div class="modal fade" id="client_show" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Client List</h3>
                <div class="box-tools pull-right">
                    <a data-toggle="modal" href="#add_client">
                        <button class="btn btn-shadow btn-success btn-sm"><i class="fa fa-plus-circle"></i> Add New Client</button>
                    </a>
                    <button class="btn btn-sm btn-transparent" data-widget="collapse"><i class="fa fa-chevron-up"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php
                $DATA = array();
                $QA = array();
                foreach ($clients as $client) {
                    $linkToEdit = "<a href='client/$client->id/edit' data-toggle='modal' data-target='#client_edit'> <i class='fa fa-edit'></i> </a>";
                    $linkToView = "<a href='client/$client->id' data-toggle='modal' data-target='#client_show'> <i class='fa fa-external-link'></i> </a>";
                    $linkToDelete = "<a href='client/$client->client_id/delete' class='alert_delete'> <i class='fa fa-trash-o'></i> </a>";
                    $Option = "$linkToView <span class=hspacer></span> $linkToEdit <span class=hspacer></span> $linkToDelete";
                    $QA[] = array($client->company_name, $client->contact_person, $client->email, isset($countries[$client->country_id]) ? $countries[$client->country_id] : '', $Option);
                }

                    $cacheKey = 'client.list.'. session()->getId();
                    \Cache::put($cacheKey, $QA,100);
                ?>
                <table class="table table-striped table-bordered table-hover datatableclass" id="client_table">
                    <thead>
                    <tr>
                        <th>
                            Company Name
                        </th>
                        <th>
                            Contact Person
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Country
                        </th>
                        <th>
                            Option
                        </th>
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