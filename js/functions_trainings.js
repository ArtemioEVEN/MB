function get_events_form_add_trains(option){// option 0 = Eventos, 1 = Masters
	if (option == 1) {//Si es para Templates
		jQuery('li').removeClass('active');//Para eliminar el señuelo de activo en cualquier otra opcion
		jQuery('#trains').addClass('active');//Para activar el señuelo de activo en la opcion seleccionada
		jQuery('.row').html('<h5 class="subtitle">Cargando formulario para asignar Entrenamientos...</h5><br />');//Mensaje de espera
		jQuery('.pageicon').html('<span class="iconfa-table"></span>');//Cambiamos el icono en titulo
		jQuery('.pagetitle').html('<h5>Asignar, Modificar y Consultar</h5><h1>Elaborar Template</h1>');//Cambiamos mensaje en titulo
		x_get_events_form_add_trains(option,'', Content_general);
	}
	if (option == 2) {
		jQuery('#extra_notes').html('');
		jQuery('.col-md-16').html('<h5 class="subtitle">Cargando información template...</h5><br />');
		var info_assigment = document.getElementById('info_assigment').value;
		var weeks_before = jQuery("#weeks_before").val();
		var d_e = jQuery("#d_e").val();
		x_get_events_form_add_trains__(option,info_assigment,weeks_before,d_e, Content_process);
	}
}
function load_wizard_table_trains(id_level_base, id_position, type){//id_level from DB (1=baja 2= media 3=alta), id_position in wisard content (1,2,3,4,...), type 0 = evento y 1 = master
	//Tomamos espacio en memoria para notificaciones
	var messageDiv = jQuery('#step1_wizard'); // get the reference of the div
	jQuery('#step'+id_position+'_wizard').html('');
	var id_event_base = '';
	if(!document.getElementById('name_master_sel_base') || document.getElementById('name_master_sel_base').value == ''){
		id_event_base = 'new_base';
	}else{
		id_event_base = document.getElementById('name_master_sel_base').value;
	}
	var name_event 	  = document.getElementById('name_event').value;
	var type_master   = document.getElementById('type_master').value;
	var level_master  = document.getElementById('level_master').value;
	var weeks_train   = document.getElementById('weeks_train').value;
	jQuery('#step'+id_position+'_wizard').html('<h5>Cargando tabla...</h5>');
	if (id_event_base === '' || id_level_base === '' || name_event == '' || type_master == '' || level_master == '' || weeks_train  == '') { 
		jQuery('#step1_wizard').html('<span style="color:red;font-size:14px;"><p>Por favor, introduce y/o selecciona los valores obligatorios...</p></span>');
	}else{
		jQuery('#step1_wizard').html('<span style="color:green;font-size:14px;"><p>Cargando información en los siguientes pasos...</p></span>');
		setTimeout(function(){ jQuery('#step1_wizard').html(''); }, 2000);
		//mostramos mensaje de aviso por 5 segundos
		x_load_wizard_table_trains(name_event,type_master,level_master,weeks_train, id_event_base,id_level_base,id_position,type,0,0, Content_universal);//Carga tipo 1 (Sencilla)
		x_load_wizard_table_trains(name_event,type_master,level_master,weeks_train, id_event_base,id_level_base,3,2,0,0, Content_universal);// id_event = id evento DB, id_level = nivel de master, position = numero de wizard, type = 1 Sencilla, 2 Completa
		x_load_wizard_table_trains(name_event,type_master,level_master,weeks_train, id_event_base,id_level_base,4,1,0,0, Content_universal);
	}
}
function load_wizard_table_trains_direct(id_event_base,id_level_base,id_position,type){//id_level from DB (1=baja 2= media 3=alta), id_position in wisard content (1,2,3,4,...), type 0 = evento y 1 = master
	//Tomamos espacio en memoria para notificaciones
	var messageDiv = jQuery('#step1_wizard'); // get the reference of the div
	jQuery('#step'+id_position+'_wizard').html('');
	var name_event 	  = document.getElementById('name_event').value;
	var type_master   = document.getElementById('type_master').value;
	var level_master  = id_level_base;
	var weeks_train   = document.getElementById('weeks_train').value;
	jQuery('#step'+id_position+'_wizard').html('<h5>Cargando tabla...</h5>');
	if (id_event_base === '' || id_level_base === '' || name_event == '' || type_master == '' || level_master == '' || weeks_train  == '') {
		swal("Error", "Por favor, introduce y/o selecciona los valores obligatorios...", "error");
	}else{
		if (name_event == document.getElementById('name_event_orig').value) {
			swal("Error", "El Nombre del Template Alterno no debe ser igual al Template Original...", "error");
		}else{
			jQuery('#step1_wizard').html('<span style="color:green;font-size:14px;"><p>Cargando información en los siguientes pasos...</p></span>');
			setTimeout(function(){ jQuery('#step1_wizard').html(''); }, 2000);
			var weeks_bef = "";
			var weeks_pos = "";
			if(!document.getElementById('weeks_train_b') || document.getElementById('weeks_train_b').value == ''){ weeks_bef = 0; }else{ weeks_bef = document.getElementById('weeks_train_b').value; }
			if(!document.getElementById('weeks_train_p') || document.getElementById('weeks_train_p').value == ''){ weeks_pos = 0; }else{ weeks_pos = document.getElementById('weeks_train_p').value; }
			x_load_wizard_table_trains(name_event,type_master,level_master,weeks_train, id_event_base,id_level_base,id_position,type,weeks_bef,weeks_pos, Content_universal);//Carga tipo 1 (Sencilla)
			x_load_wizard_table_trains(name_event,type_master,level_master,weeks_train, id_event_base,id_level_base,3,2,weeks_bef,weeks_pos, Content_universal);// id_event = id evento DB, id_level = nivel de master, position = numero de wizard, type = 1 Sencilla, 2 Completa
			x_load_wizard_table_trains(name_event,type_master,level_master,weeks_train, id_event_base,id_level_base,4,1,weeks_bef,weeks_pos, Content_universal);
		}
	}
}
function load_prev_session_value(prev_session, id_input, row, id_pop){//$session_training, $id_input, $row, $type,
	if(jQuery("#anterior_session_week").prop('checked') == true){
		prev_session = replace_string(prev_session, "+", "mmaass");
		x_show_session_training_form(prev_session,id_input,row,id_pop,prev_session, Content_popup);
	}else{
		prev_session = replace_string(prev_session, "+", "mmaass");
		x_show_session_training_form('',id_input,row,id_pop,prev_session, Content_popup);
	}
}
function load_prev_session_value_all_column(prev_session, id_input, row, id_pop){
	var all_values = ''; var input_val = '';
	var weeks = parseInt(document.getElementById('weeks_temp').value);
	var id_base = id_input.substr(0,12);
	var actual_pos = parseInt(id_input.substr(12));
	if(jQuery("#anterior_session_week_all_column").prop('checked') == true){
		if(confirm("Al completar la columna, la sesion de la fila anterior se duplicara en el resto de la columna,\npara guardar estos cambios deberas hacer click en 'Guardar Cambios'\nDeseas continuar?")){
			x_show_session_training_form(prev_session,id_input,row,id_pop,replace_string(prev_session, "+", "mmaass"), Content_popup);
			var class_note = '';
			//////FALTA QUE SE DESGLOCE LA SESSION EN LA TABLA 2
			for (var i=actual_pos; i<=weeks; i++) {
				jQuery('.'+id_base+i).val(prev_session);
				if (id_pop != 3) {
					jQuery('.'+id_base+i+'_c').val(prev_session);
					jQuery('.'+id_base+i+'_s').val(prev_session);
					calculate_session_try(prev_session, i, id_base+i);
				}else{
					load_data_summary_value(i, 0);
				}
			}
		}
	} close_popup(id_pop);
}
function show_popup(type, data, id_input, row){//type: tipo de contenido que mostrará, data: Datos que ocupa para mostrar la info
	//SE EJECUTARA EN PHP (functions_trainings.php)
	var prev_ses = '';
	var id_table = '';
	var prev_input = id_input;
	if (prev_input.slice(-2) == '_c') {
		id_table = '_c';
		prev_input = prev_input.slice(0,-2);
	}
	var last_2 = prev_input.slice(-2);
	//Comprobamos que los ultimos dos digitos sean numeros
	(/^\d+$/.test(last_2)) ? prev_input = prev_input.slice(0,-2) : prev_input = prev_input.slice(0,-1);
	if ((row-1) > 0) {
		prev_ses = document.getElementById(prev_input+(row-1)+id_table).value;
		prev_ses = replace_string(prev_ses, "+", "mmaass");
	}
	data = replace_string(data, "+", "mmaass");
	x_show_session_training_form(data,id_input,row,type,prev_ses, Content_popup);
	jQuery("#hider_"+type).fadeIn("slow");
    jQuery('#popup_box_'+type).fadeIn("slow");
}
function load_data_summary_value(num_row, type){//Obtiene todos los campos de session en una fila determianda, Type 0 = Tabla1, 1 = Tabla2
	var count = 0;
	var input_val  = '';
	var all_values = '';
	//var type = 0;///0 Summary, 1 Master
	var identifier = '';
	if (type == 1) { identifier = '_c'; }
	if (type == 0) { identifier = ''; }
	for (var i=1; i <= 7; i++) {
		for (var j=1; j <= 3; j++) {
			//alert('i: '+i+', j: '+j+', num_row: '+num_row); //alert('#session_'+j+'_'+i+'_'+num_row+': '+input_val);
			input_val = document.getElementById('session_'+j+'_'+i+'_'+num_row+identifier).value;
			if (input_val != '') { all_values = all_values+input_val+'@&@'; }
		}
	}
	all_values = all_values.substring(0, all_values.length-3);//Removemos ultimo 3 caracteres de la cadena
	x_calculate_values_session(num_row,all_values,type, Content_json_tss);//Content_universal
}
function show_value_tables(row, value, id){
	value = value.toUpperCase();
	// Desde Tabla 1
	var last_2 = id.slice(-2);
	//Comprobamos que los ultimos dos digitos sean numeros
	(/^\d+$/.test(last_2)) ? id = id.slice(0,-2) : id = id.slice(0,-1);
	var with_out_numbers = id.replace(/[0-9]/g, '');
	if (with_out_numbers == 'cicle_week_') { 
		jQuery('.'+id+row).val(value);
		jQuery('.'+id+row+'_c').val(value); /* De Tabla 1 a Tabla 2 //input*/
		jQuery('.'+id+row+'_s').val(value); /* De Tabla 1 a Tabla 3 //input*/
	}else{
		if (with_out_numbers == 'session___') {//Para copiar las sessiones de tabla 1 a Tabla 2 y 3
			jQuery('.'+id+row+'_c').val(value); //1 a 2
			jQuery('.'+id+row+'_s').val(value); //1 a 3
			x_desintegrate_session(row,value,id, Content_json_tss);
		}
		if (with_out_numbers == 'cicle_week__') {
			if (last_2 == '_c') {
				jQuery('.cicle_week_'+row+'_c').val(value);
			}
			jQuery('.cicle_week_'+row+'_s').val(value);
		}
	}
}
function save_all_data_db(opc){//opc 1 = completamente nuevo, opc 2 = master sin sessiones, opc 3 = master con sessiones
	jQuery('.load').html('<span style="color:red;font-size:14px;"><p>Guardando información...</p></span>');
	var name_event  = document.getElementById('name_event').value;
	var type_master = document.getElementById('type_master').value;
	var notes_event = document.getElementById('notes_event').value;
	var id_event  = document.getElementById('name_master_sel').value;
	var id_level  = document.getElementById('level_master').value; if (id_level == null) { id_level = document.getElementById('level_master_tst').value; }
	var num_weeks = parseInt(document.getElementById('weeks_train').value);
	var type_event = document.getElementById('event_type');
	var weeks_bef = "";
	var weeks_pos = "";
	var orig_assig = '';

	if(!document.getElementById('weeks_train_b') || document.getElementById('weeks_train_b').value == ''){ 
		weeks_bef = 0; 
		orig_assig = 0;
	}else{ 
		weeks_bef = document.getElementById('weeks_train_b').value; 
		orig_assig = document.getElementById('orig_assig').value;
	}
	num_weeks = parseInt(num_weeks)+parseInt(weeks_bef);

	var cicles_weeks = '', lrs = '', weeks = '';//sessions = '';
	var temp = '', temp1 = '', temp2 = '', temp3 = '';
	var sessions = [], cants = [], units = [], tsss = [];
	var num_session = 3;
	var num_days    = 7;
	if (type_master == '11' || type_master == '12' || type_master == '13') {
		num_weeks = parseInt(num_weeks);
	}else{ num_weeks = parseInt(num_weeks+1); }
	for (var i = 1; i <= num_weeks; i++) {
		//ciclos semanales
		cicles_weeks = cicles_weeks.concat(document.getElementById('cicle_week_'+i+'_s').value)+',';
		//lookup_references
		lrs   = lrs+',';
		//semanas
		weeks = weeks.concat(i)+',';
		//sesiones
		for (var j = 1; j <= num_days; j++) {
			for (var k = 1; k <= num_session; k++) {
				sessions.push(replace_string((((document.getElementById('session_'+k+'_'+j+'_'+i+'_c').value).replace("+","mmaass").replace("@","aarroobbaa"))), "+", "mmaass")+'///');
			}
			sessions.push('|||');
		}
		sessions.push('@@@');
		//cantidades
		for (j = 1; j <= num_days; j++) {
			for (k = 1; k <= num_session; k++) {
				temp1 = document.getElementById('s_cant_'+k+'_'+j+'_'+i+'_c');
				temp2 = document.getElementById('b_cant_'+k+'_'+j+'_'+i+'_c');
				temp3 = document.getElementById('r_cant_'+k+'_'+j+'_'+i+'_c');
				temp  = temp1.innerHTML+'&&&'+temp2.innerHTML+'&&&'+temp3.innerHTML+'///';
				cants.push(temp);
			}
			cants.push('|||');
		}
		cants.push('@@@');
		//medidas
		for (j = 1; j <= num_days; j++) {
			for (k = 1; k <= num_session; k++) {
				temp1 = document.getElementById('s_med_'+k+'_'+j+'_'+i+'_c');
				temp2 = document.getElementById('b_med_'+k+'_'+j+'_'+i+'_c');
				temp3 = document.getElementById('r_med_'+k+'_'+j+'_'+i+'_c');
				temp  = temp1.innerHTML+'&&&'+temp2.innerHTML+'&&&'+temp3.innerHTML+'///';
				units.push(temp);
			}
			units.push('|||');
		}
		units.push('@@@');
		//tsss
		for (j = 1; j <= num_days; j++) {
			for (k = 1; k <= num_session; k++) {
				temp1 = document.getElementById('s_tss_'+k+'_'+j+'_'+i+'_c');
				temp2 = document.getElementById('b_tss_'+k+'_'+j+'_'+i+'_c');
				temp3 = document.getElementById('r_tss_'+k+'_'+j+'_'+i+'_c');
				temp  = temp1.innerHTML+'&&&'+temp2.innerHTML+'&&&'+temp3.innerHTML+'///';
				tsss.push(temp);
			}
			tsss.push('|||');
		}
		tsss.push('@@@');
	}
	cicles_weeks = cicles_weeks.slice(0,-1);
	lrs 		 = lrs.slice(0,-1);
	weeks 		 = weeks.slice(0,-1);
	x_save_all_data_db(opc, id_event,id_level,num_weeks, name_event,type_master,notes_event, cicles_weeks,lrs,weeks,sessions,cants,units,tsss, type_event,orig_assig, Content_universal);
}
function save_all_data_db_confirm(){
	var content = jQuery(".col_day_7").html(); // Toma el valor de uno de los espacios donde va el intercambio de columnas
	if (content != "") {
		jQuery().toastmessage('showErrorToast', 'El evento no puede guardarse, Intercambio de Columnas pendiente...');
	}else{
		var id_event = document.getElementById('name_master_sel').value;
		var id_level = document.getElementById('level_master').value;
		if (id_level == null) { id_level = document.getElementById('level_master_tst').value; }
		if (id_event == 'new_master') {//Insertara directamente los datos en PHP //Primero el evento, despues le agregara las sessioes
			save_all_data_db(1);
		}else{ x_save_all_data_db_confirm(id_event,id_level, save_all_data_db_options); }
	}
}
function save_all_data_db_secundary_confirm(type){
	var content = jQuery(".col_day_7").html(); // Toma el valor de uno de los espacios donde va el intercambio de columnas
	if (content != "") {
		jQuery().toastmessage('showErrorToast', 'El evento no puede guardarse, Intercambio de Columnas pendiente...');
	}else{
		var id_event   = document.getElementById('name_master_sel').value;
		var id_level   = document.getElementById('level_master').value;
		var name_event = document.getElementById('name_event').value;
		var num_weeks  = parseInt(document.getElementById('weeks_train').value);
		x_save_all_data_db_secundary_confirm(id_event,id_level,name_event,num_weeks,type, save_all_data_db_secundary_options);
	}
}
function save_all_data_db_secundary_event(option,id_event_t){
	jQuery('.load').html('<span style="color:red;font-size:14px;"><p>Guardando información...</p></span>');
	var name_event  = document.getElementById('name_event').value;
	var id_event  = document.getElementById('name_master_sel').value;
	var id_level  = document.getElementById('level_master').value;
	var num_weeks = parseInt(document.getElementById('weeks_train').value);
	var cicles_weeks = '', weeks = '';//sessions = '';
	var sessions = [];
	var num_session = 3;
	var num_days    = 7;
	for (var i = 1; i <= num_weeks; i++) {
		//ciclos semanales
		cicles_weeks = cicles_weeks.concat(document.getElementById('cicle_week_'+i).value)+',';
		//semanas
		weeks = weeks.concat(i)+',';
		//sesiones
		for (var j = 1; j <= num_days; j++) {
			for (var k = 1; k <= num_session; k++) {
				sessions.push(replace_string((((document.getElementById('session_'+k+'_'+j+'_'+i).value).replace("+","mmaass").replace("@","aarroobbaa"))), "+", "mmaass")+'///');
			}
			sessions.push('|||');
		}
		sessions.push('@@@');
	}
	cicles_weeks = cicles_weeks.slice(0,-1);
	weeks 		 = weeks.slice(0,-1);
	var notes = document.getElementById('notes_event').value;
	var id_temp   = '';
	var id_temp_l = '';
	if (document.getElementById('id_temp') != null) { id_temp = document.getElementById('id_temp').value; }else{ id_temp = document.getElementById('name_master_sel').value; }
	if (document.getElementById('level_temp') != null) { id_temp_l = document.getElementById('level_temp').value; }else{ id_temp_l = document.getElementById('level_master').value; }
	x_save_all_data_db_secundary_event(option,id_event,id_level,name_event,cicles_weeks,id_event_t,sessions,num_weeks,notes,id_temp,id_temp_l, Content_process);
}
function save_temporal_event(){
	if(confirm("Deseas guardar esta información como un Evento Secundario del Evento Actual?")){
        //Si guardas esta información como un Evento Secundario, al pasar la fecha del Evento Principal, este evento será eliminado y no podrás ver mas informacón al respecto, deseas continuar?
        save_all_data_db_secundary_event();
    }else{ jQuery().toastmessage('showErrorToast', 'Ningún dato guardado...'); }
}
function save_all_data_db_secundary_options(res){//id_temporary|@|trains_event|@|type
	res = res.split("|@|");
	if (res[0] == "repeat_name" && res[2] == 0) { jQuery().toastmessage('showErrorToast', 'El Nombre del Evento ya existe...'); /*alert("--1--");*/ }
	if (res[0] == "repeat_name" && res[2] == 1) { save_all_data_db_secundary_event(4,res[0]); /*alert("--2--");*/ }
	if ((res[0] == 0 && res[2] == 0)||(res[0] == 0 && res[1] == 0)) { save_all_data_db_secundary_event(1,res[0]); /*alert("--3--");*/ }//Se generará nuevo evento y se agregaran trainings
	if (res[0] > 0 && res[1] == 0 && res[2] == 0) { save_all_data_db_secundary_event(2,res[0]); /*alert("--4--");*/ }//Se agregarán solamente los trainings
	if (res[0] > 0 && res[1] > 0 && res[2] == 0) {//Se agregarán y/o sustituiran trainings
		if (confirm("El Evento Temporal ya existe, deseas modificar las sesiones?")) {
			save_all_data_db_secundary_event(3,res[0]);
		}else{
			jQuery().toastmessage('showErrorToast', 'Evento no modificado...');
		}
	}
}
