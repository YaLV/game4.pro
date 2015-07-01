<?
if($me) {
  $current_password=get_reply("select password from users where id='$me'");
  if(crypt($_POST['curpwd'],$current_password)==$current_password) {
    if($_POST['newpwd']=='') {
      $return['infoText']=$message->text('no_password');
      $return['action']=false;
    } else {
      if($_POST['newpwd']==$_POST['confpwd']) {
        $pwd=crypt($_POST['newpwd']);
        $sql->query("update users set password='$pwd' where id='$me'");
        $return['infoText']=$message->text('pass_changed');
        $return['action']="parent.top.document.location.href='/site/';";
      } else {
        $return['infoText']=$message->text('pass_dont_match');
        $return['action']=false;
      }
    }
  } else {
    $return['infoText']=$message->text('wrong_pass');
    $return['action']=false;
  }
} else {
  $return['infotText']=$message->text('not_logged_in');
  $return['action']="parent.top.document.location.href='/';";
}
echo json_encode($return);
exit();
?>