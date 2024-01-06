<?php
$pageTitle = "الرئيسية";
include "init.php";

if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 0) {  // ###################### if isset session And user is not Admin
?>
    <!-- if Not Admin -->
    <div class="container page_404">
        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">
        <div class="not-found p-2 mt-4 text-center">
            <span class="shadow"> 403 | USER DOES NOT HAVE THE RIGHT PERMISSIONS. </span>
            <a href="../user/index.php" class="mt-2 btn btn-primary btn-sm shadow"> الصفحة الرئيسية </a>
        </div>

    </div>
<?php
} elseif (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {  // ##################### if isset session and user is admin
?>

    <div class="dashboard">
        <div class="barsBox">
            <i class="fa-solid fa-bars"></i>
        </div>


        <div class="page">

            <!-- Result -->
            <div class="boxs mt-3">
                <div class="box border bg-info bg-gradient shadow-sm">
                    <i class="fa-solid fa-users"></i>
                    <span> <?= calcAll("users") ?> </span>
                    <div class="name">
                        المستخدمين
                    </div>
                </div>

                <div class="box border bg-info bg-gradient shadow-sm">
                    <i class="fa-solid fa-list"></i>
                    <span> <?= calcAll("category") ?> </span>
                    <div class="name">
                        الفئة
                    </div>
                </div>

                <div class="box border bg-info bg-gradient shadow-sm">
                    <i class="fa-solid fa-store"></i>
                    <span> <?= calcAll("items") ?> </span>
                    <div class="name">
                        المنتجات
                    </div>
                </div>

                <div class="box border bg-info bg-gradient shadow-sm">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span><?= calcAll("orders") ?></span>
                    <div class="name">
                        الطلبات
                    </div>
                </div>
            </div>

            <!-- Latest Boxs -->
            <div class="latest-boxs mt-3">

                <div class="latest-orders border shadow-sm">

                    <?php
                    // Get The Last 5 Orders.
                    $stm = $conn->prepare("SELECT * FROM orders LIMIT 5");
                    $stm->execute();
                    $orders = $stm->fetchAll();
                    ?>

                    <div class="dash-title mb-2" dir="rtl">
                        <div class="titl"> طلبات جديدة </div>
                        <span> لديك <?= count($orders) ?> من طلبات جديدة</span>
                    </div>

                    <div class="order-content" dir="rtl">
                        <table class="table table-responsive orders">
                            <thead>
                                <tr>
                                    <th> الاسم </th>
                                    <th> الهاتف </th>
                                    <th> تاريخ الانشاء </th>
                                    <th> الاجمالي </th>
                                    <th> الحالة </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($orders as $order) {
                                ?>
                                    <tr>
                                        <td> <?= $order["name"] ?> </td>
                                        <td> 0<?= $order["phone"] ?> </td>
                                        <td> <?php
                                                // To Separate The Date And Time From The DateTime.
                                                $s = strtotime($order["time"]);

                                                // Get The Date From DAteTime
                                                $date = date('m/d/Y', $s);

                                                echo $date;
                                                ?> </td>
                                        <td> <?= $order["total_price"] ?> DA </td>
                                        <td class="status"> <?= $order["status"] ?> </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="latest-items border shadow-sm">
                    <?php
                    // Get All items 
                    $stm = $conn->prepare("SELECT * FROM items ORDER BY time DESC LIMIT 5");
                    $stm->execute();
                    $items = $stm->fetchAll();
                    ?>
                    <div class="dash-title mb-2" dir="rtl">
                        <div class="titl"> احدث المنتجات المضافة </div>
                        <span> لديك اخر <?= count($items) ?> منتجات </span>
                    </div>

                    <div class="products">
                        <?php
                        foreach ($items as $item) {

                            // Get All images With item_ID
                            $stm2 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ?");
                            $stm2->execute(array($item["item_ID"]));

                            $imgs = $stm2->fetchAll();
                        ?>
                            <div class="product border shadow-sm rounded-2 p-2" dir="rtl">
                                <div class="pr-thumbnail">
                                    <img src="../item_images/<?= $imgs[0]["img"] ?>" width="50" class="border">
                                    <div class="img-title">
                                        <?= $item["item_Name"] ?>
                                    </div>
                                </div>
                                <div class="pr-more">
                                    <div class="date">
                                        تم الانشاء
                                        <span>
                                            <?php
                                            $s = strtotime($item["time"]);
                                            $date = date('m/d/Y', $s);
                                            echo $date;
                                            ?>
                                        </span>
                                    </div>
                                    <div class="status">
                                        <?php
                                        if ($item["status"] == 1) {
                                            echo "<div class='yes'> منشور </div>";
                                        } else {
                                            echo "<div class='no'> غير منشور </div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Side Bar Options -->
        <div class="sideBar">
            <div class="side-bar-content">
                <h1 class="side-title text-center mb-3">
                    لوحة التحكم
                </h1>
                <ul class="links" dir="rtl">
                    <li class="current_link">
                        <a href="dashboard.php">
                            <i class="fa-solid fa-home ms-2"></i>
                            الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="users.php">
                            <i class="fa-solid fa-users ms-2"></i>
                            المستخدمين
                        </a>
                    </li>
                    <li>
                        <a href="category.php">
                            <i class="fa-solid fa-list ms-2"></i>
                            الفئات
                        </a>
                    </li>
                    <li>
                        <a href="items.php">
                            <i class="fa-solid fa-bag-shopping ms-2"></i>
                            المنتجات
                        </a>
                    </li>
                    <li>
                        <a href="orders.php">
                            <i class="fa-solid fa-cart-shopping ms-2"></i>
                            الطلبات
                        </a>
                    </li>
                </ul>
            </div>
            <div class="toMarket">
                <a href="../user/index.php" class="btn btn-primary btn-sm d-block"> زيارة الموقع </a>
            </div>
        </div>
    </div>

<?php
} else {   // ############################## if not isset session 
    header("Location: ../user/login.php");
    exit();
}
?>


<?php include "templates/footer.php" ?>