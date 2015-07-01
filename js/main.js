var t;
var l;
var filters=Array();
var yp_playing=0;
var yp;
var video=new Array()
var updated;
var news = new Array();
var curNews=0;
var curPage=0;
var player=Array();
var totalVideos;
var vid_loaded=Array();
var curVid=-1;
var pageCount;
var curPage=1;
var changePage;
var loaded_hash;

$(document).ready(function(){
  var str = new RegExp(/Games|Preorder|SuperSave/);
  var masivs=document.location.href.toString().match(str);
  $('.accepting').bind("mouseover mouseout", function(e) {
    elem=$(this);
    if(e.originalEvent.type=='mouseover') {
      BGpos=elem.height();
    } else {
      BGpos=0;
    }
    elem.css("background-position","0 "+BGpos+"px");
  });
  if(masivs) {
    $('#search').keyup(function() {
      clearTimeout(sQueue);
      sQueue=setTimeout(function() {
        changePage=false;
        load_filter();
      },500);
    });
    $('#srcbtn').click(function(){ return false;});
  }
});

function createPageList() {
  showStart=false;
  showStop=false;
  lastShowed=false;
  if(pageCount>0) {
    items="<button class='btn btn-mini page-prev pages' id='page-prev'>&#171;</button>";
    for(x=1;x<=pageCount;x++) {
      if(pageCount>10) {
        if(x>1) {
          pageSpanStart=x+3;
          pageSpanStop=x-3
          if(pageSpanStart>=curPage && pageSpanStop<=curPage) {
            if(pageCount==x) { lastShowed=true; }
            items+="<button class='btn btn-mini page-"+x+" pages' id='page-"+x+"'>"+x+"</button>";
          } else if(pageSpanStart<curPage) {
            if(!showStart) {
              items+=".......";
              showStart=true;              
            }
          } else if(pageSpanStop>curPage) {
            if(!showStop) {
              items+=".......";
              showStop=true;              
            }
          }
          if(pageCount==x && lastShowed==false) {
            items+="<button class='btn btn-mini page-"+x+" pages' id='page-"+x+"'>"+x+"</button>";
          }
        } else {
          items+="<button class='btn btn-mini page-"+x+" pages' id='page-"+x+"'>"+x+"</button>";
        } 
      } else {
        items+="<button class='btn btn-mini page-"+x+" pages' id='page-"+x+"'>"+x+"</button>";
      }
    }                                     
    items+="<button class='btn btn-mini page-next pages' id='page-next'>&#187;</button>";
    return items;
  } else {
    return "";
  }
}

showPaymentStatus = function() {
  $.post("/airpay?getLastStatus&rand=bu7tcvuijy6yliv","",function(data) {
    if($(".modal-win").length>0) {
      redrawPop(data,false);
    } else {
      pop(data,false);
    }
  });
}


hideItems=false;           
           
$('[name^=option]').change(function(){
  filter=$(this).attr("name").split("_");
  curPage=1;
  changePage=false;
  rebuild_filter(filter[1]);
  hideItems=true;
  SHGames();
  //load_filter();
});

function rebuild_filter(filter) {
  f="";
  $('input[name^=option_'+filter+']:checked').each(function(){
    f+=$(this).val()+",";
  });
  f=f.substr(0,f.length-1);
  $("#filter_field_"+filter).val(f);
}

function load_filter() {
  params=createParamList();
  $.post('/getGames', "getGameList&"+params,
    function(data) {
      res=$.parseJSON(data);
      if(!changePage) {
        pageCount=res.pages;
        pageList=createPageList();
        $('.pageSel').html(pageList);
        changePage=false;
      }
      addPageClick();
      $("#gameList").html(res.data);
      $('#gameList a').click(function(){ document.location.href=document.location.pathname+'#page'+curPage; });
      hideItems=false;
      setTimeout(SHGames,500);
      updateBuyNow('');   
  });
}
var waits;

function SHGames() {
  seq=0;
  hi=(hideItems ? 0 : 1);
  $('#gameList li').each(function(){$(this).delay(seq*50).animate({opacity: hi},200); seq+=2;});
  if(hideItems) {
    setTimeout(load_filter,Number(waits)*70);
  }                                        
}

