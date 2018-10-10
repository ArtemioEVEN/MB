function get_events_form(){// Llama al formulario para agregar EVENTOS o MASTERS
	jQuery('li').removeClass('active');//Para eliminar el señuelo de activo en cualquier otra opcion
	jQuery('#events').addClass('active');//Para activar el señuelo de activo en la opcion seleccionada
	jQuery('.row').html('<h5 class="subtitle">Cargando formulario para agregar Grupos...</h5><br />');//Mensaje de espera
	jQuery('.pageicon').html('<span class="iconfa-book"></span>');//Cambiamos el icono en titulo
	jQuery('.pagetitle').html('<h5>Agregar, Editar y Consultar</h5><h1>Definir Grupos</h1>');//Cambiamos mensaje en titulo
	x_get_events_form(0, Content_general);//Llamada a PHP
}
function get_events_table(filter,option){// Muestra EVENTOS o MASTERS
	jQuery('.second_content').html('<h5 class="subtitle">Cargando Grupos...</h5><br />');//Definir <div class="second_content"></div> antes de usar la caida en el div
	x_get_events_table(filter,option, Content_second);
}
