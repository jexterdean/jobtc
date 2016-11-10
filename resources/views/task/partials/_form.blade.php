
<div class="form-body">
    {!!  Form::hidden('belongs_to',$belongs_to)  !!}
    {!!  Form::hidden('unique_id', $unique_id)  !!}
    {!!  Form::hidden('project_id', $unique_id)  !!}


    <div class="form-group">
        {!!  Form::label('task_title','Title',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','task_title', (isset($task->task_title)?$task->task_title: ''),['class' => 'form-control', 'placeholder' => 'Title']) !!}
        </div>
    </div>

    <div class="form-group">
        {!!  Form::label('task_description','Description',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::textarea('task_description', (isset($task->task_description)?$task->task_description: ''),
            ['size' => '30x3','class' => 'form-control', 'placeholder' => 'Description']) !!}
        </div>
    </div>

    <div class="form-group">
        {!!  Form::label('due_date','Due Date',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::input('text','due_date','',['class' => 'form-control form-control-inline
            input-medium date-picker', 'placeholder' => 'Due Date',
            'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
        </div>
    </div>

    @if(Auth::user('user')->user_type === 1 || Auth::user('user')->user_type === 2 || Auth::user('user')->user_type === 3)
    <div class="form-group">
        {!!  Form::label('username','Assign User',['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-9">
            {!!  Form::select('username', $assign_username, isset
            ($task->user_id) ? $task->user_id : '',
             ['class' => 'form-control input-xlarge select2me',
            'placeholder' => 'Assign User',] )  !!}
        </div>
    </div>
    @endif
    @if(Auth::user('user')->user_type === 4)
    {!!  Form::hidden('assign_username',Auth::user('user')->email,['readonly' => true])  !!}
    @endif
    <div class="form-group">
        <label class="col-md-3"></label>
        <div class="col-md-9">
        {!!  Form::submit(isset($buttonText) ? $buttonText : 'Submit',['class' => 'btn btn-edit btn-shadow pull-right'])  !!}
        </div>
    </div>
</div>