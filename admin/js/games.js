// filter button functionality (games/list)
$(document).ready(function(){
  var regex = new RegExp("games/list$");
  if(document.URL.match(regex)) { 
    load_data();

    $(".reset").click(
      function(){      
        replaceButtonText('filterage', "No Age Filter");
        age_filter="";
        replaceButtonText('filtergenre', "No Genre Filter");
        price_filter="";
        replaceButtonText('filterprice', "No Price Filter");
        genre_filter="";
        $('#searchField').val("");
        list = list_head();
        items=1;
        for(x in arr.games) {
          if(items<=20) {
            list+=createList(arr.games[x]);
            items++;
          }
        }
        totalItems=Number(x)+1;
        if(totalItems>20) {
          pages=createPages();
          curPage=1;
          $(".pageDiv").html(pages);
        }
        $('.content-table').html(list);
        addClick('games');
        updateList();
    });
   
    $("[id^=age]").click(                    
      function(){
        found=this.id.substr(3,this.id.length);
        age_filter=(found=='no' ? "" : found);
        if(found=='no') {
          replaceButtonText('filterage', "No Age Filter");
        } else {
          replaceButtonText('filterage', "Age "+age_filter+"+");
        }
        search_filter($("#searchField").val(),0);     
    });
    
    $("[id^=type]").click(                    
      function(){
        found=this.id.substr(4,this.id.length);
        genre_filter=(found=='no' ? "" : found);
        if(found=='no') {
          replaceButtonText('filtergenre', "No Genre Filter");
        } else {
          replaceButtonText('filtergenre', genre_filter);
        }
        search_filter($("#searchField").val(),0);     
    });
    
    $("[id^=price]").click(                    
      function(){
        found=this.id.substr(5,this.id.length);
        price_filter=(found=='no' ? "" : found);
        if(found=='no') {
          replaceButtonText('filterprice', "No Price Filter");
        } else {
          price=price_filter.split("-");
          price[0]=Number(price[0]/100);
          price[1]=Number(price[1]/100);            
          replaceButtonText('filterprice', price[0]+"Eur - "+price[1]+"Eur");
        }
        search_filter($("#searchField").val(),0);     
    });
    
    $("#searchField").keyup(
      function(){
        search_filter(this.value,0);  
    });
    addLabelClick();
  }
});

// Game image count (games/edit/add)
while($("#saveImage-"+uploadItem).hasClass("busy")) {
  upload.push("");
  uploadItem++;
} 
// Game listing headers (games/list)
function list_head() {
      return "<tr>"
        +"<td style='width:15px;'>ID</td>"
        +"<td style='width:60%;text-align:left;'>Game Name</td>"
        +"<td style='width:10%;'>Genre</td>"
        +"<td style='width:10%;'>Age</td>"
        +"<td style='width:10%;'>Price</td>"
        +"<td style='width:10%;'>Aviability</td>"
      +"</tr>";
}

// Apply search filter  (games/list)
function search_filter(string,x) {
  if(x==0) {curPage=1;}
  list = list_head();
  regex = new RegExp(string,"i");
  items=0;
  itemsLeft=x;
  while(arr.games[x]) {
    if(string!='') {
      item=(arr.games[x].name.match(regex) && check_filters(arr.games[x]) ? createList(arr.games[x]) : "");
      if(item) {
        items++;
        if(items<=20) {
          list+=item;
        }
      }
    } else {
      item=(check_filters(arr.games[x]) ? createList(arr.games[x]) : "");
      if(item) {
        items++;
        if(items<=20) {
          list+=item;
        }
      }
    }
  x++;
  }
  totalItems=Number(items)+Number(itemsLeft);
  if(totalItems>20) {
    pages=createPages();
    $(".pageDiv").html(pages);
  }
  $('.content-table').html(list);
  addClick('games');
  updateList();
}

// load Game list (games/list) 
function load_data() {
  $.get('/admin/games/getList', function(data) {
    arr = jQuery.parseJSON(data);
    list = list_head();
    items=0;
    for(x in arr.games) {
      if(items<20) {
        list+=createList(arr.games[x]);
        items++;
      }
    }
    totalItems=Number(x)+1;
    if(totalItems>20) {
      pages=createPages();
      $(".pageDiv").html(pages);
    }
    $('.content-table').html(list);
    addClick('games');
    updateList();
  });
}                                         

