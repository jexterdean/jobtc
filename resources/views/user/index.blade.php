@extends('layouts.default')
@section('content')
    <div class="modal fade" id="add_user" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Client</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => 'user.store','class' => 'form-horizontal user-form'])  !!}
                    @include('user/partials/_form')
                    {!!  Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
        <div class="page-loading page-loading-boxed">
            <img src={{ url('assets/global/img/loading-spinner-grey.gif') }}  alt="" class="loading">
				<span>
				&nbsp;&nbsp;Loading... </span>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="box box-solid box-primary">
            <div class="box-header">
                <h3 class="box-title">User List</h3>
                <div class="box-tools pull-right">
                    <a data-toggle="modal" href="#add_user">
                        <button class="btn btn-sm"><i class="fa fa-plus-circle"></i> Add New User</button>
                    </a>
                    <button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php $DATA = array();
                $QA = array();
                foreach ($users as $user) {
                    if ($user->client_id == '')
                        $company_name = '-';
                    else
                        $company_name = $clients[$user->client_id];

                    if ($user->user_status == 'Active'){
                        $user_status = " <span class='label label-success'>Active</span>";
                    }
                    else{
                        $user_status = " <span class='label label-danger'>Banned</span>";
                    }

                    $linkToEdit = "<a href='user/$user->user_id/edit' data-toggle='modal' data-target='#ajax'> <i
                    class='fa fa-edit fa-2x'></i> </a>";
                    $linkToDelete = "<a href='user/$user->user_id/delete'> <i class='fa fa-trash-o alert_delete fa-2x
                    '></i> </a>";
                    $Options = "$linkToEdit $linkToDelete";
                    $QA[] = array($user->name,
                            $user->username,
                            $user_status,
                            $user->email,
                            $roles[$user->role_id],
                            $company_name,
                            $Options);
                }
                    $cacheKey = md5('user.list.'. session()->getId());
                    Cache::put($cacheKey, $QA,100);
                ?>
                <table class="table table-striped table-bordered table-hover datatableclass" id="user_table">
                    <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Username
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Role
                        </th>
                        <th>
                            Company
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