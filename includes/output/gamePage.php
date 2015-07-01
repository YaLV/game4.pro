<?
$bn=$message->text('buy_now');

$gameList="";

$section['Games']['timeQuery']="unix_timestamp(concat_ws('-',substring(date_$language,7,4),substring(date_$language,4,2),substring(date_$language, 1,2)))<='".time()."'";
$section['Games']['bannertype']='gamepage';
$section['Games']['style']='badge_hot" style="margin-top:-25px;margin-left:-16px;z-index:100;"';

$section['Preorder']['timeQuery']="unix_timestamp(concat_ws('-',substring(date_$language,7,4),substring(date_$language,4,2),substring(date_$language, 1,2)))>'".time()."'";
$section['Preorder']['bannertype']='preorderpage';
$section['Preorder']['style']='badge_cs';

$section['SuperSave']['timeQuery']="(select active from super_savings where gameID=games.id)=1";
$section['SuperSave']['bannertype']='savingpage';
$section['SuperSave']['style']='badge_cs';
                                 
ob_start();

$sql->query("select points,id,date_$language,cover,name_$language,price_$currency,(select percent from super_savings where gameID=games.id),(select active from super_savings where gameID=games.id) from games where cover!='' and {$section[$_GET['x']]['timeQuery']} order by id DESC limit 9");
while($sql->get_row()) {
  list($points,$preSale_id,$preSale_date,$preSale_cover,$preSale_name,$preSale_price,$save,$ss_enabled)=Array($sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col());
  if($ss_enabled) {
    $super_saving="<div style='position:absolute;background:url(images/price_tag.png);width:80px;height:80px;text-align:center;'><div style='margin-top:30px;color:white;font-weight:bold;font-size:20px;' class='supersave'>-$save%</div></div>";
  } else {
    $super_saving="";
  }
  if($points) {
    $punkts="<div style='position:absolute;background:url(images/badge_points.png);width:60px;height:38px;text-align:left;margin-top:90px;margin-left:25px;'><div style='margin:0px;padding:0px;margin-top:5px;color:white;font-weight:bold;'><div style='margin-top:-5px;padding:0px;font-size:10px;margin-left:5px;'>Awards</div><div style='padding:0px;margin-top:-2px;font-size:12px;margin-left:7px;float:left;'>$points</div><div style='font-size:10px;margin-left:3px;float:left;'>pts</div></div></div>";
  } else {
    $punkts="";
  }
  include getcwd()."/templates/best_sellers.php";
}
$gameList=ob_get_clean();

$genres_list=Array('Action','Adventure','Strategy','Racing','RPG','Shooter','Simulation','Sport','MMORPG');
foreach($genres_list as $val) {
  $genres.="<div style='clear:left;'><input type='checkbox' name='option_genre_$val' id='option_genre_$val' value='$val' class='pull-left'><label for='option_genre_$val' class='pull-left' style='margin-left:5px;'>$val</label></div>";
}

$ages_list=Array("3","7","12","15","16","18");
foreach($ages_list as $val) {
  $ages.="<div style='clear:left;'><input type='checkbox' name='option_age_$val' id='option_age_$val' value='$val' class='pull-left'> <label for='option_age_$val' class='pull-left' style='margin-left:5px;'> Age $val+</label></div>";
}

$price_list=json_decode(get_reply("select `value` from settings where `key`='$currency'"),true);
foreach($price_list as $val) {
  list($p1,$p2)=split("-",$val,2);
  $p1=number_format($p1/100,2);
  $p2=Number_format($p2/100,2);
  $curr=ucfirst($currency);
  $prices.="<div style='clear:left;'><input type='checkbox' name='option_price' id='option_price_$p1' value='$val' class='pull-left'><label for='option_price_$p1' class='pull-left' style='margin-left:5px;'> $p1 $curr - $p2 $curr</label></div>";
}
$sql->query("select link,id from sliders where height='{$section[$_GET['x']]['bannertype']}' and active='y' order by rand() limit 1");
$sql->get_row();
list($link,$image)=Array($sql->get_col(),"slider/slide_".$sql->get_col().".jpg");
  $content_top="
    <div id='slider' style='height: 200px;'>
    <div>
        <div class='clickClass' data-link='$link' style='position: absolute; background: url(/images/content_top.png) no-repeat -139px -105px transparent; width: 900px; height: 200px; overflow: hidden;'></div>
        </div>
        <img src='$image' />
    </div>";
ob_start();
include getcwd()."/templates/gameList.php";
$outPut=ob_get_clean();