<?php
$pageTitle = "الفاتورة";
include "init.php";
?>

<?php
if (isset($_SERVER["HTTP_REFERER"]) && isset($_GET["orderID"])) {

    $total = 0;
    $orderId = $_GET["orderID"];

    // Get Order By OrderId.
    $stm = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stm->execute(array($orderId));
    $order = $stm->fetch();

    // Get All Product By OrderId.
    $stm2 = $conn->prepare("SELECT basket.* , 
                                items.new_price as price_item,
                                items.item_Name as name_item
                            FROM 
                                basket
                            INNER JOIN 
                                items 
                            ON 
                                items.item_ID = basket.item_id
                            WHERE 
                                isOrdering = ?");
    $stm2->execute(array($orderId));
    $products = $stm2->fetchAll();

?>

    <div class="container">

        <!-- :: Start Page Facture -->
        <div class="facture-page">

            <!-- :: Start Header -->
            <div class="header">

                <div class="logo-shop">
                    <img src="includes/images/logo.png" alt="Logo" width="400" height="160">
                </div>

                <div class="info-seler">
                    <div class="email box">
                        <i class="fa-solid fa-envelope"></i>
                        <span>fcb4355@gmail.com</span>
                    </div>
                    <div class="phone-number box">
                        <i class="fa-solid fa-phone"></i>
                        <span>0796140504</span>
                    </div>
                    <div class="address box">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>
                            Ain Defla Djendel
                        </span>
                    </div>
                </div>
            </div>
            <hr class="mt-1 ">

            <!-- :: Start the buyer -->
            <div class="buyer p-2" dir="rtl">
                <b>فاتورة الى :</b>
                <div class="info-buyer">
                    <div class="الاسم box">
                        الاسم :
                        <span>
                            <?= $order["name"] ?>
                        </span>
                    </div>
                    <div class="الولاية box">
                        الولاية :
                        <span>
                            <?= $order["wilaya"] ?>
                        </span>
                    </div>
                    <div class="العنوان box">
                        العنوان :
                        <span>
                            <?= $order["address"] ?>
                        </span>
                    </div>
                    <div class="الهاتف box">
                        الهاتف :
                        <span>
                            0<?= $order["phone"] ?>
                        </span>
                    </div>
                    <div class="num-fac box">
                        رقم الفاتورة :
                        <span>
                            <?= $order["tracking_code"] ?>
                        </span>
                    </div>
                    <div class="date box">
                        تاريخ الطلب :
                        <span>
                            <?= $order["time"] ?>
                        </span>
                    </div>
                </div>
            </div>

            <hr class="mt-1 ">

            <!-- Start Order -->
            <div class="order">
                <table dir="rtl">
                    <thead>
                        <tr>
                            <td>المنتج</td>
                            <td>السعر</td>
                            <td>الكمية</td>
                            <td>الاجمالي</td>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($products as $product) {
                        ?>
                            <tr>
                                <td><?= $product["name_item"] ?></td>
                                <td><?= $product["price_item"] ?> دج</td>
                                <td><?= $product["Quantity"] ?></td>
                                <td>
                                    <?php
                                    echo $product["Quantity"] * $product["price_item"];
                                    $total = $total + $product["Quantity"] * $product["price_item"];
                                    ?>
                                    دج
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- :: Total Price Of Order -->
            <div class="total border">
                الاجمالي: <span> <?= $total ?> دج </span>
            </div>

            <hr class="mt-1 mb-1">

            <!-- Start Footer -->
            <div class="footer">
                <div class="thanks">
                    🥰 شكرا لثقتكم 🥰
                </div>
                <div class="logo">
                    <img src="includes/images/logo.png" alt="logo" width="180" height="60">
                </div>
            </div>

        </div>
        <!-- :: End Facture Page -->
    </div>
<?php
} else {
?>
    <!-- if Not Admin -->
    <div class="container page_404">
        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

        <div class="not-found p-2 mt-4 text-center">
            <span class="shadow"> 404 | Page Not Found Or You Don't Access In This Page Direct. </span>
            <a href="orders.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
        </div>

    </div>
<?php
}
?>


<?php include "templates/footer.php"; ?>


<script>
    window.onload = () => {
        setTimeout(() => {
            print();
        }, 2000);
    }
</script>