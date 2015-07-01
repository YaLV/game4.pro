<?
$cart_id=session_id();
if(isset($_GET['insert']) && $_POST['id']>0) {
  $count_id=get_reply("select id from cart where gameID='{$_POST['id']}' and cart_no='$cart_id'");
  if($count_id) {
    $pcount=get_reply("select count from cart where gameID='{$_POST['id']}' and cart_no='$cart_id'");
    if($limits[$uLVL]['count']>$pcount || $limits[$uLVL]['count']==0) { 
      $sql->query("update cart set count=count+1 where gameID='{$_POST['id']}' and cart_no='$cart_id'") or $error=1;
    } else { 
      $error=2; 
    }
  } else {
    $itemCount=get_reply("select count(*) from cart where cart_no='$cart_id'");
    $carttime=get_reply("select added from cart where cart_no='$cart_id'");
    $carttime=($carttime>0 ? $carttime : time());
    if($limits[$uLVL]['itemCount']>$itemCount || $limits[$uLVL]['itemCount']==0) { 
      $sql->query("insert into cart values('','$carttime','$cart_id','{$_POST['id']}','1')") or $error=1;    
    } else { 
      $error=2; 
    } 
  }
  if(!$error) {
    echo json_encode(Array("result" => $message->text('added_to_cart'), 'success' => true));
  } elseif($error==1) {
    echo json_encode(Array('success' => false, 'result' => $message->text('sql_error_adding_to_cart')));    
  } elseif($error==2) {
    echo json_encode(Array('success' => false, 'result' => $message->text('max_items_in_cart')));    
  }
  exit();
} 

if(isset($_GET['count'])) {
  echo json_encode(Array("hasItems" => get_reply("select count(*) from cart where cart_no='$cart_id'")));
  exit();
}

if(isset($_GET['html'])) {
  if(get_reply("select count(*) from cart where cart_no='$cart_id'")>0) {
    $totalprice=0;
    $sql->query("select gameID,count from cart where cart_no='$cart_id' and count>0");
    while($sql->get_row()) {
      $gid[]=Array('id' => $sql->get_col(), 'cnt' => $sql->get_col());
    }
    $result="<div style='position:absolute;z-index:1111111111;'><table class='cart popplesejs'>";
    if(is_array($gid)) {
      foreach($gid as $val) {
        $sql->query("select id,cover,name_en,price_$currency from games where id={$val['id']}");
        $sql->get_row();
        list($id,$cover,$name,$price_per_unit)=Array($sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col());
        if($uLVL>1) {
          $minus="<button class='btn btn-mini pull-left minus changeCount' product-id='$id'>-</button>";
          $plus="<button class='btn btn-mini pull-left plus changeCount' product-id='$id'>+</button>";
        }
        $rem="<button class='btn btn-danger btn-mini pull-left remove changeCount' product-id='$id'>X</button><div style='clear:left;'></div>";
        $price=number_format(round($price_per_unit*$val['cnt'],2),2);
        $price_per_unit_show="<div class='pull-right'>&nbsp;$currency</div><div class='pull-right' id='ppu_$id'>".number_format(round($price_per_unit,2),2)."</div>";
        $price_show="<div class='pull-right'>&nbsp;$currency</div><div class='pull-right' id='p_$id'>$price</div>";
        $count=$val['cnt'];
        ob_start();
        include getcwd()."/templates/cart_item.php";
        $result.=ob_get_clean();
        $totalprice+=$price;
      }
      $totalprice="<div class='pull-right'>&nbsp;$currency</div><div class='pull-right' id='tp'>".number_format(round($totalprice,2),2)."</div>";
      $result.="<tr><td colspan='4' style='text-align:right;'>".$message->text('total')."</td><td>$totalprice</td></tr>";
      $result.="<tr><td colspan='6' style='text-align:right;'>".buynow()."</td></tr>";
      $result.="</table></div><div id='plesejs'></div>";
    } else {
      $result=$message->text('empty_cart');
    }
  }
  echo json_encode(Array("html" => $result));
  exit();
}

