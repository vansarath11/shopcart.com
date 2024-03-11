<?php
    include "config.php";
    if(isset($_POST['submit'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = mysqli_real_escape_string($conn, $_POST['password']);
        $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);

        $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email='$email' AND password= '$pass'") or die ('query failed');

        if(mysqli_num_rows($select) > 0){
            $message[] = 'User already exists!';
        }
        else{
            mysqli_query($conn, "INSERT INTO `user_form`(name, email, password) VALUES ('$name','$email','$pass')") or die ('query failed');
            $message[] = 'Registered successfully!';
            header('location: Llogin.php');
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .logo-container {
    text-align: center;
    margin-bottom: 20px;
}

.logo-container img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 2px solid #ccc;
}
</style>
<body>
<?php
    if(isset($message)){
        foreach($message as $message){
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
?>
    <div class="form-container">
        <form action="" method="post">
        <div class="logo-container">
            <img src="shop.png" alt="Logo">
        </div>
            <h3>Register Now</h3>
            <input type="text" name="name" require placeholder="Enter Username" class="box">
            <input type="email" name="email" require placeholder="Enter Email" class="box">
            <input type="password" name="password" require placeholder="Enter Password" class="box">
            <input type="password" name="cpassword" require placeholder="Confirm Username" class="box">
            <input type="submit" name="submit" class="btn" value="register now">
            <P>Already have an account? <a href="Llogin.php">Login Now</a></P>
        </form>
    </div>
</body>
</html>