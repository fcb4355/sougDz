<?php
$pageTitle = "تتبع طلبي";
include "init.php";
?>

<?php
$total = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = htmlspecialchars($_POST["code_tracking"]);

    $referer = $_SERVER["HTTP_REFERER"];
    $userID  = $_SESSION["id"];
    // Get Order By Tracking Code
    $stm = $conn->prepare("SELECT * FROM orders WHERE tracking_code = ? AND user_id = ?");
    $stm->execute(array($code, $userID));
    $orders = $stm->fetchAll();


    if (!empty($code) && count($orders) !== 0) {
?>
        <div class="container">
            <div class="steps-tracking p-2 border mt-4" dir="rtl">
                <div class="title-track p-2">
                    مراحل تتبع الطلب
                </div>

                <hr>

                <div class="shemain-track">
                    <div class="panel border">
                        <div class="header-panel bg-secondary p-2 text-light">
                            حالة الطلب
                        </div>
                        <div class="body-panel p-2" style="background-color: var(--white-color);">
                            <!-- Date Of Order And update time -->
                            <div class="date">
                                <?php
                                if ($orders[0]["mssg_update"] !== NULL) {
                                ?>
                                    <div class="update alert alert-info text-dark p-2 border">
                                        <?php
                                        echo $orders[0]["mssg_update"];
                                        ?>
                                    </div>
                                <?php
                                }
                                ?>

                                تاريخ اخر تحديث:
                                <span>
                                    <?php
                                    // To Separate The Date And Time From The DateTime.
                                    $s = strtotime($orders[0]["update_time"]);

                                    // Get The Date From DAteTime
                                    $date = date('m/d/Y', $s);

                                    echo $date;

                                    ?>
                                    <br>
                                    <span>
                                        <?php
                                        // Category Create Time
                                        $created_date = new DateTime($orders[0]["update_time"]);

                                        // Calculate The Deffrent Time with created time and current time
                                        $diff = $created_date->diff(new DateTime());

                                        $second = $diff->s;
                                        $minuts = $diff->i;
                                        $hours = $diff->h;
                                        $days = $diff->d;
                                        $month = $diff->m;

                                        if ($month !== 0) {
                                            echo " منذ " . $month . " أشهر";
                                        } elseif ($days !== 0 && $month == 0) {
                                            echo " منذ " . $days . " يوم";
                                        } elseif ($days == 0 && $hours !== 0) {
                                            echo "منذ "   . $hours . " ساعة";
                                        } elseif ($days == 0 && $hours == 0 && $minuts !== 0) {
                                            echo "منذ " . $minuts . " دقيقة";
                                        } else {
                                            echo "منذ " . $second . " ثانية";
                                        }
                                        ?>
                                    </span>
                                </span>
                            </div>

                            <!-- Status of orders -->
                            <div class="status p-2 mt-2 alert alert-success text-success">
                                <?= $orders[0]["status"] ?>
                            </div>


                            <!-- info or order -->
                            <div class="info-order">
                                <?php
                                // Get All Products By orderID.
                                $orderID = $orders[0]["order_id"];
                                $stm3 = $conn->prepare("SELECT basket.* , 
                                                            items.item_Name AS itemName,
                                                            items.price AS price,
                                                            items.item_Name AS name
                                                                FROM 
                                                            basket 
                                                                INNER JOIN 
                                                            items 
                                                                ON 
                                                            basket.item_id = items.item_ID
                                                                WHERE isOrdering = ?");
                                $stm3->execute(array($orderID));
                                $products = $stm3->fetchAll();
                                ?>
                                <table class="border">
                                    <thead>
                                        <tr>
                                            <td>المنتج</td>
                                            <td>الكمية</td>
                                            <td>السعر</td>
                                            <td>الاجمالي</td>
                                            <td>تاريخ الطلب</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($products as $product) {
                                            // Get The image Of The Product.
                                            $stm4 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ?");
                                            $stm4->execute(array($product["item_id"]));
                                            $imgs = $stm4->fetch();
                                        ?>
                                            <tr>
                                                <td>
                                                    <img src="../item_images/<?= $imgs["img"] ?>" width="40" height="40" class="border p-1" style="object-fit: cover;">
                                                    <?= $product["name"] ?>
                                                </td>
                                                <td><?= $product["Quantity"] ?></td>
                                                <td>DA <?= $product["price"] ?></td>
                                                <td>
                                                    <?php
                                                    $total = $total + $product["price"]  * $product["Quantity"];
                                                    echo "DA " . $product["price"]  * $product["Quantity"];
                                                    ?>
                                                </td>
                                                <td><?= $orders[0]["time"] ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total Of price order -->
                            <div class="total">
                                السعر الاجمالي :
                                <span>DA <?= $total ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
<?php

    } else {
        $_SESSION["mssg"] = "كود التتبع غير صالح";

        header("Location:$referer ");
        exit();
    }
}

?>


<div class="container" style="margin-bottom: 70px;">
    <form method="post" action="<?= $_SERVER["PHP_SELF"] ?>" class="box-tracking mt-4 p-1 border" dir="rtl">
        <div class="title-track p-2">
            تتبع الطلب
        </div>

        <hr>

        <div class="group p-2">
            <div style="font-size: 14px;"> رقم التتبع <span style="color: red;">*</span> </div>
            <input type="text" placeholder="YM-xxxxxxx" name="code_tracking" class="border input_track">
        </div>

        <hr>

        <div class="box p-2">
            <input type="submit" class="track" value="تتبع">
        </div>

    </form>
</div>


<?php include "templates/footer.php"; ?>

<script>
    window.onload = () => {
        document.querySelector(".input_track").focus();
    }
</script>

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