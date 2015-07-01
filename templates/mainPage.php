        <div class="clear"></div>
        <div id='flavor_1'>
        </div>
        <div id="badge_cs">
          <object data="/badge?direction=up&amp;text=<?=urlencode(mb_convert_case($message->text('coming_soon'),MB_CASE_UPPER, "UTF-8"));?>" type="image/svg+xml" style='width:200px;height:80px;cursor:pointer;overflow:hidden;'></object>
        </div>
        <div id="coming_soon_block" style='height:446px;'>
          <div style='position:absolute;width:100%;'><div class='more_btn'><a href='Preorder'><?=$message->text('direction_more');?></a></div></div>
          <ul>
            <?=$comingSoon;?>
          </ul>
        </div>
        <div id="badge_hot">
          <object data="/badge?direction=down&amp;text=<?=urlencode(mb_convert_case($message->text('best_seller'),MB_CASE_UPPER, "UTF-8"));?>" type="image/svg+xml" style='	height: 100px;width: 190px;owerflow;hidden;'></object>
        </div>
        <div id="hot_sales_block">
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