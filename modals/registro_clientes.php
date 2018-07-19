<?php
    if(isset($con)){
?>
<!-- Modal -->
<div class="modal fade" id="nuevoCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-plus'></i> Agregar nuevo cliente</h4>
         </div>
         <div class="modal-body">
            <form class="form-horizontal" method="post" id="guardar_cliente" name="guardar_cliente">
               <div id="resultados_ajax"></div>
               <div class="form-group">
                  <label for="nombre" class="col-sm-3 control-label">Nombres y apellidos</label>
                  <div class="col-sm-8">
                     <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombres y Apellidos" required>
                  </div>
               </div>
               <div class="form-group">
                  <label for="identificacion" class="col-sm-3 control-label">Número de cédula</label>
                  <div class="col-sm-8">
                     <input type="number" class="form-control" id="identificacion" name="identificacion" placeholder="Número de identificación">
                  </div>
               </div>
               <div class="form-group">
                  <label for="telefono" class="col-sm-3 control-label">Número telefónico</label>
                  <div class="col-sm-8">
                     <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Número telefónico" required>
                  </div>
               </div>
               <div class="form-group">
                  <label for="direccion" class="col-sm-3 control-label">Dirección</label>
                  <div class="col-sm-8">
                     <textarea class="form-control" id="direccion" name="direccion" placeholder="Dirección" maxlength="255" required></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <label for="email" class="col-sm-3 control-label">Correo electrónico</label>
                  <div class="col-sm-8">
                     <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                  </div>
               </div>
               <div class="form-group">
				<label for="estado" class="col-sm-3 control-label">Estado</label>
				<div class="col-sm-8">
					<select class="form-control" id="estado" name="estado" required>
						<option value="">Selecciona el estado del cliente</option>
						<option value="1">Activo</option>
						<option value="0">Inactivo</option>
					</select>
				</div>
				</div>
         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
         <button type="submit" class="btn btn-warning" id="guardar_datos">Guardar datos</button>
         </div>
         </form>
      </div>
   </div>
</div>
<?php
   }
   ?>