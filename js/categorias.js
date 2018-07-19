$(document).ready(function(){
    load(1);
});

//Buscar categorías almacenadas en el inventario
function load(page) {
    var q=$("#q").val();
    $("#loader").fadeIn('slow');
    $.ajax({
        url:'./ajax/buscar_categorias.php?action=ajax&page='+page+'&q='+q,
        beforeSend: function(objeto){
            $('#loader').html('<img src=""> Cargando...');    
        },
        success:function(data){
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    })
}

//Eliminar categorías
function eliminar(id) {
    var q=$("#q").val();
    if(confirm("Realmente deseas eliminar esta categoría")){
        $.ajax({
            type: "GET",
            url: "./ajax/buscar_categorias.php",
            data: "id="+id,"q":q,
            beforeSend: function(objeto){
                $("#resultados").html("Mensaje: Cargando...");
            },
            success: function(datos){
                $("#resultados").html(datos);
                load(1);
            }
        });
    }
}

//Almacenar nueva categoría
$("#guardar_categoria").submit(function(event) {
    $('#guardar_datos').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "ajax/nueva_categoria.php",
        data: parametros,
        beforeSend: function(objeto){
            $("#resultados_ajax").html("Mensaje: Cargando...");
        },
        success: function(datos){
            $("#resultados_ajax").html(datos);
            $('#guardar_datos').attr("disabled", false);
            load(1);
        }
    });
    event.preventDefault();
})

//Editar y actualizar datos de una categoría existente
$("#editar_categoria").submit(function(event){
    $('#actualizar_datos').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "ajax/editar_categoria.php",
        data: parametros,
        beforeSend: function(objeto){
            $("#resultados_ajax2").html("Mensaje: Cargando...");
        },
            success: function(datos){
            $("#resultados_ajax2").html(datos);
            $('#actualizar_datos').attr("disabled", false);
            load(1);
        }
    });
    event.preventDefault();
})

$('#myModal2').on('show.bs.modal', function(event){
    var button = $(event.relatedTarget) //Botón que activa el modal
    var nombre = button.data('nombre')
    var descripcion = button.data('descripcion')
    var id = button.data('id')
    var modal = $(this)
    modal.find('.modal-body #mod_nombre').val(nombre)
    modal.find('.modal-body #mod_descripcion').val(descripcion)
    modal.find('.modal-body #mod_id').val(id)
})


