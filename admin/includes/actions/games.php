<?
$fieldNames=Array(
  "name-en" => 'Game Name (English)',
//  "name-ru" => 'Game Name (Russian)',
  "date-en" => 'Release date (English)',
//  "date-ru" => 'Release date (Russian)',
  "platform-en" => 'Platform (English)',
//  "platform-ru" => 'Platform (Russian)',
  "languages-en" => 'Languages (English)',
//  "languages-ru" => 'Languages (Russian)',
  "price-eur" => 'Price (Eur)',
  "price-usd" => 'Price (USD)',
  "price-gbp" => 'Price (GBP)',
//  "price-rub" => 'Price (RUB)',
  "description-en" => 'Description (English)',
//  "description-ru" => 'Description (Russian)',
  "sysreq-en" => 'System Requirements (English)',
//  "sysreq-ru" => 'System Requirements (Russian)',
  "age" => 'Age Rating',
  "genre" => 'Genres',
  "youtube-video-1" => 'Youtube Video 1',
  "youtube-video-2" => 'Youtube Video 1',
  "youtube-video-3" => 'Youtube Video 1',
  "points" => 'Bonus points'
);

$game_add_fields= Array(
  "name-en" => '',
//  "name-ru" => '',
  "date-en" => '/^\d\d\/\d\d\/\d\d\d\d$/',
//  "date-ru" => '/^\d\d\/\d\d\/\d\d\d\d$/',
  "platform-en" => '',
//  "platform-ru" => '',
  "languages-en" => '',
//  "languages-ru" => '',
  "price-eur" => '/^[\d]+\.[\d]{1,2}|[\d]+$/',
  "price-usd" => '/^[\d]+\.[\d]{1,2}|[\d]+$/',
  "price-gbp" => '/^[\d]+\.[\d]{1,2}|[\d]+$/',
//  "price-rub" => '/^[\d]+\.[\d]{1,2}|[\d]+$/',
  "description-en" => '',
//  "description-ru" => '',
  "sysreq-en" => '',
//  "sysreq-ru" => '',
  "age" => '/^[\d]{1,2}$/',
  "genre" => '/^[a-zA-Z,]+$/',
  "youtube-video-1" => '/[^&]{5,15}/',
  "youtube-video-2" => '/[^&]{5,15}/',
  "youtube-video-3" => '/[^&]{5,15}/',
  "points" => '/[\d]+/'
);

$game_req_fields=Array("name-en","date-en","platform-en","languages-en","price-eur","age","genre");

