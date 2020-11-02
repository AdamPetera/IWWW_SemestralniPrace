<!DOCTYPE html>
<html lang="cs">
<?php
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 'admin') {
            $dataTable = new DataTable(UserController::getAllUsers());
            $dataTable->addColumn('user_id', 'ID');
            $dataTable->addColumn('firstname', 'Jméno');
            $dataTable->addColumn('lastname', 'Příjmení');
            $dataTable->addColumn('email', 'Email');
            $dataTable->addColumn('phone', 'Telefon');
            $dataTable->renderTable();
        }
    }
?>

<div class="edit_form_wrap">
    <div class="edit_form">
        <h1>Úprava uživatele</h1>
        <form method="post">
            <div class="txt_field">
                <input type="text" name="current_email" required>
                <span></span>
                <label>Stávající email</label>
            </div>
            <div class="txt_field">
                <input type="text" name="firstname" required>
                <span></span>
                <label>Jméno</label>
            </div>
            <div class="txt_field">
                <input type="text" name="lastname" required>
                <span></span>
                <label>Příjmení</label>
            </div>
            <div class="txt_field">
                <input type="email" name="email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="txt_field">
                <input type="tel" name="phone" pattern="((\+420|00420) ?)?\d{3}( |-)?\d{3}( |-)?\d{3}" required>
                <span></span>
                <label>Telefonní číslo</label>
            </div>
            <input type="submit" value="Uložit změny">
        </form>
    </div>
</div>