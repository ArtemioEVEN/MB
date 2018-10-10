<?php
	date_default_timezone_set("America/Mexico_City");
  
  	sajax_export("get_events_form"); //Obtiene forma para insertar Eventos o Masters en DB
	sajax_export("get_events_table");		  //Obtiene los Eventos o Masters disponibles en DB

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
	function get_events_table($filter, $option){
		$table = $check1 = $check2 = $check3 = $check4 = $status = $type = '';
		switch ($filter) {
			case 0: $check1 = 'checked'; $status = 'IS NOT NULL';  break;// All
			case 1: $check2 = 'checked'; $status = '= "PASADO"';   break;// Past
			case 2: $check3 = 'checked'; $status = '= "PRESENTE"'; break;// Present
			case 3: $check4 = 'checked'; $status = '= "FUTURO"';   break;// Future
			
			default:
				$check1 = 'checked'; $status = 'IS NOT NULL';
				break;
		}
		switch ($option) {
			case 0: //EVENTO
				$type = 'Grupo';
				$filters = '&nbsp;&nbsp;&nbsp;&nbsp;
					<input id="opt_event1" type="radio" value="0" name="all_events" '.$check1.' onclick="get_events_table(this.value, '.$option.'); return false;"> Todos     
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input id="opt_event2" type="radio" value="1" name="all_events" '.$check2.' onclick="get_events_table(this.value, '.$option.'); return false;"> Pasados   
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input id="opt_event3" type="radio" value="2" name="all_events" '.$check3.' onclick="get_events_table(this.value, '.$option.'); return false;"> Presentes 
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input id="opt_event4" type="radio" value="3" name="all_events" '.$check4.' onclick="get_events_table(this.value, '.$option.'); return false;"> Futuros <br><br>';
					break;
			case 1: //MASTER 
				$type = 'Template'; 
				$filters = '';
					break;
			default:
				# code...
				break;
		}
		$values = select_gral('`Events` AS E INNER JOIN `Types` AS T ON T.id_t = E.type INNER JOIN `VIEW_get_status_events` AS F ON F.id_e = E.id_e',
							  'E.id_e, E.name_e, T.name_t AS type_e, E.weeks, E.date_e, F.status_e, E.master,
      (SELECT COUNT(*) FROM Trainings WHERE event = E.id_e) AS trains',
							  'F.status_e '.$status.' AND E.master = "'.$option.'"',
							  'E.date_e');// tabla, campos, filtros, orden
            $table_aux = '<hr>
            <h4 class="widgettitle">'.$type.' Agregados</h4>
                  '.$filters.'';
  $table_aux .= ' <table id="dyntable" class="table table-bordered responsive">
                        <thead>
                              <tr>
                                    <th class="head1" title="Nombre del '.$type.'">'.$type.'</th>';
                                    if ($option == 0) { $table_aux .= '<th class="head0" title="Fecha del '.$type.'">Fecha</th>'; }//SI Eventos, NO Masters
                                    if ($option == 1) { $table_aux .= '<th class="head1" title="Semanas del '.$type.'">Semanas</th>'; }//Si Master
            $table_aux .= '         <th class="head0" title="Categoría del '.$type.'">Categoría</th>';
                                    if ($option == 0) { $table_aux .= '<th class="head1" title="Estado del '.$type.'">Estado</th>'; }//SI Eventos, NO Masters
                                    if ($option == 1) { $table_aux .= '<th class="head0" title="Estado del '.$type.'">Estado</th>'; }//Si Mstr
            $table_aux .= '   </tr>
                        </thead>
                        <tbody>';
            if($values == true){
                  foreach ($values as $key) {
                        $table_aux .= '
                                    <tr class="grade'.utf8_encode($key[1]).'">
                                          <td>'.utf8_encode($key[1]).'</td>';
                                          if ($option == 0) { $table_aux .= '<td>'.utf8_encode($key[4]).'</td>'; }//SI Eventos, NO Masters
                                          if ($option == 1) { $table_aux .= '<td>'.utf8_encode($key[3]).'</td>'; }//Si Master
                        $table_aux .= '       <td>'.utf8_encode($key[2]).'</td>';
                                          if ($option == 0) { $table_aux .= '<td>'.utf8_encode($key[5]).'</td>'; }//SI Eventos, NO Masters
                                          if ($option == 1) { ($key[7] > 0) ? $table_aux .= '<td>Con sessiones</td>' : $table_aux .= '<td>Incompleto</td>'; }//Si Master
                        $table_aux .= ' </tr>';
                  }
            }#else{ $table_aux .= ' <tr><td colspan="6"> No hay '.$type.'s disponibles...</td></tr>'; }
            $table_aux .= '       </tbody>
                              </table>';
		//return $title.$filters.$table.$table_aux;

            return $table_aux;
	}
	// / / / / / / / / /
?>
