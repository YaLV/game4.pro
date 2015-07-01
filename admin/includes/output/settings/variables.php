<?
$sql->query("select `key`,`value`,comment from settings");
while($sql->get_row()) {
  $keys=$sql->get_col();
  $val=json_decode($sql->get_col(),TRUE);
  $comment=$sql->get_col();
  if($keys=='levels') {
    $ret.="
      <tr>
        <td>User Levels:</td>
        <td>Count per item</td>
        <td>Item Count</td>
      </tr>        
    ";
    foreach($val as $k => $v) {
      $ret.="
      <tr>
        <td>Level $k</td>
        <td><input type='text' name='$keys"."[$k][count]' value='{$v['count']}' style='width:30px;'/></td>
        <td><input type='text' name='$keys"."[$k][itemCount]' value='{$v['itemCount']}' style='width:30px;'/></td>
      </tr>        
      ";
    }
    $ret.="<tr><td colspan='3'><hr /></td></tr>";
  } else {
    $frst="";
    $ln="";
    $line=0;
    foreach($val as $k => $v) {
      $line++;
      if(!$frst) {
        $frst="<td colspan='2'><input type='text' name='$keys"."[$k]' value='$v' /> $k</td>";
      } else {
        $ln.="<tr><td colspan='2'><input type='text' name='$keys"."[$k]' value='$v' /> $k</td></tr>";
      }
    }
      $ret.="
        <tr>
          <td rowspan='$line' style='vertical-align:top;padding-right:5px;'>$comment:</td>
          $frst
        </tr>
        $ln
        <tr><td colspan='3'><hr /></td></tr>";      
  }  
}
$contents="
    <div class='shadow box content' style='position:relative'>
      <form method='post' id='translation'>
        <table style='margin:auto;color:white;'>
          $ret
          <tr>
            <td colspan='2'><button class='btn btn-success' />Save</button></td>
          </tr>
        </table>
      </form>
    </div>
";
?>