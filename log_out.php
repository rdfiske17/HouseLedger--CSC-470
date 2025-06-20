<?php

session_start();

//echo $_SESSION["UserSession"];

unset($_SESSION["UserSession"]);

//echo $_SESSION["UserSession"];
header("Location: index.php");
exit();

?>