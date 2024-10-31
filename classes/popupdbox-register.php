<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class popupdbox_cls_registerhook {
	public static function popupdbox_activation() {
	
		global $wpdb;

		add_option('popup-dropping-box', "1.0");

		$charset_collate = '';
		$charset_collate = $wpdb->get_charset_collate();
	
		$popupdbox_default_tables = "CREATE TABLE {$wpdb->prefix}popupdbox (
										pop_id INT unsigned NOT NULL AUTO_INCREMENT,
										pop_title VARCHAR(1024) NOT NULL default '',
										pop_text TEXT,
										pop_group VARCHAR(20) NOT NULL default 'General',
										pop_width VARCHAR(6) NOT NULL default '300',
										pop_bgcolor VARCHAR(10) NOT NULL default '',
										pop_boxtype VARCHAR(10) NOT NULL default 'round',
										pop_animation VARCHAR(50) NOT NULL default '',
										pop_positionx VARCHAR(10) NOT NULL default '-20',
										pop_positiony VARCHAR(10) NOT NULL default '-50',
										pop_freq VARCHAR(10) NOT NULL default 'always',
										pop_deferred int(11) NOT NULL default '2',
										pop_showduration int(11) NOT NULL default '10',
										pop_status VARCHAR(3) NOT NULL default 'Yes',
										pop_start date NOT NULL DEFAULT '0000-00-00', 
										pop_end date NOT NULL DEFAULT '9999-12-31',
										PRIMARY KEY (pop_id)
									) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $popupdbox_default_tables );
		
		$popupdbox_default_tablesname = array( 'popupdbox' );
	
		$popupdbox_has_errors = false;
		$popupdbox_missing_tables = array();
		foreach($popupdbox_default_tablesname as $table_name) {
			if(strtoupper($wpdb->get_var("SHOW TABLES like  '". $wpdb->prefix.$table_name . "'")) != strtoupper($wpdb->prefix.$table_name)) {
				$popupdbox_missing_tables[] = $wpdb->prefix.$table_name;
			}
		}
		
		if($popupdbox_missing_tables) {
			$errors[] = __( 'These tables could not be created on installation ' . implode(', ',$popupdbox_missing_tables), 'popup-dropping-box' );
			$popupdbox_has_errors = true;
		}
		
		if($popupdbox_has_errors) {
			wp_die( __( $errors[0] , 'popup-dropping-box' ) );
			return false;
		} 
		else {
			popupdbox_cls_dbquery::popupdbox_default();
		}
		
		return true;
	}

	public static function popupdbox_deactivation() {
		// do not generate any output here
	}

	public static function popupdbox_adminoptions() {
	
		global $wpdb;
		$current_page = isset($_GET['ac']) ? sanitize_text_field($_GET['ac']) : '';
		
		switch($current_page) {
			case 'edit':
				require_once(POPUPDBOX_DIR . 'pages' . DIRECTORY_SEPARATOR . 'image-management-edit.php');
				break;
			case 'add':
				require_once(POPUPDBOX_DIR . 'pages' . DIRECTORY_SEPARATOR . 'image-management-add.php');
				break;
			default:
				require_once(POPUPDBOX_DIR . 'pages' . DIRECTORY_SEPARATOR . 'image-management-show.php');
				break;
		}
	}
	
	public static function popupdbox_frontscripts() {
		if (!is_admin()) {
			wp_enqueue_script( 'jquery');
			wp_enqueue_script( 'jquery.easing.1.3', plugin_dir_url( __DIR__ ) . 'inc/jquery.easing.1.3.js');
			wp_enqueue_script( 'popup-dropping-box', plugin_dir_url( __DIR__ ) . 'inc/popup-dropping-box.js');
		}	
	}

	public static function popupdbox_addtomenu() {
	
		if (is_admin()) {
			add_options_page( __('Popup dropping box', 'popup-dropping-box'), 
								__('Popup dropping box', 'popup-dropping-box'), 'manage_options', 
									'popup-dropping-box', array( 'popupdbox_cls_registerhook', 'popupdbox_adminoptions' ) );
		}
	}
	
	public static function popupdbox_adminscripts() {
	
		if(!empty($_GET['page'])) {
			switch (sanitize_text_field($_GET['page'])) {
				case 'popup-dropping-box':
					wp_register_script( 'popupdbox-adminscripts', plugin_dir_url( __DIR__ ) . '/pages/setting.js', '', '', true );
					wp_enqueue_script( 'popupdbox-adminscripts' );
					$popupdbox_select_params = array(
						'pop_title'  		=> __( 'Please enter popup title.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_text'  		=> __( 'Please enter popup text.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_group'  		=> __( 'Please select group for this popup.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_numletters'  	=> __( 'Please input numeric and letters only.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_width'  		=> __( 'Please enter width of the popup window, only number.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_bgcolor'  		=> __( 'Please select background color of the popup window.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_boxtype'  		=> __( 'Please select popup box type.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_animation'  	=> __( 'Please select popup dropping animation type.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_positionx'  	=> __( 'Please enter popup position x value.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_positiony'  	=> __( 'Please enter popup position y value.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_deferred'  	=> __( 'Please enter popup window delay value in seconds, only number.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_showduration'  => __( 'Please enter popup window disappear seconds, only number.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_freq'  		=> __( 'Please select popup window frequency.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_status'  		=> __( 'Please select display status of this popup.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_start'  		=> __( 'Please enter start date of this popup window, format YYYY-MM-DD.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_end'  			=> __( 'Please enter end date of this popup window, format YYYY-MM-DD.', 'popupdbox-select', 'popup-dropping-box' ),
						'pop_delete'  		=> __( 'Do you want to delete this record?', 'popupdbox-select', 'popup-dropping-box' ),
					);
					wp_localize_script( 'popupdbox-adminscripts', 'popupdbox_adminscripts', $popupdbox_select_params );
					break;
			}
		}
	}
}

class popupdbox_cls_shortcode {
	public function __construct() {
	}
	
	public static function popupdbox_shortcode( $atts ) {
		ob_start();
		if (!is_array($atts)) {
			return '';
		}
		
		//[popupdbox group="General"]
		//[popupdbox id="2"]
		$atts = shortcode_atts( array(
				'group'	=> '',
				'id'	=> ''
			), $atts, 'popup-dropping-box' );

		$group 	= isset($atts['group']) ? sanitize_text_field($atts['group']) : '';
		$id 	= isset($atts['id']) ? intval($atts['id']) : '';

		$data = array(
			'group' => $group,
			'id' 	=> $id
		);
		
		self::popupdbox_render( $data );

		return ob_get_clean();
	}
	
	public static function popupdbox_render( $data = array() ) {	
		
		$popupd = "";
		
		if(count($data) == 0) {
			return $popupd;
		}

		$id		= intval($data['id']);
		$group 	= sanitize_text_field($data['group']);

		$data = popupdbox_cls_dbquery::popupdbox_select_shortcode($id, $group);
		
		if(count($data) > 0 ) {
			$url = plugin_dir_url( __DIR__ ) . 'inc/closebox.gif';
			$rand = rand();
			$pop_positionx = "'center'";
			$pop_positiony = "'center'";
			$pop_deferred = 2;
			$pop_showduration = 10;
			
			if($data['pop_positionx'] <> 'center') {
				$pop_positionx = $data['pop_positionx'];
			}
			
			if($data['pop_positiony'] <> 'center') {
				$pop_positiony = $data['pop_positiony'];
			}
			
			if(is_numeric($data['pop_deferred'])) { 
				$pop_deferred = $data['pop_deferred'];
			}
			
			if(is_numeric($data['pop_showduration'])) { 
				$pop_showduration = $data['pop_showduration'];
			}
			
			$popupd = "<script>";
			$popupd .= "var popupdbox=new popupdroppingbox({";
				$popupd .= "source:'#popupdbox" . $rand . "',";
				$popupd .= "cssclass:'popupdbox popupdboxstyle',";
				$popupd .= "fx:'swing',";
				$popupd .= "pos:[" . $pop_positionx . ", " . $pop_positiony . "],";
				$popupd .= "deferred:" . $pop_deferred . ",";
				$popupd .= "showduration:" . $pop_showduration . ",";
				$popupd .= "freq: '" . $data['pop_freq'] . "',";
				$popupd .= "closeimage:'" . $url . "'";
			$popupd .= "})";
			$popupd .= "</script>";
			
			if($data['pop_boxtype'] == "square") {
				$css = "border:1px solid #CCCCCC;padding:5px;background:". $data['pop_bgcolor'] .";";
			}
			else {
				$css = "background:". $data['pop_bgcolor'] .";box-shadow: 0 0 10px gray inset;-webkit-border-radius:8px;-moz-border-radius:8px;border-radius:8px;";
			}
			
			$popupd .= '<div id="popupdbox' . $rand . '" style="max-width:90%;width:' . $data['pop_width'] . 'px;padding:10px;z-index: 1000;' . $css . '">';
			$popupd .= stripslashes($data['pop_text']);
			$popupd .= '</div>';
		}
		echo $popupd;
	}
}
?>