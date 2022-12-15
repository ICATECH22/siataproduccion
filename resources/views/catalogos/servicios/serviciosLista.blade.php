@extends('layouts.app', ['title' => __('User Profile')])

@section('content')

@include('users.partials.header', [
'title' => __('Lista de servicios') ,
'description' => __('Aqui aparecen todos los servicios disponibles de acuerdo a los distintos departamentos del ICATECH'),
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
                            </a>Servicios</h3>
                    </div>
                    <div class="col text-right">
                        <a href="{{route('servicioCrear')}}" class="btn btn-sm btn-primary">Agregar</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Servicio</th>
                            <th scope="col">Departamento que ofrece el servicio</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($servicios as $servicio)
                        <tr>
                            <td>
                                {{ $loop->index + 1}}<!-- indice -->
                            </td>
                            <td>
                                {{$servicio->descripcion}}
                            </td>
                            <td>
                                {{$servicio->departamento->area}}
                            </td>

                            <td>
                                <a href="{{route('servicioEditar', $servicio->idServicio)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"><i class="fas fa-pen"></i></a>

                                <a href="" onclick="eliminacion({{$servicio->idServicio}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"><i class="fas fa-trash"></i></a>

                            </td>

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

    $(document).ready(function() {
        // show the alert
        $(".alert").first().hide().slideDown(200).delay(2000).slideUp(500, function() {
            $(this).remove();
        });
    });
</script>


<script>
    function eliminacion(idServicio) {

        event.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de eliminar este servicio?',
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
                    url: "/catalogos/servicios/eliminar/" + idServicio,

                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        "idServicio": idServicio,
                    },
                    success: function(data) {
                        window.location = "{{ route('serviciosLista') }}";
                    }
                });
            }
        })
    }
</script>

@endsection