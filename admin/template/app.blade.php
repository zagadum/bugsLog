<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pressure Dx') }}</title>

    <!-- Styles -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/select2.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="/js/jquery-1.11.3.min.js"></script>
    <script src="/js/jquery.mask.min.js"></script>

    <script src="/js/app.js"></script>
    <script src="/js/select2.min.js"></script>
    <script src="/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/datatables-bs/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select').select2({
                minimumResultsForSearch: -1
            });
        });
    </script>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div class="main">
        <header class="header">
            <div class="container-fluid">
                <?php
                    $url_arr = explode('/', Route::getCurrentRoute()->getPath());
                    $main_url = '/';
                    if(isset($url_arr[0])) {
                        if($url_arr[0] === "staff") {
                            $main_url = url('/staff/main');
                            $area_url = url('/adm/main');
                            $cur_area_text = 'Staff';
                            $area_text = 'Admin';
                        } elseif($url_arr[0] === "adm") {
                            $main_url = url('/adm/main');
                            $area_url = url('/staff/main');
                            $cur_area_text = 'Admin';
                            $area_text = 'Staff';
                        }
                    }
                ?>
                <a class="navbar-brand" href="{{ $main_url }}">
                    <img src="{{ url('/images/logo.png') }}" alt="logo" width="131" height="16">
                </a>

                <div class="pull-left">
                    <a class="btn btn-default" type="button" href="{{ $main_url }}"><span class="icon-home"></span></a>
                    <button class="btn btn-default" type="button" onclick="javascript:window.history.back(-1);"
                    @if($main_url === url(Route::getCurrentRoute()->getPath()))
                        disabled
                    @endif
                    ><span class="icon-arrow-back"></span></button>
                </div>

                <div class="pull-right">
				  @if (!Auth::guest())
                    <span class="user-name">{{ Auth::user()->first_name }} ({{ $cur_area_text }})</span>

					                    {{--<div class="dropdown pull-right">--}}
                        {{--<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">--}}
                            {{--<span class="icon-menu">Dev</span>--}}
                        {{--</button>--}}
						{{--<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">--}}
						  {{--<li><a href="add_user">- add user </a></li>--}}
						  {{--<li><a href="/staff/diagnosis-do">- diagnosis-visualisation</a></li>--}}
						  {{--<li><a href="/staff/diagnosis-result">- diagnosis-result</a></li>--}}
						  {{--<li><a href="/staff/diagnosis-reject">- diagnosis workflow</a></li>--}}

						  {{--<li><a href="/staff/history">- history</a></li>--}}
						  {{--<li><a href="/staff/diagnosis-assess">- questionary</a></li>--}}
						     {{--<li><a href="#">User History</a></li>--}}
						 {{--</ul>--}}
						{{--</div>--}}
@endif
                    <div class="dropdown pull-right">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="icon-menu"></span>
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            @if (Auth::guest())
                                <li><a href="{{ url('/login') }}">Login</a></li>
                                <li><a href="{{ url('/adm/main') }}">Main</a></li>
                            @else
                                <li class="dropdown">
                                    @if (Auth::user()->prev === "1")
                                        <li>
                                            <a style="color:red" href="{{ $area_url }}">{{ $area_text }}</a>
                                        </li>
                                    @endif
                                        <li>
                                            <a href="{{ url('/adm/user_edit?id=') . Auth::user()->id }}">
                                                Configure
                                            </a>
                                        </li>
										   <li><a href="{{ url('/staff/user-history') }}">User History</a></li>
                                        <li>
                                            <a href="{{ url('/logout') }}"
                                               onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')
    </div>

    <footer class="footer">
        <span class="copyright">© 2016 Right &amp; Above</span>
    </footer>

    @yield('bottom_content')

    @yield('scripts')
    @yield('bottom_scripts')
</body>
</html>
