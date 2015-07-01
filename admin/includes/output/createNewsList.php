<?
$result="";
$y=0;
$sql->query("select id,data_en,active from news order by id DESC");
while($sql->get_row()) {
  $id=$data=$date="";
  $id=$sql->get_col();
  $data=explode(" ",$sql->get_col());
  $state=$sql->get_col();
  unset($dataJoin);
  for($x=0;$x<15;$x++) {
    $dataJoin[]=$data[$x];
  }
  $data=implode(" ",$dataJoin);
  $date=date("d/m/Y",$id);
  $result[$y]['id']=$id;
  $result[$y]['date']=$date;
  $result[$y]['news']=$data;
  $result[$y]['state']=$state;
  $y++;
}
echo json_encode($result);
exit();