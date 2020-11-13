<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Editace produktu</title>
    <link rel="stylesheet" href="../styles/add_product.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
            $conn = Connection::getPdoInstance();
            $product = ProductController::getProductById($conn, $product_id);
            $product_attributes = ProductHasAttributesController::getAllProductAttributes($product_id);
        } else {
            die('Produkt neexistuje :(');
        }

        if (isset($_POST['update'])) {
            $variables_array = ProductController::setVariables($_POST, $product);
            $updateRowCount = ProductController::updateProduct($product_id, $_POST['name'], $_POST['description'], $_POST['price']);
            if ($updateRowCount != 0) {
                $success_message = 'Produkt úspěšně zeditován';
            }
        }

        if (isset($_POST['add_attribute'])) {
            $attribute_id = (int) AttributesController::getAttributeId($_POST['select']);
            if ($attribute_id) {
                ProductHasAttributesController::insertPHA($product_id, $attribute_id, $_POST['value']);
            }
        }

        if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
            ProductHasAttributesController::deleteAttribute($product_id, $_GET['remove'], $_GET['value']);
        }

    } else {
        die('Na editaci produktu musíš mít práva :(');
    }
} else {
    die('Tady nemáš co dělat :(');
}
?>

<div class="edit_product_form_wrap">
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
                <div class="save_button">
                    <input name="update" type="submit" value="Uložit do databáze" class="save">
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
                    <input name="add_attribute" type="submit" value="Přidat atribut" class="save">
                </div>
            </form>
        </div>
    </div>
    <form method="post">
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
                            <a href="index.php?page=edit_product&product_id=<?=$product_id?>&remove=<?=$pa['attribute_id']?>&value=<?=$pa['value']?>" class="remove">Odstranit</a>
                        </td>
                        <td class="value"><?=$pa['value']?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>
