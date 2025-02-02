@extends('layouts.app', ['title' => __('Detalles del Servicio - SIATA')])

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
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-default"><i class="fas fa-pause"></i> {{$detallesServicio->estatusSolicitud }} </a>
                            @elseif($detallesServicio->estatusSolicitud == 'Rechazado')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-danger"><i class="fas fa-ban"></i> {{$detallesServicio->estatusSolicitud }} </a>
                            @elseif($detallesServicio->estatusSolicitud == 'Atendido')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-success"><i class="fas fa-check"></i> {{$detallesServicio->estatusSolicitud }} </a>
                            @elseif($detallesServicio->estatusSolicitud == 'Turnado')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-warning"><i class="fas fa-undo-alt"></i> Se ha {{$detallesServicio->estatusSolicitud }} a  {{$detallesServicio->departamentoReceptor}} </a>
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
                                                    <button {{$detallesServicio->estatusSolicitud == 'Rechazado' ? 'data-toggle=modal data-target=#modalCorrecion' : ''}} type="button" class="btn btn-info"><i class="mdi mdi-plus-box text-info mr-2"></i>Enviar nueva correccion</button>
                                                @endif



                                            </div>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <div class="sender-details">
                                            <img class="img-sm rounded-circle mr-3" src="{{ asset('argon') }}/img/icons/common/archivo.svg" alt="">
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
                                                    Enviado el: {{$fechaEnvio}}
                                                </p>
                                                <p>
                                                    {{$detallesServicio->departamentoSolicitante ?? 'nombre del departamento solicitante'}}
                                                    Escribió:
                                                    <b>{{$detallesServicio->detallesServicio??'aqui va los detalles del serivicio'}}</b>
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

                                        <div class="attachments-sections">
                                            <ul>
                                                <p class="msg-subject">
                                                    Archivos adjuntos:
                                                </p>
                                                <div class="form-row">
                                                    @foreach($files as $file)
                                                    @php
                                                        $info = pathinfo(storage_path().$file->urlArchivo);
                                                        $ext = $info['extension'];
                                                    @endphp
                                                    <div class="col-sm-3">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5 class="card-title">
                                                                    <div class="thumb">
                                                                        <i class="mdi mdi-file-image"></i>
                                                                    </div>
                                                                    {{$file->nombreArchivo}}
                                                                </h5>
                                                                @if ($file->tipoArchivo == 'atendido')
                                                                    <h6 class="card-subtitle mb-2 text-muted">Archivo de Seguimiento</h6>
                                                                @else
                                                                    <h6 class="card-subtitle mb-2 text-muted">Archivo de Solicitud</h6>
                                                                @endif
                                                                <a href="{{ route('archivo.getfile',$file->id) }}" class="view" target="_blank">Descargar</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                @endforeach
                                                </div>
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

@include('modals.modalCorreccion')

@endsection
@section('contenidoJavaScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/jqueryValidate/jquery.validate.js') }}"></script>
<script src="{{ asset('assets/js/jqueryValidate/additional-methods.min.js') }}"></script>
<script>
    var url = window.location.pathname;
    var id = url.substring(url.lastIndexOf('/') + 1);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    $('#corregirForm').validate({
        errorClass: "error",
        rules: {
            organo: { required: true  },
            servicio: { required: true},
            archivoNuevo: {
                extension: "jpg|jpeg|pdf|doc|docx|png|xlsx|xls"
            }
        },
        messages:{
            organo: {required: "El organo administrativo es requerido"},
            servicio: {required: "Servicio es requerido"},
        },
        highlight: function(element, errorClass) {
            $(element).addClass(errorClass);
        }
    });

    function aceptar() {

        event.preventDefault();
        Swal.fire({
            title: 'Se responderá esta solicitud como VALIDA, ¿Desea continuar?',
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
            icon: 'warning',
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
        $.ajax({
            url: '/unidad/getOrgano/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $("#organoSelect").empty();

                for (var i = 0; i < response.length; i++) {
                    var idUnidad = response[i]['idparent'];
                    $("#organoSelect").append("<option value='" + response[i]['id'] + "'>" + response[i]['organo'] + "</option>");
                }
                $("#organoSelect").prepend("<option value='default' selected='true' disabled='disabled'>Selecciona una opción</option>")
            }
        });


    });
    $("#organo").change(function() {
        var id = $(this).children(":selected").attr("value");
        let URL = '{{ route("serviciosDepartamentos", ":id") }}';
        URL = URL.replace(':id', id);
        $.ajax({
            url: URL,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                console.log(response)
                $("#servicio").empty();
                var departamento = '';
                for (var i = 0; i < response.length; i++) {
                    if (departamento != response[i]['departamento']) {
                        $("#servicio").append("<option disabled> ────────────────────────────────────────────────── </option>");
                        $("#servicio").append("<option disabled>" + response[i]['departamento'] + "</option>");
                        departamento = response[i]['departamento'];
                    }
                    $("#servicio").append("<option value='" + response[i]['idServicio'] + "-" + response[i]['idDepartamento'] + "'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + response[i]['descripcion'] + "</option>");

                }
                $("#servicio").prepend("<option value='default' selected='true' disabled='disabled'>Selecciona una opción</option>")
            }
        });


    });
</script>
@endsection
