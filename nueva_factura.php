<?php
session_start();
if(!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] !=1) {
    header("location: login.php");
    exit;
}

//Conección a la base de datos
    require_once ("./models/database.php"); //Variables para la conexión a la base
    require_once ("./models/connect.php"); //Función que conecta a la base de datos
  
    $active_facturas="active";
    $active_productos="";
    $active_clientes="";
    $active_usuarios="";
    $title="Nueva Factura | Micromercado @-----";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
  </head>
  <body>
    <?php
      include("navbar.php");
      ?>  
    <div class="container">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h4><i class='glyphicon glyphicon-file'></i> Nueva Factura</h4>
        </div>
        <div class="panel-body">
          <?php 
            include("modals/buscar_productos.php");
            include("modals/registro_clientes.php");
            include("modals/registro_productos.php"); 
            ?>
          <form class="form-horizontal" role="form" id="datos_factura">
            <div class="form-group row">
              <label for="nombre_cliente" class="col-md-1 control-label">Cliente</label>
              <div class="col-md-3">
                <input type="text" class="form-control input-sm" id="nombre_cliente" placeholder="Selecciona un cliente" required>
                <input id="id_cliente" type='hidden'>	
              </div>
              <label for="tel1" class="col-md-1 control-label">Teléfono</label>
              <div class="col-md-2">
                <input type="text" class="form-control input-sm" id="tel1" placeholder="Teléfono" readonly>
              </div>
              <label for="mail" class="col-md-1 control-label">Email</label>
              <div class="col-md-3">
                <input type="text" class="form-control input-sm" id="mail" placeholder="Email" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="empresa" class="col-md-1 control-label">Vendedor</label>
              <div class="col-md-3">
                <select class="form-control input-sm" id="id_vendedor">
                  <?php
                    $sql_vendedor=mysqli_query($con,"SELECT * FROM usuarios ORDER BY apellido");
                    while ($rw=mysqli_fetch_array($sql_vendedor)){
                    	$id_vendedor=$rw["id_usuario"];
                    	$nombre_vendedor=$rw["nombre"]." ".$rw["apellido"];
                    	if ($id_vendedor==$_SESSION['id_usuario']){
                    		$selected="selected";
                    	} else {
                    		$selected="";
                    	}
                    	?>
                  <option value="<?php echo $id_vendedor?>" <?php echo $selected;?>><?php echo $nombre_vendedor?></option>
                  <?php
                    }
                    ?>
                </select>
              </div>
              <label for="tel2" class="col-md-1 control-label">Fecha</label>
              <div class="col-md-2">
                <input type="text" class="form-control input-sm" id="fecha" value="<?php echo date("d/m/Y");?>" readonly>
              </div>
              <label for="email" class="col-md-1 control-label">Pago</label>
              <div class="col-md-3">
                <select class='form-control input-sm' id="condiciones">
                  <option value="1">Efectivo</option>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="pull-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nuevoProducto">
                <span class="glyphicon glyphicon-plus"></span> Crear producto
                </button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#nuevoCliente">
                <span class="glyphicon glyphicon-user"></span> Nuevo cliente
                </button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-shopping-cart"></span> Cargar productos
                </button>
                <button type="submit" class="btn btn-success">
                <span class="glyphicon glyphicon-print"></span> Imprimir factura
                </button>
              </div>
            </div>
          </form>
          <div id="resultados" class='col-md-12' style="margin-top:10px"></div>
          <!-- Carga los datos ajax -->			
        </div>
      </div>
      <div class="row-fluid">
        <div class="col-md-12">
        </div>
      </div>
    </div>
    <hr>
    <?php
      include("footer.php");
      ?>
    <script type="text/javascript" src="js/VentanaCentrada.js"></script>
    <script type="text/javascript" src="js/nueva_factura.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
      $(function() {
      				$("#nombre_cliente").autocomplete({
      					source: "./controllers/autocomplete_clients.php",
      					minLength: 2,
      					select: function(event, ui) {
      						event.preventDefault();
      						$('#id_cliente').val(ui.item.id_cliente);
      						$('#nombre_cliente').val(ui.item.nombre_cliente);
      						$('#tel1').val(ui.item.telefono_cliente);
      						$('#mail').val(ui.item.email_cliente);	
      					 }
      				});
      			});
      			
      $("#nombre_cliente" ).on( "keydown", function( event ) {
      				if (event.keyCode== $.ui.keyCode.LEFT || event.keyCode== $.ui.keyCode.RIGHT || event.keyCode== $.ui.keyCode.UP || event.keyCode== $.ui.keyCode.DOWN || event.keyCode== $.ui.keyCode.DELETE || event.keyCode== $.ui.keyCode.BACKSPACE )
      				{
      					$("#id_cliente" ).val("");
      					$("#tel1" ).val("");
      					$("#mail" ).val("");
      									
      				}
      				if (event.keyCode==$.ui.keyCode.DELETE){
      					$("#nombre_cliente" ).val("");
      					$("#id_cliente" ).val("");
      					$("#tel1" ).val("");
      					$("#mail" ).val("");
      				}
      	});	
    </script>
  </body>
</html>


