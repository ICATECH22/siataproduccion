@extends('layouts.app', ['title' => __('Editar Información del Departamento')])

@section('content')

@include('users.partials.header', [
'title' => __('Editar informacion de un departamento: ') ,
'description' => __('Escriba el nombre del departamento y luego seleccione la unidad a la que pertenece'),
'class' => 'col-lg-12'
])


<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><a href="{{route('departamentosLista')}}" class="back-button">
                                <i class="fas fa-chevron-left"></i>
                                </a>Regresar a Lista Departamentos</h3>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <h6 class="required">* : campos obligatorios</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form id="creacion" action="{{ route('departamentoUpdate', $departamento->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group{{ $errors->has('deptoEditar') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-building"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('deptoEditar') ? ' is-invalid' : '' }}" placeholder="{{ __('Nombre Area') }}" type="text" name="deptoEditar" value="{{ $departamento->departamento }}" required autofocus>
                            </div>
                            @if ($errors->has('deptoEditar'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('deptoEditar') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('titularEditar') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('titularEditar') ? ' is-invalid' : '' }}" placeholder="{{ __('Titular') }}" type="text" name="titularEditar" value="{{ $departamento->titular }}" required autofocus>
                            </div>
                            @if ($errors->has('titularEditar'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('titularEditar') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('emailEditar') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('emailEditar') ? ' is-invalid' : '' }}" placeholder="{{ __('Correo Electronico') }}" type="email" name="emailEditar" value="{{ $departamento->correo }}" required>
                            </div>
                            @if ($errors->has('emailEditar'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('emailEditar') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('organoEditar') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-5">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <div class="form-row col-10 col-sm-10">
                                    <label for="to" class="col-2 col-sm-2 col-form-label">Organo:</label>
                                    <div class="col-8 col-sm-8">
                                        <select style="width:100%;" class="form-control @error('organoEditar')  is-invalid @enderror" aria-label=".form-select-md example" name="organoEditar">
                                            <option class="form-control form-control-alternative{{ $errors->has('organoEditar') ? ' is-invalid' : '' }}" selected="true" disabled="disabled">Selecciona el Organo al que pertenece esta Departamento/Area</option>
                                            @foreach ($organos as $organo)
                                                <option value="{{ $organo->id }}" {{$organo->id == $departamento->idOrgano ? 'selected' : ''}} style="background-color: #3332;">{{ $organo->organo }}</option>
                                            @endforeach
                                        </select>


                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('organoEditar'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('organoEditar') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('telefonoEditar') ? ' has-danger' : '' }}">
                                    <div class="input-group input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-tablet-button"></i></span>
                                        </div>
                                        <input class="form-control{{ $errors->has('telefonoEditar') ? ' is-invalid' : '' }}" placeholder="{{ __('Telefono') }}" type="text" name="telefonoEditar" value="{{ $departamento->telefono }}" required autofocus>
                                    </div>
                                    @if ($errors->has('telefonoEditar'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('telefonoEditar') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('celularEditar') ? ' has-danger' : '' }}">
                                    <div class="input-group input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                        </div>
                                        <input class="form-control{{ $errors->has('celularEditar') ? ' is-invalid' : '' }}" placeholder="{{ __('celular') }}" type="text" name="celularEditar" value="{{ $departamento->celular }}" required autofocus>
                                    </div>
                                    @if ($errors->has('celularEditar'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('celularEditar') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>



                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-6 col-sm-2 col align-self-end">
                                <a type="button" href="{{route('departamentosLista')}}" class="btn btn-danger mt-4">{{ __('Cancelar') }}</a>
                                <a type="button" onclick="creacion()" class="btn btn-success mt-4">{{ __('Guardar') }}</a>

                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    function creacion() {
        event.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de actualizar este registro?',
            icon: 'warning',
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
