<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="Game keys, game serials, buy now" />
  <meta name="keywords" content="games,keys,gold" />
  <title>Games 4 Pro // Homepage</title>
</head>
  <body style='margin:0px;<?=($_GET['direction']=='up' ? "height: 74px;width: 197px;" : "height: 74px;width: 182px;");?>'>
    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">
      <defs>
      <style type="text/css">
       <![CDATA[
        @font-face {
            font-family: tedays;
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
       <image x="0" y="0" width="197" height="74" xlink:href="/images/badge_<?=$_GET['direction'];?>.png" />
       <defs>
        <path id="path-up" d="M13 40 L180 32" />
        <path id="path-down" d="M13 47 L180 54" />
      </defs>
      <text x="10" y="100">
        <textPath xlink:href="#path-<?=$_GET['direction'];?>" style="font-family: 'tedays';font-size:25px;text-shadow: 1px 1px #FFFFFF;" class='unselectable'><?=$_GET['text'];?></textPath>
      </text>
    </svg>
  </body>
</html>