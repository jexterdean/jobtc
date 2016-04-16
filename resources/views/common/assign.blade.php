<div class="modal fade" id="add_assign" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Attachments</h4>
            </div>
            {!!  Form::open(['method' => 'POST','route' => ['assigneduser.store'],'class' => 'assign-user-form'])  !!}
            <div class="modal-body">
                {!!  Form::hidden('belongs_to',$belongs_to)  !!}
                {!!  Form::hidden('unique_id', $unique_id)  !!}
                <div class="form-group">
                    {!!  Form::select('username',  $users, '', ['class' => 'form-control
                    select2me', 'placeholder' => 'Select User', 'tabindex' =>'1'] )  !!}
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    {!!  Form::submit('Add',['class' => 'btn btn-primary'])  !!}
                </div>
            </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>
<div class="box box-default collapsed-box">
    <div class="box-header">
        <h3 class="box-title">Assign</h3>
        <div class="box-tools pull-right">
            <a data-toggle="modal" href="#add_assign">
                <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Assign</button>
            </a>
            <button class="btn btn-sm btn-transparent" data-widget="collapse"><i class="fa fa-chevron-down"></i></button>
        </div>
    </div>
    <div class="box-body collapse">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>
                    Username
                </th>
                <th>
                    Assigned On
                </th>
                <th>
                    Delete
                </th>
            </tr>
            </thead>
            <tbody>
            @if(count($assignedUsers) > 0)
                @foreach($assignedUsers as $assignedUser)
                    <tr>
                        <td>{{ $assignedUser->username }}</td>
                        <td>{{ date("d M Y h:ia",strtotime($assignedUser->created_at)) }}</td>
                        <td>
                            {!!  Form::open(array('route' => array('assigneduser.destroy', $assignedUser->id),
                            'method' => 'delete'))  !!}
                            <button type="submit" class="btn btn-danger btn-xs"><i class="icon-trash"></i> Delete
                            </button>
                            {!!  Form::close()  !!}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">No data was found.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
