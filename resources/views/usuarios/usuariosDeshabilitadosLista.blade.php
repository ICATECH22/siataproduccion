@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Lista de usuarios Deshabilitados: ') ,
'description' => __('Listado de usuarios deshabilitados que estan registrados en el sistema'),
'class' => 'col-lg-12'
])
@if (session()->has('message'))
<div class="alert {{session('alert') ?? 'alert-info'}}">
    {{session('message')}}
    {{session()->forget('message')}}
</div>
@endif

<div class="container-fluid">

    <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Usuarios</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('usuarioCrear') }}" class="btn btn-sm btn-primary">Registrar nuevo usuario</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Correo</th>
                                <th scope="col">Unidad</th>
                                <th scope="col">Puesto</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $usuario)
                            <tr>
                                <td>
                                    {{ $loop->index + 1}}<!-- indice -->
                                </td>
                                <td>
                                    {{$usuario->name}}
                                </td>
                                <td>
                                    {{$usuario->email}}
                                </td>

                                <td>
                                    {{$usuario->unidad}}
                                </td>
                                <td>
                                    puesto
                                </td>
                                <td>
                                    <div class="row w-auto ">
                                        <div class="col">
                                            <a href=""><i class="fas fa-key"></i></a>
                                            <a href="{{route('usuarioEditar', $usuario->idUsuario)}}"><i class="fas fa-pen"></i></a>

                                            <a href="" onclick="eliminacion({{$usuario->idUsuario}})"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function eliminacion(idUsuario) {

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
                    url: "/usuarios/desactivar/" + idUsuario,
                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        "idUsuario": idUsuario,
                    },
                    success: function(data) {
                        window.location = "{{ route('usuariosLista') }}";
                    }
                });
            }
        })
    }
</script>
@endsection