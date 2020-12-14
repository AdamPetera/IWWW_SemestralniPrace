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
        $product_identifier = CategoryController::getProductCategory($_GET['id']);
        $similar_products = ProductController::getSimilarProducts($_GET['id'], $product_identifier, 3);
        if (isset($_SESSION["role"])) {
            if ($_SESSION["role"] == "admin" || $_SESSION['role'] == 'seller') {
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
    $variants = ProductVariantsController::getAllProductVariants($_GET['id']);
    $size_attributes = $additional_attributes = array();
    foreach ($attributes as $at) {
            array_push($additional_attributes, [0 => $at['human_readable'],
                                                            1 => $at['value']]);
    }

    if (isset($_POST['saveReview'])) {
        $validation = ReviewController::reviewValidation($_POST['reviewName'], $_POST['reviewBody']);
        if (count($validation) == 0) {
            ReviewController::insertReview($product['product_id'], $_SESSION['row']['user_id'], $_POST['reviewName'], $_POST['reviewRating'], $_POST['reviewBody']);
        }
    }

    $reviews = ReviewController::getAllProductReviews($product['product_id']);

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
                <?php if (!empty($variants)): ?>
                    <select name="variants" class="variants">
                        <?php foreach ($variants as $variant): ?>
                            <option value="<?=$variant?>"><?=$variant?></option>
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
    <?php
        if (isset($_SESSION['email'])) {
            $data = ReviewController::getUserProductReview($product['product_id'], $_SESSION['row']['user_id']);
            if ($data['rowCount'] == 0) {
    ?>
        <div class="add_review">
            <h2>Přidání vlastní recenze</h2>
            <div class="review_form_wrap">
                <form method="post">
                    <div class="txt_field_name">
                        <p>Název recenze:</p>
                        <input type="text" name="reviewName" required class="reviewName">
                    </div>
                    <div class="txt_field_rating">
                        <p>Hodnocení produktu v %:</p>
                        <input type="number" name="reviewRating" required min="0" max="100" value="50">
                    </div>
                    <div class="txt_field_body">
                        <p>Vlastní recenze:</p>
                        <input type="text" name="reviewBody" required class="reviewBody">
                    </div>
                    <div class="save_button">
                        <input name="saveReview" type="submit" value="Uložit recenzi" class="saveReview">
                    </div>
                </form>
            </div>
            <?php if (isset($error_message)): ?>
                <div class="form_error">
                    <span class="error"><?php echo $error_message; ?></span>
                </div>
            <?php endif ?>
        </div>
    <?php
            }
        }
    ?>
    <div class="product_review_wrap">
        <?php
            echo '<h2>Recenze produktu</h2>';
            if (!empty($reviews)) {
                foreach ($reviews as $review) {
                    $lastnameCharacter = substr($review['lastname'], 0, 1);
                    $date = strtotime($review['dateAdded']);
                    $editedDate = date("d. m. yy", $date);
                    echo '
                        <div class="review_card">
                        <p class="review_author_date">'. $review['firstname'] .' '.$lastnameCharacter.'. dne '.$editedDate.'</p>
                        <p class="review_name">'. $review['name'] .'</p>
                        <p class="review_rating">'. $review['rating'] .' %</p>
                        <p class="review_body">'. $review['description'] .'</p>
                        </div>';
                }
            } else {
                echo '<p class="noReviews">Produkt zatím nemá žádné recence :(</p>';
            }
        ?>
    </div>
    <div class="similar_products_wrap">
        <h2 class="similar_title">Uživatelé také zakoupili</h2>
        <div class="sticks_preview">
            <?php
            if ($product_identifier == 'stick') {
                foreach ($similar_products as $item) {
                    $image = ProductImageController::getProductImage($item['product_id'], 'main');
                    echo '
                          <a href="index.php?page=product&id=' . $item['product_id'] . '">
                               <div class="stick_card">
                                    <div class="stick_card_image_wrap">
                                        <img src="' . $image . '" alt="Florbalka ' . $item["name"] . '">
                                    </div>
                                    <div class="stick_card_name">
                                         <h2 class="title">' . $item["name"] . '</h2>
                                    </div>
                                    <div class="stick_card_price">
                                         <span class="price" itemprop="price" content="' . $item["price"] . '">' . $item["price"] . ' Kč</span>
                                    </div>
                                    <div class="stick_card_button">
                                         <button class="detail_button">Detail produktu</button>
                                    </div>
                               </div>
                          </a>';
                }
            } else {
                foreach ($similar_products as $item) {
                    $image = ProductImageController::getProductImage($item['product_id'], 'main');
                    echo '
                        <a href="index.php?page=product&id=' . $item['product_id'] . '">
                            <div class="item_card">
                                <div class="item_card_image_wrap">
                                    <img src="' . $image . '" alt="' . $item["name"] . '">
                                </div>
                                <div class="item_card_name">
                                    <h2 class="title">' . $item["name"] . '</h2>
                                </div>
                                <div class="item_card_price">
                                    <span class="price" itemprop="price" content="' . $item["price"] . '">' . $item["price"] . ' Kč</span>
                                </div>
                                <div class="item_card_button">
                                    <button class="item_detail_button">Detail produktu</button>
                                </div>
                            </div>
                        </a>';
                }
            }
            ?>
        </div>
    </div>
</div>