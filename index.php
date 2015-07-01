<?php
session_start();
@include getcwd()."/includes/config.php";
include getcwd()."/includes/mm.php";
include getcwd()."/includes/actions.php";
include getcwd()."/includes/output.php";
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="Game keys, game serials, buy now" />
  <meta name="keywords" content="games,keys,gold" />
  <title>Games 4 Pro // Homepage</title>
  <link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
  <link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/reset.css">  
  <link rel="stylesheet" href="/css/slider.css">
  <link rel="stylesheet" href="/css/userpanel.css">
  <link rel="stylesheet" href="/css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  <!--[if IE]>
	 <link rel="stylesheet" type="text/css" href="css/ie.css" />
  <![endif]-->
  <script type="text/javascript">
  jQuery(document).ready(function(){
    if ((jQuery.browser.msie == true)&(jQuery.browser.version <=8)) 
    {
    	var deg2radians = Math.PI * 2 / 360,
    		deg = 2;
    		rad = deg * deg2radians,
    		costheta = Math.cos(rad),
    		sintheta = Math.sin(rad);
    		jQuery('.topmenurotate').css({
    				filter: 'progid:DXImageTransform.Microsoft.Matrix(M11='+costheta+', M12='+(-1)*sintheta+', M21='+sintheta+', M22='+costheta+', SizingMethod="auto expand", enabled=true)'
    			});
    		deg = -2.5;
    		rad = deg * deg2radians,
    		costheta = Math.cos(rad),
    		sintheta = Math.sin(rad);
    		jQuery('.order_button div').css({
    				filter: 'progid:DXImageTransform.Microsoft.Matrix(M11=-'+costheta+', M12=-'+(-1)*sintheta+', M21=-'+sintheta+', M22=-'+costheta+', SizingMethod="auto expand", enabled=true)'
    			});
    		deg = -30;
    		rad = deg * deg2radians,
    		costheta = Math.cos(rad),
    		sintheta = Math.sin(rad);
    		jQuery('.supersave').css({
    				filter: 'progid:DXImageTransform.Microsoft.Matrix(M11=-'+costheta+', M12=-'+(-1)*sintheta+', M21=-'+sintheta+', M22=-'+costheta+', SizingMethod="auto expand", enabled=true)'
    			});
    };
  });
  </script>
  <style type="text/css">
  .topmenurotate {
  	font-size: 16px;
  	line-height: 34px;
  	padding: 12px 0 0 35px;
  	-moz-transform: rotate(2deg);
  	-webkit-transform: rotate(2deg);
  	-o-transform: rotate(2deg);
  	-ms-transform: rotate(2deg);
  	transform: rotate(2deg);
  }
  .order_button div {
  	-moz-transform: rotate(-2.5deg);
  	-webkit-transform: rotate(-2.5deg);
  	-o-transform: rotate(-2.5deg);
  	-ms-transform: rotate(-2.5deg);
  	transform: rotate(-2.5deg);
  }
  .supersave {
  	-moz-transform: rotate(-30deg);
  	-webkit-transform: rotate(-30deg);
  	-o-transform: rotate(-30deg);
  	-ms-transform: rotate(-30deg);
  	transform: rotate(-30deg);
  }
  </style>
  </head>
  <body <?=$baseUpd;?>>
    <header>
      <div class="wrapper">
        <div id="langs"> 
            <!--a href="?setLang=en&amp;returnTo=<?=htmlspecialchars($_SERVER['REQUEST_URI']);?>" class='lang <?=($language=='en' ? active: "");?>'>en</a>
            <a href="?setLang=ru&amp;returnTo=<?=htmlspecialchars($_SERVER['REQUEST_URI']);?>" class='lang <?=($language=='ru' ? active: "");?>'>ru</a-->
        	<!--span style="font-size:20px;vertical-align: -2px;">&</span-->
            <a href="?setCurrency=eur&amp;returnTo=<?=htmlspecialchars($_SERVER['REQUEST_URI']);?>" class='currency <?=($currency=='eur' ? active : "");?>'>eur</a>
            <a href="?setCurrency=usd&amp;returnTo=<?=htmlspecialchars($_SERVER['REQUEST_URI']);?>" class='currency <?=($currency=='usd' ? active : "");?>'>usd</a>
            <a href="?setCurrency=gbp&amp;returnTo=<?=htmlspecialchars($_SERVER['REQUEST_URI']);?>" class='currency <?=($currency=='gbp' ? active : "");?>'>gbp</a>
            <!--a href="?setCurrency=rub&amp;returnTo=<?=htmlspecialchars($_SERVER['REQUEST_URI']);?>" class='currency <?=($currency=='rub' ? active : "");?>'>rub</a-->
        </div>
        <div class='src pull-left'><form method='get' action='/Games'><input type='text' name='search' id='search' value='<?=$_GET['search'];?>'/>&nbsp;<button class='btn btn-info btn-src'>Search</button></form></div>
        <form method='post' id="loginform">
        <?=$loginform;?>
        </form>
      </div>
    </header>
    <div class="wrapper">
      <div id="content_top" style='z-index:90;'></div>
      <div id="content" style='z-index:90;'>
        <div id="logo" style='z-index:90;'></div>
        <nav style='z-index:90;'>
          <div class="topmenurotate" style='z-index:90;'>
            <ul  style='z-index:90;'>
            	<li><a href="./"><?=$message->text('menu_home');?></a></li>
                <li><a href="Games"><?=$message->text('menu_games');?></a></li>
                <li><a href="Preorder"><?=$message->text('menu_preorder');?></a></li>
                <li><a href="SuperSave"><?=$message->text('menu_supersave');?></a></li>
                <li><a href="AboutUs"><?=$message->text('menu_aboutus');?></a></li>
                <li><a href="Contacts"><?=$message->text('menu_contacts');?></a></li>
            </ul>
          </div>
        </nav>
        <?=$outPut;?>
        <div id="news">
          <div class='newsSels pull-right' style='padding-right:20px;margin-top:5px;'>
            <div style='float:right;'><a href='javascript:;' class='newsChange' id='next'><?=$message->text('direction_next');?></a></div>
            <div style='float:right;margin-right:10px;'><a href='javascript:;' class='newsChange' id='previous'><?=$message->text('direction_prev');?></a></div>
          </div>
          <br>
          <div id='allNews'>
            <div id="video_block" style='padding-left:15px;'>
              <div id='newsVid'></div>
            </div>
            <div id="news_block">
              <span id="news_title"><?=$message->text('news');?></span>
              <div style='padding:5px;' id='newsText'><?=$news_data?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer> 
    <a href="http://www.energy.lv" target="_blank" id='byenergy'></a>
    <div style='float:left;background:white;width:120px;height:41px;'><div class='accepting' style='float:left;background:white;width:60px;margin-top:10px;height:21px;background: url(/images/visa1.jpeg);'></div><div class='accepting' style='float:left;background:white;width:60px;height:41px;background: url(/images/Mastercard1.jpg);'></div></div>
    <div style='padding-bottom:20px;'><?=$message->text('copyright');?></div>
    </footer>
    <div id='curtains'>&nbsp;</div>
    <script type='text/javascript' src='/js/slider.js'></script>
    <script type="text/javascript" src="/js/main.js"></script>
    <script type="text/javascript" src="/js/cart.js"></script>
    <script type="text/javascript" src="/js/swfobject.js"></script>
    <script src='/bootstrap/js/bootstrap-tab.js'></script>
    <?=($_GET['x']=='recover' ? "<script type='text/javascript' src='/js/recover.js'></script>" : "");?>
  </body>
</html>
