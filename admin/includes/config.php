<?
$mysql['db']='game4pro_site';
$mysql['host']='localhost';
$mysql['user']='game4pro_user';
$mysql['pass']='16sTaQut1P0S';
include getcwd()."/includes/sql.inc.php";

function buildSQL($data,$var) {
  global $me;
  $first=true;
  $query="select [field] from [table] where [search]";
  if($data['table'] && $data['field']) {
    foreach($data as $k => $v) {
      if($k[0]!="=" && $k[0]!="-") {
        $query=preg_replace("/\[$k\]/",$v,$query);
      } elseif($k[0]=='=') {
        $vv=($var[$v] ? $var[$v] : $v);
        if($first) { 
          $fld=substr($k,1,strlen($k)); 
          if(strpos($var[$v],".")>0) { 
            $search="$fld={$var[$v]}"; 
          } else { 
            $search="$fld='$vv'"; 
          } 
          $first=false; 
        } else { 
          $fld=substr($k,1,strlen($k)); 
          if(strpos($var[$v],".")>0) { 
            $search.=" and $fld={$var[$v]}"; 
          } else { 
            $search.=" and $fld='$vv'"; 
          } 
        }
      } elseif($k[0]=='-') {
        $addon.=" $v";
      }
    }
  } 
  return preg_replace("/\[search\]/",$search,$query);
}

if(isset($_GET['send'])) {
  include getcwd()."/../includes/mm.php";



  exit();
}