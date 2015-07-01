<?
$pwd=get_reply("select password from users where email='{$_POST['login']}'");
$user=get_reply("select id from users where email='{$_POST['login']}'");
$conf=get_reply("select confirmed from users where email='{$_POST['login']}'");

if($pwd) {
  if($pwd==crypt($_POST['password'],$pwd)) {
    if($conf=='y') {
      $result['success']=true;
      $_SESSION['user_in_system']=$user;
      $uid=$user;
      $upw=$pwd;
      $ip=$_SERVER['REMOTE_ADDR'];
      $_SESSION['user_string']=crypt("$ip-$uid-$upw-$uid");
    } else {
      $result['success']=false;
      $result['error']=$message->text('not_confirmed');
    }
  } else {
    $result['success']=false;
  }  
} else {
    $result['success']=false;
}

if($result['success']==false && !$result['error']) {
    $result['error']=$message->text('incorrect_login_info');
}

echo json_encode($result);