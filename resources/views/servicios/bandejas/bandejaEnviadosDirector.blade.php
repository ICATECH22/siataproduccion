<ul class="nav nav-pills" disabled>
    @foreach($departamentosDirector as $unidad)
    <li class="nav-item ">
        @if($loop->index == 0)
        <a id="liGenerales" class="nav-link active" data-bs-toggle="tab" href="#tab{{$unidad->idDepartamento}}">{{$unidad->departamento}}</a>
        @else
        <a id="liGenerales" class="nav-link" data-bs-toggle="tab" href="#tab{{$unidad->idDepartamento}}">{{$unidad->departamento}}</a>
        @endif
    </li>
    @endforeach
</ul>
<div class="tab-content">

    <input class="input-values" type="text" hidden value="{{count($departamentosDirector)}}" id="numUnidades">

    <br>
    @foreach($departamentosDirector as $unidad)
    @if($loop->index == 0)
    <div class="tab-pane active" id="tab{{$unidad->idDepartamento}}">
        @else
        <div class="tab-pane" id="tab{{$unidad->idDepartamento}}">
            @endif
            <inbox>
                <inbox-list>

                    <table id="tablaEnviados{{$loop->index}}" class="table table-hover table-bordered">
                        <thead id="">
                            <tr>
                                <th>Historial Enviados
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($unidad->enviados as $solicitud)
                            <tr>
                                <td>
                                    @if($solicitud->visto == 0 && $solicitud->estatusSolicitud =='Pendiente')
                                    <message-item class="unread solicitud {{$solicitud->estatusSolicitud}}"" onclick=" verAtencion({{$solicitud->id}})" style="cursor: pointer;  ">
                                        @else
                                        <message-item class="read solicitud {{$solicitud->estatusSolicitud}}"" onclick=" verAtencion({{$solicitud->id}})" style="cursor: pointer;  ">
                                            @endif
                                            <header>
                                                <div class="sender-info">
                                                    <span class="subject">De: {{$solicitud->departamentoSolicitante}}</span>
                                                    <span class="from">
                                                        Solicitud: {{$solicitud->descripcion}} &nbsp; Estatus:@if($solicitud->estatusSolicitud == 'Pendiente')
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
                                                <p>Detalles: {{$solicitud->detallesServicio}}</p>
                                            </main>
                                        </message-item>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </inbox-list>
            </inbox>
        </div>

        @endforeach
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var numUnidades = $('#numUnidades').val();
            for (var i = 0; i < numUnidades; i++) {
                new DataTable('#tablaEnviados' + i, {
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                });
            }
        });
    </script>