<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Registrace</title>
    <link rel="stylesheet" href="../styles/add_product.css">
    <script src="https://kit.fontawesome.com/cb337acf51.js" crossorigin="anonymous"></script>
</head>

<?php
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        $conn = Connection::getPdoInstance();
        if (isset($_FILES['image'])) {
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

            if (empty($errors) == true) {
                move_uploaded_file($file_tmp,"images/".$file_name);
                echo "Úspěšně vybráno";
            } else {
                print_r($errors);
            }
        }

        if (isset($_POST['update'])) {
            echo 'aaa';
            if (isset($_FILES['image'])) {
                ProductImageController::insert($conn, $_POST['img_name'], "", $_FILES['image'], 1);
            } else {
                echo 'mimo';
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
        <form method="post">
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
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="image"/>
                <input name="update" class="check" type="submit" value="Zkontroluj"/>
            </form>
            <div class="save_button">
                <input name="update" type="submit" value="Uložit do databáze" class="save">
            </div>
        </form>
    </div>
</div>