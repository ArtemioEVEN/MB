<?php
      date_default_timezone_set("America/Mexico_City");

      sajax_export("show_session_notes");
      sajax_export("save_update_session_note");
      sajax_export("load_extra_notes");
      sajax_export("save_relation_note");
      sajax_export("show_session_notes_week");
      sajax_export("save_relation_note_hide");
      sajax_export("delete_session_note");
      sajax_export("load_sessions_nf");
      sajax_export("check_session_not_found");

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
      function save_update_session_note($type_note, $info_sess, $note){
            ////info_sess = id_event+'@@'+id_level+'@@'+week+'@@'+day+'@@'+no_sess
            $res = $class = '';
            $close = 1;
            $info_sess = explode('@@', $info_sess);
            if ($type_note == 1) {//NOTA EN TEMPLATE
                  #  B U S C A M O S   S I   E X I S T E   U N A   N O T A   A Q U I
                  $id_note = select_big('`Session_notes` AS SN INNER JOIN `Events` AS E ON E.id_e = SN.id_e INNER JOIN `Sessions` AS S ON S.id_ssn = SN.id_ssn',//TABLAS
                        'SN.id_ssn_n',//CAMPOS
                        'SN.id_ssn="'.$info_sess[5].'" AND SN.id_e="'.$info_sess[0].'" AND SN.id_l="'.$info_sess[1].'" AND SN.week="'.$info_sess[2].'" AND SN.day="'.$info_sess[3].'" AND SN.session="'.$info_sess[4].'"
                          AND E.master="1" AND E.temporary="0"',//FILTROS
                        'SN.id_ssn'//ORDEN
                        );
                  if ($id_note == true) { foreach ($id_note as $key) { $id_note = $key[0]; } }else{ $id_note = 0; }
                  if ($id_note > 0) {
                        #  M O D I F I C A M O S   L A   N O T A   Q U E   Y A   E X I S T E
                        $edit_note = update_gral('`Session_notes`', 'note="'.$note.'"', 'id_ssn_n="'.$id_note.'"');
                        ($edit_note == 1) ? $res = 'La nota se modificó correctamente...' : $res = 'La nota no se pudó modificar...';
                        ($edit_note == 1) ? $close = 1 : $res = $close = 0;
                  }else{
                        #  A G R E G A M O S   U N A   N U E V A   N O T A   E N   L A   S E S I O N
                        $add_note = insert_gral('`Session_notes`',
                                                'id_ssn, id_e, id_l, week, day, session, note',
                                                '"'.$info_sess[5].'","'.$info_sess[0].'","'.$info_sess[1].'","'.$info_sess[2].'","'.$info_sess[3].'","'.$info_sess[4].'","'.$note.'"');
                        ($add_note > 0) ? $res = 'La nota se agregó correctamente...' : $res = 'No se pudó agregar la nueva nota...';
                        ($add_note > 0) ? $close = 1 : $res = $close = 0;
                        $class = "note_".$info_sess[4]."_".$info_sess[3]."_".$info_sess[2];
                  }
            }
            if ($type_note == 2) {//NOTA EVENTO
                  #  B U S C A M O S   S I   E X I S T E   U N A   N O T A   A Q U I
                  $id_note = select_big('`Session_notes` AS SN INNER JOIN `Events` AS E ON E.id_e = SN.id_e INNER JOIN `Sessions` AS S ON S.id_ssn = SN.id_ssn',//TABLAS
                        'SN.id_ssn_n',//CAMPOS
                        'SN.id_ssn="'.$info_sess[5].'" AND SN.id_e="'.$info_sess[0].'" AND SN.id_l="'.$info_sess[1].'" AND SN.week="'.$info_sess[2].'" AND SN.day="'.$info_sess[3].'" AND SN.session="'.$info_sess[4].'"
                          AND E.master="0" AND E.temporary="0"',//FILTROS
                        'SN.id_ssn'//ORDEN
                        );
                  if ($id_note == true) { foreach ($id_note as $key) { $id_note = $key[0]; } }else{ $id_note = 0; }
                  if ($id_note > 0) {
                        #  M O D I F I C A M O S   L A   N O T A   Q U E   Y A   E X I S T E
                        $edit_note = update_gral('`Session_notes`', 'note="'.$note.'"', 'id_ssn_n="'.$id_note.'"');
                        ($edit_note == 1) ? $res = 'La nota se modificó correctamente...' : $res = 'La nota no se pudó modificar...';
                        ($edit_note == 1) ? $close = 1 : $res = $close = 0;
                  }else{
                        #  A G R E G A M O S   U N A   N U E V A   N O T A   E N   L A   S E S I O N
                        $add_note = insert_gral('`Session_notes`',
                                                'id_ssn, id_e, id_l, week, day, session, note',
                                                '"'.$info_sess[5].'","'.$info_sess[0].'","'.$info_sess[1].'","'.$info_sess[2].'","'.$info_sess[3].'","'.$info_sess[4].'","'.$note.'"');
                        ($add_note > 0) ? $res = 'La nota se agregó correctamente...' : $res = 'No se pudó agregar la nueva nota...';
                        ($add_note > 0) ? $close = 1 : $res = $close = 0;
                        $class = "note_".$info_sess[4]."_".$info_sess[3]."_".$info_sess[2];
                  }
            }
            if ($type_note == 3) {//NOTA EVENTO SECUNDARIO
                  #  B U S C A M O S   S I   E X I S T E   U N A   N O T A   A Q U I
                  $id_note = select_big('`Session_notes` AS SN INNER JOIN `Events` AS E ON E.id_e = SN.id_e INNER JOIN `Sessions` AS S ON S.id_ssn = SN.id_ssn',//TABLAS
                        'SN.id_ssn_n',//CAMPOS
                        'SN.id_ssn="'.$info_sess[5].'" AND SN.id_e="'.$info_sess[0].'" AND SN.id_l="'.$info_sess[1].'" AND SN.week="'.$info_sess[2].'" AND SN.day="'.$info_sess[3].'" AND SN.session="'.$info_sess[4].'"
                          AND E.master="0" AND E.temporary <> "0"',//FILTROS
                        'SN.id_ssn'//ORDEN
                        );
                  if ($id_note == true) { foreach ($id_note as $key) { $id_note = $key[0]; } }else{ $id_note = 0; }
                  if ($id_note > 0) {
                        #  M O D I F I C A M O S   L A   N O T A   Q U E   Y A   E X I S T E
                        $edit_note = update_gral('`Session_notes`', 'note="'.$note.'"', 'id_ssn_n="'.$id_note.'"');
                        ($edit_note == 1) ? $res = 'La nota se modificó correctamente...' : $res = 'La nota no se pudó modificar...';
                        ($edit_note == 1) ? $close = 1 : $res = $close = 0;
                  }else{
                        #  A G R E G A M O S   U N A   N U E V A   N O T A   E N   L A   S E S I O N
                        $add_note = insert_gral('`Session_notes`',
                                                'id_ssn, id_e, id_l, week, day, session, note',
                                                '"'.$info_sess[5].'","'.$info_sess[0].'","'.$info_sess[1].'","'.$info_sess[2].'","'.$info_sess[3].'","'.$info_sess[4].'","'.$note.'"');
                        ($add_note > 0) ? $res = 'La nota se agregó correctamente...' : $res = 'No se pudó agregar la nueva nota...';
                        ($add_note > 0) ? $close = 1 : $res = $close = 0;
                        $class = "note_".$info_sess[4]."_".$info_sess[3]."_".$info_sess[2];
                  }
            }
            if ($type_note == 4) {//NOTAS PARA SEMANAS EN DETERMINADO EVENTO
                  #  B U S C A M O S   S I   E X I S T E   N O T A   D E   S E M A N A  E N   D E T E R M I N A D O   E V E N T O
                  $id_note = select_big('`Session_notes`',//TABLAS
                                        'id_ssn_n',//CAMPOS
                                        'id_ssn="n/a" AND id_e="'.$info_sess[0].'" AND id_l="'.$info_sess[1].'" AND week="'.$info_sess[2].'" AND day="n/a" AND session="n/a"',//FILTROS
                                        'id_ssn_n'//ORDEN
                                    );
                  if ($id_note == true) { foreach ($id_note as $key) { $id_note = $key[0]; } }else{ $id_note = 0; }
                  if ($id_note > 0) {
                        #  M O D I F I C A M O S   L A   N O T A   Q U E   Y A   E X I S T E
                        $edit_note = update_gral('`Session_notes`', 'note="'.$note.'"', 'id_ssn_n="'.$id_note.'"');
                        ($edit_note == 1) ? $res = 'La nota se modificó correctamente...' : $res = 'La nota no se pudó modificar...';
                        ($edit_note == 1) ? $close = 1 : $res = $close = 0;
                  }else{
                        #  A G R E G A M O S   U N A   N U E V A   N O T A   S E M A N A L
                        $add_note = insert_gral('`Session_notes`',
                                                'id_e, id_l, week, note',
                                                '"'.$info_sess[0].'","'.$info_sess[1].'","'.$info_sess[2].'","'.$note.'"');
                        ($add_note > 0) ? $res = 'La nota se agregó correctamente...' : $res = 'No se pudó agregar la nueva nota...';
                        ($add_note > 0) ? $close = 1 : $res = $close = 0;
                        $class = "note_week_".$info_sess[2];
                  }
            }
            return '24@-@'.$res.'--'.$close.'--'.$class.'@-@'.$type_note;
      }
      ///////////////
      function load_extra_notes($id_event,$id_level,$id_temp,$id_temp_l,$id_sec,$id_sec_l,$type,$edit_sec){
            $edit_sec = intval($edit_sec);
            # type 1 = evento, 2 = secundario, 3 = template # edit_sec true/false (1/0)
            $title = $id_event_asign = $id_level_asign = '';//$action = '';
            if ($type == 1) { # Para ocultar notas de un template en un evento
                  $id_event_asign = $id_event;
                  $id_level_asign = $id_level;

                  $event_name = select_gral('`Events`','name_e','id_e="'.$id_event_asign.'"','name_e');
                  if ($event_name == true) { foreach ($event_name as $e_n) { $event_name = $e_n[0]; } }
                  $event_level = select_gral('`Levels`','name_l','id_l="'.$id_level_asign.'"','name_l');
                  if ($event_level == true) { foreach ($event_level as $e_l) { $event_level = $e_l[0]; } }
                  $title = '<h4>Ocultar notas para el grupo <b>'.$event_name.'</b> nivel <b>'.$event_level.'</b></h4><br>';
                  $this_table = 'Grupo';
            }elseif ($type == 2) { # Para conservar notas de template y/o evento en un evento secundario ya independiente
                  $id_event_asign = $id_sec;
                  $id_level_asign = $id_sec_l;

                  $event_name = select_gral('`Events`','name_e','id_e="'.$id_event_asign.'"','name_e');
                  if ($event_name == true) { foreach ($event_name as $e_n) { $event_name = $e_n[0]; } }
                  $title = '<h4>Ocultar notas para el grupo <b>'.$event_name.'</b></h4><br>';
                  $this_table = 'Grupo Secundario';
            }elseif ($type == 3) {
                  $event_name = select_gral('`Events`','name_e','id_e="'.$id_temp.'"','name_e');
                  if ($event_name == true) { foreach ($event_name as $e_n) { $event_name = $e_n[0]; } }
                  $event_level = select_gral('`Levels`','name_l','id_l="'.$id_temp_l.'"','name_l');
                  if ($event_level == true) { foreach ($event_level as $e_l) { $event_level = $e_l[0]; } }
                  $title = '<h5>Ocultar notas para el template <b>'.$event_name.'</b> nivel <b>'.$event_level.'</b></h5><br>';
                  $this_table = 'Template';
            }
            $table = '';
            $table .= $title;
            $table .= '<table class="table table-bordered responsive">
                        <thead>
                              <tr><th colspan="5"><center>Notas de Template</center></th></tr>
                              <tr> <th>Nota</th><th>Semana</th><th>Día</th><th>Sesión</th><th>Ocultar</th> </tr>
                        </thead>';

            if ($edit_sec == 1) {
                  $notes_temp = select_gral('`Session_notes` AS SN INNER JOIN `Events` AS E ON E.id_e = SN.id_e', 
                                            'SN.id_ssn_n,SN.id_ssn,SN.id_e,SN.id_l,SN.week,SN.day,SN.session,SN.note',
                                            'SN.id_ssn_n IN (SELECT id_ssn_n FROM `Session_notes_extra` WHERE id_e="'.$id_event_asign.'") AND E.master="1"',
                                            'SN.day,SN.session,SN.week,SN.id_ssn_n');
            }else{
                  $notes_temp = select_gral('`Session_notes`',
                                            'id_ssn_n,id_ssn,id_e,id_l,week,day,session,note',
                                            'id_e="'.$id_temp.'" AND id_l="'.$id_temp_l.'"',
                                            'day,session,week,id_ssn_n');
            }
            if ($notes_temp == true) {
                  foreach ($notes_temp as $key) {
                        $checked = '';
                        $table .= '<tr>';
                        $table .= ' <td>'.utf8_encode($key[7]).'</td>
                                    <td>'.$key[4].'</td>
                                    <td>'.$key[5].'</td>
                                    <td>'.$key[6].'</td>
                                    <td><input id="chk'.$key[0].'" type="checkbox" onclick="';
                        if ($type != 3) {
                              $table .= 'save_relation_note_hide('.$key[0].','.$id_event_asign.','.$id_level_asign.', this.id)';
                              $session_exist = select_gral('`Session_notes_hide`','COUNT(*)','id_ssn_n="'.$key[0].'" AND id_e="'.$id_event_asign.'" AND id_l="'.$id_level_asign.'"','id_ssn_n_h');
                        }else{
                              $table .= 'save_relation_note_hide('.$key[0].','.$id_temp.','.$id_temp_l.', this.id)';
                              $session_exist = select_gral('`Session_notes_hide`','COUNT(*)','id_ssn_n="'.$key[0].'" AND id_e="'.$id_temp.'" AND id_l="'.$id_temp_l.'"','id_ssn_n_h');
                        }
                        
                        if ($session_exist == true) { foreach ($session_exist as $s_e) { $session_exist = intval($s_e[0]); } }
                        ($session_exist > 0) ? $checked = 'checked' : $checked = '';
                        $table .= '" '.$checked.'/>'.'</td>';
                        $table .= '</tr>';
                  }
            }else{ $table .= '<tr><td colspan="5">Notas de Template no disponibles...</td></tr>'; }
            if ($type == 2) { # Se agrega tabla con notas de evento base para secundario
                  $table .= '<!--<table class="table table-bordered responsive">-->
                              <thead>
                                    <tr><th colspan="5"><center>Notas de Grupo</center></th></tr>
                                    <tr> <th>Nota</th><th>Semana</th><th>Día</th><th>Sesión</th><th>Ocultar</th> </tr>
                              </thead>';
                  if ($edit_sec == 1) {
                        $notes_even = select_gral('`Session_notes` AS SN INNER JOIN `Events` AS E ON E.id_e = SN.id_e', 
                                            'SN.id_ssn_n,SN.id_ssn,SN.id_e,SN.id_l,SN.week,SN.day,SN.session,SN.note',
                                            'SN.id_ssn_n IN (SELECT id_ssn_n FROM `Session_notes_extra` WHERE id_e="'.$id_event_asign.'") AND E.master="0"',
                                            'SN.day,SN.session,SN.week,SN.id_ssn_n');
                  }else{
                        $notes_even = select_gral('`Session_notes`',
                                                  'id_ssn_n,id_ssn,id_e,id_l,week,day,session,note',
                                                  'id_e="'.$id_event.'" AND id_l="'.$id_level.'"',
                                                  'id_ssn_n,week,day,session');
                  }
                  if ($notes_even == true) {
                        foreach ($notes_even as $key) {
                              $checked = '';
                              $table .= '<tr>';
                              $table .= ' <td>'.utf8_encode($key[7]).'</td>
                                          <td>'.$key[4].'</td>
                                          <td>'.$key[5].'</td>
                                          <td>'.$key[6].'</td>
                                          <td><input id="chk'.$key[0].'" type="checkbox" onclick="';
                              $table .= 'save_relation_note_hide('.$key[0].','.$id_event_asign.','.$id_level_asign.', this.id)';
                              $session_exist = select_gral('`Session_notes_hide`','COUNT(*)','id_ssn_n="'.$key[0].'" AND id_e="'.$id_event_asign.'" AND id_l="'.$id_level_asign.'"','id_ssn_n_h');
                              if ($session_exist == true) { foreach ($session_exist as $s_e) { $session_exist = intval($s_e[0]); } }
                              ($session_exist > 0) ? $checked = 'checked' : $checked = '';
                              $table .= '" '.$checked.' />'.'</td>';
                              $table .= '</tr>';
                        }
                  }else{ $table .= '<tr><td colspan="5">Notas de Grupo no disponibles...</td></tr>'; }
            }
            if ($type != 3) {
                  $table .= '<!--<table class="table table-bordered responsive">-->
                              <thead>
                                    <tr><th colspan="5"><center>Notas de este '.$this_table.'</center></th></tr>
                                    <tr> <th>Nota</th><th>Semana</th><th>Día</th><th>Sesión</th><th>Ocultar</th> </tr>
                              </thead>';
                  $own_notes = select_gral('`Session_notes`',
                                                    'id_ssn_n,id_ssn,id_e,id_l,week,day,session,note',
                                                    'id_e="'.$id_event_asign.'" AND id_l="'.$id_level_asign.'"',
                                                    'day,session,week,id_ssn_n');//ESTE ES EL ORDEN CORRECTO PARA DEJAR ALULTIMO LAS SEMNAS
                  if ($own_notes == true) {
                        foreach ($own_notes as $key) {
                              $checked = '';
                              $table .= '<tr>';
                              $table .= ' <td>'.utf8_encode($key[7]).'</td>
                                          <td>'.$key[4].'</td>
                                          <td>'.$key[5].'</td>
                                          <td>'.$key[6].'</td>
                                          <td><input id="chk'.$key[0].'" type="checkbox" onclick="';
                              $table .= 'save_relation_note_hide('.$key[0].','.$id_event_asign.','.$id_level_asign.', this.id)';
                              $session_exist = select_gral('`Session_notes_hide`','COUNT(*)','id_ssn_n="'.$key[0].'" AND id_e="'.$id_event_asign.'" AND id_l="'.$id_level_asign.'"','id_ssn_n_h');
                              if ($session_exist == true) { foreach ($session_exist as $s_e) { $session_exist = intval($s_e[0]); } }
                              ($session_exist > 0) ? $checked = 'checked' : $checked = '';
                              $table .= '" '.$checked.' />'.'</td>';
                              $table .= '</tr>';
                        }
                  }else{ $table .= '<tr><td colspan="5">Notas de '.$this_table.' no disponibles...</td></tr>'; }
            }
            $table .= '</table>';
            $table .= '<br> <a class="btn btn-default" type="reset" onclick="close_load_extra_notes(); return false;" >Cerrar Opción</a>';
            return $table.'&&&#extra_notes';
      }
      ///////////////
      function save_relation_note($id_note_session,$id_sec_event,$save){
            $res = '';
            if ($save == 1) { $res = insert_gral("`Session_notes_extra`", 'id_ssn_n, id_e', '"'.$id_note_session.'","'.$id_sec_event.'"');//Se guarda
            }else{ $res = delete_gral('`Session_notes_extra`', 'id_ssn_n = "'.$id_note_session.'" AND id_e = "'.$id_sec_event.'" '); }//Se borra
            return '25@-@'.$res.'@-@'.$save.',1';
      }
      ///////////////
      function save_relation_note_hide($id_note_session,$id_sec_event,$id_lev_event,$save){
            $res = '';
            if ($save == 1) {
                  $res = insert_gral("`Session_notes_hide`",'id_ssn_n,id_e,id_l', '"'.$id_note_session.'","'.$id_sec_event.'","'.$id_lev_event.'"');
            }else{ $res = delete_gral("`Session_notes_hide`",'id_ssn_n="'.$id_note_session.'" AND id_e="'.$id_sec_event.'" AND id_l="'.$id_lev_event.'"'); }
            return '25@-@'.$res.'@-@'.$save.',2';
      }
      ///////////////
      function show_session_notes_week($type,$week,$id_event,$id_level,$read_only){
            $delete_note = 0;
            $type_note = 0;
            $rows_tab1 = $rows_tab2 = $rows_tab3 = '3';
            $disabled1 = $disabled2 = $disabled3 = '';
            $style_ta1 = $style_ta2 = $style_ta3 = '';
            $class_ta1 = $class_ta2 = $class_ta3 = '';
            $hide_1 = $hide_2 = $hide_3 = '';
            $id_name1 = $id_name2 = $id_name3 = '';
            $note_temp = $note_even = $note_secd = '';
            ## DETERMINAMOS QUE TIPO DE EVENTO ES
            $event_info = select_gral('`Events`','master,temporary','id_e="'.$id_event.'"','id_e');
            if($event_info == true){ 
                  foreach ($event_info as $key){ $master_e = intval($key[0]); $tempor_e = intval($key[1]); }
            }else{ $master_e = ''; $tempor_e = ''; }
            if ($master_e == 1 && $tempor_e == 0) { $type_note = 1; } # NOTA TEMPLATE
            if ($master_e == 0 && $tempor_e == 0) { $type_note = 2; } # NOTA EVENTO
            if ($tempor_e > 0) { $type_note = 3; } # NOTA SEC
            if ($type_note == 1) {
                  $class_ta1 = 'tinymce';
                  $class_ta2 = $class_ta3 = '';
                  $rows_tab1 = '7';
                  $disabled2 = $disabled3 = 'disabled';
                  $style_ta2 = $style_ta3 = 'display:none;';
                  $id_name1  = 'notes_event_week';
                  $id_temp    = $id_event;
                  $level_temp = $id_level;
                  $note_temp = select_gral('`Session_notes`', 'note,id_ssn_n',
                                      'id_e="'.$id_temp.'" AND id_l="'.$level_temp.'" AND week="'.$week.'" AND day="n/a" AND session="n/a"', 'note');
                  if($note_temp == true){ 
                        foreach ($note_temp as $key) { 
                              $note_temp = $key[0]; 
                              $note_temp = utf8_encode($note_temp);
                              $note_temp = str_replace("____", '"', $note_temp);
                              $id_sess_n    = $key[1];
                        }
                        $is_hide = select_gral('`Session_notes_hide`',
                                               'COUNT(*)',
                                               'id_e="'.$id_event.'" AND id_l="'.$id_level.'" AND id_ssn_n="'.$id_sess_n.'"',
                                               'id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_1 = '(Oculta)'; }else{ $hide_1 = ''; } } }
                  }else{ $note_temp = ''; $id_sess_n = ''; }
                  $delete_note = $id_sess_n;
            }elseif ($type_note == 2) {
                  $class_ta1 = $class_ta3 = '';
                  $class_ta2 = 'tinymce';
                  $rows_tab2 = '7';
                  $disabled1 = $disabled3 = 'disabled';
                  $style_ta3 = 'display:none;';
                  $id_name2  = 'notes_event_week';
                  $id_evnt    = $id_event;
                  $level_evnt = $id_level;
                  $get_temp_info = select_gral('`Assignments`','id_m,level_m','id_e="'.$id_evnt.'" AND level_e="'.$level_evnt.'"','id_e');
                  if ($get_temp_info == true) { foreach ($get_temp_info as $ti) { $id_temp = $ti[0]; $level_temp = $ti[1]; } }else{ $id_temp = 0; $level_temp = 0; }
                  # # # # # # # N O T A   E V E N T O 
                  $note_even = select_gral('`Session_notes`', 'note,id_ssn_n',
                                      'id_e="'.$id_evnt.'" AND id_l="'.$level_evnt.'" AND week="'.$week.'" AND day="n/a" AND session="n/a"', 'note');
                  if($note_even == true){ 
                        foreach ($note_even as $key) { 
                              $note_even = $key[0]; 
                              $note_even = utf8_encode($note_even);
                              $note_even = str_replace("____", '"', $note_even);
                              $id_sess_n    = $key[1];
                        }
                        $is_hide = select_gral('`Session_notes_hide`',
                                               'COUNT(*)',
                                               'id_e="'.$id_evnt.'" AND id_l="'.$level_evnt.'" AND id_ssn_n="'.$id_sess_n.'"',
                                               'id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_2 = '(Oculta)'; }else{ $hide_2 = ''; } } }
                  }else{ $note_even = ''; $id_sess_n = ''; }
                  $delete_note = $id_sess_n;
                  $id_sess_n = '';
                  # # # # # # # N O T A   T E M P L A T E
                  $note_temp = select_gral('`Session_notes`', 'note,id_ssn_n',
                                      'id_e="'.$id_temp.'" AND id_l="'.$level_temp.'" AND week="'.$week.'" AND day="n/a" AND session="n/a"', 'note');
                  if($note_temp == true){ 
                        foreach ($note_temp as $key) {
                              $note_temp = $key[0]; 
                              $note_temp = utf8_encode($note_temp);
                              $note_temp = str_replace("____", '"', $note_temp);
                              $id_sess_n    = $key[1];
                        }
                        $is_hide = select_gral('`Session_notes_hide`',
                                               'COUNT(*)',
                                               'id_e="'.$id_evnt.'" AND id_l="'.$level_evnt.'" AND id_ssn_n="'.$id_sess_n.'"',
                                               'id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_1 = '(Oculta)'; }else{ $hide_1 = ''; } } }
                  }else{ $note_temp = ''; $id_sess_n = ''; }
                  # # # # # # #
            }elseif ($type_note == 3) {
                  $class_ta1 = $class_ta2 = '';
                  $class_ta3 = 'tinymce';
                  $rows_tab3 = '6';
                  $disabled1 = $disabled2 = 'disabled';
                  $id_name3  = 'notes_event_week';
                  $id_temp    = '';
                  $level_temp = '';
                  $id_evnt    = '';
                  $level_evnt = '';
                  $id_evsc    = $id_event;
                  $level_evsc = $id_level;
                  # # # # # # # N O T A   S E C U N D A R I O
                  $note_secd = select_gral('`Session_notes`', 'note,id_ssn_n',
                                      'id_e="'.$id_evsc.'" AND id_l="'.$level_evsc.'" AND week="'.$week.'" AND day="n/a" AND session="n/a"', 'note');
                  if($note_secd == true){ 
                        foreach ($note_secd as $key) { 
                              $note_secd = $key[0]; 
                              $note_secd = utf8_encode($note_secd);
                              $note_secd = str_replace("____", '"', $note_secd);
                              $id_sess_n = $key[1];
                        }
                        $is_hide = select_gral('`Session_notes_hide`',
                                               'COUNT(*)',
                                               'id_e="'.$id_evsc.'" AND id_l="'.$level_evsc.'" AND id_ssn_n="'.$id_sess_n.'"',
                                               'id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_3 = '(Oculta)'; }else{ $hide_3 = ''; } } }
                  }else{ $note_secd = ''; $id_sess_n = ''; }
                  $delete_note = $id_sess_n;
                  $id_sess_n = '';
                  # # # # # # # N O T A   E V E N T O 
                  $note_even = select_big('(SELECT SNE.id_ssn_n FROM `Session_notes_extra` AS SNE 
                                                INNER JOIN `Events` AS E ON E.id_e = SNE.id_e
                                                      WHERE SNE.id_e="'.$id_evsc.'") AS A 
                                                            INNER JOIN `Session_notes` AS B ON B.id_ssn_n = A.id_ssn_n
                                                            INNER JOIN `Events` AS E ON E.id_e = B.id_E',//TABLAS
                        'A.id_ssn_n&&&B.note',//CAMPOS
                        'B.week="'.$week.'" AND B.day="n/a" AND B.session="n/a" AND E.master="0" AND E.temporary="0"',//FILTROS
                        'A.id_ssn_n'//ORDEN
                        );
                  if ($note_even == true) { 
                        foreach ($note_even as $key) { 
                              $note_even = $key[1];
                              $note_even = utf8_encode($note_even);
                              $note_even = str_replace("____", '"', $note_even);
                              $id_sess_n = $key[0];
                        }
                        $is_hide = select_gral('`Session_notes_hide`','COUNT(*)','id_e="'.$id_evsc.'" AND id_l="'.$level_evsc.'" AND id_ssn_n="'.$id_sess_n.'"','id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_2 = '(Oculta)'; }else{ $hide_2 = ''; } } }
                  }else{ $note_even = ''; $id_sess_n = ''; }
                  $id_sess_n = '';
                  # # # # # # # N O T A   T E M P L A T E
                  $note_temp = select_big('(SELECT SNE.id_ssn_n FROM `Session_notes_extra` AS SNE 
                                                INNER JOIN `Events` AS E ON E.id_e = SNE.id_e
                                                      WHERE SNE.id_e="'.$id_evsc.'") AS A 
                                                            INNER JOIN `Session_notes` AS B ON B.id_ssn_n = A.id_ssn_n
                                                            INNER JOIN `Events` AS E ON E.id_e = B.id_E',//TABLAS
                        'A.id_ssn_n&&&B.note',//CAMPOS
                        'B.week="'.$week.'" AND B.day="n/a" AND B.session="n/a" AND E.master="1" AND E.temporary="0" ',//FILTROS
                        'A.id_ssn_n'//ORDEN
                        );
                  if ($note_temp == true) { 
                        foreach ($note_temp as $key) { 
                              $note_temp = $key[1];
                              $note_temp = utf8_encode($note_temp);
                              $note_temp = str_replace("____", '"', $note_temp);
                              $id_sess_n = $key[0];
                        }
                        $is_hide = select_gral('`Session_notes_hide`','COUNT(*)','id_e="'.$id_evsc.'" AND id_l="'.$level_evsc.'" AND id_ssn_n="'.$id_sess_n.'"','id_ssn_n_h');
                        if ($is_hide == true) { foreach ($is_hide as $key) { if (intval($key[0]) > 0) { $hide_1 = '(Oculta)'; }else{ $hide_1 = ''; } } }
                  }else{ $note_temp = ''; $id_sess_n = ''; }
                  $id_sess_n = '';
                  # - - # - - # - - # - - # - - #
            }
            if ($read_only == 1) {
                  $class_ta1 = $class_ta2 = $class_ta3 = '';
                  $disabled1 = $disabled2 = $disabled3 = 'disabled';
            }
            $table = '';
            //$table .= ;
            $table .= ' <div id="load_table'.$type.'">
                        <table class="popup_table">';
            $table .= '       <tr><th><span id="session_t'.$type.'">Nota de la Semana '.$week.' </span></th></tr>';
            $info_sess = $id_event.'@@'.$id_level.'@@'.$week;
            $table .= '       <tr><td><input type="hidden" id="info_sess" value="'.$info_sess.'"></td></tr>
                              <tr>
                               <td>
                                <div style="'.$style_ta1.'"> <b>Nota Semanal Template: '.$hide_1.'</b><br>
                                 <div style="width: 100%;">
                                  <textarea id="'.$id_name1.'" name="'.$id_name1.'" rows="'.$rows_tab1.'" style="width: 100%" class="'.$class_ta1.'" placeholder="Agrega aqui las notas para esta semana." '.$disabled1.'>'.$note_temp.'
                                  </textarea>
                                 </div>
                                 <!--<textarea rows="10" id="notes_event_week" style="width: 100%;" placeholder="Agrega aqui las notas para esta semana." >'.utf8_encode($note_temp).'</textarea>-->
                                </div>
                               </td>
                              </tr>';
            $table .= '       <tr>
                               <td>
                                <div style="'.$style_ta2.'"> <b>Notas Grupo: '.$hide_2.'</b><br>
                                 <div style="width: 100%;">
                                  <textarea id="'.$id_name2.'" name="'.$id_name2.'" rows="'.$rows_tab2.'" style="width: 100%;" class="'.$class_ta2.'" placeholder="Agrega aqui las notas para esta sesion." '.$disabled2.'>'.$note_even.'
                                  </textarea>
                                 </div>
                                </div>
                               </td>
                              </tr>';
            $table .= '       <tr>
                               <td>
                                <div style="'.$style_ta3.'"> <b>Notas Grupo Secundario: '.$hide_3.'</b><br>
                                 <div style="width: 100%;">
                                  <textarea id="'.$id_name3.'" name="'.$id_name3.'" rows="'.$rows_tab3.'" style="width: 100%;" class="'.$class_ta3.'" placeholder="Agrega aqui las notas para esta sesion." '.$disabled3.'>'.$note_secd.'
                                  </textarea>
                                 </div>
                                </div>
                               </td>
                              </tr>';
            $table .= ' </table>';
            $class_button = 'btn-disabled';
            $class_button = 'btn-primary';
            $table .= ' <center>
                              <p class="stdformbutton">
                                    <a href="#" class="btn '.$class_button.'" id="btn_box_'.$type.'" onclick="save_update_session_note(4); return false;">Guardar</a>
                                    <a class="btn btn-default" type="reset" onclick="close_popup('.$type.'); return false;" >Cancelar</a>
                              </p>
                        </center>
                        <a class="manual_input" href="#" style="text-align: right;" onclick="delete_session_note('.$delete_note.','.$type_note.'); return false;">Eliminar Nota</a>';
            return $type.'|&|'.$table.'|&|4';
      }
      ///////////////
      function delete_session_note($id_note){
            $res = 0;
            $delete_notes_extra = delete_gral('`Session_notes_extra`','id_ssn_n="'.$id_note.'"');
            $delete_note_hide   = delete_gral('`Session_notes_hide`','id_ssn_n="'.$id_note.'"');
            $delete_note        = delete_gral('`Session_notes`','id_ssn_n="'.$id_note.'"');
            $res = $delete_notes_extra+$delete_note_hide+$delete_note;
            return "26@-@".$res."@-@0";
      }
      ///////////////
      function load_sessions_nf(){
      $title = $table = '';
      $title = '<h4 class="widgettitle">Sesiones no Encontradas en la Plataforma.</h4>';
      $table .= ' <table id="dyntable" class="table table-bordered responsive">
                        <thead>
                            <tr>
                              <th></th>
                                <th class="head1" title="Descripción de la Sesión"> Sesión</th>
                                <th class="head0" title="Fecha y Hora de registro"> Fecha / Hora</th>
                                <th class="head1" title="Información Adicional"> Info Gral</th>
                                <th class="head0" title="Marcar sesión como revisada."></th>
                            </tr>
                        </thead>
                        <tbody>';
      $values = select_gral('`Session_not_found`', 'id_ssnnf,session,date,id_group,(SELECT name_e FROM `Events` WHERE id_e=id_group) AS name_e', 'id_group IS NOT NULL AND completed IS NULL','id_ssnnf DESC');// tabla, campos, filtros, orden
      if($values == true){
            $i = 1;
            $date = "";
            foreach ($values as $key) {
                  $date = strtotime(substr(utf8_encode($key[2]), 0,-9));
                  $table .= ' <tr class="grade'.utf8_encode($key[0]).'">
                                <td>'.$i.'</td>
                                <td><b>'.utf8_encode($key[1]).'</b></td>';
                  $table .= '     <td>'.translate_day(date("l",$date)).' '.date("d", $date).', '.translate_month(date("F", $date)).' del '.date("y",$date).'</td>';
                  $event = "";
                  $table .= '     <td>'.$key[4].'</td>';
                  $table .= '     <td><center><a style="cursor: pointer;" title="Marcar esta sesión como revisada." onclick="confirm_check_session_not_found('.utf8_encode($key[0]).','."'".$_SESSION['name_u']."'".'); return false;"><i class="glyphicon glyphicon-ok"></i></a></center></td>';
                  $table .= ' </tr>';
                  $i++;
            }
      }
      $table .= '     </tbody>
                    </table>';
      $load_div = '<div id="load_edit_form"></div>';
      $all_content = '<div id="dashboard-left" class="col-md-8">'.$title.$table.'</div>'.$load_div;
      return $all_content.'&&&.row_aux';
}
      ///////////////
      function check_session_not_found($id_session){
            $res = 0;
            $check = update_gral('`Session_not_found`','completed="'.$_SESSION['name_u'].', '.date('Y-m-d').'"',"id_ssnnf='".$id_session."'");
            $res = $check;
            return '31@-@'.$res.'@-@'.$id_session;
      }
      ///////////////
?>
