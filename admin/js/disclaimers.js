$('.disclaimer').click(function(){
  $('.disclaimer.active').toggleClass("active btn-success btn-inverse");
  $(this).toggleClass("active btn-success btn-inverse");
  $('.disclaimer-win').hide();
  $('.disclaimer-win.'+$(this).attr('id')).show();
  return false;
});

$('.disclaimer-win:not(:first)').hide();

$('#submitForm').click(function(){
  for (var i = 0; i < tinyMCE.editors.length; i) {
    tinyMCE.execCommand('mceRemoveControl',false,tinyMCE.editors[i].editorId);
  }
  page=document.location.pathname;
  $.post(page+'?save',$('#dataForm').serialize(),function(data) {
    res=$.parseJSON(data);
    pop(res.result,"document.location=document.location");
  });
  return false;
});

$(document).ready( function() {
  tinyMCE.init({
    mode : "exact",
    elements : "preorder_en,preorder_ru,bonuses_en,bonuses_ru,purchase_en,purchase_ru,contacts_en,contacts_ru,help_en,help_ru,help2_en,help2_ru",
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
});
