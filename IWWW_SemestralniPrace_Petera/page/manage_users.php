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

    if (isset($_POST["updateUser"])) {
        $conn = Connection::getPdoInstance();
        $result = UserController::emailExistsReturnArray($conn, $_POST["current_email"]);
        $row = $result["row"];
        $rowCount = $result["rowCount"];

        if ($rowCount == 1) {
            if (isset($_POST["firstname"])) {
                if (!empty($_POST["firstname"])) {
                    $firstname = $_POST["firstname"];
                } else {
                    $firstname = $row["firstname"];
                }
            }
            if (isset($_POST["lastname"])) {
                if (!empty($_POST["lastname"])) {
                    $lastname = $_POST["lastname"];
                } else {
                    $lastname = $row["lastname"];
                }
            }
            if (isset($_POST["email"])) {
                if (!empty($_POST["email"])) {
                    $email = $_POST["email"];
                    $emailRowCount = UserController::emailExists($conn, $email);
                } else {
                    $email = $row["email"];
                }
            }
            if (isset($_POST["phone"])) {
                if (!empty($_POST["phone"])) {
                    $phone = $_POST["phone"];
                } else {
                    $phone = $row["phone"];
                }
            }

            if ($emailRowCount > 0) {
                $error_message = "Tento nový email již někdo používá!";
            } else {
                $updateRowCount = UserController::updateUser($conn, $_POST["current_email"], $firstname, $lastname, $email, $phone);

                if ($updateRowCount == 1) {
                    echo '<script type="text/javascript">
                    window.location = "index.php?page=manage_users"
                    </script>';
                }
            }

        } else {
            $error_message = "Uživatel s tímto emailem neexistuje";
        }
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
            <input type="submit" name="updateUser" value="Uložit změny">
        </form>
    </div>
    <?php if (isset($error_message)): ?>
        <div class="form_error">
            <span class="error"><?php echo $error_message; ?></span>
        </div>
    <?php endif ?>
</div>