<?php

$pageTitle = 'Members';
session_start();
if (isset($_SESSION['username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID ASC;");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        if (!empty($rows)) {
?>

            <h1 class="text-center">Manage Member</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-dark table-hover border">
                        <tr>
                            <td>#ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Register Date</td>
                            <td>Action</td>
                        </tr>
                        <?php foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                        <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='fa fa-edit btn btn-secondary'>Edit</a>
                        <a href='members.php?do=Delete&userid=" . $row['UserID'] . "'  class='fa fa-close btn btn-danger'>Delete</a>";
                            if ($row['RegStatus'] == 0) {
                                echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "'  class='btn btn-info'>Activate</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        } ?>
                    </table>
                </div>
                <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>Add Member</a>
            </div>
        <?php }
    } else if ($do == 'Add') { ?>
        <h1 class="text-center">Add Member</h1>
        <div class="container">
            <form class="edit-form" action="?do=Insert" method="POST">
                <div class="form-group mb-3">
                    <label for="exampleInputEmail1" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" aria-describedby="emailHelp" autocomplete="off" required="required">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" autocomplete="off" required="required">
                </div>
                <div class=" form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required="required">
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Full Name</label>
                    <input type="text" name="full" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <input type="submit" value="Save" class="btn btn-primary">
                </div>
            </form>
        </div>

        <?php } else if ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Member</h1>";
            echo '<div class="container">';

            $username = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $full = $_POST['full'];

            $hashedPass = sha1($pass);

            $errorForm = array();

            if (empty($username)) {
                $errorForm[] = '<div class = "alert alert-danger text-center">Username Can Not Be be Empty!</div>';
            }
            if (empty($email)) {
                $errorForm[] = '<div class = "alert alert-danger text-center">Email Can Not Be be Empty!</div>';
            }
            if (empty($pass)) {
                $errorForm[] = '<div class = " alert alert-danger text-center">Email Can Not Be be Empty!</div>';
            }
            if (empty($full)) {
                $errorForm[] = '<div class = " alert alert-danger text-center">Full Name Can Not Be be Empty!</div>';
            }
            foreach ($errorForm as $error) {
                echo $error . "</br>";
            }
            $check = checkItem('Username', "users", $username);
            if ($check > 0) {
                redirectHome("Sorry! Username Is Taken");
            } else {
                if (empty($errorForm)) {
                    $stmt = $conn->prepare("INSERT INTO users (Username, Password, Email, FullName, RegStatus, Date) Values(:zunam, :zpass, :zemail, :zname, 1, now());");
                    $stmt->execute(array(
                        'zunam' => $username,
                        'zpass' => $hashedPass,
                        'zemail' => $email,
                        'zname' => $full,
                    ));
                    echo "<div class='container'>";
                    $Msg = '<div class="alert alert-success text-center"> ' . $stmt->rowCount() . '<storng> Field Inserted</storng></div>';
                    redirectHome($Msg, 'back');
                    echo "</div>";
                }
            }
        } else {
            echo "<div class='container'>";
            $Msg = "<div class='alert alert-danger'>Sorry!!! You Can Not Visit This Page.</div>";
            redirectHome($Msg, 'back', 3);
            echo "</div>";
        }
    } else if ($do == 'Edit') {

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1;");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();

        if ($stmt->rowCount() > 0) { ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="edit-form" action="?do=Update" method="POST">
                    <div class="form-group mb-3">
                        <label for="exampleInputEmail1" class="form-label">Username</label>
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <input type="text" class="form-control" name="username" value="<?php echo $row['Username'] ?>" aria-describedby="emailHelp" autocomplete="off" required="required">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
                        <input type="password" name="newpassword" class="form-control" autocomplete="new-password">
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" required="required">
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1" class="form-label">Full Name</label>
                        <input type="text" name="full" class="form-control" value="<?php echo $row['FullName'] ?>" required="required">
                    </div>
                    <div class="form-group mb-3">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                </form>
            </div>
<?php } else {
            echo "<div class='container'>";
            $Msg = "<div class='alert alert-danger'>There Is No Such ID</div>";
            redirectHome($Msg);
            echo "</div>";
        }
    } else if ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Member</h1>";
            echo '<div class="container">';
            $id = $_POST['userid'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $full = $_POST['full'];
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            $stmt = $conn->prepare("UPDATE users SET Username = ?, Password = ?, Email = ?, FullName = ? WHERE UserID = ?;");
            $errorForm = array();

            if (empty($username)) {
                $errorForm[] = '<div class="alert alert-danger text-center">Username Can Not Be be Empty!</div>';
            }
            if (empty($email)) {
                $errorForm[] = '<div class="alert alert-danger text-center">Email Can Not Be be Empty!</div>';
            }
            if (empty($full)) {
                $errorForm[] = '<div class="alert alert-danger text-center">Full Name Can Not Be be Empty!</div>';
            }
            foreach ($errorForm as $error) {
                echo $error . "</br>";
            }
            if (empty($errorForm)) {
                $stmt2 = $conn->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?;");
                $stmt2->execute(array($username, $id));
                if ($stmt2->rowCount() > 0) {
                    echo 'Sory This User Is Taken';
                } else {
                    $stmt->execute(array($username, $pass, $email, $full, $id));
                    $Msg = '<div class = "alert alert-success text-center">' . $stmt->rowCount() . '<storng>Field Updated</storng></div>';
                    redirectHome($Msg, 'back');
                }
            }
        } else {
            echo "<div class='container'>";
            $Msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
            redirectHome($Msg);
            echo "</div>";
        }
        echo "</div>";
    } else if ($do == 'Delete') {

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check = checkItem('userid', 'users', $userid);

        if ($check > 0) {
            $stmt = $conn->prepare("DELETE FROM users WHERE UserID = :zuser");
            $stmt->bindParam(":zuser", $userid);
            $stmt->execute();
            echo "<div class='container'>";
            $Msg = '<div class="alert alert-danger text-center"><storng>Field Deleted</storng></div>';
            redirectHome($Msg);
            echo "</div>";
        } else {
            $Msg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
        }
    } else if ($do == 'Activate') {

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check = checkItem('userid', 'users', $userid);

        if ($check > 0) {
            $stmt = $conn->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?;");
            $stmt->execute(array($userid));
            echo "<div class='container'>";
            $Msg = '<div class="alert alert-success text-center"><storng>Field Activated</storng></div>';
            redirectHome($Msg);
            echo "</div>";
        }
        include $tpl . 'footer.php';
    } else {
        header("location: index.php");
    }
}
