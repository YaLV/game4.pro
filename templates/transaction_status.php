<?
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//echo $_SESSION['paymentStatus'];
//if($_SESSION['paymentStatus']=='1') {
  ?>
  <div style='margin:5px;width:300px;margin-top:20px;background:rgba(150,150,150,.7);color:black;padding:20px;' class='round-corners'><?=$message->text('transaction_successful');?></div>
  <?
/*} else {
  
  <div style='margin:5px;width:300px;margin-top:20px;background:rgba(150,150,150,.7);color:black;padding:20px;' class='round-corners'><?=$message->text('transaction_cancelled');</div>
  
} */
