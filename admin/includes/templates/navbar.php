<nav class="navbar navbar-inverse bg-light fixed-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-brand">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#app-nav" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="nav-link navbar-brand" href="index.php"><?php echo lang("ADMIN") ?></a>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php"><?php echo lang("CATEGORIES") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="items.php"><?php echo lang("ITEMS") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="members.php"><?php echo lang("MEMBERS") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="comments.php"><?php echo lang("COMMENTS") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="statistics.php"><?php echo lang("STATISTICS") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logs.php"><?php echo lang("LOGS") ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Visit Shop</a>
                    </li>
                </ul>
            </div>
    </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-target="#app-nav" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class=""><?php echo "User: " . $_SESSION["username"]; ?></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

            <div class="offcanvas-body" id="app-nav">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>">Edit Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Setting</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
</nav>
<br>
<br>
<br>
<br>