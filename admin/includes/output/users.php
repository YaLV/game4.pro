<?
if(isset($_GET['remove'])) {
  $sql->query("update users set confirmed='r' where id='{$_POST['uid']}'");
  echo json_encode(array("success" => true, uid => $_POST['uid']));
  exit();
}

if(isset($_GET['renew'])) {
  $sql->query("update users set confirmed='y' where id='{$_POST['uid']}'");
  echo json_encode(array("success" => true, uid => $_POST['uid']));
  exit();
}


if(isset($_GET['list']) || isset($_GET['listRem'])) {
  $contents="
  <div class='shadow box filters'>
    <div class='btn-toolbar' style='margin: 0px;margin-top:2px;'>
      <div class='pull-right'><form class='navbar-search' action=''><input type='text' class='search-query span2' placeholder='Search' id='src'/></form></div>
      <div style='float:center;color:white;padding-top:50px;' class='pageDiv'></div>
      <table class='content-table'>
        <tr class='header'>
          <td style='width:40px;'>ID</td>
          <td style='width:200px;'>Name</td>
          <td style='width:300px;'>Email</td>
          <td style='width:100px;'>Bonus Points</td>
          <td style='width:160px;'>Level</td>
        </tr>
      </table>    
      <div style='float:center;color:white;' class='pageDiv'></div>
    </div>
  </div>";
}

if(isset($_GET['loadList'])) {
  if(isset($_GET['removed'])) {
    $where="confirmed='r'";
  } else {
    $where="confirmed!='r'";
  }
  $sql->query("select id,name,email,(select sum(points) from codes where owned=users.id),used_points,level from users where $where order by id DESC");
  while($sql->get_row()) {
    $result[]=Array(
      'id' => $sql->get_col(),
      'name' => $sql->get_col(),
      'email' => $sql->get_col(),
      'points' => round("0".$sql->get_col()-$sql->get_col()),
      'level' => $sql->get_col()
    );
  }
  echo json_encode($result);
  exit();
}

if(isset($_GET['saveLevel'])) {
  $sql->query("update users set `level`='{$_POST['level']}' where id='{$_POST['id']}'") or die('error');
  echo json_encode(array("success" => true, 'id' => $_POST['id'], 'level' => $_POST['level'], 'result' => "User's Level has been changed"));
  exit();
}

$status['n']="Unpaid";
$status['y']="Paid";
$status['yn']="Paid, Preordered";
$status['yc']="Paid, Claimed";
$status['ycn']="Paid, Claimed, Preordered";
if(isset($_GET['showTable'])) {
  $points=get_reply("select sum(points) from codes,invoices where owned='{$_POST['uid']}' and codes.invoice=invoices.invoice and invoices.status='yc'");
  if(!$points) {$points=0;}
  $sum="User has <strong>$points</strong> points, and has spent:<br />";
  $sql->query("select sum(price),currency from invoices where status='yc' and uid='{$_POST['uid']}' group by currency");
  $x=0;
  while($sql->get_row()) {
    $sum.=$sql->get_col()." ".$sql->get_col()."<br />";
    $x++;
  }
  if($x==0) {
     $sum.="No Money";
  }
  $sql->query("select invoice,price,currency,time,status from invoices where uid='{$_POST['uid']}' order by time DESC");
  while($sql->get_row()) {
    $invoice['number']=$sql->get_col();
    $invoice['price']=$sql->get_col();
    $invoice['currency']=$sql->get_col();
    $invoice['time']=date("d/m/Y H:i",$sql->get_col());
    $invoice['status']=$status[$sql->get_col()];
    $invoices.="<li><a href='#{$invoice['number']}' class='invoiceInfo' invoice='{$invoice['number']}' data-toggle='tab'>{$invoice['number']} ({$invoice['price']} {$invoice['currency']})</a></li>";
    $invoices_info.="<div class='tab-pane' id='{$invoice['number']}'></div>";
  }
    echo "
    <div class='tabbable tabs-left' style='width:600px;height:300px;overflow-y:scroll;'>
      <ul class='nav nav-tabs invoiceList'>
        <li class='active'><a href='#information' data-toggle='tab'>Information</a></li>
        $invoices
      </ul>
      <div class='tab-content contentList' style='overflow-y:visible;'>
        <div class='tab-pane active' id='information' style='text-aling:left;'>
          $sum
        </div>
        $invoices_info
      </div>
    </div>";
    exit();
}

