<?
$points=getUserBP();
$status['n']="Unpaid";
$status['y']="Paid";
$status['yc']="Paid, Claimed";
$sql->query("select invoice,price,currency,time,status from invoices where uid='$me' order by time DESC");
while($sql->get_row()) {
  $invoice['number']=$sql->get_col();
  $invoice['price']=$sql->get_col();
  $invoice['currency']=$sql->get_col();
  $invoice['time']=date("d/m/Y H:i",$sql->get_col());
  $invoice['status']=$status[$sql->get_col()];
  $invoices.="<li><a href='#{$invoice['number']}' class='invoiceInfo' invoice='{$invoice['number']}' data-toggle='tab'>{$invoice['time']} - {$invoice['number']} ({$invoice['price']} {$invoice['currency']})</a></li>";
  $invoices_info.="<div class='tab-pane' id='{$invoice['number']}'></div>";
}
?>
<div class="tabbable" style='background:#333;height:100%;'>
  <ul class="nav nav-tabs">
    <li class="active"><a href="#games" data-toggle="tab"><?=$message->text('my_games');?></a></li>
    <li><a href="#profile" data-toggle="tab"><?=$message->text('my_profile');?></a></li>
    <li class='infoText pull-right'><?=$message->text('your_bonus_points');?> <?=$points;?></li>
  </ul>
  <div class="tab-content" style='max-height:600px;'>
    <div class="tab-pane active" id="games">
      <div class="tabbable tabs-left">
        <ul class="nav nav-tabs invoiceList">
          <li class='active'><a href='#information' data-toggle='tab'><?=$message->text('information');?></a></li>
          <?=$invoices;?>
        </ul>
        <div class="tab-content contentList" style='overflow-y:visible;position:relative'>
          <div class='tab-pane active' id='information'>
          </div>
          <?=$invoices_info;?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="profile">
    </div>
  </div>
</div>
<script src="js/users.js"></script>