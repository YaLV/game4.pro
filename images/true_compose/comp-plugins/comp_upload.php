<?php
if ($_FILES["file"]["size"] < 2000000 && !empty($_FILES))
  {
	  
	if (!empty($_FILES["comp_data"]) && $_POST['type'] == '1') { //logos
		$FileName = 'logo_' . mt_rand(1000,9999) . '_';
		move_uploaded_file($_FILES['comp_data']["tmp_name"],
		"../uploads/". $FileName . $_FILES["comp_data"]["name"]);
		$db = mysql_connect('mysql.compose.lv','divonis','compose.lv') or die("Database error"); 
		mysql_select_db('compose', $db);
		mysql_query("INSERT into comp_data (img,type,link,data1) VALUES ('" . $FileName . "" . $_FILES["comp_data"]["name"] . "','" . $_POST["type"] . "','" . $_POST["link"] . "','" . $_POST["data1"] . "')");
		echo 'Success! Redirecting..';
		echo '<script>window.location = "admin.php?page=logos"</script>';
	  } else {
	   echo 'Error 21! Please contact tdmarko@gmail.com for fix!';
	  }
	  
  } else {
	 if(empty($_GET['action'])) {
		echo 'File to big, please <a href="javascript:self.history.back ();">go back!</a> Or contact tdmarko@gmail.com for fix!';
	}
  }
  
 if(!empty($_GET['action'])) {
	if($_GET['action'] == 'delete') {
		$db = mysql_connect('mysql.compose.lv','divonis','compose.lv') or die("Database error"); 
		mysql_select_db('compose', $db);
		if($_GET['type'] == '1') { //logos
			mysql_query("DELETE FROM comp_data WHERE id = '". $_GET['id'] ."'");
			echo 'Success! Redirecting..';
			echo '<script>window.location = "admin.php?page=logos"</script>';
		}

	} else {
	   echo 'Error 22! Please contact tdmarko@gmail.com for fix!';
	  }
 }	

?>