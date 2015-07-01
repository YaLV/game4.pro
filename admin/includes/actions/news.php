<?
if($_GET['x']=='news/action/save') {
  $time=time();
  if($_POST['news_id']) {
    $result['id']=$_POST['news_id'];
    $sql->query("update news set data_en='{$_POST['news_en']}',data_ru='{$_POST['news_ru']}',video='{$_POST['youtube-video-1']}' where id='{$_POST['news_id']}'");
  } else {
    $result['id']=$time;
    $sql->query("insert into news values('$time','{$_POST['news_en']}','{$_POST['news_ru']}','{$_POST['youtube-video-1']}','n')");
  }
  $result['added']=true;    
  echo json_encode($result);  
  exit();
}

if($_GET['x']=='news/action/state') {
  $s['n']='y';
  $s['y']='n';
  list($a,$id)=explode("_",$_POST['id']);
  $curstate=get_reply("select active from news where id='$id'");
  $newstate=$s[$curstate];
  $sql->query("update news set active='$newstate' where id='$id'");
  $return=Array('state' => $newstate, 'success' => true, 'id' => $id);
  echo json_encode($return);
  exit();  
}