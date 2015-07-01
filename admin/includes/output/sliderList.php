<?
$tableContents="";
$tip=Array("slider" => "Main Page", "gamepage" => "Game Page", "preorderpage" => "Preorder Page", "savingpage" => "SuperSavings Page");
$sql->query("select id,link,active,height from sliders order by id DESC");
while($sql->get_row()) {
  $id=$sql->get_col();
  $link=$sql->get_col();
  $active=$sql->get_col();
  $tp=$sql->get_col();
  $type=$tip[$tp];
  $image="<div style='display:none;position:absolute;top:0px;left:0px;' id='img-$id'><img src='../slider/slide_$id.jpg' style='width:605px;'/></div>";
  $de=($active=='n' ? "A" : "Dea");
  $ac=($active=='n' ? "" : "active");
  $link=(!$link ? 'No Link present' : $link);
  $d=($tp=='slider' ? "disabled" : "");
  $type="
    <div class='btn-group' style='width:100%;'>
      <button class='btn btn-inverse filter-btn dropdown-toggle' $d id='pageSel-$id' data-toggle='dropdown'>$type</button>
      <button class='btn btn-inverse dropdown-toggle' $d data-toggle='dropdown'>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu'>
        <li><a href='javascript:void(0);' id='p-gamepage' data-id='$id'>Game Page</a></li>
        <li><a href='javascript:void(0);' id='p-preorderpage' data-id='$id'>Preorder Page</a></li>
        <li><a href='javascript:void(0);' id='p-savingpage' data-id='$id'>SuperSavings Page</a></li>
      </ul>
    </div>";
  $tableContents.="
      <tr>
        <td>$type</td>
        <td id='lnk-$id'><div id='lnk-text-$id' style='padding-left:10px;' class='pull-left'>$link</div>$image<div class='pull-right'><button class='btn btn-mini btn-warning' id='editSlider-$id'>Edit</button></div></td>
        <td class='control-$id'><button class='btn btn-success $ac btn-mini activateSlider' style='width:70px;' id='ac-$id'>".$de."ctivate</button><button class='btn btn-success btn-mini viewImage' id='$id'>View Image</button><button class='btn btn-danger btn-mini removeSlider' id='$id'>Remove</button></td>
      </tr>
  ";
}
$contents="
<div class='shadow box filters'>
  <div class='btn-toolbar' style='margin: 0px;margin-top:2px;'>                                                                                                                                
    <table style='color:white;margin:auto;border:1px solid white;border-collapse:collapse;' border='1'>
      <tr>
        <td style='width:170px;'>Location</td>
        <td style='width:600px;'>Links</td>
        <td style='width:200px;'>Options</td>
      </tr>
      $tableContents
    </table>
  </div>
</div>";