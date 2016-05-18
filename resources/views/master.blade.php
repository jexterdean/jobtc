<?php
$assets = [];
?>
@include('layouts.head')
<body>

@include('layouts.header')

<div class="wrapper row-offcanvas row-offcanvas-left">
    <section class="content">
        {{ \App\Helpers\Helper::showMessage() }}

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
            @yield('content')
            </div>
            <div class="col-md-1"></div>
        </div>
    </section>
</div>

@include('layouts.foot')