@extends('layouts.default')

@section('content')
@parent
@include('quiz.' . $page)
@stop