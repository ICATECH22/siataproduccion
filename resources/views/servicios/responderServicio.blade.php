@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Responder servicio: ') ,
'description' => __('Seleccion un servicio de la lista y añade archivos(opciona)'),
'class' => 'col-lg-12'
])

<style>
    @media (min-width: 768px) {
      .modal-xl {
        width: 90%;
       max-width:1200px;
      }
    }
</style>

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0"><a href="{{route('bandejaEntrada')}}" class="back-button">
                                <i class="fas fa-chevron-left"></i>
                            </a>Regresar bandeja de entrada</h3>

                        <h3 style="margin-left: 20px;" class="mb-0">&nbsp;&nbsp;|&nbsp; ESTADO DE LA SOLICITUD &nbsp;|</h3>

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
                                                    @if($detallesServicio->estatusSolicitud != 'Atendido')
                                                        <button {{$detallesServicio->estatusSolicitud == 'Pendiente' || $detallesServicio->estatusSolicitud == 'Turnado' ? 'onclick=rechazar()' : ''}} type="button" class="btn btn-danger"><i class="mdi mdi-reply text-danger mr-2"></i> Rechazar</button>
                                                        <button {{$detallesServicio->estatusSolicitud == 'Pendiente' || $detallesServicio->estatusSolicitud == 'Turnado' ? 'data-toggle=modal data-target=#modalAtender' : ''}} type="button" class="btn btn-success"><i class="mdi mdi-check text-success mr-2"></i>Atender</button>
                                                        <button {{$detallesServicio->estatusSolicitud == 'Pendiente' || $detallesServicio->estatusSolicitud == 'Turnado' ? 'data-toggle=modal data-target=#turnadoModal' : ''}} type="button" class="btn btn-info"><i class="mdi mdi-swap-horizontal text-info mr-2"></i>Turnar</button>
                                                    @endif
                                                @else
                                                    @if($detallesServicio->estatusSolicitud == 'Rechazado')
                                                    <button {{$detallesServicio->estatusSolicitud == 'Rechazado' ? 'onclick=mostrarOpcionesCorregir()' : ''}} type="button" class="btn btn-info"><i class="mdi mdi-plus-box text-info mr-2"></i>Enviar nueva correccion</button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- div transferir solicitud cuando sea requerido -->


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
                                                    Enviado el: {{$fechaEnviado}}
                                                </p>
                                            </div>
                                        </div>


                                        @if($infoAdicionalSolicitud->motivo != '' )
                                        <div class="sender-details">

                                            <div class="details">
                                                <p class="sender-email">
                                                    Respuesta enviado el : {{$fechaEnviado}}
                                                </p>
                                                <p class="msg-subject">
                                                    {{$detallesServicio->estatusSolicitud == 'Rechazado' ? 'Motivo de rechazo': 'Repuesta de la solicitud' }}: {{$infoAdicionalSolicitud->motivo}}
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
                                                <div class="form-row">
                                                @foreach($files as $file)
                                                @php
                                                    $info = pathinfo(storage_path().$file->urlArchivo);
                                                    $ext = $info['extension'];
                                                @endphp
                                                <div class="col-sm-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                          <h5 class="card-title"><div class="thumb"><i class="mdi mdi-file-image"></i></div> {{$file->nombreArchivo}}</h5>
                                                         @if ($file->tipoArchivo == 'atendido')
                                                            <h6 class="card-subtitle mb-2 text-muted">Archivo de Seguimiento</h6>
                                                         @else
                                                            <h6 class="card-subtitle mb-2 text-muted">Archivo de Solicitud</h6>
                                                         @endif
                                                        @switch($ext)
                                                            @case('pdf')
                                                                <a href="{{ route('verArchivo',$file->id) }}" class="view" target="_blank">Ver</a>
                                                                @break
                                                            @case('jpeg')
                                                                <a href="{{ route('verArchivo',$file->id) }}" class="view" target="_blank">Ver</a>
                                                                @break
                                                            @case('jpg')
                                                                <a href="{{ route('verArchivo',$file->id) }}" class="view" target="_blank">Ver</a>
                                                                @break
                                                            @default

                                                        @endswitch
                                                          <a href="{{ route('descargarArchivo',$file->id) }}" class="download">Descargar</a>
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
    {{-- modal add folio --}}
    @include('modals.modalatencion')
    {{-- modal add folio END --}}

    {{-- modal turnar --}}
    @include('modals.modalTurnar')
    {{-- modal turnar END --}}
