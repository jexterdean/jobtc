<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">{{ studly_case($belongs_to) }} Attachments</h3>
    </div>
    <div class="panel-body">
        {!!  Form::open(['files' => 'true', 'method' => 'POST','route' => ['attachment.store'],'class' =>
        'attachment-form'])  !!}
        {!!  Form::hidden('belongs_to',$belongs_to)  !!}
        {!!  Form::hidden('unique_id', $unique_id)  !!}
        <div class="form-group">
            {!!  Form::input('text','attachment_title','',['class' => 'form-control', 'placeholder' => 'Enter
            Title', 'tabindex' => '1']) !!}
        </div>
        <div class="form-group">
            {!!  Form::textarea('attachment_description','',['size' => '30x3', 'class' => 'form-control',
            'placeholder' => 'Enter Description', 'tabindex' => '2']) !!}
        </div>
        <div class="form-group">
            {!! Form::input('file','file','') !!}
        </div>
        <div class="form-group">
            {!!  Form::submit('Add',['class' => 'btn btn-primary'])  !!}
        </div>
        {!!  Form::close() !!}
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">{{ studly_case($belongs_to) }} Attachments List</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>
                    Title
                </th>
                <th>
                    Uploaded On
                </th>
                <th>
                    Description
                </th>
                <th>
                    Download Link
                </th>
                <th>
                    Delete
                </th>
            </tr>
            </thead>
            <tbody>
            @if(count($attachments) > 0)
                @foreach($attachments as $attachment)
                    <tr>
                        <td>{{ $attachment->attachment_title }}</td>
                        <td>{{ date("d M Y",strtotime($attachment->created_at)) }}</td>
                        <td>{{ $attachment->attachment_description }}</td>
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
			