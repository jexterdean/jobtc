@include('layouts.head')
<body class="skin-black">

@include('layouts.header')

<div class="wrapper row-offcanvas row-offcanvas-left">

    <section class="content">
        {{ \App\Helpers\Helper::showMessage() }}
        @yield('content')
    </section>
</div>

@include('layouts.foot')