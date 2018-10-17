<?php
      date_default_timezone_set("America/Mexico_City");

      sajax_export("get_events_form_add_trains");   //Obtiene Wisard para asignar entrenamiento a Evento o Master
      sajax_export("get_events_form_add_trains__"); //Obtiene Wisard para asignar entrenamiento a Evento o Master
      sajax_export("load_wizard_table_trains");     //Carga tabla de sessiones (resumen)
      sajax_export("show_session_training_form");
      sajax_export("calculate_values_session");     //Calcula valor de itensidad tss basado en una cadena de sessiones
      sajax_export("desintegrate_session");         //Regresa los valores y unidades de cada disciplina de una formula dada
      sajax_export("save_all_data_db");             //Guarda los valores de un nuevo master (Valores de Tabla 2 y Tabla 3) en DB (Trinings y Sessions)
      sajax_export("save_all_data_db_confirm");
      sajax_export("save_all_data_db_secundary_confirm");//Revisa si el evento secundario existe
      sajax_export("save_all_data_db_secundary_event");
      sajax_export("load_db_sessions_values");
      sajax_export("save_individual_session"); //-Para guardar sesiones individuales
      sajax_export("show_event_sessions"); //-Para mostrar el contenido de sessiones en un evento
      sajax_export("get_all_values_session"); //-Para regresar todos los valores de una session
      sajax_export("load_discipline_parameters");

      //////////////
      function get_events_form_add_trains($type_event,$info_event){//0 = evento, 1 = master
            ////Comienza Formulario de info
            $date = $type = '';
            switch ($type_event) {//antes $option
                  case 0://EVENTO
                        $type = 'Grupo';
                        $date = '<div class="form-group">
                                          <label for="date_event">Fecha Grupo <span style="color:red;">*</span></label>
                                          <input id="datepicker" type="text" placeholder="yyyy/mm/dd" name="date" class="form-control" />
                                       </div>';
                        break;
                  case 1: $type = 'Template'; break; //MASTER
                  default:
                        break;
            }
            if ($type_event == 0) {//Event
                  $select_event = build_select_db('Events', 'name_e', 'id_e', 'id_evento', 'Grupo', 1, 'master = "0"', 'width: 200px;', 'load_select_wisard(this.value);', '', '', 0);
            }else{//Master
                  $select_event = build_select_db('Events', 'name_e', 'id_e', 'id_evento', 'Macro', 1, 'master = "1"', 'width: 200px;', 'load_select_wisard(this.value);', '', '', 0);
            }
            $tit1 = $tit2 = $tit3 = $tit4 = '';
            $tit1 = 'Ingresa Información '.$type;      //'Ingresa Datos Master';
            $tit2 = 'Diseña '.$type; //'Modifica y/o Corrige';
            $tit3 = 'Modifica y/o Corrige';           //'Revisa y Guarda';
            $tit4 = 'Revisa y Guarda';          //'Diseñar Master';
            $wisard = ' <!-- START OF TABBED WIZARD -->
                    <br />
                    <form method="post" action="wizards.html">
                    <div id="wizard3" class="wizard tabbedwizard">
                        <ul class="tabbedmenu">
                            <li> <a href="#wiz3step1"> <span class="h2">PASO 1</span> <span class="label">'.$tit1.'</span> </a> </li>
                            <li> <a href="#wiz3step2"> <span class="h2">PASO 2</span> <span class="label">'.$tit2.'</span> </a> </li>
                            <li> <a href="#wiz3step3"> <span class="h2">PASO 3</span> <span class="label">'.$tit3.'</span> </a> </li>
                            <li> <a href="#wiz3step4"> <span class="h2">PASO 4</span> <span class="label">'.$tit4.'</span> </a> </li>
                        </ul>
                        <div id="wiz3step1" class="formwiz">
                                          <h4>Paso 1: '.$tit1.'</h4>
                                          <div id="wiz_step1"> <br>
                                                <!--CAMPOS ADICIONALES-->';
                                    if ($type_event == 1) {
                                          $wisard .= '<div>
                                                      <button class="btn btn-primary" type="button" onclick="load_select_wisard_name('."'".'new_master'."'".'); return false;" >Generar Nuevo </button>
                                                      <button class="btn btn-primary" type="button" onclick="load_edit_master_form(1); return false;" >Editar Nivel Existente / Agregar Nuevos Niveles </button>
                                                </div>';
                                    }
                                    if ($type_event == 0) {
                                          $info_event = explode("||", $info_event);
                                          $id_e   = $info_event[0];
                                          $name_e = $info_event[1];
                                          $id_t   = $info_event[2];
                                          #$wisard .= load_master_form($info_event);
                                          $wisard .= '<div class="form-group">'.build_select_db('Types', 'name_t', 'id_t', 'type_master', 'Categoría Grupo', 1, '', '', '', '', $id_t, 1).'</div>';
                                          $wisard .= '<div class="form-group">'.build_select_db('Levels', 'name_l', 'id_l', 'level_master', 'Nivel Grupo', 1, '', '', '', '', '', 0).'</div>';
                                          $wisard .= '<div class="form-group">
                                                            <label for="weeks_train">Semanas de Entrenamiento <span id="amount_alert" style="color:red;">*</span></label>
                                                            <select id="weeks_train" class="dropdown form-control input-default " name="plan_id_col1" tabindex="2" style="width:100px">
                                                                  <option value="">...</option>
                                                                  <option value="1"> 1</option> <option value="2"> 2</option> <option value="3"> 3</option>
                                                                  <option value="4"> 4</option> <option value="5"> 5</option> <option value="6"> 6</option>
                                                                  <option value="7"> 7</option> <option value="8"> 8</option> <option value="9"> 9</option>
                                                                  <option value="10"> 10</option> <option value="11"> 11</option> <option value="12"> 12</option>
                                                                  <option value="13"> 13</option> <option value="14"> 14</option> <option value="15"> 15</option>
                                                                  <option value="16"> 16</option> <option value="17"> 17</option> <option value="18"> 18</option>
                                                                  <option value="19"> 19</option> <option value="20"> 20</option> <option value="21"> 21</option>
                                                                  <option value="22"> 22</option> <option value="23"> 23</option> <option value="24"> 24</option>
                                                                  <option value="25"> 25</option> <option value="26"> 26</option> <option value="27"> 27</option>
                                                                  <option value="28"> 28</option> <option value="29"> 29</option> <option value="30"> 30</option>
                                                            </select>
                                                          </div>';
                                          $wisard .= '<div class="form-group">
                                                            <label for="name_event">Nombre del Grupo <span style="color:red;">*</span></label>
                                                            <input id="name_event" class="form-control input-default" type="text" placeholder="Ej. '."'".'Básico'."'".'" value="'.utf8_encode($name_e).'" disabled>
                                                      </div>
                                                      <div class="form-group">
                                                            <label for="notes_event">Notas</label>
                                                            <textarea cols="50" rows="4" id="notes_event" class="form-control input-default" placeholder="Escribe cualquier comentario que sirva en el futuro para el Grupo que vas a diseñar"></textarea>
                                                      </div>
                                                      <div class="form-group">
                                                            <label for="cuestion">Usar un Template Existente como Base?</label>
                                                            &nbsp;&nbsp;&nbsp;
                                                            <button class="btn btn-primary" type="button" onclick="show_template_base(); return false;" > Si </button>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button class="btn" type="button" onclick="load_select_wisard('."'".'new_base'."'".'); return false;" >No, Grupo sin Plantilla</button>
                                                      </div>
                                                      <div class="form-group" id="show_temp_base" style="display:none">'.
                                                            build_select_db('`Events` INNER JOIN `Trainings` ON `Trainings`.`event` =`Events`.`id_e`',
                                                                            'name_e','id_e', 'name_master_sel_base', 'Selecciona Template Base ', 1,
                                                                            '`Events`.`master` = "1"', '', 'load_select_wisard(this.value);', '3', '', 0)/*Cambie 3 x 2*/.'
                                                            <div id="select_level_wisard1"></div>
                                                      </div>
                                                      <input type="hidden" id="name_master_sel" value="'.$id_e.'"/>
                                                      <input type="hidden" id="temp_event" value="'.$id_e.'"/>';
                                    }
                                    $wisard .= '<input type="hidden" id="event_type" value="'.$type_event.'"/>';
                                    $wisard .= '<br>
                                                <div id="option_forms"> </div>
                                                <div class="form_master_load"></div>
                                                <!-- - - ORIGINAL - - <div class="form-group">
                                                      '.build_select_db('Events', 'name_e', 'id_e', 'name_master_sel', 'Nombre '.$type, 1, 'master = "1"', '', 'load_select_wisard_name(this.value);', '1', '', 0).'
                                                      <div class="form_master_load"></div>
                                                </div>-->
                                                <!--CAMPOS ADICINALES TEMRINA-->
                                                <!--<div class="form-group"> '.$select_event.' </div>-->
                                                <div class="form-group" id="select_level_wisard1"></div>
                                                <div id="step1_wizard"></div>
                                          </div>
                        </div><!--#wiz13tep1-->
                        <div id="wiz3step2" class="formwiz">
                                          <h4>Paso 2: '.$tit2.'</h4>
                            <div id="wiz_step2"> <br>
                                <div id="step2_wizard"></div>
                            </div>
                        </div><!--#wiz3step2-->
                        <div id="wiz3step3">
                                          <h4>Paso 3: '.$tit3.'</h4>
                                          <!--<div class="par terms"></div>-->
                            <div id="wiz_step3"> <br>
                              <div id="step3_wizard"></div>
                            </div>
                        </div><!--#wiz3step3-->
                        <div id="wiz3step4">
                                          <h4>Paso 4: '.$tit4.'</h4>
                                          <!--<p>kjvuiul idsi  PROBANDO... ;)</p>-->
                                          <div id="wiz_step4"> <br>
                              <div id="step4_wizard"></div>
                            </div>
                        </div><!--#wiz3step4-->
                    </div><!--#wizard-->
                    </form>            
                    <!-- END OF TABBED WIZARD -->
            ';
            return '3@@@'.$wisard;
      }
      //////////////
      function get_events_form_add_trains__($type_event,$info_assigments,$weeks_before,$date_event){//0 = evento, 1 = master
            $date = $type = '';
            $type = 'Template';
            $info_assigments = explode(',', $info_assigments);

            $select_event = build_select_db('Events', 'name_e', 'id_e', 'id_evento', 'Macro', 1, 'master = "1"', 'width: 200px;', 'load_select_wisard(this.value);', '', '', 0);

            $tit1 = $tit2 = $tit3 = $tit4 = '';
            $tit1 = 'Ingresa Información '.$type;      //'Ingresa Datos Master';
            $tit2 = 'Diseña '.$type; //'Modifica y/o Corrige';
            $tit3 = 'Modifica y/o Corrige';           //'Revisa y Guarda';
            $tit4 = 'Revisa y Guarda';          //'Diseñar Master';
            $wisard = ' <!-- START OF TABBED WIZARD -->
                    <form method="post" action="wizards.html">
                    <div id="wizard3" class="wizard tabbedwizard">
                        <ul class="tabbedmenu">
                            <li> <a href="#wiz3step1"> <span class="h2">PASO 1</span> <span class="label">'.$tit1.'</span> </a> </li>
                            <li> <a href="#wiz3step2"> <span class="h2">PASO 2</span> <span class="label">'.$tit2.'</span> </a> </li>
                            <li> <a href="#wiz3step3"> <span class="h2">PASO 3</span> <span class="label">'.$tit3.'</span> </a> </li>
                            <li> <a href="#wiz3step4"> <span class="h2">PASO 4</span> <span class="label">'.$tit4.'</span> </a> </li>
                        </ul>
                        <div id="wiz3step1" class="formwiz">
                                          <h4>Paso 1: '.$tit1.'</h4>
                                          <div id="wiz_step1"> <br>
                                                <!--CAMPOS ADICIONALES-->';
                                    $wisard .= '<input type="hidden" id="event_type" value="'.$type_event.'"/>';
                                    $wisard .= '<br>
                                                <div id="option_forms"> </div>
                                                <div class="form_master_load">';
                  $master_n = $matser_w = $master_t = $mastr_nt = "";
                  $master_info = select_gral('`Events`', 'name_e, weeks, type, notes', 'id_e="'.$info_assigments[2].'"', 'id_e');
                  if ($master_info == true) { 
                        foreach ($master_info as $m_i) { $master_n = $m_i[0]; $matser_w = $m_i[1]; $master_t = $m_i[2]; $mastr_nt = $m_i[3]; } 
                  }
                  $master_t = select_gral('`Types`', 'name_t', 'id_t="'.$master_t.'"', 'id_t');
                  if ($master_t == true) { foreach ($master_t as $m_t) { $master_t = $m_t[0]; } }
                  $wisard .= '<div class="form-group" style="display: none;">'.build_select_db('Types', 'name_t', 'id_t', 'type_master', 'Categoría Template', 0, '', '', '', '', $master_t, 1).'</div>';
                  $wisard .= '<input type="hidden" id="level_master_tst" value="'.$info_assigments[3].'"/>';
                  $wisard .= '<div class="form-group">
                                    <div class="alert alert-block" style="width:30%;">
                                          <button data-dismiss="alert" class="close" type="button">x</button>
                                          <h4>Notas</h4>
                                          <p style="margin: 8px 0">Hay '.$matser_w.' Semanas asignadas de '.(intval($matser_w)+intval($weeks_before)).', por lo tanto '.$weeks_before.' semanas estan vacias.<br>El Grupo inicia en: '.date('d-M-Y', strtotime($date_event)).'</p>
                                    </div>
                                    <label for="weeks_train_b">Semanas de Entrenamiento Previas</label>
                                    <select id="weeks_train_b" class="dropdown form-control input-default " name="plan_id_col1" tabindex="2" style="width:100px">';
                  $selected = "";
                  (intval($weeks_before == 0)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="0" '.$selected.'></option>';
                  (intval($weeks_before == 1)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="1" '.$selected.'>1</option>';
                  (intval($weeks_before == 2)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="2" '.$selected.'>2</option>';
                  (intval($weeks_before == 3)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="3" '.$selected.'>3</option>';
                  (intval($weeks_before == 4)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="4" '.$selected.'>4</option>';
                  (intval($weeks_before == 5)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="5" '.$selected.'>5</option>';
                  (intval($weeks_before == 6)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="6" '.$selected.'>6</option>';
                  (intval($weeks_before == 7)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="7" '.$selected.'>7</option>';
                  (intval($weeks_before == 8)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="8" '.$selected.'>8</option>';
                  (intval($weeks_before == 9)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="9" '.$selected.'>9</option>';
                  (intval($weeks_before == 10)) ? $selected = "selected" : $selected = "";
                  $wisard .= '            <option value="10" '.$selected.'>10</option>
                                    </select>
                                  </div>';
                  $wisard .= '<div class="form-group" style="display: none;">
                                    <label for="weeks_train">Semanas de Entrenamiento Template Original</label>
                                    <select id="weeks_train" class="dropdown form-control input-default " name="plan_id_col1" tabindex="2" style="width:100px" disabled="true">
                                          <option value="'.$matser_w.'">'.$matser_w.'</option>
                                    </select>
                                  </div>';
                  $wisard .= '<div class="form-group" style="display: none;">
                                    <label for="name_event">Como quieres que se llame el nuevo Template <span style="color:red;">*</span></label>
                                    <input id="name_event" class="form-control input-default" type="text" placeholder="Ej. '."'".'Básico'."'".'" value="'.utf8_encode($master_n).'__ATT" >
                              </div>
                              <input id="name_event_orig" type="hidden" value="'.utf8_encode($master_n).'" >';

                  $wisard .= '<div class="form-group">
                                    <label for="notes_event">Notas</label>
                                    <textarea cols="50" rows="4" id="notes_event" class="form-control input-default" placeholder="Escribe cualquier comentario que sirva en el futuro para el Template que vas a diseñar">'.utf8_encode($mastr_nt).'</textarea>
                              </div>
                              <div class="form-group">
                                    <button class="btn btn-primary" type="button" onclick="load_wizard_table_trains_direct('."'".$info_assigments[2]."','".$info_assigments[3]."'".',2,1); return false;" > Cargar Template </button>
                              </div>
                              <div class="form-group" id="show_temp_base" style="display:none">'.
                                    build_select_db('`Events` INNER JOIN `Trainings` ON `Trainings`.`event` =`Events`.`id_e`',
                                                    'name_e','id_e', 'name_master_sel_base', 'Selecciona Template Base ', 1,
                                                    '`Events`.`master` = "1"', '', 'load_select_wisard(this.value);', '3', '', 0)/*Cambie 3 x 2*/.'
                                    <div id="select_level_wisard1"></div>
                              </div>
                              <div><input type="hidden" id="name_master_sel" value="new_master"/></div>';
                                    $wisard .= '</div>
                                                <!--CAMPOS ADICINALES TEMRINA-->
                                                <div class="form-group" id="select_level_wisard1"></div>
                                                <div id="step1_wizard"></div>
                                          </div>
                        </div><!--#wiz13tep1-->
                        <div id="wiz3step2" class="formwiz">
                                          <h4>Paso 2: '.$tit2.'</h4>
                            <div id="wiz_step2"> <br>
                                <div id="step2_wizard"></div>
                            </div>
                        </div><!--#wiz3step2-->
                        <div id="wiz3step3">
                                          <h4>Paso 3: '.$tit3.'</h4>
                                          <!--<div class="par terms"></div>-->
                            <div id="wiz_step3"> <br>
                              <div id="step3_wizard"></div>
                            </div>
                        </div><!--#wiz3step3-->
                        <div id="wiz3step4">
                                          <h4>Paso 4: '.$tit4.'</h4>
                                          <!--<p>kjvuiul idsi  PROBANDO... ;)</p>-->
                                          <div id="wiz_step4"> <br>
                              <div id="step4_wizard"></div>
                            </div>
                        </div><!--#wiz3step4-->
                    </div><!--#wizard-->
                    </form>            
                    <!-- END OF TABBED WIZARD -->';
            return '33@-@'.$wisard.'@-@0';
      }
      //////////////
      function load_wizard_table_trains($name_event_new,$type_master_new,$level_master_new,$weeks_train_new, $id_event, $id_level, $position, $type, $weeks_b, $weeks_p){//id_event DB, id_level DB, id_position wizard, type 0 = evento 1 = master
            $table_trains = '';
            $level_e    = select_gral('Levels', 'name_l', 'id_l = "'.$level_master_new.'"', 'id_l');

            foreach ($level_e as $level_name) { $level_e = $level_name[0]; }
            $type_master_new = select_gral('Types', 'name_t', 'id_t = "'.$type_master_new.'"', 'id_t');
            foreach ($type_master_new as $key_type_name) { $type_master_new = $key_type_name[0]; }
            if ($id_event == 'new_base') {
                  $id_event = '0';
                  if ($type == 1) {//Simple
                        $table_trains .= new_table_sessions($type_master_new,$weeks_train_new,$id_event,$level_e,$position,$id_level,$name_event_new,$weeks_b,$weeks_p);
                  }if ($type == 2) {
                        $table_trains .= new_table_sessions_complete($type_master_new,$weeks_train_new,$id_event,$level_e,$id_level,$name_event_new,$weeks_b,$weeks_p);
                  }
            }else{
                  #### C O I E N Z A   P R O B A N D O   C A R G A   D E   D A T O S   D E S D E   D B ############
                  if ($type == 1) {//Simple
                        $table_trains .= new_table_sessions($type_master_new,$weeks_train_new,$id_event,$level_e,$position,$id_level,$name_event_new,$weeks_b,$weeks_p);
                  }if ($type == 2) {
                        $table_trains .= new_table_sessions_complete($type_master_new,$weeks_train_new,$id_event,$level_e,$id_level,$name_event_new,$weeks_b,$weeks_p);
                  }
                  #### T E R M I N A   P R O B A N D O   C A R G A   D E   D A T O S   D E S D E   D B ############
            }
            $auto_saved = 0;
            if (intval($position) == 4 && (intval($weeks_b) > 0 || intval($weeks_p) > 0)) { $auto_saved=1; }
		return $table_trains.'&&&#step'.$position.'_wizard&&&'.$auto_saved;//Contenido &&& identificador de DIV
	}
      //////////////
      function show_session_training_form($session_training, $id_input, $row, $type, $prev_ses){
            $session_training = str_replace('mmaass', '+', $session_training);
            $prev_ses_content = $checked = '';
            $prev_ses         = str_replace('mmaass', '+', $prev_ses);
            $class_button = 'btn-disabled';
            if ($prev_ses != '') {
                  ($prev_ses == $session_training) ? $checked = 'checked' : $checked = '';
                  $prev_ses_content = '<td>
                                          <!--<label>Copiar '.$prev_ses.'? </label>
                                          <input type="checkbox" id="anterior_session_week" onchange="load_prev_session_value('."'".$prev_ses."'".','."'".$id_input."'".','."'".$row."'".','."'".$type."'".'); return false;" '.$checked.'/>-->
                                          <label>Copiar </label>
                                          <input type="checkbox" id="anterior_session_week" onchange="load_prev_session_value('."'".$prev_ses."'".','."'".$id_input."'".','."'".$row."'".','."'".$type."'".'); return false;" '.$checked.'/>';
                  if ($type != 3) {
                        $prev_ses_content .= ' <label> Completar </label>
                        <input type="checkbox" id="anterior_session_week_all_column" onchange="load_prev_session_value_all_column('."'".$prev_ses."'".','."'".$id_input."'".','."'".$row."'".','."'".$type."'".'); return false;" />';
                  }
                  $prev_ses_content .= '  <label>'.$prev_ses.'</label>
                                       </td>';
                  if ($checked == 'checked') { $class_button = 'btn-primary'; }
            }
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
                        <table class="popup_table">';
            $table .= '       <tr>
                                    '.$prev_ses_content.'
                              </tr>';
            $table .= '       <tr><th><span id="session_t'.$type.'">'.$s_t.'</span></th></tr>';
            //if ($session_training == '') {
                  $table .= ' <!--COMIENZA NUEVO CON RADIOS-->
                              <tr><td> <span>Disciplina </span><span style="color:red;">* </span><span>:</span> </td></tr>
                              <tr>
                                    <td>
                                          <center>';
                        ($session_parts[0] == 'S' || $session_parts[0] == 'Prueba de la T' || $session_parts[0] == 'Prueba CSS') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                        ($session_parts[0] == 'S' || $session_parts[0] == 'Prueba de la T' || $session_parts[0] == 'Prueba CSS') ? $checked = 'checked' : $checked = '';
                              $table .= '       <label '.$selected.'>Swim</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="S" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
                        ($session_parts[0] == 'B' || $session_parts[0] == 'FTP') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                        ($session_parts[0] == 'B' || $session_parts[0] == 'FTP') ? $checked = 'checked' : $checked = '';
                              $table .= '       <label '.$selected.'>Bike</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="B" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
                        ($session_parts[0] == 'R' || $session_parts[0] == 'Vdot' || $session_parts[0] == 'PruebaMilla' || $session_parts[0] == 'Prueba3k' || $session_parts[0] == 'Prueba5k') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                        ($session_parts[0] == 'R' || $session_parts[0] == 'Vdot' || $session_parts[0] == 'PruebaMilla' || $session_parts[0] == 'Prueba3k' || $session_parts[0] == 'Prueba5k') ? $checked = 'checked' : $checked = '';
                              $table .= '       <label '.$selected.'>Run</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="R" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
                        ($session_parts[0] == 'BR' || $session_parts[0] == 'KW') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                        ($session_parts[0] == 'BR' || $session_parts[0] == 'KW') ? $checked = 'checked' : $checked = '';
                              $table .= '       <label '.$selected.'>Brick</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="BR" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/>
                                                <br>';
                        ($session_parts[0] == 'F') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                        ($session_parts[0] == 'F') ? $checked = 'checked' : $checked = '';
                              $table .= '       <label '.$selected.'>Fuerza</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="F" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
                        ($session_parts[0] == 'XT') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                        ($session_parts[0] == 'XT') ? $checked = 'checked' : $checked = '';
                              $table .= '       <label '.$selected.'>Xtrain</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="XT" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/> &nbsp;|&nbsp; ';
                        ($session_parts[0] == 'off') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                        ($session_parts[0] == 'off') ? $checked = 'checked' : $checked = '';
                              $table .= '       <label '.$selected.'>Descanso</label> <input type="checkbox" id="discipline_'.$type.'" name="discipline_'.$type.'" value="off" onchange="check_just_one_check(this.id, this.value); load_discipline_parameters('.$type.');" '.$checked.'/>
                                                <br><br>';
                                    $table .= ' <b>PRUEBA </b><input type="checkbox" id="checkbox_is_try_'.$type.'" onchange="load_discipline_parameters('.$type.');" '.$checked_try.' '.$disss.'/>
                                          </center>
                                    </td>
                              </tr>
                              <!--TERMINA NUEVO CON RADIOS-->
                              <!--<tr>  AL ACTIVAR EL SELECT MODIFICAR LLEGADA DEL CAMPO EN load_discipline_parameters
                                    <td>
                                          <span>Disciplina </span><span style="color:red;">* </span><span>:</span>
                                          <select id="discipline_'.$type.'" onchange="load_discipline_parameters('.$type.');">
                                                <option value=""></option>';
                                                ($session_parts[0] == 'S' || $session_parts[0] == 'Prueba de la T' || $session_parts[0] == 'Prueba CSS') ? $selected = 'selected' : $selected = '';
                                    $table .= ' <option value="S" '.$selected.'>Swim</option>';
                                                ($session_parts[0] == 'B' || $session_parts[0] == 'FTP') ? $selected = 'selected' : $selected = '';
                                    $table .= ' <option value="B" '.$selected.'>Bike</option>';
                                                ($session_parts[0] == 'R' || $session_parts[0] == 'Vdot') ? $selected = 'selected' : $selected = '';
                                    $table .= ' <option value="R" '.$selected.'>Run</option>';
                                                ($session_parts[0] == 'BR' || $session_parts[0] == 'KW') ? $selected = 'selected' : $selected = '';
                                    $table .= ' <option value="BR" '.$selected.'>Brick</option>';
                                                ($session_parts[0] == 'F') ? $selected = 'selected' : $selected = '';
                                    $table .= ' <option value="F" '.$selected.'>Fuerza</option>';
                                                ($session_parts[0] == 'XT') ? $selected = 'selected' : $selected = '';
                                    $table .= ' <option value="XT" '.$selected.'>Xtrain</option>';
                                                ($session_parts[0] == 'off') ? $selected = 'selected' : $selected = '';
                                    $table .= ' <option value="off" '.$selected.'>Descanso</option>
                                          <select>
                                          Es prueba<input type="checkbox" id="checkbox_is_try_'.$type.'" onchange="load_discipline_parameters('.$type.');" '.$checked_try.'/>
                                    </td>
                              </tr>-->
                              <tr> <td> <div id="is_try_'.$type.'">';
                              if ($session_training != '') {
                                    $discipline = $session_parts[0];
                                    $selected = '';
                                    $s_types = '';
                                    switch ($discipline) {
                                          case 'S':
                                                preg_match('#\((.*?)\)#', $session_parts[1], $match);
                                                (isset($match[1])) ? $match[1]=$match[1] : $match[1]='';
                                                $session_parts[1] = explode('(', $session_parts[1]);
                                                //// - - - - - - - - - - - - - - - - - - - - - - - - - - -
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
                                                $s_types .= '<br>'.$zones;
                                                //Finish personalized zones
                                                //// - - - - - - - - - - - - - - - - - - - - - - - - - - -
                                          case 'Prueba de la T':
                                                $zones = '';
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
                                                            '.$s_types.'<br>'.$zones.' <br>&nbsp;<!--222222222-->
                                                            Observación:
                                                            <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;" value="'.$match[1].'" '.$diss.'>
                                                      </center>';
                                                break;
                                          case 'Prueba CSS':
                                                $zones = '';
                                                if ($discipline == 'Prueba CSS') { $match[1] = ''; }
                                                ($discipline == 'Prueba CSS') ? $try = '1' : $try = '0';
                                                ($discipline == 'Prueba CSS') ? $diss = 'disabled' : $diss = '';
                                                ($discipline == 'Prueba CSS') ? $data_sesion = '1500' : $data_sesion = $session_parts[1][0];
                                                ($discipline == 'Prueba CSS') ? $table .= 'Prueba CSS:<br>' : $table .= 'Parámetros de S:<br>';
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
                                                // / / # # # # # # # # # # #
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
                                                                  </select>
                                                            <br>';
                                                      $data_sesion = '60';
                                                      $zoneb = '';
                                                }else{
                                                      $try = '0';
                                                      $disb = $unitb = '';
                                                      if (substr($data_sesion, -1) == 'k'){ $unitb = 'km'; }
                                                      if (substr($data_sesion, -1) == 'm'){ $unitb = 'min'; }
                                                      $data_sesion = substr($data_sesion, 0, -1);
                                                      $table .= 'Parámetros de B:<br>';
                                                      $tssb_t = explode(',', session_calculate(','.$data_sesion.'-'.$unitb.','));
                                                      $tssb_t = $tssb_t[1];
                                                      //
                                                      if (count($session_parts) >= 3) {
                                                            preg_match('#\((.*?)\)#', $session_parts[2], $match);
                                                            $session_parts[2] = explode('(', $session_parts[2]);
                                                            (isset($match[1])) ? $match[1]=$match[1] : $match[1]='';
                                                            $zb = $session_parts[2][0];
                                                      }else{ $zb = ""; $match[1]=''; }
                                                      $zoneb = $style = '';
                                                      $zoneb .= '<b>Zona:<span style="color:red;"> *</span></b><br>';
                                                      //CON CHECKBOX
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
                                                      //Finish personalized zones
                                                }
                                                $table .= '<center>';
                                                if ($unitb == 'min') {
                                                      $table .= '<span id="sel_cant_b'.$type.'">
                                                                  <select class="input_box" id="b'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disb.'>
                                                                        <option value="">0</option>';
                                                            ($data_sesion == '10') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="10" '.$selected.'>10</option>';
                                                            ($data_sesion == '20') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="20" '.$selected.'>20</option>';
                                                            ($data_sesion == '30') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                            ($data_sesion == '40') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="40" '.$selected.'>40</option>';
                                                            ($data_sesion == '50') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="50" '.$selected.'>50</option>';
                                                            ($data_sesion == '60') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="60" '.$selected.'>60</option>';
                                                            ($data_sesion == '70') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="70" '.$selected.'>70</option>';
                                                            ($data_sesion == '80') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="80" '.$selected.'>80</option>';
                                                            ($data_sesion == '90') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="90" '.$selected.'>90</option>';
                                                            ($data_sesion == '100') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="100" '.$selected.'>100</option>';
                                                            ($data_sesion == '110') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="110" '.$selected.'>110</option>';
                                                            ($data_sesion == '120') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="120" '.$selected.'>120</option>';
                                                            ($data_sesion == '130') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="130" '.$selected.'>130</option>';
                                                            ($data_sesion == '140') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="140" '.$selected.'>140</option>';
                                                            ($data_sesion == '150') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="150" '.$selected.'>150</option>';
                                                            ($data_sesion == '160') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="160" '.$selected.'>160</option>';
                                                            ($data_sesion == '170') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="170" '.$selected.'>170</option>';
                                                            ($data_sesion == '180') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="180" '.$selected.'>180</option>';
                                                            ($data_sesion == '190') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="190" '.$selected.'>190</option>';
                                                            ($data_sesion == '200') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="200" '.$selected.'>200</option>';
                                                            ($data_sesion == '210') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="210" '.$selected.'>210</option>';
                                                            ($data_sesion == '220') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="220" '.$selected.'>220</option>';
                                                            ($data_sesion == '230') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="230" '.$selected.'>230</option>';
                                                            ($data_sesion == '240') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="240" '.$selected.'>240</option>';
                                                            ($data_sesion == '250') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="250" '.$selected.'>250</option>';
                                                            ($data_sesion == '260') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="260" '.$selected.'>260</option>';
                                                            ($data_sesion == '270') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="270" '.$selected.'>270</option>';
                                                            ($data_sesion == '280') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="280" '.$selected.'>280</option>';
                                                            ($data_sesion == '290') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="290" '.$selected.'>290</option>';
                                                            ($data_sesion == '300') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="300" '.$selected.'>300</option>';
                                                            ($data_sesion == '310') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="310" '.$selected.'>310</option>';
                                                            ($data_sesion == '320') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="320" '.$selected.'>320</option>';
                                                            ($data_sesion == '330') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="330" '.$selected.'>330</option>';
                                                            ($data_sesion == '340') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="340" '.$selected.'>340</option>';
                                                            ($data_sesion == '350') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="350" '.$selected.'>350</option>';
                                                            ($data_sesion == '360') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="360" '.$selected.'>360</option>';
                                                            ($data_sesion == '370') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="370" '.$selected.'>370</option>';
                                                            ($data_sesion == '380') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="380" '.$selected.'>380</option>';
                                                            ($data_sesion == '390') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="390" '.$selected.'>390</option>';
                                                            ($data_sesion == '400') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="400" '.$selected.'>400</option>
                                                                  </select>
                                                            </span> &nbsp;&nbsp;&nbsp;
                                                      ';
                                                }else{
                                                      $table .= '<span id="sel_cant_b'.$type.'">
                                                                  <select class="input_box" id="b'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disb.'>
                                                                        <option value="">0</option>';
                                                            ($data_sesion == '5') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="5" '.$selected.'>5</option>';
                                                            ($data_sesion == '10') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="10" '.$selected.'>10</option>';
                                                            ($data_sesion == '15') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="15" '.$selected.'>15</option>';
                                                            ($data_sesion == '20') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="20" '.$selected.'>20</option>';
                                                            ($data_sesion == '25') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="25" '.$selected.'>25</option>';
                                                            ($data_sesion == '30') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                            ($data_sesion == '35') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="35" '.$selected.'>35</option>';
                                                            ($data_sesion == '40') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="40" '.$selected.'>40</option>';
                                                            ($data_sesion == '45') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="45" '.$selected.'>45</option>';
                                                            ($data_sesion == '50') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="50" '.$selected.'>50</option>';
                                                            ($data_sesion == '55') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="55" '.$selected.'>55</option>';
                                                            ($data_sesion == '60') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="60" '.$selected.'>60</option>';
                                                            ($data_sesion == '65') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="65" '.$selected.'>65</option>';
                                                            ($data_sesion == '70') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="70" '.$selected.'>70</option>';
                                                            ($data_sesion == '75') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="75" '.$selected.'>75</option>';
                                                            ($data_sesion == '80') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="80" '.$selected.'>80</option>';
                                                            ($data_sesion == '85') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="85" '.$selected.'>85</option>';
                                                            ($data_sesion == '90') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="90" '.$selected.'>90</option>';
                                                            ($data_sesion == '95') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="95" '.$selected.'>95</option>';
                                                            ($data_sesion == '100') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="100" '.$selected.'>100</option>';
                                                            ($data_sesion == '105') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="105" '.$selected.'>105</option>';
                                                            ($data_sesion == '110') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="110" '.$selected.'>110</option>';
                                                            ($data_sesion == '115') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="115" '.$selected.'>115</option>';
                                                            ($data_sesion == '120') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="120" '.$selected.'>120</option>';
                                                            ($data_sesion == '125') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="125" '.$selected.'>125</option>';
                                                            ($data_sesion == '130') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="130" '.$selected.'>130</option>';
                                                            ($data_sesion == '135') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="135" '.$selected.'>135</option>';
                                                            ($data_sesion == '140') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="140" '.$selected.'>140</option>';
                                                            ($data_sesion == '145') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="145" '.$selected.'>145</option>';
                                                            ($data_sesion == '150') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="150" '.$selected.'>150</option>';
                                                            ($data_sesion == '155') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="155" '.$selected.'>155</option>';
                                                            ($data_sesion == '160') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="160" '.$selected.'>160</option>';
                                                            ($data_sesion == '165') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="165" '.$selected.'>165</option>';
                                                            ($data_sesion == '170') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="170" '.$selected.'>170</option>';
                                                            ($data_sesion == '175') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="175" '.$selected.'>175</option>';
                                                            ($data_sesion == '180') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="180" '.$selected.'>180</option>';
                                                            ($data_sesion == '185') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="185" '.$selected.'>185</option>';
                                                            ($data_sesion == '190') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="190" '.$selected.'>190</option>';
                                                            ($data_sesion == '195') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="195" '.$selected.'>195</option>';
                                                            ($data_sesion == '200') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="200" '.$selected.'>200</option>';
                                                            ($data_sesion == '205') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="205" '.$selected.'>205</option>';
                                                            ($data_sesion == '210') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="210" '.$selected.'>210</option>';
                                                            ($data_sesion == '215') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="215" '.$selected.'>215</option>';
                                                            ($data_sesion == '220') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="220" '.$selected.'>220</option>';
                                                            ($data_sesion == '225') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="225" '.$selected.'>225</option>';
                                                            ($data_sesion == '230') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="230" '.$selected.'>230</option>';
                                                            ($data_sesion == '235') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="235" '.$selected.'>235</option>';
                                                            ($data_sesion == '240') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="240" '.$selected.'>240</option>';
                                                            ($data_sesion == '245') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="245" '.$selected.'>245</option>';
                                                            ($data_sesion == '250') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="250" '.$selected.'>250</option>
                                                                  </select>
                                                            </span> &nbsp;&nbsp;&nbsp;
                                                      ';
                                                }
                                                $table .= '<span id="mb'.$type.'">
                                                            <!--UNIDADES ATERIORES-->
                                                            <!--<select id="selb'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'b'".'); return false;" '.$disb.'>
                                                                  <option value=""></option>';
                                                                  ($unitb == 'min') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="min" '.$selected.'>min</option>';
                                                                  ($unitb == 'km') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="km" '.$selected.'>km</option>
                                                            </select>-->
                                                            <!--NUEVAS UNIDADES CON RADIO modificar tambien las funciones que llama onclick-->';
                                                            ///AHORA CON CHECKBOX
                                                            ($unitb == 'min') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                                                            ($unitb == 'min') ? $checked = 'checked' : $checked = '';
                                                $table .= ' <label '.$selected.'>min</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'b'".');" '.$disb.' '.$checked.'/> &nbsp;|&nbsp;';
                                                            ($unitb == 'km') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                                                            ($unitb == 'km') ? $checked = 'checked' : $checked = '';
                                                $table .= ' <label '.$selected.'> km</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'b'".');" '.$disb.' '.$checked.'/>
                                                            <!---->';
                                          $table .= ' </span> &nbsp;&nbsp;&nbsp;
                                                ';
                                                $table .= '<span id="b_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
                                                           <span id="tss'.$type.'">'.$tssb_t.'</span>&nbsp;&nbsp; TSS
                                                            <br>';
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
                                                      ////
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
                                                      $table .= '       <label '.$style.'>Vdot</label>
                                                                        <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="vdot" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');" '.$checked.'/> |';
                                                      ($discipline == 'PruebaMilla') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                                                      ($discipline == 'PruebaMilla') ? $checked = 'checked' : $checked = '';
                                                      $table .= '       <label '.$style.'>Milla</label>
                                                                        <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="milla" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');" '.$checked.'/> |';
                                                      ($discipline == 'Prueba3k') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                                                      ($discipline == 'Prueba3k') ? $checked = 'checked' : $checked = '';
                                                      $table .= '       <label '.$style.'>3k</label>
                                                                        <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="3k" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');" '.$checked.'/> |';
                                                      ($discipline == 'Prueba5k') ? $style = 'style="color:#0DA5AB;"' : $style = '';
                                                      ($discipline == 'Prueba5k') ? $checked = 'checked' : $checked = '';
                                                      $table .= '       <label '.$style.'>5k</label>
                                                                        <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="5k" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');" '.$checked.'/>';
                                                      $table .= '</center>';
                                                      ////
                                                }else{
                                                      $data_sesion = $session_parts[1];
                                                      $try = '0';
                                                      $disr = $unitr = '';
                                                      if(substr($data_sesion, -1) == 'k'){ $unitr = 'km'; }
                                                      if(substr($data_sesion, -1) == 'm'){ $unitr = 'min'; }
                                                      $data_sesion = substr($data_sesion, 0, -1);
                                                      $table .= 'Parámetros de R:<br>';
                                                      $tssr_t = explode(',', session_calculate(',,'.$data_sesion.'-'.$unitr));
                                                      $tssr_t = $tssr_t[2];
                                                      //
                                                      if (count($session_parts) >= 3) {
                                                            preg_match('#\((.*?)\)#', $session_parts[2], $match);
                                                            $session_parts[2] = explode('(', $session_parts[2]);
                                                            (isset($match[1])) ? $match[1]=$match[1] : $match[1]='';
                                                            $zr = $session_parts[2][0];
                                                      }else{ $zr = ""; $match[1]=''; }
                                                      $zoner = $style = '';
                                                      $all_zones = select_gral('`Zones`','name_z','name_z NOT IN("Z1+","Z1-","Z2+","Z2-","Z3+","Z3-","Z4+","Z4-")','id_z','name_z');
                                                      if ($all_zones == true) {
                                                            $zoner .= '<b>Zona:<span style="color:red;"> *</span></b><br>';
                                                            foreach ($all_zones as $key) {
                                                                  //CON CHECKBOX
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
                                                      $table .= '<span id="sel_cant_r'.$type.'">
                                                                  <select class="input_box" id="r'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disr.'>
                                                                        <option value="">0</option>';
                                                            ($data_sesion == '10') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="10" '.$selected.'>10</option>';
                                                            ($data_sesion == '20') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="20" '.$selected.'>20</option>';
                                                            ($data_sesion == '30') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                            ($data_sesion == '40') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="40" '.$selected.'>40</option>';
                                                            ($data_sesion == '50') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="50" '.$selected.'>50</option>';
                                                            ($data_sesion == '60') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="60" '.$selected.'>60</option>';
                                                            ($data_sesion == '70') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="70" '.$selected.'>70</option>';
                                                            ($data_sesion == '80') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="80" '.$selected.'>80</option>';
                                                            ($data_sesion == '90') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="90" '.$selected.'>90</option>';
                                                            ($data_sesion == '100') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="100" '.$selected.'>100</option>';
                                                            ($data_sesion == '110') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="110" '.$selected.'>110</option>';
                                                            ($data_sesion == '120') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="120" '.$selected.'>120</option>';
                                                            ($data_sesion == '130') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="130" '.$selected.'>130</option>';
                                                            ($data_sesion == '140') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="140" '.$selected.'>140</option>';
                                                            ($data_sesion == '150') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="150" '.$selected.'>150</option>';
                                                            ($data_sesion == '160') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="160" '.$selected.'>160</option>';
                                                            ($data_sesion == '170') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="170" '.$selected.'>170</option>';
                                                            ($data_sesion == '180') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="180" '.$selected.'>180</option>';
                                                            ($data_sesion == '190') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="190" '.$selected.'>190</option>';
                                                            ($data_sesion == '200') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="200" '.$selected.'>200</option>
                                                                  </select>
                                                            </span> &nbsp;&nbsp;&nbsp;
                                                      ';
                                                }else{
                                                      $table .= '<span id="sel_cant_r'.$type.'">
                                                                  <select class="input_box" id="r'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disr.'>
                                                                        <option value="">0</option>';
                                                            ($data_sesion == '1') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="1" '.$selected.'>1</option>';
                                                            ($data_sesion == '2') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="2" '.$selected.'>2</option>';
                                                            ($data_sesion == '3') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="3" '.$selected.'>3</option>';
                                                            ($data_sesion == '4') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="4" '.$selected.'>4</option>';
                                                            ($data_sesion == '5') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="5" '.$selected.'>5</option>';
                                                            ($data_sesion == '6') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="6" '.$selected.'>6</option>';
                                                            ($data_sesion == '7') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="7" '.$selected.'>7</option>';
                                                            ($data_sesion == '8') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="8" '.$selected.'>8</option>';
                                                            ($data_sesion == '9') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="9" '.$selected.'>9</option>';
                                                            ($data_sesion == '10') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="10" '.$selected.'>10</option>';
                                                            ($data_sesion == '11') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="11" '.$selected.'>11</option>';
                                                            ($data_sesion == '12') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="12" '.$selected.'>12</option>';
                                                            ($data_sesion == '13') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="13" '.$selected.'>13</option>';
                                                            ($data_sesion == '14') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="14" '.$selected.'>14</option>';
                                                            ($data_sesion == '15') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="15" '.$selected.'>15</option>';
                                                            ($data_sesion == '16') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="16" '.$selected.'>16</option>';
                                                            ($data_sesion == '17') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="17" '.$selected.'>17</option>';
                                                            ($data_sesion == '18') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="18" '.$selected.'>18</option>';
                                                            ($data_sesion == '19') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="19" '.$selected.'>19</option>';
                                                            ($data_sesion == '20') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="20" '.$selected.'>20</option>';
                                                            ($data_sesion == '21') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="21" '.$selected.'>21</option>';
                                                            ($data_sesion == '22') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="22" '.$selected.'>22</option>';
                                                            ($data_sesion == '23') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="23" '.$selected.'>23</option>';
                                                            ($data_sesion == '24') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="24" '.$selected.'>24</option>';
                                                            ($data_sesion == '25') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="25" '.$selected.'>25</option>';
                                                            ($data_sesion == '26') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="26" '.$selected.'>26</option>';
                                                            ($data_sesion == '27') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="27" '.$selected.'>27</option>';
                                                            ($data_sesion == '28') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="28" '.$selected.'>28</option>';
                                                            ($data_sesion == '29') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="29" '.$selected.'>29</option>';
                                                            ($data_sesion == '30') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                            ($data_sesion == '31') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="31" '.$selected.'>31</option>';
                                                            ($data_sesion == '32') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="32" '.$selected.'>32</option>';
                                                            ($data_sesion == '33') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="33" '.$selected.'>33</option>';
                                                            ($data_sesion == '34') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="34" '.$selected.'>34</option>';
                                                            ($data_sesion == '35') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="35" '.$selected.'>35</option>';
                                                            ($data_sesion == '36') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="36" '.$selected.'>36</option>';
                                                            ($data_sesion == '37') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="37" '.$selected.'>37</option>';
                                                            ($data_sesion == '38') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="38" '.$selected.'>38</option>';
                                                            ($data_sesion == '39') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="39" '.$selected.'>39</option>';
                                                            ($data_sesion == '40') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="40" '.$selected.'>40</option>';
                                                            ($data_sesion == '41') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="41" '.$selected.'>41</option>';
                                                            ($data_sesion == '42') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="42" '.$selected.'>42</option>';
                                                            ($data_sesion == '43') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="43" '.$selected.'>43</option>';
                                                            ($data_sesion == '44') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="44" '.$selected.'>44</option>';
                                                            ($data_sesion == '45') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="45" '.$selected.'>45</option>';
                                                            ($data_sesion == '46') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="46" '.$selected.'>46</option>';
                                                            ($data_sesion == '47') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="47" '.$selected.'>47</option>';
                                                            ($data_sesion == '48') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="48" '.$selected.'>48</option>';
                                                            ($data_sesion == '49') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="49" '.$selected.'>49</option>';
                                                            ($data_sesion == '50') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="50" '.$selected.'>50</option>
                                                                  </select>
                                                            </span> &nbsp;&nbsp;&nbsp;';
                                                }
                                                $table .= '<span id="mb'.$type.'"><!--UNIDADES ATERIORES-->
                                                            <!--
                                                            <select id="selr'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disr."'".','."'r'".');" '.$disr.'>
                                                                  <option value=""></option>';
                                                                  ($unitr == 'min') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="min" '.$selected.'>min</option>';
                                                                  ($unitr == 'km') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="km" '.$selected.'>km</option>
                                                            </select>-->
                                                            <!--NUEVAS UNIDADES CON RADIO modificar tambien las funciones que llama onclick-->';
                                                            //AHORA CON CHECKBOX
                                                            ($unitr == 'min') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                                                            ($unitr == 'min') ? $checked = 'checked' : $checked = '';
                                                $table .= ' <label '.$selected.'>min</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disr."'".','."'r'".');" '.$disr.' '.$checked.' '.$checked_r.'/> &nbsp;|&nbsp;';
                                                            ($unitr == 'km') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                                                            ($unitr == 'km') ? $checked = 'checked' : $checked = '';
                                                $table .= ' <label '.$selected.'> km</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disr."'".','."'r'".');" '.$disr.' '.$checked.'/>
                                                            <!---->';
                                          $table .= ' </span> &nbsp;&nbsp;&nbsp;
                                                ';
                                                $table .= '<span id="r_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
                                                           <span id="rtss'.$type.'">'.$tssr_t.'</span>&nbsp; rTSS
                                                            <br>';
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
                                                $unitb = $unitr = '';
                                                if(substr($data_sesion, -1) == 'k'){ $unitb = $unitr = 'km'; }
                                                if(substr($data_sesion, -1) == 'm'){ $unitb = $unitr = 'min'; }
                                                //
                                                if (count($session_parts) >= 3) {
                                                      preg_match('#\((.*?)\)#', $session_parts[2], $match);
                                                      $session_parts[2] = explode('(', $session_parts[2]);
                                                      (isset($match[1])) ? $match[1]=$match[1] : $match[1]='';
                                                      $zbr = $session_parts[2][0];
                                                }else{ $zbr = ""; $match[1]=''; }
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
                                                            ($distance_b == '10') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="10" '.$selected.'>10</option>';
                                                            ($distance_b == '20') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="20" '.$selected.'>20</option>';
                                                            ($distance_b == '30') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                            ($distance_b == '40') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="40" '.$selected.'>40</option>';
                                                            ($distance_b == '50') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="50" '.$selected.'>50</option>';
                                                            ($distance_b == '60') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="60" '.$selected.'>60</option>';
                                                            ($distance_b == '70') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="70" '.$selected.'>70</option>';
                                                            ($distance_b == '80') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="80" '.$selected.'>80</option>';
                                                            ($distance_b == '90') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="90" '.$selected.'>90</option>';
                                                            ($distance_b == '100') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="100" '.$selected.'>100</option>';
                                                            ($distance_b == '110') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="110" '.$selected.'>110</option>';
                                                            ($distance_b == '120') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="120" '.$selected.'>120</option>';
                                                            ($distance_b == '130') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="130" '.$selected.'>130</option>';
                                                            ($distance_b == '140') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="140" '.$selected.'>140</option>';
                                                            ($distance_b == '150') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="150" '.$selected.'>150</option>';
                                                            ($distance_b == '160') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="160" '.$selected.'>160</option>';
                                                            ($distance_b == '170') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="170" '.$selected.'>170</option>';
                                                            ($distance_b == '180') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="180" '.$selected.'>180</option>';
                                                            ($distance_b == '190') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="190" '.$selected.'>190</option>';
                                                            ($distance_b == '200') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="200" '.$selected.'>200</option>';
                                                            ($distance_b == '210') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="210" '.$selected.'>210</option>';
                                                            ($distance_b == '220') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="220" '.$selected.'>220</option>';
                                                            ($distance_b == '230') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="230" '.$selected.'>230</option>';
                                                            ($distance_b == '240') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="240" '.$selected.'>240</option>';
                                                            ($distance_b == '250') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="250" '.$selected.'>250</option>';
                                                            ($distance_b == '260') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="260" '.$selected.'>260</option>';
                                                            ($distance_b == '270') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="270" '.$selected.'>270</option>';
                                                            ($distance_b == '280') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="280" '.$selected.'>280</option>';
                                                            ($distance_b == '290') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="290" '.$selected.'>290</option>';
                                                            ($distance_b == '300') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="300" '.$selected.'>300</option>';
                                                            ($distance_b == '310') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="310" '.$selected.'>310</option>';
                                                            ($distance_b == '320') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="320" '.$selected.'>320</option>';
                                                            ($distance_b == '330') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="330" '.$selected.'>330</option>';
                                                            ($distance_b == '340') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="340" '.$selected.'>340</option>';
                                                            ($distance_b == '350') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="350" '.$selected.'>350</option>';
                                                            ($distance_b == '360') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="360" '.$selected.'>360</option>';
                                                            ($distance_b == '370') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="370" '.$selected.'>370</option>';
                                                            ($distance_b == '380') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="380" '.$selected.'>380</option>';
                                                            ($distance_b == '390') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="390" '.$selected.'>390</option>';
                                                            ($distance_b == '400') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="400" '.$selected.'>400</option>
                                                                  </select>
                                                      ';
                                                }else{
                                                      $table .= '<select class="input_box" id="b'.$type.'" onchange="change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');">
                                                                        <option value="">0</option>';
                                                            ($distance_b == '5') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="5" '.$selected.'>5</option>';
                                                            ($distance_b == '10') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="10" '.$selected.'>10</option>';
                                                            ($distance_b == '15') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="15" '.$selected.'>15</option>';
                                                            ($distance_b == '20') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="20" '.$selected.'>20</option>';
                                                            ($distance_b == '25') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="25" '.$selected.'>25</option>';
                                                            ($distance_b == '30') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                            ($distance_b == '35') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="35" '.$selected.'>35</option>';
                                                            ($distance_b == '40') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="40" '.$selected.'>40</option>';
                                                            ($distance_b == '45') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="45" '.$selected.'>45</option>';
                                                            ($distance_b == '50') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="50" '.$selected.'>50</option>';
                                                            ($distance_b == '55') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="55" '.$selected.'>55</option>';
                                                            ($distance_b == '60') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="60" '.$selected.'>60</option>';
                                                            ($distance_b == '65') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="65" '.$selected.'>65</option>';
                                                            ($distance_b == '70') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="70" '.$selected.'>70</option>';
                                                            ($distance_b == '75') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="75" '.$selected.'>75</option>';
                                                            ($distance_b == '80') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="80" '.$selected.'>80</option>';
                                                            ($distance_b == '85') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="85" '.$selected.'>85</option>';
                                                            ($distance_b == '90') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="90" '.$selected.'>90</option>';
                                                            ($distance_b == '95') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="95" '.$selected.'>95</option>';
                                                            ($distance_b == '100') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="100" '.$selected.'>100</option>';
                                                            ($distance_b == '105') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="105" '.$selected.'>105</option>';
                                                            ($distance_b == '110') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="110" '.$selected.'>110</option>';
                                                            ($distance_b == '115') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="115" '.$selected.'>115</option>';
                                                            ($distance_b == '120') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="120" '.$selected.'>120</option>';
                                                            ($distance_b == '125') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="125" '.$selected.'>125</option>';
                                                            ($distance_b == '130') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="130" '.$selected.'>130</option>';
                                                            ($distance_b == '135') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="135" '.$selected.'>135</option>';
                                                            ($distance_b == '140') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="140" '.$selected.'>140</option>';
                                                            ($distance_b == '145') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="145" '.$selected.'>145</option>';
                                                            ($distance_b == '150') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="150" '.$selected.'>150</option>';
                                                            ($distance_b == '155') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="155" '.$selected.'>155</option>';
                                                            ($distance_b == '160') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="160" '.$selected.'>160</option>';
                                                            ($distance_b == '165') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="165" '.$selected.'>165</option>';
                                                            ($distance_b == '170') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="170" '.$selected.'>170</option>';
                                                            ($distance_b == '175') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="175" '.$selected.'>175</option>';
                                                            ($distance_b == '180') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="180" '.$selected.'>180</option>';
                                                            ($distance_b == '185') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="185" '.$selected.'>185</option>';
                                                            ($distance_b == '190') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="190" '.$selected.'>190</option>';
                                                            ($distance_b == '195') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="195" '.$selected.'>195</option>';
                                                            ($distance_b == '200') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="200" '.$selected.'>200</option>';
                                                            ($distance_b == '205') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="205" '.$selected.'>205</option>';
                                                            ($distance_b == '210') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="210" '.$selected.'>210</option>';
                                                            ($distance_b == '215') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="215" '.$selected.'>215</option>';
                                                            ($distance_b == '220') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="220" '.$selected.'>220</option>';
                                                            ($distance_b == '225') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="225" '.$selected.'>225</option>';
                                                            ($distance_b == '230') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="230" '.$selected.'>230</option>';
                                                            ($distance_b == '235') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="235" '.$selected.'>235</option>';
                                                            ($distance_b == '240') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="240" '.$selected.'>240</option>';
                                                            ($distance_b == '245') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="245" '.$selected.'>245</option>';
                                                            ($distance_b == '250') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="250" '.$selected.'>250</option>
                                                                  </select>
                                                      ';
                                                }
                                                $table .= '  </span> &nbsp;&nbsp;&nbsp;';
                                                $table .= ' <span id="mb'.$type.'">';
                                                            //AHORA CON CHECKBOX
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
                                                                  ($distance_r === '10') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="10" '.$selected.'>10</option>';
                                                                  ($distance_r === '20') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="20" '.$selected.'>20</option>';
                                                                  ($distance_r === '30') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                                  ($distance_r === '40') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="40" '.$selected.'>40</option>';
                                                                  ($distance_r === '50') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="50" '.$selected.'>50</option>';
                                                                  ($distance_r === '60') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="60" '.$selected.'>60</option>';
                                                                  ($distance_r === '70') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="70" '.$selected.'>70</option>';
                                                                  ($distance_r === '80') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="80" '.$selected.'>80</option>';
                                                                  ($distance_r === '90') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="90" '.$selected.'>90</option>';
                                                                  ($distance_r === '100') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="100" '.$selected.'>100</option>';
                                                                  ($distance_r === '110') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="110" '.$selected.'>110</option>';
                                                                  ($distance_r === '120') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="120" '.$selected.'>120</option>';
                                                                  ($distance_r === '130') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="130" '.$selected.'>130</option>';
                                                                  ($distance_r === '140') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="140" '.$selected.'>140</option>';
                                                                  ($distance_r === '150') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="150" '.$selected.'>150</option>';
                                                                  ($distance_r === '160') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="160" '.$selected.'>160</option>';
                                                                  ($distance_r === '170') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="170" '.$selected.'>170</option>';
                                                                  ($distance_r === '180') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="180" '.$selected.'>180</option>';
                                                                  ($distance_r === '190') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="190" '.$selected.'>190</option>';
                                                                  ($distance_r === '200') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="200" '.$selected.'>200</option>
                                                            </select>';
                                                }else{
                                                      $table .= '<span id="sel_cant_r'.$type.'">
                                                                  <select class="input_box" id="r'.$type.'" onchange="change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');" >
                                                                        <option value="">0</option>';
                                                            ($distance_r == '1') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="1" '.$selected.'>1</option>';
                                                            ($distance_r == '2') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="2" '.$selected.'>2</option>';
                                                            ($distance_r == '3') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="3" '.$selected.'>3</option>';
                                                            ($distance_r == '4') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="4" '.$selected.'>4</option>';
                                                            ($distance_r == '5') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="5" '.$selected.'>5</option>';
                                                            ($distance_r == '6') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="6" '.$selected.'>6</option>';
                                                            ($distance_r == '7') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="7" '.$selected.'>7</option>';
                                                            ($distance_r == '8') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="8" '.$selected.'>8</option>';
                                                            ($distance_r == '9') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="9" '.$selected.'>9</option>';
                                                            ($distance_r == '10') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="10" '.$selected.'>10</option>';
                                                            ($distance_r == '11') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="11" '.$selected.'>11</option>';
                                                            ($distance_r == '12') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="12" '.$selected.'>12</option>';
                                                            ($distance_r == '13') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="13" '.$selected.'>13</option>';
                                                            ($distance_r == '14') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="14" '.$selected.'>14</option>';
                                                            ($distance_r == '15') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="15" '.$selected.'>15</option>';
                                                            ($distance_r == '16') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="16" '.$selected.'>16</option>';
                                                            ($distance_r == '17') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="17" '.$selected.'>17</option>';
                                                            ($distance_r == '18') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="18" '.$selected.'>18</option>';
                                                            ($distance_r == '19') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="19" '.$selected.'>19</option>';
                                                            ($distance_r == '20') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="20" '.$selected.'>20</option>';
                                                            ($distance_r == '21') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="21" '.$selected.'>21</option>';
                                                            ($distance_r == '22') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="22" '.$selected.'>22</option>';
                                                            ($distance_r == '23') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="23" '.$selected.'>23</option>';
                                                            ($distance_r == '24') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="24" '.$selected.'>24</option>';
                                                            ($distance_r == '25') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="25" '.$selected.'>25</option>';
                                                            ($distance_r == '26') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="26" '.$selected.'>26</option>';
                                                            ($distance_r == '27') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="27" '.$selected.'>27</option>';
                                                            ($distance_r == '28') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="28" '.$selected.'>28</option>';
                                                            ($distance_r == '29') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="29" '.$selected.'>29</option>';
                                                            ($distance_r == '30') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                            ($distance_r == '31') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="31" '.$selected.'>31</option>';
                                                            ($distance_r == '32') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="32" '.$selected.'>32</option>';
                                                            ($distance_r == '33') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="33" '.$selected.'>33</option>';
                                                            ($distance_r == '34') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="34" '.$selected.'>34</option>';
                                                            ($distance_r == '35') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="35" '.$selected.'>35</option>';
                                                            ($distance_r == '36') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="36" '.$selected.'>36</option>';
                                                            ($distance_r == '37') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="37" '.$selected.'>37</option>';
                                                            ($distance_r == '38') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="38" '.$selected.'>38</option>';
                                                            ($distance_r == '39') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="39" '.$selected.'>39</option>';
                                                            ($distance_r == '40') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="40" '.$selected.'>40</option>';
                                                            ($distance_r == '41') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="41" '.$selected.'>41</option>';
                                                            ($distance_r == '42') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="42" '.$selected.'>42</option>';
                                                            ($distance_r == '43') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="43" '.$selected.'>43</option>';
                                                            ($distance_r == '44') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="44" '.$selected.'>44</option>';
                                                            ($distance_r == '45') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="45" '.$selected.'>45</option>';
                                                            ($distance_r == '46') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="46" '.$selected.'>46</option>';
                                                            ($distance_r == '47') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="47" '.$selected.'>47</option>';
                                                            ($distance_r == '48') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="48" '.$selected.'>48</option>';
                                                            ($distance_r == '49') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="49" '.$selected.'>49</option>';
                                                            ($distance_r == '50') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="50" '.$selected.'>50</option>
                                                                  </select>
                                                            </span> &nbsp;&nbsp;&nbsp;
                                                      ';
                                                }
                                                $table .= ' </span> &nbsp;&nbsp;&nbsp;';
                                                $table .= ' <span id="mr'.$type.'">';
                                                            //AHORA CON CHECKBOX
                                                                        ($unitr == 'min') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                                                                        ($unitr == 'min') ? $checked = 'checked' : $checked = '';
                                                            $table .= ' <label '.$selected.'>min</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');" disabled '.$checked.'/> |';
                                                                        ($unitr == 'km') ? $selected = 'style="color:#0DA5AB;"' : $selected = '';
                                                                        ($unitr == 'km') ? $checked = 'checked' : $checked = '';
                                                            $table .= ' <label '.$selected.'> km</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'BR'".', '.$try.'); build_session_individual(1,'.$type.','."'BR'".','.$try.');" disabled '.$checked.'/>';

                                                $table .= ' </span> &nbsp;&nbsp;&nbsp;
                                                            <span id="r_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
                                                            <span id="rtss'.$type.'">'.$tssr_t.'</span>&nbsp; rTSS
                                                            <br>
                                                            '.
                                                            //Finish zones from DB
                                                            //Start personalized zones
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
                                                            //Finish personalized zones
                                                            $match[1] = str_replace('ppuunnttoo', '.', $match[1]);
                                                            $table .= '
                                                            <br>Observación:
                                                            <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'BR'".','.$try.'); return false;" value="'.$match[1].'">
                                                      </center>';
                                                break;
                                          case 'F':
                                                $data_sesion = $session_parts[1];
                                                $data_sesion = substr($data_sesion, 0, -1);
                                                $option = $identi = '';
                                                if (count($session_parts) >= 3) {
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
                                                }
                                                $table .= 'Parámetros de F:<br>';
                                                $table .= '
                                                      <center>
                                                            <!--&nbsp;&nbsp;&nbsp;&nbsp;-->
                                                            Opción: <span style="color:red;">* </span><br>
                                                            <!--<select id="force_option'.$type.'" onchange="build_session_individual(1,'.$type.','."'".$discipline."'".','."'0'".'); return false;">
                                                                  <option value=""></option>';
                                                                  ($option == 'Core') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="Core" '.$selected.'>Core</option>';
                                                                  ($option == 'Pierna') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="Pierna" '.$selected.'>Pierna</option>';
                                                                  ($option == 'Superior') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="Superior" '.$selected.'>Superior</option>';
                                                                  ($option == 'General') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="General">General</option>
                                                            </select>-->';
                                                            //CON CHECKBOX
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
                                                $table .= ' <br>
                                                            &nbsp;&nbsp;
                                                            Duración: <span style="color:red;">* </span>
                                                            <select id="force_time'.$type.'" style="width:50px;" onchange="build_session_individual(1,'.$type.','."'".$discipline."'".','."'0'".'); return false;">
                                                                  <option value=""></option>';
                                                                  ($data_sesion == '10') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="10" '.$selected.'>10</option>';
                                                                  ($data_sesion == '20') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="20" '.$selected.'>20</option>';
                                                                  ($data_sesion == '30') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                                  ($data_sesion == '40') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="40" '.$selected.'>40</option>';
                                                                  ($data_sesion == '50') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="50" '.$selected.'>50</option>';
                                                                  ($data_sesion == '60') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="60" '.$selected.'>60</option>';
                                                                  ($data_sesion == '70') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="70" '.$selected.'>70</option>';
                                                                  ($data_sesion == '80') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="80" '.$selected.'>80</option>';
                                                                  ($data_sesion == '90') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="90" '.$selected.'>90</option>';
                                                                  ($data_sesion == '100') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="100" '.$selected.'>100</option>';
                                                                  ($data_sesion == '110') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="110" '.$selected.'>110</option>';
                                                                  ($data_sesion == '120') ? $selected = 'selected' : $selected = '';
                                                      $table .= ' <option value="120" '.$selected.'>120</option>
                                                            </select> min
                                                            <br>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            Identificador: <span style="color:red;">* </span>
                                                            <input type="text" id="force_id'.$type.'" maxlength="20"  value="'.$identi.'" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','."'0'".'); return false;">
                                                            <br>
                                                      </center>';
                                                break;
                                          case 'XT':
                                                //
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
                                                                  ($data_sesion == '15') ? $selected = 'selected' : $selected ='';
                                                      $table .= ' <option value="15" '.$selected.'>15</option>';
                                                                  ($data_sesion == '30') ? $selected = 'selected' : $selected ='';
                                                      $table .= ' <option value="30" '.$selected.'>30</option>';
                                                                  ($data_sesion == '45') ? $selected = 'selected' : $selected ='';
                                                      $table .= ' <option value="45" '.$selected.'>45</option>';
                                                                  ($data_sesion == '60') ? $selected = 'selected' : $selected ='';
                                                      $match[1] = str_replace('ppuunnttoo', '.', $match[1]);
                                                      $table .= ' <option value="60" '.$selected.'>60</option>
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
            $table .= ' </div> </td> </tr>
                              <tr> <td> <div id="errors_popup_'.$type.'"></div> </td> </tr>';
            $table .= '</table>
                       </div>';
            $table .= ' <center>
                              <p class="stdformbutton">
                                    <a href="#" class="btn '.$class_button.'" id="btn_box_'.$type.'" onclick="session_modify_values_master_individual('."'".$id_input."'".', '."'".$row."'".','.$type.'); return false;">Actualizar</a>
                                    <a class="btn btn-default" type="reset" onclick="close_popup('.$type.'); return false;" >Cancelar</a>
                              </p>
                        </center>
                        <center>
                              <a class="manual_input" href="#" style="text-align: right;" onclick="load_manual_session_form('.$type.'); return false;">Editar Manual</a>';
                              if ($type == 3) {
                                    $table .= ' 
                                          <span>
                                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                          </span>
                                          <a class="manual_input" href="#" style="text-align: right;" onclick="clear_session('."'".$id_input."'".', '.$type.', '."'".$row."'".'); return false;"> Borrar Sesión</a>';
                              }else{
                                    $table .= ' <span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span>
                                                      <a class="manual_input" href="#" style="text-align: center;" onclick="clear_session('."'".$id_input."'".', '.$type.', '."'".$row."'".'); return false;"> Borrar Sesión</a>
                                                <span> &nbsp; &nbsp; &nbsp; &nbsp; </span>
                                                      <a class="manual_input" href="#" style="text-align: right;" onclick="clear_sessions_all_column('."'".$id_input."'".', '.$type.', '."'".$row."'".'); return false;"> Borrar Columna</a>';
                              }
            $table .= ' </center>';
            return $type.'|&|'.$table;
      }
      //////////////
      function calculate_values_session($row, $sessions, $type){//row num, sessions String separated by @&@, Type 0 summary 1 master
            $times = $session_data = $results = '';
            $tss = new tss_vars();
            $sessions = explode('@&@', $sessions);//Todas las sessiones obtenidas de la fila
            for ($i=0; $i < count($sessions); $i++) {
                  $session_data = session_interpretation($sessions[$i]);//se separan todas las sessiones en cantidades de c/u
                  $times .= $session_data.'||';
            }
            $times = substr($times, 0, -2);
            $times = explode('||', $times);
            for ($i=0; $i < count($times); $i++) { 
                  $session_data = session_calculate($times[$i]);//cantS-unit, cantB-unit, cantR-unit //Se calculan los tss con las cantidades
                  $results .= $session_data.'|=|';
            }
            $results = substr($results, 0, -3);
            $results = explode('|=|', $results);//Se separan todos los tss para sumarlos por disciplina
            for ($i=0; $i < count($results); $i++) { 
                  $all_tss = explode(',', $results[$i]);//Se dividen cada tss por disciplina para obtener un total

                  $tss->tsss = $tss->tsss + $all_tss[0];
                  $tss->tssb = $tss->tssb + $all_tss[1];
                  $tss->tssr = $tss->tssr + $all_tss[2];
            }
            $tss->row = $row;
            return $type.'&&&'.json_encode($tss);//.'&&&.summary_session_'.$row;
      }
      //////////////
      function desintegrate_session($row, $session, $id){
            $values = session_interpretation($session);//Regresa cantS-unit, cantB-unit, cantR-unit 
            $session_data = session_calculate($values);
            $all_tss = explode(',', $session_data);//Se dividen cada tss por disciplina para obtener un total
            $values = explode(',', $values);
            for ($i=0; $i < count($values); $i++) { $values[$i] = explode('-', $values[$i]); }
            $all_values = new tss_vars();
            $all_values->row = str_replace('session', '', $id).$row.'_c';
            $all_values->cans = $values[0][0];
            $all_values->canb = $values[1][0];
            $all_values->canr = $values[2][0];
            $all_values->unis = $values[0][1];
            $all_values->unib = $values[1][1];
            $all_values->unir = $values[2][1];
            $all_values->tsss = $all_tss[0];
            $all_values->tssb = $all_tss[1];
            $all_values->tssr = $all_tss[2];
            return '2&&&'.json_encode($all_values);
      }
      //////////////
      function save_all_data_db($opc, $id_event,$id_level,$num_weeks, $name_event,$type_master,$notes_event, $cicles_weeks,$lrs,$weeks,$sessions,$cants,$units,$tsss, $type_event, $orig_assig){//Donde $type_event 1-Master y 0-Evento
      //id_event(DB) ,id_level(DB) ,num_weeks,cicles_weeks,lrs,weeks,sessions(cadena con todos),cants(cadena con todos),units(cadena con todos),tsss(cadena con todos)
            $num_weeks_aux = 0;
            ($type_master == '11' || $type_master == '12' || $type_master == '13') ? $num_weeks_aux = $num_weeks : $num_weeks_aux = ($num_weeks-1);
      	switch ($opc) {
      		case '1': ////Insertara primero el evento, despues le agregara las sessioes
      			$master_exist_aux = $assignment_exist_aux = '';
                        $master_exist = select_gral('`Events`',
                                                    'id_e',
                                                    'name_e="'.$name_event.'" AND weeks="'.$num_weeks_aux/*$num_weeks*/.'" AND type="'.$type_master.'" AND master="1"',
                                                    'id_e');
      			if($master_exist == true) { foreach ($master_exist as $key) { $master_exist_aux = $key[0]; } }
                        if($master_exist_aux == '') {
                              if ($type_master == '11' || $type_master == '12' || $type_master == '13') {//PARA MASTER CIRCULARES
                                    $id_event = insert_gral('`Events`',
                                                            'name_e, date_e, weeks, type, master',
                                                            '"'.$name_event.'","'.date("Y-m-d", strtotime('sunday this week')).'","'.$num_weeks_aux.'","'.$type_master.'","1"');
### SI SE AGREGA UN TEMPLATE ALTERNO SE SUSTITUYE ESTE POR EL QUE PREVIAMENTE ESTABA ASIGNADO
                        if ($orig_assig != 0 && $orig_assig != "0") {
                              $orig_assig = explode(",", $orig_assig);
                              $updt_assig = update_gral('`Assignments`','id_m="'.$id_event.'"',
                                    'id_e="'.$orig_assig[0].'" AND level_e="'.$orig_assig[1].'" AND id_m="'.$orig_assig[2].'" AND level_m="'.$orig_assig[3].'"');
                              $insert_tt = insert_gral('`Temporal_templates`','id_m','"'.$id_event.'"');
                        }
                                    $assignment_exist = select_gral('`Assignments`', 'id_a',
                                                                    'id_e="'.$id_event.'" AND level_e="'.$id_level.'" AND id_m="'.$id_event.'" AND level_m="'.$id_level.'"', 'id_a');
                                    if ($assignment_exist == true) { foreach ($assignment_exist as $ass_ex) { $assignment_exist_aux = $ass_ex[0]; } }
                                    if ($assignment_exist_aux == '') {
                                          //Una asignacion por cada nivel (BAJA, MEDIA, ALTA, MUY ALTA, MUY BAJA)
                                         $algo = insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event.'","1","'.$id_event.'","1"');
                                         $algo = insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event.'","2","'.$id_event.'","2"');
                                         $algo = insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event.'","3","'.$id_event.'","3"');
                                         $algo = insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event.'","4","'.$id_event.'","4"');
                                         $algo = insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event.'","5","'.$id_event.'","5"');
                                         $algo = insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event.'","6","'.$id_event.'","6"');
                                    }
                              }else{
                                    $id_event = insert_gral('`Events`',
                                                            'name_e, weeks, type, master',
                                                            '"'.$name_event.'","'.$num_weeks_aux.'","'.$type_master.'","1"');
### SI SE AGREGA UN TEMPLATE ALTERNO SE SUSTITUYE ESTE POR EL QUE PREVIAMENTE ESTABA ASIGNADO
                                    if ($orig_assig != 0 && $orig_assig != "0") {
                                          $orig_assig = explode(",", $orig_assig);
                                          $updt_assig = update_gral('`Assignments`','id_m="'.$id_event.'"',
                                                'id_e="'.$orig_assig[0].'" AND level_e="'.$orig_assig[1].'" AND id_m="'.$orig_assig[2].'" AND level_m="'.$orig_assig[3].'"');
                                          $insert_tt = insert_gral('`Temporal_templates`','id_m','"'.$id_event.'"');
                                    }
                              }
                        }else{ $id_event = $master_exist_aux; }
      			break;
      		case '2': ////Se insertaran las sessiones a un evento que ya existe pero no tiene sessiones
                        if ($type_event == 0) {//Si es un Evento Temporal
                              $id_event_temp = insert_gral('`Events`',
                                                      'name_e, date_e, weeks, type, master',
                                                      '"'.$name_event.'","'.date("Y-m-d", strtotime('sunday this week')).'","'.$num_weeks_aux.'","'.$type_master.'","1"');
                              $assignment_exist = select_gral('`Assignments`', 'id_a',
                                                              'id_e="'.$id_event_temp.'" AND level_e="'.$id_level.'" AND id_m="'.$id_event_temp.'" AND level_m="'.$id_level.'"', 'id_a');
                              $assignment_exist_aux = '';
                              if (!empty($assignment_exist)) { foreach ($assignment_exist as $ass_ex) { $assignment_exist_aux = $ass_ex[0]; } }
                              if ($assignment_exist_aux == '') { //Una asignacion por cada nivel (BAJA, MEDIA, ALTA, MUY ALTA y MUY BAJA)
                                   $algo = insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event_temp.'","'.$id_level.'","'.$id_event_temp.'","'.$id_level.'"');
                              }
                              # Checamos que no este la asignación del temporal con el evento
                              $assignment_exist = select_gral('`Assignments`', 'id_a',
                                                              'id_e="'.$id_event.'" AND level_e="'.$id_level.'" AND id_m="'.$id_event_temp.'" AND level_m="'.$id_level.'"', 'id_a');
                              if ($assignment_exist == true) { foreach ($assignment_exist as $ass_ex) { $assignment_exist_aux = $ass_ex[0]; } }
                              if ($assignment_exist_aux == '') { //Se asigna Temporal a Evento en el mismo nivel
                                   $algo = insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event.'","'.$id_level.'","'.$id_event_temp.'","'.$id_level.'"');
                              }
                              $some = insert_gral('`Events_temp`','event,level','"'.$id_event.'","'.$id_level.'"');
                              $some = insert_gral('`Events_temp`','event,level','"'.$id_event_temp.'","'.$id_level.'"');
                              $id_event = $id_event_temp;
                        }
      			break;
			case '3': ////Se eliminaran las sessiones del evento y se agregarán nuevas
      			$delete_sessions = delete_gral('`Trainings`', 'event = "'.$id_event.'" AND level = "'.$id_level.'" ');
				if($delete_sessions != 1) { $id_event = ''; }
      			break;			
      		default:
      			break;
      	}
            // - - - - Se edita o guardan las notas - - - - //
            $note_exist = '';
            $note_exist_aux = '';
            $id_note = '';
            if ($notes_event != '') {
                  $note_exist = select_gral('`Notes`','id_n','id_e="'.$id_event.'" AND id_l="'.$id_level.'"','id_n');
                  if ($note_exist == true) { foreach ($note_exist as $note) { $note_exist_aux = $note[0]; } }
                  if ($note_exist_aux > 0) { $id_note = update_gral('`Notes`','note="'.$notes_event.'"','id_e="'.$id_event.'" AND id_l="'.$id_level.'"'); }else{ $id_note = insert_gral('`Notes`','id_e, id_l, note','"'.$id_event.'","'.$id_level.'","'.$notes_event.'"'); }
            }
            // - - - - Se edita o guardan las notas - - - - //
            $res = $message = $color = $response = '';
            $cont = 0;
            // session vars
            $id_ssn = $session_des = $unit_s = $unit_b = $unit_r = '';
            $disc_s = $stss = $disc_b = $btss = $disc_r = $rtss = 0;
            // trainings vars 
            $id_trn = $session = $event = $level = $week = $week_cicle = $lookup_ref = $day = $session_num = '';
            $sessions = str_replace(',', '', $sessions);
            $cants    = str_replace(',', '', $cants);
            $units    = str_replace(',', '', $units);
            $tsss     = str_replace(',', '', $tsss);
            try{
                  ///SEPARAMOS CADENA A SEMANAS
                  $sessions_week = explode('@@@', $sessions);
                  $cants_week    = explode('@@@', $cants);
                  $units_week    = explode('@@@', $units);
                  $tsss_week     = explode('@@@', $tsss);
                  $cicles_weeks  = explode(',', $cicles_weeks);
                  $lrs           = explode(',', $lrs);
                  for ($i=0; $i < $num_weeks; $i++) { 
                        //SEPARAMOS A DIA
                        $sessions_day  = explode('|||', $sessions_week[$i]);
                        $cants_day     = explode('|||', $cants_week[$i]);
                        $units_day     = explode('|||', $units_week[$i]);
                        $tsss_day      = explode('|||', $tsss_week[$i]);

                        for ($j=0; $j < 7; $j++) { // 7 es el numero de dias de sessiones
                              ///SEAPRAMOS A SESSION
                              $session_individual = explode('///', $sessions_day[$j]);
                              $cants_individual   = explode('///', $cants_day[$j]);
                              $units_individual   = explode('///', $units_day[$j]);
                              $tsss_individual    = explode('///', $tsss_day[$j]);

                              for ($k=0; $k < 3; $k++) { // 3 Es el numero de sesiones por dia
                                    $cants_individual[$k] = explode('&&&', $cants_individual[$k]);
                                    $units_individual[$k] = explode('&&&', $units_individual[$k]);
                                    $tsss_individual[$k]  = explode('&&&', $tsss_individual[$k]);
                                    ///VARIABLES PARA LA TABLA "Sessions"
                                    // (id_ssn) session_des, disc_s, unit_s, stss, disc_b, unit_b, btss, disc_r, unit_r, rtss
                                    $key_ssn = $key_trn = '';
                                    $session_des = $session_individual[$k];
                                    ($cants_individual[$k][0] != '') ? $disc_s = $cants_individual[$k][0] : $disc_s = 0;
                                    ($cants_individual[$k][1] != '') ? $disc_b = $cants_individual[$k][1] : $disc_b = 0;
                                    ($cants_individual[$k][2] != '') ? $disc_r = $cants_individual[$k][2] : $disc_r = 0;
                                    ($units_individual[$k][0] != '') ? $unit_s = $units_individual[$k][0] : $unit_s = 'mts';
                                    ($units_individual[$k][1] != '') ? $unit_b = $units_individual[$k][1] : $unit_b = 'min';
                                    ($units_individual[$k][2] != '') ? $unit_r = $units_individual[$k][2] : $unit_r = 'min';
                                    ($tsss_individual[$k][0] != '') ? $stss = $tsss_individual[$k][0] : $stss = 0;
                                    ($tsss_individual[$k][1] != '') ? $btss = $tsss_individual[$k][1] : $btss = 0;
                                    ($tsss_individual[$k][2] != '') ? $rtss = $tsss_individual[$k][2] : $rtss = 0;
                                    if ($session_des != '') {//Si la session no esta vacia
                                          $session_des = str_replace('mmaass', '+', $session_des);
                                          $session_des = str_replace('aarroobbaa', '@', $session_des);
                                          $id_ssn = select_gral('`Sessions`', 
                                                                 'id_ssn',
                                                                 "session_desc='".$session_des."' AND disc_s='".$disc_s."' AND unit_s='".$unit_s."' AND stss='".$stss."' AND disc_b='".$disc_b."' AND unit_b='".$unit_b."' AND btss='".$btss."' AND disc_r='".$disc_r."' AND unit_r='".$unit_r."' AND rtss='".$rtss."'",
                                                                 'id_ssn');// tabla, campos, filtros, orden
                                          if($id_ssn == true){ foreach ($id_ssn as $key) { $key_ssn = $key[0]; } }
                                          if ($key_ssn == '') {//La session no existe
                                                $insert_new_session = insert_gral('`Sessions`',
                                                                                  'session_desc, disc_s, unit_s, stss, disc_b, unit_b, btss, disc_r, unit_r, rtss',
                                                                                  '"'.$session_des.'","'.$disc_s.'","'.$unit_s.'","'.$stss.'","'.$disc_b.'","'.$unit_b.'","'.$btss.'","'.$disc_r.'","'.$unit_r.'","'.$rtss.'"');
                                                $key_ssn = $insert_new_session; //echo "Se inserto la session: ".$insert_new_session.'|-|';
                                          }//else{ echo "La session ya existe su ID es: ".$key_ssn.'|-|'; }
                                          ///Variables para la Tabla "Trainings"
                                          // (id_trn) session, event, level, week, week_cicle, lookup_ref, day, session_num
                                          $id_trn = select_gral('`Trainings`',
                                                                'id_trn',
                                                                "session='".$key_ssn."' AND event='".$id_event."' AND level='".$id_level."' AND week='".($i+1)."' AND week_cicle='".$cicles_weeks[$i]."' AND lookup_ref='".$lrs[$i]."' AND day='".($j+1)."' AND session_num='".($k+1)."'",
                                                                'id_trn');
                                          if ($id_trn == true) { foreach ($id_trn as $key) { $key_trn = $key[0]; } }
                                          if ($key_trn == '') {
                                                $insert_new_training = insert_gral('`Trainings`',
                                                                             'session, event, level, week, week_cicle, lookup_ref, day, session_num',
                                                                             '"'.$key_ssn.'","'.$id_event.'","'.$id_level.'","'.($i+1).'","'.$cicles_weeks[$i].'","'.$lrs[$i].'","'.($j+1).'","'.($k+1).'"');
                                                $cont++;
                                          }
                                    }
                              }
                        }
                  }
                  $message = 'Se guardaron las sessiones ('.$cont.' En total)...'; $color = 'green';
                  $response = '1';
            } catch (Mandrill_Error $e) {
                  $message = 'Error al insertar sessiones...'; $color   = 'red';
                  $response = '0';
            }
            $res = '<span style="color:'.$color.';font-size:14px;"><p>'.$message.'</p></span>';
            return $res.'&&&.load&&&'.$id_event;
      }
      //////////////
      function save_all_data_db_confirm($id_event,$id_level){
      	$exist = select_gral('`Trainings`','COUNT(*)','event="'.$id_event.'" AND level="'.$id_level.'"','event');
      	if ($exist == true) { foreach ($exist as $key) { $exist = $key[0]; } }
      	return $exist;
      }
      //////////////
      function save_all_data_db_secundary_confirm($id_event,$id_level,$name_event,$num_weeks,$type){
            $id_temporary = 0;
            $trains_event = 0;
            //Obtenemos info del evento Original
            $event_info = select_gral('`Events`','name_e,date_e,weeks,type','id_e="'.$id_event.'" AND temporary="0"','id_e');
            if ($event_info == true) {
                  foreach ($event_info as $data_event) {
                        $name_e = $data_event[0];  $date_e = $data_event[1];  $weeks_e = $data_event[2];  $type_e = $data_event[3];
                        $weeks_e = ($num_weeks-1);
                  }
            }
            //Revisamos que no exista un evento temporal con las caracteristicas actuales
            $temporary_exist = select_gral('`Events`','id_e','name_e="'.$name_event.'" AND date_e="'.$date_e.'" AND weeks="'.$weeks_e.'" AND type="'.$type_e.'" AND temporary <> "0"','id_e');
            if ($temporary_exist == true) {
                  foreach ($temporary_exist as $id_temporary) {
                        $have_trainings = select_gral('`Trainings`','COUNT(*)','event="'.$id_temporary[0].'" AND level="'.$id_level.'"','event');
                        if ($have_trainings == true) { foreach ($have_trainings as $cant_trains) { $cant_trains = $cant_trains[0]; } }
                        $id_temporary = intval($id_temporary[0]);
                  }
                  $cant_trains = intval($cant_trains);
                  if ($cant_trains > 0) { $trains_event = $cant_trains; } 
            }
            if ($id_temporary == 0) {
                   # Se agrego una revisión para evitar guardar eventos identicos de nombre
                  $exist_evt = select_gral('`Events`','count(*)','name_e ="'.$name_event.'"','name_e');
                  if ($exist_evt == true) { foreach ($exist_evt as $key) { $exist_evt = intval($key[0]); } }else{ $exist_evt = 0; }
                  if ($exist_evt > 0) {
                        $id_temporary = "repeat_name";
                  }
            }
            return $id_temporary.'|@|'.$trains_event.'|@|'.$type;
      }
      //////////////
	function save_all_data_db_secundary_event($option,$id_event,$id_level,$name_event,$cicles_weeks,$id_event_t,$sessions,$num_weeks,$notes,$id_template, $id_level_temp){
            //option,id_event,name_event,cicles_weeks,sessions
            //$opc, $id_event,$id_level,$num_weeks, $name_event,$type_master,$notes_event, $cicles_weeks,$lrs,$weeks,$sessions,$cants,$units,$tsss
            // - - - - - - - - - - - - - - - - - - - - - - -
            //Obtenemos info del evento Original
            $event_info = select_gral('`Events`','name_e,date_e,weeks,type','id_e="'.$id_event.'" AND temporary="0"','id_e');
            if ($event_info == true) {
                  foreach ($event_info as $data_event) {
                        $name_e  = $data_event[0];
                        $date_e  = $data_event[1];
                        $weeks_e = $data_event[2];
                        $type_e  = $data_event[3];
                  }
            }
            $weeks_e = $num_weeks;
            # DONDE $option
            # 1 - Se generará nuevo evento y se agregaran trainings
            # 2 - Se agregarán solamente los trainings
            # 3 - Se agregarán y/o sustituiran trainings
            # 4 - Se generará un secundario con un nombre repetido,
            if ($option == 4) {
                  $aux_name = select_gral('`Events`','id_e','name_e="'.$name_event.'"','id_e LIMIT 1');
                  if ($aux_name == true) { foreach ($aux_name as $aux_n) { $aux_name = intval($aux_n[0]); } }
                  if (trim($name_event) == trim($name_e)) { $aux_name = $id_event; } 
                  delete_gral('`Assignments`', 'id_e="'.$aux_name.'"');
                  update_gral('`Events`', 'name_e=CONCAT("_",name_e)', 'id_e="'.$aux_name.'"');
                  #UPDATE Tablename SET Username = Concat('0', Username);
            }
            if ($option == 1 || $option == 4) {
                  $id_event_t = insert_gral('`Events`',
                                            'name_e, date_e, weeks, type, master, temporary, notes',
                                            '"'.$name_event.'","'.$date_e.'","'.($weeks_e-1).'","'.$type_e.'","0","'.$id_event.'","'.$notes.'"');
            }
            //Se verifica si ya esta asignado, si no se asigna el evento temporal - - - -
            $assignment_exist_aux = '';
            $assignment_exist = select_gral('`Assignments`', 'id_a',
                                            'id_e="'.$id_event_t.'" AND level_e="'.$id_level.'" AND id_m="'.$id_event_t.'" AND level_m="'.$id_level.'"', 'id_a');
            if ($assignment_exist == true) { foreach ($assignment_exist as $ass_ex) { $assignment_exist_aux = $ass_ex[0]; } }
            if ($assignment_exist_aux == '') {
                  $algo = insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event_t.'","'.$id_level.'","'.$id_event_t.'","'.$id_level.'"');
            }
            //Termina la verificacion de asignado - - - - - - - - - - - - - - - - - - - -
            //Se obtienes las sesiones a guardar con el evento
            $res = $message = $color = $response = '';
            $cont = $cont_up = $cont_de = 0;
            // session vars
            $id_ssn = $session_des = $unit_s = $unit_b = $unit_r = '';
            $disc_s = $stss = $disc_b = $btss = $disc_r = $rtss = 0;
            // trainings vars 
            $id_trn = $session = $event = $level = $week = $week_cicle = $lookup_ref = $day = $session_num = '';
            $sessions = str_replace(',', '', $sessions);
            try{
                  ///SEPARAMOS CADENA A SEMANAS
                  $sessions_week = explode('@@@', $sessions);
                  $cicles_weeks  = explode(',', $cicles_weeks);
                  for ($i=0; $i < $weeks_e; $i++) { //weeks_e+1 sustituyo a $num_weeks
                        //SEPARAMOS A DIA
                        $sessions_day  = explode('|||', $sessions_week[$i]);
                        for ($j=0; $j < 7; $j++) { // 7 es el numero de dias de sessiones
                              ///SEAPRAMOS A SESSION
                              $session_individual = explode('///', $sessions_day[$j]);
                              for ($k=0; $k < 3; $k++) { // 3 Es el numero de sesiones por dia
                                    $key_ssn = $key_trn = '';
                                    $session_des = $session_individual[$k];
                                    // EVENTO: 6,NIVEL: 3,SEMANA: 1,CICLO SEMANAL: ,LOOKUP REF:,DIA:1,SESSION_NUM:1,SESSION:B.20.Z1,CAN S:0,UNI S:mts,TSS S:0,CAN B:20,UNI B:min,TSS B:23,CAN R:0,UNI R:min,TSS R:0
                                    ///VARIABLES PARA LA TABLA "Sessions"
                                    // (id_ssn) session_des, disc_s, unit_s, stss, disc_b, unit_b, btss, disc_r, unit_r, rtss
                                    if ($session_des != '') {//Si la session no esta vacia
                                          $cants = session_interpretation($session_des);/// return distance_s-units,distance_b-unitb,distance_r-unitr
                                          $cants = explode(',', $cants);
                                          $cants[0] = explode('-', $cants[0]);
                                          $cants[1] = explode('-', $cants[1]);
                                          $cants[2] = explode('-', $cants[2]);
                                          $disc_s = $cants[0][0]; $disc_b = $cants[1][0]; $disc_r = $cants[2][0];
                                          $unit_s = $cants[0][1]; $unit_b = $cants[1][1]; $unit_r = $cants[2][1];
                                          //cantS-unit, cantB-unit, cantR-unit
                                          $tsss = session_calculate($disc_s.'-'.$unit_s.','.$disc_b.'-'.$unit_b.','.$disc_r.'-'.$unit_r); //return $calc_s.','.$calc_b.','.$calc_r;
                                          $tsss = explode(',', $tsss);
                                          $stss = $tsss[0]; $btss = $tsss[1]; $rtss = $tsss[2];
                                          $session_des = str_replace('mmaass', '+', $session_des);
                                          $session_des = str_replace('aarroobbaa', '@', $session_des);
                                          $id_ssn = select_gral('`Sessions`', 
                                                                 'id_ssn',
                                                                 "session_desc='".$session_des."' AND disc_s='".$disc_s."' AND unit_s='".$unit_s."' AND stss='".$stss."' AND disc_b='".$disc_b."' AND unit_b='".$unit_b."' AND btss='".$btss."' AND disc_r='".$disc_r."' AND unit_r='".$unit_r."' AND rtss='".$rtss."'",
                                                                 'id_ssn');// tabla, campos, filtros, orden
                                    
                                          if($id_ssn == true){ foreach ($id_ssn as $key) { $key_ssn = $key[0]; } }
                                          if ($key_ssn == '') {//La session no existe
                                                $insert_new_session = insert_gral('`Sessions`',
                                                                                  'session_desc, disc_s, unit_s, stss, disc_b, unit_b, btss, disc_r, unit_r, rtss',
                                                                                  '"'.$session_des.'","'.$disc_s.'","'.$unit_s.'","'.$stss.'","'.$disc_b.'","'.$unit_b.'","'.$btss.'","'.$disc_r.'","'.$unit_r.'","'.$rtss.'"');
                                                $key_ssn = $insert_new_session; //echo "Se inserto la session: ".$insert_new_session.'|-|';
                                          }
                                          ///Variables para la Tabla "Trainings" // (id_trn) session, event, level, week, week_cicle, lookup_ref, day, session_num
                                          $info_train = $session_train = '';//id_trn
                                          $info_train = select_gral('`Trainings`',
                                                                'id_trn,session',
                                                                "event='".$id_event_t."' AND level='".$id_level."' AND week='".($i+1)."' AND week_cicle='".$cicles_weeks[$i]."' AND day='".($j+1)."' AND session_num='".($k+1)."'",
                                                                'id_trn');
                                          if ($info_train == true) { foreach ($info_train as $key) { $key_trn = $key[0]; $session_train = $key[1]; } }
                                          //Si la consulta retorna vacio, no hay sesion agregada en esa posicion del template (en Trainings)
                                          if ($key_trn == '') {
                                                $insert_new_training = insert_gral('`Trainings`',
                                                                             'session, event, level, week, week_cicle, day, session_num',
                                                                             '"'.$key_ssn.'","'.$id_event_t.'","'.$id_level.'","'.($i+1).'","'.$cicles_weeks[$i].'","'.($j+1).'","'.($k+1).'"');
                                                $cont++;
                                          }else{//Si hay una sesion asignada
                                                if ($session_train != $key_ssn) {
                                                      $update_training = update_gral('`Trainings`',
                                                                                 //'date_e="'.date("Y-m-d", strtotime('sunday this week')).'"',
                                                                                 'session="'.$key_ssn.'"',
                                                                                 "event='".$id_event_t."' AND level='".$id_level."' AND week='".($i+1)."' AND week_cicle='".$cicles_weeks[$i]."' AND day='".($j+1)."' AND session_num='".($k+1)."'");
                                                      $cont_up++;
                                                }
                                          }
                                    }else{
                                          $info_train = $key_trn = '';//id_trn
                                          $info_train = select_gral('`Trainings`',
                                                                'id_trn',
                                                                "event='".$id_event_t."' AND level='".$id_level."' AND week='".($i+1)."' AND week_cicle='".$cicles_weeks[$i]."' AND day='".($j+1)."' AND session_num='".($k+1)."'",
                                                                'id_trn');
                                          if ($info_train == true) { foreach ($info_train as $key) { $key_trn = $key[0]; } }
                                          if ($key_trn != '') {
                                                $delete_train = delete_gral('`Trainings`',
                                                                            "event='".$id_event_t."' AND level='".$id_level."' AND week='".($i+1)."' AND week_cicle='".$cicles_weeks[$i]."' AND day='".($j+1)."' AND session_num='".($k+1)."'");
                                                if ($delete_train == 1) {
                                                      $cont_de++;
                                                }
                                          }
                                    }
                              }
                        }
                  }
                  $message = 'Se guardó la Información ('.$cont.' sesiones nuevas, '.$cont_up.' sesiones modificadas, '.$cont_de.' sesiones eliminadas)...'; $color = 'green';
                  ## Se buscan Sessiones extra de este evento en la tabla, si encuentra las elimina y agrega nuevas
                  $delete_extra_sessions = delete_gral('`Session_notes_extra`', 'id_e="'.$id_event_t.'"');
                  //$extra_notes_temp = select_gral('`Session_notes`', 'id_ssn_n', 'id_e="'..'" AND id_l="'..'"', 'id_ssn_n');
                  $extra_notes_temp = insert_from_query('`Session_notes_extra`', 
                                       'id_ssn_n, id_e',
                                       'SELECT id_ssn_n,"'.$id_event_t.'" FROM `Session_notes` WHERE id_e="'.$id_template.'" AND id_l="'.$id_level_temp.'"');
                  $extra_notes_evnt = insert_from_query('`Session_notes_extra`', 
                                       'id_ssn_n, id_e',
                                       'SELECT id_ssn_n,"'.$id_event_t.'" FROM `Session_notes` WHERE id_e="'.$id_event.'" AND id_l="'.$id_level.'"');
                  //$extra_notes_temp = select_gral('`Session_notes`', 'id_ssn_n', 'id_e="'..'" AND id_l="'..'"', 'id_ssn_n');
                  ## Termina busqueda de sesiones extra
                  $response = '1';
            } catch (Mandrill_Error $e) { $message = 'Error al insertar sessiones...'; $color   = 'red'; $response = '0'; }
            $res = $message;
            return '22@-@'.$res.'@-@'.$id_event_t.','.$id_level.','.$option.','.$id_event.','.$name_event;
      }
      //////////////
	function load_db_sessions_values($weeks, $id_event, $id_level){
		$res = $parametro_ad = '';
        if ($id_level != 0) {
        	$trainings_week = '';
        	$i = $j = $k = $l = 0;
        	$sessions = $cants_s = $units_s = $tsss_s = $cants_b = $units_b = $tsss_b = $cants_r = $units_r = $tsss_r = $cw = $lr = array();
	        $weeks = intval($weeks+2);
        	for ($i=1; $i <= $weeks; $i++) { 
        		$trainings_week = get_sessions_week($id_event, $id_level, $i);
        		//print_r($trainings_week); echo "<.............----------.............------......------......-----....---...>";
        		$l = intval($i-1);
				if ($trainings_week == true) {
					foreach ($trainings_week as $key) {
						$cw[$l]  = $key[0];//cicle week
						$lr[$l]  = $key[1];//lookup reference
						for ($j=2; $j <= 22; $j++) { //Los entrenamientos por semana
							$k = intval($j-2);
							$l = intval($i-1);
							if ($key[$j] != '') {
								$key[$j] = explode('|', $key[$j]);
								//antes j era 0
								$sessions[$l][$k] = $key[$j][0];
								$cants_s[$l][$k]  = $key[$j][1]; $units_s[$l][$k]  = $key[$j][2]; $tsss_s[$l][$k]   = $key[$j][3];
								$cants_b[$l][$k]  = $key[$j][4]; $units_b[$l][$k]  = $key[$j][5]; $tsss_b[$l][$k]   = $key[$j][6];
								$cants_r[$l][$k]  = $key[$j][7]; $units_r[$l][$k]  = $key[$j][8]; $tsss_r[$l][$k]   = $key[$j][9];
							}else{
								$sessions[$l][$k] = '';
								$cants_s[$l][$k]  = ''; $units_s[$l][$k]  = ''; $tsss_s[$l][$k]   = '';
								$cants_b[$l][$k]  = ''; $units_b[$l][$k]  = ''; $tsss_b[$l][$k]   = '';
								$cants_r[$l][$k]  = ''; $units_r[$l][$k]  = ''; $tsss_r[$l][$k]   = '';
							}
						}
					}
				}else{
					$cw[$l]  = '';
					$lr[$l]  = '';
					for ($j=2; $j <= 22; $j++) { //Los entrenamientos por semana
						$k = intval($j-2);
						$l = intval($i-1);
						$sessions[$l][$k] = '';
						$cants_s[$l][$k]  = ''; $units_s[$l][$k]  = ''; $tsss_s[$l][$k]   = '';
						$cants_b[$l][$k]  = ''; $units_b[$l][$k]  = ''; $tsss_b[$l][$k]   = '';
						$cants_r[$l][$k]  = ''; $units_r[$l][$k]  = ''; $tsss_r[$l][$k]   = '';
					}
				}
        	}
        	//print_r($cw); echo "<hr>"; print_r($lr); echo "<hr>"; print_r($sessions); echo "<hr>";
        	$res = json_encode($sessions).'&&&'.json_encode($cants_s).'&&&'.json_encode($units_s).'&&&'.json_encode($tsss_s).'&&&'.json_encode($cants_b).'&&&'.json_encode($units_b).'&&&'.json_encode($tsss_b).'&&&'.json_encode($cants_r).'&&&'.json_encode($units_r).'&&&'.json_encode($tsss_r).'&&&'.json_encode($cw).'&&&'.json_encode($lr).'&&&'.$weeks;//json_encode($tss)
	    }else{
	    	$res = '';
	    }
	    $origen = 2;
	    return $origen.'@-@'.$res.'@-@'.$parametro_ad; //origen@-@response@-@paramtr
	}
      //////////////
	function save_individual_session($id_session, $id_event, $id_level, $notes_event, $info_sess){
#      echo "aqui hay que guardar las sessiones individuales... EVENTO: ".$id_event." NIVEL: ".$id_level." SESSION: ".$id_session;
      $res = 0;
      // - - - - Se edita o guardan las notas - - - - //
      $note_exist = '';
      $note_exist_aux = '';
      $id_note = '';
      if ($notes_event != '') {
            $note_exist = select_gral('`Notes`','id_n','id_e="'.$id_event.'" AND id_l="'.$id_level.'"','id_n');
            if ($note_exist == true) {
                  foreach ($note_exist as $note) {
                        $note_exist_aux = $note[0];
                  }
            }
            if ($note_exist_aux > 0) {
                  $id_note = update_gral('`Notes`','note="'.$notes_event.'"','id_e="'.$id_event.'" AND id_l="'.$id_level.'"');
            }else{
                  $id_note = insert_gral('`Notes`','id_e, id_l, note','"'.$id_event.'","'.$id_level.'","'.$notes_event.'"');
            }
      }
      // - - - - Se edita o guardan las notas - - - - //
      ////// - - - - - - - - - - - - - - - - - - - - - ///////
      //session_des,disc_s,unit_s,stss,disc_b,unit_b,btss,disc_r,unit_r,rtss,cicle_week,lr(valor cualquiera),week,day,session
      //session_desc+'|-|'+disc_s+'|-|'+unit_s+'|-|'+stss+'|-|'+disc_b+'|-|'+unit_b+'|-|'+btss+'|-|'+disc_r+'|-|'+unit_r+'|-|'+rtss+'|-|'+cicle_week+'|-|'+week+'|-|'+day+'|-|'+session
      $insert_new_training = 0;
      $info_sess = explode('|-|', $info_sess);
      $session_des = $info_sess[0];//$info_sess[]
      ($info_sess[1] == '') ? $disc_s = '0' : $disc_s = trim($info_sess[1]);
      ($info_sess[2] == '') ? $unit_s = 'mts' : $unit_s = trim($info_sess[2]);
      ($info_sess[3] == '') ? $stss = '0' : $stss = trim($info_sess[3]);
      ($info_sess[4] == '') ? $disc_b = '0' : $disc_b = trim($info_sess[4]);
      ($info_sess[5] == '') ? $unit_b = 'min' : $unit_b = trim($info_sess[5]);
      ($info_sess[6] == '') ? $btss = '0' : $btss = trim($info_sess[6]);
      ($info_sess[7] == '') ? $disc_r = '0' : $disc_r = trim($info_sess[7]);
      ($info_sess[8] == '') ? $unit_r = 'min' : $unit_r = trim($info_sess[8]);
      ($info_sess[9] == '') ? $rtss = '0' : $rtss = trim($info_sess[9]);

      $cicle_week = trim($info_sess[10]);
      $week = trim($info_sess[11]);
      $day = trim($info_sess[12]);
      $session = trim($info_sess[13]);

      $type_event = trim($info_sess[14]);

      $key_ssn = '';
      if ($session_des == '') {//Session Vacia
            $delete_sessions = delete_gral('`Trainings`',
                                          "event='".$id_event."' AND level='".$id_level."' AND week='".$week."' AND day='".$day."' AND session_num='".$session."'");
            if ($delete_sessions == 1) {
                  $res = 4;
                  # SE ELIMINA LA NOTA DE LA SESION
                  $delete_note = delete_gral('`Session_notes`',
                                    "id_e='".$id_event."' AND id_l='".$id_level."' AND week='".$week."' AND day='".$day."' AND session='".$session."'");
            }else{ $res = 5; }
      }
      if ($session_des != '') {//Si la session no esta vacia
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
                  //Se inserto la session: $insert_new_session
            }//La session ya existe su ID es: $key_ssn
            # SE ACTUALIZA LA NOTA DE LA SESION
            $update_note = update_gral('`Session_notes`',
                              "id_ssn='".$key_ssn."'",
                              "id_e='".$id_event."' AND id_l='".$id_level."' AND week='".$week."' AND day='".$day."' AND session='".$session."'");
            $key_trn = '';
            $id_trn = select_gral('`Trainings`',
                                  'id_trn',
                                  "event='".$id_event."' AND level='".$id_level."' AND week='".$week."' AND day='".$day."' AND session_num='".$session."'",
                                  'id_trn');
            if ($id_trn == true) { foreach ($id_trn as $key) { $key_trn = $key[0]; } }
            $id_training = $key_trn;
            if ($key_trn != '') {
                  $key_trn = $id_trn = '';
                  //Esta session ya tiene valores
                  $id_trn = select_gral('`Trainings`',
                                          'id_trn',
                                          "session='".$key_ssn."' AND event='".$id_event."' AND level='".$id_level."' AND week='".$week."' AND week_cicle='".$cicle_week."' AND day='".$day."' AND session_num='".$session."'",
                                          'id_trn');
                  if ($id_trn == true) { foreach ($id_trn as $key) { $key_trn = $key[0]; } }
                  if ($key_trn == '') {
                        //Hay que modificar esta session por los valores actuales -".$id_training."-";
                        $update_training = update_gral('`Trainings`', "session='".$key_ssn."', week_cicle='".$cicle_week."'", "id_trn='".$id_training."'");
                        $res = 2;
                  }else{ $res = 3; /*Esta session es exactamente igual a la que me muestras*/ }
            }else{//echo "Esta session esta vacia";
                  $insert_new_training = insert_gral('`Trainings`',
                                               'session, event, level, week, week_cicle, day, session_num',
                                               '"'.$key_ssn.'","'.$id_event.'","'.$id_level.'","'.$week.'","'.$cicle_week.'","'.$day.'","'.$session.'"');
                  $res = 1;
            }
      }
      // / / / - - - - - -
      if (($type_event == '11' || $type_event == '12' || $type_event == '13')) {
            $assignment_exist_aux = '';
            $assignment_exist = select_gral('`Assignments`', 'id_a',
                                            'id_e="'.$id_event.'" AND level_e="'.$id_level.'" AND id_m="'.$id_event.'" AND level_m="'.$id_level.'"', 'id_a');
            //print_r($assignment_exist);
            if ($assignment_exist == true) { foreach ($assignment_exist as $ass_ex) { $assignment_exist_aux = $ass_ex[0]; } }
            if ($assignment_exist_aux == '') {
                  insert_gral('`Assignments`','id_e,level_e,id_m,level_m','"'.$id_event.'","'.$id_level.'","'.$id_event.'","'.$id_level.'"');
            }
      }
      // / / / - - - - - -
      //LOG
      $evnt = $l_e = "";
      $evnt = select_gral('`Events`', 'name_e', 'id_e="'.$id_event.'"', 'id_e');
      $l_e  = select_gral('`Levels`', 'name_l', 'id_l="'.$id_level.'"', 'id_l');
      if ($evnt == true) { foreach ($evnt as $key) { $evnt = $key[0]; } }else{ $evnt = $id_event; }
      if ($l_e == true) { foreach ($l_e as $key) { $l_e = $key[0]; } }else{ $l_e = $id_level; }

      if ($res == 1) {#Insertar
            # Activity_code = 6
            $message = "El usuario *".$_SESSION['name_u']."* insertó una nueva sesión en Grupo: *".$evnt."* Nivel: *".$l_e."* Semana: *".$week."* Día: *".$day."* Sesión: *".$session."* ";
            log_mb_register($message, "6");
            #Deleting from summary json sended
            $delete_user = delete_gral('`Summ_sended`', "id_m='".$id_event."' AND week='".$week."'");
      }
      if ($res == 2) {#Actualizar
            # Activity_code = 7
            $message = "El usuario *".$_SESSION['name_u']."* actualizó la sesión del Grupo: *".$evnt."* Nivel: *".$l_e."* Semana: *".$week."* Día: *".$day."* Sesión: *".$session."* ";
            log_mb_register($message, "7");
            #Deleting from summary json sended
            $delete_user = delete_gral('`Summ_sended`', "id_m='".$id_event."' AND week='".$week."'");
      }
      if ($res == 4) {#Eliminar
            # Activity_code = 8
            $message = "El usuario *".$_SESSION['name_u']."* eliminó la sesión del Grupo: *".$evnt."* Nivel: *".$l_e."* Semana: *".$week."* Día: *".$day."* Sesión: *".$session."* ";
            log_mb_register($message, "8");
            #Deleting from summary json sended
            $delete_user = delete_gral('`Summ_sended`', "id_m='".$id_event."' AND week='".$week."'");
      }
      return "6@-@".$res."@-@".$id_session;
      ////// - - - - - - - - - - - - - - - - - - - - - ///////
}
      //////////////
	function show_event_sessions($id_event_e,$id_level_e,$id_template,$id_level_t,$type){//$template_id, $level_id
      $tab1 = $tab2 = '';
      $template_id = $id_template;
      $level_id = $id_level_e;
      $table_trains = '';
      $id_level = $level_id;
      $info_template = select_gral('Events','id_e,name_e,weeks,type','id_e="'.$template_id.'"','id_e');
      if ($info_template == true) { foreach ($info_template as $key) { $id_event = $key[0]; $name_event_new = $key[1]; $weeks_train = $key[2]; $type_master_new = $key[3]; } }
      $info_event = select_gral('Events','name_e,date_e,weeks,type,temporary,master,notes','id_e="'.$id_event_e.'"','id_e');
      if ($info_event == true) {
            foreach ($info_event as $key_e) {
                  $name_event_new  = $key_e[0];
                  $date_event      = $key_e[1];
                  $weeks_train_new = $key_e[2];
                  $type_master_new = $key_e[3];
                  $is_temporary    = intval($key_e[4]);
                  $is_master       = intval($key_e[5]);
                  $notes_temps     = $key_e[6];
            }
      }
      $weeks_train_new = $weeks_train;
      $date_aux = $date_event;
      $date = strtotime($date_event);
      $date = date('l', $date);
      if ($date != 'Sunday') {
            if ($date == 'Monday' || $date == 'Tuesday' || $date == 'Wednesday') {
                  $date = date("Y-m-d", strtotime('last Sunday', strtotime($date_aux)));//last or next
            }
            if ($date == 'Thursday' || $date == 'Friday' || $date == 'Saturday') {
                  $date = date("Y-m-d", strtotime('next Sunday', strtotime($date_aux)));
            }
            $date_event = $date;
      }
      $date_event = strtotime($date_event);
      $firts_date_event = strtotime("-".$weeks_train." week", $date_event);
      $firts_date_event = date('Y-m-d', $firts_date_event);
      $firts_date_event = strtotime($firts_date_event);
      $firts_date_event = strtotime("+1 week", $firts_date_event);
      $charge    = select_gral('Levels', 'name_l', 'id_l = "'.$level_id.'"', 'id_l');
      foreach ($charge as $level_name) { $charge = $level_name[0]; }
      $type_aux = $type_master_new;
      $type_master_new = select_gral('Types', 'name_t', 'id_t = "'.$type_master_new.'"', 'id_t');
      foreach ($type_master_new as $key_type_name) { $type_master_new = $key_type_name[0]; }
      $table = ''; $weeks_template = '';
      $weeks_e = $weeks_train_new;
      $type_e  = $type_master_new;
      $id_pos = $style_input_ses = '';
      $background_color = '#EFFBFB';
      if($type_aux == '11' || $type_aux == '12' || $type_aux == '13') {// || $is_temporary > 0
            $weeks_e = intval($weeks_e);
            $style_input_ses = 'disabled';
      }else{ $weeks_e = intval($weeks_e)+1; }
      $is_converted = 0;
      $have_other_master = '';
      $level_master = '';
      $have_other_master = select_gral('`Assignments`', 'id_m, level_m', 'id_e="'.$id_event_e.'" AND level_e="'.$level_id.'"', 'id_m');
      if ($have_other_master == true) {
            foreach ($have_other_master as $key_id_m) {
                  $have_other_master = $key_id_m[0];
                  $level_master      = $key_id_m[1];
            }
      }
      if (intval($id_event_e) != intval($have_other_master)) { $style_input_ses = 'disabled'; $is_converted = 1; }
      if ($is_master > 0) { $style_input_ses = 'disabled'; }
      ///Para saber si hay sessiones en alguna columna///
            $sess01 = $sess02 = $sess03 = $sess04 = $sess05 = $sess06 = $sess07 = 0;
            $sess08 = $sess09 = $sess10 = $sess11 = $sess12 = $sess13 = $sess14 = 0;
            $sess15 = $sess16 = $sess17 = $sess18 = $sess19 = $sess20 = $sess21 = 0;
      ///Termina para saber si hay sessiones en columnas///
      $date_event_aux = '';
      $weeks_template = '<input id="weeks_temp" type="hidden" value="'.$weeks_e.'" />';
        for ($i=1; $i <= $weeks_e; $i++) {// una semana que no me mostraba y la semana de descanso
            ///Weeks date
            $date_event_aux = strtotime("+".($i-1)." week", $firts_date_event);
            $date_event_aux = date('d M', $date_event_aux);
            ///finish weeks date
            ///////Vaciamos datos de DB/////////
            $session01 = $session02 = $session03 = $session04 = $session05 = $session06 = $session07 = '';
            $session08 = $session09 = $session10 = $session11 = $session12 = $session13 = $session14 = '';
            $session15 = $session16 = $session17 = $session18 = $session19 = $session20 = $session21 = '';
            $cants_s = $units_s = $tsss_s = $cants_b = $units_b = $tsss_b = $cants_r = $units_r = $tsss_r = $cw = $lr = $all_tss = '';
            $tsss = $tssb = $tssr = 0;
            $res1 = 0;
            $summary_values = '';
            if ($id_event != 0) {
                  $trainings_week = '';
                  $trainings_week = get_sessions_week($id_event, $id_level_t, $i);
                  if ($trainings_week == true) {
                        foreach ($trainings_week as $key) {
                              $cw = $key[0];
                              $lr = $key[1];
                              $key[2] = explode('|', $key[2]); $session01 = $key[2][0];
                              if (count($key[2])==1) { $key[2][3] = 0; $key[2][6] = 0; $key[2][9] = 0; }
                              $key[3] = explode('|', $key[3]); $session02 = $key[3][0];
                              if (count($key[3])==1) { $key[3][3] = 0; $key[3][6] = 0; $key[3][9] = 0; }
                              $key[4] = explode('|', $key[4]); $session03 = $key[4][0];
                              if (count($key[4])==1) { $key[4][3] = 0; $key[4][6] = 0; $key[4][9] = 0; }
                              $key[5] = explode('|', $key[5]); $session04 = $key[5][0];
                              if (count($key[5])==1) { $key[5][3] = 0; $key[5][6] = 0; $key[5][9] = 0; }
                              $key[6] = explode('|', $key[6]); $session05 = $key[6][0];
                              if (count($key[6])==1) { $key[6][3] = 0; $key[6][6] = 0; $key[6][9] = 0; }
                              $key[7] = explode('|', $key[7]); $session06 = $key[7][0];
                              if (count($key[7])==1) { $key[7][3] = 0; $key[7][6] = 0; $key[7][9] = 0; }
                              $key[8] = explode('|', $key[8]); $session07 = $key[8][0];
                              if (count($key[8])==1) { $key[8][3] = 0; $key[8][6] = 0; $key[8][9] = 0; }
                              $key[9] = explode('|', $key[9]); $session08 = $key[9][0];
                              if (count($key[9])==1) { $key[9][3] = 0; $key[9][6] = 0; $key[9][9] = 0; }
                              $key[10] = explode('|', $key[10]); $session09 = $key[10][0];
                              if (count($key[10])==1) { $key[10][3] = 0; $key[10][6] = 0; $key[10][9] = 0; }
                              $key[11] = explode('|', $key[11]); $session10 = $key[11][0];
                              if (count($key[11])==1) { $key[11][3] = 0; $key[11][6] = 0; $key[11][9] = 0; }
                              $key[12] = explode('|', $key[12]); $session11 = $key[12][0];
                              if (count($key[12])==1) { $key[12][3] = 0; $key[12][6] = 0; $key[12][9] = 0; }
                              $key[13] = explode('|', $key[13]); $session12 = $key[13][0];
                              if (count($key[13])==1) { $key[13][3] = 0; $key[13][6] = 0; $key[13][9] = 0; }
                              $key[14] = explode('|', $key[14]); $session13 = $key[14][0];
                              if (count($key[14])==1) { $key[14][3] = 0; $key[14][6] = 0; $key[14][9] = 0; }
                              $key[15] = explode('|', $key[15]); $session14 = $key[15][0];
                              if (count($key[15])==1) { $key[15][3] = 0; $key[15][6] = 0; $key[15][9] = 0; }
                              $key[16] = explode('|', $key[16]); $session15 = $key[16][0];
                              if (count($key[16])==1) { $key[16][3] = 0; $key[16][6] = 0; $key[16][9] = 0; }
                              $key[17] = explode('|', $key[17]); $session16 = $key[17][0];
                              if (count($key[17])==1) { $key[17][3] = 0; $key[17][6] = 0; $key[17][9] = 0; }
                              $key[18] = explode('|', $key[18]); $session17 = $key[18][0];
                              if (count($key[18])==1) { $key[18][3] = 0; $key[18][6] = 0; $key[18][9] = 0; }
                              $key[19] = explode('|', $key[19]); $session18 = $key[19][0];
                              if (count($key[19])==1) { $key[19][3] = 0; $key[19][6] = 0; $key[19][9] = 0; }
                              $key[20] = explode('|', $key[20]); $session19 = $key[20][0];
                              if (count($key[20])==1) { $key[20][3] = 0; $key[20][6] = 0; $key[20][9] = 0; }
                              $key[21] = explode('|', $key[21]); $session20 = $key[21][0];
                              if (count($key[21])==1) { $key[21][3] = 0; $key[21][6] = 0; $key[21][9] = 0; }
                              $key[22] = explode('|', $key[22]); $session21 = $key[22][0];
                              if (count($key[22])==1) { $key[22][3] = 0; $key[22][6] = 0; $key[22][9] = 0; }

                              $tsss = $key[2][3]+$key[3][3]+$key[4][3]+$key[5][3]+$key[6][3]+$key[7][3]+$key[8][3]+$key[9][3]+$key[10][3]+$key[11][3]+$key[12][3]+$key[13][3]+$key[14][3]+$key[15][3]+$key[16][3]+$key[17][3]+$key[18][3]+$key[19][3]+$key[20][3]+$key[21][3]+$key[22][3];
                              $tssb = $key[2][6]+$key[3][6]+$key[4][6]+$key[5][6]+$key[6][6]+$key[7][6]+$key[8][6]+$key[9][6]+$key[10][6]+$key[11][6]+$key[12][6]+$key[13][6]+$key[14][6]+$key[15][6]+$key[16][6]+$key[17][6]+$key[18][6]+$key[19][6]+$key[20][6]+$key[21][6]+$key[22][6];
                              $tssr = $key[2][9]+$key[3][9]+$key[4][9]+$key[5][9]+$key[6][9]+$key[7][9]+$key[8][9]+$key[9][9]+$key[10][9]+$key[11][9]+$key[12][9]+$key[13][9]+$key[14][9]+$key[15][9]+$key[16][9]+$key[17][9]+$key[18][9]+$key[19][9]+$key[20][9]+$key[21][9]+$key[22][9];

                              $all_tss = 'S: '.$tsss.' | B: '.$tssb.' | R: '.$tssr;
                              if ($tssr > 0) {
                                    $res1 = round(($tssb/$tssr),2);
                              }else{
                                    $res1 = 0;
                              }
                              if ($res1 < 2) { $summary_values = '↑ B/R ↓ → <span id="br_'.$i.'">'.$res1.'</span> | <span id="tp_'.$i.'">'.round(($tsss+$tssb+$tssr)/7).'</span> TSS/día'; }
                              if ($res1 == 2) { $summary_values = 'B/R → <span id="br_'.$i.'">'.$res1.'</span> | <span id="tp_'.$i.'">'.round(($tsss+$tssb+$tssr)/7).'</span> TSS/día'; }
                              if ($res1 > 2) { $summary_values = '↓ B/R ↑ → <span id="br_'.$i.'">'.$res1.'</span> | <span id="tp_'.$i.'">'.round(($tsss+$tssb+$tssr)/7).'</span> TSS/día'; }

                              if ($session01 != '') { $sess01++; } if ($session02 != '') { $sess02++; } if ($session03 != '') { $sess03++; }
                              if ($session04 != '') { $sess04++; } if ($session05 != '') { $sess05++; } if ($session06 != '') { $sess06++; }
                              if ($session07 != '') { $sess07++; } if ($session08 != '') { $sess08++; } if ($session09 != '') { $sess09++; }
                              if ($session10 != '') { $sess10++; } if ($session11 != '') { $sess11++; } if ($session12 != '') { $sess12++; }
                              if ($session13 != '') { $sess13++; } if ($session14 != '') { $sess14++; } if ($session15 != '') { $sess15++; }
                              if ($session16 != '') { $sess16++; } if ($session17 != '') { $sess17++; } if ($session18 != '') { $sess18++; }
                              if ($session19 != '') { $sess19++; } if ($session20 != '') { $sess20++; } if ($session21 != '') { $sess21++; }

                        }
                  }else{
                        $cw = '';
                        $lr = '';
                        $all_tss = '';
                        $summary_values = 'B/R → <span id="br_'.$i.'">0</span> | <span id="tp_'.$i.'">0</span> TSS/día';
                        $session01 = $session02 = $session03 = $session04 = $session05 = $session06 = $session07 = '';
                        $session08 = $session09 = $session10 = $session11 = $session12 = $session13 = $session14 = '';
                        $session15 = $session16 = $session17 = $session18 = $session19 = $session20 = $session21 = '';
                  }
            }else{
                  $sessions = $cants_s = $units_s = $tsss_s = $cants_b = $units_b = $tsss_b = $cants_r = $units_r = $tsss_r = $cw = $lr = '';
                  $summary_values = 'B/R → <span id="br_'.$i.'">0</span> | <span id="tp_'.$i.'">0</span> TSS/día';
            }
            (intval($id_event_e) == intval($have_other_master)) ? $aditional_function = 'change_cicle_week_temp(this.value,'.$i.');' : $aditional_function = '';
            $aditional_function = 'change_cicle_week_temp(this.value,'.$i.');';
            ////Termina vacio de datos de DB////
            # COMIENZA NOTAS INTENTO 2
            $note01 = $note02 = $note03 = $note04 = $note05 = $note06 = $note07 = $note08 = $note09 = $note10 = $note11 = '';
            $note12 = $note13 = $note14 = $note15 = $note16 = $note17 = $note18 = $note19 = $note20 = $note21 = '';
            $hide = '';
            $type_event_note = '2';
            if ($is_temporary > 0) {# SI ES EVENTO SECUNDRIO
                  $type_event_note = '3';
            }
            $temp_note = $event_note = $sec_note = '';
            //if ($style_input_ses != 'disabled') { # SI LA TABLA NO ES PROTEGIDA (CUANDO NO ES POSIBLE EDITARLA)
                  $style_notes     = 'width:18px; vertical-align: bottom; text-align: right; cursor:pointer; ';//position:absolute;

                  ($i > 9) ? $space = '&nbsp;&nbsp;&nbsp;&nbsp;' : $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                  $image_direction = './img/images/notes_.png';
                  $image_direction = get_direction_imagen($is_temporary, $id_event_e, $id_level_e, $id_template, $id_level_t, $i, "n/a", "n/a");
            # TERMINA NOTAS INTENTO 2
            $table .= '             <tr class="row_'.$i.'" style="background:'.$background_color.';">
                                    <td class="g h">'.$date_event_aux.'</td>
                                    <td class="g"><input type="text" class="row_'.$i.' med cicle_week_'.$i.'" id="cicle_week_'.$i.'" onkeyup="show_value_tables('.$i.', this.value, this.id); change_r_color(this.value,'.$weeks_e.','.$i.','."'none'".'); '.$aditional_function.' return false;" value="'.$cw.'" style="background:'.$background_color.';" '.$style_input_ses.'></td><!--style="width: 80px;"  -->
                                    <!--<td>';
            $table .= '             </td>-->
                                    <td class="summary_session_'.$i.' n_w" id="summary_session_'.$i.'">'.$summary_values.'</td>
                                    <td id="week_num" class="g n_w">Sem '.$i.'</td>
                                    <td class="g h"></td><td class="d03 h"></td>';

            $image_direction = './img/images/notes_.png';
            if ($session01 != '') { $hide = ''; }else{ $hide = 'hidden'; }
            if($hide == ''){$image_direction = get_direction_imagen($is_temporary, $id_event_e, $id_level_e, $id_template, $id_level_t, $i, "1", "1");}
            $function_note = "show_popup_notes(3, '".trim($session01)."', 'session_1_1_".$i."', ".$i.", ".$type_event_note.",0); return false;";
            $note01 = '<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_1_1_'.$i.'" '.$hide.'>';
            $table .= '             <td class="ses1 n_w" style="border-left-width:medium;border-left-color:darkgray;"><input type="text" class="row_'.$i.' session_trains session_1_1_'.$i.'" id="session_1_1_'.$i.'" value="'.$session01.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note01.'</td>';
            $table .= '             <td class="ses2 n_w"><input type="text" class="row_'.$i.' session_trains session_2_1_'.$i.'" id="session_2_1_'.$i.'" value="'.$session02.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note02.'</td>';
            $table .= '             <td class="ses3 n_w"><input type="text" class="row_'.$i.' session_trains session_3_1_'.$i.'" id="session_3_1_'.$i.'" value="'.$session03.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note03.'</td>';

            $image_direction = './img/images/notes_.png';
            if ($session04 != '') { $hide = ''; }else{ $hide = 'hidden'; }
            if($hide == ''){$image_direction = get_direction_imagen($is_temporary, $id_event_e, $id_level_e, $id_template, $id_level_t, $i, "2", "1");}
            $function_note = "show_popup_notes(3, '".trim($session04)."', 'session_1_2_".$i."', ".$i.", ".$type_event_note.",0); return false;";
            $note04 = '<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_1_2_'.$i.'" '.$hide.'>';
            $table .= '             <td class="ses4 n_w" style="border-left-width:medium;border-left-color:darkgray;"><input type="text" class="row_'.$i.' session_trains session_1_2_'.$i.'" id="session_1_2_'.$i.'" value="'.$session04.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note04.'</td>';
            $table .= '             <td class="ses5 n_w"><input type="text" class="row_'.$i.' session_trains session_2_2_'.$i.'" id="session_2_2_'.$i.'" value="'.$session05.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note05.'</td>';
            $table .= '             <td class="ses6 n_w"><input type="text" class="row_'.$i.' session_trains session_3_2_'.$i.'" id="session_3_2_'.$i.'" value="'.$session06.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note06.'</td>';

            $image_direction = './img/images/notes_.png';
            if ($session07 != '') { $hide = ''; }else{ $hide = 'hidden'; }
            if($hide == ''){$image_direction = get_direction_imagen($is_temporary, $id_event_e, $id_level_e, $id_template, $id_level_t, $i, "3", "1");}
            $function_note = "show_popup_notes(3, '".trim($session07)."', 'session_1_3_".$i."', ".$i.", ".$type_event_note.",0); return false;";
            $note07 = '<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_1_3_'.$i.'" '.$hide.'>';
            $table .= '             <td class="ses7 n_w" style="border-left-width:medium;border-left-color:darkgray;"><input type="text" class="row_'.$i.' session_trains session_1_3_'.$i.'" id="session_1_3_'.$i.'" value="'.$session07.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note07.'</td>';
            $table .= '             <td class="ses8 n_w"><input type="text" class="row_'.$i.' session_trains session_2_3_'.$i.'" id="session_2_3_'.$i.'" value="'.$session08.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note08.'</td>';
            $table .= '             <td class="ses9 n_w"><input type="text" class="row_'.$i.' session_trains session_3_3_'.$i.'" id="session_3_3_'.$i.'" value="'.$session09.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note09.'</td>';

            $image_direction = './img/images/notes_.png';
            if ($session10 != '') { $hide = ''; }else{ $hide = 'hidden'; }
            if($hide == ''){$image_direction = get_direction_imagen($is_temporary, $id_event_e, $id_level_e, $id_template, $id_level_t, $i, "4", "1");}
            $function_note = "show_popup_notes(3, '".trim($session10)."', 'session_1_4_".$i."', ".$i.", ".$type_event_note.",0); return false;";
            $note10 = '<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_1_4_'.$i.'" '.$hide.'>';
            $table .= '             <td class="ses10 n_w" style="border-left-width:medium;border-left-color:darkgray;"><input type="text" class="row_'.$i.' session_trains session_1_4_'.$i.'" id="session_1_4_'.$i.'" value="'.$session10.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note10.'</td>';
            $table .= '             <td class="ses11 n_w"><input type="text" class="row_'.$i.' session_trains session_2_4_'.$i.'" id="session_2_4_'.$i.'" value="'.$session11.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note11.'</td>';
            $table .= '             <td class="ses12 n_w"><input type="text" class="row_'.$i.' session_trains session_3_4_'.$i.'" id="session_3_4_'.$i.'" value="'.$session12.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note12.'</td>';

            $image_direction = './img/images/notes_.png';
            if ($session13 != '') { $hide = ''; }else{ $hide = 'hidden'; }
            if($hide == ''){$image_direction = get_direction_imagen($is_temporary, $id_event_e, $id_level_e, $id_template, $id_level_t, $i, "5", "1");}
            $function_note = "show_popup_notes(3, '".trim($session13)."', 'session_1_5_".$i."', ".$i.", ".$type_event_note.",0); return false;";
            $note13 = '<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_1_5_'.$i.'" '.$hide.'>';
            $table .= '             <td class="ses13 n_w" style="border-left-width:medium;border-left-color:darkgray;"><input type="text" class="row_'.$i.' session_trains session_1_5_'.$i.'" id="session_1_5_'.$i.'" value="'.$session13.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note13.'</td>';
            $table .= '             <td class="ses14 n_w"><input type="text" class="row_'.$i.' session_trains session_2_5_'.$i.'" id="session_2_5_'.$i.'" value="'.$session14.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note14.'</td>';
            $table .= '             <td class="ses15 n_w"><input type="text" class="row_'.$i.' session_trains session_3_5_'.$i.'" id="session_3_5_'.$i.'" value="'.$session15.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note15.'</td>';

            $image_direction = './img/images/notes_.png';
            if ($session16 != '') { $hide = ''; }else{ $hide = 'hidden'; }
            if($hide == ''){$image_direction = get_direction_imagen($is_temporary, $id_event_e, $id_level_e, $id_template, $id_level_t, $i, "6", "1");}
            $function_note = "show_popup_notes(3, '".trim($session16)."', 'session_1_6_".$i."', ".$i.", ".$type_event_note.",0); return false;";
            $note16 = '<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_1_6_'.$i.'" '.$hide.'>';
            $table .= '             <td class="ses16 n_w" style="border-left-width:medium;border-left-color:darkgray;"><input type="text" class="row_'.$i.' session_trains session_1_6_'.$i.'" id="session_1_6_'.$i.'" value="'.$session16.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note16.'</td>';
            $table .= '             <td class="ses17 n_w"><input type="text" class="row_'.$i.' session_trains session_2_6_'.$i.'" id="session_2_6_'.$i.'" value="'.$session17.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note17.'</td>';
            $table .= '             <td class="ses18 n_w"><input type="text" class="row_'.$i.' session_trains session_3_6_'.$i.'" id="session_3_6_'.$i.'" value="'.$session18.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note18.'</td>';

            $image_direction = './img/images/notes_.png';
            if ($session19 != '') { $hide = ''; }else{ $hide = 'hidden'; }
            if($hide == ''){$image_direction = get_direction_imagen($is_temporary, $id_event_e, $id_level_e, $id_template, $id_level_t, $i, "7", "1");}
            $function_note = "show_popup_notes(3, '".trim($session19)."', 'session_1_7_".$i."', ".$i.", ".$type_event_note.",0); return false;";
            $note19 = '<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_1_7_'.$i.'" '.$hide.'>';
            $table .= '             <td class="ses19 n_w" style="border-left-width:medium;border-left-color:darkgray;"><input type="text" class="row_'.$i.' session_trains session_1_7_'.$i.'" id="session_1_7_'.$i.'" value="'.$session19.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note19.'</td>';
            $table .= '             <td class="ses20 n_w"><input type="text" class="row_'.$i.' session_trains session_2_7_'.$i.'" id="session_2_7_'.$i.'" value="'.$session20.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note20.'</td>';
            $table .= '             <td class="ses21 n_w"><input type="text" class="row_'.$i.' session_trains session_3_7_'.$i.'" id="session_3_7_'.$i.'" value="'.$session21.'" '.$style_input_ses.' onclick="show_popup(3, this.value, this.id, '.$i.'); return false;" style="background:'.$background_color.';">'.$note21.'</td>

                                    <td id="total_tss_week_'.$i.'" style="border-left-width:medium;border-left-color:darkgray;">'.$all_tss.'</td>
                                </tr>';
            //$background_color = '#EFFBFB';
            if ($cw == 'r' || $cw == 'R') {
                  ($background_color == '#EFFBFB') ? $background_color = '#CEF6F5' : $background_color = '#EFFBFB';
            } 
        }

            $table .= '       </tbody>
                        </table>
                        <div class="load"></div>';
            $table .= '
                            </tbody>
                        </table>
                        <br/>
                        <!-- Ventana emregente -->
                        <div id="hider_3" onclick="close_popup(3); return false;"></div>
                        <div id="popup_box_3">
                            <a class="close" onclick="close_popup(3); return false;">X</a>
                            <br>
                            <div class="message_box_3"></div>
                        </div> ';
            $table .= $weeks_template;

            $cols01 = $cols02 = $cols03 = $cols04 = $cols05 = $cols06 = $cols07 = 0;
            $cols08 = $cols09 = $cols10 = $cols11 = $cols12 = $cols13 = $cols14 = 0;
            $cols15 = $cols16 = $cols17 = $cols18 = $cols19 = $cols20 = $cols21 = 0;
            
            ($sess01 > 0) ? $cols01 = 1 : $cols01 = 0; ($sess02 > 0) ? $cols02 = 1 : $cols02 = 0; ($sess03 > 0) ? $cols03 = 1 : $cols03 = 0;
            ($sess04 > 0) ? $cols04 = 1 : $cols04 = 0; ($sess05 > 0) ? $cols05 = 1 : $cols05 = 0; ($sess06 > 0) ? $cols06 = 1 : $cols06 = 0;
            ($sess07 > 0) ? $cols07 = 1 : $cols07 = 0; ($sess08 > 0) ? $cols08 = 1 : $cols08 = 0; ($sess09 > 0) ? $cols09 = 1 : $cols09 = 0;
            ($sess10 > 0) ? $cols10 = 1 : $cols10 = 0; ($sess11 > 0) ? $cols11 = 1 : $cols11 = 0; ($sess12 > 0) ? $cols12 = 1 : $cols12 = 0;
            ($sess13 > 0) ? $cols13 = 1 : $cols13 = 0; ($sess14 > 0) ? $cols14 = 1 : $cols14 = 0; ($sess15 > 0) ? $cols15 = 1 : $cols15 = 0;
            ($sess16 > 0) ? $cols16 = 1 : $cols16 = 0; ($sess17 > 0) ? $cols17 = 1 : $cols17 = 0; ($sess18 > 0) ? $cols18 = 1 : $cols18 = 0;
            ($sess19 > 0) ? $cols19 = 1 : $cols19 = 0; ($sess20 > 0) ? $cols20 = 1 : $cols20 = 0; ($sess21 > 0) ? $cols21 = 1 : $cols21 = 0;
            ///..... .. .. .. .. .. .. .. .. .. ..
            $notes = '';
            if ($type_aux != '11' && $type_aux != '12' && $type_aux != '13' && $is_temporary == 0) {
                  $notes = '<div style="text-align:left; display:inline;">
                              <label for="notes_event">Notas: </label>
                              <textarea cols="50" rows="4" id="notes_event" class="input-default" placeholder="Agregue aqui sus notas para Guardar Grupo Secundario..."></textarea>
                        </div>';
            }
            $master_data = '';
            if ($is_temporary > 0) {
                  $notes .= ' <div style="text-align:left; display:inline;">
                              <label for="notes_event">Notas: </label>
                              <textarea cols="50" rows="4" id="notes_event" class="input-default" placeholder="Agregue aqui sus notas...">'.$notes_temps.'</textarea>
                              &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" type="button" onclick="save_notes_temporary_event();">Guardar Notas</button>
                        </div>';
            }
            # - # - # - # - # - # - # - # - # - # - # - #
            if (intval($id_event_e) == intval($have_other_master)) {
                  $desc_m = 'Temporal (no disponible)';
            }else{
                  $master_name = select_gral('`Events`','name_e,type','id_e="'.$have_other_master.'"','name_e');
                  if ($master_name == true) { foreach ($master_name as $key_nm) { $master_name = $key_nm[0]; $master_type = $key_nm[1]; } }
                  $master_leve = select_gral('`Levels`','name_l','id_l="'.$level_master.'"','name_l');
                  if ($master_leve == true) { foreach ($master_leve as $key_lm) { $master_leve = $key_lm[0]; } }
                  $master_type_name = select_gral('`Types`','name_t','id_t="'.$master_type.'"','name_t');
                  if ($master_type_name == true) { foreach ($master_type_name as $key_tm) { $master_type_name = $key_tm[0]; } }
                  $desc_m = utf8_encode($master_type_name).' -- '.utf8_encode($master_name).' -- '.utf8_encode($master_leve);
            }
            $date_evt = $weeks_evt = "";
            $date_event = select_gral('`Events`','date_e','id_e="'.$id_event_e.'"','name_e');
            if ($date_event == true) { foreach ($date_event as $key_de) { $date_evt = $key_de[0]; } }
            $date_event = select_gral('`Events`','weeks','id_e="'.$id_template.'"','name_e');
            if ($date_event == true) { foreach ($date_event as $key_de) { $weeks_evt = $key_de[0]; } }
            if ($date_evt != "" && $weeks_evt != "") {
                  $date_event = strtotime('-'.($weeks_evt-1).' weeks', strtotime($date_evt));
                  $date_event = date("d-M-Y", $date_event);
            }else{ $date_event = "Fecha no disponible..."; }
            $master_data = '<div style="width: 100%;"><h4> &nbsp;&nbsp;&nbsp;&nbsp;Template: <b>'.$desc_m.'</b>,&nbsp;&nbsp;&nbsp;&nbsp; Este Grupo Comienza el: <b>'.$date_event.'</b></h4></div><br>';
            # - # - # - # - # - # - # - # - # - # - # - #
            //$id_event_e $have_other_master $level_master
            ///..... .. .. .. .. .. .. .. .. .. ..
            $width_button = '';
            $width_button = '<br>
                  <div style="width:100%;">'.$master_data.'
                        '.$notes.'
                        <div style="text-align:right;">';
            if ($is_temporary > 0) {
                  $width_button .= '<button class="btn add_block" type="button" onclick="load_form_block(3);">Cargar Bloque</button> ';
            }
            $width_button .= '<button class="btn" type="button" onclick="show_all_cols();">Complementar Sesiones</button>
                              <button class="btn width_button" type="button" onclick="modify_width_tables('."'normal'".');">Ampliar Sesiones</button> ';
            if ($type_aux != '11' && $type_aux != '12' && $type_aux != '13' && $is_temporary == 0) {
                  $width_button .= '<button class="btn exchg_button" type="button" onclick="show_exchange_cols_inputs(0);">Intercambiar Columnas</button>';
            }
            $width_button .= '</div></div><br><div id="note_columns" style="width:100%;"></div><br>';//Simple
            ///..... .. .. .. .. .. .. .. .. .. ..

            $header = ' <img src="./img/images/back_icon02.png" title="Regresar al Resumen" style="cursor:pointer; width:4%;" onclick="load_summary_macro_form('."''".'); return false;">
                        <input type="hidden" id="weeks_train" value="'.$weeks_e.'"></input>
                        <input type="hidden" id="level_master" value="'.$level_id.'"></input>
                        <input type="hidden" id="type_master" value="'.$type_aux.'"></input>'.//
                       '<input type="hidden" id="name_master_sel" value="'.$id_event_e.'"></input>'.
                       '<input type="hidden" id="level_temp" value="'.$id_level_t.'"></input>'.//
                       '<input type="hidden" id="id_temp" value="'.$id_template.'"></input>';//
                        $val_type_table = '';
                        $event_temp = $exist_t = '';
                        $event_temp = select_gral('`Events_temp`','COUNT(*)','event="'.$id_event_e.'" AND level="'.$id_level_e.'"', 'id_et');
                        if ($event_temp == true) { foreach ($event_temp as $key) { $exist_t = intval($key[0]); } }
                        if ($type_aux != '11' && $type_aux != '12' && $type_aux != '13' && $is_temporary == 0 && $exist_t == 0){
                              if ($type == 1) {
                                    $header .= '<h3 class="pagetitle" id="type_train">';
                                    $header .= utf8_encode($name_event_new);
                                    $header .= '</h3>';
                                    $header .= '<br>';
                                    $header .= '<div class="form-group">&nbsp;&nbsp;&nbsp;
                                                      <button class="btn btn-primary" type="button" onclick="load_groups_withoutassign(); return false;" > Realizar cambio al nombre del Grupo </button>
                                                </div>';
                                    $header .= '<span id="value_name_event">
                                                      <input type="hidden" id="name_event" value="'.utf8_encode($name_event_new).'" />
                                                </span>';
                              }else{
                                    $header .= '<h3 class="pagetitle" id="type_train"> '.utf8_encode($type_e).', Carga '.utf8_encode($charge).' - <input type="text" id="name_event" value="'.utf8_encode($name_event_new).'" style="font-size: 25px;"/> <a style="cursor: pointer;" title="Actividad del Grupo" onclick="load_info_event_activity('."'".utf8_encode($name_event_new)."'".'); return false;"><span class="iconfa-list-alt"></span></a></h3>';
                              }
                              $val_type_table = 'normal';
                        }else{
                              $header .= '<h3 class="pagetitle" id="type_train"> '.utf8_encode($type_e).', Carga '.utf8_encode($charge).' - '.utf8_encode($name_event_new).' <a style="cursor: pointer;" title="Actividad del Grupo" onclick="load_info_event_activity('."'".utf8_encode($name_event_new)."'".'); return false;"><span class="iconfa-list-alt"></span></a></h3>';
                              $val_type_table = 'temporal';
                        }
                        $header .= '<input type="hidden" id="type_table" value="'.$val_type_table.'"/>';
                        $header .= $width_button.'
                        <table class="table table-bordered trainings" id="t_ex">
                            <thead>
                                <tr>
                                    <th title="Fecha">Fecha</th>
                                    <th class="d" title="Ciclo Semanal">C.Sem.</th>
                                    <!--<th class="d"></th>-->
                                    <th title="Datos">Datos</th>
                                    <th title="Semana" class="g">Sem.</th>
                                    <th class="d"></th>
                                    <th style="border-right: dodgerblue !important; border-right-style: solid !important;"></th>';
                                    (($cols01+$cols02+$cols03) > 0) ? $style = '' : $style = 'style="display: none;"';
                        $header .= '<th colspan="'.($cols01+$cols02+$cols03).'" '.$style .' class="a col_day_1_p" style="border-left-width:medium;border-left-color:darkgray;">Lunes / Monday</th>';
                                    (($cols04+$cols05+$cols06) > 0) ? $style = '' : $style = 'style="display: none;"';
                        $header .= '<th colspan="'.($cols04+$cols05+$cols06).'" '.$style .' class="col_day_2_p" style="border-left-width:medium;border-left-color:darkgray;">Martes / Tuesday</th>';
                                    (($cols07+$cols08+$cols09) > 0) ? $style = '' : $style = 'style="display: none;"';
                        $header .= '<th colspan="'.($cols07+$cols08+$cols09).'" '.$style .' class="a col_day_3_p" style="border-left-width:medium;border-left-color:darkgray;">Miercoles / Wednesday</th>';
                                    (($cols10+$cols11+$cols12) > 0) ? $style = '' : $style = 'style="display: none;"';
                        $header .= '<th colspan="'.($cols10+$cols11+$cols12).'" '.$style .' class="col_day_4_p" style="border-left-width:medium;border-left-color:darkgray;">Jueves / Thursday</th>';
                                    (($cols13+$cols14+$cols15) > 0) ? $style = '' : $style = 'style="display: none;"';
                        $header .= '<th colspan="'.($cols13+$cols14+$cols15).'" '.$style .' class="a col_day_5_p" style="border-left-width:medium;border-left-color:darkgray;">Viernes / Friday</th>';
                                    (($cols16+$cols17+$cols18) > 0) ? $style = '' : $style = 'style="display: none;"';
                        $header .= '<th colspan="'.($cols16+$cols17+$cols18).'" '.$style .' class="col_day_6_p" style="border-left-width:medium;border-left-color:darkgray;">Sabado / Saturday</th>';
                                    (($cols19+$cols20+$cols21) > 0) ? $style = '' : $style = 'style="display: none;"';
                        $header .= '<th colspan="'.($cols19+$cols20+$cols21).'" '.$style .' class="a col_day_7_p" style="border-left-width:medium;border-left-color:darkgray;">Domingo / Sunday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="c">
                                    <td class="d"> </td><td class="d"></td><td class="d"></td>
                                    <td class="d"></td><td class="d"></td><td class="d02"></td>
                                    <td class="desc_day col_day_1" colspan="3" style="border-left-width:medium;border-left-color:darkgray;"></td>
                                    <td class="desc_day col_day_2" colspan="3" style="border-left-width:medium;border-left-color:darkgray;"></td>
                                    <td class="desc_day col_day_3" colspan="3" style="border-left-width:medium;border-left-color:darkgray;"></td>
                                    <td class="desc_day col_day_4" colspan="3" style="border-left-width:medium;border-left-color:darkgray;"></td>
                                    <td class="desc_day col_day_5" colspan="3" style="border-left-width:medium;border-left-color:darkgray;"></td>
                                    <td class="desc_day col_day_6" colspan="3" style="border-left-width:medium;border-left-color:darkgray;"></td>
                                    <td class="desc_day col_day_7" colspan="3" style="border-left-width:medium;border-left-color:darkgray;"></td>
                                    <td class="exchange_button" style="border: none; border-left-width:medium;border-left-color:darkgray;"></td>
                                </tr>
                                <tr class="c sess">
                                    <td class="d"></td>
                                    <td class="d"></td>
                                    <td class="e"><span id="total_br" class="total_br">0</span> | <span id="total_tp" class="total_tp">0</span> TSS/dia</td>
                                    <td class="d"></td><td class="d"></td><td class="d02"></td>';

                        $header .= '<td class="ses1" style="border-left-width:medium;border-left-color:darkgray;">Session 1</td>';
                        $header .= '<td class="ses2">Session 2</td>';
                        $header .= '<td class="ses3">Session 3</td>';
                        $header .= '<td class="ses4" style="border-left-width:medium;border-left-color:darkgray;">Session 1</td>';
                        $header .= '<td class="ses5">Session 2</td>';
                        $header .= '<td class="ses6">Session 3</td>';
                        $header .= '<td class="ses7" style="border-left-width:medium;border-left-color:darkgray;">Session 1</td>';
                        $header .= '<td class="ses8">Session 2</td>';
                        $header .= '<td class="ses9">Session 3</td>';
                        $header .= '<td class="ses10" style="border-left-width:medium;border-left-color:darkgray;">Session 1</td>';
                        $header .= '<td class="ses11">Session 2</td>';
                        $header .= '<td class="ses12">Session 3</td>';
                        $header .= '<td class="ses13" style="border-left-width:medium;border-left-color:darkgray;">Session 1</td>';
                        $header .= '<td class="ses14">Session 2</td>';
                        $header .= '<td class="ses15">Session 3</td>';
                        $header .= '<td class="ses16" style="border-left-width:medium;border-left-color:darkgray;">Session 1</td>';
                        $header .= '<td class="ses17">Session 2</td>';
                        $header .= '<td class="ses18">Session 3</td>';
                        $header .= '<td class="ses19" style="border-left-width:medium;border-left-color:darkgray;">Session 1</td>';
                        $header .= '<td class="ses20">Session 2</td>';
                        $header .= '<td class="ses21">Session 3</td>';
                        $header .= '<td title="TSS Totales" class="b" style="border-left-width:medium;border-left-color:darkgray;">TSS Totales</td>
                                </tr>';

                        $cols_hide = '';
                        ($cols01 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '1,';
                        ($cols02 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '2,';
                        ($cols03 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '3,';
                        ($cols04 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '4,';
                        ($cols05 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '5,';
                        ($cols06 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '6,';
                        ($cols07 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '7,';
                        ($cols08 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '8,';
                        ($cols09 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '9,';
                        ($cols10 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '10,';
                        ($cols11 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '11,';
                        ($cols12 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '12,';
                        ($cols13 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '13,';
                        ($cols14 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '14,';
                        ($cols15 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '15,';
                        ($cols16 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '16,';
                        ($cols17 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '17,';
                        ($cols18 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '18,';
                        ($cols19 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '19,';
                        ($cols20 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '20,';
                        ($cols21 > 0) ? $cols_hide = $cols_hide : $cols_hide .= '21,';
                        $cols_hide = substr($cols_hide, 0, -1);
                        //echo $cols_hide;
            $tab1 = '';
            $tab2 = '';
            $save_button = '';
            if ($type_aux != '11' && $type_aux != '12' && $type_aux != '13' && $is_temporary == 0) {
                  $save_button = '
                              <div style="display:inline;">
                                    <button class="btn" type="button" onclick="load_extra_notes_edit('.$id_event_e.','.$id_level_e.','.$id_template.','.$id_level_t.',0,0,1,0);">Ocultar/Mostrar Notas</button>
                              </div>
                              <div style="display:inline; float: right;">
                                    <button class="btn btn-primary" type="button" onclick="save_all_data_db_secundary_confirm('.$type.');">Guardar Grupo Secundario</button>
                              </div><br>';
            }
            if ($is_temporary != 0 && $is_master == 0 && $is_converted == 0) {
                  $save_button = '
                              <div style="display:inline;">
                                    <button class="btn" type="button" onclick="load_extra_notes_edit(0,0,'.$id_event_e.','.$id_level_e.','.$id_template.','.$id_level_t.',2,1);">Ocultar/Mostrar Notas</button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-primary" id="add_weeks_button" type="button" onclick="form_extra_weeks_event('."'".($i-1)."'".','."'".$date_event_aux."'".','."'".$background_color."'".','."'".$cols_hide."'".');">Agregar Semanas</button>
                              </div>
                              <div style="display:inline; float: right;"><!--text-align:right;-->
                                    <input type="text" class="input-default" id="new_name_to_temp_sec" maxlength="50" onkeyup="check_name_temp(this.value); return false;" style="width: 200px;" placeholder="Nombre del Template" >
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-primary event_sec_to_master" type="button" onclick="save_secundary_evento_like_master();" disabled="">Guardar Grupo Secundario Como Template</button>
                              </div><br><br><br>';
            }
            $save_button .= '<div id="form_add_weeks_event"></div>';
            $save_other_notes = '';
            $save_other_notes = '<div id="extra_notes" style="text-align:center;"></div>';
            $table .= $save_button;
      return '7@-@'.$header.$table.$save_other_notes.'@-@'.$cols_hide;
}
      //////////////
	function get_all_values_session($id_session,$session){
            $session_info = '';
            $session_data = session_interpretation($session);//$distance_s.'-'.$units.','.$distance_b.'-'.$unitb.','.$distance_r.'-'.$unitr;
            $session_tss  = session_calculate($session_data);//$calc_s.','.$calc_b.','.$calc_r
            $session_data = explode(',', $session_data);
            $session_data[0] = explode('-', $session_data[0]);
            $session_data[1] = explode('-', $session_data[1]);
            $session_data[2] = explode('-', $session_data[2]);
            $session_tss  = explode(',', $session_tss);
            //session_desc+'|-|'+disc_s+'|-|'+unit_s+'|-|'+stss+'|-|'+disc_b+'|-|'+unit_b+'|-|'+btss+'|-|'+disc_r+'|-|'+unit_r+'|-|'+rtss
            $session_info = $session_data[0][0].'|-|'.$session_data[0][1].'|-|'.$session_tss[0].'|-|'.$session_data[1][0].'|-|'.$session_data[1][1].'|-|'.$session_tss[1].'|-|'.$session_data[2][0].'|-|'.$session_data[2][1].'|-|'.$session_tss[2];
            return $id_session.'|---|'.$session.'|-|'.$session_info;
      }
      //////////////
	function load_discipline_parameters($discipline, $try, $type){
      $filters = '';
      $sels = $diss = $zones = ''; $tsss_t = 0; 
      $selb = $disb = $zoneb = ''; $tssb_t = 0;
      $selr = $disr = $zoner = ''; $tssr_t = 0;
      $observation_session = 'observation';
      switch ($discipline) {
            case 'S':
                  $vis_s = "";
                  if ($try == 1) {
                        $sels = 'selected';
                        $diss = 'disabled';
                        $vis_s = "hidden";
                        $filters .= '<br><select id="sel_ps" onchange="reload_test_option(this.value,'.$type.','."'".$discipline."'".','.$try.','."'".$sels."'".','.$tsss_t.','."'".$diss."'".');">
                                          <option value="">Selecciona una Prueba...</option>
                                          <option value="t">Prueba de la T</option>
                                          <option value="css">Prueba CSS</option>
                                    </select>';
                  }else{ $filters .= 'Parámetros de S:<br>'; }
                  $filters .= '<div id="test_s" '.$vis_s.'>
                        <center>
                              <select class="input_box" id="s'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); reset_chk_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$diss.'>
                                    <option value="">0</option>
                                    <option value="500" >500</option>
                                    <option value="1000" >1000</option>
                                    <option value="1500" '.$sels.'>1500</option>
                                    <option value="2000" >2000</option>
                                    <option value="2500" >2500</option>
                                    <option value="3000" >3000</option>
                                    <option value="3500" >3500</option>
                                    <option value="4000" >4000</option>
                                    <option value="4500" >4500</option>
                              </select> &nbsp;&nbsp;&nbsp;
                              <span id="m'.$type.'">mts</span> &nbsp;&nbsp;&nbsp;
                              <span id="s_t'.$type.'">60</span> &nbsp;&nbsp;&nbsp;
                              <span id="stss'.$type.'">'.$tsss_t.'</span> sTSS';
                  ///-- -- -- -- -- -- -- -- --
                  $filters .= '<br>';
                  $filters .= '<label>S.1</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="1" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$diss.'/> | ';
                  $filters .= '<label>S.2</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="2" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$diss.'/> | ';
                  $filters .= '<label>S.3</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="3" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$diss.'/> | ';
                  $filters .= '<label>S.4</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="4" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$diss.'/> | ';
                  $filters .= '<label>S.5</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="5" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$diss.'/> | ';
                  $filters .= '<label>S.6</label> <input type="checkbox" id="type_s_'.$type.'" name="type_s_'.$type.'" value="6" onchange="check_just_one_check(this.id, this.value); reset_sel_s('.$type.'); build_session_individual(1,'.$type.','."'".$discipline."'".',0);" '.$diss.'/> | ';
                  $filters = substr($filters, 0, -3);
                  $filters .= '';
                  ///-- -- -- -- -- -- -- -- --
                  //Start personalized zones
                  $zones .= '<b>Zona:<span style="color:red;"> *</span></b><br>';
                  $zones .= '<label>Fondo</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="F" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                  $zones .= '<label>Recuperación</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="R" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/><br>';
                  $zones .= '<label>Umbral</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="U" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                  $zones .= '<label>Velocidad</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="V" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/>';
                  //Finish personalized zones
                  $filters .=' <br>'.$zones.' <br>&nbsp;';
                  
                  $filters .=' <br>Observación:
                              <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;" '.$diss.'>
                        </center></div>';
                  break;
            case 'B':
                  $disb_02 = 'disabled';
                  if ($try == 1) {
                        $filters .= 'FTPs:<br>';
                        $filters .= '
                              <center>
                                    <b><span>FTP: </span><span style="color:red;">*</span></b>
                                    <select id="ftp_num'.$type.'" onchange="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');">
                                          <option value=""></option>
                                          <option value="1">FTP.1</option>
                                          <option value="2">FTP.2</option>
                                    </select>
                              <br>';
                        $selb = 'selected';
                        $disb = 'disabled';
                        $tssb_t = 80;
                  }else{
                        $filters .= 'Parámetros de B:<br>';
                        $filters .= '
                              <center>';
                        $zoneb = '';
                        //Start personalized zones
                        $zoneb .= '<b>Zona:<span style="color:red;"> *</span></b><br>';
                        $zoneb .= '<label>Z1</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z1" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                        $zoneb .= '<label>Z2</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z2" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                        $zoneb .= '<label>Z3</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z3" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                        $zoneb .= '<label>Z4</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z4" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                        $zoneb .= '<label>Z5</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z5" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/><br>';
                        $zoneb .= '<label>Fza</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Fza" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                        $zoneb .= '<label>PC</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="PC" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                        $zoneb .= '<label>SST</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="SST" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                        $zoneb .= '<label>AC</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="AC" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                        $zoneb .= '<label>NP</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="NP" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                        $zoneb = substr($zoneb, 0, -3);
                        $zoneb .= '';
                        //Finish personalized zones
                  }
                  $filters .= '
                              <!--<center>-->
                                    <span id="sel_cant_b'.$type.'">
                                          <select class="input_box" id="b'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disb.' '.$disb_02.' onclick="show_alert();">
                                                <option value="">0</option>
                                                <option value="10" >10</option>
                                                <option value="20" >20</option>
                                                <option value="30" >30</option>
                                                <option value="40" >40</option>
                                                <option value="50" >50</option>
                                                <option value="60" '.$selb.'>60</option>
                                                <option value="70" >70</option>
                                                <option value="80" >80</option>
                                                <option value="90" >90</option>
                                                <option value="100" >100</option>
                                                <option value="110" >110</option>
                                                <option value="120" >120</option>
                                                <option value="130" >130</option>
                                                <option value="140" >140</option>
                                                <option value="150" >150</option>
                                                <option value="160" >160</option>
                                                <option value="170" >170</option>
                                                <option value="180" >180</option>
                                                <option value="190" >190</option>
                                                <option value="200" >200</option>
                                                <option value="210" >210</option>
                                                <option value="220" >220</option>
                                                <option value="230" >230</option>
                                                <option value="240" >240</option>
                                                <option value="250" >250</option>
                                                <option value="260" >260</option>
                                                <option value="270" >270</option>
                                                <option value="280" >280</option>
                                                <option value="290" >290</option>
                                                <option value="300" >300</option>
                                                <option value="310" >310</option>
                                                <option value="320" >320</option>
                                                <option value="330" >330</option>
                                                <option value="340" >340</option>
                                                <option value="350" >350</option>
                                                <option value="360" >360</option>
                                                <option value="370" >370</option>
                                                <option value="380" >380</option>
                                                <option value="390" >390</option>
                                                <option value="400" >400</option>
                                          </select>
                                    </span> &nbsp;&nbsp;&nbsp;
                                    <span id="mb'.$type.'">';
                                          $filters .= '<!-- <label>min</label> <input type="radio" id="selb'.$type.'" name="selb'.$type.'" value="min" onclick="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'b'".');" '.$disb.'/> &nbsp;|&nbsp;';
                                          $filters .= ' <label> km</label> <input type="radio" id="selb'.$type.'" name="selb'.$type.'" value="km" onclick="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'b'".');" '.$disb.'/>-->
                                          <!---->
                                          <!--AHORA CON CHECKBOX-->';
                                          $filters .= ' <label>min</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'b'".');" '.$disb.'/> &nbsp;|&nbsp;';
                                          $filters .= ' <label> km</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'b'".');" '.$disb.'/>
                                    </span> &nbsp;&nbsp;&nbsp;
                                    <span id="b_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
                                    <span id="tss'.$type.'">'.$tssb_t.'</span>&nbsp;&nbsp; TSS
                                    <br>
                                    '.$zoneb.'
                                    <br>Observación:
                                    <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;" '.$disb.'>
                              </center>';
                  break;
            case 'R':
                  $disr_02 = 'disabled';
                  if ($try == 1) {
                          $disr = 'disabled';
                        $filters .= 'Prueba:<br>';
                        $filters .= '<center>';
                        $filters .= '     <label>Vdot</label>
                                          <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="vdot" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');"/> |';
                        $filters .= '     <label>Milla</label>
                                          <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="milla" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');"/> |';
                        $filters .= '     <label>3k</label>
                                          <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="3k" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');"/> |';
                        $filters .= '     <label>5k</label>
                                          <input type="checkbox" id="try_r'.$type.'" name="try_r'.$type.'" value="5k" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".'R'."'".','.$try.');"/>';
                        $filters .= '</center>';
                  }else{
                        $filters .= 'Parámetros de R:<br>'; $zoner = '';
                        $all_zones = select_gral('`Zones`','name_z','name_z NOT IN("Z1+","Z1-","Z2+","Z2-","Z3+","Z3-","Z4+","Z4-")','id_z','name_z');
                        if ($all_zones == true) {
                              $zoner .= '<b>Zona:<span style="color:red;"> *</span></b><br>';
                              foreach ($all_zones as $key) {
                                    //CHECKBOX
                                    $zoner .= '<label>'.$key[0].'</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="'.$key[0].'" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                              }
                        }
                        $zoner = substr($zoner, 0, -3);
                        $zoner .= '';
                  }
                  $filters .= '
                              <center>
                                    <span id="sel_cant_r'.$type.'">
                                          <select class="input_box" id="r'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disr.' '.$disr_02.'>
                                                <option value="">0</option>
                                                <option value="10" >10</option>
                                                <option value="20" >20</option>
                                                <option value="30" >30</option>
                                                <option value="40" >40</option>
                                                <option value="50" '.$selr.'>50</option>
                                                <option value="60" >60</option>
                                                <option value="70" >70</option>
                                                <option value="80" >80</option>
                                                <option value="90" >90</option>
                                                <option value="100" >100</option>
                                                <option value="110" >110</option>
                                                <option value="120" >120</option>
                                                <option value="130" >130</option>
                                                <option value="140" >140</option>
                                                <option value="150" >150</option>
                                                <option value="160" >160</option>
                                                <option value="170" >170</option>
                                                <option value="180" >180</option>
                                                <option value="190" >190</option>
                                                <option value="200" >200</option>
                                          </select>
                                    </span> &nbsp;&nbsp;&nbsp;
                                    <span id="mr'.$type.'">';
                                          $filters .= '<!-- <label>min</label> <input type="radio" id="selr'.$type.'" name="selr'.$type.'" value="min" onclick="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'r'".');" '.$disb.'/> &nbsp;|&nbsp;';
                                          $filters .= ' <label> km</label> <input type="radio" id="selr'.$type.'" name="selr'.$type.'" value="km" onclick="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'r'".');" '.$disb.'/>-->
                                          <!---->
                                          <!--AHORA CON CHECKBOX-->';
                                          $filters .= ' <label>min</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'r'".');" '.$disr.'/> &nbsp;|&nbsp;';
                                          $filters .= ' <label> km</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."'".$disb."'".','."'r'".');" '.$disr.'/>
                                          <!---->
                                    </span> &nbsp;&nbsp;&nbsp;
                                    <span id="r_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
                                    <span id="rtss'.$type.'">'.$tssr_t.'</span>&nbsp; rTSS
                                    <br>
                                    '.$zoner.'
                                    <br>Observación:
                                    <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;" '.$disr.'>
                              </center>';
                  break;
            case 'BR':
                  $disb = 'disabled';
                  $disr = 'disabled';
                  if ($try == 1) {
                        $filters .= 'Parámetros de KW:<br>';
                  }else{
                        $filters .= 'Parámetros de BR:<br>';
                  }
                  $filters .= '
                              <center>&nbsp;B:
                                    <span id="sel_cant_b'.$type.'">
                                          <select class="input_box" id="b'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disb.'>
                                                <option value="">0</option>
                                                <option value="10" >10</option>
                                                <option value="20" >20</option>
                                                <option value="30" >30</option>
                                                <option value="40" >40</option>
                                                <option value="50" >50</option>
                                                <option value="60" >60</option>
                                                <option value="70" >70</option>
                                                <option value="80" >80</option>
                                                <option value="90" >90</option>
                                                <option value="100" >100</option>
                                                <option value="110" >110</option>
                                                <option value="120" >120</option>
                                                <option value="130" >130</option>
                                                <option value="140" >140</option>
                                                <option value="150" >150</option>
                                                <option value="160" >160</option>
                                                <option value="170" >170</option>
                                                <option value="180" >180</option>
                                                <option value="190" >190</option>
                                                <option value="200" >200</option>
                                                <option value="210" >210</option>
                                                <option value="220" >220</option>
                                                <option value="230" >230</option>
                                                <option value="240" >240</option>
                                                <option value="250" >250</option>
                                                <option value="260" >260</option>
                                                <option value="270" >270</option>
                                                <option value="280" >280</option>
                                                <option value="290" >290</option>
                                                <option value="300" >300</option>
                                                <option value="310" >310</option>
                                                <option value="320" >320</option>
                                                <option value="330" >330</option>
                                                <option value="340" >340</option>
                                                <option value="350" >350</option>
                                                <option value="360" >360</option>
                                                <option value="370" >370</option>
                                                <option value="380" >380</option>
                                                <option value="390" >390</option>
                                                <option value="400" >400</option>
                                          </select>
                                    </span> &nbsp;&nbsp;&nbsp;
                                    <span id="mb'.$type.'">';
                                          $filters .= '<!-- <label>min</label> <input type="radio" id="selb'.$type.'" name="selb'.$type.'" value="min" onclick="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); modify_units_brick(this.value,'.$type.');"/> | ';
                                          $filters .= ' <label> km</label> <input type="radio" id="selb'.$type.'" name="selb'.$type.'" value="km" onclick="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); modify_units_brick(this.value,'.$type.');"/>-->
                                          <!---->
                                          <!--AHORA CON CHECKBOX-->';
                                          $filters .= ' <label>min</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); modify_units_brick(this.value,'.$type.');"/> | ';
                                          $filters .= ' <label> km</label> <input type="checkbox" id="selb'.$type.'" name="selb'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); modify_units_brick(this.value,'.$type.');"/>
                                          <!---->
                                    </span> &nbsp;&nbsp;&nbsp;
                                    <span id="b_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
                                    <span id="tss'.$type.'">0</span>&nbsp; bTSS
                              <br>R:
                                    <span id="sel_cant_r'.$type.'">
                                          <select class="input_box" id="r'.$type.'" onchange="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');" '.$disr.'>
                                                <option value="">0</option>
                                                <option value="10" >10</option>
                                                <option value="20" >20</option>
                                                <option value="30" >30</option>
                                                <option value="40" >40</option>
                                                <option value="50" >50</option>
                                                <option value="60" >60</option>
                                                <option value="70" >70</option>
                                                <option value="80" >80</option>
                                                <option value="90" >90</option>
                                                <option value="100" >100</option>
                                                <option value="110" >110</option>
                                                <option value="120" >120</option>
                                                <option value="130" >130</option>
                                                <option value="140" >140</option>
                                                <option value="150" >150</option>
                                                <option value="160" >160</option>
                                                <option value="170" >170</option>
                                                <option value="180" >180</option>
                                                <option value="190" >190</option>
                                                <option value="200" >200</option>
                                          </select>
                                    </span> &nbsp;&nbsp;&nbsp;
                                    <span id="mr'.$type.'">';
                                          $filters .= '<!-- <label>min</label> <input type="radio" id="selr'.$type.'" name="selr'.$type.'" value="min" onclick="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."''".','."'r'".');" '.$disb.' disabled/> | ';
                                          $filters .= ' <label> km</label> <input type="radio" id="selr'.$type.'" name="selr'.$type.'" value="km" onclick="change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."''".','."'r'".');" '.$disb.' disabled/>-->
                                          <!---->
                                          <!--AHORA CON CHECKBOX-->';
                                          $filters .= ' <label>min</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="min" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."''".','."'r'".');" '.$disb.' disabled/> | ';
                                          $filters .= ' <label> km</label> <input type="checkbox" id="selr'.$type.'" name="selr'.$type.'" value="km" onchange="check_just_one_check(this.id, this.value); change_session_box_individual('.$type.', '."'".$discipline."'".', '.$try.'); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); load_cant_sel(this.value, '.$type.','."'".$discipline."'".','.$try.','."''".','."'r'".');" '.$disb.' disabled/>
                                          <!---->
                                    </span> &nbsp;&nbsp;&nbsp;
                                    <span id="r_t'.$type.'"></span> &nbsp;&nbsp;&nbsp;
                                    <span id="rtss'.$type.'">0</span>&nbsp; rTSS
                                    <br>';
                                    //Start personalized zones
                                    $filters .= '<b>Zona:<span style="color:red;"> *</span></b><br>';
                                    $filters .= '<label>Z1</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z1" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                                    $filters .= '<label>Z2</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z2" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                                    $filters .= '<label>Z3</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z3" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                                    $filters .= '<label>Z4</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z4" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                                    $filters .= '<label>Z5</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Z5" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/><br>';
                                    $filters .= '<label>Fza</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="Fza" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                                    $filters .= '<label>PC</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="PC" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                                    $filters .= '<label>SST</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="SST" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                                    $filters .= '<label>AC</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="AC" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                                    $filters .= '<label>NP</label> <input type="checkbox" id="zone_session_sel_'.$type.'" name="zone_session_sel_'.$type.'" value="NP" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                                    $filters = substr($filters, 0, -3);
                                    $filters .= '';
                                    //Finish personalized zones
                                    $filters .='
                                          <br>Observación:
                                          <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;">';
                                    $filters .='
                              </center>';
                  break;
            case 'F':
                  $filters .= 'Parámetros de F:<br>';
                  $filters .= '
                        <center>
                              <!--&nbsp;&nbsp;&nbsp;&nbsp;-->
                              Opción: <span style="color:red;">* </span><br>';
                  //CON CHECKBOX
                  $filters .= '<label>Core</label> <input id="force_option'.$type.'" type="checkbox" value="Core" name="force_option'.$type.'" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                  $filters .= '<label>Pierna</label> <input id="force_option'.$type.'" type="checkbox" value="Pierna" name="force_option'.$type.'" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                  $filters .= '<label>Superior</label> <input id="force_option'.$type.'" type="checkbox" value="Superior" name="force_option'.$type.'" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/> | ';
                  $filters .= '<label>General</label> <input id="force_option'.$type.'" type="checkbox" value="General" name="force_option'.$type.'" onchange="check_just_one_check(this.id, this.value); build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.');"/>';
                 $filters .= '<br>
                              &nbsp;&nbsp;
                              Duración: <span style="color:red;">* </span>
                              <select id="force_time'.$type.'" style="width:50px;" onchange="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;">
                                    <option value=""></option>
                                    <option value="10">10</option> <option value="20">20</option> <option value="30">30</option>
                                    <option value="40">40</option> <option value="50">50</option> <option value="60">60</option>
                                    <option value="70">70</option> <option value="80">80</option> <option value="90">90</option>
                                    <option value="100">100</option> <option value="110">110</option> <option value="120">120</option>
                              </select> min
                              <br>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              Identificador: <span style="color:red;">* </span>
                              <input type="text" id="force_id'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;">
                              <br>
                        </center>';
                  break;
            case 'XT':
                  $filters .= 'Parámetros de XT:<br>';
                  $filters .= '
                        <center>
                              Duración: 
                              <select id="tx_time'.$type.'" style="width:50px;" onchange="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;">
                                    <option value=""></option>
                                    <option value="15">15</option> <option value="30">30</option>
                                    <option value="45">45</option> <option value="60">60</option>
                              </select> min
                              <br>Observación:
                              <input type="text" id="observation_session'.$type.'" maxlength="20" onkeyup="build_session_individual(1,'.$type.','."'".$discipline."'".','.$try.'); return false;">
                        </center>';
                  break;
            case 'off': $filters .= ''; break;
            default: $filters .= '<span style="color:red;font-size:12px;">Por favor, selecciona un disciplina...</span>'; break;
      }
      return $filters.'&&&#is_try_'.$type;//content&&&id_or_class
}
      //////////////
?>
