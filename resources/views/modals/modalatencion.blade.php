<div class="modal fade" id="modalAtender" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dar Seguimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmSeguimiento" method="POST">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Segerencia:</label>
            <textarea id="descripcion" placeholder="Escriba alguna sugerencia (Opcional)" name="descripcion" rows="8" cols="20" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <input type="file" name="archivoValidar" id="archivoValidar" class="form-control" />
          </div>
          <button type="submit" class="btn btn-success">Enviar</button>
        </form>
      </div>
    </div>
  </div>
</div>
