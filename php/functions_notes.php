<?php
      date_default_timezone_set("America/Mexico_City");

      sajax_export("show_session_notes");

      function show_session_notes($session_training, $type, $info_sess, $type_note, $info_temp, $read_only){
      # info_sess = id_event+'@@'+id_level+'@@'+week+'@@'+day+'@@'+no_sess
            $delete_note = $note_del = 0;
            $session_training = str_replace('mmaass', '+', $session_training);

            $s_t = $session_training;

            $rows_tab1 = $rows_tab2 = $rows_tab3 = '3';
            $disabled1 = $disabled2 = $disabled3 = '';
            $style_ta1 = $style_ta2 = $style_ta3 = '';
            $class_ta1 = $class_ta2 = $class_ta3 = '';
            $hide_1 = $hide_2 = $hide_3 = '';
            $note_temp = ''; $note_even = ''; $note_secd = '';
            $info_temp = explode('@@', $info_temp);
            $info_session = explode('@@', $info_sess);
            $id_session = '';
            //$tabla, $campos, $filtros, $orden
            $id_session = select_gral('`Trainings`', 'session',
                                      'event="'.$info_temp[0].'" AND level="'.$info_temp[1].'" AND week="'.$info_session[2].'" AND day="'.$info_session[3].'" AND session_num="'.$info_session[4].'"', 'session');
            if($id_session == true){ foreach ($id_session as $key) { $id_session = $key[0]; } }else{ $id_session = 0; }
            $info_sess .= '@@'.$id_session;//Agregamos el id de la sesion
            $table = '';
            $table .= ' <div id="load_table'.$type.'">
                        <table class="popup_table">';
            $table .= '       <tr> <th><span id="session_t'.$type.'">Notas para '.$s_t.'</span></th> </tr>';
            if ($type_note == 1) {//SOLO NOTAS DE TEMPLATE
                  $class_ta1 = 'tinymce';
                  $class_ta2 = $class_ta3 = '';
                  $rows_tab1 = '7';
                  $disabled2 = $disabled3 = 'disabled';
                  $style_ta2 = $style_ta3 = 'display:none;';
                  $note_temp = select_big('`Session_notes` AS SN INNER JOIN `Events` AS E ON E.id_e = SN.id_e INNER JOIN `Sessions` AS S ON S.id_ssn = SN.id_ssn',//TABLAS
                        'SN.id_ssn_n&&&SN.note',//CAMPOS
                        'SN.id_ssn="'.$id_session.'" AND SN.id_e="'.$info_session[0].'" AND SN.id_l="'.$info_session[1].'" AND SN.week="'.$info_session[2].'" AND SN.day="'.$info_session[3].'" AND SN.session="'.$info_session[4].'"
                          AND E.master="1" AND E.temporary="0" ',//FILTROS
                        'SN.id_ssn'//ORDEN
                        );
                  if ($note_temp == true) {
                        foreach ($note_temp as $key) { 
                              $note_temp = $key[1]; 
                              $note_temp = utf8_encode($note_temp);
                              $note_temp = str_replace("____", '"', $note_temp);
                              $id_sess_n = $key[0];
                        }
                        $is_hide = select_gral('`Session_notes_hide`',
                                               'COUNT(*)',
                                               'id_e="'.$info_session[0].'" AND id_l="'.$info_session[1].'" AND id_ssn_n="'.$id_sess_n.'"',
                                               'id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_1 = '(Oculta)'; }else{ $hide_1 = ''; } } }
                  }else{ $note_temp = ''; $id_sess_n = ''; }
                  $is_hide = '';
                  $delete_note = $id_sess_n;
            }
            if ($type_note == 2) {//SOLO NOTAS DE EVENTO
                  $class_ta1 = $class_ta3 = '';
                  $class_ta2 = 'tinymce';
                  $rows_tab2 = '7';
                  $disabled1 = $disabled3 = 'disabled';
                  $style_ta3 = 'display:none;';
                  # # # # # # # N O T A   E V E N T O 
                  $note_even = select_big('`Session_notes` AS SN INNER JOIN `Events` AS E ON E.id_e = SN.id_e INNER JOIN `Sessions` AS S ON S.id_ssn = SN.id_ssn',//TABLAS
                        'SN.id_ssn_n&&&SN.note',//CAMPOS
                        'SN.id_ssn="'.$id_session.'" AND SN.id_e="'.$info_session[0].'" AND SN.id_l="'.$info_session[1].'" AND SN.week="'.$info_session[2].'" AND SN.day="'.$info_session[3].'" AND SN.session="'.$info_session[4].'"
                          AND E.master="0" AND E.temporary="0"',//FILTROS
                        'SN.id_ssn'//ORDEN
                        );
                  if ($note_even == true) { 
                        foreach ($note_even as $key) { 
                              $note_even = $key[1];
                              $note_even = utf8_encode($note_even);
                              $note_even = str_replace("____", '"', $note_even);
                              $id_sess_n = $key[0];
                        }
                        $is_hide = select_gral('`Session_notes_hide`','COUNT(*)','id_e="'.$info_session[0].'" AND id_l="'.$info_session[1].'" AND id_ssn_n="'.$id_sess_n.'"','id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_2 = '(Oculta)'; }else{ $hide_2 = ''; } } }
                  }else{ $note_even = ''; $id_sess_n = ''; }
                  $delete_note = $id_sess_n;
                  $id_sess_n = '';
                  # # # # # # # N O T A   T E M P L A T E
                  $note_temp = select_big('`Session_notes` AS SN INNER JOIN `Events` AS E ON E.id_e = SN.id_e INNER JOIN `Sessions` AS S ON S.id_ssn = SN.id_ssn',//TABLAS
                        'SN.id_ssn_n&&&SN.note',//CAMPOS
                        'SN.id_ssn="'.$id_session.'" AND SN.id_e="'.$info_temp[0].'" AND SN.id_l="'.$info_temp[1].'" AND SN.week="'.$info_session[2].'" AND SN.day="'.$info_session[3].'" AND SN.session="'.$info_session[4].'"
                          AND E.master="1" AND E.temporary="0"',
                        'SN.id_ssn'//ORDEN
                        );
                  if ($note_temp == true) { 
                        foreach ($note_temp as $key) { 
                              $note_temp = $key[1]; 
                              $note_temp = utf8_encode($note_temp);
                              $note_temp = str_replace("____", '"', $note_temp);
                              $id_sess_n = $key[0];
                        }
                        $is_hide = select_gral('`Session_notes_hide`','COUNT(*)','id_e="'.$info_session[0].'" AND id_l="'.$info_session[1].'" AND id_ssn_n="'.$id_sess_n.'"','id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_1 = '(Oculta)'; }else{ $hide_1 = ''; } } }
                  }else{ $note_temp = ''; $id_sess_n = ''; }
            }
            if ($type_note == 3) {//SOLO LAS NOTAS DE EVENTO SECUNDARIO
                  $class_ta1 = $class_ta2 = '';
                  $class_ta3 = 'tinymce';
                  $rows_tab3 = '6';
                  $disabled1 = $disabled2 = 'disabled';
                  # # # # # # # N O T A   T E M P L A T E
                  $note_temp = select_big('(SELECT SNE.id_ssn_n FROM `Session_notes_extra` AS SNE '.
                                                #INNER JOIN `Session_notes` AS SN ON SN.id_e = SNE.id_e 
                                                'INNER JOIN `Events` AS E ON E.id_e = SNE.id_e
                                                      WHERE SNE.id_e="'.$info_temp[0].'") AS A 
                                                            INNER JOIN `Session_notes` AS B ON B.id_ssn_n = A.id_ssn_n
                                                            INNER JOIN `Events` AS E ON E.id_e = B.id_E',//TABLAS
                        'A.id_ssn_n&&&B.note',//CAMPOS
                        'B.week="'.$info_session[2].'" AND B.day="'.$info_session[3].'" AND B.session="'.$info_session[4].'" AND E.master="1" AND E.temporary="0" ',//FILTROS
                        'A.id_ssn_n'//ORDEN
                        );
                  if ($note_temp == true) { 
                        foreach ($note_temp as $key) { 
                              $note_temp = $key[1];
                              $note_temp = utf8_encode($note_temp);
                              $note_temp = str_replace("____", '"', $note_temp);
                              $id_sess_n = $key[0];
                        }
                        $is_hide = select_gral('`Session_notes_hide`','COUNT(*)','id_e="'.$info_temp[0].'" AND id_l="'.$info_temp[1].'" AND id_ssn_n="'.$id_sess_n.'"','id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_1 = '(Oculta)'; }else{ $hide_1 = ''; } } }
                  }else{ $note_temp = ''; $id_sess_n = ''; }
                  $id_sess_n = '';
                  # # # # # # # N O T A   E V E N T O 
                  $note_even = select_big('(SELECT SNE.id_ssn_n FROM `Session_notes_extra` AS SNE '.
                                                #INNER JOIN `Session_notes` AS SN ON SN.id_e = SNE.id_e 
                                                'INNER JOIN `Events` AS E ON E.id_e = SNE.id_e
                                                      WHERE SNE.id_e="'.$info_temp[0].'") AS A 
                                                            INNER JOIN `Session_notes` AS B ON B.id_ssn_n = A.id_ssn_n
                                                            INNER JOIN `Events` AS E ON E.id_e = B.id_E',//TABLAS
                        'A.id_ssn_n&&&B.note',//CAMPOS
                        'B.week="'.$info_session[2].'" AND B.day="'.$info_session[3].'" AND B.session="'.$info_session[4].'" AND E.master="0" AND E.temporary="0"',//FILTROS
                        'A.id_ssn_n'//ORDEN
                        );
                  if ($note_even == true) { 
                        foreach ($note_even as $key) { 
                              $note_even = $key[1];
                              $note_even = utf8_encode($note_even);
                              $note_even = str_replace("____", '"', $note_even);
                              $id_sess_n = $key[0];
                        }
                        $is_hide = select_gral('`Session_notes_hide`','COUNT(*)','id_e="'.$info_temp[0].'" AND id_l="'.$info_temp[1].'" AND id_ssn_n="'.$id_sess_n.'"','id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_2 = '(Oculta)'; }else{ $hide_2 = ''; } } }
                  }else{ $note_even = ''; $id_sess_n = ''; }
                  $id_sess_n = '';
                  # # # # # # # N O T A   E V E N T O   S E C U N D A R I O
                  $note_secd = select_big('`Session_notes` AS SN INNER JOIN `Events` AS E ON E.id_e = SN.id_e INNER JOIN `Sessions` AS S ON S.id_ssn = SN.id_ssn',//TABLAS
                        'SN.id_ssn_n&&&SN.note',//CAMPOS
                        'SN.id_ssn="'.$id_session.'" AND SN.id_e="'.$info_session[0].'" AND SN.id_l="'.$info_session[1].'" AND SN.week="'.$info_session[2].'" AND SN.day="'.$info_session[3].'" AND SN.session="'.$info_session[4].'"
                          AND E.master="0" AND E.temporary <> "0"',//FILTROS
                        'SN.id_ssn'//ORDEN
                        );
                  if ($note_secd == true) { 
                        foreach ($note_secd as $key) { 
                              $note_secd = $key[1];
                              $note_secd = utf8_encode($note_secd);
                              $note_secd = str_replace("____", '"', $note_secd);
                              $id_sess_n = $key[0];
                        }
                        $is_hide = select_gral('`Session_notes_hide`','COUNT(*)','id_e="'.$info_session[0].'" AND id_l="'.$info_session[1].'" AND id_ssn_n="'.$id_sess_n.'"','id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_3 = '(Oculta)'; }else{ $hide_3 = ''; } } }
                  }else{ $note_secd = ''; $id_sess_n = ''; }
                  $delete_note = $id_sess_n;
            }
            if ($read_only == 1) {
                  $class_ta1 = $class_ta2 = $class_ta3 = '';
                  $disabled1 = $disabled2 = $disabled3 = 'disabled';
            }
            $table .= '       <tr><td><input type="hidden" id="info_sess" value="'.$info_sess.'"></td></tr>
                              <tr>
                                    <td>
                                          <div style="'.$style_ta1.'"> <b>Notas Template: '.$hide_1.'</b><br>
                                                <textarea rows="'.$rows_tab1.'" id="notes_sesion_temp" name="notes_sesion_temp" style="width: 100%" class="'.$class_ta1.'" placeholder="Agrega aqui las notas para esta semana." '.$disabled1.'>'.$note_temp.'</textarea>
                                          </div>
                                    </td>
                              </tr>';

            $table .= '       <tr>
                                    <td>
                                          <div style="'.$style_ta2.'"> <b>Notas Grupo: '.$hide_2.'</b><br>
                                                <textarea rows="'.$rows_tab2.'" id="notes_sesion_event" name="notes_sesion_event" style="width: 100%" class="'.$class_ta2.'" placeholder="Agrega aqui las notas para esta semana." '.$disabled2.'>'.$note_even.'</textarea>
                                          </div>
                                    </td>
                              </tr>';

            $table .= '       <tr>
                                    <td>
                                          <div style="'.$style_ta3.'"> <b>Notas Grupo: '.$hide_3.'</b><br>
                                                <textarea rows="'.$rows_tab3.'" id="notes_sesion_event_sec" name="notes_sesion_event_sec" style="width: 100%" class="'.$class_ta3.'" placeholder="Agrega aqui las notas para esta semana." '.$disabled3.'>'.$note_secd.'</textarea>
                                          </div>
                                    </td>
                              </tr>';
            $table .= ' </table>';

            $class_button = 'btn-disabled';
            $class_button = 'btn-primary';
            $table .= ' <center>
                              <p class="stdformbutton">
                                    <a class="btn '.$class_button.'" id="btn_box_'.$type.'" onclick="save_update_session_note('.$type_note.'); return false;">Guardar</a>
                                    <a class="btn btn-default" type="reset" onclick="close_popup('.$type.'); return false;" >Cancelar</a>
                              </p>
                        </center>
                        <a class="manual_input" href="#" style="text-align: right;" onclick="delete_session_note('.$delete_note.','.$type_note.'); return false;">Eliminar Nota</a>';
            return $type.'|&|'.$table.'|&|'.$type_note;
      }
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
      ///////////////
?>
