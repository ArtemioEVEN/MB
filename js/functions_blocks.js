// Llama al formulario para agregar BLOCKS
function get_blocks_form(id_block){
	if (id_block == "0" || id_block == 0) {// Si se trata de Agregar un nuevo Bloque
		jQuery('li').removeClass('active');//Para eliminar el señuelo de activo en cualquier otra opcion
		jQuery('#blocks').addClass('active');//Para activar el señuelo de activo en la opcion seleccionada
		jQuery('.row').html('<h5 class="subtitle">Cargando formulario para Bloques...</h5><br />');//Mensaje de espera
		jQuery('.pageicon').html('<span class="iconfa-th"></span>');//Cambiamos el icono en titulo
		jQuery('.pagetitle').html('<h5>Agregar</h5><h1>Bloques</h1>');//Cambiamos mensaje en titulo
	}
	x_get_blocks_form(id_block, Content_universal);//Content_general //Llamada a PHP
}
// Cargamos información del Block para guardar el bloque y regresar la tabla como se pidió
// Si Opcion = 0 se guardara bloque nuevo, si opcion = 1 se buscara hacer un update a determinado bloque
function load_block_info(opc){
	opc = parseInt(opc);
	var id_b  = document.getElementById('id_block').value;
	var nam_b = document.getElementById('name_block').value;

	var dis_b = document.getElementById('disc_block').value;

	var des_b = document.getElementById('notes_block').value;
	var sem_b = document.getElementById('weeks_block').value;
//	var ses_b = document.getElementById('sessions_block').value;
	var day_b = document.getElementById('days_block').value;
	var err_mess = "";
	var sess_b = ""; var j = 0;
	if (id_b == "")  { err_mess = "Bloque no identificado..."; }
	if (nam_b == "") { err_mess = "Por favor asigna un nombre..."; }
	if (des_b == "") { err_mess = "Por favor agrega una descripción..."; }
	if (sem_b == "") { err_mess = "Por favor selecciona un número de semanas..."; }
	if (day_b == "") { err_mess = "Por favor selecciona un número de días para el bloque..."; }
	if (nam_b == "" && des_b == "" && sem_b == "" && day_b == "") { 
		err_mess = "Por favor introduce los datos obligatorios..."; 
	}else{
		for (var i = 1; i <= day_b; i++) {
			if (document.getElementById('sessn_blck'+i).value != "") { sess_b += document.getElementById('sessn_blck'+i).value+"-###-"; }else{ j++; }
		}
		if (j >= 1){ err_mess = "Por favor selecciona las sessiones de cada día"; }else{ sess_b = sess_b.substr(0, sess_b.length-5); }
	}
	if (err_mess != "") { //jQuery("#load_nothing").html('<span style="color:red;font-size:12px;">'+err_mess+'</span>'); //jQuery().toastmessage('showErrorToast', err_mess); 
		swal("Error", err_mess, "error");
	}else{
//		alert("-"+sess_b+"-");
		if (opc == 0) { x_load_block_info(0,nam_b,des_b,sem_b,day_b,sess_b,dis_b, Content_universal); }
		if (opc == 1) { x_update_block_info(id_b,nam_b,des_b,sem_b,sess_b,dis_b,day_b, Content_process_blocks); }
	}	
}
// Cargamos ventana auxiliar Blocks
function show_popup_block(type, data, id_input, row){//type: tipo de contenido que mostrará, data: Datos que ocupa para mostrar la info
	var prev_ses = '';
	var id_table = '';
	var prev_input = id_input;
	(row < 10) ? prev_input = prev_input.slice(0,-1) : prev_input = prev_input.slice(0,-2);
	if ((row-1) > 0) {
		prev_ses = document.getElementById(prev_input+(row-1)).value;
		prev_ses = replace_string(prev_ses, "+", "mmaass");
	}
	data = replace_string(data, "+", "mmaass");
	//x_show_session_training_form(data,id_input,row,type, Content_popup);
	x_show_session_training_form_block(data,id_input,row,type,prev_ses, Content_popup);
	jQuery("#hider_"+type).fadeIn("slow");
    jQuery('#popup_box_'+type).fadeIn("slow");
}
// Obtenemos la info para guardar sesion el block
function get_session_block_data(id_input, row, id_pop){
	var box_session = '';
	var cans = ''; var canb = ''; var canr = '';
	var unis = ''; var unib = ''; var unir = '';
	var tsss = ''; var tssb = ''; var tssr = '';
	if (!document.getElementById('manual_ses'+id_pop) || document.getElementById('manual_ses'+id_pop).value == '') {//manual_ses != ''
		box_session = document.getElementById("session_t"+id_pop);
		box_session = box_session.innerHTML;
		var session_parts = box_session.split('.');
		var discipline = session_parts[0];

		if (discipline == 'S' || discipline == 'Prueba de la T') {
			(discipline == 'S') ? cans = document.getElementById('s'+id_pop).value : cans = '1500';
			unis = 'mts';
			if(discipline == 'S'){ tsss = document.getElementById('stss'+id_pop); tsss = tsss.innerHTML; }else{ tsss = '42'; }
		}
		if (discipline == 'B' || discipline == 'FTP') {
			(discipline == 'B') ? unib = jQuery('input[name=selb'+id_pop+']:checked').val() : unib = 'min';
			canb = document.getElementById('b'+id_pop).value;
			tssb = document.getElementById('tss'+id_pop);  tssb = tssb.innerHTML;
		}
		if (discipline == 'R' || discipline == 'Vdot' || discipline == 'PruebaMilla' || discipline == 'Prueba3k' || discipline == 'Prueba5k') {
			canr = document.getElementById('r'+id_pop).value;
			(discipline == 'R') ? unir = jQuery('input[name=selr'+id_pop+']:checked').val() : unir = 'min';
			tssr = document.getElementById('rtss'+id_pop);  tssr = tssr.innerHTML;
		}
		if (discipline == 'BR' || discipline == 'KW') {
			canb = document.getElementById('b'+id_pop).value;
			unib = jQuery('input[name=selb'+id_pop+']:checked').val();
			tssb = document.getElementById('tss'+id_pop);  tssb = tssb.innerHTML;
			canr = document.getElementById('r'+id_pop).value;
			unir = jQuery('input[name=selr'+id_pop+']:checked').val();
			tssr = document.getElementById('rtss'+id_pop);  tssr = tssr.innerHTML;
		}
		if (discipline == 'F') {}
		if (discipline == 'XT') {}
		if (discipline == 'off') {}
	}else{//Si la entrada es manual
		box_session = document.getElementById('manual_ses'+id_pop).value;
		var session_parts = box_session.split('.');
		var discipline = session_parts[0];
		if (discipline == 'S' || discipline == 'Prueba de la T') {
			(discipline == 'S') ? cans = session_parts[1] : cans = '1500';
			unis = 'mts';
			tsss = '42';
		}
		if (discipline == 'B' || discipline == 'FTP') {
			if (discipline == 'B') {
				canb = session_parts[1].slice(0,-1);
				(session_parts[1].substr(-1) == 'm') ? unib = 'min' : unib = 'km';
				tssb = '0';
				var aux01 = '';
				var aux02 = '';
				(unib == 'km') ? aux01 = canb*2 : aux01 = canb;
	            (canb > 120) ? aux02 = '0.78' : aux02 = '0.83';
	            tssb = Math.round(Math.pow(aux02, 2)*100*aux01/60);
			}else{
				canb = '60';
				unib = 'min';
				tssb = '80';
			}
		}
		if (discipline == 'R' || discipline == 'Vdot' || discipline == 'PruebaMilla' || discipline == 'Prueba3k' || discipline == 'Prueba5k') {
            if (discipline == 'R') {
            	canr = session_parts[1].slice(0,-1);
				(session_parts[1].substr(-1) == 'm') ? unir = 'min' : unir = 'km';
				tssr = '0';
				var aux01 = ''; var aux02 = '';
				(unir == 'km') ? aux01 = canr*5 : aux01 = canr;
		        aux02 = '0.8';
		        tssr = Math.round(Math.pow(aux02, 2)*112*aux01/60);
	        }else{
	        	unir = 'min';
	        	if (discipline == 'Vdot' || discipline == 'Prueba5k') { canr='50'; tssr='75'; }
	        	if (discipline == 'PruebaMilla') { canr='20'; tssr='30'; }
	        	if (discipline == 'Prueba3k') { canr='30'; tssr='40'; }
	        }
		}
		if (discipline == 'BR' || discipline == 'KW') {
			var aux = session_parts[1].split("+");
			canb = aux[0].replace("(","");
			(session_parts[1].substr(-1) == 'm') ? unib = 'min' : unib = 'km';
			tssb = '0';
			var aux01 = '';
			var aux02 = '';
			(unib == 'km') ? aux01 = canb*2 : aux01 = canb;
            (canb > 120) ? aux02 = '0.78' : aux02 = '0.83';
            tssb = Math.round(Math.pow(aux02, 2)*100*aux01/60);
			canr = aux[1].slice(0, -2);
			(session_parts[1].substr(-1) == 'm') ? unir = 'min' : unir = 'km';
			tssr = '0';
			aux01 = ''; aux02 = '';
			(unir == 'km') ? aux01 = canr*5 : aux01 = canr;
            aux02 = '0.8';
            tssr = Math.round(Math.pow(aux02, 2)*112*aux01/60);
		}
	}
	jQuery('.'+id_input).val(box_session);
	if (unib === 'km') { canb = canb*2; unib = 'min'; }
	if (unir === 'km') { canr = canr*5; unir = 'min'; }

	var session_desc = document.getElementById(id_input).value;
		session_desc = replace_string(session_desc, "+", "mmaass");
	var week 	 = row;
	//Tenia 9
// NUEVO 02-05-2018
	var session = id_input.replace("session_", "");
	session = session.split("_");
	var day_b = session[1];
	session = session[0];

	//var info_ses = session_desc+'|-|'+cans+'|-|'+unis+'|-|'+tsss+'|-|'+canb+'|-|'+unib+'|-|'+tssb+'|-|'+canr+'|-|'+unir+'|-|'+tssr+'|-|'+cicle_week+'|-|'+week+'|-|'+day+'|-|'+session+'|-|'+type_event;
	var info_ses = session_desc+'|-|'+cans+'|-|'+unis+'|-|'+tsss+'|-|'+canb+'|-|'+unib+'|-|'+tssb+'|-|'+canr+'|-|'+unir+'|-|'+tssr+'|-|'+week+'|-|'+session+'|-|'+day_b;
	var id_block = document.getElementById("id_block").value;
	//alert('BLOCK: '+id_block+'INFO: '+info_ses); //alert("Session: "+session);
	if (id_block != "none") { x_save_session_block(id_block,info_ses, Content_process); }
	close_popup(id_pop);
}
// Muestra la lista de bloques para editarlos
function get_edit_list_blocks(){
	jQuery('li').removeClass('active');
	jQuery('#blocks').addClass('active');
	jQuery('.row').html('<h5 class="subtitle">Cargando Tabla...</h5><br />');
	jQuery('.pageicon').html('<span class="iconfa-pencil"></span>');
	jQuery('.pagetitle').html('<h5>Editar</h5><h1>Bloques</h1>');
	//alert('edit_event...');
	//x_get_events_table_editable(filter,opc,order, Content_universal); //content&&&id_or_class
	x_get_edit_list_blocks(0, Content_universal);
}
// Elimina directamente la session al hace clck en "Borrar Sesion"
function clear_session_block(id_session, id_pop, row){
	//jQuery('').html('');
	var id_block = document.getElementById("id_block").value;
	var week 	 = row;
	var aux      = id_session.replace("session_","");
		aux 	 = aux.split("_");
//	var session  = id_session.substr(id_session.length -3);
//		session  = session.charAt(0);
	var session  = aux[0];
	var day 	 = aux[1];
	jQuery('#'+id_session).val('');
	//alert('Block: '+id_block+'Semana: '+week+'Session: '+session);
	var info_ses = '|-||-||-||-||-||-||-||-||-||-|'+week+'|-|'+session+'|-|'+day;
	x_save_session_block(id_block,info_ses, Content_process);
	close_popup(id_pop);
}
// Carga las opciones para la carga de bloques
function load_form_block(id_pop){
	var weeks_train = document.getElementById('weeks_train').value;
	x_load_form_block(id_pop,weeks_train, Content_popup);
	jQuery("#hider_"+id_pop).fadeIn("slow");
    jQuery('#popup_box_'+id_pop).fadeIn("slow");
}
// Carga la informacion para verla por el usuario en el popup
function show_info_block(){
	var block_info  = document.getElementById('block_info').value;//id_b,weeks_b,sess_b,desc_b
	if (block_info == "") {
		jQuery("#info_block").html('');
	}else{
		block_info = block_info.split("##");
		jQuery("#info_block").html('<input type="hidden" id="sess_evt" value="'+block_info[2]+'"><input type="hidden" id="id_block" value="'+block_info[0]+'">Contenido de <b>'+block_info[1]+'</b> Semanas, <b>'+block_info[4]+'</b> Días y <b>'+block_info[2]+'</b> Sesiones<div class="form-group"><label for="notes_block">Descripción</label><textarea cols="50" rows="2" id="notes_block" class="form-control input-default" placeholder="Escribe cualquier comentario que sirva en el futuro para el Block que vas a diseñar" disabled>'+block_info[3]+'</textarea><input type="hidden" id="days_blck" value="'+parseInt(block_info[4])+'" /></div>');
	}
}
function load_days_order_block(){
	var days = document.getElementById('days_blck').value; days = parseInt(days);
	var days_box = '<label for="days_order">Orden del bloque<span style="color:red;">*</span></label><br><center>';
	var selected = '';
	for (var i=1; i<=days; i++) {
		//alert("some-"+i);
		days_box += '<select id="oday_'+i+'" name="indication_subject[]" class="dropdown input-default" tabindex="2" style="width: 10%;">';
		for (var j=1; j<=days; j++) {
			if (j == i) { selected = 'selected'; }else{ selected = ''; }
     		days_box += '<option value="'+j+'" '+selected+'> '+j+'</option>';
     	}
        days_box += '</select>&nbsp;&nbsp;&nbsp;&nbsp;';
	}
	days_box += '</center>';
	jQuery("#order_days").html(days_box);
}
// Obtiene los datos para definir como se vaciará el bloque
function get_block_set_info(){
	var id_block  = document.getElementById('id_block').value;
	var col_event = document.getElementById('col_event').value;
	var week_evt  = document.getElementById('sessions_event').value;

	var days = document.getElementById('days_blck').value; days = parseInt(days);
	var aux_days  = ''; var aux_input = ''; var error = 0;

	if (id_block == "" || col_event == "" || week_evt == "") { 
		swal("Error", 'Por Favor, Inserta Datos Obligatorios...', "error"); 
	}else{
		///// Obtenemos valores del reacomode al asignar el bloque
		for (var i=1; i<=days; i++) {
			aux_input = document.getElementById('oday_'+i).value;
//			alert("--"+aux_days.includes(aux_input)+"--");
//			aux_days += aux_input+"#--#";
			if (aux_days.includes(aux_input) == true) {
				swal("Error", 'Los días del bloque no se pueden asignar en dias repetidos...', "error");
				error++;
				break;
			}else{ aux_days += aux_input+"#--#"; }
		}
		if (error == 0) {
			aux_days = aux_days.slice(0,-4);
			x_get_block_set_info(id_block,col_event,week_evt,aux_days, Content_process_blocks);
		}
	}
}
// Confirma eliminacion bloque
function confirm_delete_block(id_block){
//	if(confirm("Se eliminará la información relacionada con este Block, deseas continuar?")) { x_delete_block(id_block, Content_process_blocks); }else{ jQuery().toastmessage('showErrorToast', 'Eliminación Cancelada...'); }
    swal({
		title: "Eliminar datos de Block?",
		text: "Se eliminará la información relacionada con este Block, deseas continuar?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#EA2734",
		confirmButtonText: "Si, eliminar!"
		},
		function(){ x_delete_block(id_block, Content_process_blocks); }
	);
}
// Revisa el numero de dias que se requieren en el bloque para cargar las sessiones por dia que llevará
function load_sessions_rows(days){
	//alert(days);
	var html_res = "";
	html_res = '<label for="sessn_blck1">Sesiones por Día <span id="amount_alert" style="color:red;">*</span></label><br>';
	html_res += '<div class="form-inline">';
	for (var i = 1; i <= days; i++) {
		//html_res += 'día: '+i;
		html_res += '<select id="sessn_blck'+i+'" class="dropdown form-control input-default" name="plan_id_col'+i+'" tabindex="2" style="width:70px" onchange="">';
		html_res += '<option value="">Día: '+i+'</option>';
		html_res += '<option value="1"> 1</option> <option value="2"> 2</option> <option value="3"> 3</option>';
		html_res += '</select>&nbsp;&nbsp;&nbsp;&nbsp;';
		//alert("-"+i+"-");
	}
	html_res += '</div>';
	jQuery("#sessions_rows").html(html_res);
}
//
function load_prev_session_value_block(prev_session, id_input, row, id_pop){//$session_training, $id_input, $row, $type,
	//alert(prev_session);
	if(jQuery("#anterior_session_week").prop('checked') == true){
		prev_session = replace_string(prev_session, "+", "mmaass");
		x_show_session_training_form_block(prev_session,id_input,row,id_pop,prev_session, Content_popup);
	}else{
		prev_session = replace_string(prev_session, "+", "mmaass");
		x_show_session_training_form_block('',id_input,row,id_pop,prev_session, Content_popup);
	}
}
/// CONTENEDORES BLOQUES
function Content_process_blocks(res){//Contenedor para la caida de respuesta a diferentes procesos (insert, update, delete, etc..)
	res = res.split("@-@");
	var origen   = res[0];// De donde viene el proceso
	var response = res[1];// Respuesta concreta del proceso
	var paramtr  = res[2];// paramentro adicional
	// Origenes
	if (origen == 1) {
		response = parseInt(response);
		paramtr  = paramtr.split("#-#");//id_b, name_b, weeks_b, sess_b, desc_b
										//$id_b, $name_b, $desc_b, $weeks_b, $sess_b
		if (response == 1) { jQuery().toastmessage('showSuccessToast', 'Información del Bloque Actualizada...'); x_load_block_info(paramtr[0],paramtr[1],paramtr[4],paramtr[2],paramtr[3], Content_universal); }
		if (response == 2) { jQuery().toastmessage('showErrorToast', 'La Información del Bloque no se pudo Actualizar...'); }
		//function load_block_info($id_b, $name_b, $desc_b, $weeks_b, $days_b, $sess_b, $discipline_b)
//		alert(paramtr[0]+"<<>>"+paramtr[1]+"<<>>"+paramtr[4]+"<<>>"+paramtr[2]+"<<>>"+paramtr[3]+"<<>>"+paramtr[4]);
		if (response == 3) { x_load_block_info(paramtr[0],paramtr[1],paramtr[4],paramtr[2],paramtr[6],paramtr[3],paramtr[4], Content_universal); }
	}
	if (origen == 2) {
		if (response == 0) { jQuery().toastmessage('showErrorToast', 'Error al Obtener Información del Bloque...'); }
		if (response != 0) {
			response = JSON.parse(response);
			//alert(response.toSource());
			for (var i=1; i <= response.total; i++) {
				//save_individual_session(response["position_"+i]);
				some_some((response["position_"+i]),response["week_"+i],2,response["session_"+i]);
				load_data_summary_value(response["week_"+i], 0);
				// $res["position_".$j], $res["session_".$j], $res["week_".$j]
			}
			var id_pop = parseInt(document.getElementById('id_pop').value);
			close_popup(id_pop);
		}
	}
	if (origen == 3) {
		if (response > 0) {
			jQuery().toastmessage('showSuccessToast', 'Bloque Eliminado...');
		}else{
			jQuery().toastmessage('showErrorToast', 'No fue posible eliminar el bloque...');
		}
		x_get_edit_list_blocks(0, Content_universal);
	}
}

