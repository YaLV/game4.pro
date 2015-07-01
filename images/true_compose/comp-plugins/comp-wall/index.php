<?php

/*
Plugin Name: Slider
Plugin URI: http://www.compose.lv
Description: Plugin by Mark Timofejev
Version: 1
Author: Mark Timofejev
Author URI: http://www.retina.lv
*/

add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_menu_page( 'Slider', 'Slider', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
}

function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';

	echo '<p>
	
	
	<form action="uploadpdf.php" method="post" enctype="multipart/form-data">
		<table>
	<tr><td>
	Brokastis
</td><td>
	<input type="file" name="pdf_brokastis">
</td></tr>
	<tr><td>
	Pusdienas
</td><td>
	<input type="file" name="pdf_pusdienas">
</td></tr>
	<tr><td>
	A la carte (LV)
</td><td>
	<input type="file" name="pdf_alacarte_lv">
</td></tr>
<tr><td>
	A la carte (RU)
</td><td>
	<input type="file" name="pdf_alacarte_ru">
</td></tr>
<tr><td>
	A la carte (EN)
</td><td>
	<input type="file" name="pdf_alacarte_en">
</td></tr>
	<tr><td>
	Special
</td><td>
	<input type="file" name="pdf_special">
</td></tr>
	<tr><td>
	</td><td>
	&nbsp;
</td></tr>
	<tr><td>
	Baltvini
</td><td>
	<input type="file" name="pdf_baltvini">
</td></tr>
	<tr><td>
	Sakranvini
</td><td>
	<input type="file" name="pdf_sarkanvini">
</td></tr>
	<tr><td>
	Šampanietis
</td><td>
	<input type="file" name="pdf_sampane">
</td></tr>
<tr><td>
	&nbsp;
</td><td>
	
</td></tr>
<tr><td>
	Vinu karte
</td><td>
	<input type="file" name="pdf_vinemap">
</td></tr>
<tr><td>
	&nbsp;
</td><td>
	
</td></tr>
<tr><td>
	
</td><td>
	<input type="submit" style="float:right;">
</td></tr></table></form>
	</p>';
	echo '</div>';
}
?>