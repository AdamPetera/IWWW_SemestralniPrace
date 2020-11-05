<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/product.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
    $conn = Connection::getPdoInstance();
    if (isset($_GET['id'])) {
        $stmt = $conn->prepare('SELECT * FROM product WHERE product_id = ?');
        $stmt->execute([$_GET['id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            die ("Produkt neexistuje");
        }
    } else {
        die ("Produkt neexistuje");
    }
?>

<div class="product product_detail_wrap">
    <img src="<?=$product['image']?>" width="500" height="250" alt="<?=$product['name']?>">
    <div>
        <h1 class="name"><?=$product['name']?></h1>
        <span class="price">
            <?=$product['price']?> Kč
        </span>
        <form action="index.php?page=cart" method="post">
            <input type="number" name="quantity" value="1" min="1"" placeholder="Quantity" required>
            <input type="hidden" name="product_id" value="<?=$product['product_id']?>">
            <input type="submit" value="Add To Cart">
        </form>
        <div class="description">
            <?=$product['description']?>
        </div>
    </div>
</div>