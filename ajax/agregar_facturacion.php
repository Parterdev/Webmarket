<?php

include('is_logged.php'); //Verificación de logueo
$session_id= session_id();
if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$cantidad=$_POST['cantidad'];}
if (isset($_POST['precio_venta'])){$precio_venta=$_POST['precio_venta'];}

	/* Conexión a la Database */
	require_once ("../models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../models/connect.php");//Contiene funcion que conecta a la base de datos
	//Archivo de funciones PHP
	include("../controllers/funciones.php");
if (!empty($id) and !empty($cantidad) and !empty($precio_venta))
{
$insert_transaccion=mysqli_query($con, "INSERT INTO transaccion (id_producto,cantidad_transaccion,precio_transaccion,id_sesion) VALUES ('$id','$cantidad','$precio_venta','$session_id')");

}
if (isset($_GET['id']))//codigo elimina un elemento del array
{
$id_trs=intval($_GET['id']);	
$delete=mysqli_query($con, "DELETE FROM transaccion WHERE id_transaccion='".$id_trs."'");
}
$simbolo_moneda=get_row('empresa','moneda', 'id_perfil', 1);
?>
<table class="table">
<tr>
	<th class='text-center'>Código producto</th>
	<th class='text-center'>Cantidad</th>
	<th>Descripción</th>
	<th class='text-right'>VALOR UNITARIO</th>
	<th class='text-right'>PRECIO TOTAL</th>
	<th></th>
</tr>
<?php
	$sumador_total=0;
	$sql=mysqli_query($con, "SELECT * FROM productos, transaccion WHERE productos.id_producto=transaccion.id_producto AND transaccion.id_sesion='".$session_id."'");
	while ($row=mysqli_fetch_array($sql))
	{
	$id_trs=$row["id_transaccion"];
	$codigo_producto=$row['codigo_producto'];
	$cantidad=$row['cantidad_transaccion'];
	$nombre_producto=$row['nombre_producto'];
	
	
	$precio_venta=$row['precio_transaccion'];
	$precio_venta_f=number_format($precio_venta,2);//Formateo variables
	$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
	$precio_total=$precio_venta_r*$cantidad;
	$precio_total_f=number_format($precio_total,2);//Precio total formateado
	$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
	$sumador_total+=$precio_total_r;//Sumador
	
		?>
		<tr>
			<td class='text-center'><?php echo $codigo_producto;?></td>
			<td class='text-center'><?php echo $cantidad;?></td>
			<td><?php echo $nombre_producto;?></td>
			<td class='text-right'><?php echo $precio_venta_f;?></td>
			<td class='text-right'><?php echo $precio_total_f;?></td>
			<td class='text-center'><a href="#" onclick="eliminar('<?php echo $id_trs ?>')"><i class="glyphicon glyphicon-trash" style="color:red"></i></a></td>
		</tr>		
		<?php
	}
	$impuesto=get_row('empresa','impuesto', 'id_perfil', 1);
	$subtotal=number_format($sumador_total,2,'.','');
	$total_iva=($subtotal * $impuesto )/100;
	$total_iva=number_format($total_iva,2,'.','');
	$total_factura=$subtotal+$total_iva;

?>
<tr>
	<td class='text-right' colspan=4>SUBTOTAL <?php echo $simbolo_moneda;?></td>
	<td class='text-right'><?php echo number_format($subtotal,2);?></td>
	<td></td>
</tr>
<tr>
	<td class='text-right' colspan=4>IVA (<?php echo $impuesto;?>)% <?php echo $simbolo_moneda;?></td>
	<td class='text-right'><?php echo number_format($total_iva,2);?></td>
	<td></td>
</tr>
<tr>
	<td class='text-right' colspan=4>TOTAL <?php echo $simbolo_moneda;?></td>
	<td class='text-right'><?php echo number_format($total_factura,2);?></td>
	<td></td>
</tr>

</table>
