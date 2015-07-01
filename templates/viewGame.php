<div class="clear"></div>
<?=$content_top;?>
</div>
<div id="fb-root"></div>
    <div id="badge_cs">
      <a style='position:absolute;width:197px;height:74px;z-index:101;display:block;' class='click_back'></a>
      <object data="/badge?direction=up&text=<?=mb_convert_case($message->text('direction_back'),MB_CASE_UPPER, "UTF-8");?>" type="image/svg+xml" style='width:200px;height:80px;cursor:pointer;'></object>
    </div>
    <div style='position:absolute;width:900px;z-index:90;margin-top:-5px;'><div class='more_btn'><a href='AboutUs'><?=$message->text('direction_rules');?></a></div></div>
    <div id='check_itout_block'>
      <div style="width: 820px;padding: 20px 40px 0px 40px;">
        <h2 style="font-size: 22px;margin: 14px 0 14px 0;color:#5A5A5A;"><?=$name;?></h2>
        <div style='width:640px;height:430px;float:right;'>
          <ul class="nav nav-tabs" id='viewGame'>
            <li class="active">
              <a href="#desc" data-toggle='tab'><?=$message->text('description');?></a>
            </li>
            <li>
              <a href="#ss" data-toggle='tab'><?=$message->text('screenshots');?></a>
            </li>
            <li>
              <a href="#vids" data-toggle='tab'><?=$message->text('videos');?></a>
            </li>
          </ul>
            <div class="tab-content vg round-corners-5">
              <div class="tab-pane active description" style='overflow-y:auto;height:540px;' id="desc">
                <?=$sysreq;?>
                <br />
                <strong>Release Date:</strong> <?=$rdate;?>
                <br /><br />
                <?=htmlspecialchars_decode($description);?>
              </div>
              <div class="tab-pane" id="vids" style='margin-left:30px;'>
                <div style='position:absolute;margin:130px 0 0 -30px;cursor:pointer;'>
                  <div style='width:22px;height:42px;cursor:pointer;position:absolute;' class='changeV prev-items'>
                  </div>
                  <object data="/arrow?path=b" type="image/svg+xml" style='height: 42px;width: 22px;'></object>
                </div>
                <div style='position:absolute;margin:130px 0 0 583px;cursor:pointer;'>
                  <div style='width:22px;height:42px;cursor:pointer;position:absolute;' class='changeV next-items'>
                  </div>
                  <object data="/arrow?path=f" type="image/svg+xml" style='height: 42px;width: 22px;'></object>
                </div>

                <?=$vthumbs;?>
              </div>
              <div class="tab-pane" id="ss" style='margin-left:30px;'>
                <?=$ithumbs;?>
              </div>
            </div>
          </div>
    	  <div style="width:180px;float:left;">
          <?=$super_saving;?>
          <?=$punkts;?>
          <?=$oos;?>               
          <img src="<?=$cover;?>" alt="" style="width: 150px;" />
          <?=$op;?>
          <div class="price_tag" style="margin-left: -5px;"><?=$price;?> <span class="curency"><?=strtoupper($currency);?></span></div>
          <a href='javascript:void(0);' class='nodec' id='vg_<?=$id;?>'><div class='order_button buynow' product-id='<?=$id;?>' style="margin-left: 3px;"><div style='margin-left:30px;margin-top:10px;'><?=$bs;?></div></div></a>
          <br /><br />
          <!-- AddThis Button BEGIN -->
          <div class="addthis_toolbox addthis_default_style ">
          <a class="addthis_button_facebook_like" style='min-width:180px;' fb:like:layout="button_count"></a>
          <div class="clear"></div>
          <a class="addthis_button_tweet"></a>
          <div class="clear"></div><br />
          <a class="addthis_button_pinterest_pinit"></a>
          <div class="clear"></div><br />
          <a class="addthis_counter addthis_pill_style"></a>
          </div>
          <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=xa-508f8fcc1bb2cd76"></script>
          <!-- AddThis Button END -->
        </div>
      	<div class="clear"></div>
        <div class='oldprice'><?=$message->text($stock);?></div>        
      </div>
    </div>
    <div class="clear"></div>
    <div id="hot_sales_block">
      <div id="badge_hot" style='margin-top:22px;margin-left:-16px;'>
        <object data="/badge?direction=down&amp;text=<?=urlencode(mb_convert_case($message->text('best_seller'),MB_CASE_UPPER, "UTF-8"));?>" type="image/svg+xml" style='	height: 100px;width: 190px;owerflow;hidden;'></object>
      </div>
      <div style='position:absolute;margin:130px 0 0 -30px;cursor:pointer;'>
        <div style='width:22px;height:42px;cursor:pointer;position:absolute;' class='changeBS prev-items'>
        </div>
        <object data="/arrow?path=b" type="image/svg+xml" style='height: 42px;width: 22px;'></object>
      </div>
      <div style='position:absolute;margin:130px 0 0 945px;cursor:pointer;'>
        <div style='width:22px;height:42px;cursor:pointer;position:absolute;' class='changeBS next-items'>
        </div>
        <object data="/arrow?path=f" type="image/svg+xml" style='height: 42px;width: 22px;'></object>
      </div>
      <ul style='margin-left:20px;'>
        <?=$bestSellers;?>
      </ul>
    </div>
    <script src="/js/clearbox.js" type="text/javascript"></script>
