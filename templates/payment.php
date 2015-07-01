<div id='pd'>
  <a href='/airpay?invoice=<?=$invoice;?>&payWith=paypal' class='paypal popupwindow' rel='payment'></a>
  <a href='/airpay?invoice=<?=$invoice;?>&payWith=cc_transpro' class='visa popupwindow' rel='payment'></a><br />
  <a href='/airpay?invoice=<?=$invoice;?>&payWith=moneybookers' class='moneybookers popupwindow' rel='payment'></a>
  <a href='/airpay?invoice=<?=$invoice;?>&payWith=bp' class='bp'></a><br />
</div>
<script src='/js/jquery.popupwindow.js' type='text/javascript'></script>
<script type='text/javascript'>
  var profiles = {
    payment: 
    {
      width:800,
      height:700,
      onUnload: showPaymentStatus,
      center:1
    }
  }
  $(function()
  {
  		$(".popupwindow").popupwindow(profiles);
  });
  $('#pd').height($(".data").height()-10);
  $('.bp').click(function() {
    $.post($(this).attr('href'),"",function(data){
      redrawPop(data,false);
      return false;
    });
    return false;
  });        

</script>