<?
$users="";
$sql->query("select id,username,email,phone from admin_users order by id DESC");
while($sql->get_row()) {
  list($id,$username,$email,$phone)=Array($sql->get_col(),$sql->get_col(),$sql->get_col(),$sql->get_col());
  $users.="
        <tr id='$id'>
          <td class='username'>$username</td>
          <td class='email'>$email</td>
          <td class='phone'>$phone</td>
          <td class='btn-opt'><button class='btn btn-mini btn-warning'>Edit</button><button class='btn btn-mini btn-danger'>Remove</button></td>
        </tr>
  ";
}
$contents="
    <div class='shadow box content' style='position:relative'>
      <a href='#' id='addUser'>Add new user</a>
      <form method='post' id='addForm' style='display:none;background:#EEE;width:300px;padding:10px;margin:auto;' class='round-corners'>
        <input type='hidden' name='id' value='' />
        <table>
          <tr>
            <td>Username:</td>
            <td><input type='text' name='username' id='username' value='' /></td>
          </tr>
          <tr>
            <td>Email:</td>
            <td><input type='text' name='email' id='email' value='' /></td>
          </tr>         
          <tr>
            <td>Phone:</td>
            <td><input type='text' name='phone' id='phone' value='' /></td>
          </tr>         
          <tr>
            <td>Password:</td>
            <td><input type='password' name='password' id='password' value='' /></td>
          </tr>
          <tr>
            <td colspan='2'><button class='btn btn-success addUserButton'>Save</button><button class='btn btn-danger'>Clear</button></td>
          </tr>
        </table>         
      </form>  
      <br />
      <br />                                                               
      <table class='content-table'>
        <tr>
          <td>Username</td>
          <td>Email</td>
          <td>Phone</td>
          <td>Options</td>
        </tr>
        $users
      </table>
    </div>
    <div class='curtains'></div>
";
?>