<?

$sql->query("select id,link from sliders where active='y' and height='slider' order by rand()");
while($sql->get_row()) {
      $img=$sql->get_col();
      $link=$sql->get_col();
      $response[]['content']="
      <div class='slide_inner' style='position:relative;'>
        <a class='openthislink' href='$link'>
          <img class='photo' src='/slider/slide_$img.jpg' />
        </a>
      </div>";
}


echo json_encode($response);