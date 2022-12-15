@extends('layouts.app', ['title' => __('User Profile')])

@section('content')

@include('users.partials.header', [
'title' => __('Lista Organos') ,
'description' => __('Aqui aparecen todas los departamentos que pertenecen a las distintas unidades del ICATECH'),
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
                            </a>Regresar a Catalogos</h3>
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
                            <th scope="col">Unidad</th>
                            <th scope="col">Organo</th>
                            <th scope="col">Titular</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Telefono</th>
                            <th scope="col">Celular</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($direcciones as $direccion)
                        <tr>
                            <td>
                                {{ $loop->index + 1}}<!-- indice -->
                            </td>
                            <td>
                                {{ $loop->index + 1}}<!-- indice -->
                            </td>
                            <td>
                                {{$direccion->organo}}
                            </td>
                            <td>
                                {{$direccion->titular}}
                            </td>
                            <td>
                                {{$direccion->correo}}
                            </td>
                            <td>
                                {{$direccion->telefono}}
                            </td>
                            <td>
                                {{$direccion->celular}}
                            </td>

                            <td>
                                <a href="{{route('organosEditar', $direccion->id)}}"><i class="fas fa-pen"></i></a>


                                <a href="" onclick="eliminacion({{$direccion->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"><i class="fas fa-trash"></i></a>

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
                    url: "/catalogos/organos/eliminar/" + idDepartamento,

                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        "id": idDepartamento,
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