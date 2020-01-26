<?php

ini_set('display_errors', 'off');
error_reporting(0);

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require 'libs/Bootstrap.php';
require 'libs/Controller.php';
require 'libs/Model.php';
require 'libs/View.php';
require 'libs/Database.php';
require 'libs/Session.php';
require 'libs/hash/Hash.php';
require 'config/constants.php';
require 'config/paths.php';
require 'config/database.php';

// phpinfo();

$app = new Bootstrap();

?>