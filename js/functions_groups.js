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
function get_event_form_edit(id_event, opt){
	jQuery('#load_edit_form').html('<h4>Cargando Evento...</h4>');
	x_get_event_form_edit(id_event,opt, Content_universal);
}
function get_info_edit_event(opt){
	var id_event = document.getElementById('event_edit').value;
	if (opt == 1) {//eventos antiguos
		var category = document.getElementById('type_event').value;
		var name     = document.getElementById('name_event').value;
		var date     = document.getElementById('datepicker').value;
		if (category == '' || name == '' || date == '') {
			jQuery('#load_edit_event').html('<span style="color:red;font-size:14px;"><p>Por favor, inserta los campos obligados...</p></span>');
		}else{ x_edit_event(opt,id_event,category,name,date, Content_process); }
	}else{//eventos nuevos
		var type_e   = document.getElementById('type_ee').value;
		var category = document.getElementById('type_event').value;
		var name     = document.getElementById('name_event').value
		var date     = document.getElementById('datepicker').value;
		var srt_e 	 = document.getElementById('str_ee').value;
		var lvl_e 	 = document.getElementById('level_master').value;
		if (type_e == '' || category == '' || name == '' || date == '' || srt_e == '' || lvl_e == '') {
			jQuery('#load_edit_event').html('<span style="color:red;font-size:14px;"><p>Por favor, inserta los campos obligados...</p></span>');
		}else{
			name = type_e+'.'+category+'.'+name+'.'+srt_e+'.'+lvl_e;
			//alert(opt+','+id_event+','+category+','+name+','+date); 
			x_edit_event(opt,id_event,category,name,date, Content_process); 
		}
	}
}
function form_extra_weeks_event(weeks,date,background,hidde_cols){
	weeks = parseInt(weeks);
	jQuery("#form_add_weeks_event").html('<div class="form-group"><label for="weeks_to_add">&nbsp;&nbsp;&nbsp;&nbsp;Semanas a agregar<span style="color:red;"> *</span></label>&nbsp;&nbsp;&nbsp;&nbsp;<select id="weeks_to_add" style="width: 45px;"><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select><input type="hidden" id="weeks" value="'+weeks+'"/><input type="hidden" id="date" value="'+date+'"/><input type="hidden" id="background" value="'+background+'"/><input type="hidden" id="hidde_cols" value="'+hidde_cols+'"/>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" type="button" onclick="generate_extra_weeks_event();">Agregar</button></div>');
}
function generate_extra_weeks_event(){
	var weeks = document.getElementById('weeks_to_add').value;
	var week  = document.getElementById('weeks').value;
	var date  = document.getElementById('date').value;
	var backg = document.getElementById('background').value;
	var hiddn = document.getElementById('hidde_cols').value;
	var id_tm = document.getElementById('id_temp').value;
	if (weeks != "" && weeks != "undefined" && week != "" && week != "undefined") {
		x_generate_extra_weeks_event(weeks,week,date,backg,hiddn,id_tm, load_extra_weeks_event);
	}else{ swal("Error", "Por favor, selecciona las semanas que deseas agregar...", "error"); }
}
function get_block_event_info(row){
	var id_e = document.getElementById('ie').value;
	var wk_e = document.getElementById('wk').value;
	var nt_e = document.getElementById('nb').value;
	x_save_blocked_event_week(id_e,wk_e,nt_e,row, Content_process);
}
function del_block_event(id_gb,row){ x_del_block_event(id_gb,row, Content_process); }
function save_secundary_evento_like_master(){
	var new_name = document.getElementById('new_name_to_temp_sec').value;
	if(confirm("Deseas guardar este Evento Secundario como un Template llamado "+new_name+"? (Una vez que lo hagas, no podrás modificarlo mas desde aqui)")){
        //Si guardas esta información como un Evento Secundario, al pasar la fecha del Evento Principal, este evento será eliminado y no podrás ver mas informacón al respecto, deseas continuar?
        var id_event = document.getElementById('name_master_sel').value;
        var id_level = document.getElementById('level_master').value;
        x_save_secundary_evento_like_master(id_event,new_name,id_level, Content_process);
    }else{ jQuery().toastmessage('showErrorToast', 'Ninguna modificación realizada...'); }
}
function load_groups_withoutassign(){ x_load_groups_withoutassign(0, Content_universal); }
function change_span_name_event(){
	jQuery('#span_name_event').html('&nbsp;&nbsp;<input type="text" id="name_event" style="font-size: 25px;"><sup title="Cancelar entrada manual" style="cursor:pointer;" onclick="load_groups_withoutassign(); return false;"><span style="color:red; font-size:15px; padding:0 0 0 5px;"><b>x</b></span></sup>');
	jQuery('#btn_manual_name').html('<br><span style="color:red; font-size:15;"><b>Nota: </b></span><span style="font-size:15;"><b> El nuevo Grupo Secundario será guardado con este nombre.</b></span>');
}
