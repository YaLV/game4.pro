var temp_list,full_list;

function list_head() {
      return "<tr>"
        +"<td style='width:15px;'>Date</td>"
        +"<td style='text-align:left;'>Invoice</td>"
        +"<td style='text-align:left;'>User</td>"
        +"<td style='text-align:left;'>Game Name</td>"
        +"<td style='width:10%;'>Count</td>"
        +"<td style='width:10%;'></td>"
      +"</tr>";
}

function load_data() {
  $.get('/admin/preorders?loadPreorderList', function(data) {
    console.log(data);
    arr = jQuery.parseJSON(data);
    if(typeof arr.success=="undefined") {
      list = list_head();
      x=0;
      temp_list=full_list=arr;
      while(x<=20) {
          if(!arr[x]) { break; }
          list+=createList(arr[x]);
          x++;
      }
      totalItems=full_list.length+1;
      if(totalItems>20) {
        pages=createPages();
        $(".pageDiv").html(pages);
      }
      $('.content-table').html(list);
      addClick('preorder');
      updateIt();
    } else {
      pop(arr.msg,false);
    }
  });
}

function createList(data) {
  btn="<button class='btn btn-warning sm' id='p_"+data.id+"' data-invoice='"+data.invoice+"'>Send Mail</button>";  
  return "<tr class='item "+data.id+"' id='"+data.invoice+"'>"
    +"<td>"+data.time+"</td>"
    +"<td style='text-align:left;' class='p_"+data.id+"'>"+data.invoice+"</td>"
    +"<td style='text-align:left;'>"+data.user+"</td>"
    +"<td style='text-align:left;'>"+data.name+"</td>"
    +"<td style='text-align:left;' class='count'>"+data.count+"</td>"
    +"<td>"+btn+"</td>"
    +"</tr>";
}

function createPreorderList(offset,lists) {
  end=offset+20;
  list=list_head();
  while(end>offset) {
    if(!lists[offset]) { break; }
    list+=createList(lists[offset]);
    offset++;
  }
  pages=createPages();
  $(".pageDiv").html(pages);
  $('.content-table').html(list);
  addClick('preorder');
  updateIt();
}

var lastSel;

function updateIt() {
  $('.sm').click(function(){
    $.post("/admin/preorders?MailForm", 'gid='+$(this).attr('id')+"&inv="+$(this).attr('data-invoice'), function(data) {
      pop(data,false);
      setTimeout(function(){
        $('#keys option').click(
          function() {
            keys=new Array();
            if($(this).parent().val().length>$("#maxSel").val()) {
              alert("Maximum selections made");
              $(this).removeAttr('selected');
            } else {
              $("#keylist").html("");
              $(this).parent().children(":selected").each(
                function() {
                  content=($(this).attr('data-type')=='text' ? "<div style='float:left;'>"+$(this).attr('data-content')+"</div>" : "<div style='float:left;'><img src='"+$(this).attr('data-content')+"' style='max-height:100px;max-width:400px;' /></div>");
                  $("#keylist").append(content);
                  keys.push($(this).val());
                }
              );
              $("[name=keys]").val(keys.join(','));                          
            }
          }
        );
        $(".send").click(function(){
          $.post("/admin/preorders?dosnd",$('#sform').serialize(),function(data) {
            console.log(data);
            res=$.parseJSON(data);
            console.log(res);
            inform(res.message,res.success);
            if(typeof res.run!='undefined' && res.run!='') {
              eval(res.run);
            }
            if(res.success==true) {             
              $('.curtains').click();
            }
          });
          return false;
        });
      },300);
    });
    return false;
  });
}

function cul(data) {
  if(data) {
    return "<tr>"
      +"<td>"+data.eml+"</td>"
      +"<td>"+data.count+"</td>"
      +"</tr>"
  } else {
    return "<tr>"
      +"<td>User</td>"
      +"<td style='width:50px;'>Ordered copies</td>"
      +"</tr>"
  }
}

$(document).ready(function() {
  load_data();    
});