<?php
include "config.php";
session_start();
if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email='$email' AND password= '$pass'") or die ('query failed');

    if(mysqli_num_rows($select) > 0){
        $row = mysqli_fetch_assoc($select);
        $_SESSION['user_id'] = $row['id'];
        header('Location: inndex.php');
    }
    else{
        $message[] = 'incorrect password or email!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h3>Login Now</h3>
            
            <input type="email" name="email" required placeholder="Enter Email" class="box">
            <input type="password" name="password" required placeholder="Enter Password" class="box">
           
            <input type="submit" name="submit" class="btn" value="Login now">
            <p>Don't have an account? <a href="register.php">Register Now</a></p>
        </form>
    </div>
</body>
</html>