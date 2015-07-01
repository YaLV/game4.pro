// image position (slider/add)
$(document).ready(function(){
  var regex = new RegExp("slider/edit");
  var clicked=false;
  var elTop;
  var elLeft;
  if(document.URL.match(regex)) {
    var maxtop=$('#imy').val();
    var maxleft=$('#imx').val();
    var elposleft;
    var elpostop;
    $('#draggable_div').mousemove(
      function(e) {
        clickTop=Number(e.pageY)-Number($(this).offset().top);
      	clickLeft=Number(e.pageX)-Number($(this).offset().left);
        if(clicked) {
          elpostop=clickTop-elTop;
          elposleft=clickLeft-elLeft;
          if(elpostop>=0) { elpostop=0; }
          if(elposleft>=0) { elposleft=0; }
          if(elpostop<=maxtop) { elpostop=maxtop; }
          if(elposleft<=maxleft) { elposleft=maxleft; }
          $("#source_image").css("background-position",elposleft+"px "+elpostop+"px");
          $('#coordinates').val(Math.abs(elposleft)+","+Math.abs(elpostop));
        }
      }
    );
    $('#draggable_div').click(function(e){ 
      pos=$("#source_image").css('background-position');
      poz=pos.split(' ');
      x=parseInt(poz[0]);
      y=parseInt(poz[1]);
      elTop=Number(e.pageY)-Number($(this).offset().top)-y;
      elLeft=Number(e.pageX)-Number($(this).offset().left)-x;
      maxtop=$('#imy').val();
      maxleft=$('#imx').val();
      clicked=(clicked==true ? false : true);
    });  
    //$('#draggable_div').mouseup(function(e){ clicked=false;});  
  }
});

// slider badge/link buttons (slider/add) 
$('.st').click(function(){
    $(this).toggleClass("btn-success btn-warning active");
    $('.'+$(this).attr('id')+'_div').toggle();
    if($(this).hasClass('active')) {
    	$('[name^='+$(this).attr('id')+']').val('1');
    } else {
    	$('[name^='+$(this).attr('id')+']').val('0');
    }
    return false;
});

// link doubler (slider/add)
$('[name^=link]').keyup(function(){
    $('.double').val($(this).val());
});

// slider place (slider/add)
$('.sizeSel').click(function() {
    $('.sizeSel').removeClass('active');
    $('.sizeSel').addClass('btn-inverse');
    $('.sizeSel').removeClass('btn-success');
    $(this).addClass('active');
    $(this).addClass('btn-success');
    $(this).removeClass('btn-inverse');
    active_id=$('.sizeSel.active').attr('id');
    if(active_id!='slider') {
    	$('#source_image').css({height:'200px',width:'900px;'});
    	$('#imy').val($('#imy').val()-278);
    	$('#badge').hide();
    	if($('#badge').hasClass('active')) {
    	    $('.badge_div').hide();
    	}
    } else {
    	$('#source_image').css({height:'478px',width:'900px;'});
    	$('#imy').val(Number($('#imy').val())+278);
      p=$('#coordinates').val().split(",");
      if(p[1]) {
        maxLine=0-278-Number(p[1]);
        lft=p[0];
      } else {
        maxLine=0;
        lft=0;
      }
      maxtop=$('#imy').val();
      if(maxLine<maxtop) {
        $("#source_image").css("background-position",lft+"px "+maxtop+"px");
        $('#coordinates').val(Math.abs(lft)+","+Math.abs(maxtop));
      }
    	$('#badge').show();
    	if($('#badge').hasClass('active')) {
    	    $('.badge_div').show();
    	}
    }
    $('[name^=position]').val($('.sizeSel.active').attr('id'));
    return false;
});

// slider save (slider/add)
$('.slideAdd').click(function(){
  $.post("slider/action/save", $("#sliderForm").serialize(), function (data) {
    console.log(data);
    res=$.parseJSON(data);
    if(res.success==true) {
      pop(res.result,document.location.href='slider/list');
    }
  });
  return false;
});

// game point retriever  (slider/add)
$('[name^=link]').change(function(){
  re=new RegExp("viewGame/([0-9]+)",'i');
  gameId=$(this).val().match(re);
  if(gameId[1]) {
    $.post("slider/getPoints",'id='+gameId[1],function(pnts){ $('#gamePoints').html(pnts); $('[name^=pnts]').val(pnts); })
  }
});

// image shower (slider/list)
$('.viewImage').bind('mouseover mouseout click',function() {
  id=$(this).attr('id');
  $("#img-"+id).css({top: $('#lnk-'+id).offset().top, left: $('#lnk-'+id).offset().left});
  $("#img-"+id).toggle();
  return false;
});

// slider on/off (slider/list)
$('.activateSlider').click(function() {
  $.post('slider/activate',"id="+$(this).attr('id'),function(data) {
    res=$.parseJSON(data);
    if(res.success==true) {
      $('#'+res.id).toggleClass("active");
      replaceButtonText(res.id,res.response);
      pop(res.result,false);
    }
  });
  return false;
});

// slider link changer (slider/list)
var oldtext = new Array();
$('[id^=editSlider]').click(function(){
  id=$(this).attr('id').split("-");
  $(this).toggle();
  oldtext[id[1]]=$('#lnk-text-'+id[1]).html();
  editForm="<form method='post' id='edit-"+id[1]+"'><input type='text' name='link' value='"+oldtext[id[1]]+"'/><input type='hidden' name='id' value='"+id[1]+"' /><button class='btn btn-success btn-savelink btn-mini'>Save</button><button class='btn btn-danger btn-cancel btn-mini' id='"+id[1]+"'>Cancel</button></form>";
  $('#lnk-text-'+id[1]).html(editForm);
  $('[name^=link]').focus();
  $('.btn-savelink').click(function(){
    $.post('slider/action/save?saveField=link',$('#edit-'+$(this).prev().val()).serialize(), function(data) {
      result=$.parseJSON(data);
      if(result.success==true) {
        $('#lnk-text-'+result.id).html(result.response);
        $('#editSlider-'+result.id).toggle();
        pop(result.info,false)
      } else {
        pop(result.response,false)
      }
    });
    return false;
  });
  $('.btn-cancel').click(function(){
    el=$(this).parent().parent();
    $(this).parent().remove();
    id=$(this).attr('id');
    el.html(oldtext[id]);
    $('#editSlider-'+id).toggle();
    return false;
  });
});

// slider remover (slider/list)
$('.removeSlider').click(function(){
  if(confirm("Really Delete this slider ?")) {
    $.post("slider/action/save?saveField=remove","id="+$(this).attr('id'), function(data){
      res=$.parseJSON(data);
      if(res.success==true) {
        $('#lnk-'+res.id).parent().remove();
        pop("Slider/comercial removed",false);
      }
    });
  }
});

$('[id^=p]').click(function(){
  found=this.id.substr(2,this.id.length);
  $.post('slider/list?changeSel','item='+$(this).attr('data-id')+"&changeTo="+found,function(data){
    res=$.parseJSON(data);
    if(res.success==true) {
      replaceButtonText('pageSel-'+res.id,res.page);
    } else {
      pop("Problem changing place",false);
    }
  });
});