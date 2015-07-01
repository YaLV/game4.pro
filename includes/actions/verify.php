<?
$user_exists=get_reply("select id from users where id='{$verify[1]}'");
$confirmation_key=get_reply("select id from users where confirmation_key='{$verify[2]}' and id='{$verify[1]}'");
if($user_exists && $confirmation_key==$user_exists) {
  $sql->query("update users set confirmed='y' where id='$user_exists' and confirmation_key='{$verify[2]}'");
}
$baseUpd='onload="showVerify();"';

