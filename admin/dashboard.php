<?php

ob_start();
$pageTitle = 'Dashboard';
session_start();
if (isset($_SESSION['username'])) {
    include 'init.php';

?>

    <div class="container home-stats">
        <h1 class="text-center">Dashboard</h1>
        <br>
        <br>
        <div class="row">
            <div class="col-md-3 border text-center">
                <div class="state">
                    Total Number
                    <span><a href="members.php"><?php echo countItems("UserID", "users"); ?></a></span>
                </div>
            </div>
            <div class="col-md-3 border text-center">
                <div class="stat">
                    Pending Number
                    <span><a href="members.php?page=Pending">
                            <?php echo checkItem("RegStatus", "users", 0); ?>
                    </span></a>

                </div>
            </div>
            <div class="col-md-3 border text-center">
                <div class="state">
                    Total Items
                    <span><a href="items.php"><?php echo countItems("Item_ID", "items"); ?></a></span>
                </div>
            </div>
            <div class="col-md-3 border text-center">
                <div class="state">
                    Total Comments
                    <span><a href="comments.php"><?php echo countItems("C_ID", "comments"); ?></a></span>
                </div>
            </div>
            <div class="latest">
                <br>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-default text-center">

                                <div class="panel-heading">
                                    <i class="fa fa-user">Latest Registered Users</i>
                                </div>

                                <div class="panel-body">
                                    <?php
                                    $latestUser = getLatest("Username", "users", "UserID");

                                    if ($latestUser) {
                                        foreach ($latestUser as $user) {
                                            echo $user['Username'] . "<br>";
                                        }
                                    } else {
                                        echo 'There Is No Record To Show';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-default text-center">
                                <div class="panel-heading">
                                    <i class="fa fa-user">Latest Registered Users</i>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    $latestItems = getLatest("Name", "items", "Item_ID");
                                    if (!empty($latestItems)) {
                                        $latestItems = getLatest("Name", "items", "Item_ID");

                                        foreach ($latestItems as $item) {
                                            echo $item['Name'] . "<br>";
                                        }
                                    } else {
                                        echo 'There Is No Record To Show';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include $tpl . 'footer.php';
} else {
    header("location: index.php");
}

ob_end_flush();

?>