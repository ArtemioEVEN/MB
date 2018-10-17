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
