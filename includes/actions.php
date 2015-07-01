<?
if($_GET['x']=='getGames') {
  //echo "a";
  $bn=$message->text('buy_now');
  $gameList="";
  $searchString=($_POST['searchString']!='' ? " and name_$language like '%{$_POST['searchString']}%'": "");
  if(!$_POST['searchString']) {
    $section['Games']['timeQuery']="and unix_timestamp(concat_ws('-',substring(date_$language,7,4),substring(date_$language,4,2),substring(date_$language, 1,2)))<='".time()."'";
    $section['Preorder']['timeQuery']="and unix_timestamp(concat_ws('-',substring(date_$language,7,4),substring(date_$language,4,2),substring(date_$language, 1,2)))>'".time()."'";
    $section['SuperSave']['timeQuery']="and (select active from super_savings where gameID=games.id)=1";
    $genre_filter=createFilters('genre');
    $age_filter=createFilters('age');
    $price_filter=createFilters('price');
  }
  $cp=$_POST['page']*6-6;
  ob_start(); 
  $sql->query("select points,id,date_$language,cover,name_$language,price_$currency,(select percent from super_savings where gameID=games.id),(select active from super_savings where gameID=games.id),(select count(*) from codes where gid=games.id) from games where cover!='' and active='y' $genre_filter $age_filter $price_filter $searchString {$section[$_POST['pageLoc']]['timeQuery']} order by id DESC limit $cp,6");
  while($sql->get_row()) {
    list($points,$preSale_id,$preSale_date,$preSale_cover,$preSale_name,$preSale_price,$save,$ss_enabled,$cnt)=Array($sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col());
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
    $imSize=getimagesize(getcwd()."/gameImages/$preSale_id/$preSale_cover");
    $posX=100-66;
    $posY=($imSize[1]/$imSize[0])*100-41;
    $pos = $posY."px 0px 0px ".$posX."px";
    $oos=($cnt>0 ? "" : "<div class='soos' style='margin: $pos;position:absolute;background:url(/images/out_of_stock.png) no-repeat;background-size:100px 60px;width:100px;height:60px;'></div>");
    $pathname=$_POST['pageLoc'];
    $h=" style='opacity:0;'";
    include getcwd()."/templates/best_sellers.php";
  }
  $returnData=ob_get_clean();
  //$returnData="select points,id,date_$language,cover,name_$language,price_$currency,(select percent from super_savings where gameID=games.id),(select active from super_savings where gameID=games.id) from games where cover!='' $genre_filter $age_filter $price_filter and {$section[$_POST['pageLoc']]['timeQuery']} order by id DESC limit $cp,9";
  echo json_encode(Array("data" => $returnData, "pages" => ceil(get_reply("select count(*) from games where cover!='' and active='y' $genre_filter $age_filter $price_filter $searchString {$section[$_POST['pageLoc']]['timeQuery']}")/6)));
  exit();  
}

function createFilters($type) {
  global $currency;
  $_SESSION['filter_'.$type]=$_POST[$type];
  if(strpos($_POST[$type],",")) {
    $items = spliti(",",$_POST[$type]);
    foreach($items as $val) {
      if($type=='price') {
        list($p1,$p2)=split("-",$val,2);
        $p1=number_format($p1/100,2);
        $p2=Number_format($p2/100,2);
        
        $filterz[]="(price_$currency>=$p1 and price_$currency<=$p2)";
      } else {
        if($type=='genre') {
          $filterz[]="$type LIKE '%$val%'";
        } else {
          $filterz[]="$type = '$val'";
        }
      }    
    }
    $filter="and (".implode($filterz," or ").")";
  } else {
    if($_POST[$type]!="") {
      if($type=='price') {
        list($p1,$p2)=split("-",$_POST[$type],2);
        $p1=number_format($p1/100,2);
        $p2=Number_format($p2/100,2);
        $filter="and (price_$currency>=$p1 and price_$currency<=$p2)";
      } else {
        if($type=='genre') {
          $filter="and $type LIKE '%{$_POST[$type]}%'";
        } else {
          $filter="and $type = '{$_POST[$type]}'";
        }
      }
    }
  }
  return $filter;
}

if($_GET['x']=='getBS') {
  $gameList="";
  $gameCount=get_reply("select count(*) from games,best_sales where cover!='' and active='y' and best_sales.gameID=games.id");
  $from=$_GET['from']*5;
  $neg=($from>0 ? false : true);
  $from=abs($from);
  $rounds=floor(abs($from)/$gameCount);
  if($rounds>=1) {
    while($rounds!=0) {
      $from-=$gameCount;
      $rounds--;
    }
  }
  $from=($neg ? -$from : $from);
  $current_item=0;
  while($current_item!=5) {
    $from_real=$from+$current_item;
    if($from_real<0) {
      $from_real=$gameCount+$from_real;
    } elseif($from_real>=$gameCount) {
      $from_real-=$gameCount;
    }
    $result[]=getGM($from_real,1);
    $current_item++;
  }
  echo json_encode($result);
  exit();  
}

function getGM($from,$limit) {
  global $sql,$language,$currency,$message;
  $bn=$message->text('buy_now');
  $sql->query("select points,games.id,date_$language,cover,name_$language,price_$currency,(select percent from super_savings where gameID=games.id),(select active from super_savings where gameID=games.id),(select count(*) from codes where gid=games.id) from games,best_sales where cover!='' and active='y' and best_sales.gameID=games.id order by games.id DESC limit $from,$limit");
  $sql->get_row();
  list($points,$preSale_id,$preSale_date,$preSale_cover,$preSale_name,$preSale_price,$save,$ss_enabled,$cnt)=Array($sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col());
  if($preSale_id) {
    if($ss_enabled) {
      $super_saving="<div style='position:absolute;background:url(images/price_tag.png);width:80px;height:80px;text-align:center;'><div style='margin-top:30px;color:white;font-weight:bold;font-size:20px;' class='supersave'>-$save%</div></div>";
    }
    if($points) {
      $punkts="<div style='position:absolute;background:url(images/badge_points.png);width:60px;height:38px;text-align:left;margin-top:90px;margin-left:25px;'><div style='margin:0px;padding:0px;margin-top:5px;color:white;font-weight:bold;'><div style='margin-top:-5px;padding:0px;font-size:10px;margin-left:5px;'>Awards</div><div style='padding:0px;margin-top:-2px;font-size:12px;margin-left:7px;float:left;'>$points</div><div style='font-size:10px;margin-left:3px;float:left;'>pts</div></div></div>";
    } else {
      $punkts="";
    }
    $imSize=getimagesize(getcwd()."/gameImages/$preSale_id/$preSale_cover");
    $posX=100-66;
    if(is_array($imSize)) {
      $posY=($imSize[1]/$imSize[0])*100-41;
    }
    $pos = $posY."px 0px 0px ".$posX."px";
    $oos=($cnt>0 ? "" : "<div class='oos' style='margin: $pos;position:absolute;background:url(/images/out_of_stock.png) no-repeat;background-size:100px 60px;width:100px;height:60px;'></div>");
    $pathname="Games";
    ob_start();
    include getcwd()."/templates/best_sellers.php";
    $r=Array("/\<li\>/","/\<\/li\>/");
    $ret=preg_replace($r,Array("",""),ob_get_clean());
    return $ret;
  } else {
    return "";
  }
}