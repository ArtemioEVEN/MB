function show_popup_notes(type, session, id_input, row, type_note, read_only){//type: tipo de contenido que mostrará, data: Datos que ocupa para mostrar la info
	//type_note 1=template, 2=evento y 3=evento secundario
	var id_event = document.getElementById('name_master_sel').value;
	var id_level = document.getElementById('level_master').value;
	var last_id   = id_input.replace("session_","");
	var parts_id  = last_id.split('_');
	var week 	  = parts_id[2];
	var day 	  = parts_id[1];
	var no_sess   = parts_id[0];
	var info_ses  = id_event+'@@'+id_level+'@@'+week+'@@'+day+'@@'+no_sess;
	var id_temp   = '';
	var id_temp_l = '';
	if (document.getElementById('id_temp') != null) { id_temp = document.getElementById('id_temp').value; }else{ id_temp = document.getElementById('name_master_sel').value; }
	if (document.getElementById('level_temp') != null) { id_temp_l = document.getElementById('level_temp').value; }else{ id_temp_l = document.getElementById('level_master').value; }
	var info_temp = id_temp+'@@'+id_temp_l;
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
	session = replace_string(session, "+", "mmaass");
	x_show_session_notes(session,type,info_ses,type_note,info_temp,read_only, Content_popup_notes);
	jQuery("#hider_"+type).fadeIn("slow");
    jQuery('#popup_box_'+type).fadeIn("slow");
}
function show_popup_notes_summary(session,type,info_ses,type_note,info_temp,read_only){//type: tipo de contenido que mostrará, data: Datos que ocupa para mostrar la info
	//type_note 1=template, 2=evento y 3=evento secundario
	session = replace_string(session, "+", "mmaass");
	x_show_session_notes(session,type,info_ses,type_note,info_temp,read_only, Content_popup_notes);
	jQuery("#hider_"+type).fadeIn("slow");
    jQuery('#popup_box_'+type).fadeIn("slow");
}
function save_update_session_note(type_note){
	var info_session = '';
	if (info_session = document.getElementById('info_sess') != null) { info_session = document.getElementById('info_sess').value; }
	var note = '';
	if (type_note == 1) { note = jQuery('#notes_sesion_temp').html(); note = replace_string(note, '"', "____"); }//TEMPLATE
	if (type_note == 2) { note = jQuery('#notes_sesion_event').html(); note = replace_string(note, '"', "____"); }//EVENTO
	if (type_note == 3) { note = jQuery('#notes_sesion_event_sec').html(); note = replace_string(note, '"', "____"); }//SECUNDARIO
	if (type_note == 4) { note = jQuery('#notes_event_week').html(); note = replace_string(note, '"', "____"); }//NOTA SEMANAL
	x_save_update_session_note(type_note,info_session,note, Content_process);//origen@-@response@-@paramtr
}
function close_load_extra_notes(){ jQuery('#extra_notes').hide(); }
function save_relation_note(id_note_session, id_sec_event, id_check){
	var func = 0;
	if ( document.getElementById(id_check).checked ) { func = 1; }
	x_save_relation_note(id_note_session,id_sec_event,func, Content_process);
}
function save_relation_note_hide(id_note_session, id_sec_event, id_lev_event, id_check){
	var func = 0;
	if ( document.getElementById(id_check).checked ) { func = 1; }
	x_save_relation_note_hide(id_note_session,id_sec_event,id_lev_event,func, Content_process);
}
function show_popup_notes_week(type,row,read_only){
	var id_event = document.getElementById('name_master_sel').value;
	var id_level = document.getElementById('level_master').value;
	x_show_session_notes_week(type,row,id_event,id_level,read_only, Content_popup_notes);
	jQuery("#hider_"+type).fadeIn("slow");
    jQuery('#popup_box_'+type).fadeIn("slow");
}
function show_popup_notes_week_summary(type,row,id_event,id_level,read_only){
	x_show_session_notes_week(type,row,id_event,id_level,read_only, Content_popup_notes);
	jQuery("#hider_"+type).fadeIn("slow");
    jQuery('#popup_box_'+type).fadeIn("slow");
}
function delete_session_note(id_note, type_note){
	var message_confirm = '';
	if (type_note == 3) { // Secundario
		message_confirm = 'La nota del Evento Secundario será eliminada y ya no podrás recuperarla, Deseas continuar?';
	}
	if (type_note == 2) { // Evento
		message_confirm = 'Esta nota podría estar siendo utilizada en algunos Grupos Secundarios, si la eliminas, tambien dejarás de verla en ellos, deseas continuar?';
	}
	if (type_note == 1) { // Template
		message_confirm = 'Esta nota podría estar siendo utilizada en algunos Grupos y Secundarios, si la eliminas, tambien dejarás de verla en ellos, deseas continuar?';
	}
	if (confirm(message_confirm)) { x_delete_session_note(id_note, Content_process); }else{ jQuery().toastmessage('showErrorToast', 'Eliminación Cancelada...'); }
}
function load_sessions_nf(){
	jQuery('li').removeClass('active');
	jQuery('#summary').addClass('active');
	jQuery('.row').html('<h5 class="subtitle">Cargando Sesioes No Encontradas En Plataforma...</h5><br />');
	x_load_sessions_nf(0, Content_universal);
}
function confirm_check_session_not_found(id_sess,user){
	swal({
		title: "Registro Sesión no Encontrada",
		text: "La sesión se registrará como revisada por "+user+"\nDeseas continuar?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#EA2734",
		confirmButtonText: "Si, Registrar!"
	},
	function(){ x_check_session_not_found(id_sess, Content_process); });
}
