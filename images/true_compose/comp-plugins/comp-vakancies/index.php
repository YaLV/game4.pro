<?php

/*
Plugin Name: Vakancies
Plugin URI: http://www.compose.lv
Description: Plugin by Mark Timofejev
Version: 1
Author: Mark Timofejev
Author URI: http://www.retina.lv
*/

add_action( 'admin_menu', 'my_vakancies_menu' );

function my_vakancies_menu() {
	add_menu_page( 'Vakancies', 'Vakancies', 'manage_options', 'vakancies', 'my_vakancies_options' );
}

function my_vakancies_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';

	echo '<p>
	To do!
	</p>';
	echo '</div>';
}
?>