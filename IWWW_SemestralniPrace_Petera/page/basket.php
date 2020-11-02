<?php
session_destroy();
session_unset();
unset($_SESSION["email"]);
$_SESSION = array();
