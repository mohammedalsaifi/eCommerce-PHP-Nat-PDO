<?php

$pageTitle = 'Comments';
session_start();
if (isset($_SESSION['username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $query = '';

        $stmt = $conn->prepare("SELECT
                                    comments.*,
                                    items.Name AS Item_Name,
                                    users.Username AS User
                                FROM
                                    comments
                                INNER JOIN
                                    items
                                ON
                                    items.Item_ID
                                    =
                                    comments.Item_ID
                                INNER JOIN
                                    users
                                ON
                                    users.UserID
                                    =
                                    comments.User_ID;");
        $stmt->execute();
        $rows = $stmt->fetchAll();

?>

        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-dark table-hover border">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Username</td>
                        <td>Full Name</td>
                        <td>Add Date</td>
                        <td>Action</td>
                    </tr>
                    <?php foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['C_ID'] . "</td>";
                        echo "<td>" . $row['Comment'] . "</td>";
                        echo "<td>" . $row['Item_Name'] . "</td>";
                        echo "<td>" . $row['User'] . "</td>";
                        echo "<td>" . $row['Comment_Date'] . "</td>";
                        echo "<td>
                        <a href='comments.php?do=Edit&comntid=" . $row['C_ID'] . "' class='fa fa-edit btn btn-secondary'>Edit</a>
                        <a href='comments.php?do=Delete&comntid=" . $row['C_ID'] . "'  class='fa fa-close btn btn-danger'>Delete</a>";
                        if ($row['Status'] == 0) {
                            echo "<a href='comments.php?do=Approve&comntid=" . $row['C_ID'] . "'  class='btn btn-info'>Activate</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    } ?>
                </table>
            </div>
        </div>
    <?php } elseif ($do == 'Add') {  ?>
        <h1 class="text-center">Add Item</h1>
        <div class="container">
            <form class="edit-form" action="?do=Insert" method="POST">
                <div class="form-group mb-3">
                    <label for="exampleInputEmail1" class="form-label">Name</label>
                    <input type="hidden" name="itemid">
                    <input type="text" class="form-control" name="name" aria-describedby="emailHelp" autocomplete="off">
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Description</label>
                    <input type="text" name="description" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Price</label>
                    <input type="text" name="price" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Country</label>
                    <input type="text" name="country" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Status</label>
                    <select class="form-control" name="status" id="">
                        <option value="0">...</option>
                        <option value="1">New</option>
                        <option value="2">Like New</option>
                        <option value="3">Used</option>
                        <option value="4">Very Old</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Members</label>
                    <select class="form-control" name="member" id="">
                        <option value="0">...</option>
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM users");
                        $stmt->execute();
                        $users = $stmt->fetchAll();
                        foreach ($users as $user) {
                            echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Categories</label>
                    <select class="form-control" name="category" id="">
                        <option value="0">...</option>
                        <?php
                        $stmt1 = $conn->prepare("SELECT * FROM categories");
                        $stmt1->execute();
                        $cats = $stmt1->fetchAll();
                        foreach ($cats as $cat) {
                            echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <input type="submit" value="Save" class="btn btn-primary">
                </div>
                <?php
            } else if ($do == 'Edit') {

                $comntid = isset($_GET['comntid']) && is_numeric($_GET['comntid']) ? intval($_GET['comntid']) : 0;
                $stmt = $conn->prepare("SELECT comment FROM comments WHERE C_ID = ?;");
                $stmt->execute(array($comntid));
                $row = $stmt->fetch();

                if ($stmt->rowCount() > 0) { ?>
                    <h1 class="text-center">Edit Comment</h1>
                    <div class="container">
                        <form class="edit-form" action="?do=Update" method="POST">
                            <div class="form-group mb-3">
                                <label for="exampleInputPassword1" class="form-label">Comment</label>
                                <input type="hidden" name="comntid" value="<?php echo $comntid ?>">
                                <textarea type="textarea" name="comment" class="form-control"><?php echo $row['comment'] ?></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <input type="submit" value="Save" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
        <?php
                } else {
                    echo "<div class='container'>";
                    $Msg = "<div class='alert alert-danger'>There Is No Such ID</div>";
                    redirectHome($Msg);
                    echo "</div>";
                }
            } else if ($do == 'Update') {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo "<h1 class='text-center'>Update Member</h1>";
                    echo '<div class="container">';

                    $id = $_POST['comntid'];
                    $comment = $_POST['comment'];
                    $errorForm = array();

                    $stmt = $conn->prepare("UPDATE comments SET comment = ? Where C_ID = ?");
                    $stmt->execute(array($comment, $id));
                    $Msg = '<div class = "alert alert-success text-center">' . $stmt->rowCount() . '<storng>Field Updated</storng></div>';
                    redirectHome($Msg);
                } else {
                    echo "<div class='container'>";
                    $Msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
                    redirectHome($Msg);
                    echo "</div>";
                }
                echo "</div>";
            } else if ($do == 'Delete') {

                $comntid = isset($_GET['comntid']) && is_numeric($_GET['comntid']) ? intval($_GET['comntid']) : 0;
                $check = checkItem('C_ID', 'comments', $comntid);

                if ($check > 0) {
                    $stmt = $conn->prepare("DELETE FROM comments WHERE C_ID = :zid");
                    $stmt->bindParam(":zid", $comntid);
                    $stmt->execute();
                    echo "<div class='container'>";
                    $Msg = '<div class="alert alert-danger text-center"><storng>Field Deleted</storng></div>';
                    redirectHome($Msg, 'back');
                    echo "</div>";
                } else {
                    $Msg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
                }
            } else if ($do == 'Approve') {

                $comntid = isset($_GET['comntid']) && is_numeric($_GET['comntid']) ? intval($_GET['comntid']) : 0;
                $check = checkItem('C_ID', 'comments', $comntid);

                if ($check > 0) {
                    $stmt = $conn->prepare("UPDATE comments SET Status = 1 WHERE C_ID = ?;");
                    $stmt->execute(array($comntid));
                    echo "<div class='container'>";
                    $Msg = '<div class="alert alert-success text-center"><storng>Field Activated</storng></div>';
                    redirectHome($Msg, 'back');
                    echo "</div>";
                }
                include $tpl . 'footer.php';
            } else {
                header("location: index.php");
            }
        }
