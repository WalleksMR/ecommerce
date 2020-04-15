<?php 
session_start();
require_once("vendor/autoload.php");
use \Slim\Slim;
$app = new Slim();

$app->config('debug', true);

include_once("function.php");
include_once("Site/Site.php");
include_once("Site/Category.php");
include_once("Dashboard/Admin.php");
include_once("Dashboard/AdminUsers.php");
include_once("Dashboard/AdminCategories.php");
include_once("Dashboard/AdminProducts.php");

$app->run();

 ?>