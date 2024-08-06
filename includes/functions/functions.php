<?php

function getAllForm($feild, $table, $where = NULL, $AND = NULL, $orderfield = NULL, $ordering = "DESC")
{
    global $conn;
    $getAll = $conn->prepare("SELECT $feild FROM $table $where $AND ORDER BY $orderfield $ordering");
    $getAll->execute();
    $row = $getAll->fetchAll();
    return $row;
}

function getAll($tableName)
{
    global $conn;
    $getAll = $conn->prepare("SELECT * FROM $tableName");
    $getAll->execute();
    $row = $getAll->fetchAll();
    return $row;
}

function getCat()
{
    global $conn;

    $getCat = $conn->prepare("SELECT * FROM categories ORDER BY ID ASC;");
    $getCat->execute();
    $rows = $getCat->fetchAll();

    return $rows;
}

function getItems($where, $value)
{
    global $conn;

    $getItems = $conn->prepare("SELECT * FROM items WHERE $where = ? ORDER BY Item_ID DESC;");
    $getItems->execute(array($value));
    $rows = $getItems->fetchAll();

    return $rows;
}

function checkUserStatus($user)
{
    global $conn;
    $stmt = $conn->prepare("SELECT 
                                    Username, RegStatus 
                            From    users 
                            WHERE   Username = ?
                            AND     RegStatus = 0;");
    $stmt->execute(array($user));
    $status = $stmt->rowCount();
    return $status;
}

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
