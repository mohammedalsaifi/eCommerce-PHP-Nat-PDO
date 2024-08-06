<?php

ob_start();
session_start();
if (isset($_SESSION['user'])) {
    header('location: index.php');
    exit();
}

include 'admin/connect.php';
$tpl = 'includes/templates/';
$lang = 'includes/languages/';
$func = 'includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';

include $func . 'functions.php';
include $lang . 'english.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['login'])) {

        $username = $_POST['user'];
        $hashedPass = sha1($_POST['pass']);

        $stmt = $conn->prepare("SELECT 
                                    UserID, Username, Password 
                            From    users 
                            WHERE   Username = ? 
                            AND     Password = ?");
        $stmt->execute(array($username, $hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            $_SESSION['user'] = $username;
            $_SESSION['uid'] = $row['UserID'];
            header('location: index.php');
            exit();
        }
    } else {
        $username = $_POST['user'];
        $email = $_POST['email'];
        $password = sha1($_POST['password']);
        $confPass = sha1($_POST['confPass']);

        $formErrors = array();
        if (isset($_POST['user'])) {
            $filteredUser = htmlspecialchars($_POST['user']);
            if (strlen($filteredUser) < 4) {
                $formErrors[] = 'Username Must Be Larger Than 4 Characters!';
            }
        }
        if (isset($_POST['password']) && isset($_POST['confPass'])) {

            if (empty($_POST['password'])) {
                $formErrors[] = 'Password Can\'t Be Empty!';
            }

            if (sha1($_POST['password']) !== sha1($_POST['confPass'])) {
                $formErrors[] = 'Password Does Not Match!';
            }
        }
        if (isset($_POST['email'])) {
            $filteredEmail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            if ($filteredEmail != true) {
                $formErrors[] = 'This Email Is Not Valid!';
            }
        }
        if (empty($errorForm)) {
            $check = checkItem('Username', "users", $username);
            if ($check > 0) {
                $formErrors[] = 'Username Is Tacken!';
            } else {
                $stmt = $conn->prepare("INSERT INTO
                                        users (Username, Password, Email, RegStatus, Date)
                                        Values (:zunam, :zpass, :zemail, 1, now());");

                $stmt->execute(array(
                    'zunam' => $username,
                    'zpass' => $password,
                    'zemail' => $email,
                ));

                $succesMsg = 'Congrates You Are Now Registered User';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, 
                         initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1 class="heading">Rackan Shop</h1>
    </header>

    <!-- container div -->
    <div class="container">

        <!-- upper button section to select
             the login or signup form -->
        <div class="slider"></div>
        <div class="btn">
            <button class="login">Login</button>
            <button class="signup">Signup</button>
        </div>

        <!-- Form section that contains the
             login and the signup form -->
        <div class="form-section">

            <!-- login form -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="login-box">
                    <input type="text" name="user" class="email ele" placeholder="Username">
                    <input type="password" name="pass" class="password ele" placeholder="password">
                    <button class="clkbtn" name="login" type="submit">Login</button>
                </div>
            </form>

            <!-- signup form -->
            <form action="" method="post">
                <div class="signup-box">
                    <input type="text" name="user" class="name ele" placeholder="Enter your name">
                    <input type="email" name="email" class="email ele" placeholder="Email">
                    <input type="password" name="password" class="password ele" placeholder="password">
                    <input type="password" name="confPass" class="password ele" placeholder="Confirm password">
                    <button class="clkbtn" name="signup">Signup</button>
            </form>
        </div>
    </div>
    </div>
    <div class="error text-center">
        <?php
        if (!empty($formErrors)) {
            foreach ($formErrors as $error) {
                echo $error . '<br>';
            }
        }
        if (isset($succesMsg)) {
            echo '<div>' . $succesMsg . '</div>';
            header('location: index.php');
            exit();
        }
        ?>
    </div>
    <script src="main.js"></script>
</body>

</html>
<?php
ob_end_flush();
?>