if(isset($_GET['minus']) || isset($_GET['plus']) || isset($_GET['remove'])) {
  if(!isset($_GET['remove'])) {
    $sign=(isset($_GET['plus']) ? "+" : "-");
    $count=get_reply("select count from cart where gameID='{$_POST['id']}' and cart_no='$cart_id'");
    $itemCount=get_reply("select count(*) from cart where cart_no='$cart_id'");
    $allow=1;
    if(isset($_GET['plus'])) {
      if($limits[$uLVL]['count']>$count || $limits[$uLVL]['count']==0) { $count++; } else { $allow=0; }
    } else {
      $count--;
    }
    if($allow) {
      $sql->query("update cart set count=$count where gameID='{$_POST['id']}' and cart_no='$cart_id'") or $error=1;
    } else {
      $error=2;
    }
  }
  if($count<=0 || isset($_GET['remove'])) {
    $sql->query("delete from cart where gameID='{$_POST['id']}' and cart_no='$cart_id'");
  }
  if(!$error) {
    echo json_encode(Array('success' => true, 'id' => $_POST['id'], 'count' => ($count==0 ? "remove" : $count)));    
  } elseif($error==1) {
    echo json_encode(Array('success' => false, 'id' => $_POST['id'], 'result' => $message->text('sql_error_adding_to_cart')));    
  } elseif($error==2) {
    echo json_encode(Array('success' => false, 'id' => $_POST['id'], 'result' => $message->text('sql_error_adding_to_cart')));    
  }
  exit();
}


if(isset($_GET['finishOrder']) && $me) {
  if($_POST['accepted']=='y') {
    $invoice_number=date("dmy")."001";
    $in=get_reply("select id from invoices where invoice='G4P-$invoice_number'");
    while($in) {
      $invoice_number++;
      $invoice_number=sprintf("%1$09d",$invoice_number);
      $in=get_reply("select id from invoices where invoice='G4P-$invoice_number'");    
    }
    $invoice_number=sprintf("%1$09d",$invoice_number);
    $invoice_number="G4P-$invoice_number";
    $code=sprintf("%1$04d",rand(0,9999));
    $price=get_reply("select sum(price_$currency*cart.count) from games,cart where games.id=cart.gameID and cart.cart_no='$cart_id'");
    $sql->query("insert into invoices values('','$invoice_number','$price','$currency','".time()."','n','$me','$code')") or die('invoice insert');
    $sql->query("select gameID,count,games.price_$currency,games.points from games,cart where cart_no='$cart_id' and cart.gameID=games.id") or die(mysql_error());
    while($sql->get_row()) {
      $games[]=Array('id' => $sql->get_col(), 'count' => $sql->get_col(), 'price' => $sql->get_col(), 'points' => $sql->get_col());
    }
    foreach($games as $val) {
      $sql->query("insert into orders_pending values('','{$val['id']}','{$val['count']}','{$val['price']}','$currency','{$val['points']}','$invoice_number','$me')");
      while($val['count']>0) {
        $count_aviable=get_reply("select count(*) from codes where reserved='0' and owned='0'") or 0;
        if($count_aviable>0) {
          $sql->query("update codes set reserved='$me',invoice='$invoice_number' where reserved='0' and owned='0' and gid='{$val['id']}' order by rand() limit 1") or die(mysql_error());
        }
        $val['count']--; 
      }      
    }
    $sql->query("delete from cart where cart_no='$cart_id'");
    $result['result']=true;
    $invoice=$invoice_number;
    ob_start();
    include getcwd()."/templates/payment.php";
    $result['message']=ob_get_clean();
  } else {
    $result['message']=$message->text('accept_tanda');
    $result['result']=false;
  }
  echo json_encode($result);
  exit();
}

