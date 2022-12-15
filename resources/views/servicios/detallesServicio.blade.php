@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Detalles del servicio : ') ,
'description' => __('Seleccion un servicio de la lista y añade archivos(opciona)'),
'class' => 'col-lg-12'
])


<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0"><a href="{{route('enviados')}}" class="back-button">
                                <i class="fas fa-chevron-left"></i>
                            </a>Regresar a enviados</h3>

                        <h3 style="margin-left: 20px;" class="mb-0">&nbsp;&nbsp;|&nbsp;&nbsp;ESTATUS&nbsp;&nbsp;|</h3>
                        <h3 style="margin-left: 20px;" class="mb-0">
                            @if($detallesServicio->estatusSolicitud == 'Pendiente')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-default">{{$detallesServicio->estatusSolicitud }} </a>
                            @elseif($detallesServicio->estatusSolicitud == 'Rechazado')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-danger">{{$detallesServicio->estatusSolicitud }} </a>
                            @elseif($detallesServicio->estatusSolicitud == 'Atendido')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-success">{{$detallesServicio->estatusSolicitud }} </a>
                            @elseif($detallesServicio->estatusSolicitud == 'Turnado')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-warning">Se ha {{$detallesServicio->estatusSolicitud }} al departamento: {{$detallesServicio->departamentoReceptor}} </a>
                            @endif

                        </h3>
                    </div>

                </div>
                <div class="card-body container-fluid">

                    @csrf
                    <div class="container">
                        <div class="email-wrapper wrapper">
                            <div class="row align-items-stretch">
                                <div class="mail-view d-none d-md-block col-md-12 col-lg-12 bg-white">

                                    <div class="row">
                                        <div class="col-md-12 mb-4 mt-4">
                                            <div class="btn-toolbar">


                                                @if($detallesServicio->estatusSolicitud == 'Rechazado')
                                                <button {{$detallesServicio->estatusSolicitud == 'Rechazado' ? 'onclick=mostrarOpcionesCorregir()' : ''}} type="button" class="btn btn-info"><i class="mdi mdi-plus-box text-info mr-2"></i>Enviar nueva correccion</button>
                                                @endif



                                            </div>
                                        </div>
                                    </div>

                                    <!-- div corregir solicitud cuando sea rechazadp -->
                                    <div class="message-body" id="formCorregir" style="display: none">
                                        <div class="sender-details">
                                            <div id="opciones-transferir" class="col-md-12">
                                                <div class="card-body">
                                                    <form id="corregirForm" action="{{route('corregirSolicitud',$detallesServicio->id)}}" method="POST" enctype="multipart/form-data">
                                                        @csrf

                                                        <main class="col-md-12 col-sm-12">
                                                            <p class="text-center">Corregir Servicio</p>

                                                            <div class="form-row mb-3">
                                                                <label for="to" class="col-md-2 col-sm-1 col-form-label">Unidad de Capacitacion:</label>
                                                                <div class="col-10 col-sm-11">
                                                                    <select style="width:100%;" class="form-control @error('servicio')  is-invalid @enderror" aria-label=".form-select-md example" name="unidad" id="unidad">
                                                                        <option selected="true" disabled="disabled">Selecciona una unidad</option>

                                                                        @foreach ($unidades as $unidad)
                                                                        <option value="{{ $unidad->idUnidad }}"> {{ $unidad->descripcion }}</option>
                                                                        @endforeach

                                                                    </select>

                                                                    @error('unidad')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-row mb-3">
                                                                <label for="to" class="col-md-2 col-sm-1 col-form-label">Organo:</label>
                                                                <div class="col-10 col-sm-11">
                                                                    <select style="width:100%;" class="form-control @error('organo')  is-invalid @enderror" aria-label=".form-select-md example" name="organo" id="organoSelect">
                                                                        <option class="form-control form-control-alternative{{ $errors->has('organo') ? ' is-invalid' : '' }}" selected="true" disabled="disabled">Selecciona un servicio</option>
                                                                    </select>

                                                                    @error('organo')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-row mb-3">
                                                                <label for="to" class="col-md-2 col-sm-1 col-form-label">Servicios por departamento:</label>
                                                                <div class="col-10 col-sm-11">
                                                                    <select style="width:100%;" class="form-control @error('servicio')  is-invalid @enderror" aria-label=".form-select-md example" name="servicio" id="servicioSelect">
                                                                        <option class="form-control form-control-alternative{{ $errors->has('servicio') ? ' is-invalid' : '' }}" selected="true" disabled="disabled">Selecciona un servicio</option>
                                                                    </select>

                                                                    @error('servicio')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-11 ml-auto">
                                                                    <div class="toolbar" role="toolbar">
                                                                        <button type="button" class="btn btn-light">
                                                                            <span class="fa fa-paperclip"></span>
                                                                            <input hidden type="file" name="archivoNuevo" id="file-1" class="inputfile2 @error('archivoNuevo')  is-invalid @enderror" data-multiple-caption="{count} files selected" multiple />
                                                                            <label for="file-1"><span>Adjuntar nuevo archivo&hellip;</span></label>
                                                                            @error('archivoNuevo')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                            @enderror
                                                                        </button>
                                                                    </div>



                                                                    <div class="form-group mt-4">
                                                                        <textarea class="form-control @error('descripcion')  is-invalid @enderror" id="detalles" name="descripcion" rows="12" placeholder="Escriba aqui los detalles de la solicitud"></textarea>
                                                                        @error('descripcion')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <a onclick="ocultarOpcionesCorregir()" type="button" class="btn btn-danger mt-4">{{ __('Cancelar') }}</a>
                                                                        <button onclick="corregir()" class="btn btn-success mt-4">{{ __('Enviar correccion') }} </button>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </main>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <div class="sender-details">
                                            <img class="img-sm rounded-circle mr-3" src="{{ asset('argon') }}/img/icons/common/prueba.svg" alt="">
                                            <div class="details">
                                                <p class="msg-subject">
                                                    Asunto: {{$detallesServicio->servicio}} - {{$detallesServicio->departamentoReceptor}}
                                                </p>
                                                <p class="sender-email">
                                                    De: {{$detallesServicio->departamentoSolicitante}}

                                                    <!-- <a href="#">itsmesarah268@gmail.com</a> -->
                                                    &nbsp;<i class="mdi mdi-account-multiple-plus"></i>
                                                </p>
                                                <p class="sender-email">
                                                    Enviado el: {{$detallesServicio->fechaAltaa}}
                                                </p>
                                            </div>
                                        </div>

                                        @if($infoAdicionalSolicitud->estatusSolicitud != 'Pendiente')
                                        <div class="sender-details">

                                            <div class="details">
                                                <p class="sender-email">
                                                    Respuesta enviado el : {{$infoAdicionalSolicitud->fechaAlta}}
                                                </p>
                                                <p class="msg-subject">
                                                    Observaciones: {{$infoAdicionalSolicitud->motivo}}
                                                </p>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="message-content">
                                            <p>{{$detallesServicio->departamentoSolicitante??'nombre del departamento solicitante'}} Escribio: </p>

                                            {{$detallesServicio->detalles??'aqui va los detalles del serivicio'}}
                                        </div>
                                        <div class="attachments-sections">
                                            <ul>
                                                @foreach($files as $file)
                                                <li>
                                                    <div class="thumb"><i class="mdi mdi-file-image"></i></div>
                                                    <div class="details">
                                                        <p class="file-name">{{$file->nombreArchivo}}</p>
                                                        <div class="buttons">
                                                            <a href="{{ route('verArchivo',$file->id) }}" class="view" target="_blank">View</a>
                                                            <a href="{{ route('descargarArchivo',$file->id) }}" class="download">Download</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <br>
                                                @endforeach

                                            </ul>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>



