<?
if(isset($_GET['list'])) {
$contents="
<div class='shadow box content'>
  <div style='float:center;color:white;' class='pageDiv'></div>
  <table class='content-table'>
  </table>
  <div style='float:center;color:white;' class='pageDiv'></div>
</div>";
}

if(isset($_GET['loadPreorderList'])) {
  $page=$_GET['page'];
  $page=0;
  $sql->query("select gid,preorder.time,(select email from users where preorder.owner=users.id),preorder.owner,preorder.invoice from preorder,invoices where preorder.invoice=invoices.invoice and invoices.status='yc' group by preorder.invoice order by preorder.time ASC limit $page,20");
  while($sql->get_row()) {
    $orders[]=Array('id' => $sql->get_col(), 'time' => $sql->get_col(), 'owner' => $sql->get_col(), 'uid' => $sql->get_col(), 'invoice' => $sql->get_col());
  }
  if(is_array($orders)) {
    foreach($orders as $val) {
      $result[]=Array('id' => $val['id'], 'invoice' => $val['invoice'], 'time' => date("d.m.Y H:i",$val['time']),'uid' => $val['uid'], 'user' => $val['owner'], 'name' => get_reply("select name_en from games where id='{$val['id']}'"), 'count' => get_reply("select count(*) from preorder where owner='{$val['uid']}' and gid='{$val['id']}' and invoice='{$val['invoice']}'"));
    }
  } else {
    $result=Array("success" => false, "msg" => "No Preorders at this time");
  }
  echo json_encode($result);
  exit();
}

if(isset($_GET['MailForm'])) {
  $keylist="";
  $gameid=spliti("_",$_POST['gid']);
  $sql->query("select cid,codeImage from codes where gid='{$gameid[1]}' and `owned`='0' and `reserved`='0' order by `owned` ASC");
  
  while($sql->get_row()) {
    $cid = $sql->get_col();
    $code=json_decode($sql->get_col(),true);  
    if($code['type']=='text') { $code['name']='Textcode: '.$code['value']; } 
    $keylist.="<option value='$cid' data-content='{$code['value']}' data-type='{$code['type']}'>{$code['name']}</option>";
  }
  
  $maxcount=get_reply("select count(*) from preorder where gid='{$gameid[1]}' and invoice='{$_POST['inv']}'");
  
  echo "
    <form method='post' id='sform'>
      <input type='hidden' name='game' value='{$_POST['gid']}' />
      <input type='hidden' name='invoice' value='{$_POST['inv']}' />
      <input type='hidden' id='maxSel' value='$maxcount' />
      <table style='width:580px;'>
        <tr>
          <td>
          </td>
        </tr>
        <tr>
          <td>
            <div class='pull-right'>Subject
              <input type='text' name='subject_en' class='mail'>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <textarea class='mail' name='mail_en' style='width:580px;height:300px;'></textarea>
          </td>
        </tr>
        <tr>
          <td style='text-align:left;'>Keys: <input type='hidden' name='keys' /><select multiple='multiple' id='keys'>$keylist</select><div id='keylist' style='float:right;'></div></td>
        </tr>
        <tr>
          <td>
            <button class='sm btn btn-success pull-right send'>Send</button>
          </td>
        </tr>
      </table>
    </form>
  ";
  exit();
}

