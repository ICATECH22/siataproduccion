<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema de asistencia tecnica</title>
    <!-- Favicon -->
    <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Extra details for Live View on GitHub Pages -->

    <!-- Icons -->
    <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">

    <!-- custom css '(btn seleccionar archivo en vista servicioCrear)' -->
    <link type="text/css" href="{{ asset('argon') }}/css/component.css" rel="stylesheet">
    <link type="text/css" href="{{ asset('argon') }}/css/login.css" rel="stylesheet">
    <link type="text/css" href="{{ asset('argon') }}/css/demo.css" rel="stylesheet">
    <link type="text/css" href="{{ asset('argon') }}/css/normalize.css" rel="stylesheet">
    <link type="text/css" href="{{ asset('argon') }}/css/inbox.css" rel="stylesheet">
    <link type="text/css" href="{{ asset('argon') }}/css/email.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script><!-- tabs  -->

    <!-- <link type="text/css" href="{{ asset('argon') }}/css/input-slide.css" rel="stylesheet"> -->
    <!-- inbox css -->
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" defer></script>


    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" />
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>



</head>

<body class="{{ $class ?? '' }}">
    @auth()
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @include('layouts.navbars.sidebar')
    @endauth

    <div class="main-content">
        @include('layouts.navbars.navbar')
        @yield('content')
    </div>

    @guest()
    @include('layouts.footers.guest')
    @endguest


    <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    @stack('js')


    <!-- jquery -->

    <!-- Argon JS -->
    <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
    <!-- custom js boton agregar archivo-->
    <script src="{{ asset('argon') }}/js/custom-file-input.js"></script>

    <!-- alerts"(modals)" -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- alerts"(ocultar automaticamente pop up's)" -->
    <script src="{{ asset('argon') }}/js/alerts.js" defer></script>
    <script src="{{ asset('argon') }}/js/ranking.js" defer></script>

</body>

</html>