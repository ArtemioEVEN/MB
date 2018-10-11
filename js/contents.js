function Content_general(res){ // YA
	res = res.split("@@@");
	var origen  = res[0];//De que funcion viene el contenido
	var content = res[1];//Contenido
	var paramtr = res[2];//Parametro(s) adicional(es)
	jQuery('.row').html(content);
	
	if (origen == 2) { load_datepicker(); get_events_table(0, paramtr);}//Funciones para formulario de eventos //get_events_form() ||0 es el filtro ALL de Events, paramtr trae 0 o 1 que es (evento, master)
	if (origen == 3) { load_form_wisard_script(); }// wisard para asignar entrenamientos //get_events_form_add_trains
	if (origen == 4 && paramtr == 1) { 
		var event = '';
		if (document.getElementById('id_evento')) { event = document.getElementById('id_evento').value; }
		load_select_levels_assigments(event);
	}
	if (origen == 5) { load_levels_table(); }
	if (origen == 6) {
		jQuery(document).ready(function(){
	        jQuery('#dyntable').dataTable({
	            "sPaginationType": "full_numbers",
	            "fnDrawCallback": function(oSettings) {
	                jQuery.uniform.update();
	            }
	        });
	    });
	}
	if (origen == 7) {
		var id_event = document.getElementById('id_evento');
		load_select_master_assigments(id_event);
	}
}
/////////
function Content_second(res){// Hay que definir previamente <div class="second_content"></div>
	jQuery('.second_content').html(res);
	jQuery(document).ready(function(){
	        jQuery('#dyntable').dataTable({
	            "sPaginationType": "full_numbers",
	            "aaSortingFixed": [[0,'asc']],
	            "fnDrawCallback": function(oSettings) {
	                jQuery.uniform.update();
	            }
	        });
	    });
}
/////////
function Content_process(res){//Contenedor para la caida de respuesta a diferentes procesos (insert, update, delete, etc..)
	res = res.split("@-@");
	var origen   = res[0];// De donde viene el proceso
	var response = res[1];// Respuesta concreta del proceso
	var paramtr  = res[2];// paramentro adicional
	// Origenes
	if (origen == 1) {// save_new_event (PHP)
		paramtr = paramtr.split("###");
		master  = paramtr[0];
		name_e  = paramtr[1];
		id_e 	= paramtr[2];
		id_t 	= paramtr[3];
		//Si se inserto ok = 1 si no = 0
		if (response == "repeat_name") {
			jQuery().toastmessage('showErrorToast', 'El Nombre del Evento ya existe...');
		}else{
			if (response > 0) {
				jQuery().toastmessage('showSuccessToast', 'El Evento fue agregado con éxito...');
				document.getElementById("type_ee").value = "";
			    document.getElementById("type_event").value = "";
			    document.getElementById("name_ee").value = "";
			    document.getElementById("str_ee").value = "";
			    document.getElementById("level_master").value = "";
				document.getElementById("datepicker").value = "";
				get_events_table(0, master);
//	    		jQuery("#load_nothing").html('<b>Acciones Disponibles para '+name_e+':</b><br><button class="btn" type="button" onclick="x_get_assignments_trains_form('+id_e+',1, Content_general); return false;">Asignarle Entrenamientos por Template</button>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn" type="button" onclick="x_get_events_form_add_trains(0,'+"'"+id_e+"||"+name_e+"||"+id_t+"'"+', Content_general); return false;">Diseñar Evento Temporal</button>');
			}
		}
	}
	if (origen == 2) {
		if (response == '') { //alert('debe de cargar tabla nueva...');
		}else{
			response = response.split("&&&");
			var sessions = JSON.parse(response[0]);
			var cants_s = JSON.parse(response[1]); var units_s = JSON.parse(response[2]); var tsss = JSON.parse(response[3]);
			var cants_b = JSON.parse(response[4]); var units_b = JSON.parse(response[5]); var tssb = JSON.parse(response[6]);
			var cants_r = JSON.parse(response[7]); var units_r = JSON.parse(response[8]); var tssr = JSON.parse(response[9]);
			var cws = JSON.parse(response[10]);
			var lrs = JSON.parse(response[11]);
			var weeks = response[12];

			jQuery('.look_ref_1').val(lrs[0]);//se pone al inicio para evitar la carga de ciclos
			var l = 0; var m = 0; var n = 0;
			for (var i=0; i<weeks; i++) {
				n = i+1;
				/*Tabla 1*/ jQuery('.cicle_week_'+n).val(cws[i]); jQuery('.look_ref_'+n).html(lrs[i]);
				/*Tabla 2*/ jQuery('.cicle_week_'+n+'_c').val(cws[i]); jQuery('.look_ref_'+n+'_c').html(lrs[i]);
				/*Tabla 3*/ jQuery('.cicle_week_'+n+'_s').val(cws[i]); jQuery('.look_ref_'+n+'_s').html(lrs[i]);
				for (var k=0; k<3; k++) {//Entrenamientos por dia
					l = k+1;
					/*Tabla 1*/ jQuery('.session_'+l+'_1_'+n).val(sessions[i][k]); jQuery('.session_'+l+'_2_'+n).val(sessions[i][(k+3)]); jQuery('.session_'+l+'_3_'+n).val(sessions[i][(k+6)]); jQuery('.session_'+l+'_4_'+n).val(sessions[i][(k+9)]); jQuery('.session_'+l+'_5_'+n).val(sessions[i][(k+12)]); jQuery('.session_'+l+'_6_'+n).val(sessions[i][(k+15)]); jQuery('.session_'+l+'_7_'+n).val(sessions[i][(k+18)]);
					/*Tabla 2*/ jQuery('.session_'+l+'_1_'+n+'_c').val(sessions[i][k]); jQuery('.session_'+l+'_2_'+n+'_c').val(sessions[i][(k+3)]); jQuery('.session_'+l+'_3_'+n+'_c').val(sessions[i][(k+6)]); jQuery('.session_'+l+'_4_'+n+'_c').val(sessions[i][(k+9)]); jQuery('.session_'+l+'_5_'+n+'_c').val(sessions[i][(k+12)]); jQuery('.session_'+l+'_6_'+n+'_c').val(sessions[i][(k+15)]); jQuery('.session_'+l+'_7_'+n+'_c').val(sessions[i][(k+18)]);
					///s_cant_1_1_1_c  s_med_1_1_1_c  s_res_1_1_1_c (Es cant med)  s_tss_1_1_1_c //cants_s  units_s  tsss
					jQuery('.s_cant_'+l+'_1_'+n+'_c').html(cants_s[i][k]); jQuery('.s_cant_'+l+'_2_'+n+'_c').html(cants_s[i][(k+3)]); jQuery('.s_cant_'+l+'_3_'+n+'_c').html(cants_s[i][(k+6)]); jQuery('.s_cant_'+l+'_4_'+n+'_c').html(cants_s[i][(k+9)]); jQuery('.s_cant_'+l+'_5_'+n+'_c').html(cants_s[i][(k+12)]); jQuery('.s_cant_'+l+'_6_'+n+'_c').html(cants_s[i][(k+15)]); jQuery('.s_cant_'+l+'_7_'+n+'_c').html(cants_s[i][(k+18)]);
					jQuery('.s_med_'+l+'_1_'+n+'_c').html(units_s[i][k]); jQuery('.s_med_'+l+'_2_'+n+'_c').html(units_s[i][(k+3)]); jQuery('.s_med_'+l+'_3_'+n+'_c').html(units_s[i][(k+6)]); jQuery('.s_med_'+l+'_4_'+n+'_c').html(units_s[i][(k+9)]); jQuery('.s_med_'+l+'_5_'+n+'_c').html(units_s[i][(k+12)]); jQuery('.s_med_'+l+'_6_'+n+'_c').html(units_s[i][(k+15)]); jQuery('.s_med_'+l+'_7_'+n+'_c').html(units_s[i][(k+18)]);
					jQuery('.s_res_'+l+'_1_'+n+'_c').html(cants_s[i][k]+' '+units_s[i][(k)]); jQuery('.s_res_'+l+'_2_'+n+'_c').html(cants_s[i][(k+3)]+' '+units_s[i][(k+3)]); jQuery('.s_res_'+l+'_3_'+n+'_c').html(cants_s[i][(k+6)]+' '+units_s[i][(k+6)]); jQuery('.s_res_'+l+'_4_'+n+'_c').html(cants_s[i][(k+9)]+' '+units_s[i][(k+9)]); jQuery('.s_res_'+l+'_5_'+n+'_c').html(cants_s[i][(k+12)]+' '+units_s[i][(k+12)]); jQuery('.s_res_'+l+'_6_'+n+'_c').html(cants_s[i][(k+15)]+' '+units_s[i][(k+15)]); jQuery('.s_res_'+l+'_7_'+n+'_c').html(cants_s[i][(k+18)]+' '+units_s[i][(k+18)]);
					jQuery('.s_tss_'+l+'_1_'+n+'_c').html(tsss[i][k]); jQuery('.s_tss_'+l+'_2_'+n+'_c').html(tsss[i][(k+3)]); jQuery('.s_tss_'+l+'_3_'+n+'_c').html(tsss[i][(k+6)]); jQuery('.s_tss_'+l+'_4_'+n+'_c').html(tsss[i][(k+9)]); jQuery('.s_tss_'+l+'_5_'+n+'_c').html(tsss[i][(k+12)]); jQuery('.s_tss_'+l+'_6_'+n+'_c').html(tsss[i][(k+15)]); jQuery('.s_tss_'+l+'_7_'+n+'_c').html(tsss[i][(k+18)]);
					///b_cant_1_1_1_c b_med_1_1_1_c b_res_1_1_1_c(Es cant med) b_tss_1_1_1_c
					jQuery('.b_cant_'+l+'_1_'+n+'_c').html(cants_b[i][k]); jQuery('.b_cant_'+l+'_2_'+n+'_c').html(cants_b[i][(k+3)]); jQuery('.b_cant_'+l+'_3_'+n+'_c').html(cants_b[i][(k+6)]); jQuery('.b_cant_'+l+'_4_'+n+'_c').html(cants_b[i][(k+9)]); jQuery('.b_cant_'+l+'_5_'+n+'_c').html(cants_b[i][(k+12)]); jQuery('.b_cant_'+l+'_6_'+n+'_c').html(cants_b[i][(k+15)]); jQuery('.b_cant_'+l+'_7_'+n+'_c').html(cants_b[i][(k+18)]);
					jQuery('.b_med_'+l+'_1_'+n+'_c').html(units_b[i][k]); jQuery('.b_med_'+l+'_2_'+n+'_c').html(units_b[i][(k+3)]); jQuery('.b_med_'+l+'_3_'+n+'_c').html(units_b[i][(k+6)]); jQuery('.b_med_'+l+'_4_'+n+'_c').html(units_b[i][(k+9)]); jQuery('.b_med_'+l+'_5_'+n+'_c').html(units_b[i][(k+12)]); jQuery('.b_med_'+l+'_6_'+n+'_c').html(units_b[i][(k+15)]); jQuery('.b_med_'+l+'_7_'+n+'_c').html(units_b[i][(k+18)]);
					jQuery('.b_res_'+l+'_1_'+n+'_c').html(cants_b[i][k]+' '+units_b[i][(k)]); jQuery('.b_res_'+l+'_2_'+n+'_c').html(cants_b[i][(k+3)]+' '+units_b[i][(k+3)]); jQuery('.b_res_'+l+'_3_'+n+'_c').html(cants_b[i][(k+6)]+' '+units_b[i][(k+6)]); jQuery('.b_res_'+l+'_4_'+n+'_c').html(cants_b[i][(k+9)]+' '+units_b[i][(k+9)]); jQuery('.b_res_'+l+'_5_'+n+'_c').html(cants_b[i][(k+12)]+' '+units_b[i][(k+12)]); jQuery('.b_res_'+l+'_6_'+n+'_c').html(cants_b[i][(k+15)]+' '+units_b[i][(k+15)]); jQuery('.b_res_'+l+'_7_'+n+'_c').html(cants_b[i][(k+18)]+' '+units_b[i][(k+18)]);
					jQuery('.b_tss_'+l+'_1_'+n+'_c').html(tssb[i][k]); jQuery('.b_tss_'+l+'_2_'+n+'_c').html(tssb[i][(k+3)]); jQuery('.b_tss_'+l+'_3_'+n+'_c').html(tssb[i][(k+6)]); jQuery('.b_tss_'+l+'_4_'+n+'_c').html(tssb[i][(k+9)]); jQuery('.b_tss_'+l+'_5_'+n+'_c').html(tssb[i][(k+12)]); jQuery('.b_tss_'+l+'_6_'+n+'_c').html(tssb[i][(k+15)]); jQuery('.b_tss_'+l+'_7_'+n+'_c').html(tssb[i][(k+18)]);
					///r_cant_1_1_1_c r_med_1_1_1_c r_res_1_1_1_c(Es cant med) r_tss_1_1_1_c
					jQuery('.r_cant_'+l+'_1_'+n+'_c').html(cants_r[i][k]); jQuery('.r_cant_'+l+'_2_'+n+'_c').html(cants_r[i][(k+3)]); jQuery('.r_cant_'+l+'_3_'+n+'_c').html(cants_b[i][(k+6)]); jQuery('.r_cant_'+l+'_4_'+n+'_c').html(cants_r[i][(k+9)]); jQuery('.r_cant_'+l+'_5_'+n+'_c').html(cants_b[i][(k+12)]); jQuery('.r_cant_'+l+'_6_'+n+'_c').html(cants_r[i][(k+15)]); jQuery('.r_cant_'+l+'_7_'+n+'_c').html(cants_b[i][(k+18)]);
					jQuery('.r_med_'+l+'_1_'+n+'_c').html(units_r[i][k]); jQuery('.r_med_'+l+'_2_'+n+'_c').html(units_r[i][(k+3)]); jQuery('.r_med_'+l+'_3_'+n+'_c').html(units_b[i][(k+6)]); jQuery('.r_med_'+l+'_4_'+n+'_c').html(units_r[i][(k+9)]); jQuery('.r_med_'+l+'_5_'+n+'_c').html(units_b[i][(k+12)]); jQuery('.r_med_'+l+'_6_'+n+'_c').html(units_r[i][(k+15)]); jQuery('.r_med_'+l+'_7_'+n+'_c').html(units_b[i][(k+18)]);
					jQuery('.r_res_'+l+'_1_'+n+'_c').html(cants_r[i][k]+' '+units_r[i][(k)]); jQuery('.r_res_'+l+'_2_'+n+'_c').html(cants_r[i][(k+3)]+' '+units_r[i][(k+3)]); jQuery('.r_res_'+l+'_3_'+n+'_c').html(cants_r[i][(k+6)]+' '+units_r[i][(k+6)]); jQuery('.r_res_'+l+'_4_'+n+'_c').html(cants_r[i][(k+9)]+' '+units_r[i][(k+9)]); jQuery('.r_res_'+l+'_5_'+n+'_c').html(cants_r[i][(k+12)]+' '+units_r[i][(k+12)]); jQuery('.r_res_'+l+'_6_'+n+'_c').html(cants_r[i][(k+15)]+' '+units_r[i][(k+15)]); jQuery('.r_res_'+l+'_7_'+n+'_c').html(cants_r[i][(k+18)]+' '+units_r[i][(k+18)]);
					jQuery('.r_tss_'+l+'_1_'+n+'_c').html(tssr[i][k]); jQuery('.r_tss_'+l+'_2_'+n+'_c').html(tssr[i][(k+3)]); jQuery('.r_tss_'+l+'_3_'+n+'_c').html(tssr[i][(k+6)]); jQuery('.r_tss_'+l+'_4_'+n+'_c').html(tssr[i][(k+9)]); jQuery('.r_tss_'+l+'_5_'+n+'_c').html(tssr[i][(k+12)]); jQuery('.r_tss_'+l+'_6_'+n+'_c').html(tssr[i][(k+15)]); jQuery('.r_tss_'+l+'_7_'+n+'_c').html(tssr[i][(k+18)]);
					/*Tabla 3*/
					jQuery('.session_'+l+'_1_'+n+'_s').val(sessions[i][k]); jQuery('.session_'+l+'_2_'+n+'_s').val(sessions[i][(k+3)]); jQuery('.session_'+l+'_3_'+n+'_s').val(sessions[i][(k+6)]); jQuery('.session_'+l+'_4_'+n+'_s').val(sessions[i][(k+9)]); jQuery('.session_'+l+'_5_'+n+'_s').val(sessions[i][(k+12)]); jQuery('.session_'+l+'_6_'+n+'_s').val(sessions[i][(k+15)]); jQuery('.session_'+l+'_7_'+n+'_s').val(sessions[i][(k+18)]);
				}
			}
		}
	}
	if (origen == 3) {
		var message = '';
		if (response == 1) {
			//message = '<span style="color:green;font-size:14px;"><p>El evento se ah modificado correctamente...</p></span>';
			jQuery().toastmessage('showSuccessToast', 'El evento se ah modificado correctamente...');
		}else{
			//message = '<span style="color:red;font-size:14px;"><p>No fue posible modificar el evento, por favor intentalo mas tarde...</p></span>';
			jQuery().toastmessage('showErrorToast', 'No fue posible modificar el evento, por favor intentalo mas tarde...');
		}
		//jQuery('#load_edit_event').html(message);
		setTimeout(function(){ get_edit_list(0,1,''); }, 2000);
	}
	if (origen == 4) {
		//if (id_or_class == '#table_template') {
		jQuery('#table_template').html(response);
		//load_tables_script();
		setTimeout(function(){ load_tables_script(); }, 1000);
		var total_weeks = document.getElementById("weeks_train").value;
		setTimeout(function(){ recalculate_totals(total_weeks);}, 6000);//Esperamos 6 segundo para que carguen las tablas, despues cargamos totales de BR y TSS
		//Se ocultan las columnas sin session
		setTimeout(function(){ hidde_cols(paramtr); }, 3000);
		resize_rows_height();
		setTimeout(function(){ load_form_events_script(); }, 3000);
	}
	if (origen == 5) {// save_new_master_individual (PHP)
		response = parseInt(response);
		//Si se inserto ok = 1 si no = 0
		if (response > 0) {
			jQuery().toastmessage('showSuccessToast', 'Nuevo Template agregado...');
			jQuery('#name_master_sel').attr('value', response);
			save_individual_session(paramtr);
		}
	}
	if (origen == 6) {//save_individual_session
		if (response == 1) { 
			jQuery().toastmessage('showSuccessToast', 'Sesion agregada...');
			var class_note = paramtr.replace("session","note");
			class_note = class_note.replace("_c","");
			jQuery('.'+class_note).show(); 
		}
		if (response == 2) { jQuery().toastmessage('showSuccessToast', 'Sesion actualizada...'); }
		if (response == 3) { jQuery().toastmessage('showErrorToast', 'Ningún cambio realizado...'); }
		if (response == 4) { jQuery().toastmessage('showSuccessToast', 'Sesion Eliminada...'); }
		if (response == 5) { jQuery().toastmessage('showErrorToast', 'No se pudo eliminar la sesion...'); }
	}
	if (origen == 7) {//show_event_sessions
		jQuery('.row_aux').html(response);
		setTimeout(function(){ load_tables_script(); }, 1000);
		var total_weeks = document.getElementById("weeks_train").value;
		setTimeout(function(){ recalculate_totals(total_weeks);}, 6000);//Esperamos 6 segundo para que carguen las tablas, despues cargamos totales de BR y TSS
		/*Se ocultan las columnas sin session*/ setTimeout(function(){ hidde_cols(paramtr); }, 3000);
		resize_rows_height();
	}
	if (origen == 8) {//session destroy
		//window.location="./index.html";
		window.location="../entrenos/index.html";
	}
	if (origen == 9) {
		jQuery('.row').html(response);
		setTimeout(function(){
			jQuery('#name_user').val('');
			jQuery('#pass_user').val('');
			jQuery('#email_user').val('');
			jQuery(".checkbox_privileges").attr('checked', false);
		}, 100);
		x_load_users_table(paramtr, Content_universal);
	}
	if (origen == 10) {//paramtr
		response = response.split(",");
		var event_week = paramtr-1;
		for (var i = 1; i <= paramtr; i++) {
			if (event_week == i) {
				jQuery(".date_week_i_"+i).val(response[(i-1)]);
			}else{
				jQuery(".date_week_"+i).text(response[(i-1)]);
			}
		}
	}
	if (origen == 11) {//save_new_user
		if (response == 'exist') {
			jQuery('#load').html('<span style="color:red;font-size:12px;">Este nombre de usuario ya existe...</span>');
			setTimeout(function(){ jQuery('#load').html(''); }, 3000);
		}else{
			if (response > 0) {
				jQuery().toastmessage('showSuccessToast', 'Nuevo Usuario Agregado...');
				jQuery('#name_user').val('');
				jQuery('#pass_user').val('');
				jQuery('#email_user').val('');
				jQuery(".checkbox_privileges").attr('checked', false);
				x_load_users_table(0, Content_universal);
			}else{ jQuery().toastmessage('showErrorToast', 'No fue posible agregar el nuevo usuario...'); }
		}
	}
	if (origen == 12) {
		if (response == 1) {
			jQuery().toastmessage('showSuccessToast', 'Los datos del Usuario se han modificado...');
		}else{ jQuery().toastmessage('showErrorToast', 'No fue posible editar los datos del usuario...'); }
		x_load_users_table(paramtr, Content_universal);
	}
	if (origen == 13) {
		if (response == 1) {
			jQuery().toastmessage('showSuccessToast', 'Usuario Eliminado...');
		}else{ jQuery().toastmessage('showErrorToast', 'No fue posible eliminar el usuario...'); }
		x_load_users_table(paramtr, Content_universal);
	}
	if (origen == 14) {
		if (response > 1) {
			jQuery().toastmessage('showSuccessToast', 'Evento Eliminado...');
		}else{ jQuery().toastmessage('showErrorToast', 'No fue posible eliminar el evento...'); }
		x_get_events_table_editable(0,1,'', Content_universal);
	}
	if (origen == 15) {
		if (response >= 1) {
			jQuery().toastmessage('showSuccessToast', 'Nivel Removido...');
			hide_div("#row_"+paramtr);
		}else{ jQuery().toastmessage('showErrorToast', 'No fue posible remover el nivel...'); }
	}
	if (origen == 16) {
		jQuery('#summary_table_individual').html(response);
		jQuery('#week_and_ends').html(paramtr);
		load_form_events_script();
	}
	if (origen == 17) {
		//(response == 1) ? jQuery().toastmessage('showSuccessToast', 'El macro se asignó correctamente al evento...') : jQuery().toastmessage('showErrorToast', 'Error al asignar, intentalo más tarde...');
		if (response == 1) {
			var weeks_before = jQuery("#weeks_before").val();
			var d_e = jQuery("#d_e").val();
			if (weeks_before > 0) {
				var master = jQuery("#id_master").val();
				var lvl_m  = jQuery('#level_master').val();
				var n_e = jQuery('#n_e').val(); var n_m = jQuery('#n_m').val();
				jQuery('#load___').html('<input type="hidden" id="orig_assig" value="'+paramtr+'"/><p>Faltan <b>'+weeks_before+'</b> semanas para que la asignación del Template <b>'+n_m+'</b> comience en el grupo <b>'+n_e+'</b><br><button class="btn btn-primary" type="button" onclick="get_events_form_add_trains(2); return false;" >Generar Template Alterno</button> &nbsp;&nbsp;&nbsp;<button class="btn" type="button" onclick="clear_div('+"'#load___'"+'); return false;" >Conservar asi</button></p><input id="info_assigment" type="hidden" value="'+paramtr+'"/><input id="weeks_before" type="hidden" value="'+weeks_before+'"/><input id="d_e" type="hidden" value="'+d_e+'"/>');d_e
				//jQuery('.widgetcontent').html('<p>Faltan <b>'+weeks_before+'</b> semanas para que la asignación del Template <b>'+n_m+'</b> comience en el grupo <b>'+n_e+'</b><br><button class="btn btn-primary" type="button" onclick="get_events_form_add_trains(2); return false;" >Editar Template</button> &nbsp;&nbsp;&nbsp;<button class="btn" type="button" onclick="get_assignments_trains_form(0); return false;" >Conservar asi</button></p><input id="info_assigment" type="hidden" value="'+paramtr+'"/>');
			}
			jQuery("#date_event").html('');
			jQuery('#level_event').html(''); jQuery('#id_master').html(''); jQuery('#level_master').html(''); jQuery("#id_evento").val('');
			jQuery().toastmessage('showSuccessToast', 'El macro se asignó correctamente al evento...');

			swal({
		  		title: "Ocultar notas heredadas?",
		  		text: "El Template que estas asignando a este Grupo puede tener notas que este heredará, deseas ocultar algunas?",
		  		type: "warning",
		  		showCancelButton: true,
		  		confirmButtonColor: "#EA2734",
		  		confirmButtonText: "Si, revisar!"
			},
			function(){
		  		jQuery('#extra_notes').show();//modstramos el campo que puede estar oculto
				paramtr = paramtr.split(",");
				x_load_extra_notes(paramtr[0],paramtr[1],paramtr[2],paramtr[3],0,0,1,0, Content_universal);
			});
		}else{ jQuery().toastmessage('showErrorToast', 'Error al asignar, intentalo más tarde...'); }
	}
	if (origen == 18) {
		if (response == 1) {
			jQuery().toastmessage('showSuccessToast', 'El evento se ah modificado a Template...');
			jQuery('.session_trains').prop("disabled", true);
			jQuery('.event_sec_to_master').hide();
			jQuery('#new_name_to_temp_sec').hide();
		}else{ jQuery().toastmessage('showErrorToast', 'Error al modificar Evento, intentalo más tarde...'); }
	}
	if (origen == 19) {
		if (response >= 1) {
			jQuery().toastmessage('showSuccessToast', 'Nivel Oculto...');
		}else{
			jQuery().toastmessage('showErrorToast', 'No fue posible ocultar el nivel...');
		}
		reload_table_summary('');
	}
	if (origen == 20) {
		if (response >= 1) {
			jQuery().toastmessage('showSuccessToast', 'Nivel Activo...');
			jQuery('#show_circular_temps_options').hide();
		}else{
			jQuery().toastmessage('showErrorToast', 'No fue posible mostrar el nivel...');
		}
	}
	if (origen == 21) {
		if (response >= 1) {
			jQuery().toastmessage('showSuccessToast', 'Notas Modificadas...');
		}else{
			jQuery().toastmessage('showErrorToast', 'No fue posible modificar las notas...');
		}
	}
	if (origen == 22) {
		jQuery('.load').html("");
		swal("Resumen", response, "success");
		paramtr = paramtr.split(",");
		//show_event_sessions(paramtr[0],paramtr[1],paramtr[0],paramtr[1]);//id_event,id_level_e,id_template,id_level_t
		// S I   S E   G U A R D O   U N   N U E V O   E V E N T O   S E C U N D A R I O
		if (paramtr[2] == 1 || paramtr[2] == 4) {
			show_event_sessions(paramtr[0],paramtr[1],paramtr[0],paramtr[1],0);//id_event,id_level_e,id_template,id_level_t
			jQuery().toastmessage('showSuccessToast', 'Evento Secundario Agregado...');
		    swal({
		  		title: "Eliminar Nivel Evento Primario?",
		  		text: "El Evento Secundario se Agregó, deseas eliminar el Nivel del Evento en el que esta basado este Secundario?",
		  		type: "warning",
		  		showCancelButton: true,
		  		confirmButtonColor: "#EA2734",
		  		confirmButtonText: "Si, eliminar!"
			},
			function(){
		  		x_deallocate_level(paramtr[3],paramtr[1],0, Content_process);//id_event,level_event
			});
		}
	}
	if (origen == 23) {
		if (response >= 1) { jQuery().toastmessage('showSuccessToast', 'Ciclo Modificado...'); }else{ jQuery().toastmessage('showErrorToast', 'No fue posible modificar el ciclo semanal...'); }
	}
	if (origen == 24) {
		var response = response.split("--");
		var type_answer = 'showSuccessToast';
		if (response[1] == 0) { type_answer='showErrorToast'; }
		jQuery().toastmessage(type_answer, response[0]);
		close_popup(1); close_popup(2); close_popup(3);
		if (response[2] != "") { jQuery("."+response[2]).attr("src","./img/images/notes_add.png"); } //Cambiamos el icono de nota, por el que marca que ya tiene una nota agregada
		//jQuery().toastmessage('showErrorToast', 'No fue posible modificar el ciclo semanal...');
	}
	if (origen == 25) {
		paramtr = paramtr.split(',');
		paramtr[0]  = parseInt(paramtr[0]);
		response = parseInt(response);
		var type_note = parseInt(paramtr[1]);//IF 1 Conservar (Secundario) IF 2 Ocultar (Evento)
		var note = '';
		if (paramtr[0] == 1) {//Vemos si se agregó nueva relacion de nota
			if (response > 0) {
				(type_note == 1) ? note = 'Nota Conservada...' : note = 'Nota Oculta...';
				jQuery().toastmessage('showSuccessToast', note);
			}else{
				(type_note == 1) ? note = 'La Nota no se pudo Conservar...' : note = 'La nota no se pudo ocultar...';
				jQuery().toastmessage('showErrorToast', note);
			}
		}else{//Vemos si se eliminó nueva relacion de nota
			if (response > 0) {
				(type_note == 1) ? note = 'Nota Eliminada...' : note = 'Nota restablecida...';
				jQuery().toastmessage('showSuccessToast', note);
			}else{
				(type_note == 1) ? note = 'La nota no se pudo Eliminar...' : note = 'La nota no se pudo restablecer...';
				jQuery().toastmessage('showErrorToast', note);
			}
		}
	}
	if (origen == 26) {
		if (response > 0) {
			jQuery().toastmessage('showSuccessToast', "Nota Eliminada...");
		}else{
			jQuery().toastmessage('showErrorToast', "La nota no se pudo eliminar, por favor intentalo mas tarde...");
		}
		close_popup(1); close_popup(2); close_popup(3);
	}
	if (origen == 27){
		if (response > 0) {
			paramtr = parseInt(paramtr);
			var action = "desactivada";
			if(paramtr == 1){ action = "activada"; }
			jQuery().toastmessage('showSuccessToast', "La alerta fue "+action+"...");
		}else{
			jQuery().toastmessage('showSuccessToast', "La alerta no pudo ser "+action+", inténtalo mas tarde...");
		}
	}
	if (origen == 28) {
		jQuery('#summary_table_individual').html(response);
		jQuery('#week_and_ends').html(paramtr);
		load_form_events_script();
	}
	if (origen == 29) {
		if (response == 0) {
			jQuery().toastmessage('showErrorToast', "La información no se pudo enviar, por favor intentalo más tarde..."); 
			swal("Info. NO enviada!", "La información no pudo ser enviada via JSON!", "error");
		}else{
			jQuery().toastmessage('showSuccessToast', "Información enviada...");
			swal("Info. enviada!", "La información fue enviada via JSON!", "success");
			paramtr = paramtr.split('###'); //alert(paramtr[1]);
			var sended = paramtr[1].split(',');
			if (paramtr[0] == 0) { jQuery("#json_button").attr('value', 'Reenviar Json'); }
			for (var i=1; i<= sended.length; i++) { jQuery(".s_json").css("visibility","visible"); }
			if (parseInt(paramtr[2]) > 0) { jQuery().toastmessage('showNoticeToast', "Se reportarón "+paramtr[2]+" sesiones no encontradas en Plataforma EVEN..."); }
		}	
	}
	if (origen == 30) {
		if (response >= 1) { jQuery().toastmessage('showSuccessToast', 'Actividad Registrada...');
		}else{ jQuery().toastmessage('showErrorToast', 'No fue posible registrar la atividad como completada...'); }
		hide_div(".ap_"+paramtr);
	}
	if (origen == 31) {
		if (response >= 1) { jQuery().toastmessage('showSuccessToast', 'Sesión Registrada...');
		}else{ jQuery().toastmessage('showErrorToast', 'No fue posible registrar la sesión como revisada...'); }
		hide_div(".grade"+paramtr);
	}
	if (origen == 32) {
		response = response.split(",");
		var sec = response[1]; var asi = response[2]; var mas = response[3];
		if (sec == 0 && asi == 0) { get_assignments_trains_form(response[0]); }// Grupo Normal sin asignar
		if (sec == 0 && asi == 1) { //alert("Grupo Normal asignado");
			paramtr = paramtr.split(","); 
			show_event_sessions(response[0],paramtr[0],paramtr[1],paramtr[2],1);
		}// Grupo Normal asignado
		if (sec == 1 && mas == 0) { // Grupo Secundario sin master para tomar valor
			paramtr = paramtr.split(","); 
			show_event_sessions(response[0],paramtr[0],paramtr[1],paramtr[2],0);
		}
		if (sec == 1 && mas > 0) { // Grpo secundario que tomara forma del master de la asignacion del evento aterior
			paramtr = paramtr.split(","); 
			show_event_sessions(paramtr[0],paramtr[1],paramtr[2],paramtr[3],0);
		}
	}
	if (origen == 33) { jQuery(".col-md-16").html(response); load_form_wisard_script(); }
	if (origen == 34) {
		if (response > 0) { 
			jQuery().toastmessage('showSuccessToast', 'Semana de Grupo Bloqueada...');
			paramtr = paramtr.split("-|-");
			jQuery('#row_'+paramtr[0]).css("background", "lightgray");
												// show_popup_block_group(week,event,id_e,i,id_gb,user,reason,actual_user,type)
			jQuery('#'+paramtr[1]).attr("onclick","show_popup_block_group('"+paramtr[3]+"','"+paramtr[2]+"','"+paramtr[1]+"','"+paramtr[0]+"','"+response+"','"+paramtr[4]+"','"+paramtr[5]+"','"+paramtr[4]+"','2')");
			jQuery('#block_icon_'+paramtr[0]).removeClass('glyphicon-ok-circle');
			jQuery('#block_icon_'+paramtr[0]).addClass('glyphicon-ban-circle'); jQuery('#block_icon_'+paramtr[0]).css("color","rgb(234, 39, 52)");
			close_popup(3);
//			if (cw == 'r' || cw == 'R') { (color == '#EFFBFB') ? color = '#CEF6F5' : color = '#EFFBFB'; }
		}else{ jQuery().toastmessage('showErrorToast', 'No fue posible bloquear la semana del grupo...'); }
	}
	if (origen == 35) {
		if (response > 0) { 
			jQuery().toastmessage('showSuccessToast', 'Semana de Grupo Desbloqueada...');
			paramtr = paramtr.split("-|-");//$row."-|-".$id_e."-|-".$week."-|-".$name;
			jQuery('#row_'+paramtr[0]).css("background", "#FFFFFF");
			jQuery('#'+paramtr[1]+'_'+paramtr[0]).attr("onclick","show_popup_block_group('"+paramtr[2]+"','"+paramtr[3]+"','"+paramtr[1]+"','"+paramtr[0]+"','0','','','','1')");

			jQuery('#block_icon_'+paramtr[0]).removeClass('glyphicon-ban-circle');
			jQuery('#block_icon_'+paramtr[0]).addClass('glyphicon-ok-circle'); jQuery('#block_icon_'+paramtr[0]).css("color","rgb(0, 128, 0)");
			close_popup(3);
		}else{ jQuery().toastmessage('showErrorToast', 'No fue posible desbloquear la semana del grupo...'); }
	}
}
/////////
function Content_universal(data){//contenido html  &&&  id or class of div or span  &&&  type (id or class)
	data 	    = data.split('&&&');//content&&&id_or_class&&&add_info
	content     = data[0];
	id_or_class = data[1];
	add_info 	= data[2];
	jQuery(id_or_class).html(content);
	if (id_or_class == '#select_level_wisard1') { resize_height_wisard(); }
	if (id_or_class == '#step2_wizard' || id_or_class == '#step4_wizard') { resize_rows_wizard(); }
	if (id_or_class == '#step3_wizard') {
		setTimeout(function(){ load_tables_script(); }, 1000);
		jQuery('#step1_wizard').html('');
		if (id_or_class == '#step4_wizard') {
			var total_weeks = document.getElementById("weeks_train").value;
			setTimeout(function(){ recalculate_totals(total_weeks);}, 6000);//Esperamos 6 segundo para que carguen las tablas, despues cargamos totales de BR y TSS
		}
	}
	if (id_or_class == '#step4_wizard' && add_info == '1') { save_all_data_db_confirm(); }
	if (id_or_class == '.load') {
		var $messageDiv = jQuery(id_or_class); // get the reference of the div
		$messageDiv.show().html(content); // show and set the message
		setTimeout(function(){ $messageDiv.html('');}, 3000); // 3 seconds later, hide // and clear the message
		//if (response == '1') { get_events_form_add_trains(1); }
		jQuery("#name_master_sel").val(add_info);
	}
	if (id_or_class == '.form_master_load') { resize_height_wisard(); load_form_events_script(); }
	if (id_or_class == '#load') { load_levels_table(); }
	if (id_or_class == '#load_') {
		if (data[2] != 0) {
			if(confirm("El Evento ya tiene asignado un master en ese nivel, deseas reemplazarlo?")){
        		x_add_assignments_value(data[3],data[4],data[5],data[6],1, Content_process);//
				
				);
    		}
		}else{
			x_add_assignments_value(data[3],data[4],data[5],data[6],0, Content_process);//Content_universal);
		}
	}
	if (id_or_class == '#option_forms') {resize_height_wisard();}
	if (id_or_class == '.row') { 
		load_form_events_script();
		var interval = 30;
	  	var duration= 1000;
	  	var shake= 3;
	  	var vibrateIndex = 0;
	  	var object_to_move = jQuery('#date_ref');
	    vibrateIndex = setInterval(function(){ jQuery(object_to_move).stop(true,false).css({position: 'relative', left: Math.round(Math.random() * shake) - ((shake + 1) / 2) +'px', top: Math.round(Math.random() * shake) - ((shake + 1) / 2) +'px'}); }, interval);
	    setTimeout(function() { clearInterval(vibrateIndex); jQuery(object_to_move).stop(true,false).css({position: 'static', left: '0px', top: '0px'}); }, duration);
	}//PARA RESUMEN GENERAL
	if (id_or_class == '#load_edit_form') { load_form_events_script(); }
	if (id_or_class == '#value_name_event') { // Select with Search
		jQuery(".chzn-select").chosen(); }
	if (id_or_class == '#show_temp_note') { load_textarea(); jQuery('#info_wait_table').html('<span style="color:red;font-size:12px;">Por favor, espera el aviso de carga completa...</span>'); }
	if(id_or_class == '.row_aux'){ 
		jQuery(document).ready(function(){
	        jQuery('#dyntable').dataTable({
	            "sPaginationType": "full_numbers",
	            "aaSortingFixed": [[0,'asc']],
	            "fnDrawCallback": function(oSettings) {
	                jQuery.uniform.update();
	            }
	        });
	    });
	    load_form_events_script();
	    if (add_info != '' && add_info != 'undefined') {
	    	jQuery().toastmessage('showNoticeToast', 'Espera...');
	    	add_info = add_info.split('@@@');
	    	check_type_ap(add_info[0],add_info[1]);
	    }
	}
	if (id_or_class == ".second_content") { 
		if (add_info != "0" && add_info != 0) { jQuery("#id_block").val(add_info); jQuery().toastmessage('showSuccessToast', 'Nuevo Bloque Agregado...'); }
	}
}
/////////
//Contenedor de tablas generadas con semanas adicionales
function load_extra_weeks_event(response){
	response = response.split("#@#@#");
	var update = parseInt(response[0]);
	if (update > 0) {
		jQuery('.trainings').attr('id','test');
		/*Table 1*/ jQuery('#t_ex tr:last').after(response[1]);
		/*Table 2*/ jQuery('#test tr:last').after(response[2]);	
		jQuery('.trainings').attr('id','t_ex');
		resize_rows_height();
		jQuery().toastmessage('showSuccessToast', 'Fecha de Grupo editada...');
		jQuery('#form_add_weeks_event').html("");
		jQuery("#add_weeks_button").prop("disabled",true);
	}else{ swal("Error", "No fue posible editar la fecha del grupo...", "error"); }
}
/////////
function Content_col(res){//Hay que definir previamente <div id="dashboard-left" class="col-md-8"></div> en .row
	jQuery('.col-md-8').html(res);
}
/////////
function Content_popup(res){
	res = res.split('|&|');
	var type = res[0];
	var message = res[1];
	jQuery('.message_box_'+type).html(message);
	jQuery(".chzn-select").chosen();
}
/////////
function Content_popup_notes(res){
	res = res.split('|&|');
	var type = res[0];
	var message = res[1];
	var type_note = res[2];
	jQuery('.message_box_'+type).html(message);
	jQuery("#popup_box_"+type).animate({width:390},"slow"); //Ajustamos el ancho para que entre correctamente el editor de texts
	jQuery("#popup_box_"+type).animate({height:480},"slow");
    jQuery().ready(function() { //El codigo original de este script esta en "wysiwyg.js"
		jQuery('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : 'js/tinymce/tiny_mce.js',
			// General options
			theme : "simple",//advanced
			skin : "default",//themepixels
			width: "100%",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
			inlinepopups_skin: "themepixels",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			//readonly : 1,
		});
		jQuery('.editornav a').click(function(){
			jQuery('.editornav li.current').removeClass('current');
			jQuery(this).parent().addClass('current');
			return false;
		});
	});
}
/////////
/////////
/////////
/////////
/////////
/////////
/////////
