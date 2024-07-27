<?php
$do = '';

if (isset($_GET['do'])) {
    $do = $_GET['do'];
} else {
    $do = 'manage';
}

if ($do == 'manage') {
    echo 'Welcome you are in manage page';
} elseif ($do == 'Add') {
    echo 'Welcome You Are In Add Category Page';
} elseif ($do == 'insert') {
    echo 'Welcome You Are In Insert Category Page';
} else {
    echo 'Error There\'s No Page With This Title';
}
