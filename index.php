<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'app/config/constants.php';
require_once 'app/helpers/url_helper.php';
require_once 'app/core/App.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Model.php';
require_once 'app/core/Database.php';

$app = new App();
