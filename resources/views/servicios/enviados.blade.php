@extends('layouts.app')
@section('content')
<div class="main-content">
    <!-- Top navbar -->

    <div class="header fondo-color pb-8 pt-5 pt-md-8">
        <div class="container-fluid">

            <div class="header-body">
                <!-- Card stats -->

            </div>
        </div>
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Enviados</h3>
                            </div>
                            <div class="col-4 text-right">
                                <!-- <a href="{{ route('crearServicio') }}" class="btn btn-sm btn-primary">Solicitar nuevo servicio</a> -->
                                <a href="{{ route('crearServicio') }}" class="btn btn-danger btn-block">Nuevo servicio</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                    </div>

                    @if (session()->has('message'))
                    <div class="alert {{session('alert') ?? 'alert-info'}}">
                        {{session('message')}}
                        {{session()->forget('message')}}
                    </div>
                    @endif

                    <div class="container bootdey">
                        <div class="email-app mb-4">

                            <main class="inbox">
                                <div class="toolbar">

                                    <br>
                                    @if($director)
                                    @include('servicios.bandejas.bandejaEnviadosDirector')
                                    @else
                                    @include('servicios.bandejas.bandejaEnviadosJefe')
                                    @endif


                            </main>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function verAtencion(idSolicitud) {
        window.location = 'enviados/detalles/' + idSolicitud;
    }

    function filtar(filtro) {

        var target = '.solicitud';

        $(target + '.' + filtro).css('display', 'block');
        $(target).not('.' + filtro).css('display', 'none');
        $('#filtro' + filtro).css('display', 'block');
    }

    function quitarFiltro(filtro) {
        $('#filtro' + filtro).css('display', 'none');

        var target = '.solicitud';
        $(target).not('.' + filtro).css('display', 'block');
    }
</script>
@endsection