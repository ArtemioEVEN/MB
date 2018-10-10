<?php
	date_default_timezone_set("America/Mexico_City");
  
  sajax_export("get_events_form"); //Obtiene forma para insertar Eventos o Masters en DB

  	// / / / / / / / / /
	function get_events_form(){
            $option = 0;
		$form = '';
		$form .= '
				<div class="widget">
					<h4 class="widgettitle"> Agregar Nuevo Grupo</h4>
					<div class="widgetcontent">
                                 <form class="form-inline" role="form">
                                 <label>Info Grupo <span style="color:red;">*</span></label><br> 
                                    <div class="form-group">
                                          <select id="type_ee" class="dropdown form-control input-default" name="type_ee" tabindex="2" style="width:100px" >
                                                <option value=""></option>
                                                <!--
                                                <option value="Bicicleta">Bicicleta</option>
                                                <option value="Carrera">Carrera</option>
                                                <option value="Natacion">Natación</option>
                                                <option value="Triatlon">Triatlón</option>
                                                -->
                                                <option value="Carrera">Carrera</option>
                                                <option value="Natacion">Natación</option>
                                                <option value="Triatlon">Triatlón</option>
                                                <option value="Ciclismo">Ciclismo</option>
                                                <option value="Otros">Otros</option>
                                          </select><b style="font-size:20px;"> . </b>
                                    </div>
                                    <div class="form-group">'.build_select_db('Types', 'name_t', 'name_t', 'type_event', '', 0, '', '', '', '', '', 0).'</div><b style="font-size:20px;"> . </b>
                                    <div class="form-group">
                                          <label class="sr-only" for="name_ee">Tipo</label>
                                          <input id="name_ee" class="form-control input-default" placeholder="Ingresa Nombre" type="text">
                                    </div><b style="font-size:20px;"> . </b>
                                    <div class="form-group">
                                          <label class="sr-only" for="str_ee">Tipo</label>
                                          <input id="str_ee" class="form-control input-default" placeholder="Estructura" type="text">
                                    </div><b style="font-size:20px;"> . </b>
                                    <div class="form-group">'.build_select_db('Levels', 'name_l', 'name_l', 'level_master', '', 0, '', '', '', '', '', 0).'</div>
                                 </form>
                                    <!--
                                    <div class="form-group">'.build_select_db('Types', 'name_t', 'id_t', 'type_event', 'Categoría Grupo', 1, '', '', '', '', '', 0).'</div>
						<div class="form-group">
							<label for="name_event">Nombre Grupo <span style="color:red;">*</span></label>
							<input id="name_event" class="form-control input-default" type="text" placeholder="Nombre del Grupo">
						</div>
                                    -->
						<div class="form-group">
                                          <label for="date_event">Fecha Grupo <span style="color:red;">*</span></label>
                                          <input id="datepicker" type="text" placeholder="yyyy/mm/dd" name="date" class="form-control" onchange="check_day_date(this.value); return false;"/>
                                    </div>
						<div class="form-group">
                        	<!--<label for="notes_event">Notas</label>-->
                        	<textarea cols="50" rows="4" id="notes_event" class="form-control input-default" style="display:none;"></textarea>
                        
                    </div>
                    <div id="load"></div><div id="load_nothing"></div>
					<br>
                    <button class="btn btn-primary" type="button" onclick="get_info_new_event(); return false;">Guardar Grupo</button>
					
					</div>
				  </div>
				  <div class="second_content"></div>
		';
		return '2@@@'.'<div id="dashboard-left" class="col-md-12">'.$form.'</div>@@@'.$option;
	}
	// / / / / / / / / /
?>
