<?php
ob_start();
session_start();
$pageTitle = 'Create New Ad';
include "init.php";
if (isset($_SESSION['user'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formErrors = array();

        $name       = htmlspecialchars($_POST['name']);
        $desc       = htmlspecialchars($_POST['description']);
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT); 
        $country    = htmlspecialchars($_POST['country']);
        $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);


        if (strlen($name) < 4) {
            $formErrors[] = 'Title Item Must Be At Least 4 Characters!';
        }
        if (strlen($desc) < 10) {
            $formErrors[] = 'Description Item Must Be At Least 10 Characters!';
        }
        if (empty($price)) {
            $formErrors[] = 'Price Item Must Not Be Empty!';
        }
        if (empty($country)) {
            $formErrors[] = 'Country Item Must Be At Least 2 Characters!';
        }
        if (empty($status)) {
            $formErrors[] = 'Price Item Must Not Be Status!';
        }
        if (empty($category)) {
            $formErrors[] = 'Category Item Must Not Be Category!';
        }
        foreach ($formErrors as $error) {
            echo $error . "</br>";
        }
        if (empty($formErrors)) {
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
                'zmember_ID' => $_SESSION['uid'],
            ));
            echo "<div class='container'>";
            $Msg = '<div class="alert alert-success text-center"> ' . $stmt->rowCount() . '<storng> Field Inserted</storng></div>';
            echo redirectHome($Msg);
            echo "</div>";
        }
    }
?>

    <div class="container">
        <h1 class="text-center">Create New Ad</h1>
        <br>
        <div class="panel panel-primary">
            <div class="panel-body">
                <form class="edit-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
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
                        <label for="exampleInputPassword1" class="form-label">Categories</label>
                        <select class="form-control" name="category" id="">
                            <option value="0">...</option>
                            <?php
                            $cats = getAll('categories');
                            foreach ($cats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <?php
            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
            }
            ?>
        </div>
    </div>
<?php
} else {
    header('location: index.php');
    exit();
} ?>
<?php include $tpl . 'footer.php';
ob_end_flush();
