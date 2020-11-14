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
        $product = ProductController::getProductById($conn, $_GET['id']);

        if (isset($_SESSION["role"])) {
            if ($_SESSION["role"] == "admin") {
                if (isset($_POST['remove'])) {
                    ProductController::deleteProduct($_GET['id']);
                    echo '<script type="text/javascript">
                                window.location = "index.php"
                            </script>';
                }
            }
        }

        if (!$product) {
            die ("Produkt neexistuje");
        }
    } else {
        die ("Produkt neexistuje");
    }

    $category = ProductController::getProductCategory($_GET['id']);
    $attributes = ProductController::getAllProductAttributes($_GET['id']);
    $length_attributes = $holding_attributes = $size_attributes = $additional_attributes = array();
    foreach ($attributes as $at) {
        if ($at['name'] == 'length') {
            array_push($length_attributes, (int) $at['value']);
        } elseif ($at['name'] == 'holding') {
            array_push($holding_attributes, $at['value']);
        } elseif ($at['name'] == 'size') {
            array_push($size_attributes, $at['value']);
        } else {
            array_push($additional_attributes, [0 => $at['human_readable'],
                                                            1 => $at['value']]);
        }
    }


?>

<div class="product_detail_wrap">
    <?php
    if (isset($_SESSION["role"])) {
        if ($_SESSION["role"] == "admin") {
            ?>
                <div class="editors_buttons">
                    <div class="edit_button">
                        <p><a href="index.php?page=edit_product&product_id=<?=$_GET['id']?>">Editovat produkt</a></p>
                    </div>
                    <form method="post" class="delete_form">
                        <input class="delete_button" name="remove" type="submit" value="Smazat produkt">
                    </form>
                </div>
            <?php
        }
    }
    ?>
    <div class="product_wrap">
        <?php
        $image = ProductImageController::getProductImage($product['product_id'], 'main');
        if (strpos($category, 'stick') !== false) {
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
                <?php if (!empty($length_attributes)): ?>
                    <select name="length_atrs" class="length_attributes">
                        <?php foreach ($length_attributes as $la): ?>
                            <option value="<?=$la?>"><?=$la?> cm</option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <?php if (!empty($size_attributes)): ?>
                    <select name="size_atrs" class="size_attributes">
                        <?php foreach ($size_attributes as $sa): ?>
                            <option value="<?=$sa?>"><?=$sa?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <?php if (!empty($holding_attributes)): ?>
                    <select name="holding_atrs" class="holding_attributes">
                        <?php foreach ($holding_attributes as $ha): ?>
                            <option value="<?=$ha?>"><?=$ha?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <input type="number" name="quantity" value="1" min="1" placeholder="Quantity" required>
                <input type="hidden" name="product_id" value="<?=$product['product_id']?>">
                <input type="submit" name="add_to_basket" value="Přidat do košíku">
            </form>
        </div>
    </div>
    <?php if (!empty($additional_attributes)): ?>
        <div class="additional_attributes">
            <h3>Ostatní parametry</h3>
            <ul class="aa_list">
                <?php foreach ($additional_attributes as $aa): ?>
                    <li class="aa_element"><?=ucfirst($aa[0])?>: <?=ucfirst($aa[1])?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <div class="product_description">
        <h2>Popis produktu</h2>
        <p><?=$product['description']?></p>
    </div>
</div>