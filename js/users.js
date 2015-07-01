$(document).ready(function(){
  $.post('userProfile?loadMyProfile','',function(data){
    $('#profile').html(data);
    updateClicks();
  });
  $('.invoiceInfo').click(function(){
    $.post("userProfile?loadGames", "invoice="+$(this).attr('href'), function(data) {
      res=$.parseJSON(data);
      $('#'+res.id).html(res.result);
      formClick();
    });
  });
});

$('.closeWin').click(function() {
  parent.top.$('.modal-win').fadeOut(400, function() {
    parent.top.toggleCurtains();
    $(this).remove();
  });
});

function updateClicks() {
  $('#rstme').click(function(){
    nstate=($(this).is(":checked") ? "1" : "0");
    $.post("userProfile?changeState", "state="+nstate, function(data){
      res=$.parseJSON(data);
      inform(res.message,res.success);
    });
  });
  $('.pwch').click(function(){
    $.post('userProfile?changePW', $('#profileForm').serialize(), function(data) {
      res=$.parseJSON(data);
      inform(res.infoText,res.action);
    });
    return false;
  });
}

function formClick() {
  $('.keyz').click(function(){
    showKey($(this).children().attr('src'));
  });
  $('.btn-ok').click(function(){
    $.post('/cart?unlock', $(this).closest('form').serialize(), function(data) {
      res=$.parseJSON(data);
      if(res.success==true) {
        if(res.result=='redraw') {
          redrawPop(res.message);
        } else {
          inform(res.result,true);
          $('a[href^=#'+res.id+"]").click();
        }
      } else {
        inform(res.result,false);
      }
    });
    return false;
  });
}

function showKey(html) {
  console.log($('.data').width());
  var mw=$('.data').width()-200;
  var mh=$('.data').height()-200;
  $('.data').prepend("<div style='position:absolute;width:"+$('.data').width()+"px;height:"+$('.data').height()+"px;background: rgba(0,0,0,0.3);z-index:100000;vertical-align:middle;text-align:center;' class='kcur'><div style='margin-right:100px;' class='btn btn-danger btn-mini pull-right kcur'>Close</div><div style='clear:both;'></div><br /><br /><img src='"+html+"' style='max-width:"+mw+"px;max-height:"+mh+"px;' /></div>");
  $('.kcur').click(function(){ $(this).remove();});
  return false;
}