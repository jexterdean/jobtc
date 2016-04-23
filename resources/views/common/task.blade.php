<div class="modal fade" id="add_task" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Task</h4>
            </div>
            {!!  Form::open(['method' => 'POST','route' => ['task.store'],'class' => 'task-form'])  !!}
            <div class="modal-body">
                {!!  Form::hidden('belongs_to',$belongs_to)  !!}
                {!!  Form::hidden('unique_id', $unique_id)  !!}
                {!!  Form::hidden('project_id', $project_id)  !!}
                <div class="form-group">
                    {!!  Form::input('text','task_title','',['class' => 'form-control', 'placeholder' => 'Title', 'tabindex' => '1']) !!}
                </div>
                <div class="form-group">
                    {!!  Form::textarea('task_description','',['size' => '30x3', 'class' => 'form-control',
                    'placeholder' => 'Description', 'tabindex' => '2']) !!}
                </div>
                <div class="form-group">
                    {!!  Form::input('text','due_date','',['class' => 'form-control form-control-inline
                    input-medium date-picker', 'placeholder' => 'Due Date', 'tabindex' => '3', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true'])  !!}
                </div>
                @if(Auth::user('user')->user_type === 1 || Auth::user('user')->user_type === 2 || Auth::user('user')->user_type === 3)
                    <div class="form-group">
                            {!!  Form::select('username', $assign_username, isset
                            ($task->user_id) ? $task->user_id : '',
                             ['class' => 'form-control input-xlarge select2me',
                            'placeholder' => 'Assign User',] )  !!}
                    </div>
                @endif
                @if(Auth::user('user')->user_type === 4)
                    {!!  Form::hidden('assign_username',Auth::user('user')->email,['readonly' => true])  !!}
                @endif
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    {!!  Form::submit('Add',['class' => 'btn btn-primary', 'tabindex' => '5'])  !!}
                </div>
            </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion_" data-target="#task-details" aria-expanded="true">
        <h4 class="panel-title">Task List
            <a data-toggle="modal" href="#add_task">
                <button class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
            </a>
        </h4>
    </div>
    <div id="task-details" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
        <div class="panel-body">
            <table class="table table-hover table-striped">
                @if(count($tasks) > 0)
                    @foreach($tasks as $task)
                        @if((Auth::user()->is('client') && $task->is_visible == 'yes') ||
                             !Auth::user()->is('client'))

                            <tr>
                                <td>{{ $task->task_title }}</td>
                                <td>{{ date("d M Y",strtotime($task->due_date)) }}</td>
                                <td>{{ $task->name }}</td>
                                @if(!Auth::user()->is('Client'))
                                    <td class="text-right">
                                        <a href="{{ route('task.edit',$task->task_id) }}" class="show_edit_form"><i class="fa fa-2x fa-pencil"></i></a>
                                        <a href="{{ route('project.destroy',$task->task_id) }}" class="alert_delete"><i class="fa fa-2x fa-trash-o"></i></a>
                                    </td>
                                @endif
                            </tr>

                        @endif
                    @endforeach
                @else
                     <tr>
                        <td colspan="6">No data was found.</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</div>
