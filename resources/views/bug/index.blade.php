@extends('layouts.default')
	@section('content')
		<div class="modal fade" id="add_bug" tabindex="-1" role="basic" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Add Bug</h4>
					</div>
					<div class="modal-body">
					@if(Entrust::hasRole('Admin'))
						{{ Form::open(['route' => 'bug.store','class' => 'form-horizontal bug-form']) }}
						@include('bug/partials/_form')
						{{ Form::close() }}
					@else
					<div class='alert alert-danger alert-dismissable'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
						<strong>You dont have to perform this action!!</strong>
					</div>
					@endif
					</div>
				</div>
			</div>
		</div>


		<div class="modal fade" id="ajax" role="basic" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				</div>
			</div>
		</div>


		<div class="col-md-12">
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <h3 class="box-title">Bug List</h3>
                    <div class="box-tools pull-right">
                        <a data-toggle="modal" href="#add_bug">
                        	<button class="btn btn-sm"><i class="fa fa-plus-circle"></i> Add New Bug</button>
                        </a>
                    	<button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">

					<?php $DATA=array();
					$QA=array();
					foreach ($bugs as $bug){
						$linkToEdit = "<a href='bug/$bug->bug_id/edit' data-toggle='modal' data-target='#ajax'> <i class='fa fa-edit'></i> </a>";
						$linkToView = "<a href='bug/$bug->bug_id'><i class='fa fa-external-link'></i></a>";
						$linkToDelete = "<a href='bug/$bug->bug_id/delete' class='alert_delete'> <i class='fa fa-trash-o'></i> </a>";
						$Option = "$linkToView <span class='hspacer'></span> $linkToEdit <span class='hspacer'></span> $linkToDelete";
						$QA[] = array($bug->ref_no,isset($projects[$bug->project_id]) ? $projects[$bug->project_id] : '',date("d M Y h:ia",strtotime($bug->reported_on)),$bug->bug_priority,$bug->bug_status,$Option);	
					}
					
					$DATA['aaData'] = $QA;
					$fp = fopen('data.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp); ?>
					<table class="table table-striped table-bordered table-hover datatableclass" id="project_table">
					<thead>
					<tr>
						<th>
							Ref No
						</th>
						<th>
							Project Title
						</th>
						<th>
							 Reported On
						</th>
						<th>
							 Priority
						</th>	
						<th>
							 Status
						</th>	
						<th>
							 Option
						</th>	
					</tr>
					</thead>
					<tbody>
					</tbody>
					</table>
				</div>
                <div style="clear:both;"></div>
			</div>
		</div>
	@stop