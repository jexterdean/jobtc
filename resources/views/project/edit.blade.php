
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">Edit Project</h4>
	</div>
	<div class="modal-body">
		@if(Entrust::hasRole('Admin'))
		{{ Form::model($project,['method' => 'PATCH','route' => ['project.update',$project->project_id] ,'class' => 'form-horizontal project-form']) }}
			@include('project/partials/_form', ['buttonText' => 'Update Project'] )
		{{ Form::close() }}
		@else
		<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong>You dont have to perform this action!!</strong>
		</div>
		@endif
	</div>
	<script>
	$(function() {
  	 Validate.init();
    });
	</script>