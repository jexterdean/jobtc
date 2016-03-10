
	
	<div class="row">
		@if(!Entrust::hasRole('Client'))
		<div class="col-md-4">
		    <div class="box box-solid box-primary">
		        <div class="box-header">
		            <h3 class="box-title">Add Task</h3>
		        </div>
				<div class="box-body">
					{{ Form::open(['method' => 'POST','route' => ['task.store'],'class' => 'task-form']) }}
					{{ Form::hidden('belongs_to',$belongs_to) }}
					{{ Form::hidden('unique_id', $unique_id) }}
						<div class="form-group">
							{{ Form::input('text','task_title','',['class' => 'form-control', 'placeholder' => 'Enter Title', 'tabindex' => '1'])}}
						</div>
						<div class="form-group">
							{{ Form::textarea('task_description','',['size' => '30x3', 'class' => 'form-control', 'placeholder' => 'Enter Description', 'tabindex' => '2'])}}
						</div>
						<div class="form-group">
							{{ Form::input('text','due_date','',['class' => 'form-control form-control-inline input-medium date-picker', 'placeholder' => 'Enter Due Date', 'tabindex' => '3', 'data-inputmask' => "'alias': 'dd-mm-yyyy'", 'data-mask' => 'true']) }}
						</div>
						@if(Entrust::hasRole('Admin'))
						<div class="form-group">
							{{ Form::select('assign_username', [null=>'Assign to user'] + $assign_username, isset($task->assign_username) ? $task->assign_username : '', ['class' => 'form-control input-xlarge select2me', 'placeholder' => 'Select One', 'tabindex' => '3'] ) }}
						</div>
						@elseif(Entrust::hasRole('Staff'))
						{{ Form::hidden('assign_username',Auth::user()->username,['readonly' => true]) }}
						@endif

						@if($belongs_to != 'general')
						<div class="form-group">
							<label> Visible to Client </label>
							<div class="radio-list">
								<label class="radio-inline">
								{{ Form::radio('is_visible','yes',true) }} Yes</label>
								<label class="radio-inline">
								{{ Form::radio('is_visible','no') }} No </label>
							</div>
						</div>
						@else
						{{ Form::hidden('is_visible','no',['readonly' => true]) }}
						@endif

						<div class="form-group">
							{{ Form::submit('Add',['class' => 'btn btn-primary', 'tabindex' => '5']) }}
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		@endif
		<div class="col-md-8">
		    <div class="box box-solid box-primary">
		        <div class="box-header">
		            <h3 class="box-title">Task List</h3>
		        </div>
				<div class="box-body">
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
						@if(!Entrust::hasRole('Client'))
						<th>
							Option
						</th>
						@endif
					</tr>
					</thead>
					<tbody>
						@if($tasks!='')
						@foreach($tasks as $task)

							@if((Entrust::hasRole('Client') && $task->is_visible == 'yes') || !Entrust::hasRole('Client'))

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
									@if(!Entrust::hasRole('Client'))
									{{ Form::open(['method' => 'POST','url' => 'updateTaskStatus','class' => 'form-horizontal']) }}
									{{ Form::select('task_status', [
										'pending' => 'Pending',
										'progress' => 'Progress',
										'completed' => 'Completed'
									], $task->task_status, ['class' => 'form-control', 'placeholder' => 'Select One', "onchange" => "this.form.submit()"] ) }}
									{{ Form::hidden('task_id',$task->task_id)}}
									{{ Form::close() }}
									@else
									{{ studly_case($task->task_status) }}
									@endif
								</td>
								
								@if(!Entrust::hasRole('Client'))
								<td>
									<a href="{{ url($task->belongs_to.'/'.$task->unique_id) }}"><i class="fa fa-external-link"></i></a>
									<span class="hspacer"></span>
									{{ Form::open(array('route' => array('task.destroy', $task->task_id), 'method' => 'delete')) }}
								        <button type="submit" class="btn btn-danger btn-sm"><i class='fa fa-trash-o'></i></button>
								    {{ Form::close() }}
								</td>
								@endif
							</tr>

							@endif
						@endforeach
						@endif
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
