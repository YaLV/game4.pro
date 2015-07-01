<?
$langs=Array("en","ru");
if($_GET['setLang'] && in_array($_GET['setLang'],$langs)) {
  $_SESSION['language']=$_GET['setLang'];
  header("location:".htmlspecialchars($_GET['returnTo']));
  exit();
}
if($_SERVER['HTTP_HOST']=='game4.ru') {
  $_SESSION['language']=$language="ru";
  $_SESSION['currency']='rub';   
} else {
  if(!$_SESSION['language']) {
    $_SESSION['language']=$language="en";
  } else {
    if(in_array($_SESSION['language'],$langs)) { $language=$_SESSION['language']; } else { $_SESSION['language']=$language="en"; }
  }
  $money=Array("eur","usd","gbp","rub");
  if($_GET['setCurrency'] && in_array($_GET['setCurrency'],$money)) {
    $_SESSION['currency']=$_GET['setCurrency'];
    header("location:".htmlspecialchars($_GET['returnTo']));
    exit();
  }
  
  if(!$_SESSION['currency']) {
    $_SESSION['currency']=$currency="eur";
  } else {
    if(in_array($_SESSION['currency'],$money)) { $currency=$_SESSION['currency']; } else { $_SESSION['currency']=$currency="eur"; }
  }
}


$e404=true;
$mysql['db']='game4pro_site';
$mysql['host']='localhost';
$mysql['user']='game4pro_user';
$mysql['pass']='16sTaQut1P0S';
include getcwd()."/includes/sql.inc.php";



$sql->query("select `value` from settings where `key`='levels'");
$sql->get_row(); 
$limits=json_decode($sql->get_col(),true);


if($_SESSION['user_in_system']) {
  $uid=$_SESSION['user_in_system'];
  $upw=get_reply("select password from users where id='$uid'");
  $ip=$_SERVER['REMOTE_ADDR'];
  $session_accepted=crypt("$ip-$uid-$upw-$uid",$_SESSION['user_string']);
}

if($session_accepted==$_SESSION['user_string'] && $_SESSION['user_in_system']) {
  $me=get_reply("select id from users where id='{$_SESSION['user_in_system']}'");
  $uLVL=get_reply("select level from users where id='{$_SESSION['user_in_system']}'");
  $_SESSION['language']=get_reply("select language from users where id='{$_SESSION['user_in_system']}'");
} elseif($_SESSION['user_in_system']) {
  session_destroy();
  header("location:/");
  exit();
} else {
  $uLVL=1;
}


function buildSQL($data,$var) {
  global $me;
  $first=true;
  $query="select [field] from [table] where [search]";
  if($data['table'] && $data['field']) {
    foreach($data as $k => $v) {
      if($k[0]!="=" && $k[0]!="-") {
        $query=preg_replace("/\[$k\]/",$v,$query);
      } elseif($k[0]=='=') {
        if($first) { $fld=substr($k,1,strlen($k)); if(strpos($var[$v],".")>0) { $search="$fld='{$var[$v]}'"; } else { $search="$fld='{$var[$v]}'"; } $first=false; } else { $fld=substr($k,1,strlen($k)); if(strpos($var[$v],".")>0) { $search.=" and $fld='{$var[$v]}'"; } else { $search.=" and $fld='{$var[$v]}'"; } }
      } elseif($k[0]=='-') {
        $addon.=" $v";
      }
    }
  }
  return preg_replace("/\[search\]/",$search,$query);
}

function generate_password() {
	$chars=Array(48,49,50,51,52,53,54,55,56,57,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122);
  $string="";
	while(strlen($string)<12) {
    $chr=$chars[round(rand(0,count($chars)-1))];
		$string.=chr($chr);
	}
  return $string;
}
