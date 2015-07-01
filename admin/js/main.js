// Various variables
var keys;
var age_filter="";
var genre_filter="";
var price_filter="";
var y=0;
var upload=[];
var uploadItem=0;
var interval;
var curPage=1;
var pageCount=0;
var totalItems=0;
var pages;
var full_list;
var temp_list;
var t;


$("body").bind("keydown", function(e) {
  if(e.ctrlKey) {
    $('#RM').toggle();
  }
});


// common (page selector)
function createPages() {
  pageCount=Math.ceil(totalItems/20);
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

// common (click for page select)
function addClick(filterit) {
  $("[class^=page-").removeClass("disabled");
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
      page=Number(curPage)-1;
    } else if(page=='next') {
      page=Number(curPage)+1;
    }
    curPage=page;
    page=(page*20)-20;
    if(filterit=='games') {
      search_filter($("#searchField").val(),page);
    } else if(filterit=='news'){
      createNewsTable(page);      
    } else if(filterit=='users') {
      createUserList(page,temp_list);
    } else if(filterit=='preorder') {
      createPreorderList(page,temp_list);
    } else if(filterit=='msgs') {
      loadMSGList(page);
    }
  });
}

$(document).ready(function(){
  // common (slider upload)
  $('.addSlider').click(function() {
    $('#sliderUpload').click();
	  return false;
  });
  // curtains x/y
  h=$(document).height();
  w=$(window).width();
  $('.curtains').css({ height: h, width: w });
});

// common (slider upload)
$('#sliderUpload').change(function(){
  if(this.files.length==1) {
    $('#upload').submit();
  }  
});

// change Filter button text (common)
function replaceButtonText(buttonId, text)
{
  if (document.getElementById)
  {
    var button=document.getElementById(buttonId);
    if (button)
    {
      if (button.childNodes[0])
      {
        button.childNodes[0].nodeValue=text;
      }
      else if (button.value)
      {
        button.value=text;
      }
      else
      {
        button.innerHTML=text;
      }
    }
  }
}

// Check for youtube link input (common)
$('.youtube').change(
  function() {
    if($(this).val().match(/youtube\.com/i)) {
      video=$(this).val().match(/v=([^&]{5,15})/i);
      if(!video) {
        video=$(this).val().match(/v=([^&]{11})/i);
      }
      if(video) {
        vid=$(this).attr('id').match(/youtube-(\d)/i);
        $('#youtube-video-'+vid[1]).val(video[1]);
        startShowing(video[1],vid[1]);        
      } else {
        $(this).val("Video Id Not Found");
      }
    } else {
      $(this).val("This Is Not A Youtube Video");    
    } 
}); 

// show image, title of video (common)
function startShowing(id,elem) {
  $.get('http://gdata.youtube.com/feeds/api/videos/'+id+'?v=2&alt=jsonc',function (data) { $('#title-'+elem).html(data.data.title); });
  $('#preview-'+elem).attr({src: 'http://img.youtube.com/vi/'+id+'/0.jpg'});
}
// show youtube stuff (common)
$('.youtube').each(function() {
  vid=$(this).attr('id').match(/youtube-(\d)/i);
  if($(this).val()!='') {
    startShowing($(this).val(),vid[1]);
  }
  $("#youtube-video-"+vid[1]).val($(this).val());
});

function pop(text,close_action,el) {
  html="<div class='modal-win' style='z-index:1001;position:fixed;display:none;background:white;padding:10px;color:black;'>"
      +"<button class='btn btn-mini close-modal btn-danger pull-right'>X</button>"
      +"<div style='margin-top:22px;'>"+text+"</div>"
  +"</div>";
  $('body').append(html);
  w=$('.modal-win').width();
  h=$('.modal-win').height();
  if(w>=700) {
    $('.modal-win').css("width","700");
    w=$('.modal-win').width();
    h=$('.modal-win').height();
  }
  
  if(typeof el != 'undefined') {
    $('.modal-win').css({width: $(el).width()+60, height: $(el).height()+60});
  }
  t=$(window).height()/2-h/2;
  l=$(window).width()/2-w/2;
  if(t<120) { t=120; }
  $('.modal-win').css({top: t, left: l, width: w, height: h});
  $(".curtains").toggle();
  $('.curtains').css({width: $(window).width(), height: $(window).height()});
  $('.modal-win').fadeIn(300);
  $('.close-modal, .curtains').click(function(){
    $('.modal-win').fadeOut('400',function() {
      $('.modal-win').remove();
      
      $('.curtains').toggle();
      eval(close_action);
    });
    return false;    
  });
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