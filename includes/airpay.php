<?

if(isset($_GET['withBP'])) {
  if($me && $_POST['invoice']) {
    if(get_reply("select id from invoices where invoice='{$_POST['invoice']}' and uid='$me'")) {
      $userBP=getUserBP();
      $pointsDiff=json_decode(get_reply("select `value` from settings where `key`='pfc'"),true);
      $sum = get_reply("select price from invoices where invoice='{$_POST['invoice']}'");
      $curr = get_reply("select currency from invoices where invoice='{$_POST['invoice']}'");
      $points_for_Invoice=ceil($sum*$pointsDiff[strtolower($curr)]);  
      if($points_for_Invoice<=$userBP) {
        saveOrder($_POST['invoice']);
        $sql->query("update users set used_points=used_points+$points_for_Invoice where id='$me'");
        echo json_encode(Array("success" => true, "data" => "<br /><br /><br />Invoice has been paid with Bonus Points,<br /> Serial Keys are aviable at Your profile page"));
      } else {
        echo json_encode(Array("success" => false, "data" => "<br /><br /><br />Not enough bonus points!"));
      } 
    } else {
      echo json_encode(Array("success" => false, "data" => "<br /><br /><br />Invoice not found!"));
    }
  } else {
    echo json_encode(Array("success" => false, "data" => "<br /><br /><br />No Invoice Number!"));
  }
}

if(isset($_GET['getLastStatus'])) {
  include getcwd()."/templates/transaction_status.php";
  //unset($_SESSION['paymentStatus']);
  exit();
}


$l=Array("en" => "ENG", "ru" => "RUS");
date_default_timezone_set('Europe/Riga');    
$merchant_id=109;
$merchant_secret="7257e7035462232ef49d0110ef3ac644";
include getcwd()."/includes/airpay.class.php";
$airpay = new airpay( $merchant_id, $merchant_secret );




if(isset($_GET['response'])) {
  if (!$ret = $airpay->response($_GET, 'return')) { 
    echo "error";
  } else {
    if ($ret['status'] == 1) {
  		$_SESSION['paymentStatus']=1;
  	}
  	if ($ret['status'] == 2) {
  		$_SESSION['paymentStatus']=2;
  	}
  }
  echo "<script>self.close();</script>";
  exit();
} 


function saveOrder($invoice) {
  global $sql;
  $me=get_reply("select uid from invoices where invoice='$invoice'");
  $bponlvl=json_decode(get_reply("select `value` from settings where `key`='bponlvl'"), true);
  $sql->query("update users set level=2, used_points=used_points-{$bponlvl['bponlvl']} where id='$me' and level=1");
  $sql->query("update invoices set status='yc' where invoice='$invoice' and uid='$me'");
  $sql->query("select gameid,count,price,currency,points from orders_pending where invoice='$invoice' and `owner`='$me'");
  while($sql->get_row()) {
    $games[]=Array('id' => $sql->get_col(), 'count' => $sql->get_col(), 'price' => $sql->get_col(), 'currency' => $sql->get_col(), 'points' => $sql->get_col());
  }
  foreach($games as $val) {
    $to_give=0;
    $reserved_count=get_reply("select count(*) from codes where reserved='$me' and gid='{$val['id']}' and invoice='$invoice'");
    if($reserved_count==$val['count']) {
      $sql->query("update codes set owned='$me',reserved='0',points='{$val['points']}' where invoice='$invoice' and gid='{$val['id']}' and reserved='$me'");
      $to_give=0;            
    } elseif($reserved_count<$val['count']) {
      $sql->query("update codes set owned='$me',reserved='0',points='{$val['points']}' where invoice='$invoice' and gid='{$val['id']}' and reserved='$me'");
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
          $sql->query("update codes set owned='$me', invoice='$invoice' where cid='$v'");
          $to_give--;
        }
      }                                          
    }
    while($to_give>0) {
      $sql->query("insert into preorder values('','$invoice','".time()."','{$val['id']}','$me')") or die(mysql_error());
      $to_give--;
    }          
  }
  $sql->query("delete from orders_pending where invoice='$invoice' and `owner`='$me'");
}