//FUNCIONES GENERALES
function change_value_button(id_button, new_value){ jQuery(id_button).text(new_value); }

function some_some(id_input, row, id_pop, box_session){
	//var box_session = '';
	var cans = ''; var canb = ''; var canr = '';
	var unis = ''; var unib = ''; var unir = '';
	var tsss = ''; var tssb = ''; var tssr = '';
	var session_parts = box_session.split('.');
	var discipline = session_parts[0];
	if (discipline == 'S' || discipline == 'Prueba de la T') { (discipline == 'S') ? cans = session_parts[1] : cans = '1500'; unis = 'mts'; tsss = '42'; }
	if (discipline == 'B' || discipline == 'FTP') {
		if (discipline == 'B') {
			canb = session_parts[1].slice(0,-1);
			(session_parts[1].substr(-1) == 'm') ? unib = 'min' : unib = 'km';
			tssb = '0'; var aux01 = ''; var aux02 = '';
			(unib == 'km') ? aux01 = canb*2 : aux01 = canb;
	        (canb > 120) ? aux02 = '0.78' : aux02 = '0.83';
	        tssb = Math.round(Math.pow(aux02, 2)*100*aux01/60);
		}else{ canb = '60'; unib = 'min'; tssb = '80'; }
	}
	if (discipline == 'R' || discipline == 'Vdot' || discipline == 'PruebaMilla' || discipline == 'Prueba3k' || discipline == 'Prueba5k') {
        if (discipline == 'R') {
           	canr = session_parts[1].slice(0,-1);
			(session_parts[1].substr(-1) == 'm') ? unir = 'min' : unir = 'km';
			tssr = '0'; var aux01 = ''; var aux02 = '';
			(unir == 'km') ? aux01 = canr*5 : aux01 = canr;
	        aux02 = '0.8';
	        tssr = Math.round(Math.pow(aux02, 2)*112*aux01/60);
	    }else{
	       	unir = 'min';
	       	if (discipline == 'Vdot' || discipline == 'Prueba5k') { canr='50'; tssr='75'; }
	       	if (discipline == 'PruebaMilla') { canr='20'; tssr='30'; }
	       	if (discipline == 'Prueba3k') { canr='30'; tssr='40'; }
	    }
	}
	if (discipline == 'BR' || discipline == 'KW') {
		var aux = session_parts[1].split("+");
		canb = aux[0].replace("(","");
		(session_parts[1].substr(-1) == 'm') ? unib = 'min' : unib = 'km';
		tssb = '0'; var aux01 = ''; var aux02 = '';
		(unib == 'km') ? aux01 = canb*2 : aux01 = canb;
        (canb > 120) ? aux02 = '0.78' : aux02 = '0.83';
        tssb = Math.round(Math.pow(aux02, 2)*100*aux01/60);
		canr = aux[1].slice(0, -2);
		(session_parts[1].substr(-1) == 'm') ? unir = 'min' : unir = 'km';
		tssr = '0'; aux01 = ''; aux02 = '';
		(unir == 'km') ? aux01 = canr*5 : aux01 = canr;
        aux02 = '0.8';
        tssr = Math.round(Math.pow(aux02, 2)*112*aux01/60);
	}
	var aux_id_input = id_input;
	//Modificado desde Tabla 1
	jQuery('.'+id_input).val(box_session);
	//Modificado desde Tabla 2
	if (id_pop == 2) { id_input = id_input+'_c'; jQuery('.'+id_input).val(box_session); }
	var id_table3 = id_input.replace('c','s');
	jQuery('.'+id_table3).val(box_session);//vaciamos valor en tabla 3
	var id_input_base = id_input.replace('session','');

	jQuery('.s_cant'+id_input_base).html(cans); jQuery('.b_cant'+id_input_base).html(canb); jQuery('.r_cant'+id_input_base).html(canr);
	jQuery('.s_med'+id_input_base).html(unis); jQuery('.b_med'+id_input_base).html(unib); jQuery('.r_med'+id_input_base).html(unir);

	if (unib === 'km') { canb = canb*2; unib = 'min'; }	if (unir === 'km') { canr = canr*5; unir = 'min'; }
	jQuery('.s_res'+id_input_base).html(cans+' '+unis); jQuery('.b_res'+id_input_base).html(canb+' '+unib); jQuery('.r_res'+id_input_base).html(canr+' '+unir);
	jQuery('.s_tss'+id_input_base).html(tsss); jQuery('.b_tss'+id_input_base).html(tssb); jQuery('.r_tss'+id_input_base).html(tssr);
	var id_pop = parseInt(document.getElementById('id_pop').value);
	//alert("id_pop: "+id_pop);
	if (id_pop == 2) { save_individual_session(id_input); }else{
		if (id_pop == 3) { 
			var cicle_week = document.getElementById("cicle_week_"+row).value;
			var last_id = id_input.replace("session_","");
			var parts_id = last_id.split('_');
			var week 	  = parts_id[2];
			var day 	  = parts_id[1];
			var session   = parts_id[0];
			var type_event = document.getElementById('type_master').value;
			var info_ses = box_session+'|-|'+cans+'|-|'+unis+'|-|'+tsss+'|-|'+canb+'|-|'+unib+'|-|'+tssb+'|-|'+canr+'|-|'+unir+'|-|'+tssr+'|-|'+cicle_week+'|-|'+row+'|-|'+day+'|-|'+session+'|-|'+type_event;
			var id_event = document.getElementById('name_master_sel').value;
			var id_level = document.getElementById('level_master').value;
			var note_event = '';
			(document.getElementById('notes_event')) ? note_event = document.getElementById("notes_event").value : note_event = '';
			if (row > 0 && day > 0 && session > 0) {
				//alert("INFO---- id_input: "+aux_id_input+" id_event: "+id_event+" id_level: "+id_level+" note_event: "+note_event+" info_ses: "+info_ses);
				x_save_individual_session(aux_id_input,id_event,id_level,note_event,info_ses, Content_process);
			}
		}
	}
}
