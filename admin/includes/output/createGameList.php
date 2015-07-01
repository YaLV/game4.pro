<?
$sql->query("select id,(select id from best_sales where best_sales.gameID=games.id),active,price_eur,name_en,genre,age,(select count(*) from codes where gid=games.id),(select count(*) from codes where gid=games.id and reserved='0') from games order by id DESC");
while($sql->get_row()) {
  $id=$sql->get_col();
  $bss=$sql->get_col();
  $active=($bss ? "active" : "");
  $bs=($bss ? "On" : "Off");
  $acton=$sql->get_col();
  $aon=($acton=='y' ? "Dea" : "A");
  $aac=($acton=='y' ? "active" : "");
  if($id!=1) { 
    $data['games'][]=Array(
        'id' => $id,
        'price' => $sql->get_col(),
        'name' => $sql->get_col(),
        'genre' => preg_replace("/,/",", ",$sql->get_col()),
        'age' => $sql->get_col(),
        'count' => $sql->get_col(),
        'countWithoutReserved' => $sql->get_col(),
        'ac' => $active,
        'bs' => $bs,
        'aac' => $act,
        'aon' => $aon
    );
  }  
} 
echo json_encode($data);
exit();