if(isset($_GET['loadGames'])) {
  $iid=$_POST['invoice'];
  $iid=substr($iid,1,strlen($iid));
  $code=get_reply("select code from invoices where invoice='$iid'");
  $st=get_reply("select status from invoices where invoice='$iid'");
  if($st!='n' && $st!='y') {
    //preorder
    $sql->query("select invoices.status,invoices.invoice,invoices.price,invoices.currency,games.id,games.name_en,games.cover from preorder,games,invoices where preorder.invoice='$iid' and preorder.gid=games.id and invoices.invoice='$iid'");
    while($sql->get_row()) {
      $s=$sql->get_col();
      $stat=$status[$s."n"];
      $games=Array( 'number' => $sql->get_col(), 'price' => $sql->get_col(), 'currency' => ucfirst(strtolower($sql->get_col())), 'id' => $sql->get_col(), 'name' => $sql->get_col(), 'cover' => $sql->get_col());
      $returns.="
        <tr>
          <td style='width:30px;'><img src='/gameImages/{$games['id']}/{$games['cover']}' style='width:30px;' /></td>
          <td>{$games['name']}</td>
          <td style='width:140px;font-variant:small-caps;' nowrap>$stat</td>
        </tr>";
    }
    //games
    $sql->query("select invoices.status,codes.codeText,codes.codeImage,invoices.invoice,invoices.price,invoices.currency,games.id,games.name_en,games.cover from codes,games,invoices where codes.invoice='$iid' and codes.gid=games.id and invoices.invoice='$iid'");
    while($sql->get_row()) {
      $s=$sql->get_col();
      $ct=$sql->get_col();
      $ci=$sql->get_col();
      $stat=$status[$s];
      $games=Array( 'number' => $sql->get_col(), 'price' => $sql->get_col(), 'currency' => ucfirst(strtolower($sql->get_col())), 'id' => $sql->get_col(), 'name' => $sql->get_col(), 'cover' => $sql->get_col());
      $returns.="
        <tr>
          <td style='width:30px;'><img src='/gameImages/{$games['id']}/{$games['cover']}' style='width:30px;' /></td>
          <td>{$games['name']}</td>
          <td style='width:140px;font-variant:small-caps;' nowrap>$stat</td>
        </tr>";
    }
  } else {
    $sql->query("select op.invoice,op.gameid,op.count,op.price,op.currency,op.invoice,games.cover,games.name_en from orders_pending as op,games where invoice='$iid' and op.gameid=games.id");
    while($sql->get_row()) {
      $s='n';
      $stat=$status[$st];
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
    $inv=($st=='n' ? "&nbsp;<button class='btn btn-success btn-payed' invoice='$iid'>Mark as Payed</button>" : "");
  }
  $statuz=$stat;
  $return['id']=$iid;
  $code=($s!='yc' && $s!='ycn' ? "(Code to activate: $code)" : "");
  $return['result']="
    <table style='width:100%;'>
      <tr>
        <td colspan='3' style='font-variant:small-caps;padding-top:5px;'>$statuz $code $inv</td>
      </tr>
      $returns
    </table>";
  echo json_encode($return);
  exit();
}

if(isset($_GET['markAsPayed'])) {
  $iid=$_POST['id'];
  $sql->query("update invoices set status='y' where invoice='$iid'");
  echo json_encode(Array("id" => $iid));
  exit();
}