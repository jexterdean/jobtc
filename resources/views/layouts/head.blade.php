<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Freelance Plus</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    {!!  HTML::style('assets/css/bootstrap.css')  !!}
    {!!  HTML::style('assets/css/font-awesome.min.css')  !!}
    {!!  HTML::style('assets/css/ionicons.min.css')  !!}
    {!!  HTML::style('assets/css/jquery-ui.min.css')  !!}

    {!!  HTML::style('assets/custom.css')  !!}
    {!! HTML::style('assets/css/AdminLTE.css')  !!}
    {{--{!!  HTML::style('assets/css/app.css')  !!}--}}

        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    @if(in_array('table',$assets))
        {!!  HTML::style('assets/css/datatables/dataTables.bootstrap.css')  !!}
        {!!  HTML::style('assets/css/datatables/dataTables.tableTools.css')  !!}
        {!!  HTML::style('assets/css/datatables/dataTables.colVis.css')  !!}
        {!!  HTML::style('assets/css/datatables/dataTables.colReorder.css')  !!}
    @endif

    @if(in_array('calendar',$assets))
        {!!  HTML::style('assets/css/fullcalendar.css')  !!}
        {!! HTML::style('assets/css/bootstrap-datetimepicker.min.css') !!}
    @endif

    @if(in_array('select',$assets))
        {!!  HTML::style('assets/css/bootstrap-select.css') !!}
    @endif

    @if(in_array('magicSuggest',$assets))
        {!!  HTML::style('assets/css/magicsuggest-min.css') !!}
    @endif

    {!!  HTML::style('assets/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') !!}

    <script> var public_path = "{{ URL::to('/') }}/"; </script>
</head>