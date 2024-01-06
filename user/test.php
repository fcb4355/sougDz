<?php
$pageTitle = "Test";
include "init.php";
?>

<?php

// Start The Row 
$start = 0;

// Num of The Rows Selected From DataBase
$row_pre_page = 2;

// Get All Categories From Data Base To Calc The Count Of The Cats
$stm2 = $conn->prepare("SELECT * FROM category");
$stm2->execute();
$rows = $stm2->fetchAll();
$count = count($rows);


// Number Of pages 
$pages = ceil($count / $row_pre_page);



if (isset($_GET["page"])) {

    $page = $_GET["page"] - 1;

    $start = $page * $row_pre_page;
}




// Get All Category From DataBase With Limit && Start Get  
$stm = $conn->prepare("SELECT * FROM category limit $start , $row_pre_page");
$stm->execute();
$cats = $stm->fetchAll();
?>


<div class="container">

    <!-- Table -->
    <table class="table shadow-sm text-center">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
            </tr>
        </thead>
        <tbody>

            <?php
            foreach ($cats as $cat) {
            ?>
                <tr>
                    <th scope="row"> <?php echo $cat["cat_ID"] ?> </th>
                    <td> <?php echo $cat["cat_name"] ?> </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>


    <!-- Page info text -->
    <div class="mb-2">
        Show <?php echo isset($_GET['page']) ? $_GET['page'] : "1" ?> of <?= $pages ?>
    </div>


    <!-- Paginations -->
    <nav aria-label="...">
        <ul class="pagination">

            <!-- First Page -->
            <li class="page-item">
                <a href="?page=1" class="page-link">First</a>
            </li>


            <!-- Previous Page -->
            <?php
            if (isset($_GET['page']) && $_GET['page'] > 1) {
            ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $_GET['page'] - 1 ?>">Previous</a>
                </li>
            <?php
            } else {
            ?>
                <li class="page-item disabled">
                    <a class="page-link">Previous</a>
                </li>
            <?php
            }
            ?>


            <!-- Number Of Page -->
            <?php
            for ($i = 1; $i <= $pages; $i++) {
            ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php
            }
            ?>


            <!-- Next Page -->
            <?php
            if (isset($_GET['page']) && $_GET['page'] < $pages) {
            ?>
                <li class="page-item <?= $start == ($pages + 1) ? "disabled" : "" ?>">
                    <a class="page-link" href="?page=<?= $_GET['page'] + 1 ?>">Next</a>
                </li>
            <?php
            } else {
            ?>
                <li class="page-item disabled">
                    <a class="page-link">Next</a>
                </li>
            <?php
            }
            ?>

            <!-- Last Page -->
            <li class="page-item">
                <a class="page-link" href="?page=<?= $pages ?>">Last</a>
            </li>

        </ul>
    </nav>





</div>

<?php
$url = "http://localhost/market/user/index.php";

$arr = parse_url($url);

$path = $arr["path"];

$exp = explode("/", $path);

$referer_url = end($exp);

echo $referer_url;

?>

<?php include "templates/footer.php"; ?>