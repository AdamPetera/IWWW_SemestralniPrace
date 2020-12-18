<?php
if (isset($_POST['addAddress'])) {
    $user_id = $_GET['id'];
    $validation = AddressController::addressValidation($_POST['street'], $_POST['no'], $_POST['city'], $_POST['zipcode']);

    if (count($validation) == 0) {
        if (AddressController::insertAddress($_POST['street'], $_POST['no'], $_POST['city'], $_POST['zipcode'], $user_id) == 1) {
            echo '<script type="text/javascript">
                     window.location = "index.php?page=user_details"
                  </script>';
        } else {
            $error_message = "Něco se pokazilo :(";
        }

    } else {
        $error_message = "Některé zadané údaje nejsou validní";
    }
}
?>

<div class="add_address_form_wrap">
    <div class="add_address_form">
        <h1>Přidání adresy</h1>
        <form method="post">
            <div class="txt_field">
                <input type="text" name="street" required>
                <span></span>
                <label>Ulice</label>
            </div>
            <div class="txt_field">
                <input type="text" name="no" required>
                <span></span>
                <label>Číslo popisné</label>
            </div>
            <div class="txt_field">
                <input type="text" name="city" required>
                <span></span>
                <label>Obec</label>
            </div>
            <div class="txt_field">
                <input type="number" name="zipcode" min="0" required>
                <span></span>
                <label>PSČ</label>
            </div>
            <input type="submit" name="addAddress" value="Přidat adresu">
        </form>
    </div>
    <?php if (isset($error_message)): ?>
        <div class="form_error">
            <span class="error"><?php echo $error_message; ?></span>
        </div>
    <?php endif ?>
</div>
