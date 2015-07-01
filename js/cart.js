$(document).ready(function() {
  $('body').append("<div class='alert'></div>");
  $('.alert').css({position: 'absolute', display: 'none', zIndex: "501", textAlign: 'center'});
  $(window).scroll(function(){ tops=$(window).scrollTop(); $('.alert').css('top', tops); $('div.cart').css('top', Number(tops)+10);});
  checkCart();
});

function updateBuyNow(preel) {
  if(!preel) { preel=''; }
  $('.nodec').unbind('click');
  $(preel+'.nodec').click(function(){
    ID=$(this).attr('id').split("_");
    ID=ID[1];
    $.post('/cart?insert','id='+ID,function(data){
      res=$.parseJSON(data);
      inform(res.result,res.success);
      if($("div.cart").length!=1) {
        checkCart();
      }
    });  
  });
}

function checkCart() {
  $.post('/cart?count','',function(data){
    res=$.parseJSON(data);
    cart(res);
  });
}

function cart(json) {
  if(json.hasItems>0) {
    r=$(window).width()/2-550;
    $('body').append("<div class='round-corners cart' style='z-index:99;display:none;cursor:pointer;position:absolute;right:"+r+"px;top:10px;background:black;opacity:.8;'><img src='images/shopping_cart.png' /></div>");
    $('.cart').css({top: Number($(window).scrollTop())+10});
    $('.cart').fadeToggle();
    $('.cart').click(function(){
      $.post('/cart?html','',function(data){
        res=$.parseJSON(data);
        if($(".modal-win").length>0) {
          redrawPop(res.html,false);
        } else {
          pop(res.html,false);
        }
        updateC();
      });
    });
    if(document.location.hash=='#cart') {
      $('.cart').click();
    }
  }
}



function updateC() {
  $('#doLogin').click(function(){ setTimeout(showLogin,500); });
  $('#doRegister').click(function(){ setTimeout(showRegister,500); });
  $('#buyIt').click(function(){
    if($('[name^=accepted]').is(':checked')) {
        $.post("/cart?finishOrder",$('#buyForm').serialize(),function(data) {
          res=$.parseJSON(data);
          if(res.result==false) {
            inform(res.message,res.result);
          } else {
            redrawPop(res.message);
            $('.cart').fadeToggle('300',function(){$('.cart').remove();});
          }          
        });
    } else {
      inform("You must accept terms and conditions");
    }
  });  
  $('.changeCount').click(function(){
    remove=true;
    if($(this).hasClass('plus')) { 
      act='plus'; 
    } else if($(this).hasClass('minus')) { 
      act='minus'; 
    } else if($(this).hasClass('remove')) { 
      act='remove'; 
    }
    $.post('/cart?'+act, 'id='+$(this).attr('product-id'), function(data){
      res=$.parseJSON(data);
      if(res.success==true) {
        if(res.count=='remove') {
          removeItem(res.id);
        }
        tp=0;
        $('#count_'+res.id).html(res.count);
        single=res.count*$('#ppu_'+res.id).html();
        $('#p_'+res.id).html(single.toFixed(2));
        $('[id^=p_]').each(function(){
          tp+=Number($(this).html());
          remove=false;
        });
        if(!remove) {
          $('#tp').html(tp.toFixed(2));
        } else {
          $('.close-modal').click();
          $('.cart').fadeToggle(function(){ $(this).remove();});
        }
      } else {
        inform(res.result,false);
      }
    });
  });
}

function removeItem(id) {
  $('#line-'+id).remove();
}

$(window).resize(function(){r=$(window).width()/2-550; $('.cart').css("right",r);});
