<?php
      date_default_timezone_set("America/Mexico_City");
    
      sajax_export("load_summary_form");
      sajax_export("load_simple_table_summary");
      sajax_export("load_sessions_table_summary");
      sajax_export("load_complete_sessions");
      sajax_export("load_summary_macro_form");
      sajax_export("load_table_summary");
      sajax_export("load_summary_repeat_sessions");
      sajax_export("load_table_repeat_sessiones");

      function load_summary_form($date_ref, $event_ref, $order){
      $table = $val_f = $val_e = '';
      $lun = $mar = $mie = $jue = $vie = $sab = $dom = $end_w = '';
      if ($event_ref != 'none' && $event_ref != '') { $val_e = $event_ref; }
      if ($date_ref != 'none' && $date_ref != '' && $date_ref != '0000-00-00') { $date_ref = $date_ref; }else{ $date_ref = date('Y-m-d'); }
      $val_f = $date_ref;
      ////DAYS WEEK
      $date_aux = new DateTime($date_ref);
      $week = $date_aux->format("W");
      $year = $date_aux->format("Y");
      for($day=1; $day<=7; $day++){
          switch ($day) {
                case 1: $lun = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 2: $mar = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 3: $mie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 4: $jue = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 5: $vie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 6: $sab = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 7: $dom = date('d-M-y', strtotime($year."W".$week.$day))."\n"; 
                        $end_w = date('d-M', strtotime($year."W".$week.$day))."\n"; break;
                default: break;
          }
      }
      $table = '  <div class="widget">
                        <h4 class="widgettitle">Resumen Semanal </h4>
                        <div class="widgetcontent">
                              <div id="load_table_template"></div>
                                 <div id="summary_table_content"><!--CONTENDOR PARA MOSTRAR EL RESUMEN Y SUS FILTROS-->
                                    <div class="form-group form-inline">
                                    <center>
                                          '.build_select_db('Events', 'name_e', 'id_e', 'event_ref', 'Grupo ', 0, 'master = "0"', 'width:20%;', '', '3', $val_e, 0).'
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <label for="date_event">Fecha </label>
                                          <input id="date_ref" type="text" placeholder="yyyy/mm/dd" name="date" class="datepicker form-control" style="width:20%;" value="'.$val_f.'"/>
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <label>
                                                <div id="week_and_ends">Semana: '.$week/*($week+1)*/.' | Termina en: '.$end_w.' </div>
                                          </label>
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <button class="btn btn-primary" type="button" onclick="load_simple_table_summary('."''".'); return false;">Actualizar Info.</button>
                                    </center>
                                    </div>
                              <div id="summary_table_individual"><!--CONTENEDOR DE TABLA CON RESUMEN-->
                              </div><!--TERMIAN TABLA CON RESUMEN-->
                        </div><!--TERMINA CONTENDOR PARA MOSTRAR EL RESUMEN Y SUS FILTROS-->
                  </div>
            </div>';
      return $table.'&&&.row';
}
      ////////////
      function load_simple_table_summary($date_ref, $event_ref, $order, $begin){
// Se verifica que las fechas de los templates circulares esten actuales, de lo contrario se actualizan
      $temp_rounds = update_gral('`Events`',
                                 'date_e=DATE_ADD(date_e, INTERVAL (weeks-1) WEEK)',
                                 'type IN(11,12,13) AND master="1" AND date_e < NOW()');
      // - - - - - - - - - - / / / - - - - - - - - - - //
      $image_order_f = $image_order_t = $image_order_e = 'up01.png';
      if ($order == '') {
            $order = 'T.name_t,E.name_e,E.date_e,L.id_l';
      }else{
            $order = $order.', L.id_l ASC';
            $order_id = explode(' ', $order);
            if ( $order_id[0] == 'E.date_e') { $order = 'E.date_e,E.name_e,T.name_t, L.id_l ASC'; }
            if ( $order_id[0] == 'T.name_t') { $order = 'T.name_t,E.name_e,E.date_e, L.id_l ASC'; }
            if ( $order_id[0] == 'E.name_e') { $order = 'E.name_e,T.name_t,E.date_e, L.id_l ASC'; }

            if ( $order_id[0] == 'E.date_e' && $order_id[1] == 'DESC' ) { $image_order_f = 'down01.png'; }
            if ( $order_id[0] == 'T.name_t' && $order_id[1] == 'DESC' ) { $image_order_t = 'down01.png'; }
            if ( $order_id[0] == 'E.name_e' && $order_id[1] == 'DESC' ) { $image_order_e = 'down01.png'; }
      }
      ////
      $aditional_where = $val_f = $val_e = $event_sel = '';
      $lun = $mar = $mie = $jue = $vie = $sab = $dom = $end_w = '';
      if ($event_ref != 'none' && $event_ref != '') {
            $event_sel = ' AND E.id_e = "'.$event_ref.'" ';
            $val_e = $event_ref;
      }
      if ($date_ref != 'none' && $date_ref != '' && $date_ref != '0000-00-00') {
            $date_ref = $date_ref;
            $dte = strtotime($date_ref);
            $dte = date('l', $dte);
            if ($dte == 'Sunday') {
                  $date_ref = date('Y-m-d', strtotime($date_ref . ' -1 day'));
            }
            $date_ref = date("Y-m-d", strtotime('next Sunday', strtotime($date_ref)));
      }else{
            $date_ref = date('Y-m-d');
            $dte = strtotime($date_ref);
            $dte = date('l', $dte);
            if ($dte == 'Sunday') {
                  $date_ref = date('Y-m-d', strtotime($date_ref . ' -1 day'));
            }
            $date_ref = date("Y-m-d", strtotime('next Sunday', strtotime(date('Y-m-d'))));
      }
      $val_f = $date_ref;
      ////DAYS WEEK
      $date_aux = new DateTime($date_ref);
      $week = $date_aux->format("W");
      $year = $date_aux->format("Y");
      for($day=1; $day<=7; $day++){
          switch ($day) {
                case 1: $lun = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 2: $mar = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 3: $mie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 4: $jue = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 5: $vie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 6: $sab = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 7: $dom = date('d-M-y', strtotime($year."W".$week.$day))."\n"; 
                        $end_w = date('d-M', strtotime($year."W".$week.$day))."\n"; break;
                default: break;
          }
      }
      if ($event_ref != 'none' && $event_ref != '') {
            $trains_week = call_store_procedure_determined("get_summary_simple",array($date_ref,$order),"16"); //"get_summary_trainings_week_event", event_ref
      }else{
            $trains_week = call_store_procedure_determined("get_summary_simple",array($date_ref,$order),"16");//"get_summary_trainings_week"
            # EL ULTIMO CAMPO INDICA EL NUMERO DE COLUMNAS QUE DEVOLVERA LA CONSULTA
      }
      $table = $table_ses = $table_notes = ''; $tab1 = $tab2 = '';
      $sess_aux1 = $sess_aux2 = $sess_aux3 = $sess_aux4 = $sess_aux5 = $sess_aux6 = $sess_aux7 = '';

      $tab1 = '   <div id="table_sessions" style="display: none;"><!-- class="scrollable_t_s"-->
                        <table id="t_ex" class="table table-bordered summary_table trainings">
                                    <thead>
                                          <tr>
                                                <th title="Status" style="">Status</th>
                                                <th title="Fecha de Arranque" style="">F.A.</th>
                                                <th title="Ordenar por Fecha de Evento" style="">F.E.</th>
                                                <th title="Ciclo Semanal" style="">C.S.</th>
                                                <th class="g" title="Datos" style="">Datos</th>
                                                <th title="Semana" style="">Sem.</th>
                                                <th title="Ordenar por Tipo" style="">Tipo</th>
                                                <th title="Ordenar por Grupo" style="">Grupo</th>
                                                <th title="Nivel" style="">Nivel</th>
                                                <th class="a" colspan="3" style="position: inherit;">Lunes / Monday</th>
                                                <th colspan="3" style="position: inherit;">Martes / Tuesday</th>
                                                <th class="a" colspan="3" style="position: inherit;">Miercoles / Wednesday</th>
                                                <th colspan="3" style="position: inherit;">Jueves / Thursday</th>
                                                <th class="a" colspan="3" style="position: inherit;">Viernes / Friday</th>
                                                <th colspan="3" style="position: inherit;">Sabado / Saturday</th>
                                                <th class="a" colspan="3" style="position: inherit;">Domingo / Sunday</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <tr class="c">
                                                <td class="d" style=""> </td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td colspan="3" style="position: inherit;">'.$lun.'</td>
                                                <td colspan="3" style="position: inherit;">'.$mar.'</td>
                                                <td colspan="3" style="position: inherit;">'.$mie.'</td>
                                                <td colspan="3" style="position: inherit;">'.$jue.'</td>
                                                <td colspan="3" style="position: inherit;">'.$vie.'</td>
                                                <td colspan="3" style="position: inherit;">'.$sab.'</td>
                                                <td colspan="3" style="position: inherit;">'.$dom.'</td>
                                          </tr>
                                          <tr class="c">
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td style="position: inherit;">Session 1</td>
                                                <td style="position: inherit;">Session 2</td>
                                                <td style="position: inherit;">Session 3</td>
                                                <td style="position: inherit;">Session 1</td>
                                                <td style="position: inherit;">Session 2</td>
                                                <td style="position: inherit;">Session 3</td>
                                                <td style="position: inherit;">Session 1</td>
                                                <td style="position: inherit;">Session 2</td>
                                                <td style="position: inherit;">Session 3</td>
                                                <td style="position: inherit;">Session 1</td>
                                                <td style="position: inherit;">Session 2</td>
                                                <td style="position: inherit;">Session 3</td>
                                                <td style="position: inherit;">Session 1</td>
                                                <td style="position: inherit;">Session 2</td>
                                                <td style="position: inherit;">Session 3</td>
                                                <td style="position: inherit;">Session 1</td>
                                                <td style="position: inherit;">Session 2</td>
                                                <td style="position: inherit;">Session 3</td>
                                                <td style="position: inherit;">Session 1</td>
                                                <td style="position: inherit;">Session 2</td>
                                                <td style="position: inherit;">Session 3</td>
                                          </tr>';
                                          
      $tab2 = '   <div id="pinned"><!-- class="pinned_t_s"-->
                        <table id="t_ex" class="table table-bordered summary_table">
                                    <thead>
                                          <tr style="height: 37px;">
                                                <th title="Status">Status</th>
                                                <th title="Fecha de Arranque">F.A.</th>
                                                <th title="Fecha de Evento"><!--Ordenar por -->
                                                <!--<img id="order_s_f" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png">-->
                                                F.E.</th>
                                                <th title="Ciclo Semanal">C.S.</th>
                                                <th title="Semana">Sem.</th>
                                                <th title="Tipo"><!--Ordenar por -->
                                                <!--<img id="order_s_t" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png">-->
                                                Tipo</th>
                                                <th title="Nombre del Grupo"><!--Ordenar por -->
                                                <!--<img id="order_s_e" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png">-->
                                                Nombre del Grupo</th>
                                                <th title="Nombre especifico del grupo">Grupo</th>
                                                <th title="Nivel">Nivel</th>
                                                <th style="text-align: center;" class="expand_info">
                                                      <a href="#" id="" onclick="load_complete_sessions('."'".$order."'".'); return false;">
                                                            <i class="glyphicon glyphicon-arrow-right" title="Muestra los Entrenamientos Semanales de Todos los Grupos" style="cursor: pointer;"></i>
                                                      </a>
                                                </th>
                                                <th></th><th></th>
                                                <th class="a" colspan="3" style="display: none;">Lunes / Monday</th>
                                                <th colspan="3" style="display: none;">Martes / Tuesday</th>
                                                <th class="a" colspan="3" style="display: none;">Miercoles / Wednesday</th>
                                                <th colspan="3" style="display: none;">Jueves / Thursday</th>
                                                <th class="a" colspan="3" style="display: none;">Viernes / Friday</th>
                                                <th colspan="3" style="display: none;">Sabado / Saturday</th>
                                                <th class="a" colspan="3" style="display: none;">Domingo / Sunday</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <tr class="c" style="height: 37px;">
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td class="d expand_info"></td>
                                                <td class="d"></td><td class="d"></td>
                                                <td colspan="3" style="display: none;">'.$lun.'</td>
                                                <td colspan="3" style="display: none;">'.$mar.'</td>
                                                <td colspan="3" style="display: none;">'.$mie.'</td>
                                                <td colspan="3" style="display: none;">'.$jue.'</td>
                                                <td colspan="3" style="display: none;">'.$vie.'</td>
                                                <td colspan="3" style="display: none;">'.$sab.'</td>
                                                <td colspan="3" style="display: none;">'.$dom.'</td>
                                          </tr>
                                          <tr class="c" style="height: 37px;">
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td class="d expand_info"></td>
                                                <td class="d"><a id="check_box_all" name="check_box_all" onclick="check_box_all('."'event_summ'".'); return false;" style="color: rgb(0,128,0); cursor:pointer;"><i class="glyphicon glyphicon-ok"> </i></a></td>
                                                <td class="d">
                                                </td><td class="d"></td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                          </tr>
                                          ';
      $del_level_option = '';
      if ($trains_week == true) {
            $aux_expl = $name_aux = "";
            $j = 1;
            $changes_json = 0;
            foreach ($trains_week as $key) {
                  $summary_values = '';
                  $bac_past = '';
                  $exist_json = select_gral('`Summ_sended`', 'COUNT(*) AS exist', 'id_m="'.$key[1].'" AND week="'.$key[9].'"', 'exist');
                  if ($exist_json == true) { foreach ($exist_json as $jsn) { $changes_json = $changes_json+intval($jsn[0]); } }
                  (utf8_encode($key[7]) == 'PASADO') ? $bac_past = 'background-color: FFF9E6;' : $bac_past = '';
                        //////RELLENAMOS FILAS DE TABLA 1
                        $tab1 .= '<tr style="'.$bac_past.'" id="row_'.$j.'">';
                        $tab1 .= '  <td style="">'.utf8_encode($key[7]).'</td>';
                        $tab1 .= '  <td style="">'.date('d M', strtotime(utf8_encode($key[4]))).'</td>';
                        $tab1 .= '  <td style="">'.date('d M', strtotime(utf8_encode($key[5]))).'</td>';
                        $tab1 .= '  <td style="">'.utf8_encode($key[12]).'</td>';
                        $tab1 .= '  <td style="">'.$summary_values.'</td>';
                        $tab1 .= '  <td style="">Sem '.utf8_encode($key[9]).'</td>';
                        $tab1 .= '  <td style="">'.strtoupper(utf8_encode($key[10])).'</td>';
                        $tab1 .= '  <td style="">'.utf8_encode($key[3]).'</td>';

                        if ($key[14] == "0" AND $key[15] != "11" AND $key[15] != "12" AND $key[15] != "13") {
                              $del_level_option = '<sup title="Remover este nivel" style="cursor: pointer; color:red;" onclick="confirm_deallocate_level('.$key[0].','.$key[13].','.$j.'); return false;"><b>X</b></sup>';
                        }elseif ($key[15] == "11" OR $key[15] == "12" OR $key[15] == "13") {
                              $del_level_option = '<sup title="Ocultar este nivel" style="cursor: pointer;" onclick="confirm_hidde_level('.$key[0].','.$key[13].'); return false;"><img src="./img/images/prohibition_sign.png" style="width: 10px;"/></sup>';
                        }else{ $del_level_option = ''; }
                        $tab1 .= '  <td style="">'.$del_level_option.' '.utf8_encode($key[11]).'</td>';
                        $note01 = $note02 = $note03 = $note04 = $note05 = $note06 = $note07 = $note08 = $note09 = $note10 = $note11 = '';
                        $note12 = $note13 = $note14 = $note15 = $note16 = $note17 = $note18 = $note19 = $note20 = $note21 = '';
                        $notes01 = $notes02 = $notes03 = $notes04 = $notes05 = $notes06 = $notes07 = $notes08 = $notes09 = $notes10 = $notes11 = '';
                        $notes12 = $notes13 = $notes14 = $notes15 = $notes16 = $notes17 = $notes18 = $notes19 = $notes20 = $notes21 = '';
                        $style_notes     = 'width:18px; vertical-align: bottom; text-align: right; cursor:pointer; ';
                        $image_direction = './img/images/notes_.png';
                        $type_event_note = '2';
                        $is_temporary = select_gral('`Events`', 'temporary', 'id_e="'.$key[0].'"', 'temporary');
                        if ($is_temporary == true) { foreach ($is_temporary as $i_t) { $is_temporary = intval($i_t[0]); } }
                        # SI ES EVENTO SECUNDRIO
                        if ($is_temporary > 0) { $type_event_note = '3'; }
                        ($key[9] > 9) ? $space = '&nbsp;&nbsp;&nbsp;&nbsp;' : $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        $tab1 .= '  <td style="">some'.'</td>';
                        # PROPUESTA 1
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_1_1"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_1_2"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_1_3"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_2_1"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_2_2"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_2_3"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_3_1"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_3_2"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_3_3"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_4_1"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_4_2"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_4_3"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_5_1"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_5_2"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_5_3"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_6_1"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_6_2"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_6_3"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_7_1"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_7_2"></td>';
                        $tab1 .= '  <td style="position: inherit;" class="session_trains" id="'.$j.'_7_3"></td>
                              <!-- A LAS 21 INPUTS SE LES ELIMINO EL ATRIBUTO: width: 1px;
                                   SE CAMBIO POR width: 100%;-->
                              </tr>';
                        //////TERMINA RELLENO DE TABLA 1
                        //////RELLENAMOS FILAS DE TABLA 2
                        $is_blocked = select_gral('`Blocked_groups`', 'count(*) as n,id_bg,user,reason', 'id_e="'.$key[0].'" AND week="'.utf8_encode($key[9]).'"', 'n');
                        $icon_block = $color_blck = $function_b = $id_bg = $user_bg = $reason_b = "";
                        if ($is_blocked == true) {
                              foreach ($is_blocked as $keyb) { $is_blocked=intval($keyb[0]); $id_bg=intval($keyb[1]); $user_bg=$keyb[2]; $reason_b=$keyb[3]; } 
                        }
                        if ($is_blocked == 0) {
                              $icon_block = "glyphicon-ok-circle";
                              $color_blck = "rgb(0,128,0)";
                              $function_b = 'show_popup_block_group('."'".utf8_encode($key[9])."','".utf8_encode($key[3])."','".$key[0]."','".$j."','0','','','','1');";
                        }else{
                              $icon_block = "glyphicon-ban-circle";
                              $color_blck = "rgb(234,39,52)";
                              $function_b = "show_popup_block_group('".utf8_encode($key[9])."','".utf8_encode($key[3])."','0','".$j."','".$id_bg."','".$user_bg."','".$reason_b."','".$_SESSION['name_u']."','2');";
                              if ($bac_past == "") { $bac_past = 'background-color: lightgray;'; }
                        }

                        $tab2 .= '<tr style="height: 39px; '.$bac_past.'" id="row_'.$j.'">';
                        $tab2 .= '  <td>'.utf8_encode($key[7]).'</td>
                                    <td>'.date('d M', strtotime(utf8_encode($key[4]))).'</td>
                                    <td>'.date('d M', strtotime(utf8_encode($key[5]))).'</td>
                                    <td>'.utf8_encode($key[12]).'</td>';
                        /* * * * * * * * * * * * * */ $note_weekly = '';
                        $tab2 .= '  <td>Sem '.utf8_encode($key[9]).' '.$note_weekly.'</td>';
                        $name_aux = strtoupper(utf8_encode($key[10]));
                        if ($name_aux == "NULL") {
                              $name_aux = explode(".", $key[3]);
                              if (count($name_aux) == 5 || count($name_aux) == 6){ $name_aux = strtoupper($name_aux[1]); }else{ $name_aux = "NULL"; }
                        }
                        $tab2 .= '  <td><b style="font-size:15px;">'.strtoupper(utf8_encode($key[10])).'</b></td>';
                        $aux_send = select_gral('`Summ_sended`', 'count(*) as n', 'id_m="'.$key[1].'" AND week="'.$key[9].'"', 'n');
                        if ($aux_send == true) { foreach ($aux_send as $as) { $aux_send = $as[0]; } }else{ $aux_send = 1; }
                        ($aux_send == 1) ? $style_json = "visibility:visible;" : $style_json = "visibility:hidden";
                        $tab2 .= '  <td>
                                          <sup title="Grupo enviado a la plataforma" class="s_json" id="s_json_'.$j.'" style="'.$style_json.'"><b class="glyphicon glyphicon-ok" style="color:#000000;"></b></sup>
                                          <a onclick="show_event_sessions_aux('.$key[0].','.$key[13].','.$key[1].','.$key[2].'); return false;" href="#">'.utf8_encode($key[3]).'</a></td>';
                        $aux_expl = explode(".", $key[3]);
                        if (count($aux_expl) == 5 || count($aux_expl) == 6) {
                              $tab2 .= '  <td id="'.$j.'">'.utf8_encode($aux_expl[2]).' ('.utf8_encode($aux_expl[3]).')</td>';
                        }else{
                              $tab2 .= '  <td id="'.$j.'">'.utf8_encode($key[3]).'</td>';
                        }
                        $tab2 .= '  <td>'.$del_level_option.' '.utf8_encode($key[11]).'</td>';
                        $aux_order = str_replace(",", "ccoommaa", $order);
                        $tab2 .= '  <td style="text-align: center;" class="expand_info">
                                          <a href="#down_explain" id="'.$key[0].'" onclick="load_sessions_table_summary('.$key[0].','."'".$order."'".');">
                                                <i class="glyphicon glyphicon-plus-sign" title="Muestra Entrenamientos Semanales de este Grupo" style="cursor: pointer;"></i>
                                          </a> 
                                    </td>';
                        $tab2 .= '  <td style=""><input type="checkbox" class="event_summ" name="id_event_res_'.$j.'" id="'.$j.'" checked /></td>';
                        $tab2 .= '  <td style="text-align: center;" class="expand_info">
                                          <a id="'.$key[0].'_'.$j.'" onclick="'.$function_b.'">
                                                <i id="block_icon_'.$j.'" class="glyphicon '.$icon_block.'" title="Bloquear/Liberar Grupo" style="color: '.$color_blck.'; cursor: pointer;"></i>
                                          </a> <!--glyphicon-ban-circle-->
                                    </td>';
                        $tab2 .= '  <td id="'.$j.'_1_1_2" style="display: none;" ></td>
                                    <td id="'.$j.'_1_2_2" style="display: none;" ></td>
                                    <td id="'.$j.'_1_3_2" style="display: none;" ></td>
                                    <td id="'.$j.'_2_1_2" style="display: none;" ></td>
                                    <td id="'.$j.'_2_2_2" style="display: none;" ></td>
                                    <td id="'.$j.'_2_3_2" style="display: none;" ></td>
                                    <td id="'.$j.'_3_1_2" style="display: none;" ></td>
                                    <td id="'.$j.'_3_2_2" style="display: none;" ></td>
                                    <td id="'.$j.'_3_3_2" style="display: none;" ></td>
                                    <td id="'.$j.'_4_1_2" style="display: none;" ></td>
                                    <td id="'.$j.'_4_2_2" style="display: none;" ></td>
                                    <td id="'.$j.'_4_3_2" style="display: none;" ></td>
                                    <td id="'.$j.'_5_1_2" style="display: none;" ></td>
                                    <td id="'.$j.'_5_2_2" style="display: none;" ></td>
                                    <td id="'.$j.'_5_3_2" style="display: none;" ></td>
                                    <td id="'.$j.'_6_1_2" style="display: none;" ></td>
                                    <td id="'.$j.'_6_2_2" style="display: none;" ></td>
                                    <td id="'.$j.'_6_3_2" style="display: none;" ></td>
                                    <td id="'.$j.'_7_1_2" style="display: none;" ></td>
                                    <td id="'.$j.'_7_2_2" style="display: none;" ></td>
                                    <td id="'.$j.'_7_3_2" style="display: none;" ></td>
                              </tr>';

                  ((fmod($j, 2)) == 0) ? $background = '#E6E6E6': $background = '#FFFFFF';
                  $j++;  
            }
      }else{
            $tab1 .= '<tr><td colspan="30"> No hay datos disponibles </td></tr>';
            $tab2 .= '<tr><td colspan="30"> No hay datos disponibles </td></tr>';
      }
      $tab1 .= '</tbody></table></div>';
      $tab2 .= '</tbody></table></div>';
      $tabs = '<hr><div class="table-wrapper_t_s">'.$tab2.$tab1.'</div>';
      $down_show = '
                  <div id="down_explain" class="widget" style="display: none;">
                        <h4 class="widgettitle">Sesiones Semanales del Grupo </h4>
                        <div class="widgetcontent">
                              <div id="close_sessions_event" style="text-align: right;"></div>
                              <div id="load_table_sessions_week_event"></div>
                              <div id="summary_table_content"><!--CONTENDOR PARA MOSTRAR EL RESUMEN Y SUS FILTROS-->
                                    <div id="table_session_week_event"></div><!--CONTENEDOR DE TABLA CON RESUMEN-->
                              </div><!--TERMINA CONTENDOR PARA MOSTRAR EL RESUMEN Y SUS FILTROS-->
                        </div>
                  </div>';
      $table .= $tabs;
      $table .= $down_show;
      $table .=  '
                  <form action="./php/file_xls_down.php" method="post" target="_blank" id="FormularioExportacion">
                              <button class="btn botonExcel" type="button" onclick="button_export_function('."'".'t_ex'."'".','."'".'FormularioExportacion'."'".','."'".'summary'."'".');" disabled>Exportar a Excel</button>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <button class="btn botonExcel" type="button" onclick="button_export_function('."'".'table_sess'."'".','."'".'FormularioExportacion'."'".','."'".'sessions'."'".');" disabled>Descargar Sesiones</button>
                              <!--
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <button class="btn botonExcel" type="button" onclick="button_export_function('."'".'table_notes'."'".','."'".'FormularioExportacion'."'".','."'".'notes'."'".');">Descargar Notas de Sesiones</button>
                              -->
                              <input type="hidden" id="info_xls" name="info_xls"/>
                              <input type="hidden" id="name_xls" name="name_xls"/>
                  </form>
                  <div id="sessions_table_div">'.$table_ses.'</div>'.$table_notes;
      $return = '';
      //BOTON PARA MODIFICAR ANCHO EN TABLA
      $width_button = '';
      $width_button = '
            <div>
                  <br><div style="text-align:left; float:left;">';
      $weeks = $j-1;
      if (($weeks) == $changes_json) {
            $width_button .= '<input type="button" id="json_button" class="btn btn-primary" value="Reenviar Json" onclick="load_complete_json_file('."0".','.$weeks.');" />';
      }else{
            if ($changes_json > 0) {
                  $width_button .= '<input type="button" id="json_button" class="btn btn-primary" value="Json, Cambios Parciales" onclick="load_complete_json_file('."1".','.$weeks.');" />&nbsp;&nbsp;
                  <input type="button" id="json_button" class="btn btn-primary" value="Json Completo" onclick="load_complete_json_file('."0".','.$weeks.');" />';
            }else{
                  $width_button .= '<input type="button" id="json_button" class="btn btn-primary" value="Generar y Enviar Json" onclick="load_complete_json_file('."0".','.$weeks.');" />';
            }
      }
      $width_button .= '<input type="hidden" id="json_content" />
                  </div>
                  <div style="text-align:right;"><button class="btn width_button" type="button" onclick="modify_width_tables('."'summary'".');" disabled>Ampliar Sesiones</button><br></div><br>
            </div>';
      $table = $width_button.$table;
      //TERMINA BOTON PARA MODIFICAR ANCHO EN BOTON
      $table_drag = '';
      $table .= '<hr>'.$table_drag;
      $table .= ' <!-- Ventana emregente -->
                        <div id="hider_3" onclick="close_popup(3); return false;"></div>
                        <div id="popup_box_3" style="width:40%;">
                            <a class="close" onclick="close_popup(3); return false;">X</a>
                            <br>
                            <div class="message_box_3"></div>
                        </div>
                  <!-- ------ -->
                  <input type="hidden" id="end_ww" value="'.$end_w.'">';
      ($begin != 0) ? $return = '28@-@'.$table.'@-@Semana: '.$week/*($week+1)*/.' | Termina en: '.$end_w : $return = $table;
      return $return;
}
      ////////////
      function load_sessions_table_summary($event_ref, $date_ref, $order){
      $table = ""; $begin = 0;
      // Se verifica que las fechas de los templates circulares esten actuales, de lo contrario se actualizan
      $temp_rounds = update_gral('`Events`',
                                 'date_e=DATE_ADD(date_e, INTERVAL (weeks-1) WEEK)',
                                 'type IN(11,12,13) AND master="1" AND date_e < NOW()');
      $image_order_f = $image_order_t = $image_order_e = 'up01.png';
      if ($order == '') {
            $order = 'T.name_t,E.name_e,E.date_e,L.id_l';
      }else{
            $order = $order.', L.id_l ASC';
            $order_id = explode(' ', $order);
            if ( $order_id[0] == 'E.date_e') { $order = 'E.date_e,E.name_e,T.name_t, L.id_l ASC'; }
            if ( $order_id[0] == 'T.name_t') { $order = 'T.name_t,E.name_e,E.date_e, L.id_l ASC'; }
            if ( $order_id[0] == 'E.name_e') { $order = 'E.name_e,T.name_t,E.date_e, L.id_l ASC'; }

            if ( $order_id[0] == 'E.date_e' && $order_id[1] == 'DESC' ) { $image_order_f = 'down01.png'; }
            if ( $order_id[0] == 'T.name_t' && $order_id[1] == 'DESC' ) { $image_order_t = 'down01.png'; }
            if ( $order_id[0] == 'E.name_e' && $order_id[1] == 'DESC' ) { $image_order_e = 'down01.png'; }
      }
      $aditional_where = $val_f = $val_e = $event_sel = ''; $lun = $mar = $mie = $jue = $vie = $sab = $dom = $end_w = '';
      if ($event_ref != 'none' && $event_ref != '') { $event_sel = ' AND E.id_e = "'.$event_ref.'" '; $val_e = $event_ref; }
      if ($date_ref != 'none' && $date_ref != '' && $date_ref != '0000-00-00') {
            $date_ref = $date_ref;
            $dte = strtotime($date_ref);
            $dte = date('l', $dte);
            if ($dte == 'Sunday') { $date_ref = date('Y-m-d', strtotime($date_ref . ' -1 day')); }
            $date_ref = date("Y-m-d", strtotime('next Sunday', strtotime($date_ref)));
      }else{
            $date_ref = date('Y-m-d');
            $dte = strtotime($date_ref);
            $dte = date('l', $dte);
            if ($dte == 'Sunday') { $date_ref = date('Y-m-d', strtotime($date_ref . ' -1 day')); }
            $date_ref = date("Y-m-d", strtotime('next Sunday', strtotime(date('Y-m-d'))));
      }
      $val_f = $date_ref;
      ////DAYS WEEK
      $date_aux = new DateTime($date_ref);
      $week = $date_aux->format("W");
      $year = $date_aux->format("Y");
      for($day=1; $day<=7; $day++){
          switch ($day) {
                case 1: $lun = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 2: $mar = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 3: $mie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 4: $jue = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 5: $vie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 6: $sab = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 7: $dom = date('d-M-y', strtotime($year."W".$week.$day))."\n"; 
                        $end_w = date('d-M', strtotime($year."W".$week.$day))."\n"; break;
                
                default: break;
          }
      }
      $trains_week = call_store_procedure("get_summary_trainings_week_event",array($date_ref,$order,$event_ref));
      $table = $table_ses = $table_notes = ''; $sess_aux1 = $sess_aux2 = $sess_aux3 = $sess_aux4 = $sess_aux5 = $sess_aux6 = $sess_aux7 = '';
      //FILTER VA EN El FINAL
      $tab1 = '   <table class="table table-bordered summary_table trainings"><!-- id="t_ex"-->
                        <thead>
                              <tr>
                                    <th title="Status" style="">Status</th>
                                    <th title="Fecha de Arranque" style="">F.A.</th>
                                    <th title="Ordenar por Fecha de Evento" style="">
                                          <img id="order_s_f" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png"> F.E.</th>
                                    <th title="Ciclo Semanal" style="">C.S.</th>
                                    <th class="g" title="Datos" style="">Datos</th>
                                    <th title="Semana" style="">Sem.</th>
                                    <th title="Ordenar por Tipo" style="">
                                          <img id="order_s_t" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png"> Tipo</th>
                                    <th title="Ordenar por Grupo" style="">
                                          <img id="order_s_e" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png"> Grupo</th>
                                    <th title="Nivel" style="">Nivel</th>
                                    <th title="Nivel" style="">Nivel</th>
                                    <th class="a" colspan="3">Lunes / Monday</th>
                                    <th colspan="3">Martes / Tuesday</th>
                                    <th class="a" colspan="3">Miercoles / Wednesday</th>
                                    <th colspan="3">Jueves / Thursday</th>
                                    <th class="a" colspan="3">Viernes / Friday</th>
                                    <th colspan="3">Sabado / Saturday</th>
                                    <th class="a" colspan="3">Domingo / Sunday</th>
                              </tr>
                        </thead>
                        <tbody>
                              <tr class="c">
                                    <td class="d" style=""> </td><td class="d" style=""></td><td class="d" style=""></td>
                                    <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                    <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                    <td class="c"></td>
                                    <td colspan="3">'.$lun.'</td>
                                    <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$mar.'</td>
                                    <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$mie.'</td>
                                    <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$jue.'</td>
                                    <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$vie.'</td>
                                    <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$sab.'</td>
                                    <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$dom.'</td>
                              </tr>
                              <tr class="c">
                                    <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                    <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                    <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                    <td class="c"></td>
                                    <td>Session 1</td><td>Session 2</td><td>Session 3</td>
                                    <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                    <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                    <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                    <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                    <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                    <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                              </tr>';
      $del_level_option = '';
      if ($trains_week == true) {
            $j = 1;
            foreach ($trains_week as $key) {
                  $summary_values = '';
                  $key[13] = explode('|', $key[13]); $key[14] = explode('|', $key[14]); $key[15] = explode('|', $key[15]);
                  $key[16] = explode('|', $key[16]); $key[17] = explode('|', $key[17]); $key[18] = explode('|', $key[18]);
                  $key[19] = explode('|', $key[19]); $key[20] = explode('|', $key[20]); $key[21] = explode('|', $key[21]);
                  $key[22] = explode('|', $key[22]); $key[23] = explode('|', $key[23]); $key[24] = explode('|', $key[24]);
                  $key[25] = explode('|', $key[25]); $key[26] = explode('|', $key[26]); $key[27] = explode('|', $key[27]);
                  $key[28] = explode('|', $key[28]); $key[29] = explode('|', $key[29]); $key[30] = explode('|', $key[30]);
                  $key[31] = explode('|', $key[31]); $key[32] = explode('|', $key[32]); $key[33] = explode('|', $key[33]);
                  if($key[13][0] == '') { $key[13][0] = '-'; $key[13][3] = 0; $key[13][6] = 0; $key[13][9] = 0; }
                  if($key[14][0] == '') { $key[14][0] = '-'; $key[14][3] = 0; $key[14][6] = 0; $key[14][9] = 0; }
                  if($key[15][0] == '') { $key[15][0] = '-'; $key[15][3] = 0; $key[15][6] = 0; $key[15][9] = 0; }
                  if($key[16][0] == '') { $key[16][0] = '-'; $key[16][3] = 0; $key[16][6] = 0; $key[16][9] = 0; }
                  if($key[17][0] == '') { $key[17][0] = '-'; $key[17][3] = 0; $key[17][6] = 0; $key[17][9] = 0; }
                  if($key[18][0] == '') { $key[18][0] = '-'; $key[18][3] = 0; $key[18][6] = 0; $key[18][9] = 0; }
                  if($key[19][0] == '') { $key[19][0] = '-'; $key[19][3] = 0; $key[19][6] = 0; $key[19][9] = 0; }
                  if($key[20][0] == '') { $key[20][0] = '-'; $key[20][3] = 0; $key[20][6] = 0; $key[20][9] = 0; }
                  if($key[21][0] == '') { $key[21][0] = '-'; $key[21][3] = 0; $key[21][6] = 0; $key[21][9] = 0; }
                  if($key[22][0] == '') { $key[22][0] = '-'; $key[22][3] = 0; $key[22][6] = 0; $key[22][9] = 0; }
                  if($key[23][0] == '') { $key[23][0] = '-'; $key[23][3] = 0; $key[23][6] = 0; $key[23][9] = 0; }
                  if($key[24][0] == '') { $key[24][0] = '-'; $key[24][3] = 0; $key[24][6] = 0; $key[24][9] = 0; }
                  if($key[25][0] == '') { $key[25][0] = '-'; $key[25][3] = 0; $key[25][6] = 0; $key[25][9] = 0; }
                  if($key[26][0] == '') { $key[26][0] = '-'; $key[26][3] = 0; $key[26][6] = 0; $key[26][9] = 0; }
                  if($key[27][0] == '') { $key[27][0] = '-'; $key[27][3] = 0; $key[27][6] = 0; $key[27][9] = 0; }
                  if($key[28][0] == '') { $key[28][0] = '-'; $key[28][3] = 0; $key[28][6] = 0; $key[28][9] = 0; }
                  if($key[29][0] == '') { $key[29][0] = '-'; $key[29][3] = 0; $key[29][6] = 0; $key[29][9] = 0; }
                  if($key[30][0] == '') { $key[30][0] = '-'; $key[30][3] = 0; $key[30][6] = 0; $key[30][9] = 0; }
                  if($key[31][0] == '') { $key[31][0] = '-'; $key[31][3] = 0; $key[31][6] = 0; $key[31][9] = 0; }
                  if($key[32][0] == '') { $key[32][0] = '-'; $key[32][3] = 0; $key[32][6] = 0; $key[32][9] = 0; }
                  if($key[33][0] == '') { $key[33][0] = '-'; $key[33][3] = 0; $key[33][6] = 0; $key[33][9] = 0; }
                  $all_tsss = $key[13][3]+$key[14][3]+$key[15][3]+$key[16][3]+$key[17][3]+$key[18][3]+$key[19][3]+$key[20][3]+$key[21][3]+$key[22][3]+$key[23][3]+$key[24][3]+$key[25][3]+$key[26][3]+$key[27][3]+$key[28][3]+$key[29][3]+$key[30][3]+$key[31][3]+$key[32][3]+$key[33][3];
                  $all_tssb = $key[13][6]+$key[14][6]+$key[15][6]+$key[16][6]+$key[17][6]+$key[18][6]+$key[19][6]+$key[20][6]+$key[21][6]+$key[22][6]+$key[23][6]+$key[24][6]+$key[25][6]+$key[26][6]+$key[27][6]+$key[28][6]+$key[29][6]+$key[30][6]+$key[31][6]+$key[32][6]+$key[33][6];
                  $all_tssr = $key[13][9]+$key[14][9]+$key[15][9]+$key[16][9]+$key[17][9]+$key[18][9]+$key[19][9]+$key[20][9]+$key[21][9]+$key[22][9]+$key[23][9]+$key[24][9]+$key[25][9]+$key[26][9]+$key[27][9]+$key[28][9]+$key[29][9]+$key[30][9]+$key[31][9]+$key[32][9]+$key[33][9];
                  if ($all_tssr > 0) { $res1 = round(($all_tssb/$all_tssr),2); }else{ $res1 = 0; }//round(($all_tsss+$all_tssb+$all_tssr)/7)
                  if ($res1 < 2) { $summary_values = '↑ B/R ↓ → '.$res1.' | '.round(($all_tsss+$all_tssb+$all_tssr)/7).' TSS/día'; }
                  if ($res1 == 2) { $summary_values = 'B/R → '.$res1.' | '.round(($all_tsss+$all_tssb+$all_tssr)/7).' TSS/día'; }
                  if ($res1 > 2) { $summary_values = '↓ B/R ↑ → '.$res1.' | '.round(($all_tsss+$all_tssb+$all_tssr)/7).' TSS/día'; }
                  $bac_past = '';
                  (utf8_encode($key[7]) == 'PASADO') ? $bac_past = 'background-color: FFF9E6;' : $bac_past = '';
                        //////RELLENAMOS FILAS DE TABLA 1
                        $tab1 .= '<tr style="'.$bac_past.'">';
                        $tab1 .= '  <td style="">'.utf8_encode($key[7]).'</td>';
                        $tab1 .= '  <td style="">'.date('d M', strtotime(utf8_encode($key[4]))).'</td>';
                        $tab1 .= '  <td style="">'.date('d M', strtotime(utf8_encode($key[5]))).'</td>';
                        $tab1 .= '  <td style="">'.utf8_encode($key[12]).'</td>';
                        $tab1 .= '  <td style="">'.$summary_values.'</td>';
                        $tab1 .= '  <td style="">Sem '.utf8_encode($key[9]).'</td>';
                        $tab1 .= '  <td style=""><b style="font-size:15px;">'.strtoupper(utf8_encode($key[10])).'</b></td>';
                        $tab1 .= '  <td style=""><a onclick="show_event_sessions_aux('.$key[0].','.$key[34].','.$key[1].','.$key[2].'); return false;" href="#">'.utf8_encode($key[3]).'</a></td>';
                        if ($key[14] == "0" AND $key[15] != "11" AND $key[15] != "12" AND $key[15] != "13") {
                              $del_level_option = '<sup title="Remover este nivel" style="cursor: pointer; color:red;" onclick="confirm_deallocate_level('.$key[0].','.$key[13].','.$j.'); return false;"><b>X</b></sup>';
                        }elseif ($key[15] == "11" OR $key[15] == "12" OR $key[15] == "13") {
                              $del_level_option = '<sup title="Ocultar este nivel" style="cursor: pointer;" onclick="confirm_hidde_level('.$key[0].','.$key[13].'); return false;"><img src="./img/images/prohibition_sign.png" style="width: 10px;"/></sup>';
                        }else{ $del_level_option = ''; }
                        $tab1 .= '  <td style="">'.$del_level_option.' '.utf8_encode($key[11]).'</td>';
                        $tab1 .= '  <td>'.utf8_encode($key[11]).'</td>';
                        $note01 = $note02 = $note03 = $note04 = $note05 = $note06 = $note07 = $note08 = $note09 = $note10 = $note11 = '';
                        $note12 = $note13 = $note14 = $note15 = $note16 = $note17 = $note18 = $note19 = $note20 = $note21 = '';
                        $notes01 = $notes02 = $notes03 = $notes04 = $notes05 = $notes06 = $notes07 = $notes08 = $notes09 = $notes10 = $notes11 = '';
                        $notes12 = $notes13 = $notes14 = $notes15 = $notes16 = $notes17 = $notes18 = $notes19 = $notes20 = $notes21 = '';
                        $style_notes     = 'width:18px; vertical-align: bottom; text-align: right; cursor:pointer; ';
                        $image_direction = './img/images/notes_.png';
                        $type_event_note = '2';
                        $is_temporary = select_gral('`Events`', 'temporary', 'id_e="'.$key[0].'"', 'temporary');
                        if ($is_temporary == true) { foreach ($is_temporary as $i_t) { $is_temporary = intval($i_t[0]); } }
                        # SI ES EVENTO SECUNDRIO
                        if ($is_temporary > 0) { $type_event_note = '3'; }
                        ($key[9] > 9) ? $space = '&nbsp;&nbsp;&nbsp;&nbsp;' : $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        $tab1 .= '  <td>'.utf8_encode($key[13][0]).' '.$note01.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[14][0]).' '.$note02.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[15][0]).' '.$note03.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[16][0]).' '.$note04.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[17][0]).' '.$note05.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[18][0]).' '.$note06.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[19][0]).' '.$note07.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[20][0]).' '.$note08.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[21][0]).' '.$note09.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[22][0]).' '.$note10.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[23][0]).' '.$note11.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[24][0]).' '.$note12.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[25][0]).' '.$note13.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[26][0]).' '.$note14.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[27][0]).' '.$note15.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[28][0]).' '.$note16.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[29][0]).' '.$note17.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[30][0]).' '.$note18.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[31][0]).' '.$note19.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[32][0]).' '.$note20.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[33][0]).' '.$note21.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                              </tr>';                      
                        //////TERMINA RELLENO DE TABLA 1
                  $sess_aux1 .= utf8_encode($key[13][0]).'@@@'.utf8_encode($key[14][0]).'@@@'.utf8_encode($key[15][0]).'@@@';
                  $sess_aux2 .= utf8_encode($key[16][0]).'@@@'.utf8_encode($key[17][0]).'@@@'.utf8_encode($key[18][0]).'@@@';
                  $sess_aux3 .= utf8_encode($key[19][0]).'@@@'.utf8_encode($key[20][0]).'@@@'.utf8_encode($key[21][0]).'@@@';
                  $sess_aux4 .= utf8_encode($key[22][0]).'@@@'.utf8_encode($key[23][0]).'@@@'.utf8_encode($key[24][0]).'@@@';
                  $sess_aux5 .= utf8_encode($key[25][0]).'@@@'.utf8_encode($key[26][0]).'@@@'.utf8_encode($key[27][0]).'@@@';
                  $sess_aux6 .= utf8_encode($key[28][0]).'@@@'.utf8_encode($key[29][0]).'@@@'.utf8_encode($key[30][0]).'@@@';
                  $sess_aux7 .= utf8_encode($key[31][0]).'@@@'.utf8_encode($key[32][0]).'@@@'.utf8_encode($key[33][0]).'@@@';
                  ((fmod($j, 2)) == 0) ? $background = '#E6E6E6': $background = '#FFFFFF';
                  $desc_event = '<b>'.strtoupper(utf8_encode($key[10])).' - '.utf8_encode($key[3]).' - '.utf8_encode($key[11]).'</b>';
                  $j++; 
            }
      }else{ $tab1 .= '<tr><td colspan="30"> No hay datos disponibles.</td></tr>'; }
      $tab1 .= '</tbody></table>';
      $table .= '<div style="overflow: auto;"><br>'.$desc_event.'<br>'.$tab1.'</div>';
      $return = '';
      return $table."&&&#load_table_sessions_week_event";
}
      ////////////
      function load_complete_sessions($date_ref, $order){
      $all_sessions = ""; $begin = 0;
      if ($order == '') {
            $order = 'T.name_t,E.name_e,E.date_e,L.id_l';
      }else{
            $order = $order.', L.id_l ASC';
            $order_id = explode(' ', $order);
            if ( $order_id[0] == 'E.date_e') { $order = 'E.date_e,E.name_e,T.name_t, L.id_l ASC'; }
            if ( $order_id[0] == 'T.name_t') { $order = 'T.name_t,E.name_e,E.date_e, L.id_l ASC'; }
            if ( $order_id[0] == 'E.name_e') { $order = 'E.name_e,T.name_t,E.date_e, L.id_l ASC'; }
      }
      if ($date_ref != 'none' && $date_ref != '' && $date_ref != '0000-00-00') {
            $date_ref = $date_ref;
            $dte = strtotime($date_ref);
            $dte = date('l', $dte);
            if ($dte == 'Sunday') { $date_ref = date('Y-m-d', strtotime($date_ref . ' -1 day')); }
            $date_ref = date("Y-m-d", strtotime('next Sunday', strtotime($date_ref)));
      }else{
            $date_ref = date('Y-m-d');
            $dte = strtotime($date_ref);
            $dte = date('l', $dte);
            if ($dte == 'Sunday') { $date_ref = date('Y-m-d', strtotime($date_ref . ' -1 day')); }
            $date_ref = date("Y-m-d", strtotime('next Sunday', strtotime(date('Y-m-d'))));
      }
      $trains_week = call_store_procedure("get_summary_trainings_week",array($date_ref,$order));
      $table = $table_ses = '';
      $table_ses .= '<table id="table_sess" style="visibility:hidden;"><!---->
                        <tr><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th><th>Sabado</th><th>Domingo</th></tr>';
      $sess_aux1 = $sess_aux2 = $sess_aux3 = $sess_aux4 = $sess_aux5 = $sess_aux6 = $sess_aux7 = '';   
      $del_level_option = '';
      if ($trains_week == true) {
            $j = 1;
            foreach ($trains_week as $key) {
                  $summary_values = '';
                  $key[13] = explode('|', $key[13]); $key[14] = explode('|', $key[14]); $key[15] = explode('|', $key[15]);
                  $key[16] = explode('|', $key[16]); $key[17] = explode('|', $key[17]); $key[18] = explode('|', $key[18]);
                  $key[19] = explode('|', $key[19]); $key[20] = explode('|', $key[20]); $key[21] = explode('|', $key[21]);
                  $key[22] = explode('|', $key[22]); $key[23] = explode('|', $key[23]); $key[24] = explode('|', $key[24]);
                  $key[25] = explode('|', $key[25]); $key[26] = explode('|', $key[26]); $key[27] = explode('|', $key[27]);
                  $key[28] = explode('|', $key[28]); $key[29] = explode('|', $key[29]); $key[30] = explode('|', $key[30]);
                  $key[31] = explode('|', $key[31]); $key[32] = explode('|', $key[32]); $key[33] = explode('|', $key[33]);
                  if($key[13][0] == '') { $key[13][0] = '-'; $key[13][3] = 0; $key[13][6] = 0; $key[13][9] = 0; }
                  if($key[14][0] == '') { $key[14][0] = '-'; $key[14][3] = 0; $key[14][6] = 0; $key[14][9] = 0; }
                  if($key[15][0] == '') { $key[15][0] = '-'; $key[15][3] = 0; $key[15][6] = 0; $key[15][9] = 0; }
                  if($key[16][0] == '') { $key[16][0] = '-'; $key[16][3] = 0; $key[16][6] = 0; $key[16][9] = 0; }
                  if($key[17][0] == '') { $key[17][0] = '-'; $key[17][3] = 0; $key[17][6] = 0; $key[17][9] = 0; }
                  if($key[18][0] == '') { $key[18][0] = '-'; $key[18][3] = 0; $key[18][6] = 0; $key[18][9] = 0; }
                  if($key[19][0] == '') { $key[19][0] = '-'; $key[19][3] = 0; $key[19][6] = 0; $key[19][9] = 0; }
                  if($key[20][0] == '') { $key[20][0] = '-'; $key[20][3] = 0; $key[20][6] = 0; $key[20][9] = 0; }
                  if($key[21][0] == '') { $key[21][0] = '-'; $key[21][3] = 0; $key[21][6] = 0; $key[21][9] = 0; }
                  if($key[22][0] == '') { $key[22][0] = '-'; $key[22][3] = 0; $key[22][6] = 0; $key[22][9] = 0; }
                  if($key[23][0] == '') { $key[23][0] = '-'; $key[23][3] = 0; $key[23][6] = 0; $key[23][9] = 0; }
                  if($key[24][0] == '') { $key[24][0] = '-'; $key[24][3] = 0; $key[24][6] = 0; $key[24][9] = 0; }
                  if($key[25][0] == '') { $key[25][0] = '-'; $key[25][3] = 0; $key[25][6] = 0; $key[25][9] = 0; }
                  if($key[26][0] == '') { $key[26][0] = '-'; $key[26][3] = 0; $key[26][6] = 0; $key[26][9] = 0; }
                  if($key[27][0] == '') { $key[27][0] = '-'; $key[27][3] = 0; $key[27][6] = 0; $key[27][9] = 0; }
                  if($key[28][0] == '') { $key[28][0] = '-'; $key[28][3] = 0; $key[28][6] = 0; $key[28][9] = 0; }
                  if($key[29][0] == '') { $key[29][0] = '-'; $key[29][3] = 0; $key[29][6] = 0; $key[29][9] = 0; }
                  if($key[30][0] == '') { $key[30][0] = '-'; $key[30][3] = 0; $key[30][6] = 0; $key[30][9] = 0; }
                  if($key[31][0] == '') { $key[31][0] = '-'; $key[31][3] = 0; $key[31][6] = 0; $key[31][9] = 0; }
                  if($key[32][0] == '') { $key[32][0] = '-'; $key[32][3] = 0; $key[32][6] = 0; $key[32][9] = 0; }
                  if($key[33][0] == '') { $key[33][0] = '-'; $key[33][3] = 0; $key[33][6] = 0; $key[33][9] = 0; }
                  $all_tsss = $key[13][3]+$key[14][3]+$key[15][3]+$key[16][3]+$key[17][3]+$key[18][3]+$key[19][3]+$key[20][3]+$key[21][3]+$key[22][3]+$key[23][3]+$key[24][3]+$key[25][3]+$key[26][3]+$key[27][3]+$key[28][3]+$key[29][3]+$key[30][3]+$key[31][3]+$key[32][3]+$key[33][3];
                  $all_tssb = $key[13][6]+$key[14][6]+$key[15][6]+$key[16][6]+$key[17][6]+$key[18][6]+$key[19][6]+$key[20][6]+$key[21][6]+$key[22][6]+$key[23][6]+$key[24][6]+$key[25][6]+$key[26][6]+$key[27][6]+$key[28][6]+$key[29][6]+$key[30][6]+$key[31][6]+$key[32][6]+$key[33][6];
                  $all_tssr = $key[13][9]+$key[14][9]+$key[15][9]+$key[16][9]+$key[17][9]+$key[18][9]+$key[19][9]+$key[20][9]+$key[21][9]+$key[22][9]+$key[23][9]+$key[24][9]+$key[25][9]+$key[26][9]+$key[27][9]+$key[28][9]+$key[29][9]+$key[30][9]+$key[31][9]+$key[32][9]+$key[33][9];
                  if ($all_tssr > 0) { $res1 = round(($all_tssb/$all_tssr),2); }else{ $res1 = 0; } //round(($all_tsss+$all_tssb+$all_tssr)/7)
                  if ($res1 < 2) { $summary_values = '↑ B/R ↓ → '.$res1.' | '.round(($all_tsss+$all_tssb+$all_tssr)/7).' TSS/día'; }
                  if ($res1 == 2) { $summary_values = 'B/R → '.$res1.' | '.round(($all_tsss+$all_tssb+$all_tssr)/7).' TSS/día'; }
                  if ($res1 > 2) { $summary_values = '↓ B/R ↑ → '.$res1.' | '.round(($all_tsss+$all_tssb+$all_tssr)/7).' TSS/día'; }
                  $all_sessions["id"] = $j;
                  $all_sessions[$j."_1_1"] = $key[13][0];
                  $all_sessions[$j."_1_2"] = $key[14][0];
                  $all_sessions[$j."_1_3"] = $key[15][0];
                  $all_sessions[$j."_2_1"] = $key[16][0];
                  $all_sessions[$j."_2_2"] = $key[17][0];
                  $all_sessions[$j."_2_3"] = $key[18][0];
                  $all_sessions[$j."_3_1"] = $key[19][0];
                  $all_sessions[$j."_3_2"] = $key[20][0];
                  $all_sessions[$j."_3_3"] = $key[21][0];
                  $all_sessions[$j."_4_1"] = $key[22][0];
                  $all_sessions[$j."_4_2"] = $key[23][0];
                  $all_sessions[$j."_4_3"] = $key[24][0];
                  $all_sessions[$j."_5_1"] = $key[25][0];
                  $all_sessions[$j."_5_2"] = $key[26][0];
                  $all_sessions[$j."_5_3"] = $key[27][0];
                  $all_sessions[$j."_6_1"] = $key[28][0];
                  $all_sessions[$j."_6_2"] = $key[29][0];
                  $all_sessions[$j."_6_3"] = $key[30][0];
                  $all_sessions[$j."_7_1"] = $key[31][0];
                  $all_sessions[$j."_7_2"] = $key[32][0];
                  $all_sessions[$j."_7_3"] = $key[33][0];
                  $all_sessions["data_".$j] = $summary_values;
                  $sess_aux1 .= utf8_encode($key[13][0]).'@@@'.utf8_encode($key[14][0]).'@@@'.utf8_encode($key[15][0]).'@@@';
                  $sess_aux2 .= utf8_encode($key[16][0]).'@@@'.utf8_encode($key[17][0]).'@@@'.utf8_encode($key[18][0]).'@@@';
                  $sess_aux3 .= utf8_encode($key[19][0]).'@@@'.utf8_encode($key[20][0]).'@@@'.utf8_encode($key[21][0]).'@@@';
                  $sess_aux4 .= utf8_encode($key[22][0]).'@@@'.utf8_encode($key[23][0]).'@@@'.utf8_encode($key[24][0]).'@@@';
                  $sess_aux5 .= utf8_encode($key[25][0]).'@@@'.utf8_encode($key[26][0]).'@@@'.utf8_encode($key[27][0]).'@@@';
                  $sess_aux6 .= utf8_encode($key[28][0]).'@@@'.utf8_encode($key[29][0]).'@@@'.utf8_encode($key[30][0]).'@@@';
                  $sess_aux7 .= utf8_encode($key[31][0]).'@@@'.utf8_encode($key[32][0]).'@@@'.utf8_encode($key[33][0]).'@@@';
                  $j++;             
            }
      }else{ $table_ses .= '<tr><td colspan="30"> No hay datos disponibles </td></tr>'; }
      if ($trains_week == true) {#Si la consulta tiene valores los tomamos para rellenar la tabla de descarga
            $sess_aux1 = substr($sess_aux1, 0, -3); $sess_aux1 = explode('@@@', $sess_aux1);
            $sess_aux2 = substr($sess_aux2, 0, -3); $sess_aux2 = explode('@@@', $sess_aux2);
            $sess_aux3 = substr($sess_aux3, 0, -3); $sess_aux3 = explode('@@@', $sess_aux3);
            $sess_aux4 = substr($sess_aux4, 0, -3); $sess_aux4 = explode('@@@', $sess_aux4);
            $sess_aux5 = substr($sess_aux5, 0, -3); $sess_aux5 = explode('@@@', $sess_aux5);
            $sess_aux6 = substr($sess_aux6, 0, -3); $sess_aux6 = explode('@@@', $sess_aux6);
            $sess_aux7 = substr($sess_aux7, 0, -3); $sess_aux7 = explode('@@@', $sess_aux7);
            $cont_sess = count($sess_aux1);
            $sess_aux1 = array_unique($sess_aux1);
            $sess_aux2 = array_unique($sess_aux2);
            $sess_aux3 = array_unique($sess_aux3);
            $sess_aux4 = array_unique($sess_aux4);
            $sess_aux5 = array_unique($sess_aux5);
            $sess_aux6 = array_unique($sess_aux6);
            $sess_aux7 = array_unique($sess_aux7);
            $ses01 = $ses02 = $ses03 = $ses04 = $ses05 = $ses06 = $ses07 = array();
            for ($i=0; $i < $cont_sess; $i++) {
                  if (isset($sess_aux1[$i]) && trim($sess_aux1[$i])!='-') { array_push($ses01, $sess_aux1[$i]); }
                  if (isset($sess_aux2[$i]) && trim($sess_aux2[$i])!='-') { array_push($ses02, $sess_aux2[$i]); }
                  if (isset($sess_aux3[$i]) && trim($sess_aux3[$i])!='-') { array_push($ses03, $sess_aux3[$i]); }
                  if (isset($sess_aux4[$i]) && trim($sess_aux4[$i])!='-') { array_push($ses04, $sess_aux4[$i]); }
                  if (isset($sess_aux5[$i]) && trim($sess_aux5[$i])!='-') { array_push($ses05, $sess_aux5[$i]); }
                  if (isset($sess_aux6[$i]) && trim($sess_aux6[$i])!='-') { array_push($ses06, $sess_aux6[$i]); }
                  if (isset($sess_aux7[$i]) && trim($sess_aux7[$i])!='-') { array_push($ses07, $sess_aux7[$i]); }
            }
            for ($i=0; $i < $cont_sess; $i++) { 
                  $table_ses .= '<tr>';
                  $table_ses .= '<td>'; if (!isset($ses01[$i])) { $ses01[$i] = ''; } $table_ses .= $ses01[$i]; $table_ses .= '</td>';
                  $table_ses .= '<td>'; if (!isset($ses02[$i])) { $ses02[$i] = ''; } $table_ses .= $ses02[$i]; $table_ses .= '</td>';
                  $table_ses .= '<td>'; if (!isset($ses03[$i])) { $ses03[$i] = ''; } $table_ses .= $ses03[$i]; $table_ses .= '</td>';
                  $table_ses .= '<td>'; if (!isset($ses04[$i])) { $ses04[$i] = ''; } $table_ses .= $ses04[$i]; $table_ses .= '</td>';
                  $table_ses .= '<td>'; if (!isset($ses05[$i])) { $ses05[$i] = ''; } $table_ses .= $ses05[$i]; $table_ses .= '</td>';
                  $table_ses .= '<td>'; if (!isset($ses06[$i])) { $ses06[$i] = ''; } $table_ses .= $ses06[$i]; $table_ses .= '</td>';
                  $table_ses .= '<td>'; if (!isset($ses07[$i])) { $ses07[$i] = ''; } $table_ses .= $ses07[$i]; $table_ses .= '</td>';
                  $table_ses .= '</tr>';
            }
      }
      $table_ses .= '</table>';
      $all_sessions["total"] = $j;
      return "3&&&".json_encode($all_sessions)."&&&".$table_ses;
}
      ////////////
      function load_summary_macro_form($date_ref, $event_ref, $order){
      //delete_events_past();//BORRA LOS EVENTOS PRIMARIOS Y/O SECUNDARIOS QUE YA PASARON, Trainings, Events, Assignments and Notes (ALL)
      $table = $val_f = $val_e = '';
      $lun = $mar = $mie = $jue = $vie = $sab = $dom = $end_w = '';
      if ($event_ref != 'none' && $event_ref != '') { $val_e = $event_ref; }
      if ($date_ref != 'none' && $date_ref != '' && $date_ref != '0000-00-00') { $date_ref = $date_ref; }else{ $date_ref = date('Y-m-d'); }
      $val_f = $date_ref;
      ////DAYS WEEK
      $date_aux = new DateTime($date_ref);
      $week = $date_aux->format("W");
      $year = $date_aux->format("Y");
      for($day=1; $day<=7; $day++){
          switch ($day) {
                case 1: $lun = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 2: $mar = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 3: $mie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 4: $jue = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 5: $vie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 6: $sab = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 7: $dom = date('d-M-y', strtotime($year."W".$week.$day))."\n"; 
                        $end_w = date('d-M', strtotime($year."W".$week.$day))."\n"; break;
                default: break;
          }
      }
      $table = '  <div class="widget">
                        <h4 class="widgettitle">Resumen Semanal </h4>
                        <div class="widgetcontent">
                              <div id="load_table_template"></div>
                                 <div id="summary_table_content"><!--CONTENDOR PARA MOSTRAR EL RESUMEN Y SUS FILTROS-->
                                    <div class="form-group form-inline">
                                    <center>
                                          '.build_select_db('Events', 'name_e', 'id_e', 'event_ref', 'Grupo ', 0, 'master = "0"', 'width:20%;', '', '3', $val_e, 0).'
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <label for="date_event">Fecha </label>
                                          <input id="date_ref" type="text" placeholder="yyyy/mm/dd" name="date" class="datepicker form-control" style="width:20%;" value="'.$val_f.'"/>
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <label>
                                                <div id="week_and_ends">Semana: '.$week/*($week+1)*/.' | Termina en: '.$end_w.' </div>
                                          </label>
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <button class="btn btn-primary" type="button" onclick="reload_table_summary('."''".'); return false;">Actualizar Info.</button>
                                    </center>
                                    </div>
                              <div id="summary_table_individual"><!--CONTENEDOR DE TABLA CON RESUMEN-->';
      $table .= '             </div><!--TERMIAN TABLA CON RESUMEN-->
                        </div><!--TERMINA CONTENDOR PARA MOSTRAR EL RESUMEN Y SUS FILTROS-->
                  </div>
            </div>';
      return $table.'&&&.row';
}
      ////////////
      function load_table_summary($date_ref, $event_ref, $order, $begin){
      // Se verifica que las fechas de los templates circulares esten actuales, de lo contrario se actualizan
      $temp_rounds = update_gral('`Events`',
                                 'date_e=DATE_ADD(date_e, INTERVAL (weeks-1) WEEK)',
                                 'type IN(11,12,13) AND master="1" AND date_e < NOW()');
      $image_order_f = $image_order_t = $image_order_e = 'up01.png';
      if ($order == '') {
            $order = 'T.name_t,E.name_e,E.date_e,L.id_l';
      }else{
            $order = $order.', L.id_l ASC';
            $order_id = explode(' ', $order);
            if ( $order_id[0] == 'E.date_e') { $order = 'E.date_e,E.name_e,T.name_t, L.id_l ASC'; }
            if ( $order_id[0] == 'T.name_t') { $order = 'T.name_t,E.name_e,E.date_e, L.id_l ASC'; }
            if ( $order_id[0] == 'E.name_e') { $order = 'E.name_e,T.name_t,E.date_e, L.id_l ASC'; }
            if ( $order_id[0] == 'E.date_e' && $order_id[1] == 'DESC' ) { $image_order_f = 'down01.png'; }
            if ( $order_id[0] == 'T.name_t' && $order_id[1] == 'DESC' ) { $image_order_t = 'down01.png'; }
            if ( $order_id[0] == 'E.name_e' && $order_id[1] == 'DESC' ) { $image_order_e = 'down01.png'; }
      }
      $aditional_where = $val_f = $val_e = $event_sel = '';
      $lun = $mar = $mie = $jue = $vie = $sab = $dom = $end_w = '';
      if ($event_ref != 'none' && $event_ref != '') { $event_sel = ' AND E.id_e = "'.$event_ref.'" '; $val_e = $event_ref; }
      if ($date_ref != 'none' && $date_ref != '' && $date_ref != '0000-00-00') {
            $date_ref = $date_ref;
            $dte = strtotime($date_ref);
            $dte = date('l', $dte);
            if ($dte == 'Sunday') { $date_ref = date('Y-m-d', strtotime($date_ref . ' -1 day')); }
            $date_ref = date("Y-m-d", strtotime('next Sunday', strtotime($date_ref)));
      }else{
            $date_ref = date('Y-m-d');
            $dte = strtotime($date_ref);
            $dte = date('l', $dte);
            if ($dte == 'Sunday') { $date_ref = date('Y-m-d', strtotime($date_ref . ' -1 day')); }
            $date_ref = date("Y-m-d", strtotime('next Sunday', strtotime(date('Y-m-d'))));
      }
      $val_f = $date_ref;
      ////DAYS WEEK
      $date_aux = new DateTime($date_ref);
      $week = $date_aux->format("W");
      $year = $date_aux->format("Y");
      for($day=1; $day<=7; $day++){
          switch ($day) {
                case 1: $lun = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 2: $mar = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 3: $mie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 4: $jue = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 5: $vie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 6: $sab = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 7: $dom = date('d-M-y', strtotime($year."W".$week.$day))."\n"; 
                        $end_w = date('d-M', strtotime($year."W".$week.$day))."\n"; break;
                default: break;
          }
      }
      if ($event_ref != 'none' && $event_ref != '') {
            $trains_week = call_store_procedure("get_summary_trainings_week_event",array($date_ref,$order,$event_ref));
      }else{
            $trains_week = call_store_procedure("get_summary_trainings_week",array($date_ref,$order));
      }
      $table = $table_ses = $table_notes = '';
      $sess_aux1 = $sess_aux2 = $sess_aux3 = $sess_aux4 = $sess_aux5 = $sess_aux6 = $sess_aux7 = '';
      $table_ses .= '<table id="table_sess" style="visibility:hidden;">
                        <tr> <th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th><th>Sabado</th><th>Domingo</th> </tr>';
      $table_notes .= '<table id="table_notes" style="visibility:hidden;" >
                        <tr>  <th>Grupo</th>
                              <th>Semana</th>
                              <th>Nota Semanal</th>
                              <th colspan="3">Lunes</th>
                              <th colspan="3">Martes</th>
                              <th colspan="3">Miercoles</th>
                              <th colspan="3">Jueves</th>
                              <th colspan="3">Viernes</th>
                              <th colspan="3">Sabado</th>
                              <th colspan="3">Domingo</th>
                        </tr>
                        <tr>
                              <td></td><td></td><td></td>
                              <td>Sesion 1</td><td>Sesion 2</td><td>Sesion 3</td>
                              <td>Sesion 1</td><td>Sesion 2</td><td>Sesion 3</td>
                              <td>Sesion 1</td><td>Sesion 2</td><td>Sesion 3</td>
                              <td>Sesion 1</td><td>Sesion 2</td><td>Sesion 3</td>
                              <td>Sesion 1</td><td>Sesion 2</td><td>Sesion 3</td>
                              <td>Sesion 1</td><td>Sesion 2</td><td>Sesion 3</td>
                              <td>Sesion 1</td><td>Sesion 2</td><td>Sesion 3</td>
                        </tr>';
      //FILTER VA EN El FINAL
      $tab1 = '   <div class="scrollable_t_s">
                        <table id="t_ex" class="table table-bordered summary_table trainings">
                                    <thead>
                                          <tr>
                                                <th title="Status" style="">Status</th>
                                                <th title="Fecha de Arranque" style="">F.A.</th>
                                                <th title="Ordenar por Fecha de Evento" style="">
                                                      <img id="order_s_f" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png"> F.E.</th>
                                                <th title="Ciclo Semanal" style="">C.S.</th>
                                                <th class="g" title="Datos" style="">Datos</th>
                                                <th title="Semana" style="">Sem.</th>
                                                <th title="Ordenar por Tipo" style="">
                                                      <img id="order_s_t" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png"> Tipo</th>
                                                <th title="Ordenar por Grupo" style="">
                                                      <img id="order_s_e" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png"> Grupo</th>
                                                <th title="Nivel" style="">Nivel</th>
                                                <th class="a" colspan="3">Lunes / Monday</th>
                                                <th colspan="3">Martes / Tuesday</th>
                                                <th class="a" colspan="3">Miercoles / Wednesday</th>
                                                <th colspan="3">Jueves / Thursday</th>
                                                <th class="a" colspan="3">Viernes / Friday</th>
                                                <th colspan="3">Sabado / Saturday</th>
                                                <th class="a" colspan="3">Domingo / Sunday</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <tr class="c">
                                                <td class="d" style=""> </td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td colspan="3">'.$lun.'</td>
                                                <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$mar.'</td>
                                                <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$mie.'</td>
                                                <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$jue.'</td>
                                                <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$vie.'</td>
                                                <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$sab.'</td>
                                                <td colspan="3" style="border-left-width:medium;border-left-color:darkgray;">'.$dom.'</td>
                                          </tr>
                                          <tr class="c">
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td class="d" style=""></td><td class="d" style=""></td><td class="d" style=""></td>
                                                <td>Session 1</td><td>Session 2</td><td>Session 3</td>
                                                <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                                <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                                <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                                <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                                <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                                <td style="border-left-width:medium;border-left-color:darkgray;">Session 1</td><td>Session 2</td><td>Session 3</td>
                                          </tr>';
      $tab2 = '   <div class="pinned_t_s">
                        <table id="t_ex" class="table table-bordered summary_table">
                                    <thead>
                                          <tr style="height: 37px;">
                                                <th title="Status">Status</th>
                                                <th title="Fecha de Arranque">F.A.</th>
                                                <th title="Ordenar por Fecha de Evento"><img id="order_s_f" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png"> F.E.</th>
                                                <th title="Ciclo Semanal">C.S.</th>
                                                <th class="g" title="Datos">Datos</th>
                                                <th title="Semana">Sem.</th>
                                                <th title="Ordenar por Tipo"><img id="order_s_t" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png"> Tipo</th>
                                                <th title="Ordenar por Grupo"><img id="order_s_e" onclick="check_order_summary(this.id); return false;" style="width:10px; position:inherit; cursor:pointer;" src="./img/images/up01.png"> Grupo</th>
                                                <th title="Nivel">Nivel</th>
                                                <th class="a" colspan="3" style="display: none;">Lunes / Monday</th>
                                                <th colspan="3" style="display: none;">Martes / Tuesday</th>
                                                <th class="a" colspan="3" style="display: none;">Miercoles / Wednesday</th>
                                                <th colspan="3" style="display: none;">Jueves / Thursday</th>
                                                <th class="a" colspan="3" style="display: none;">Viernes / Friday</th>
                                                <th colspan="3" style="display: none;">Sabado / Saturday</th>
                                                <th class="a" colspan="3" style="display: none;">Domingo / Sunday</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <tr class="c" style="height: 37px;">
                                                <td class="d"> </td><td class="d"></td><td class="d"></td>
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td colspan="3" style="display: none;">'.$lun.'</td>
                                                <td colspan="3" style="display: none;">'.$mar.'</td>
                                                <td colspan="3" style="display: none;">'.$mie.'</td>
                                                <td colspan="3" style="display: none;">'.$jue.'</td>
                                                <td colspan="3" style="display: none;">'.$vie.'</td>
                                                <td colspan="3" style="display: none;">'.$sab.'</td>
                                                <td colspan="3" style="display: none;">'.$dom.'</td>
                                          </tr>
                                          <tr class="c" style="height: 37px;">
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td class="d"></td><td class="d"></td><td class="d"></td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                                <td style="display: none;">Session 1</td><td style="display: none;">Session 2</td><td style="display: none;">Session 3</td>
                                          </tr>';
      $del_level_option = '';
      if ($trains_week == true) {
            $j = 1;
            foreach ($trains_week as $key) {
                  $summary_values = '';
                  $key[13] = explode('|', $key[13]); $key[14] = explode('|', $key[14]); $key[15] = explode('|', $key[15]);
                  $key[16] = explode('|', $key[16]); $key[17] = explode('|', $key[17]); $key[18] = explode('|', $key[18]);
                  $key[19] = explode('|', $key[19]); $key[20] = explode('|', $key[20]); $key[21] = explode('|', $key[21]);
                  $key[22] = explode('|', $key[22]); $key[23] = explode('|', $key[23]); $key[24] = explode('|', $key[24]);
                  $key[25] = explode('|', $key[25]); $key[26] = explode('|', $key[26]); $key[27] = explode('|', $key[27]);
                  $key[28] = explode('|', $key[28]); $key[29] = explode('|', $key[29]); $key[30] = explode('|', $key[30]);
                  $key[31] = explode('|', $key[31]); $key[32] = explode('|', $key[32]); $key[33] = explode('|', $key[33]);
                  if($key[13][0] == '') { $key[13][0] = '-'; $key[13][3] = 0; $key[13][6] = 0; $key[13][9] = 0; }
                  if($key[14][0] == '') { $key[14][0] = '-'; $key[14][3] = 0; $key[14][6] = 0; $key[14][9] = 0; }
                  if($key[15][0] == '') { $key[15][0] = '-'; $key[15][3] = 0; $key[15][6] = 0; $key[15][9] = 0; }
                  if($key[16][0] == '') { $key[16][0] = '-'; $key[16][3] = 0; $key[16][6] = 0; $key[16][9] = 0; }
                  if($key[17][0] == '') { $key[17][0] = '-'; $key[17][3] = 0; $key[17][6] = 0; $key[17][9] = 0; }
                  if($key[18][0] == '') { $key[18][0] = '-'; $key[18][3] = 0; $key[18][6] = 0; $key[18][9] = 0; }
                  if($key[19][0] == '') { $key[19][0] = '-'; $key[19][3] = 0; $key[19][6] = 0; $key[19][9] = 0; }
                  if($key[20][0] == '') { $key[20][0] = '-'; $key[20][3] = 0; $key[20][6] = 0; $key[20][9] = 0; }
                  if($key[21][0] == '') { $key[21][0] = '-'; $key[21][3] = 0; $key[21][6] = 0; $key[21][9] = 0; }
                  if($key[22][0] == '') { $key[22][0] = '-'; $key[22][3] = 0; $key[22][6] = 0; $key[22][9] = 0; }
                  if($key[23][0] == '') { $key[23][0] = '-'; $key[23][3] = 0; $key[23][6] = 0; $key[23][9] = 0; }
                  if($key[24][0] == '') { $key[24][0] = '-'; $key[24][3] = 0; $key[24][6] = 0; $key[24][9] = 0; }
                  if($key[25][0] == '') { $key[25][0] = '-'; $key[25][3] = 0; $key[25][6] = 0; $key[25][9] = 0; }
                  if($key[26][0] == '') { $key[26][0] = '-'; $key[26][3] = 0; $key[26][6] = 0; $key[26][9] = 0; }
                  if($key[27][0] == '') { $key[27][0] = '-'; $key[27][3] = 0; $key[27][6] = 0; $key[27][9] = 0; }
                  if($key[28][0] == '') { $key[28][0] = '-'; $key[28][3] = 0; $key[28][6] = 0; $key[28][9] = 0; }
                  if($key[29][0] == '') { $key[29][0] = '-'; $key[29][3] = 0; $key[29][6] = 0; $key[29][9] = 0; }
                  if($key[30][0] == '') { $key[30][0] = '-'; $key[30][3] = 0; $key[30][6] = 0; $key[30][9] = 0; }
                  if($key[31][0] == '') { $key[31][0] = '-'; $key[31][3] = 0; $key[31][6] = 0; $key[31][9] = 0; }
                  if($key[32][0] == '') { $key[32][0] = '-'; $key[32][3] = 0; $key[32][6] = 0; $key[32][9] = 0; }
                  if($key[33][0] == '') { $key[33][0] = '-'; $key[33][3] = 0; $key[33][6] = 0; $key[33][9] = 0; }
                  $all_tsss = $key[13][3]+$key[14][3]+$key[15][3]+$key[16][3]+$key[17][3]+$key[18][3]+$key[19][3]+$key[20][3]+$key[21][3]+$key[22][3]+$key[23][3]+$key[24][3]+$key[25][3]+$key[26][3]+$key[27][3]+$key[28][3]+$key[29][3]+$key[30][3]+$key[31][3]+$key[32][3]+$key[33][3];
                  $all_tssb = $key[13][6]+$key[14][6]+$key[15][6]+$key[16][6]+$key[17][6]+$key[18][6]+$key[19][6]+$key[20][6]+$key[21][6]+$key[22][6]+$key[23][6]+$key[24][6]+$key[25][6]+$key[26][6]+$key[27][6]+$key[28][6]+$key[29][6]+$key[30][6]+$key[31][6]+$key[32][6]+$key[33][6];
                  $all_tssr = $key[13][9]+$key[14][9]+$key[15][9]+$key[16][9]+$key[17][9]+$key[18][9]+$key[19][9]+$key[20][9]+$key[21][9]+$key[22][9]+$key[23][9]+$key[24][9]+$key[25][9]+$key[26][9]+$key[27][9]+$key[28][9]+$key[29][9]+$key[30][9]+$key[31][9]+$key[32][9]+$key[33][9];
                  if ($all_tssr > 0) { $res1 = round(($all_tssb/$all_tssr),2); }else{ $res1 = 0; } //round(($all_tsss+$all_tssb+$all_tssr)/7)
                  if ($res1 < 2) { $summary_values = '↑ B/R ↓ → '.$res1.' | '.round(($all_tsss+$all_tssb+$all_tssr)/7).' TSS/día'; }
                  if ($res1 == 2) { $summary_values = 'B/R → '.$res1.' | '.round(($all_tsss+$all_tssb+$all_tssr)/7).' TSS/día'; }
                  if ($res1 > 2) { $summary_values = '↓ B/R ↑ → '.$res1.' | '.round(($all_tsss+$all_tssb+$all_tssr)/7).' TSS/día'; }
                  $bac_past = '';
                  (utf8_encode($key[7]) == 'PASADO') ? $bac_past = 'background-color: FFF9E6;' : $bac_past = '';
                        //////RELLENAMOS FILAS DE TABLA 1
                        $tab1 .= '<tr style="'.$bac_past.'">';
                        $tab1 .= '  <td style="">'.utf8_encode($key[7]).'</td>';
                        $tab1 .= '  <td style="">'.date('d M', strtotime(utf8_encode($key[4]))).'</td>';
                        $tab1 .= '  <td style="">'.date('d M', strtotime(utf8_encode($key[5]))).'</td>';
                        $tab1 .= '  <td style="">'.utf8_encode($key[12]).'</td>';
                        $tab1 .= '  <td style="">'.$summary_values.'</td>';
                        $tab1 .= '  <td style="">Sem '.utf8_encode($key[9]).'</td>';
                        $tab1 .= '  <td style=""><b style="font-size:15px;">'.strtoupper(utf8_encode($key[10])).'</b></td>';
                        $tab1 .= '  <td style=""><a onclick="show_event_sessions_aux('.$key[0].','.$key[34].','.$key[1].','.$key[2].'); return false;" href="#">'.utf8_encode($key[3]).'</a></td>';
                  
                        if ($key[35] == "0" AND $key[36] != "11" AND $key[36] != "12" AND $key[36] != "13") {
                              $del_level_option = '<sup title="Remover este nivel" style="cursor: pointer; color:red;" onclick="confirm_deallocate_level('.$key[0].','.$key[34].','.$j.'); return false;"><b>X</b></sup>';
                        }elseif ($key[36] == "11" OR $key[36] == "12" OR $key[36] == "13") {
                              $del_level_option = '<sup title="Ocultar este nivel" style="cursor: pointer;" onclick="confirm_hidde_level('.$key[0].','.$key[34].'); return false;"><img src="./img/images/prohibition_sign.png" style="width: 10px;"/></sup>';
                        }else{ $del_level_option = ''; }
                        $tab1 .= '  <td style="">'.$del_level_option.' '.utf8_encode($key[11]).'</td>';
                        ### ###
                        $note01 = $note02 = $note03 = $note04 = $note05 = $note06 = $note07 = $note08 = $note09 = $note10 = $note11 = '';
                        $note12 = $note13 = $note14 = $note15 = $note16 = $note17 = $note18 = $note19 = $note20 = $note21 = '';
                        $notes01 = $notes02 = $notes03 = $notes04 = $notes05 = $notes06 = $notes07 = $notes08 = $notes09 = $notes10 = $notes11 = '';
                        $notes12 = $notes13 = $notes14 = $notes15 = $notes16 = $notes17 = $notes18 = $notes19 = $notes20 = $notes21 = '';
                        $style_notes     = 'width:18px; vertical-align: bottom; text-align: right; cursor:pointer; ';
                        $image_direction = './img/images/notes_.png';
                        $type_event_note = '2';
                        $is_temporary = select_gral('`Events`', 'temporary', 'id_e="'.$key[0].'"', 'temporary');
                        if ($is_temporary == true) { foreach ($is_temporary as $i_t) { $is_temporary = intval($i_t[0]); } }
                        if ($is_temporary > 0) {# SI ES EVENTO SECUNDRIO
                              $type_event_note = '3';
                        }
                        ($key[9] > 9) ? $space = '&nbsp;&nbsp;&nbsp;&nbsp;' : $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        if (utf8_encode($key[13][0]) != '-') {
                              $notes01 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "1", "1");
                              $notes01 = explode("###", $notes01);
                              if ($notes01[3] != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[13][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@1@@1',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note01 = $space.'<img id="add_note_pencil" src="'.$notes01[3].'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[14][0]) != '-') {
                              $notes02 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "1", "2");
                              $notes02 = explode("###", $notes02);
                              $image_direction = $notes02[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[14][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@1@@2',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note02 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[15][0]) != '-') {
                              $notes03 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "1", "3");
                              $notes03 = explode("###", $notes03);
                              $image_direction = $notes03[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[15][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@1@@3',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note03 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[16][0]) != '-') {
                              $notes04 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "2", "1");
                              $notes04 = explode("###", $notes04);
                              $image_direction = $notes04[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[16][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@2@@1',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note04 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[17][0]) != '-') {
                              $notes05 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "2", "2");
                              $notes05 = explode("###", $notes05);
                              $image_direction = $notes05[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[17][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@2@@2',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note05 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[18][0]) != '-') {
                              $notes06 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "2", "3");
                              $notes06 = explode("###", $notes06);
                              $image_direction = $notes06[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[18][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@2@@3',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note06 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[19][0]) != '-') {
                              $notes07 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "3", "1");
                              $notes07 = explode("###", $notes07);
                              $image_direction = $notes07[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[19][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@3@@1',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note07 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[20][0]) != '-') {
                              $notes08 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "3", "2");
                              $notes08 = explode("###", $notes08);
                              $image_direction = $notes08[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[20][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@3@@2',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note08 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[21][0]) != '-') {
                              $notes09 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "3", "3");
                              $notes09 = explode("###", $notes09);
                              $image_direction = $notes09[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[21][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@3@@3',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note09 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[22][0]) != '-') {
                              $notes10 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "4", "1");
                              $notes10 = explode("###", $notes10);
                              $image_direction = $notes10[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[22][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@4@@1',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note10 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[23][0]) != '-') {
                              $notes11 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "4", "2");
                              $notes11 = explode("###", $notes11);
                              $image_direction = $notes11[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[23][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@4@@2',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note11 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[24][0]) != '-') {
                              $notes12 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "4", "3");
                              $notes12 = explode("###", $notes12);
                              $image_direction = $notes12[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[24][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@4@@3',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note12 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[25][0]) != '-') {
                              $notes13 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "5", "1");
                              $notes13 = explode("###", $notes13);
                              $image_direction = $notes13[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[25][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@5@@1',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note13 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[26][0]) != '-') {
                              $notes14 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "5", "2");
                              $notes14 = explode("###", $notes14);
                              $image_direction = $notes14[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[26][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@5@@2',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note14 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[27][0]) != '-') {
                              $notes15 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "5", "3");
                              $notes15 = explode("###", $notes15);
                              $image_direction = $notes15[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[27][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@5@@3',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note15 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[28][0]) != '-') {
                              $notes16 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "6", "1");
                              $notes16 = explode("###", $notes16);
                              $image_direction = $notes16[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[28][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@6@@1',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note16 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[29][0]) != '-') {
                              $notes17 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "6", "2");
                              $notes17 = explode("###", $notes17);
                              $image_direction = $notes17[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[29][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@6@@2',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note17 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[30][0]) != '-') {
                              $notes18 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "6", "3");
                              $notes18 = explode("###", $notes18);
                              $image_direction = $notes18[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[30][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@6@@3',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note18 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[31][0]) != '-') {
                              $notes19 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "7", "1");
                              $notes19 = explode("###", $notes19);
                              $image_direction = $notes19[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[31][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@7@@1',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note19 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[32][0]) != '-') {
                              $notes20 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "7", "2");
                              $notes20 = explode("###", $notes20);
                              $image_direction = $notes20[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[32][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@7@@2',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note20 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        if (utf8_encode($key[33][0]) != '-') {
                              $notes21 = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "7", "3");
                              $notes21 = explode("###", $notes21);
                              $image_direction = $notes21[3];
                              if ($image_direction != './img/images/notes_.png') {
                                    $function_note = "show_popup_notes_summary('".utf8_encode($key[33][0])."',3,'".$key[0]."@@".$key[34]."@@".$key[9]."@@7@@3',".$type_event_note.",'".$key[1]."@@".$key[2]."',1)";
                                    $note21 = $space.'<img id="add_note_pencil" src="'.$image_direction.'" style="'.$style_notes.'" onclick="'.$function_note.'" class="note_week_'.$key[9].'" >';
                              }
                        }
                        $tab1 .= '  <td>'.utf8_encode($key[13][0]).' '.$note01.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[14][0]).' '.$note02.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[15][0]).' '.$note03.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[16][0]).' '.$note04.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[17][0]).' '.$note05.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[18][0]).' '.$note06.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[19][0]).' '.$note07.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[20][0]).' '.$note08.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[21][0]).' '.$note09.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[22][0]).' '.$note10.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[23][0]).' '.$note11.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[24][0]).' '.$note12.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[25][0]).' '.$note13.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[26][0]).' '.$note14.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[27][0]).' '.$note15.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[28][0]).' '.$note16.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[29][0]).' '.$note17.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[30][0]).' '.$note18.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[31][0]).' '.$note19.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[32][0]).' '.$note20.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>';
                        $tab1 .= '  <td>'.utf8_encode($key[33][0]).' '.$note21.'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                              </tr>';
                        //////TERMINA RELLENO DE TABLA 1
                        //////RELLENAMOS FILAS DE TABLA 2
                        $tab2 .= '<tr style="height: 39px; '.$bac_past.'">';
                        $tab2 .= '  <td>'.utf8_encode($key[7]).'</td>
                                    <td>'.date('d M', strtotime(utf8_encode($key[4]))).'</td>
                                    <td>'.date('d M', strtotime(utf8_encode($key[5]))).'</td>
                                    <td>'.utf8_encode($key[12]).'</td>
                                    <td>'.$summary_values.'</td>';
                        $note_weekly = '';
                        $notes_weekly = get_n_i_session($is_temporary, $key[0], $key[34], $key[1], $key[2], $key[9], "n/a", "n/a");
                        $notes_weekly = explode("###", $notes_weekly);
                        if ($notes_weekly[3] != './img/images/notes_.png') {
                              $style_notes     = 'width:18px; vertical-align: bottom; text-align: right; cursor:pointer; ';
                              ($key[9] > 9) ? $space = '&nbsp;&nbsp;&nbsp;&nbsp;' : $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                        $tab2 .= '  <td>Sem '.utf8_encode($key[9]).' '.$note_weekly.'</td>
                                    <td><b style="font-size:15px;">'.strtoupper(utf8_encode($key[10])).'</b></td>
                                    <td><a onclick="show_event_sessions_aux('.$key[0].','.$key[34].','.$key[1].','.$key[2].'); return false;" href="#">'.utf8_encode($key[3]).'</a></td>
                                    <td>'.$del_level_option.' '.utf8_encode($key[11]).'</td>
                                    <td style="display: none;">'.utf8_encode($key[13][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[14][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[15][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[16][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[17][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[18][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[19][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[20][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[21][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[22][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[23][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[24][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[25][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[26][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[27][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[28][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[29][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[30][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[31][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[32][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                                    <td style="display: none;">'.utf8_encode($key[33][0]).'<input class="session_trains" type="text" style="width: 1px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 1.69em;" disabled=""></td>
                              </tr>';
                        //////TERMINA RELLENO DE TABLA 2
                  $sess_aux1 .= utf8_encode($key[13][0]).'@@@'.utf8_encode($key[14][0]).'@@@'.utf8_encode($key[15][0]).'@@@';
                  $sess_aux2 .= utf8_encode($key[16][0]).'@@@'.utf8_encode($key[17][0]).'@@@'.utf8_encode($key[18][0]).'@@@';
                  $sess_aux3 .= utf8_encode($key[19][0]).'@@@'.utf8_encode($key[20][0]).'@@@'.utf8_encode($key[21][0]).'@@@';
                  $sess_aux4 .= utf8_encode($key[22][0]).'@@@'.utf8_encode($key[23][0]).'@@@'.utf8_encode($key[24][0]).'@@@';
                  $sess_aux5 .= utf8_encode($key[25][0]).'@@@'.utf8_encode($key[26][0]).'@@@'.utf8_encode($key[27][0]).'@@@';
                  $sess_aux6 .= utf8_encode($key[28][0]).'@@@'.utf8_encode($key[29][0]).'@@@'.utf8_encode($key[30][0]).'@@@';
                  $sess_aux7 .= utf8_encode($key[31][0]).'@@@'.utf8_encode($key[32][0]).'@@@'.utf8_encode($key[33][0]).'@@@';
                  ((fmod($j, 2)) == 0) ? $background = '#E6E6E6': $background = '#FFFFFF';
                  $table_notes .= '<tr style="background:'.$background.';">';
                  $table_notes .= '<td><b>'.strtoupper(utf8_encode($key[10])).' - '.utf8_encode($key[3]).' - '.utf8_encode($key[11]).'</b></td><td>'.utf8_encode($key[9]).'</td>';
                  $table_notes .= '<td>';
                  if (isset($notes_weekly[0])) { $table_notes .= $notes_weekly[0]; } 
                  if (isset($notes_weekly[1])) { $table_notes .= $notes_weekly[1].'<br>'; }
                  if (isset($notes_weekly[2])) { $table_notes .= $notes_weekly[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes01[0])) { $table_notes .= $notes01[0]; } 
                  if (isset($notes01[1])) { $table_notes .= $notes01[1].'<br>'; }
                  if (isset($notes01[2])) { $table_notes .= $notes01[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes02[0])) { $table_notes .= $notes02[0]; } 
                  if (isset($notes02[1])) { $table_notes .= $notes02[1].'<br>'; }
                  if (isset($notes02[2])) { $table_notes .= $notes02[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes03[0])) { $table_notes .= $notes03[0]; } 
                  if (isset($notes03[1])) { $table_notes .= $notes03[1].'<br>'; }
                  if (isset($notes03[2])) { $table_notes .= $notes03[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes04[0])) { $table_notes .= $notes04[0]; } 
                  if (isset($notes04[1])) { $table_notes .= $notes04[1].'<br>'; }
                  if (isset($notes04[2])) { $table_notes .= $notes04[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes05[0])) { $table_notes .= $notes05[0]; } 
                  if (isset($notes05[1])) { $table_notes .= $notes05[1].'<br>'; }
                  if (isset($notes05[2])) { $table_notes .= $notes05[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes06[0])) { $table_notes .= $notes06[0]; } 
                  if (isset($notes06[1])) { $table_notes .= $notes06[1].'<br>'; }
                  if (isset($notes06[2])) { $table_notes .= $notes06[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes07[0])) { $table_notes .= $notes07[0]; } 
                  if (isset($notes07[1])) { $table_notes .= $notes07[1].'<br>'; }
                  if (isset($notes07[2])) { $table_notes .= $notes07[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes08[0])) { $table_notes .= $notes08[0]; } 
                  if (isset($notes08[1])) { $table_notes .= $notes08[1].'<br>'; }
                  if (isset($notes08[2])) { $table_notes .= $notes08[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes09[0])) { $table_notes .= $notes09[0]; } 
                  if (isset($notes09[1])) { $table_notes .= $notes09[1].'<br>'; }
                  if (isset($notes09[2])) { $table_notes .= $notes09[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes10[0])) { $table_notes .= $notes10[0]; } 
                  if (isset($notes10[1])) { $table_notes .= $notes10[1].'<br>'; }
                  if (isset($notes10[2])) { $table_notes .= $notes10[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes11[0])) { $table_notes .= $notes11[0]; } 
                  if (isset($notes11[1])) { $table_notes .= $notes11[1].'<br>'; }
                  if (isset($notes11[2])) { $table_notes .= $notes11[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes12[0])) { $table_notes .= $notes12[0]; } 
                  if (isset($notes12[1])) { $table_notes .= $notes12[1].'<br>'; }
                  if (isset($notes12[2])) { $table_notes .= $notes12[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes13[0])) { $table_notes .= $notes13[0]; } 
                  if (isset($notes13[1])) { $table_notes .= $notes13[1].'<br>'; }
                  if (isset($notes13[2])) { $table_notes .= $notes13[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes14[0])) { $table_notes .= $notes14[0]; } 
                  if (isset($notes14[1])) { $table_notes .= $notes14[1].'<br>'; }
                  if (isset($notes14[2])) { $table_notes .= $notes14[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes15[0])) { $table_notes .= $notes15[0]; } 
                  if (isset($notes15[1])) { $table_notes .= $notes15[1].'<br>'; }
                  if (isset($notes15[2])) { $table_notes .= $notes15[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes16[0])) { $table_notes .= $notes16[0]; } 
                  if (isset($notes16[1])) { $table_notes .= $notes16[1].'<br>'; }
                  if (isset($notes16[2])) { $table_notes .= $notes16[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes17[0])) { $table_notes .= $notes17[0]; } 
                  if (isset($notes17[1])) { $table_notes .= $notes17[1].'<br>'; }
                  if (isset($notes17[2])) { $table_notes .= $notes17[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes18[0])) { $table_notes .= $notes18[0]; } 
                  if (isset($notes18[1])) { $table_notes .= $notes18[1].'<br>'; }
                  if (isset($notes18[2])) { $table_notes .= $notes18[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes19[0])) { $table_notes .= $notes19[0]; } 
                  if (isset($notes19[1])) { $table_notes .= $notes19[1].'<br>'; }
                  if (isset($notes19[2])) { $table_notes .= $notes19[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes20[0])) { $table_notes .= $notes20[0]; } 
                  if (isset($notes20[1])) { $table_notes .= $notes20[1].'<br>'; }
                  if (isset($notes20[2])) { $table_notes .= $notes20[2].'<br>'; }
                  $table_notes .= '</td><td>';
                  if (isset($notes21[0])) { $table_notes .= $notes21[0]; } 
                  if (isset($notes21[1])) { $table_notes .= $notes21[1].'<br>'; }
                  if (isset($notes21[2])) { $table_notes .= $notes21[2].'<br>'; }
                  $table_notes .= '</td>';
                  $table_notes .= '</tr>';
                  $j++; 
                  
            }
      }else{
            $table_ses .= '<tr><td colspan="30"> No hay datos disponibles </td></tr>';
            $tab1 .= '<tr><td colspan="30"> No hay datos disponibles </td></tr>';
            $tab2 .= '<tr><td colspan="30"> No hay datos disponibles </td></tr>';
            $table_notes .= '<tr><td colspan="30"> No hay datos disponibles </td></tr>';
      }

      $sess_aux1 = substr($sess_aux1, 0, -3); $sess_aux1 = explode('@@@', $sess_aux1);
      $sess_aux2 = substr($sess_aux2, 0, -3); $sess_aux2 = explode('@@@', $sess_aux2);
      $sess_aux3 = substr($sess_aux3, 0, -3); $sess_aux3 = explode('@@@', $sess_aux3);
      $sess_aux4 = substr($sess_aux4, 0, -3); $sess_aux4 = explode('@@@', $sess_aux4);
      $sess_aux5 = substr($sess_aux5, 0, -3); $sess_aux5 = explode('@@@', $sess_aux5);
      $sess_aux6 = substr($sess_aux6, 0, -3); $sess_aux6 = explode('@@@', $sess_aux6);
      $sess_aux7 = substr($sess_aux7, 0, -3); $sess_aux7 = explode('@@@', $sess_aux7);
      $cont_sess = count($sess_aux1);
      $sess_aux1 = array_unique($sess_aux1);
      $sess_aux2 = array_unique($sess_aux2);
      $sess_aux3 = array_unique($sess_aux3);
      $sess_aux4 = array_unique($sess_aux4);
      $sess_aux5 = array_unique($sess_aux5);
      $sess_aux6 = array_unique($sess_aux6);
      $sess_aux7 = array_unique($sess_aux7);
      $ses01 = $ses02 = $ses03 = $ses04 = $ses05 = $ses06 = $ses07 = array();
      for ($i=0; $i < $cont_sess; $i++) {
            if (isset($sess_aux1[$i]) && trim($sess_aux1[$i])!='-') { array_push($ses01, $sess_aux1[$i]); }
            if (isset($sess_aux2[$i]) && trim($sess_aux2[$i])!='-') { array_push($ses02, $sess_aux2[$i]); }
            if (isset($sess_aux3[$i]) && trim($sess_aux3[$i])!='-') { array_push($ses03, $sess_aux3[$i]); }
            if (isset($sess_aux4[$i]) && trim($sess_aux4[$i])!='-') { array_push($ses04, $sess_aux4[$i]); }
            if (isset($sess_aux5[$i]) && trim($sess_aux5[$i])!='-') { array_push($ses05, $sess_aux5[$i]); }
            if (isset($sess_aux6[$i]) && trim($sess_aux6[$i])!='-') { array_push($ses06, $sess_aux6[$i]); }
            if (isset($sess_aux7[$i]) && trim($sess_aux7[$i])!='-') { array_push($ses07, $sess_aux7[$i]); }
      }
      for ($i=0; $i < $cont_sess; $i++) { 
            $table_ses .= '<tr>';
            $table_ses .= '<td>'; if (!isset($ses01[$i])) { $ses01[$i] = ''; } $table_ses .= $ses01[$i]; $table_ses .= '</td>';
            $table_ses .= '<td>'; if (!isset($ses02[$i])) { $ses02[$i] = ''; } $table_ses .= $ses02[$i]; $table_ses .= '</td>';
            $table_ses .= '<td>'; if (!isset($ses03[$i])) { $ses03[$i] = ''; } $table_ses .= $ses03[$i]; $table_ses .= '</td>';
            $table_ses .= '<td>'; if (!isset($ses04[$i])) { $ses04[$i] = ''; } $table_ses .= $ses04[$i]; $table_ses .= '</td>';
            $table_ses .= '<td>'; if (!isset($ses05[$i])) { $ses05[$i] = ''; } $table_ses .= $ses05[$i]; $table_ses .= '</td>';
            $table_ses .= '<td>'; if (!isset($ses06[$i])) { $ses06[$i] = ''; } $table_ses .= $ses06[$i]; $table_ses .= '</td>';
            $table_ses .= '<td>'; if (!isset($ses07[$i])) { $ses07[$i] = ''; } $table_ses .= $ses07[$i]; $table_ses .= '</td>';
            $table_ses .= '</tr>';
      }
      $table_ses .= '</table>';
      $table_notes .= '</table>';
      $tab1 .= '</tbody></table></div>';
      $tab2 .= '</tbody></table></div>';
      $tabs = '<hr><div class="table-wrapper_t_s">'.$tab1.$tab2.'</div>';
      $table .= $tabs.'
                  <form action="./php/file_xls_down.php" method="post" target="_blank" id="FormularioExportacion">
                              <button class="btn botonExcel" type="button" onclick="button_export_function('."'".'t_ex'."'".','."'".'FormularioExportacion'."'".','."'".'summary'."'".');">Exportar a Excel</button>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <button class="btn botonExcel" type="button" onclick="button_export_function('."'".'table_sess'."'".','."'".'FormularioExportacion'."'".','."'".'sessions'."'".');">Descargar Sesiones</button>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <button class="btn botonExcel" type="button" onclick="button_export_function('."'".'table_notes'."'".','."'".'FormularioExportacion'."'".','."'".'notes'."'".');">Descargar Notas de Sesiones</button>

                              <input type="hidden" id="info_xls" name="info_xls"/>
                              <input type="hidden" id="name_xls" name="name_xls"/>
                  </form>
                  '.$table_ses.$table_notes;
      $return = '';
      //BOTON PARA MODIFICAR ANCHO EN TABLA
      $width_button = '';
      $width_button = '<div style="text-align:right;"><button class="btn width_button" type="button" onclick="modify_width_tables('."'summary'".');">Ampliar Sesiones</button><br></div>';
      $table = $width_button.$table;
      //TERMINA BOTON PARA MODIFICAR ANCHO EN BOTON
      $table .= '<hr>';
      $table .= ' <!-- Ventana emregente -->
                        <div id="hider_3" onclick="close_popup(3); return false;"></div>
                        <div id="popup_box_3">
                            <a class="close" onclick="close_popup(3); return false;">X</a>
                            <br>
                            <div class="message_box_3"></div>
                        </div>
                  <!-- ------ -->
                  <br><p>algo aqui</p><input value=""/>';
      ($begin != 0) ? $return = '16@-@'.$table.'@-@Semana: '.$week.' | Termina en: '.$end_w : $return = $table;
      return $return;
}
      ////////////
      function load_summary_repeat_sessions($date_ref, $event_ref, $order){ //BORRA LOS EVENTOS PRIMARIOS Y/O SECUNDARIOS QUE YA PASARON, Trainings, Events, Assignments and Notes (ALL)
      $table = $val_f = $val_e = '';
      $lun = $mar = $mie = $jue = $vie = $sab = $dom = $end_w = '';
      if ($event_ref != 'none' && $event_ref != '') { $val_e = $event_ref; }
      if ($date_ref != 'none' && $date_ref != '' && $date_ref != '0000-00-00') { $date_ref = $date_ref; }else{ $date_ref = date('Y-m-d'); }
      $val_f = $date_ref;
      ////DAYS WEEK
      $date_aux = new DateTime($date_ref);
      $week = $date_aux->format("W"); $year = $date_aux->format("Y");
      for($day=1; $day<=7; $day++){
          switch ($day) {
                case 1: $lun = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 2: $mar = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 3: $mie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 4: $jue = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 5: $vie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 6: $sab = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 7: $dom = date('d-M-y', strtotime($year."W".$week.$day))."\n"; 
                        $end_w = date('d-M', strtotime($year."W".$week.$day))."\n"; break;
                default: break;
          }
      }
      $table = '  <div class="widget">
                        <h4 class="widgettitle">Resumen de Sesiones </h4>
                        <div class="widgetcontent">
                              <div id="load_table_template"></div>
                                 <div id="summary_table_content"><!--CONTENDOR PARA MOSTRAR EL RESUMEN Y SUS FILTROS-->
                                    <div class="form-group form-inline">
                                    <center>
                                          <!--'.build_select_db('Events', 'name_e', 'id_e', 'event_ref', 'Grupo ', 0, 'master = "0"', 'width:20%;', '', '3', $val_e, 0).'-->
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <label for="date_event">Fecha </label>
                                          <input id="date_ref" type="text" placeholder="yyyy/mm/dd" name="date" class="datepicker form-control" style="width:20%;" value="'.$val_f.'"/>
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <label>
                                                <div id="week_and_ends">Semana: '.$week.' | Termina en: '.$end_w.' </div>
                                          </label>
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <button class="btn btn-primary" type="button" onclick="reload_table_summary_repeat_sessions('."''".'); return false;">Actualizar Info.</button>
                                    </center>
                                    </div>
                              <div id="summary_table_individual"><!--CONTENEDOR DE TABLA CON RESUMEN-->';
      $table .= load_table_repeat_sessiones($date_ref, $event_ref, $order, 0);
      $table .= '             </div><!--TERMIAN TABLA CON RESUMEN-->
                        </div><!--TERMINA CONTENDOR PARA MOSTRAR EL RESUMEN Y SUS FILTROS-->
                  </div>
            </div>';
      return $table.'&&&.row';
}
      ////////////
      function load_table_repeat_sessiones($date_ref, $order, $begin){
      $tables = $return = '';
      $sesion_des = $eventos = $day = $session = '';
      if ($date_ref == "") { $date_ref = date("Y-m-d"); }
      if ($order == "") { $order = "T.name_t,E.name_e,E.date_e,L.id_l"; }
      $trains_week = call_store_procedure("get_summary_trainings_week",array($date_ref,$order));
//      $sess_aux1 = $sess_aux2 = $sess_aux3 = $sess_aux4 = $sess_aux5 = $sess_aux6 = $sess_aux7 = '';
      $arr_mon = $arr_tue = $arr_wed = $arr_thu = $arr_fri = $arr_sat = $arr_sun = array();
      $aux1=$aux2=$aux3= array();
      $arr_ses = array();
      if ($trains_week == true) { $i = 1;
            foreach ($trains_week as $key) {
                  if ($key[7] != 'PASADO') {
                        $summary_values = '';
# session_desc|disc_s|unit_s|stss|disc_b|unit_b|btss|disc_r|unit_r|rtss
                        $key[13] = explode('|', $key[13]); $key[14] = explode('|', $key[14]); $key[15] = explode('|', $key[15]);
                        $key[16] = explode('|', $key[16]); $key[17] = explode('|', $key[17]); $key[18] = explode('|', $key[18]);
                        $key[19] = explode('|', $key[19]); $key[20] = explode('|', $key[20]); $key[21] = explode('|', $key[21]);
                        $key[22] = explode('|', $key[22]); $key[23] = explode('|', $key[23]); $key[24] = explode('|', $key[24]);
                        $key[25] = explode('|', $key[25]); $key[26] = explode('|', $key[26]); $key[27] = explode('|', $key[27]);
                        $key[28] = explode('|', $key[28]); $key[29] = explode('|', $key[29]); $key[30] = explode('|', $key[30]);
                        $key[31] = explode('|', $key[31]); $key[32] = explode('|', $key[32]); $key[33] = explode('|', $key[33]);
                        if($key[13][0] != '') {
                              if (array_key_exists(trim($key[13][0]), $arr_mon)) {
                                    $arr_mon[trim($key[13][0])] = ($arr_mon[trim($key[13][0])])."#-#".$key[3];
                              }else{ $arr_mon[trim($key[13][0])] = $key[3]; }
                        }
                        if($key[14][0] != '') { if (array_key_exists(trim($key[14][0]), $arr_mon)) { $arr_mon[trim($key[14][0])] = ($arr_mon[trim($key[14][0])])."#-#".$key[3]; }else{ $arr_mon[trim($key[14][0])] = $key[3]; } }
                        if($key[15][0] != '') { if (array_key_exists(trim($key[15][0]), $arr_mon)) { $arr_mon[trim($key[15][0])] = ($arr_mon[trim($key[15][0])])."#-#".$key[3]; }else{ $arr_mon[trim($key[15][0])] = $key[3]; } }

                        if($key[16][0] != '') { if (array_key_exists(trim($key[16][0]), $arr_tue)) { $arr_tue[trim($key[16][0])] = ($arr_tue[trim($key[16][0])])."#-#".$key[3]; }else{ $arr_tue[trim($key[16][0])] = $key[3]; } }
                        if($key[17][0] != '') { if (array_key_exists(trim($key[17][0]), $arr_tue)) { $arr_tue[trim($key[17][0])] = ($arr_tue[trim($key[17][0])])."#-#".$key[3]; }else{ $arr_tue[trim($key[17][0])] = $key[3]; } }
                        if($key[18][0] != '') { if (array_key_exists(trim($key[18][0]), $arr_tue)) { $arr_tue[trim($key[18][0])] = ($arr_tue[trim($key[18][0])])."#-#".$key[3]; }else{ $arr_tue[trim($key[18][0])] = $key[3]; } }

                        if($key[19][0] != '') { if (array_key_exists(trim($key[19][0]), $arr_wed)) { $arr_wed[trim($key[19][0])] = ($arr_wed[trim($key[19][0])])."#-#".$key[3]; }else{ $arr_wed[trim($key[19][0])] = $key[3]; } }
                        if($key[20][0] != '') { if (array_key_exists(trim($key[20][0]), $arr_wed)) { $arr_wed[trim($key[20][0])] = ($arr_wed[trim($key[20][0])])."#-#".$key[3]; }else{ $arr_wed[trim($key[20][0])] = $key[3]; } }
                        if($key[21][0] != '') { if (array_key_exists(trim($key[21][0]), $arr_wed)) { $arr_wed[trim($key[21][0])] = ($arr_wed[trim($key[21][0])])."#-#".$key[3]; }else{ $arr_wed[trim($key[21][0])] = $key[3]; } }

                        if($key[22][0] != '') { if (array_key_exists(trim($key[22][0]), $arr_thu)) { $arr_thu[trim($key[22][0])] = ($arr_thu[trim($key[22][0])])."#-#".$key[3]; }else{ $arr_thu[trim($key[22][0])] = $key[3]; } }
                        if($key[23][0] != '') { if (array_key_exists(trim($key[23][0]), $arr_thu)) { $arr_thu[trim($key[17][0])] = ($arr_thu[trim($key[23][0])])."#-#".$key[3]; }else{ $arr_thu[trim($key[23][0])] = $key[3]; } }
                        if($key[24][0] != '') { if (array_key_exists(trim($key[24][0]), $arr_thu)) { $arr_thu[trim($key[18][0])] = ($arr_thu[trim($key[24][0])])."#-#".$key[3]; }else{ $arr_thu[trim($key[24][0])] = $key[3]; } }

                        if($key[25][0] != '') { if (array_key_exists(trim($key[25][0]), $arr_fri)) { $arr_fri[trim($key[25][0])] = ($arr_fri[trim($key[25][0])])."#-#".$key[3]; }else{ $arr_fri[trim($key[25][0])] = $key[3]; } }
                        if($key[26][0] != '') { if (array_key_exists(trim($key[26][0]), $arr_fri)) { $arr_fri[trim($key[17][0])] = ($arr_fri[trim($key[26][0])])."#-#".$key[3]; }else{ $arr_fri[trim($key[26][0])] = $key[3]; } }
                        if($key[27][0] != '') { if (array_key_exists(trim($key[27][0]), $arr_fri)) { $arr_fri[trim($key[18][0])] = ($arr_fri[trim($key[27][0])])."#-#".$key[3]; }else{ $arr_fri[trim($key[27][0])] = $key[3]; } }

                        if($key[28][0] != '') { if (array_key_exists(trim($key[28][0]), $arr_sat)) { $arr_sat[trim($key[28][0])] = ($arr_sat[trim($key[28][0])])."#-#".$key[3]; }else{ $arr_sat[trim($key[28][0])] = $key[3]; } }
                        if($key[29][0] != '') { if (array_key_exists(trim($key[29][0]), $arr_sat)) { $arr_sat[trim($key[29][0])] = ($arr_sat[trim($key[29][0])])."#-#".$key[3]; }else{ $arr_sat[trim($key[29][0])] = $key[3]; } }
                        if($key[30][0] != '') { if (array_key_exists(trim($key[30][0]), $arr_sat)) { $arr_sat[trim($key[30][0])] = ($arr_sat[trim($key[30][0])])."#-#".$key[3]; }else{ $arr_sat[trim($key[30][0])] = $key[3]; } }

                        if($key[31][0] != '') { if (array_key_exists(trim($key[31][0]), $arr_sun)) { $arr_sun[trim($key[31][0])] = ($arr_sun[trim($key[31][0])])."#-#".$key[3]; }else{ $arr_sun[trim($key[31][0])] = $key[3]; } }
                        if($key[32][0] != '') { if (array_key_exists(trim($key[32][0]), $arr_sun)) { $arr_sun[trim($key[32][0])] = ($arr_sun[trim($key[32][0])])."#-#".$key[3]; }else{ $arr_sun[trim($key[32][0])] = $key[3]; } }
                        if($key[33][0] != '') { if (array_key_exists(trim($key[33][0]), $arr_sun)) { $arr_sun[trim($key[33][0])] = ($arr_sun[trim($key[33][0])])."#-#".$key[3]; }else{ $arr_sun[trim($key[33][0])] = $key[3]; } }
                  }
            }
      }
      $tables .= '<table class="table table-bordered responsive">';
      $tables .= '      <thead> <tr> <th>Sesion Desc</th><th>Grupos</th> </tr> </thead>';
      $aux_day = $aux_ses = '';
      for ($i=1; $i <= 7; $i++) { 
            switch ($i) {
                  case 1: $aux_day = "Lunes"; $aux_ses = $arr_mon; break;
                  case 2: $aux_day = "Martes"; $aux_ses = $arr_tue; break;
                  case 3: $aux_day = "Miércoles"; $aux_ses = $arr_wed; break;
                  case 4: $aux_day = "Jueves"; $aux_ses = $arr_thu; break;
                  case 5: $aux_day = "Viernes"; $aux_ses = $arr_fri; break;
                  case 6: $aux_day = "Sábado"; $aux_ses = $arr_sat; break;
                  case 7: $aux_day = "Domingo"; $aux_ses = $arr_sun; break;
                  default: break;
            }
            $tables .= '<tr style="background:#EEEEEE;"><td colspan="2"><b>'.$aux_day.'</b></td></tr>';
            $keys = array_keys($aux_ses);
            if (count($aux_ses) != 0) {
                  for ($j=0; $j < count($aux_ses); $j++) { 
                        $tables .= '<tr><td><b>'.utf8_encode($keys[$j]).'</b></td><td>'.utf8_encode(str_replace("#-#", "<br/>", $aux_ses[$keys[$j]])).'</td></tr>';
                  }
            }else{ $tables .= '<tr><td colspan="2"><b>No se encontrarón sesiones...</b></td></tr>'; }
      }
      $tables .= '</table>';
      ////DAYS WEEK
      $week = $end_w = 0;
      $date_aux = new DateTime($date_ref);
      $week = $date_aux->format("W");
      $year = $date_aux->format("Y");
      for($day=1; $day<=7; $day++){
          switch ($day) {
                case 1: $lun = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 2: $mar = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 3: $mie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 4: $jue = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 5: $vie = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 6: $sab = date('d-M-y', strtotime($year."W".$week.$day))."\n"; break;
                case 7: $dom = date('d-M-y', strtotime($year."W".$week.$day))."\n"; 
                        $end_w = date('d-M', strtotime($year."W".$week.$day))."\n"; break;
                default: break;
          }
      }
      ($begin != 0) ? $return = '16@-@'.$tables.'@-@Semana: '.$week.' | Termina en: '.$end_w : $return = $tables;
      return $return;
}
      ////////////
?>
