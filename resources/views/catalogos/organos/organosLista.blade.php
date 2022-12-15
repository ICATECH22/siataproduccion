@extends('layouts.app', ['title' => __('User Profile')])

@section('content')

@include('users.partials.header', [
'title' => __('Lista de unidades') ,
'description' => __('Aqui aparecen todas las unidades que pertenecen al ICATECH'),
'class' => 'col-lg-12'
])


<div class="container-fluid row mt--7">
    <div class="col-xl-12 mb-5 mb-xl-0">
        <div class="card shadow">
            @if (session()->has('message'))
            <div class="alert {{session('alert') ?? 'alert-info'}}">
                {{session('message')}}
                {{session()->forget('message')}}
            </div>
            @endif
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0"><a href="{{route('catalogosLista')}}" class="back-button">
                                <i class="fas fa-chevron-left"></i>
                            </a>Organos Administrativo</h3>
                    </div>
                    <div class="col text-right">
                        <a href="{{route('organosCrear')}}" class="btn btn-sm btn-primary">Agregar</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Direccion</th>
                            <th scope="col">Area</th>
                            <th scope="col">Titular</th>
                            <th scope="col">Puesto</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Telefono</th>
                            <th scope="col">Celular</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($unidades as $unidad)
                        <tr>
                            <td>
                                {{ $loop->index + 1}}<!-- indice -->
                            </td>
                            <td>
                                {{ $unidad->direccion ??''}}<!-- indice -->
                            </td>
                            <td>
                                {{$unidad->area ?? ''}}
                            </td>
                            <td>
                                {{$unidad->titular ??''}}
                            </td>
                            <td>
                                {{$unidad->puesto ??''}}
                            </td>
                            <td>
                                {{$unidad->correo ??''}}
                            </td>
                            <td>
                                {{$unidad->telefono ??''}}
                            </td>
                            <td>
                                {{$unidad->celular ??''}}
                            </td>

                            <td>
                                <a href="{{route('organosEditar', $unidad->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"><i class="fas fa-pen"></i></a>

                                <a href="" onclick="eliminacion({{$unidad->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"><i class="fas fa-trash"></i></a>

                            </td>

                            <!-- +"direccion": "Unidad Ejecutiva"
                            +"titular": "Ing. Alejandro Montoya Ruiz"
                            +"puesto": "Jefe Área de Informática"
                            +"correo": "informatica@icatech.chiapas.gob.mx"
                            +"telefono": "(961) 6121621 / 6127121. Ext. 110"
                            +"celular": nul -->
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "scrollX": true,
            responsive: true,
            "order": [
                [0, 'asc']
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        // show the alert
        $(".alert").first().hide().slideDown(200).delay(2000).slideUp(500, function() {
            $(this).remove();
        });
    });
</script>


<script>
    function eliminacion(idOrgano) {

        event.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de eliminar este usuario?',
            imageUrl: '/images/iconos/exclamacion.PNG',
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            showCancelButton: true,
            cancelButtonColor: '#656665',
            confirmButtonColor: '#9f2241',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/catalogos/organos/eliminar/" + idOrgano,

                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        "idOrgano": idOrgano,
                    },
                    success: function(data) {
                        window.location = "{{ route('organosLista') }}";
                    }
                });
            }
        })
    }
</script>

@endsection