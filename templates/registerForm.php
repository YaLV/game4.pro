<div style='position:absolute;z-index:1111111111;'>
<form id='register'>
  <table class='ctable popplesejs' style='color:black;width:300px;height:229px;'>
    <tr>
      <td>Name</td>
      <td><input type='text' name='name' class='form_field' /></td>
    </tr>
    <tr>
      <td>Email:</td>
      <td><input type='text' name='email' class='form_field' /></td>
    </tr>
    <tr>
      <td style='padding-right:5px;'>Password:</td>
      <td><input type='password' name='rpassword' class='form_field' /></td>
    </tr>
    <tr>
      <td style='padding-right:5px;'>Password Repeat:</td>
      <td><input type='password' name='rpasswordr' class='form_field' /></td>
    </tr>
    <tr>
      <td style='text-align:right;padding-right:5px;'><input type='checkbox' id='ia' name='acceptTandC'/></td>
      <td nowrap><label for='ia'><?=$message->text("acceptTandC");?></label></td>
    </tr>
    <tr>
      <td colspan='2' style='text-align:right'><button class='btn btn-info' id='regBtn' style='height:30px;font-weight:bold;'>Register</button></td>
    </tr>
  </table>
</form>
</div>
<div id='plesejs'></div>