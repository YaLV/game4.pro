<?
date_default_timezone_set("GMT");
header("content-type:text/html; charset=utf8;");
if($_SERVER['REMOTE_ADDR']=='193.46.236.209' || $_SERVER['REMOTE_ADDR']=='159.148.102.30' || $_SERVER['REMOTE_ADDR']=='85.31.99.114'  || $_SERVER['REMOTE_ADDR']=='94.30.181.2') {
?>
<body style='height:100%;overflow:hidden;margin:0px;'>
<div style='padding:5px;'><center><form method='post'>Enter String: <input type='text' name='string' value='<?=$_POST['string']?>' onDblClick='this.value=""' /><br />
<input type='submit' name='doIt' value='Pārbaudīt' /></form></center></div>
<?
function pwgen($num) {
  if(!$num) { $num=12; }
	$chars=Array(48,49,50,51,52,53,54,55,56,57,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122);
  for($y=0;$y<25;$y++) {
    $string="";
  	while(strlen($string)<$num) {
      $chr=$chars[round(rand(0,count($chars)-1))];
  		$string.=chr($chr);
  	}
    $ec[]=$string;
  }
  echo "<table style='margin:auto;border-collapse;collapse;' border='1' cellpadding='5'>";
  $z=0;
  foreach($ec as $val) {
    if($z==5) { echo "</tr><tr>"; $z=0;}
    echo "<td>$val</td>";
    $z++;
  }
  echo "</table>";
}

function do_host_info($host) {
    ?><div style='width:45%;float:right;'><pre><?
    if(!preg_match("/.+\..+\..{2,4}/",$host) || preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/",$host) || preg_match("/.+\.[a-zA-Z]{2,3}\.[a-zA-Z]{2,3}/",$host)) {
        system("/usr/bin/whois ".$host,$whoiss);
    } else {
	     echo "This is not a domain name";
    }
    ?></div><?
    ?><div style='width:45%;float:right;'><pre><?
?>
---------------------
Mūsu serveru adreses:

IF21: 91.203.68.66
IF23: 91.203.68.130
IF24: 193.46.236.34
IF25: 193.46.236.105
IF26: 85.31.97.194
IF27: 85.31.97.226
--------------------

<?
    system("host ".$host,$whoiss);
    ?></div><?
}

function check_pk($pk) {
  //echo "In Production";
  $date=substr($pk,0,2);
  $month=substr($pk,2,2);
  $year=substr($pk,4,2);
  $numm=Array("",1,6,3,7,9,10,5,8,4,2);
  $pk=str_split(implode("",explode("-",$pk)));
  if($date<=31 && $month<=12) {
    if($year>date("y")) { $n7=1; }
    foreach($pk as $num) {
      $x++;
      if($x<11) {
        $sum+=$num*$numm[$x];
      } else { $lastnumber=$num; }
    }
    $lnc=1101-$sum-floor((1101-$sum)/11)*11;
    $howold=date("Y")-($year>date("y") ? (substr(date("Y"),0,2)-1).$year : substr(date("Y"),0,2).$year);
    if($lnc==$lastnumber) {
      echo "Personas kods ir pareizs!!! Klienta vecums: <strong>$howold</strong>";
    } else {
      echo "Personas kods nav derīgs";
    }
  }
}

?>
<div style='overflow-y:scroll;height:90%;width:100%;overflow-x:hidden;'>
<?
    if($_POST['doIt']) {
	if(preg_match("/^[0-9]{6,6}-[0-9]{5,5}$/",trim($_POST['string']))) {
	    ?><div style='width:90%;float:right;'><pre><?
	    check_pk(trim($_POST['string']));
	    ?></div><?
	} elseif(empty($_POST['string']) || is_numeric(trim($_POST['string']))) {
	    ?><div style='width:90%;float:right;'><pre><?
	    pwgen(trim($_POST['string']));
	    ?></div><?
	} else {
	    do_host_info(trim($_POST['string']));
	}
    }
} else {
echo $_SERVER['REMOTE_ADDR'];
}
?>
</div>

