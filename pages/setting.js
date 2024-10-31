function _popupdbox_submit() {
	if(document.pop_form.pop_title.value == "") {
		alert(popupdbox_adminscripts.pop_title);
		document.pop_form.pop_title.focus();
		return false;
	}
	else if(document.pop_form.pop_group.value == "" && document.pop_form.pop_group_txt.value == "") {
		alert(popupdbox_adminscripts.pop_group);
		document.pop_form.pop_group_txt.focus();
		return false;
	}
	else if(document.pop_form.pop_width.value == "" || isNaN(document.pop_form.pop_width.value)) {
		alert(popupdbox_adminscripts.pop_width);
		document.pop_form.pop_width.focus();
		document.pop_form.pop_width.select();
		return false;
	}
	else if(document.pop_form.pop_deferred.value == "" || isNaN(document.pop_form.pop_deferred.value)) {
		alert(popupdbox_adminscripts.pop_deferred);
		document.pop_form.pop_deferred.focus();
		document.pop_form.pop_deferred.select();
		return false;
	}
	else if(document.pop_form.pop_showduration.value == "" || isNaN(document.pop_form.pop_showduration.value)) {
		alert(popupdbox_adminscripts.pop_showduration);
		document.pop_form.pop_showduration.focus();
		document.pop_form.pop_showduration.select();
		return false;
	}
}

function _popupdbox_delete(id) {
	if(confirm(popupdbox_adminscripts.pop_delete)) {
		document.frm_pop_display.action="options-general.php?page=popup-dropping-box&ac=del&did="+id;
		document.frm_pop_display.submit();
	}
}	

function _popupdbox_redirect() {
	window.location = "options-general.php?page=popup-dropping-box";
}

function _popupdbox_help() {
	window.open("http://www.gopiplus.com/work/2019/12/25/popup-dropping-box-wordpress-plugin/");
}

function _popupdbox_numericandtext(inputtxt) {  
	var numbers = /^[0-9a-zA-Z]+$/;  
	document.getElementById('pop_group').value = "";
	if(inputtxt.value.match(numbers)) {  
		return true;  
	}  
	else {  
		alert(popupdbox_adminscripts.pop_numletters); 
		newinputtxt = inputtxt.value.substring(0, inputtxt.value.length - 1);
		document.getElementById('pop_group_txt').value = newinputtxt;
		return false;  
	}  
}