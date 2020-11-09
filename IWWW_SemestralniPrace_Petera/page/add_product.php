<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidání produktu</title>
    <link rel="stylesheet" href="../styles/add_product.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        $conn = Connection::getPdoInstance();
        if (isset($_POST)) {
            if (isset($_FILES['image'])) {
                if (!empty($_FILES['image']['name'])) {
                    $errors = array();
                    $file_name = $_FILES['image']['name'];
                    $file_size = $_FILES['image']['size'];
                    $file_tmp = $_FILES['image']['tmp_name'];
                    $file_type = $_FILES['image']['type'];
                    $tmp_array = explode('.',$_FILES['image']['name']);
                    $file_ext = strtolower(end($tmp_array));

                    $extensions = array("jpeg","jpg","png");

                    if (in_array($file_ext,$extensions) == false) {
                        $errors[] = "Nepodporovaná přípona, prosím vyberte příponu .jpg/.png/.jpeg.";
                    }

                    if ($file_size > 16777216) {
                        $errors[] = 'Soubor nesmí být větší než 16 MB';
                    }

                    $validation = ProductController::insertProductAndImageValidation($_POST['name'], $_POST['description'], $_POST['price'], $_POST['img_name']);
                    if (count($validation) == 0) {
                        if (empty($errors) == true) {
                            $image_path = 'images/'.$file_name;
                            ProductController::insertProduct($_POST['name'], $_POST['description'], $_POST['price']);
                            $last_inserted_product_id = $conn->lastInsertId();
                            ProductImageController::insert($_POST['img_name'], $image_path, $last_inserted_product_id);
                            ProductHasCategoryController::insert($last_inserted_product_id, $_POST['select']);
                            move_uploaded_file($file_tmp,"images/".$file_name);
                            $success_message = "Produkt úspěšně vložen";
                        } else {
                            print_r($errors);
                        }

                    }
                } else {
                    echo 'Nenahrál jsi žádný soubor!';
                }
            }
        }
    }
} else {
    echo 'Tady nemáš co dělat :(';
}
?>

<div class="add_product_form_wrap">
    <div class="add_product_form">
        <h1>Přidání produktu</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="txt_field">
                <input type="text" name="name" required>
                <span></span>
                <label>Název produktu</label>
            </div>
            <div class="txt_field">
                <input type="text" name="description" required>
                <span></span>
                <label>Popis produktu</label>
            </div>
            <div class="txt_field">
                <input type="number" name="price" min="0" required>
                <span></span>
                <label>Cena produktu</label>
            </div>
            <div class="txt_field">
                <input type="text" name="img_name" required>
                <span></span>
                <label>Název obrázku</label>
            </div>
            <input class="image_upload" type="file" name="image" required>
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
</div>