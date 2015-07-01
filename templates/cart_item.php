<?
echo "
<tr id='line-$id'>
  <td style='width:20px;'><img src='gameImages/$id/$cover' style='height:30px;'/></td>
  <td>$name</td>
  <td style='width:80px;'>$price_per_unit_show</td>
  <td nowrap='nowrap' style='width:75px;'>$minus <div class='pull-left' id='count_$id' style='width:20px;text-align:center;'>$count</div> $plus</td>
  <td style='width:80px;'>$rem $price_show</td>
</tr>";