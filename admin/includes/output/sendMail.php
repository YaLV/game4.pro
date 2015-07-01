<?
if($_POST['subject'] && $_POST['text']) {
  $m='{"name":{"datatype":"vars","data":"name"}}';
  $sql->query("insert into mail_send values('','{$_POST['subject']}','','{$_POST['text']}','','$m','1','0','0','plain')");
  header("location:/admin/settings/messages_sent");
  exit();
}

$in="Добавляя фотографии в письмах не гарантирует доставку писем получателей (возможно доставит, но многие не увидят эти фотографии)";

$contents.="
<div class='shadow box content' style='position:relative;height:520px;color:white;'>
    <form method='post' id='mailForm'>
      <div class='pull-left disclaimer-win' style='margin:auto;margin-left:80px;'>
        <table>
          <tr>
            <td>Subject: <input type='text' name='subject' class='pull-right'/></td>
          </tr>
          <tr>
            <td><textarea name='text' style='height:400px;'></textarea></td>
          </tr>
        </table>
        $in
        <br />        
      <button class='btn btn-success pull-right' id='sendMail' style='margin-right:70px;'>Send</button>
      </div>
    </form>
  </div>";
$contents.='
<script type="text/javascript" src="/admin/tiny_mce/tiny_mce.js"></script>
<script>
        tinyMCE.init({
            mode : "exact",
            elements : "text",
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
      </script>'; 