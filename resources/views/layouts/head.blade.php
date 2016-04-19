<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Freelance Plus</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"
=======
    {!!  HTML::style('assets/css/bootstrap.css')  !!}
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"
>>>>>>> 7961e7ff7602b9e3394a2c9c4880dfe48422af76
=======
    {!!  HTML::style('assets/css/bootstrap.css')  !!}
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"
=======
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"
>>>>>>> 9c35634d6341f4119334b566861bca0dd430be62
>>>>>>> project_update
=======
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"
>>>>>>> project-merge-04-19-2016
          type="text/css"/>
    <link href="http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <link href="http://fullcalendar.io/js/fullcalendar-2.6.1/lib/cupertino/jquery-ui.min.css" rel="stylesheet"
              type="text/css"/>

    {!!  HTML::style('assets/css/bootstrap.css')  !!}
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css"/>

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
