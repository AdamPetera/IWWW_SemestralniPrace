<div class="sticks_wrapper">
    <h2 class="stick_title"><i class="fas fa-check"></i>&nbsp;Florbalky</h2>
    <div class="sticks_preview">
        <?php
        foreach (Stick::getAllSticks() as $stick) {
            echo '
                  <a href="#">
                       <div class="stick_card">
                            <div class="stick_card_image_wrap">
                                <img src="' . $stick["image"] . '" alt="Florbalky">
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

