<?php

/*
Plugin Name: Projects
Plugin URI: http://www.compose.lv
Description: Plugin by Mark Timofejev
Version: 1
Author: Mark Timofejev
Author URI: http://www.retina.lv
*/

add_action( 'admin_menu', 'comp_projects_menu' );

function comp_projects_menu() {
	add_menu_page( 'Projects', 'Projects', 'manage_options', 'projects', 'comp_projects_options' );
}

function comp_projects_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$db = mysql_connect('mysql.compose.lv','divonis','compose.lv') or die("Database error"); 
	mysql_select_db('compose', $db);
	$getDb = mysql_query("SELECT * from `comp_data` where type='1'");
	echo '<div class="wrap">';
	echo '<p>
	Управление завершенными проектами:<br />
	<form action="comp_upload.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="type" value="2">
		<table>
		<tr><td>
			Image small:
		</td><td>
			<input type="file" name="comp_data">
		</td></tr>
		<tr><td>
			Image big:
		</td><td>
			<input type="file" name="comp_data2">
		</td></tr>
		<tr><td>
			Name:
		</td><td>
			<input type="text" name="data1">
		</td></tr>
		<tr><td>
			Type:
		</td><td>
			<select>
				<option>Web</option>
				<option>Advertisment</option>
				<option>Idea</option>
				<option>Promo</option>
				<option>Other</option>
			</select>
		</td></tr>
		<tr><td>
			Desc:
		</td><td>
			<textarea style="width:250px;height:140px;"></textarea>
		</td></tr>
		<tr><td>
			Desc 2:
		</td><td>
			<textarea style="width:250px;height:140px;"></textarea>
		</td></tr>
		<tr><td></td><td>
			<input type="submit" style="float:right;">
		</td></tr>
		</table>
		<br /><br />
		<table>
	';
	/*while ($showDb = mysql_fetch_assoc($getDb)) {
			echo '<tr><td>'. $showDb['id'] .'. '. $showDb['data1'] .'</td><td><a href="comp_upload.php?action=delete&type=1&id='. $showDb['id'] .'" style="color:red;">удалить</a> </td></tr>';
		} */
	echo '
		</table>
	</form>
	</p>';
	echo '</div>';
}
?>