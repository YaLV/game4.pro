<?
if($fnd[1]!='') {
  $id=$fnd[1];
  $is=(get_reply("select count(*) from mail_send where gid='$id' and template='new_game'")>0 ? true : false);
  $dis=($is ? "disabled" : "");
  $dat=($is ? "active" : "");
  $nm=($is ? "Information sent" : "Send information");
  $sql->query("select price_eur,price_usd,price_gbp,points,images,cover,age,(select percent from super_savings where gameID=games.id),genre,name_en,name_ru,sysreq_en,sysreq_ru,date_en,date_ru,platform_en,platform_ru,languages_en,languages_ru,description_en,description_ru,youtube_video_1,youtube_video_2,youtube_video_3 from games where id='$id'");
  $sql->get_row();
  $eur=$sql->get_col();
  $usd=$sql->get_col();
  $gbp=$sql->get_col();
  $points=$sql->get_col();
  $images=$sql->get_col();
  $cover=$sql->get_col();
  $age=$sql->get_col();
  $ssv=$sql->get_col();
  $ss=($ssv>0 ? 1 : 0);
  $genres=$sql->get_col();
  $agerestr=($age>0 ? "Age $age+" : "No Age Restriction");
  if(stripos($genres,",")>0) {
    $genre=spliti(",",$genres);
  }
  $genrez="";
  foreach($genres_list as $genre_item) {
    if(is_array($genre)) {
      if(in_array(trim($genre_item),$genre)) {
        $genrez.="<button class='btn check btn-success active' id='select-type-$genre_item'>$genre_item</button>";
      } else {
        $genrez.="<button class='btn check btn-inverse' id='select-type-$genre_item'>$genre_item</button>";
      }
    } else {
      if($genre_item==$genres) {
        $genrez.="<button class='btn check btn-success active' id='select-type-$genre_item'>$genre_item</button>";
      } else {
        $genrez.="<button class='btn check btn-inverse' id='select-type-$genre_item'>$genre_item</button>";
      }
    }
  }
  if($show) {
    $genrez=preg_replace("/button/","div",$genrez);
    $genrez=preg_replace("/check/","",$genrez);
    $agez="<div class='btn btn-inverse'>$agerestr</div>";
  }
  $imglist="";
  foreach(spliti("\n",$images) as $k => $img) {
    if($img!='') {
    $cov=($img==$cover ? "Cover" : "ScreenShot");
    $cls=($img==$cover ? "success" : "info");
    $imglist.="
      <div style='float:left;width:200px;height:150px;margin-right:5px;' class='thumbnail container-$k'>
        <div id='data-uploaded-$k' style='margin:auto;width:180px;height:130px;'>
          <img src='/gameImages/$id/$img' title='$img' style='height:130px;'>
        </div>
        <div id='data-info-$k'>
          <input type='hidden' class='cover' name='cover[$k]' id='cover-$k' value='0'>
          <span class='label label-$cls label-$k' style='float:left;cursor:pointer;'>$cov</span>
          <input type='hidden' value='$img' name='saveImage[$k]' id='saveImage-$k' class='busy'>
          <a href='javascript:removeImage($k)' style='float:right;'>Remove</a>
        </div>
      </div>";
      if($show) {
        $imglist=preg_replace("/\<a.+a\>/","",$imglist);
      }
    }
  }
$idfield="<input type='hidden' id='thisId' name='gameId' value='$id' />";  
} else {
  $agerestr="No Age Restriction";
  foreach($genres_list as $genre_item) {
    $genrez.="<button class='btn check btn-inverse' id='select-type-$genre_item'>$genre_item</button>";
  }
  $nm="Send information";
}
