<?
$genres_list=Array('Action','Adventure','Strategy','Racing','RPG','Shooter','Simulation','Sport','MMORPG');
$jses=Array('games','users','news','slider','settings','disclaimers','contacts' => 'disclaimers', 'preorders');
foreach($jses as $k => $val) {
  if(is_numeric($k)) { $k = $val; }
  if(strpos("page:".$_GET['x'],$k)==5) {
    $js="<script src='/admin/js/$val.js'></script>";
  }
}

function random($num) {
  if(!$num) { $num=12; }
	$chars=Array(48,49,50,51,52,53,54,55,56,57,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122);
  for($y=0;$y<25;$y++) {
    $string="";
  	while(strlen($string)<$num) {
      $chr=$chars[round(rand(0,count($chars)-1))];
  		$string.=chr($chr);
  	}
  }
  return $string;
}
if($_GET['x']=='sendmail') {
  include getcwd()."/includes/output/sendMail.php";
} elseif($_GET['x']=='games/list') {
  include getcwd()."/includes/output/list.php";
} elseif($_GET['x']=='games/add' || preg_match("/games\/edit\/([0-9]+)$/",$_GET['x'],$fnd)) {
  include getcwd()."/includes/output/selections.php";
  include getcwd()."/includes/output/addoredit.php";
} elseif(preg_match("/games\/view\/([0-9]+)$/",$_GET['x'],$fnd)) {
  $show=true;
  include getcwd()."/includes/output/selections.php";
  include getcwd()."/includes/output/overview.php";
} elseif($_GET['x']=='games/getList') {
  include getcwd()."/includes/output/createGameList.php";
  echo json_encode($result);
  exit();
} elseif($_GET['x']=='news/add' || preg_match("/news\/edit\/([0-9]+)$/",$_GET['x'],$fnd)) {
  include getcwd()."/includes/output/newsSel.php";
  include getcwd()."/includes/output/newsAdd.php";
} elseif($_GET['x']=='news/remove') {
  $id=spliti("_",$_POST['id']); 
  $sql->query("delete from news where id='{$id[1]}'");
  echo json_encode(array("success" => true, 'msg' => $id[1]));
  exit();                
} elseif($_GET['x']=='news/getList') {
  include getcwd()."/includes/output/createNewsList.php";
} elseif($_GET['x']=='news/list') {
  include getcwd()."/includes/output/newsList.php";
} elseif($_GET['x']=='news/view' || preg_match("/news\/view\/([0-9]+)$/",$_GET['x'],$fnd)) {
  include getcwd()."/includes/output/newsSel.php";
  include getcwd()."/includes/output/newsView.php";
} elseif(preg_match("/games\/codes\/([0-9]+)$/",$_GET['x'],$fnd)) {
  include getcwd()."/includes/output/codes.php";
} elseif(preg_match("/games\/addCodes\/([0-9]+)$/",$_GET['x'],$fnd)) {
  include getcwd()."/includes/actions/codes.php";
} elseif($_GET['x']=='games/codes/remove') {
  include getcwd()."/includes/actions/codes.php"; 
} elseif(preg_match("/games\/getCodeList\/([0-9]+)$/",$_GET['x'],$fnd)) {
  include getcwd()."/includes/output/codesList.php";
} elseif($_GET['x']=='games/codes/upload') {
  include getcwd()."/includes/actions/codes.php";
} elseif($_GET['x']=="slider/edit") {
  include getcwd()."/includes/output/sliderEdit.php";
} elseif($_GET['x']=='slider/action/save') {
  include getcwd()."/includes/actions/slider.php";
  exit();
} elseif($_GET['x']=='slider/getPoints') {
  if(preg_match('/^\d+$/',$_POST['id'])) {
    echo get_reply("select points from games where id='{$_POST['id']}'");
  }
  exit();
} elseif($_GET['x']=='slider/list') {
  if(isset($_GET['changeSel'])) {
    $tip=Array("slider" => "Main Page", "gamepage" => "Game Page", "preorderpage" => "Preorder Page", "savingpage" => "SuperSavings Page");
    $sql->query("update sliders set height='{$_POST['changeTo']}' where id='{$_POST['item']}'");
    echo json_encode(Array("success" => true, "id" => $_POST['item'], "page" => $tip[$_POST['changeTo']]));
    exit();
  }
  include getcwd()."/includes/output/sliderList.php";
} elseif($_GET['x']=='games/changeBS' && $_POST['id']) {
  $id=spliti("_",$_POST['id']);
  $Sid=get_reply("select id from best_sales where gameID='{$id[1]}'");
  if($Sid) {
    $sql->query("delete from best_sales where gameID='{$id[1]}'");
    echo json_encode(Array("success" => true, "state" => "n", 'id' => $_POST['id']));
  } else {
    $sql->query("insert into best_sales values('','{$id[1]}')");
    echo json_encode(Array("success" => true, "state" => "y", 'id' => $_POST['id']));
  }
  exit();
} elseif($_GET['x']=='games/changeActivation' && $_POST['id']) {
  $id=spliti("_",$_POST['id']);
  $Sid=get_reply("select active from games where id='{$id[1]}'");
  $newstate=($Sid=='n' ? 'y' : "n");
  if($Sid) {
    $sql->query("update games set active='$newstate' where id='{$id[1]}'");
    echo json_encode(Array("success" => true, "state" => "$newstate", 'id' => $_POST['id']));
  }
  exit();
} elseif($_GET['x']=='games/remove' && $_POST['id']) {
  $id=spliti("_",$_POST['id']);
  $sql->query("delete from games where id='{$id[1]}'");
  echo json_encode(Array("removed" => $id[1]));
  exit();
} elseif($_GET['x']=='slider/activate') {
  $id=spliti("-",$_POST['id']);
  $activate['n']['name']="Deactivate";
  $activate['y']['name']="Activate";
  $activate['n']['state']="y";
  $activate['y']['state']="n";
  $Sid=get_reply("select active from sliders where id='{$id[1]}'");
  $Oid=($Sid=='y' ? "n" : "y");
  $sql->query("update sliders set active='{$activate[$Sid]['state']}' where id='{$id[1]}'");
  echo json_encode(array("success" => true, "response" => $activate[$Sid]['name'], "id" => $_POST['id'], 'result' => "Slider/Comecrial ".$activate[$Oid]['name']."d"));
  exit();
} elseif(preg_match("/settings/",$_GET['x'])) {
  include getcwd()."/includes/output/settings/direct.php";
  $settings=true;
} elseif($_GET['x']=='users') {
  include getcwd()."/includes/output/users.php";
} elseif($_GET['x']=='disclaimers') {
  include getcwd()."/includes/output/disclaimers.php";
} elseif($_GET['x']=='contacts') {
  include getcwd()."/includes/output/contacts.php";
} elseif($_GET['x']=='preorders') {
  include getcwd()."/includes/output/preorders.php";
}

               
?>