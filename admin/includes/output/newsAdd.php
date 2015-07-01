<?
$contents="
<script type='text/javascript' src='tiny_mce/tiny_mce.js'></script>
  <div class='shadow box filters' style='position:relative;min-height:385px;'>

    <form method='post' id='newsForm'>
      $edit
      <div style='position:relative;padding:10px;' class='pull-right'>
        <div class='youtube-video thumbnail' style='height:248px;'>
          <div>
            <div style='position:absolute;margin-left:5px;margin-right:5px;background: black; -moz-opacity: 0.8; opacity:0.8;width:260px;padding-bottom:1px;vertical-align:middle;color:white;' id='title-1'></div>
            <img src='/images/260x180.gif' id='preview-1'/>
          </div>
          <div style='padding:5px;padding-top:10px;'>
            <hr style='width:95%;margin:auto;border:2px inset #777'/>
            <input type='text' placeholder='Youtube Link' style='width:250px' class='navbar-search youtube' id='youtube-1' value='$y_v' />
            <input type='hidden' name='youtube-video-1' id='youtube-video-1' value='$y_v' />
          </div>
        </div>
      </div>
      <div style='vertical-align:top;color:white;padding-top:15px;' class='pull-left'>
        <textarea style='width:660px;height:348px;' name='news_en'>$data_en</textarea>
      </div>
      <!--div style='clear:both;'></div>
      <div style='vertical-align:top;color:white;' class='pull-left'>
        Русский:
        <textarea style='width:990px;height:348px;' name='news_ru'>$data_ru</textarea>
      </div-->
      <button class='btn btn-success pull-right' style='margin-top:55px;margin-right:50px;' id='newsadd'>Save</button>
    </form>
  </div>";