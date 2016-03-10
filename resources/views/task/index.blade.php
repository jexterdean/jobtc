@extends('layouts.default')
	@section('content')
		@include('common.task',['tasks' => $tasks, 'belongs_to' => 'general', 'unique_id' => '0'])
	@stop