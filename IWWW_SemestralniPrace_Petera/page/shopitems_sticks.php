<div class="sticks_wrapper">
    <?php
    if (isset($_SESSION["role"])) {
        if ($_SESSION["role"] == "admin") {
            ?>
            <form method="post" action="index.php?page=add_product" class="addProductForm">
                <input class="addProduct" type="submit" name="addProduct" value="Přidej produkt">
            </form>
            <?php
        }
    }
    ?>
    <h2 class="stick_title"><i class="fas fa-check"></i>&nbsp;Florbalky</h2>
    <div class="sticks_preview">
        <?php
        foreach (ProductController::getAllSticks() as $stick) {
            echo '
                  <a href="index.php?page=product&id='. $stick['product_id'] .'">
                       <div class="stick_card">
                            <div class="stick_card_image_wrap">
                                <img src="' . $stick["image"] . '" alt="Florbalka ' . $stick["name"] . '">
                            </div>
                            <div class="stick_card_name">
                                 <h2 class="title">' . $stick["name"] . '</h2>
                            </div>
                            <div class="stick_card_price">
                                 <span class="price" itemprop="price" content="' . $stick["price"] . '">' . $stick["price"] . ' Kč</span>
                            </div>
                            <div class="stick_card_button">
                                 <button class="detail_button">Detail produktu</button>
                            </div>
                       </div>
                  </a>';
        }
        ?>
    </div>
</div>
<?php
//$a = ProductImageController::getProductImage(Connection::getPdoInstance(), 1, 'main_image');
//echo '<img src="data:image/jpeg;base64,'.base64_encode( $a['image'] ).'"/>';
//echo '<img src="data:image/gif;base64,'.base64_encode( $a['image'] ).'"/>';

