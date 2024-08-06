<?php
ob_start();
session_start();
$pageTitle = 'Homepage';
include "init.php";
if (isset($_SESSION['user'])) {
    $getUser = $conn->prepare("SELECT * FROM users WHERE Username = ?;");
    $getUser->execute(array($_SESSION['user']));
    $row = $getUser->fetch();
?>

    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

    <div class="container">
        <h1 class="text-center">My Profile</h1>
        <br>
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                Name: <?php echo $row['Username'] . "<br>" ?>
                Email: <?php echo $row['Email'] . "<br>" ?>
                Full Name: <?php echo $row['FullName'] . "<br>" ?>
                Date: <?php echo $row['Date'] . "<br>" ?>
                Favourite Category: <?php echo $row['UserID'] . "<br>" ?>
                <a class="btn btn-default">Edit Infromation</a>
            </div>

        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">My Ads</div>
            <?php
$items = getAll('items');
?>
<div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($items as $item) { ?>
            <div class="col">
                <div class="card">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <a href="items.php?itemid=<?php echo $item['Item_ID']; ?>">
                            <h5 class="card-title"><?php echo $item['Name']; ?></h5>
                        </a>
                        <p class="card-text"><?php echo $item['Description']; ?></p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><?php echo 'Price: $' . $item['Price']; ?></li>
                        <li class="list-group-item"><?php echo 'Country_Made: ' . $item['Country_Made']; ?></li>
                        <li class="list-group-item"><?php echo 'Rating: ' . $item['Rating']; ?></li>
                        <li class="list-group-item"><?php echo 'Add_Date: ' . $item['Add_Date']; ?></li>
                    </ul>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                <?php
                $stmt = $conn->prepare("SELECT comment FROM comments WHERE User_ID = ?;");
                $stmt->execute(array($row['UserID']));
                $rows = $stmt->fetchAll();

                if (!empty($rows)) {
                    foreach ($rows as $row) {
                        echo "<p>" . $row['comment'] . "</p>";
                    }
                } else {
                    echo '<div class="alert alert-secondary">There\'s Is No Comments, Create <a href="newad.php">New Ad</a></div>';
                }

                ?>
            </div>
        </div>
    </div>
<?php } else {
    header('location: index.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
