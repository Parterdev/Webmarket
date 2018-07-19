<?php
    
    include('is_logged.php'); //Verificación de logueo
	/* Conexión a la Database */
	require_once ("../models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../models/connect.php");//Contiene funcion que conecta a la base de datos
    /* Archivo de funciones */
    include("../controllers/funciones.php");
    $action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if(isset($_GET['id'])) {
        $id_producto=intval($_GET['id']);
        if($delete1=mysqli_query($con, "DELETE FROM productos WHERE id_producto='".$id_producto."'")) {
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong>Los datos han sido eliminados exitosamente.
			</div>
            <?php        
        }else {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong>Ha ocurrido un error, por favor intenta nuevamente.
			</div>
            <?php
        }
    }

    if($action == 'ajax'){
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        //Función "strip_tags" la cual devuelve un string con las etiquetas HTML y PHP para ser eliminadas.
        $q=mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
        $id_categoria=intval($_REQUEST['id_categoria']);
        $aColumns=array('codigo_producto','nombre_producto'); //Columnas de busqueda
        $sTable="productos"; //Tabla productos
        $sWhere="";

        $sWhere="WHERE (";
        for ($i=0; $i<count($aColumns) ; $i++) {
            $sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';

        if($id_categoria>0) {
            $sWhere .="AND id_categoria='$id_categoria'";
        }
        $sWhere.=" ORDER BY id_producto DESC";
        include 'pagination.php'; //Archivo de paginación
        //Variables de paginacion
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 18; //Número de registros que se quieren mostrar
		$adjacents  = 4; //Espacio entre página después del número de adyacentes
        $offset = ($page - 1) * $per_page;
        //Conteo del número total de filas en la tabla
        $count_query=mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");
        $row=mysqli_fetch_array($count_query); //Obtención de fila de resultados del array numérico.
        $numrows=$row['numrows'];
        $total_pages=ceil($numrows/$per_page);
        $reload='./producto.php';
        //Consulta principal para buscar datos
        $sql="SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
        $query=mysqli_query($con,$sql);
        //Bucle a través de datos obtenidos
        if($numrows>0) {
            ?>
            <?php
            $nums=1;
            while($row=mysqli_fetch_array($query)) {
                $id_producto=$row['id_producto'];
				$codigo_producto=$row['codigo_producto'];
                $nombre_producto=$row['nombre_producto'];
                $estado_producto=$row['estado_producto'];
                if($estado_producto==1){$estado="Activo";}
                else{$estado="Inactivo";}
                $stock=$row['stock'];
                ?>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 thumb text-center ng-scope" ng-repeat="item in records">
					<a class="thumbnail" href="producto.php?id=<?php echo $id_producto;?>">
						<span title="Stock del producto" class="badge badge-default stock-counter ng-binding"><?php echo number_format($stock); ?></span>
						<span title="Low stock" class="low-stock-alert ng-hide" ng-show="item.current_quantity <= item.low_stock_threshold"><i class="fa fa-exclamation-triangle"></i></span>
						<img class="img-responsive" src="img/product_icon.png" alt="<?php echo $nombre_producto;?>">
					</a>
					<span class="thumb-name"><strong><?php echo $nombre_producto;?></strong></span>
					<span class="thumb-code ng-binding"><?php echo $codigo_producto;?></span>
                    <span class="thumb-name"><h5>Estado producto: <strong><?php echo $estado;?></h5></strong></span>
				</div>
                <?php
                if($nums%6==0){
                    echo "<div class='clearfix'></div>";
                }
                $nums++;
            }
            ?>
            <div class="clearfix"></div>
				<div class='row text-center'>
					<div ><?php
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?>
                </div>
			</div>
            <?php
        }
    }
?>