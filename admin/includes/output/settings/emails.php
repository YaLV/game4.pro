<?
$sql->query("select message_en,message_ru,identify,modifiers,message_for from translation_emails order by id asc");
while($sql->get_row()) {
  $m_en=$sql->get_col();
  $m_ru=$sql->get_col();
  $id=$sql->get_col();
  $mods=json_decode($sql->get_col());
  $msg=$sql->get_col();
  $vars="";
  foreach($mods as $k => $v) {
    $vars.="<strong style='padding-left:20px;'>[$k]</strong> <br />";
  }
  $transl.="
    <tr>
      <td style='color:white;vertical-align:top;padding-right:10px;font-weight:bold;'>$msg:</td>
      <td><textarea style='width:350px;height:100%;min-height:200px;' name=\"{$id}[en]\">$m_en</textarea></td>
      <!--td><textarea style='width:350px;height:100%;min-height:200px;' name=\"{$id}[ru]\">$m_ru</textarea></td-->
      <td style='vertical-align:top;color:white;text-align:left;'>Used variables:<br />$vars</td>
    </tr>
  ";
}

$contents="
    <div class='shadow box content' style='position:relative'>
      <form method='post' id='translation'>
        <table style='margin:auto;'>
          <tr>
            <td></td>
            <td style='color:white;text-align:center;'>English</td>
            <!--td style='color:white;'>Russian</td-->
          </tr>
          $transl
          <tr>
            <td colspan='2'><button class='btn btn-success' />Save</button></td>
          </tr>
        </table>
      </form>
    </div>
";

?>