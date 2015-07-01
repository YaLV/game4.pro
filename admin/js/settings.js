$(document).ready(function(){
  var t=0;
  $('#addUser').click(function(){
    $('#addForm').slideToggle(200);
    return false;
  });
  $('#addForm button.btn-danger').click(function(){ $('#addForm input').each(function(){ $(this).val(''); }); return false;});
  $('.addUserButton').click(function(){
    $.post('settings/users?add', $('#addForm').serialize(), function(data) {
      result=$.parseJSON(data);
      if(result.success==true) {
        if(result.changed==true) {
          changeUserData(result.userdata);
          pop("User Data was successfully changed",false);
        } else {
          addUserToList(result.userdata);
          pop("User was successfully added",false);
        }
        $('#addForm input').each(function(){ $(this).val(''); });
        $('#addForm').slideToggle(200);
        addClicks();
      } else {
        pop("<b>There was Errors adding user: </b><br />"+result.error,false);
      }
    });
    return false;
  });
  $('.curtains').css({height:$(document).height(),width:$(window).width(),zIndex:1000});
  if($('.msg_snt').length>0) {
    loadMSGList(1);
  }
  addClicks();
});

function createMSGList(items) {
  html=MSGHeader();
  for( x in items) {
    html+=MSGLine(items[x]);
  }
  if(totalItems>20) {
    console.log("creating pages");
    pages=createPages();
    $(".pageDiv").html(pages);
  }
  $(".content-table").html(html);
  addClick('msgs');
  $('.openmsg').click(function(){ console.log('#el-'+$(this).attr('id')); pop($('#el-'+$(this).attr('id')).html(),false); });
}

function loadMSGList(page) {
  $.post('settings/messages_sent?listEm',"page="+page,function(data) {
    res=$.parseJSON(data);
    console.log(res);
    totalItems=res.cnt;
    createMSGList(res.items);
  });  
}

function MSGLine(data) {
  statuz=(data.js>0 && data.je==0 ? "Sending in progress: "+(data.progress==0 ? "Queued" : data.progress+"%") : (data.je>0 && data.js>0 ? "Sending Finished" : "New message"));
  return "<tr class='openmsg' id='"+data.id+"'>"
            +"<td><div style='display:none;' id='el-"+data.id+"'>"+data.mailmessage+"</div>"+data.id+"</td>"
            +"<td>"+data.date+"</td>"
            +"<td>"+data.sen+"</td>"
            +"<td>"+statuz+"</td>"
            +"<td>"+data.game+" ("+data.gid+")</td>"
         +"</tr>";            
}

function MSGHeader() {
  return "<tr>"
            +"<td>ID</td>"
            +"<td>Date</td>"
            +"<td>Subject</td>"
            +"<td>Status</td>"
            +"<td>Game (Game ID)</td>"
         +"</tr>";            
}


function addClicks() {
  $('.btn-opt button.btn-warning').click(function(){
    $('[name^=id]').val($(this).parent().parent().attr('id'));
    $(this).parent().parent().children('td:not(.btn-opt)').each(function(){
      $('[name^='+$(this).attr('class')+']').val($(this).html());
      $('#addForm').slideDown(200);
    });
  });

  $('.btn-opt button.btn-danger').click(function(){
    id=$(this).parent().parent().attr('id');
    if(confirm('Do You Really want to delete '+$('#'+id).children("td.username").html())) {
      $.post('settings/users?remove',"id="+id, function(data){
        result=$.parseJSON(data);
        if(result.success==true) {
          $('#'+result.id).remove();
          pop("User has been removed.",false);
        }
      });
    }
  });
}


function addUserToList(data) {
    html="<tr id='"+data.id+"'>"
      +"<td class='username'>"+data.username+"</td>"
      +"<td class='email'>"+data.email+"</td>"
      +"<td class='phone'>"+data.phone+"</td>"
      +"<td class='btn-opt'><button class='btn btn-mini btn-warning'>Edit</button><button class='btn btn-mini btn-danger'>Remove</button></td>"
    +"</tr>";
    $('.content-table').append(html);
}

function changeUserData(data) {
  $('#'+data.id).children('td:not(.btn-opt)').each(function(){ $(this).html(eval("data."+$(this).attr('class'))); });  
}
                        

$('#translation .btn').click(function(){
  $.post(document.location.pathname+"?save",$("#translation").serialize(),function(data){
    res=$.parseJSON(data);
    pop(res.result,false);
  })
  return false;
});

