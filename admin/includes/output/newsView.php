<?
$contents="
  <div class='shadow box filters' style='position:relative;min-height:300px;'>

    <form method='post' id='newsForm'>
      $edit
      <div style='position:relative;padding:10px;' class='pull-right'>
        <div class='youtube-video thumbnail' style='height:208px;'>
          <div>
            <div style='position:absolute;margin-left:5px;margin-right:5px;background: black; -moz-opacity: 0.8; opacity:0.8;width:260px;padding-bottom:1px;vertical-align:middle;color:white;' id='title-1'></div>
            <img src='/images/260x180.gif' id='preview-1'/>
          </div>
          <div style='padding:5px;padding-top:10px;'>
            <input type='hidden' placeholder='Youtube Link' style='width:250px' class='navbar-search youtube' id='youtube-1' value='$y_v' />
            <input type='hidden' name='youtube-video-1' id='youtube-video-1' value='$y_v' />
          </div>
        </div>
      </div>
      <div style='vertical-align:top;color:white;padding-top:15px;' class='pull-left'>
        <div class='btn btn-inverse active' disabled style='width:660px;margin-left:10px;'>$data_en</div>
      </div>
      <div style='clear:both;'></div>
      <!--div style='vertical-align:top;color:white;' class='pull-left'>
        Русский:
        <div class='btn btn-inverse active' disabled style='width:800px;'>$data_ru</div>
      </div-->
      <button class='btn btn-warning pull-right' style='margin-top:25px;margin-right:50px;' id='newsedit'>Edit</button>
    </form>
  </div>";