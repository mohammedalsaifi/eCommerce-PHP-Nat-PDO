<?php

ob_start();

session_start();
$pageTitle = 'Categories';

if (isset($_SESSION['username'])) {
    include 'init.php';

    $do = isset($_GET['do'])  ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $sort = '';
        $sort_array = array('ASC', 'DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }
        $stmt2 = $conn->prepare("SELECT * FROM categories ORDER BY Visibility $sort");
        $stmt2->execute();
        $cats = $stmt2->fetchAll(); ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container">
            Ordering:
            <a href="?sort=ASC"><button type="button" class="btn btn-outline-primary">ASC</button></a>
            <a href="?sort=DESC"><button type="button" class="btn btn-outline-primary">DESC</button></a>
            <br>
            <br>
            <?php
            foreach ($cats as $cat) { ?>
                <div class="card-group">

                    <div class="card">
                        <div class="card-body">
                            <a href='categories.php?do=Edit&catid=<?php echo $cat['ID'] ?>' class='btn btn-secondary'><i class="fa-solid fa-trash"> Edit</i></a>
                            <a href='categories.php?do=Delete&catid=<?php echo $cat['ID'] ?>' class='btn btn-danger'><i class="fa-solid fa-trash"> Delete</i></a>
                            <h5 class="card-title"><?php echo "Name Is: " . $cat['Name']; ?></h5>
                            <p class="card-text "><?php echo "Description Is: " . $cat['Description']; ?></p>
                            <p class="card-text"><?php echo "Ordering Is: " . $cat['Ordering']; ?></p>
                            <div class="cart-footer">
                                <p class="card-text"><small><?php echo "Visibility Is: " . $cat['Visibility']; ?></small></p>
                                <p class="card-text"><small><?php echo "Allow_Comment Is: " . $cat['Allow_Comment']; ?></p>
                                <p class="card-text"><small><?php echo "Allow_Ads Is: " . $cat['Allow_Ads']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php   } ?>
            <br>
            <a href="?do=Add"><button class="btn btn-primary"><i class="fa fa-plus"> Add Category</i></button></a>
            <br>
            <br>
        </div>
    <?php
    } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add Category</h1>
        <div class="container">
            <form class="edit-form" action="?do=Insert" method="POST">
                <div class="form-group mb-3">
                    <label for="exampleInputEmail1" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" aria-describedby="emailHelp" autocomplete="off" required="required">
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Description</label>
                    <input type="text" name="description" class="form-control">
                </div>
                <div class=" form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Ordering</label>
                    <input type="text" name="ordering" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Visible</label>
                    <div>
                        <input id="vis-yes" type="radio" name="visibility" value="0">
                        <label for="vis-yes">Yes</label>
                    </div>
                    <div>
                        <input id="vis-no" type="radio" name="visibility" value="1">
                        <label for="vis-no">No</label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Allow Commenting</label>
                    <div>
                        <input id="com-yes" type="radio" name="commenting" value="0">
                        <label for="com-yes">Yes</label>
                    </div>
                    <div>
                        <input id="com-no" type="radio" name="commenting" value="1">
                        <label for="com-no">No</label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="exampleInputPassword1" class="form-label">Allow Ads</label>
                    <div>
                        <input id="ads-yes" type="radio" name="ads" value="0">
                        <label for="ads-yes">Yes</label>
                    </div>
                    <div>
                        <input id="ads-no" type="radio" name="ads" value="1">
                        <label for="ads-no">No</label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <input type="submit" value="Save" class="btn btn-primary">
                </div>
            </form>
        </div>
        <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Category</h1>";
            echo '<div class="container">';

            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $visibl = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];

            $check = checkItem('Name', "categories", $name);
            if ($check > 0) {
                $Msg = '<div class="alert alert-danger text-center"><storng>Sorry! Category Name Is Taken</storng></div>';
                redirectHome($Msg, 'back');
            } else {
                if (empty($errorForm)) {
                    $stmt = $conn->prepare("INSERT INTO categories (Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads) 
                        Values(:zname, :zdesc, :zorder, :zvisibel, :zcomm, :zads);");
                    $stmt->execute(array(
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zorder' => $order,
                        'zvisibel' => $visibl,
                        'zcomm' => $comment,
                        'zads' => $ads,
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
            redirectHome($Msg, 'back');
            echo "</div>";
        }
    } elseif ($do == 'Edit') {

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        $stmt = $conn->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1;");
        $stmt->execute(array($catid));
        $cat = $stmt->fetch();

        if ($stmt->rowCount() > 0) { ?>
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="edit-form" action="?do=Update" method="POST">
                    <div class="form-group mb-3">
                        <label for="exampleInputEmail1" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp" required="required" value="<?php echo $cat['Name'] ?>">
                        <input type="hidden" name="catid" value="<?php echo $catid ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1" class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" value="<?php echo $cat['Description'] ?>">
                    </div>
                    <div class=" form-group mb-3">
                        <label for="exampleInputPassword1" class="form-label">Ordering</label>
                        <input type="text" name="ordering" class="form-control" value="<?php echo $cat['Ordering'] ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1" class="form-label">Visible</label>
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) {
                                                                                                echo 'checked';
                                                                                            } ?> />
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) {
                                                                                            echo 'checked';
                                                                                        } ?>>
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1" class="form-label">Allow Commenting</label>
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) {
                                                                                                echo 'checked';
                                                                                            } ?>>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) {
                                                                                            echo 'checked';
                                                                                        } ?>>
                            <label for="com-no">No</label>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1" class="form-label">Allow Ads</label>
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) {
                                                                                        echo 'checked';
                                                                                    } ?>>
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) {
                                                                                        echo 'checked';
                                                                                    } ?>>
                            <label for="ads-no">No</label>
                        </div>
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
            redirectHome($Msg, 'back');
            echo "</div>";
        }
    } elseif ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Delete Category</h1>";
            echo '<div class="container">';
            $id          =  $_POST['catid'];
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $ordering        = $_POST['ordering'];
            $visibility        = $_POST['visibility'];
            $commenting        = $_POST['commenting'];
            $ads        = $_POST['ads'];
            $stmt       = $conn->prepare("UPDATE 
                                                categories 
                                        SET 
                                                Name = ?,
                                                Description = ?, 
                                                Ordering = ?, 
                                                Visibility = ? , 
                                                Allow_Comment = ?, 
                                                Allow_Ads = ?
                                        WHERE 
                                                ID = ?;");
            $stmt->execute(array($name, $description, $ordering, $visibility, $commenting, $ads, $id));
            $Msg = '<div class = "alert alert-success text-center">' . $stmt->rowCount() . '<storng>Field Updated</storng></div>';
            redirectHome($Msg, 'back');
        } else {
            echo "<div class='container'>";
            $Msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
            redirectHome($Msg);
            echo "</div>";
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        $check = checkItem('ID', 'categories', $catid);

        if ($check > 0) {
            $stmt = $conn->prepare("DELETE FROM categories WHERE ID = :zuser");
            $stmt->bindParam(":zuser", $catid);
            $stmt->execute();
            echo "<div class='container'>";
            $Msg = '<div class="alert alert-danger text-center"><storng>Field Deleted</storng></div>';
            redirectHome($Msg);
            echo "</div>";
        } else {
            $Msg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
        }
    }
}

ob_end_flush();
