<?php
$pageTitle = "الدفع";
include "init.php";
?>

<?php

$userID = $_SESSION["id"];

// Get All Product in The Basket With user id.
$stm = $conn->prepare("SELECT basket.* , 
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


$all_price = 0;
foreach ($items as $item) {

    $calc = $item["price"] * $item["Quantity"];

    $all_price += $calc;
}


if ($countBasket > 0) {
?>
    <div class="container container-buy mt-5">
        <form action="thankyou.php" method="post" class="form-buy border shadow-sm p-2" dir="rtl">
            <input type="hidden" name="t-price" value="<?= $all_price ?>">
            <div class="group">
                <input type="text" placeholder="الاسم الكامل" class="border" name="name" required="required">
                <input type="number" placeholder="رقم الهاتف" class="border" name="phone" required="required">
            </div>
            <div class="group">
                <input type="text" placeholder="الولاية" class="border" name="wilaya" required="required">
                <input type="text" placeholder="المدينة" class="border" name="city" required="required">
            </div>
            <input type="text" placeholder="العنوان" class="border" name="addresse" required="required">
            <button type="submit"> <i class="fa-solid fa-cart-shopping"></i> اشتري الان</button>
        </form>
        <div class="products border p-2" dir="rtl">
            <div class="products-thumb">
                المنتجات
            </div>
            <hr class="mt-0 mb-1">
            <ul>
                <?php
                foreach ($items as $item) {

                    // Get Images With itemId.
                    $itemId = $item["item_id"];
                    $stm2 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ? limit 1");

                    $stm2->execute(array($itemId));

                    $imgs = $stm2->fetchAll();

                ?>
                    <li>
                        <div class="right">
                            <img src="../item_images/<?= $imgs[0]["img"] ?>" width="50" height="50" class="border">
                            <div class="pr-name"> <?= $item["pr_name"] ?></div>
                        </div>
                        <div class="left">
                            <span class="quantity"> <?= $item["Quantity"] ?> </span> × <span class="price"> $<?= $item["price"] ?> </span>
                        </div>
                    </li>
                <?php
                }
                ?>
            </ul>
            <hr class="mt-0 mb-1">
            <div class="total">
                مجموع السعر :
                <span> <?= $all_price ?> $ </span>
            </div>
        </div>
    </div>
<?php
} else {

    header("Location: cart.php");
    exit();
}
?>


<?php include "templates/footer.php"; ?>