function addPageClick(filterit) {
  $(".pages").unbind('click');
  $(".pages").removeClass("disabled");
  $(".page-"+curPage).addClass("disabled");
  if(curPage==1) { $(".page-prev").addClass("disabled"); }
  if(curPage==pageCount) { $(".page-next").addClass("disabled"); }
  $(".pages").click(function() {
    if($(this).hasClass('disabled')) {
      return false;
    }
    pageId = $(this).attr('id').split("-");
    page = pageId[1];
    if(page=='prev') {
      curPage--;
    } else if(page=='next' || page=='nexts') {
      curPage++;
    } else {
      curPage=page;
    }
    changePage=true;
    hideItems=true;
    SHGames();
    //load_filter();
  });
}

function createParamList() {
  returnData=Array();
  x=0;
  $("[id^=filter_field]").each(function(){
    x++;
    type=$(this).attr('id').split("_");
    returnData[x]=type[2]+"="+$(this).val();
  });
  x++;
  returnData[x]='pageLoc='+$('#pageLoc').val();
  if(!changePage) {
    if(loaded_hash) {
      loaded_hash=false;
    } else {
      curPage=1;
    }
  }
  x++;
  returnData[x]="page="+curPage;
  x++;
  returnData[x]="searchString="+$('#search').val();
  return returnData.join("&");
}
var scroll;

function toggleCurtains() {
  $('#curtains').css('width',$(window).width());
  $('#curtains').css('height',$(document).height());
  $('#curtains').toggle();
}


var sQueue;
$(document).ready( function() {
  $('cite.help').css({display: "none"});
  $('a[title=help]').click(function(){
    pop($("cite.help").html(),false,{height: $(window).height()*0.8, overflowY: "scroll", position: "relative"},1);
    return false;
  });

  $('.click_back').click(function(){ history.go('-1');});
  $('.createAcc').click(function() {
    showRegister();
    return false;
  });
  $(window).scroll(function(){
    $('.modal-win').css('top',$(window).scrollTop()+t);
  });
  $('input[id^=filter_field]').each(function(){
    explod=$(this).val().split(',');
    for(x in explod) {
      $('input[value='+explod[x]+']').attr('checked','checked');
    };
  });
  if($('#pageLoc').length>0) { if(document.location.hash) { curPage=document.location.hash.substr(5,document.location.hash.length-1); loaded_hash=true; } load_filter(); }
  loadNews();
});

function showLogin() {
  $.post('/getTemplate/loginForm',function(data) {
    redrawPop(data);
    setTimeout(function() {
      $('#loginBtn').click(function(){ 
        doLogin('#popForm'); return false;
      });
      $('#popForm').submit(function() { doLogin('#popForm'); }); 
    },500);
  });
}

$('.forgotPass').click(function() {
  $.post('/getTemplate/remindForm',function(data) {
    pop(data,false);
    $('#remindBtn').click(function(){ doRemind(); return false;});  
  });
  return false;
});

function doRemind() {
  $.post('/recover',$('#remindForm').serialize(),function(data) {
    res=$.parseJSON(data);
    if(res.success==true) {
      redrawPop(res.message);
    } else {
      inform(res.message);
    }
  });
}

function lang(item) {
  $.get('/translation',"for="+item, function(data) { return data;})
}
  
function showRegister() {
  $.post('/getTemplate/registerForm',function(data) {
    if($('.modal-win').length>0) {
      redrawPop(data);
    } else {
      pop(data,false);
    }
    setTimeout(function() {
      $('#regBtn').click(function(){
        $.post('/do/register',$('#register').serialize(),function(res){
          data=$.parseJSON(res);
          if(data.success==true) {
            redrawPop(data.message);
          } else {
            $('#register input').each(function() {
              if(typeof data[$(this).attr('name')].status!='undefined' && data[$(this).attr('name')].status=='fail') {
                $(this).css("background","#FFBBBB");
                inform(data.message,false);
              } else {
                $(this).css("background","white");
              }
            });
          }        
        });
        return false;
      });},500);
  });
}

