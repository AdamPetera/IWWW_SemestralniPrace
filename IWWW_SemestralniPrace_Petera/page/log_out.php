<?php
session_destroy();
session_unset();
unset($_SESSION["email"]);
$_SESSION = array();
echo '<script type="text/javascript">
    window.location = "index.php"
</script>';
