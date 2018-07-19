<?php
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }

	/* Conexión a la Database*/
	require_once ("./models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("./models/connect.php");//Contiene funcion que conecta a la base de datos
	
	$active_categoria="active";
	$title="Categorias | Micromercado @-----";
?>
<html lang="es">
   <head>
   <meta charset="UTF-8">
      <?php include("head.php");?>
   </head>
   <body>
      <?php
         include("navbar.php");
         ?>
      <div class="container">
         <div class="panel panel-primary">
            <div class="panel-heading">
               <div class="btn-group pull-right">
                  <button type='button' class="btn btn-default" data-toggle="modal" data-target="#nuevoCliente"><span class="glyphicon glyphicon-plus" ></span> Nueva Categoría</button>
               </div>
               <h4><i class='glyphicon glyphicon-search'></i> Buscar Categorías</h4>
            </div>
            <div class="panel-body">
               <?php
                  include("./modals/editar_categorias.php");
                  include("./modals/registro_categorias.php");
                  ?>
               <form class="form-horizontal" role="form" id="datos_cotizacion">
                  <div class="form-group row">
                     <label for="q" class="col-md-2 control-label">Nombre</label>
                     <div class="col-md-5">
                        <input type="text" class="form-control" id="q" placeholder="Nombre de la categoría" onkeyup='load(1);'>
                     </div>
                     <div class="col-md-3">
                        <button type="button" class="btn btn-default" onclick='load(1);'>
                        <span class="glyphicon glyphicon-search" ></span> Buscar</button>
                        <span id="loader"></span>
                     </div>
                  </div>
               </form>
               <div id="resultados"></div>
               <!-- Carga los datos ajax -->
               <div class='outer_div'></div>
               <!-- Carga los datos ajax -->
            </div>
         </div>
      </div>
      <hr>
      <?php
         include("footer.php");
         ?>
      <script type="text/javascript" src="js/categorias.js"></script>
   </body>
</html>