<?php
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'seller') {
        if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
            $conn = Connection::getPdoInstance();
            $product = ProductController::getProductById($conn, $product_id);
            $product_attributes = ProductHasAttributesController::getAllProductAttributes($product_id);
            $product_variants = ProductVariantsController::getAllProductVariants($product_id);
            //var_dump($product_variants);
        } else {
            die('Produkt neexistuje :(');
        }

        if (isset($_POST['update'])) {
            $variables_array = ProductController::setVariables($_POST, $product);
            $updateProductRowCount = ProductController::updateProduct($product_id, $_POST['name'], $_POST['description'], $_POST['price']);
            $updateProductCategoryRowCount = ProductHasCategoryController::updateProductCategory($product_id, $_POST['select']);
            if (($updateProductRowCount + $updateProductCategoryRowCount) == 2) {
                $success_message = 'Produkt úspěšně zeditován';
            }
        }

        if (isset($_POST['add_attribute'])) {
            $attribute_id = (int) AttributesController::getAttributeId($_POST['select']);
            if ($attribute_id) {
                ProductHasAttributesController::insertPHA($product_id, $attribute_id, $_POST['value']);
                echo '<script type="text/javascript">
                                window.location = "index.php?page=edit_product&product_id='.$product_id.'"
                            </script>';
            }
        }

        if (isset($_POST['add_variant'])) {
            if (!empty($_POST['var_value'])) {
                ProductVariantsController::insertVariantOfProduct($product_id, $_POST['var_value']);
                //header("Location: index.php?page=edit_product&product_id='.$product_id.'");
                echo '<script type="text/javascript">
                                window.location = "index.php?page=edit_product&product_id='.$product_id.'"
                            </script>';
            }
        }

        if (isset($_GET['remove_atr']) && is_numeric($_GET['remove_atr'])) {
            ProductHasAttributesController::deleteAttribute($product_id, $_GET['remove_atr'], $_GET['value_atr']);
            echo '<script type="text/javascript">
                                window.location = "index.php?page=edit_product&product_id='.$product_id.'"
                            </script>';
        }

        if (isset($_GET['remove_var'])) {
            ProductVariantsController::removeVariantOfProduct($product_id, $_GET['remove_var']);
            echo '<script type="text/javascript">
                                window.location = "index.php?page=edit_product&product_id='.$product_id.'"
                            </script>';
        }

    } else {
        die('Na editaci produktu musíš mít práva :(');
    }
} else {
    die('Tady nemáš co dělat :(');
}
?>

<div class="edit_product_form_wrap">
    <?php
    if (isset($_SESSION["role"])) {
        if ($_SESSION["role"] == "admin") {
            ?>
            <div class="edit_button">
                <p><a href="index.php?page=product&id=<?=$_GET['product_id']?>">Zpět na produkt</a></p>
            </div>
            <?php
        }
    }
    ?>
    <div class="forms">
        <div class="edit_product_form">
            <h1>Editace produktu</h1>
            <form method="post">
                <div class="txt_field">
                    <input type="text" name="name" value="<?=$product['name']?>">
                    <span></span>
                    <label>Název produktu</label>
                </div>
                <div class="txt_field">
                    <input type="text" name="description" value="<?=$product['description']?>">
                    <span></span>
                    <label>Popis produktu</label>
                </div>
                <div class="txt_field">
                    <input type="number" name="price" min="0" value="<?=$product['price']?>">
                    <span></span>
                    <label>Cena produktu</label>
                </div>
                <div class="selection">
                    <select name="select" required>
                        <?php
                        foreach (CategoryController::getAllCategories() as $cat) {
                            echo '<option value="'.$cat['name'].'">'.$cat['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="save_button">
                    <input name="update" type="submit" value="Uložit do databáze" class="savebtn">
                </div>
            </form>
        </div>
        <?php if (isset($success_message)): ?>
            <div class="successful">
                <div class="succ_mess">
                    <span class="message"><?php echo $success_message; ?></span>
                </div>
            </div>
        <?php endif ?>
        <div class="add_attribute_form">
            <h1>Přidání atributu</h1>
            <form method="post">
                <div class="selection">
                    <select name="select" required>
                        <?php
                        foreach (AttributesController::getAllAttributes() as $atr) {
                            echo '<option value="'.$atr['name'].'">'.strtoupper($atr['name']).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="txt_field">
                    <input type="text" name="value" required>
                    <span></span>
                    <label>Hodnota</label>
                </div>
                <div class="save_button">
                    <input name="add_attribute" type="submit" value="Přidat atribut" class="savebtn">
                </div>
            </form>
        </div>
        <div class="add_variant_form">
            <h1>Přidání varianty</h1>
            <form method="post">
                <div class="txt_field">
                    <input type="text" name="var_value" required>
                    <span></span>
                    <label>Varianta</label>
                </div>
                <div class="save_button">
                    <input name="add_variant" type="submit" value="Přidat variantu" class="savebtn">
                </div>
            </form>
        </div>
    </div>
    <form method="post" class="table_wrap">
        <table>
            <thead class="t_head">
            <tr>
                <td>Varianta</td>
            </tr>
            </thead>
            <tbody class="t_body">
            <?php if (empty($product_variants)): ?>
                <tr>
                    <td colspan="1" style="text-align: center">Produkt nemá žádné varianty</td>
                </tr>
            <?php else: ?>
                <?php foreach ($product_variants as $pv): ?>
                    <tr>
                        <td>
                            <p><?=$pv?></p>
                            <br>
                            <a href="index.php?page=edit_product&product_id=<?=$product_id?>&remove_var=<?=$pv?>" class="remove">Odstranit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
    <form method="post" class="table_wrap">
        <table>
            <thead class="t_head">
            <tr>
                <td>Atribut</td>
                <td>Hodnota</td>
            </tr>
            </thead>
            <tbody class="t_body">
            <?php if (empty($product_attributes)): ?>
                <tr>
                    <td colspan="2" style="text-align: center">Produkt nemá žádné atributy</td>
                </tr>
            <?php else: ?>
                <?php foreach ($product_attributes as $pa): ?>
                    <tr>
                        <td>
                            <p><?=$pa['human_readable']?></p>
                            <br>
                            <a href="index.php?page=edit_product&product_id=<?=$product_id?>&remove_atr=<?=$pa['attribute_id']?>&value_atr=<?=$pa['value']?>" class="remove">Odstranit</a>
                        </td>
                        <td class="value"><?=$pa['value']?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>
