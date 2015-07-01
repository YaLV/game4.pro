<?
if($_GET['x']=='settings/users') {
  if(isset($_GET['add'])) {
    $action='add';
    include getcwd()."/includes/actions/settings/users.php";
  }
  if(isset($_GET['remove'])) {
    $action='remove';
    include getcwd()."/includes/actions/settings/users.php";
  }
  include getcwd()."/includes/output/settings/users.php";
}

if($_GET['x']=='settings/translation') {
  if(isset($_GET['save'])) {
    if(is_array($_POST)) {
      foreach($_POST as $k => $v) {
        if($v['en']!='') {
          $sql->query("update translations set message_en='".addslashes(htmlspecialchars($v['en'],ENT_QUOTES))."' where identifier='$k'");
        }
      }
    }
    echo json_encode(Array("success" => true, "result" => "Values have been saved"));
    exit();
  }
  include getcwd()."/includes/output/settings/texts.php";
}

if($_GET['x']=='settings/email_translation') {
  if(isset($_GET['save'])) {
    if(is_array($_POST)) {
      foreach($_POST as $k => $v) {
        if($v['en']!='') {
          $sql->query("update translation_emails set message_en='".htmlspecialchars($v['en'])."' where identify='$k'");
        }
      }
    }
    echo json_encode(Array("success" => true, "result" => "Values have been saved"));
    exit();
  }
  include getcwd()."/includes/output/settings/emails.php";
}

if($_GET['x']=='settings/vars') {
  if(isset($_GET['save'])) {
    foreach($_POST as $key => $v) {
      $data=json_encode($v);
      $sql->query("update settings set `value`='$data' where `key`='$key'");
    }
    echo json_encode(Array("success" => true, "result" => "New variables have been saved."));
    exit();
  }
  include getcwd()."/includes/output/settings/variables.php";  
}

if($_GET['x']=='settings/messages_sent') {
  include getcwd()."/includes/output/settings/messages.php";  
}

?>