<?
if(preg_match("/remove/",$_GET['x']) && $_POST['cid']) {
  if(get_reply("select `owned` from codes where cid='{$_POST['cid']}'")) {
    $sql->query("select invoice,`owned`,gid from codes where cid='{$_POST['cid']}' ");
    $sql->get_row();
    $invoice=$sql->get_col();
    $owner=$sql->get_col();
    $gid=$sql->get_col();
    $sql->query("insert into preorder values('','$invoice','".time()."','$gid','$owner')");
    $return=Array("removed" => true, "message" => "Key Have been removed, This purchase have been added to PREORDER");
  } else {
    $return=Array("removed" => true, "message" => "Key Have been removed");
  }
  $sql->query("delete from codes where cid='{$_POST['cid']}'");
} else {
  if($_POST['codes'] && !empty($_POST['codes'])) {
    $codes=spliti("\n",$_POST['codes']);
    if(!is_array($codes)) {
      $codes[]=$_POST['codes'];
    } 
    foreach($codes as $v) {
      $v=trim($v);
      if(!get_reply("select cid from codes where codeImage like '%$v%' and gid='{$_POST['gID']}'")) {
        $codedata=json_encode(Array('type' => 'text', "value" => "$v"));
        $sql->query("insert into codes values('','','$codedata','','','{$_POST['gID']}','0','0')");
        $cid=get_reply("select last_insert_id() from codes");
        $return[]=Array(
          'cid' => $cid,
          'tcode' => $codedata,
          'reserved' => '',
          'owned' => '',
          'owner' => ''   
        );
      } else {
        $return[]=Array(
          'cid' => false,
          'tcode' => "Code Already Exists",
          'reserved' => '1'
        );
      }
    }
  } 
  if(is_array($_FILES) && isset($_GET['gID'])) {
    $v=trim(file_get_contents($_FILES['codeIm']['tmp_name']));
    if(!get_reply("select cid from codes where codeImage like '%$v%' and gid='{$_GET['gID']}'")) {
      $imdata=json_encode(Array('type' => 'image', "name" => $_FILES['codeIm']['name'], "value" => "$v"));
      $sql->query("insert into codes values('','','$imdata','','','{$_GET['gID']}','0','0')");
      $cid=get_reply("select last_insert_id() from codes");
      $return[]=Array(
        'cid' => $cid,
        'tcode' => $imdata,
        'reserved' => '',
        'owned' => '',
        'owner' => ''    
      );
    } else {
      $return[]=Array(
        'cid' => false,
        'tcode' => "Code Already Exists",
        'reserved' => '1'
      );
    }
  }
}
echo json_encode($return);
exit();


/*

    $preorders=get_reply("select count(*) from preorder where gid='{$_GET['gID']}'");
    if($preorders>0) {
      $sql->query("select id,owner,invoice from preorder where gid='{$_GET['gID']}' order by time ASC limit 1");
      $sql->get_row();
      $pid=$sql->get_col();
      $owner=$sql->get_col();
      $invoice=$sql->get_col();
      $points=get_reply("select points from games where id='{$_GET['gID']}'");
      $sql->query("update codes set owned='$owner',invoice='$invoice' where cid='$cid'");
      $sql->query("delete from preorder where id='$pid'");
    }
*/          