<?php
$pageTitle = "الرئيسية";
include "init.php";
?>

<div class="container" style="margin-bottom: 70px !important">

    <!-- :: Start Categories  -->
    <h3 class="content-title mb-4" data-aos="fade-up"> الفئــات </h3>

    <?php
    $stm = $conn->prepare("SELECT * FROM category WHERE visiblity = 1 ORDER BY time DESC");
    $stm->execute();
    $cats = $stm->fetchAll();

    if (count($cats) == 0) {
    ?>
        <div class="text-center alert alert-danger text-danger border"> لا يوجد فئات </div>
    <?php
    } else {
    ?>
        <div class="categories mt-4">
            <?php
            foreach ($cats as $cat) {
            ?>
                <a href="category.php?catID=<?= $cat["cat_ID"] ?>" class="cat shadow-sm" data-aos="fade-up">
                    <div class="imgThumbnail">
                        <img src="../category_images/<?= $cat["cat_img"] ?>" alt="category img">
                    </div>
                    <div class="cat-name"><?php echo $cat["cat_name"] ?></div>
                </a>
            <?php
            }
            ?>
        </div>
        <!-- :: End Categories  -->

    <?php
    }

    // :: Start Discount items 
    $stm = $conn->prepare("SELECT items.* , 
                                            category.visiblity as cat_visible
                                        FROM 
                                            items
                                        INNER JOIN 
                                            category 
                                        ON 
                                            items.cat_ID = category.cat_ID
                                        WHERE
                                            category.visiblity = 1
                                        AND
                                            status = 1
                                        AND
                                            descount != 0");
    $stm->execute();
    $items_descount = $stm->fetchAll();

    $count = count($items_descount);

    if ($count > 0) {
    ?>
        <h3 class="content-title" data-aos="fade-up">
            تخفيضات
            <span>(سارع قبل نفاذ الكمية)</span>
        </h3>
        <div class="discount-box mt-4 mb-4">
            <?php
            // Start Loop
            foreach ($items_descount as $item) {
            ?>
                <a href="product.php?pr-id=<?= $item["item_ID"] ?>" class="discount shadow-sm" data-aos="fade-up">
                    <div class="itemImg">
                        <?php
                        $itemID = $item["item_ID"];
                        // Get All Images With This item ID
                        $stm2 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ? LIMIT 1");
                        $stm2->execute(array($itemID));
                        $img = $stm2->fetch();
                        ?>
                        <img src="../item_images/<?= $img["img"] ?>">
                    </div>
                    <div class="disAbout">
                        <div class="name"><?= $item["item_Name"] ?></div>
                        <div class="stars">
                            <?php
                            // Calc The Rating System Stars
                            $stm = $conn->prepare("SELECT * FROM comments WHERE item_id = ?");
                            $stm->execute(array($item["item_ID"]));

                            $cmmnts = $stm->fetchAll();

                            if (count($cmmnts) > 0) {
                                $num = 0;
                                foreach ($cmmnts as $cmmnt) {
                                    $num = $cmmnt["rating"] + $num;
                                }
                                $numStars = round($num / count($cmmnts));

                                for ($i = 0; $i < $numStars; $i++) {
                            ?>
                                    <i class="fa-solid fa-star" style="color:gold;"></i>
                                <?php
                                }

                                for ($i = 0; $i < 5 - $numStars; $i++) {
                                ?>
                                    <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <?php
                                }
                            } else {
                                ?>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <?php

                            }

                            ?>
                        </div>
                        <div class="prices">
                            <div class="old">DA <?= $item["price"] ?></div>
                            <div class="new">DA <?= $item["new_price"] ?></div>
                        </div>
                        <div class="persent">%<?= $item["descount"] ?></div>
                    </div>
                </a>
            <?php
            } // End Loop
            ?>
        </div>
    <?php
    }
    ?>
    <!-- :: End Discount items -->

    <!-- :: Start Latest items -->
    <?php
    $stm = $conn->prepare("SELECT * FROM items WHERE status = 1");
    $stm->execute();
    $items = $stm->fetchAll();
    $count2 = count($items);

    if ($count2 > 0) {
    ?>
        <h3 class="content-title" data-aos="fade-down">
            احدث المنتجات
        </h3>

        <div class="latest-items mt-4 mb-4">
            <?php
            $stm = $conn->prepare("SELECT items.* , 
                                                    category.visiblity as cat_visible
                                                FROM 
                                                    items
                                                INNER JOIN 
                                                    category 
                                                ON 
                                                    items.cat_ID = category.cat_ID
                                                WHERE
                                                    category.visiblity = 1
                                                AND
                                                    status = 1
                                                ORDER BY time DESC LIMIT 12 ");
            $stm->execute();
            $items = $stm->fetchAll();

            $count = count($items_descount);

            foreach ($items as $item) {
            ?>
                <a href="product.php?pr-id=<?= $item["item_ID"] ?>" class="item shadow-sm" data-aos="fade-down">
                    <div class="itemImg">
                        <?php
                        $itemID = $item["item_ID"];
                        // Get All Images With This item ID
                        $stm2 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ? LIMIT 1");
                        $stm2->execute(array($itemID));
                        $img = $stm2->fetch();
                        ?>
                        <img src="../item_images/<?= $img["img"] ?>">
                    </div>
                    <div class="itemAbout">
                        <div class="name"><?= $item["item_Name"] ?></div>
                        <div class="stars">
                            <?php
                            // Calc The Rating System Stars
                            $stm = $conn->prepare("SELECT * FROM comments WHERE item_id = ?");
                            $stm->execute(array($item["item_ID"]));

                            $cmmnts = $stm->fetchAll();

                            if (count($cmmnts) > 0) {
                                $num = 0;
                                foreach ($cmmnts as $cmmnt) {
                                    $num = $cmmnt["rating"] + $num;
                                }
                                $numStars = round($num / count($cmmnts));

                                for ($i = 0; $i < $numStars; $i++) {
                            ?>
                                    <i class="fa-solid fa-star" style="color:gold;"></i>
                                <?php
                                }

                                for ($i = 0; $i < 5 - $numStars; $i++) {
                                ?>
                                    <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <?php
                                }
                            } else {
                                ?>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <?php

                            }

                            ?>
                        </div>
                        <div class="prices">

                            <?php
                            if ($item["descount"] == 0) {
                            ?>
                                <div class="new">DA <?= $item["price"] ?></div>
                            <?php
                            } else {
                            ?>
                                <div class="old">DA <?= $item["price"] ?></div>
                                <div class="new">DA <?= $item["new_price"] ?></div>
                                <div class="persent">%<?= $item["descount"] ?></div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </a>
            <?php
            }
            ?>
        </div>
    <?php
    } else {

        // No Itmes

    }
    ?>
    <!-- :: End Latest items -->
</div>


<?php include "templates/footer.php"; ?>


<?php

if (isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"] == "http://localhost/market/user/login.php") {
?>
    <script>
        swal({
            icon: "success",
            title: "<?= $_SESSION["fullName"] ?> مرحبا",
        });
    </script>
<?php
}
?>

<script>
    AOS.init();
</script>