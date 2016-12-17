<form id="edit-briefcase-form">
    <div class="form-body">
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
    </div>
</form>