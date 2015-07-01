$(document).ready( function() {
  if(typeof tinyMCE!='undefined')
    activateEditors();
});

function activateEditors() {
  tinyMCE.init({
    mode : "exact",
    elements : "news_en,news_ru",
    theme: "advanced",
    plugins : "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
    valid_elements : "*[*]",
    inline_styles : true,
    schema: "html5",
    force_br_newlines : true,
    force_p_newlines : false,
    forced_root_block : "",
    invalid_elements : "p"
  });
}

// news edit button (news/list)
$("#newsedit").click(function(){
  document.location.href="/admin/news/edit/"+$('[name^=news_id]').val();
  return false;
});

// news add button (news/add/edit)
$("#newsadd").click(function (){
  for (var i = 0; i < tinyMCE.editors.length; i) {
    tinyMCE.execCommand('mceRemoveControl',false,tinyMCE.editors[i].editorId);
  }
  submitData = $("#newsForm").serialize();
  $.post('news/action/save', submitData, function(data) {
    data=$.parseJSON(data);
    if(data.fail) {
      if(data.fail.length>0) {
        for ( x in data.fail ) {
          $("#"+data.fail[x]).css({background: '#FEFFD4'});
          activateEditors();        
        }
      } 
    } else if(data.added==true) {
      document.location.href="/admin/news/view/"+data.id;
    }            

  });
  return false;
});

// news lister (news/list)
$(document).ready(function(){
  var regex = new RegExp("news/list$");
  if(document.URL.match(regex)) {
    $.post('news/getList', function(data) {
      arr=$.parseJSON(data);
      createNewsTable(0);
    }) 
  }
});

// news table headers (news/list) 
function newsTableHeaders() {
  return "<tr>" +
      "<td style='width:95px;'> Date</td>" +
      "<td> Partial Data</td>" +
      "<td style='width:205px;'> Opts</td>" +
    "</tr>";
}

// news table creator (news/list)
function createNewsTable(startID) {
  var states=[];
  states['y']='De';
  states['n']='A';
  stopID=startID+20;
  html = newsTableHeaders();
  totalItems=arr.length;
  for(x=startID;x<=stopID;x++) {
    if(!arr[x]) { break; }
    html+="<tr id='"+arr[x].id+"'>" +
      "<td style='vertical-align:top;'> "+arr[x].date+"</td>" +
      "<td style='vertical-align:top;'> "+arr[x].news+" .......</td>" +
      "<td style='vertical-align:top;text-align:right;'> <button class='btn btn-info btn-list btn-mini' id='act_"+arr[x].id+"'>"+states[arr[x].state]+"ctivate</button>&nbsp;&nbsp;<button class='btn btn-warning btn-list btn-mini' id='"+arr[x].id+"'>Edit</button>&nbsp;&nbsp;<button class='btn btn-success  btn-mini btn-list' id='"+arr[x].id+"'>View</button>&nbsp;&nbsp;<button class='btn btn-danger btn-rem btn-list btn-mini btn-list' id='rem_"+arr[x].id+"'>Remove</button></td>" +
    "</tr>";
  }
  if(totalItems>20) {
    pages=createPages();
    $(".pageDiv").html(pages);
  }
  $(".content-table").html(html);
  addClick('news');
  createEdit();
}

// edit btn (news/list)
function createEdit() {
  $(".btn-list").click(function() {
    if($(this).hasClass("btn-warning")) {
      document.location.href="/admin/news/edit/"+$(this).attr('id');
    } else if($(this).hasClass("btn-success")) {
      document.location.href="/admin/news/view/"+$(this).attr('id');
    } else if($(this).hasClass("btn-info")) {
      $.post("news/action/state", 'id='+$(this).attr('id'), function(data) {
        var states=[];
        states['y']='De';
        states['n']='A';
        res=$.parseJSON(data);
        replaceButtonText('act_'+res.id, states[res.state]+"ctivate");
        $('#act_'+res.id).toggleClass('active');
      });
    } else if($(this).hasClass("btn-danger")) {
      if(confirm("Do You really want to delete this News entry")) {
        thisid=$(this).attr('id');
        $.post('news/remove','id='+thisid, function(data) {
          res=$.parseJSON(data);
          if(res.success) {
            $('#'+thisid).parent().parent().remove();
          }
        });
      }
    }
  });
}
