<?
$contents="
  <img src='' id='iim' style='position:absolute;z-index:0;top:-10000px;'/>
  <div class='shadow box content' style='position:relative'>
    <form method='post' enctype='multipart/form-data' id='codeForm' action='games/codes/upload'>
      <input type='hidden' id='gID' name='gID' value='{$fnd[1]}' />
      <table style='width:800px;margin:auto;color:white;height:300px;' border='1'>
        <tr>
          <td> Add Serial Keys:</td>
          <td> Aviable Serial Keys:</td>
        </tr>
        <tr>
          <td style='width:200px;'><textarea name='codes' style='width:200px;height:200px;'></textarea><br /><button class='btn btn-success btn-save'>Save</button></td>
          <td rowspan='2'><div class='codes_aviable keylist' style='height:300px;overflow-y:scroll;text-align:left;vertical-align:top;'></div></td>
        </tr>
        <tr>
          <td style='vertical-align:top;'>        
            <input type='file' id='kodes' name='codesFiles' multiple style='display:inline;visibility:hidden;'/>
            <button class='btn btn-primary btn-upload uploadKodes'>Select file(s) for upload</button>
          </td>
        </tr>
      </table>
    </form>
  </div>
";