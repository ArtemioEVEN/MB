<?php
    //header("Content-type:application/json"); 
    // Session time
    // 2 hours in seconds (7200)
    $inactive = 28800;//3600;//una hora 
    ini_set('session.gc_maxlifetime', $inactive); // set the session max lifetime to 2 hours
    session_start();
    if (isset($_SESSION['testing']) && (time() - $_SESSION['testing'] > $inactive)) {
        // last request was more than 2 hours ago
        session_unset();     // unset $_SESSION variable for this page
        session_destroy();   // destroy session data
    }
    $_SESSION['testing'] = time(); // Update session
    if ((!isset($_SESSION['name_u'])) || ($_SESSION['activo'] == 0)){
        header("Location: index.html");
    }
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once('../lista_stripe/php/Sajax.php');
    $sajax_debug_mode=0;
    sajax_init();
    require_once('./php/functions_trainings.php');

    require_once("./php/functions_blocks.php");
    require_once("./php/functions_groups.php");

    require_once('./php/db_connection.php');
    sajax_handle_client_request();

    $function_load = '';
    if (isset($_GET['function']) && $_GET['function'] != '') {
        $function_load = 'onload = "'.$_GET['function'].'"';
    }
    if ($function_load == '' && $_SESSION['name_u'] == 'admin' && $_SESSION['del_events'] == 0) { //echo "Hay que revisar si hay eventos vencidos...";
        $function_load = 'onload = "delete_events_past(); load_actions_platform('."''".');"';/**/
//        $function_load = 'onload = "load_actions_platform('."''".');"';/**/
    }
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>EVEN - Entrenamientos</title>

    <link rel="stylesheet" href="./css/style.default.css" />
    <link id="theme" rel="stylesheet" href="./css/style.red.css" />
    <link rel="stylesheet" href="./css/responsive-tables.css">
    <link rel="stylesheet" href="./css/jquery.toastmessage.css">
    <!--<link rel="stylesheet" href="css/ventana_emergente.css"/>-->
    <link rel="stylesheet" href="./css/popup.css"/>
    <link rel="stylesheet" href="./css/bootstrap-fileupload.min.css" />
    <link rel="stylesheet" href="./css/bootstrap-timepicker.min.css" />
    <link rel="stylesheet" href="./css/isotope.css" />
    <link rel="stylesheet" href="./js/sweetalert-master/dist/sweetalert.css" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <script language="javascript">
        <?php sajax_show_javascript(); ?>

        var some = <?php echo $_SESSION['test_mode']; ?>;
        check_mode(some);
        function check_mode(is_test){
            is_test = parseInt(is_test);
            var id_theme = "";
            if (is_test == 1) { id_theme = "navyblue"; }else{ id_theme = "red"; }
            var link = document.getElementById('theme');
            link.setAttribute('href', './css/style.'+id_theme+'.css');
        }
    </script>
    <style type="text/css">
        table { border-collapse: collapse; font-size: 12px; }
        #notes{ }
    </style>
