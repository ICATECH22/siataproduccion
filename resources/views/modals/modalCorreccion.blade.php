<div class="modal fade" id="modalCorrecion" tabindex="-1" aria-labelledby="modalCorreccionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCorreccionLabel">Corregir Servicio y Enviar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="corregirForm" action="{{route('corregirSolicitud',$detallesServicio->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            <main class="col-md-12 col-sm-12">
                <div class="form-row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="organo">Organo:</label>
                        <select style="width:100%;" class="form-control @error('organo')  is-invalid @enderror" aria-label=".form-select-md example" name="organo" id="organo">
                            <option class="{{ $errors->has('organo') ? ' is-invalid' : '' }}" value="">Selecciona un servicio</option>
                            @foreach ($organoAdmin as $item)
                                <option  value="{{ $item->id }}">{{ $item->organo }}</option>
                            @endforeach
                        </select>

                        @error('organo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="servicio">Servicios por departamento:</label>
                        <select style="width:100%;" class="form-control @error('servicio')  is-invalid @enderror" aria-label=".form-select-md example" name="servicio" id="servicio">
                            <option class="form-control form-control-alternative{{ $errors->has('servicio') ? ' is-invalid' : '' }}" selected="true" disabled="disabled">Selecciona un servicio</option>
                        </select>

                        @error('servicio')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-8 mb-3">
                        <label for="archivoNuevo"><span>Adjuntar nuevo archivo&hellip;</span></label>
                        <input type="file" name="archivoNuevo" id="archivoNuevo" class="form-control @error('archivoNuevo')  is-invalid @enderror" data-multiple-caption="{count} files selected" multiple />
                        @error('archivoNuevo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-12 mb-3">
                        <label for="descripcion">Servicios por departamento:</label>
                        <textarea class="form-control @error('descripcion')  is-invalid @enderror" id="detalles" name="descripcion" rows="12" placeholder="Escriba aqui los detalles de la solicitud"></textarea>
                        @error('descripcion')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </main>
            <button type="submit" class="btn btn-success">Enviar Corrección</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
