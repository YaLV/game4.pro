<?php

/*
Plugin Name: Slider
Plugin URI: http://www.compose.lv
Description: Plugin by Mark Timofejev
Version: 1
Author: Mark Timofejev
Author URI: http://www.retina.lv
*/

add_action( 'admin_menu', 'comp_slider_menu' );

function comp_slider_menu() {
	add_menu_page( 'Slider', 'Slider', 'manage_options', 'slider', 'comp_slider_options' );
}

function comp_slider_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$db = mysql_connect('mysql.compose.lv','divonis','compose.lv') or die("Database error"); 
	mysql_select_db('compose', $db);
	$getDb = mysql_query("SELECT * from `comp_slider`");
	echo '<div class="wrap">';
	echo '<p>
	<form action="comp_upload.php" method="post" enctype="multipart/form-data">
		<table>
		<tr><td>
			Image:
		</td><td>
			<input type="file" name="comp_slider">
		</td></tr>
			<tr><td>
			Lang:
		</td><td>
			<select name="lang">
				<option value="ru">ru</option>
				<option value="en">en</option>
				<option value="lv">lv</option>
			</select>
		</td></tr>
			<tr><td>
			Link:
		</td><td>
			<input type="text" name="link">
		</td></tr><tr><td></td><td>
			<input type="submit" style="float:right;">
		</td></tr>
		</table>
		<br /><br />
		<table>
	';
	while ($showDb = mysql_fetch_assoc($getDb)) {
			echo '<tr><td>'. $showDb['id'] .'. '. $showDb['image_name'] .'</td><td><a href="comp_upload.php?action=delete_slide&id='. $showDb['id'] .'" style="color:red;">удалить</a> </td></tr>';
		}
	echo '
		</table>
	</form>
	</p>';
	echo '</div>';
}
?>