// game function buttons (games/list)
function updateList() {
  $(".item").children().mouseenter(function(){
    $(".buttons", this).fadeIn('fast');
  });
  $(".item").children().mouseleave(function(){
    $(".buttons", this).fadeOut('fast');
  });
  $(".buttons").children(':not(.pull-right)').click(function() {
    clickData=$(this).attr('id').split("-");
    document.location="games/"+clickData[0]+"/"+clickData[1];
  });
  
  $(".buttons").children(".btn-info").click(function(){
    $.post("games/changeBS", "id="+$(this).attr('id'), function(data){
      changeBS($.parseJSON(data));
    })
  });

  $(".buttons").children(".btn-danger").click(function(){
    if(confirm("Do You really want to remove this game?")) {
      $.post("games/remove","id="+$(this).attr('id'), function(data) {
        res=$.parseJSON(data);
        if(res.removed) {
          $("#"+res.removed).remove();
        }
      });
    }
    return false;
  });
  
  $(".buttons").children(".pull-right.btn-success").click(function(){
    $.post("games/changeActivation","id="+$(this).attr('id'), function(data) {
      res=$.parseJSON(data);
      if(res.success) {
        var state=new Array();
        state["n"] = "A";
        state["y"] = "Dea";
        replaceButtonText(res.id,state[res.state]+"ctivate");
        $("#"+res.id).toggleClass("active");
      }
    });
    return false;
  });
}

function changeBS(res) {
  var state=new Array();
  state["n"] = "Off";
  state["y"] = "On";
  replaceButtonText(res.id,"BestSeller: "+state[res.state]);
  $("#"+res.id).toggleClass("active");
}

// game function keys (games/list)
function createButtons(id,ac,bs,aac,aon) {
  return "<div class='buttons' style='display:none;width:100%;'>"
    +"<button class='btn btn-mini btn-success' style='margin-left:5px;' id='view-"+id+"'>View</button>"
    +"<button class='btn btn-mini btn-success' style='margin-left:5px;' id='codes-"+id+"'>Add Game Codes</button>"
    +"<button class='btn btn-mini btn-warning' style='margin-left:5px;' id='edit-"+id+"'>Edit</button>"
    +"<button class='btn btn-mini btn-danger pull-right' style='margin-left:5px;' id='remove_"+id+"'>Remove</button>"
    +"<button class='btn btn-mini btn-success pull-right "+aac+"' style='margin-left:5px;' id='activate_"+id+"'>"+aon+"ctivate</button>"
    +"<button class='btn btn-mini btn-info pull-right "+ac+"' style='margin-left:5px;' id='BestSeller_"+id+"'>BestSeller: "+bs+"</button>"
    +"</div>";
}

// Creating Game list (games/list)
function createList(data) {
      return "<tr class='item' id='"+data.id+"'>"
        +"<td>"+data.id+"</td>"
        +"<td style='position:relative;text-align:left;'>"+data.name+createButtons(data.id,data.ac,data.bs,data.aac,data.aon)+"</td>"
        +"<td>"+data.genre+"</td>"
        +"<td>"+data.age+"</td>"
        +"<td>"+data.price+"</td>"
        +"<td>"+data.count+"("+data.countWithoutReserved+")</td>"
      +"</tr>";
}

// filter list (games/list)
function check_filters(data) {
  var pass_price,pass_genre,pass_age;
  
  if(price_filter!='') {
    price=price_filter.split("-");
    price[0]=Number(price[0]/100);
    price[1]=Number(price[1]/100);            
    if(data.price>=price[0] && data.price<=price[1]) {
      pass_price=true;
    }
  } else {
    pass_price=true;
  }
  
  if(age_filter!='') {
    if(Number(data.age)>=Number(age_filter)) {
      pass_age=true;
    }
  } else {
    pass_age=true;
  }
  
  if(genre_filter!='') {
    re=new RegExp(genre_filter);
    if(data.genre.match(re)) {
      pass_genre=true;
    }
  } else {
    pass_genre=true;
  }
  
  if(pass_age==true && pass_genre==true && pass_price==true) {
    y++;
    return data;
  } else {
    return false;
  }  
}

