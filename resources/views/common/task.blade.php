@if(!Auth::user()->is('Client'))
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Add Task</h3>
        </div>
        <div class="panel-body">
            {!!  Form::open(['method' => 'POST','route' => ['task.store'],'class' => 'task-form'])  !!}
            {!!  Form::hidden('belongs_to',$belongs_to)  !!}
            {!!  Form::hidden('unique_id', $unique_id)  !!}
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
            @endif

            @if($belongs_to != 'general')
                <div class="form-group">
                    <label> Visible to Client </label>
                    <div class="radio-list">
                        <label class="radio-inline">
                            {!!  Form::radio('is_visible','yes',true) !!} Yes</label>
                        <label class="radio-inline">
                            {!! Form::radio('is_visible','no')  !!} No </label>
                    </div>
                </div>
            @else
                {!!  Form::hidden('is_visible','no',['readonly' => true]) !!}
            @endif

            <div class="form-group">
                {!!  Form::submit('Add',['class' => 'btn btn-primary', 'tabindex' => '5'])  !!}
            </div>
            {!!  Form::close()  !!}
        </div>
    </div>
@endif
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Task List</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>
                    Title
                </th>
                <th>
                    Description
                </th>
                <th>
                    Belongs to
                </th>
                <th>
                    Due Date
                </th>
                <th>
                    Status
                </th>
                @if(!Auth::user()->is('client'))
                    <th>
                        Option
                    </th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if(count($tasks) > 0)
                @foreach($tasks as $task)

                    @if((Auth::user()->is('client') && $task->is_visible == 'yes') ||
                         !Auth::user()->is('client'))

                        <tr>
                            <td>{{ $task->task_title }}
                                @if($task->is_visible === 'yes')
                                    <span class="badge bg-green">Visible to client</span>
                                @endif
                            </td>
                            <td>{{ $task->task_description }}</td>
                            <td>{{ studly_case($task->belongs_to) }}</td>
                            <td>{{ date("d M Y",strtotime($task->due_date)) }}</td>
                            <td>
                                @role('client')
                                    {{ studly_case($task->task_status) }}
                                @else
                                    {!!  Form::open(['method' => 'POST','url' => 'updateTaskStatus','class' =>
                                     'form-horizontal'])  !!}
                                    {!!  Form::select('task_status', [
                                        'pending' => 'Pending',
                                        'progress' => 'Progress',
                                        'completed' => 'Completed'
                                    ], $task->task_status, ['class' => 'form-control',
                                    'placeholder' => 'Update Task',
                                    "onchange" => "this.form.submit()"] )  !!}
                                    {!!  Form::hidden('task_id',$task->task_id) !!}
                                    {!!  Form::close()  !!}
                                @endif
                            </td>

                            @if(!Auth::user()->is('Client'))
                                <td>
                                    <a href="{{ url($task->belongs_to.'/'.$task->unique_id) }}"><i
                                                class="fa fa-external-link"></i></a>
                                    <span class="hspacer"></span>
                                    {!!  Form::open(array('route' => array('task.destroy', $task->task_id),
                                    'method' => 'delete'))  !!}
                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                class='fa fa-trash-o'></i></button>
                                    {!!  Form::close()  !!}
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
            </tbody>
        </table>
    </div>
</div>
