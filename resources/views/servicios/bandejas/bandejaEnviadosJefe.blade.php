<inbox>
    <inbox-list>

        <table id="tablaSolicitudesEnviados2" class="table table-hover table-bordered">
            <thead id="">
                <tr>
                    <th>Historial Bandeja de Enviados
                </tr>
            </thead>


            <tbody>
                @foreach($enviados as $solicitud)
                <tr>
                    <td>
                        <message-item class="{{ $solicitud->visto == 0 && $solicitud->estatusSolicitud =='Pendiente' ? "unread" : "read"}}"  onclick="verAtencion({{$solicitud->id}})" style="cursor: pointer; display:block  ">
                            <header>
                                <div class="sender-info">
                                    <span class="subject">De: {{$solicitud->departamentoSolicitante}}</span>
                                    <span class="from">
                                        Solicitud: {{$solicitud->descripcion}} &nbsp; Estatus:@if($solicitud->estatusSolicitud == 'Pendiente')
                                        <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-default"><i class="fas fa-pause"></i> {{$solicitud->estatusSolicitud }} </a>
                                        @elseif($solicitud->estatusSolicitud == 'Rechazado')
                                        <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-danger"><i class="fas fa-ban"></i> {{$solicitud->estatusSolicitud }} </a>
                                        @elseif($solicitud->estatusSolicitud == 'Atendido')
                                        <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-success"><i class="fas fa-check"></i> {{$solicitud->estatusSolicitud }} </a>
                                        @elseif($solicitud->estatusSolicitud == 'Turnado')
                                        <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-warning"><i class="fas fa-undo-alt"></i> {{$solicitud->estatusSolicitud }} </a>
                                        @endif
                                    </span>
                                </div>
                                <span class="time">{{$solicitud->fechaCreacion}}</span>
                            </header>
                            <main>
                                <p class="evitar_desborde">Detalles: {{$solicitud->detallesServicio}}</p>
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
        new DataTable('#tablaSolicitudesEnviados2', {
            ordering: false, //disable ordering datatable
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                oPaginate: {
                    sNext: '<i class="fas fa-fast-forward"></i>',
                    sPrevious: '<i class="fas fa-fast-backward"></i>',
                    sFirst: '<i class="fas fa-fast-backward"></i>',
                    sLast: '<i class="fas fa-fast-forward"></i>'
                }
            },
            pageLength: 5,
        });
    });

    function detallesAtencion(idSolicitud) {

        window.location = 'recibidos/detalles/' + idSolicitud;
    }
</script>
