<?
$section['Preorder']['bannertype']='preorderpage';
$section['Games']['bannertype']='gamepage';
$section['SuperSave']['bannertype']='savingpage';
$bs=$message->text('buy_now');
if(get_reply("select id from games where id='{$_GET['viewGame']}'")) {
  $sql->query("select id,points,(select active from super_savings where gameID=games.id),(select percent from super_savings where gameID=games.id),price_$currency,name_$language,sysreq_$language,description_$language,date_$language,youtube_video_1,youtube_video_2,youtube_video_3,images,cover from games where id='{$_GET['viewGame']}'");
  $sql->get_row();
  $id=$sql->get_col();
  $points=$sql->get_col();
  if($points) {
    $punkts="<div style='position:absolute;background:url(images/badge_points.png);width:60px;height:38px;text-align:left;margin-top:150px;margin-left:-5px;'><div style='margin:0px;padding:0px;margin-top:5px;color:white;font-weight:bold;'><div style='margin-top:2px;padding:0px;font-size:10px;margin-left:5px;'>Awards</div><div style='padding:0px;margin-top:2px;font-size:12px;margin-left:7px;float:left;'>$points</div><div style='font-size:10px;margin-left:10px;float:left;margin-top:4px;'>pts</div></div></div>";
  } else {
    $punkts="";
  }
  $ss_enabled=$sql->get_col();
  $save=$sql->get_col();
  $price=sprintf("%1.02f",$sql->get_col());
  if($ss_enabled) {
    $super_saving="<div style='position:absolute;background:url(images/price_tag.png);width:80px;height:80px;text-align:center;margin-left:-30px;margin-top:-20px;'><div style='margin-top:30px;color:white;font-weight:bold;font-size:20px;' class='supersave'>-$save%</div></div>";
    $op="<div class='oldprice'><div style='float:left;'>Old Price</div><div style='float:right;'>".sprintf("%1.02f",round($price*($save/100+1),2))." $currency</div></div>";
  }
  $name=$sql->get_col();
  $sysreq=buildSysReq(htmlspecialchars_decode($sql->get_col()));
  //$sysreq=$sql->get_col();
  $description=$sql->get_col();
  $description = preg_replace('/<p[^>]*>/', '', $description); // Remove the start <p> or <p attr="">
  $description = preg_replace('/\<\/p\>/', '<br />', $description); // Replace the end
  $rdate=$sql->get_col();
  $youtube[]=$sql->get_col();
  $youtube[]=$sql->get_col();
  $youtube[]=$sql->get_col();
  foreach($youtube as $val) {
    if($val!='') { $yt[]=$val;}
  }
  $x=0;
  if(count($yt)>0) {
    while($youtube[$x]!='') {
      $ytt.=$youtube[$x].",";
      $x++;
    }
  }
  $ytt=substr($ytt,0,strlen($ytt)-1);
  $vthumbs.="<input type='hidden' id='vidz' value='$ytt'  /><div id='graph_contents'></div>";
  $imgs=explode("\n",$sql->get_col());
  $cvi=$sql->get_col();
  $cover="/gameImages/".$_GET['viewGame']."/".$cvi;
  $x=0;
  foreach($imgs as $k => $val) {
    if($val!=$cvi && $val!='') {
      $x++;
      if($ithumbs=="") {
        $csize=getimagesize(getcwd()."/gameImages/{$_GET['viewGame']}/$val");
        if($csize[0]>580) { $sz="background-size:580px;"; }
        $ithumbs="<div class='big_im_div'>
          <a href='/gameImages/{$_GET['viewGame']}/$val' id='big_im' data-from='$k' style='background: url(/gameImages/{$_GET['viewGame']}/$val) no-repeat center top;$sz'></a>
        </div>";
      }
      $nm=($x==4 || $x==8 || $x==12 ? "nm" : "");
      $ithumbs.="<div class='thumbs_div $nm'>
                  <a href='/gameImages/{$_GET['viewGame']}/$val'  data-id='$k' rel='clearbox[gallery=ss]' data-title=\"".htmlspecialchars($name)."\">
                    <div class='im_over'></div>
                    <img src='/gameImages/{$_GET['viewGame']}/$val' alt='' class='img_thumb' />
                  </a>
                </div>";
    }
  }

  $imSize=getimagesize(getcwd().$cover);
  $posX=7;
  $posY=($imSize[1]/$imSize[0])*150-84;
  $pos = $posY."px 0px 0px ".$posX."px";
  $oos=($cnt>0 ? "" : "<div class='soos' style='height:90px;width:150px;position:absolute;background:url(/images/out_of_stock.png) no-repeat;background-size:150px 90px;margin:$pos'></div>");
  if($cnt<=0) {
    $stock='out_of_stock';
  } else {
    $stock='in_stock';
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
  $bestSellers=getBestSellers();  
  ob_start();
  include getcwd()."/templates/viewGame.php";
  $outPut=ob_get_clean();
} else {
  $viewed=false;
}

function getBestSellers() {
  global $sql,$language,$currency,$message;
  $bn=$message->text('buy_now');
  $bestSellers="";
  ob_start();
  $sql->query("select points,games.id,date_$language,cover,name_$language,price_$currency,(select percent from super_savings where gameID=games.id),(select active from super_savings where gameID=games.id) from games,best_sales where cover!='' and best_sales.gameID=games.id order by games.id DESC limit 5");
  while($sql->get_row()) {
    list($points,$preSale_id,$preSale_date,$preSale_cover,$preSale_name,$preSale_price,$save,$ss_enabled)=Array($sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col());
    $pathname='Games';
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
  return $bestSellers=ob_get_clean();
}
?>