<?

if(isset($_GET['listEm'])) {
  $p1=$_POST['page']*20-20;
  $cnt=ceil(get_reply("select count(*) from mail_send")-1);
  $sqlz->query("select message_en,mail_send.id,job_started,job_done,subject_en,games.name_en,gid from mail_send,games where games.id=mail_send.gid order by mail_send.id DESC limit $p1,20");
  while($sqlz->get_row()) {
    $msg=$sqlz->get_col();
    $msg=base64_decode($msg,true) ? base64_decode($msg) : nl2br($msg);
    $id=$sqlz->get_col();
    $js=$sqlz->get_col();
    $je=$sqlz->get_col();
    $total=get_reply("select count(*) from emails_to_send where mID='$id'");
    $done=get_reply("select count(*) from emails_to_send where mID='$id' and sent='1'");
    $progress=0;
    if($total>0) {
      $progress=round($done/$total,4)*100;
    }
    if($je>0) { $progress=100; }
    $dt=($js>0 || $je>0 ? date("d/m/Y H:i",($je ? $je : $js)) : "Not Started");
    $ret[]=Array("id" => $id, "js" => $js, "je" => $je, "sen" => $sqlz->get_col(), "game" => $sqlz->get_col(), "gid" => $sqlz->get_col(), "progress" => $progress, "date" => $dt, "mailmessage" => $msg);
  }
  echo json_encode(Array('items' => $ret, 'cnt' => $cnt));
  exit();
}

if(isset($_GET['showIt'])) {
  
}

$contents="
<div class='shadow box content msg_snt'>
  <div style='float:center;color:white;' class='pageDiv'></div>
  <table class='content-table'>
  </table>
  <div style='float:center;color:white;' class='pageDiv'></div>
</div>
";