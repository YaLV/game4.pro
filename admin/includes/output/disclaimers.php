<?
if(isset($_GET['save'])) {
  $flds=Array('bonuses','preorder','purchase','help','help2');
  foreach($flds as $field) {
    $fields="txt_en='".$_POST[$field."_en"]."', txt_ru='".$_POST[$field."_ru"]."'";
    $sql->query("update disclaimers set $fields where id='$field'") or $error=1;
  }
  if(!$error) {
    echo json_encode(Array("success" => true, "result" => "Disclaimers have been saved"));
    exit();
  } else {
    echo json_encode(Array("success" => false, "result" => "Error Saving Disclaimers"));
    exit();
  }
}
$sql->query('select id,txt_en,txt_ru from disclaimers');
while($sql->get_row()) {
    $d[$sql->get_col()]=Array('en' => $sql->get_col(), 'ru' => $sql->get_col());
}
$contents="
<script type='text/javascript' src='tiny_mce/tiny_mce.js'></script>
  <div class='shadow box content' style='position:relative;height:520px;'>
    <form method='post' id='dataForm'>
      <div class='btn-group pull-left' data-toggle='buttons-radio' style='padding-left:20px;'>
        <button class='btn active btn-success disclaimer' id='preorder'>Preorder disclaimer</button>
        <button class='btn btn-inverse disclaimer' id='bonuses'>Bonuspoint disclaimer</button>
        <button class='btn btn-inverse disclaimer' id='purchase'>Purchase disclaimer</button>
        <button class='btn btn-inverse disclaimer' id='help'>Help Page</button>
        <button class='btn btn-inverse disclaimer' id='help2'>Help2 Page</button>
      </div>      
      <button class='btn btn-success pull-right' id='submitForm' style='margin-right:20px;'>Save</button>
      <div class='pull-left preorder disclaimer-win'>
        <table>
          <tr>
            <td>English</td>
            <td><textarea name='preorder_en'>{$d['preorder']['en']}</textarea></td>
          </tr>
          <!--tr>
            <td>Русский</td>
            <td><textarea name='preorder_ru'>{$d['preorder']['ru']}</textarea></td>
          </tr-->
        </table>        
      </div>
      <div class='pull-left bonuses disclaimer-win'>
        <table>
          <tr>
            <td>English</td>
            <td><textarea name='bonuses_en'>{$d['bonuses']['en']}</textarea></td>
          </tr>
          <!--tr>
            <td>Русский</td>
            <td><textarea name='bonuses_ru'>{$d['bonuses']['ru']}</textarea></td>
          </tr-->
        </table>        
      </div>  
      <div class='pull-left purchase disclaimer-win'>
        <table>
          <tr>
            <td>English</td>
            <td><textarea name='purchase_en'>{$d['purchase']['en']}</textarea></td>
          </tr>
          <!--tr>
            <td>Русский</td>
            <td><textarea name='purchase_ru'>{$d['purchase']['ru']}</textarea></td>
          </tr-->
        </table>        
      </div>  
      <div class='pull-left help disclaimer-win'>
        <table>
          <tr>
            <td>English</td>
            <td><textarea name='help_en'>{$d['help']['en']}</textarea></td>
          </tr>
          <!--tr>
            <td>Русский</td>
            <td><textarea name='help_ru'>{$d['help']['ru']}</textarea></td>
          </tr-->
        </table>        
      </div>  
      <div class='pull-left help2 disclaimer-win'>
        <table>
          <tr>
            <td>English</td>
            <td><textarea name='help2_en'>{$d['help2']['en']}</textarea></td>
          </tr>
          <!--tr>
            <td>Русский</td>
            <td><textarea name='help2_ru'>{$d['help2']['ru']}</textarea></td>
          </tr-->
        </table>        
      </div>  
  
    </form>
  </div>";