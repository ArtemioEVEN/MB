<?php
	date_default_timezone_set("America/Mexico_City");
  
  	sajax_export("get_events_form");	//Obtiene forma para insertar Eventos o Masters en DB
	sajax_export("get_events_table");	//Obtiene los Eventos o Masters disponibles en DB
	sajax_export("save_new_event");		//Guarda un Evento o un Master en DB
	sajax_export("get_events_table_editable");
	sajax_export("get_event_form_edit");
	sajax_export("edit_event");
	sajax_export("generate_extra_weeks_event");
	sajax_export("save_blocked_event_week");//Bloqueo de grupos
	sajax_export("del_block_event");	//Eliminado de bloque de grupos

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
	function save_new_event($event_info, $process){//name_event||date_event||week_event||type_event||note_event
            $master = 0;
		$event_info = explode('||', $event_info);
		$chars = array("á", "é", "í", "ó", "ú");
		$replc = array("a", "e", "i", "o", "u");
		$name_event = $date_event = $response = '';
            $type_event = $week_event = $note_event = '';
		$name_event = $event_info[0];
            $name_details = explode(".", $name_event);
		$date_event = $event_info[1];
            # Se agrego una revisión para evitar guardar eventos identicos de nombre
            $exist_evt = select_gral('`Events`','count(*)','name_e ="'.$name_event.'"','name_e'); #$response = $exist_evt;
            if ($exist_evt == true) { foreach ($exist_evt as $key) { $exist_evt = $key[0]; } }else{ $exist_evt = 0; } #$response = $exist_evt;
            if ($exist_evt > 0) {
                  $response = "repeat_name";
            }else{
                  $insert = insert_gral('`Events`',
                                            'name_e, date_e, weeks, type, master',
                                            '"'.$name_event.'","'.$date_event.'","0",(SELECT id_t FROM `Types` WHERE name_t='.'"'.$name_details[1].'"'.'),"'.$master.'"');
                  $response = intval($insert); #$response = $insert;
            }
            if ($response > 0) {
                  # Activity_code = 2
                  $message = "El usuario *".$_SESSION['name_u']."* agregó un grupo nuevo llamado *".$name_event."*";
                  log_mb_register($message, "2");
            }
		return $process.'@-@'.$response.'@-@'.$master.'###'.$name_event.'###'.$response.'###'.$type_event;
	}
	// / / / / / / / / /
	function get_events_table_editable($filter, $option, $order_extra){// option 1 = Eventos, 2 = Template 
            if ($order_extra == '') { $order_extra = 'E.date_e'; }
            $table = $check1 = $check2 = $check3 = $check4 = $status = $type = '';
            ($option == 1) ? $template = 0 : $template = 1;
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
                  case 1: //EVENTO
                        $type = 'Grupo';
                        $filters = '&nbsp;&nbsp;&nbsp;&nbsp;
                              <input id="opt_event1" type="radio" value="0" name="all_events" '.$check1.' onclick="get_edit_list(this.value, '.$option.','."''".'); return false;"> Todos     
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <input id="opt_event2" type="radio" value="1" name="all_events" '.$check2.' onclick="get_edit_list(this.value, '.$option.','."''".'); return false;"> Pasados   
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <input id="opt_event3" type="radio" value="2" name="all_events" '.$check3.' onclick="get_edit_list(this.value, '.$option.','."''".'); return false;"> Presentes 
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <input id="opt_event4" type="radio" value="3" name="all_events" '.$check4.' onclick="get_edit_list(this.value, '.$option.','."''".'); return false;"> Futuros <br><br>';
                              break;
                  case 2: //Template
                        $type = 'Template'; 
                        $filters = '';
                              break;
                  default:
                        # code...
                        break;
            }
            $title = '<h4 class="widgettitle">'.$type.' Agregados</h4><!--<h5 class="subtitle">'.$type.' Agregados</h5>-->';
            $table .= '<input type="hidden" id="filter_e_e" value="'.$filter.'"/>';
            $table .= '<input type="hidden" id="option_e_e" value="'.$option.'"/>';
            $table .= ' <table id="dyntable" class="table table-bordered responsive">
                                    <thead>
                                          <tr>
                                                <!--<th class="head1" title="Nombre del '.$type.'">'.$type.'</th>-->
                                                <th class="head0"></th>
                                                <th class="head1" title="Nombre del '.$type.'"><img id="order_e_n" src="./img/images/up01.png" style="width:10px; position:inherit; cursor:pointer;" onclick="check_order_edit_list(this.id); return false;"> '.$type.'</th>';
                                                if ($option == 1) { $table .= '<th class="head0" title="Fecha del '.$type.'"><img id="order_e_d" src="./img/images/up01.png" style="width:10px; position:inherit; cursor:pointer;" onclick="check_order_edit_list(this.id); return false;"> Fecha</th>'; }//SI Eventos, NO Masters
                                                if ($option == 2) { $table .= '<th class="head1" title="Semanas del '.$type.'">Semanas</th>'; }//Si Master
            $table .= '                         <th class="head0" title="Categoría del '.$type.'"><img id="order_e_c" src="./img/images/up01.png" style="width:10px; position:inherit; cursor:pointer;" onclick="check_order_edit_list(this.id); return false;"> Categoría</th>';
                                                if ($option == 1) { $table .= '<th class="head1" title="Estado del '.$type.'">Estado</th>'; }//SI Eventos, NO Masters
            //$table .= '                             <th class="head0" title="Notas acerca del '.$type.'">Notas</th>';
                                                if ($option == 2) { $table .= '<th class="head0" title="Estado del '.$type.'">Estado</th>'; }//Si Mstr
                                                if ($option == 1) { $table .= '<th class="head1" title="Eliminar '.$type.'"></th>'; }//SI Eventos, NO Masters
            $table .= '                       </tr>
                                    </thead>
                                    <tbody>';
            $values = select_gral('`Events` AS E INNER JOIN `Types` AS T ON T.id_t = E.type INNER JOIN `VIEW_get_status_events` AS F ON F.id_e = E.id_e',
                                            'E.id_e, E.name_e, T.name_t AS type_e, E.weeks, E.date_e, F.status_e, E.master,
      (SELECT COUNT(*) FROM Trainings WHERE event = E.id_e) AS trains',
                                            'F.status_e '.$status.' AND E.master = "'.$template.'" '/*AND temporary="0"*/,
                                            $order_extra);// tabla, campos, filtros, orden
            if($values == true){
                  $i=1;
                  foreach ($values as $key) {
                        $table .= '
                                    <tr class="grade'.utf8_encode($key[1]).'">
                                          <td>'.$i.'</td>
                                          <td><b><a href="#" onclick="get_event_form_edit('.$key[0].','.$option.'); return false;">'.utf8_encode($key[1]).'</a></b></td>';
                                          if ($option == 1) { $table .= '<td>'.utf8_encode($key[4]).'</td>'; }//SI Eventos, NO Masters
                                          if ($option == 2) { $table .= '<td>'.utf8_encode($key[3]).'</td>'; }//Si Master
                        $table .= '       <td>'.utf8_encode($key[2]).'</td>';
                                          if ($option == 1) { $table .= '<td>'.utf8_encode($key[5]).'</td>'; }//SI Eventos, NO Masters
                        //$table .= '       <td>'.utf8_encode($key[6]).'</td>';
                                          if ($option == 2) { ($key[7] > 0) ? $table .= '<td>Con sessiones</td>' : $table .= '<td>Incompleto</td>'; }//Si Master
                                          if ($option == 1) { $table .= '<td><center><img title="Eliminar este Grupo" src="./img/images/garbage.png" style="width:16px; cursor:pointer;" onclick="confirm_delete_event('.utf8_encode($key[0]).'); return false;"/></center></td>'; }//SI Eventos, NO Masters
                        $table .= ' </tr>';
                        $i++;
                  }
            }else{
                  $table .= '
                              <tr>
                                    <td colspan="6"> No hay '.$type.'s disponibles...</td>
                              </tr>';
            }
            $table .= '       </tbody>
                              </table>
                              <div id="load_edit_form"></div>';
            $all_content = '<div id="dashboard-left" class="col-md-12">'.$title.$filters.$table.'</div>';
            return $all_content.'&&&.row_aux';
      }
	// / / / / / / / / /
	function get_event_form_edit($id_event, $option){#77776666
            $date = $type = $weeks = '';
            ($option == 1) ? $template = 0 : $template = 1;
            $category_event = $name_event = $date_event = '';
            $event_info = select_gral('`Events`', 'id_e, name_e, date_e, (SELECT name_t FROM Types WHERE id_t = type) AS type', 'id_e="'.$id_event.'"', 'id_e');//`Types`
            if($event_info == true){
                  foreach ($event_info as $key) {
                        $category_event = $key[3];//utf8_encode($key[3]);
                        $name_event = utf8_encode($key[1]);
                        $date_event = utf8_encode($key[2]);
                  }
            }
            switch ($option) {
                  case 1://EVENTO
                        $type = 'Grupo';
                        $date = '<div class="form-group">
                                    <label for="date_event">Fecha Grupo <span style="color:red;">*</span></label>
                                    <input id="datepicker" type="text" placeholder="yyyy/mm/dd" name="date" class="form-control" value="'.$date_event.'"/>
                                 </div>';
                        break;
                  case 2://Template
                        $type = 'Template';
                        break;
                  
                  default: break;
            }
            $form = '<br>';
            if ($id_event <= 1667) {
                  $form .= '
                        <div class="widget">
                              <h4 class="widgettitle">Editar '.$type.' '.$name_event.'</h4>
                              <div class="widgetcontent">
                                    <input type="hidden" id="event_edit" value="'.$id_event.'">
                                    <div class="form-group">'.build_select_db('Types', 'name_t', 'id_t', 'type_event', 'Categoría '.$type, 1, '', '', '', '', $category_event, 0).'</div>
                                    <div class="form-group">
                                          <label for="name_event">Nombre '.$type.'<span style="color:red;">*</span></label>
                                          <input id="name_event" class="form-control input-default" type="text" placeholder="Nombre del '.$type.'" value="'.utf8_encode($name_event).'">
                                    </div>
                                    '.$date.'         
                                    '.$weeks.'
                                    <!--<div class="form-group">
                                          <textarea cols="50" rows="3" id="notes_event" class="form-control input-default" style="display:none;"></textarea>
                                    </div>-->
                                    <div id="load_edit_event"></div>
                                    <br>
                                    <button class="btn btn-primary" type="button" onclick="get_info_edit_event('.$option.'); return false;">Editar '.$type.'</button>
                              </div>
                        </div>';
            }else{
                  $t_e = $c_e = $n_e = $s_e = $l_e = $selected = '';
                  $name_event = explode('.', $name_event);
                  $t_e = $name_event[0]; $c_e = $name_event[1]; $n_e = $name_event[2]; $s_e = $name_event[3]; $l_e = $name_event[4];
                  $form .= '  
                        <div class="widget">
                              <h4 class="widgettitle"> Agregar Nuevo Grupo</h4>
                              <div class="widgetcontent">
                                    <input type="hidden" id="event_edit" value="'.$id_event.'">
                                 <form class="form-inline" role="form">
                                 <label>Info Grupo <span style="color:red;">*</span></label><br> 
                                    <div class="form-group">
                                          <select id="type_ee" class="chzn-select dropdown form-control input-default" name="type_ee" tabindex="2" style="width:130px" >
                                                <option value=""></option>';
                                    ($t_e == "Carrera") ? $selected = 'selected' : $selected = '';
                                    $form .= '  <option value="Carrera" '.$selected.'>Carrera</option>';
                                    ($t_e == "Natacion") ? $selected = 'selected' : $selected = '';
                                    $form .= '  <option value="Natacion" '.$selected.'>Natación</option>';
                                    ($t_e == "Triatlon") ? $selected = 'selected' : $selected = '';
                                    $form .= '  <option value="Triatlon" '.$selected.'>Triatlón</option>';
                                    ($t_e == "Ciclismo" || $t_e == 'Bicicleta') ? $selected = 'selected' : $selected = '';
                                    $form .= '  <option value="Ciclismo" '.$selected.'>Ciclismo</option>';
                                    ($t_e == "Otros") ? $selected = 'selected' : $selected = '';
                                    $form .= '  <option value="Otros" '.$selected.'>Otros</option>
                                          </select>
                                    </div><b style="font-size:20px;"> . </b>
                                    <div class="form-group">'.build_select_db('Types', 'name_t', 'name_t', 'type_event', '', 0, '', '', '', '', $c_e, 0).'</div><b style="font-size:20px;"> . </b>
                                    <div class="form-group">
                                          <label class="sr-only" for="name_ee">Tipo</label>
                                          <input id="name_event" class="form-control input-default" placeholder="Ingresa Nombre" type="text" value="'.$n_e.'">
                                    </div><b style="font-size:20px;"> . </b>
                                    <div class="form-group">
                                          <label class="sr-only" for="str_ee">Tipo</label>
                                          <input id="str_ee" class="form-control input-default" placeholder="Estructura" type="text" value="'.$s_e.'">
                                    </div><b style="font-size:20px;"> . </b>
                                    <div class="form-group">'.build_select_db('Levels', 'name_l', 'name_l', 'level_master', '', 0, '', '', '', '', $l_e, 0).'</div>
                                 </form>
                                    <div class="form-group">
                                          <label for="date_event">Fecha Grupo <span style="color:red;">*</span></label>
                                          <input id="datepicker" type="text" placeholder="yyyy/mm/dd" name="date" class="form-control" value="'.$date_event.'" onchange="check_day_date(this.value); return false;"/>
                                    </div>
                                    <div id="load_edit_event"></div>
                                    <br>
                                    <button class="btn btn-primary" type="button" onclick="get_info_edit_event(2); return false;">Editar '.$type.'</button>
                              </div>
                          </div>';
            }
            return $form.'&&&#load_edit_form';
      }
	// / / / / / / / / /
	function edit_event($option, $id_event, $parameter1, $parameter2, $parameter3){
            $parameter1 = utf8_decode(str_replace("%uFFFD", "ó", $parameter1));
            $parameter2 = utf8_decode(str_replace("%uFFFD", "ó", $parameter2));
            $parameter3 = utf8_decode(str_replace("%uFFFD", "ó", $parameter3));
            $result = $edit_note = '';
            if ($option == 2) {
                  $category = select_gral('`Types`', 'id_t', 'name_t="'.$parameter1.'"', 'id_t LIMIT 1');
                  if ($category == true) { foreach ($category as $key) { $category = $key[0]; } }
            }else{ $category = $parameter1; }
            $name =     $parameter2;
            $date =     $parameter3;
            $edit_note = update_gral('`Events`','type="'.$category.'", name_e="'.$name.'", date_e="'.$date.'"','id_e="'.$id_event.'"');
            if ($edit_note > 0) {
                  # Activity_code = 1
                  $message = "El usuario *".$_SESSION['name_u']."* editó el grupo llamado *".$name."*";
                  log_mb_register($message, "1");
#######COMIENZA
                  $lcr = select_gral('`Last_change_realized`', 'COUNT(*)', 'id_e="'.$id_event.'" AND id_s=0 AND user="'.$_SESSION['name_u'].'"', 'id_lcr LIMIT 1');
                  if ($lcr_exist == true) { foreach ($lcr_exist as $lcr) {$lcr_exist = intval($lcr[0]); } }
                  if ($lcr_exist > 0) {
                        $insert_lcr = update_gral('`Last_change_realized`','date_lcr="'.date("Y-m-d h:i:s").'"', 'id_e="'.$id_event.'" AND id_s=0 AND user="'.$_SESSION['name_u'].'"');
                  }else{ $insert_lcr = insert_gral('`Last_change_realized`','id_e,id_s,user,date_lcr','"'.$id_event.'",0,"'.$_SESSION['name_u'].'","'.date("Y-m-d h:i:s").'"'); }
#######FINALIZA
            }
            return '3@-@'.$edit_note.'@-@'.$option;
      }
	// / / / / / / / / /
	function generate_extra_weeks_event($weeks,$week,$date,$background,$hidden_cols,$id_temporal){# return $weeks.",".$week.",".$date.",".$background;
      $tabla1 = $tabla2 = "";
      $all_weeks = intval($week+$weeks);
      $update_temporal = 0;
      $date_temp = select_gral('`Events`','date_e','id_e="'.$id_temporal.'"','id_e');
      if ($date_temp == true) { 
            foreach ($date_temp as $key_e) { $date_temp = $key_e[0]; } 
            $date_temp = strtotime($date_temp);
            $date_temp = strtotime("+".$weeks." week", $date_temp);
            $date_temp = date('Y-m-d', $date_temp);
            $update_temporal = update_gral('`Events`','date_e="'.$date_temp.'", weeks="'.($all_weeks-1).'"','id_e="'.$id_temporal.'"');
      }
      if ($update_temporal > 0) {
            $hidde01 = $hidde02 = $hidde03 = $hidde04 = $hidde05 = $hidde06 = $hidde07 = $hidde08 = $hidde09 = $hidde10 = $hidde11 = $hidde12 = $hidde13 = $hidde14 = $hidde15 = $hidde16 = $hidde17 = $hidde18 = $hidde19 = $hidde20 = $hidde21 = "";
            $hidden_cols = ",".$hidden_cols.",";
            if(strpos($hidden_cols, ",1,") !== false){$hidde01 = 'display:none;';}if(strpos($hidden_cols, ",2,") !== false){$hidde02 = 'display:none;';}
            if(strpos($hidden_cols, ",3,") !== false){$hidde03 = 'display:none;';}if(strpos($hidden_cols, ",4,") !== false){$hidde04 = 'display:none;';}
            if(strpos($hidden_cols, ",5,") !== false){$hidde05 = 'display:none;';}if(strpos($hidden_cols, ",6,") !== false){$hidde06 = 'display:none;';}
            if(strpos($hidden_cols, ",7,") !== false){$hidde07 = 'display:none;';}if(strpos($hidden_cols, ",8,") !== false){$hidde08 = 'display:none;';}
            if(strpos($hidden_cols, ",9,") !== false){$hidde09 = 'display:none;';}if(strpos($hidden_cols, ",10,") !== false){$hidde10 = 'display:none;' ;}
            if(strpos($hidden_cols, ",11,") !== false){$hidde11 = 'display:none;';}if(strpos($hidden_cols, ",12,") !== false){$hidde12 = 'display:none;';}
            if(strpos($hidden_cols, ",13,") !== false){$hidde13 = 'display:none;';}if(strpos($hidden_cols, ",14,") !== false){$hidde14 = 'display:none;';}
            if(strpos($hidden_cols, ",15,") !== false){$hidde15 = 'display:none;';}if(strpos($hidden_cols, ",16,") !== false){$hidde16 = 'display:none;';}
            if(strpos($hidden_cols, ",17,") !== false){$hidde17 = 'display:none;';}if(strpos($hidden_cols, ",18,") !== false){$hidde18 = 'display:none;';}
            if(strpos($hidden_cols, ",19,") !== false){$hidde19 = 'display:none;';}if(strpos($hidden_cols, ",20,") !== false){$hidde20 = 'display:none;';}
            if(strpos($hidden_cols, ",21,") !== false){$hidde21 = 'display:none;';}

            for ($i=($week+1); $i<=$all_weeks; $i++) {
                  $date = strtotime($date);
                  $date = strtotime("+1 week", $date);
                  $date = date('d M', $date);
                  $tabla1 .= '<tr class="row_'.$i.'" style="background:'.$background.'"><td class="g h">'.$date.'</td><td class="g"><input type="text" class="row_'.$i.' med cicle_week_'.$i.'" id="cicle_week_'.$i.'" onkeyup="show_value_tables('.$i.', this.value, this.id); change_r_color(this.value,'.$all_weeks.','.$i.','."'none'".'); change_cicle_week_temp(this.value,'.$i.'); return false;" value="" style="background:'.$background.';"></td><td class="summary_session_'.$i.'" id="summary_session_'.$i.'">B/R → <span id="br_'.$i.'">0</span> | <span id="tp_'.$i.'">0</span> TSS/día</td><td id="week_num" class="g">Sem '.$i.'</td><td class="g h"></td><td class="d03 h"></td></tr>';

                  $tabla2 .= '<tr class="row_'.$i.'" style="background:'.$background.'"><td></td><td></td><td></td><td></td><td></td><td></td><td class="ses1 n_w" style="'.$hidde01.' border-left-width:medium;border-left-color:darkgray;"><input type="text" class="row_'.$i.' session_trains session_1_1_'.$i.'" id="session_1_1_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde01.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_1_1_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses2" style="'.$hidde02.'"><input type="text" class="row_'.$i.' session_trains session_2_1_'.$i.'" id="session_2_1_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde02.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_2_1_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses3" style="'.$hidde03.'"><input type="text" class="row_'.$i.' session_trains session_3_1_'.$i.'" id="session_3_1_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde03.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_3_1_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses4" style="'.$hidde04.'border-left-width:medium;border-left-color:darkgray;"><input type="text" class="row_'.$i.' session_trains session_1_2_'.$i.'" id="session_1_2_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde04.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_1_2_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses5" style="'.$hidde05.'"><input type="text" class="row_'.$i.' session_trains session_2_2_'.$i.'" id="session_2_2_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde05.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_2_2_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses6" style="'.$hidde06.'"><input type="text" class="row_'.$i.' session_trains session_3_2_'.$i.'" id="session_3_2_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde06.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_3_2_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses7" style="'.$hidde07.'"><input type="text" class="row_'.$i.' session_trains session_1_3_'.$i.'" id="session_1_3_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde07.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_1_3_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses8" style="'.$hidde08.'"><input type="text" class="row_'.$i.' session_trains session_2_3_'.$i.'" id="session_2_3_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde08.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_2_3_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses9" style="'.$hidde09.'"><input type="text" class="row_'.$i.' session_trains session_3_3_'.$i.'" id="session_3_3_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde09.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_3_3_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses10" style="'.$hidde10.'"><input type="text" class="row_'.$i.' session_trains session_1_4_'.$i.'" id="session_1_4_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde10.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_1_4_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses11" style="'.$hidde11.'"><input type="text" class="row_'.$i.' session_trains session_2_4_'.$i.'" id="session_2_4_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde11.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_2_4_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses12" style="'.$hidde12.'"><input type="text" class="row_'.$i.' session_trains session_3_4_'.$i.'" id="session_3_4_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde12.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_3_4_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses13" style="'.$hidde13.'"><input type="text" class="row_'.$i.' session_trains session_1_5_'.$i.'" id="session_1_5_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde13.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_1_5_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses14" style="'.$hidde14.'"><input type="text" class="row_'.$i.' session_trains session_2_5_'.$i.'" id="session_2_5_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde14.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_2_5_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses15" style="'.$hidde15.'"><input type="text" class="row_'.$i.' session_trains session_3_5_'.$i.'" id="session_3_5_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde15.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_3_5_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses16" style="'.$hidde16.'"><input type="text" class="row_'.$i.' session_trains session_1_6_'.$i.'" id="session_1_6_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde16.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_1_6_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses17" style="'.$hidde17.'"><input type="text" class="row_'.$i.' session_trains session_2_6_'.$i.'" id="session_2_6_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde17.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_2_6_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses18" style="'.$hidde18.'"><input type="text" class="row_'.$i.' session_trains session_3_6_'.$i.'" id="session_3_6_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde18.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_3_6_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses19" style="'.$hidde19.'"><input type="text" class="row_'.$i.' session_trains session_1_7_'.$i.'" id="session_1_7_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde19.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_1_7_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses20" style="'.$hidde20.'"><input type="text" class="row_'.$i.' session_trains session_2_7_'.$i.'" id="session_2_7_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde20.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_2_7_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td class="ses21" style="'.$hidde21.'"><input type="text" class="row_'.$i.' session_trains session_3_7_'.$i.'" id="session_3_7_'.$i.'" value="" onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background: rgb(239, 251, 251); height: 1.65em; '.$hidde21.'"><img id="add_note_pencil" src="./img/images/notes_.png" style="width:18px; vertical-align: bottom; text-align: right; cursor:pointer; " onclick="show_popup_notes(3, '."''".', '."'session_3_7_".$i."'".', '.$i.', 3,0); return false;" class="note_1_1_'.$i.'" hidden></td><td id="total_tss_week_'.$i.'">S: 0 | B: 0 | R: 0</td></tr>';
            }
      }
      return $update_temporal."#@#@#".$tabla1."#@#@#".$tabla2;
}
	// / / / / / / / / /
	function save_blocked_event_week($id_event,$week,$note,$row){
      //origen@-@response@-@paramtr
            $insert_bg = insert_gral('`Blocked_groups`', '`id_e`,`week`,`user`,`reason`', '"'.$id_event.'","'.$week.'","'.$_SESSION['name_u'].'","'.utf8_decode($note).'"');# tabla, campos, valores
            $res_log = intval($insert_bg);

            if ($res_log > 0) {
                  # Activity_code = 19
                  $name = select_gral('Events','name_e','id_e="'.$id_event.'"','id_e LIMIT 1');
                  if ($name == true) { foreach ($name as $key) { $name = $key[0]; } }
                  $message = "El usuario *".$_SESSION['name_u']."* bloqueó el envio del grupo *".$name."* en la semana *".$week."*";
                  log_mb_register($message, "19");
            }
            return "34@-@".intval($insert_bg)."@-@".$row."-|-".$id_event."_".$row."-|-".$name."-|-".$week."-|-".$_SESSION['name_u']."-|-".utf8_decode($note)."-|-".$_SESSION['name_u'];
      }
	// / / / / / / / / /
	function del_block_event($id_bg,$row){
            $id_e = $week = $name = "";
            $info_bg = select_gral('`Blocked_groups` AS bg','bg.id_e,bg.week,(SELECT name_e FROM `Events` WHERE id_e=bg.id_e LIMIT 1) as name','bg.id_bg="'.$id_bg.'"','bg.id_bg LIMIT 1');
            if ($info_bg == true) { foreach ($info_bg as $key_bg) { $id_e = $key_bg[0]; $week = $key_bg[1]; $name = $key_bg[2]; } }
            $delete_gb = delete_gral('`Blocked_groups`', "id_bg='".$id_bg."'");
            if ($delete_gb == 1) {
                  # Activity_code = 20
                  $message = "El usuario *".$_SESSION['name_u']."* activó el envio del grupo *".$name."* en la semana *".$week."*";
                  log_mb_register($message, "20");
            }
            return "35@-@".$delete_gb."@-@".$row."-|-".$id_e."-|-".$week."-|-".$name;
      }
	// / / / / / / / / /
	// / / / / / / / / /
	// / / / / / / / / /
	// / / / / / / / / /
	// / / / / / / / / /
?>