if(isset($_GET['unlock'])) {
  $invoice=$_POST['unlock'];
  $status=get_reply("select status from invoices where invoice='$invoice' and uid='$me'");
  $result['id']=$invoice;
  switch ($status) {
    case "n":
      $invoice_number=$invoice;
      ob_start();
      include getcwd()."/templates/payment.php";
      $result['message']=preg_replace("/-40/","0",ob_get_clean());
      $result['result']="redraw";
      $result['success']=true;
    break;
    case "y":
      $code=get_reply("select code from invoices where invoice='$invoice' and uid='$me'") or die(mysql_error());
      if($code==$_POST['code']) {
        $bponlvl=json_decode(get_reply("select `value` from settings where `key`='bponlvl'"), true);
        //print_r($bponlvl);
        //echo "update users set level=2, used_points=used_points-{$bponlvl['bponlvl']} where id='$me' and level=1";
        $sql->query("update users set level=2, used_points=used_points-{$bponlvl['bponlvl']} where id='$me' and level=1") or die(mysql_error());
        $sql->query("update invoices set status='yc' where invoice='$invoice' and uid='$me'") or die(mysql_error());
        $sql->query("select gameid,count,price,currency,points from orders_pending where invoice='$invoice' and `owner`='$me'") or die(mysql_error());
        while($sql->get_row()) {
          $games[]=Array('id' => $sql->get_col(), 'count' => $sql->get_col(), 'price' => $sql->get_col(), 'currency' => $sql->get_col(), 'points' => $sql->get_col());
        }
        foreach($games as $val) {
          $to_give=0;
          $reserved_count=get_reply("select count(*) from codes where reserved='$me' and gid='{$val['id']}' and invoice='$invoice'");
          if($reserved_count==$val['count']) {
            $sql->query("update codes set owned='$me',reserved='0',points='{$val['points']}' where invoice='$invoice' and gid='{$val['id']}' and reserved='$me'") or die(mysql_error());
            $to_give=0;            
          } elseif($reserved_count<$val['count']) {
            $sql->query("update codes set owned='$me',reserved='0',points='{$val['points']}' where invoice='$invoice' and gid='{$val['id']}' and reserved='$me'") or die(mysql_error());
            $to_give=$val['count']-$reserved_count;            
          } 
          if($to_give>0) {
            unset($code_id);
            $sql->query("select cid from codes where reserved='0' and owned='0' and gid='{$val['id']}' limit $to_give") or die(mysql_error());
            while($sql->get_row()) {
              $code_id[]=$sql->get_col();
            }
            if(is_array($code_id)) {
              foreach($code_id as $v) {
                $sql->query("update codes set owned='$me', invoice='$invoice' where cid='$v'") or die(mysql_error());
                $to_give--;
              }
            }                                          
          }
          while($to_give>0) {
            $sql->query("insert into preorder values('','$invoice','".time()."','{$val['id']}','$me')") or die(mysql_error());
            $to_give--;
          }          
        }
        $sql->query("delete from orders_pending where invoice='$invoice' and `owner`='$me'") or die(mysql_error());
        $result['result']=$message->text('pin_accepted');
        $result['success']=true;
        $_SESSION['unlocked']=1;
      } else {
        $result['result']=$message->text('wrong_pin');
        $result['success']=false;
      }
    break;
    case "yc":
      $pwd=get_reply("select password from users where id='$me'");
      if(crypt($_POST['password'],$pwd)==$pwd) {
        $_SESSION['unlocked']=1;
        $result['result']=$message->text('pass_accept');
        $result['success']=true;
      } else {
        $result['result']=$message->text('wrong_pass');
        $result['success']=false;
      }
    break;
    default:
      $result['result']="Error!";
      $result['success']=false;
    break;
  }
  echo json_encode($result);
  exit();
}

function buynow() {
  global $me,$message;
  if($me) {
    return "<div class='pull-left' style='font-variant:small-caps;'><form id='buyForm'><input type='checkbox' name='accepted' value='y'/>".$message->text('tanda')." </form></div><br /><button class='btn btn-success pull-right' id='buyIt'>".$message->text('buy_now')."</button>";
  } else {
    return $message->text('please')." <a href='javascript:void(0);' id='doLogin'>".$message->text('login')."</a> ".$message->text('or')." <a href='javascript:void(0);' id='doRegister'>".$message->text('register')."</a>".$message->text('to_buy');
  }
}