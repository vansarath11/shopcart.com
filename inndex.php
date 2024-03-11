<?php
    include "config.php";
    session_start();
    $user_id = $_SESSION['user_id'];

    if(!isset($user_id)) {
        header('Location: Llogin.php');
    }
    if(isset($_GET['logout'])){
        unset($user_id);
        session_destroy();
        header('Location: Llogin.php');
    }

    if(isset($_POST['add_to_cart'])){
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];
    
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'")
         or die ('query failed');
    
        if(mysqli_num_rows($select_cart) > 0){
            $message[] = 'Product already added to cart!';
        }
        else{
            mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES
             ('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die ('query failed');
            $message[] = 'Product added to cart!';
        }
    }


    if(isset($_POST['update_cart'])){
        $update_quantity = $_POST['cart_quantity'];
        $update_id = $_POST['cart_id'];
        mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id =
         '$update_id' ") or die('query failed');
          $message[] = 'cart quantity update successfully!';
    }

    if(isset($_GET['remove'])){
        $remove_id = $_GET['remove'];
        mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id' ") or die('query failed');
        header('Location: inndex.php');
    }

    if(isset($_GET['delete_all'])){
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id' ") or die('query failed');
        header('Location: inndex.php');
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .isertupdate{
    width: 100%;
    align-items: center;
    justify-content: center;
    display: flex;
}
.isertupdate a{
    display: flex;
    font-size: 20px;
    padding: 10px 14px;
    background-color: var(--blue);
    border-radius: 5px;
    color: white;
    margin-buttom: 10px;
}
.isertupdate a:hover{
    color: red;
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

<div class="container">

    <div class="user-profile">

    <?php
    $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die ('query failed');
    if(mysqli_num_rows($select_user) > 0){
        $fetch_user = mysqli_fetch_assoc($select_user);
    };
?>
        <p> username : <span><?php echo $fetch_user['name']; ?></span></p>
        <p> email : <span><?php echo $fetch_user['email']; ?></span></p>
        <div class="flex">
        <a href="Llogin.php" class="btn">Login</a>
        <a href="register.php" class="option-btn">register</a>
        <a href="inndex.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are your sure you want to logout?');" 
        class="delete-btn">logout</a>
        </div>

    </div>
    <div class="products">

        <h1 class="heading">latest products</h1>
        <span class="isertupdate" ><a  href="importImg.php">
            Upload Image More 
        </a></span>

        <div class="box-container">

        <?php
    $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die ('query failed');
    if(mysqli_num_rows($select_product) > 0) {
        while($fetch_product = mysqli_fetch_assoc($select_product)) {
?>
        <form method="post" class="box" action="">
            <img src="images/<?php echo $fetch_product['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_product['name']; ?></div>
            <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
            <input type="number" min="1" name="product_quantity" value="1">
            <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">

            <input type="submit" value="add to cart" name="add_to_cart" class="btn">
        </form>
<?php
    }
}
?>

        </div>
    </div>
    <div class="shopping-cart">
        <h1 class="heading">shopping cart</h1>
        <table>
            <thead>
                <th>image</th>
                <th>name</th>
                <th>price</th>
                <th>quantity</th>
                <th>total price</th>
                <th>action</th>
            </thead>
            <tbody>
            <?php
$grand_total = 0;
$cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die ('query failed');
if(mysqli_num_rows($cart_query) > 0) {
    while($fetch_cart = mysqli_fetch_assoc($cart_query)) {
        $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
        $grand_total += $sub_total;
        ?>
        <tr>
            <td><img src="images/<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
            <td><?php echo $fetch_cart['name']; ?></td>
            <td>$<?php echo $fetch_cart['price']; ?>/-</td>
            <td>
                <form action="" method="post">
                    <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                    <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                    <input type="submit" name="update_cart" value="update" class="option-btn">
                </form>
            </td>
            <td>$<?php echo number_format($sub_total); ?>/-</td>
            <td><a href="inndex.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('Remove item from cart?');">Remove</a></td>
        </tr>
        <?php
    }
} else {
    echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">no item added</td></tr>';
}
?>
<tr class="table-bottom">
    <td colspan="4">Grand total:</td>
    <td>$<?php echo $grand_total; ?>/-</td>
    <td><a href="inndex.php?delete_all" onclick="return confirm('Delete all from cart?');"
     class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">
    Delete all</a></td>
</tr>
</tbody>
        </table>

        <div class="cart-btn">
            <a href="#" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
        </div>

    </div>
</div>
</body>
</html>