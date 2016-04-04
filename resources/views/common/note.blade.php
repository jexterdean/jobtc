<div class="panel panel-{{ \App\Helpers\Helper::getRandomColor() }}">
    <div class="panel-heading">
        <h3 class="panel-title">Private Notes {{ Auth::user()->name }}</h3>
    </div>
    <div class="panel-body">
        @if(isset($note->note_id))
            {!!  Form::open(['method' => 'PUT','route' => ['note.update', isset($note->note_id) ? $note->note_id : ''],
            'class' => 'note-form'])  !!}
        @else
            {!!  Form::open(['method' => 'POST','route' => ['note.store'],'class' => 'note-form'])  !!}
        @endif
        <div class="form-group">
            {!!  Form::textarea('note_content',isset($note->note_content) ? $note->note_content : '',['size' => '25x3',
            'class' => 'form-control textarea', 'placeholder' => 'Enter Notes']) !!}
        </div>
        <div class="form-group">
            {!!  Form::hidden('belongs_to',$belongs_to) !!}
            {!!  Form::hidden('unique_id', $unique_id) !!}
            {!! Form::submit('Save',['class' => 'btn btn-primary'])  !!}
        </div>
        {!!  Form::close()  !!}
    </div>
</div>