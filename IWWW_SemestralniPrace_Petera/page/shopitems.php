<div class="items_wrapper">
    <?php
        echo '<h2 class="items_title">'. ProductController::getAllSpecifiedItemName($_GET["items"]) .'</h2>';
    ?>
    <div class="items_preview">
        <?php
        foreach (ProductController::getAllSpecifiedItems($_GET["items"]) as $item) {
            echo '<div class="item_card">
                    <a href="#">
                        <div class="item_card_image_wrap">
                            <img src="' . $item["image"] . '" alt="'. $_GET["items"] .'">
                        </div>
                    </a>
                    <div class="item_card_name">
                        <h2 class="title">' . $item["name"] . '</h2>
                    </div>
                    <div class="item_card_price">
                        <span class="price" itemprop="price" content="' . $item["price"] . '">' . $item["price"] . ' Kč</span>
                    </div>
                    <div class="item_card_button">
                        <button class="item_detail_button">Detail produktu</button>
                    </div>
                </div>';
            }
        ?>
    </div>
</div>
