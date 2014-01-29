<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create Account</title>
    </head>
    <body>
        <form method="post">
            <h1>Add a new user account</h1>
            
            Username: <input type="text" name="username"><br>
            Password: <input type="text" name="password"><br>
            First Name: <input type="text" name="firstname"><br>
            Last Name: <input type="text" name="lastname"><br>
            <br>
            Email: <input type="email" name="email"><br>
            <br>
            
            Privilege:
            <select name="privilege">
                <option value="wait">Wait Staff</option>
                <option value="cook">Cook Staff</option>
                <option value="manager">Manager</option>
                <option value="admin">System Admin</option>
            </select>
            <br>
            <input type="submit">
        </form>
        
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $username = $_POST['username'];
                $password = $_POST['password'];
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];

                $email = $_POST['email'];
                $privilege = $_POST['privilege'];

                if (!(empty($username) || empty($password) || empty($firstname) || empty($lastname) || empty($email))){

                        // Create connection
                        $server= mysqli_connect("localhost","root","mushroom","payomca_rms");

                        // Check connection
                        if (mysqli_connect_errno())
                        {
                                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                        }

                        $sql = "INSERT INTO users (Username, Password, FirstName, LastName, Email, Privilege) VALUES ('$username', '$password', '$firstname', '$lastname', '$email', '$privilege');";
                        // For debugging purposes
                        //echo $sql;
                        //echo mysqli_query($server,$sql);

                        //$sql2 = "SELECT MAX(patientid) from patients";
                        $result = mysqli_query($server, $sql);

                        header("Location: index.php");
                        die();
                } else {
                        echo "Error: Invalid data entered on form";
                }
            }
        ?>
    </body>
</html>
