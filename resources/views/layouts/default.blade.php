@include('layouts.head')
    <body class="skin-black">

	@include('layouts.header')

        <div class="wrapper row-offcanvas row-offcanvas-left">

		@include('layouts.sidebar')

            <aside class="right-side">

                <section class="content">
					{{ Helper::showMessage() }}
						@yield('content')
                </section>
            </aside>
        </div>

@include('layouts.foot')