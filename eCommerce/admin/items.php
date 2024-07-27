<?php

ob_start();
session_start();
$pageTitle = 'Items';

if (isset($_SESSION['username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $stmt = $conn->prepare("SELECT 
                                        items.*, categories.Name 
                                AS 
                                        category_name, 
                                        users.Username 
                                FROM 
                                        Items
                                INNER JOIN
                                        categories
                                ON
                                        categories.ID = items.Cat_ID
                                INNER JOIN
                                        users
                                ON
                                        users.UserID = items.Member_ID");
        $stmt->execute();
        $rows = $stmt->fetchAll();

?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-dark table-hover border">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Action</td>
                    </tr>
                    <?php foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['Item_ID'] . "</td>";
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td>" . $row['Description'] . "</td>";
                        echo "<td>" . $row['Price'] . "</td>";
                        echo "<td>" . $row['Add_Date'] . "</td>";
                        echo "<td>" . $row['category_name'] . "</td>";
                        echo "<td>" . $row['Username'] . "</td>";
                        echo "<td>
                        <a href='items.php?do=Edit&itemid=" . $row['Item_ID'] . "' class='fa fa-edit btn btn-secondary'>Edit</a>
                        <a href='items.php?do=Delete&itemid=" . $row['Item_ID'] . "'  class='fa fa-close btn btn-danger'>Delete</a>";
                        if ($row['Approve'] == 0) {
                            echo "<a href='items.php?do=Approve&itemid="
                                . $row['Item_ID'] .
                                "'  class='btn btn-info'><i class='fa fa-check'>Approve</i></a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    } ?>
                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>Add Item</a>
        </div>
    <?php
    } elseif ($do == 'Add') { ?>
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
                <?php } elseif ($do == 'Insert') {

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo "<h1 class='text-center'>Insert Item</h1>";
                    echo '<div class="container">';

                    $name = $_POST['name'];
                    $desc = $_POST['description'];
                    $price = $_POST['price'];
                    $country = $_POST['country'];
                    $status = $_POST['status'];
                    $member = $_POST['member'];
                    $category = $_POST['category'];

                    $errorForm = array();

                    if (empty($name)) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">name Can Not Be be Empty!</div>';
                    }
                    if (empty($desc)) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">Description Can Not Be be Empty!</div>';
                    }
                    if (empty($price)) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">Price Can Not Be be Empty!</div>';
                    }
                    if (empty($country)) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">Country Name Can Not Be be Empty!</div>';
                    }
                    if ($status === 0) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">You Must Choose The <strong>Status</strong></div>';
                    }
                    if ($member === 0) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">You Must Choose The <strong>Member</strong></div>';
                    }
                    if ($category === 0) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">You Must Choose The <strong>Category</strong></div>';
                    }
                    foreach ($errorForm as $error) {
                        echo $error . "</br>";
                    }
                    if (empty($errorForm)) {
                        $stmt = $conn->prepare("INSERT INTO 
                    items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID) 
                    Values(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat_ID, :zmember_ID)");
                        $stmt->execute(array(
                            'zname' => $name,
                            'zdesc' => $desc,
                            'zprice' => $price,
                            'zcountry' => $country,
                            'zstatus' => $status,
                            'zcat_ID' => $category,
                            'zmember_ID' => $member,
                        ));
                        echo "<div class='container'>";
                        $Msg = '<div class="alert alert-success text-center"> ' . $stmt->rowCount() . '<storng> Field Inserted</storng></div>';
                        redirectHome($Msg, 'back');
                        echo "</div>";
                    }
                } else {
                    echo "<div class='container'>";
                    $Msg = "<div class='alert alert-danger'>Sorry!!! You Can Not Visit This Page.</div>";
                    redirectHome($Msg, 'back', 3);
                    echo "</div>";
                }
            } elseif ($do == 'Edit') {
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                $stmt = $conn->prepare("SELECT * FROM items WHERE Item_ID = ?;");
                $stmt->execute(array($itemid));
                $row = $stmt->fetch();

                if ($stmt->rowCount() > 0) { ?>
                    <h1 class="text-center">Edit Item</h1>
                    <div class="container">
                        <form class="edit-form" action="?do=Update" method="POST">
                            <div class="form-group mb-3">
                                <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" aria-describedby="emailHelp" autocomplete="off" value="<?php echo $row['Name']; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleInputPassword1" class="form-label">Description</label>
                                <input type="text" name="description" class="form-control" value="<?php echo $row['Description']; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleInputPassword1" class="form-label">Price</label>
                                <input type="text" name="price" class="form-control" value="<?php echo $row['Price']; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleInputPassword1" class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="<?php echo $row['Country_Made']; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleInputPassword1" class="form-label">Status</label>
                                <select class="form-control" name="status" id="">
                                    <option value="1" <?php if ($row['Status'] == 1) {
                                                            echo 'selected';
                                                        } ?>>New</option>
                                    <option value="2" <?php if ($row['Status'] == 2) {
                                                            echo 'selected';
                                                        } ?>>Like New</option>
                                    <option value="3" <?php if ($row['Status'] == 3) {
                                                            echo 'selected';
                                                        } ?>>Used</option>
                                    <option value="4" <?php if ($row['Status'] == 4) {
                                                            echo 'selected';
                                                        } ?>>Very Old</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleInputPassword1" class="form-label">Members</label>
                                <select class="form-control" name="member" id="">
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach ($users as $user) {
                                        echo "<option value='" . $user['UserID'] . "'";
                                        if ($row['Member_ID'] == $user['UserID']) {
                                            echo 'selected';
                                        }
                                        echo ">" . $user['Username'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleInputPassword1" class="form-label">Categories</label>
                                <select class="form-control" name="category" id="">
                                    <?php
                                    $stmt1 = $conn->prepare("SELECT * FROM categories");
                                    $stmt1->execute();
                                    $cats = $stmt1->fetchAll();
                                    foreach ($cats as $cat) {
                                        echo "<option value='" . $cat['ID'] . "'";
                                        if ($row['Cat_ID'] == $cat['ID']) {
                                            echo 'selected';
                                        }
                                        echo ">" . $cat['Name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <input type="submit" value="Save" class="btn btn-primary">
                            </div>
                        </form>
                        <?php
                        $stmt = $conn->prepare("SELECT
                        comments.*, users.Username AS User
                        FROM
                        comments
                        INNER JOIN
                        users
                        ON
                        users.UserID = comments.User_ID
                        WHERE Item_ID = ?;");
                        $stmt->execute(array($itemid));
                        $rows = $stmt->fetchAll();
                        if (!empty($rows)) {
                        ?>
                        <h1 class="text-center">Manage [<?php echo $row['Name']; ?>] Comments</h1>
                            <div class="table-responsive">
                                <table class="main-table text-center table table-dark table-hover border">
                                    <tr>
                                        <td>Comment</td>
                                        <td>Username</td>
                                        <td>Add Date</td>
                                        <td>Action</td>
                                    </tr>
                                    <?php foreach ($rows as $row) {
                                        echo "<tr>";
                                        echo "<td>" . $row['Comment'] . "</td>";
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
                            <?php }?>
                    </div>
        <?php
                }
            } elseif ($do == 'Update') {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo "<h1 class='text-center'>Update Item</h1>";
                    echo '<div class="container">';
                    $id = $_POST['itemid'];
                    $name = $_POST['name'];
                    $desc = $_POST['description'];
                    $price = $_POST['price'];
                    $country = $_POST['country'];
                    $status = $_POST['status'];
                    $category = $_POST['category'];
                    $member = $_POST['member'];

                    $errorForm = array();

                    if (empty($name)) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">name Can Not Be be Empty!</div>';
                    }
                    if (empty($desc)) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">Description Can Not Be be Empty!</div>';
                    }
                    if (empty($price)) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">Price Can Not Be be Empty!</div>';
                    }
                    if (empty($country)) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">Country Name Can Not Be be Empty!</div>';
                    }
                    if ($status === 0) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">You Must Choose The <strong>Status</strong></div>';
                    }
                    if ($member === 0) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">You Must Choose The <strong>Member</strong></div>';
                    }
                    if ($category === 0) {
                        $errorForm[] = '<div class = "alert alert-danger text-center">You Must Choose The <strong>Category</strong></div>';
                    }
                    foreach ($errorForm as $error) {
                        echo $error . "</br>";
                    }
                    if (empty($errorForm)) {
                        $stmt = $conn->prepare("UPDATE 
                                                    items 
                                                SET 
                                                    Name = ?,
                                                    Description = ?,
                                                    Price = ?,
                                                    Country_Made = ?,
                                                    Status = ?,
                                                    Cat_ID = ?,
                                                    Member_ID = ?
                                                WHERE 
                                                    Item_ID = ?;");
                        $stmt->execute(array($name, $desc, $price, $country, $status, $category, $member, $id));
                        $Msg = '<div class = "alert alert-success text-center">' . $stmt->rowCount() . '<storng>Field Updated</storng></div>';
                        redirectHome($Msg, 'back');
                    }
                } else {
                    echo "<div class='container'>";
                    $Msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
                    redirectHome($Msg);
                    echo "</div>";
                }
                echo "</div>";
            } elseif ($do == 'Delete') {
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                $check = checkItem('Item_ID', 'items', $itemid);

                if ($check > 0) {
                    $stmt = $conn->prepare("DELETE FROM items WHERE Item_ID = :zid");
                    $stmt->bindParam(":zid", $itemid);
                    $stmt->execute();
                    echo "<div class='container'>";
                    $Msg = '<div class="alert alert-danger text-center"><storng>Field Deleted</storng></div>';
                    redirectHome($Msg);
                    echo "</div>";
                } else {
                    $Msg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
                }
            } elseif ($do == 'Approve') {
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                $check = checkItem('Item_ID', 'items', $itemid);

                if ($check > 0) {
                    $stmt = $conn->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?;");
                    $stmt->execute(array($itemid));
                    echo "<div class='container'>";
                    $Msg = '<div class="alert alert-danger text-center"><storng>Field Activated</storng></div>';
                    redirectHome($Msg);
                    echo "</div>";
                }
            }
        } else {
            header('location: index.php');
            exit();
        }

        ob_end_flush();