if($_GET['x']=='games/action/save') {
  $accept=true;
  $returndata=array();
  foreach($_POST as $k => $val) {
    if(array_key_exists($k,$game_add_fields)) {
      if($game_add_fields[$k]!='') {
        if(trim($val)!='') { preg_match($game_add_fields[$k],trim($val),$found) or die($k); } else { $val=""; }
      } else {
        $found[0]=trim($val);
      }
      if($found[0]==trim($val) && $val!='' || preg_match("/youtube-video/",$k)) {
        $returndata[$k]=htmlspecialchars($val);
        $accepted['success'][]=$k;
      } else {
        $accepted['fail'][]=$k;
        $returndata[$k]='';
        if($_POST['saveWithErrors']!='1') {
          $accept==false;
        }
      } 
    }
  }
  $accepted['added']=false;
  if($accept==true && ($_POST['saveWithErrors']==1 || count($accepted['fail'])==0)) {
    if(!$_POST['gameId']) {
      $sql->query("insert into games (id,active) values('','n')");
      $gameId=get_reply("select last_insert_id() from games");
      $ss=($_POST['superSave'] ? $_POST['SuperSave'] : 0);
      $sse=($_POST['superSavingEnabled'] ? $_POST['SuperSavingEnabled'] : 0);
      $sql->query("insert into super_savings values('','$gameId','$ss','$sse')");
    } else {
      $gameId=$_POST['gameId'];
      $sql->query("update super_savings set percent='{$_POST['SuperSave']}', active='{$_POST['SuperSavingEnabled']}' where gameID='$gameId'");
    }
    if($_POST['sendmail']) {
      $sql->query("select subject_en,subject_ru,message_en,message_ru from translation_emails where identify='new_game'");
      $sql->get_row();
      $p=spliti("\/",$_POST['date-en']);
      $gten=mktime(0,0,0,$p[1],$p[0],$p[2]);
      //$p=spliti("/",$_POST['date-ru']);
      //$gtru=mktime(0,0,0,$p[1],$p[0],$p[2]);
      $tten=(time()>$gten ? 'Games' : "Preorder");
      $ttru=(time()>$gtru ? 'Games' : "Preorder");
      $mods=json_encode(Array(
        'name' => array(
          'datatype' => 'sql',
          'data' => array(
            'table' => 'users',
            'field' => 'name',
            '=id' => 'uid'
          )
        ),
        'link_en' => array(
          'datatype' => 'vars',
          'data' => 'http://game4.pro/'.$tten.'?viewGame='.$gameId,
        ),
        'link_ru' => array(
          'datatype' => 'vars',
          'data' => 'http://game4.pro/'.$ttru.'?viewGame='.$gameId,
        ),
        'link' => array(
          'datatype' => 'vars',
          'data' => 'link'
        ),
        'game' => array(
          'datatype' => 'vars',
          'data' => 'game'
        )
      ));
      list($s_en,$s_ru,$m_en,$m_ru)=Array($sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col());
      $sql->query("insert into mail_send values('','$s_en','$s_ru','$m_en','$m_ru','$mods','$gameId','0','0','new_game')");
    }
    $fields='';
    $yv='';
    foreach($returndata as $k => $v) {
      $fields.=preg_replace("/-/","_",$k).'="'.htmlspecialchars($v).'",';
    }
    $images="";
    $cover=get_reply("select cover from games where id='$gameId'");    
    $sql->query("update games set $fields images='',cover='' where id='$gameId'");
    $accepted['added']=true;
    $accepted['id']=$gameId;
    if(is_array($_POST['saveImage'])) {
      if(!file_exists(dirname(getcwd())."/gameImages/$gameId")) {
        mkdir(dirname(getcwd())."/gameImages/$gameId");
      }
      foreach($_POST['saveImage'] as $k => $val) {
        $path=dirname(getcwd());
        if($val!='' && file_exists($path."/admin/upload/$val")) {
          $filename=round(rand(1000,9999));
          $ext="jpg";
          while(file_exists($path."/gameImages/$gameId/$filename.$ext")) { $filename=round(rand(1000,9999)); }
          $file=file_get_contents($path."/admin/upload/$val") or die('cannot read file');
          list($headers,$image) = spliti(",",$file,2);
          $f=fopen($path."/gameImages/$gameId/$filename.$ext","w+") or die('cannot open file');
          fwrite($f,base64_decode($image));
          fclose($f);          
          unlink($path."/admin/upload/$val");
          ob_start();
          echo "$val ($k) -> $filename.$ext ({$_POST['cover'][$k]})\n";
          $debug=ob_get_clean();
          $f=fopen("log","a+");
          fwrite($f,$debug);
          fclose($f);
          $images.="$filename.$ext\n";
          if($_POST['cover'][$k]==1) {
            $cover=$filename.".".$ext;
          } 
        } elseif(file_exists($path."/gameImages/$gameId/$val")) {
          if($_POST['cover'][$k]==1) {
            $cover=$val;
          }
          $images.="$val\n"; 
        }
      }
      $sql->query("update games set images='$images', cover='$cover' where id='$gameId'");
    }
  }
  $accepted['result']="";
  if(count($accepted['hardFail'])>0) {
    $accepted['result']="These <strong>required</strong> fields are not set:<br /><hr />";
    foreach($accepted['hardFail'] as $val) {
      $valz=$fieldNames[$val];
      $accepted['result'].=" - $valz<br />";
    }
  }
  $accepted['result'].="</td><td style='vertical-align:top;text-align:left;padding:5px;'>";
  if(count($accepted['fail'])>0) {
    $accepted['result'].="These optional fields are not set:<br /><hr />";
    foreach($accepted['fail'] as $val) {
      $valz=$fieldNames[$val];
      $accepted['result'].=" - $valz<br />";
    }        
  }
  $accepted['result']="<table style='padding:5px;'><tr><td style='vertical-align:top;border-right:1px solid black;text-align:left;padding:5px;'>{$accepted['result']}</td></tr></table>";  
  echo json_encode($accepted);
  exit(); 
} 