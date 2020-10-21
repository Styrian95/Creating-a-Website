<?php

//https://www.w3schools.com/php/php_superglobals.asp

print_r($_SERVER);

//vereint post und get array = egal ob index in einem get oder post array ist

print_r($_REQUEST);

//html form -> post

print_r($_POST);

//variablen über link übergeben -> get

print_r($_GET);

?>