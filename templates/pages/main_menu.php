<h1>Menu<h1>

<?php 
include("../../src/Utils/debug.php");
session_start();
echo "Witaj użytkowniku" . ' ' . $_SESSION['userId']['id'];

?>
