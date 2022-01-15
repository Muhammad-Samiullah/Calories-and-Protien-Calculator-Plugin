<?php
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { die(); };
		 global $wpdb;
		 $wp_track_table = $wpdb->prefix . 'Ingredients';
		 $wpdb->query( "DROP TABLE IF EXISTS {$wp_track_table}" );
?>