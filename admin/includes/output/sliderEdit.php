<?
$file = dirname(getcwd())."/slider/slider.jpg";
$dimensions=getimagesize($file);
$maxx=900-$dimensions[0];
$maxy=478-$dimensions[1];
$img="../slider/slider.jpg";
$contents="
  <div class='shadow box content' style='position:relative;text-align:center;'>
    <form method='post' id='sliderForm'>
      <input type='hidden' id='coordinates' name='coords' value='0,0' />
      <input type='hidden' id='imx' value='$maxx' />
      <input type='hidden' id='imy' value='$maxy' />
      <input type='hidden' name='pnts' />
      <div>
      	<div style='width:700px;margin:auto;'>
          <div class='btn-group' data-toggle='buttons-radio'>
        		<button id='slider' class='btn btn-success active sizeSel'>Main Page Slider</button>
        		<button id='gamepage' class='btn btn-inverse sizeSel'>Game page image</button>
        		<button id='preorderpage' class='btn btn-inverse sizeSel'>Preorder page image</button>
        		<button id='savingpage' class='btn btn-inverse sizeSel'>SuperSavings page image</button>
          </div>
      	</div>
        <button class='btn btn-success slideAdd pull-right'>Save</button>
      	<input type='hidden' name='position' value='slider' />
      	<input type='hidden' name='badge' value='1' />
      	<input type='hidden' name='click' value='1' />
      	<button class='btn btn-success active st' id='badge'>Badge</button>&nbsp;<button class='btn btn-success active st' id='click'>Clickable</button>
      	<input type='text' value='' readonly class='click_div double' style='margin-top:5px;display:none;'/>
      	<input type='text' name='link' value='' class='click_div' style='margin-top:5px;'/>
      </div>
      <div id='source_image' style='position:relative;background-position: 0px 0px;cursor:pointer;width:900px;height:478px;margin:auto;background:url($img) no-repeat;'>
      	<div class='overlay' style='position:absolute;top:0px;background:url(../images/content_top.png) no-repeat;width:900px;height:300px;background-position:-139px -105px;'></div>
      	<div class='badge_div' style='position:absolute;top:342px;width:98px;height:62px;background:url(../images/badge.png);color:white;text-align:left;' class='bdg'><div style='padding-top:33px;padding-left:12px;font-weight:bold;font-size:20px;font-family: Arial, Verdana;' id='gamePoints'></div></div>
      	<div style='position:absolute;top:0px;left:0px;width:900px;height:478px;cursor:pointer;' id='draggable_div'></div>
      </div>
    </form>
  </div>
";