function showVerify() {
  link=document.location.href;
  linkDetails=link.split("/");
  string="u="+linkDetails[linkDetails.length-2]+"&c="+linkDetails[linkDetails.length-1];
  $.post('/do/checkVerify',string, function(res) {
      data=$.parseJSON(res);
      pop(data.response,data.action);
  });
}

function doLogin(formid) {
  if(!formid) { 
    formid='#loginform';
  }
  $.post('/do/login',$(formid).serialize(),function(data) {
    result=$.parseJSON(data);
    if(result.success==true) {
      if(formid=='#popForm') {
        $('#curtains').click();
        document.location.href=document.location.href+"/#cart"       
      } else {
        document.location.href=document.location.href;
      }  
    } else {
      inform(result.error,false);
    }
  });
}



$('#loginform').submit(function(){
  doLogin();
  return false;
});

$('.loginbutton').click(function() {
  doLogin();
  return false;
});

$.post("/carouselIms",'', function(resp) {
    data=$.parseJSON(resp);
    $("#flavor_1").agile_carousel({
        carousel_data: data,
        carousel_outer_height: 478,
        carousel_height: 478,
        slide_height: 478,
        carousel_outer_width: 900,
        slide_width: 900,
        transition_time: 300,
        timer: 5000,
        continuous_scrolling: true,
        control_set_1: "numbered_buttons",
        no_control_set: "hover_previous_button,hover_next_button"
    });
    $('#flavor_1').append("<div class='clickOverlay' style='cursor:pointer;position:absolute;top:0px;left:0px;background:url(/images/content_top.png) no-repeat;width:900px;height:528px;background-position:-139px -55px;overflow:hidden;'></div>");
    $(".clickOverlay").mouseover(
      function() {
      var overl=$(this);
        $('.slide').each(function(){
          if(parseInt($(this).css('top'))==0 && parseInt($(this).css('top'))==0) {
            item=$(this).children().children("a");
    	      if(item.attr('href')=='') {
    		      overl.css("cursor","default");
    	      } else {
    	        overl.css("cursor","pointer");
    	      }
          };
        });  
      }
    );
    $(".clickOverlay").click(
      function() {
        $('.slide').each(function(){
          if(parseInt($(this).css('top'))==0 && parseInt($(this).css('top'))==0) {
            item=$(this).children().children("a");
            if(item.attr('href')!='') {
              document.location.href=item.attr('href');
            } else {
              return false;
            }
          };
        });  
      }
    );                   
}); 

$(document).ready(function() {
    if($('.changeBS').length>0) {
      toggleLi();
    }
    links=$('.clickClass').attr('data-link');
    if(links!='') {
    	$('.clickClass').css("cursor","pointer");
    	$('.clickClass').click(function(){ document.location.href=links; });
    }
    updateBuyNow();
});

$('.propile').click(function(){
  $.get("userProfile","showFrame",function(data){
    	w=$(window).width()*0.9;
    	h=$(window).height()*0.9;
    	l=$(window).width()/2-w/2;
    	//t=$(window).height()/2-h/2;
      t=0;
      pop(data,false,{top: t, left: l, height: h, width: w, minHeight: "446px"});
    });
    return false;
});

