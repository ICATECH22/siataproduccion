@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Organos') ,
'description' => __('Modulo para editar informacion de organos'),
'class' => 'col-lg-12'
])
<div class="container-fluid mt--7">
    <div class="row">

        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">{{ __('Editar Organo') }}</h3>
                    </div>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-3 col-sm-4">
                        <div class="form-group">
                            <h6 class="required">* : campos obligatorios</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-content" id="creacion" action="{{ route('organosActualizar', $unidad->id) }}" method="POST">
                        @csrf
                        <div class="form-group{{ $errors->has('area') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('area') ? ' is-invalid' : '' }}" placeholder="{{ __('Nombre Departamento') }}" type="text" name="area" 
                                value="{{ old('area',$unidad->area) }}"  autofocus>
                            </div>
                            @if ($errors->has('area'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('area') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('titular') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('titular') ? ' is-invalid' : '' }}" placeholder="{{ __('Titular') }}" type="text" name="titular" 
                                value="{{ old('titular', $unidad->titular) }}"  autofocus>
                            </div>
                            @if ($errors->has('titular'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('titular') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('puesto') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('puesto') ? ' is-invalid' : '' }}" placeholder="{{ __('Puesto del Titular') }}" type="text" name="puesto" value="{{ old('puesto', $unidad->puesto) }}"  autofocus>
                            </div>
                            @if ($errors->has('puesto'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('puesto') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Correo Electronico') }}" type="email" name="email" value="{{ old('email', $unidad->correo) }}" >
                            </div>
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
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
                                        <input class="form-control{{ $errors->has('telefono') ? ' is-invalid' : '' }}" placeholder="{{ __('Telefono') }}" type="text" name="telefono" value="{{ old('telefono', $unidad->telefono) }}"  autofocus>
                                    </div>
                                    @if ($errors->has('telefono'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('telefono') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('celular') ? ' has-danger' : '' }}">
                                    <div class="input-group input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                        </div>
                                        <input class="form-control{{ $errors->has('celular') ? ' is-invalid' : '' }}" placeholder="{{ __('celular') }}" type="text" name="celular" value="{{ old('celular', $unidad->celular) }}"  autofocus>
                                    </div>
                                    @if ($errors->has('celular'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('celular') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('direccion') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-5">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <div class="form-row col-10 col-sm-10">
                                    <label for="to" class="col-2 col-sm-2 col-form-label">Direccion:</label>
                                    <div class="col-8 col-sm-8">
                                        <select style="width:100%;" class="form-control @error('direccion')  is-invalid @enderror" aria-label=".form-select-md example" name="direccion">
                                            <option class="form-control form-control-alternative{{ $errors->has('idUnidad') ? ' is-invalid' : '' }}" selected="true" disabled="disabled">Selecciona la direccion a la que pertenece esta unidad</option>

                                            @foreach ($direcciones as $direccion)
                                            <option {{$direccion->id == $unidad->idparent ? 'selected' : ''}} name="id" value="{{ $direccion->id }}" style="background-color: #3332;">Unidad: {{ $direccion->area }}</option>
                                            @endforeach
                                        </select>


                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('direccion'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('direccion') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-md-6 col-sm-2 col align-self-end">
                                <a type="button" href="{{route('departamentosLista')}}" class="btn btn-danger mt-4">{{ __('Cancelar') }}</a>
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