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
function get_info_new_event(){//Obtiene los datos del formulario de eventos/master para insertar en DB en PHP
	var type_event = document.getElementById('type_ee').value;//jQuery("#type_ee option:selected").text();
	var cate_event = document.getElementById('type_event').value;
	var name_event = (document.getElementById('name_ee').value).trim();
	var stre_event = (document.getElementById('str_ee').value).trim();
	var levl_event = document.getElementById('level_master').value;
	var date_event = (document.getElementById('datepicker').value).trim();
	//Comprobamos que no vengan datos obligatorios vacios
	if ( type_event == '' || cate_event == '' || name_event == '' || stre_event == '' || levl_event == '' || date_event == '' ) {//Si es Evento ocupa datepicker si es Master NO
		jQuery('#load').html('<span style="color:red;font-size:14px;"><p>Por favor, inserta los campos obligados...</p></span>');
	}
	else{
		var info_event = type_event+'.'+cate_event+'.'+name_event+'.'+stre_event+'.'+levl_event+'||'+date_event;
		x_save_new_event(info_event,1, Content_process);//1 = from come in Content_process
	}
}
function get_edit_list(filter,opc,order){//filter 0 default 1past, 2present, 3future //opc 1=Eventos, 2=Templates
	jQuery('li').removeClass('active');
	jQuery('#events').addClass('active');
	jQuery('.row').html('<h5 class="subtitle">Cargando Tabla...</h5><br />');
	jQuery('.pageicon').html('<span class="iconfa-pencil"></span>');
	jQuery('.pagetitle').html('<h5>Editar Grupos</h5><h1>Editar Grupos</h1>');
	x_get_events_table_editable(filter,opc,order, Content_universal); //content&&&id_or_class
}