function pop(text,close_action,css,bu) {
  if(!$('.modal-win').length>0) {
    button="<div class='top'></div><div style='position:absolute;width:100%;'><button class='btn btn-mini close-modal btn-danger pull-right' class='z-index:2000;'>X</button></div><table class='mt'><tr><td class='tl'></td><td class='t'><td><td class='tr'></td></tr><tr><td class='l'><div style='height:150px;'></div></td><td class='data' style='position:relative;'><div class='cntt' style='position:relative;'>"+text+"</div><td><td class='r'></td></tr><tr><td class='bl'></td><td class='b'><td><td class='br'></td></tr></table><div class='bottom'>"; 
    html="<div class='modal-win'>"
        +button
    +"</div>";
    $('body').append(html);
    $('.modal-win').fadeIn(300);
    if($('#plesejs').length>0) {
      $('#plesejs').css({height: $('.popplesejs').height()+40, width: $('.popplesejs').width()+20});
    }
    w=$('.modal-win').width();
    h=$('.modal-win').height();
    if(w>=900) {
      $('.modal-win').css("width","900");
      w=$('.modal-win').width();
      h=$('.modal-win').height();
    }
    if(css) {
      if(!bu) {
        $('.modal-win .data').attr("id","propile");
        $('#uframe').css({height: $('.modal-win').height()});
        $('.modal-win').css(css);
      } else {
        $('.cntt').css(css);
        $('.modal-win').css("width", $(window).width()*0.7);
        $('.cntt').css("width",$('.modal-win').width()-137);
        w=$('.modal-win').width();
        h=$('.modal-win').height();
        l=$(window).width()/2-w/2;
        t=$(window).height()/2-h/2;
        $('.modal-win').css({top: Number(t)+$(window).scrollTop(), left: l });
      }
    } else {
      t=$(window).height()/2-h/2;
      l=$(window).width()/2-w/2;
      $('.modal-win').css({top: Number(t)+$(window).scrollTop(), left: l });
    }
    toggleCurtains();
    $('.close-modal, #curtains').click(function(){
      $('.modal-win').fadeOut('300',function() {
        $(this).remove();
        toggleCurtains();
        eval(close_action);
      });
      return false;    
    });
  }
  return false;
}

function changeVideo() {
  player['myytplayer'].loadVideoById(video[yp_playing], 0, 'default');
  player['myytplayer'].stopVideo();
}

$('.yp_control').click(function(){
  if($(this).hasClass('yp_next')) {
    yp_playing++;
    if(video.length<=yp_playing) { yp_playing--; return false; }
  } else if($(this).hasClass('yp_prev')) {
    if(yp_playing==0) { return false;}
    yp_playing--;
  }
  changeVideo();
});

onYouTubePlayerReady = function(playerid) {
  player[playerid] = document.getElementById(playerid);
  if(playerid=='myytplayer') {
    $('#newsText').html(news[curNews].text);
    player['myytplayer'].addEventListener("onStateChange", "showNews");
  } 
}

showNews = function(newState) {
  $('#allNews').delay(400).animate({opacity:1},300);
}

function inform(text,type) {
  if(type==true) {
    alertType='success';
    lng=1000;
  } else {
    alertType='error';
    lng=3000;
  }
  $('.alert').html(text);
  $('.alert').addClass("alert-"+alertType);
  moveIn();  
  setTimeout(function() {$('.alert').slideUp(300); $('.alert').removeClass('alert-success alert-error');},lng);
}

function moveIn() {
  l=$(window).width()/2-$('.alert').width()/2;
  $('.alert').css("left",l);
  $('.alert').slideDown(300);
}

function loadNews() {
  $('#allNews').css({opacity:0});
  $.post('loadNews','',function(data){
    news=$.parseJSON(data);
    var params = { allowScriptAccess: 'always', wmode: 'transparent', allowFullScreen: "true" };
    var atts = { id: 'myytplayer' };
    swfobject.embedSWF('http://www.youtube.com/v/'+news[curNews].video+'?version=3&rel=0&enablejsapi=1&playerapiid=myytplayer',
           'newsVid', '360', '200', '9.0.0', null, null, params, atts);
  }); 
  $('.newsChange').click(function(){
    if($(this).attr('id')=='next') {
      curNews++;
    } else {
      curNews--;
    }
    if(curNews>=news.length) {
      curNews=0;
    }
    if(curNews<0) {
      curNews=news.length-1;
    }
    drawNews();
  });
}

function drawNews() {
  $('#allNews').animate({opacity: 0},300,function(){
    $('#newsText').html(news[curNews].text);
    player['myytplayer'].loadVideoById(news[curNews].video, 0, 'default');
    player['myytplayer'].stopVideo();
  });
}

