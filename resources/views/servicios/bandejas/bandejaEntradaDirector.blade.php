<!-- tab navigation -->
<ul class="nav nav-pills" disabled>
    @foreach($departamentosDirector as $departamento)
    <li class="nav-item ">
        @if($loop->index == 0)
        <a id="liGenerales" class="nav-link active" data-bs-toggle="tab" href="#tab{{$departamento->idDepartamento}}">{{$departamento->departamento}}</a>
        @else
        <a id="liGenerales" class="nav-link" data-bs-toggle="tab" href="#tab{{$departamento->idDepartamento}}">{{$departamento->departamento}}</a>
        @endif
    </li>
    @endforeach
</ul>
<br>
<!-- tab content -->
<div class="tab-content">

    <input class="input-values" type="text" hidden value="{{count($departamentosDirector)}}" id="numUnidades">

    @foreach($departamentosDirector as $departamento)
    @if($loop->index == 0)
    <div class="tab-pane active" id="tab{{$departamento->idDepartamento}}">
        @else
        <div class="tab-pane" id="tab{{$departamento->idDepartamento}}">
            @endif
            <input class="input-values" type="text" hidden value="{{count($departamento->recibidos)}}" id="{{$departamento->idDepartamento}}-values">

            <inbox>
                <inbox-list>
                    <table id="tablaSolicitudes{{$loop->index}}" class="table table-hover table-bordered">
                        <thead id="">
                            <tr>
                                <th>Historial Bandeja de entrada
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($departamento->recibidos as $solicitud)
                            <tr class="">
                                <td class="">
                                    @if($solicitud->visto == 0 && $solicitud->estatusSolicitud =='Pendiente')
                                    <message-item id="item-{{$departamento->idDepartamento}}-{{$loop->index}}" class="unread solicitud {{$solicitud->estatusSolicitud}}" onclick="detallesAtencion({{$solicitud->id}})" style="cursor: pointer;  ">
                                        @else
                                        <message-item id="item-{{$departamento->idDepartamento}}-{{$loop->index}}" class="read solicitud {{$solicitud->estatusSolicitud}}" onclick="detallesAtencion({{$solicitud->id}})" style="cursor: pointer;  ">
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
        </div>

        @endforeach
    </div>



    <script type="text/javascript">
        $(document).ready(function() {
            var numUnidades = $('#numUnidades').val();
            for (var i = 0; i < numUnidades; i++) {
                new DataTable('#tablaSolicitudes' + i, {
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                });
            }
        });

        function detallesAtencion(idSolicitud) {


            window.location = 'recibidos/detalles/' + idSolicitud;
        }
    </script>