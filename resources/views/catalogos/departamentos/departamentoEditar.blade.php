@extends('layouts.app', ['title' => __('User Profile')])

@section('content')

@include('users.partials.header', [
'title' => __('Editar informacion de un departamento: ') ,
'description' => __('Escriba el nombre del departamento y luego seleccione la unidad a la que pertenece'),
'class' => 'col-lg-12'
])

<div class="row justify-content-end">
    <div class="col-md-3 col-sm-4">
        <div class="form-group">
            <h6 class="required">* : campos obligatorios</h6>
        </div>
    </div>
</div>

<form id="creacion" action="{{ route('departamentoUpdate', $departamento->idDepartamento) }}" method="POST">

    @csrf
    <div class="form-content">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-4">
                <div class="form-group">
                    <label for="">Descripción <span class="required">*</span></label>
                    <input type="text" name="descripcion" value="{{old('descripcion', $departamento->descripcion)}}" class="form-control @error('descripcion')  is-invalid @enderror" placeholder="Descripción">
                    @error('descripcion')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-4">
                <div class="form-group">
                    <label for="">Unidad <span class="required">*</span></label>
                    <select class="form-select form-select-md @error('idUnidad')  is-invalid @enderror" aria-label=".form-select-md example" name="idUnidad">
                        <option selected="true" disabled="disabled">Selecciona la unidad a la que pertenece</option>
                        @if(!empty($unidades))
                        @forelse($unidades as $unidad)
                        <option name="idUnidad" value="{{ $unidad->idUnidad }}" {{$unidad->idUnidad == $departamento->idUnidad ? 'selected' : ''}}>{{ $unidad->descripcion }}</option>
                        @empty
                        <p> No hay Adscripciones para mostrar</p>
                        @endforelse
                        @endif
                    </select>
                    @error('idUnidad')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-6 col-sm-2 col align-self-end">

                <!-- <button type="button" class="btnCancel"><a href="" class="btnCancel">Cancelar</a></button> -->
                <a type="button" href="{{route('departamentosLista')}}" class="btn btn-danger mt-4">{{ __('Cancelar') }}</a>
                <button onclick="creacion()" class="btn btn-success mt-4">{{ __('Guardar') }}</button>

            </div>
        </div>
    </div>
    <br><br>



</form>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    function creacion() {
        event.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de actualizar este registro?',
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