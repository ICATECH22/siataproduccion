<div class="modal fade" id="turnadoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Turnar la Solicitud:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="transferirForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="unidades">Turnar a: <span class="form-control-label">*</span></label>
                    <select style="width:100%;" class="form-control  @error('servicio')  is-invalid @enderror" aria-label=".form-select-md example" name="unidades" id="unidades">
                        <option value="" selected>Selecciona una Unidad</option>
                        @foreach ($unidades as $unidad)
                                <option  value="{{ $unidad->idUnidad }}" style="background-color: #3332;">Unidad: {{ $unidad->descripcion }}</option>
                        @endforeach
                    </select>
                    @error('servicio')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="organos">Organo Administrativo: <span class="form-control-label">*</span></label>
                    <select style="width:100%;" class="form-control  @error('servicio')  is-invalid @enderror" aria-label=".form-select-md example" name="organos" id="organos">
                        <option class="form-control " value="">Selecciona un Organo Administrativo</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="deptos">Departamentos: <span class="form-control-label">*</span></label>
                    <select style="width:100%;" class="form-control  @error('servicio')  is-invalid @enderror" aria-label=".form-select-md example" name="deptos" id="deptos">
                        <option class="form-control " value="">Selecciona un Departamento</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="servicios">Servicios: <span class="form-control-label">*</span></label>
                    <select style="width:100%;" class="form-control  @error('servicio')  is-invalid @enderror" aria-label=".form-select-md example" name="servicios" id="servicios">
                        <option class="form-control " value="">Selecciona un Servicio</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-8 mb-3">
                    <label for="Notas">Observaciones</label>
                    <textarea id="detalles" placeholder="Escriba aqui las observaciones de la transferencia" name="descripcionTransferencia" rows="8" cols="20" class="form-control @error('descripcionTransferencia')  is-invalid @enderror"></textarea>
                    @error('descripcionTransferencia')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <input type="file" name="archivoReturnar" id="archivoReturnar" class="form-control @error('archivo')  is-invalid @enderror"/>
                    @error('archivo')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-success">Re-turnar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
