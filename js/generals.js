function hide_div(id_or_class){
	jQuery(id_or_class).hide();
}
//---
function show_div(id_or_class){
	jQuery(id_or_class).show();
}
//---
function clear_div(id_or_class){
	jQuery(id_or_class).html("");
}
//---
function get_day(num_day){//1-L,7-D
	var day = 'none';
	num_day = parseInt(num_day);
	if (num_day == 1) { day = 'Lunes'; }
	if (num_day == 2) { day = 'Martes'; }
	if (num_day == 3) { day = 'Miercoles'; }
	if (num_day == 4) { day = 'Jueves'; }
	if (num_day == 5) { day = 'Viernes'; }
	if (num_day == 6) { day = 'Sabado'; }
	if (num_day == 7) { day = 'Domingo'; }
	return day;
}
//--- Si email ok=1 no=0
function valida_email(email) {
	var res;
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(email) ){
    	//res = "La dirección de correo " + email + " es incorrecta.";
    	res = 0;
    }else{
    	//res = "Ok";
    	res = 1;
    }
    return res;
}
//---
function escapeRegExp(str) {
    return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}
//---
function replace_string(str, find, replace) {
  return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
}
//---
function validate_numbers(event){
	var code = (event.keyCode ? event.keyCode : event.which);
	if (!((code >= 48 && code <= 57) || (code == 46) || (code == 8) || (code == 9))){//48 a 57 numeros, 46 punto, 8 backspace
		event.preventDefault();
		jQuery().toastmessage('showErrorToast', 'Este campo solo permite números...');
	}
}
