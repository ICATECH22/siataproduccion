@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => __('Crear servicio: ') ,
'description' => __('Seleccion un servicio de la lista y añade archivos(opciona)'),
'class' => 'col-lg-12'
])


<div class="container-fluid mt--7">
    <div class="row">

        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0"><a href="{{route('bandejaEntrada')}}" class="back-button">
                                <i class="fas fa-chevron-left"></i>
                            </a>Regresar bandeja de entrada</h3>
                    </div>
                </div>

                <form id="creacion" action="{{route('guardarServicio')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <main class="col-md-12 col-sm-12">
                        <p class="text-center">Nuevo Servicio</p>

                        <div class="form-row mb-3">

                            <div class="col-6 col-sm-6">
                                <label for="organo" class="col-form-label">Organo Administrativo:</label>
                                <select style="width:100%;" class="form-control @error('organo')  is-invalid @enderror" aria-label=".form-select-md example" name="organo" id="organoSelect">
                                    <option class="form-control" value="">Selecciona un servicio</option>
                                    @foreach ($organoAdm as $item)
                                        <option value="{{ $item->id }}"> {{ $item->organo }}</option>
                                    @endforeach
                                </select>

                                @error('organo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-6 col-sm-6">
                                <label for="servicio" class="col-form-label">Servicios por departamento:</label>
                                <select style="width:100%;" class="form-control @error('servicio')  is-invalid @enderror" aria-label=".form-select-md example" name="servicio" id="servicioSelect">
                                    <option class="form-control form-control-alternative{{ $errors->has('servicio') ? ' is-invalid' : '' }}" selected="true" disabled="disabled">Selecciona un servicio</option>
                                </select>

                                @error('servicio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 ml-auto">
                                <div class="toolbar" role="toolbar">
                                    <button type="button" class="btn btn-light">
                                        <span class="fa fa-paperclip"></span>
                                        <input hidden type="file" name="archivo" id="file-1" class="inputfile2 @error('archivo')  is-invalid @enderror" data-multiple-caption="{count} files selected" multiple />
                                        <label for="file-1"><span>Adjuntar archivo&hellip;</span></label>
                                        @error('archivo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </button>
                                </div>



                                <div class="form-group mt-4">
                                    <textarea class="form-control @error('descripcion')  is-invalid @enderror" id="detalles" name="descripcion" rows="12" placeholder="Escriba aqui los detalles de la solicitud"></textarea>
                                    @error('descripcion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <a type="button" href="{{route('departamentosLista')}}" class="btn btn-danger mt-4">{{ __('Cancelar') }}</a>
                                    <button onclick="creacion()" class="btn btn-success mt-4">{{ __('Enviar') }} </button>

                                </div>
                            </div>
                        </div>
                    </main>

                </form>
            </div>
        </div>

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
    });
    function creacion() {

        event.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de enviar este registro?',
            icon: 'warning',
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            showCancelButton: true,
            cancelButtonColor: '#656665',
            confirmButtonColor: '#4897D0',
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('creacion').submit();
            }
        })
    }
    $(document).ready(function(){
        // $("#unidad").change(function() {
        //     var id = $(this).children(":selected").attr("value");
        //     let url = "{{ route('unidadOrgano', ":idUnidad") }}";
        //     url = url.replace(":idUnidad", id);
        //     console.log(url);
        //     $.ajax({
        //         url: url,
        //         type: 'get',
        //         dataType: 'json',
        //         success: function(response) {
        //             console.log(response)
        //             $("#organoSelect").empty();

        //             for (var i = 0; i < response.length; i++) {
        //                 var idUnidad = response[i]['idparent'];
        //                 $("#organoSelect").append("<option value='" + response[i]['id'] + "'>" + response[i]['organo'] + "</option>");
        //             }
        //             $("#organoSelect").prepend("<option value='default' selected='true' disabled='disabled'>Selecciona una opción</option>")
        //         }
        //     });
        // });

        $("#organoSelect").change(function() {
            var id = $(this).children(":selected").attr("value");
            console.log(id);
            $.ajax({
                url: '/departamentos/servicios/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $("#servicioSelect").empty();
                    var departamento = '';
                    for (var i = 0; i < response.length; i++) {
                        if(departamento != response[i]['departamento'] ){
                            $("#servicioSelect").append("<option disabled> ────────────────────────────────────────────────── </option>");
                            $("#servicioSelect").append("<option disabled>" + response[i]['departamento'] + "</option>");
                            departamento = response[i]['departamento'];
                        }
                        $("#servicioSelect").append("<option value='" + response[i]['idServicio'] + "-"+response[i]['idDepartamento']+"'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + response[i]['descripcion'] + "</option>");

                    }
                    $("#servicioSelect").prepend("<option value='default' selected='true' disabled='disabled'>Selecciona una opción</option>")
                }
            });


        });
    });
</script>

@endsection
