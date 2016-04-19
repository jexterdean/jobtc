<div class="modal fade" id="add_attachment" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Attachments</h4>
            </div>
            {!!  Form::open(['files' => 'true', 'method' => 'POST','route' => ['attachment.store'],'class' => 'attachment-form'])  !!}
            <div class="modal-body">
                {!!  Form::hidden('belongs_to',$belongs_to)  !!}
                {!!  Form::hidden('unique_id', $unique_id)  !!}
                <div class="form-group">
                    {!!  Form::input('text','attachment_title','',['class' => 'form-control', 'placeholder' => 'Title', 'tabindex' => '1']) !!}
                </div>
                <div class="form-group">
                    {!!  Form::textarea('attachment_description','',['size' => '30x3', 'class' => 'form-control',
                    'placeholder' => 'Description', 'tabindex' => '2']) !!}
                </div>
                <div class="form-group">
                    {!! Form::input('file','file','') !!}
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
        <h3 class="box-title">Attachments</h3>
        <div class="box-tools pull-right">
            <a data-toggle="modal" href="#add_attachment">
                <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add Attachment</button>
            </a>
            <button class="btn btn-sm btn-transparent" data-widget="collapse"><i class="fa fa-chevron-down"></i></button>
        </div>
    </div>
    <div class="box-body collapse">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>
                    Title
                </th>
                <th>
                    Download Link
                </th>
                <th>
                    Option
                </th>
            </tr>
            </thead>
            <tbody>
            @if(count($attachments) > 0)
                @foreach($attachments as $attachment)
                    <tr>
                        <td>{{ $attachment->attachment_title }}</td>
                        <td><a href="{{ url('assets/attachment_files/'.$attachment->file) }}">click here</a>
                        </td>
                        <td>
                            @if(Auth::user()->is('admin') || Auth::user()->username == $comment->username)
                                {!!  Form::open(array('route' => array('attachment.destroy',
                                $attachment->attachment_id), 'method' => 'delete'))  !!}
                                <button type="submit" class="btn btn-danger btn-xs"><i class="icon-trash"></i>
                                    Delete
                                </button>
                                {!!  Form::close()  !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">No data was found.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
			