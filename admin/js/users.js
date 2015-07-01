var timer;

// user functions usergrabber (users/list)
$(document).ready(function(){
  console.log(document.location);
  r=(document.location.search=='?listRem' ? "&removed" : "");
  $.post("users?loadList"+r,'',function(data){
    full_list=$.parseJSON(data);
    createUserList(0,full_list);
    temp_list=full_list;
    curPage=1;            
  });
});

// user function user table generator (users/list)
function createUserList(startOffset,list) {
  userItems="";
  endOffset=startOffset+20;
  endOffset=(endOffset>list.length ? list.length : endOffset);
  for(x=startOffset;x<endOffset;x++) {
    userItems+=userLine(list[x]);    
  }
  totalItems=list.length;
  if(totalItems>20) {
    pages=createPages();
    $(".pageDiv").html(pages);
  }
  $('.content-table').append(userItems);
  addClick('users'); 
  addLevelClick();
  addInfoClick();
  addRemClick(); 
}

function addRemClick() {
  $('.rem').click(function(){
    uid=$(this).parent().attr('id');
    act=(document.location.search=='?listRem' ? "renew" : "remove");
    $.post("/admin/users?"+act,"uid="+uid,function(data) {
      res=$.parseJSON(data);
      $('#'+res.uid).parent().remove();
    });
    return false;
  });
}

function addInfoClick() {
  $('.user').click(function(){
    uid=$(this).children('.id').html();
    $.post("/admin/users?showTable","uid="+uid,function(data) {
      pop(data,false);
      setTimeout(inv,300);
    });
  });
}

function inv() {
  $('.invoiceInfo').click(function(){
    $.post("users?loadGames", "invoice="+$(this).attr('href'), function(data) {
      res=$.parseJSON(data);
      $('#'+res.id).html(res.result);
      setTimeout(bp,300);
    });
  });      
}

function bp() {
  $('.btn-payed').click(function(){
    $.post("users?markAsPayed", 'id='+$(this).attr('invoice'), function(data){ res=$.parseJSON(data); $('#'+res.id).click();});      
  });
}

function userLine(data) {

  return "<tr class='user'>"
    +"<td class='id'>"+data.id+"</td>"
    +"<td>"+data.name+"</td>"
    +"<td>"+data.email+"</td>"
    +"<td>"+data.points+"</td>"
    +"<td id='"+data.id+"'>"+levelButtons(data.level)+"</td>"
  +"</tr>";
}


function levelButtons(lvl) {
  level=Array();
  level[lvl]='active btn-success';
  act=(document.location.search=='?listRem' ? "Renew" : "Remove");
  actt=(document.location.search=='?listRem' ? "success" : "danger");
  d=(document.location.search=='?listRem' ? "disabled='disabled'" : "");
  return "<button class='btn btn-mini "+level[1]+" "+d+" level' level='1'>1</button><button class='btn btn-mini "+level[2]+" "+d+" level' level='2'>2</button><button class='btn btn-mini "+level[3]+" "+d+" level' level='3'>3</button><button class='btn btn-mini rem pull-right btn-"+actt+"'>"+act+"</button>";
}

$('#src').keyup(function(){
  temp_list=new Array();
  for(x in full_list) {
    re = new RegExp($(this).val(),'i');
    nameMatch=full_list[x].name.match(re);
    emailMatch=full_list[x].email.match(re);
    if(nameMatch || emailMatch) {
      temp_list.push(full_list[x]);
    }
  }
  $('.content-table tr.user').remove();
  createUserList(0,temp_list);
});

function addLevelClick() {
  $('.level:not(.active)').click(function(){
    $.post('users?saveLevel','id='+$(this).parent().attr('id')+'&level='+$(this).attr('level'), function(data) {
      res = $.parseJSON(data);
      if(res.success==true) {
        $('#'+res.id).children('button').removeClass('active btn-success');
        $('#'+res.id+' [level^='+res.level+']').addClass('active btn-success');
        pop(res.result,false);
      }
    });
    return false;
  });
  $('.level .active').click(function (){ return false});
}