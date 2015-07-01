<?

$register_fields= Array(
  "name" => '/^[a-zA-Z0-9\x{0430}-\x{044F}\x{0410}-\x{042F}\s]+$/u'
);

foreach($register_fields as $k => $v) {
  $result=0;
  preg_match($v,trim($_POST[$k]),$found);
  if($found[0]==trim($_POST[$k]) && $_POST[$k]!='') {
    $result++;    
    $error[$k]['status']='success';
  } else {
    $error[$k]['status']='fail';
  }
}
if($_POST['acceptTandC']) {
  $error['acceptTandC']['status']='success';
} else {
  $error['acceptTandC']['status']='fail';
}

if($_POST['email']!='') {
  if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $dom=explode("@",$_POST['email']);
    if(checkdnsrr($dom[1],'MX')) {
      $error['email']['status']='success';
      $result++;
    } else {
      $error['email']['status']='fail';
    }
  } else {
    $error['email']['status']='fail';
  }
} else {
    $error['email']['status']='fail';
}

if($_POST['rpassword']!='') {
  $result++;
  $error['rpassword']['status']='success';
} else {
  $error['rpassword']['status']='fail';
}

if($_POST['rpassword']==$_POST['rpasswordr']) {
  $result++;
  $error['rpasswordr']['status']='success';
} else {
  $error['rpasswordr']['status']='fail';
}

if($result==4) {
  if(!get_reply("select id from users where email='{$_POST['email']}'")) {
    $confirmation=md5(crypt($_SERVER['REMOTE_ADDR']."-".$_POST['mail']."-".$_POST['name']));
    $user=time();
    $sql->query("insert into users values ('$user','{$_POST['email']}','{$_POST['name']}','".crypt($_POST['rpassword'])."','$confirmation','n','1','0','$language','1')");
    $mailer->vars=array('user' => $user, 'confirmation' => $confirmation, 'email' => $_POST['email'], 'name' => $_POST['name']);
    $mailer->receiver=$_POST['email'];
    $mailer->respond="email_register_sent";
    $mailer->send("registration");
  } else {
    $error['email']['status']='fail';
    echo json_encode(array_merge(Array("success" => 'exists', "email" => Array("status" => "fail"), "message" => "<br /><br /><br />".$message->text("account_exists")), $error));
    exit();
  }
  echo json_encode(Array("success" => $mailer->result, "message" => "<br /><br /><br />".$mailer->message));
} else {
  $error['success']=false;
  $error['message']=$message->text('error_fields');
  echo json_encode($error);
}
  