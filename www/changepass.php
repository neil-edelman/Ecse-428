<?php

include "session.php";

  $s = new Session();

  $db = $s->link_database() or header_error("database error");
  $user = $s->get_user() or header_error("user timeout error");
  $info = $s->user_info($user) or header_error("user info error");
  is_admin($info) or header_error("not authorised");


?>

<!DOCTYPE html>
<html>

 <head>
        <meta charset="UTF-8">
    <meta name = "Author" content = "Team RMS">
    <link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
    <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>Change Password</title>
    </head>

    <body>

      <h1>Change Password</h1>

      <form method="post">
        <div>
        <label>Old Password: </label>
        <input type="text" name="username"
                maxlength = "<?php echo Session::PASSWORD_MAX;?>"/><br/>

       <label>New Password: </label>
        <input type="text" name="oldPass"
                maxlength = "<?php echo Session::PASSWORD_MAX;?>"/><br/>

      <label>Re-enter Password: </label>
        <input type="text" name="username"
                maxlength = "<?php echo Session::PASSWORD_MAX;?>"/><br/>

     <p></p>
     <input type = "submit" value = "Change Password"/>
      <br/>
      <p></p>
      <input type = "reset" value = "Reset"/>
    </div>
      </form>

    </body>

</html>