</head>
<body <?php echo $function_load; ?> >
<div id="mainwrapper" class="mainwrapper">
    <div class="header">
        <div class="logo">
            <a href="http://www.even.mx/"><img alt="" src="./img/logo_even_blanco_320X132.png" width="200px"></a>
        </div>
        <div style="float:left; text-align:right; color:white;"><h1 style=" font-size:  360%;"><br>mb V2</h1></div>
        <div class="headerinner" style="margin-left: 260px;">
            <ul class="headmenu">
                <li class="right">
                    <div class="userloggedinfo">
                        <div class="userinfo">
                        <h5> <?php echo $_SESSION['name_u']; ?> <small>- even.mx</small></h5>
                        <ul>
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>
                            <li><a href="#" onclick="destroy_session(); return false;">Salir</a></li>
                        </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="leftpanel">
        <div class="leftmenu">        
            <ul class="nav nav-tabs nav-stacked">
            	<li class="nav-header">Navigation</li>
                <!-- - - - - - - - - - - - -->
                <?php
                    $privileges = explode('-', $_SESSION['privileges']);
                    $priv1 = str_split($privileges[0]);
                    $priv2 = str_split($privileges[1]);
                    $priv3 = str_split($privileges[2]);
                    $priv4 = str_split($privileges[3]);
                    $priv5 = str_split($privileges[4]);
                ?>
                    <li id="act_platform" class="dropdown"><a href="" style="cursor:pointer;"><span class="iconfa-book"></span> Actividad Plataforma</a>
                        <ul>
                            <li><a href="#" style="cursor:pointer;" onclick="load_actions_platform(); return false;">Enlistar Actividades</a></li>
                        </ul>
                    </li>
                <?php
                    if ($priv1[0] == 1 || $priv1[1] == 1 || $priv1[2] == 1) { ?>
                        <li id="events" class="dropdown"><a href="" style="cursor:pointer;"><span class="iconfa-book"></span> Grupos</a>
                            <ul>
                        <?php
                            if ($priv1[0] == 1) { ?>
                                <li><a href="#" style="cursor:pointer;" onclick="get_events_form(); return false;">Definir Grupos</a></li>
                            <?php }
                            if ($priv1[2] == 1) { ?>
                                <li><a href="#" style="cursor:pointer;" onclick="get_assignments_trains_form(0); return false;">Asignar Entrenos</a></li>
                            <?php }
                            if ($priv1[1] == 1) { ?>
                                <li><a href="#" style="cursor:pointer;" onclick="get_edit_list(0,1,''); return false;">Editar Grupos</a></li>
                            <?php }
                        ?>
                            </ul>
                        </li>
                    <?php }
                    // / / / / / / / / / / / / / / / /
                    if ($priv2[0] == 1 || $priv2[1] == 1) { ?>
                        <li id="trains" class="dropdown"><a href="" style="cursor:pointer;"><span class="iconfa-calendar"></span> Template</a>
                            <ul>
                        <?php
                            if ($priv2[0] == 1) { ?>
                                <li><a href="#" style="cursor:pointer;" onclick="get_events_form_add_trains(1); return false;">Elaborar y/o Editar Template</a></li>
                            <?php }
                            if ($priv2[1] == 1) { ?>
                                <li><a href="#" style="cursor:pointer;" onclick="get_form_show_temps(); return false;">Ver Templates</a></li>
                            <?php }
                        ?>
                            </ul>
                        </li>
                    <?php }
                    /// / / / / / / / /// / /// //  / / 
                    ?>
                    <li id="blocks" class="dropdown"><a href="" style="cursor:pointer;"><span class="iconfa-th"></span> Bloques</a>
                        <ul>
                            <li><a href="#" style="cursor:pointer;" onclick="get_blocks_form(0); return false;">Elaborar Bloques</a></li>
                            <li><a href="#" style="cursor:pointer;" onclick="get_edit_list_blocks(); return false;">Editar Bloques</a></li>
                            <!--<li><a href="#" style="cursor:pointer;" onclick="get_form_show_temps(); return false;">Ver Templates</a></li>-->
                        </ul>
                    </li>
                    <?php
                    /// / / / / / / / / / / // ////  / /
                    if ($priv3[0] == 1) { ?>
                        <li id="settings" class="dropdown"><a href="" style="cursor:pointer;"><span class="iconfa-pencil"></span> General</a>
                            <ul>
                        <?php
                            if ($priv3[0] == 1) { ?>
                                <li><a href="#" style="cursor:pointer;" onclick="get_add_level_form(); return false;">Agregar Nivel</a></li>
                                <li><a href="#" style="cursor:pointer;" onclick="get_logs_dash(); return false;">Log de Actividad</a></li>
                            <?php }
                        ?>
                            </ul>
                        </li>
                    <?php }
                    // / / / / / / / / / / / / / / / /
                    if ($priv4[0] == 1) { ?>
                        <li id="summary" class="dropdown"><a href="" style="cursor:pointer;"><span class="iconfa-signal"></span> Resumen</a>
                            <ul>
                                <li><a href="#" style="cursor:pointer;" onclick="load_summary_form(''); return false;">Resumen General V2</a></li>
                                <li><a href="#" style="cursor:pointer;" onclick="load_summary_repeat_sessions(''); return false;">Resumen Sesiones</a></li>
                                <li><a href="#" style="cursor:pointer;" onclick="load_sessions_nf(''); return false;">Sessiones No Encontradas</a></li>
                            </ul>
                        </li>
                    <?php }
                    // / / / / / / / / / / / / / / / /
                    if ($priv5[0] == 1 || $priv5[1] == 1) { ?>
                        <li id="admin" class="dropdown"><a href="" style="cursor:pointer;"><span class="iconfa-user"></span> Usuarios</a>
                            <ul>
                        <?php
                            if ($priv5[0] == 1) { ?>
                                <li><a href="#" style="cursor:pointer;" onclick="form_add_user(0); return false;">Agregar Usuario</a></li>
                            <?php }
                            if ($priv5[1] == 1) { ?>
                                <li><a href="#" style="cursor:pointer;" onclick="form_add_user(1); return false;">Editar Usuario</a></li>
                            <?php }
                        ?>
                            </ul>
                        </li>
                    <?php }
                    // / / / / / / / / / / / / / / / /
                ?>
                <!-- - - - - - - - - - - - -->
            </ul>
        </div><!--leftmenu-->
    </div><!-- leftpanel -->
    <div class="rightpanel">
        <ul class="breadcrumbs">
            <li><a href="dashboard.html"><i class="iconfa-calendar"></i></a> <span class="separator"></span></li>
            <li>Entrenamientos</li>
            <li class="right">
                    <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Modo de Operación &nbsp;&nbsp;&nbsp;&nbsp;</a>
                    <ul class="dropdown-menu pull-right">
                        <li><a id="red" onclick="change_theme(this.id);">Online</a></li>
                        <li><a id="navyblue" onclick="change_theme(this.id);">Test Mode</a></li>
                    </ul>
            </li>
        </ul>
        <div class="pageheader">
            <div class="pageicon"><span class="iconfa-calendar"></span></div>
            <div class="pagetitle">
                <h5>All Features Summary</h5>
                <h1>Entrenamientos</h1>
            </div>
            <div id="test_alert" class="col-md-4"></div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row row_aux">
                        <!-- Ventana emregente -->
                        <div id="hider" onclick="close_popup();"></div>
                        <div id="popup_box">
                            <a class="close" onclick="close_popup();">X</a>
                            <br>
                            <div class="message_box"></div>
                        </div>    
                    <!--</div>--><!--col-md-8-->
                    <div id="dashboard-right" class="col-md-4">
                        <div class="divider15"></div>
                        <br />
                    </div><!-- col-md-4 -->
                </div><!--row-->
                <div class="footer">
                    <div>
                        <center>Copyright© 2001 - 2018 Evenlabs.   <br>
                        Todos Los Derechos Reservados Even, Evenlabs®  son marcas registradas de Evenlabs. <br>
                        Se prohíbe el uso de estas marcas sin el consentimiento expreso por escrito de Evenlabs S.A. de C.V.
                        </center>
                    </div>
                </div><!--footer-->
            </div><!--maincontentinner-->
        </div><!--maincontent-->
    </div><!--rightpanel-->
