<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Psicometria - @yield('title')</title>

    <!-- Bootstrap Core CSS -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" integrity="sha512-4uGZHpbDliNxiAv/QzZNo/yb2FtAX+qiDb7ypBWiEdJQX8Pugp8M6il5SRkN8jQrDLWsh3rrPDSXRf3DwFYM6g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,300,400,700" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="/css/agency.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    html {
        position: relative;
        min-height: 100%;
    }
    body {
        margin-bottom: 90px; /* Margin bottom by footer height */
    }
    .sticky-footer {
        position: absolute;
        width: 100%;
        bottom: 0;
        height: 90px; /* Set the fixed height of the footer here */
    }
    .navbar-custom .navbar-nav > li > a:hover,
    .navbar-custom .navbar-nav > li > a:active {
        background-color: transparent;
        color: #f05e3a;
    }
    .navbar-custom .navbar-nav > .active > a {
        background-color: transparent;
        color: #f05e3a;
    }
    .navbar-custom .navbar-nav > .active > a:hover,
    .navbar-custom .navbar-nav > .active > a:active {
        background-color: transparent;
        color: #f05e3a;
    }
    </style>
</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-primary navbar-custom navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="{{ route('admin_home') }}"><span class="text-muted">eNe</span>+</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="{{ (strpos(Route::currentRouteName(), 'admin_exam_list') === 0) ? 'active' : '' }}">
                        <a href="{{ route('admin_exam_list') }}">Evaluaciones</a>
                    </li>
                    <li class="{{ (strpos(Route::currentRouteName(), 'admin_create_link') === 0) ? 'active' : '' }}">
                        <a href="{{ route('admin_create_link') }}">Crear link</a>
                    </li>
                    <!-- Authentication Links -->
                    @guest
                        <li>
                            <a href="{{ route('login') }}">Iniciar sesión</a>
                        </li>
                    @else
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg width="14" height="14" viewBox="0 0 24 24" stroke="#666" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" fill="none">
                                    <circle cx="12" cy="8" r="5" />
                                    <path d="M3,21 h18 C 21,12 3,12 3,21"/>
                                </svg> {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar sesión</a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="sticky-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-left">
                    <span class="copyright">Copyright &copy; eNe Consultora 2022</span>
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-4 text-right">
                  <span class="copyright"><a href="https://www.propositiva.com.ar" target="_blank" rel="noreferrer noopener"><img src="https://turnos.tdaanodizado.com.ar/images/propositiva-white.png" alt="Propositiva | Consultora IT" height="30" /></a></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://www.eneconsultora.com/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="https://www.eneconsultora.com/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    @yield('extrajs')
</body>

</html>
