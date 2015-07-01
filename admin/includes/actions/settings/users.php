<?
$fields=Array(
  'username' => '/[a-zA-Z0-9]+/',
  'phone' => '/[\+0-9 ]+/'  
);
$fieldTypes=Array("username" => "can contain only letters and numbers<br />","phone" => "can contain only numbers, spaces and '+'<br />");
$errorCount=0;
$error="";
if($action=='add') {                   
  foreach($fields as $field => $regexp) {
    if(!preg_match($regexp,$_POST[$field])) {
      $error.=" - ".ucfirst($field)." ".$fieldTypes[$field];
      $errorCount++;    
    }    
  }
  if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $dom=explode("@",$_POST['email']);
    if(!checkdnsrr($dom[1],'MX')) {
      $error.=" - Email domain does not have MX entry.<br />";
      $errorCount++;
    } 
  } else {
    $error.=" - Email Domain does not exist.<br />";
    $errorCount++;    
  }
  if($_POST['password']=='') {
    if(!$_POST['id']) {
      $error.=" - Passowrd can not be empty<br />";
      $errorCount++;
    }
  }
  if($errorCount==0) {
    $pwd=crypt($_POST['password']);
    if(!$_POST['id']) {
      $sql->query("insert into admin_users values('','{$_POST['username']}','{$_POST['email']}','{$_POST['phone']}','$pwd')");
      $id=get_reply("select last_insert_id() from admin_users");
      $result['success']=true;
      $result['changed']=false;
      $result['userdata']=Array('username' => $_POST['username'], 'email' => $_POST['email'], 'phone' => $_POST['phone'], 'id' => $id);
    } else {
      $pw=$_POST['password']=='' ? "" : ",password='$pwd'";
      $sql->query("update admin_users set username='{$_POST['username']}', email='{$_POST['email']}', phone='{$_POST['phone']}' $pw where id='{$_POST['id']}'");    
      $result['success']=true;                                                             
      $result['changed']=true;
      $result['userdata']=Array('username' => $_POST['username'], 'email' => $_POST['email'], 'phone' => $_POST['phone'], 'id' => $_POST['id']);
    }
  } else {
    $result['success']=false;
    $result['error']="<div style='background:#DDDDDD;padding:10px;color:red;' class='round-corners'>$error</div>";
  }
} 

if($action=='remove') {
  $sql->query("delete from admin_users where id='{$_POST['id']}'");
  $result['success']=true;
  $result['id']=$_POST['id'];  
} 
echo json_encode($result);
exit();
?>