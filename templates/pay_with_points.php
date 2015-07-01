<div style='text-align:center;'>
  <br /><br />Invoice <?=$_GET['invoice'];?> sum in Bonus points is: <?=$points_for_Invoice;?><br />
  <form method='post' id='bpform'>
    <input type='hidden' name='invoice' value='<?=$_GET['invoice'];?>'>
    <br />
  <button class='btn btn-success buy_points'>Buy with bonus points</button>
  </form>
</div>
<script type='text/javascript'>
$(".buy_points").click(function() {
  $.post("/airpay?withBP",$('#bpform').serialize(),function(data) {
    res=$.parseJSON(data);
    if(res.success) {
      redrawPop(res.data);
    } else {
      inform(res.data,false);
    }
  });
  return false;
});
</script>