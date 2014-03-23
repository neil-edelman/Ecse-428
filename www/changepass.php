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

?>

<!DOCTYPE html>
<html>

 <head>
        <meta charset="UTF-8">
    <meta name = "Author" content = "Team RMS">
    <link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
    <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>Change Password</title>
<script type="text/javascript">

  function validate(){

    var oldPassword = document.getElementById("oldPass").value;
    var newPassword = document.getElementById("newPass").value;
    var verPassword = document.getElementById("verPass").value;

    if (   oldPassword.length == 0
        || newPassword.length == 0
        || verPassword.length == 0) {
        alert("Required fields are not complete");
        return false;
    } else {
        alert("All fields filled in");
    }
  }

</script>
</head>

<body>

    <h1>Change Password</h1>

      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
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
     <div><input type = "submit" value = "Change Password" onclick = "validate()"/></div>
      <br/>
      <div><input type = "reset" value = "Reset"/></div>
   <p>
      Go back to <a href = "mainmenu.php">Main Menu</a>.
   </p>

    </form>

    <?php

  /*    echo $password."</br>";
      echo $oldpass."</br>";
      echo $newpass."</br>";
      echo $verpass."</br>";*/
      $isModified = false;

      if(isset($oldpass) && isset($newpass) && isset($verpass)){
          $isModified = true;
         /*echo "All fields are filled in. </br>";*/

          if((strlen($newpass) > Session::PASSWORD_MAX) || (strlen($verpass) > Session::PASSWORD_MAX) ) {
          $isModified = false;
          echo "Password is too long.<br/>\n";
          }


      if($isModified){
        /*echo "Can be modified!</br>";*/
        if($s->update_password($oldpass, $password, $newpass, $verpass, $user, $info["FirstName"], $info["LastName"], $info["Email"], $info["Privilege"])){
          echo "<h4> Password successfully modified!</br></h4>";
        } else {
          echo "<h4> Password not modified.</br></h4>";
          /*echo $s->status();*/
        }
      }

      }

    ?>

  </body>

</html>
