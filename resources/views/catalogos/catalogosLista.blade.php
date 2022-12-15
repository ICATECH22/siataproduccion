@extends('layouts.app', ['title' => __('User Profile')])

@section('content')

@include('users.partials.header', [
'title' => __('Catalogos: ') ,
'description' => __('Aqui aparecen todas las solicitudes que han enviado las distintas areas y departamentos del Icatech'),
'class' => 'col-lg-12'
])


<div class="card shadow">
    <div class="table-responsive">
        <table id="myTable" class="table table-hover table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Catálogo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Unidades</td>
                    <td><a class="view" href="{{route('unidadesLista')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver"><i class="fas fa-eye"></i></a></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Organos</td>
                    <td><a class="view" href="{{route('organosLista')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver"><i class="fas fa-eye"></i></a></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Departamentos</td>
                    <td><a class="view" href="{{route('departamentosLista')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver"><i class="fas fa-eye"></i></a></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Servicios</td>
                    <td><a class="view" href="{{route('serviciosLista')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver"><i class="fas fa-eye"></i></a></td>
                </tr>

            </tbody>
        </table>
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

@endsection