@extends('layouts.app')
@section('content')


<div class="main-content">
    <div class="header fondo-color pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
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
                                <h3 class="mb-0">Bandeja de entrada</h3>
                            </div>
                            <div class="col-4 text-right">
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


                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
                                            <span class="fa fa-filter" aria-hidden="true"></span>

                                            <span class="caret"></span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" onclick="filtar('Pendiente')" href="#"><span class="badge badge-danger">
                                                    Pendiente</span></a>
                                            <a class="dropdown-item" onclick="filtar('Atendido')" href="#"><span class="badge badge-info"> Atendido</span></a>
                                            <a class="dropdown-item" onclick="filtar('Rechazado')" href="#"><span class="badge badge-success">
                                                    Rechazado</span></a>
                                            <a class="dropdown-item" onclick="filtar('Turnado')" href="#"><span class="badge badge-warning">
                                                    Turnado</span></a>
                                        </div>
                                        <div class="mb-0">
                                            <a style="display:none" class="dropdown-item" id="filtroPendiente" onclick="quitarFiltro('Pendiente')" href="#"><span class="badge badge-danger">
                                                    Pendiente X</span></a>
                                        </div>
                                        <div class="mb-0">
                                            <a style="display:none" class="dropdown-item" id="filtroAtendido" onclick="quitarFiltro('Atendido')" href="#"><span class="badge badge-info"> Atendido X</span></a>
                                        </div>
                                        <div class="mb-0">
                                            <a style="display:none" class="dropdown-item" id="filtroRechazado" onclick="quitarFiltro('Rechazado')" href="#"><span class="badge badge-success">
                                                    Rechazado X</span></a>
                                        </div>
                                        <div class="mb-0">
                                            <a style="display:none" class="dropdown-item" id="filtroTurnado" onclick="quitarFiltro('Turnado')" href="#"><span class="badge badge-warning">
                                                    Turnado X</span></a>
                                        </div>

                                    </div>
                                </div>

                                <br>
                                @if($director)
                                @include('servicios.bandejas.bandejaEntradaDirector')
                                @else
                                @include('servicios.bandejas.bandejaEntradaJefe')
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