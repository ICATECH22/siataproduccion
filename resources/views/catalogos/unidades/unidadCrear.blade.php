@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Nueva Unidad ') ,
'description' => __('Modulo para crear Unidades de Capacitacion'),
'class' => 'col-lg-12'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><a href="{{route('unidadesLista')}}" class="back-button">
                                    <i class="fas fa-chevron-left"></i>
                                </a>Regresar a Lista Unidades</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('unidadesGuardar') }}" autocomplete="off" id="creacion">
                        @csrf
                        <div class="form-group{{ $errors->has('unidad') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-building"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('unidad') ? ' is-invalid' : '' }}" placeholder="{{ __('Nombre Unidad') }}" type="text" name="unidad" value="{{ old('unidad') }}" required autofocus>
                            </div>
                            @if ($errors->has('unidad'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('unidad') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('direccion') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-map-big"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('direccion') ? ' is-invalid' : '' }}" placeholder="{{ __('Direccion de la Unidad') }}" type="direccion" name="direccion" value="{{ old('direccion') }}" required>
                            </div>
                            @if ($errors->has('direccion'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('direccion') }}</strong>
                            </span>
                            @endif
                        </div>


                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('telefono') ? ' has-danger' : '' }}">
                                    <div class="input-group input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-tablet-button"></i></span>
                                        </div>
                                        <input class="form-control{{ $errors->has('telefono') ? ' is-invalid' : '' }}" placeholder="{{ __('Telefono') }}" type="text" name="telefono" value="{{ old('telefono') }}" required autofocus>
                                    </div>
                                    @if ($errors->has('telefono'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('telefono') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-6 col-sm-2 col align-self-end">
                                <a type="button" href="{{route('unidadesLista')}}" class="btn btn-danger mt-4">{{ __('Cancelar') }}</a>
                                <a type="button" onclick="creacion()" class="btn btn-success mt-4">{{ __('Guardar') }}</a>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function creacion() {
        event.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de guardar este registro?',
            imageUrl: '/images/iconos/exclamacion.PNG',
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            showCancelButton: true,
            cancelButtonColor: '#656665',
            confirmButtonColor: '#4897D0',
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('creacion').submit();
            }
        })
    }
</script>
@endsection