function redrawPop(message) {
  $('.modal-win').fadeToggle("300",function() {
    $('.modal-win').removeAttr("style");
    $('.modal-win .data').attr('id',"");
    $('.modal-win .data').html(message);
    $('.modal-win').fadeToggle("300");
    w=$('.modal-win').width();
    h=$('.modal-win').height();
    if($('#plesejs').length>0) {
      $('#plesejs').css({height: $('.popplesejs').height()+40, width: $('.popplesejs').width()+20});
    }
    if(w>=900) {
      $('.modal-win').css("width","900");
      w=$('.modal-win').width();
      h=$('.modal-win').height();
    }
    t=$(window).height()/2-h/2;
    l=$(window).width()/2-w/2;
    //$('.modal-win table').css("height",h);    
    $('.modal-win').css({top: Number(t)+$(window).scrollTop(), left: l });
  });
}

function loadBS() {
  $.get('/getBS',"from="+curPage, function(data) {
    res = $.parseJSON(data);
    changeGames(res);
  });
}

function changeGames(gm) {
  elementSec=0;
  $('#hot_sales_block ul li').each(function(seq){
    $(this).html(gm[seq]);
  });
  $('#hot_sales_block ul li').each(function(seq) { $(this).delay(seq*100).animate({opacity: 1},300); });
  setTimeout("updateBuyNow()",300);
}

$('.changeBS').click(function() {
  if($(this).hasClass('next-items')) {
    curPage++;
  } else {
    curPage--;
  }
  toggleLi(true);
  return false;
});

$('.changeV').click(function() {
  if($(this).hasClass('next-items')) {
    if(curVid==vid_loaded.length-1) {
      return false;
    } else {
      curVid++;
    }
  } else {
    if(curVid==0) {
      return false;
    }
    curVid--;
  }
  checkArrows();
  loadNextVid();
  return false;
});

function checkArrows() {
  if(curVid==0) {
    $('#vids .prev-items').hide();
    $('#vids .prev-items').next().hide();
  } else {
    $('#vids .prev-items').show();
    $('#vids .prev-items').next().show();
  }
  if(curVid==vid_loaded.length-1) {
    $('#vids .next-items').hide();    
    $('#vids .next-items').next().hide();    
  } else {
    $('#vids .next-items').show();  
    $('#vids .next-items').next().show();    
  }
}

function toggleLi() {
  $('#hot_sales_block ul li').each(function(seq) { $(this).delay(seq*100).animate({opacity: 0},300) });
  setTimeout("loadBS()",500);
}

function showVid() {
    vidid=vid_loaded[curVid];
    checkArrows();
    var params = { allowScriptAccess: 'always', wmode: 'transparent', allowFullScreen: "true" };
    var atts = { id: 'trailers' };
    if(vidid) {
      swfobject.embedSWF('http://www.youtube.com/v/'+vidid+'?version=3&rel=0&enablejsapi=1&playerapiid=trailers',
             'graph_contents', '585', '329', '9.0.0', null, null, params, atts);
    }
}

loadNextVid = function() {
  player['trailers'].loadVideoById(vid_loaded[curVid], 0, 'default');
}

var big_clicked=false;

$(document).ready(function() {
  $('#big_im').click(function(){
    CB_AllowedToRun='on';
    big_clicked=true;
    $("a[data-id="+$(this).attr('data-from')+"]").click();
    return false;
  });
  if($("#vidz").length>0) {
    vids=$("#vidz").val();
    vid_loaded=vids.split(",");
    curVid=0;
    showVid();
  }
  
  $('.thumbs_div a').click(function() {
    newIm=$(this).attr('href');
    dataid=$(this).attr('data-id');
    if(!big_clicked) { 
      $('#big_im').animate({opacity: 0},200, function() {
        $("#big_im").attr("href", newIm);
        $("#big_im").attr("data-from", dataid);
        $("#big_im").css({background: "url("+newIm+") no-repeat center top", backgroundSize: "573px"});
        $('#big_im').animate({opacity:1},200);
      });
    } else {
      big_clicked=false;
    }  
    return false
  });
});

function CB_ExternalFunctionCBClose() {
  CB_AllowedToRun='off';
}