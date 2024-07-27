<?php

$noNavbar = '';
$pageTitle = 'Login';
session_start();
if (isset($_SESSION['username'])) {
    header("location: dashboard.php");
}
include "init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $hashedPass = sha1($_POST['password']);

    $stmt = $conn->prepare("SELECT 
                                    UserID, Username, Password 
                            From    users 
                            WHERE   Username = ? 
                            AND     Password = ?");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['ID'] = $row['UserID'];
        header('location: dashboard.php');
        exit();
    }
}
?>
<div class="container">
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h1 class="text-center">Admin Login</h1>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php

include $tpl . 'footer.php';

?>