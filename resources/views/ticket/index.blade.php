@extends('layouts.default')
@section('content')
    <div class="modal fade" id="add_ticket" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Ticket</h4>
                </div>
                <div class="modal-body">
                    {!!  Form::open(['files' => 'true', 'route' => 'ticket.store','class' => 'form-horizontal
                    ticket-form'])  !!}
                    <div class="form-body">
                        <div class="form-group">
                            {!!  Form::label('ticket_subject','Subject',['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                                {!!  Form::input('text','ticket_subject',isset($ticket->ticket_subject) ?
                                $ticket->ticket_subject : '',['class' => 'form-control', 'placeholder' => 'Enter Subject', 'tabindex' => '1']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!!  Form::label('ticket_description','Description',['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                                {!!  Form::textarea('ticket_description',isset($ticket->ticket_description) ?
                                $ticket->ticket_description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => 'Enter Description', 'tabindex' => '2']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('ticket_priority','Priority',['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                                {!!  Form::select('ticket_priority', [
                                    null => 'Please select',
                                    'low' => 'Low',
                                    'medium' => 'Medium',
                                    'high' => 'High',
                                    'critical' => 'Critical'
                                ], isset($ticket->ticket_priority) ? $ticket->ticket_priority : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '3'] )  !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!!  Form::label('file','Select File',['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                                {!! Form::input('file','file','') !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!!  Form::submit(isset($buttonText) ? $buttonText : 'Send',['class' => 'btn
                                btn-success', 'tabindex' => '5'])  !!}
                            </div>
                        </div>
                    </div>
                    {!!  Form::close()  !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">Ticket List</h3>
                <div class="box-tools pull-right">
                    <a data-toggle="modal" href="#add_ticket">
                        <button class="btn btn-sm"><i class="fa fa-plus-circle"></i> Add New Ticket</button>
                    </a>
                    <button class="btn btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php $DATA = array();
                $QA = array();
                foreach ($tickets as $ticket) {
                    $linkToView = "<a href='ticket/$ticket->ticket_id'><i class='fa fa-external-link'></i></a>";
                    if ($ticket->ticket_status == "open")
                        $ticket_status = "<span class='label label-sm label-danger'>$ticket->ticket_status</span>";
                    else
                        $ticket_status = "<span class='label label-sm label-success'>$ticket->ticket_status</span>";
                    $linkToDelete = "<a href='ticket/$ticket->ticket_id/delete' class='alert_delete'> <i class='fa fa-trash-o'></i> </a>";
                    $Options = "$linkToView <span class='hspacer'></span> $linkToDelete";
                    $QA[] = array($ticket->ticket_subject, isset($ticket->username) ? $ticket->username : '', studly_case($ticket->ticket_priority), $ticket_status, date("d M Y", strtotime($ticket->created_at)), $Options);
                }

                    $cacheKey = 'tickets.list.'. session()->getId();
                    \Cache::put($cacheKey,$QA , 100);
                ?>
                <table class="table table-striped table-bordered table-hover datatableclass" id="project_table">
                    <thead>
                    <tr>
                        <th>
                            <span style="width: 250px;">Subject</span>

                        </th>
                        <th>
                            User Name
                        </th>
                        <th>
                            Priority
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Date Logged
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