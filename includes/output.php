<?

function buildSysReq($data) {
  return "<table class='sysreq'><tr><td>".preg_replace("/\n/","</td></tr><tr><td>",preg_replace("/:/","</td><td>",$data))."</td></tr></table>";
}

function getUserBP() {
  global $me,$sql;
  $points=get_reply("select sum(points) from codes,invoices where owned='$me' and codes.invoice=invoices.invoice and invoices.status='yc'");
  if(!$points) {$points=0;}
  $points-=get_reply("select used_points from users where id='$me'");
  return $points;
}
if($_GET['x']=='badge') {
  include getcwd()."/includes/badge.php";
  exit();
}

if($_GET['x']=='userProfile') {
    include getcwd()."/includes/output/userProfile.php";
    exit();
}

if($_GET['x']=='loadNews') {
  $sql->query("select data_$language,video from news where active='y' order by id desc");
  while($sql->get_row()) {
    $news[]=Array('text' => nl2br($sql->get_col()), 'video' => $sql->get_col());
  }
  echo json_encode($news);
  exit();
}




if($_SESSION['user_in_system']) {
  if($me) {
    $profile=$message->text('profile');
    $lo=$message->text('logout');
    $loginform='<div style="padding-top:10px;">Hello '.get_reply("select name from users where id='$me'")."! <a href='' class='propile'>$profile</a>  <a href='/logout'>$lo</a></div>";
    $logged_in=true;
  } 
}
if($logged_in!=true) {
  $ca=$message->text('create_account');
  $remind=$message->text('remind');
  $or=$message->text('or');
  $loginform='
        <input type="text" placeHolder="email" name="login" style="width:160px;margin-top:8px;"/>
        <input type="password" placeHolder="password" name="password" style="width:120px;margin-top:8px;"/>
        <button class="btn btn-info btn-go" style="font-weight:bold;">GO!</button><br /><div id="lor"><a href="Register" class="createAcc">'.$ca.'</a> '.$or.' <a href="Forgot" class="forgotPass">'.$remind.'</a></div>
        ';
}


if($_GET['x']=='arrow') {
  include getcwd()."/includes/arrow.php";
  exit();
}
if($_GET['x']=='airpay') {
  include getcwd()."/includes/airpay.php";
}

if($_GET['x']=='logout') {
  session_destroy();
  header("location:/");
  exit();
} elseif($_GET['x']=='carouselIms') {
  include getcwd()."/includes/output/sliderImages.php";
  exit();
} elseif(preg_match("/getTemplate\/([a-zA-Z]+)/",$_GET['x'],$fnd)) {
  if(file_exists(getcwd()."/templates/{$fnd[1]}.php")) {
    include getcwd()."/templates/{$fnd[1]}.php";
  }
  exit();
} elseif(preg_match("/do\/([a-zA-Z]+)/",$_GET['x'],$fnd)) {
  if(file_exists(getcwd()."/includes/actions/{$fnd[1]}.php")) {
    include getcwd()."/includes/actions/{$fnd[1]}.php";
  }
  exit();
} elseif($_GET['x']=='Games' || $_GET['x']=='Preorder' || $_GET['x']=='SuperSave') {
  if($_GET['viewGame'] && is_numeric($_GET['viewGame'])) {
    include getcwd()."/includes/output/viewGame.php";
  } else {
    include getcwd()."/includes/output/gamePage.php";
  }
  $e404=false;
} elseif($_GET['x']=='') {
  include getcwd()."/includes/output/mainPage.php";
  $e404=false;
} elseif(preg_match("/verify\/([0-9]{10})\/([a-zA-Z0-9]+)$/",$_GET['x'],$verify)) {
  include getcwd()."/includes/actions/verify.php";
  $e404=false;
} elseif($_GET['x']=='cart') {
  include getcwd()."/includes/output/cart.php";
  $e404=false;
} elseif($_GET['x']=='recover') {
  if($_POST['remind_id'] && $_POST['for'] && $_POST['user']) {
    if(get_reply("select id from pass_remind where remind_key='{$_POST['remind_id']}' and user='{$_POST['for']}'")) {
      if($_POST['remind_id']==crypt("{$_SERVER['REMOTE_ADDR']}-{$_POST['user']}-{$_POST['for']}",$_POST['remind_id'])) {
        $mailer->receiver=$_POST['user'];
        $mailer->respond="password_sent";
        $mailer->vars=Array("password" => generate_password(), 'email' => $_POST['user']);
        $mailer->send("new_password");
        if($mailer->result) {
          $sql->query("update users set password=ENCRYPT('{$mailer->vars['password']}') where id='{$_POST['for']}'");
          $sql->query("delete from pass_remind where user='{$_POST['for']}'");
        }
        echo json_encode(Array("success" => $mailer->result, "response" => $mailer->message));
      } else {
        echo json_encode(Array("success" => false, "response" => "Wrong authentication Data, please use this link from computer wich was used to recover password"));
      }
    } else {
      echo json_encode(Array("success" => false, "response" => "Authentication data not found!"));
    }
    exit();
  } elseif(!$_GET['remind_id'] && !$_GET['for'] && !$_GET['user'] && !$_POST['email']) {
    echo json_encode(Array("success" => false, "response" => "Authentication data not found!"));
    exit();
  } 
  if($_POST['email']) {
    $eml=$_POST['email'];
    $user_exists=get_reply("select id from users where email='$eml' and confirmed='y'");
    if($user_exists) {
      $mailer->respond="pass_reminder";
      $mailer->receiver=$eml;
      $cc=crypt("{$_SERVER['REMOTE_ADDR']}-$eml-$user_exists");
      $mailer->vars=Array("confirm_key" => urlencode($cc), 'email' => $eml, 'uid' => $user_exists);
      $mailer->send('remind_confirm');
      if($mailer->result) {
        $sql->query("insert into pass_remind values('','".time()."','$user_exists','$cc')");
      }
      echo json_encode(Array("success" => $mailer->result, 'message' => "<br /><br /><br />".$mailer->message));
    } else {
      echo json_encode(Array("success" => false, 'message' => $message->text('remind_email_not_found')));
    }
    exit();
  }
} elseif($_GET['x']=='AboutUs') {
  include getcwd()."/includes/output/about_us.php";
  $e404=false;
} elseif($_GET['x']=='Contacts' || $_GET['x']=='help' || $_GET['x']=='help2') {
  include getcwd()."/includes/output/contacts.php";
  $e404=false;
}

if($e404) {
  include getcwd()."/includes/output/mainPage.php";
}


?>