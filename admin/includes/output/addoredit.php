<?
$contents="
  <div class='shadow box content' style='position:relative'>
    <form method='post' id='addForm'> 
      $idfield
      <table style='color:white;margin:auto;width:1000px;'>
        <tr>
          <td colspan='4' style='text-align:center;'>
            <!--div class='btn-group pull-left' data-toggle='buttons-radio'>
              <button class='btn active btn-success language' id='en'>English</button>
              <button class='btn language btn-inverse' id='ru'>Русский</button>
            </div-->
              <input type='hidden' name='saveWithErrors' value='0' id='swe' />
              <button class='btn btn-success pull-right' id='submitForm'>Save</button>
          <td>
        </tr>
        <tr>
          <td style='width:100px;'>
            <div id='name-label-en'>Game Name:</div>
            <div id='name-label-ru' style='display:none;'>Название:</div>
          </td> 
          <td style='width:150px;'>
            <input type='text' id='name-en' name='name-en' class='navbar-search' placeholder='Game Name' value='".htmlspecialchars($sql->get_col(),ENT_QUOTES)."' />
            <input type='text' style='display:none;' class='navbar-search' id='name-ru' name='name-ru' placeholder='Название игры' value='".htmlspecialchars($sql->get_col(),ENT_QUOTES)."' />
          </td>
          <td rowspan='4' style='vertical-align:top;padding-top:12px;padding-left:10px;width:180px;text-align:right;'>
            <div id='sysreq-label-en'>System Requirements:</div>
            <div id='sysreq-label-ru' style='display:none;'>Системные требования:</div>
          </td>
          <td rowspan='4' style='vertical-align:top;'>
            <textarea id='sysreq-en' name='sysreq-en' class='navbar-search txt-sized' style='width:440px;height:155px;'>".$sql->get_col()."</textarea>
            <textarea id='sysreq-ru' name='sysreq-ru' class='navbar-search txt-sized' style='width:440px;height:155px;display:none;'>".$sql->get_col()."</textarea>
          </td>
        </tr>
        <tr>
          <td>
            <div id='date-label-en'>Release Date:</div>
            <div id='date-label-ru' style='display:none;'>Дата Выпуска:</div>
          </td> 
          <td style='width:220px;'>
            <input type='text' id='date-en' name='date-en' class='navbar-search' placeholder='Release Date: dd/mm/yyyy' value='".$sql->get_col()."' />
            <input type='text' id='date-ru' name='date-ru' style='display:none;' class='navbar-search' placeholder='Дата выпуска: dd/mm/yyyy' value='".$sql->get_col()."' />
          </td>
        </tr>
        <tr>
          <td>
            <div id='platform-label-en'>Platform:</div>
            <div id='platform-label-ru' style='display:none;'>Платформа:</div>
          </td> 
          <td>
            <input type='text' class='navbar-search' id='platform-en' name='platform-en' placeholder='Platform' value='".$sql->get_col()."' />
            <input type='text' class='navbar-search' id='platform-ru' name='platform-ru' style='display:none;' placeholder='Платформа' value='".$sql->get_col()."' />
          </td>
        </tr>
        <tr>
          <td>
            <div id='languages-label-en'>Languages:</div>
            <div id='languages-label-ru' style='display:none;'>Языки:</div>
          </td> 
          <td>
            <input id='languages-en' name='languages-en' type='text' class='navbar-search' placeholder='Languages' value='".$sql->get_col()."' />
            <input id='languages-ru' name='languages-ru' style='display:none;' type='text' class='navbar-search' placeholder='Языки' value='".$sql->get_col()."' />
          </td>
        </tr>
        <tr>
          <td>Price:</td>
          <td>
            <div class='pull-left'><label for='price-eur' style='position:top;'>EUR</label><input id='price-eur' name='price-eur' type='text' class='navbar-search' placeholder='EUR' style='width:40px;margin-right:2px;' value='".$eur."' /></div>
            <div class='pull-left'><label for='price-eur' style='position:top;'>USD</label><input id='price-usd' name='price-usd' type='text' class='navbar-search' placeholder='USD' style='width:40px;margin-right:2px;' value='".$usd."' /></div>
            <div class='pull-left'><label for='price-eur' style='position:top;'>GBP</label><input id='price-gbp' name='price-gbp' type='text' class='navbar-search' placeholder='GBP' style='width:40px;margin-right:2px;' value='".$gbp."' /></div>
            <div class='pull-left' style='display:none;'><label for='price-eur' style='position:top;'>RUB</label><input id='price-rub' name='price-rub' type='text' class='navbar-search' placeholder='RUB' style='width:40px;' value='' /></div>
          </td>
          <td colspan='2'>
            <div class='btn-toolbar' style='margin: 0px;margin-top:2px;text-align:center;'>
              <div class='btn-group'>
                <button class='btn btn-inverse filter-btn dropdown-toggle' id='filterage' data-toggle='dropdown'>$agerestr</button>
                <button class='btn btn-inverse dropdown-toggle' data-toggle='dropdown'>
                  <span class='caret' style='margin-bottom:7px;'></span>
                </button>
                <ul class='dropdown-menu'>
                  <li><a href='javascript:void(0);' id='select-age-no'>No Age Restrictions</a></li>
                  <li><a href='javascript:void(0);' id='select-age-3'>Age 3+</a></li>
                  <li><a href='javascript:void(0);' id='select-age-7'>Age 7+</a></li>
                  <li><a href='javascript:void(0);' id='select-age-12'>Age 12+</a></li>
                  <li><a href='javascript:void(0);' id='select-age-15'>Age 15+</a></li>
                  <li><a href='javascript:void(0);' id='select-age-16'>Age 16+</a></li>
                  <li><a href='javascript:void(0);' id='select-age-18'>Age 18+</a></li>
                </ul>
              </div>
              <input type='hidden' name='age' id='age' value='$age' />
              <input type='hidden' name='genre' id='genres' value='$genres'/>
              <div class='btn-group' data-toggle='buttons-checkbox'>
                $genrez
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>Bonus Points:</td>
          <td>              
            <input type='text' name='points' value='$points' class='navbar-search' placeholder='Bonus Points'/>
          </td>
          <td colspan='2' style='text-align:center;'><div class='pull-left' style='height:40px;width:220px;padding-left:20px;'><button class='pull-left btn btn-inverse btn-ss'>SuperSaving</button><input type='hidden' name='SuperSavingEnabled' id='sse' value='$ss'/><input type='text' id='ss' style='display:none;width:50px;' value='$ssv' name='SuperSave' /></div><button class='btn btn-inverse btn-spam $dat' $dis>$nm</button> <input type='hidden' name='sendmail' id='spam' value='0' /></td>
        </tr>                                                                                                                                                 
        <tr>
          <td style='vertical-align:top;padding-top:12px;padding-left:10px;'>
            <div id='description-label-en'>Description:</div>
          </td>
          <td colspan='3' style='vertical-align:top;'>
            <textarea  id='description-en' name='description-en' class='navbar-search txt-sized' style='width:793px;height:155px;'>".$sql->get_col()."</textarea>
          </td>
        </tr>
        <!--tr>
          <td style='vertical-align:top;padding-top:12px;padding-left:10px;'>
            <div id='description-label-ru'>Описание:</div>
          </td>
          <td colspan='3' style='vertical-align:top;'>
            <textarea class='navbar-search txt-sized' id='description-ru' name='description-ru' style='width:793px;height:155px;'>".$sql->get_col()."</textarea>
          </td>
        </tr-->
  
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
                  <input type='text' placeholder='Youtube Link' style='width:250px' class='navbar-search youtube' id='youtube-1' value='".$y1=$sql->get_col()."' />
                  <input type='hidden' name='youtube-video-1' id='youtube-video-1' value='$y1' />
                </div>
              </div>
              <div class='youtube-video thumbnail' style='text-align:center;margin-left:5px;'>
                <div>
                  <div style='position:absolute;margin-left:5px;margin-right:5px;background: black; -moz-opacity: 0.8; opacity:0.8;width:260px;padding-bottom:1px;vertical-align:middle' id='title-2'></div>
                  <img src='/images/260x180.gif' id='preview-2'/>
                </div>
                <div style='padding:5px;padding-top:10px;'>
                  <hr style='width:95%;margin:auto;border:2px inset #777'/>
                  <input type='text' placeholder='Youtube Link' style='width:250px' class='navbar-search youtube' id='youtube-2' value='".$y2=$sql->get_col()."'/>
                  <input type='hidden' name='youtube-video-2' id='youtube-video-2' value='$y2' />
                </div>
              </div>
              <div class='youtube-video thumbnail' style='text-align:center;margin-left:5px;'>
                <div>
                  <div style='position:absolute;margin-left:5px;margin-right:5px;background: black; -moz-opacity: 0.8; opacity:0.8;width:260px;padding-bottom:1px;vertical-align:middle' id='title-3'></div>
                  <img src='/images/260x180.gif' id='preview-3'/>
                </div>
                <div style='padding:5px;padding-top:10px;'>
                  <hr style='width:95%;margin:auto;border:2px inset #777'/>
                  <input type='text' placeholder='Youtube Link' style='width:250px' class='navbar-search youtube' id='youtube-3' ".$y3=$sql->get_col()."/>
                  <input type='hidden' name='youtube-video-3' id='youtube-video-3' value='$y3' />
                </div>
              </div>
            </div>
          </td>
        <tr>
          <td colspan='6' style='text-align:center;padding:5px;padding-bottom:10px;'><hr style='width:95%;margin:auto;border:2px inset #777'/></td>
        </tr>
        <tr>
          <td style='vertical-align:top;'> Images:</td>
          <td> <button class='btn btn-primary btn-upload'>Select Files for Upload</button><input type='file' name='upim' multiple id='game-img' style='visibility:hidden;'/></td>
        </tr>
        <tr>
          <td></td><td colspan='3' id='image-result-div'>$imglist</td>
        </tr>
        <tr><td colspan='4' id='result'></td></tr>        
      </table>
      <script type='text/javascript' src='tiny_mce/tiny_mce.js'></script>";
$contents.='<script>
        tinyMCE.init({
            mode : "exact",
            elements : "description-en,description-ru",
            theme: "advanced",
            plugins : "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
        		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
        		theme_advanced_toolbar_location : "top",
        		theme_advanced_toolbar_align : "left",
        		theme_advanced_statusbar_location : "bottom",
        		theme_advanced_resizing : true,
            valid_elements : "*[*]",
            inline_styles : true,
            schema: "html5",
            force_br_newlines : true,
            force_p_newlines : false,
            forced_root_block : "",
            invalid_elements : "p"
          });
      </script>
      ';  