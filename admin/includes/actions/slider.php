<?
$a=array('remove','link');
$sadala=Array(
  'preorderpage' => 'Preorder',
  'savingpage' => 'SuperSave',
  'slider' => 'Games',
  'gamepage' => 'Games',
);
if($_GET['saveField'] && in_array($_GET['saveField'],$a)) {
  if($_GET['saveField']=='remove') {
    $sql->query("delete from sliders where id='{$_POST['id']}'");
    echo json_encode(Array("success" => true, "id" => $_POST['id']));
    exit();
  } else {
    if($_POST[$_GET['saveField']]=='No Link present' || $_POST[$_GET['saveField']]=='') {
      $sql->query("update sliders set link='' where id='{$_POST['id']}'");
      echo json_encode(Array("success" => true, "response" => 'No Link present', "id" => $_POST['id'], "info" => "Link Removed"));
      exit();
    } elseif(preg_match("/^http:\/\/|^https:\/\//",$_POST[$_GET['saveField']]) || is_numeric($_POST[$_GET['saveField']])) {
      if(is_numeric($_POST[$_GET['saveField']])) {
        $sad=$sadala[get_reply("select height from sliders where id='{$_POST['id']}'")];
        $linkz="$sad?viewGame=".$_POST[$_GET['saveField']];
        $info='Entered link seems to be game ID, autocorrecting link';
      } else {
        $linkz=$_POST[$_GET['saveField']];
        $info='Link saved';
      }
      $sql->query("update sliders set {$_GET['saveField']}='$linkz' where id='{$_POST['id']}'");
      if($_POST[$_GET['saveField']]=='') { $res="No Link present"; } else { $res=$linkz; }
      echo json_encode(Array("success" => true, "response" => $res, "id" => $_POST['id'], "info" => $info));
      exit();
    } else {
      echo json_encode(Array("success" => false, "response" => 'Invalid link, please use http(s)://', "id" => $_POST['id']));
      exit();
    }
  }
}
$createfrom=Array('','imagecreatefromgif','imagecreatefromjpeg','imagecreatefrompng');
if($_POST['coords']) {
  $y=($_POST['position']=='slider' ? 478 : 200);
  $c=spliti(",",$_POST['coords']);
  $gis=getimagesize("../slider/slider.jpg");
  $image = $createfrom[$gis[2]]("../slider/slider.jpg") or die('slider img');
  $thumb = imagecreatetruecolor( 900, $y );
  
  imagecopyresampled($thumb,
                     $image,
                     0,
                     0,
                     $c[0], $c[1],
                     900, $y,
                     900, $y);
  $points=$_POST['pnts'];
  if($_POST['badge']=='1' && $y==478 && $points>0) {
    $badge=imagecreatefrompng("../images/badge.png") or die("badge");
    $white=imagecolorallocate($thumb,0xFF,0xFF,0xFF);
    imagefttext($badge,18,0,10,47,$white,getcwd()."/Arial.ttf",$points) or die("error writing");
    imagecopyresampled($thumb,$badge,0,342,0,0,98,62,98,62) or die('copy');
  }
  if(is_numeric($_POST['link'])) {
    $linkz=$sadala[$_POST['position']]."?viewGame=".$_POST['link'];
  } else {
    $linkz=$_POST['link'];
  }
  $sql->query("insert into sliders values('','{$_POST['position']}','$linkz','n')") or die("cannot insert");
  $id=get_reply("select last_insert_id() from sliders")or die("not get id");
  imagejpeg($thumb,"../slider/slide_$id.jpg",100) or die('fire create');
  echo json_encode(Array("success" => true, 'result' => 'Slider Have been Added.'));
  exit();
}