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
                                <h3 class="mb-0">Servicios Turnados</h3>
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

                                <br>
                                @if($director)
                                    @include('servicios.bandejas.bandejaEntradaDirector')
                                @else
                                    @include('servicios.bandejas.bandejaTurnadojefe')
                                @endif
                            </main>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('contenidoJavaScript')
@endsection
