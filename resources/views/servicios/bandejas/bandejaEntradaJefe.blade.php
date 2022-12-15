<br>

<inbox>
    <inbox-list>

        <table id="tablaSolicitudesEnviados" class="table table-hover table-bordered">
            <thead id="">
                <tr>
                    <th>Historial Bandeja de entrada
                </tr>
            </thead>

            <tbody>
                @foreach($recibidos as $solicitud)
                <tr class="">
                    <td class="">
                        @if($solicitud->visto == 0 && $solicitud->estatusSolicitud =='Pendiente')
                        <message-item class="unread solicitud {{$solicitud->estatusSolicitud}}" onclick="detallesAtencion({{$solicitud->id}})" style="cursor: pointer; display:block;  ">
                            @else
                            <message-item class="read solicitud {{$solicitud->estatusSolicitud}}" onclick="detallesAtencion({{$solicitud->id}})" style="cursor: pointer; display:block;  ">
                                @endif


                                <header>
                                    <div class="sender-info">
                                        <span class="subject">De: {{$solicitud->departamentoSolicitante}}</span>
                                        <span class="from">
                                            Solicita: {{$solicitud->servicio}} &nbsp; Estatus:@if($solicitud->estatusSolicitud == 'Pendiente')
                                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-default">{{$solicitud->estatusSolicitud }} </a>
                                            @elseif($solicitud->estatusSolicitud == 'Rechazado')
                                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-danger">{{$solicitud->estatusSolicitud }} </a>
                                            @elseif($solicitud->estatusSolicitud == 'Atendido')
                                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-success">{{$solicitud->estatusSolicitud }} </a>
                                            @elseif($solicitud->estatusSolicitud == 'Turnado')
                                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-warning">{{$solicitud->estatusSolicitud }} </a>
                                            @endif
                                        </span>
                                    </div>
                                    <span class="time">{{$solicitud->fechaCreacion}}</span>
                                </header>
                                <main>
                                    <p>Detalles:{{$solicitud->detallesServicio}}</p>
                                </main>
                            </message-item>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </inbox-list>
</inbox>



<script type="text/javascript">
    $(document).ready(function() {
        new DataTable('#tablaSolicitudesEnviados', {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
        });
    });

    function detallesAtencion(idSolicitud) {


        window.location = 'recibidos/detalles/' + idSolicitud;
    }
</script>