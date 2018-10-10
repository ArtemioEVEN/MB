<?php
	date_default_timezone_set("America/Mexico_City");

	sajax_export("get_blocks_form"); # Formulario de Bloques
	sajax_export("load_block_info"); # Carga la estructura definida de un block para guardar sesiones
	sajax_export("show_session_training_form_block"); # Carga ventana para diseñar las sesiones en bloques
	sajax_export("save_session_block"); # Guarda las sesiones insertadas en los bloques (individualmemte)
	sajax_export("get_edit_list_blocks"); # Obtiene lista de bloques para editar
	sajax_export("update_block_info"); # Obtiene y busca actualizar la infromacion general de un bloque
	sajax_export("load_form_block"); # Carga las opciones para cargar un bloque en un template
	sajax_export("get_block_set_info"); # Obtiene las sessiones contenidas en el block para ponerlos en el template
    sajax_export("delete_block"); # Elimina bloque y sesiones de este

	function get_blocks_form($id_block){
		# $id_block si es 0, es para generar nuevo, sino, es para editar
		$option = 0;
		$content = $hr = $selected = $on_change = "";
		$id_b = $name_b = $weeks_b = $sess_b = $desc_b = $disc_b = $days_b = "";
		if ($id_block == 0 || $id_block == "0") { # Si es un Bloque nuevo
			$content = ".row"; 
			$id_b = "none";
			$option = 0;
		}else{ # Si es la edición de un bloque que ya existe
			$content = "#load_edit_form";
			$hr = "<hr>";
			$block_info = select_gral('`Blocks`', 'id_b,name_b,weeks_b,sess_b,desc_b,discip_name,days_b', 'id_b="'.$id_block.'"', 'id_b');
			if ($block_info == true) {
				foreach ($block_info as $key) {
					$id_b    = intval($key[0]);
					$name_b  = $key[1];
					$weeks_b = intval($key[2]);
					$sess_b  = $key[3];
					$desc_b  = $key[4];
                    $disc_b  = $key[5];
                    $days_b  = $key[6];
				}
			}
			$option = 1;
			$on_change = "change_value_button('#blck_button','Actualizar Info Bloque y Cargar Sesiones'); return false;";
		}
		$form = '';
		$form .= '
				<div class="widget">
					<h4 class="widgettitle">Define el Nuevo Bloque</h4>
					<div class="widgetcontent">
						<input type="hidden" id="id_block" value="'.$id_b.'">
						<div class="form-group">
							<label for="name_block">Nombre del Bloque <span style="color:red;">*</span></label>
							<input id="name_block" class="form-control input-default" type="text" placeholder="Nombre del Bloque" value="'.utf8_encode($name_b).'" onkeyup="'.$on_change.'">
						</div>
                        <div class="form-group">
                            <label for="disc_block">Disciplina <span style="color:red;">*</span></label>
                            <select id="disc_block" class="dropdown form-control input-default " name="plan_id_col1" tabindex="2" style="width:200px">
                                <option value="">Selecciona una disciplina...</option>';
        ($disc_b == "run") ? $selected = "selected" : $selected = ""; $form .= '<option value="run" '.$selected.'> Run</option>';
        ($disc_b == "bike") ? $selected = "selected" : $selected = ""; $form .= '<option value="bike" '.$selected.'> Bike</option>';
        ($disc_b == "swim") ? $selected = "selected" : $selected = ""; $form .= '<option value="swim" '.$selected.'> Swim</option>';
        ($disc_b == "fuerza") ? $selected = "selected" : $selected = ""; $form .= '<option value="fuerza" '.$selected.'> Fuerza</option>';
        ($disc_b == "triatlon") ? $selected = "selected" : $selected = ""; $form .= '<option value="triatlon" '.$selected.'> Triatlon</option>
                                <!--<option value="run">Run</option>
                                <option value="bike">Bike</option>
                                <option value="swim">Swim</option>
                                <option value="fuerza">Fuerza</option>-->
                            </select>
                        </div>
						<div class="form-group">
                            <label for="notes_block">Descripción <span style="color:red;">*</span></label>
                            <textarea cols="50" rows="4" id="notes_block" class="form-control input-default" placeholder="Escribe cualquier comentario que sirva en el futuro para el Block que vas a diseñar" onkeyup="'.$on_change.'">'.utf8_encode($desc_b).'</textarea>
                        </div>
                        <div class="form-group">
                            <label for="weeks_block">Semanas del Bloque <span id="amount_alert" style="color:red;">*</span></label>
                            <select id="weeks_block" class="dropdown form-control input-default " name="plan_id_col1" tabindex="2" style="width:140px" onchange="'.$on_change.'">
                                <option value="">...</option>';
        ($weeks_b == 1) ? $selected = "selected" : $selected = ""; $form .= '<option value="1" '.$selected.'> 1</option>';
        ($weeks_b == 2) ? $selected = "selected" : $selected = ""; $form .= '<option value="2" '.$selected.'> 2</option>';
        ($weeks_b == 3) ? $selected = "selected" : $selected = ""; $form .= '<option value="3" '.$selected.'> 3</option>';
        ($weeks_b == 4) ? $selected = "selected" : $selected = ""; $form .= '<option value="4" '.$selected.'> 4</option>';
        ($weeks_b == 5) ? $selected = "selected" : $selected = ""; $form .= '<option value="5" '.$selected.'> 5</option>';
        ($weeks_b == 6) ? $selected = "selected" : $selected = ""; $form .= '<option value="6" '.$selected.'> 6</option>';
        ($weeks_b == 7) ? $selected = "selected" : $selected = ""; $form .= '<option value="7" '.$selected.'> 7</option>';
        ($weeks_b == 8) ? $selected = "selected" : $selected = ""; $form .= '<option value="8" '.$selected.'> 8</option>';
        ($weeks_b == 9) ? $selected = "selected" : $selected = ""; $form .= '<option value="9" '.$selected.'> 9</option>';
        ($weeks_b == 10) ? $selected = "selected" : $selected = ""; $form .= '<option value="10" '.$selected.'> 10</option>';
        ($weeks_b == 11) ? $selected = "selected" : $selected = ""; $form .= '<option value="11" '.$selected.'> 11</option>';
        ($weeks_b == 12) ? $selected = "selected" : $selected = ""; $form .= '<option value="12" '.$selected.'> 12</option>';
        ($weeks_b == 13) ? $selected = "selected" : $selected = ""; $form .= '<option value="13" '.$selected.'> 13</option>';
        ($weeks_b == 14) ? $selected = "selected" : $selected = ""; $form .= '<option value="14" '.$selected.'> 14</option>';
        ($weeks_b == 15) ? $selected = "selected" : $selected = ""; $form .= '<option value="15" '.$selected.'> 15</option>';
        ($weeks_b == 16) ? $selected = "selected" : $selected = ""; $form .= '<option value="16" '.$selected.'> 16</option>';
        ($weeks_b == 17) ? $selected = "selected" : $selected = ""; $form .= '<option value="17" '.$selected.'> 17</option>';
        ($weeks_b == 18) ? $selected = "selected" : $selected = ""; $form .= '<option value="18" '.$selected.'> 18</option>';
        ($weeks_b == 19) ? $selected = "selected" : $selected = ""; $form .= '<option value="19" '.$selected.'> 19</option>';
        ($weeks_b == 20) ? $selected = "selected" : $selected = ""; $form .= '<option value="20" '.$selected.'> 20</option>';
        ($weeks_b == 21) ? $selected = "selected" : $selected = ""; $form .= '<option value="21" '.$selected.'> 21</option>';
        ($weeks_b == 22) ? $selected = "selected" : $selected = ""; $form .= '<option value="22" '.$selected.'> 22</option>';
        ($weeks_b == 23) ? $selected = "selected" : $selected = ""; $form .= '<option value="23" '.$selected.'> 23</option>';
        ($weeks_b == 24) ? $selected = "selected" : $selected = ""; $form .= '<option value="24" '.$selected.'> 24</option>';
        ($weeks_b == 25) ? $selected = "selected" : $selected = ""; $form .= '<option value="25" '.$selected.'> 25</option>';
        ($weeks_b == 26) ? $selected = "selected" : $selected = ""; $form .= '<option value="26" '.$selected.'> 26</option>';
        ($weeks_b == 27) ? $selected = "selected" : $selected = ""; $form .= '<option value="27" '.$selected.'> 27</option>';
        ($weeks_b == 28) ? $selected = "selected" : $selected = ""; $form .= '<option value="28" '.$selected.'> 28</option>';
        ($weeks_b == 29) ? $selected = "selected" : $selected = ""; $form .= '<option value="29" '.$selected.'> 29</option>';
        ($weeks_b == 30) ? $selected = "selected" : $selected = ""; $form .= '<option value="30" '.$selected.'> 30</option>
  				            </select>
                        </div>
                        <div class="form-group">
                            <label for="days_block">Dias del bloque <span style="color:red;">*</span></label>
                            <select id="days_block" class="dropdown form-control input-default " name="days_block" tabindex="2" style="width:140px" onchange="load_sessions_rows(this.value);">
                                <option value="">...</option>';
          ($days_b == 1) ? $selected = "selected" : $selected = ""; $form .= '<option value="1" '.$selected.'>1</option>';
          ($days_b == 2) ? $selected = "selected" : $selected = ""; $form .= '<option value="2" '.$selected.'>2</option>';
          ($days_b == 3) ? $selected = "selected" : $selected = ""; $form .= '<option value="3" '.$selected.'>3</option>';
          ($days_b == 4) ? $selected = "selected" : $selected = ""; $form .= '<option value="4" '.$selected.'>4</option>';
          ($days_b == 5) ? $selected = "selected" : $selected = ""; $form .= '<option value="5" '.$selected.'>5</option>';
          ($days_b == 6) ? $selected = "selected" : $selected = ""; $form .= '<option value="6" '.$selected.'>6</option>';
          ($days_b == 7) ? $selected = "selected" : $selected = ""; $form .= '<option value="7" '.$selected.'>7</option>
                            </select>
                        </div>
                        <div class="form-group" id="sessions_rows">
                            <label for="sessn_blck1">Sesiones por Día <span id="amount_alert" style="color:red;">*</span></label><br>
                            <div class="form-inline">';
            $aux_sesss = explode("-###-", $sess_b);
            $cout_sess = count($aux_sesss);
            for ($i=1; $i <= $cout_sess; $i++) {
                $form .= '      <select id="sessn_blck'.$i.'" class="dropdown form-control input-default" name="plan_id_col'.$i.'" tabindex="2" style="width:70px" onchange="">';
                $form .= '          <option value="">Día: '.$i.'</option>';
                (intval($aux_sesss[$i-1]) == 1) ? $selected = "selected" : $selected = "";
                $form .= '          <option value="1" '.$selected.'> 1</option>';
                (intval($aux_sesss[$i-1]) == 2) ? $selected = "selected" : $selected = "";
                $form .= '          <option value="2" '.$selected.'> 2</option>';
                (intval($aux_sesss[$i-1]) == 3) ? $selected = "selected" : $selected = "";
                $form .= '          <option value="3" '.$selected.'> 3</option>';
                $form .= '      </select>&nbsp;&nbsp;&nbsp;&nbsp;';
            }
                $form .= '  </div>
                        </div>
                        <!--
                        <div class="form-group">
                        	<label for="sessions_block">Sesiones Columna <span id="amount_alert" style="color:red;">*</span></label>
                            <select id="sessions_block" class="dropdown form-control input-default " name="plan_id_col1" tabindex="2" style="width:140px" onchange="'.$on_change.'">
       			                <option value="">...</option>';
        ($sess_b == 1) ? $selected = "selected" : $selected = ""; $form .= '<option value="1" '.$selected.'> 1</option>';
        ($sess_b == 2) ? $selected = "selected" : $selected = ""; $form .= '<option value="2" '.$selected.'> 2</option>';
        ($sess_b == 3) ? $selected = "selected" : $selected = ""; $form .= '<option value="3" '.$selected.'> 3</option>
                            </select>
                        </div>
                        -->
                    <div id="load"></div><div id="load_nothing"></div>
					<br>
                    <button class="btn btn-primary" id="blck_button" type="button" onclick="load_block_info('.$option.'); return false;">Cargar Bloque</button>
					</div>
				</div>
				<div class="second_content"></div>';
		# Con Content_general
		#return '1@@@'.'<div id="dashboard-left" class="col-md-8">'.$form.'</div>@@@';#PARA CARGAR BLOCKS BASARSE EN get_events_table (js)
		# Con Content_universal
		return '<div id="dashboard-left" class="col-md-8">'.$hr.$form.'</div>&&&'.$content.'&&&';
	}
	function load_block_info($id_b, $name_b, $desc_b, $weeks_b, $days_b, $sess_b, $discipline_b){
		$title = $table = $simple_session = "";
		$weeks_b = intval($weeks_b);
        $days_b  = intval($days_b);
		$sess_b  = trim($sess_b);
		$id_b 	 = intval($id_b);
		if ($id_b == 0) {
			$insert_block = intval(insert_gral('`Blocks`','name_b,discip_name,weeks_b,days_b,sess_b,desc_b','"'.$name_b.'","'.$discipline_b.'","'.$weeks_b.'","'.$days_b.'","'.$sess_b.'","'.$desc_b.'"'));
			if ($insert_block > 0) {
				# LOG
	            # Activity_code = 13
	            $message = "El usuario *".$_SESSION['name_u']."* agregó un nuevo Block: *".utf8_encode($name_b)."*";
                log_mb_register($message, "13");
			}
		}else{
			$insert_block = $id_b;
		}
		
		if ($insert_block > 0) {
            $title = '<div class="aux_load"></div>
                      <h3 class="pagetitle" id="type_train"> '.utf8_encode($name_b).'</h3>';
###### NUEVO
            $sess_b = explode("-###-", $sess_b);
            $aux_sess = 0;
            for ($i=0; $i < count($sess_b); $i++) { $aux_sess = intval($aux_sess+$sess_b[$i]); }
            $table = '<table id="t_blocks" class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="'.($aux_sess+1).'">'.utf8_encode($name_b).'</th>
                            </tr>
                            <tr style="background: lightgray;">
                                <td></td>';
            for ($i=1; $i <= $days_b; $i++) { 
                $table .= '     <td colspan="'.($sess_b[$i-1]).'"><b>Dia '.$i.'</b></td>';
            }
            $table .= '     </tr>';
            $table .= '     <tr style="background:#DDDDDD;">
                                <td></td>';
            for ($i=1; $i <= $days_b; $i++) { 
                for ($j=1; $j <= $sess_b[$i-1]; $j++) { 
                    $table .= ' <td>Session '.$j.'</td>';
                }
            }
            $table .= "     </tr>
                        </thead>
                        <body>";
            for ($i=0; $i < $weeks_b; $i++) {
                $table .= ' <tr>';
                $table .= '     <td>'.($i+1).'</td>';
                for ($j=1; $j <= $days_b; $j++) { 
                    for ($k=1; $k <= $sess_b[$j-1]; $k++) { #$simple_session = "";
                        if ($id_b > 0) {
                            $simple_session = select_gral('`Sessions` AS S INNER JOIN `Trainings_blocks` AS TB ON TB.session = S.id_ssn',
                                                          'S.session_desc',
                                                          'TB.block="'.$id_b.'" AND TB.week="'.($i+1).'" AND TB.day="'.$j.'" AND TB.session_num="'.$k.'"',
                                                          'S.session_desc');
                            if ($simple_session == true) { foreach ($simple_session as $key) { $simple_session = $key[0]; } }else{ $simple_session = ""; }
                        }

                        $table .= '<td><input id="session_'.$k.'_'.$j.'_'.($i+1).'" class="row_'.($i+1).' session_trains session_'.$k.'_'.$j.'_'.($i+1).'" type="text" style="border: none; height: 1.615em; cursor:pointer; width:100%;" onclick="show_popup_block(1, this.value, this.id, '.($i+1).'); return false;" value="'.$simple_session.'"></td>';
                    }
                }
                $table .= '</tr>';
            }
###### TERMINA NUEVO
		}
		$table .= '		</tbody>
                    </table>
                    <div class="load"></div>
                    <br/>
                    <!--<button class="btn btn-primary" type="button" style="position:absolute; right:5%;" onclick="save_all_data_db_confirm(); return false;" > Guardar Cambios </button>-->
                    <!-- Ventana emregente -->
                    <div id="hider_1" onclick="close_popup(1); return false;"></div>
                    <div id="popup_box_1">
                        <a class="close" onclick="close_popup(1); return false;">X</a>
                        <br>
                        <div class="message_box_1"></div>
                    </div> ';
        if ($id_b > 0) { $insert_block = 0; }
		return $title.$table.'&&&.second_content&&&'.$insert_block;
	}
	function show_session_training_form_block($session_training, $id_input, $row, $type, $prev_ses){
        $session_training = str_replace('mmaass', '+', $session_training);
        $prev_ses_content = $checked = '';
        $prev_ses         = str_replace('mmaass', '+', $prev_ses);
        $class_button = 'btn-disabled';
        # DE MOMENTO NO ESTA FUNCIONANADO PARA COPIAR SESION ANTERIOR NO COMPLETAR COLUMNA
#########

        if ($prev_ses != '') {
            ($prev_ses == $session_training) ? $checked = 'checked' : $checked = '';
            $prev_ses_content = '<td>
                                    <label>Copiar </label>
                                    <input type="checkbox" id="anterior_session_week" onchange="load_prev_session_value_block('."'".$prev_ses."'".','."'".$id_input."'".','."'".$row."'".','."'".$type."'".'); return false;" '.$checked.'/>';
/*
            if ($type != 3) {
            	$prev_ses_content .= ' 
            		<label> Completar </label>
                    <input type="checkbox" id="anterior_session_week_all_column" onchange="load_prev_session_value_all_column('."'".$prev_ses."'".','."'".$id_input."'".','."'".$row."'".','."'".$type."'".'); return false;" />';
            }
*/
            $prev_ses_content .= '<label>'.$prev_ses.'</label>
                                </td>';
            if ($checked == 'checked') { $class_button = 'btn-primary'; }
        }

#########
        $s_t = $session_training;
        $aux_session = explode('(', $session_training);
        if (count($aux_session) > 1) {
            if ($aux_session[0] != 'BR.' && $aux_session[0] != 'KW.') {
                $aux_session[1] = str_replace('.', 'ppuunnttoo', $aux_session[1]);
                $session_training = $aux_session[0].'('.$aux_session[1];
            }else{
                if (count($aux_session) > 2) {
                    $aux_session[2] = str_replace('.', 'ppuunnttoo', $aux_session[2]);
                    $session_training = $aux_session[0].'('.$aux_session[1].' ('.$aux_session[2];
                }
            }
        }
        $session_parts = explode('.', $session_training);
        $selected = $checked = $checked_try = $disss = '';
        if($session_parts[0] == 'Prueba de la T' || $session_parts[0] == 'Prueba CSS' || $session_parts[0] == 'FTP' || $session_parts[0] == 'Vdot' || $session_parts[0] == 'PruebaMilla' || $session_parts[0] == 'Prueba3k' || $session_parts[0] == 'Prueba5k' || $session_parts[0] == 'KW'){ $checked_try = 'checked'; }
        if($session_parts[0] == 'F' || $session_parts[0] == 'XT' || $session_parts[0] == 'off'){ $disss = 'disabled'; }
        $table = '';
        $table .= ' <div id="load_table'.$type.'">
                    	<table class="popup_table"><tr>'.$prev_ses_content.'</tr>';
        $table .= '     	<tr><th><span id="session_t'.$type.'">'.$s_t.'</span></th></tr>';
        $table .= ' <!--COMIENZA NUEVO CON RADIOS-->
                            <tr><td><span>Disciplina </span><span style="color:red;">* </span><span>:</span></td></tr>
                            <tr><td><center>';
        ($session_parts[0] == 'S' || $session_parts[0] == 'Prueba de la T' || $session_parts[0] == 'Prueba CSS') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
        ($session_parts[0] == 'S' || $session_parts[0] == 'Prueba de la T' || $session_parts[0] == 'Prueba CSS') ? $checked = 'checked' : $checked = '';
        $table .= '<label '.$selected.'>Swim</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="S" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
        ($session_parts[0] == 'B' || $session_parts[0] == 'FTP') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
        ($session_parts[0] == 'B' || $session_parts[0] == 'FTP') ? $checked = 'checked' : $checked = '';
        $table .= '<label '.$selected.'>Bike</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="B" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
        ($session_parts[0] == 'R' || $session_parts[0] == 'Vdot' || $session_parts[0] == 'PruebaMilla' || $session_parts[0] == 'Prueba3k' || $session_parts[0] == 'Prueba5k') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
        ($session_parts[0] == 'R' || $session_parts[0] == 'Vdot' || $session_parts[0] == 'PruebaMilla' || $session_parts[0] == 'Prueba3k' || $session_parts[0] == 'Prueba5k') ? $checked = 'checked' : $checked = '';
        $table .= '<label '.$selected.'>Run</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="R" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
        ($session_parts[0] == 'BR' || $session_parts[0] == 'KW') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
        ($session_parts[0] == 'BR' || $session_parts[0] == 'KW') ? $checked = 'checked' : $checked = '';
        $table .= '<label '.$selected.'>Brick</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="BR" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/><br>';
        ($session_parts[0] == 'F') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
        ($session_parts[0] == 'F') ? $checked = 'checked' : $checked = '';
        $table .= '<label '.$selected.'>Fuerza</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="F" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
        ($session_parts[0] == 'XT') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
        ($session_parts[0] == 'XT') ? $checked = 'checked' : $checked = '';
        $table .= '<label '.$selected.'>Xtrain</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="XT" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
        ($session_parts[0] == 'off') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
        ($session_parts[0] == 'off') ? $checked = 'checked' : $checked = '';
        $table .= '<label '.$selected.'>Descanso</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="off" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/><br><br>';
        $table .= ' <b>PRUEBA </b><input type="checkbox" id="checkbox_is_try_'.$type.'" onchange="load_discipline_parameters('.$type.');" '.$checked_try.' '.$disss.'/>
                            </center></td></tr>
        			<!--TERMINA NUEVO CON RADIOS-->
                            <tr><td><div id="is_try_'.$type.'">';
        if ($session_training != '') {
        	$discipline = $session_parts[0];
            $selected = '';
            $s_types = '';
            $zones = '';
            switch ($discipline) {
                case 'S':
                    preg_match('#\((.*?)\)#', $session_parts[1], $match);
                    (isset($match[1])) ? $match[1]=$match[1] : $match[1]='';
                    $session_parts[1] = explode('(', $session_parts[1]);
                    $s_types = '<br>';
                    ($session_parts[1][0] == '1') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($session_parts[1][0] == '1') ? $checked = 'checked' : $checked = '';
                    $s_types .= '<label '.$style.'>S.1</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="1" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/> | ';
                    ($session_parts[1][0] == '2') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($session_parts[1][0] == '2') ? $checked = 'checked' : $checked = '';
                    $s_types .= '<label '.$style.'>S.2</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="2" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/> | ';
                    ($session_parts[1][0] == '3') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($session_parts[1][0] == '3') ? $checked = 'checked' : $checked = '';
                    $s_types .= '<label '.$style.'>S.3</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="3" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/> | ';
                    ($session_parts[1][0] == '4') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($session_parts[1][0] == '4') ? $checked = 'checked' : $checked = '';
                    $s_types .= '<label '.$style.'>S.4</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="4" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/> | ';
                    ($session_parts[1][0] == '5') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($session_parts[1][0] == 'S5') ? $checked = 'checked' : $checked = '';
                    $s_types .= '<label '.$style.'>S.5</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="5" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/> | ';
                    ($session_parts[1][0] == '6') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($session_parts[1][0] == '6') ? $checked = 'checked' : $checked = '';
                    $s_types .= '<label '.$style.'>S.6</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="6" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/> | ';
                    $s_types = substr($s_types, 0, -3);
                    $s_types .= '';
######----------
                    $zones = $style = '';
                    $zones = '<b>Zona:<span style="color:red;"> *</span></b><br>';
                    (!empty($session_parts[2])) ? $zs = trim($session_parts[2]) : $zs = "";
                    ($zs == 'F') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($zs == 'F') ? $checked = 'checked' : $checked = '';
                    $zones .= '<label '.$style.'>Fondo</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="F" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/> | ';
                    ($zs == 'R') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($zs == 'R') ? $checked = 'checked' : $checked = '';
                    $zones .= '<label '.$style.'>Recuperación</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="R" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/><br>';
                    ($zs == 'U') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($zs == 'U') ? $checked = 'checked' : $checked = '';
                    $zones .= '<label '.$style.'>Umbral</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="U" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/> | ';
                    ($zs == 'V') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                    ($zs == 'V') ? $checked = 'checked' : $checked = '';
                    $zones .= '<label '.$style.'>Velocidad</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="V" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$checked.'/>';
#                    $table .='<br><center>'.$zones.'</center> <br>&nbsp;';
######----------
                case 'Prueba de la T':
                    if ($discipline == 'Prueba de la T') { $match[1] = ''; }
                    ($discipline == 'Prueba de la T') ? $try = '1' : $try = '0';
                    ($discipline == 'Prueba de la T') ? $diss = 'disabled' : $diss = '';
                    ($discipline == 'Prueba de la T') ? $data_sesion = '1500' : $data_sesion = $session_parts[1][0];
                    ($discipline == 'Prueba de la T') ? $table .= 'Prueba de la T:<br>' : $table .= 'Parámetros de S:<br>';
                    $table .= '
                          	<center>
                                <select class="input_box" id="s'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); reset_chk_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$diss.'>
                                    <option value="">0</option>';
                    ($data_sesion == '500') ? $selected = 'selected' : $selected = '';
                    $table .= ' 	<option value="500" '.$selected.'>500</option>';
                    ($data_sesion == '1000') ? $selected = 'selected' : $selected = '';
                    $table .= ' 	<option value="1000" '.$selected.'>1000</option>';
                    ($data_sesion == '1500') ? $selected = 'selected' : $selected = '';
                    $table .= '		<option value="1500" '.$selected.'>1500</option>';
                    ($data_sesion == '2000') ? $selected = 'selected' : $selected = '';
                    $table .= '		<option value="2000" '.$selected.'>2000</option>';
                    ($data_sesion == '2500') ? $selected = 'selected' : $selected = '';
                    $table .= '		<option value="2500" '.$selected.'>2500</option>';
                    ($data_sesion == '3000') ? $selected = 'selected' : $selected = '';
                    $table .= '		<option value="3000" '.$selected.'>3000</option>';
                    ($data_sesion == '3500') ? $selected = 'selected' : $selected = '';
                    $table .= '		<option value="3500" '.$selected.'>3500</option>';
                    ($data_sesion == '4000') ? $selected = 'selected' : $selected = '';
                    $table .= '		<option value="4000" '.$selected.'>4000</option>';
                    ($data_sesion == '4500') ? $selected = 'selected' : $selected = '';
                    $match[1] = str_replace('ppuunnttoo', '.', $match[1]);
                    $table .= ' 	<option value="4500" '.$selected.'>4500</option>
                                </select> &nbsp;&nbsp;&nbsp;
                                <span id="m'.$type.'">mts</span> &nbsp;&nbsp;&nbsp;
                                <span id="s_t'.$type.'">60</span> &nbsp;&nbsp;&nbsp;
                                <span id="stss'.$type.'">42</span> sTSS
                                '.$s_types.'<br>'.$zones.' <br>&nbsp;
                                <br>Observación:
                                <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;" value="'.$match[1].'" '.$diss.'>
                            </center>';
                    break;
                case 'Prueba CSS':
                    //
                    if ($discipline == 'Prueba CSS') { $match[1] = ''; }
                    ($discipline == 'Prueba CSS') ? $try = '1' : $try = '0';
                    ($discipline == 'Prueba CSS') ? $diss = 'disabled' : $diss = '';
                    ($discipline == 'Prueba CSS') ? $data_sesion = '1500' : $data_sesion = $session_parts[1][0];
                    ($discipline == 'Prueba CSS') ? $table .= 'Prueba CSS:<br>' : $table .= 'Parámetros de S:<br>';
                    //$data_sesion = $session_parts[1];
                    $table .= '
                              <center>
                                    <select class="input_box" id="s'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); reset_chk_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$diss.'>
                                                                  <option value="">0</option>';
                                                                  ($data_sesion == '500') ? $selected = 'selected' : $selected = '';
                    $table .= ' <option value="500" '.$selected.'>500</option>';
                                ($data_sesion == '1000') ? $selected = 'selected' : $selected = '';
                    $table .= ' <option value="1000" '.$selected.'>1000</option>';
                                ($data_sesion == '1500') ? $selected = 'selected' : $selected = '';
                    $table .= ' <option value="1500" '.$selected.'>1500</option>';
                                ($data_sesion == '2000') ? $selected = 'selected' : $selected = '';
                    $table .= ' <option value="2000" '.$selected.'>2000</option>';
                                ($data_sesion == '2500') ? $selected = 'selected' : $selected = '';
                    $table .= ' <option value="2500" '.$selected.'>2500</option>';
                                ($data_sesion == '3000') ? $selected = 'selected' : $selected = '';
                    $table .= ' <option value="3000" '.$selected.'>3000</option>';
                                ($data_sesion == '3500') ? $selected = 'selected' : $selected = '';
                    $table .= ' <option value="3500" '.$selected.'>3500</option>';
                                ($data_sesion == '4000') ? $selected = 'selected' : $selected = '';
                    $table .= ' <option value="4000" '.$selected.'>4000</option>';
                                ($data_sesion == '4500') ? $selected = 'selected' : $selected = '';
                    $match[1] = str_replace('ppuunnttoo', '.', $match[1]);
                    $table .= ' <option value="4500" '.$selected.'>4500</option>
                          </select> &nbsp;&nbsp;&nbsp;
                          <span id="m'.$type.'">mts</span> &nbsp;&nbsp;&nbsp;
                          <span id="s_t'.$type.'">60</span> &nbsp;&nbsp;&nbsp;
                          <span id="stss'.$type.'">42</span> sTSS
                          '.$s_types.'
                          <br>Observación:
                          <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;" value="'.$match[1].'" '.$diss.'>
                                                      </center>';
                    break;
                case '':
					break;
                case 'B':
                case 'FTP':
                    $data_sesion = $session_parts[1];
                    if ($discipline == 'FTP') {
                        $try = '1';
                        $disb = 'disabled';
                        $tssb_t = 80;
                        $unitb = 'min';
                        $match[1]='';
                        $table .= 'FTPs:<br>';
                        $table .= '
                            <center>
                                <b><span>FTP: </span><span style="color:red;">*</span></b>
                                <select id="ftp_num'.$type.'" onchange="build_session_individual(1,'.$type.','."'B'".','.$try.');">
                                    <option value=""></option>';
                        ($data_sesion == '1') ? $selected = 'selected' : $selected = '';
                        $table .= ' <option value="1" '.$selected.'>FTP.1</option>';
                        ($data_sesion == '2') ? $selected = 'selected' : $selected = '';
                        $table .= ' <option value="2" '.$selected.'>FTP.2</option>
                                </select><br>';
                        $data_sesion = '60';
                        $zoneb = '';
                    }else{
                        $try = '0';
                        $disb = '';
                        (substr($data_sesion, -1) == 'k') ? $unitb = 'km' : $unitb = 'min';
                        $data_sesion = substr($data_sesion, 0, -1);
                        $table .= 'Parámetros de B:<br>';
                        $tssb_t = explode(',', session_calculate(','.$data_sesion.'-'.$unitb.','));
                        $tssb_t = $tssb_t[1];
                        preg_match('#\((.*?)\)#', $session_parts[2], $match);
                        $session_parts[2] = explode('(', $session_parts[2]);
                        (isset($match[1])) ? $match[1]=$match[1] : $match[1]='';
                        $zb = $session_parts[2][0];
                        $zoneb = $style = '';
                        $zoneb .= '<b>Zona:<span style="color:red;"> *</span></b><br>';
                        $zb = trim($zb);
                        ($zb == 'Z1') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'Z1') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>Z1</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z1" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                        ($zb == 'Z2') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'Z2') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>Z2</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z2" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                        ($zb == 'Z3') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'Z3') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>Z3</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z3" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                        ($zb == 'Z4') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'Z4') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>Z4</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z4" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                        ($zb == 'Z5') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'Z5') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>Z5</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z5" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/><br>';
                        ($zb == 'Fza') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'Fza') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>Fza</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Fza" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                        ($zb == 'PC') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'PC') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>PC</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="PC" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                        ($zb == 'SST') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'SST') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>SST</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="SST" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                        ($zb == 'AC') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'AC') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>AC</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="AC" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                        ($zb == 'NP') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($zb == 'NP') ? $checked = 'checked' : $checked = '';
                        $zoneb .= '<label '.$style.'>NP</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="NP" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                        $zoneb = substr($zoneb, 0, -3);
                        $zoneb .= '';
                    }
                    $table .= '<center>';
                    if ($unitb == 'min') {
                    	$table .= '<span id="sel_cant_b'.$type.'">
                                    <select class="input_box" id="b'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disb.'>
                                        <option value="">0</option>';
                        ($data_sesion == '10') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="10" '.$selected.'>10</option>';
                        ($data_sesion == '20') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="20" '.$selected.'>20</option>';
                        ($data_sesion == '30') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="30" '.$selected.'>30</option>';
                        ($data_sesion == '40') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="40" '.$selected.'>40</option>';
                        ($data_sesion == '50') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="50" '.$selected.'>50</option>';
                        ($data_sesion == '60') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="60" '.$selected.'>60</option>';
                        ($data_sesion == '70') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="70" '.$selected.'>70</option>';
                        ($data_sesion == '80') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="80" '.$selected.'>80</option>';
                        ($data_sesion == '90') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="90" '.$selected.'>90</option>';
                        ($data_sesion == '100') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="100" '.$selected.'>100</option>';
                        ($data_sesion == '110') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="110" '.$selected.'>110</option>';
                        ($data_sesion == '120') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="120" '.$selected.'>120</option>';
                        ($data_sesion == '130') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="130" '.$selected.'>130</option>';
                        ($data_sesion == '140') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="140" '.$selected.'>140</option>';
                        ($data_sesion == '150') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="150" '.$selected.'>150</option>';
                        ($data_sesion == '160') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="160" '.$selected.'>160</option>';
                        ($data_sesion == '170') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="170" '.$selected.'>170</option>';
                        ($data_sesion == '180') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="180" '.$selected.'>180</option>';
                        ($data_sesion == '190') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="190" '.$selected.'>190</option>';
                        ($data_sesion == '200') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="200" '.$selected.'>200</option>';
                        ($data_sesion == '210') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="210" '.$selected.'>210</option>';
                        ($data_sesion == '220') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="220" '.$selected.'>220</option>';
                        ($data_sesion == '230') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="230" '.$selected.'>230</option>';
                        ($data_sesion == '240') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="240" '.$selected.'>240</option>';
                        ($data_sesion == '250') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="250" '.$selected.'>250</option>';
                        ($data_sesion == '260') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="260" '.$selected.'>260</option>';
                        ($data_sesion == '270') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="270" '.$selected.'>270</option>';
                        ($data_sesion == '280') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="280" '.$selected.'>280</option>';
                        ($data_sesion == '290') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="290" '.$selected.'>290</option>';
                        ($data_sesion == '300') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="300" '.$selected.'>300</option>';
                        ($data_sesion == '310') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="310" '.$selected.'>310</option>';
                        ($data_sesion == '320') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="320" '.$selected.'>320</option>';
                        ($data_sesion == '330') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="330" '.$selected.'>330</option>';
                        ($data_sesion == '340') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="340" '.$selected.'>340</option>';
                        ($data_sesion == '350') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="350" '.$selected.'>350</option>';
                        ($data_sesion == '360') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="360" '.$selected.'>360</option>';
                        ($data_sesion == '370') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="370" '.$selected.'>370</option>';
                        ($data_sesion == '380') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="380" '.$selected.'>380</option>';
                        ($data_sesion == '390') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="390" '.$selected.'>390</option>';
                        ($data_sesion == '400') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="400" '.$selected.'>400</option>
                                    </select>
                                </span>&nbsp;&nbsp;&nbsp;';
                    }else{
                    	$table .= '<span id="sel_cant_b'.$type.'">
                                    <select class="input_box" id="b'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disb.'>
                                        <option value="">0</option>';
          				($data_sesion == '5') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="5" '.$selected.'>5</option>';
          				($data_sesion == '10') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="10" '.$selected.'>10</option>';
          				($data_sesion == '15') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="15" '.$selected.'>15</option>';
          				($data_sesion == '20') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="20" '.$selected.'>20</option>';
          				($data_sesion == '25') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="25" '.$selected.'>25</option>';
          				($data_sesion == '30') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="30" '.$selected.'>30</option>';
          				($data_sesion == '35') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="35" '.$selected.'>35</option>';
          				($data_sesion == '40') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="40" '.$selected.'>40</option>';
          				($data_sesion == '45') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="45" '.$selected.'>45</option>';
          				($data_sesion == '50') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="50" '.$selected.'>50</option>';
          				($data_sesion == '55') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="55" '.$selected.'>55</option>';
          				($data_sesion == '60') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="60" '.$selected.'>60</option>';
          				($data_sesion == '65') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="65" '.$selected.'>65</option>';
          				($data_sesion == '70') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="70" '.$selected.'>70</option>';
          				($data_sesion == '75') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="75" '.$selected.'>75</option>';
          				($data_sesion == '80') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="80" '.$selected.'>80</option>';
          				($data_sesion == '85') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="85" '.$selected.'>85</option>';
          				($data_sesion == '90') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="90" '.$selected.'>90</option>';
          				($data_sesion == '95') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="95" '.$selected.'>95</option>';
          				($data_sesion == '100') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="100" '.$selected.'>100</option>';
          				($data_sesion == '105') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="105" '.$selected.'>105</option>';
          				($data_sesion == '110') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="110" '.$selected.'>110</option>';
          				($data_sesion == '115') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="115" '.$selected.'>115</option>';
          				($data_sesion == '120') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="120" '.$selected.'>120</option>';
          				($data_sesion == '125') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="125" '.$selected.'>125</option>';
          				($data_sesion == '130') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="130" '.$selected.'>130</option>';
          				($data_sesion == '135') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="135" '.$selected.'>135</option>';
          				($data_sesion == '140') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="140" '.$selected.'>140</option>';
          				($data_sesion == '145') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="145" '.$selected.'>145</option>';
          				($data_sesion == '150') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="150" '.$selected.'>150</option>';
                        ($data_sesion == '155') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="155" '.$selected.'>155</option>';
                        ($data_sesion == '160') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="160" '.$selected.'>160</option>';
                        ($data_sesion == '165') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="165" '.$selected.'>165</option>';
                        ($data_sesion == '170') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="170" '.$selected.'>170</option>';
                        ($data_sesion == '175') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="175" '.$selected.'>175</option>';
                        ($data_sesion == '180') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="180" '.$selected.'>180</option>';
                        ($data_sesion == '185') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="185" '.$selected.'>185</option>';
                        ($data_sesion == '190') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="190" '.$selected.'>190</option>';
                        ($data_sesion == '195') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="195" '.$selected.'>195</option>';
                        ($data_sesion == '200') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="200" '.$selected.'>200</option>';
                        ($data_sesion == '205') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="205" '.$selected.'>205</option>';
                        ($data_sesion == '210') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="210" '.$selected.'>210</option>';
                        ($data_sesion == '215') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="215" '.$selected.'>215</option>';
                        ($data_sesion == '220') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="220" '.$selected.'>220</option>';
                        ($data_sesion == '225') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="225" '.$selected.'>225</option>';
                        ($data_sesion == '230') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="230" '.$selected.'>230</option>';
                        ($data_sesion == '235') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="235" '.$selected.'>235</option>';
                        ($data_sesion == '240') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="240" '.$selected.'>240</option>';
                        ($data_sesion == '245') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="245" '.$selected.'>245</option>';
                        ($data_sesion == '250') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="250" '.$selected.'>250</option>
                                    </select>
                                </span>&nbsp;&nbsp;&nbsp;';
                    }
                    $table .= '<span id="mb'.$type.'">
                                <!--NUEVAS UNIDADES CON RADIO modificar tambien las funciones que llama onclick-->';
                    ($unitb == 'min') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                    ($unitb == 'min') ? $checked = 'checked' : $checked = '';
                    $table .= '	<label '.$selected.'>min</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'b'".');" '.$disb.' '.$checked.'/> &nbsp;|&nbsp;';
                    ($unitb == 'km') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                    ($unitb == 'km') ? $checked = 'checked' : $checked = '';
                    $table .= ' <label '.$selected.'> km</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'b'".');" '.$disb.' '.$checked.'/>
                                <!---->';
                    $table .= ' </span> &nbsp;&nbsp;&nbsp;';
                    $table .= '<span id="b_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
                               <span id="tss'.$type.'">'.$tssb_t.'</span>&nbsp;&nbsp; TSS<br>';
                    $match[1] = str_replace('ppuunnttoo', '.', $match[1]);
                    $table .= $zoneb.'
                                <br>Observación:
                                <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;" value="'.$match[1].'" '.$disb.'>
                            </center>';
                    break;
                case 'R':
                    $checked_r = '';
                case 'Vdot':
                case 'PruebaMilla':
                case 'Prueba3k':
                case 'Prueba5k':
                	if ($discipline != 'R') {
                        $try = '1';
                        $disr = 'disabled';
                        $unitr = 'min';
                        $match[1]='';
                        $zoner = '';
                        $checked_r = 'checked';
                        $tssr_t = $tssr_t = '';
                        if ($discipline == 'Vdot') {
                            $tssr_t = 75; $data_sesion = '50';
                        }elseif ($discipline == 'PruebaMilla') {
                            $tssr_t = 30; $data_sesion = '20';
                        }elseif ($discipline == 'Prueba3k') {
                            $tssr_t = 40; $data_sesion = '30';
                        }elseif ($discipline == 'Prueba5k') {
                            $tssr_t = 75; $data_sesion = '50';
                        }
						$table .= 'Prueba:<br>';///TRYRR
                        ($discipline == 'Vdot') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($discipline == 'Vdot') ? $checked = 'checked' : $checked = '';
                        $table .= '<center>';
                        $table .= ' <label '.$style.'>Vdot</label>
                                    <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="vdot" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');" '.$checked.'/> |';
                        ($discipline == 'PruebaMilla') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($discipline == 'PruebaMilla') ? $checked = 'checked' : $checked = '';
                        $table .= ' <label '.$style.'>Milla</label>
                                    <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="milla" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');" '.$checked.'/> |';
                        ($discipline == 'Prueba3k') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($discipline == 'Prueba3k') ? $checked = 'checked' : $checked = '';
                        $table .= ' <label '.$style.'>3k</label>
                                    <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="3k" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');" '.$checked.'/> |';
                        ($discipline == 'Prueba5k') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                        ($discipline == 'Prueba5k') ? $checked = 'checked' : $checked = '';
                        $table .= ' <label '.$style.'>5k</label>
                                    <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="5k" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');" '.$checked.'/>';
                        $table .= '</center>';
                    }else{
                    	$data_sesion = $session_parts[1];
                        $try = '0';
                        $disr = '';
                        (substr($data_sesion, -1) == 'k') ? $unitr = 'km' : $unitr = 'min';
                        $data_sesion = substr($data_sesion, 0, -1);
                        $table .= 'Parámetros de R:<br>';
                        $tssr_t = explode(',', session_calculate(',,'.$data_sesion.'-'.$unitr));
                        $tssr_t = $tssr_t[2];
                        preg_match('#\((.*?)\)#', $session_parts[2], $match);
                        $session_parts[2] = explode('(', $session_parts[2]);
                        (isset($match[1])) ? $match[1]=$match[1] : $match[1]='';
                        $zr = $session_parts[2][0];
                        $zoner = $style = '';
                        $all_zones = select_gral('`Zones`','name_z','name_z NOT IN("Z1+","Z1-","Z2+","Z2-","Z3+","Z3-","Z4+","Z4-")','id_z','name_z');
                        if ($all_zones == true) {
                        	$zoner .= '<b>Zona:<span style="color:red;"> *</span></b><br>';
                            foreach ($all_zones as $key) {
                                $zr = trim($zr);
                                ($zr == $key[0]) ? $style = 'style="color:#0DA5AB;"' : $style = '';
                                ($zr == $key[0]) ? $checked = 'checked' : $checked = '';
                                $zoner .= '<label '.$style.'>'.$key[0].'</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="'.$key[0].'" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
                            }
                        }
                        $zoner = substr($zoner, 0, -3);
                        $zoner .= '';
                    }
                    $table .= '<center>';
                    if ($unitr == 'min') {
                    	$table .= '
                    		<span id="sel_cant_r'.$type.'">
                                <select class="input_box" id="r'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disr.'>
                                    <option value="">0</option>';
                        ($data_sesion == '10') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="10" '.$selected.'>10</option>';
                        ($data_sesion == '20') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="20" '.$selected.'>20</option>';
                        ($data_sesion == '30') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="30" '.$selected.'>30</option>';
                        ($data_sesion == '40') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="40" '.$selected.'>40</option>';
                        ($data_sesion == '50') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="50" '.$selected.'>50</option>';
                        ($data_sesion == '60') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="60" '.$selected.'>60</option>';
                        ($data_sesion == '70') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="70" '.$selected.'>70</option>';
                        ($data_sesion == '80') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="80" '.$selected.'>80</option>';
                        ($data_sesion == '90') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="90" '.$selected.'>90</option>';
                        ($data_sesion == '100') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="100" '.$selected.'>100</option>';
                        ($data_sesion == '110') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="110" '.$selected.'>110</option>';
                        ($data_sesion == '120') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="120" '.$selected.'>120</option>';
                        ($data_sesion == '130') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="130" '.$selected.'>130</option>';
                        ($data_sesion == '140') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="140" '.$selected.'>140</option>';
                        ($data_sesion == '150') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="150" '.$selected.'>150</option>';
                        ($data_sesion == '160') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="160" '.$selected.'>160</option>';
                        ($data_sesion == '170') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="170" '.$selected.'>170</option>';
                        ($data_sesion == '180') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="180" '.$selected.'>180</option>';
                        ($data_sesion == '190') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="190" '.$selected.'>190</option>';
                        ($data_sesion == '200') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="200" '.$selected.'>200</option>
                                </select>
                            </span> &nbsp;&nbsp;&nbsp;';
                    }else{
                        $table .= '
                        	<span id="sel_cant_r'.$type.'">
                                <select class="input_box" id="r'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disr.'>
                                    <option value="">0</option>';
                        ($data_sesion == '1') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="1" '.$selected.'>1</option>';
                        ($data_sesion == '2') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="2" '.$selected.'>2</option>';
                        ($data_sesion == '3') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="3" '.$selected.'>3</option>';
                        ($data_sesion == '4') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="4" '.$selected.'>4</option>';
                        ($data_sesion == '5') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="5" '.$selected.'>5</option>';
                        ($data_sesion == '6') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="6" '.$selected.'>6</option>';
                        ($data_sesion == '7') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="7" '.$selected.'>7</option>';
                        ($data_sesion == '8') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="8" '.$selected.'>8</option>';
                        ($data_sesion == '9') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="9" '.$selected.'>9</option>';
                        ($data_sesion == '10') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="10" '.$selected.'>10</option>';
                        ($data_sesion == '11') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="11" '.$selected.'>11</option>';
                        ($data_sesion == '12') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="12" '.$selected.'>12</option>';
                        ($data_sesion == '13') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="13" '.$selected.'>13</option>';
                        ($data_sesion == '14') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="14" '.$selected.'>14</option>';
                        ($data_sesion == '15') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="15" '.$selected.'>15</option>';
                        ($data_sesion == '16') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="16" '.$selected.'>16</option>';
                        ($data_sesion == '17') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="17" '.$selected.'>17</option>';
                        ($data_sesion == '18') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="18" '.$selected.'>18</option>';
                        ($data_sesion == '19') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="19" '.$selected.'>19</option>';
                        ($data_sesion == '20') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="20" '.$selected.'>20</option>';
                        ($data_sesion == '21') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="21" '.$selected.'>21</option>';
                        ($data_sesion == '22') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="22" '.$selected.'>22</option>';
                        ($data_sesion == '23') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="23" '.$selected.'>23</option>';
                        ($data_sesion == '24') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="24" '.$selected.'>24</option>';
                        ($data_sesion == '25') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="25" '.$selected.'>25</option>';
                        ($data_sesion == '26') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="26" '.$selected.'>26</option>';
                        ($data_sesion == '27') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="27" '.$selected.'>27</option>';
                        ($data_sesion == '28') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="28" '.$selected.'>28</option>';
                        ($data_sesion == '29') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="29" '.$selected.'>29</option>';
                        ($data_sesion == '30') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="30" '.$selected.'>30</option>';
                        ($data_sesion == '31') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="31" '.$selected.'>31</option>';
                        ($data_sesion == '32') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="32" '.$selected.'>32</option>';
                        ($data_sesion == '33') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="33" '.$selected.'>33</option>';
                        ($data_sesion == '34') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="34" '.$selected.'>34</option>';
                        ($data_sesion == '35') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="35" '.$selected.'>35</option>';
                        ($data_sesion == '36') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="36" '.$selected.'>36</option>';
                        ($data_sesion == '37') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="37" '.$selected.'>37</option>';
                        ($data_sesion == '38') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="38" '.$selected.'>38</option>';
                        ($data_sesion == '39') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="39" '.$selected.'>39</option>';
                        ($data_sesion == '40') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="40" '.$selected.'>40</option>';
                        ($data_sesion == '41') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="41" '.$selected.'>41</option>';
                        ($data_sesion == '42') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="42" '.$selected.'>42</option>';
                        ($data_sesion == '43') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="43" '.$selected.'>43</option>';
                        ($data_sesion == '44') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="44" '.$selected.'>44</option>';
                        ($data_sesion == '45') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="45" '.$selected.'>45</option>';
                        ($data_sesion == '46') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="46" '.$selected.'>46</option>';
                        ($data_sesion == '47') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="47" '.$selected.'>47</option>';
                        ($data_sesion == '48') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="48" '.$selected.'>48</option>';
                        ($data_sesion == '49') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="49" '.$selected.'>49</option>';
                        ($data_sesion == '50') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="50" '.$selected.'>50</option>
                                </select>
                            </span> &nbsp;&nbsp;&nbsp;';
                    }
                    $table .= '<span id="mb'.$type.'">
                                <!--NUEVAS UNIDADES CON RADIO modificar tambien las funciones que llama onclick-->';
                    ($unitr == 'min') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                    ($unitr == 'min') ? $checked = 'checked' : $checked = '';
                    $table .= ' <label '.$selected.'>min</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disr."'".','."'r'".');" '.$disr.' '.$checked.' '.$checked_r.'/> &nbsp;|&nbsp;';
                    ($unitr == 'km') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                    ($unitr == 'km') ? $checked = 'checked' : $checked = '';
                    $table .= ' <label '.$selected.'> km</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disr."'".','."'r'".');" '.$disr.' '.$checked.'/>
                                <!---->';
                    $table .= ' </span> &nbsp;&nbsp;&nbsp;';
                    $table .= '<span id="r_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
                               <span id="rtss'.$type.'">'.$tssr_t.'</span>&nbsp; rTSS<br>';
                    $match[1] = str_replace('ppuunnttoo', '.', $match[1]);
                    $table .= $zoner.'
                                <br>Observación:
                                <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;" value="'.$match[1].'" '.$disr.'>
                            </center>';
                    break;
                case 'BR':
                case 'KW':
                    $data_sesion = $session_parts[1];
                    if ($discipline == 'BR') { $try = '0'; }else{ $try = '1'; }
                    (substr($data_sesion, -1) == 'k') ? $unitb = $unitr = 'km' : $unitb = $unitr = 'min';
                    preg_match('#\((.*?)\)#', $session_parts[2], $match);
                    $session_parts[2] = explode('(', $session_parts[2]);
                    (isset($match[1])) ? $match[1]=$match[1] : $match[1]='';
                    $zbr = $session_parts[2][0];
                    $data_sesion = substr($data_sesion, 0, -2);
                    $data_sesion = substr($data_sesion, 1);
                    $data_sesion = explode('+', $data_sesion);
                    $distance_b = $data_sesion[0];
                    $distance_r = $data_sesion[1];
                    $tsss = explode(',', session_calculate(','.$distance_b.'-'.$unitb.','.$distance_r.'-'.$unitr));
                    $tssb_t = $tsss[1];
                    $tssr_t = $tsss[2];
                    $table .= 'Parámetros de '.$discipline.':<br>';
                    $table .= '
                            <center>&nbsp;B:';
                    $table .= ' <span id="sel_cant_b'.$type.'">';
                    if ($unitb == 'min') {
                    	$table .= '<select class="input_box" id="b'.$type.'" onchange="change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');">
                                    <option value="">0</option>';
                    	($distance_b == '10') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="10" '.$selected.'>10</option>';
                    	($distance_b == '20') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="20" '.$selected.'>20</option>';
                    	($distance_b == '30') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="30" '.$selected.'>30</option>';
                    	($distance_b == '40') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="40" '.$selected.'>40</option>';
                    	($distance_b == '50') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="50" '.$selected.'>50</option>';
                    	($distance_b == '60') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="60" '.$selected.'>60</option>';
                    	($distance_b == '70') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="70" '.$selected.'>70</option>';
                    	($distance_b == '80') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="80" '.$selected.'>80</option>';
                    	($distance_b == '90') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="90" '.$selected.'>90</option>';
                    	($distance_b == '100') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="100" '.$selected.'>100</option>';
                    	($distance_b == '110') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="110" '.$selected.'>110</option>';
                    	($distance_b == '120') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="120" '.$selected.'>120</option>';
                    	($distance_b == '130') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="130" '.$selected.'>130</option>';
                    	($distance_b == '140') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="140" '.$selected.'>140</option>';
                    	($distance_b == '150') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="150" '.$selected.'>150</option>';
                    	($distance_b == '160') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="160" '.$selected.'>160</option>';
                    	($distance_b == '170') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="170" '.$selected.'>170</option>';
                    	($distance_b == '180') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="180" '.$selected.'>180</option>';
                    	($distance_b == '190') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="190" '.$selected.'>190</option>';
                    	($distance_b == '200') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="200" '.$selected.'>200</option>';
                    	($distance_b == '210') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="210" '.$selected.'>210</option>';
                    	($distance_b == '220') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="220" '.$selected.'>220</option>';
                    	($distance_b == '230') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="230" '.$selected.'>230</option>';
                    	($distance_b == '240') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="240" '.$selected.'>240</option>';
                    	($distance_b == '250') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="250" '.$selected.'>250</option>';
                    	($distance_b == '260') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="260" '.$selected.'>260</option>';
                    	($distance_b == '270') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="270" '.$selected.'>270</option>';
                    	($distance_b == '280') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="280" '.$selected.'>280</option>';
                    	($distance_b == '290') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="290" '.$selected.'>290</option>';
                    	($distance_b == '300') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="300" '.$selected.'>300</option>';
                    	($distance_b == '310') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="310" '.$selected.'>310</option>';
                    	($distance_b == '320') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="320" '.$selected.'>320</option>';
                    	($distance_b == '330') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="330" '.$selected.'>330</option>';
                    	($distance_b == '340') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="340" '.$selected.'>340</option>';
                    	($distance_b == '350') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="350" '.$selected.'>350</option>';
                    	($distance_b == '360') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="360" '.$selected.'>360</option>';
                    	($distance_b == '370') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="370" '.$selected.'>370</option>';
                    	($distance_b == '380') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="380" '.$selected.'>380</option>';
                    	($distance_b == '390') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="390" '.$selected.'>390</option>';
                    	($distance_b == '400') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="400" '.$selected.'>400</option>
                                    </select>';
                	}else{
                        $table .= '<select class="input_box" id="b'.$type.'" onchange="change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');">
                                    <option value="">0</option>';
                        ($distance_b == '5') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="5" '.$selected.'>5</option>';
                        ($distance_b == '10') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="10" '.$selected.'>10</option>';
                        ($distance_b == '15') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="15" '.$selected.'>15</option>';
                        ($distance_b == '20') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="20" '.$selected.'>20</option>';
                        ($distance_b == '25') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="25" '.$selected.'>25</option>';
                        ($distance_b == '30') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="30" '.$selected.'>30</option>';
                        ($distance_b == '35') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="35" '.$selected.'>35</option>';
                        ($distance_b == '40') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="40" '.$selected.'>40</option>';
                        ($distance_b == '45') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="45" '.$selected.'>45</option>';
                        ($distance_b == '50') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="50" '.$selected.'>50</option>';
                        ($distance_b == '55') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="55" '.$selected.'>55</option>';
                        ($distance_b == '60') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="60" '.$selected.'>60</option>';
                        ($distance_b == '65') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="65" '.$selected.'>65</option>';
                        ($distance_b == '70') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="70" '.$selected.'>70</option>';
                        ($distance_b == '75') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="75" '.$selected.'>75</option>';
                        ($distance_b == '80') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="80" '.$selected.'>80</option>';
                        ($distance_b == '85') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="85" '.$selected.'>85</option>';
                        ($distance_b == '90') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="90" '.$selected.'>90</option>';
                        ($distance_b == '95') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="95" '.$selected.'>95</option>';
                        ($distance_b == '100') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="100" '.$selected.'>100</option>';
                        ($distance_b == '105') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="105" '.$selected.'>105</option>';
                        ($distance_b == '110') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="110" '.$selected.'>110</option>';
                        ($distance_b == '115') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="115" '.$selected.'>115</option>';
                        ($distance_b == '120') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="120" '.$selected.'>120</option>';
                        ($distance_b == '125') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="125" '.$selected.'>125</option>';
                        ($distance_b == '130') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="130" '.$selected.'>130</option>';
                        ($distance_b == '135') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="135" '.$selected.'>135</option>';
                        ($distance_b == '140') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="140" '.$selected.'>140</option>';
                        ($distance_b == '145') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="145" '.$selected.'>145</option>';
                        ($distance_b == '150') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="150" '.$selected.'>150</option>';
                        ($distance_b == '155') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="155" '.$selected.'>155</option>';
                        ($distance_b == '160') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="160" '.$selected.'>160</option>';
                        ($distance_b == '165') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="165" '.$selected.'>165</option>';
                        ($distance_b == '170') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="170" '.$selected.'>170</option>';
                        ($distance_b == '175') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="175" '.$selected.'>175</option>';
                        ($distance_b == '180') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="180" '.$selected.'>180</option>';
                        ($distance_b == '185') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="185" '.$selected.'>185</option>';
                        ($distance_b == '190') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="190" '.$selected.'>190</option>';
                        ($distance_b == '195') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="195" '.$selected.'>195</option>';
                        ($distance_b == '200') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="200" '.$selected.'>200</option>';
                        ($distance_b == '205') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="205" '.$selected.'>205</option>';
                        ($distance_b == '210') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="210" '.$selected.'>210</option>';
                        ($distance_b == '215') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="215" '.$selected.'>215</option>';
                        ($distance_b == '220') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="220" '.$selected.'>220</option>';
                        ($distance_b == '225') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="225" '.$selected.'>225</option>';
                        ($distance_b == '230') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="230" '.$selected.'>230</option>';
                        ($distance_b == '235') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="235" '.$selected.'>235</option>';
                        ($distance_b == '240') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="240" '.$selected.'>240</option>';
                        ($distance_b == '245') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="245" '.$selected.'>245</option>';
                        ($distance_b == '250') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="250" '.$selected.'>250</option>
                                </select>';
                    }
	                $table .= '  </span> &nbsp;&nbsp;&nbsp;';
	                $table .= ' <span id="mb'.$type.'">';
	                $table .= '<!--NUEVAS UNIDADES CON RADIO modificar tambien las funciones que llama onclick-->';
	                ($unitb == 'min') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
	                ($unitb == 'min') ? $checked = 'checked' : $checked = '';
	                $table .= ' <label '.$selected.'>min</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.'); modify_units_brick(this.value,'.$type.');" '.$checked.'/> |';
	                ($unitb == 'km') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
	                ($unitb == 'km') ? $checked = 'checked' : $checked = '';
	                $table .= ' <label '.$selected.'> km</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.'); modify_units_brick(this.value,'.$type.');" '.$checked.'/>';
					$table .= ' </span> &nbsp;&nbsp;&nbsp;';
	                $table .= ' <span id="b_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
	                            <span id="tss'.$type.'">'.$tssb_t.'</span>&nbsp; bTSS
	                            <br>R:';
	                $table .= ' <span id="sel_cant_r'.$type.'">';
	                if ($unitr == 'min') {
	                	$table .= ' <select class="input_box" id="r'.$type.'" onchange="change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');">
	                                <option value="">0</option>';
	                  	($distance_r === '10') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="10" '.$selected.'>10</option>';
	                  	($distance_r === '20') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="20" '.$selected.'>20</option>';
	                  	($distance_r === '30') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="30" '.$selected.'>30</option>';
	                  	($distance_r === '40') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="40" '.$selected.'>40</option>';
	                  	($distance_r === '50') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="50" '.$selected.'>50</option>';
	                  	($distance_r === '60') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="60" '.$selected.'>60</option>';
	                  	($distance_r === '70') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="70" '.$selected.'>70</option>';
	                  	($distance_r === '80') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="80" '.$selected.'>80</option>';
	                  	($distance_r === '90') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="90" '.$selected.'>90</option>';
	                  	($distance_r === '100') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="100" '.$selected.'>100</option>';
	                  	($distance_r === '110') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="110" '.$selected.'>110</option>';
	                  	($distance_r === '120') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="120" '.$selected.'>120</option>';
	                  	($distance_r === '130') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="130" '.$selected.'>130</option>';
	                  	($distance_r === '140') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="140" '.$selected.'>140</option>';
	                  	($distance_r === '150') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="150" '.$selected.'>150</option>';
	                  	($distance_r === '160') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="160" '.$selected.'>160</option>';
	                  	($distance_r === '170') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="170" '.$selected.'>170</option>';
	                  	($distance_r === '180') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="180" '.$selected.'>180</option>';
	                  	($distance_r === '190') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="190" '.$selected.'>190</option>';
	                  	($distance_r === '200') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="200" '.$selected.'>200</option>
	                                </select>';
	                }else{
	                    $table .= '
	                    	<span id="sel_cant_r'.$type.'">
	                            <select class="input_box" id="r'.$type.'" onchange="change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');" >
	                                <option value="">0</option>';
	                    ($distance_r == '1') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="1" '.$selected.'>1</option>';
	                    ($distance_r == '2') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="2" '.$selected.'>2</option>';
	                    ($distance_r == '3') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="3" '.$selected.'>3</option>';
	                    ($distance_r == '4') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="4" '.$selected.'>4</option>';
	                    ($distance_r == '5') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="5" '.$selected.'>5</option>';
	                    ($distance_r == '6') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="6" '.$selected.'>6</option>';
	                    ($distance_r == '7') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="7" '.$selected.'>7</option>';
	                    ($distance_r == '8') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="8" '.$selected.'>8</option>';
	                    ($distance_r == '9') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="9" '.$selected.'>9</option>';
	                    ($distance_r == '10') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="10" '.$selected.'>10</option>';
	                    ($distance_r == '11') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="11" '.$selected.'>11</option>';
	                    ($distance_r == '12') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="12" '.$selected.'>12</option>';
	                    ($distance_r == '13') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="13" '.$selected.'>13</option>';
	                    ($distance_r == '14') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="14" '.$selected.'>14</option>';
	                    ($distance_r == '15') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="15" '.$selected.'>15</option>';
	                    ($distance_r == '16') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="16" '.$selected.'>16</option>';
	                    ($distance_r == '17') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="17" '.$selected.'>17</option>';
	                    ($distance_r == '18') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="18" '.$selected.'>18</option>';
	                    ($distance_r == '19') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="19" '.$selected.'>19</option>';
	                    ($distance_r == '20') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="20" '.$selected.'>20</option>';
	                    ($distance_r == '21') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="21" '.$selected.'>21</option>';
	                    ($distance_r == '22') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="22" '.$selected.'>22</option>';
	                    ($distance_r == '23') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="23" '.$selected.'>23</option>';
	                    ($distance_r == '24') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="24" '.$selected.'>24</option>';
	                    ($distance_r == '25') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="25" '.$selected.'>25</option>';
	                    ($distance_r == '26') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="26" '.$selected.'>26</option>';
	                    ($distance_r == '27') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="27" '.$selected.'>27</option>';
	                    ($distance_r == '28') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="28" '.$selected.'>28</option>';
	                    ($distance_r == '29') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="29" '.$selected.'>29</option>';
	                    ($distance_r == '30') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="30" '.$selected.'>30</option>';
	                    ($distance_r == '31') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="31" '.$selected.'>31</option>';
	                    ($distance_r == '32') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="32" '.$selected.'>32</option>';
	                    ($distance_r == '33') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="33" '.$selected.'>33</option>';
	                    ($distance_r == '34') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="34" '.$selected.'>34</option>';
	                    ($distance_r == '35') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="35" '.$selected.'>35</option>';
	                    ($distance_r == '36') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="36" '.$selected.'>36</option>';
	                    ($distance_r == '37') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="37" '.$selected.'>37</option>';
	                    ($distance_r == '38') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="38" '.$selected.'>38</option>';
	                    ($distance_r == '39') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="39" '.$selected.'>39</option>';
	                    ($distance_r == '40') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="40" '.$selected.'>40</option>';
	                    ($distance_r == '41') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="41" '.$selected.'>41</option>';
	                    ($distance_r == '42') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="42" '.$selected.'>42</option>';
	                    ($distance_r == '43') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="43" '.$selected.'>43</option>';
	                    ($distance_r == '44') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="44" '.$selected.'>44</option>';
	                    ($distance_r == '45') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="45" '.$selected.'>45</option>';
	                    ($distance_r == '46') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="46" '.$selected.'>46</option>';
	                    ($distance_r == '47') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="47" '.$selected.'>47</option>';
	                    ($distance_r == '48') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="48" '.$selected.'>48</option>';
	                    ($distance_r == '49') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="49" '.$selected.'>49</option>';
	                    ($distance_r == '50') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="50" '.$selected.'>50</option>
	                            </select>
	                        </span> &nbsp;&nbsp;&nbsp;';
	                }
	                $table .= ' </span> &nbsp;&nbsp;&nbsp;';
	                $table .= ' <span id="mr'.$type.'">';
	                $table .= '<!--NUEVAS UNIDADES CON RADIO modificar tambien las funciones que llama onclick-->';
	                ($unitr == 'min') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
	                ($unitr == 'min') ? $checked = 'checked' : $checked = '';
	                $table .= ' <label '.$selected.'>min</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');" disabled '.$checked.'/> |';
	                ($unitr == 'km') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
	                ($unitr == 'km') ? $checked = 'checked' : $checked = '';
	                $table .= ' <label '.$selected.'> km</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');" disabled '.$checked.'/>';
					$table .= ' </span> &nbsp;&nbsp;&nbsp;
	                            <span id="r_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
	                            <span id="rtss'.$type.'">'.$tssr_t.'</span>&nbsp; rTSS<br>'.
	                $style = '';
	                $table .= '<b>Zona:<span style="color:red;"> *</span></b><br>';
	                $zbr = trim($zbr);
	                ($zbr == 'Z1') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'Z1') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>Z1</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z1" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
	                ($zbr == 'Z2') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'Z2') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>Z2</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z2" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
	                ($zbr == 'Z3') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'Z3') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>Z3</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z3" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
	                ($zbr == 'Z4') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'Z4') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>Z4</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z4" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
	                ($zbr == 'Z5') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'Z5') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>Z5</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z5" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/><br>';
	                ($zbr == 'Fza') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'Fza') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>Fza</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Fza" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
	                ($zbr == 'PC') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'PC') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>PC</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="PC" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
	                ($zbr == 'SST') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'SST') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>SST</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="SST" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
	                ($zbr == 'AC') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'AC') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>AC</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="AC" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
	                ($zbr == 'NP') ? $style = 'style="color:#0DA5AB;"' : $style = '';
	                ($zbr == 'NP') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$style.'>NP</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="NP" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$checked.'/> | ';
	                $table = substr($table, 0, -3);
	                $table .= '';
	                $match[1] = str_replace('ppuunnttoo', '.', $match[1]);
	                $table .= '
	                        <br>Observación:
	                        <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'BR'".','.$try.'); return false;" value="'.$match[1].'">
	                    </center>';
	                break;
	            case 'F':
	                $data_sesion = $session_parts[1];
	                $data_sesion = substr($data_sesion, 0, -1);
	                if ($session_parts[2] != 'Core' && $session_parts[2] != 'Pierna' && $session_parts[2] != 'Superior' && $session_parts[2] != 'General') {
	                    $op_id = $session_parts[2];
	                    $op_id = substr($op_id, 0, -1);
	                    $op_id = explode('(', $op_id);
	                    $option = $op_id[0];
	                    $identi = str_replace('ppuunnttoo', '.', $op_id[1]);
	                }else{
	                    $identi = '';
	                    $option = $session_parts[2];
	                }
	                $table .= 'Parámetros de F:<br>';
	                $table .= '
	                    <center>
	                        Opción: <span style="color:red;">* </span><br>';
	                ($option == 'Core') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
	                ($option == 'Core') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$selected.'>Core</label> <input id="force_option'.$type.'" type="checkbox" value="Core" name="force_option'.$type.'" '.$checked.' onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','."'0'".');"/> | ';
	                ($option == 'Pierna') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
	                ($option == 'Pierna') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$selected.'>Pierna</label> <input id="force_option'.$type.'" type="checkbox" value="Pierna" name="force_option'.$type.'" '.$checked.' onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','."'0'".');"/> | ';
	                ($option == 'Superior') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
	                ($option == 'Superior') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$selected.'>Superior</label> <input id="force_option'.$type.'" type="checkbox" value="Superior" name="force_option'.$type.'" '.$checked.' onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','."'0'".');"/> | ';
	                ($option == 'General') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
	                ($option == 'General') ? $checked = 'checked' : $checked = '';
	                $table .= '<label '.$selected.'>General</label> <input id="force_option'.$type.'" type="checkbox" value="General" name="force_option'.$type.'" '.$checked.' onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','."'0'".');"/>';
					$table .= ' <br>&nbsp;&nbsp;
	                            Duración: <span style="color:red;">* </span>
	                            <select id="force_time'.$type.'" style="width:50px;" onchange="build_session_individual(1,'.$type.','."'".$discipline."'".','."'0'".'); return false;">
	                                <option value=""></option>';
	                ($data_sesion == '10') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="10" '.$selected.'>10</option>';
	                ($data_sesion == '20') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="20" '.$selected.'>20</option>';
	                ($data_sesion == '30') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="30" '.$selected.'>30</option>';
	                ($data_sesion == '40') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="40" '.$selected.'>40</option>';
	                ($data_sesion == '50') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="50" '.$selected.'>50</option>';
	                ($data_sesion == '60') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="60" '.$selected.'>60</option>';
	                ($data_sesion == '70') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="70" '.$selected.'>70</option>';
	                ($data_sesion == '80') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="80" '.$selected.'>80</option>';
	                ($data_sesion == '90') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="90" '.$selected.'>90</option>';
	                ($data_sesion == '100') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="100" '.$selected.'>100</option>';
	                ($data_sesion == '110') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="110" '.$selected.'>110</option>';
	                ($data_sesion == '120') ? $selected = 'selected' : $selected = ''; $table .= ' <option value="120" '.$selected.'>120</option>
	                            </select> min<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                            Identificador: <span style="color:red;">* </span>
	                            <input type="text" id="force_id'.$type.'" maxlength="20"  value="'.$identi.'" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','."'0'".'); return false;"><br>
	                        </center>';
	                break;
	            case 'XT':
	                preg_match('#\((.*?)\)#', $session_parts[1], $match);
	                $session_parts[1] = explode('(', $session_parts[1]);
	                (isset($match[1])) ? $match[1]=$match[1] : $match[1]='';
	                $data_sesion = substr($session_parts[1][0], 0, -1);
	                $table .= 'Parámetros de XT:<br>';
	                $table .= '
	                      <center>
	                            Duración: 
	                            <select id="tx_time'.$type.'" style="width:50px;" onchange="build_session_individual(1,'.$type.','."'".$discipline."'".',0); return false;">
	                                <option value=""></option>';
	                ($data_sesion == '15') ? $selected = 'selected' : $selected =''; $table .= ' <option value="15" '.$selected.'>15</option>';
	                ($data_sesion == '30') ? $selected = 'selected' : $selected =''; $table .= ' <option value="30" '.$selected.'>30</option>';
	                ($data_sesion == '45') ? $selected = 'selected' : $selected =''; $table .= ' <option value="45" '.$selected.'>45</option>';
	                ($data_sesion == '60') ? $selected = 'selected' : $selected ='';
	                $match[1] = str_replace('ppuunnttoo', '.', $match[1]);
	                $table .= ' 	<option value="60" '.$selected.'>60</option>
	                            </select> min
	                            <br>Observación:
	                            <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".',0); return false;" value="'.$match[1].'">
	                      </center>';
	                break;
	            default:
	                # code...
	                break;
        	}
        }
        $table .= ' </div></td></tr>
                              	<tr><td><div id="errors_popup_'.$type.'"></div></td></tr>';
        $table .= ' </table>
                       </div>';
        $table .= ' <center>
                        <p class="stdformbutton">
                            <a href="#" class="btn '.$class_button.'" id="btn_box_'.$type.'" onclick="get_session_block_data('."'".$id_input."'".', '."'".$row."'".','.$type.'); return false;">Actualizar</a>
                            <a class="btn btn-default" type="reset" onclick="close_popup('.$type.'); return false;" >Cancelar</a>
                        </p>
                    </center>
                    <center>
                        <a class="manual_input" href="#" style="text-align: right;" onclick="load_manual_session_form('.$type.'); return false;">Editar Manual</a>';

        $table .= ' <span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span>

        			<span> &nbsp; &nbsp; &nbsp; &nbsp; </span>

                       <a class="manual_input" href="#" style="text-align: center;" onclick="clear_session_block('."'".$id_input."'".', '.$type.', '."'".$row."'".'); return false;"> Borrar Sesión</a>
                       <!--<span> &nbsp; &nbsp; &nbsp; &nbsp; </span>
                       <a class="manual_input" href="#" style="text-align: right;" onclick="clear_sessions_all_column('."'".$id_input."'".', '.$type.', '."'".$row."'".'); return false;"> Borrar Columna</a>-->';
        $table .= ' </center>';
        return $type.'|&|'.$table;
    }
    function save_session_block($id_block, $info_sess){#$id_event, $id_level, $notes_event, $info_sess
      	$res = 0;
      	# session_desc+'|-|'+cans+'|-|'+unis+'|-|'+tsss+'|-|'+canb+'|-|'+unib+'|-|'+tssb+'|-|'+canr+'|-|'+unir+'|-|'+tssr+'|-|'+week+'|-|'+session;
      	$insert_new_training = 0;
      	$info_sess = explode('|-|', $info_sess);
      	$session_des = $info_sess[0];
      	($info_sess[1] == '') ? $disc_s = '0' : $disc_s = trim($info_sess[1]);
      	($info_sess[2] == '') ? $unit_s = 'mts' : $unit_s = trim($info_sess[2]);
      	($info_sess[3] == '') ? $stss = '0' : $stss = trim($info_sess[3]);
      	($info_sess[4] == '') ? $disc_b = '0' : $disc_b = trim($info_sess[4]);
      	($info_sess[5] == '') ? $unit_b = 'min' : $unit_b = trim($info_sess[5]);
      	($info_sess[6] == '') ? $btss = '0' : $btss = trim($info_sess[6]);
      	($info_sess[7] == '') ? $disc_r = '0' : $disc_r = trim($info_sess[7]);
      	($info_sess[8] == '') ? $unit_r = 'min' : $unit_r = trim($info_sess[8]);
      	($info_sess[9] == '') ? $rtss = '0' : $rtss = trim($info_sess[9]);

      	$week = trim($info_sess[10]);
      	$session = trim($info_sess[11]);
        $day = trim($info_sess[12]);
      	$key_ssn = '';
      	if ($session_des == '') {# Session Vacia
            $delete_sessions = delete_gral('`Trainings_blocks`',
                                          "block='".$id_block."' AND week='".$week."' AND day='".$day."' AND session_num='".$session."'");
            if ($delete_sessions == 1) { $res = 4; }else{ $res = 5; }
      	}
      	if ($session_des != '') {# Session No Vacia
            $session_des = str_replace('mmaass', '+', $session_des);
            $id_ssn = select_gral('`Sessions`', 
                                   'id_ssn',
                                   "session_desc='".$session_des."' AND disc_s='".$disc_s."' AND unit_s='".$unit_s."' AND stss='".$stss."' AND disc_b='".$disc_b."' AND unit_b='".$unit_b."' AND btss='".$btss."' AND disc_r='".$disc_r."' AND unit_r='".$unit_r."' AND rtss='".$rtss."'",
                                         'id_ssn');// tabla, campos, filtros, orden
            if($id_ssn == true){ foreach ($id_ssn as $key) { $key_ssn = $key[0]; } }
            if ($key_ssn == '') {//La session no existe
                $insert_new_session = insert_gral('`Sessions`',
                                                      'session_desc, disc_s, unit_s, stss, disc_b, unit_b, btss, disc_r, unit_r, rtss',
                                                      '"'.$session_des.'","'.$disc_s.'","'.$unit_s.'","'.$stss.'","'.$disc_b.'","'.$unit_b.'","'.$btss.'","'.$disc_r.'","'.$unit_r.'","'.$rtss.'"');
                $key_ssn = $insert_new_session;
            }
            $key_trn = '';
            $id_trn = select_gral('`Trainings_blocks`',
                                  'id_tb',
                                  "block='".$id_block."' AND week='".$week."' AND day='".$day."' AND session_num='".$session."'",
                                  'id_tb');
            if ($id_trn == true) { foreach ($id_trn as $key) { $key_trn = $key[0]; } }
            $id_training = $key_trn;
            if ($key_trn != '') {
                $key_trn = $id_trn = '';
                $id_trn = select_gral('`Trainings_blocks`',
                                          'id_tb',
                                          "session='".$key_ssn."' AND block='".$id_block."' AND week='".$week."' AND day='".$day."' AND session_num='".$session."'",
                                          'id_tb');
                if ($id_trn == true) { foreach ($id_trn as $key) { $key_trn = $key[0]; } }
                if ($key_trn == '') {
                	$update_training = update_gral('`Trainings_blocks`', "session='".$key_ssn."'", "id_tb='".$id_training."'");
                    $res = 2;
                }else{
                    $res = 3;
                }
            }else{
                $insert_new_training = insert_gral('`Trainings_blocks`',
                                               'session, block, week, day, session_num',
                                               '"'.$key_ssn.'","'.$id_block.'","'.$week.'","'.$day.'","'.$session.'"');
                $res = 1;
            }
      	}
      	//LOG
      	$blck = $l_e = "";
      	$blck = select_gral('`Blocks`', 'name_b', 'id_b="'.$id_block.'"', 'id_b');
      	if ($blck == true) { foreach ($blck as $key) { $blck = $key[0]; } }else{ $blck = $id_block; }

      	if ($res == 1) {#Insertar
            # Activity_code = 14
            $message = "El usuario *".$_SESSION['name_u']."* insertó una nueva sesión en Block: *".$blck."* Semana: *".$week."* Día: *".$day."* Sesión: *".$session."*";
            log_mb_register($message, "14");
      	}
      	if ($res == 2) {#Actualizar
            # Activity_code = 15
            $message = "El usuario *".$_SESSION['name_u']."* actualizó la sesión del Block: *".$blck."* Semana: *".$week."* Día: *".$day."* Sesión: *".$session."*";
            log_mb_register($message, "15");
      	}
      	if ($res == 4) {#Eliminar
            # Activity_code = 16
            $message = "El usuario *".$_SESSION['name_u']."* eliminó la sesión del Block: *".$blck."* Semana: *".$week."* Día: *".$day."* Sesión: *".$session."*";
            log_mb_register($message, "16");
      	}
      	return "6@-@".$res."@-@";
	}
	#		 get_events_table_editable($filter, $option, $order_extra){// option 1 = Eventos, 2 = Template 
	function get_edit_list_blocks(){
        $title = $table = '';
        $title = '<h4 class="widgettitle">Bloques Disponibles</h4>';
        $table .= ' <table id="dyntable" class="table table-bordered responsive">
                        <thead>
                            <tr>
                            	<th></th>
                                <th class="head1" title="Nombre del Bloque"><img id="order_e_n" src="./img/images/up01.png" style="width:10px; position:inherit; cursor:pointer;" onclick="get_edit_list_blocks(); return false;"> Bloque</th>
                                <th class="head0" title="Disciplina del Bloque"> Disciplina</th>
                                <th class="head1" title="Semanas del Bloque"><img id="order_e_d" src="./img/images/up01.png" style="width:10px; position:inherit; cursor:pointer;" onclick="get_edit_list_blocks(); return false;"> Semanas</th>
                                <th class="head0" title="Semanas del Bloque"><img id="order_e_d" src="./img/images/up01.png" style="width:10px; position:inherit; cursor:pointer;" onclick="get_edit_list_blocks(); return false;"> Días</th>
                                <th class="head1" title="Sessiones por Columna"><img id="order_e_c" src="./img/images/up01.png" style="width:10px; position:inherit; cursor:pointer;" onclick="get_edit_list_blocks(); return false;"> Sesiones</th>
                                <th class="head0" title="Descripción del Bloque">Descripción</th>
                                <th class="head1" title="Eliminar Bloque"></th>
                            </tr>
                        </thead>
                        <tbody>';
        $values = select_gral('`Blocks`', 'id_b,name_b,weeks_b,sess_b,desc_b,discip_name,days_b', 'id_b IS NOT NULL','id_b');// tabla, campos, filtros, orden
        if($values == true){
        	$i = 1;
        	foreach ($values as $key) {
        		$table .= '	<tr class="grade'.utf8_encode($key[0]).'">
        						<td>'.$i.'</td>
                                <td><b><a href="#" onclick="get_blocks_form('.$key[0].'); return false;">'.utf8_encode($key[1]).'</a></b></td>';
                $table .= '     <td>'.utf8_encode($key[5]).'</td>';
                $table .= '     <td>'.utf8_encode($key[2]).'</td>';
                $table .= '     <td>'.utf8_encode($key[6]).'</td>';
                $table .= '     <td>'.str_replace("-###-", "-", utf8_encode($key[3])).'</td>';
                $table .= '     <td>'.utf8_encode($key[4]).'</td>';
                $table .= '     <td><center><img title="Eliminar este Block" src="./img/images/garbage.png" style="width:16px; cursor:pointer;" onclick="confirm_delete_block('.utf8_encode($key[0]).'); return false;"/></center></td>';
                $table .= ' </tr>';
                $i++;
            }
        }else{
        	$table .= ' <tr><td colspan="6"> No hay Bloques Disponibles...</td></tr>';
        }
        $table .= '     </tbody>
                    </table>';
        $load_div = '<div id="load_edit_form"></div>';
        $all_content = '<div id="dashboard-left" class="col-md-8">'.$title.$table.'</div>'.$load_div;
        return $all_content.'&&&.row_aux';
	}
	function update_block_info($id_b, $name_b, $desc_b, $weeks_b, $sess_b, $discipline_b, $days_b){
		$id_b 	 = intval($id_b);
		$weeks_b = intval($weeks_b);
#		$sess_b  = intval($sess_b);
        $sess_b  = trim($sess_b);
		$name_b  = trim($name_b);
		$desc_b  = trim($desc_b);
        $disc_b  = trim($discipline_b);
        $days_b  = intval($days_b);
		$block_update = "none";
		$res = 0;
		$name_db = $desc_db = $weeks_db = $sess_db = $days_db = "";
		$block_info = select_gral('`Blocks`', 'name_b,weeks_b,sess_b,desc_b,discip_name,days_b', 'id_b="'.$id_b.'"', 'id_b');
		if ($block_info == true) {
			foreach ($block_info as $key) {
				$name_db  = trim($key[0]);//utf8_encode(trim($key[0]));
				$desc_db  = trim($key[3]);//utf8_encode(trim($key[3]));
				$weeks_db = intval($key[1]);
				$sess_db  = trim($key[2]);
                $disc_db  = trim($key[4]);
                $days_db  = intval($key[5]);
			}
		}
		# echo "NOMBRE QUE LLEGÓ: ".$name_b."<br>"; echo "NOMBRE DE DB: ".$name_db;
		if ($name_b != $name_db || $desc_b != $desc_db || $weeks_b != $weeks_db || $sess_b != $sess_db || $disc_b != $disc_db || $days_b != $days_db) {# Algo se modifico
			$block_update = intval(update_gral('`Blocks`', "name_b='".$name_b."', discip_name='".$disc_b."', weeks_b='".$weeks_b."', days_b='".$days_b."', sess_b='".$sess_b."', desc_b='".$desc_b."'", "id_b='".$id_b."'"));
            #echo "---".$block_update."---";
			if ($block_update > 0) {
				#SI SE DISMINULLEN LAS SESIONES EN LA COLUMNA; SE DEBEN ELIMINAR EN `Trainings_blocks`
				if ($weeks_b < $weeks_db) { # Semanas
	 				$del_trains_week = delete_gral('`Trainings_blocks`', 'block="'.$id_b.'" AND week > "'.$weeks_b.'" ');
				}
# SE AGREGA EDICION CUANDO EL USUARIO QUITA DIAS AL BLOQUE
                if ($days_b < $days_db) { # Semanas
                    $del_trains_week = delete_gral('`Trainings_blocks`', 'block="'.$id_b.'" AND day > "'.$days_b.'" ');
                }
# TERMINA QUITAR DIAS
# Eliminacion de sessiones por dia
                $aux_sess_b  = explode("-###-", $sess_b);
                $aux_sess_db = explode("-###-", $sess_db); 
                for ($i=1; $i <= $days_b ; $i++) {
                    if (intval($aux_sess_b[$i-1]) < intval($aux_sess_db[$i-1])) {
                        $del_trains_week = delete_gral('`Trainings_blocks`', 'block="'.$id_b.'" AND day = "'.$i.'" AND session_num > "'.intval($aux_sess_b[$i-1]).'"');
                    }
                }
# TERMINA
# ORIGINAL SOLO CON 1 DIA
                /*
				if ($sess_b < $sess_db) { # Sesiones
					$del_trains_sess = delete_gral('`Trainings_blocks`', 'block="'.$id_b.'" AND session_num > "'.$sess_b.'" ');
				}
                */
# TERMINA ORIGINAL SOLO CON 1 DIA
				# Activity_code = 17
	            $message = "El usuario *".$_SESSION['name_u']."* modificó la información del Block: *".$name_db."*";
                log_mb_register($message, "17");
			}
		}
		if ($block_update == "none") {# NO SE MODIFICÓ NADA
			$res = 3;
		}else{
			if ($block_update > 0) {# SE MODIFICÓ EL BLOQUE
				$res = 1;
			}else{# NO SE PUDO MODIFICAR
				$res = 2;
			}
		}
		//$res = $name_b."--".$name_db."||".$desc_b."--".$desc_db."||".$weeks_b."--".$weeks_db."||".$sess_b."--".$sess_db;
		/*
		res = res.split("@-@");
		var origen   = res[0];// De donde viene el proceso
		var response = res[1];// Respuesta concreta del proceso
		var paramtr  = res[2];// paramentro adicional
		*/
		return '1@-@'.$res.'@-@'.$id_b.'#-#'.utf8_encode($name_b).'#-#'.$weeks_b.'#-#'.$sess_b.'#-#'.utf8_encode($desc_b).'#-#'.$disc_b.'#-#'.$days_b;
		#return $title.$table.'&&&.second_content&&&'.$insert_block;
	}
	function load_form_block($type,$weeks_train){
		$weeks_train = intval($weeks_train);
		$data = $block_info = $days_b = "";
		$block_info = select_gral('`Blocks`', 'id_b,name_b,weeks_b,sess_b,desc_b,discip_name,days_b', 'id_b IS NOT NULL', 'name_b');
		$data .= ' <div id="load_table'.$type.'">
                    <div class="form-group">
                        <label for="block_info">Bloque <span style="color:red;">*</span></label>
                            <select data-placeholder="Selecciona/Busca un Bloque..." style="width:100%" class="chzn-select" id="block_info" tabindex="2" onchange="show_info_block(); return false;">
                                <option value=""></option>';
        if ($block_info == true) {
        	foreach ($block_info as $key) {
        		$data .= '<option value="'.$key[0].'##'.$key[2].'##'.str_replace("-###-", " - ", $key[3]).'##'.utf8_encode($key[4]).'##'.intval($key[6]).'">'.utf8_encode($key[1]).' - '.utf8_encode($key[5]).'</option>';
                $days_b = intval($key[6]);
        	}
        }else{
        	$data .= '<option value="">Sin Bloques Disponibles...</option>';
        }
        $data .= ' 		    </select>
                    </div>';
        $data .= '	<div id="info_block"></div>';
        $data .= '  <div class="form-inline"><!--form-group-->
                        <label for="col_event">Columna del Bloque<span id="amount_alert" style="color:red;">*</span></label>
                        <select id="col_event" class="dropdown form-control input-default " name="plan_id_col1" tabindex="2" style="width:140px">
                            <option value="">...</option>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                            <option value="7">Domingo</option>
                	    </select>
                    </div><br>';
        $data .= '	<div class="form-inline">
                        <label for="sessions_event">Semana de Inicio<span id="amount_alert" style="color:red;">*</span></label>&nbsp;&nbsp;
                        <select id="sessions_event" class="dropdown form-control input-default " name="plan_id_col1" tabindex="2" style="width:140px" onchange="load_days_order_block();">
       			            <option value="">...</option>';
     	for ($i=0; $i < ($weeks_train+1); $i++) { 
     		$data .= '		<option value="'.($i+1).'"> '.($i+1).'</option>';
     	}
        $data .= '	    </select>
                    </div><br>

                    <div class="form-inline" id="order_days">
                    </div><br>

                    <!--
                    <div class="form-inline">
                        <label for="sess_col">Sesion de Columna<span id="amount_alert" style="color:red;">*</span></label>&nbsp;&nbsp;
                        <select id="sess_col" class="dropdown form-control input-default " name="plan_id_col1" tabindex="2" style="width:140px">
                            <option value="">...</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div><br>
                    -->
                    <input type="hidden" id="id_pop" value="'.$type.'">
        			<div id="load"></div>
                    <center><button class="btn btn-primary" id="blck_button" type="button" onclick="get_block_set_info(); return false;">Cargar Bloque</button></center>';
        $data .= '</div>';
        return $type.'|&|'.$data;
	}
	function get_block_set_info($id_b,$col_event,$week_event,$days_block_order){
		$sessions_block = $res = "";

        $days_block_order = explode("#--#", $days_block_order);
        $aux_days  = intval(count($days_block_order));
        $new_order = "";
        for ($i=1; $i <= $aux_days; $i++) { $new_order .= 'IF(TB.day="'.$i.'","'.$days_block_order[($i-1)].'",'; }
        $new_order .= 'TB.day';
        for ($i=1; $i <= $aux_days; $i++) { $new_order .= ')'; }
        $new_order .= ' AS dayy';
#        echo "--->".$new_order."<---";
		$sessions_block = select_big('`Sessions` AS S INNER JOIN `Trainings_blocks` AS TB ON TB.session = S.id_ssn',
									  'S.session_desc&&&TB.week&&&TB.session_num&&&TB.session&&&S.unit_s&&&S.stss&&&S.unit_b&&&S.btss&&&S.unit_r&&&S.rtss&&&'.$new_order,
									  'TB.block="'.$id_b.'"', 'TB.week,TB.day,TB.session_num');
		if ($sessions_block == true) {
			$j = 0;
			foreach ($sessions_block as $key) {
				$j++;
                $res["position_".$j] = "session_".trim($key[2])."_".($col_event+($key[10]-1))."_".($week_event+($key[1]-1));
                $res["session_".$j]  = trim(utf8_encode($key[0]));
                $res["week_".$j]	 = intval($week_event+($key[1]-1));

                $res["sess_".$j]	 = intval($key[3]);
                $res["day_".$j]		 = intval($col_event);
                $res["sesn_".$j]	 = intval($key[2]);

                $res["unit_s".$j]	 = trim($key[4]);
                $res["stss".$j]		 = trim($key[5]);
                $res["unit_b".$j]    = trim($key[6]);
                $res["btss".$j]		 = trim($key[7]);
                $res["unit_r".$j]	 = trim($key[8]);
                $res["rtss".$j]		 = trim($key[9]);
			}
			$res["total"] = $j;
		}else{ $res = 0; } #print_r($res);
		return '2@-@'.json_encode($res).'@-@';
	}
    function delete_block($id_block){
        $block = select_gral('`Blocks`', 'name_b', 'id_b="'.$id_block.'"', 'id_b');
        foreach ($block as $key) { $block = $key[0]; }

        $res = 0;
        $delete_trainings = $delete_block = 0;
        $delete_trainings = delete_gral('`Trainings_blocks`', "block='".$id_block."'");//Elimina entrenamientos asociados a el block
        if ($delete_trainings == 1) {
            $delete_block = delete_gral('`Blocks`', "id_b='".$id_block."'");//Elimina el block
        }else{ $delete_trainings = 0; }
        $res = $delete_trainings+$delete_block;
        /*
        $message = "El usuario *".$_SESSION['name_u']."* eliminó el Evento: *".$block."*";
        $insert_log = insert_gral('`Activity_log`', '`code`,`desc`', '"12","'.utf8_decode($message).'"');# tabla, campos, valores
        $res_log = intval($insert_log);
        $slack_active = select_gral('`Activity_code`', 'slack_alert', 'id_ac="12"', 'id_ac');
        if ($slack_active == true){ foreach ($slack_active as $key) { $slack_active = intval($key[0]); } }
        if ($res_log > 0 && $slack_active == 1) {
              slack_message('#actividad-mb', 'Macro Builder Log', $message, 'computer');
        }
        */
        return '3@-@'.$res.'@-@0';
    }
?>
