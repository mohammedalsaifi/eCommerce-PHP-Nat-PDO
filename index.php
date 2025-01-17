<?php
session_start();
$pageTitle = 'Homepage';
include "init.php";

?>
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
<?php
include $tpl . 'footer.php';
?>