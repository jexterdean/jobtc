<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><a role="tab" id="headingFour" data-toggle="collapse" data-parent="#accordion_" href="#project-notes" aria-expanded="true">Notes {{ Auth::user()->name }}</a></h3>
    </div>
    <div id="project-notes" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
        <div class="panel-body">
            @if(isset($note->note_id))
                {!!  Form::open(['method' => 'PUT','route' => ['note.update', isset($note->note_id) ? $note->note_id : ''],
                'class' => 'note-form'])  !!}
            @else
                {!!  Form::open(['method' => 'POST','route' => ['note.store'],'class' => 'note-form'])  !!}
            @endif
            <div class="form-group">
                {!!  Form::textarea('note_content',isset($note->note_content) ? $note->note_content : '',['size' => '25x3',
                'class' => 'form-control textarea', 'placeholder' => 'Notes']) !!}
            </div>
            <div class="form-group">
                {!!  Form::hidden('belongs_to',$belongs_to) !!}
                {!!  Form::hidden('unique_id', $unique_id) !!}
                {!! Form::submit('Save',['class' => 'btn btn-primary'])  !!}
            </div>
            {!!  Form::close()  !!}
        </div>
    </div>
</div>