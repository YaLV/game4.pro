<div class="clear"></div>
<?=$content_top;?>
</div>
<div id="<?=$section[$_GET['x']]['style'];?>">
  <?
  if($section[$_GET['x']]['style']=='badge_cs') {
    ?><object data="/badge?direction=up&text=<?=mb_convert_case($message->text('menu_'.strtolower($_GET['x'])),MB_CASE_UPPER, "UTF-8");?>" type="image/svg+xml" style='width:200px;height:80px;'></object><?
  } else {
    ?><object data="/badge?direction=down&text=<?=mb_convert_case($message->text('menu_'.strtolower($_GET['x'])),MB_CASE_UPPER, "UTF-8");?>" type="image/svg+xml" style='	height: 105px;width: 190px;'></object><?
  }
  ?>
</div>
<div id="check_itout_block">
  <div style='position:absolute;width:900px;z-index:100;'><div class='more_btn'><a class="page-next pages" id="page-nexts" href='javascript:;'><?=$message->text('direction_more');?></a></div></div>
	<div id="sidebar">
    <span class="slogan"><?=$message->text('gameType')?>:</span>
      <input type='hidden' id='filter_field_genre' value='<?=$_SESSION['filter_genre'];?>' />
      <input type='hidden' id='pageLoc' value='<?=$_GET['x'];?>' />
      <?=$genres;?>
     
    <br /><br /><span class="slogan" style='clear:left;'><?=$message->text('ageRating');?>:</span>
      <input type='hidden' id='filter_field_age' value='<?=$_SESSION['filter_age'];?>' />
      <?=$ages;?>     
     
    <br /><br /><span class="slogan"><?=$message->text('priceRange');?>:</span>
      <input type='hidden' id='filter_field_price' value='<?=$_SESSION['filter_price'];?>' />
      <?=$prices;?>
    
  </div>
  <div id="contents">
    <div class='pageSel' style='width:670px;text-align:center;margin-top:30px;'></div>
    <ul id='gameList'>
    </ul>
    <div class="clear"></div>
    <div class='pageSel' style='width:670px;text-align:center;margin-top:30px;'></div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear" style='padding-bottom:20px;'></div>
<div style="background: url(images/spacer_zoom.png); width:942px; height:77px;position:absolute;margin: -78px 0 0 -19px;"></div>
