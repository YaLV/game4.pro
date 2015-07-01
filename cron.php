<?
//ob_start();
include getcwd()."/includes/config.php";
include getcwd()."/includes/mm.php";

function send_mail($job) {
  global $sqlz,$sql,$mailer;
  $type=get_reply("select template from mail_send where id='$job'");
  switch($type) {    
    case "new_game":
      if($job) {
        for($x=0;$x<20;$x++) {
          $line = get_reply("select id from emails_to_send where mID='$job' and sent!='1' order by id asc limit 1");
          if(!$line) {
            $sql->query("update mail_send set job_done='".time()."' where id='$job'");
            $sql->query("delete from emails_to_send where mID='$job'");
            break;
          } 
          $varz=json_decode(get_reply("select vars from emails_to_send where id='$line'"),true);
          $varz['language']='en';
          $varz['game']=get_reply("select name_en from mail_send,games where mail_send.id='$job' and mail_send.gid=games.id");
          $mailer->sender="no-reply@game4.pro";
          $mailer->receiver=get_reply("select email from users where id='{$varz['uid']}'");
          $mailer->vars=$varz;
          //$mailer->test=1;
          $mailer->send($job);
          if($mailer->result) {
            $sql->query("update emails_to_send set sent='1' where id='$line'");
          }
        }          
      }
    break;
    
    case "plain":
      if($job) {
        for($x=0;$x<20;$x++) {
          $line = get_reply("select id from emails_to_send where mID='$job' and sent!='1' order by id asc limit 1");
          if(!$line) {
            $sql->query("update mail_send set job_done='".time()."' where id='$job'");
            $sql->query("delete from emails_to_send where mID='$job'");
            break;
          } 
          $varz=json_decode(get_reply("select vars from emails_to_send where id='$line'"),true);
          //$varz['language']='en';
          //$varz['game']=get_reply("select name_en from mail_send,games where mail_send.id='$job' and mail_send.gid=games.id");
          $varz['name']=get_reply("select name from users where id='{$varz['uid']}'");
          $mailer->sender="info@game4.pro";
          $mailer->ContentType="text/html";
          $mailer->receiver=get_reply("select email from users where id='{$varz['uid']}'");
          $mailer->vars=$varz;
          //$mailer->test=1;
          $mailer->send($job);
          if($mailer->result) {
            $sql->query("update emails_to_send set sent='1' where id='$line'");
          }
        }          
      }
    break;
  }
}

function check_jobs($type) {
  global $sql,$sqlz;
  $running_jobs=get_reply("select count(*) from mail_send where job_started>'0' and job_done='0'");
  $new_jobs=get_reply("select count(*) from mail_send where job_started='0' and job_done='0'");
  $sql->query("select id,template,gid from mail_send where job_started='0' and job_done='0'");
  while($sql->get_row()) {
    $new_jobs_to_add[]=array('id' => $sql->get_col(), "type" => $sql->get_col(), 'gid' => $sql->get_col());
  }
  if($new_jobs) {
    foreach($new_jobs_to_add as $v) {
      if($v['type']=='new_game') {
        $sqlz->query("select id,language from users where receive='1'");
        while($sqlz->get_row()) {
          $vars['uid']=$sqlz->get_col();
          $vars['language']='en';
          $dt=json_decode(get_reply("select modifiers from mail_send where id='{$v['id']}'"),true);
          $vars['link']=$dt['link_'.$vars['language']]['data'];
          $vv=json_encode($vars);
          $sql->query("insert into emails_to_send values('','{$vars['uid']}','{$v['id']}','$vv','0')");        
        }
        $sql->query("update mail_send set job_started='".time()."' where id='{$v['id']}'"); 
      } elseif($v['type']=='plain') {
        $sqlz->query("select id,language from users where receive='1'");
        while($sqlz->get_row()) {
          $vars['uid']=$sqlz->get_col();
          $vars['language']='en';
          //$dt=json_decode(get_reply("select modifiers from mail_send where id='{$v['id']}'"),true);
          //$vars['link']=$dt['link_'.$vars['language']]['data'];
          $vv=json_encode($vars);
          $sql->query("insert into emails_to_send values('','{$vars['uid']}','{$v['id']}','$vv','0')");        
        }
        $sql->query("update mail_send set job_started='".time()."' where id='{$v['id']}'"); 
      }
    }
  }
  $job=get_reply("select id from mail_send where job_started>'1' and job_done='0' order by id asc limit 1");
  return $job;
}


$is_job=check_jobs();
send_mail($is_job);

//$output=ob_get_clean();

//$f=fopen("/home/game4pro/public_html/log","w+");
//fwrite($f,$output);
//fclose($f);
?>