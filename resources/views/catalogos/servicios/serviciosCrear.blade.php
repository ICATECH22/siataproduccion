@extends('layouts.app', ['title' => __('User Profile')])

@section('content')

@include('users.partials.header', [
'title' => __('Registro de un servicio: ') ,
'description' => __('Escriba el nombre del servicio y luego seleccione al departamento al que pertenece'),
'class' => 'col-lg-12'
])

<div class="container-fluid mt--7">
    <div class="row">

        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">{{ __('Nueva departamento de Direccion') }}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-end">
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <h6 class="required">* : campos obligatorios</h6>
                            </div>
                        </div>
                    </div>
                    <form id="creacion" action="{{route('servicioGuardar')}}" method="POST">
                        @csrf
                        <div class="form-content">
                            <div class="col-md-6">
                                <div class="">
                                    <div class="form-group {{ $errors->has('descripcion') ? ' has-danger' : '' }}">
                                        <label for="">Descripción <span class="required">*</span></label>
                                        <input type="text" name="descripcion" class="form-control @error('descripcion')  is-invalid @enderror" placeholder="Descripción">
                                        @error('descripcion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="row justify-content-end">
                            <div class="col-md-6 col-sm-2 col align-self-end">
                                <!-- <a type="button" href="{{route('departamentosLista')}}" class="btnCancel">Cancelar</a> -->
                                <!-- <button type="button" class="btnCancel"><a href="" class="btnCancel">Cancelar</a></button> -->
                                <a type="button" href="{{route('serviciosLista')}}" class="btn btn-danger mt-4">{{ __('Cancelar') }}</a>
                                <a type="button" onclick="creacion()" class="btn btn-success mt-4">{{ __('Guardar') }}</a>

                            </div>
                        </div>
                </div>
                <br><br>



                </form>

            </div>
        </div>
    </div>
</div>
</div>



<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    function creacion() {
        event.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de guardar este registro?',
            imageUrl: '/images/iconos/exclamacion.PNG',
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

    $('#unidad').on('change', function() {
        console.log(this.value);
        $.ajax({
            url: '/unidad/getDepartamentos/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $("#idDepartamento").empty();
                var tipo = $("#idDireccion").children("option:selected").val();
                for (var i = 0; i < response.length; i++) {
                    var idUnidad = response[i]['idparent'];
                    if (idUnidad == tipo) {
                        $("#idDepartamento").append("<option value='" + response[i]['id'] + "'>" + response[i]['departamento'] + "</option>");
                    }
                }
                $("#idDepartamento").prepend("<option value='default' selected='true' disabled='disabled'>Selecciona una opción</option>")
            }
        });
    });
</script>
@endsection