<div class="col-md-6">
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title">Assign</h3>
        </div>
        <div class="box-body">
            {!!  Form::open(['method' => 'POST','route' => ['assigneduser.store'],'class' => 'assign-user-form'])  !!}
            {!!  Form::hidden('belongs_to',$belongs_to)  !!}
            {!!  Form::hidden('unique_id', $unique_id)  !!}
            <div class="form-group">
                {!!  Form::select('username', [null=>'Please Select'] + $users, '', ['class' => 'form-control
                select2me', 'placeholder' => 'Select One', 'tabindex' =>'1'] )  !!}
            </div>
            <div class="form-group">
                {!!  Form::submit('Add',['class' => 'btn btn-primary '])  !!}
            </div>
            {!!  Form::close()  !!}


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
                @if($assignedUsers!='')
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
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
