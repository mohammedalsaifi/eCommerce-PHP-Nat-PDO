<?php
ini_set('dispaly_errors', 'on');
error_reporting(E_ALL);
include 'admin/connect.php';
$tpl = 'includes/templates/';
$lang = 'includes/languages/';
$func = 'includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';

include $func . 'functions.php';
include $lang . 'english.php';
include $tpl . 'header.php';