</div>
@endsection

@section('contenidoJavaScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/jqueryValidate/jquery.validate.js') }}"></script>
<script src="{{ asset('assets/js/jqueryValidate/additional-methods.min.js') }}"></script>
<script type="text/javascript">
    // jquery
    var url = window.location.pathname;
    var id = url.substring(url.lastIndexOf('/') + 1);
    var areaTransferida = "";

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        $('#frmSeguimiento').validate({
            errorClass: "error",
            rules: {
                descripcion: {
                    required: true
                },
                archivoValidar: {
                    extension: "jpg|jpeg|pdf|doc|docx|png"
                }
            },
            messages:{
                descripcion: {required: "La descripción es Requerida."},
                archivoValidar: "La extensión que quiere cargar no es válida."
            },
            highlight: function(element, errorClass) {
                $(element).addClass(errorClass);
            },
            submitHandler: function(form, event){
                // manejamos el submiteo del formulario
                event.preventDefault();
                    const fd = new FormData($('#frmSeguimiento')[0]);
                    let url = '{{ route("aceptarSolicitud", ":id") }}';
                    url = url.replace(':id', id);
                    $.ajax({
                        url: url,
                        method: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        data: fd,
                        beforeSend: function()
                        {
                            $('#formFactura').attr('disabled', 'disabled');
                            $('.process').css('display', 'block');
                            // modificamos el botón
                            $('#addBillingItem').prop('disabled', true); // deshabilitar botón
                            $("#submitForm").prop('disabled', true); // deshabilitar submit
                            $("#submitForm")
                                .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
                        },
                        success: function(data)
                        {
                           console.log(data);
                           if (data.success == true) {
                            // cierro modal
                                $('#modalAtender').hide();
                                setTimeout( function() { window.location.href = "{{ URL::to('bandejaEntrada')}}"; }, 1500 );
                           } else {

                           }
                        //    $('#modalSuccess').modal('show'); // se abre el modal
                        //     // manejando porcentaje
                        //     let percentage = 0;
                        //     const timer = setInterval(() => {
                        //         percentage = percentage + 20;
                        //         spinnerProgress(percentage, timer, data)
                        //     }, 1000);
                        },
                        error: function(xhr, textStatus, error)
                        {
                            // manejar errores
                            console.log(xhr.statusText);
                            console.log(xhr.responseText);
                            console.log(xhr.status);
                            console.log(textStatus);
                            console.log(error);
                        }
                    })
            }
        });

    $('#unidades').on('change',async function(){
       const idUnidad = this.value;
       let URL = '{{ route("unidadOrgano", ":idUnidad") }}';
       URL = URL.replace(':idUnidad', idUnidad);
       const result = await $.get(URL)
        .done(function(data, textStatus, jqXHR){
            let opciones = '<option value="">Selecciona un Organo Administrativo</option>';
            Object.values(data).forEach(val => {
                opciones += '<option value="'+val.id+'">'+val.organo+'</option>';
            });
            document.getElementById('organos').innerHTML = opciones;
        })
        .fail(function( jqXHR, textStatus, errorThrown ){
            console.log(jqXHR.statusText);
            console.log(jqXHR.responseText);
            console.log(jqXHR.status);
            console.log(textStatus);
            console.log(errorThrown);
        });
       return result;
    });

    $('#organos').on('change', async function(){
        const idOrgano = this.value;
        if (idOrgano.length > 0) {
            let URL = '{{ route("usuarioDepartamento", ":idOrgano") }}';
            URL = URL.replace(':idOrgano', idOrgano);
            const response = await $.get(URL)
                .done(function(data, textStatus, jqXHR){
                    let deptos = '<option value="">Selecciona un Departamento</option>';
                    Object.values(data).forEach(val => {
                        deptos += '<option value="'+val.id+'">'+val.departamento+'</option>';
                    });
                    document.getElementById('deptos').innerHTML = deptos;
                })
                .fail(function(jqXHR, textStatus, errorThrown){
                    console.log(jqXHR.statusText);
                    console.log(jqXHR.responseText);
                    console.log(jqXHR.status);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            return response;
        }
    });

    $("#deptos").on('change', async function() {
        const idDepto = this.value;
        if (idDepto.length > 0) {
            let URL = '{{ route("ServicioByDepto", ":idDepto") }}';
            URL = URL.replace(':idDepto', idDepto);
            const res = await $.get(URL)
                .done(function(data, textStatus, jqXHR){
                    let servicio = '<option value="">Selecciona un Servicio</option>';
                    Object.values(data).forEach(val => {
                        servicio += '<option value="'+val.idServicio+'">'+val.descripcion+'</option>';
                    });
                    document.getElementById('servicios').innerHTML = servicio;
                })
                .fail(function(jqXHR, textStatus, errorThrown){
                    console.log(jqXHR.statusText);
                    console.log(jqXHR.responseText);
                    console.log(jqXHR.status);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            return res;
        }
    });

    $('#transferirForm').validate({
        errorClass: "error",
        rules: {
            unidades: { required: true },
            organos: {required: true},
            deptos: {required: true},
            servicios: {required: true},
            archivoReturnar: { extension: "jpg|jpeg|pdf|doc|docx|png" }
        },
        messages:{
            unidades: {required: "La Unidad es Requerida."},
            organos: {required: "El organo es Requerido"},
            deptos: {required: "El departamento es requerido"},
            servicios: {required: "El servicio es requerido"},
            archivoReturnar: "La extensión que quiere cargar no es válida."
        },
        highlight: function(element, errorClass) {
            $(element).addClass(errorClass);
        },
        submitHandler: function(form, event){
            event.preventDefault();
            const id = {{ $detallesServicio->id }};
            const formData = new FormData($('#transferirForm')[0]);
            let URL = '{{ route("transferirSolicitud", ":id") }}';
            URL = URL.replace(':id', id);
            $.ajax({
                url: URL,
                method: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                data: formData,
                beforeSend: function()
                {
                    $('#formFactura').attr('disabled', 'disabled');
                    $('.process').css('display', 'block');
                    // modificamos el botón
                    $('#addBillingItem').prop('disabled', true); // deshabilitar botón
                    $("#submitForm").prop('disabled', true); // deshabilitar submit
                    $("#submitForm")
                        .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
                },
                success: function(response)
                {
                    console.log(response);
                    if (response.success == true) {
                    // cierro modal
                        $('#turnadoModal').hide();
                        setTimeout( function() { window.location = response.url; }, 1500 );
                    } else {
                        console.log('Mensaje de error del sistema');
                    }
                //    $('#modalSuccess').modal('show'); // se abre el modal
                //     // manejando porcentaje
                //     let percentage = 0;
                //     const timer = setInterval(() => {
                //         percentage = percentage + 20;
                //         spinnerProgress(percentage, timer, data)
                //     }, 1000);
                },
                error: function(xhr, textStatus, error)
                {
                    // manejar errores
                    console.log(xhr.statusText);
                    console.log(xhr.responseText);
                    console.log(xhr.status);
                    console.log(textStatus);
                    console.log(error);
                }
            });
        }
    });

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
