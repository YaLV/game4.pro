<?
$bn=$message->text('buy_now');
$comingSoon="";

ob_start();
$sql->query("select price_$currency,points,id,date_$language,cover,name_$language,(select percent from super_savings where gameID=games.id),(select active from super_savings where gameID=games.id) from games where unix_timestamp(concat_ws('-',substring(date_$language,7,4),substring(date_$language,4,2),substring(date_$language, 1,2)))>'".time()."' and games.active='y' limit 5");
while($sql->get_row()) {
  $preSale_price=$sql->get_col();
  list($points,$preSale_id,$preSale_date,$preSale_cover,$preSale_name,$save,$ss_enabled)=Array($sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col());
  $pathname='Preorder';
  if($ss_enabled) {
    $super_saving="<div style='position:absolute;background:url(images/price_tag.png);width:80px;height:80px;text-align:center;'><div style='margin-top:30px;color:white;font-weight:bold;font-size:20px;' class='supersave'>-$save%</div></div>";
  } else {
    $super_saving="";
  }
  if($points) {
    $punkts="<div style='position:absolute;background:url(images/badge_points.png);width:60px;height:38px;text-align:left;margin-top:90px;margin-left:25px;'><div style='margin:0px;padding:0px;margin-top:5px;color:white;font-weight:bold;'><div style='margin-top:-5px;padding:0px;font-size:10px;margin-left:5px;'>Awards</div><div style='padding:0px;margin-top:-2px;font-size:12px;margin-left:7px;float:left;'>1$points</div><div style='font-size:10px;margin-left:3px;float:left;'>pts</div></div></div>";
  } else {
    $punkts="";
  }
  include getcwd()."/templates/coming_soon.php";
}
$comingSoon=ob_get_clean();

$bestSellers="";
ob_start();
$sql->query("select points,games.id,date_$language,cover,name_$language,price_$currency,(select percent from super_savings where gameID=games.id),(select active from super_savings where gameID=games.id) from games,best_sales where cover!='' and best_sales.gameID=games.id and games.active='y' order by games.id desc limit 5");
while($sql->get_row()) {
  list($points,$preSale_id,$preSale_date,$preSale_cover,$preSale_name,$preSale_price,$save,$ss_enabled)=Array($sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col());
  $pathname='Games';
  if($ss_enabled) {
    $super_saving="<div style='position:absolute;background:url(images/price_tag.png);width:80px;height:80px;text-align:center;'><div style='margin-top:30px;color:white;font-weight:bold;font-size:20px;' class='supersave'>-$save%</div></div>";
  } else {
    $super_saving="";
  }
  if($points) {
    $punkts="<div style='position:absolute;background:url(images/badge_points.png);width:60px;height:38px;text-align:left;margin-top:90px;margin-left:25px;'><div style='margin:0px;padding:0px;margin-top:5px;color:white;font-weight:bold;'><div style='margin-top:-5px;padding:0px;font-size:10px;margin-left:5px;'>Awards</div><div style='padding:0px;margin-top:-2px;font-size:12px;margin-left:7px;float:left;'>2$points</div><div style='font-size:10px;margin-left:3px;float:left;'>pts</div></div></div>";
  } else {
    $punkts="";
  }
  include getcwd()."/templates/best_sellers.php";
}
$bestSellers=ob_get_clean();

ob_start();
include getcwd()."/templates/mainPage.php";
$outPut=ob_get_clean();