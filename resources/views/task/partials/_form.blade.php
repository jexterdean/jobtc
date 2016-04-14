
<div class="form-body">
    {!!  Form::hidden('belongs_to',$belongs_to)  !!}
    {!!  Form::hidden('unique_id', $unique_id)  !!}


    <div class="form-group">
        {!!  Form::label('task_title','Title',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','task_title', (isset($task->task_title)?$task->task_title: ''),['class' => 'form-control', 'placeholder' => '
        Title', 'tabindex' => '1']) !!}
        </div>
    </div>

    <div class="form-group">
        {!!  Form::label('task_description','Description',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('task_description', (isset($task->task_description)?$task->task_description: ''),
            ['size' => '30x3','class' => 'form-control', 'placeholder' => '
        Description', 'tabindex' => '2']) !!}
        </div>
    </div>

    <div class="form-group">
        {!!  Form::label('due_date','Due Date',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','due_date','',['class' => 'form-control form-control-inline
            input-medium date-picker', 'placeholder' => 'Due Date',
            'tabindex' => '3', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
        </div>
    </div>

    @role('admin')
    <div class="form-group">
        {!!  Form::label('username','Assign User',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('username', $assign_username, isset
            ($task->username) ? $task->username : '',
             ['class' => 'form-control input-xlarge select2me',
            'placeholder' => 'Assign User', 'tabindex' => '3'] )  !!}
        </div>
    </div>
    @endrole
    @role('staff')
    {!!  Form::hidden('assign_username',Auth::user()->username,['readonly' => true])  !!}
    @endif
    <div class="form-group">
        <label class="col-md-3"></label>
        <div class="col-md-9">
        {!!  Form::submit('Add',['class' => 'btn btn-primary', 'tabindex' => '5'])  !!}
        </div>
    </div>
</div>