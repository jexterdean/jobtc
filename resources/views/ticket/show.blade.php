@extends('layouts.default')
	@section('content')

			<div class="row">
				<div class="col-md-12">
	                <div class="nav-tabs-custom">
	                    <ul class="nav nav-tabs">
	                        <li class="active"><a href="#tab_1" data-toggle="tab">Details</a></li>
	                        <li><a href="#tab_2" data-toggle="tab">Attachments</a></li>
	                        <li><a href="#tab_3" data-toggle="tab">Task</a></li>
	                        <li class="dropdown pull-right">
	                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
	                                Options <span class="caret"></span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ url('ticket') }}">Back</a></li>
	                                <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ url('ticket/'.$ticket->ticket_id.'/delete') }}">Delete</a></li>
	                            </ul>
	                        </li>
	                    </ul>
	                    <div class="tab-content">
	                        <div class="tab-pane active" id="tab_1">
								<div class="row">
							        <div class="col-md-6">
							            <div class="box box-solid box-primary">
							                <div class="box-header">
							                    <h3 class="box-title">Ticekt Detail</h3>
							                </div>
											<div class="box-body">
												<div class="row static-info">
													<div class="col-md-5 name">
														 User:
													</div>
													<div class="col-md-7 value">
														 {{ $ticket->username }} 
														 ({{ $users[$ticket->username] }})
													</div>
												</div>
												<div class="row static-info">
													<div class="col-md-5 name">
														 Description:
													</div>
													<div class="col-md-7 value">
														 {{ $ticket->ticket_description }}
													</div>
												</div>
												<div class="row static-info">
													<div class="col-md-5 name">
														 Priority:
													</div>
													<div class="col-md-7 value">
														 {{ $ticket->ticket_priority }}
													</div>
												</div>
												<div class="row static-info">
													<div class="col-md-5 name">
														 Status:
													</div>
													<div class="col-md-7 value">
														 @if(!Entrust::hasRole('Client'))
															{{ Form::open(['method' => 'POST','url' => 'updateTicketStatus','class' => 'form-horizontal']) }}
																 {{ Form::select('ticket_status', [
																	'open' => 'Open',
																	'close' => 'Close'
													 			], isset($ticket->ticket_status) ? $ticket->ticket_status : '', ['class' => 'form-control', 'placeholder' => 'Select One', "onchange" => "this.form.submit()"] ) }}
															{{ Form::hidden('ticket_id',$ticket->ticket_id)}}
															{{ Form::close() }}	 
														@else
															{{ studly_case($ticket->ticket_status) }}
														@endif
													</div>
												</div>
												<div class="row static-info">
													<div class="col-md-5 name">
														 Date Logged:
													</div>
													<div class="col-md-7 value">
														 {{ date("d M Y", strtotime($ticket->created_at)) }}
													</div>
												</div>
											</div>
										</div>

										@include('common.note',['note' => $note, 'belongs_to' => 'ticket', 'unique_id' => $ticket->ticket_id])

									</div>

									@if(Entrust::hasRole('Admin'))
										@include('common.assign',['assignedUsers' => $assignedUsers, 'belongs_to' => 'ticket', 'unique_id' => $ticket->ticket_id])
									@endif	

									@include('common.comment',['comments' => $comments, 'belongs_to' => 'ticket', 'unique_id' => $ticket->ticket_id])
								</div>
							</div>
	                        <div class="tab-pane" id="tab_2">
	                        	@include('common.attachment',['attachments' => $attachments, 'belongs_to' => 'ticket', 'unique_id' => $ticket->ticket_id])
	                        </div>
	                        <div class="tab-pane" id="tab_3">
	                        	@include('common.task',['tasks' => $tasks, 'belongs_to' => 'ticket', 'unique_id' => $ticket->ticket_id])
	                        </div>
	                    </div>
	                </div>
				</div>
			</div>
	@stop