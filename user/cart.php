<?php
$pageTitle = "السلة";
include "init.php";
?>


<?php

$userID = "";
if (isset($_SESSION["id"])) {
    $userID = $_SESSION["id"];
} else {
    header("Location: login.php");
    exit();
}

if (isset($_GET["itemID"])) {

    $itemID =  $_GET["itemID"];

    $stmD = $conn->prepare("DELETE FROM basket WHERE item_id = ? AND user_id = ? AND isOrdering = 0");

    $stmD->execute(array($itemID, $userID));

    $_SESSION["mssg"] = "تم حذف المنتج من السلة";

    header("Location:cart.php");
    exit();
}


// Get All Product in The Basket With user id.
$stm = $conn->prepare(" SELECT basket.* , 
                            items.item_Name 
                        AS 
                            pr_name,
                            items.new_price
                        AS
                            price
                        FROM 
                            basket 
                        INNER JOIN 
                            items 
                        ON 
                            basket.item_id = items.item_ID
                        WHERE 
                            user_id = ?
                        AND
                            isOrdering = 0");

$stm->execute(array($userID));

$items = $stm->fetchAll();

$countBasket = count($items);

?>

<div class="container cart-shooping mt-5 mb-5">

    <div class="details shadow-sm" dir="rtl">
        <div class="cart-title d-flex justify-content-between align-items-center">
            مجموع المشتريات (<?= $countBasket ?>)
            <?php
            if ($countBasket != 0) {
            ?>
                <a href="shop.php" class="btn btn-primary btn-sm text-light">
                    <i class="fa-solid fa-plus"></i>
                    تسوق المزيد
                </a>
            <?php
            }
            ?>
        </div>
        <div class="table-cart">
            <?php
            if ($countBasket > 0) {
            ?>

                <table>
                    <thead>
                        <td>المنتج</td>
                        <td>الكمية</td>
                        <td>السعر</td>
                        <td>الاجمالي</td>
                        <td>حذف</td>
                    </thead>
                    <tbody>

                        <?php
                        foreach ($items as $item) {

                            // Get Images With itemId.
                            $itemId = $item["item_id"];
                            $stm2 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ? limit 1");

                            $stm2->execute(array($itemId));

                            $imgs = $stm2->fetchAll();
                        ?>
                            <tr>
                                <td class="pr-name">
                                    <img src="../item_images/<?= $imgs[0]["img"] ?>" class="border p-1 rounded-2">
                                    <?= $item["pr_name"] ?>
                                </td>
                                <td> <?= $item["Quantity"] ?> </td>
                                <td> <?= $item["price"] ?> DA </td>
                                <td class="total_price"><span><?= $item["price"] * $item["Quantity"] ?></span> DA </td>
                                <td>
                                    <a href="?itemID=<?= $item["item_id"] ?>" onclick="return confirm('هل انت متاكد من حذف المنتج من سلة المشتريات')">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>

            <?php
            } else {
            ?>
                <div class="text-center fs-6 p-2">
                    <img src="includes/images/empty-cart.png" width="200" height="200" class="empty-cart-icon">
                    لا يوجد مشتريات في السلة !
                </div>
                <a href="shop.php" class="btn btn-primary text-light btn-sm"> تسوق الان </a>
            <?php
            }
            ?>
        </div>
    </div>

    <?php
    if ($countBasket > 0) {
    ?>
        <div class="result shadow-sm" dir="rtl">
            <div class="cart-title"> اجمالي السلة </div>
            <hr class="m-1">
            <div class="all-price">
                الاجمالي في السلة : <span></span>
            </div>
            <hr class="m-1">
            <div class="submit-btn">
                <a href="checkout.php">اشتري الان</a>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="result shadow-sm" dir="rtl">
            <div class="cart-title"> اجمالي السلة </div>
            <hr class="m-1">
            <div class="all-price">
                الاجمالي في السلة : <span>DA 0</span>
            </div>
            <hr class="m-1">
            <div class="submit-btn">
                <a style="opacity: .4; pointer-events:none">اشتري الان</a>
            </div>
        </div>
    <?php
    }
    ?>

</div>


<?php include "templates/footer.php"; ?>

<?php
if (isset($_SESSION["mssg"])) {
?>
    <script>
        swal({
            title: "<?= $_SESSION["mssg"] ?>",
        });
    </script>
<?php
    unset($_SESSION["mssg"]);
}
?>