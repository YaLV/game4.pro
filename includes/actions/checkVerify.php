<?
$response=get_reply("select confirmed from users where id='{$_POST['u']}' and confirmation_key='{$_POST['c']}'");
if($response=='y') {
  $data['response']="<br /><br /><br />".$message->text('verify_ok');
  $data['action']="document.location.href='/';";
} elseif(!$response) {
  $data['response']="<br /><br /><br />".$message->text('no_user');
  $data['action']="document.location.href='/';";
} elseif($response=='n') {
  $data['response']="<br /><br /><br />".$message->text('wrong_confirmation_code');
  $data['action']="document.location.href='/';";
}

echo json_encode($data);