</div>
</div>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var url = window.location.pathname;
    var id = url.substring(url.lastIndexOf('/') + 1);

    function aceptar() {

        event.preventDefault();
        Swal.fire({
            title: 'Se responderá esta solicitud como VALIDA, ¿Desea continuar?',
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
                $.ajax({
                    url: '/solicitud/aceptar/' + id,
                    type: 'post',
                    data: {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        console.log(response);
                        window.location = response.url
                    }
                });
            }
        })
    }

    function rechazar() {

        Swal.fire({
            title: 'Rechazar solicitud',
            imageUrl: '/images/iconos/exclamacion.PNG',
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            showCancelButton: true,
            cancelButtonColor: '#656665',
            confirmButtonColor: '#611031',
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            html: `<textarea id="motivo" placeholder="Escriba el motivo de rechazo" name="descripcion" rows="8" cols="20" class="form-control"></textarea>`,


            focusConfirm: false,
            preConfirm: () => {
                const motivo = Swal.getPopup().querySelector('#motivo').value
                if (!motivo) {
                    Swal.showValidationMessage(`Por favor, introduzca motivo de rechazo`)
                }
                return {
                    motivo: motivo,
                }
            }
        }).then((result) => {
            let motivo = result.value.motivo;

            $.ajax({
                url: '/solicitud/rechazar/' + id,
                type: 'post',
                dataType: 'json',
                data: {
                    "motivoRechazo": motivo,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log(response);
                    window.location = response.url
                }
            });
        })

    }

    function transferir() {
        let areaTranferida = document.getElementById('servicio').value;
        let detalles = document.getElementById('detalles').value;

        let file;
        event.preventDefault();
        Swal.fire({
            title: 'Se transferira esta solicitdu a ',
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
                document.getElementById('transferirForm').submit();
            }
        })
    }

    function corregir() {
        let areaTranferida = document.getElementById('servicio').value;
        let detalles = document.getElementById('detalles').value;

        let file;
        event.preventDefault();
        Swal.fire({
            title: 'Se enviara la correccion',
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
                document.getElementById('corregirForm').submit();
            }
        })
    }


    function mostrarOpcionesTransferir() {
        $("#formTransferir").show();
        const checkbox = document.getElementById('checkMantenerArchivoOriginal')

        checkbox.addEventListener('change', (event) => {
            if (event.currentTarget.checked) {
                $("#subirNuevoArchivo").hide();
            } else {
                $("#subirNuevoArchivo").show();

            }
        })
    }

    function ocultarOpcionesTransferir() {
        $("#formTransferir").hide();
    }

    function mostrarOpcionesCorregir() {
        $("#formCorregir").show();
    }

    function ocultarOpcionesCorregir() {
        $("#formCorregir").hide();
    }

    $("#unidad").change(function() {
        var id = $(this).children(":selected").attr("value");
        console.log(id);
        $.ajax({
            url: '/unidad/getOrgano/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                console.log(response)
                $("#organoSelect").empty();

                for (var i = 0; i < response.length; i++) {
                    var idUnidad = response[i]['idparent'];
                    $("#organoSelect").append("<option value='" + response[i]['id'] + "'>" + response[i]['organo'] + "</option>");
                }
                $("#organoSelect").prepend("<option value='default' selected='true' disabled='disabled'>Selecciona una opción</option>")
            }
        });


    });
    $("#organoSelect").change(function() {
        var id = $(this).children(":selected").attr("value");
        console.log(id);
        $.ajax({
            url: '/departamentos/servicios/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                console.log(response)
                $("#servicioSelect").empty();
                var departamento = '';
                for (var i = 0; i < response.length; i++) {
                    if (departamento != response[i]['departamento']) {
                        $("#servicioSelect").append("<option disabled> ────────────────────────────────────────────────── </option>");
                        $("#servicioSelect").append("<option disabled>" + response[i]['departamento'] + "</option>");
                        departamento = response[i]['departamento'];
                    }
                    $("#servicioSelect").append("<option value='" + response[i]['idServicio'] + "-" + response[i]['idDepartamento'] + "'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + response[i]['descripcion'] + "</option>");

                }
                $("#servicioSelect").prepend("<option value='default' selected='true' disabled='disabled'>Selecciona una opción</option>")
            }
        });


    });
</script>

@endsection