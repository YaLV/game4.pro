<?
if(isset($_GET['logout'])) {
  session_destroy();
  header("location:/admin/");
  exit();
}
if($_SESSION['id']) {
  $uid=$_SESSION['id'];
  $upw=get_reply("select password from admin_users where id='$uid'");
  $ip=$_SERVER['REMOTE_ADDR'];
  $session_accepted=crypt("$ip-$uid-$upw-$uid",$_SESSION['login_information']);
}
if($_SESSION['id'] && $session_accepted==$_SESSION['login_information']) {
  $menu="
      <form method='post' enctype='multipart/form-data' id='upload'>
        <input type='file' id='sliderUpload' name='slider' style='display:inline;visibility:hidden;'/>
      </form>
        <div class='navbar navbar-fixed-top navbar-shadow'>
        <div class='navbar-inner'>
          <div class='container'>
            <a class='brand' href='/admin/'>Games 4 Pro Admin</a>
              <ul class='nav'>
                <li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>Home <b class='caret'></b></a>
          		    <ul class='dropdown-menu'>
              			<li class='nav-header'>Sliders</li>
              			<li><a href='#' class='addSlider'>Add</a></li>
              			<li><a href='/admin/slider/list'>List</a></li>
                    <li class='nav-header'>Information</li>
              			<li><a href='/admin/disclaimers'>Disclaimers</a></li>
              			<li><a href='/admin/contacts'>Contacts</a></li>
          		    </ul>
                </li>
                <li><a href='/admin/sendmail' class='sendMail'>Mass Email</a></li>
                <li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>News <b class='caret'></b></a>
                  <ul class='dropdown-menu'>
                    <li><a href='/admin/news/add'>Add</a></li>
                    <li><a href='/admin/news/list'>List</a></li>
                  </ul>
                </li>
                <li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>Games <b class='caret'></b></a>
                  <ul class='dropdown-menu'>
                    <li><a href='/admin/games/add'>Add</a></li>
                    <li><a href='/admin/games/list'>List</a></li>
                    <li><a href='/admin/preorders?list'>Preorders</a></li>
                  </ul>
                </li>
                <li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>Users <b class='caret'></b></a>
                  <ul class='dropdown-menu'>
                    <li><a href='/admin/users?list'>List</a></li>
                    <li><a href='/admin/users?listRem' style='display:none;' id='RM'>List removed accounts</a></li>
                  </ul>
                </li>
                <li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>Settings <b class='caret'></b></a>
                  <ul class='dropdown-menu'>
                    <li><a href='/admin/settings/users'>Admin Users</a></li>
                    <li class='nav-header'>Translations</li>
                    <li><a href='/admin/settings/translation'>Page Texts</a></li>
                    <li><a href='/admin/settings/email_translation'>Email Messages</a></li>
                    <li class='nav-header'>Variables</li>
                    <li><a href='/admin/settings/vars'>Used Variables</a></li>
                    <li class='nav-header'>Mail messages</li>
                    <li><a href='/admin/settings/messages_sent'>List</a></li>
                  </ul>
                </li>
              </ul>
              <ul class='nav pull-right'>
                <li><a href='?logout'>Logout</a></li>
              </ul>
          </div>
        </div>
      </div>";
    include getcwd()."/includes/actions.php";
    include getcwd()."/includes/output.php";
} else {
  if(!$_POST['login']) {
    if($_SESSION['login_error']) {
      unset($_SESSION['login_error']);
      $err='<div class="alert alert-error">Username or password Incorrect</div>';
    } else {
      $err='<div class="alert alert-error invis">Username or password Incorrect</div>';
    }
    $menu="
      <section>
        <div class='navbar navbar-fixed-top navbar-shadow'>
          <div class='navbar-inner'>
            <div class='container'>
              <a class='brand' href='/admin/'>Games 4 Pro Admin</a>  
            </div>
          </div>
        </div>         
      </section>";
      
      $contents="
      <div class='box login-box shadow'>
        <form method='post'>
          <img src='/images/logo.png' />
          <br /><br />$err
          <input type='text' name='login_username' class='searchquery span2' placeholder='Username' />
          <input type='password' name='login_password' class='searchquery span2' placeholder='Password' />
          <br />
          <div class='center'><input type='submit' name='login' value='Login' class='btn btn-primary' /></div>
        </form>
      </div>
      ";
    } else {
      $login=get_reply("select username from admin_users where username='{$_POST['login_username']}'");
      $password=get_reply("select password from admin_users where username='{$_POST['login_username']}'");
      if($login && crypt($_POST['login_password'],$password)==$password) {
        $_SESSION['id']=get_reply("select id from admin_users where username='{$_POST['login_username']}'");
        $upw=$password;
        $ip=$_SERVER['REMOTE_ADDR'];
        $uid=$_SESSION['id'];
        $_SESSION['login_information']=crypt("$ip-$uid-$upw-$uid");
      } else {
        $_SESSION['login_error']=1;
      }         
      header("location:/admin/{$_GET['x']}");
      exit();
    }
}


?>