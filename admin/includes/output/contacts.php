<?
if(isset($_GET['save'])) {
  $fields="txt_en='".$_POST["contacts_en"]."', txt_ru='".$_POST["contacts_ru"]."'";
  $sql->query("update disclaimers set $fields where id='contacts'") or $error=1;
  if(!$error) {
    echo json_encode(Array("success" => true, "result" => "Contacts have been saved"));
    exit();
  } else {
    echo json_encode(Array("success" => false, "result" => "Error Saving Disclaimers"));
    exit();
  }
}
$sql->query('select txt_en,txt_ru from disclaimers where id="contacts"');
$sql->get_row();
$contents="
<script type='text/javascript' src='tiny_mce/tiny_mce.js'></script>  <div class='shadow box content' style='position:relative;height:520px;'>
    <form method='post' id='dataForm'>
      <button class='btn btn-success pull-right' id='submitForm' style='margin-right:20px;'>Save</button>
      <div class='pull-left disclaimer-win'>
        <table>
          <tr>
            <td>English</td>
            <td><textarea name='contacts_en'>".$sql->get_col()."</textarea></td>
          </tr>
          <!--tr>
            <td>Русский</td>
            <td><textarea name='contacts_ru'>".$sql->get_col()."</textarea></td>
          </tr-->
        </table>        
      </div>
    </form>
  </div>";