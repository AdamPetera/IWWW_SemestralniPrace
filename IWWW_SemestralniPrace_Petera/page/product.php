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

<div class="product_detail_wrap">
    <div class="product_wrap">
        <?php
        $image = ProductImageController::getProductImage($product['product_id'], 'main');
        if (strpos($product["description"], 'Florbalka') !== false) {
            ?>
            <img src="<?=$image?>" width="600" height="250" class="product_stick_img" alt="<?=$product['name']?>">
            <?php
        } else {
            ?>
            <img src="<?=$image?>" width="400" height="400" class="product_img" alt="<?=$product['name']?>">
            <?php
        }
        ?>
        <div class="properties">
            <h1 class="name"><?=$product['name']?></h1>
            <span class="price">
                <?=$product['price']?> Kč
            </span>
            <form action="index.php?page=cart" method="post">
                <input type="number" name="quantity" value="1" min="1"" placeholder="Quantity" required>
                <input type="hidden" name="product_id" value="<?=$product['product_id']?>">
                <input type="submit" name="add_to_basket" value="Přidat do košíku">
            </form>
            <div class="description">
                <?=$product['description']?>
            </div>
        </div>
    </div>
</div>