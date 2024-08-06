<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php getTitle() ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>backend.css">
</head>

<body>
    <nav class="navbar navbar-inverse bg-light">

        <div class="container">
            <?php if (isset($_SESSION['user'])) { ?>
                <div class="upper-bar">
                    <?php echo 'Welcom ' . $_SESSION['user'] . '<a href="profile.php"><br> - my profile</a>' . "<br>"
                        . '<a href="newad.php"> - newad </a>' . "<br>"
                        . '<a href="logout.php"> - logout </a>';
                    ?>
                </div>
            <?php } else {
                echo "<a href='login.php'>Registration</a>";
            } ?>

            <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-brand">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#app-nav" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                    <a class="nav-link navbar-brand" href="index.php">Home Page</a>
                </div>
                <div class="collapse navbar-collapse d-flex" id="app-nav">
                    <ul class="navbar-nav navbar-right">
                        <?php $cats = getAllForm("*", "categories", "where parent = 0", "", "ID", "ASC");
                        foreach ($cats as $cat) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="categories.php?pageid=
                                <?php echo $cat['ID']; ?>">
                                    <?php echo $cat['Name'] ?>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </nav>
        </div>
    </nav>
    <br>