<!DOCTYPE html>
<html lang="cs">
<?php
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "admin" || $_SESSION['role'] == 'seller') {
        $roles = RoleController::getAllRoles();
        $dataTable = new DataTable(UserController::getAllUsers());
        $dataTable->addColumn('user_id', 'ID');
        $dataTable->addColumn('firstname', 'Jméno');
        $dataTable->addColumn('lastname', 'Příjmení');
        $dataTable->addColumn('email', 'Email');
        $dataTable->addColumn('phone', 'Telefon');
        $dataTable->renderTable();

        if (isset($_POST["updateUser"])) {
            $conn = Connection::getPdoInstance();
            $result = UserController::emailExistsReturnArray($conn, $_POST["current_email"]);
            $row = $result["row"];
            $rowCount = $result["rowCount"];

            if ($rowCount == 1) {
                $variableArray = UserController::setVariables($_POST, $row);

                if ($variableArray['emailRowCount'] > 0) {
                    $error_message = "Tento nový email již někdo používá!";
                } else {
                    $role_id = RoleController::getRoleIdByName($_POST['select']);
                    if ($role_id) {
                        $user_id = UserController::getUserIdByEmail($_POST["current_email"]);
                        UserController::updateUser($conn, $_POST["current_email"], $variableArray['firstname'],
                            $variableArray['lastname'], $variableArray['email'], $variableArray['phone'],
                            $variableArray['password']);
                        RoleController::updateUserRole($user_id, $role_id);
                        echo '<script type="text/javascript">
                              window.location = "index.php?page=manage_users"
                              </script>';
                    } else {
                        $error_message = "Něco se pokazilo :(";
                    }
                }
            } else {
                $error_message = "Uživatel s tímto emailem neexistuje";
            }
        }
    } else {
        die('Na editaci produktu musíš mít práva :(');
    }
} else {
    die('Tady nemáš co dělat :(');
}
?>

<div class="edit_form_wrap">
    <div class="edit_form">
        <h1>Úprava uživatelů</h1>
        <form method="post">
            <div class="txt_field">
                <input type="text" name="current_email" required>
                <span></span>
                <label>Stávající email</label>
            </div>
            <div class="txt_field">
                <input type="text" name="firstname">
                <span></span>
                <label>Jméno</label>
            </div>
            <div class="txt_field">
                <input type="text" name="lastname">
                <span></span>
                <label>Příjmení</label>
            </div>
            <div class="txt_field">
                <input type="email" name="email">
                <span></span>
                <label>Email</label>
            </div>
            <div class="txt_field">
                <input type="tel" name="phone" pattern="((\+420|00420) ?)?\d{3}( |-)?\d{3}( |-)?\d{3}">
                <span></span>
                <label>Telefonní číslo</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password">
                <span></span>
                <label>Heslo</label>
            </div>
            <div class="selection">
                <select name="select" required>
                    <?php
                    foreach ($roles as $role) {
                        echo '<option value="'.$role.'">'.$role.'</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="submit" name="updateUser" value="Uložit změny">
        </form>
    </div>
    <?php if (isset($error_message)): ?>
        <div class="form_error">
            <span class="error"><?php echo $error_message; ?></span>
        </div>
    <?php endif ?>
</div>