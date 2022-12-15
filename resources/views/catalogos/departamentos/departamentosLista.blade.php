@extends('layouts.app', ['title' => __('User Profile')])

@section('content')

@include('users.partials.header', [
'title' => __('Lista de Departamentos') ,
'description' => __('Aqui aparecen todas los departamentos que pertenecen a las distintas unidades del ICATECH'),
'class' => 'col-lg-12'
])
@if (session()->has('message'))
<div class="alert {{session('alert') ?? 'alert-info'}}">
    {{session('message')}}
    {{session()->forget('message')}}
</div>
@endif


<div class="row mt-5">
    <div class="col-xl-12 mb-5 mb-xl-0">
        <div class="card shadow">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0"><a href="{{route('catalogosLista')}}" class="back-button">
                                <i class="fas fa-chevron-left"></i>
                            </a>Departamentos</h3>
                    </div>
                    <div class="col text-right">
                        <a href="{{route('departamentosCrear')}}" class="btn btn-sm btn-primary">Agregar</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Organo a la que pertenece</th>
                            <th scope="col">Departamentos</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($departamentos as $departamento)
                        <tr>
                            <td>
                                {{ $loop->index + 1}}<!-- indice -->
                            </td>
                            <td>
                                {{$departamento->organo->organo}}
                            </td>
                            <td>
                                {{$departamento->departamento}}
                            </td>

                            <td>
                                <a href="{{route('departamentoActualizar', $departamento->id)}}" ><i class="fas fa-pen"></i></a>
                                <a href="{{route('asignarServicios', $departamento->id)}}" ><i class="fas fa-pen"></i></a>
                                

                                <a href="" onclick="eliminacion({{$departamento->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"><i class="fas fa-trash"></i></a>

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
</script>


<script>
    function eliminacion(idDepartamento) {

        event.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de eliminar este departamento?',
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
                    url: "/catalogos/departamentos/eliminar/" + idDepartamento,

                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        "idDepartamento": idDepartamento,
                    },
                    success: function(data) {
                        window.location = "{{ route('departamentosLista') }}";
                    }
                });
            }
        })
    }
</script>

@endsection