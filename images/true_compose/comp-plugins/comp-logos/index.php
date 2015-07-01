<?php

/*
Plugin Name: Logos
Plugin URI: http://www.compose.lv
Description: Plugin by Mark Timofejev
Version: 1
Author: Mark Timofejev
Author URI: http://www.retina.lv
*/

add_action( 'admin_menu', 'comp_logos_menu' );

function comp_logos_menu() {
	add_menu_page( 'Logos', 'Logos', 'manage_options', 'logos', 'comp_logos_options' );
}

function comp_logos_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$db = mysql_connect('mysql.compose.lv','divonis','compose.lv') or die("Database error"); 
	mysql_select_db('compose', $db);
	$getDb = mysql_query("SELECT * from `comp_data` where type='1'");
	echo '<div class="wrap">';
	echo '<p>
	Тут можно добавить логотипы на главную:<br />
	<form action="comp_upload.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="type" value="1">
		<table>
		<tr><td>
			Image:
		</td><td>
			<input type="file" name="comp_data">
		</td></tr>
		<tr><td>
			Name:
		</td><td>
			<input type="text" name="data1">
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
			echo '<tr><td>'. $showDb['id'] .'. '. $showDb['data1'] .'</td><td><a href="comp_upload.php?action=delete&type=1&id='. $showDb['id'] .'" style="color:red;">удалить</a> </td></tr>';
		}
	echo '
		</table>
	</form>
	</p>';
	echo '</div>';
}
?>