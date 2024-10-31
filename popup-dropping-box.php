<?php
/*
Plugin Name: Popup dropping box
Plugin URI: http://www.gopiplus.com/work/2019/12/25/popup-dropping-box-wordpress-plugin/
Description: This popup plugin drops your text in the popup window. So that the text on the popup window in your page gets the attention to your users. To get started: activate the plugin and then go to your Popup dropping box Settings page.
Author: Gopi Ramasamy
Version: 1.5
Author URI: http://www.gopiplus.com/work/about/
Donate link: http://www.gopiplus.com/work/2019/12/25/popup-dropping-box-wordpress-plugin/
Tags: plugin, popup, text
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: popup-dropping-box
Domain Path: /languages
*/

if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
	die('You are not allowed to call this page directly.');
}

if(!defined('POPUPDBOX_DIR')) 
	define('POPUPDBOX_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);

if ( ! defined( 'POPUPDBOX_ADMIN_URL' ) )
	define( 'POPUPDBOX_ADMIN_URL', admin_url() . 'options-general.php?page=popup-dropping-box' );

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'popupdbox-register.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'popupdbox-query.php');

function popupdbox_textdomain() {
	  load_plugin_textdomain( 'popup-dropping-box', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_shortcode( 'popupdbox', array( 'popupdbox_cls_shortcode', 'popupdbox_shortcode' ) );

add_action('wp_enqueue_scripts', array('popupdbox_cls_registerhook', 'popupdbox_frontscripts'));
add_action('plugins_loaded', 'popupdbox_textdomain');
add_action('admin_enqueue_scripts', array('popupdbox_cls_registerhook', 'popupdbox_adminscripts'));
add_action('admin_menu', array('popupdbox_cls_registerhook', 'popupdbox_addtomenu'));

register_activation_hook(POPUPDBOX_DIR . 'popup-dropping-box.php', array('popupdbox_cls_registerhook', 'popupdbox_activation'));
register_deactivation_hook(POPUPDBOX_DIR . 'popup-dropping-box.php', array('popupdbox_cls_registerhook', 'popupdbox_deactivation'));
?>