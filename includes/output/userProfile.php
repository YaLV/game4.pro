<?
$pst=array(
  "-1" => "Payment not done",
  "0" => "Payment window open",
  "1" => "Invoice paid: [date]",
  "2" => "Payment is being processed",
  "3" => "Payment rejected",
  "4" => "Payment rejected (expired)",
  "5" => "Payment refunded"  
);

if(isset($_GET['changeState'])) {
  $sql->query("update users set receive='{$_POST['state']}' where id='$me'");
  echo json_encode(array("success" => true, "message" => $message->text("news_state_".$_POST['state'])));
  exit();
}
if(isset($_GET['showFrame'])) {
  $_SESSION['unlocked']=0; 
  include getcwd()."/includes/output/userProfile/profile.php";
} elseif(isset($_GET['loadGames'])) {
  $iid=$_POST['invoice'];
  $iid=substr($iid,1,strlen($iid));
  $st=get_reply("select `status` from invoices where invoice='$iid' and uid='$me'");
  if($st) {
    $pstatus=get_reply("select `status` from airpay where invoice_nr='$iid'");
    $pstatuss=preg_replace("/\[date\]/",date("d-m-y H:i",intval(get_reply("select if(statusTime!='', statusTime, 0) as statusTime from airpay where invoice_nr='$iid'"))),$pst[$pstatus]);
  }
  if($st!='n' && $st!='y') {
    //preorder
    $sql->query("select invoices.status,invoices.invoice,invoices.price,invoices.currency,games.id,games.name_en,games.cover from preorder,games,invoices where preorder.invoice='$iid' and preorder.gid=games.id and invoices.uid='$me' and invoices.invoice='$iid'");
    while($sql->get_row()) {
      $status=$sql->get_col();
      $status=($status=='yc' ? $status."n" : $status);
      $stat=$message->text('invoice_status_'.$status);
      $games=Array( 'number' => $sql->get_col(), 'price' => $sql->get_col(), 'currency' => ucfirst(strtolower($sql->get_col())), 'id' => $sql->get_col(), 'name' => $sql->get_col(), 'cover' => $sql->get_col());
      $returns.="
        <tr>
          <td style='width:30px;'><img src='admin/gameImages/{$games['id']}/{$games['cover']}' style='width:30px;' /></td>
          <td>{$games['name']}</td>
          <td style='width:140px;font-variant:small-caps;' nowrap>$stat</td>
        </tr>";
    }
    //games
    $sql->query("select invoices.status,codes.codeText,codes.codeImage,invoices.invoice,invoices.price,invoices.currency,games.id,games.name_en,games.cover from codes,games,invoices where codes.invoice='$iid' and codes.gid=games.id and invoices.uid='$me' and invoices.invoice='$iid'");
    while($sql->get_row()) {
      $status=$sql->get_col();
      $ct=$sql->get_col();
      $ci=$sql->get_col();
      $ci=json_decode($ci,true);
      if($_SESSION['unlocked']==1) {
        $key=($ci['type']=='text' ? $ci['value'] : "<a href='#' class='keyz'><img src='{$ci['value']}' style='height:40px;'/></a>");      
      }
      
      $stat=($_SESSION['unlocked']==1 ? $key : $message->text('invoice_status_'.$status));
      $games=Array( 'number' => $sql->get_col(), 'price' => $sql->get_col(), 'currency' => ucfirst(strtolower($sql->get_col())), 'id' => $sql->get_col(), 'name' => $sql->get_col(), 'cover' => $sql->get_col());
      $returns.="
        <tr>
          <td style='width:30px;'><img src='gameImages/{$games['id']}/{$games['cover']}' style='width:30px;' /></td>
          <td>{$games['name']}</td>
          <td style='width:140px;font-variant:small-caps;' nowrap>$stat</td>
        </tr>";
    }
  } else {
    $sql->query("select op.invoice,op.gameid,op.count,op.price,op.currency,op.invoice,games.cover,games.name_en from orders_pending as op,games where invoice='$iid' and op.gameid=games.id and `owner`='$me'");
    while($sql->get_row()) {
      $status=$st;
      $stat=$message->text('invoice_status_'.$status);
      $games=Array( 'number' => $sql->get_col(), 'id' => $sql->get_col(), 'count' => $sql->get_col(), 'price' => $sql->get_col(), 'currency' => ucfirst(strtolower($sql->get_col())), 'invoice' => $sql->get_col(), 'cover' => $sql->get_col(), 'name' => $sql->get_col());
      $tp+=$games['price']*$games['count'];
      while($games['count']>0) {
        $returns.="
          <tr>
            <td style='width:30px;'><img src='gameImages/{$games['id']}/{$games['cover']}' style='width:30px;' /></td>
            <td>{$games['name']}</td>
            <td style='width:140px;font-variant:small-caps;' nowrap>$stat</td>
          </tr>";
        $games['count']--;
      }
    }
  }
  //$inv="&nbsp;<button class='btn btn-success'>Download Invoice</button>";
  $statuz=($status=='n' ? "Current Invoice is not paid: ({$tp} {$games['currency']}) <button class='btn btn-success btn-ok'>Pay Now</button>" : ($status=='y' ? "<div class='pull-left' style='height:100%;margin-top:5px;'>Please Enter PIN code to confirm purchase:&nbsp; <sup><button class='btn btn-mini btn-inverse' onclick=\"document.location.href='/AboutUs';return false;\">?</button></sup>&nbsp;</div><input type='text' name='code' class='codeKey navbar-search' style='margin-top:0px;width:40px;'/>&nbsp;<button class='btn btn-success btn-ok'>Ok</button>" : ($status=='yc' ? "<div class='pull-left' style='height:100%;margin-top:5px;'>$stat enter password to reveal:&nbsp;</div><input type='password' name='password' class='codeKey navbar-search' style='margin-top:0px;width:80px;' /><button class='btn btn-success btn-ok'>Ok</button>" : "")));
  if($_SESSION['unlocked'] && $status=='yc') { $statuz=''; }
  $return['id']=$iid;
  $return['result']="
    <table>
      <tr>
        <td colspan='3' style='font-variant:small-caps;padding-top:5px;vertical-align:top;'><div class='pull-left' style='width:200px;'>$pstatuss</div><form method='post' id='unlockForm'><input type='hidden' name='unlock' value='{$games['number']}' /><div class='pull-right'>$inv</div><div class='pull-right status'>$statuz </div></form></td>
      </tr>
      $returns
    </table>";
  echo json_encode($return);
} elseif(isset($_GET['loadMyProfile'])) {
  $email = get_reply("select email from users where id='$me'");
  $chk = get_reply("select receive from users where id='$me'")==1 ? "checked='checked'" : "";
  echo "
    <form method='post' id='profileForm'>
      <table style='margin:auto;margin-top:10px;'>
        <tr>
          <td>".$message->text('email').":</td>
          <td>$email </td>
        </tr>
        <tr>
          <td colspan='2'><hr style='background:black;' /></td>
        </tr>
        <tr>
          <td style='vertical-align:middle;'>".$message->text('current_pass').":</td>
          <td><input type='password' class='navbar-search' name='curpwd' value='' /></td>
        </tr>
        <tr>
          <td style='vertical-align:middle;'>".$message->text('new_pass').":</td>
          <td><input type='password' class='navbar-search' name='newpwd' value='' /></td>
        </tr>
        <tr>
          <td style='vertical-align:middle;'>".$message->text('new_pass_confirm').":</td>
          <td><input type='password' class='navbar-search' name='confpwd' value='' /></td>
        </tr>
        <tr>
          <td colspan='2'><div class='pull-right'><a class='pwch' style='cursor:pointer;'>".$message->text('change_pass')."</a></div></td>
        </tr>
        <tr>
          <td colspan='2'><hr /></td>
        </tr>
        <tr>
          <td colspan='2' nowrap><input type='checkbox' name='rstme' value='1' id='rstme' $chk style='float:left;margin-right:10px;'/> <label for='rstme'>".$message->text('subscribe')."</label></td>
        </tr>
      </table>
    </form>";
} elseif(isset($_GET['changePW'])) {
  include getcwd()."/includes/actions/userProfile/change_pw.php";
}
exit();