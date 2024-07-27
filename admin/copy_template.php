<?php

ob_start();
session_start();
$pageTitle = '';

if (isset($_SESSION['username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Add') {
    } elseif ($do == 'Insert') {
    } elseif ($do == 'Edit') {
    } elseif ($do == 'Update') {
    } elseif ($do == 'Delete') {
    } elseif ($do == 'Activate') {
    }

}else {
    header('location: index.php');
    exit();
}

ob_end_flush();
?>