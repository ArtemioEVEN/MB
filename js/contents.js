function Content_general(res){ // YA
	res = res.split("@@@");
	var origen  = res[0];//De que funcion viene el contenido
	var content = res[1];//Contenido
	var paramtr = res[2];//Parametro(s) adicional(es)
	jQuery('.row').html(content);
	// - - - - - - - 
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
