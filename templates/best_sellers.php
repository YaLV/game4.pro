<?
$preSale_name_t=urlencode($preSale_name);

echo "<li$h>
        <a href='$pathname?viewGame=$preSale_id'>
          <div class='product'>
            <div style='height:150px;margin:0px;padding:0px;'>
              $super_saving
              $punkts
              $oos
              <img src='gameImages/$preSale_id/$preSale_cover' alt='$preSale_name_t' style='width:100px;' />
            </div>
            <br />
          <h2>$preSale_name</h2>
          </div>
        </a>
        <div class='price_tag'>$preSale_price <span class='curency'>".strtoupper($currency)."</span></div>
        <a href='javascript:void(0);' class='nodec' id='bestSell_$preSale_id'><div class='order_button buynow'><div>$bn</div></div></a>
      </li>";