</div><!--mainwrapper-->

<script src="./js/jquery-1.10.2.min.js"></script>
<script src="./js/jquery-migrate-1.2.1.min.js"></script>
<script src="./js/jquery-ui-1.10.3.min.js"></script>

<script src="./js/bootstrap.min.js"></script>

<script src="./js/modernizr.min.js"></script>
<script src="./js/jquery.cookies.js"></script>
<script src="./js/jquery.uniform.min.js"></script>
<script src="./js/flot/jquery.flot.min.js"></script>
<script src="./js/flot/jquery.flot.resize.min.js"></script>
<script src="./js/responsive-tables.js"></script>
<script src="./js/jquery.slimscroll.js"></script>
<script src="./js/charCount.js"></script><!--contador de caracteres-->

<script src="./js/custom.js"></script>
<!--<script src="js/forms.js"></script>-->

<script type="text/javascript" src="./js/functions_trainings.js?<?php echo time(); ?>"></script>
    
<script type="text/javascript" src="./js/functions_blocks.js?<?php echo time(); ?>"></script>

<script type="text/javascript" src="./js/functions_groups.js?<?php echo time(); ?>"></script>
    
<!-- - W I S A R D - -->
<script type="text/javascript" src="./js/jquery.smartWizard.min.js"></script>
<!-- - A L E R T   M E S S A G E S - -->
<script type="text/javascript" src="./js/jquery.toastmessage.js?<?php echo time(); ?>"></script>
<!-- - D y n a m i c   T a b l e s - -->
<script type="text/javascript" src="./js/jquery.dataTables.min.js?<?php echo time(); ?>"></script>
<!-- - E d i t   B o x   T e x t - -->
<script type="text/javascript" src="./js/tinymce/jquery.tinymce.js?<?php echo time(); ?>"></script>
<!-- - S e a r c h   S e l e c t - -->
<script type="text/javascript" src="./js/chosen.jquery.min.js?<?php echo time(); ?>"></script>
<!-- - J a v a s c r t i p t   W i n d o w s  C S S - -->
<script type="text/javascript" src="./js/sweetalert-master/dist/sweetalert.min.js?<?php echo time(); ?>"></script>

</body>
</html>
