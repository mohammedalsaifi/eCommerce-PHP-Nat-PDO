<?php

function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'default';
    }
}

function redirectHome($Msg, $url = null, $seconds = 3)
{
    if ($url == null) {
        $url = 'index.php';
    } else {
        isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $url = $_SERVER['HTTP_REFERER'] : $url = 'index.php'; {
        }
    }
    echo "<br>" . "<br>";
    echo $Msg;
    echo "<div class='alert alert-info'>You Will Be Redirected To Home Page After $seconds</div>";
    header("refresh:$seconds;url=$url");
    exit();
}

function checkItem($column, $table, $value)
{
    global $conn;
    $statement = $conn->prepare("SELECT $column FROM $table WHERE $column = ?;");
    $statement->execute(array($value));
    $count = $statement->rowCount();

    return $count;
}

function countItems($item, $table)
{
    global $conn;
    $stmt1 = $conn->prepare("SELECT COUNT($item) FROM $table;");
    $stmt1->execute();
    return $stmt1->fetchColumn();
}

function getLatest($column, $table, $order, $limit = 5)
{
    global $conn;

    $stmt2 = $conn->prepare("SELECT $column FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt2->execute();
    $rows = $stmt2->fetchAll();

    return $rows;
}