// Upload button (games/add/edit)
$(".btn-upload").click(function(){ $('#game-img').click(); return false;});

// file adding (games/add/edit)
$('#game-img').change(function(){
  maxsize=1024*1024*2;
  for(x=0; x<this.files.length; x++) {
      if(this.files[x].size>maxsize) {
        alert("Image: "+this.files[x].name+" exceeds max allowed size: "+maxsize+' bytes');      
      } else {
        y=x+uploadItem;
        appendhtml="<div style='float:left;width:200px;height:150px;margin-right:5px;' class='thumbnail container-"+y+"'>"
          +"<div id='data-uploaded-"+y+"' style='margin:auto;width:180px;height:130px;'>Loading Image</div>"
          +"<div id='data-info-"+y+"'></div>"
        +"</div>";
        $("#image-result-div").append(appendhtml);
        upload.push(this.files[x]);
      } 
    }
    doUpload();
});

// if file exists in file list (games/add/edit)
function file_exists(filename) {
  for(zz in upload) {
    if(upload[zz].name==filename && zz!=uploadItem) {
      return true           
    }
  }
  return false;
}

// upload starter (games/add/edit)
function doUpload() {
  start_upload(upload[uploadItem]);    
}

// Image Preview and upload function (games/add/edit)
function start_upload(fil) {
  file = fil; 
  var req = new XMLHttpRequest();
  req.open("POST", "/admin/upload/", true);
  boundary = "---------------------------7da24f2e50046";
  req.setRequestHeader("Content-Type", "multipart/form-data, boundary="+boundary);
  var reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = (function(theFile) {
    return function(e) {
      if(e.target.result.substr(0,40).match(/image\//i)) {
        if(!file_exists(upload[uploadItem].name)) {
          $("#data-uploaded-"+uploadItem).html("<img src='"+e.target.result+"' title='"+file.name+"' style='height:130px;'/>");
          $("#data-info-"+uploadItem).html("<input type='hidden' class='cover' name='cover["+uploadItem+"]' id='cover-"+uploadItem+"' value='0'/><span class='label label-info label-"+uploadItem+"' style='float:left;cursor:pointer;'>ScreenShot</span><input type='hidden' value='"+file.name+"' name='saveImage["+uploadItem+"]'  id='saveImage-"+uploadItem+"'><a href='javascript:removeImage("+uploadItem+")' style='float:right;'>Remove</a>");
          addLabelClick();
          var body = "--" + boundary + "\r\n";
          body += "Content-Disposition: form-data; name='upim'; filename='" + file.name + "'\r\n";
          body += "Content-Type: application/octet-stream\r\n\r\n";
          body += e.target.result + "\r\n";
          body += "--" + boundary + "--";
          req.send(body);
          uploadItem++;
          if(upload[uploadItem] instanceof File) { doUpload(); }
        } else {
          alert("file: "+upload[uploadItem].name+" already exists in uploaded files");
          removeImage(uploadItem);
          uploadItem++;
          if(upload[uploadItem] instanceof File) { doUpload(); }
        }
      } else {
        alert("this aint image");
        removeImage(uploadItem);
        uploadItem++;
        if(upload[uploadItem] instanceof File) { doUpload(); }
      }
    };
  })(file);
}

// remove image (games/add/edit)
function removeImage(id) {
  for(zz in upload) {
    if(upload[zz].name==$('#saveImage-'+id).val()) {
      upload[zz]='';
    }
  }
  $(".container-"+id).remove();
}

// Change language fields  (games/add/edit)
var modifyfields = Array("name","date","platform","languages","sysreq");
$('.language').click(
  function() {
    clicked=$(this).attr('id');
    lang=(clicked=="en" ? "ru" : "en");
    
    $(this).toggleClass("btn-inverse btn-success active")

    $('#'+lang).toggleClass("btn-inverse btn-success active")

    $.each(modifyfields, function(data){
      $('#'+this+"-"+lang).hide();
      $('#'+this+"-label-"+lang).hide();
      $('#'+this+"-"+clicked).show();
      $('#'+this+"-label-"+clicked).show();
  });
  return false;
});


// age, genre selection (games/add/edit)
$("[id^=select]").click(function(){
  parts=this.id.split('-');
  changeSelectionFor=parts[1];
  if(changeSelectionFor=='age') {
    if(parts[2]=='no') {
      replaceButtonText('filterage', "No Age Restriction");
      $("#"+changeSelectionFor).val("");
    } else {
      replaceButtonText('filterage', "Ages "+parts[2]+"+");    
      $("#"+changeSelectionFor).val(parts[2]);
    }
  }
});

// Submit Form initiator (games/add/edit)
$('#submitForm').click(function () {
  tinyMCE.execCommand('mceRemoveControl',false,'description-en');
  tinyMCE.execCommand('mceRemoveControl',false,'description-ru');
  doSaveForm();
  $("#swe").val('0');
  return false;
});

// submit form (games/add/edit)
function doSaveForm() {
  submitData = $("#addForm").serialize();
  $.post('games/action/save', submitData, function(data) {
    console.log(data);
    data=$.parseJSON(data);
    console.log(data);
    $("#savewitherrors").remove();
    if(data.hardFail) {
      if(data.hardFail.length>0) {
        for ( x in data.hardFail ) {
          $("#"+data.hardFail[x]).css({background: '#FFDEDE'});        
        }
      }
    }
    if(data.fail) {
      if(data.fail.length>0) {
        for ( x in data.fail ) {
          $("#"+data.fail[x]).css({background: '#FEFFD4'});        
        }
      }
      if(!data.hardFail) {
        $('#submitForm').parent().prepend("<button class='btn btn-warning pull-right' id='savewitherrors'>Save With Errors</button>&nbsp;");
        $('#savewitherrors').click(function(){
          $("#swe").val('1');
          doSaveForm();  
          return false;
        }); 
      }
    } 
    if(data.success) {
      if(data.success.length>0) {
        for( x in data.success ) {
          $("#"+data.success[x]).css({background: 'white'});        
        }
      }
    }
    if(data.added==true) {
      document.location.href="games/view/"+data.id;
    } else {
      pop(data.result,false);
    }            
  }); 
}

// genre selector (games/add/edit)
$(".check").click(function(){ 
  $(this).button("toggle");
  $(this).toggleClass("btn-success btn-inverse");
  updateGenre(); 
  return false; 
});

// create genre list (games/add/edit)
function updateGenre() {
  var genres="";
  $(".check").each(function(){
    if($(this).hasClass("active")) {
      genres+=$(this).html()+",";
    }
  });
  $("#genres").val(genres.substr(0,genres.length-1));
}

// date specifier (games/add/edit)
$("input[id^=date-],input[name^=date-]").focus(function(){
  if($(this).val()=='')
    $(this).val("__/__/____");
    setTimeout($(this).setCursorPosition(0),100);
});

// date specifier (games/add/edit)
$("input[id^=date-],input[name^=date-]").blur(function(){
  if($(this).val()=='__/__/____') 
    $(this).val("");
});

// date specifier (games/add/edit)
$("input[id^=date-],input[name^=date-]").bind("keydown click",function(e){
  if(e.ctrlKey) {
   return;
  }
  selStart=$(this).caret().start;
  selEnd=Number(selStart)+1;
  if(selStart==2 || selStart==5) { selStart++; selEnd++; $(this).setCursorPosition(selStart); }
  if((e.keyCode>=48 && e.keyCode<=57) || (e.keyCode>=96 && e.keyCode<=105) ) {
    if((e.keyCode>=96 && e.keyCode<=105)) {
      numbers=String.fromCharCode(e.keyCode-48);
    } else {
      numbers=String.fromCharCode(e.keyCode);
    }
    selStart=$(this).caret().start;
    selEnd=Number(selStart)+1;
    if(selStart<10) {
      text = $(this).val().substr(0,selStart);
      text += numbers;
      text += $(this).val().substr(selEnd,$(this).val().length);
      $(this).val(text);
      if(selEnd==2) { selEnd=3; }
      if(selEnd==5) { selEnd=6; }
      $(this).setCursorPosition(selEnd);
      return false; 
    } else {
      return false;
    }   
  } else if(e.keyCode==8 || e.keyCode==46) {
      if(selStart==3) { selStart=2; selEnd=3;}
      if(selStart==6) { selStart=5; selEnd=6;}
      if(selStart>0) {
        selStart--;
        selEnd--;
        text = $(this).val().substr(0,selStart);
        text += "_";
        text += $(this).val().substr(selEnd,$(this).val().length);
        $(this).val(text);
        $(this).setCursorPosition(selStart);
      }
      return false; 
  } else if(e.keyCode==37 || e.keyCode==39) {
    if(e.keyCode==37) {
      if(selStart==3) { selStart=2; selEnd=3;}
      if(selStart==6) { selStart=5; selEnd=6;}
      curs=selStart;
    } else {
      if(selEnd==2 || selEnd==5) { selStart++; }
      curs=selStart;
    }
    
    $(this).setCursorPosition(curs);
    return;
  } else if(e.keyCode==35 || e.keyCode==36) {
    return;
  } else {
    return false;
  }    
});

var ctrl=false;

$('textarea').keydown(function(e) {
  if(e.keyCode==17) {
    ctrl=true;
    return false;
  }
  if(ctrl && (e.keyCode==66 || e.keyCode==89)) {
    $(this).val($(this).caret().replace("<strong>"+$(this).caret().text+"</strong>"));
    return false;
  }
  if(ctrl && (e.keyCode==73 || e.keyCode==105)) {
    $(this).val($(this).caret().replace("<em>"+$(this).caret().text+"</em>"));
    return false;
  }
});

$('textarea').keyup(function(e) {
  if(e.keyCode==17) {
    ctrl=false;
  }
});

// cursor position (games/add/edit)
new function($) {
  $.fn.setCursorPosition = function(pos) {
    if ($(this).get(0).setSelectionRange) {
      $(this).get(0).setSelectionRange(pos, pos);
    } else if ($(this).get(0).createTextRange) {
      var range = $(this).get(0).createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
  }
}(jQuery);

// screenshot/cover changer (games/add/edit/view)
function addLabelClick() {                
  $(".label").click(function () {
    if(!$(location).attr("href").match(/view/)) {
      $(".label-success").html("ScreenShot");
      $(".label-success").addClass("label-info");
      $(".label-success").prev().val('0');            
      $(".label-success").removeClass("label-success");
      $(this).removeClass("label-info");
      $(this).addClass("label-success");
      $(this).html("Cover Image");
      $(this).prev().val("1");
    }
  });
}

// game codes (games/codes)
$(document).ready(function(){
  var regex = new RegExp("games/codes");
  if(document.URL.match(regex)) {
    $.post('games/getCodeList/'+$('#gID').val(), function(data) {
      if(data) {
        keys=$.parseJSON(data);
        buildKeysList('list');        
      }      
    }); 
  }
});

// key list builder (games/codes)
var gameCode = new Array();
var Added=false;
function buildKeysList(type) {
  result="";
  for(x in keys) {
    if(keys[x].cid==false) { 
      alert(keys[x].tcode); 
    } else {
      if(type=='add' && !Added && keys[x].cid!=false) {
        $('.keylist').prepend("<hr style='width:100%;'/>");
        Added=true;
      }
      cid=keys[x].cid;
      code=$.parseJSON(keys[x].tcode);
      gameCode[cid]=code.value;
      key=(code.type=='text' ? code.value : "<a href='javascript:void(0);' onclick='showCode("+cid+")'>"+code.name+"</a>");
      key+="<button class='btn btn-danger pull-right btn-mini' style='z-index:11111111;margin-right:5px;' id='"+cid+"'>X</button>";
      if(keys[x].owned != '') {
        key+="<a href='javascript:void(0)' onclick='showUser("+keys[x].owner+")' class='pull-right'>Owned by: "+keys[x].owned+"</a>";
      }
      style=(keys[x].reserved>0 ? "background:#FFDDDD;color:black;" : "color:white;");
      result+="<div class='kil' id='key-"+cid+"' style='width:100%;height:24px;"+style+"'>"+key+"</div>";
    }
  }
  if(type=='list') {
    $('.keylist').html(result);
  } else {
    $('.keylist').prepend(result);
  }
  $('.keylist .btn-danger').click(function() {
    ccid=$(this).attr('id');
    if(confirm("Are You Sure You want to remove this Code ?")) {
      $.post("games/codes/remove", "cid="+ccid, function(data) {
        console.log(data);
        res=$.parseJSON(data);
        if(res.removed) {
          if(typeof res.message!='undefined') {
            inform(res.message,false);
          }
          $("#key-"+ccid).remove();
        }
      });
    }
    return false;
  });
  $('#iim').load(function() { pop("<img src='"+$('#iim').attr('src')+"' style='padding:15px;max-width:550px;' />",false,'#iim'); });
}

function showUser(uid) {
  $.post("/admin/users?showTable","uid="+uid,function(data) {
    pop(data,false);
    setTimeout(inv,300);
  });
}

function inv() {
  $('.invoiceInfo').click(function(){
    $.post("users?loadGames", "invoice="+$(this).attr('href'), function(data) {
      res=$.parseJSON(data);
      $('#'+res.id).html(res.result).ready(function(){ bp(); });
    });
  });      
}

function bp() {
  $('.btn-payed').click(function(){
    $.post("users?markAsPayed", 'id='+$(this).attr('invoice'), function(data){ res=$.parseJSON(data); $('#'+res.id).click();});      
  });
}

function showCode(x) {
  $('#iim').attr('src',gameCode[x]);
}

// keylist save (games/codes)
$('.btn-save').click(function(){
  $.post("games/addCodes/"+$('#gID').val(), $('#codeForm').serialize(), function(data){
    if(data) {
      keys=$.parseJSON(data);
      buildKeysList('add');        
    }      
  });
  return false;
});

// send information (games/add/edit)
$('.btn-spam').click(function(){
  $(this).toggleClass('active');
  $(this).toggleClass('btn-inverse');
  $(this).toggleClass('btn-success');
  $('#spam').val(Number($(this).hasClass("active")));
  return false;
});

$('.uploadKodes').click(function(){
  $('#kodes').click();
  return false;
});

$('#kodes').change(function(){
  maxsize=1024*1024*2;
  for(x=0; x<this.files.length; x++) {
    if(this.files[x].size>maxsize) {
      alert("Image: "+this.files[x].name+" exceeds max allowed size: "+maxsize+' bytes');      
    } else {
      upload.push(this.files[x]);
    }
  }      
  start_upload_codes(upload[uploadItem]);
});

// super saver (games/add/edit)
$('.btn-ss').click(function(){
  $(this).toggleClass('active btn-inverse btn-success');
  $('#sse').val(Number($(this).hasClass('active')));
  $('#ss').fadeToggle('200');
  $('#ssd').fadeToggle('200');
  return false;
});

// super saver shower (games/edit)
$(document).ready(function(){
  if($('#sse').val()=='1') {
    $('.btn-ss').click();    
  }
});


function start_upload_codes(fil) {
  console.log(fil);
  file = fil; 
  var req = new XMLHttpRequest();
  req.onloadend= function(data) {
    keys=$.parseJSON(data.target.response);
    buildKeysList('add');        
  }
  req.open("POST", "games/codes/upload?gID="+$('#gID').val(), true);
  boundary = "---------------------------7da24f2e50046";
  req.setRequestHeader("Content-Type", "multipart/form-data, boundary="+boundary);
  var reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = (function(theFile) {
    return function(e) {
      if(e.target.result.substr(0,40).match(/image\//i)) {
        if(!file_exists(upload[uploadItem].name)) {
          var body = "--" + boundary + "\r\n";
          body += "Content-Disposition: form-data; name='codeIm'; filename='" + file.name + "'\r\n";
          body += "Content-Type: application/octet-stream\r\n\r\n";
          body += e.target.result + "\r\n";
          body += "--" + boundary + "--";
          req.send(body);
          uploadItem++;
          if(upload[uploadItem] instanceof File) { start_upload_codes(upload[uploadItem]); }
        } else {
          alert("file: "+upload[uploadItem].name+" already exists in uploaded files");
          uploadItem++;
          if(upload[uploadItem] instanceof File) { start_upload_codes(upload[uploadItem]); }
        }
      } else {
        alert("This aint image");
        uploadItem++;
        if(upload[uploadItem] instanceof File) { doUpload(); }
      }
    };
  })(file);
}