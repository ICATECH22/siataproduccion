<inbox>
    <inbox-list>

        <table id="tablaSolicitudesEnviados" class="table table-hover table-bordered">
            <thead id="">
                <tr>
                    <th>Historial de Servicios Turnados
                </tr>
            </thead>

            <tbody>
                <tr class="">
                    <td class="">
                       @if (count($historial) > 0)
                            @foreach ($historial as $itemHistorial)
                                <message-item class="read solicitud" style="cursor: pointer; display:block;">
                                    <header>
                                        <div class="sender-info">
                                            <span class="subject">De: {{$itemHistorial->solicitante}}</span>
                                            <span class="from">
                                                <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-warning"><i class="fas fa-share-square"></i> {{ $itemHistorial->servicioDescripcion }}</a>
                                            </span>
                                        </div>
                                        <span class="time">Turnado a: {{ $itemHistorial->receptorTurnado }}</span>
                                    </header>
                                    <main>
                                        <p> Detalles: {{$itemHistorial->detallesServicio}}</p>
                                    </main>
                                </message-item>
                            @endforeach
                       @else
                        <message-item class="read solicitud" style="cursor: pointer; display:block;">
                                <header>
                                    <div class="sender-info">
                                        <span class="subject">No hay registros</span>
                                        <span class="from">
                                            <a style="pointer-events: none; cursor: default; color: white;" type="button" class="btn btn-default"><i class="fas fa-database"></i> No hay Datos</a>
                                        </span>
                                    </div>
                                    <span class="time"></span>
                                </header>
                        </message-item>
                       @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </inbox-list>
</inbox>
{{-- nuevo comentario --}}



<script type="text/javascript">
    $(document).ready(function() {
        new DataTable('#tablaSolicitudesEnviados', {
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
