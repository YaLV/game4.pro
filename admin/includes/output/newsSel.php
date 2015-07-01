<?
if($fnd[1]) {
  $sql->query("select data_en, data_ru, video from news where id='{$fnd[1]}'");
  $sql->get_row();
  $data_en=$sql->get_col();
  $data_ru=$sql->get_col();
  $y_v=$sql->get_col();
  $edit="<input type='hidden' name='news_id' value='{$fnd[1]}' />";
}