<?php
ob_start();
session_start();
$pageTitle = 'Categories';
include "init.php";
?>

<div class="container">
    <h1 class="text-center">
        Show Category
    </h1>
    <br>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        foreach (getItems('Cat_ID', $_GET['pageid']) as $item) {
        ?>
            <div class="row col-md-4">
                <div class="card" style="width: 18rem;">
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

<?php
include $tpl . 'footer.php';
ob_end_flush();
