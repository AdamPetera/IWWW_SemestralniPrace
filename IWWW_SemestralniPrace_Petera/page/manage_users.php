<?php
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "admin" || $_SESSION['role'] == 'seller') {
        $roles = RoleController::getAllRoles();
        $users = UserController::getAllUsersWithRoles();

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
<div class="orders_wrap">
    <h2>Uživatelé systému</h2>
    <form method="post">
        <table>
            <thead class="t_head">
            <tr>
                <td>ID</td>
                <td>Jméno</td>
                <td>Příjmení</td>
                <td>Email</td>
                <td>Telefon</td>
                <td>Role</td>
            </tr>
            </thead>
            <tbody class="t_body">
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" style="text-align: center">V systému nejsou žádní uživatelé</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="user_id"><?=$user['user_id']?></td>
                        <td class="user_firstname"><?=$user['firstname']?></td>
                        <td class="user_lastname"><?=$user['lastname']?></td>
                        <td class="user_email"><?=$user['email']?></td>
                        <td class="user_phone"><?=$user['phone']?></td>
                        <td class="user_role"><?=$user['rolename']?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
    <form method="post">
        <div class="selection">
            <select name="select" required>
                <?php
                    foreach ($users as $user) {
                        echo '<option value="'.$user['user_id'].'">'.$user['user_id'].' - '.$user['firstname'].' '.$user['lastname'].'</option>';
                    }
                ?>
            </select>
        </div>
    </form>
    <button>Tlačidlo</button>
</div>

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
                <input type="text" name="firstname" id="aaa">
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