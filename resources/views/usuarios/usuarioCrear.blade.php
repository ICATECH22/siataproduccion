@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Admin') ,
'description' => __('Modulo para crear nuevos usuarios'),
'class' => 'col-lg-7'
])

<div class="container-fluid mt--7">
    <div class="row">

        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">{{ __('Nuevo Usuario') }}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('usuarioGuardar') }}" autocomplete="off" id="creacion">
                        @csrf
                        <div class="form-group {{ $errors->has('nombre') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                </div>
                                <input class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" placeholder="{{ __('Nombre') }}" type="text" name="nombre" value="{{ old('nombre') }}" required autofocus>
                            </div>
                            @if ($errors->has('nombre'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('nombre') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Correo Electronico') }}" type="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Contraseña') }}" type="password" name="password" required>
                            </div>
                            @if ($errors->has('password'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __('Confirmar Contraseña') }}" type="password" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('unidad') ? ' has-danger' : '' }}">

                                    <label class="form-control-label" for="input-unidad">{{ __('Organo') }}</label>
                                    <select class="form-control form-control-alternative {{ $errors->has('idUnidad') ? ' is-invalid' : '' }}" aria-label=".form-select-md example" name="idDireccion" id="organosLista">
                                        <option selected="true" disabled="disabled">Selecciona una Organo</option>
                                        @foreach($organos as $organo)
                                        <option value="{{ $organo->id }}">{{ $organo->organo }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="row my-4">
                                <div class="col-12">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input checked onchange="mostrarSelectArea()" class="custom-control-input" id="customCheckRegister" name="isDirector" type="checkbox">
                                        <label class="custom-control-label" for="customCheckRegister">
                                            <a href="#!">Agregar usuario como Director </a>
                                        </label>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div id="selectArea" class="row" style="display: none">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('Area') }}</label>
                                    <select class="form-control form-control-alternative {{ $errors->has('idDepartamento') ? ' is-invalid' : '' }}" aria-label=".form-select-md example" name="idDepartamento" id="idDepartamento">
                                        <option selected="true" disabled>Selecciona una unidad primero</option>



                                    </select>
                                    @error('idDepartamento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-6 col-sm-2 col align-self-end">
                                <a type="button" href="{{route('usuariosLista')}}" class="btn btn-danger mt-4">{{ __('Cancelar') }}</a>
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

    $("#organosLista").change(function() {
        var id = $(this).children(":selected").attr("value");
        console.log(id);
        $.ajax({
            url: '/usuarios/getDepartamentos/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $("#idDepartamento").empty();
                var tipo = $("#idDireccion").children("option:selected").val();
                for (var i = 0; i < response.length; i++) {
                    var idUnidad = response[i]['idparent'];
                    if (idUnidad == tipo) {
                        $("#idDepartamento").append("<option value='" + response[i]['id'] + "'>" + response[i]['departamento'] + "</option>");
                    }
                }
                $("#idDepartamento").prepend("<option value='default' selected='true' disabled='disabled'>Selecciona una opción</option>")
            }
        });

        $('#idUnidad').prop('disabled', false);
    });



    function mostrarSelectArea() {


        if ($('#customCheckRegister').is(":checked"))
            $("#selectArea").hide();
        else
            $("#selectArea").show();

    }
</script>
@endsection