<?
$sql->query("select link,id from sliders where height='gamepage' and active='y' order by rand() limit 1");
$sql->get_row();
list($link,$image)=Array($sql->get_col(),"slider/slide_".$sql->get_col().".jpg");
  $content_top="
    <div id='slider' style='height: 200px;'>
    <div>
        <div class='clickClass' data-link='$link' style='position: absolute; background: url(/images/content_top.png) no-repeat -139px -105px transparent; width: 900px; height: 200px; overflow: hidden;'></div>
        </div>
        <img src='$image' />
    </div>";

$sql->query("select id,txt_$language from disclaimers where id!='help' and id!='help2'");
while($sql->get_row()) {
  $h=$sql->get_col()."_hdr";
  if($h!='contacts_hdr') {
    
    $data.="<div style='width: 820px;padding: 40px;color:#494949;''>
      <svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='40' width='600'>
        <defs>
          <style type='text/css'>
           <![CDATA[
            @font-face {
                font-family: days;
                src: url('/fonts/font_ru.ttf');
            }
            *.unselectable {
               -moz-user-select: -moz-none;
               -khtml-user-select: none;
               -webkit-user-select: none;
            
               /*
                 Introduced in IE 10.
                 See http://ie.microsoft.com/testdrive/HTML5/msUserSelect/
               */
               -ms-user-select: none;
               user-select: none;
               cursor:default;
            }
           ]]>
          </style>
        </defs>
        <text x='0' y='25' style='font-family: \"days\";font-size:25px;text-shadow: 1px 1px #FFFFFF;' class='unselectable'>{$message->text($h)}</text>
      </svg>
      <br /><br /><br />
        {$sql->get_col()}
      </div>
      <div style='background: url(images/spacer_zoom_2.png); width:942px; height:77px;position:absolute;margin: -16px 0 0 -18px;'></div>";
  }
}

$bdg = $message->text("menu_aboutus");

ob_start();
include getcwd()."/templates/about_us.php";
$outPut=ob_get_clean();
?>