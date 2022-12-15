@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Responder servicio: ') ,
'description' => __('Seleccion un servicio de la lista y añade archivos(opciona)'),
'class' => 'col-lg-12'
])


<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0"><a href="{{route('bandejaEntrada')}}" class="back-button">
                                <i class="fas fa-chevron-left"></i>
                            </a>Regresar bandeja de entrada</h3>

                        <h3 style="margin-left: 20px;" class="mb-0">&nbsp;&nbsp;|&nbsp;&nbsp;ESTATUS&nbsp;&nbsp;|</h3>

                        <h3 style="margin-left: 20px;" class="mb-0">

                            @if($detallesServicio->estatusSolicitud == 'Pendiente')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-default">{{$detallesServicio->estatusSolicitud }} </a>
                            @elseif($detallesServicio->estatusSolicitud == 'Rechazado')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-danger">{{$detallesServicio->estatusSolicitud }} </a>
                            @elseif($detallesServicio->estatusSolicitud == 'Atendido')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-success">{{$detallesServicio->estatusSolicitud }} </a>
                            @elseif($detallesServicio->estatusSolicitud == 'Turnado')
                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-warning">Se ha {{$detallesServicio->estatusSolicitud }} aL departamento de {{$detallesServicio->departamentoReceptor}}</a>
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
                                                @if($unidadUsuario->id != $detallesServicio->idDepartamentoSolicitante )
                                                <button {{$detallesServicio->estatusSolicitud == 'Pendiente' || $detallesServicio->estatusSolicitud == 'Turnado' ? 'onclick=rechazar()' : ''}} type="button" class="btn btn-danger"><i class="mdi mdi-reply text-danger mr-2"></i> Rechazar</button>
                                                <button {{$detallesServicio->estatusSolicitud == 'Pendiente' || $detallesServicio->estatusSolicitud == 'Turnado' ? 'onclick=aceptar()' : ''}} type="button" class="btn btn-success"><i class="mdi mdi-check text-success mr-2"></i>Atender</button>
                                                <button {{$detallesServicio->estatusSolicitud == 'Pendiente' || $detallesServicio->estatusSolicitud == 'Turnado' ? 'onclick=mostrarOpcionesTransferir()' : ''}} type="button" class="btn btn-info"><i class="mdi mdi-swap-horizontal text-info mr-2"></i>Turnar</button>
                                                @else
                                                @if($detallesServicio->estatusSolicitud == 'Rechazado')
                                                <button {{$detallesServicio->estatusSolicitud == 'Rechazado' ? 'onclick=mostrarOpcionesCorregir()' : ''}} type="button" class="btn btn-info"><i class="mdi mdi-plus-box text-info mr-2"></i>Enviar nueva correccion</button>
                                                @endif
                                                @endif



                                            </div>
                                        </div>
                                    </div>

                                    <!-- div transferir solicitud cuando sea requerido -->

                                    <div class="message-body" id="formTransferir" style="display: none">
                                        <div class="sender-details">
                                            <div id="opciones-transferir" class="col-md-12">
                                                <div class="card-body">
                                                    <form id="transferirForm" action="{{route('transferirSolicitud',$detallesServicio->id)}}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row justify-content-center">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">

                                                                    <label for="input-unidad">Turnar a: <span class="form-control-label">*</span></label>
                                                                    <select style="width:100%;" class="form-control  @error('servicio')  is-invalid @enderror" aria-label=".form-select-md example" name="servicio" id="servicio" onChange="myNewFunction(this);">
                                                                        <option class="form-control " selected="true" disabled="disabled">Selecciona un servicio</option>

                                                                        @foreach ($unidades as $unidad)
                                                                        @if($unidad->id != $unidadUsuario->idArea)
                                                                        <option disabled name="idUnidad" value="{{ $unidad->id }}" style="background-color: #3332;">Unidad: {{ $unidad->area }}</option>
                                                                        @foreach ($unidad->servicios as $servicio)
                                                                        @if($servicio->estatus == 1)
                                                                        <option id="servicio" name="servicio" value="{{ $servicio->idServicio }}-{{ $unidad->id }}">&nbsp;&nbsp;&nbsp;&nbsp;Servicio: {{ $servicio->descripcion }}</option>
                                                                        @endif
                                                                        @endforeach
                                                                        @endif
                                                                        <option disabled>──────────────────────────────────────────────────</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('servicio')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror

                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="toolbar" role="toolbar">
                                                            <button type="button" class="btn btn-light">
                                                                <span class="fa fa-paperclip"></span>
                                                                <input hidden type="file" name="archivo" id="file-1" class="inputfile2 @error('archivo')  is-invalid @enderror" data-multiple-caption="{count} files selected" multiple />
                                                                <label for="file-1"><span>Adjuntar nuevo archivo&hellip;</span></label>
                                                                @error('archivo')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </button>
                                                        </div>
                                                        <div class="form-group mt-4">

                                                            <div class="form-group">
                                                                <label for="Notas">Observaciones</label>
                                                                <textarea id="detalles" placeholder="Escriba aqui las observaciones de la transferencia" name="descripcionTransferencia" rows="8" cols="20" class="form-control @error('descripcionTransferencia')  is-invalid @enderror"></textarea>
                                                                @error('descripcionTransferencia')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>


                                                        </div>

                                                        <div class="row justify-content-end">
                                                            <div class="col-md-6 col-sm-2 col align-self-end">
                                                                <!-- <a type="button" href="{{route('departamentosLista')}}" class="btnCancel">Cancelar</a> -->
                                                                <!-- <button type="button" class="btnCancel"><a href="" class="btnCancel">Cancelar</a></button> -->
                                                                <a onclick="ocultarOpcionesTransferir()" type="button" class="btn btn-danger mt-4">{{ __('Cancelar') }}</a>
                                                                <button onclick="transferir()" class="btn btn-success mt-4">{{ __('Turnar') }} </button>

                                                            </div>
                                                        </div>

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


                                        @if($infoAdicionalSolicitud->motivo != '' )
                                        <div class="sender-details">

                                            <div class="details">
                                                <p class="sender-email">
                                                    Respuesta enviado el : {{$infoAdicionalSolicitud->fechaAlta}}
                                                </p>
                                                <p class="msg-subject">
                                                    {{$detallesServicio->estatusSolicitud == 'Rechazado' ? 'Motivo de rechazo': 'Motivo de Transferencia' }}: {{$infoAdicionalSolicitud->motivo}}
                                                </p>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="message-content">
                                            <p>{{$detallesServicio->departamentoSolicitante}} escribió:</p>
                                            {{$detallesServicio->detallesServicio}}
                                        </div>
                                        <div class="attachments-sections">
                                            <ul>
                                                <p class="msg-subject">
                                                    Archivos adjuntos:
                                                </p>

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


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var url = window.location.pathname;
    var id = url.substring(url.lastIndexOf('/') + 1);
    var areaTransferida = "";

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
            confirmButtonColor: '#611031',
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            html: `<textarea id="sugerencia" placeholder="Escriba alguna sugerencia (Opcional)" name="descripcion" rows="8" cols="20" class="form-control"></textarea>`,
            focusConfirm: false,
            preConfirm: () => {
                const sugerencia = Swal.getPopup().querySelector('#sugerencia').value
                return {
                    sugerencia: sugerencia,
                }
            }
        }).then((result) => {
            let sugerencia = result.value.sugerencia;

            $.ajax({
                url: '/solicitud/aceptar/' + id,
                type: 'post',
                dataType: 'json',
                data: {
                    "sugerencia": sugerencia,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log(response);
                    window.location = response.url
                }
            });
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
            title: 'Se enviara la correccion a',
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

    function myNewFunction(sel) {
        areaTransferida = sel.options[sel.selectedIndex].text;
    }
</script>

@endsection