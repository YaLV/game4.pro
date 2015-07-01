<?
$sql->query("select cid,codeImage,`reserved`,(select email from users where users.id=`owned`),`owned` from codes where gid='{$fnd[1]}' order by `owned` ASC");

while($sql->get_row()) {
  $data[] = Array(
    'cid' => $sql->get_col(),
    'tcode' => $sql->get_col(),
    'reserved' => $sql->get_col(),
    'owned' => $sql->get_col(),
    'owner' => $sql->get_col()
  );
}

echo json_encode($data);
exit();