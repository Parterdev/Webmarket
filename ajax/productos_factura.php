<?php
    
    include('is_logged.php'); //Verificación de logueo
	/* Conexión a la Database */
	require_once ("../models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../models/connect.php");//Contiene funcion que conecta a la base de datos

    $action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL) ?$_REQUEST['action']:'';
    if($action == 'ajax') {
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        //Función "strip_tags" la cual devuelve un string con las etiquetas HTML y PHP para ser eliminadas.
        $q=mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
        $aColumns = array('codigo_producto','nombre_producto'); //Columnas de busqueda de la tabla productos. 
        $sTable="productos";
        $sWhere="";
        if($_GET['q'] !="") {
            $sWhere="WHERE (";
            for($i=0; $i<count($aColumns); $i++) {
                $sWhere.=$aColumns[$i]."LIKE '%".$q."%' OR ";
            }
            $sWhere=substr_replace($sWhere, "", -3);
            $sWhere.= ')';
		}
			include 'pagination.php'; //Archivo de paginación
            //Variables de paginacion
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		    $per_page = 5; //Número de registros que se quieren mostrar
		    $adjacents  = 4; //Espacio entre página después del número de adyacentes
            $offset = ($page - 1) * $per_page;
            //Conteo del número total de filas en la tabla
            $count_query=mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");
            $row=mysqli_fetch_array($count_query); //Obtención de fila de resultados del array numérico.
            $numrows=$row['numrows'];
            $total_pages=ceil($numrows/$per_page);
            $reload='./index.php';
            //Consulta principal para buscar datos
            $sql="SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
            $query=mysqli_query($con,$sql);
            //Bucle a través de datos obtenidos
            if($numrows>0) {
                ?>
                <div class="table-responsive">
			    <table class="table">
				<tr class="bg-primary">
					<th>Código de producto</th>
					<th>Nombre del producto</th>
					<th><span class="pull-right">Cantidad</span></th>
					<th><span class="pull-right">$Precio</span></th>
					<th class='text-center' style="width: 36px;">Agregar</th>
				</tr>
				<?php
				while($row=mysqli_fetch_array($query)){
					$id_producto=$row['id_producto'];
					$codigo_producto=$row['codigo_producto'];
					$nombre_producto=$row['nombre_producto'];
					$precio_venta=$row["precio_producto"];
					$precio_venta=number_format($precio_venta,2);
					?>
					<tr>
						<td><?php echo $codigo_producto; ?></td>
						<td><?php echo $nombre_producto; ?></td>
						<td class='col-xs-1'>
						<div class="pull-right">
						<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $id_producto; ?>"  value="1" >
						</div></td>
						<td class='col-xs-2'><div class="pull-right">
						<input type="text" class="form-control" style="text-align:right" id="precio_venta_<?php echo $id_producto; ?>"  value="<?php echo $precio_venta;?>" >
						</div></td>
						<td class='text-center'><a class='btn btn-primary'href="#" onclick="agregar('<?php echo $id_producto ?>')"><i class="glyphicon glyphicon-plus"></i></a></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=5><span class="pull-right"><?
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
            <?php
            }
		}
	?>