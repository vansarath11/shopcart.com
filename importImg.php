<?php
$conn = mysqli_connect('localhost', 'root', '', 'shop_db');

if(isset($_POST['btn'])){
    $image = $_FILES['pic']['name'];
    $photo = $_FILES['pic']['tmp_name'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $sql = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$image')";
    $exec = mysqli_query($conn, $sql);

    if($exec){
        move_uploaded_file($photo, "images/$image");
        $message = "Image added successfully.";
    } else {
        $message = "Failed to add image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Picture</title>
    <style>
        body { 
        font-family: Arial, sans-serif; 
        background-color: #f4f4f4; 
        margin: 0; 
        padding: 0; }
        form {
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #4caf50;
        color: #fff;
        cursor: pointer;
    }

    span.insertupdate {
        display: block;
        text-align: center;
        margin-top: 20px;
    }

    span.insertupdate a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
    }
    span.insertupdate a:hover{
        color: red;
    }
    </style>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        Enter Name Image: <input type="text" name="name" />
        Enter Price Image: <input type="text" name="price" />
        Enter Image: <input type="file" name="pic" />
        <input type="submit" name="btn" />
        <span class="insertupdate">
            <?php
                if(isset($message)){
                    echo $message;
                }
            ?>
            <a href="inndex.php">Back Home</a>
        </span>
    </form>
</body>
</html>