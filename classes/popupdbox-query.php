<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class popupdbox_cls_dbquery {

	public static function popupdbox_count($id = 0) {

		global $wpdb;
		$result = '0';
		if(!is_numeric($id)) { 
			return $result;
		}
		
		if($id <> "" && $id > 0) {
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS count FROM " . $wpdb->prefix . "popupdbox WHERE pop_id = %d", array($id));
		} 
		else {
			$sSql = "SELECT COUNT(*) AS count FROM " . $wpdb->prefix . "popupdbox";
		}
		
		$result = $wpdb->get_var($sSql);
		return $result;
	}
	
	public static function popupdbox_select_bygroup($group = "") {

		global $wpdb;
		$arrRes = array();
		$sSql = "SELECT * FROM " . $wpdb->prefix . "popupdbox";

		if($group <> "") {
			$sSql = $sSql . " WHERE pop_group = %s";
			$sSql = $sSql . " Order by rand() LIMIT 0,1";
			$sSql = $wpdb->prepare($sSql, array($group));
		}
		else {
			$sSql = $sSql . " Order by rand() LIMIT 0,1";
		}
		//echo $sSql;
		
		$arrRes = $wpdb->get_row($sSql, ARRAY_A);
		return $arrRes;
	}
	
	public static function popupdbox_select() {

		global $wpdb;
		$arrRes = array();
		$sSql = "SELECT * FROM " . $wpdb->prefix . "popupdbox";
		$sSql = $sSql . " Order by pop_group, pop_id";
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		return $arrRes;
	}
	
	public static function popupdbox_select_byid($id = "") {

		global $wpdb;
		$arrRes = array();
		$sSql = "SELECT * FROM " . $wpdb->prefix . "popupdbox";

		if($id <> "") {
			$sSql = $sSql . " WHERE pop_id = %d LIMIT 0,1";
			$sSql = $wpdb->prepare($sSql, array($id));
			$arrRes = $wpdb->get_row($sSql, ARRAY_A);
		}
		else {
			$sSql = $sSql . " Order by rand() LIMIT 0,1";
			$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		}
		
		return $arrRes;
	}
	
	public static function popupdbox_select_shortcode($id = "", $group = "") {

		global $wpdb;
		$arrRes = array();
		$sSql = "SELECT * FROM " . $wpdb->prefix . "popupdbox WHERE pop_status = 'Yes'";
		$sSql .= " AND ( pop_start <= NOW() or pop_start = '0000-00-00' )";
		$sSql .= " AND ( pop_end >= NOW() or pop_end = '0000-00-00' )";
		
		if($id <> "" && $id <> "0") {
			$sSql .= " AND pop_id = %d LIMIT 0,1";
			$sSql = $wpdb->prepare($sSql, array($id));
		}
		elseif($group <> "") {
			$sSql .= " AND pop_group = %s Order by rand() LIMIT 0,1";
			$sSql = $wpdb->prepare($sSql, array($group));
		}
		else {
			$sSql = $sSql . " Order by rand() LIMIT 0,1";
		}
		
		$arrRes = $wpdb->get_row($sSql, ARRAY_A);
		
		return $arrRes;
	}
	
	public static function popupdbox_group() {

		global $wpdb;
		$arrRes = array();
		$sSql = "SELECT distinct(pop_group) FROM " . $wpdb->prefix . "popupdbox order by pop_group";
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		return $arrRes;
	}

	public static function popupdbox_delete($id = "") {

		global $wpdb;

		if($id <> "") {
			$sSql = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "popupdbox WHERE pop_id = %s LIMIT 1", $id);
			$wpdb->query($sSql);
		}
		
		return true;
	}

	public static function popupdbox_insert($data = array()) {

		global $wpdb;
		
		$sql = $wpdb->prepare("INSERT INTO " . $wpdb->prefix . "popupdbox (
			pop_group, 
			pop_title, 
			pop_text, 
			pop_width, 
			pop_bgcolor, 
			pop_boxtype,
			pop_animation, 
			pop_positionx, 
			pop_positiony,
			pop_freq,
			pop_deferred,
			pop_showduration,
			pop_status,
			pop_start,
			pop_end
			) 
			VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %s, %s, %s)", 
			array(
			$data["pop_group"], 
			$data["pop_title"], 
			$data["pop_text"],
			$data["pop_width"], 
			$data["pop_bgcolor"], 
			$data["pop_boxtype"], 
			$data["pop_animation"], 
			$data["pop_positionx"], 
			$data["pop_positiony"], 
			$data["pop_freq"], 
			$data["pop_deferred"],
			$data["pop_showduration"],
			$data["pop_status"],
			$data["pop_start"],
			$data["pop_end"]
			));
		$wpdb->query($sql);
		return "inserted";
	}
	
	public static function popupdbox_update($data = array()) {

		global $wpdb;
		
		$sSql = $wpdb->prepare("UPDATE " . $wpdb->prefix . "popupdbox SET 
			pop_group = %s, 
			pop_title = %s, 
			pop_text = %s, 
			pop_width = %s, 
			pop_bgcolor = %s, 
			pop_boxtype = %s, 
			pop_animation = %s, 
			pop_positionx = %s, 
			pop_positiony = %s, 
			pop_freq = %s, 
			pop_deferred = %s,
			pop_showduration = %s, 
			pop_status = %s, 
			pop_start = %s, 
			pop_end = %s 
			WHERE pop_id = %d LIMIT 1", 
			array(
			$data["pop_group"], 
			$data["pop_title"], 
			$data["pop_text"],
			$data["pop_width"], 
			$data["pop_bgcolor"], 
			$data["pop_boxtype"], 
			$data["pop_animation"], 
			$data["pop_positionx"], 
			$data["pop_positiony"], 
			$data["pop_freq"], 
			$data["pop_deferred"], 
			$data["pop_showduration"], 
			$data["pop_status"], 
			$data["pop_start"], 
			$data["pop_end"], 
			$data["pop_id"]
			));
		$wpdb->query($sSql);
		return "update";
	}

	public static function popupdbox_default() {

		$count = popupdbox_cls_dbquery::popupdbox_count($id = 0);
		if($count == 0){
			
			$today = date("Y-m-d");
			$text = 'This is sample popup text for popup dropping box wordpress plugin. ';
			$text .= 'This popup plugin drops your text in the popup window. ';
			$text .= 'So that the text on the popup window in your page gets the attention to your users. ';
			
			$data['pop_group'] = 'General';
			$data['pop_title'] = 'This is sample popup title.';
			$data['pop_text'] = $text;
			$data['pop_width'] = '400';
			$data['pop_bgcolor'] = '#EDEDED';
			$data['pop_boxtype'] = 'round';
			$data['pop_animation'] = '';
			$data['pop_positionx'] = '-20';
			$data['pop_positiony'] = '-50';
			$data['pop_freq'] = 'always';
			$data['pop_deferred'] = '2';
			$data['pop_showduration'] = '45';
			$data['pop_status'] = 'Yes';
			$data['pop_start'] = $today;
			$data['pop_end'] = '9999-12-31';
			
			popupdbox_cls_dbquery::popupdbox_insert($data);
		}
	}
	
	public static function popupdbox_common_text($value) {
		
		$returnstring = "";
		switch ($value) {
			case "Yes":
				$returnstring = '<span style="color:#006600;">Yes</span>';
				break;
			case "No":
				$returnstring = '<span style="color:#FF0000;">No</span>';
				break;
			case "square":
				$returnstring = 'Square Corner';
				break;
			case "round":
				$returnstring = 'Rounded Corner';
				break;
			default:
       			$returnstring = $value;
		}
		return $returnstring;
	}
}