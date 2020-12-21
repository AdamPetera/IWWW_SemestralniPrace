<?php
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "admin" || $_SESSION['role'] == 'seller') {
        $users = UserController::getAllUsersWithRoles();
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
                        <td class="user_id"><a href="index.php?page=edit_user&user_id=<?=$user['user_id']?>"><?=$user['user_id']?></a></td>
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
</div>