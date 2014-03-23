<?php

include "session.php";

  $s = new Session();

  $db = $s->link_database() or header_error("database error");
  $user = $s->get_user() or header_error("user timeout error");
  $info = $s->user_info($user) or header_error("user info error");

  $password = $info["password"];
  $oldpass = NULL;
  $newpass = NULL;
  $verpass = NULL;

  isset($_REQUEST["oldPass"])  and $_REQUEST["oldPass"] != "" and $oldpass = $_REQUEST["oldPass"];
  isset($_REQUEST["newPass"])  and $_REQUEST["newPass"] != "" and $newpass = $_REQUEST["newPass"];
  isset($_REQUEST["verPass"])  and $_REQUEST["verPass"] != "" and $verpass = $_REQUEST["verPass"];

  $salt = bin2hex(openssl_random_pseudo_bytes(22, $isCrypto));
  $isCrypto or die("No cryptography on this server.");
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
        <div><label>Old Password: </label>
        <input type="text" name="oldPass"
                maxlength = "<?php echo Session::PASSWORD_MAX;?>"/><br/></div>

        <div><label>New Password: </label>
        <input type="text" name="newPass"
                maxlength = "<?php echo Session::PASSWORD_MAX;?>"/><br/></div>

      <div><label>Re-enter Password: </label>
        <input type="text" name="verPass"
                maxlength = "<?php echo Session::PASSWORD_MAX;?>"/><br/></div>

      <p></p>
     <input type = "submit" value = "Change Password"/>
      <br/>
      <p></p>
      <input type = "reset" value = "Reset"/>
    </form>

    <?php

      echo $password."</br>";
      echo $oldpass."</br>";
      echo $newpass."</br>";
      echo $verpass."</br>";



      if($s->update_password($oldpass, $password, $newpass, $verpass, $user, $info["FirstName"], $info["LastName"], $info["Email"], $info["Privilege"])){
          echo "Account created";
      } else {
          echo "Something went wrong. </br>";
          echo $s->status();
      }

    ?>

  </body>

</html>