if(isset($_GET['dosnd'])) {
  if($_POST['mail_en'] && $_POST['subject_en']) {
    $gme=spliti("_",$_POST['game']); 
    $game=$gme[1];
    $invoice=$_POST['invoice'];
    $mail['receiver']=get_reply("select email from users,preorder where preorder.invoice='$invoice' and users.id=preorder.`owner`");
    $owner=get_reply("select users.id from users,preorder where preorder.invoice='$invoice' and users.id=preorder.`owner`");
    $gamename=get_reply("select name_en from games where id='$game'"); 
    $mail['subject']=$_POST['subject_en'];
    $mail['message']=$_POST['mail_en'];
    $msg=$mail['message'];
    if($_POST['keys']!='') {
  
      $uid = md5(uniqid(time()));
      $keys=spliti(",",$_POST['keys']);
  
      if(is_array($keys)) {
        foreach($keys as $v) {        
          $key[]=json_decode(get_reply("select codeImage from codes where cid='$v'"),true);
          $skey[]=$v;
        }
      } else {
        $key[]=json_decode(get_reply("select codeImage from codes where cid='$v'"),true);
        $skey[]=$v;
      }
      
      foreach($key as $kkk => $val) {
        if($val['type']=='text') {
          $mail['message'].="\n\n GameKey: {$val['value']}";
          $keyz.="<div>GameKey: {$val['value']}</div>";
        } else {
          list($ctype,$attache)=split(";",$val['value'],2);
          $attach=split(",",$attache,2);
          $attach=chunk_split($attach[1]);
          $ctype=split(":",$ctype,2);
          $ctype=$ctype[1];
          $mail['amessage'] .="--$uid\r\n";
          $mail['amessage'] .= "Content-Type: $ctype; name=\"".$val['name']."\"\r\n";
          $mail['amessage'] .= "Content-Transfer-Encoding: base64\r\n";
          $mail['amessage'] .= "Content-Disposition: attachment; filename=\"".$val['name']."\"\r\n\r\n";
          $mail['amessage'] .= $attach."\r\n\r\n";
          $isoneimage=true;
          $keyz.="<div>GameKey: <img src='{$val['value']}' style='max-width:200px;max-height:100px;'/></div>";
        }
      }
      if($isoneimage) {
        $mail['headerss'] = "MIME-Version: 1.0\r\n";
        $mail['headerss'] .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n";
        $mail['headerss'] .= "--$uid\r\n";
        $mail['headerss'] .= "Content-Type: text/plain; charset=utf8\r\n";
        $mail['headerss'] .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $mail['amessage'] .= "--$uid--";
      } else {
        $mail['headerss'] = "MIME-Version: 1.0\r\n";
        $mail['headerss'] .= "Content-Type: text/plain; charset=utf8\r\n";
        $mail['headerss'] .= "Content-Transfer-Encoding: base64\r\n\r\n";
      }      
      $mail['message'] = base64_encode($mail['message'])."\r\n\r\n";
      $mail['message'] .= $mail['amessage'];
    } else {
      $mail['headerss'] = "MIME-Version: 1.0\r\n";
      $mail['headerss'] .= "Content-Type: text/plain; charset=utf8\r\n";
      $mail['headerss'] .= "Content-Transfer-Encoding: base64\r\n\r\n";
      $mail['message'] = base64_encode($mail['message']);
    } 
    $mail['headers'] = "From: Game key for $gamename <no_reply@game4.pro>\r\n";
    $mail['headers'] .= $mail['headerss'];
    $mail['headerss']="";
    $mail['amessage']="";
    $fun="mail";
    if($fun($mail['receiver'],$mail['subject'],$mail['message'],$mail['headers'])) {
      if(is_array($skey)) {
        foreach($skey as $v) {
          $sql->query("update codes set owned='$owner',invoice='$invoice' where cid=$v");
          $sql->query("delete from preorder where invoice='$invoice' and gid='$game' limit 1");
        }
        $em=", Codes have been assigned!";
        $cleft=get_reply("select count(*) from preorder where invoice='$invoice' and gid='$game' and owner='$owner'");
        if($cleft>0) {
          $run="$('#$invoice.$game').children('.count').html('$cleft');";
        } else {
          $run="$('#$invoice.$game').remove();";
        }        
      }
      $msg=base64_encode(nl2br($msg)."<br />$keyz");
      $fl=fopen('logs','w+');
      fwrite($fl,"insert into mail_send values('','{$mail['subject']} $have_key','','$msg','','','$game','".time()."','".time()."','')");
      fclose($fl);
      
      $sql->query("insert into mail_send values('','{$mail['subject']} $have_key','','$msg','','','$game','".time()."','".time()."','')");                     
      echo json_encode(Array("message" => "Mail sent$em", "success" => true, "run" => $run));     
    } else {
      echo json_encode(Array("message" => "Error sending mail", "success" => false)); 
    }
    
  } else {
    echo json_encode(Array("message" => "Please fill all fields", "success" => false)); 
  }
  exit();
}
