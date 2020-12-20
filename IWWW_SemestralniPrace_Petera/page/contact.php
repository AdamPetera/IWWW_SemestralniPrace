<?php
    if (isset($_POST['send'])) {
        $wholename = htmlspecialchars($_POST['wholename']);
        $email = htmlspecialchars($_POST['email']);
        $question_name = htmlspecialchars($_POST['question_name']);
        $message = htmlspecialchars($_POST['message']);

        $validation = ContactController::contactValidation($wholename, $email, $question_name, $message);

        if (count($validation) == 0) {
            if (!empty(htmlspecialchars($_POST['phone']))) {
                ContactController::insertMessage($wholename, $email, $question_name, $message, htmlspecialchars($_POST['phone']));
            } else {
                ContactController::insertMessage($wholename, $email, $question_name, $message);
            }

            $success_message = "Vaše otázka k nám úspěšně dorazila!";
        }
    }
?>

<div class="contact_form_wrap">
    <div class="contact_form">
        <h1>Neváhejte nás s čímkoli zkontaktovat!</h1>
        <form method="post">
            <div class="txt_field">
                <input type="text" name="wholename" required value="<?= isset($_SESSION['row']) ? $_SESSION['row']['firstname'] .' '.  $_SESSION['row']['lastname'] : ''; ?>">
                <span></span>
                <label>Celé jméno *</label>
            </div>
            <div class="txt_field">
                <input type="email" name="email" required value="<?= isset($_SESSION['row']) ? $_SESSION['row']['email'] : ''; ?>">
                <span></span>
                <label>Email *</label>
            </div>
            <div class="txt_field">
                <input type="tel" name="phone" value="<?= isset($_SESSION['row']) ? $_SESSION['row']['phone'] : ''; ?>"
                       pattern="((\+420|00420) ?)?\d{3}( |-)?\d{3}( |-)?\d{3}">
                <span></span>
                <label>Telefonní číslo (nepovinné)</label>
            </div>
            <div class="txt_field">
                <input type="text" name="question_name" required>
                <span></span>
                <label>Název dotazu *</label>
            </div>
            <div class="txt_field">
                <input type="text" name="message" required>
                <span></span>
                <label>Vaše zpráva *</label>
            </div>
            <input type="submit" name="send" value="Odeslat">
        </form>
    </div>
    <?php if (isset($success_message)): ?>
        <div class="success_div">
            <span class="success_message"><?php echo $success_message; ?></span>
        </div>
    <?php endif ?>
</div>