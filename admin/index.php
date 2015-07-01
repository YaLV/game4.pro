<?
session_start();
if($_GET['ascii']) {
  for($x=0; $x<200; $x++) {
    echo "$x: &#$x;<br />";
  }
}

if($_GET['x']=='upload/') {
  move_uploaded_file($_FILES['upim']['tmp_name'],dirname(__FILE__)."/upload/".$_FILES['upim']['name']);
  exit();
}
if($_FILES['slider']['tmp_name']) {
  move_uploaded_file($_FILES['slider']['tmp_name'],dirname(dirname(__FILE__))."/slider/slider.jpg") or die('false');
  $dim=getImageSize(dirname(dirname(__FILE__))."/slider/slider.jpg");
  header("location:/admin/slider/edit");
  exit();
}
include getcwd()."/includes/config.php";
include getcwd()."/includes/check.php";


?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Game keys, game serials, buy now" />
    <meta name="keywords" content="games,keys,gold" />
    <base href='/admin/'>
    <title>Games 4 Pro // Homepage</title>
    <link href="/admin/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="/admin/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/css/adminpanel.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  </head>
  <body data-spy="scroll" data-target=".subnav" data-offset="250" <?=$onload;?>>
    <div id='#curtains' class='curtains' style='display:none;'></div>
    <section>
    <?=$menu;?>
    </section>
    <section>
    <div class='contents'><?=$contents;?></div>
    </section>
    <script src="/admin/bootstrap/js/jquery.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-transition.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-alert.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-modal.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-dropdown.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-scrollspy.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-tab.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-tooltip.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-popover.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-button.js"></script>
    <script src="/admin/bootstrap/js/bootstrap-collapse.js"></script>
    <script src='/admin/js/caret.js'></script>
    <script src='/admin/js/main.js'></script>
    <?=$js;?>
    <div class='alert' style='position: absolute; display: none; z-index: 501; text-align: center; top:50px;'></div>
  </body>
</html>