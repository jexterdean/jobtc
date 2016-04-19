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
                @role('admin')
                    <div class="form-group">
                        {!!  Form::select('assign_username', $assign_username, isset
                        ($task->assign_username) ? $task->assign_username : '', ['class' => 'form-control input-xlarge select2me',
                        'placeholder' => 'Assign User', 'tabindex' => '3'] )  !!}
                    </div>
                @endrole
                @role('staff')
                    {!!  Form::hidden('assign_username',Auth::user()->username,['readonly' => true])  !!}
                @endrole
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
<div class="box box-default">
    <div class="box-header">
        <h3 class="box-title">Task List</h3>
        <div class="box-tools pull-right">
            <a data-toggle="modal" href="#add_task">
                <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add Task</button>
            </a>
            <button class="btn btn-sm btn-transparent" data-widget="collapse"><i class="fa fa-chevron-up"></i></button>
        </div>
    </div>
    <div class="box-body">
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
                                    <a href="{{ url('task/'.$task->task_id) }}"><i class="fa fa-2x fa-external-link"></i></a>
                                    <a href="{{ route('task.update',$task->task_id) }}"><i class="fa fa-2x fa-pencil"></i></a>
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
