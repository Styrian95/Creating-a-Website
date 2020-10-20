<?php

require_once('../classes/course.php');
require_once('../classes/database.php');
require_once('../classes/document.php');
require_once('../classes/internalUser.php');
require_once('../classes/user.php');


define('DB_HOST', 'localhost');
define('DB_USER', 'luis1995');
define('DB_PASSWORD', 'Lukas1995');
define('DB_NAME', 'seminarportal');

$database = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);


?>