@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Nuevo Departamento/Area') ,
'description' => __('Modulo para crear direcciones'),
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
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('asignarServiciosStore',$idDepartamento) }}" autocomplete="off" id="creacion">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" id="txtServicio" class="form-control @error('curpCompareciente')  is-invalid @enderror" onkeyup="buscarServicio()" />
                                    <ul id="searchResult"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('servicio') ? ' has-danger' : '' }}">
                            <input type="text" hidden id="idServicio" name="idServicio">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('servicio') ? ' is-invalid' : '' }}" placeholder="{{ __('Servicio') }}" type="text" name="servicio" id="servicio" value="{{ old('servicio') }}" required autofocus>
                            </div>
                            @if ($errors->has('servicio'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('servicio') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="row my-4">
                            <div class="col-12">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input onchange="nuevoServicioArea()" class="custom-control-input" id="checkNuevoServicio" name="isNuevoServicio" type="checkbox">
                                    <label class="custom-control-label" for="checkNuevoServicio">
                                        <a>Crear nuevo servicio</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="nuevoServicioArea" class="form-group{{ $errors->has('nuevoServicio') ? ' has-danger' : '' }}" style="display: none">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('nuevoServicio') ? ' is-invalid' : '' }}" placeholder="{{ __('Nuevo Servicio') }}" type="text" name="nuevoServicio" value="{{ old('nuevoServicio') }}" required>
                            </div>
                            @if ($errors->has('nuevoServicio'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('nuevoServicio') }}</strong>
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
    function getID(e) {
        index = e;
    }

    function nuevoServicioArea() {
        if ($('#checkNuevoServicio').is(":checked"))
            $("#nuevoServicioArea").show();
        else
            $("#nuevoServicioArea").hide();

    }

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

    function buscarServicio() {
        var txtServicio = document.getElementById("txtServicio").value
        if (txtServicio !== "") {
            $.ajax({
                url: '/catalogos/servicios/buscador',
                type: 'get',
                data: {
                    "servicio": txtServicio
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    var len = response.length;
                    $("#searchResult").empty();
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['idServicio']
                        var descripcion = response[i]['descripcion'];
                        $("#searchResult").append("<li onclick='getID(this.id)' id='" + i + "' value='" + id + "'>" + descripcion + "</li>");
                    }
                    $("#searchResult li").on("click", function() {
                        $("#searchResult").empty();
                        $("#servicio").val(response[index]['descripcion']);
                        $("#idServicio").val(response[index]['idServicio']);
                    });
                }
            });
        } else {

            $('#otroPuebloC').hide();
        }
    }
</script>
@endsection