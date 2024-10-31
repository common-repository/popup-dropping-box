<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('popup-dropping-box');
 
// for site options in Multisite
delete_site_option('popup-dropping-box');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}popupdbox");