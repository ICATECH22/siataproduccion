@extends('layouts.app')
@section('content')


<div class="main-content">
    <div class="header fondo-color pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Bandeja de entrada</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('crearServicio') }}" class="btn btn-danger btn-block">Nuevo servicio</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                    </div>


                    @if (session()->has('message'))
                    <div class="alert {{session('alert') ?? 'alert-info'}}">
                        {{session('message')}}
                        {{session()->forget('message')}}
                    </div>
                    @endif
                    <div class="container bootdey">
                        <div class="email-app mb-4">

                            <main class="inbox">
                                <div class="toolbar">


                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
                                            <span class="fa fa-filter" aria-hidden="true"></span>

                                            <span class="caret"></span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" onclick="filtar('Pendiente')" href="#"><span class="badge badge-danger">
                                                    Pendiente</span></a>
                                            <a class="dropdown-item" onclick="filtar('Atendido')" href="#"><span class="badge badge-info"> Atendido</span></a>
                                            <a class="dropdown-item" onclick="filtar('Rechazado')" href="#"><span class="badge badge-success">
                                                    Rechazado</span></a>
                                            <a class="dropdown-item" onclick="filtar('Turnado')" href="#"><span class="badge badge-warning">
                                                    Turnado</span></a>
                                        </div>
                                        <div class="mb-0">
                                            <a style="display:none" class="dropdown-item" id="filtroPendiente" onclick="quitarFiltro('Pendiente')" href="#"><span class="badge badge-danger">
                                                    Pendiente X</span></a>
                                        </div>
                                        <div class="mb-0">
                                            <a style="display:none" class="dropdown-item" id="filtroAtendido" onclick="quitarFiltro('Atendido')" href="#"><span class="badge badge-info"> Atendido X</span></a>
                                        </div>
                                        <div class="mb-0">
                                            <a style="display:none" class="dropdown-item" id="filtroRechazado" onclick="quitarFiltro('Rechazado')" href="#"><span class="badge badge-success">
                                                    Rechazado X</span></a>
                                        </div>
                                        <div class="mb-0">
                                            <a style="display:none" class="dropdown-item" id="filtroTurnado" onclick="quitarFiltro('Turnado')" href="#"><span class="badge badge-warning">
                                                    Turnado X</span></a>
                                        </div>

                                    </div>
                                </div>

                                <br>

                                <div class="form-row">
                                    <div class="col-6 col-sm-6">
                                        <select id="selectFiltrar" name="selectFiltrar" class="form-control form-select form-select-sm" aria-label=".form-select-sm example">
                                            <option value="">FILTRO POR SERVICIOS</option>
                                           @foreach ($servicioByDepto as $k => $v)
                                            <option value="{{ $v->idServicio }}">{{ $v->descripcion }}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                </div>

                                <br>
                                @if($director)
                                    @include('servicios.bandejas.bandejaEntradaDirector')
                                @else
                                    @include('servicios.bandejas.bandejaEntradaJefe')
                                @endif
                            </main>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('contenidoJavaScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
    function filtar(filtro) {

        var target = '.solicitud';

        $(target + '.' + filtro).css('display', 'block');
        $(target).not('.' + filtro).css('display', 'none');
        $('#filtro' + filtro).css('display', 'block');
    }

    function quitarFiltro(filtro) {
        $('#filtro' + filtro).css('display', 'none');

        var target = '.solicitud';
        $(target).not('.' + filtro).css('display', 'block');
    }
    $('#selectFiltrar').on('change', async function(){
        const idServicio = this.value;
        if (idServicio.length > 0) {
            let URL = '{{ route("getFilterByServicios", ":idServicio") }}';
            URL = URL.replace(':idServicio', idServicio);
            let contenido = '';
            let content = '';
            let status = '';
            const result = await $.get(URL)
            .done(function(data, textStatus, jqXHR){
                if (data.dataResponse.length > 0) {
                    $('#mostrarInfo tr').remove();
                    Object.values(data.dataResponse).forEach(val => {
                        let format = formatDate(val.fechaAlta);
                        switch (val.estatusSolicitud) {
                            case 'Rechazado':
                                status += `<a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-danger"><i class="fas fa-ban"></i> ${val.estatusSolicitud} </a>`;
                                break;
                            case 'Pendiente':
                                status += `<a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-default"><i class="fas fa-pause"></i> ${val.estatusSolicitud}</a>`;
                                break;
                            case 'Atendido':
                                status += `<a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-success"> <i class="fas fa-check"></i> ${val.estatusSolicitud}</a>`;
                                break;
                            case 'Turnado':
                                status += `<a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-warning"><i class="fas fa-undo-alt"></i> ${val.estatusSolicitud} </a>`;
                                break;
                            default:
                                break;
                        }
                        contenido += `<tr class="">` +
                                       `<td class="">` +
                                    `<message-item class="unread solicitud ${val.estatusSolicitud}" onclick="detallesAtencion(${val.id})" style="cursor: pointer; display:block;  ">`+
                                        '<header>' +
                                            '<div class="sender-info">'+
                                                `<span class="subject">De: ${val.departamentoSolicitante}</span>` +
                                                '<span class="from">' +
                                                    `Solicita: ${val.nombreServicio}` +
                                                    ` &nbsp;&nbsp; Estado: ${status}` +
                                                '</span>' +
                                            '</div>' +
                                            `<span class="time">Fecha: ${format}</span>` +
                                        '</header>' +
                                        '<main>' +
                                            `<p>Detalles: ${val.detallesServicio}</p>` +
                                        '</main>' +
                                    '</message-item>' +
                            '</td>' +
                        '</tr>'
                    });
                    document.getElementById('mostrarInfo').innerHTML = contenido;
                } else {
                    let format = formatDate(Date.now());
                    /*
                    * modificaciones - no hay registros envíar mensaje
                    */
                    content += `<tr class="">` +
                                       `<td class="">` +
                                    `<message-item class="unread solicitud" style="cursor: pointer; display:block;  ">`+
                                        '<header>' +
                                            '<div class="sender-info">'+
                                                `<span class="subject">EL FILTRADO NO ARROJÓ COINCIDENCIAS</span>` +
                                                '<span class="from">' +
                                                    `NO HAY REGISTROS` +
                                                '</span>' +
                                            '</div>' +
                                            `<span class="time">Fecha: ${format}</span>` +
                                        '</header>' +
                                    '</message-item>' +
                            '</td>' +
                        '</tr>';
                    document.getElementById('mostrarInfo').innerHTML = content;
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ){
                console.log(jqXHR.statusText);
                console.log(jqXHR.responseText);
                console.log(jqXHR.status);
                console.log(textStatus);
                console.log(errorThrown);
            });
            return result;
        } else {
            // regresamos al index
            let URL_NEW = '{{ route("filtro.index") }}';
            let contenido = '';
            let status = '';
            const resultado = await $.get(URL_NEW)
            .done(function(data, textStatus, jqXHR){
                if (data.Response.length > 0) {
                    $('#mostrarInfo tr').remove();
                    Object.values(data.Response).forEach(val => {
                        let format = formatDate(val.fechaAlta);
                        switch (val.estatusSolicitud) {
                            case 'Rechazado':
                                status += `<a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-danger"><i class="fas fa-ban"></i> ${val.estatusSolicitud} </a>`;
                                break;
                            case 'Pendiente':
                                status += `<a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-default"><i class="fas fa-pause"></i> ${val.estatusSolicitud}</a>`;
                                break;
                            case 'Atendido':
                                status += `<a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-success"> <i class="fas fa-check"></i> ${val.estatusSolicitud}</a>`;
                                break;
                            case 'Turnado':
                                status += `<a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-warning"><i class="fas fa-undo-alt"></i> ${val.estatusSolicitud} </a>`;
                                break;
                            default:
                                break;
                        }
                        contenido += `<tr class="">` +
                                       `<td class="">` +
                                    `<message-item class="unread solicitud ${val.estatusSolicitud}" onclick="detallesAtencion(${val.id})" style="cursor: pointer; display:block;  ">`+
                                        '<header>' +
                                            '<div class="sender-info">'+
                                                `<span class="subject">De: ${val.departamentoSolicitante}</span>` +
                                                '<span class="from">' +
                                                    `Solicita: ${val.nombreServicio}` +
                                                    ` &nbsp;&nbsp; Estado: ${status}` +
                                                '</span>' +
                                            '</div>' +
                                            `<span class="time">Fecha: ${format}</span>` +
                                        '</header>' +
                                        '<main>' +
                                            `<p>Detalles: ${val.detallesServicio}</p>` +
                                        '</main>' +
                                    '</message-item>' +
                            '</td>' +
                        '</tr>'
                    });
                    document.getElementById('mostrarInfo').innerHTML = contenido;
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ){
                console.log(jqXHR.statusText);
                console.log(jqXHR.responseText);
                console.log(jqXHR.status);
                console.log(textStatus);
                console.log(errorThrown);
            });
            return resultado;
        }
    });

    function formatDate(date) {
      var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

      if (month.length < 2)
        month = '0' + month;
      if (day.length < 2)
        day = '0' + day;

      return [day, month, year].join('/');
    }

</script>
@endsection
