<?php
ob_start();
session_start();
$pageTitle = 'Show Item';
include "init.php";

$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

$stmt = $conn->prepare("SELECT 
                                items.*,
                                categories.Name AS Category_Name,
                                users.Username
                        FROM    items
                        INNER JOIN
                                categories
                        ON
                                categories.ID = items.Cat_ID
                        INNER JOIN
                                users
                        ON
                                users.UserID = items.Member_ID
                        WHERE   Item_ID = ?;");
$stmt->execute(array($itemid));
if ($stmt->rowCount()) {
    $row = $stmt->fetch();
?>

    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

    <div class="container">
        <h1 class="text-center"><?php echo $row['Name']; ?></h1>
        <br>
        <div class="card text-center">
            <img src="..." class="card-img-top" alt="...">
            <div class="card-body">
                <h3 class="card-title"><?php echo 'Name:' . $row['Name']; ?></h3>
                <h4 class="card-text"><?php echo 'Description:' . $row['Description']; ?></h4>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5><?php echo 'Price: $' . $row['Price']; ?></h5>
                </li>
                <li class="list-group-item">
                    <h5><?php echo 'Country: ' . $row['Country_Made']; ?></h5>
                </li>
                <li class="list-group-item">
                    <h5><?php echo 'Date: ' . $row['Add_Date']; ?></h5>
                </li>
            </ul>
            <div class="card-body">
                <a href="categories.php?pageid=<?php echo $row['Cat_ID']; ?>" class="card-link"><?php echo 'Category: ' . $row['Category_Name']; ?></a>
                <a href="#" class="card-link"><?php echo 'Added By: ' . $row['Username']; ?></a>

            </div>
        </div>
        <br>
        <div class="card">
            <?php if (isset($_SESSION['user'])) { ?>
                <h3 class="card-header">Add Your Comment</h3>
                <div class="card-body">
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $row['Item_ID']; ?>" method="POST">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Comment</label>
                            <textarea class="form-control" name="comment"></textarea><br>
                            <input type="submit" class="btn btn-success form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                    </form>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $comment = htmlspecialchars($_POST['comment']);
                        $userid = filter_var($_SESSION['uid'], FILTER_VALIDATE_INT);
                        $itemid = filter_var($row['Item_ID'], FILTER_VALIDATE_INT);

                        if (!empty($comment)) {
                            $stmt = $conn->prepare("Insert Into
                                                    comments(comment, status, comment_date, item_id, user_id)
                                                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid);");

                            $stmt->execute(array(
                                'zcomment' => $comment,
                                'zitemid'  => $itemid,
                                'zuserid'  => $userid
                            ));
                            if ($stmt) {
                                echo '<div class="alert alert-success">Comment Added</div>';
                            } else {
                                echo 'Something went Wrong';
                            }
                        }
                    }
                    ?>
                </div>
            <?php
            } else {
                echo 'You Need To Be <a href="login.php">Registered OR Login</a> For Adding Comment';
            }
            ?>
        </div>
        <br>
        <?php
        $stmt = $conn->prepare("SELECT 
                                    comments.*,
                                    users.Username AS Member
                                from 
                                    comments
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = comments.User_ID
                                WHERE
                                    Item_ID = ?
                                AND
                                    status = 1
                                ORDER BY
                                    C_ID
                                DESC;");

        $stmt->execute(array($row['Item_ID']));

        $comments = $stmt->fetchAll();
        ?>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-header">User Comment</h3><br>
                        <?php
                        foreach ($comments as $comment) {
                            echo 'Comment: ' . $comment['Comment'] . "<br>";
                            echo 'Added By: ' . $comment['Member'] . "<br><br>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-header">User Image</h3> <br>
                        <img class="card-img-top" src="..." alt="Card image cap">
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    echo 'There\s Is No Such ID.';
}
include $tpl . 'footer.php';
ob_end_flush();