if(isset($_GET['statusUpdate'])) {
  if (!$ret = $airpay->response($_POST, 'status')) {
    $e=1;
  } else {
    $inv_nr=get_reply("select invoice_nr from airpay where transaction_id='{$ret['transaction_id']}'");
    if($ret['status_id']==1) {
      if($inv_nr) {
        $sql->query("select price,currency from invoices where invoice='$inv_nr'");
        $sql->get_row();
        if($sql->get_col()==$ret['amount']/100 && $sql->get_col()==$ret['currency']) {
          if($ret['payment_system']=='cc_transpro') {
            //if($ret['cc_3dsecure']=='1') {
//              $mailMessage="sendPIN";
//            } else {
              $sql->query("update invoices set code='{$ret['cc_approval_code']}' where invoice='$inv_nr'");
              $mailMessage="sendPINInfo";
//            }
          } else {
            $mailMessage="sendPIN";
          }
          $sql->query("update invoices set status='y' where invoice='$inv_nr'");          
        }          
      }
      $mailer->receiver=get_reply("select email from users,invoices where invoices.invoice='$inv_nr' and invoices.uid=users.id");
      $Uname=get_reply("select name from users,invoices where invoices.invoice='$inv_nr' and invoices.uid=users.id");
      $mailer->respond="pass_reminder";
      $mailer->vars=Array("pin" => get_reply("select code from invoices where invoice='$inv_nr'"), "name" => $Uname);
      $mailer->send($mailMessage);
    }
    $sql->query("update airpay set payment_system='{$ret['payment_system']}', status='{$ret['status_id']}',statusTime='".time()."' where transaction_id='{$ret['transaction_id']}'");
    $sql->query("insert into payments values('$inv_nr',".time().",'{$ret['transaction_id']}','{$ret['status_id']}')");
  }
}

 
if(isset($_GET['payWith'])) {
  $psystem=array('cc_transpro','paypal','moneybookers','bl_test_bank');
  if(in_array($_GET['payWith'],$psystem)) {
   $invoice=$_GET['invoice'];
   $invoice_nr=$invoice;
   $sum = get_reply("select price from invoices where invoice='$invoice'");
   $curr = get_reply("select currency from invoices where invoice='$invoice'");
   $name=get_reply("select name from users where id='$me'");
   $email=get_reply("select email from users where id='$me'"); 
   $sql->query("select id,cover,name_en,price_$currency from games where id={$val['id']}");
   $invoice = array(
  		'amount'	=> $sum*100,					// minor units, e.g. 1 for 0.01
  		'currency'	=> strtoupper($curr),				// currency code in ISO 4217
  		'invoice'	=> $invoice,	// unique transaction value
  		'language'	=> $l[$language],				// language: LAT, RUS, ENG
  		'cl_fname'	=> $name,				// client's first name
  		'cl_lname'	=> "",				// client's last name
  		'cl_email'	=> $email,	// client's e-mail address
  		'cl_country'	=> 'LV',				// country code in ISO 3166-1-alpha-2
  		'cl_city'	=> 'Riga',				// city name
  		'description'	=> htmlspecialchars("Order ".$invoice),			// description of the transaction, visible to the client, e.g. description of the product
  		'psys'		=> $_GET['payWith'], 				// payment system alias. empty for default or taken from $airpay->psystems
   );
    $ret = $airpay->payment_req($invoice);
    $sql->query("replace into airpay values('','$invoice_nr','{$ret['transaction_id']}','','','')");
    header("location:".$ret['url']);
  } elseif($_GET['payWith']=='bp') {
    $bp_aviable=getUserBP();
    $pointsDiff=json_decode(get_reply("select `value` from settings where `key`='pfc'"),true);
    $sum = get_reply("select price from invoices where invoice='{$_GET['invoice']}'");
    $curr = get_reply("select currency from invoices where invoice='{$_GET['invoice']}'");
    $points_for_Invoice=$sum*$pointsDiff[strtolower($curr)];
    include getcwd()."/templates/pay_with_points.php";
    exit();
  } else {
    echo "Unknown payment system";
  }
}
exit();