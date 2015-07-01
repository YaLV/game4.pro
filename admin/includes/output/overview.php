<?
$contents="
<div class='shadow box content' style='position:relative'>
<form method='post' id='addForm'>

  <table style='color:white;margin:auto;width:1000px' class='editTable'>
    <tr>
      <td colspan='4' style='text-align:center;'>
        <!--div class='btn-group pull-left' data-toggle='buttons-radio'>
          <button class='btn active pull-left btn-success language' id='en'>English</button>
          <button class='btn pull-left language btn-inverse' id='ru'>Русский</button>
        </div-->
        <div><button class='btn btn-warning pull-right' onclick=\"document.location='games/edit/$id';return false;\">Edit</button></div>
      <td>
    </tr>
    <tr>
      <td style='width:100px;'><div id='name-label-en'>Game Name:</div><div id='name-label-ru' style='display:none;'>Название:</div></td> 
      <td>
        <div id='name-en' class='btn active pull-left btn-inverse' style='min-width:190px;text-align:left;'>".$sql->get_col()."</div>
        <div id='name-ru' class='btn active pull-left btn-inverse' style='display:none;min-width:190px;text-align:left;'>".$sql->get_col()."</div>
      </td>
      <td rowspan='4' style='vertical-align:top;padding-left:10px;width:180px;text-align:right;'>
        <div id='sysreq-label-en'>System Requirements:</div>
        <div id='sysreq-label-ru' style='display:none;'>Системные требования:</div>
      </td>
      <td rowspan='4' style='vertical-align:top;'>
        <div id='sysreq-en' class='btn active pull-left btn-inverse area' style='width:440px;min-height:20px;max-height:110px;text-align:left;overflow-y:auto;'>".nl2br($sql->get_col())."</div>
        <div id='sysreq-ru' class='btn active pull-left btn-inverse area' style='display:none;width:440px;max-height:110px;text-align:left;overflow-y:auto;'>".nl2br($sql->get_col())."</div>
      </td>
    </tr>
    <tr>
      <td><div id='date-label-en'>Release Date:</div><div id='date-label-ru' style='display:none;'>Дата Выпуска:</div></td> 
      <td style='width:220px;'>
        <div id='date-en' class='btn active pull-left btn-inverse' style='min-width:190px;text-align:left;'>".$sql->get_col()."</div>
        <div id='date-ru' class='btn active pull-left btn-inverse' style='display:none;min-width:190px;text-align:left;'>".$sql->get_col()."</div>
      </td>
    </tr>
    <tr>
      <td><div id='platform-label-en'>Platform:</div><div id='platform-label-ru' style='display:none;'>Платформа:</div></td> 
      <td>
        <div id='platform-en' class='btn active pull-left btn-inverse' style='min-width:190px;text-align:left;'>".$sql->get_col()."</div>
        <div id='platform-ru' class='btn active pull-left btn-inverse' style='display:none;min-width:190px;text-align:left;'>".$sql->get_col()."</div>
      </td>
    </tr>
    <tr>
      <td><div id='languages-label-en'>Languages:</div><div id='languages-label-ru' style='display:none;'>Языки:</div></td> 
      <td>
        <div id='languages-en' class='btn active pull-left btn-inverse' style='min-width:190px;text-align:left;'>".$sql->get_col()."</div>
        <div id='languages-ru' class='btn active pull-left btn-inverse' style='display:none;min-width:190px;text-align:left;'>".$sql->get_col()."</div>
      </td>
    </tr>
    <tr>
      <td style='vertical-align:top;'>Price:</td>
      <td style='text-align:left;'>
        <!--div id='price-rub' class='pull-left btn active pull-left btn-inverse' style='min-width:82px;margin-right:3px;'> RUB</div-->
        <div id='price-gbp' class='pull-left btn active pull-left btn-inverse' style='min-width:82px;'>".$gbp." GBP</div>
        <div id='price-usd' class='pull-left btn active pull-left btn-inverse' style='min-width:82px;margin-right:3px;'>".$usd." USD</div>
        <div id='price-eur' class='pull-left btn active pull-left btn-inverse' style='min-width:82px;'>".$eur." Eur</div>
      </td>
      <td colspan='2'>
        <div class='btn-toolbar' style='margin: 0px;margin-top:2px;'>
          $agez
        </div>
        <div class='btn-group'>
          $genrez
        </div>
      </td>
    </tr>
    <tr>
      <td>Bonus Points:</td>
      <td><div class='pull-left btn active pull-left btn-inverse' style='min-width:82px;margin-right:3px;'>$points</div></td>
      <td colspan='2' style='text-align:center;'><div class='pull-left' style='height:40px;width:220px;padding-left:20px;'><button class='pull-left btn btn-inverse btn-ss' disabled>SuperSaving</button><input type='hidden' name='SuperSavingEnabled' id='sse' value='$ss'/><input type='text' readonly id='ss' style='display:none;width:50px;' value='$ssv' name='SuperSave' /></div></td>
    </tr>
    <tr>
      <td style='vertical-align:top;padding-left:10px;'><div id='description-label-en'>Description:</div><div id='description-label-ru' style='display:none;'>Описание:</div></td>
      <td colspan='3' style='vertical-align:top;'>
        <div id='description-en' class='btn active pull-left btn-inverse area desc' style='width:793px;text-align:left;max-height:155px;overflow-y:auto;'>".nl2br($sql->get_col())."</div>
        <div id='description-ru' class='btn active pull-left btn-inverse area desc' style='display:none;width:793px;text-align:left;max-height:155px;overflow-y:auto;'>".nl2br($sql->get_col())."</div>
      </td>
    </tr>

    <tr>
      <td colspan='6' style='text-align:center;padding:5px;padding-bottom:10px;'><hr style='width:95%;margin:auto;border:2px inset #777'></td>
    </tr>
    <tr>
      <td style='vertical-align:top;'>Videos: </td>
      <td colspan='5' style='text-align:center;'>
        <div>
          <div class='youtube-video thumbnail'>
            <div>
              <div style='position:absolute;margin-left:5px;margin-right:5px;background: black; -moz-opacity: 0.8; opacity:0.8;width:260px;padding-bottom:1px;vertical-align:middle' id='title-1'></div>
              <img src='/images/260x180.gif' id='preview-1'/>
            </div>
            <div style='padding:5px;padding-top:10px;'>
              <hr style='width:95%;margin:auto;border:2px inset #777'/>
              <input type='text' placeholder='Youtube Link' style='width:250px' class='navbar-search youtube' id='youtube-1' readonly value='".$sql->get_col()."' />
            </div>
          </div>
          <div class='youtube-video thumbnail' style='text-align:center;margin-left:5px;'>
            <div>
              <div style='position:absolute;margin-left:5px;margin-right:5px;background: black; -moz-opacity: 0.8; opacity:0.8;width:260px;padding-bottom:1px;vertical-align:middle' id='title-2'></div>
              <img src='/images/260x180.gif' id='preview-2'/>
            </div>
            <div style='padding:5px;padding-top:10px;'>
              <hr style='width:95%;margin:auto;border:2px inset #777'/>
              <input type='text' placeholder='Youtube Link' style='width:250px' class='navbar-search youtube' id='youtube-2' readonly value='".$sql->get_col()."' />
              <input type='hidden' name='youtube-video-2' id='youtube-video-2' />
            </div>
          </div>
          <div class='youtube-video thumbnail' style='text-align:center;margin-left:5px;'>
            <div>
              <div style='position:absolute;margin-left:5px;margin-right:5px;background: black; -moz-opacity: 0.8; opacity:0.8;width:260px;padding-bottom:1px;vertical-align:middle' id='title-3'></div>
              <img src='/images/260x180.gif' id='preview-3'/>
            </div>
            <div style='padding:5px;padding-top:10px;'>
              <hr style='width:95%;margin:auto;border:2px inset #777'/>
              <input type='text' placeholder='Youtube Link' style='width:250px' class='navbar-search youtube' id='youtube-3' readonly value='".$sql->get_col()."' />
              <input type='hidden' name='youtube-video-3' id='youtube-video-3' />
            </div>
          </div>
        </div>
      </td>
    <tr>
      <td colspan='6' style='text-align:center;padding:5px;padding-bottom:10px;'><hr style='width:95%;margin:auto;border:2px inset #777'/></td>
    </tr>
    <tr>
      <td style='vertical-align:top;'> Images:</td>
      <td colspan='3' id='image-result-div'>$imglist</td>
    </tr>
    <tr><td colspan='4' id='result'></td></tr>        
  </table>";  
