<?
$sql->query("select message_en,message_ru,identifier from translations order by id asc");
while($sql->get_row()) {
  $m_en=$sql->get_col();
  $m_ru=$sql->get_col();
  $id=$sql->get_col();
  $transl.="
    <tr>
      <td><textarea style='width:350px;' name=\"{$id}[en]\">$m_en</textarea></td>
      <!--td><textarea style='width:350px;' name=\"{$id}[ru]\">$m_ru</textarea></td-->
    </tr>
  ";
}

$contents="
    <div class='shadow box content' style='position:relative'>
      <form method='post' id='translation'>
        <table style='margin:auto;'>
          <tr>
            <td style='color:white;text-align:center;'>English</td>
            <td style='color:white;'>Russian</td>
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